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
 * Style parameters can be used in styles as constants that insert certain style constructs, like colours or margins
 *
 * @since 0.4.0
 */
class StyleParam extends NamedEntity {
    private $styleparamversions = array(); // the array with the versions of this style parameter
    
    /**
     * Get the style parameter from the store
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableStyleParams();
        $this->loadAttributes();
    }
    
    protected function setChanged($force = false) {
        CacheStyles::outdateStyleCache();
        return parent::setChanged($force);
    }
    
    /**
     * Load the attributes for the style parameter 
     * 
     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getStyleParam($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->getId() . ' @ ' . __METHOD__);
    }
    
    /**
     * init the style parameter
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the style param version, depending on mode and context a different style param body is returned
     * 
     * @param mode $mode
     * @param context $context
     * @return contextedversion
     */
    public function getVersion($mode, $context) {
        if (isset($this->styleparamversions[$mode->getId()][$context->getId()])) {
            return $this->styleparamversions[$mode->getId()][$context->getId()];
        } else {
            $this->styleparamversions[$mode->getId()][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, $mode, $context);
            return $this->styleparamversions[$mode->getId()][$context->getId()];
        }
    }
    
    /**
     * Create a new version for a style for a specific context, versions are always added
     * in edit mode, so they don't wreck the site. View mode versions are added when 
     * publishing.
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function newVersion($context) {
        // just a wrapper for newstyleversion, to prevent externals from using the $force argument
        return $this->newStyleParamVersion($context);
    }

    /**
     * Create a new version for a style param for a specific context, versions are always added
     * in edit mode, so they don't wreck the site. View mode versions are added when 
     * publishing.
     * 
     * @param boolean $force force the create, used when publishing and getVersion gives outdated info
     * @param context $context
     * @return boolean true if success
     */
    private function newStyleParamVersion($context, $force = false) {
        // check that the style versions don't already exist
        if (!$this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getOriginal() || $force) {
            // create the new style versions
            $editstyleversionid = Store::insertStyleParamVersion($this->getId(), Mode::EDITMODE, $context->getId());
            // update the style version cache array
            $this->styleparamversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, Modes::getMode(Mode::EDITMODE), $context);
            return true;
        }
        return false;
    }

    /**
     * Publish a version for a style for a specific context
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
            $this->newStyleParamVersion($context, true);
            // update the style version cache array
            $this->styleparamversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, Modes::getMode(Mode::VIEWMODE), $context);
            $this->styleparamversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, Modes::getMode(Mode::EDITMODE), $context);
            // copy the attributes from the view to the edit version (the body is the only attribute...)
            $this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->setBody($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getBody());
            // changed
            $this->setChanged();
            return true;
        }
        return false;
    }
    
    /**
     * Cancel changes for a version for a style for a specific context
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
            // update the style edit version cache array
            $this->styleparamversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, Modes::getMode(Mode::EDITMODE), $context);
            // publish the edit version
            $this->publishVersion($context);
            // update the style view version cache array
            $this->styleparamversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, Modes::getMode(Mode::VIEWMODE), $context);
            // changed
            $this->setChanged();
            return true;
        }
        return false;
    }
    
    /**
     * Remove a version for all modes, update the styleparamversions array 
     * 
     * @param context $context
     * @return boolean true if success
     */
    public function removeVersion($context) {
        // never remove the style versions for the default context group - default context (without this style version, there is no style)
        if (!($context->isDefault() && $context->getContextGroup()->isDefault())) {
            // check that the style version exists
            if ($this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getOriginal()) {
                // remove the style version
                if (Store::deleteStyleParamVersion($this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getId())) {
                    // update the style version cache array
                    $this->styleparamversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, Modes::getMode(Mode::EDITMODE), $context);
                    $this->setChanged();
                }
            }
            // check that the style version exists
            if ($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getOriginal()) {
                // remove the style version
                if (Store::deleteStyleParamVersion($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getId())) {
                    // update the style version cache array
                    $this->styleparamversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, Modes::getMode(Mode::VIEWMODE), $context);
                    $this->setChanged();
                }
            }
            return true;
        }
        return false;
    }

}

?>
