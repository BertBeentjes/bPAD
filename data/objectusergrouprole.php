<?php
/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2015
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
 * Connects objects, user groups and roles to define what role users have for
 * the object. The role defines the permissions given.
 *
 */
class ObjectUserGroupRole extends StoredEntity {
    private $object; // the object 
    private $usergroup; // the user group
    private $role; // the role
    private $inherit; // whether to inherit these permissions to child objects
    
    /**
     * Constructor for the object user group role
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableObjectUserGroupRoles();
        $this->loadAttributes();
    }
    
    /**
     * load the attributes for this object user group role
     * 
     * @return boolean true if success
     * @throws Exception when loading fails
     */
    public function loadAttributes() {
        if ($result = Store::getObjectUserGroupRole($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * initialize the attributes
     * 
     * @param resultobject $attr
     * @return boolean true
     */
    public function initAttributes ($attr) {
        $this->object = Objects::getObject($attr->objectid);
        $this->usergroup = UserGroups::getUsergroup($attr->usergroupid);
        $this->role = Roles::getRole($attr->roleid);
        $this->inherit = $attr->inherit;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the object
     * 
     * @return object
     */
    public function getObject () {
        return $this->object;
    }
    
    /**
     * setter for the object
     * 
     * @param object the new object
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setObject ($newobject) {
        if (Store::setObjectUserGroupRoleObjectId($this->id, $newobject->getId()) && $this->setChanged()) {
            $this->object = $newobject;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * getter for the user group
     * 
     * @return int user group
     */
    public function getUserGroup () {
        return $this->usergroup;
    }
    
    /**
     * setter for the user group
     * 
     * @param usergroup the new user group
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setUserGroup ($newusergroup) {
        if (Store::setObjectUserGroupRoleUserGroupId($this->id, $newusergroup->getId()) && $this->setChanged()) {
            $this->usergroup = $newusergroup;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * getter for the role
     * 
     * @return role
     */
    public function getRole () {
        return $this->role;
    }
    
    /**
     * setter for the role
     * 
     * @param role the new role
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setRole ($newrole) {
        if (Store::setObjectUserGroupRoleRoleId($this->id, $newrole->getId()) && $this->setChanged()) {
            $this->role = $newrole;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * getter for inherit
     * 
     * @return boolean inherit or not
     */
    public function getInherit () {
        return $this->inherit;
    }
    
    /**
     * setter for inherit
     * 
     * @param boolean $bool the new value for inherit
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setInherit ($bool) {
        if (Store::setObjectUserGroupRoleInherit($this->id, $bool) && $this->setChanged()) {
            $this->inherit = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
}