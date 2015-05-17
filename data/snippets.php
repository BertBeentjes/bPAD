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
 * Contains all snippets, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Snippets {
    private static $snippets = array();
    
    /**
     * get a snippet by id, checks whether the snippet is loaded,
     * loads the snippet if necessary and fills it on demand with
     * further information
     * 
     * @param snippetid the id of the snippet to get
     * @return snippet
     */
    public static function getSnippet ($snippetid) {
        // return an snippet
        if (isset(self::$snippets[$snippetid])) {
            return self::$snippets[$snippetid];
        } else {
            self::$snippets[$snippetid] = new Snippet($snippetid);
            return self::$snippets[$snippetid];
        }
    }
    
    /**
     * Get a snippet by context group
     * 
     * @param contextgroup $contextgroup
     * @return snippet
     */
    public static function getSnippetByContextGroup($contextgroup) {
        if ($result = Store::getSnippetIdByContextGroupId($contextgroup->getId())) {
            if ($row = $result->fetchObject()) {
                return self::getSnippet($row->id);
            }
        }
    }
    
    /**
     * Get all snippets
     * 
     * @return resultset
     */
    public static function getSnippets () {
        return Store::getSnippets();
    }
    
    /**
     * Create a new snippet
     * 
     * @return type
     */
    public static function newSnippet() {
        $snippetid = Store::insertSnippet();
        // a snippet must always have an edit and view version
        Store::insertSnippetVersion($snippetid, Mode::VIEWMODE);
        Store::insertSnippetVersion($snippetid, Mode::EDITMODE);

        return true;
    }

    /**
     * remove a snippet
     * 
     * @param snippet $snippet
     * @return type
     */
    public static function removeSnippet($snippet) {
        Store::deleteSnippetVersions($snippet->getId());
        Store::deleteSnippet($snippet->getId());
        unset(self::$snippets[$snippet->getId()]);
        return true;
    }

}