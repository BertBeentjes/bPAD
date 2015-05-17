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
 * Processes a form and redirect to the next url
 *
 * @since 0.4.3
 */
class Form extends Respond {
        
    private $formhandler;
    private $formdata;
    
    /**
     * Construct the file include handler, read the requesturl
     * 
     */
    public function __construct() {
        // get the context group to use. A page always uses default context group settings using mobiledetect.
        $this->setContextGroup($this->chooseContextGroup());
        // set the mode, pages always start in view mode
        $this->setMode(Modes::getMode(Mode::VIEWMODE));

        // get the form info
        $this->formhandler = FormHandlers::getFormHandlerByName(Request::getURL()->getFileName());
        
        // store the submitted form data
        $form = json_encode($_POST);
        $this->formdata = FormStorages::newFormStorage($this->formhandler);
        $this->formdata->setForm($form);
        
        // TODO: create email
        $this->sendEmail();
                
        // redirect
        header ("HTTP/1.1 100 Continue");
        header ("Location: " . $this->formhandler->getExitURL());
        exit;
    }

    /**
     * Set the context group to use when formatting the content
     * 
     */
    protected function chooseContextGroup() {
        $mobiledetect = new Mobile_Detect();
        if ($mobiledetect->isMobile()) {
            return ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_MOBILE);
        } else {
            return ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT);
        }
    }
    
    /**
     * Send an email with the info from the form
     */
    protected function sendEmail() {        
        $text = $this->formhandler->getEmailText();
        $subject = $this->formhandler->getEmailSubject();
        $to = $this->formhandler->getEmailTo();
        $from = $this->formhandler->getEmailFrom();
        $replyto = $this->formhandler->getEmailReplyTo();
        $bcc = $this->formhandler->getEmailBCC();
        
        // replace #tags# in text and subject with the appropriate texts
        foreach ($_POST as $key => $value) {
            $text = str_replace('#' . $key . '#', $value, $text);
            $subject = str_replace('#' . $key . '#', $value, $subject);
            $to = str_replace('#' . $key . '#', $value, $to);
            $from = str_replace('#' . $key . '#', $value, $from);
            $replyto = str_replace('#' . $key . '#', $value, $replyto);
            $bcc = str_replace('#' . $key . '#', $value, $bcc);
        }
        
        $headers = "From: " . $from . PHP_EOL;
        $headers .= "Reply-To: " . $replyto . PHP_EOL;
        $headers .= "Bcc: " . $bcc . PHP_EOL;
        
        mail($to, $subject, $text, $headers);
    }
    
}