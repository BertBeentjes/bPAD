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
 * Factory to create the move panel, that shows places to move the item to
 *
 * @since 0.4.0
 */
class MoveAdminFactory extends AdminFactory {

    private $object; // the object to factor the move function for

    /**
     * Factor the admin/add functions
     */

    public function factor() {
        $section = '';
        $admin = '';
        if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_EDIT)) {
            // only template based root objects that aren't part of a larger structure (template is searchable) are moveable
            if (!$this->getObject()->getTemplate()->isDefault() && !$this->getObject()->getTemplate()->getSearchable() && !$this->getObject()->getIsTemplate() && $this->getObject()->getIsObjectTemplateRoot()) {
                $objectversion = $this->getObject()->getVersion($this->getMode());
                // with general permission, show all templates or the ones in the requested set
                // check for available positions
                if ($objectversion->hasAvailablePositions()) {
                    $baseid = 'M' . $this->getObject()->getId() . '_T';
                    // create the add buttons
                    $buttons = $this->factorButtonGroupAlt($this->factorMoveButtons($baseid));
                    // add a cancel button
                    $buttons .= $this->factorButton($baseid . '_cancel', CommandFactory::moveObjectCancel($this->getObject()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL));
                    // create a section
                    $admin .= $this->factorSection($baseid . '_cancelbutton', $buttons);
                } else {
                    Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                }
            }
        }
        $this->setContent($admin);
    }

    /**
     * Create move buttons for an object
     * 
     * @param string $baseid
     * @return string
     */
    protected function factorMoveButtons($baseid) {
        $buttons = '';
        // get the set of the template of this object, the set defines where
        // the object can be moved to
        $set = $this->getObject()->getTemplate()->getSet();
        if (!$set->isDefault()) {
            $targets = Objects::getTargetObjectBySet($set);
            $objects = array();
            while ($row = $targets->fetchObject()) {
                $object = Objects::getObject($row->id);
                // check whether this is a viable target:
                // 1. the object must be active
                // 2. the object must not be the current parent (can't move to the same place) or the parent of the target object (that will create a loop)
                // 3. the object must not be a template
                // 4. the object must have a pn type layout
                // 5. the user must have the permission to edit the object
                if (self::checkTargetObject($object, $this->getObject(), $this->getMode()) && !$object->getIsTemplate() && $object->getActive() && $object->getVersion($this->getMode())->getLayout()->isPNType() && (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT))) {
                    $objectname = Objects::getObject($row->id)->getVersion($this->getMode())->getObjectTemplateRootObject()->getNameForMove($this->getMode());
                    $objects[$objectname] = $object;
                }
            }
            ksort($objects);
            foreach ($objects as $objectname => $object) {
                // create the move buttons
                $buttons .= $this->factorButton($baseid . '_O' . $object->getId(), CommandFactory::moveObjectToObject($this->getObject(), $object, Modes::getMode(Mode::VIEWMODE), $this->getContext()), $objectname);
            }
        }
        return $buttons;
    }

    /**
     * Set the object string to factor
     * 
     * @param object $newobject
     */
    public function setObject($newobject) {
        $this->object = $newobject;
    }

    /**
     * Get the object from the factory
     * 
     * @return object
     */
    public function getObject() {
        return $this->object;
    }
    
    /**
     * Check whether this is a viable target object, check the parent and check for loops
     * 
     * @param object $target
     * @param object $object
     * @param mode $mode
     * @return boolean true if viable target
     */
    public static function checkTargetObject($target, $object, $mode) {
        if ($target->getId() == $object->getVersion($mode)->getObjectParent()->getId()) {
            return false;
        }
        $parent = $target->getVersion($mode)->getObjectParent();
        while (!$parent->isSiteRoot()) {
            if ($parent->getIsTemplate() || $parent->getId() == $object->getId() || $parent->getId() == $parent->getVersion($mode)->getObjectParent()->getId()) {
                return false;
            }
            $parent = $parent->getVersion($mode)->getObjectParent();
        }
        return true;
    }

}

?>
