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
 * Basic class that contains some stuff all stored entities have in common
 * 
 * Contains create and change info
 * 
 * Not used as an object on its own, but more specific entities will extend 
 * this class. Extenders must set the id, table name and container 
 * themselves. 
 * 
 * The container is used for propagating changes upwards. When a subentity
 * changes, it propagates the change information up to its container. That
 * way, the top level entity knows when a part of it has changed.
 *
 * @since 0.4.0
 */
abstract class StoredEntity {

    protected $id; // the id in the database of this item, must be set by the class extending this class
    protected $tablename; // the table name of this item, must be set by the class extending this class
    protected $container; // the containing object for this object, used for propagating changes
    protected $createdate;
    private $createuserid; // store the id to prevent infinite recursion when instantiating users, always use the getter 
    protected $changedate;
    private $changeuserid; // store the id to prevent infinite recursion when instantiating users, always use the getter
    protected $changed = false; // remember whether this object has been changed and outdated (speed optimization, do not outdate multiple times when the object is changed multiple times in one roundtrip, especially important for publishing, cancelling, copying, and other 'bulk' mutations.

    /**
     * initialize the create/change dates and user ids
     * 
     * @param type $attr
     */

    protected function initAttributes($attr) {
        $this->createdate = $attr->createdate;
        $this->createuserid = $attr->createuserid;
        $this->changedate = $attr->changedate;
        $this->changeuserid = $attr->changeuserid;
        return true;
    }

    /**
     * Getter for the id, used to access the store
     *
     * @return int id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Getter for the createdate
     *
     * @return datetime the createdate
     */
    public function getCreateDate() {
        return $this->createdate;
    }

    /**
     * Getter for the container
     *
     * @return mixed container
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * Getter for the create user
     *
     * @return user the user that created this item
     */
    public function getCreateUser() {
        return Users::getUser($this->createuserid);
    }

    /**
     * Getter for the changedate
     *
     * @return datetime the changedate
     */
    public function getChangeDate() {
        return $this->changedate;
    }

    /**
     * Getter for the change user
     *
     * @return user the user that last changed this item
     */
    public function getChangeUser() {
        return Users::getUser($this->changeuserid);
    }

    /**
     * The stored entity has changed, this sets the changedate and 
     * changeuserid. 
     * 
     * If this stored entity has a containing entity, the container
     * is also changed
     *
     * @param boolean $force optional, force setting the change date/user
     * @return boolean 
     * @throws exception when the store isn't accessible
     */
    protected function setChanged($force = false) {
        if (!$this->changed || $force) {
            if (Store::setChanged($this->tablename, $this->id)) {
                if ($row = Store::getChanged($this->tablename, $this->id)->fetchObject()) {
                    $this->changedate = $row->changedate;
                    $this->changeuserid = $row->changeuserid;
                    // don't do this again for this object in this roundtrip
                    $this->changed = true;
                    // propagate the change to the container for this item
                    if (isset($this->container)) {
                        $this->container->setChanged($force);
                        return true;
                    }
                    return true;
                }
            }
            throw new Exception(Helper::getLang(Errors::ERROR_SETCHANGED_FAILED) . ' @ ' . __METHOD__);
        }
        return true;
    }
    
}