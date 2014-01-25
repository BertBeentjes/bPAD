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
 * Execute an action on an object
 *
 * @since 0.4.0
 */
class ExecuteObjectAction {

    private $object; // the object
    private $action; // the type of action
    private $addpermissionsfromsource; // for copy actions
    private $activate; // for publish actions
    private $argument; // for copy actions

    // execute action types. The same class is used for these actions, instead
    // of make a super class with the shared functions, to show explicitly where
    // the actions have differences in execution

    const EXECUTE_PUBLISH = 'EXECUTE_PUBLISH'; // do a publish
    const EXECUTE_CANCEL = 'EXECUTE_CANCEL'; // do a cancel
    const EXECUTE_COPY = 'EXECUTE_COPY'; // do a create
    const EXECUTE_NONE = 'EXECUTE_NONE'; // do nothing

    /**
     * Construct 
     */

    public function __construct() {
        $this->setAction(self::EXECUTE_NONE);
    }

    /**
     * publish an object, recurse into child objects based upon the same template
     * 
     * @param boolean $activate activate the object after publishing (optional)
     * @return boolean true
     */
    public function publish($activate = true) {
        $this->activate = $activate;
        // define the action
        $this->setAction(self::EXECUTE_PUBLISH);
        if ($this->getObject()->hasChanged()) {
            // archive the current view version
            $this->getObject()->getVersion(Modes::getMode(Mode::VIEWMODE))->setMode(Modes::getMode(Mode::ARCHIVEMODE));
            // move the edit version to view mode
            $this->getObject()->getVersion(Modes::getMode(Mode::EDITMODE))->setMode(Modes::getMode(Mode::VIEWMODE));
            // now create a new edit mode version
            $this->createObjectVersionEditModeFromViewMode();
        } else {
            $this->recurseIntoChildren();
        }
        // and activate the object
        if ($this->activate) {
            $this->getObject()->setActive(true);
            // this is no longer a new object
            $this->getObject()->setNew(false);
        }
        return true;
    }

    /**
     * Cancel the changes to the object, recurse into the child objects based upon the same template
     * 
     * @return boolean true
     */
    public function cancel() {
        if ($this->getObject()->getNew()) {
            // remove the item to the trash can
            $this->getObject()->setNewRecursive(false);
        } else {
            // define the action
            $this->setAction(self::EXECUTE_CANCEL);
            // archive the current edit version
            $this->getObject()->getVersion(Modes::getMode(Mode::EDITMODE))->setMode(Modes::getMode(Mode::ARCHIVEMODE));
            // now create a new edit mode version
            $this->createObjectVersionEditModeFromViewMode();
        }
        return true;
    }

    /**
     * Keep the changes to the object in concept mode, recurse into the child objects based upon the same template
     * 
     * @return boolean true
     */
    public function keep() {
        // TODO: maybe set the new bit for the object to false, but not sure yet...
        return true;
    }

    /**
     * Create a new object based upon another object, recurse into template based children
     * 
     * @param object $source the source object to copy from
     * @param positionobject $positionobject the positionobject to add the new object to
     * @param boolean $addpermissionsfromsource copy the object user group role permissions from the source object, in addition to inheriting from the parent
     * @return boolean
     */
    public function copy($source, $positionobject, $addpermissionsfromsource = false) {
        $this->addpermissionsfromsource = $addpermissionsfromsource;
        // define the action
        $this->setAction(self::EXECUTE_COPY);
        // create a new object 
        $this->setObject(Objects::newObject());
        // set the new bit for the new object, since this is a new object
        $this->getObject()->setNew(true);
        // attach the object to the position and refresh the object with the version with the correct parent info
        $positionobject->setObject($this->getObject());
        // for readability
        $parent = $positionobject->getContainer()->getContainer()->getContainer();
        // copy the user permissions from the new parent
        $this->copyPermissions($parent);
        if ($this->addpermissionsfromsource) {
            // add the user permissions from the source
            $this->copyPermissions($source);
        }
        // copy the attributes from the source
        $this->copyObject($source);
        // copy the source object view version to the edit mode version of the object        
        $this->copyObjectVersion($source->getVersion(Modes::getMode(Mode::VIEWMODE)), $this->getObject()->getVersion(Modes::getMode(Mode::EDITMODE)));
        $publisher = new ExecuteObjectAction();
        $publisher->setObject($this->getObject());
        $publisher->publish(false);
        return true;
    }

    /**
     * Move an object a position up in the parent
     * 
     * @param object $object the object to move up
     * @return boolean true if success
     */
    public function moveObjectUp($object, $mode) {
        if ($object->getVersion($mode)->isMoveableUp()) {
            $sourceposition = $object->getVersion($mode)->getPositionParent();
            $sourcepositionnr = $sourceposition->getNumber();
            $targetposition = $object->getVersion($mode)->getMoveUpPosition();
            if (isset($targetposition)) {
                $targetpositionnr = $targetposition->getNumber();
                $sourceposition->setNumber($targetpositionnr);
                $targetposition->setNumber($sourcepositionnr);
            }
        }
        return false;
    }

    /**
     * Move an object a position down in the parent
     * 
     * @param object $object the object to move up
     * @return boolean true if success
     */
    public function moveObjectDown($object, $mode) {
        if ($object->getVersion($mode)->isMoveableDown()) {
            $sourceposition = $object->getVersion($mode)->getPositionParent();
            $sourcepositionnr = $sourceposition->getNumber();
            $targetposition = $object->getVersion($mode)->getMoveDownPosition();
            if (isset($targetposition)) {
                $targetpositionnr = $targetposition->getNumber();
                $sourceposition->setNumber($targetpositionnr);
                $targetposition->setNumber($sourcepositionnr);
            }
        }
        return false;
    }

    /**
     * Copy an object (the attributes)
     * 
     * @param object $source
     */
    private function copyObject($source) {
        $this->getObject()->setIsObjectTemplateRoot($source->getIsTemplateRoot() || $source->getIsObjectTemplateRoot());
        $this->getObject()->setIsTemplate(false);
        $this->getObject()->setIsTemplateRoot(false);
        $this->getObject()->setName($source->getName());
        $this->getObject()->setSet($source->getSet());
        $this->getObject()->setTemplate($source->getTemplate());
    }
    
    /**
     * Create a new Object Version in Edit mode, based upon the one in View mode
     */
    private function createObjectVersionEditModeFromViewMode() {
        // create a new object version for the edit mode by copying the view mode
        $source = $this->getObject()->getVersion(Modes::getMode(Mode::VIEWMODE));
        $target = $this->getObject()->newVersion(Modes::getMode(Mode::EDITMODE));
        $this->copyObjectVersion($source, $target);
        // touch the view version, to update the cache and set the change date to
        // a value more recent than the edit version
        $this->getObject()->getVersion(Modes::getMode(Mode::VIEWMODE))->setChanged();
        $this->recurseIntoChildren();
    }
    
    /**
     * recurse into the children of the object and execute the same action for 
     * these children.
     */
    private function recurseIntoChildren() {
        // recurse into child objects based upon the same template
        $children = $source->getEditChildren();
        $publisher = array();
        $counter = 0;
        foreach ($children as $child) {
            $publisher[$counter] = new ExecuteObjectAction($child);
            $publisher[$counter]->setObject($child);
            switch ($this->getAction()) {
                case self::EXECUTE_PUBLISH:
                    $publisher[$counter]->publish($this->activate);
                    break;
                case self::EXECUTE_CANCEL:
                    $publisher[$counter]->cancel();
                    break;
                default:
                    break;
            }
            $counter = $counter + 1;
        }
    }

    /**
     * Copy an object version
     * 
     * @param objectversion $source 
     * @param objectversion $target 
     */
    private function copyObjectVersion($source, $target) {
        // copy the attribute values
        $target->setLayout($source->getLayout());
        $target->setStyle($source->getStyle());
        $target->setArgument($source->getArgument());
        if ($target->getArgument()->isCreate() && !$target->getContainer()->getIsTemplate()) {
            // create a new argument for this version
            if (!isset($this->argument)) {
                $this->argument = Arguments::newArgument();
            }
            $target->setArgument($this->argument);
        }
        $target->setArgumentDefault($source->getArgumentDefault());
        $target->setInheritLayout($source->getInheritLayout());
        $target->setInheritStyle($source->getInheritStyle());
        $target->setTemplate($source->getTemplate());
        // create new positions and copy them
        $positions = $source->getPositions();
        foreach ($positions as $position) {
            // create new position
            $targetposition = $target->newPosition();
            // copy the position
            $this->copyPosition($position, $targetposition);
        }
    }

    /**
     * Copy a position
     * 
     * @param position $source 
     * @param position $target 
     */
    private function copyPosition($source, $target) {
        // copy the attribute values
        $target->setStructure($source->getStructure());
        $target->setStyle($source->getStyle());
        $target->setNumber($source->getNumber());
        $target->setInheritStyle($source->getInheritStyle());
        $target->setInheritStructure($source->getInheritStructure());
        // create the position content
        switch ($source->getPositionContent()->getType()) {
            case PositionContent::POSITIONTYPE_CONTENTITEM:
                // create content item and copy attributes
                $targetcontentitem = $target->newPositionContentItem();
                $this->copyPositionContentItem($source->getPositionContent(), $targetcontentitem);
                break;
            case PositionContent::POSITIONTYPE_INSTANCE:
                // create instance and copy attributes
                $targetinstance = $target->newPositionInstance();
                $this->copyPositionInstance($source->getPositionContent(), $targetinstance);
                break;
            case PositionContent::POSITIONTYPE_REFERRAL:
                // create referral and copy attributes
                $targetreferral = $target->newPositionReferral();
                $this->copyPositionReferral($source->getPositionContent(), $targetreferral);
                if ($targetreferral->getArgument()->isCreate() && !$target->getContainer()->getContainer()->getIsTemplate()) {
                    // create a new argument for this version
                    if (!isset($this->argument)) {
                        $this->argument = Arguments::newArgument();
                    }
                    $targetreferral->setArgument($this->argument);
                }
                break;
            case PositionContent::POSITIONTYPE_OBJECT:
                // create an empty positionobject and point to source object
                $targetpositionobject = $target->newPositionObject(false);
                switch ($this->getAction()) {
                    case self::EXECUTE_CANCEL:
                    case self::EXECUTE_PUBLISH:
                        // point the target position object to the same object as the source position object
                        $targetpositionobject->setObject($source->getPositionContent()->getObject());
                        break;
                    case self::EXECUTE_COPY:
                        // recursive copy the object from the source position object to the target position object
                        // create a new executer
                        $exec = new ExecuteObjectAction();
                        // copy
                        $exec->copy($source->getPositionContent()->getObject(), $targetpositionobject, $this->addpermissionsfromsource);
                        break;
                }
                break;
            default:
                // empty position, do nothing
                break;
        }
    }

    /**
     * Copy a positioncontentitem
     * 
     * @param positioncontentitem $source 
     * @param positioncontentitem $target 
     */
    private function copyPositionContentItem($source, $target) {
        // first the input type, it may reset certain values
        $target->setInputType($source->getInputType());
        $target->setName($source->getName());
        $target->setRootObject($source->getRootObject());
        $target->setTemplate($source->getTemplate());
        $target->setBody($source->getBody());
        // if the source contains an uploaded file, copy it to the new contentitem
        if ($source->getInputType() == PositionContentItem::INPUTTYPE_UPLOADEDFILE && $source->getBody() > '') {
            $sourcefolder = $source->calculateFolder();
            $targetfolder = $target->calculateFolder();
            // check whether the file exists
            if (file_exists($sourcefolder . '/' . $source->getBody())) {
                // check whether the target folder exists
                if (!is_dir($targetfolder)) {
                    // create the directory for this object
                    mkdir($targetfolder, intval(Settings::getSetting(Setting::SITE_UPLOAD_LOCATION_PERMISSIONS)->getValue(), 8), true);
                }
                // clean the target folder if there's anything in it
                $files = glob($targetfolder . '/*'); // get all file names
                if (isset($files)) {
                    if (count($files) > 0) {
                        foreach ($files as $file) { // iterate files
                            if (is_file($file)) {
                                unlink($file); // delete file
                            }
                        }
                    }
                }
                // now copy the source file
                copy($sourcefolder . '/' . $source->getBody(), $targetfolder . '/' . $target->getBody());
            }
        }
    }

    /**
     * Copy a positioninstance
     * 
     * @param positioninstance $source 
     * @param positioninstance $target 
     */
    private function copyPositionInstance($source, $target) {
        $target->setActiveItems($source->getActiveItems());
        $target->setGroupBy($source->getGroupBy());
        $target->setListWords($source->getListWords());
        $target->setObject($source->getObject());
        $target->setOrderBy($source->getOrderBy());
        $target->setOutdated(true);
        $target->setParent($source->getParent());
        $target->setSearchWords($source->getSearchWords());
        $target->setTemplate($source->getTemplate());
    }

    /**
     * Copy a positionreferral
     * 
     * @param positionreferral $source 
     * @param positionreferral $target 
     */
    private function copyPositionReferral($source, $target) {
        $target->setArgument($source->getArgument());
        $target->setNumberOfItems($source->getNumberOfItems());
        $target->setOrderBy($source->getOrderBy());
    }

    /**
     * The object this executer is working on
     * 
     * @return object
     */
    public function getObject() {
        return $this->object;
    }

    /**
     * Set the object for the executer
     * 
     * @param object $newobject
     */
    public function setObject($newobject) {
        $this->object = $newobject;
    }

    /**
     * The action being executed
     * 
     * @return string
     */
    private function getAction() {
        return $this->action;
    }

    /**
     * Set the action for the executer
     * 
     * @param string $newaction
     */
    private function setAction($newaction) {
        $this->action = $newaction;
    }

    /**
     * Copy the permissions from one object to another
     * 
     * @param object $source
     */
    private function copyPermissions($source) {
        $newroles = $source->getObjectUserGroupRoles();
        foreach ($newroles as $newrole) {
            // only copy inheritable permissions
            if ($newrole->getInherit() == true) {
                $this->getObject()->newObjectUserGroupRole($this->getObject(), $newrole->getUserGroup(), $newrole->getRole(), $newrole->getInherit());
            }
        }
    }

}

?>
