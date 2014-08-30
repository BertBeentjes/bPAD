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
 * a group of users
 *
 * @since 0.4.0
 */
class UserGroup extends NamedEntity{
    
    const DEFAULT_USERGROUP = 1;

    /**
     * Constructor, sets the basic user group attributes
     * By setting these attribs, the existence of the user group is 
     * verified
     * 
     * @param id contains the user group id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableUserGroups();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getUserGroup($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * Is the user group used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getUserGroupUsed($this->getId())) {
            return true;
        }
        return false;
    }
    
    /**
     * Is the user group removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return !$this->isUsed();
    }
    
    /**
     * Add a user to the group
     * 
     * @param user $user
     * @return boolean true if added
     */
    public function addUser($user) {
        $usergroups = $user->getUserGroups();
        if (array_key_exists($this->getId(), $usergroups)) {
            // the user is already there, can't add twice
            return false;
        } else {
            Store::insertUserUserGroup($user->getId(), $this->getId());
            return true;
        }
    } 
    
    /**
     * Delete a user from the group
     * 
     * @param user $user
     * @return boolean true if deleted
     */
    public function deleteUser($user) {
        $usergroups = $user->getUserGroups();
        if (array_key_exists($this->getId(), $usergroups)) {
            Store::deleteUserUserGroup($user->getId(), $this->getId());
            return true;
        } else {
            // the user isn't there, can't be deleted
            return false;
        }
    }
    
}