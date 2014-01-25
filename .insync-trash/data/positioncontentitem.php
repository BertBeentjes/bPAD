<?php
/**
 * Extends StoredEntity and contains one of the types of positions, in this case
 * the content item, that contains a basic piece of content
 *

 */
class PositionContentItem extends NamedEntity implements PositionContent {
    private $body; // the content
    private $inputtype; // used for the interface (textbox, listbox, etc)
    private $rootobject; // performance optimization for instances, points to the object template root
    private $template; // performance optimization for instances, points to the template
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
     * @return bool true if success
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
     * @return bool true if success
     */
    public function setInputType($newinputtype) {
        if (Store::setPositionContentItemInputType($this->id, $newinputtype) && $this->setChanged()) {
            $this->inputtype = $newinputtype;
            return true;
        }
    }

    /**
     * Getter for the root object, this is maintained for performance 
     * reasons, the getter is probably not necessary, it is used while
     * retrieving items from the store
     * 

     * @return int the root object
     */
    public function getRootObject() {
        return $this->rootobject;
    }
    
    /**
     * Setter for the root object
     * 

     * @param object the new root object
     * @return bool true if success
     */
    public function setRootObject($newrootobject) {
        if (Store::setPositionContentItemRootObjectId($this->id, $newrootobject->getId()) && $this->setChanged()) {
            $this->rootobject = $newrootobject;
            return true;
        }
    }

    /**
     * Getter for the template, this is maintained for performance 
     * reasons, the getter is probably not necessary, it is used while
     * retrieving items from the store
     * 

     * @return template
     */
    public function getTemplate() {
        return $this->template;
    }
    
    /**
     * Setter for the template
     * 

     * @param template the new template
     * @return bool true if success
     */
    public function setTemplate($newtemplate) {
        if (Store::setPositionContentItemTemplateId($this->id, $newtemplate->getId()) && $this->setChanged()) {
            $this->template = $newtemplate;
            return true;
        }
    }

    /**
     * Getter for hasinternallinks. True if the content item contains internal
     * links, maintained to be able to outdate the cache
     * after changes and internal links may be outdated
     * 

     * @return bool true if has internal links
     */
    public function getHasInternalLinks() {
        return $this->hasinternallinks;
    }
    
    /**
     * Setter for has internal links
     * 

     * @param int newbool the new value for hasinternallinks
     * @return bool true if success
     */
    public function setHasInternalLinks($newbool) {
        if (Store::setPositionContentItemHasInternalLinks($this->id, $newbool) && $this->setChanged()) {
            $this->hasinternallinks = $newbool;
            return true;
        }
    }
}

?>
