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
 * Contains all users, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Users {
    private static $users = array();
    
    /**
     * get a user by id, checks whether the user is loaded,
     * loads the user if necessary and fills it on demand with
     * further information
     * 
     * @param int userid the id of the user to get
     * @return user
     */
    public static function getUser ($userid) {
        // return a user
        if (isset(self::$users[$userid])) {
            return self::$users[$userid];
        } else {
            self::$users[$userid] = new User($userid);
            return self::$users[$userid];
        }
    }

    /**
     * Get a user by user name
     * 
     * @param string $name
     * @return user
     */
    public static function getUserByName($name) {
        if ($result = Store::getUserIdByName($name)) {
            if ($row = $result->fetchObject()) {
                return self::getUser($row->id);
            }
        }
    }
    
    /**
     * Get all users
     * 
     * @return resultset
     */
    public static function getUsers () {
        return Store::getUsers();
    }
    
    /**
     * Create a new user
     * 
     * @return type
     */
    public static function newUser() {
        $userid = Store::insertUser();
        return true;
    }

    /**
     * remove a user, you can only remove users that aren't used, and you can't remove users defined by bPAD
     * 
     * @param user $user
     * @return type
     */
    public static function removeUser($user) {
        if ($user->isRemovable()) {
            Store::deleteUser($user->getId());
            unset(self::$users[$user->getId()]);
            return true;
        }
        return false;
    }
}