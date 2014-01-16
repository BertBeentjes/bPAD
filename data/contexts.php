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
 * Contains all contexts, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */

class Contexts {

    private static $contexts = array();

    /**
     * get a context by id, checks whether the context is loaded,
     * loads the context if necessary and fills it on demand with
     * further information
     * 
     * @param contextid the id of the context to get
     * @return context
     */
    public static function getContext($contextid) {
        if (Validator::isNumeric($contextid)) {
            // return a context
            if (isset(self::$contexts[$contextid])) {
                return self::$contexts[$contextid];
            } else {
                self::$contexts[$contextid] = new Context($contextid);
                return self::$contexts[$contextid];
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get all contexts for use in a listbox
     * 
     * @return resultset
     */
    public static function getContexts() {
        // return a resultset
        return Store::getContexts();
    }

    /**
     * Get a context based upon a group and the name
     * 
     * @param contextgroup $contextgroup
     * @param string $name
     */
    public static function getContextByGroupAndName($contextgroup, $name) {
        if ($result = Store::getContextByGroupAndName($contextgroup->getId(), $name)) {
            if ($row = $result->fetchObject()) {
                return self::getContext($row->id);
            }
        }
    }

}

?>
