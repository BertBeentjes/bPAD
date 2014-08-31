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
        // factor the role
        $content = '';
        // open the first role
        $content = $this->factorConfigRoleContent($role);
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
        $admin = '';
        $section = '';
        // role name
        $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editRoleName($role), $role->getName(), Helper::getLang(AdminLabels::ADMIN_ROLE_NAME));
        // remove button 
        if ($role->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeRole($this->getObject(), $role, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_ROLE)));
        }
        $admin .= $this->factorSection($baseid . '_header', $section);
        $section = '';
        // TODO: add permissions for this role
        $permission = $role->getPermissions();
        $section .= $this->factorCheckBox($baseid . '_perm_vo', CommandFactory::editPermission($permission, Permission::PERMISSION_VIEW_OBJECT), $permission->getViewObject(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_VIEW_OBJECT));        
        $section .= $this->factorCheckBox($baseid . '_perm_fr', CommandFactory::editPermission($permission, Permission::PERMISSION_FRONTENT_RESPOND), $permission->getFrontendRespond(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_FRONTENT_RESPOND));        
        $section .= $this->factorCheckBox($baseid . '_perm_fce', CommandFactory::editPermission($permission, Permission::PERMISSION_FRONTEND_CREATOR_EDIT), $permission->getFrontendCreatorEdit(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_FRONTEND_CREATOR_EDIT));        
        $section .= $this->factorCheckBox($baseid . '_perm_fcd', CommandFactory::editPermission($permission, Permission::PERMISSION_FRONTEND_CREATOR_DEACTIVATE), $permission->getFrontendCreatorDeactivate(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_FRONTEND_CREATOR_DEACTIVATE));        
        $section .= $this->factorCheckBox($baseid . '_perm_uf', CommandFactory::editPermission($permission, Permission::PERMISSION_UPLOAD_FILE), $permission->getUploadFile(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_UPLOAD_FILE));        
        $admin .= $this->factorSection($baseid . '_section_use', $section, Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_USER));
        $section = '';

        $section .= $this->factorCheckBox($baseid . '_perm_mc', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_CONTENT), $permission->getManageContent(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_CONTENT));        
        $section .= $this->factorCheckBox($baseid . '_perm_fe', CommandFactory::editPermission($permission, Permission::PERMISSION_FRONTEND_EDIT), $permission->getFrontendEdit(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_FRONTEND_EDIT));        
        $section .= $this->factorCheckBox($baseid . '_perm_fadd', CommandFactory::editPermission($permission, Permission::PERMISSION_FRONTEND_ADD), $permission->getFrontendAdd(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_FRONTEND_ADD));        
        $section .= $this->factorCheckBox($baseid . '_perm_fd', CommandFactory::editPermission($permission, Permission::PERMISSION_FRONTEND_DEACTIVATE), $permission->getFrontendDeactivate(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_FRONTEND_DEACTIVATE));
        $admin .= $this->factorSection($baseid . '_section_edit', $section, Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_EDITOR));
        $section = '';

        $section .= $this->factorCheckBox($baseid . '_perm_mlay', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_LAYOUT), $permission->getManageLayout(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_LAYOUT));        
        $section .= $this->factorCheckBox($baseid . '_perm_mstr', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_STRUCTURE), $permission->getManageStructure(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_STRUCTURE));        
        $section .= $this->factorCheckBox($baseid . '_perm_msty', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_STYLE), $permission->getManageStyle(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_STYLE));        
        $section .= $this->factorCheckBox($baseid . '_perm_mt', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_TEMPLATE), $permission->getManageTemplate(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_TEMPLATE));        
        $admin .= $this->factorSection($baseid . '_section_design', $section, Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_DESIGNER));
        $section = '';
        
        $section .= $this->factorCheckBox($baseid . '_perm_sab', CommandFactory::editPermission($permission, Permission::PERMISSION_SHOW_BAR), $permission->getShowAdminBar(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_SHOW_ADMIN_BAR));        
        $section .= $this->factorCheckBox($baseid . '_perm_msys', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_SYSTEM), $permission->getManageSystem(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_SYSTEM));        
        $section .= $this->factorCheckBox($baseid . '_perm_farc', CommandFactory::editPermission($permission, Permission::PERMISSION_FLUSH_ARCHIVE), $permission->getFlushArchive(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_FLUSH_ARCHIVE));        
        $section .= $this->factorCheckBox($baseid . '_perm_mlan', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_LANGUAGE), $permission->getManageLanguage(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_LANGUAGE));        
        $section .= $this->factorCheckBox($baseid . '_perm_mset', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_SETTING), $permission->getManageSetting(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_SETTING));        
        $section .= $this->factorCheckBox($baseid . '_perm_mu', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_USER), $permission->getManageUser(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_USER));        
        $section .= $this->factorCheckBox($baseid . '_perm_mr', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_ROLE), $permission->getManageRole(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_ROLE));        
        $section .= $this->factorCheckBox($baseid . '_perm_ma', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_AUTHORIZATION), $permission->getManageAuthorization(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_AUTHORIZATION));        
        $section .= $this->factorCheckBox($baseid . '_perm_mlss', CommandFactory::editPermission($permission, Permission::PERMISSION_MANAGE_LSS_VERSION), $permission->getManageLSSVersion(), Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_MANAGE_LSS_VERSION));        
        $admin .= $this->factorSection($baseid . '_section_admin', $section, Helper::getLang(AdminLabels::ADMIN_PERMISSIONS_ADMINISTRATOR));
        $section = '';
        
        return $admin;
    }

}