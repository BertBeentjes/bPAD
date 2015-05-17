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
 * Contains a layout
 *
 */
class Layout extends SettedEntity {

    private $layoutversions = array(); // the versions of this layout
    private $isbpaddefined; // is the layout defined by bpad or by the user

    const DEFAULT_LAYOUT = 1;

    /**
     * Constructor, sets the basic layout attributes
     * By setting these attribs, the existence of the layout is 
     * verified
     * 
     * @param id contains the layout id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableLayouts();
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
            CacheObjects::outdateObjectsByLayout($this);
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
        if ($result = Store::getLayout($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }

    /**
     * Initialize the attributes
     * 
     * @return boolean true if success,
     */
    protected function initAttributes($attr) {
        $this->isbpaddefined = (bool)$attr->isbpaddefined;
        parent::initAttributes($attr);
        return true;
    }

    /**
     * getter for the layoutversion, depending on mode and context a different layoutbody is returned.
     * 
     * @param mode $mode
     * @param context $context
     * @return contextedversion
     */
    public function getVersion($mode, $context) {
        if (isset($this->layoutversions[$mode->getId()][$context->getId()])) {
            return $this->layoutversions[$mode->getId()][$context->getId()];
        } else {
            $this->layoutversions[$mode->getId()][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, $mode, $context);
            return $this->layoutversions[$mode->getId()][$context->getId()];
        }
    }

    /**
     * Create a new version for a layout for a specific context, versions are always added
     * in edit mode, so they don't wreck the site. View mode versions are added when 
     * publishing.
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function newVersion($context) {
        // just a wrapper for newlayoutversion, to prevent externals from using the $force argument
        return $this->newLayoutVersion($context);
    }

    /**
     * Create a new version for a layout for a specific context, versions are always added
     * in edit mode, so they don't wreck the site. View mode versions are added when 
     * publishing.
     * 
     * @param boolean $force force the create, used when publishing and getVersion gives outdated info
     * @param context $context
     * @return boolean true if success
     */
    private function newLayoutVersion($context, $force = false) {
        // check that the layout versions don't already exist
        if (!$this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getOriginal() || $force) {
            // create the new layout versions
            $editlayoutversionid = Store::insertLayoutVersion($this->getId(), Mode::EDITMODE, $context->getId());
            // update the layout version cache array
            $this->layoutversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, Modes::getMode(Mode::EDITMODE), $context);
            return true;
        }
        return false;
    }

    /**
     * Publish a version for a layout for a specific context
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
            $this->newLayoutVersion($context, true);
            // update the layout version cache array
            $this->layoutversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, Modes::getMode(Mode::VIEWMODE), $context);
            $this->layoutversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, Modes::getMode(Mode::EDITMODE), $context);
            // copy the attributes from the view to the edit version (the body is the only attribute...)
            $this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->setBody($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getBody());
            // changed
            $this->setChanged();
            return true;
        }
        return false;
    }

    /**
     * Cancel changes for a version for a layout for a specific context
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
            // update the layout edit version cache array
            $this->layoutversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, Modes::getMode(Mode::EDITMODE), $context);
            // publish the edit version
            $this->publishVersion($context);
            // update the layout view version cache array
            $this->layoutversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, Modes::getMode(Mode::VIEWMODE), $context);
            // changed
            $this->setChanged();
            return true;
        }
        return false;
    }

    /**
     * Remove a version for all modes, update the layoutversions array and outdate
     * the object cache for the layout/context combination
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function removeVersion($context) {
        // never remove the layout versions for the default context group - default context (without this layout version, there is no layout)
        if (!($context->isDefault() && $context->getContextGroup()->isDefault())) {
            // check that the layout version exists
            if ($this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getOriginal()) {
                // remove the layout version
                if (Store::deleteLayoutVersion($this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getId())) {
                    // update the layout version cache array
                    $this->layoutversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, Modes::getMode(Mode::EDITMODE), $context);
                    $this->setChanged();
                }
            }
            // check that the layout version exists
            if ($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getOriginal()) {
                // remove the layout version
                if (Store::deleteLayoutVersion($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getId())) {
                    // update the layout version cache array
                    $this->layoutversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::LAYOUT, Modes::getMode(Mode::VIEWMODE), $context);
                    $this->setChanged();
                }
            }
            return true;
        }
        return false;
    }

    /**
     * getter for isbpaddefined. True values are for layouts that are created in the update
     * scripts belonging to new versions of bpad.
     * 
     * @return boolean isbpaddefined is this layout defined by bpad
     */
    public function getIsBpadDefined() {
        return $this->isbpaddefined;
    }

    /**
     * setter for isbpaddefined. True values are for layouts that are created in the update
     * scripts belonging to new versions of bpad.
     * 
     * @param boolean $bool new value
     * @return boolean isbpaddefined is this layout defined by bpad
     */
    public function setIsBpadDefined($bool) {
        if (Store::setLayoutIsBpadDefined($this->getId(), $bool) && $this->setChanged()) {
            $this->isbpaddefined = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * checks whether the layout is of #pn# type in the default context
     * 
     * @return boolean true if #pn#
     */
    public function isPNType() {
        $layoutversion = $this->getVersion(Modes::getMode(Mode::VIEWMODE), Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT), Context::CONTEXT_DEFAULT));
        if (strpos($layoutversion->getBody(), '#pn#') === false) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
     * setter for the name, check whether the layout is bpad defined or not first,
     * for bpad defined layouts the name isn't editable (it's a logical key used over
     * multiple installs, where the id may vary)
     * 
     * @param newname the name
     * @return boolean true if success
     * @throws exception if the update in the store fails or if the layout is bPAD defined
     */
    public function setName($newname) {
        if (!$this->isbpaddefined) {
            parent::setName($newname);
            return true;
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_IS_DEFINED_BY_BPAD) . ' @ ' . __METHOD__);
    }

    /**
     * setter for the canonical name, only for bpad defined layouts, meant to be
     * used for bpad updates
     * 
     * @param newname the name
     * @return boolean true if success
     * @throws exception if the update in the store fails or if the layout isn't bPAD defined
     */
    public function setCanonicalName($newname) {
        if ($this->isbpaddefined && Validator::isCanonicalName($newname)) {
            parent::setName($newname);
            return true;
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * Is the layout used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getLayoutUsed($this->getId())) {
            return true;
        }
        return false;
    }

    /**
     * Is the layout removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return !$this->isUsed() && !$this->getIsBpadDefined() && !($this->getId() == Layout::DEFAULT_LAYOUT);
    }

    /**
     * Return all positions for a non-pntype layout. Based upon the default layout.
     * Only when positions are numbered continuously, starting at 1, they are counted.
     * 
     * @param mode $mode
     * @return int[]
     */
    public function getAllPositions($mode) {
        if (!$this->isPNType()) {
            $counter = 1;
            $allpositions = array();
            while (strpos($this->getVersion($mode, Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT), Context::CONTEXT_DEFAULT))->getBody(), Terms::object_p($counter)) > -1) {
                $allpositions[] = $counter;
                $counter = $counter + 1;
            }
            return $allpositions;
        }
    }

}