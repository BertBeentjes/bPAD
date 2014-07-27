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
 * A setted entity is a stored entity with a name and a set
 *
 * @since 0.4.0
 */
abstract class SettedEntity extends NamedEntity{
    protected $set;

    /**
     * init the set
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->set = Sets::getSet($attr->setid);
        parent::initAttributes($attr);
        return true;
    }

    /**
     * getter for the set
     * 
     * @return set
     */
    public function getSet() {
        return $this->set;
    }

    /**
     * setter for the set
     * 
     * @param set the new set
     * @return boolean  if success
     * @throws exception if the update in the store fails
     */
    public function setSet($newset) {
        if (Store::setEntitySetId($this->tablename, $this->id, $newset->getId()) && $this->setChanged()) {
            $this->set = $newset;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}