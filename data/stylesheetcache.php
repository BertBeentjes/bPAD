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
 * The stylesheet cache contains a cached version of the active view stylesheet, with
 * all style params resolved.
 *
 * @since 0.4.0
 */
class StyleSheetCache {
    private $id; // the id
    private $cachedate; // the date this cache item was created
    private $cache; // the cached item itself
    private $outdated; // boolean value, true if the cache item is outdated and needs to be refreshed
    
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
        if ($result = Store::getStylesheetCache($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * init the stylesheet cache item
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->cachedate = $attr->cachedate;
        $this->cache = $attr->cache;
        $this->outdated = $attr->outdated;
        return true;
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
        if (Store::setStylesheetCacheCacheDate($this->id, $newcachedate)) {
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
        if (Store::setStylesheetCacheCache($this->id, $newcache)) {
            $this->cache = $newcache;
            // and the cache is no longer outdated
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
     * is the cache item outdated?
     * 
     * @return boolean true if outdated
     */
    public function isOutdated() {
        return ($this->getOutdated() == 1);
    }
    
    /**
     * set outdated for this cache item
     * 
     * @param bool $newbool
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setOutdated($newbool) {
        if (Store::setStylesheetCacheOutdated($this->id, $newbool)) {
            $this->outdated = $newbool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}

?>
