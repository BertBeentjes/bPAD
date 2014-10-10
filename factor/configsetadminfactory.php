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
 * Factor the set configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigSetAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific set is requested, show this one, otherwise open with
        // the default set
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validSet(Request::getCommand()->getValue())) {
                $set = Sets::getSet(Request::getCommand()->getValue());
            }
        } else {
            $sets = Sets::getSets();
            $row = $sets->fetchObject();
            $set = Sets::getSet($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the sets
        $sets = Sets::getSets();
        $section .= $this->factorListBox($baseid . '_setlist', CommandFactory::configSet($this->getObject(), $this->getMode(), $this->getContext()), $sets, $set->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_SETS));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addSet($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_SET)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_SETS));
        // factor the default set
        $content = '';
        // open the first set
        $content = $this->factorConfigSetContent($set);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the set config edit content 
     * 
     * @param set $set
     * @return string
     */
    private function factorConfigSetContent($set) {
        $baseid = 'CP' . $this->getObject()->getId() . '_set';
        $section = '';
        $admin = '';
        // set name
        if ($set->getIsBpadDefined()) {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editSetName($set), $set->getName(), Helper::getLang(AdminLabels::ADMIN_SET_NAME), 'disabled');
        } else {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editSetName($set), $set->getName(), Helper::getLang(AdminLabels::ADMIN_SET_NAME));
        }
        // remove button 
        if ($set->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeSet($this->getObject(), $set, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_SET)));
        }
        $admin .= $this->factorSection($baseid . '_header', $section);
        return $admin;
    }

}