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
 * Contains all styleparams, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class StyleParams {

    private static $styleparams = array();
    private static $styleparamsloaded = false;

    /**
     * get a styleparam by id, checks whether the styleparam is loaded,
     * loads the styleparam if necessary and fills it on demand with
     * further information
     * 
     * @param int the id of the styleparam to get
     * @return styleparam
     */
    public static function getStyleParam($styleparamid) {
        // return an styleparam
        if (isset(self::$styleparams[$styleparamid])) {
            return self::$styleparams[$styleparamid];
        } else {
            self::$styleparams[$styleparamid] = new StyleParam($styleparamid);
            return self::$styleparams[$styleparamid];
        }
    }

    /**
     * get all style params ordered by name for use in a list box
     * 
     * @return StyleParam[]
     */
    public static function getStyleParamList() {
        return Store::getStyleParams();
    }

    /**
     * get a styleparam by id, checks whether the styleparam is loaded,
     * loads the styleparam if necessary and fills it on demand with
     * further information
     * 
     * @return StyleParam[]
     */
    public static function getStyleParams () {
        // return all styleparams
        if (self::$styleparamsloaded) {
            return self::$styleparams;
        } else {
            $styleparamset = Store::getStyleParams();
            while ($row = $styleparamset->fetchObject()) {
                if (isset(self::$styleparams[$row->id])) {
                    // styleparam has been set already
                } else {
                    self::$styleparams[$row->id] = new StyleParam($row->id);
                }
            }
            self::$styleparamsloaded = true;
            return self::$styleparams;
        }
    }
    
    /**
     * Create a new style param
     * 
     * @return boolean true if success
     */
    public static function newStyleParam() {
        $styleparamid = Store::insertStyleParam();
        // a style param must always have an edit and view version for the default context, so create them
        // use the store, because the new version function check originality and in doing that requires
        // the default versions to be there.
        $context = Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT), Context::CONTEXT_DEFAULT);
        Store::insertStyleParamVersion($styleparamid, Mode::VIEWMODE, $context->getId());
        Store::insertStyleParamVersion($styleparamid, Mode::EDITMODE, $context->getId());
        CacheStyles::outdateStyleCache();
        return true;
    }

    /**
     * remove a styleparam
     * 
     * @param styleparam $styleparam
     * @return type
     */
    public static function removeStyleParam($styleparam) {
        Store::deleteStyleParamVersions($styleparam->getId());
        Store::deleteStyleParam($styleparam->getId());
        unset(self::$styleparams[$styleparam->getId()]);
        CacheStyles::outdateStyleCache();
        return true;
    }
    
}

?>
