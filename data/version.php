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
 * The version of bPAD that is running
 *
 * @since 0.4.0
 */
class Version {
    private $id; // the id
    private $version; // the version
    private $releasedate; // the release date
    private $releaseinfo; // info on the changes in the release
    
    /**
     * Constructs the version and loads the attributes 
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes for the cache item
     * 
     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getVersion($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * init the version
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->version= $attr->version;
        $this->releasedate = $attr->releasedate;
        $this->releaseinfo = $attr->releaseinfo;
        return true;
    }

    /**
     * Get the version
     * 
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }
    
    /**
     * set the version for this version
     * 
     * @param string $newversion
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setVersion($newversion) {
        if (Store::setVersionVersion($this->id, $newversion)) {
            $this->version = $newversion;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the release date
     * 
     * @return datetimestring
     */
    public function getReleaseDate() {
        return $this->releasedate;
    }
    
    /**
     * set the release date for this version
     * 
     * @param datetimestring $newreleasedate
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setReleaseDate($newreleasedate) {
        if (Store::setVersionReleaseDate($this->id, $newreleasedate)) {
            $this->releasedate = $newreleasedate;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the release info
     * 
     * @return infotimestring
     */
    public function getReleaseInfo() {
        return $this->releaseinfo;
    }
    
    /**
     * set the release info for this version
     * 
     * @param infotimestring $newreleaseinfo
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setReleaseInfo($newreleaseinfo) {
        if (Store::setVersionReleaseInfo($this->id, $newreleaseinfo)) {
            $this->releaseinfo = $newreleaseinfo;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
}