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
 * Contains lss versions, packaged scripts that when execute update all layouts,
 * styles, structures and related material in the database.
 *
 * @since 0.4.0
 */
class LSSVersion {
    private $id; // the id
    private $createdate; // the date the version was created
    private $versiontext; // the version itself, stored as an encrypted text file
    
    /**
     * get the lss version record from the store
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes for the lss version record
     * 
     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getLSSVersion($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * init the lss version record
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->createdate =  $attr->createdate;
        $this->versiontext =  $attr->versiontext;
        return true;
    }

    /**
     * The date/time this version was created
     * 
     * @return datetime
     */
    public function getCreateDate() {
        return $this->createdate;
    }
    
    /**
     * set the date/time this version was created
     * 
     * @param datetime $newcreatedate
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setCreateDate($newcreatedate) {
        if (Store::setLSSVersionCreateDate($this->id,  $newcreatedate)) {
            $this->createdate = $newcreatedate;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the script for this version
     * 
     * @return int
     */
    public function getVersionText() {
        return $this->versiontext;
    }
    
    /**
     * set the script for this version
     * 
     * @param datetime $newversiontext
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setVersionText($newversiontext) {
        if (Store::setLSSVersionVersionText($this->id,  $newversiontext)) {
            $this->versiontext = $newversiontext;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}