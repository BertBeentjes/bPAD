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
 * Basic implementation of sessions, to enable anonymous visitors to add content
 * to the site. The sessions support a very basic respond process.
 *
 * @since 0.4.0
 */
class Session {
    private $id; // the id
    private $sessionidentifier; // the session id (a random string, to prevent session id guessing)
    private $object; // the object the session is active for
    private $createdate; // the date/time the session was created, sessions older than a certain amount of time are deactivated and deleted
    
    /**
     * Get the session from the store
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadAttributes();
    }
    
    /**
     * create a new command in the store
     * 
     */
    public static function newSession() {
        $sessionidentifier = self::newSessionIdentifier();
        // if the identifier exists, try a new one
        while ($session = Sessions::getSessionByIdentifier($sessionidentifier)) {
            $sessionidentifier = self::newSessionIdentifier();
        }
        return new Session(Store::insertSession($sessionidentifier, SysCon::SITE_ROOT_OBJECT));
    }

    /**
     * Create a new session identifier using random functions. Session identifiers
     * must be random, because they are used (among other things) for anonymous responses, so if they
     * aren't random, it is possible to guess a session and hack that response.
     * 
     * @return int
     */
    private static function newSessionIdentifier() {
        // unique number
        $sessionidentifier = mt_rand(100000000, 999999999);
        // if this server doesn't support mt_rand
        if ($sessionidentifier=='') {
            $sessionidentifier = rand(10000,30000) * rand(10000,30000);
        }
        return $sessionidentifier;
    }
    
    /**
     * Load the attributes for the session
     * 
     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getSession($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * init the session
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->sessionidentifier =  $attr->sessionidentifier;
        $this->object = Objects::getObject($attr->objectid);
        $this->createdate =  $attr->createdate;
        return true;
    }
    
    /**
     * The session identifier
     * 
     * @return string
     */
    public function getSessionIdentifier() {
        return $this->sessionidentifier;
    }
    
    /**
     * set the session identifier
     * 
     * @param string $newsessionidentifier
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setSessionIdentifier($newsessionidentifier) {
        if (Store::setSessionSessionIdentifier($this->id,  $newsessionidentifier)) {
            $this->sessionidentifier = $newsessionidentifier;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * The session object
     * 
     * @return object
     */
    public function getObject() {
        return $this->object;
    }
    
    /**
     * set the session object 
     * 
     * @param object the new object
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setObject($newobject) {
        if (Store::setSessionObjectId($this->id,  $newobject->getId())) {
            $this->object = $newobject;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * The session create date
     * 
     * @return string
     */
    public function getCreateDate() {
        return $this->createdate;
    }
    
    /**
     * set the session create date
     * 
     * @param datetimestring $newcreatedate
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setCreateDate($newcreatedate) {
        if (Store::setSessionCreateDate($this->id,  $newcreatedate)) {
            $this->createdate = $newcreatedate;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}