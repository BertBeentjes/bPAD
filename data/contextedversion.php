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
 * ContextedVersion is an extension of StoredEntity, that is used for layoutversions,
 * styleversions, structureversions and styleparamversion. The version contains 
 * the actual layout, style, structure or style param snippet in the body.
 *
 * @since 0.4.0
 */
class ContextedVersion extends StoredEntity {
    protected $mode; // the mode of this version
    protected $body; // the code snippet
    protected $context; // the context to use the code snippet in
    protected $type; // the type of entity, a layout, a structure or a style
    protected $original; // is this an original version, or does it use a backup version 
    
    // constants for the types
    const LAYOUT = 'layout';
    const STYLE = 'style';
    const STRUCTURE = 'structure';
    const STYLEPARAM = 'styleparam';
    
    /**
     * constructor for contexted entities
     * 
     * @param object $container the containing object for this object
     * @param string $type the type of this object, used to access the database
     * @param mode $mode the mode
     * @param context $context
     */
    public function __construct($container, $type, $mode, $context) {
        $this->container = $container;
        $this->tablename = Store::getTableContexted($type);
        $this->type = $type;
        $this->mode = $mode;
        $this->context = $context;
        $this->original = true;
        $this->loadAttributes();
    }
    
    /**
     * load the basic attributes for this contexted entity 
     * 
     * @return boolean
     * @throws Exception when no version has been found, not even using the backup
     */
    protected function loadAttributes() {
        if ($result = $this->getContextedVersion($this->context)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->container->getId() . ' @ ' . __METHOD__);
    }

    /**
     * init the basic attributes for this contexted entity 
     * 
     * @return boolean
     */
    protected function initAttributes($attr) {
        $this->id = $attr->id;
        $this->body = $attr->body;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * get a version of a layout, style or structure, based on the context. If
     * no version is found for the given context, revert to the backup context
     * for the given context
     * 
     * @param context
     * @return resultset with the version found, or null if nothing has been found
     * @throws exception when no version is found for the context and the backup context(s), this situation is illegal, but can arise when backup context settings are not set correctly
     */
    private function getContextedVersion($context) {
        if ($result = Store::getContextedVersion($this->mode->getId(), $context->getId(), $this->type, $this->container->getId())) {
            return $result;
        }
        // there is no item for the given context, go to the backup context
        if ($backupcontext = $context->getBackupContext()) {
            $this->original = false;
            return $this->getContextedVersion($backupcontext);
        }
        // there is no backup context available
        throw new Exception (Helper::getLang(Errors::ERROR_CONTEXT_NO_BACKUP_CONTEXT_AVAILABLE) . ' @ ' . __METHOD__);
    }
    
    /**
     * getter for the original indicator
     * 
     * @return boolean
     */
    public function getOriginal() {
        return $this->original;
    }
    
    /**
     * getter for the body
     * 
     * @return string
     */
    public function getBody() {
        return $this->body;
    }
    
    /**
     * Setter for the body
     * 
     * @param string $newbody
     * @return boolean
     */
    public function setBody($newbody) {
        if (Store::setContextedVersionBody($this->id, $newbody, $this->tablename) && $this->setChanged()) {
            $this->body = $newbody;
            return true;
        }
    }
    
    /**
     * setter for the mode, used for archiving and publishing
     * 
     * @param mode $mode
     * @return boolean true if success
     */
    public function setMode($mode) {
        if (Store::setContextedVersionMode($this->id, $mode->getId(), $this->tablename) && $this->setChanged()) {
            $this->mode = $mode;
            return true;
        }
    }
    
    /**
     * getter for the mode
     * 
     * @return mode
     */
    public function getMode() {
        return $this->mode;
    }
    
    /**
     * getter for the context
     * 
     * @return context
     */
    public function getContext() {
        return $this->context;
    }
    
    /**
     * getter for the type
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
}