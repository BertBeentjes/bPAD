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
 * Factor the user configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigUserAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific user is requested, show this one, otherwise open with
        // the default user
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validUser(Request::getCommand()->getValue())) {
                $user = Users::getUser(Request::getCommand()->getValue());
            }
        } else {
            $users = Users::getUsers();
            $row = $users->fetchObject();
            $user = Users::getUser($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = '';
        $section = '';
        // factor the users
        $users = Users::getUsers();
        $section .= $this->factorListBox($baseid . '_userlist', CommandFactory::configUser($this->getObject(), $this->getMode(), $this->getContext()), $users, $user->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_USERS));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addUser($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_USER)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_USERS));
        // factor the user
        $content = '';
        // open the first user
        $content = $this->factorConfigUserContent($user);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the user config edit content 
     * 
     * @param user $user
     * @return string
     */
    private function factorConfigUserContent($user) {
        $baseid = 'CP' . $this->getObject()->getId() . '_user';
        $section = '';
        $admin = '';
        // user name
        if ($user->getName() == SysCon::PUBLIC_USER) {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editUserName($user), $user->getName(), Helper::getLang(AdminLabels::ADMIN_USER_NAME), 'disabled');
        } else {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editUserName($user), $user->getName(), Helper::getLang(AdminLabels::ADMIN_USER_NAME));
        }
        // login counter reset
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_logincounter', CommandFactory::editUserLoginCounter($this->getObject(), $user, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_USER_LOGIN_COUNTER) . $user->getLoginCounter()));
        // remove button 
        if ($user->isRemovable()) {
            $section .= $this->factorButton($baseid . '_remove', CommandFactory::removeUser($this->getObject(), $user, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_USER));
        }
        $admin .= $this->factorSection($baseid . '_header', $section);
        $section = '';
        // insert user fields: password, firstname, lastname
        // TODO: create password input box (two boxes, one button that is active when both boxes are filled equally)
        $section .= $this->factorTextInput($baseid . '_password', CommandFactory::editUserPassword($user), '', Helper::getLang(AdminLabels::ADMIN_USER_PASSWORD));
        $section .= $this->factorTextInput($baseid . '_firstname', CommandFactory::editUserFirstName($user), $user->getFirstName(), Helper::getLang(AdminLabels::ADMIN_USER_FIRST_NAME));
        $section .= $this->factorTextInput($baseid . '_lastname', CommandFactory::editUserLastName($user), $user->getLastName(), Helper::getLang(AdminLabels::ADMIN_USER_LAST_NAME));
        // insert usergroups the user is a member of
        $userusergroups = $user->getUserGroups();
        $usergroups = UserGroups::getUserGroups();
        while ($usergroup = $usergroups->fetchObject()) {
            $hasusergroup = false;
            if (array_key_exists($usergroup->id, $userusergroups)) {
                $hasusergroup = true;
            }
            $section .= $this->factorCheckBox($baseid . '_uug' . $usergroup->id, CommandFactory::editUserUserGroup($user, UserGroups::getUserGroup($usergroup->id)), $hasusergroup, $usergroup->name);
        }
        $admin .= $this->factorSection($baseid . '_section', $section);
        return $admin;
    }

}