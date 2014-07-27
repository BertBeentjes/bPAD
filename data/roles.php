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

/*
 * Contains all roles, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Roles {
    private static $roles = array();
    
    /**
     * get a role by id, checks whether the role is loaded,
     * loads the role if necessary and fills it on demand with
     * further information
     * 
     * @param int the id of the role to get
     * @return role
     */
    public static function getRole ($roleid) {
        // return an role
        if (isset(self::$roles[$roleid])) {
            return self::$roles[$roleid];
        } else {
            self::$roles[$roleid] = new Role($roleid);
            return self::$roles[$roleid];
        }
    }

    /**
     * Get all roles
     * 
     * @return resultset
     */
    public static function getRoles () {
        return Store::getRoles();
    }
    
}