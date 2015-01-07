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
 * A named entity is a stored entity, with added the name and set
 *
 * @since 0.4.0
 */
abstract class NamedEntity extends StoredEntity {

    protected $name;

    /**
     * init the name
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes($attr) {
        $this->name = $attr->name;
        parent::initAttributes($attr);
        return true;
    }

    /**
     * getter for the name
     * 
     * @return string the name
     */
    public function getName() {
        // localize if possible
        return Helper::getLang($this->name);
    }

    /**
     * getter for the unlocalized name
     * 
     * @return string the name
     */
    public function getCanonicalName() {
        return $this->name;
    }

    /**
     * setter for the name
     * For some objects, this attribute can also be set by copyAttributes!
     * 
     * @param newname the name
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setName($newname) {
        if (Store::setEntityName($this->tablename, $this->id, $newname) && $this->setChanged()) {
            $this->name = $newname;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}
