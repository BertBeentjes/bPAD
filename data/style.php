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
 * Contains a style
 *
 */
class Style extends SettedEntity {
    private $styleversions = array();
    private $isbpaddefined;
    private $styletype;
    private $classsuffix;

    const OBJECT_STYLE = 'OBJECT_STYLE'; // style used for layouts in objects
    const POSITION_STYLE = 'POSITION_STYLE'; // style used for structures in positions
    const DEFAULT_STYLE = 1;
    const DEFAULT_OBJECT_STYLE = 1;
    const DEFAULT_POSITION_STYLE = 2;
    
    /**
     * Constructor, sets the basic style attributes
     * By setting these attribs, the existence of the style is 
     * verified
     * 
     * @param id contains the style id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableStyles();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getStyle($this->getId())) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->getId() . ' @ ' . __METHOD__);
    }
    
    /**
     * Initialize the attributes
     * 
     * @return boolean true if success,
     */
    protected function initAttributes($attr) {
        $this->isbpaddefined = $attr->isbpaddefined;
        $this->styletype = $attr->styletype;
        $this->classsuffix = $attr->classsuffix;
        parent::initAttributes($attr);
        return true;
    }
    
    protected function setChanged() {
        CacheStyles::outdateStyleCache();
        return parent::setChanged();
    }
    
    /**
     * getter for the styleversion, depending on mode and context a different stylebody is returned
     * 
     * @param mode $mode 
     * @param context $context
     * @return contextedversion
     */
    public function getVersion($mode, $context) {
        if (isset($this->styleversions[$mode->getId()][$context->getId()])) {
            return $this->styleversions[$mode->getId()][$context->getId()];
        } else {
            $this->styleversions[$mode->getId()][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, $mode, $context);
            return $this->styleversions[$mode->getId()][$context->getId()];
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
        return $this->newStyleVersion($context);
    }

    /**
     * Create a new version for a style for a specific context, versions are always added
     * in edit mode, so they don't wreck the site. View mode versions are added when 
     * publishing.
     * 
     * @param boolean $force force the create, used when publishing and getVersion gives outdated info
     * @param context $context
     * @return boolean true if success
     */
    private function newStyleVersion($context, $force = false) {
        // check that the style versions don't already exist
        if (!$this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getOriginal() || $force) {
            // create the new style versions
            $editstyleversionid = Store::insertStyleVersion($this->getId(), Mode::EDITMODE, $context->getId());
            // update the style version cache array
            $this->styleversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, Modes::getMode(Mode::EDITMODE), $context);
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
            $this->newStyleVersion($context, true);
            // update the style version cache array
            $this->styleversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, Modes::getMode(Mode::VIEWMODE), $context);
            $this->styleversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, Modes::getMode(Mode::EDITMODE), $context);
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
            $this->styleversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, Modes::getMode(Mode::EDITMODE), $context);
            // publish the edit version
            $this->publishVersion($context);
            // update the style view version cache array
            $this->styleversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, Modes::getMode(Mode::VIEWMODE), $context);
            // changed
            $this->setChanged();
            return true;
        }
        return false;
    }
    
    /**
     * Remove a version for all modes, update the styleversions array and outdate
     * the object cache for the style/context combination
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
                if (Store::deleteStyleVersion($this->getVersion(Modes::getMode(Mode::EDITMODE), $context)->getId())) {
                    // update the style version cache array
                    $this->styleversions[Mode::EDITMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, Modes::getMode(Mode::EDITMODE), $context);
                    $this->setChanged();
                }
            }
            // check that the style version exists
            if ($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getOriginal()) {
                // remove the style version
                if (Store::deleteStyleVersion($this->getVersion(Modes::getMode(Mode::VIEWMODE), $context)->getId())) {
                    // update the style version cache array
                    $this->styleversions[Mode::VIEWMODE][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLE, Modes::getMode(Mode::VIEWMODE), $context);
                    $this->setChanged();
                }
            }
            return true;
        }
        return false;
    }

    /**
     * getter for isbpaddefined, no setter, this value is set to false by
     * default. True values are for styles that are created in the update
     * scripts belonging to new versions of bpad.
     * 
     * @return boolean isbpaddefined is this style defined by bpad
     */
    public function getIsBpadDefined() {
        return $this->isbpaddefined;
    }
    
    /**
     * setter for the name, check whether the style is bpad defined or not first
     * 
     * @param newname the name
     * @return boolean  if success
     * @throws exception if the update in the store fails or if the style is bPAD defined
     */
    public function setName($newname) {
        if (!$this->isbpaddefined) {
            parent::setName($newname);
            return true;
        }
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_IS_DEFINED_BY_BPAD) . ' @ ' . __METHOD__);
    }

    /**
     * returns the style type, this type indicates whether the style is used
     * for structures (type 2, position style) or for layouts (type 1, object style)
     * 
     * @return string the currect style type
     */
    public function getStyleType() {
        return $this->styletype;
    }
    
    /**
     * update the style type for the style
     * 
     * @param string the new style type
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setStyleType($newstyletype) {
        if (Store::setStyleStyleType($this->getId(), $newstyletype) && $this->setChanged()) {
            $this->styletype = $newstyletype;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * The class suffix is used to add to the basic class (replaces #c#, together with 
     * the currect context). This makes it possible to define several styles for the
     * same layout or structure.
     * 
     * @return string classsuffix
     */
    public function getClassSuffix() {
        return $this->classsuffix;
    }
    
    /**
     * Change the classsuffix
     * 
     * @param string $newclasssuffix
     * @return boolean success if true
     * @throws Exception when the update failse
     */
    public function setClassSuffix($newclasssuffix) {
        if (Store::setStyleClassSuffix($this->getId(), $newclasssuffix) && $this->setChanged()) {
            $this->classsuffix = $newclasssuffix;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Is the style used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getStyleUsed($this->getId())) {
            return true;
        }
        return false;
    }
    
    /**
     * Is the style removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return !$this->isUsed() && !$this->getIsBpadDefined() && !($this->getId()==Style::DEFAULT_STYLE);
    }

}