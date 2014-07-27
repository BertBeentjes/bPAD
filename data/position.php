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
 * Every object has several positions, in a position, there is another object,
 * a referral, an instance or a content item. 
 * 
 * Positions depend on the mode, so are a part of the objectversion
 * 
 * @since 0.4.0
 */
class Position extends StoredEntity {

    private $style; // the style of this position
    private $number; // the number of this position, defines the order of positions, starts at 1 and goes up, no gaps
    private $structure; // the structure
    private $inheritstyle; // does this position inherit its style from a template?
    private $inheritstructure; // does it inherit the structure from a template?
    private $positioncontent; // whatever is in this position: an object,  a referral, an instance or a content item

    /**
     * construct the position
     * 
     * @param objectversion the objectversion these positions are part of
     * @param attr a resultset object, containing the attributes of the position
     */

    public function __construct($objectversion, $attr) {
        $this->tablename = Store::getTablePositions();
        $this->container = $objectversion;
        $this->id = $attr->id;
        $this->style = Styles::getStyle($attr->styleid);
        $this->number = $attr->number;
        $this->structure = Structures::getStructure($attr->structureid);
        $this->inheritstructure = $attr->inheritstructure;
        $this->inheritstyle = $attr->inheritstyle;
        parent::initAttributes($attr);
        $this->setPositionContent();
    }

    /**
     * get the position content
     * 
     * @return positioncontent
     */
    public function getPositionContent() {
        return $this->positioncontent;
    }

    /**
     * set the position content
     * 
     */
    public function setPositionContent() {
        // get whatever is in this position
        unset($this->positioncontent);
        if ($result = Store::getPositionObject($this->getId())) {
            while ($attr = $result->fetchObject()) {
                $this->positioncontent = new PositionObject($this, $attr);
            }
        }
        if (!isset($this->positioncontent)) {
            if ($result = Store::getPositionContentItem($this->getId())) {
                while ($attr = $result->fetchObject()) {
                    $this->positioncontent = new PositionContentItem($this, $attr);
                }
            }
        }
        if (!isset($this->positioncontent)) {
            if ($result = Store::getPositionInstance($this->getId())) {
                while ($attr = $result->fetchObject()) {
                    $this->positioncontent = new PositionInstance($this, $attr);
                }
            }
        }
        if (!isset($this->positioncontent)) {
            if ($result = Store::getPositionReferral($this->getId())) {
                while ($attr = $result->fetchObject()) {
                    $this->positioncontent = new PositionReferral($this, $attr);
                }
            }
        }
        if (!isset($this->positioncontent)) {
            $this->positioncontent = new PositionEmpty();
        }
    }

    /**
     * create a new position content item
     * 
     * @return positioncontentitem
     */
    public function newPositionContentItem() {
        if ($this->getPositionContent()->getType() == PositionContentItem::POSITIONTYPE_EMPTY) {
            $this->setChanged();
            // create a content item
            Store::insertPositionContentItem($this->getId());
            // get the content from the store
            $this->setPositionContent();
            $this->getPositionContent()->setInputType(PositionContentItem::INPUTTYPE_INPUTBOX);
            // return the new content item
            return $this->getPositionContent();
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ALREADY_EXISTS) . ' @ ' . __METHOD__);
    }

    /**
     * create a new position instance
     * 
     * @return positioninstance
     */
    public function newPositionInstance() {
        if ($this->getPositionContent()->getType() == PositionContentItem::POSITIONTYPE_EMPTY) {
            $this->setChanged();
            // create a content item
            Store::insertPositionInstance($this->getId());
            // get the content from the store
            $this->setPositionContent();
            // return the new content item
            return $this->getPositionContent();
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ALREADY_EXISTS) . ' @ ' . __METHOD__);
    }

    /**
     * create a new position referral
     * 
     * @return positionreferral
     */
    public function newPositionReferral() {
        if ($this->getPositionContent()->getType() == PositionContentItem::POSITIONTYPE_EMPTY) {
            $this->setChanged();
            // create a content item
            Store::insertPositionReferral($this->getId());
            // get the content from the store
            $this->setPositionContent();
            // return the new content item
            return $this->getPositionContent();
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ALREADY_EXISTS) . ' @ ' . __METHOD__);
    }

    /**
     * create a new position object
     * 
     * @return positionobject
     */
    public function newPositionObject($createobject = true) {
        if ($this->getPositionContent()->getType() == PositionContentItem::POSITIONTYPE_EMPTY) {
            $this->setChanged();
            // create a content item
            Store::insertPositionObject($this->getId());
            // get the content from the store
            $this->setPositionContent();
            if ($createobject) {
                // create a new object in the positionobject
                $this->getPositionContent()->setObject(Objects::newObject());
                // initialize the object
                $parent = $this->getContainer()->getContainer();
                $child = $this->getPositionContent()->getObject();
                $child->setActive($parent->getActive());
                $child->setIsTemplate($parent->getIsTemplate());
                if ($child->getIsTemplate()) {
                    $child->setTemplate($parent->getTemplate());
                }
                // add objectusergrouproles
                $newroles = $parent->getObjectUserGroupRoles();
                foreach ($newroles as $newrole) {
                    // only copy inheritable permissions
                    if ($newrole->getInherit() == true) {
                        $child->newObjectUserGroupRole($child, $newrole->getUserGroup(), $newrole->getRole(), $newrole->getInherit());
                    }
                }
            }
            // return the new content item
            return $this->getPositionContent();
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ALREADY_EXISTS) . ' @ ' . __METHOD__);
    }

    /**
     * get the structure
     * 
     * @return structure
     */
    public function getStructure() {
        return $this->structure;
    }

    /**
     * set the structure
     * 
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setStructure($newstructure) {
        if (Store::setPositionStructureId($this->id, $newstructure->getId()) && $this->setChanged()) {
            $this->structure = $newstructure;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the style
     * 
     * @return style
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * set the style
     * 
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setStyle($newstyle) {
        if (Store::setPositionStyleId($this->id, $newstyle->getId()) && $this->setChanged()) {
            $this->style = $newstyle;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the number 
     * 
     * @return int number 
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * set the number 
     * 
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setNumber($newnumber) {
        if (Store::setPositionNumber($this->id, $newnumber) && $this->setChanged()) {
            $this->number = $newnumber;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the inherit style value, if true, the position inherits its style
     * from the template it is based upon
     * 
     * @return boolean inherit the style or not
     */
    public function getInheritStyle() {
        return $this->inheritstyle;
    }

    /**
     * set the inherit style bool, only available for positions in a template
     * 
     * @param bool the new value
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setInheritStyle($newbool) {
        if (Store::setPositionInheritStyle($this->id, $newbool) && $this->setChanged()) {
            $this->inheritstyle = $newbool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the inherit structure value, if true, the position inherits its structure
     * from the template it is based upon
     * 
     * @return boolean inherit the structure or not
     */
    public function getInheritStructure() {
        return $this->inheritstructure;
    }

    /**
     * set the inherit structure bool, only available for positions in a template
     * 
     * @param bool the new value
     * @return boolean  if success
     * @throws exception if store not available
     */
    public function setInheritStructure($newbool) {
        if (Store::setPositionInheritStructure($this->id, $newbool) && $this->setChanged()) {
            $this->inheritstructure = $newbool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Remove the content from a position
     * 
     * @return boolean true if success
     */
    public function removePositionContent() {
        switch ($this->getPositionContent()->getType()) {
            case PositionContent::POSITIONTYPE_CONTENTITEM:
                return Store::deletePositionContentItem($this->getPositionContent()->getId());
                break;
            case PositionContent::POSITIONTYPE_OBJECT:
                return Store::deletePositionObject($this->getPositionContent()->getId());
                break;
            case PositionContent::POSITIONTYPE_INSTANCE:
                // first delete the cache
                Store::deletePositionInstanceCacheObjectsByPositionInstanceId($this->getPositionContent()->getId());
                // then the instance itself
                return Store::deletePositionInstance($this->getPositionContent()->getId());
                break;
            case PositionContent::POSITIONTYPE_REFERRAL:
                return Store::deletePositionReferral($this->getPositionContent()->getId());
                break;            
        }
    }

}