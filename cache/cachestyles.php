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
 * Get cached styles from the stylecache
 *
 * @since 0.4.0
 */
class CacheStyles {

    /**
     * Retrieve a cached style
     * 
     * @return string
     */
    static public function getCacheStyles($mode, $context) {
        // only use cached items in viewmode, in edit mode get the real thing
        // check whether the item is in the cache, otherwise create a new cache item
        $content = '';
        if ($mode->getId() == mode::VIEWMODE) {
            // at the moment, there is one cache item for styles in the store,
            // maybe this item should be split based on contextgroups,
            // that design decision is not yet taken 
            if ($result = Store::getStylesheetCacheItems()) {
                if ($row = $result->fetchObject()) {
                    $cache = new StyleSheetCache($row->id);
                    // only return cached items that aren't outdated
                    if ($cache->getOutdated()) {
                        // recalc the styles
                        $styles = self::factorStyles($mode, $context);
                        // refresh the cache
                        $cache->setCache($styles);
                    }
                }
            }
            if (!is_object($cache)) {
                throw new Exception(Helper::getLang(Errors::ERROR_STYLESHEET_CACHE_CORRUPT) . ' @ ' . __METHOD__);
            }
            // return the cached content
            $content = $cache->getCache();
        } else {
            // return the factored object
            $content = self::factorStyles($mode, $context);
        }
        return $content;
    }

    static public function outdateStyleCache() {
        if ($result = Store::getStylesheetCacheItems()) {
            if ($row = $result->fetchObject()) {
                $cache = new StyleSheetCache($row->id);
                $cache->setOutdated(true);
            }
        }
    }

    /**
     * Factor the styles
     * 
     * @return stylefactory
     */
    private static function factorStyles($mode, $context) {
        // initialize the style factory
        $stylefactory = new StyleFactory($mode, $context);
        // factor the styles
        $stylefactory->factor();
        return $stylefactory->getContent();
    }

}