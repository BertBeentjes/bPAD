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
 * The basic page snippet, used to build a page. Each context group mus have 
 * a snippet
 *
 * @since 0.4.0
 */
class Snippet extends NamedEntity {
    private $contextgroup; // the context group the snippet belongs to
    private $mimetype; // the mime type of the content in the snippet
    
    private $snippetversions = array(); // the versions for this snippet
    
    /**
     * Construct the snippet
     * 
     * @param int the id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableSnippets();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getSnippet($this->id)) {
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
        $this->contextgroup = ContextGroups::getContextGroup($attr->contextgroupid);
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the snippet version, depending on mode 
     * 
     * @param mode $mode
     * @return type
     */
    public function getVersion($mode) {
        if (isset($this->snippet[$mode->getId()])) {
            return $this->snippet[$mode->getId()];
        } else {
            $this->snippet[$mode->getId()] = new ModedVersion($this, ModedVersion::SNIPPET, $mode);
            return $this->snippet[$mode->getId()];
        }
    }

    /**
     * Get the context group of the snippet
     * 
     * @return contextgroup
     */
    public function getContextGroup() {
        return $this->contextgroup;
    }
    
    /**
     * Set the context group value for the snippet
     * 
     * @param contextgroup $newcontextgroup the new value for the context group
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setContextGroup($newcontextgroup) {
        if (Store::setSnippetContextGroupId($this->id, $newcontextgroup->getId()) && $this->setChanged()) {
            $this->contextgroup = $newcontextgroup;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the mime type of the snippet
     * 
     * @return string mime type
     */
    public function getMimeType() {
        return $this->mimetype;
    }
    
    /**
     * Set the mime type value for the snippet
     * 
     * @param string $newmimetype the new value for the mime type
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setMimeType($newmimetype) {
        if (Store::setSnippetMimeType($this->id, $newmimetype) && $this->setChanged()) {
            $this->mimetype = $newmimetype;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}
?>
