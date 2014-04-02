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
 * Retrieve the position instance cache, or update it when outdated
 *
 * @since 0.4.0
 */
class CachePositionInstances {
    /**
     * Get an array of objects that belong in the instance
     * 
     * @param positioninstance $positioninstance
     * @param string [usersearch] optional user search
     * @return Object[]
     */
    public static function getObjects($positioninstance, $usersearch = '') {
        $objects = array();
        // if the instance is outdated, create a new cache, otherwise return the cache
        if ($positioninstance->getOutdated()) {
            // create a new cache
            // first the delete the current cache
            Store::deletePositionInstanceCacheObjectsByPositionInstanceId($positioninstance->getId());
            // then get the new objects
            $objects = self::findObjects($positioninstance);
            // then store the new objects
            if (is_array($objects)) {
                foreach ($objects as $object) {
                    Store::insertPositionInstanceCacheObjectsByPositionInstanceId($positioninstance->getId(), $object['object']->getId(), $object['groupvalue']);
                }
            }
            // update, the cache is no longer outdated
            $positioninstance->setOutdated(false);
        }
        // the cache is now up to date, get the objects from the cache and if necessary do the user search
        // if there is a user search, or the instance should not fill on load (that is: wait for user search input)
        if ($usersearch > '' || !$positioninstance->getFillOnLoad()) {
            // if there is a user search
            if ($usersearch > '') {
                $objects = array();
                // get and return the cached objects
                if ($result = Store::getPositionInstanceCacheObjectsByPositionInstanceIdWithUserSearch($positioninstance->getId(), $usersearch)) {
                    while ($row = $result->fetchObject()) {
                        $objectvalues = array();
                        $objectvalues['object'] = Objects::getObject($row->objectid);
                        $objectvalues['groupvalue'] = $row->groupvalue;
                        $objects[] = $objectvalues;
                    }
                }
            } else {
                // no user search and no fill on load, return nothing
            }
        } else {
            $objects = array();
            // get and return the cached objects
            if ($result = Store::getPositionInstanceCacheObjectsByPositionInstanceId($positioninstance->getId())) {
                while ($row = $result->fetchObject()) {
                    $objectvalues = array();
                    $objectvalues['object'] = Objects::getObject($row->objectid);
                    $objectvalues['groupvalue'] = $row->groupvalue;
                    $objects[] = $objectvalues;
                }
            }
        }
        // return the objects
        return $objects;
    }

    /**
     * create an array with the objects selected by this instance
     * 
     * @param positioninstance $positioninstance
     * @return object[] $objects
     */
    private static function findObjects($positioninstance) {
        // get the complete query
        if ($result = Store::instanceQuery($positioninstance->getTemplate()->getId(), $positioninstance->getParent()->getId(), $positioninstance->getListWords(), $positioninstance->getSearchWords(), $positioninstance->getActiveItems(), $positioninstance->getOrderBy(), $positioninstance->getContainer()->getContainer()->getMode()->getId())) {
            $objects = array();
            while ($row = $result->fetchObject()) {
                $objectvalues = array();
                $objectvalues['object'] = Objects::getObject($row->objectid);
                if (!isset($row->groupvalue)) {
                    $objectvalues['groupvalue'] = '';
                } else {
                    $objectvalues['groupvalue'] = $row->groupvalue;
                }
                $objects[] = $objectvalues;
            }
            return $objects;
        }
    }
}

?>
