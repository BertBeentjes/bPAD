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
 * Abstract class for log records
 *
 * @since 0.4.0
 */
abstract class Log {
    protected $id; // the id
    protected $tablename; // the name of the table the log is stored in
    private $item; // the type of item the command is meant for, an item can be anything in the Store
    private $itemaddress; // the address of the item
    private $user; // the id of the user that wants to execute the command
    private $date; // the date and time of the command
    
    /**
     * Initialize the attributes
     * 
     * @param resultsetobject $attr
     * @return boolean true if success
     */
    protected function initAttributes($attr) {
        $this->item = $attr->item;
        $this->itemaddress = $attr->itemaddress;
        $this->user = Users::getUser($attr->userid);
        $this->date = $attr->date;
        return true;
    }
    
    /**
     * getter for the item the command is about
     * 
     * @return string the item
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * setter for the item
     * 
     * @param string the item
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setItem($newitem) {
        if (Store::setLogItem($this->tablename, $this->id,  $newitem)) {
            $this->item = $newitem;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the address for the item the command is about
     * 
     * @return string the itemaddress
     */
    public function getItemAddress() {
        return $this->itemaddress;
    }

    /**
     * getter for the address split in parts for the item the command is about.
     * Addresses can be of two types: something.otherthing.otherthing
     * or: some.other.other/some.other.other/some.other.other
     * 
     * @return string[] the itemaddress
     */
    public function getItemAddressParts() {
        $parts = array();
        if (strpos($this->getItemAddress(), "/") > -1) {
            $mainparts = explode("/", $this->getItemAddress());
            foreach ($mainparts as $mainpart) {
                $parts[] = explode(".", $mainpart);
            }
        } else {
            $parts = explode(".", $this->getItemAddress());
        }
        return $parts;
    }

    /**
     * setter for the item address
     * 
     * @param string newitemaddress the itemaddress
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setItemAddress($newitemaddress) {
        if (Store::setLogItemAddress($this->tablename, $this->id,  $newitemaddress)) {
            $this->itemaddress = $newitemaddress;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the userid of the user that wants to execute the command
     * 
     * @return string the userid
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * setter for the userid
     * 
     * @param user the userid
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setUser($newuser) {
        if (Store::setLogUserId($this->tablename, $this->id,  $newuser->getId())) {
            $this->user = $newuser;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * getter for the date and time the command is executed
     * 
     * @return string the date
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * setter for the date
     * 
     * @param newdate the date
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setDate($newdate) {
        if (Store::setLogDate($this->tablename, $this->id,  $newdate)) {
            $this->date = $newdate;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * getter for the id
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

}