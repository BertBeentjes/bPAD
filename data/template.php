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
 * A template is made of objects and is used as a template for user content 
 * creation. The user can select a template to add content to the site. The
 * objects of the template will be copied, so the user can fill them with
 * the new content.
 * 
 * @since 0.4.0
 */
class Template extends SettedEntity {

    const DEFAULT_TEMPLATE = 1;

    private $isbpaddefined; // true if the template is defined by bpad
    private $deleted; // true if the template has been deleted
    private $structure; // the structure to use in a #pn# layout for the position where this template is used
    private $style; // the style to use in a #pn# layout for the position where this template is used
    private $instanceallowed; // true if content based upon the template can be shown in instances. If instanceallowed is true, searchable can't be true, and vice versa. They can both be false.
    private $searchable; // true if the content based upon this template is searchable, the first parent that has instanceallowed will be shown in the instance. If instanceallowed is true, searchable can't be true, and vice versa. They can both be false.
    private $object; // the root object for the template

    /**
     * constructor, sets all the attributes for the template
     * 
     * @param id the id of the template to construct
     */

    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableTemplates();
        $this->loadAttributes();
    }

    /**
     * load the attributes
     * 
     * @return boolean true if success
     * @throws Exception when the template can't be found
     */
    private function loadAttributes() {
        if ($result = Store::getTemplate($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }

    /**
     * initialize the attributes
     * 
     * @return boolean true if success
     */
    protected function initAttributes($attr) {
        $this->deleted = (bool) $attr->deleted;
        $this->structure = Structures::getStructure($attr->structureid);
        $this->style = Styles::getStyle($attr->styleid);
        $this->instanceallowed = (bool) $attr->instanceallowed;
        $this->searchable = (bool) $attr->searchable;
        $this->isbpaddefined = (bool) $attr->isbpaddefined;
        parent::initAttributes($attr);
        return true;
    }

    /**
     * setter for the name, check whether the template is bpad defined or not first
     * 
     * @param newname the name
     * @return boolean  if success
     * @throws exception if the update in the store fails or if the style is bPAD defined
     */
    public function setName($newname) {
        if (!$this->isbpaddefined) {
            parent::setName($newname);
            return true;
        }
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_IS_DEFINED_BY_BPAD) . ' @ ' . __METHOD__);
    }

    /**
     * has the template been deleted or not
     * 
     * @return boolean deleted or not
     */
    public function getDeleted() {
        return $this->deleted;
    }

    /**
     * setter for deleted
     *  
     * @param boolean true if deleted
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setDeleted($newbool) {
        if (Store::setTemplateDeleted($this->id, $newbool) && $this->setChanged()) {
            $this->deleted = $newbool;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * get the root object for the template
     * 
     * @return object
     */
    public function getRootObject() {
        // set here, to prevent infinite loops where the root object loads the template loads the root object loads the template .... 
        if (!isset($this->object)) {
            $this->setRootObject();
        }
        return $this->object;
    }

    /**
     * setter for the root object (this does not update the store, it reads the store!!)
     */
    public function setRootObject() {
        if ($result = Store::getTemplateRootObject($this->getId())) {
            if ($row = $result->fetchObject()) {
                $this->object = Objects::getObject($row->objectid);
            }
        }
    }

    /**
     * the structure to use for the position that a template based object
     * is created in, in a #pn# layout
     * 
     * @return structure
     */
    public function getStructure() {
        return $this->structure;
    }

    /**
     * set the structure for the template
     *  
     * @param structure the new structure
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setStructure($newstructure) {
        if (Store::setTemplateStructureId($this->id, $newstructure->id) && $this->setChanged()) {
            $this->structure = $newstructure;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * the style to use for the position that a template based object
     * is created in, in a #pn# layout
     * 
     * @return style
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * set the style for the template
     *  
     * @param style the new styleid
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setStyle($newstyle) {
        if (Store::setTemplateStyleId($this->id, $newstyle->id) && $this->setChanged()) {
            $this->style = $newstyle;
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * is the template defined by bpad or not, no setter, this bool is only
     * changed through update scripts 
     * 
     * @return boolean isbpaddefined or not
     */
    public function getIsBpadDefined() {
        return $this->isbpaddefined;
    }

    /**
     * can objects based upon this template be shown in an instance or not
     * 
     * @return boolean instanceallowed or not
     */
    public function getInstanceAllowed() {
        return $this->instanceallowed;
    }

    /**
     * setter for instanceallowed
     *  
     * @param boolean true if instanceallowed
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setInstanceAllowed($newbool) {
        if (Store::setTemplateInstanceAllowed($this->id, $newbool) && $this->setChanged()) {
            $this->instanceallowed = $newbool;
            // if this boolean is changed, the instances are outdated
            CachePositionInstances::outdateInstances();
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * if the template is not instanceallowed, is it at least searchable for 
     * instances. If searchable, the first instanceallowed ancestor is shown 
     * in an instance.
     * 
     * @return boolean searchable or not
     */
    public function getSearchable() {
        return $this->searchable;
    }

    /**
     * setter for searchable
     *  
     * @param boolean true if searchable
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setSearchable($newbool) {
        if (Store::setTemplateSearchable($this->id, $newbool) && $this->setChanged()) {
            $this->searchable = $newbool;            
            // if this boolean is changed, the instances are outdated
            CachePositionInstances::outdateInstances();
            return true;
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * check for the default template (id = 1)
     * 
     * @return boolean default or not
     */
    public function isDefault() {
        return ($this->getId() == self::DEFAULT_TEMPLATE);
    }

    /**
     * Is the template used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getTemplateUsed($this->getId())) {
            return true;
        }
        return false;
    }
    
    /**
     * Is the template removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return !$this->isUsed() && !($this->getId()==Template::DEFAULT_TEMPLATE);
    }
    
}