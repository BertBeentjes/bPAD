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
 * A value list entity contains values used elsewhere, like modes or types.
 * 
 * For objects based upon the value list entity, loaders aren't necessary, in
 * general the names of these value lists are shown only in specific
 * places in admin interfaces.
 *
 * @since 0.4.0
 */
abstract class ValueListEntity {
    protected $id;
    protected $name;
    protected $tablename;
    
    /**
     * initialize the attributes
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes () {
        if ($result = Store::getValueListEntityName($this->tablename, $this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->name =  $attr->name;
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }

    /**
     * getter for the id, only for testing whether things are identical
     * 
     * @return int the id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * getter for the name
     * 
     * @return string the name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * setter for the name
     * 
     * @param newname the name
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setName($newname) {
        if (Store::setEntityName($this->tablename, $this->id,  $newname)) {
            $this->name = $newname;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}