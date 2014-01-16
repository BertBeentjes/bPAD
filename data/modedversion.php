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
 * ModedVersion is an extension of StoredEntity, that is used for file include 
 * versions and snippet versions. 
 *
 * @since 0.4.0
 */
class ModedVersion extends StoredEntity {
    protected $mode; // the mode of this version
    protected $body; // the code snippet
    protected $type; // the type of entity (see the type constants below)
    
    // constants for the types
    const FILE_INCLUDE = 'fileinclude';
    const SNIPPET = 'snippet';
    
    /**
     * constructor for moded entities
     * 
     * @param object $container the containing object for this object
     * @param string $type the type of this object, used to access the database
     * @param mode $mode the mode
     */
    public function __construct($container, $type, $mode) {
        $this->container = $container;
        $this->tablename = Store::getTableModed($type);
        $this->type = $type;
        $this->mode = Modes::getMode($mode->getId());
        $this->loadAttributes();
    }
    
    /**
     * load the basic attributes for this moded entity 
     * 
     * @return boolean
     * @throws Exception when no version has been found, not even using the backup
     */
    protected function loadAttributes() {
        if ($result = Store::getModedVersion($this->mode->getId(), $this->type, $this->container->getId())) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->container->getId() . ' @ ' . __METHOD__);
    }

    /**
     * init the basic attributes for this moded entity 
     * 
     * @return boolean
     */
    protected function initAttributes($attr) {
        $this->id = $attr->id;
        $this->body = $attr->body;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * Get the body of the version
     * 
     * @return string
     */
    public function getBody() {
        return $this->body;
    }
    
    /**
     * Set the body of the version
     * 
     * @param string $newbody
     * @return boolean true if success
     */
    public function setBody($newbody) {
        if (Store::setModedVersionBody($this->id, $newbody, $this->tablename) && $this->setChanged()) {
            $this->body = $newbody;
            return true;
        }
    }
    
}

?>
