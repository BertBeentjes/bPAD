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
 * UpdatePage is a special kind of page
 *
 * @since 0.4.0
 */
class UpdatePage extends Page {
    private $executer; // executes the upload
    private $status = AdminLabels::ADMIN_UPDATE_STATUS_WAITING; // update execute status
    /**
     * factor bPAD terms in the snippet
     * 
     */
    protected function factorResponse() {
        // initialize the factory
        $factory = new UpdatePageFactory();
        $factory->setContent($this->getResponse()->getContent());
        $factory->setContextForContextGroup($this->getContextGroup());
        // uploading is an edit activity
        $factory->setMode(Modes::getMode(Mode::EDITMODE));
        // set the status message
        $factory->setStatus($this->getStatus());
        // factor
        $factory->factor();
        // store the result in the response
        $this->getResponse()->setContent($factory->getContent());
    }
    
    /**
     * Execute a requested update
     */
    public function executeUpdate() {        
        $this->executer = new ExecuteUpdate();
        if ($this->executer->executeUpdate()) {
            $this->setStatus(AdminLabels::ADMIN_UPDATE_STATUS_SUCCESS);
        } else {
            $this->setStatus(AdminLabels::ADMIN_UPDATE_STATUS_FAILED);
        }
    }

    /**
     * set the status
     * 
     * @param string $status
     * @return string
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * get the status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

}