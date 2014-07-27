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
 * Factor the role configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigRoleAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific role is requested, show this one, otherwise open with
        // the default role
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validRole(Request::getCommand()->getValue())) {
                $role = Roles::getRole(Request::getCommand()->getValue());
            }
        } else {
            $roles = Roles::getRoles();
            $row = $roles->fetchObject();
            $role = Roles::getRole($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = '';
        $section = '';
        // factor the roles
        $roles = Roles::getRoles();
        $section .= $this->factorListBox($baseid . '_rolelist', CommandFactory::configRole($this->getObject(), $this->getMode(), $this->getContext()), $roles, $role->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_ROLES));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addRole($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_ROLE)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_ROLES));
        // factor the default role
        $content = '';
        // open the first role
        // $content = $this->factorConfigRoleContent($role);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the role config edit content 
     * 
     * @param role $role
     * @return string
     */
    private function factorConfigRoleContent($role) {
        $baseid = 'CP' . $this->getObject()->getId() . '_role';
        $section = '';
        $admin = '';
        // role name
        if ($role->getIsBpadDefined()) {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editRoleName($role), $role->getName(), Helper::getLang(AdminLabels::ADMIN_ROLE_NAME), 'disabled');
        } else {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editRoleName($role), $role->getName(), Helper::getLang(AdminLabels::ADMIN_ROLE_NAME));
        }
        // remove button 
        if ($role->isRemovable()) {
            $section .= $this->factorButton($baseid . '_remove', CommandFactory::removeRole($this->getObject(), $role, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_ROLE));
        }
        $admin .= $this->factorSection($baseid . '_header', $section);
        return $admin;
    }

}