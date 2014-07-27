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
 * A user
 *
 * @since 0.4.0
 */
class User extends NamedEntity{
    
    const USER_ADMINISTRATOR = 1;
    const DEFAULT_USER = 1;

    private $usergroups = array(); // the user groups this user is a member of
    private $usergroupsloaded = false; // are the user groups loaded

    private $firstname; // the first name of the user
    private $lastname; // the last name of the user
    private $password; // the password
    private $logincounter; // number of faulty logins, if more than 3, the user account is blocked

    /**
     * Constructor, sets the basic user attributes
     * By setting these attribs, the existence of the user is 
     * verified
     * 
     * @param id contains the user id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableUsers();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getUser($this->id)) {
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
        $this->firstname = $attr->firstname;
        $this->lastname = $attr->lastname;
        $this->password = $attr->password;
        $this->logincounter = $attr->logincounter;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * get the user groups for this mode
     * 
     * @return UserGroup[]
     */
    public function getUserGroups () {
        if ($this->usergroupsloaded) {
            return $this->usergroups;
        } else {
            if ($result = Store::getUserUserGroups($this->id)) {
                while ($attr = $result->fetchObject()) {
                    // load the user groups with the generic loader
                    $this->usergroups[$attr->id] = UserGroups::getUsergroup($attr->id);
                }
            }
            $this->usergroupsloaded = true;
            return $this->usergroups;
        }
    }

    /**
     * getter for the first name
     * 
     * @return string the first name
     */
    public function getFirstName() {
        return $this->firstname;
    }

    /**
     * setter for the first name
     * 
     * @param newfirstname the new first name
     * @return boolean  if success
     * @throws exception if the update in the store fails
     */
    public function setFirstName($newfirstname) {
        if (Store::setUserFirstName($this->id, $newfirstname) && $this->setChanged()) {
            $this->firstname = $newfirstname;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }    
    
    /**
     * getter for the last name
     * 
     * @return string the last name
     */
    public function getLastName() {
        return $this->lastname;
    }

    /**
     * getter for the full name
     * 
     * @return string the full name
     */
    public function getFullName() {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * setter for the last name
     * 
     * @param newlastname the new last name
     * @return boolean  if success
     * @throws exception if the update in the store fails
     */
    public function setLastName($newlastname) {
        if (Store::setUserLastName($this->id, $newlastname) && $this->setChanged()) {
            $this->lastname = $newlastname;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }    
    
    /**
     * getter for the password
     * 
     * @return string the password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * setter for the password
     * 
     * @param newpassword the new password
     * @return boolean  if success
     * @throws exception if the update in the store fails
     */
    public function setPassword($newpassword) {
        if (Store::setUserPassword($this->id, $newpassword) && $this->setChanged()) {
            $this->password = $newpassword;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }    
    
    /**
     * getter for the logincounter
     * 
     * @return string the logincounter
     */
    public function getLoginCounter() {
        return $this->logincounter;
    }

    /**
     * setter for the logincounter
     * 
     * @param newlogincounter the new logincounter
     * @return boolean  if success
     * @throws exception if the update in the store fails
     */
    public function setLoginCounter($newlogincounter) {
        if (Store::setUserLoginCounter($this->id, $newlogincounter) && $this->setChanged()) {
            $this->logincounter = $newlogincounter;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }    
    
    /**
     * Is the user removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        // for now, no users are removable through the application
        // TODO: make users removable
        // TODO: decide on whether users can be removed completely, or just marked inactive
        return false;
    }
    
}