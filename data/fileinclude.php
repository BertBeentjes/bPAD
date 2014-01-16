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
 * Code snippets or css snippets to include in the page as a file
 *
 * @since 0.4.0
 */
class FileInclude extends NamedEntity {
    private $mimetype; // the mime type, used to set the correct return type
    
    private $fileincludeversions = array(); // the versions for this file include
    
    /**
     * Construct the file include
     * 
     * @param int the id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableFileIncludes();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getFileInclude($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * Initialize the attributes
     * 
     * @return boolean true if success,
     */
    protected function initAttributes($attr) {
        $this->mimetype = $attr->mimetype;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the file include version, depending on mode 
     * 
     * @param mode $mode
     * @return modedversion
     */
    public function getVersion($mode) {
        if (isset($this->fileincludeversions[$mode->getId()])) {
            return $this->fileincludeversions[$mode->getId()];
        } else {
            $this->fileincludeversions[$mode->getId()] = new ModedVersion($this, ModedVersion::FILE_INCLUDE, $mode);
            return $this->fileincludeversions[$mode->getId()];
        }
    }

    /**
     * Get the mime type of the file include
     * 
     * @return string type
     */
    public function getMimeType() {
        return $this->mimetype;
    }
    
    /**
     * Set the mime type value for the file include
     * 
     * @param string $newmimetype the new value for the type
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setMimeType($newmimetype) {
        if (Store::setFileIncludeType($this->id, $newmimetype) && $this->setChanged()) {
            $this->mimetype = $newmimetype;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}
?>
