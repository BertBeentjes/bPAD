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
 * Factor the usergroup configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigUserGroupAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific usergroup is requested, show this one, otherwise open with
        // the default usergroup
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validUserGroup(Request::getCommand()->getValue())) {
                $usergroup = UserGroups::getUserGroup(Request::getCommand()->getValue());
            }
        } else {
            $usergroups = UserGroups::getUserGroups();
            $row = $usergroups->fetchObject();
            $usergroup = UserGroups::getUserGroup($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = '';
        $section = '';
        // factor the usergroups
        $usergroups = UserGroups::getUserGroups();
        $section .= $this->factorListBox($baseid . '_usergrouplist', CommandFactory::configUserGroup($this->getObject(), $this->getMode(), $this->getContext()), $usergroups, $usergroup->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_USERGROUPS));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addUserGroup($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_USERGROUP)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_USERGROUPS));
        // factor the usergroup
        $content = '';
        // open the first usergroup
        $content = $this->factorConfigUserGroupContent($usergroup);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the usergroup config edit content 
     * 
     * @param usergroup $usergroup
     * @return string
     */
    private function factorConfigUserGroupContent($usergroup) {
        $baseid = 'CP' . $this->getObject()->getId() . '_usergroup';
        $section = '';
        $admin = '';
        // usergroup name
        $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editUserGroupName($usergroup), $usergroup->getName(), Helper::getLang(AdminLabels::ADMIN_USERGROUP_NAME));
        // remove button 
        if ($usergroup->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeUserGroup($this->getObject(), $usergroup, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_USERGROUP)));
        }
        $admin .= $this->factorSection($baseid . '_header', $section);
        // insert users that are member of the user group
        $users = Users::getUsers();
        $section = '';
        while ($row = $users->fetchObject()) {
            $user = Users::getUser($row->id);
            $hasusergroup = false;
            if (array_key_exists($usergroup->getId(), $user->getUserGroups())) {
                 $hasusergroup = true;
            }
            $section .= $this->factorCheckBox($baseid . '_uug' . $user->getId(), CommandFactory::editUserUserGroup($user, $usergroup), $hasusergroup, $user->getName());
        }
        $admin .= $this->factorSection($baseid . '_section', $section);
        return $admin;
    }

}