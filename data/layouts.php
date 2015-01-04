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
 * Contains all layouts, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Layouts {

    private static $layouts = array();

    /**
     * get a layout by id, checks whether the layout is loaded,
     * loads the layout if necessary and fills it on demand with
     * further information
     * 
     * @param layoutid the id of the layout to get
     * @return layout
     */
    public static function getLayout($layoutid) {
        if (Validator::isNumeric($layoutid)) {
            // return an layout
            if (isset(self::$layouts[$layoutid])) {
                return self::$layouts[$layoutid];
            } else {
                self::$layouts[$layoutid] = new Layout($layoutid);
                return self::$layouts[$layoutid];
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get all layouts
     * 
     * @return resultset
     */
    public static function getLayouts() {
        return Store::getLayouts();
    }

    /**
     * Get all layouts by set id, include a specific layout (used for list boxes)
     * 
     * @param set $set
     * @param layout $layout
     * @return resultset
     */
    public static function getLayoutsBySet($set, $layout) {
        return Store::getLayoutsBySetId($set->getId(), $layout->getId());
    }

    /**
     * Get a layout by its name
     * 
     * @param string $name
     * @return layout
     */
    public static function getLayoutByName($name) {
        $result = Store::getLayoutByName($name);
        if ($row = $result->fetchObject()) {
            return Layouts::getLayout($row->id);
        }
    }

    /**
     * Create a new layout
     * 
     * @return type
     */
    public static function newLayout() {
        $layoutid = Store::insertLayout();
        // a layout must always have an edit and view version for the default context, so create them
        // use the store, because the new version function check originality and in doing that requires
        // the default versions to be there.
        $context = Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT), Context::CONTEXT_DEFAULT);
        Store::insertLayoutVersion($layoutid, Mode::VIEWMODE, $context->getId());
        Store::insertLayoutVersion($layoutid, Mode::EDITMODE, $context->getId());
        return Layouts::getLayout($layoutid);
    }

    /**
     * remove a layout, you can only remove layouts that aren't used, and you can't remove layouts defined by bPAD
     * 
     * @param layout $layout
     * @return type
     */
    public static function removeLayout($layout) {
        if ($layout->isRemovable()) {
            Store::deleteLayoutVersions($layout->getId());
            Store::deleteLayout($layout->getId());
            unset(self::$layouts[$layout->getId()]);
            return true;
        }
        return false;
    }

}