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
class FormHandler extends NamedEntity {

    const DEFAULT_FILE_INCLUDE = 1;

    private $emailto; // where to send a confirmation email
    private $emailfrom; // where to send a confirmation email from
    private $emailreplyto; // where to send a confirmation email reply to
    private $emailbcc; // where to send copies of the confirmation
    private $emailsubject; // the subject for the confirmation email
    private $emailtext; // the text for the confirmation email
    private $exiturl; // the url to exit to after processing the form
    
    /**
     * Construct the form handler
     * 
     * @param int the id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableFormHandlers();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getFormHandler($this->id)) {
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
        $this->emailto = $attr->emailto;
        $this->emailfrom = $attr->emailfrom;
        $this->emailreplyto = $attr->emailreplyto;
        $this->emailbcc = $attr->emailbcc;
        $this->emailsubject = $attr->emailsubject;
        $this->emailtext = $attr->emailtext;
        $this->exiturl = $attr->exiturl;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * Get the email to of the form handler
     * 
     * @return string type
     */
    public function getEmailTo() {
        return $this->emailto;
    }
    
    /**
     * Set the email to value for the form handler
     * 
     * @param string $newemailto the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setEmailTo($newemailto) {
        if (Store::setFormHandlerEmailTo($this->id, $newemailto) && $this->setChanged()) {
            $this->emailto = $newemailto;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the email from of the form handler
     * 
     * @return string type
     */
    public function getEmailFrom() {
        return $this->emailfrom;
    }
    
    /**
     * Set the email from value for the form handler
     * 
     * @param string $newemailfrom the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setEmailFrom($newemailfrom) {
        if (Store::setFormHandlerEmailFrom($this->id, $newemailfrom) && $this->setChanged()) {
            $this->emailfrom = $newemailfrom;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the email reply to of the form handler
     * 
     * @return string type
     */
    public function getEmailReplyTo() {
        return $this->emailreplyto;
    }
    
    /**
     * Set the email reply to value for the form handler
     * 
     * @param string $newemailreplyto the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setEmailReplyTo($newemailreplyto) {
        if (Store::setFormHandlerEmailReplyTo($this->id, $newemailreplyto) && $this->setChanged()) {
            $this->emailreplyto = $newemailreplyto;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the email bcc of the form handler
     * 
     * @return string type
     */
    public function getEmailBCC() {
        return $this->emailbcc;
    }
    
    /**
     * Set the email to value for the form handler
     * 
     * @param string $newemailto the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setEmailBCC($newemailbcc) {
        if (Store::setFormHandlerEmailBCC($this->id, $newemailbcc) && $this->setChanged()) {
            $this->emailbcc = $newemailbcc;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the email subject of the form handler
     * 
     * @return string type
     */
    public function getEmailSubject() {
        return $this->emailsubject;
    }
    
    /**
     * Set the email subject value for the form handler
     * 
     * @param string $newemailsubject the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setEmailSubject($newemailsubject) {
        if (Store::setFormHandlerEmailSubject($this->id, $newemailsubject) && $this->setChanged()) {
            $this->emailsubject = $newemailsubject;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the email text of the form handler
     * 
     * @return string type
     */
    public function getEmailText() {
        return $this->emailtext;
    }
    
    /**
     * Set the email text value for the form handler
     * 
     * @param string $newemailtext the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setEmailText($newemailtext) {
        if (Store::setFormHandlerEmailText($this->id, $newemailtext) && $this->setChanged()) {
            $this->emailtext = $newemailtext;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the exit url of the form handler
     * 
     * @return string type
     */
    public function getExitURL() {
        return $this->exiturl;
    }
    
    /**
     * Set the exit url value for the form handler
     * 
     * @param string $newexiturl the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setExitURL($newexiturl) {
        if (Store::setFormHandlerExitURL($this->id, $newexiturl) && $this->setChanged()) {
            $this->exiturl = $newexiturl;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
}