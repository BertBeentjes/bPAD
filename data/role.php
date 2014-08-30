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
 * a role has certain permissions. A user group gets a role for an object.
 *
 * @since 0.4.0
 */
class Role extends NamedEntity{
    
    const DEFAULT_ROLE = 1;

    private $permissions; 
    private $permissionsloaded = false; // are the permissions loaded?

    /**
     * Constructor, sets the basic role attributes
     * By setting these attribs, the existence of the role is 
     * verified
     * 
     * @param id contains the role id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableRoles();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getRole($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * get the permissions for this mode
     * 
     * @return Permission[]
     */
    public function getPermissions () {
        if ($this->permissionsloaded) {
            return $this->permissions;
        } else {
            if ($result = Store::getRolePermissions($this->id)) {
                while ($attr = $result->fetchObject()) {
                    // load the permissions with the generic loader
                    $this->permissions = Permissions::getPermission($attr->id);
                }
            }
            $this->permissionsloaded = true;
            return $this->permissions;
        }
    }

    /**
     * Is the role used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getRoleUsed($this->getId())) {
            return true;
        }
        return false;
    }
    
    /**
     * Is the role removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return !$this->isUsed();
    }
    
}