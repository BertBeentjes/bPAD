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
 * Contains all styles, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Styles {
    private static $styles = array();
    
    /**
     * get a style by id, checks whether the style is loaded,
     * loads the style if necessary and fills it on demand with
     * further information
     * 
     * @param styleid the id of the style to get
     * @return style
     */
    public static function getStyle ($styleid) {
        if (Validator::isNumeric($styleid)) {
            // return an style
            if (isset(self::$styles[$styleid])) {
                return self::$styles[$styleid];
            } else {
                self::$styles[$styleid] = new Style($styleid);
                return self::$styles[$styleid];
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get all style versions in an array, used to create the stylesheet
     * 
     * @param mode $mode
     * @return ContextedVersion[]
     */
    public static function getStyleVersions($mode) {
        $versions = array();
        if ($result = Store::getStyleVersionsByModeId($mode->getId())) {
            while ($row = $result->fetchObject()) {
                $versions[$row->id] = new ContextedVersion(self::getStyle($row->styleid), ContextedVersion::STYLE, $mode, Contexts::getContext($row->contextid));
            }
        }
        return $versions;
    }
        
    /**
     * Get all styles
     * 
     * @return array
     */
    public static function getStyles() {
        $result = Store::getStyles();
        return self::orderStyles($result);
    }

    /**
     * order styles
     * 
     * @param resultset $result
     * @return array
     */
    private static function orderStyles($result) {
        $styles = array();
        $names = array();
        while ($row = $result->fetchObject()) {
            $thisstyle = Styles::getStyle($row->id);
            $style = array();
            $style[] = $thisstyle->getId();
            $style[] = $thisstyle->getName();            
            $styles[] = $style;
            $names[] = $thisstyle->getName();
            unset($style);
        }
        array_multisort($names, SORT_ASC, $styles);
        return $styles;
    }

    /**
     * Get all styles by style type
     * 
     * @param string styletype
     * @return array
     */
    public static function getStylesByStyleType($styletype) {
        $result = Store::getStylesByStyleType($styletype);
        return self::orderStyles($result);
    }

    /**
     * Get a style by its name
     * 
     * @param string $name
     * @return style
     */
    public static function getStyleByName($name) {
        $result = Store::getStyleByName($name);
        if (is_object($result)) {
            if ($row = $result->fetchObject()) {
                return Styles::getStyle($row->id);
            }
        }
    }

    /**
     * Get all styles by set id, include a specific style (used for list boxes)
     * 
     * @param string $styletype
     * @param set $set
     * @param style $style
     * @return array
     */
    public static function getStylesBySet($styletype, $set, $style) {
        $result = Store::getStylesBySetId($styletype, $set->getId(), $style->getId());
        return self::orderStyles($result);
    }

    /**
     * Create a new style
     * 
     * @return type
     */
    public static function newStyle() {
        $styleid = Store::insertStyle();
        // a style must always have an edit and view version for the default context, so create them
        // use the store, because the new version function check originality and in doing that requires
        // the default versions to be there.
        $context = Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT), Context::CONTEXT_DEFAULT);
        Store::insertStyleVersion($styleid, Mode::VIEWMODE, $context->getId());
        Store::insertStyleVersion($styleid, Mode::EDITMODE, $context->getId());
        CacheStyles::outdateStyleCache();
        return Styles::getStyle($styleid);
    }

    /**
     * remove a style, you can only remove styles that aren't used, and you can't remove styles defined by bPAD
     * 
     * @param style $style
     * @return type
     */
    public static function removeStyle($style) {
        if ($style->isRemovable()) {
            Store::deleteStyleVersions($style->getId());
            Store::deleteStyle($style->getId());
            unset(self::$styles[$style->getId()]);
            return true;
        }
        CacheStyles::outdateStyleCache();
        return false;
    }
    
}