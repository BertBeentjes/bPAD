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
 * Contains all context groups, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class ContextGroups {
    private static $contextgroups = array();
    
    /*
     * get a context group by id, checks whether the context group is loaded,
     * loads the context group if necessary and fills it on demand with
     * further information
     * 
     * @param contextgroupid the id of the context group to get
     * @return contextgroup
     */
    public static function getContextGroup ($contextgroupid) {
        // return a context group
        if (isset(self::$contextgroups[$contextgroupid])) {
            return self::$contextgroups[$contextgroupid];
        } else {
            self::$contextgroups[$contextgroupid] = new ContextGroup($contextgroupid);
            return self::$contextgroups[$contextgroupid];
        }
    }
}

?>
