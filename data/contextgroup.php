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
 * A context group defines a group of contexts, used to create content for 
 * a specific device or screen size or frontend (rss reader, app, etc)
 *
 * @since 0.4.0
 */
class ContextGroup extends NamedEntity {
    const CONTEXTGROUP_DEFAULT = 1;
    const CONTEXTGROUP_MOBILE = 2;
    const CONTEXTGROUP_METADATA = 3;
    const CONTEXTGROUP_SITEMAP = 4;
    
    /**
     * Constructor for a context group
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableContextGroups();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getContextGroup($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }

    /**
     * Get the short name of a context group, for use in html
     * 
     * @return string
     */
    public function getShortName() {
        // localize if possible
        $name = Helper::getLang($this->name . '_SHORT');
        if ($name == $this->name . '_SHORT') {
            $name = $this->name;
        }
        return $name;
    }
    
    /**
     * Is the default context group or not
     * 
     * @return boolean true if default
     */
    public function isDefault() {
        return $this->getId() == self::CONTEXTGROUP_DEFAULT;
    }

}