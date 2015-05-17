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
 * Logging of the edit commands by the users, used to create an undo option
 *
 * @since 0.4.0
 */
class Command extends Log {
    private $command; // the command to execute on the item, contains group and member, separated by a .
    private $commandgroup; // the group for the command to execute on the item
    private $commandmember; // the member for the command to execute on the item
    private $commandnumber; // the sequence number for this command, if the number is specified, the command will not be executed if a command with a higher number is found
    private $lastcommandid; // last command id known to the session
    private $sessionidentifier; // the identifier of the session this command came from. Together with the command number used to prevent earlier commands from overwriting later commands
    private $value; // the new value for something
    private $oldvalue; // store the old value to make undo easier
    private $mode; // optionally add the mode to the command
    private $context; // optionally add the context to the command
    
    /**
     * Construct a command from the log
     * 
     * @param int $id the id of the command log to load
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableCommandLog();
        $this->loadAttributes();
    }

    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getCommand($this->id)) {
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
        $this->command = $attr->command;
        $this->evalCommand($attr->command);
        $this->commandnumber = $attr->commandnumber;
        $this->lastcommandid = $attr->lastcommandid;
        $this->sessionidentifier = $attr->sessionidentifier;
        $this->value = $attr->value;
        $this->oldvalue = $attr->oldvalue;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the command
     * 
     * @return string the command
     */
    public function getCommand() {
        return $this->command;
    }

    /**
     * getter for the command group
     * 
     * @return string the command group
     */
    public function getCommandGroup() {
        return $this->commandgroup;
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
     * getter for the command member
     * 
     * @return string the command member
     */
    public function getCommandMember() {
        return $this->commandmember;
    }

    /**
     * setter for the command
     * 
     * @param newcommand the command
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setCommand($newcommand) {
        if (Store::setCommandLogCommand($this->id,  $newcommand)) {
            $this->evalCommand($newcommand);
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * evaluate the command
     * 
     * @param newcommand the command
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    private function evalCommand($newcommand) {
        $this->command = $newcommand;
        $commandparts = explode('.', $this->command);
        $this->commandgroup = $commandparts[0];
        $this->commandmember = $commandparts[1];
        if (isset($commandparts[2])) {
            $modeid = intval($commandparts[2]);
            if (Validator::validMode($modeid)) {
                $this->mode = Modes::getMode($modeid);
            }
        }    
        if (isset($commandparts[3])) {
            $contextid = intval($commandparts[3]);
            if (Validator::isNumeric($contextid)) {
                $this->context = Contexts::getContext($contextid);
            }
        }            
    }
    
    /**
     * getter for the commandnumber
     * 
     * @return string the commandnumber
     */
    public function getCommandNumber() {
        return $this->commandnumber;
    }

    /**
     * setter for the command number
     * 
     * @param newcommandnumber the command number
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setCommandNumber($newcommandnumber) {
        if (Store::setCommandLogCommandNumber($this->id,  $newcommandnumber)) {
            $this->commandnumber = $newcommandnumber;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the sessionidentifier
     * 
     * @return string the sessionidentifier
     */
    public function getSessionIdentifier() {
        return $this->sessionidentifier;
    }

    /**
     * setter for the command number
     * 
     * @param newsessionidentifier the command number
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setSessionIdentifier($newsessionidentifier) {
        if (Store::setCommandLogSessionIdentifier($this->id,  $newsessionidentifier)) {
            $this->sessionidentifier = $newsessionidentifier;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the lastcommandid
     * 
     * @return string the lastcommandid
     */
    public function getLastCommandId() {
        return $this->lastcommandid;
    }

    /**
     * setter for the command number
     * 
     * @param newlastcommandid the command number
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setLastCommandId($newlastcommandid) {
        if (Store::setCommandLogLastCommandId($this->id,  $newlastcommandid)) {
            $this->lastcommandid = $newlastcommandid;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the value
     * 
     * @return string the value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * setter for the value
     * 
     * @param newvalue the value
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setValue($newvalue) {
        if (Store::setCommandLogValue($this->id,  $newvalue)) {
            if (Validator::isNumeric($newvalue)) {
                $this->value = intval($newvalue);
            } else {
                $this->value = $newvalue;
            }
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the oldvalue
     * 
     * @return string the oldvalue
     */
    public function getOldValue() {
        return $this->oldvalue;
    }

    /**
     * setter for the old value, if the command has been executed (and has resulted
     * in changes) the old value has a value, otherwise it is NULL.
     * 
     * @param newoldvalue the new oldvalue
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setOldValue($newoldvalue) {
        if (Store::setCommandLogOldValue($this->id,  $newoldvalue)) {
            $this->oldvalue = $newoldvalue;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}