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
 * this class contains all mode specific information for an object,
 * it extends storedentity and has object as its container
 *
 * all things in bPAD are edited in dev mode, and published in prod mode
 * unpublished versions are given the archive mode
 * 
 * used for PROD and DEV mode versions only!!! 
 * archived versions use the archive class
 * 
 * @since 0.4.0
 */
Class ObjectVersion extends StoredEntity {

    private $mode; // system defined, defines the mode the version is in
    private $layout; // user defined, the layout used 
    private $style; // user defined, the layoutstyle used 
    private $argument; // user defined, the argument for this object, having an argument makes an object addressable
    private $argumentdefault; // the default value for the argument, defines which position to show by default, 0 is the first, -1 is the last, any other number is the corresponding position
    private $inheritlayout; // system defined (based upon the template), if an object is based upon a template, it can inherit its layout from the template
    private $inheritstyle;  // system defined (based upon the template), if an object is based upon a template, it can inherit its style from the template
    private $template; // the default template used to add items in a #pn# type layout
    // hierarchy info: parents, children, etc
    private $objecttemplaterootobject; // the objecttemplateroot for the object this is a version of
    private $objectparent; // the hierarchical parent object for the object this is a version of (can be found in the positionparent, but is here to make code easier to read)
    private $positionparent; // the hierarchical parent position for the object this is a version of
    // objectversions contain positions
    private $positionsloaded = false; // flags whether the positions are loaded
    private $positions = array(); // array with the positions, only to be used in getPositions, other methods must use getPositions to get the array

    /**
     * Constructor, gets the basic attributes for the objectversion
     * 
     * @param object the object that contains this version
     * @param object the direct parent object for the object in this mode
     * @param position the position of the parent object this object is in, in this mode
     * @param object the template root object for this mode
     * @param mode the mode
     */

    public function __construct($object, $objectparent, $positionparent, $objecttemplaterootobject, $mode) {
        $this->tablename = Store::getTableObjectVersions();
        $this->objectparent = $objectparent;
        $this->positionparent = $positionparent;
        $this->objecttemplaterootobject = $objecttemplaterootobject;
        $this->mode = $mode;
        $this->container = $object;
        $this->loadAttributes();
    }

    /**
     * Load the basic attributes for this object version
     * 
     * @return boolean  if success
     * @throws exception when the store isn't available
     */
    private function loadAttributes() {
        if ($result = Store::getObjectVersionByMode($this->container->getId(), $this->mode->getId())) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->container->getId() . '-' . $this->mode->getId() . ' @ ' . __METHOD__);
    }

    /**
     * Get the basic attributes for this object version
     * 
     * @return boolean  if success
     */
    protected function initAttributes($attr) {
        $this->id = $attr->id;
        $this->layout = Layouts::getLayout($attr->layoutid);
        $this->style = Styles::getStyle($attr->styleid);
        $this->argument = Arguments::getArgument($attr->argumentid);
        $this->argumentdefault = $attr->argumentdefault;
        $this->inheritlayout = $attr->inheritlayout;
        $this->inheritstyle = $attr->inheritstyle;
        $this->template = Templates::getTemplate($attr->templateid);
        parent::initAttributes($attr);
        return true;
    }

    /**
     * override for the default changed method, object versions must also
     * change the parent object if part of a template
     * 
     * @param boolean $force optional, force setting the change date/user
     * @return boolean
     */
    public function setChanged($force = false) {
        $thischanged = parent::setChanged($force);
        $parentchanged = true;
        if (!$this->changed || $force) {
            if ($this->container->hasTemplateParent()) {
                // set the change value for the version with the correct mode of the object parent
                // check for looping...
                if ($this->getObjectParent()->getId() != $this->getContainer()->getId()) {
                    $parentchanged = $this->objectparent->getVersion($this->getMode())->setChanged();
                }
            }
            // update the templateid and rootobjectid for contentitems of this object version
            $this->recalculatePositionContentitems();
        }
        // update the cache for the addressable parents of this object version
        // CacheObjectAddressableParentObjects::updateCache($this);
        return ($thischanged && $parentchanged);
    }

    /**
     * Recalculate the template id and object root id for position content items.
     * These values are used in instances to make retrieving them (much) faster.
     * 
     * The values are recalculated every time something changes in an object version.
     */
    private function recalculatePositionContentitems() {
        // if this isn't a template
        $object = $this->getContainer();
        $template = $this->getContainer()->getTemplate();
        if (!$object->getIsTemplate()) {
            $positions = $this->getPositions();
            foreach ($positions as $position) {
                // only do this for content items
                if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_CONTENTITEM) {
                    // default values
                    $searchtemplate = $template;
                    $rootobject = $this->getObjectTemplateRootObject();
                    // if the object is based upon a template
                    if (!$template->isDefault()) {
                        // if the template for this object doesn't allow instances
                        if (!$template->getInstanceAllowed()) {
                            // and the template is searchable
                            if ($template->getSearchable()) {
                                // get the object template root for the parent of this object
                                $parent = $this->getObjectTemplateRootObject()->getVersion($this->getMode())->getObjectParent()->getVersion($this->getMode())->getObjectTemplateRootObject();
                                // if the template of the parent is also searchable, go higher in the tree
                                while ($parent->getVersion($this->getMode())->hasObjectParent() && $parent->getTemplate()->getSearchable() && !$parent->isSiteRoot()) {
                                    // get a parent higher in the tree
                                    $parent = $parent->getVersion($this->getMode())->getObjectParent()->getVersion($this->getMode())->getObjectTemplateRootObject();
                                }
                                if ($parent->getTemplate()->getInstanceAllowed()) {
                                    // refer to the parent 
                                    $searchtemplate = $parent->getTemplate();
                                    $rootobject = $parent;
                                }
                            }
                        }
                    }
                    // store the values
                    $position->getPositionContent()->setTemplate($searchtemplate);
                    $position->getPositionContent()->setRootObject($rootobject);
                }
            }
        }
    }

    /**
     * get the positions for this mode
     * 
     * @return Position[]
     */
    public function getPositions() {
        if ($this->positionsloaded) {
            return $this->positions;
        } else {
            if ($result = Store::getPositions($this->getId())) {
                while ($attr = $result->fetchObject()) {
                    $this->positions[$attr->number] = new Position($this, $attr);
                }
            }
            ksort($this->positions, SORT_NUMERIC);
            $this->positionsloaded = true;
            return $this->positions;
        }
    }

    /**
     * get the children of this object that are part of the same template
     * 
     * @return Object[]
     */
    public function getTemplateBasedChildren() {
        $children = array();
        $positions = $this->getPositions();
        foreach ($positions as $position) {
            // for each child that is an object and part of the same template, check the argument
            if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                $object = $position->getPositionContent()->getObject();
                // when an object is part of a template, or
                // if the child object isn't based upon the default template, and it isn't a root object, it is part of the
                // same template based structure as the current object, so it's a true child
                if ($object->getIsTemplate() || ($object->getIsTemplateBased() && $object->getIsObjectTemplateRoot() == 0)) {
                    $children[] = $object;
                }
            }
        }
        return $children;
    }

    /**
     * get the children of this object that are part of the same template or are
     * part of a searchable template, that links for instances/publishing/editing
     * to the parent object (that is based upon a different template)
     * 
     * @return Object[]
     */
    public function getEditChildren() {
        $children = array();
        $positions = $this->getPositions();
        foreach ($positions as $position) {
            // for each child that is an object and part of the same template, check the argument
            if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                $object = $position->getPositionContent()->getObject();
                // when an object is part of a template, or
                // when the child object isn't based upon the default template, and it isn't a root object, it is part of the
                // same template based structure as the current object, so it's a true child, or
                // when the child object is based upon a searchable template and is the object template root and is active
                if ($object->getIsTemplate() || ($object->getIsTemplateBased() && $object->getIsObjectTemplateRoot() == 0) || ($object->getIsTemplateBased() && $object->getIsObjectTemplateRoot() == 1 && ($object->getActive() || $object->getNew()) && $object->getTemplate()->getSearchable())) {
                    $children[] = $object;
                }
            }
        }
        return $children;
    }

    /**
     * get the children of this object
     * 
     * @return Object[]
     */
    public function getChildren() {
        $children = array();
        $positions = $this->getPositions();
        foreach ($positions as $position) {
            // for each child that is an object and part of the same template, check the argument
            if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                $children[] = $position->getPositionContent()->getObject();
            }
        }
        return $children;
    }

    /**
     * get a position for this mode
     * never assume the positions are already loaded, getpositions is the loader
     * 
     * check existence to prevent log record
     *  
     * @return position
     */
    public function getPosition($number) {
        $positions = $this->getPositions();
        if (isset($positions[$number])) {
            return $positions[$number];
        }
        throw new Exception(Helper::getLang(Errors::ERROR_POSITION_NUMBER_NOT_FOUND) . ': ' . $this->getId() . '-' . $number . ' @ ' . __METHOD__);
    }

    /**
     * check whether a position for this mode exists
     *  
     * @return boolean true if it exists
     */
    public function hasPosition($number) {
        $positions = $this->getPositions();
        if (isset($positions[$number])) {
            return true;
        }
        return false;
    }

    /**
     * Create a new position at the bottom
     * 
     * @return position
     */
    public function newPosition() {
        $number = $this->getPositionCount() + 1;
        $this->setChanged();
        // create the position
        Store::insertPosition($this->getId(), $number);
        // empty the cached positions
        $this->positionsloaded = false;
        $this->positions = array();
        $newposition = $this->getPosition($number);
        $newposition->setStyle(Styles::getStyle(Style::DEFAULT_POSITION_STYLE));
        return $newposition;
    }

    /**
     * Create a new position for a template
     * 
     * @param template $template the template to prepare the position object for
     * @return positionobject
     */
    public function newTemplateObjectPosition($template, $number = 0) {
        if ($number == 0) {
            $position = $this->newPosition();
        } else {
            if ($this->newPositionNumber($number)) {
                $position = $this->getPosition($number);
            }
        }
        if (isset($position)) {
            // apply the structure/style settings from the template
            $position->setStructure($template->getStructure());
            $position->setStyle($template->getStyle());
            $position->setInheritStructure(true);
            $position->setInheritStyle(true);
            // create a new empty position object
            $positionobject = $position->newPositionObject(false);
            // return the new position object
            return $positionobject;
        }
        throw new Exception(Helper::getLang(Errors::ERROR_POSITION_NUMBER_NOT_FOUND) . ': ' . $this->getId() . '-' . $number . ' @ ' . __METHOD__);
    }

    /**
     * Creat a new position at a specified number
     * 
     * @param int $number
     * @return boolean true if success
     */
    private function newPositionNumber($number) {
        $positions = $this->getPositions();
        if ($this->getLayout()->isPNType()) {
            // check whether the number exists, or insert at the end
            if (isset($positions[$number]) || $number = count($positions) + 1) {
                // set this version changed
                $this->setChanged();
                // renumber the positions
                if ($this->getLayout()->isPNType()) {
                    $counter = $number;
                    // careful here: this works because $positions contains the old situation
                    while (isset($positions[$counter])) {
                        $positions[$counter]->setNumber($counter + 1);
                        $counter = $counter + 1;
                    }
                }
                // create the position
                Store::insertPosition($this->getId(), $number);
                // empty the cached positions
                $this->positionsloaded = false;
                $this->positions = array();
                $newposition = $this->getPosition($number);
                $newposition->setStyle(Styles::getStyle(Style::DEFAULT_POSITION_STYLE));
                return true;
            }
        } else {
            // check whether the position isn't taken yet
            // minor issue: no check on whether the position is possible for this item,
            // it is only allowed to add positions for templates, this is for power users
            // only, so no threat (checks can be limiting, since the number of positions
            // in the layout for this template may be less than what is possible for alter-
            // native layouts. So checking may result in unwanted rejections.
            if (!isset($positions[$number])) {
                // set this version changed
                $this->setChanged();
                // create the position
                Store::insertPosition($this->getId(), $number);
                // empty the cached positions
                $this->positionsloaded = false;
                $this->positions = array();
                $newposition = $this->getPosition($number);
                $newposition->setStyle(Styles::getStyle(Style::DEFAULT_POSITION_STYLE));
                return true;
            }
        }
    }

    /**
     * get the number of positions
     * never assume the positions are already loaded, getpositions is the loader
     * 
     * @return int
     */
    public function getPositionCount() {
        return count($this->getPositions());
    }

    /**
     * get the parent for this object in this mode
     * 
     * @return object the parent object
     */
    public function getObjectParent() {
        return $this->objectparent;
    }

    /**
     * set the parent for this object in this mode (only to be used when moving the object through the site)
     * 
     * @param object $newparentobject the new parent
     * @return boolean true
     */
    public function setObjectParent($newparentobject) {
        $this->objectparent = $newparentobject;
        return true;
    }

    /**
     * get the parent position for this object in this mode
     * 
     * @return position the parent position
     */
    public function getPositionParent() {
        return $this->positionparent;
    }

    /**
     * get the object template root for this object in this mode
     * 
     * @return Object the template root object
     */
    public function getObjectTemplateRootObject() {
        return $this->objecttemplaterootobject;
    }

    /**
     * get the layout id
     * 
     * @return Layout
     */
    public function getLayout() {
        return $this->layout;
    }

    /**
     * set the layout
     * 
     * @param layout the new layout
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setLayout($newlayout) {
        if (Store::setObjectVersionLayoutId($this->id, $newlayout->getId()) && $this->setChanged()) {
            $this->layout = $newlayout;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the style
     * 
     * @return style
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * set the style
     * 
     * @param style the new style
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setStyle($newstyle) {
        if (Store::setObjectVersionStyleId($this->id, $newstyle->getId()) && $this->setChanged()) {
            $this->style = $newstyle;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the argument
     * 
     * @return argument
     */
    public function getArgument() {
        return $this->argument;
    }

    /**
     * set the argument
     * 
     * @param argument the new argument
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setArgument($newargument) {
        if (Store::setObjectVersionArgumentId($this->id, $newargument->getId()) && $this->setChanged()) {
            $this->argument = $newargument;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the argument default value. The value can be:
     * 
     * <-1: show all positions by default (used for a blog page)
     * -1: default is the highest position number
     * 0: default is the lowest position number
     * >0: the given position
     * 
     * @return int the default value for the argument
     */
    public function getArgumentDefault() {
        return $this->argumentdefault;
    }

    /**
     * set the argument id
     * 
     * @param newargumentid the new id
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setArgumentDefault($newargumentdefault) {
        if (Store::setObjectVersionArgumentDefault($this->id, $newargumentdefault) && $this->setChanged()) {
            $this->argumentid = $newargumentdefault;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the inherit layout value, if true, the object inherits it's 
     * layout from the template. The layout can't be changed by the user for
     * this object. When the layout is changed in the template and the 
     * template is published, the layout is also changed in this object version.
     * 
     * @return boolean inherit the layout or not
     */
    public function getInheritLayout() {
        return $this->inheritlayout;
    }

    /**
     * set the inherit layout bool, only functional in template objects
     * 
     * @param newinheritlayout the new value
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setInheritLayout($newinheritlayout) {
        if (Store::setObjectVersionInheritLayout($this->id, $newinheritlayout) && $this->setChanged()) {
            $this->inheritlayout = $newinheritlayout;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the inherit style value, if true, the object inherits it's 
     * style from the template. The style can't be changed by the user for
     * this object. When the style is changed in the template and the 
     * template is published, the style is also changed in this object version.
     * 
     * @return boolean inherit the style or not
     */
    public function getInheritStyle() {
        return $this->inheritstyle;
    }

    /**
     * set the inherit style bool, only functional in template objects
     * 
     * @param newinheritstyle the new value
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setInheritStyle($newinheritstyle) {
        if (Store::setObjectVersionInheritStyle($this->id, $newinheritstyle) && $this->setChanged()) {
            $this->inheritstyle = $newinheritstyle;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the template for this version
     * 
     * @return template
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * set the template for this version, this template id is used for 
     * adding new template based object trees to a #pn# layout.
     * 
     * @param template the new template
     * @return boolean  if success
     */
    public function setTemplate($newtemplate) {
        if (Store::setObjectVersionTemplateId($this->id, $newtemplate->getId()) && $this->setChanged()) {
            $this->template = $newtemplate;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Check whether this object version is really referring to a parent, or not
     * 
     * @return boolean
     */
    public function hasObjectParent() {
        return ($this->getContainer()->getId() != $this->getObjectParent()->getId());
    }

    /**
     * Check whether the object version has positions in wich to create new content
     * 
     * @return boolean true if positions are available
     */
    public function hasAvailablePositions() {
        if ($this->getLayout()->isPNType()) {
            return true;
        }
        return false;
    }

    /**
     * getter for the mode
     * 
     * @return mode
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * Set the mode 
     * 
     * @param mode $newmode
     */
    public function setMode($newmode) {
        // when changing the mode of a view or edit version, outdate the version cache for the object
        if ($this->getMode()->getId() == Mode::EDITMODE || $this->getMode()->getId() == Mode::VIEWMODE) {
            $this->getContainer()->outdateVersionCache();
        }
        // update the mode
        if (Store::setObjectVersionMode($this->getId(), $newmode->getId())) {
            $this->mode = $newmode;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the free positions for layouts that are not pn-type, used for adding
     * content to the free positions
     * 
     * @return int[] the numbers of the free positions
     */
    public function getFreePositions() {
        if (!$this->getLayout()->isPNType()) {
            if ($allpositions = $this->getLayout()->getAllPositions($this->getMode())) {
                $setpositions = $this->getPositions();
                $positions = array();
                foreach ($allpositions as $allposition) {
                    if (!isset($setpositions[$allposition])) {
                        $positions[] = $allposition;
                    }
                }
                return $positions;
            }
        }
    }

    /**
     * Remove a position, if the layout is of the pn-type, renumber positions.
     * 
     * @param int $number
     * @return mixed the old value for the position content
     */
    public function removePosition($number) {
        $positions = $this->getPositions();
        if (isset($positions[$number])) {
            $returnvalue = $positions[$number]->getPositionContent()->getType();
            // if the position contains an object, set the object to deleted
            if ($positions[$number]->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                $childobject = $positions[$number]->getPositionContent()->getObject();
                $childobject->setActiveRecursive(false);
            }
            // remove the content from the position
            $positions[$number]->removePositionContent();
            // remove the position
            Store::deletePosition($positions[$number]->getId());

            // renumber the positions
            if ($this->getLayout()->isPNType()) {
                $counter = $number + 1;
                while (isset($positions[$counter])) {
                    $positions[$counter]->setNumber($counter - 1);
                    $counter = $counter + 1;
                }
            }

            // return the removed position content type
            return $returnvalue;
        }
    }

    /**
     * Add a position in a certain place and create content for the position.
     * 
     * Number = 0 means at the first free positions (pn layouts only)
     * Any other number means in place of the existing position at that number (and
     * move the existing positions one up)
     * 
     * @param int $number
     * @return boolean true if success
     */
    public function newPositionContentItem($number) {
        if ($number == 0 && $this->getLayout()->isPNType()) {
            // create a position at the bottom
            $position = $this->newPosition();
            $position->newPositionContentItem();
        } else {
            // create a position at a specified position (and renumber other positions)            
            if ($this->newPositionNumber($number)) {
                $this->getPosition($number)->newPositionContentItem();
            }
        }
        return true;
    }

    /**
     * Add a position in a certain place and create content for the position
     * 
     * @param int $number
     * @return boolean true if success
     */
    public function newPositionObject($number) {
        if ($this->newPositionNumber($number)) {
            $this->getPosition($number)->newPositionObject(true);
        }
        return true;
    }

    /**
     * Add a position in a certain place and create content for the position
     * 
     * @param int $number
     * @return boolean true if success
     */
    public function newPositionInstance($number) {
        if ($this->newPositionNumber($number)) {
            $this->getPosition($number)->newPositionInstance();
        }
        return true;
    }

    /**
     * Add a position in a certain place and create content for the position
     * 
     * @param int $number
     * @return boolean true if success
     */
    public function newPositionReferral($number) {
        if ($this->newPositionNumber($number)) {
            $this->getPosition($number)->newPositionReferral();
        }
        return true;
    }

    /**
     * Decide whether an object is moveable to a lower position
     * 
     * @return boolean true if moveable
     */
    public function isMoveableUp() {
        // only if the parent has a pn-type layout, and the object is the template based root
        if ($this->getObjectParent()->getVersion($this->getMode())->getLayout()->isPNType() && $this->getContainer()->getIsObjectTemplateRoot()) {
            $positions = $this->getObjectParent()->getVersion($this->getMode())->getPositions();
            $positionnr = $this->getPositionParent()->getNumber();
            foreach ($positions as $position) {
                if ($position->getNumber() < $positionnr) {
                    switch ($position->getPositionContent()->getType()) {
                        case PositionContent::POSITIONTYPE_CONTENTITEM:
                        case PositionContent::POSITIONTYPE_INSTANCE:
                        case PositionContent::POSITIONTYPE_REFERRAL:
                            // moveable
                            return true;
                            break;
                        case PositionContent::POSITIONTYPE_OBJECT:
                            // if the object is active or new, it is moveable
                            if ($position->getPositionContent()->getObject()->getActive() || $position->getPositionContent()->getObject()->getNew()) {
                                return true;
                            }
                            // otherwise ignore the position
                            break;
                        case PositionContent::POSITIONTYPE_EMPTY:
                        default:
                            return false;
                            break;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Decide whether an object is moveable to a higher position
     * 
     * @return boolean true if moveable
     */
    public function isMoveableDown() {
        // only if the parent has a pn-type layout, and the object is the template based root
        if ($this->getObjectParent()->getVersion($this->getMode())->getLayout()->isPNType() && $this->getContainer()->getIsObjectTemplateRoot()) {
            $positions = $this->getObjectParent()->getVersion($this->getMode())->getPositions();
            $positionnr = $this->getPositionParent()->getNumber();
            foreach ($positions as $position) {
                if ($position->getNumber() > $positionnr) {
                    switch ($position->getPositionContent()->getType()) {
                        case PositionContent::POSITIONTYPE_CONTENTITEM:
                        case PositionContent::POSITIONTYPE_INSTANCE:
                        case PositionContent::POSITIONTYPE_REFERRAL:
                            // moveable
                            return true;
                            break;
                        case PositionContent::POSITIONTYPE_OBJECT:
                            // if the object is active, it is moveable
                            if ($position->getPositionContent()->getObject()->getActive() || $position->getPositionContent()->getObject()->getNew()) {
                                return true;
                            }
                            // otherwise ignore the position
                            break;
                        case PositionContent::POSITIONTYPE_EMPTY:
                        default:
                            return false;
                            break;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Return the position that can be switched with the one this object is in,
     * to swap them
     * 
     * @return position
     */
    public function getMoveUpPosition() {
        $found = false;
        $returnposition = '';
        // only if the parent has a pn-type layout, and the object is the template based root
        if ($this->getObjectParent()->getVersion($this->getMode())->getLayout()->isPNType() && $this->getContainer()->getIsObjectTemplateRoot()) {
            $positions = $this->getObjectParent()->getVersion($this->getMode())->getPositions();
            $positionnr = $this->getPositionParent()->getNumber();
            foreach ($positions as $position) {
                if ($position->getNumber() < $positionnr) {
                    switch ($position->getPositionContent()->getType()) {
                        case PositionContent::POSITIONTYPE_CONTENTITEM:
                        case PositionContent::POSITIONTYPE_INSTANCE:
                        case PositionContent::POSITIONTYPE_REFERRAL:
                            // a referral is moveable
                            $found = true;
                            $returnposition = $position;
                            break;
                        case PositionContent::POSITIONTYPE_OBJECT:
                            // if the object is active, it is moveable
                            if ($position->getPositionContent()->getObject()->getActive() || $position->getPositionContent()->getObject()->getNew()) {
                                $found = true;
                                $returnposition = $position;
                            }
                            // otherwise ignore the position
                            break;
                        case PositionContent::POSITIONTYPE_EMPTY:
                        default:
                            break;
                    }
                }
            }
        }
        if ($found) {
            return $returnposition;
        }
    }

    /**
     * Return the position that can be switched with the one this object is in,
     * to swap them
     * 
     * @return position
     */
    public function getMoveDownPosition() {
        $found = false;
        $returnposition = '';
        // only if the parent has a pn-type layout, and the object is the template based root
        if ($this->getObjectParent()->getVersion($this->getMode())->getLayout()->isPNType() && $this->getContainer()->getIsObjectTemplateRoot()) {
            $positions = $this->getObjectParent()->getVersion($this->getMode())->getPositions();
            $positionnr = $this->getPositionParent()->getNumber();
            foreach ($positions as $position) {
                if ($position->getNumber() > $positionnr && !$found) {
                    switch ($position->getPositionContent()->getType()) {
                        case PositionContent::POSITIONTYPE_CONTENTITEM:
                        case PositionContent::POSITIONTYPE_INSTANCE:
                        case PositionContent::POSITIONTYPE_REFERRAL:
                            // a referral is moveable
                            $found = true;
                            $returnposition = $position;
                            break;
                        case PositionContent::POSITIONTYPE_OBJECT:
                            // if the object is active, it is moveable
                            if ($position->getPositionContent()->getObject()->getActive() || $position->getPositionContent()->getObject()->getNew()) {
                                $found = true;
                                $returnposition = $position;
                            }
                            // otherwise ignore the position
                            break;
                        case PositionContent::POSITIONTYPE_EMPTY:
                        default:
                            break;
                    }
                }
            }
        }
        if ($found) {
            return $returnposition;
        }
    }

}

?>
