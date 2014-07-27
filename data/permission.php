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
 * Permissions are granted to roles
 *
 * @since 0.4.0
 */
class Permission extends StoredEntity {
    private $role; // the role these permissions apply to 

    // site user permissions
    private $viewobject; // basic viewing rights, for anonymous visitors
    
    private $uploadfile; // upload a file, can be given to any content creating roles

    private $frontendrespond; // most basic content creation, add content based upon a predefined template in a predefined spot
    private $frontendcreatoredit; // edit your own creations
    private $frontendcreatordeactivate; // deactivate your own creations
    
    private $frontendadd; // add content using the add menu
    private $frontendedit; // edit content
    private $frontenddeactivate; // deactivate content

    // site manager permissions
    private $managecontent; // create, edit or delete objects, templates. Anything content.
    private $managelayout; // create, edit or delete layouts
    private $managestyle; // create, edit or delete styles or style params
    private $managestructure; // create, edit or delete structures
    private $managetemplate; // create, edit or delete a template

    private $managesystem; // create and edit sets, contexts, arguments
    private $managelanguage; // manage language strings
    private $managesetting; // manage system settings
    private $manageuser; // manage users and user groups
    private $managerole; // manage roles and permissions
    private $manageauthorization; // manage authorizations in the site for objects
    private $managelssversion; // manage the lss versions (that distributes layouts/styles/structures to other installs)

    // technicalities
    private $showadminbar;
    private $flusharchive;

    /**
     * Constructor for the permission 
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTablePermissions();
        $this->loadAttributes();
    }
    
    /**
     * load the attributes for the permission
     * 
     * @return boolean true if success
     * @throws Exception when permission isn't there
     */
    private function loadAttributes() {
        if ($result = Store::getPermission($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        }
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ' @ ' . __METHOD__);
    }
    
    /**
     * initialize all the permission attributes
     * 
     * @param resultobject $attr the object fetched with the attributes
     * @return boolean true if success
     */
    protected function initAttributes($attr) {
        $this->role = Roles::getRole($attr->roleid);

        $this->managetemplate = (bool) $attr->managetemplate;

        $this->viewobject = (bool) $attr->viewobject;
        
        $this->uploadfile = (bool) $attr->uploadfile;

        $this->frontendrespond = (bool) $attr->frontendrespond;
        $this->frontendedit = (bool) $attr->frontendedit;
        $this->frontenddeactivate = (bool) $attr->frontenddeactivate;

        $this->frontendadd = (bool) $attr->frontendadd;
        $this->frontendcreatordeactivate = (bool) $attr->frontendcreatordeactivate;
        $this->frontendcreatoredit = (bool) $attr->frontendcreatoredit;

        $this->managecontent = (bool) $attr->managecontent;
        $this->managelayout = (bool) $attr->managelayout;
        $this->managestyle = (bool) $attr->managestyle;
        $this->managestructure = (bool) $attr->managestructure;

        $this->managesystem = (bool) $attr->managesystem; 
        $this->managelanguage = (bool) $attr->managelanguage; 
        $this->managesetting = (bool) $attr->managesetting; 
        $this->manageuser = (bool) $attr->manageuser; 
        $this->managerole = (bool) $attr->managerole; 
        $this->manageauthorization = (bool) $attr->manageauthorization; 

        $this->showadminbar = (bool) $attr->showadminbar;
        $this->managelssversion = (bool) $attr->managelssversion;
        $this->flusharchive = (bool) $attr->flusharchive;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * get the role these permissions are granted to
     * 
     * @return role
     */
    public function getRole() {
        return $this->role;
    }
    
    /**
     * set the role for this permission
     * 
     * @param role the new role
     * @return boolean success if true
     * @throws Exception when update fails
     */
    public function setRole($newrole) {
        if (Store::setPermissionRoleId($this->id, $newrole->getId()) && $this->setChanged()) {
            $this->role = $newrole;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage template
     * 
     * @return boolean managetemplate
     */
    public function getManageTemplate() {
        return $this->managetemplate;
    }
    
    /**
     * set managetemplate 
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageTemplate($bool) {
        if (Store::setPermissionManageTemplate($this->id, $bool) && $this->setChanged()) {
            $this->managetemplate = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get view object
     * 
     * @return boolean view object
     */
    public function getViewObject() {
        return $this->viewobject;
    }
    
    /**
     * set view object
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setViewObject($bool) {
        if (Store::setPermissionViewObject($this->id, $bool) && $this->setChanged()) {
            $this->viewobject = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get upload file
     * 
     * @return boolean uploadfile
     */
    public function getUploadFile() {
        return $this->uploadfile;
    }
    
    /**
     * set upload file permission
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setUploadFile($bool) {
        if (Store::setPermissionUploadFile($this->id, $bool) && $this->setChanged()) {
            $this->uploadfile = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get front end respond
     * 
     * @return boolean frontendrespond
     */
    public function getFrontendRespond() {
        return $this->frontendrespond;
    }
    
    /**
     * set frontend respond
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setFrontendRespond($bool) {
        if (Store::setPermissionFrontendRespond($this->id, $bool) && $this->setChanged()) {
            $this->frontendrespond = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get frontend creator edit
     * 
     * @return boolean frontendcreatoredit
     */
    public function getFrontendCreatorEdit() {
        return $this->frontendcreatoredit;
    }
    
    /**
     * set frontend creator edit
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setFrontendCreatorEdit($bool) {
        if (Store::setPermissionFrontendCreatorEdit($this->id, $bool) && $this->setChanged()) {
            $this->frontendcreatoredit = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get frontend creator deactivate
     * 
     * @return boolean frontendcreatordeactivate
     */
    public function getFrontendCreatorDeactivate() {
        return $this->frontendcreatordeactivate;
    }
    
    /**
     * set frontend creator deactivate
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setFrontendCreatorDeactivate($bool) {
        if (Store::setPermissionFrontendCreatorDeactivate($this->id, $bool) && $this->setChanged()) {
            $this->frontendcreatordeactivate = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get frontend add
     * 
     * @return boolean frontendadd
     */
    public function getFrontendAdd() {
        return $this->frontendadd;
    }
    
    /**
     * set frontend add
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setFrontendAdd($bool) {
        if (Store::setPermissionFrontendAdd($this->id, $bool) && $this->setChanged()) {
            $this->frontendadd = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get frontend edit
     * 
     * @return boolean frontendedit
     */
    public function getFrontendEdit() {
        return $this->frontendedit;
    }
    
    /**
     * set frontend edit
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setFrontendEdit($bool) {
        if (Store::setPermissionFrontendEdit($this->id, $bool) && $this->setChanged()) {
            $this->frontendedit = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get frontend deactivate
     * 
     * @return boolean frontenddeactivate
     */
    public function getFrontendDeactivate() {
        return $this->frontenddeactivate;
    }
    
    /**
     * set frontend deactivate
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setFrontendDeactivate($bool) {
        if (Store::setPermissionFrontendDeactivate($this->id, $bool) && $this->setChanged()) {
            $this->frontenddeactivate = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage content
     * 
     * @return boolean managecontent
     */
    public function getManageContent() {
        return $this->managecontent;
    }
    
    /**
     * set manage content
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageContent($bool) {
        if (Store::setPermissionManageContent($this->id, $bool) && $this->setChanged()) {
            $this->managecontent = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage layout
     * 
     * @return boolean managelayout
     */
    public function getManageLayout() {
        return $this->managelayout;
    }
    
    /**
     * set manage layout
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageLayout($bool) {
        if (Store::setPermissionManageLayout($this->id, $bool) && $this->setChanged()) {
            $this->managelayout = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage style
     * 
     * @return boolean managestyle
     */
    public function getManageStyle() {
        return $this->managestyle;
    }
    
    /**
     * set manage style
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageStyle($bool) {
        if (Store::setPermissionManageStyle($this->id, $bool) && $this->setChanged()) {
            $this->managestyle = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage structure
     * 
     * @return boolean managestructure
     */
    public function getManageStructure() {
        return $this->managestructure;
    }
    
    /**
     * set manage structure
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageStructure($bool) {
        if (Store::setPermissionManageStructure($this->id, $bool) && $this->setChanged()) {
            $this->managestructure = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage system
     * 
     * @return boolean managesystem
     */
    public function getManageSystem() {
        return $this->managesystem;
    }
    
    /**
     * set manage system
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageSystem($bool) {
        if (Store::setPermissionManageSystem($this->id, $bool) && $this->setChanged()) {
            $this->managesystem = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage language
     * 
     * @return boolean managelanguage
     */
    public function getManageLanguage() {
        return $this->managelanguage;
    }
    
    /**
     * set manage language
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageLanguage($bool) {
        if (Store::setPermissionManageLanguage($this->id, $bool) && $this->setChanged()) {
            $this->managelanguage = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage setting
     * 
     * @return boolean managesetting
     */
    public function getManageSetting() {
        return $this->managesetting;
    }
    
    /**
     * set managesetting
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageSetting($bool) {
        if (Store::setPermissionManageSetting($this->id, $bool) && $this->setChanged()) {
            $this->managesetting = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage user
     * 
     * @return boolean manageuser
     */
    public function getManageUser() {
        return $this->manageuser;
    }
    
    /**
     * set manageuser
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageUser($bool) {
        if (Store::setPermissionManageUser($this->id, $bool) && $this->setChanged()) {
            $this->manageuser = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage role
     * 
     * @return boolean managerole
     */
    public function getManageRole() {
        return $this->managerole;
    }
    
    /**
     * set managerole
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageRole($bool) {
        if (Store::setPermissionManageRole($this->id, $bool) && $this->setChanged()) {
            $this->managerole = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get manage authorization
     * 
     * @return boolean manageauthorization
     */
    public function getManageAuthorization() {
        return $this->manageauthorization;
    }
    
    /**
     * set manage authorization
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setManageAuthorization($bool) {
        if (Store::setPermissionManageAuthorization($this->id, $bool) && $this->setChanged()) {
            $this->manageauthorization = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get show admin bar
     * 
     * @return boolean showadminbar
     */
    public function getShowAdminBar() {
        return $this->showadminbar;
    }
    
    /**
     * set show admin bar
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setShowAdminBar($bool) {
        if (Store::setPermissionShowAdminBar($this->id, $bool) && $this->setChanged()) {
            $this->showadminbar = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get lss version
     * 
     * @return boolean lssversion
     */
    public function getLSSVersion() {
        return $this->lssversion;
    }
    
    /**
     * set lss version
     *
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setLSSVersion($bool) {
        if (Store::setPermissionLSSVersion($this->id, $bool) && $this->setChanged()) {
            $this->lssversion = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get flush archive
     * 
     * @return boolean flusharchive
     */
    public function getFlushArchive() {
        return $this->flusharchive;
    }
    
    /**
     * set flush archive
     * 
     * @param bool $bool
     * @return boolean success if true 
     * @throws Exception when update fails
     */
    public function setFlushArchive($bool) {
        if (Store::setPermissionFlushArchive($this->id, $bool) && $this->setChanged()) {
            $this->flusharchive = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}