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
 * The event log contains events fired by users or the system, when things
 * happen that require action from other clients.
 *
 * @since 0.4.0
 */
class Event extends Log {
    private $event; // the event fired
    private $eventnumber; // the sequence number of the event, used to prevent events from being fired in the wrong order
    
    /**
     * Construct an event from the log
     * 
     * @param int $id the id of the event log to load
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableEventLog();
        $this->loadAttributes();
    }

    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getEvent($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * initialize the attributes
     * 
     * @param resultset $attr
     * @return boolean true if success
     */
    protected function initAttributes($attr) {
        $this->event = $attr->event;
        $this->eventnumber = $attr->eventnumber;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the event
     * 
     * @return string the event
     */
    public function getEvent() {
        return $this->event;
    }

    /**
     * setter for the event
     * 
     * @param newevent the event
     * @return boolean  if success
     * @throws exception if the update in the store fails
     */
    public function setEvent($newevent) {
        if (Store::setEventLogEvent($this->id,  $newevent)) {
            $this->event = $newevent;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the eventnumber
     * 
     * @return string the eventnumber
     */
    public function getEventNumber() {
        return $this->eventnumber;
    }

    /**
     * setter for the eventnumber
     * 
     * @param neweventnumber the eventnumber
     * @return boolean  if success
     * @throws exception if the update in the store fails
     */
    public function setEventLogEventNumber($neweventnumber) {
        if (Store::setEventLogEventNumber($this->id,  $neweventnumber)) {
            $this->eventnumber = $neweventnumber;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}