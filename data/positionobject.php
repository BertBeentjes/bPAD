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
 * Extends StoredEntity and contains one of the types of positions, in this case
 * the position that connects this object to another object
 *
 * @since 0.4.0
 */
class PositionObject extends StoredEntity implements PositionContent {
    private $object; // the object this position contains
    
    /**
     * Construct the positionobject
     * 
     * @param type $position
     */
    public function __construct ($position, $attr) {
        $this->id = $attr->id;
        $this->tablename = Store::getTablePositionObjects();
        $this->container = $position;
        $this->object = Objects::getObject($attr->objectid);
        parent::initAttributes($attr);
    }

    /**
     * Return the type of content in this position
     * 
     * @return constant
     */
    public function getType() {
        return PositionContent::POSITIONTYPE_OBJECT;
    }
    
    /**
     * Return the object in this position
     * 
     * @return object the child object for this position
     */
    public function getObject() {
        // briefly, during creation, a position object can point towards the site root. To prevent infinite recursion,
        // the site root object is not returned. The root can't logically be in a position.
        if ($this->object->getId() != SysCon::SITE_ROOT_OBJECT) {
            return $this->object;
        }
    }
    
    /**
     * Change the object in this position
     * 
     * @param object $object the new object
     * @return object the refreshed child object
     */
    public function setObject($object) {
        if (Store::setPositionObjectObject($this->id, $object->getId()) && $this->setChanged()) {
            $this->object = $object;            
            // set the new parent object in the child object
            $object->getVersion($this->getContainer()->getContainer()->getMode())->setObjectParent($this->getContainer()->getContainer()->getContainer());
            return true;
        }
    }
    
}

?>
