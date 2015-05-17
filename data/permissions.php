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

/*
 * Contains all permissions, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Permissions {
    private static $permissions = array();
    
    /*
     * get a permission by id, checks whether the permission is loaded,
     * loads the permission if necessary and fills it on demand with
     * further information
     * 
     * @param permissionid the id of the permission to get
     * @return permission
     */
    public static function getPermission ($permissionid) {
        // return an permission
        if (isset(self::$permissions[$permissionid])) {
            return self::$permissions[$permissionid];
        } else {
            self::$permissions[$permissionid] = new Permission($permissionid);
            return self::$permissions[$permissionid];
        }
    }
}