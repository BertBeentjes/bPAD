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
 * Gets objects from the cache, stores new objects in the cache, outdates
 * the cache after changes
 *
 * @since 0.4.0
 */
class CacheObjects {
    private static $internallinksoutdated = false;

    /**
     * Retrieve a cached object
     * 
     * @param object $object
     * @param context $context
     * @param mode $mode
     * @return string
     */
    public static function getCacheObject($object, $context, $mode) {
        // only use cached items in viewmode, in edit mode get the real thing
        // check whether the item is in the cache, otherwise create a new cache item
        $content = '';
        if ($mode->getId() == mode::VIEWMODE) {
            $cache = '';
            if ($result = Store::getObjectCacheByObjectIdContextIdUserId($object->getId(), $context->getId(), Authentication::getUser()->getId())) {
                if ($row = $result->fetchObject()) {
                    $cache = new ObjectCache($row->id);
                    // only return cached items that aren't outdated
                    if ($cache->getOutdated()) {
                        // refresh the cache
                        $cache->setCache(self::factorObject($object, $context, $mode));
                    }
                }
            }
            if (!is_object($cache)) {
                // create and store a new object for the cache
                $content = self::factorAndCacheObject($object, $context, $mode);
            } else {
                // return the cached content
                $content = $cache->getCache();
            }
        } else {
            // return the factored object
            $content = self::factorObject($object, $context, $mode);
        }
        $content = self::getChildObjects($content, $mode);
        return $content;
    }

    /**
     * Factor an object
     * 
     * @param object $object
     * @param context $context
     * @param mode $mode
     * @return objectfactory
     */
    private static function factorObject($object, $context, $mode) {
        // initialize the object factory
        $objectfactory = new ObjectFactory($object, $context, $mode);
        // factor the object
        $objectfactory->factor();
        return $objectfactory->getContent();
    }

    /**
     * Factor and cache an object
     * 
     * @param object $object
     * @param context $context
     * @param mode $mode
     * @return objectfactory
     */
    private static function factorAndCacheObject($object, $context, $mode) {
        // initialize the object factory
        $objectfactory = new ObjectFactory($object, $context, $mode);
        // factor the object
        $objectfactory->factor();
        // if the object is cacheable, store it in the cache (objects with referrals aren't cacheable, their content depends on the request url
        if ($objectfactory->getCacheable()) {
            self::storeObject($object, $context, $objectfactory->getContent());
        }
        return $objectfactory->getContent();
    }

    /**
     * Store a factored object
     * 
     * @param object $object
     * @param context $context
     * @param string $objectcontent
     * @return objectcache
     */
    private static function storeObject($object, $context, $objectcontent) {
        // create a new item for the cache
        $item = ObjectCache::newObjectCache();
        // store the item in the cache
        $item->setObject($object);
        $item->setUser(Authentication::getUser());
        $item->setContext($context);
        $item->setCacheDate(Helper::getDateTime());
        $item->setCache($objectcontent);
        $item->setOutdated(false);
        return $item;
    }

    /**
     * recursive cache call, to fetch child objects
     * 
     * @param string $objectcontent
     * @param mode $mode
     * @return string $objectcontent
     */
    public static function getChildObjects($objectcontent, $mode) {
        $matches = array();
        while (preg_match('/#([0-9]+)\|([0-9]+)#/', $objectcontent, $matches)) {
            $subobjectcontent = self::getCacheObject(Objects::getObject($matches[1]), Contexts::getContext($matches[2]), $mode);
            $objectcontent = str_replace($matches[0], $subobjectcontent, $objectcontent);
        }
        return $objectcontent;
    }

    /**
     * outdate the cache for an object after it has been changed
     * 
     * @param object $object
     */
    public static function outdateObject($object) {
        Store::outdateCachedObject($object->getId());
    }

    /**
     * outdate the cache for a layout after it has been changed
     * 
     * @param layout $layout
     */
    public static function outdateObjectsByLayout($layout) {
        Store::outdateCachedObjectsByLayout($layout->getId());
    }

    /**
     * outdate the cache for a structure after it has been changed
     * 
     * @param structure $structure
     */
    public static function outdateObjectsByStructure($structure) {
        Store::outdateCachedObjectsByStructure($structure->getId());
    }

    /**
     * outdate the cache for referrals that refer to an object after the object has
     * changed
     * 
     * @param object $object
     */
    public static function outdateReferrals($object) {
        $viewmode = Modes::getMode(Mode::VIEWMODE);
        $editmode = Modes::getMode(Mode::EDITMODE);
        // If the object containing the argument changes (most likely change: a new child is added)
        $argument = $object->getVersion($viewmode)->getArgument();
        if (!$argument->isDefault()) {
            Store::outdateReferrals($argument->getId());
        }
        $argument = $object->getVersion($editmode)->getArgument();
        if (!$argument->isDefault()) {
            Store::outdateReferrals($argument->getId());
        }
        // if the child object is changed (relevant change: change in object name)
        $argument = $object->getVersion($viewmode)->getObjectParent()->getVersion($viewmode)->getArgument();
        if (!$argument->isDefault()) {
            Store::outdateReferrals($argument->getId());
        }
        $argument = $object->getVersion($editmode)->getObjectParent()->getVersion($editmode)->getArgument();
        if (!$argument->isDefault()) {
            Store::outdateReferrals($argument->getId());
        }
    }
    
    /**
     * Delete the object from the object cache
     * 
     * @param object $object
     */
    public static function removeObjectFromCache($object) {
        Store::deleteObjectFromCache($object->getId());
    }

    /**
     * outdate the cache for content items that contain internal links to objects
     */
    public static function outdateLinkedContentItems() {
        if (!self::$internallinksoutdated) {
            Store::outdateLinkedContentItems();
            self::$internallinksoutdated = true;
        }
    }

}