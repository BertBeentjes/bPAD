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
 * Contains all structures, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Structures {

    private static $structures = array();
    private static $structureidsbyname = array();

    /**
     * get a structure by id, checks whether the structure is loaded,
     * loads the structure if necessary and fills it on demand with
     * further information
     * 
     * @param structureid the id of the structure to get
     * @return structure
     */
    public static function getStructure($structureid) {
        if (Validator::isNumeric($structureid)) {
            // return an structure
            if (isset(self::$structures[$structureid])) {
                return self::$structures[$structureid];
            } else {
                self::$structures[$structureid] = new Structure($structureid);
                return self::$structures[$structureid];
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get a structure by structure name, store in cache for later use, 
     * structures can be called quite often while formatting content items
     * 
     * @param string $name
     * @return structure
     */
    public static function getStructureByName($name) {
        if (array_key_exists($name, self::$structureidsbyname)) {
            return self::getStructure(self::$structureidsbyname[$name]);
        } else {
            if ($result = Store::getStructureIdByName($name)) {
                if ($row = $result->fetchObject()) {
                    self::$structureidsbyname[$name] = $row->id;
                    return self::getStructure($row->id);
                }
            }
        }
    }

    /**
     * Get all structures
     * 
     * @return resultset
     */
    public static function getStructures() {
        return Store::getStructures();
    }

    /**
     * Get all structures by set id, include a specific structure (used for list boxes)
     * 
     * @param set $set
     * @param structure $structure
     * @return resultset
     */
    public static function getStructuresBySet($set, $structure) {
        return Store::getStructuresBySetId($set->getId(), $structure->getId());
    }

    /**
     * Create a new structure
     * 
     * @return type
     */
    public static function newStructure() {
        $structureid = Store::insertStructure();
        // a structure must always have an edit and view version for the default context, so create them
        // use the store, because the new version function check originality and in doing that requires
        // the default versions to be there.
        $context = Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT), Context::CONTEXT_DEFAULT);
        Store::insertStructureVersion($structureid, Mode::VIEWMODE, $context->getId());
        Store::insertStructureVersion($structureid, Mode::EDITMODE, $context->getId());

        return true;
    }

    /**
     * remove a structure, you can only remove structures that aren't used, and you can't remove structures defined by bPAD
     * 
     * @param structure $structure
     * @return type
     */
    public static function removeStructure($structure) {
        if ($structure->isRemovable()) {
            Store::deleteStructureVersions($structure->getId());
            Store::deleteStructure($structure->getId());
            unset(self::$structures[$structure->getId()]);
            return true;
        }
        return false;
    }

}

?>
