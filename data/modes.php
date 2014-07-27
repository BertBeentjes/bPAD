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
 * Contains all modes, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Modes {
    private static $modes = array();
    
    /*
     * get a mode by id, checks whether the mode is loaded,
     * loads the mode if necessary and fills it on demand with
     * further information
     * 
     * @param modeid the id of the mode to get
     * @return mode
     */
    public static function getMode ($modeid) {
        if (Validator::isNumeric($modeid)) {
            // return an mode
            if (isset(self::$modes[$modeid])) {
                return self::$modes[$modeid];
            } else {
                self::$modes[$modeid] = new Mode($modeid);
                return self::$modes[$modeid];
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }
}