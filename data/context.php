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
 * A context is used to present the content in a certain way. Each context
 * can have its own styles, structures, layouts.
 *
 * @since 0.4.0
 */
class Context extends NamedEntity{
    private $incss; // are the styles for this context added to the css or not
    private $backupcontext; // if a style, structure or layout is empty for this context, what context is used for backup?
    private $contextgroupid; // what group is this context part of? Groups are used to serve content to different kinds of clients.
    
    const CONTEXT_DEFAULT = 'CONTEXT_DEFAULT'; // the default context
    const CONTEXT_INSTANCE = 'CONTEXT_INSTANCE'; // the context used for instances
    const CONTEXT_RECYCLEBIN = 'CONTEXT_RECYCLEBIN'; // the context for recycle bin items

    /**
     * Constructor, sets the basic context attributes
     * By setting these attribs, the existence of the context is 
     * verified
     * 
     * @param id contains the context id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableContexts();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getContext($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * initialize the attributes
     * 
     * @param resultset $attr
     * @return boolean true if success
     */
    protected function initAttributes($attr) {
        $this->incss = $attr->incss;
        if ($this->getId()<>$attr->backupcontextid) {
            $this->backupcontext = Contexts::getContext($attr->backupcontextid);
        }
        $this->contextgroup = ContextGroups::getContextGroup($attr->contextgroupid);
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * Get the short name of a context, for use in html
     * 
     * @return string
     */
    public function getShortName() {
        // localize if possible
        $name = Helper::getLang($this->name . '_SHORT');
        if ($name == $this->name . '_SHORT') {
            $name = $this->name;
        }
        return $name;
    }

    /**
     * Get the incss value for the context
     * 
     * @return boolean incss
     */
    public function getInCSS() {
        return $this->incss;
    }
    
    /**
     * Set the incss value for the context
     * 
     * @param boolean $bool the new value for incss
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setInCSS($bool) {
        if (Store::setContextInCSS($this->id, $bool) && $this->setChanged()) {
            $this->incss = $bool;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the backupcontextid value for the context
     * 
     * @return context backupcontext
     */
    public function getBackupContext() {
        return $this->backupcontext;
    }
    
    /**
     * Set the backupcontext for the context
     * 
     * @param int $newbackupcontext the new context
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setBackupContext($newbackupcontext) {
        if (Store::setContextBackupContextId($this->id, $newbackupcontext->getId()) && $this->setChanged()) {
            $this->backupcontext = $newbackupcontext;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the contextgroupid value for the context
     * 
     * @return context contextgroup
     */
    public function getContextGroup() {
        return $this->contextgroup;
    }
    
    /**
     * Set the contextgroup for the context
     * 
     * @param int $newcontextgroup the new context group 
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setContextGroup($newcontextgroup) {
        if (Store::setContextContextGroupId($this->id, $newcontextgroup->getId()) && $this->setChanged()) {
            $this->contextgroup = $newcontextgroup;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Is this context the recycle bin context?
     * Do not use the getter for the name, it localizes the context name
     * 
     * @return boolean true if the recycle bin context
     */
    public function isRecycleBin() {
        return $this->name == self::CONTEXT_RECYCLEBIN;
    }
    
    /**
     * Is the default context or not
     * 
     * @return boolean true if default
     */
    public function isDefault() {
        return $this->name == self::CONTEXT_DEFAULT;
    }

}

?>
