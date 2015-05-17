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
 * Contains all sets, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Sets {
    private static $sets = array();
    
    /**
     * get a set by id, checks whether the set is loaded,
     * loads the set if necessary and fills it on demand with
     * further information
     * 
     * @param setid the id of the set to get
     * @return set
     */
    public static function getSet ($setid) {
        if (Validator::isNumeric($setid)) {
            // return an set
            if (isset(self::$sets[$setid])) {
                return self::$sets[$setid];
            } else {
                self::$sets[$setid] = new Set($setid);
                return self::$sets[$setid];
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get a set by its name
     * 
     * @param string $name
     * @return layout
     */
    public static function getSetByName($name) {
        $result = Store::getSetByName($name);
        if (is_object($result)) {
            if ($row = $result->fetchObject()) {
                return Sets::getSet($row->id);
            }
        }
    }

    /**
     * Get all sets
     * 
     * @return resultset
     */
    public static function getSets () {
        return Store::getSets();
    }
    
    /**
     * Create a new set
     * 
     * @return type
     */
    public static function newSet() {
        $setid = Store::insertSet();
        return Sets::getSet($setid);
    }

    /**
     * remove a set, you can only remove sets that aren't used, and you can't remove sets defined by bPAD
     * 
     * @param set $set
     * @return type
     */
    public static function removeSet($set) {
        if ($set->isRemovable()) {
            Store::deleteSet($set->getId());
            unset(self::$sets[$set->getId()]);
            return true;
        }
        return false;
    }
    
}