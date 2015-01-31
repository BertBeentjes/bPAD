<?php

/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2014
 * http://www.bertbeentjes.nl, http://www.bpadcms.nl
 * 
 * This file is part of the bPAD content management system.
 * 
 * bPAD is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * bPAD is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with bPAD.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Basic class that contains the data for an object 
 * Objects are the atomic building blocks of bPAD
 * 
 * Every site is built using a hierarchy of objects.
 * 
 * Several objects together can form a template, for
 * use throughout the site.
 * 
 * The complete data layer will be available in version 0.4.0
 *
 * @since 0.4.0 
 */
class Object extends SettedEntity {

    private $objectversions = array(); // child objects
    private $objectusergrouproles = array(); // define permissions for users on this object
    private $objectusergrouprolesloaded = false; // are they loaded or not
    private $active; // user controlled, bool value
    private $istemplate; // system controlled, true if part of a template
    private $istemplateroot; // system controlled, true if the root object of the template
    private $isobjecttemplateroot; // system controlled, root object of the objects based upon a template
    private $template; // system controlled, the template this object is based upon
    private $new; // indicator that the object is new, used to show the user the edit function by default for new, unpublished objects
    private $sessionidentifier; // the identifier for the session that created this object, used for anonymous editing of the object (they can edit a new object from within the same session)
    private $seourl = array(); // cache the seo url for later use
    private $address = array(); // cache the address for later use

    /**
     * Constructor, sets the basic object attributes
     * By setting these attribs, the existence of the object is 
     * verified
     * 
     * @param id contains the object id to get from the store
     */

    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableObjects();
        $this->loadAttributes();
    }

    /**
     * Load the attributes of the object
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getObject($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }

    /**
     * Initialize the attributes of the object
     * 
     * @return boolean true if success,
     */
    protected function initAttributes($attr) {
        $this->active = (bool) $attr->active;
        $this->name = $attr->name;
        $this->istemplate = (bool) $attr->istemplate;
        $this->istemplateroot = (bool) $attr->istemplateroot;
        $this->isobjecttemplateroot = (bool) $attr->isobjecttemplateroot;
        $this->template = Templates::getTemplate($attr->templateid);
        $this->setid = $attr->setid;
        $this->sessionidentifier = $attr->sessionidentifier;
        $this->new = (bool) $attr->new;
        parent::initAttributes($attr);
        return true;
    }

    /**
     * load the production or development version of the object, the basic
     * attributes are loaded directly, further information is loaded when 
     * needed.
     * 
     * When the object has a parent (which is a typical situation) the 
     * parent object is loaded
     * 
     * @param mode $mode
     * @return objectversion returns the loaded objectversion
     */
    private function initVersion($mode) {
        if ($mode->getId() == Mode::EDITMODE || $mode->getId() == Mode::VIEWMODE) {
            if ($result = Store::getObjectParentIds($this->getId(), $mode->getId())) {
                if ($row = $result->fetchObject()) {
                    $objectparent = Objects::getObject($row->parentobjectid);
                    $this->objectversions[$mode->getId()] = new ObjectVersion($this, $objectparent, $row->parentpositionnumber, $this->findObjectTemplateRoot($objectparent, $mode), $mode);
                    return $this->objectversions[$mode->getId()];
                }
            }
            // this object has no parent, it is either the site root or a template root object
            $this->objectversions[$mode->getId()] = new ObjectVersion($this, $this, NULL, $this, $mode);
            return $this->objectversions[$mode->getId()];
        }
    }

    /**
     * outdate the version cache after changing the mode of a version, called
     * from the objectversion, so not need to do this explicitly anywhere else
     */
    public function outdateVersionCache() {
        unset($this->objectversions);
        $this->objectversions = array();
    }

    /**
     * Find the object template root. Templates can be built of several objects,
     * when instantiating a template, the objects must know what the root of
     * their tree is. This info is used to publish the complete structure
     * and to propagate changes upwards from child objects to the root object.
     * 
     * @param object the parent object for this object
     * @param mode the mode, an object can have different parents in different modes,
     * allthough the root should be the same in all modes (since a template
     * based object tree is regarded as a whole)
     * @return object the template root object, either the parents object root,
     * or the object itself
     */
    private function findObjectTemplateRoot($objectparent, $mode) {
        if (!$this->getTemplate()->isDefault() && !$this->getIsObjectTemplateRoot() && !$this->getIsTemplateRoot()) {
            return $objectparent->getVersion($mode)->getObjectTemplateRootObject();
        } else {
            return $this;
        }
    }

    /**
     * Returns the version of the object for the requested mode, if the 
     * version has been used before, the loaded version is returned. Otherwise,
     * the version is constructed. Only in use for view and edit mode.
     * 
     * @param mode $mode the mode
     * @return objectversion the requested objectversion
     */
    public function getVersion($mode) {
        if ($mode->getId() == Mode::EDITMODE || $mode->getId() == Mode::VIEWMODE) {
            if (isset($this->objectversions[$mode->getId()])) {
                return $this->objectversions[$mode->getId()];
            }
            return $this->initVersion($mode);
        }
    }

    /**
     * Create a new version for edit or view mode
     * 
     * @param mode $mode
     * @return objectversion
     */
    public function newVersion($mode) {
        if ($mode->getId() == Mode::EDITMODE || $mode->getId() == Mode::VIEWMODE) {
            if (Store::getObjectVersionByMode($this->getId(), $mode->getId())) {
                throw new Exception(Helper::getLang(Errors::ERROR_ALREADY_EXISTS) . ': ' . $this->id . ' @ ' . __METHOD__);
            } else {
                Store::insertObjectVersion($this->getId(), $mode->getId());
                $this->setChanged();
                return $this->getVersion($mode);
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ': ' . $this->id . ' @ ' . __METHOD__);
        }
    }

    /**
     * Create new versions for both edit and view mode
     */
    public function newVersions() {
        if (Store::getObjectVersionByMode($this->getId(), Mode::VIEWMODE)) {
            throw new Exception(Helper::getLang(Errors::ERROR_ALREADY_EXISTS) . ': ' . $this->id . ' @ ' . __METHOD__);
        }
        if (Store::getObjectVersionByMode($this->getId(), Mode::EDITMODE)) {
            throw new Exception(Helper::getLang(Errors::ERROR_ALREADY_EXISTS) . ': ' . $this->id . ' @ ' . __METHOD__);
        }
        Store::insertObjectVersion($this->getId(), Mode::VIEWMODE);
        Store::insertObjectVersion($this->getId(), Mode::EDITMODE);
        $this->setChanged();
    }

    /**
     * Add a new object user group role to an object
     * 
     * @param object $object
     * @param usergroup $usergroup
     * @param role $role
     * @param boolean $inherit
     */
    public function newObjectUserGroupRole($object, $usergroup, $role, $inherit) {
        // insert the new one
        $newid = Store::insertObjectUserGroupRole($object->getId(), $usergroup->getId(), $role->getId(), $inherit);
        // refresh the ougrs
        $this->objectusergrouprolesloaded = false;
        $this->objectusergrouproles = array();
        $this->getObjectUserGroupRoles();
        // return whatever
        return $newid;
    }

    /**
     * Remove an object user group role from an object
     * 
     * @param objectusergrouprole $ougr
     */
    public function removeObjectUserGroupRole($ougr) {
        Store::deleteObjectUserGroupRole($ougr->getId());
        $this->objectusergrouprolesloaded = false;
        $this->objectusergrouproles = array();
        $this->getObjectUserGroupRoles();
    }

    /**
     * set changedate, changeuser for this object
     * use this function when changes impact on production mode
     * so only with object attributes that are mode independent
     * this function also outdates the objectcache
     * 
     * if this object is part of an object tree based upon a template, 
     * propagate the change upward to the object template root
     * 
     * @return boolean true if success
     */
    public function setChanged() {
        $thischanged = true;
        $parentchanged = true;
        // speed optimizaton: don't outdate the cache more than once in a roundtrip, and don't propagate changes to the parent more than once
        // $this->changed is set and kept in the storedentity superclass
        // so do this before the superclass is called (parent::setChanged())
        if (!$this->changed) {
            // outdate the caches for this object, for the parent of this object (because of potential changes in visibility
            // may affect showing or hiding certain positions), for instances, referrals and linked content items
            CacheObjects::outdateObject($this);
            CacheObjects::outdateObject($this->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectParent());
            CacheObjects::outdateObject($this->getVersion(Modes::getMode(Mode::EDITMODE))->getObjectParent());
            CacheObjects::outdateReferrals($this);
            CacheObjects::outdateLinkedContentItems();
            CachePositionInstances::outdateInstances();
        }
        // set the change date and user
        $thischanged = parent::setChanged(); // call the overridden function to set the change user/date
        // if this is not the objecttemplateroot, propagate the change upwards
        if ($this->hasTemplateParent()) {
            // propagate both in prod and dev mode (if they exist), this is a global change
            if ($this->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectParent()->getId() != $this->getId()) {
                $parentchanged = $parentchanged && $this->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectParent()->setChanged();
            }
            // propagate both in prod and dev mode (if they exist), this is a global change
            if ($this->getVersion(Modes::getMode(Mode::EDITMODE))->getObjectParent()->getId() != $this->getId()) {
                $parentchanged = $parentchanged && $this->getVersion(Modes::getMode(Mode::EDITMODE))->getObjectParent()->setChanged();
            }
        }
        // return true if success
        return ($parentchanged && $thischanged);
    }

    /**
     * check whether this object is template based and isn't the root or is searchable, or is part of a template and isn't the root
     * 
     * @return boolean true if the object has a parent or false
     */
    public function hasTemplateParent() {
        return !$this->getTemplate()->isDefault() && !$this->getIsTemplateRoot() && (!$this->getIsObjectTemplateRoot() || $this->getTemplate()->getSearchable());
    }

    /**
     * getter for the active bool, if true the object is active and visible
     * in production mode, otherwise not. The object is always visible in 
     * development mode
     * 
     * @return boolean active or not
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * set the object active or not
     * 
     * @param bool active or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setActive($bool) {
        if (Store::setObjectActive($this->id, $bool) && $this->setChanged()) {
            $this->active = $bool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * set the object active bool of the children
     * 
     * @param bool active or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setActiveRecursive($bool) {
        if (Store::setObjectActive($this->id, $bool) && $this->setChanged()) {
            $this->active = $bool;
            // set active in viewmode
            $children = $this->getVersion(Modes::getMode(mode::VIEWMODE))->getChildren();
            foreach ($children as $child) {
                $child->setActiveRecursive($bool);
            }
            // and in edit mode
            $children = $this->getVersion(Modes::getMode(mode::EDITMODE))->getChildren();
            foreach ($children as $child) {
                $child->setActiveRecursive($bool);
            }
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * set the object active bool of the children
     * 
     * @param bool active or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setActiveRecursiveTemplateBasedChildren($bool) {
        if (Store::setObjectActive($this->id, $bool) && $this->setChanged()) {
            $this->active = $bool;
            // set active in viewmode
            $children = $this->getVersion(Modes::getMode(mode::VIEWMODE))->getChildren();
            foreach ($children as $child) {
                if (!$child->isobjecttemplateroot && !$child->getTemplate()->isDefault()) {
                    $child->setActiveRecursive($bool);
                }
            }
            // and in edit mode
            $children = $this->getVersion(Modes::getMode(mode::EDITMODE))->getChildren();
            foreach ($children as $child) {
                if (!$child->isobjecttemplateroot && !$child->getTemplate()->isDefault()) {
                    $child->setActiveRecursive($bool);
                }
            }
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the istemplate bool, if true the object is part of a 
     * template
     * 
     * @return boolean template or not
     */
    public function getIsTemplate() {
        return $this->istemplate;
    }

    /**
     * is this object template based or not?
     * 
     * @return boolean template based or not
     */
    public function getIsTemplateBased() {
        return !$this->getIsTemplate() && !$this->getTemplate()->isDefault();
    }

    /**
     * set the object as part of a template, only set once when creating
     * the object
     * This attribute can also be set by copyAttributes!
     *
     * @param bool template or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setIsTemplate($bool) {
        if (Store::setObjectIsTemplate($this->id, $bool) && $this->setChanged()) {
            $this->istemplate = $bool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the istemplateroot bool, if true the object is the root
     * of the template tree
     * 
     * @return boolean templateroot or not
     */
    public function getIsTemplateRoot() {
        return $this->istemplateroot;
    }

    /**
     * set the object as root of the template tree, only set once when creating
     * the object
     * This attribute can also be set by copyAttributes!
     * 
     * @param bool templateroot or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setIsTemplateRoot($bool) {
        if (Store::setObjectIsTemplateRoot($this->id, $bool) && $this->setChanged()) {
            $this->istemplateroot = $bool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the new bool, if true the object has just been created
     * 
     * @return boolean new or not
     */
    public function getNew() {
        return $this->new;
    }

    /**
     * setter for the new bool
     * 
     * @param bool new or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setNew($bool) {
        if (Store::setObjectNew($this->id, $bool) && $this->setChanged()) {
            $this->new = $bool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * set the object new bool of the children
     * 
     * @param bool new or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setNewRecursive($bool) {
        if (Store::setObjectNew($this->id, $bool) && $this->setChanged()) {
            $this->new = $bool;
            // set active in viewmode
            $children = $this->getVersion(Modes::getMode(mode::VIEWMODE))->getChildren();
            foreach ($children as $child) {
                $child->setNewRecursive($bool);
            }
            // and in edit mode
            $children = $this->getVersion(Modes::getMode(mode::EDITMODE))->getChildren();
            foreach ($children as $child) {
                $child->setNewRecursive($bool);
            }
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter the session identifier for the session that created the object
     * 
     * @return string sessionidentifier
     */
    public function getSessionIdentifier() {
        return $this->sessionidentifier;
    }

    /**
     * setter for the session identifier
     * 
     * @param string new session identifier
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setSessionIdentifier($newsessionidentifier) {
        if (Store::setObjectSessionIdentifier($this->id, $newsessionidentifier) && $this->setChanged()) {
            $this->sessionidentifier = $newsessionidentifier;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the isobjecttemplateroot bool, if true the object is the root
     * of the object tree based upon a template
     * 
     * @return boolean objecttemplateroot or not
     */
    public function getIsObjectTemplateRoot() {
        return $this->isobjecttemplateroot;
    }

    /**
     * set the object as root of the template based object tree, only set once 
     * when creating the object
     * This attribute can also be set by copyAttributes!
     * 
     * @param bool objecttemplateroot or not
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setIsObjectTemplateRoot($bool) {
        if (Store::setObjectIsObjectTemplateRoot($this->getId(), $bool) && $this->setChanged()) {
            $this->isobjecttemplateroot = $bool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Set multiple attributes at once, performance optimization for copying objects
     * 
     * @param boolean $isobjecttemplateroot
     * @param boolean $istemplate
     * @param boolean $istemplateroot
     * @param string $name
     * @param set $set
     * @param template $template
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function copyAttributes($isobjecttemplateroot, $istemplate, $istemplateroot, $name, $set, $template) {
        if (Store::setObjectAttributes($this->getId(), $isobjecttemplateroot, $istemplate, $istemplateroot, $name, $set->getId(), $template->getId()) && $this->setChanged()) {
            $this->isobjecttemplateroot = $isobjecttemplateroot;
            $this->istemplate = $istemplate;
            $this->istemplateroot = $istemplateroot;
            $this->name = $name;
            $this->set = $set;
            $this->template = $template;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the template the object is based upon or part of
     * 
     * @return template template
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * set the template this object is based upon or part of, 
     * usually set once when creating the object
     * This attribute can also be set by copyAttributes!
     * 
     * @param template
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setTemplate($newtemplate) {
        if (Store::setObjectTemplateId($this->getId(), $newtemplate->getId()) && $this->setChanged()) {
            $this->template = $newtemplate;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the objectusergrouproles by objectid, 
     * checks whether the objectusergrouprole is loaded,
     * loads the objectusergrouprole if necessary and fills it on demand with
     * further information
     * 
     * @param objectusergrouproleid the id of the objectusergrouprole to get
     * @return ObjectUserGroupRole[]
     */
    public function getObjectUserGroupRoles() {
        // return all objectusergrouproles
        if ($this->objectusergrouprolesloaded) {
            return $this->objectusergrouproles;
        } else {
            if ($objectusergrouproles = Store::getObjectObjectUserGroupRole($this->getId())) {
                while ($row = $objectusergrouproles->fetchObject()) {
                    $this->objectusergrouproles[$row->id] = new ObjectUserGroupRole($row->id);
                }
            }
            $this->objectusergrouprolesloaded = true;
            return $this->objectusergrouproles;
        }
    }

    /**
     * Get the argument name (if any) used in this object
     * 
     * @param mode $mode
     * @return string the argument name
     */
    public function getArgumentName($mode) {
        // go to the root object
        $rootobject = $this->getVersion($mode)->getObjectTemplateRootObject();
        $argumentname = $this->findArgumentName($rootobject, $mode);
        if ($argumentname === false) {
            // multiple names found, return an empty string
            return '';
        }
        return $argumentname;
    }

    /**
     * Recursive find the argument name (if any) used in this object or its template based children
     * 
     * @param object the object to find the argument name for
     * @param mode the mode to use
     * @return string the argument name
     */
    private function findArgumentName($object, $mode) {
        // check the object
        $argument = $this->getVersion($mode)->getArgument();
        $argumentname = '';
        if (!$argument->isDefault()) {
            $argumentname = $argument->getName();
        }
        // loop through the template based children
        $children = $this->getVersion($mode)->getTemplateBasedChildren();
        foreach ($children as $child) {
            // get the child argument
            $childargumentname = $this->findArgumentName($child, $mode);
            // if an argument has been found
            if ($childargumentname > '') {
                // and if this is the first argument found
                if ($argumentname === '') {
                    // return the argument name found
                    $argumentname = $childargumentname;
                } else {
                    return false;
                }
            }
        }
        // return the name found
        return $argumentname;
    }

    /**
     * Get the object address, the address specifies which objects must be selected
     * in a referral to get to the currect object. It is effectively equal to the
     * seo url, but instead of the object name the object id is used.
     * 
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public function getAddress($mode) {
        $address = $this->getAddressRecursive($mode);
        // the address for the root object (and everything else above the first menu layer) is empty
        if ($address == '') {
            $address = '1.' . SysCon::SITE_ROOT_OBJECT . '.' . '1' . '.' . Helper::getURLSafeString(Objects::getObject(SysCon::SITE_ROOT_OBJECT)->getName());
        }
        return $address;
    }
    /**
     * Get the deep object address, used for deep links
     * 
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public function getDeepAddress($mode) {
        return $this->getVersion($mode)->getObjectParent()->getVersion($mode)->getPositionParent()->getId() . '.' . $this->getVersion($mode)->getObjectParent()->getId() . '.' . $this->getVersion($mode)->getPositionParent()->getId() . '.' . Helper::getURLSafeString($this->getVersion($mode)->getObjectTemplateRootObject()->getName());
    }

    /**
     * Get the object address, the address specifies which objects must be selected
     * in a referral to get to the currect object. It is effectively equal to the
     * seo url, but instead of the object name the object id is used.
     * 
     * @param mode $mode
     * @param context $context
     * @return string
     */
    private function getAddressRecursive($mode) {
        if (!isset($this->address[$mode->getId()])) {
            $address = '';
            // the address is a full path to this object, starting at the site root, so recursively get the parent address
            if ($this->getVersion($mode)->hasObjectParent()) {
                $address = $this->getVersion($mode)->getObjectParent()->getAddressRecursive($mode);
            }
            // now add the object information if this object is part of a pn template
            if (!$this->getVersion($mode)->getObjectParent()->getVersion($mode)->getArgument()->isDefault() && !$this->getVersion($mode)->getObjectParent()->getVersion($mode)->getArgument()->isCreate()) {
                if ($address > '') {
                    $address = $address . '/';
                }
                // add the parent object parent position id and the parent object id to the address
                // the parent object parent position id is used by the frontend to decide where to insert
                // new content when the address is used in combination with
                // a content.get command
                $address = $address . $this->getVersion($mode)->getObjectParent()->getVersion($mode)->getPositionParent()->getId() . '.' . $this->getVersion($mode)->getObjectParent()->getId() . '.' . $this->getVersion($mode)->getPositionParent()->getId() . '.' . Helper::getURLSafeString($this->getVersion($mode)->getObjectTemplateRootObject()->getName());
            }
            $this->address[$mode->getId()] = $address;
        }
        return $this->address[$mode->getId()];
    }

    /**
     * Create the base seo url for this object (name/name/name)
     * 
     * @param mode $mode
     * @return string
     */
    public function getBaseSEOURL($mode) {
        if (!isset($this->seourl[$mode->getId()])) {
            $seourl = '';
            if ($this->getVersion($mode)->hasObjectParent()) {
                $seourl = $this->getVersion($mode)->getObjectParent()->getBaseSEOURL($mode);
            }
            if (!$this->getVersion($mode)->getObjectParent()->getVersion($mode)->getArgument()->isDefault() && !$this->getVersion($mode)->getObjectParent()->getVersion($mode)->getArgument()->isCreate()) {
                if ($seourl > '') {
                    $seourl = $seourl . '/';
                }
                $seourl = $seourl . Helper::getURLSafeString($this->getVersion($mode)->getObjectTemplateRootObject()->getName());
            }
            $this->seourl[$mode->getId()] = $seourl;
        }
        return $this->seourl[$mode->getId()];
    }

    /**
     * Create the SEO URL for this object (/siterootfolder/name/name/name.html)
     * this url is used for non-AJAX navigation
     * 
     * @param mode 
     * @return string
     */
    public function getSEOURL($mode) {
        return Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue() . $this->getBaseSEOURL($mode) . '.html';
    }

    /**
     * Create the deep link for this object (/siterootfolder/name/name/name/-###/name.html)
     * this url is used for deep linking to an article, e.g. from social media sites
     * 
     * @param mode 
     * @return string
     */
    public function getDeepLink($mode) {
        // get the seo url
        $deeplink = $this->getSEOURL($mode);
        // remove the .html part
        $deeplink = substr($deeplink, 0, strlen($deeplink) - 5);
        // get the root object
        $root = $this->getVersion($mode)->getObjectTemplateRootObject();
        while ($root->hasTemplateParent() && !$root->isSiteRoot()) {
            $root = $root->getVersion($mode)->getObjectParent()->getVersion($mode)->getObjectTemplateRootObject();
        }
        // add the id and the name
        $deeplink .= '/-' . $root->getId() . '/' . $root->getURLName() . '.html';
        return $deeplink;
    }

    /**
     * Create the full deep link for this object (/siterootfolder/name/name/name/-###/name.html)
     * this url is used for deep linking to an article, e.g. from social media sites
     * 
     * @param mode 
     * @return string
     */
    public function getDeepLinkFull($mode) {
        // get the deep link
        $deeplink = $this->getDeepLink($mode);
        // add the site root
        $deeplink = Settings::getSetting(Setting::SITE_ROOT)->getValue() . $deeplink;
        return $deeplink;
    }

    /**
     * getter for the url safe name
     * 
     * @return string the url safe name
     */
    public function getURLName() {
        return Helper::getURLSafeString($this->getName());
    }

    /**
     * An object is addressable if:
     *  - it is an object template root ór not template based
     *  - ánd the parent object has an argument
     * 
     * @param mode
     * @return boolean
     */
    public function isAddressable($mode) {
        return ($this->getIsObjectTemplateRoot() || !$this->getIsTemplateBased()) && ($this->getVersion($mode)->getObjectParent()->getVersion($mode)->getArgument()->getId() != Argument::DEFAULT_ARGUMENT);
    }

    /**
     * An object is the site root if its id matches the site root id
     * 
     * @return boolean
     */
    public function isSiteRoot() {
        return ($this->getId() == SysCon::SITE_ROOT_OBJECT);
    }

    /**
     * check whether the user has the right authorizations and the object
     * is visible in this mode or context
     * 
     * @param mode $mode
     * @param context $context
     * @return boolean true if visible
     */
    public function isVisible($mode, $context) {
        // hide inactive objects in view mode, if not in the recycle bin context
        if (!$this->getActive() && $mode->getId() == Mode::VIEWMODE && !$context->isRecycleBin()) {
            return false;
        }
        // hide inactive objects that aren't new in edit mode
        if (!$this->getActive() && !$this->getNew() && $mode->getId() == Mode::EDITMODE) {
            return false;
        }
        // check authorization
        if (Authorization::getObjectPermission($this, Authorization::OBJECT_VIEW)) {
            return true;
        }
        return false;
    }

    public function isNewAndEditable() {
        return $this->getNew() && (Authorization::getObjectPermission($this, Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this, Authorization::OBJECT_MANAGE));
    }

    /**
     * Check whether the edit version contains changes from the view version,
     * to decide whether to create a new view version for this object when
     * publishing, or not.
     * 
     * @return boolean true if the object has changed
     */
    public function hasChanged() {
        if ($this->getVersion(Modes::getMode(Mode::VIEWMODE))->getChangeDate() <= $this->getVersion(Modes::getMode(Mode::EDITMODE))->getChangeDate() || $this->getNew()) {
            return true;
        }
        return false;
    }

    /**
     * Delete the object from the parent in edit/view mode. This should only
     * be used for objects in the recycle bin or objects that are new.
     * 
     * This function only deletes the parent position, the Objects::removeOrphanedObjects()
     * function will delete this object (if no references from archived versions are left)
     * 
     * @param boolean $deactivatechild default true, deactivate the child object after removing the position
     */
    public function removeFromParent($deactivatechild = true) {
        // delete this object from his parent (delete the positionobject, delete the position, renumber positions)
        $this->removeParentPositionMode(Modes::getMode(Mode::EDITMODE), $deactivatechild);
        $this->removeParentPositionMode(Modes::getMode(Mode::VIEWMODE), $deactivatechild);
    }

    /**
     * Delete the parent position
     * 
     * @param mode $mode
     * @param boolean $deactivatechild default true, deactivate the child object after removing the position
     */
    private function removeParentPositionMode($mode, $deactivatechild = true) {
        $parent = $this->getVersion($mode)->getObjectParent();
        $positionnumber = $this->getVersion($mode)->getPositionParent()->getNumber();
        $parent->getVersion($mode)->removePosition($positionnumber, $deactivatechild);
    }

    /**
     * Get the name for this object, for moves to this object, recurse into 
     * parent template roots
     * 
     * @param mode $mode
     * @return string 
     */
    public function getNameForMove($mode) {
        $name = $this->getName() . ' (' . $this->getVersion($mode)->getPositionParent()->getNumber() . ')';
        $object = $this->getVersion($mode)->getObjectTemplateRootObject();
        // don't recurse when the parent object is a template or the parent object is the site root
        if ($object->getIsTemplate() || $object->isSiteRoot()) {
            return $name;
        }
        // get the parent for this object
        $parentobject = $object->getVersion($mode)->getObjectParent()->getVersion($mode)->getObjectTemplateRootObject();
        // if the parent isn't a real parent, return the name for this parent
        if ($object->getId() == $parentobject->getId() || $parentobject->isSiteRoot()) {
            return $name;
        }
        // recurse
        return $parentobject->getNameForMove($mode) . ' - ' . $name;
    }
    
    /**
     * Set or remove an object user group role for this object and for its children
     * 
     * @param usergroup $usergroup
     * @param role $role
     * @param boolean $inherit
     * @return boolean
     */
    public function setObjectUserGroupRole($usergroup, $role, $inherit) {
        $objectusergrouproles = $this->getObjectUserGroupRoles();
        $found = false;
        // search for the usergrouprole
        foreach ($objectusergrouproles as $objectusergrouprole) {
            if ($objectusergrouprole->getUserGroup()->getId() == $usergroup->getId() && $objectusergrouprole->getRole()->getId() == $role->getId()) {
                $id = $objectusergrouprole->getId();
                if (Store::deleteObjectUserGroupRole($id)) {
                    $found = true;
                    break;
                }
            }
        }
        // if it isn't there, add it
        if (!$found) {
            Store::insertObjectUserGroupRole($this->getId(), $usergroup->getId(), $role->getId());
        }
        // refresh the object usergroup roles
        $this->objectusergrouprolesloaded = false;
        $this->getObjectUserGroupRoles();
        // if this is template based, get the children
        if (!$this->getTemplate()->isDefault() || $this->getIsTemplate()) {
            // get the child objects in view mode
            $children = $this->getVersion(Modes::getMode(Mode::VIEWMODE))->getChildren();
            foreach ($children as $child) {
                // if inherit or this child is part of the same template, or a searchable part
                if (!$child->getIsObjectTemplateRoot() || $inherit || $child->getTemplate()->getSearchable()) {
                    $child->setObjectUserGroupRole($usergroup, $role, $inherit);
                }
            }
        }
        return true;
    }

}