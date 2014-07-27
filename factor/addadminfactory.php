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
 * Factory to create the add panel, that shows templates available for adding content
 *
 * @since 0.4.0
 */
class AddAdminFactory extends AdminFactory {

    private $object; // the object to factor the add function for

    /**
     * Factor the admin/add functions
     */

    public function factor() {
        $section = '';
        $admin = '';
        if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_ADD)) {
            $objectversion = $this->getObject()->getVersion($this->getMode());
            // with general permission, show all templates or the ones in the requested set
            // check for available positions
            if ($objectversion->hasAvailablePositions()) {
                $baseid = 'A' . $this->getObject()->getId() . '_T';
                // create the add buttons
                $buttons = $this->factorButtonGroupAlt($this->factorAddButtons($this->getObject(), 0, $baseid));
                // add a cancel button
                $buttons .= $this->factorButton($baseid . '_cancel', CommandFactory::addObjectCancel($this->getObject()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL));
                // create a section
                $admin .= $this->factorSection($baseid . '_cancelbutton', $buttons);
            } else {
                Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
            }
        } elseif (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_RESPOND)) {
            // with only respond permissions, show the default add template
            // check for available positions
            if ($objectversion->hasAvailablePositions()) {
                // TODO: check that the default template is set (TODO: also do this check before showing the add button)
                // TODO: get the template
                // TODO: create the add button (TODO: use this button instead of the generic add button for responders)
            } else {
                Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
            }
        }

        $this->setContent($admin);
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

}