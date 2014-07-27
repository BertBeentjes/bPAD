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
 * The object cache contains cached objects, used to present objects fast to the
 * user. The object cache can be outdated on an individual level, based upon
 * the changes made by users to the content. 
 * 
 * The cache stores versions of objects that depend on: context, user
 * 
 * Cached versions are created on first access by the user, based upon the 
 * context the object is viewed in
 * 
 * Objects are only cached in view mode, not in edit mode
 *
 * @since 0.4.0
 */
class ObjectCache {
    private $id; // the id
    private $object; // the object stored in the cache
    private $context; // the context for this cache item
    private $cachedate; // the date this cache item was created
    private $cache; // the cached item itself
    private $outdated; // boolean value, true if the cache item is outdated and needs to be refreshed
    private $user; // the user this item is cached for
    
    /**
     * get the cache record from the store
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes for the cache item
     *
     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getObjectCache($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * init the object cache item
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->object =  Objects::getObject($attr->objectid);
        $this->context =  Contexts::getContext($attr->contextid);
        $this->cachedate = $attr->cachedate;
        $this->cache = $attr->cache;
        $this->outdated = (bool) $attr->outdated;
        $this->userid = $attr->userid;
        return true;
    }

    /**
     * Get the object for this cache item
     * 
     * @return object
     */
    public function getObject() {
        return $this->object;
    }
    
    /**
     * set the object for this cache item
     * 
     * @param object
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setObject($newobject) {
        if (Store::setObjectCacheObjectId($this->id, $newobject->getId())) {
            $this->object = $newobject;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the context for this cache item
     * 
     * @return context
     */
    public function getContext() {
        return $this->context;
    }
    
    /**
     * set the context for this cache item
     * 
     * @param context
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setContext($newcontext) {
        if (Store::setObjectCacheContextId($this->id, $newcontext->getId())) {
            $this->context = $newcontext;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the cache date for this cache item
     * 
     * @return int
     */
    public function getCacheDate() {
        return $this->cachedate;
    }
    
    /**
     * set the cache date for this cache item
     * 
     * @param datetimestring $newcachedate
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setCacheDate($newcachedate) {
        if (Store::setObjectCacheCacheDate($this->id, $newcachedate)) {
            $this->cachedate = $newcachedate;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the cache for this cache item
     * 
     * @return int
     */
    public function getCache() {
        return $this->cache;
    }
    
    /**
     * set the cache for this cache item
     * 
     * @param string cache
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setCache($newcache) {
        if (Store::setObjectCacheCache($this->id, $newcache)) {
            $this->cache = $newcache;
            // the cache is now no longer outdated
            $this->setOutdated(false);
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get outdated for this cache item
     * 
     * @return int
     */
    public function getOutdated() {
        return $this->outdated;
    }
    
    /**
     * set outdated for this cache item
     * 
     * @param bool $newbool
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setOutdated($newbool) {
        if (Store::setObjectCacheOutdated($this->id, $newbool)) {
            $this->outdated = $newbool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the user for this cache item
     * 
     * @return user
     */
    public function getUser() {
        return $this->user;
    }
    
    /**
     * set the user for this cache item
     * 
     * @param user
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setUser($newuser) {
        if (Store::setObjectCacheUserId($this->id, $newuser->getId())) {
            $this->user = $newuser;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * create a new object cache item
     * 
     * @return objectcache the new objectcache item
     */
    public static function newObjectCache() {
        return new ObjectCache(Store::insertObjectCache());
    }

}