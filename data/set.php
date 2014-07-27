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
 * A set is used to group templates, styles, structures, layouts together for
 * the user of a bPAD site and to restrict the application of styles to certain
 * places. A set can be specified for objects or positions.
 *
 * @since 0.4.0
 */
class Set extends NamedEntity {
    private $isbpaddefined;

    const DEFAULT_SET = 1;

    /**
     * Constructor, sets the basic set attributes
     * By setting these attribs, the existence of the set is 
     * verified
     * 
     * @param id contains the set id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableSets();
        $this->loadAttributes();
    }

    /**
     * Is the set the default set?
     * 
     * @return boolean
     */
    public function isDefault() {
        return $this->getId() == self::DEFAULT_SET;
    }

    /**
     * Is the set the default set?
     * 
     * @return boolean
     */
    public function getIsBpadDefined() {
        return $this->isbpaddefined;
    }

    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getSet($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->isbpaddefined = (bool) $attr->isbpaddefined;
                $this->initAttributes($attr);
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }

    /**
     * Is the set used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getSetUsed($this->getId())) {
            return true;
        }
        return false;
    }
    
    /**
     * Is the set removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return !$this->isUsed() && !$this->getIsBpadDefined() && !($this->getId()==Set::DEFAULT_SET);
    }
    
}