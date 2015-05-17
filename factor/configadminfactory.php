<?php
/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2015
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
 * Factor the configuration functions
 *
 * @since 0.4.0
 */
class ConfigAdminFactory extends AdminFactory {

    protected $object;

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        // $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // factor the configuration buttons
        $this->setContent($this->factorConfigButtons());
    }

    /**
     * Factor the configuration buttons
     */
    protected function factorConfigButtons() {
        $baseid = 'CP' . $this->getObject()->getId();
        $config = '';
        $config .= $this->factorMainOptions($baseid);
        return $config;
    }

    /**
     * Create the config detail panel for this object
     * 
     * @param string $content
     * @param string $baseid
     * @return string
     */
    protected function factorConfigDetailPanel($baseid, $content = '') {
        // get the panel
        $config = Structures::getStructureByName(LSSNames::STRUCTURE_CONFIG_PANEL)->getVersion($this->getMode(), $this->getContext())->getBody();
        // insert the id
        $config = str_replace(Terms::OBJECT_ITEM_ID, $baseid . '_detail', $config);
        // empty by default
        $config = str_replace(Terms::ADMIN_CONTENT, $content, $config);
        return $config;
    }

    /**
     * Factor the main config options
     * 
     * @param string $baseid
     * @return string
     */
    protected function factorMainOptions($baseid) {
        $configitem = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_MENU_ITEM)->getVersion($this->getMode(), $this->getContext())->getBody();
        $section = '';
        // add form handlers button (check authorization)
        if (Authorization::getPagePermission(Authorization::OBJECT_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configFormHandlers($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_FORM_HANDLERS));
        }
        // add form handlers button (check authorization)
        if (Authorization::getPagePermission(Authorization::OBJECT_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configForms($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_FORMS));
        }
        // add form handlers button (check authorization)
        if (Authorization::getPagePermission(Authorization::OBJECT_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configProducts($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_PRODUCTS));
        }
        // add form handlers button (check authorization)
        if (Authorization::getPagePermission(Authorization::OBJECT_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configOrders($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_ORDERS));
        }
        // add layout button (check authorization)
        if (Authorization::getPagePermission(Authorization::LAYOUT_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configLayouts($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_LAYOUTS));
        }
        // add structure button (check authorization)
        if (Authorization::getPagePermission(Authorization::STRUCTURE_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configStructures($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_STRUCTURES));
        }
        // add style button (check authorization)
        if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configStyles($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_STYLES));
        }
        // add style parameter button (check authorization)
        if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configStyleParams($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_STYLEPARAMS));
        }
        // add set button (check authorization, administering sets is an integral part of the proper use of templates)
        if (Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configSets($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_SETS));
        }
        // add template button (check authorization)
        if (Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configTemplates($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_TEMPLATES));
        }
        // add user button (check authorization)
        if (Authorization::getPagePermission(Authorization::USER_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configUsers($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_USERS));
        }
        // add user button (check authorization)
        if (Authorization::getPagePermission(Authorization::USER_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configUserGroups($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_USERGROUPS));
        }
        // add role button (check authorization)
        if (Authorization::getPagePermission(Authorization::ROLE_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configRoles($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_ROLES));
        }
        // add setting button (check authorization)
        if (Authorization::getPagePermission(Authorization::SETTING_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configSettings($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_SETTINGS));
        }
        // add include files button (check authorization)
        if (Authorization::getPagePermission(Authorization::SYSTEM_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configIncludeFiles($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_INCLUDE_FILES));
        }
        // add snippets button (check authorization)
        if (Authorization::getPagePermission(Authorization::SYSTEM_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configSnippets($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_SNIPPETS));
        }
        // add snippets button (check authorization)
        if (Authorization::getPagePermission(Authorization::SYSTEM_MANAGE)) {
            $section .= $this->factorMenuItem(CommandFactory::configUpdate($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CONFIG_UPDATES));
        }
        return $section;
    }

    /**
     * Factor the config cancel button
     * 
     * @param string $baseid
     * @return string
     */
    protected function factorCancelButton($baseid) {
        $section = '';
        // add a cancel button
        $section .= $this->factorMainButton($baseid . '_cancel', CommandFactory::configCancel($this->getObject()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL));
        return $section;
    }

    /**
     * Factor the config close button
     * 
     * @param string $baseid
     * @return string
     */
    protected function factorCloseButton($baseid) {
        $section = '';
        // add a cancel button
        $section .= $this->factorMainButton($baseid . '_close', CommandFactory::configCancel($this->getObject()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CLOSE));
        return $section;
    }

    /**
     * Set the object to factor
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