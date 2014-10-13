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
 * the content item, that contains a basic piece of content
 *
 */
class PositionContentItem extends NamedEntity implements PositionContent {
    private $body; // the content
    private $inputtype; // used for the interface (textbox, listbox, etc)
    private $rootobject; // performance optimization for instances, points to the object template root; for searchable and not instanciable templates: points to the root of the searchable parent
    private $template; // performance optimization for instances, points to the template; for searchable and not instanciable templates: points to the template of the searchable parent
    private $hasinternallinks; // performance optimization, tells whether the contentitembody contains links, has impact on caching
    
    // constants for position content items
    const INPUTTYPE_TEXTAREA = 'INPUTTYPE_TEXTAREA'; // input type
    const INPUTTYPE_INPUTBOX = 'INPUTTYPE_INPUTBOX'; // input type
    const INPUTTYPE_COMBOBOX = 'INPUTTYPE_COMBOBOX'; // input type
    const INPUTTYPE_UPLOADEDFILE = 'INPUTTYPE_UPLOADEDFILE'; // input type
    
    /**
     * Construct the contentitem, retrieve all the attributes
     * 
     * @param position the containing position
     * @param resultset the attributes for the positioncontentitem
     */
    public function __construct($position, $attr) {
        $this->id = $attr->id;
        $this->tablename = Store::getTablePositionContentitems();
        $this->container = $position;
        $this->body = $attr->body;
        $this->inputtype = $attr->inputtype;
        $this->rootobject = Objects::getObject($attr->rootobjectid);
        $this->template = Templates::getTemplate($attr->templateid);
        $this->hasinternallinks = $attr->hasinternallinks;
        parent::initAttributes($attr);
    }
    
    public function setChanged() {
        // update the internal links bit
        $this->setHasInternalLinks($this->checkInternalLinks());
        // do the rest
        return parent::setChanged();
    }
    
    /**
     * Return the type of content in this position
     * 
     * @return constant
     */
    public function getType() {
        return PositionContent::POSITIONTYPE_CONTENTITEM;
    }
        
    /**
     * Getter for the content item body
     * 
     * @return string the content item body
     */
    public function getBody() {
        return $this->body;
    }
    
    /**
     * Setter for the content item body
     * 
     * @param string newbody the new body
     * @return boolean true if success
     */
    public function setBody($newbody) {
        if (Store::setPositionContentItemBody($this->id, $newbody) && $this->setChanged()) {
            $this->body = $newbody;
            return true;
        }
    }

    /**
     * Getter for the input type
     * 
     * @return string the input type
     */
    public function getInputType() {
        return $this->inputtype;
    }
    
    /**
     * Setter for the content item input type
     * 
     * @param string newinputtype the new input type
     * @return boolean true if success
     */
    public function setInputType($newinputtype) {
        if (Store::setPositionContentItemInputType($this->id, $newinputtype) && $this->setChanged()) {
            $this->inputtype = $newinputtype;
            // if the new input type is a file, empty the body
            if ($newinputtype == PositionContentItem::INPUTTYPE_UPLOADEDFILE) {
                $this->setBody('');
            }
            return true;
        }
    }

    /**
     * Getter for the root object, this is maintained for performance 
     * reasons, the getter is probably not necessary, it is used while
     * retrieving items from the store.
     * Root object is used for instances, this can be the template root
     * for this item, but also a root higher in the tree, because of
     * searchable/instanceallowed settings in the template.
     * 
     * @return int the root object
     */
    public function getRootObject() {
        return $this->rootobject;
    }
    
    /**
     * Setter for the root object. This one doesn't trigger setChanged, because
     * it is a cache value maintained by the system, not by the user.
     * 
     * @param object the new root object
     * @return boolean true if success
     */
    public function setRootObject($newrootobject) {
        if (Store::setPositionContentItemRootObjectId($this->id, $newrootobject->getId())) {
            $this->rootobject = $newrootobject;
            return true;
        }
    }

    /**
     * Getter for the template, this is maintained for performance 
     * reasons, the getter is probably not necessary, it is used while
     * retrieving items from the store.
     * See also the root object, this may not be the actual template for
     * this object, because it depends on searchable and instanceallowed
     * settings for the template.
     * 
     * @return template
     */
    public function getTemplate() {
        return $this->template;
    }
    
    /**
     * Setter for the template. This one doesn't trigger setChanged, because
     * it is a cache value maintained by the system, not by the user.
     * 
     * @param template the new template
     * @return boolean true if success
     */
    public function setTemplate($newtemplate) {
        if (Store::setPositionContentItemTemplateId($this->id, $newtemplate->getId())) {
            $this->template = $newtemplate;
            return true;
        }
    }

    /**
     * Getter for hasinternallinks. True if the content item contains internal
     * links, maintained to be able to outdate the cache
     * after changes and internal links may be outdated
     * 

     * @return boolean true if has internal links
     */
    public function getHasInternalLinks() {
        return $this->hasinternallinks;
    }
    
    /**
     * Setter for has internal links
     * 
     * @param int newbool the new value for hasinternallinks
     * @return boolean true if success
     */
    public function setHasInternalLinks($newbool) {
        // Don't use setChanged as this one is called from setChanged. 
        // Results in infinite loop. Fun but not useful.
        if (Store::setPositionContentItemHasInternalLinks($this->id, $newbool)) {
            $this->hasinternallinks = $newbool;
            return true;
        }
    }

    /**
     * Check for internal links in the content, if there are internal links
     * the content item must be outdated in the cache under certain conditions
     * 
     * @return boolean
     */
    private function checkInternalLinks() {
        if (preg_match("/([\[])([0-9]+)([\|])/", $this->body) > 0) {
            return true;
        }
        return false;
    }

     /**
     * Calculate the folder for this positioncontentitem
     * 
     * @return string
     */
    public function calculateFolder() {
        $objectid = $this->getContainer()->getContainer()->getContainer()->getId();
        $positionid = $this->getContainer()->getId();
        $counter = floor($objectid / 1000);
        if ($counter >= 1000000) {
            // reduce counter to a number below 1000000 (yeah, right, ever the optimist :))
            $counter = $counter - (floor($counter / 1000000) * 1000000);
        }
        $folder = Settings::getSetting(Setting::SITE_UPLOAD_LOCATION)->getValue() . "/" . floor($counter / 1000) . "/" . floor($counter) . "/object" . $objectid . "/" . $positionid;
        return $folder;
    }
    
}