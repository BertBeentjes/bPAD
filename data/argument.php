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
 * An argument is used to couple a referral to objects, usually #pn# type layouts.
 *
 * @since 0.4.0
 */
class Argument extends NamedEntity {
    const DEFAULT_ARGUMENT = 1;
    const CREATE_ARGUMENT = 2;
    const DEFAULT_SHOW_ALL = -2;
    const DEFAULT_SHOW_HIGHEST = -1;
    const DEFAULT_SHOW_LOWEST = 0;
    
    /**
     * get the argument from the store
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableArguments();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes for the argument
     * 
     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getArgument($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * check for the default argument (id 1, name _default)
     */
    public function isDefault() {
        if ($this->getId() == self::DEFAULT_ARGUMENT) {
            return true;
        }
        return false;
    }
    
    /**
     * check for the create argument (id 2, name _create)
     */
    public function isCreate() {
        if ($this->getId() == self::CREATE_ARGUMENT) {
            return true;
        }
        return false;
    }
    
}

?>
