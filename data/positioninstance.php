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
 * the instance, that contains a basic piece of content
 *
 */
class PositionInstance extends StoredEntity implements PositionContent {
    private $object; // a specific object to show as an instance
    private $template; // restrict the instance to objects based upon this template
    private $listwords; // restrict the instance to objects containing the list words
    private $searchwords; // restrict the instance to objects containing the search words
    private $parent; // restrict the instance to descendants of a certain parent object
    private $activeitems; // show active or inactive objects (recycle bin)
    private $orderby; // ordering of the objects to show
    private $groupby; // grouping of the objects to show
    private $objects; // the position instance objects
    private $outdated; // whether the cache is outdated
    
    const POSITIONINSTANCE_ORDER_CHANGEDATE_ASC = 'POSITIONINSTANCE_ORDER_CHANGEDATE_ASC';
    const POSITIONINSTANCE_ORDER_CHANGEDATE_DESC = 'POSITIONINSTANCE_ORDER_CHANGEDATE_DESC';
    const POSITIONINSTANCE_ORDER_CREATEDATE_ASC = 'POSITIONINSTANCE_ORDER_CREATEDATE_ASC';
    const POSITIONINSTANCE_ORDER_CREATEDATE_DESC = 'POSITIONINSTANCE_ORDER_CREATEDATE_DESC';
    
    /**
     * Construct the instance, retrieve all the attributes
     * 
     * @param position the containing position
     * @param resultset the attributes for the positioninstance
     */
    public function __construct($position, $attr) {
        $this->id = $attr->id;
        $this->tablename = Store::getTablePositionInstances();
        $this->container = $position;
        $this->object = Objects::getObject($attr->objectid);
        $this->template = Templates::getTemplate($attr->templateid);
        $this->listwords = $attr->listwords;
        $this->searchwords = $attr->searchwords;
        $this->parent = Objects::getObject($attr->parentid);
        $this->activeitems = $attr->activeitems;
        $this->orderby = $attr->orderby;
        $this->groupby = $attr->groupby;
        $this->outdated = $attr->outdated;
        $this->objects = CachePositionInstances::getObjects($this);
        parent::initAttributes($attr);
    }
    
    /**
     * set the outdated bool to true when changes are made
     * 
     * return boolean true if success
     */
    public function setChanged() {
        return $this->setOutdated(True) && parent::setChanged();
    }
    
    /**
     * Return the type of content in this position
     * 
     * @return string
     */
    public function getType() {
        return PositionContent::POSITIONTYPE_INSTANCE;
    }
        
    /**
     * Return the objects for this instance
     * 
     * @return object[]
     */
    public function getObjects() {
        return $this->objects;
    }
        
    /**
     * Return the objects for this instance, restricted by the additional user search
     * 
     * @return object[]
     */
    public function getUserSearchObjects($usersearch) {
        return CachePositionInstances::getObjects($this, $usersearch);
    }
        
    /**
     * Return whether the cache is outdated 
     * 
     * @return boolean
     */
    public function getOutdated() {
        return $this->outdated;
    }
        
    /**
     * Setter for cache is outdated
     * 
     * @param boolean newbool the new value for outdated
     * @return boolean true if success
     */
    public function setOutdated($newbool) {
        // don't propagate this change upwards and do not register the change user/date (no call to setchanged)
        $intbool = (int)$newbool;
        if (Store::setPositionInstanceOutdated($this->id, $intbool)) {
            $this->outdated = $newbool;
            return true;
        }
    }        

    /**
     * Getter for the object, if set, the instance shows only the 
     * specified object
     * 
     * @return object
     */
    public function getObject() {
        return $this->object;
    }
    
    /**
     * Setter for the object
     * 
     * @param object the new object id
     * @return boolean true if success
     */
    public function setObject($newobject) {
        if (Store::setPositionInstanceObjectId($this->id, $newobject->getId()) && $this->setChanged()) {
            $this->object = $newobject;
            return true;
        }
    }
    
    /**
     * Getter for the template, if set, the instance shows only objects that
     * are based upon the specified template
     * 
     * @return template the template
     */
    public function getTemplate() {
        return $this->template;
    }
    
    /**
     * Setter for the template
     * 
     * @param template the new template id
     * @return boolean true if success
     */
    public function setTemplate($newtemplate) {
        if (Store::setPositionInstanceTemplateId($this->id, $newtemplate->getId()) && $this->setChanged()) {
            $this->template = $newtemplate;
            return true;
        }
    }
        
    /**
     * Getter for the listwords , if set, the instance shows only objects that
     * contain the specified listwords
     * 
     * @return string the listwords 
     */
    public function getListWords() {
        return $this->listwords;
    }
    
    /**
     * Setter for the listwords 
     * 
     * @param string newlistwords the new listwords 
     * @return boolean true if success
     */
    public function setListWords($newlistwords) {
        if (Store::setPositionInstanceListWords($this->id, $newlistwords) && $this->setChanged()) {
            $this->listwords = $newlistwords;
            return true;
        }
    }
        
    /**
     * Getter for the searchwords , if set, the instance shows only objects that
     * contain the specified searchwords
     * 
     * @return string the searchwords 
     */
    public function getSearchWords() {
        return $this->searchwords;
    }
    
    /**
     * Setter for the searchwords 
     * 
     * @param string newsearchwords the new searchwords 
     * @return boolean true if success
     */
    public function setSearchWords($newsearchwords) {
        if (Store::setPositionInstanceSearchWords($this->id, $newsearchwords) && $this->setChanged()) {
            $this->searchwords = $newsearchwords;
            return true;
        }
    }
        
    /**
     * Getter for the parent, if set, the instance shows only objects that
     * are descendant of this parent
     * 
     * @return object the parent
     */
    public function getParent() {
        return $this->parent;
    }
    
    /**
     * Setter for the parent
     * 
     * @param object the new parent
     * @return boolean true if success
     */
    public function setParent($newparent) {
        if (Store::setPositionInstanceParentId($this->id, $newparent->getId()) && $this->setChanged()) {
            $this->parent = $newparent;
            return true;
        }
    }

    /**
     * Getter for activeitems. 
     * 
     * @return boolean  if the instance show active items
     */
    public function getActiveItems() {
        return $this->activeitems;
    }
    
    /**
     * Setter for active items
     * 
     * @param boolean newbool the new value for activeitems
     * @return boolean true if success
     */
    public function setActiveItems($newbool) {
        if (Store::setPositionInstanceActiveItems($this->id, $newbool) && $this->setChanged()) {
            $this->activeitems = $newbool;
            return true;
        }
    }        

    /**
     * Getter for the orderby , if set, the instance shows only objects that
     * contain the specified orderby
     * 
     * @return string the orderby 
     */
    public function getOrderBy() {
        return $this->orderby;
    }
    
    /**
     * Setter for the orderby 
     * 
     * @param string neworderby the new orderby 
     * @return boolean true if success
     */
    public function setOrderBy($neworderby) {
        if (Store::setPositionInstanceOrderBy($this->id, $neworderby) && $this->setChanged()) {
            $this->orderby = $neworderby;
            return true;
        }
    }
        
    /**
     * Getter for groupby. 
     * 
     * @return boolean true if the instance show active items
     */
    public function getGroupBy() {
        return $this->groupby;
    }
    
    /**
     * Setter for group by
     * 
     * @param boolean newbool the new value for groupby
     * @return boolean true if success
     */
    public function setGroupBy($newbool) {
        if (Store::setPositionInstanceGroupBy($this->id, $newbool) && $this->setChanged()) {
            $this->groupby = $newbool;
            return true;
        }
    }        

}

?>
