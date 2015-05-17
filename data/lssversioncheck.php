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
 * Check whether the lss version is the most recent or not. LSS versions are
 * administered on a bPAD master site. Any site can be it's own master, or 
 * be slave to a master specified in the settings.
 *
 * @since 0.4.0
 */
class LSSVersionCheck {
    private $id; // the id of the check record
    private $checkdate; // the date/time of the check
    private $installedversion; // the installed version
    
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
        if ($result = Store::getLSSVersionCheck($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * init the lss version check record
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->checkdate =  $attr->checkdate;
        $this->installedversion =  $attr->installedversion;
        return true;
    }
    
    /**
     * The date/time this check was done
     * 
     * @return datetime
     */
    public function getCheckDate() {
        return $this->checkdate;
    }
    
    /**
     * set the date/time this version check took place
     * 
     * @param datetime $newcheckdate
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setCheckDate($newcheckdate) {
        if (Store::setLSSVersionCheckCheckDate($this->id,  $newcheckdate)) {
            $this->checkdate = $newcheckdate;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the installed version when the check was finished
     * 
     * @return int
     */
    public function getInstalledVersion() {
        return $this->installedversion;
    }
    
    /**
     * set the installed version for this check
     * 
     * @param datetime $newcheckdate
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setInstalledVersion($newinstalledversion) {
        if (Store::setLSSVersionCheckInstalledVersion($this->id,  $newinstalledversion)) {
            $this->installedversion = $newinstalledversion;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}