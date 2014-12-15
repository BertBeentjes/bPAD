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

    const DEFAULT_FILE_INCLUDE = 1;

    private $mimetype; // the mime type, used to set the correct return type
    private $comment; // the comment, e.g. can contain info on how to update the included file
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
        $this->comment = $attr->comment;
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

    /**
     * Get the comment of the file include
     * 
     * @return string type
     */
    public function getComment() {
        return $this->comment;
    }
    
    /**
     * Set the comment for the file include
     * 
     * @param string $newcomment the new value for the comment
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setComment($newcomment) {
        if (Store::setFileIncludeComment($this->id, $newcomment) && $this->setChanged()) {
            $this->comment = $newcomment;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Publish the edit version to the view version
     * 
     * @return boolean success or not
     */
    public function publishVersion() {
        // move viewmode to archive
        $this->getVersion(Modes::getMode(Mode::VIEWMODE))->setMode(Modes::getMode(Mode::ARCHIVEMODE));
        // move editmode to view
        $this->getVersion(Modes::getMode(Mode::EDITMODE))->setMode(Modes::getMode(Mode::VIEWMODE));        
        // create new edit mode version
        $newversionid = Store::insertFileIncludeVersion($this->getId(), Mode::EDITMODE);
        // reset the version cache
        unset($this->fileincludeversions[Mode::VIEWMODE]);
        unset($this->fileincludeversions[Mode::EDITMODE]);        
        // copy view mode attributes to edit mode
        $this->getVersion(Modes::getMode(Mode::EDITMODE))->setBody($this->getVersion(Modes::getMode(Mode::VIEWMODE))->getBody());
        // set changed
        $this->setChanged();
        return true;
    }
    
}