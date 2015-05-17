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
 * Contains a structure
 *
 */
class Structure extends SettedEntity {
    private $structureversions = array();
    private $isbpaddefined;
    
    CONST DEFAULT_STRUCTURE = 1;
    
    /**
     * Constructor, sets the basic structure attributes
     * By setting these attribs, the existence of the structure is 
     * verified
     * 
     * @param id contains the structure id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableStructures();
        $this->loadAttributes();
    }
    
    /**
     * When a layout changes, outdate cached objects that use the layout
     * 
     * @return boolean true if success
     */
    protected function setChanged() {
        // outdate the object cache when the layout changes
        if (!$this->changed) {
            CacheObjects::outdateObjectsByStructure($this);
        }
        return parent::setChanged();
    }

    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getStructure($this->id)) {
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
        $this->isbpaddefined = $attr->isbpaddefined;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the structureversion, depending on mode and context a different structurebody is returned
     * 
     * @param mode $mode
     * @param context $context
     * @return contextedversion
     */
    public function getVersion($mode, $context) {
        if (isset($this->structureversions[$mode->getId()][$context->getId()])) {
            return $this->structureversions[$mode->getId()][$context->getId()];
        } else {
            $this->structureversions[$mode->getId()][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, $mode, $context);
            return $this->structureversions[$mode->getId()][$context->getId()];
        }
    }
    
    /**
     * Create a new version for a structure for a specific context, versions are always added
     * in edit mode, so they don't wreck the site. View mode versions are added when 
     * publishing.
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function newVersion($context) {
        // just a wrapper for newstructureversion, to prevent externals from using the $force argument
        return $this->newStructureVersion($context);
    }

    /**
     * Create a new version for a structure for a specific context, versions are always added
     * in edit mode, so they don't wreck the site. View mode versions are added when 
     * publishing.
     * 
     * @param boolean $force force the create, used when publishing and getVersion gives outdated info
     * @param context $context
     * @return boolean true if success
     */
    private function newStructureVersion($context, $force = false) {
        // check that the structure versions don't already exist
        if (!$this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getOriginal() || $force) {
            // create the new structure versions
            $editstructureversionid = Store::insertStructureVersion($this->getId(), Mode::EDITMODE, $context->getId());
            // update the structure version cache array
            $this->structureversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, Modes::getMode(Mode::EDITMODE), $context);
            return true;
        }
        return false;
    }

    /**
     * Publish a version for a structure for a specific context
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function publishVersion($context) {
        $editversion = $this->getVersion(Modes::getMode(Mode::EDITMODE), $context);
        $viewversion = $this->getVersion(Modes::getMode(Mode::VIEWMODE), $context);
        // check that the edit version to publish exists
        if ($editversion->getOriginal()) {
            // archive the view mode version (if a real one)
            if ($viewversion->getOriginal()) {
                $viewversion->setMode(Modes::getMode(Mode::ARCHIVEMODE));
            }
            // move the edit version to view
            $editversion->setMode(Modes::getMode(Mode::VIEWMODE));
            // create new edit version
            $this->newStructureVersion($context, true);
            // update the structure version cache array
            $this->structureversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, Modes::getMode(Mode::VIEWMODE), $context);
            $this->structureversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, Modes::getMode(Mode::EDITMODE), $context);
            // copy the attributes from the view to the edit version (the body is the only attribute...)
            $this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->setBody($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getBody());
            // changed
            $this->setChanged();
            return true;
        }
        return false;
    }
    
    /**
     * Cancel changes for a version for a structure for a specific context
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function cancelVersion($context) {
        $editversion = $this->getVersion(Modes::getMode(Mode::EDITMODE), $context);
        $viewversion = $this->getVersion(Modes::getMode(Mode::VIEWMODE), $context);
        // check that the edit version to publish exists
        if ($viewversion->getOriginal() && $editversion->getOriginal()) {
            // move the view version to edit
            $viewversion->setMode(Modes::getMode(Mode::EDITMODE));
            // move the edit version to archive
            $editversion->setMode(Modes::getMode(Mode::ARCHIVEMODE));
            // update the structure edit version cache array
            $this->structureversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, Modes::getMode(Mode::EDITMODE), $context);
            // publish the edit version
            $this->publishVersion($context);
            // update the structure view version cache array
            $this->structureversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, Modes::getMode(Mode::VIEWMODE), $context);
            // changed
            $this->setChanged();
            return true;
        }
        return false;
    }
    
    /**
     * Remove a version for all modes, update the structureversions array and outdate
     * the object cache for the structure/context combination
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function removeVersion($context) {
        // never remove the structure versions for the default context group - default context (without this structure version, there is no structure)
        if (!($context->isDefault() && $context->getContextGroup()->isDefault())) {
            // check that the structure version exists
            if ($this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getOriginal()) {
                // remove the structure version
                if (Store::deleteStructureVersion($this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getId())) {
                    // update the structure version cache array
                    $this->structureversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, Modes::getMode(Mode::EDITMODE), $context);
                    $this->setChanged();
                }
            }
            // check that the structure version exists
            if ($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getOriginal()) {
                // remove the structure version
                if (Store::deleteStructureVersion($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getId())) {
                    // update the structure version cache array
                    $this->structureversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STRUCTURE, Modes::getMode(Mode::VIEWMODE), $context);
                    $this->setChanged();
                }
            }
            return true;
        }
        return false;
    }

    /**
     * getter for isbpaddefined. True values are for structures that are created in the update
     * scripts belonging to new versions of bpad.
     * 
     * @return boolean isbpaddefined is this structure defined by bpad
     */
    public function getIsBpadDefined() {
        return $this->isbpaddefined;
    }
    
     /**
     * setter for isbpaddefined. True values are for structures that are created in the update
     * scripts belonging to new versions of bpad.
     * 
     * @param boolean $bool new value
     * @return boolean isbpaddefined is this layout defined by bpad
     */
    public function setIsBpadDefined($bool) {
        if (Store::setStructureIsBpadDefined($this->getId(), $bool) && $this->setChanged()) {
            $this->isbpaddefined = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
   
    /**
     * setter for the name, check whether the structure is bpad defined or not first
     * overrides the generic set name (the getter is not overridden)
     * 
     * @param newname the name
     * @return boolean true if success
     * @throws exception if the update in the store fails or if the structure is bPAD defined
     */
    public function setName($newname) {
        if (!$this->isbpaddefined) {
            parent::setName($newname);
            return true;
        }
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_IS_DEFINED_BY_BPAD) . ' @ ' . __METHOD__);
    }

    /**
     * setter for the canonical name, only for bpad defined structures, meant to be
     * used for bpad updates
     * 
     * @param newname the name
     * @return boolean true if success
     * @throws exception if the update in the store fails or if the structure isn't bPAD defined
     */
    public function setCanonicalName($newname) {
        if ($this->isbpaddefined && Validator::isCanonicalName($newname)) {
            parent::setName($newname);
            return true;
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * Is the structure used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getStructureUsed($this->getId())) {
            return true;
        }
        return false;
    }
    
    /**
     * Is the structure removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return !$this->isUsed() && !$this->getIsBpadDefined() && !($this->getId()==Structure::DEFAULT_STRUCTURE);
    }

}