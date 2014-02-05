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
 * Cache the addressable parents of objects, for use in instances, to select only
 * objects that reside under a specific addressable parent.
 * 
 * The cache is used low level in the queries for instances, so no retrieval from
 * the cache in the code
 *
 * @since 0.4.0
 */
class CacheObjectAddressableParentObjects {

    /**
     * Update the cache for an object version
     * 
     * @param objectversion $objectversion
     */
    public static function updateCache($objectversion) {
        // if this is not a template
        if (!$objectversion->getContainer()->getIsTemplate()) {
            $mode = $objectversion->getMode();
            // clear the cache
            Store::deleteObjectAddressableParentCacheByObjectAndMode($objectversion->getContainer()->getId(), $mode->getId());
            // refill the cache
            $obver = $objectversion;
            // while we're not at the top of an object tree
            $atthetop = false;
            $level = 0;
            while (!$atthetop) {
                $parent = $obver->getObjectParent();
                if ($parent->isAddressable($mode) || $parent->isSiteRoot()) {
                    // store this as an addressable parent
                    // store the site root as a parent, to make the default selection for everything that is in the site but not orphaned parts of the site
                    // and great function name :)
                    $level = $level + 1;
                    Store::insertObjectAddressableParentCacheByObjectAndModeAndAddressableParent($objectversion->getContainer()->getId(), $mode->getId(), $parent->getId(), $level);
                }
                $obver = $parent->getVersion($mode);
                // end at the site root, or at a tree top (actually, the check for site root is superfluous, it is also a tree top,
                // but added for readability :)
                $atthetop = ($obver->getContainer()->isSiteRoot() || $obver->getObjectParent()->getId()==$obver->getContainer()->getId());
            }
        }
    }
    
    /**
     * Delete the object from the object addressable parent cache
     * 
     * @param object $object
     */
    public static function deleteObjectFromCache($object) {
        Store::deleteObjectFromCache($object->getId());
    }

    /**
     * Get the addressable parents for an object from the cache
     * 
     * @param object $object
     * @param mode $mode
     * @result object[]
     */
    public static function getObjectAddressableParentsByMode($object, $mode) {
        if ($result = Store::getObjectAddressableParentsByObjectIdAndModeId($object->getId(), $mode->getId())) {
            $objects = array();
            while ($row = $result->fetchObject()) {
                $objects[] = Objects::getObject($row->addressableparentid);
            }
            return $objects;
        }
    }
}

?>
