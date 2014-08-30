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
 * Contains all user groups, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class UserGroups {
    private static $usergroups = array();
    
    /**
     * get a usergroup by id, checks whether the usergroup is loaded,
     * loads the usergroup if necessary and fills it on demand with
     * further information
     * 
     * @param usergroupid the id of the usergroup to get
     * @return usergroup
     */
    public static function getUserGroup ($usergroupid) {
        // return an usergroup
        if (isset(self::$usergroups[$usergroupid])) {
            return self::$usergroups[$usergroupid];
        } else {
            self::$usergroups[$usergroupid] = new UserGroup($usergroupid);
            return self::$usergroups[$usergroupid];
        }
    }
    
    /**
     * Get all user groups
     * 
     * @return resultset
     */
    public static function getUserGroups () {
        return Store::getUserGroups();
    }

    /**
     * Create a new usergroup
     * 
     * @return type
     */
    public static function newUserGroup() {
        $usergroupid = Store::insertUserGroup();
        return true;
    }

    /**
     * remove a usergroup, you can only remove usergroups that aren't used
     * 
     * @param usergroup $usergroup
     * @return type
     */
    public static function removeUserGroup($usergroup) {
        if ($usergroup->isRemovable()) {
            Store::deleteUserGroup($usergroup->getId());
            unset(self::$usergroups[$usergroup->getId()]);
            return true;
        }
        return false;
    }
    
}