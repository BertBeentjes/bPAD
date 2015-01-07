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
            // now a new view mode version
            $this->createObjectVersionViewModeFromEditMode();
            // create the cache info needed to search the content
            $this->getObject()->getVersion(Modes::getMode(Mode::VIEWMODE))->recalculatePositionContentitems();
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
            // and do a full delete, this object has never been published so should not be kept
            // first remove the object fromt the parent (deleting the position it was in)
            $this->getObject()->removeFromParent();
            // then call the general function to fully delete objects that aren't in a position,
            // this will loop until no objects to delete are left
            Objects::removeOrphanedObjects();
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
     * Keep the changes to the object in edit mode
     * 
     * @return boolean true
     */
    public function keep() {
        // do nothing.
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
        // touch the edit version, so the object can be published again without a change,
        // but still resulting in updating the cache
        $this->getObject()->getVersion(Modes::getMode(Mode::EDITMODE))->setChanged(true);
        return true;
    }

    /**
     * Move an object to another parent
     * 
     * @param object $target the object to move up
     * @return boolean true if success
     */
    public function moveObject($target, $mode) {
        // remove object from current parent
        $this->getObject()->removeFromParent(false);
        // attach object to new parent in view mode
        if ($this->getObject()->getIsObjectTemplateRoot()) {
            $newpositionobject = $target->getVersion(Modes::getMode(Mode::VIEWMODE))->newTemplateObjectPosition($this->getObject()->getTemplate());
            $newposition = $newpositionobject->getContainer();
        } else {
            $newposition = $target->getVersion(Modes::getMode(Mode::VIEWMODE))->newPosition();
            $newpositionobject = $newposition->newPositionObject(false);
        }
        $newpositionobject->setObject($this->getObject());
        // attach object to new parent in edit mode
        if ($this->getObject()->getIsObjectTemplateRoot()) {
            $newpositionobject = $target->getVersion(Modes::getMode(Mode::EDITMODE))->newTemplateObjectPosition($this->getObject()->getTemplate());
            $newposition = $newpositionobject->getContainer();
        } else {
            $newposition = $target->getVersion(Modes::getMode(Mode::EDITMODE))->newPosition();
            $newpositionobject = $newposition->newPositionObject(false);
        }
        $newpositionobject->setObject($this->getObject());
        return true;
    }

    /**
     * Move an object a position up in the parent
     * 
     * @param object $object the object to move up
     * @return boolean true if success
     */
    public function moveObjectUp($mode) {
        if ($this->getObject()->getVersion($mode)->isMoveableUp()) {
            $sourceposition = $this->getObject()->getVersion($mode)->getPositionParent();
            $sourcepositionnr = $sourceposition->getNumber();
            $targetposition = $this->getObject()->getVersion($mode)->getMoveUpPosition();
            if (isset($targetposition)) {
                $targetpositionnr = $targetposition->getNumber();
                $sourceposition->setNumber($targetpositionnr);
                $targetposition->setNumber($sourcepositionnr);
            }
            return true;
        }
        return false;
    }

    /**
     * Move an object a position down in the parent
     * 
     * @param object $object the object to move up
     * @return boolean true if success
     */
    public function moveObjectDown($mode) {
        if ($this->getObject()->getVersion($mode)->isMoveableDown()) {
            $sourceposition = $this->getObject()->getVersion($mode)->getPositionParent();
            $sourcepositionnr = $sourceposition->getNumber();
            $targetposition = $this->getObject()->getVersion($mode)->getMoveDownPosition();
            if (isset($targetposition)) {
                $targetpositionnr = $targetposition->getNumber();
                $sourceposition->setNumber($targetpositionnr);
                $targetposition->setNumber($sourcepositionnr);
            }
            return true;
        }
        return false;
    }

    /**
     * Copy an object (the attributes)
     * 
     * @param object $source
     */
    private function copyObject($source) {
        // update all attributes of the new object in one statement
        $this->getObject()->copyAttributes($source->getIsTemplateRoot() || $source->getIsObjectTemplateRoot(), false, false, $source->getName(), $source->getSet(), $source->getTemplate());
    }

    /**
     * Create a new Object Version in view mode, based upon the one in edit mode
     */
    private function createObjectVersionViewModeFromEditMode() {
        // create a new object version for the edit mode by copying the view mode
        $source = $this->getObject()->getVersion(Modes::getMode(Mode::EDITMODE));
        $target = $this->getObject()->newVersion(Modes::getMode(Mode::VIEWMODE));
        $this->copyObjectVersion($source, $target);
        // only when the action is publish or cancel (function is only called in
        // those cases, but just to be sure...)
        if ($this->getAction() == self::EXECUTE_PUBLISH || $this->getAction() == self::EXECUTE_CANCEL) {
            $this->recurseIntoChildren();
        }
    }

    /**
     * recurse into the children of the object and execute the same action for 
     * these children.
     */
    private function recurseIntoChildren() {
        // recurse into child objects based upon the same template
        $source = $this->getObject()->getVersion(Modes::getMode(Mode::VIEWMODE));
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
        // update all attributes in one statement
        $target->copyAttributes($source->getLayout(), $source->getStyle(), $source->getArgument(), $source->getArgumentDefault(), $source->getInheritLayout(), $source->getInheritStyle(), $source->getTemplate());
        // check whether to create a new argument for this version
        if ($target->getArgument()->isCreate() && !$target->getContainer()->getIsTemplate()) {
            // create a new argument for this version
            if (NULL === $this->getArgument()) {
                $this->setArgument(Arguments::newArgument());
            }
            $target->setArgument($this->getArgument());
        }
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
        // update all attributes in one statement
        $target->copyAttributes($source->getStructure(), $source->getStyle(), $source->getNumber(), $source->getInheritStructure(), $source->getInheritStyle());
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
                    if (NULL === $this->getArgument()) {
                        $this->setArgument(Arguments::newArgument());
                    }
                    $targetreferral->setArgument($this->getArgument());
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
                        // if an argument has been created, pass it on to any children for reuse
                        if (NULL !== $this->getArgument()) {
                            $exec->setArgument($this->getArgument());
                        }
                        // copy
                        $exec->copy($source->getPositionContent()->getObject(), $targetpositionobject, $this->addpermissionsfromsource);
                        // if available, get the argument from the child for later use
                        if (NULL !== $exec->getArgument()) {
                            $this->setArgument($exec->getArgument());
                        }
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
        // update all attributes in one statement
        $target->copyAttributes($source->getInputType(), $source->getName(), $source->getRootObject(), $source->getTemplate(), $source->getBody());
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
                    if (is_array($files)) {
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
        $target->copyAttributes($source->getActiveItems(), $source->getFillOnLoad(), $source->getUseInstanceContext(), $source->getGroupBy(), $source->getListWords(), $source->getObject(), $source->getOrderBy(), true, $source->getParent(), $source->getSearchWords(), $source->getTemplate(), $source->getMaxItems());
    }

    /**
     * Copy a positionreferral
     * 
     * @param positionreferral $source 
     * @param positionreferral $target 
     */
    private function copyPositionReferral($source, $target) {
        $target->copyAttributes($source->getArgument(), $source->getNumberOfItems(), $source->getOrderBy());
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
     * The argument this executer is working with when copying objects
     * 
     * @return argument
     */
    public function getArgument() {
        return $this->argument;
    }

    /**
     * Set the argument for the executer
     * 
     * @param argument $newargument
     */
    public function setArgument($newargument) {
        $this->argument = $newargument;
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