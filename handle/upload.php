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
 * Upload is a special kind of page
 *
 * @since 0.4.0
 */
class Upload extends Page {
    var $executer; // executes the upload
    
    /**
     * factor bPAD terms in the snippet
     * 
     */
    protected function factorResponse() {
        // initialize the factory
        $this->factory = new UploadFactory();
        $this->factory->setContent($this->getResponse()->getContent());
        $this->factory->setContextForContextGroup($this->getContextGroup());
        // uploading is an edit activity
        $this->factory->setMode(Modes::getMode(Mode::EDITMODE));
        // factor
        $this->factory->factor();
        // store the result in the response
        $this->getResponse()->setContent($this->factory->getContent());
    }
    
    /**
     * Execute a requested upload
     */
    public function executeUpload() {
        $this->executer = new ExecuteUpload();
        $this->executer->setMode(Modes::getMode(Mode::EDITMODE));
        $this->executer->uploadFile();
    }
}