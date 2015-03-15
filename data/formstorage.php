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
class FormStorage {

    const DEFAULT_FILE_INCLUDE = 1;

    private $id; // the id
    private $form; // json representation of the form data
    private $formhandler; // the handler this form uses
    
    /**
     * Construct the form storage
     * 
     * @param int the id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getFormStorage($this->id)) {
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
        $this->form = $attr->form;
        $this->formhandler = FormHandlers::getFormHandler($attr->formhandlerid);
        return true;
    }
    
    /**
     * Get the form data 
     * 
     * @return string type
     */
    public function getForm() {
        return $this->form;
    }
    
    /**
     * Set the form value for the form storage
     * 
     * @param string $newform the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setForm($newform) {
        if (Store::setFormStorageForm($this->id, $newform)) {
            $this->form = $newform;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the form handler
     * 
     * @return formhandler
     */
    public function getFormHandler() {
        return $this->formhandler;
    }
    
    /**
     * Set the form value for the form storage
     * 
     * @param formhandler $newformhandler the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setFormHandler($newformhandler) {
        if (Store::setFormStorageFormHandlerId($this->id, $newformhandler->getId())) {
            $this->formhandler = $newformhandler;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
        
}