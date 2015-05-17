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
 * Check authorizations for everything in the site
 *
 * @since 0.4.0
 */
class Authorization {

    const PAGE_VIEW = 1; // view a page, the most basic of authorizations
    const OBJECT_VIEW = 2; // view an object
    const OBJECT_UPLOADFILE = 3; // upload a file, recommended not to give to anonymous user
    const OBJECT_FRONTEND_RESPOND = 4; // respond based upon one default template, this one can be given to anonymous users
    const OBJECT_FRONTEND_CREATOR_EDIT = 5; // edit your response
    const OBJECT_FRONTEND_CREATOR_DEACTIVATE = 6; // deactivate your response
    const OBJECT_FRONTEND_ADD = 7; // add from a selection of templates
    const OBJECT_FRONTEND_EDIT = 8; // edit content
    const OBJECT_FRONTEND_DEACTIVATE = 9; // deactivate content
    const OBJECT_MANAGE = 10; // manage objects, combination of all of the above and more, like changing layouts/styles/structures, forms, products, orders
    const LAYOUT_MANAGE = 11; // create and edit layouts
    const STYLE_MANAGE = 12; // create and edit styles
    const STRUCTURE_MANAGE = 13; // create and edit structures
    const TEMPLATE_MANAGE = 14; // create and edit templates and sets (using sets is an integral part of administering templates)
    const SYSTEM_MANAGE = 15; // full system clearance
    const LANGUAGE_MANAGE = 16; // edit the language strings
    const SETTING_MANAGE = 17; // edit settings
    const USER_MANAGE = 18; // edit users
    const ROLE_MANAGE = 19; // edit roles
    const AUTHORIZATION_MANAGE = 20; // ??
    const LSS_VERSION_MANAGE = 21; // manage where layouts, structures, styles come from
    const USER_SHOW_ADMIN_BAR = 22; // show admin bar, TODO: remove, is obsolete
    const USER_FLUSH_ARCHIVE = 23; // flush the archive (for a specific user?)

    /**
     * Check a permission for an object for the currect user
     * 
     * @param object $object
     * @param int $permissiontype
     * @return boolean true if permission granted
     */
    private static function objectPermission($object, $permissiontype) {
        // get the relevant object user group roles
        $objectusergrouproles = $object->getObjectUserGroupRoles();
        // get the user groups for the user
        $usergroups = Authentication::getUser()->getUserGroups();
        // get an object user group role
        foreach ($objectusergrouproles as $objectusergrouprole) {
            foreach ($usergroups as $usergroup) {
                // if the user is in a group assigned to this object
                if ($objectusergrouprole->getUserGroup()->getId() == $usergroup->getId()) {
                    // get the permissions for the role
                    $permission = $objectusergrouprole->getRole()->getPermissions();
                    switch ($permissiontype) {
                        case self::PAGE_VIEW:
                            if ($permission->getViewObject()) {
                                return true;
                            }
                            break;
                        case self::OBJECT_VIEW:
                            // true if you have view permission, or when the object is part of a template when you have manage template permission
                            // also true if you are the creator of the object, or have edit or manage permissions
                            // TODO: frontend creator permissions can be based upon user id (as is now), OR for public users on session id (not covered yet!)
                            // -> create a function to decide whether the current user OR session is the creator of the object
                            if ((($permission->getViewObject() || $permission->getManageContent() || $permission->getFrontendEdit() || ($permission->getFrontendCreatorEdit() && $object->getCreateUser()->getId() == Authentication::getUser()->getId())) && $object->getIsTemplate() == false) || ($this->getPagePermission(self::TEMPLATE_MANAGE) == true && $object->getIsTemplate() == true)) {
                                return true;
                            }
                            break;
                        case self::OBJECT_FRONTEND_RESPOND:
                            if ($permission->getFrontendRespond() && !$object->getIsTemplate()) {
                                return true;
                            }
                            break;
                        case self::OBJECT_FRONTEND_CREATOR_EDIT:
                            if ($permission->getFrontendCreatorEdit() && $object->getCreateUser()->getId() == Authentication::getUser()->getId() && !$object->getIsTemplate()) {
                                return true;
                            }
                            break;
                        case self::OBJECT_FRONTEND_CREATOR_DEACTIVATE:
                            if ($permission->getFrontendCreatorDeactivate() && $object->getCreateUser()->getId() == Authentication::getUser()->getId() && !$object->getIsTemplate()) {
                                return true;
                            }
                            break;
                        case self::OBJECT_FRONTEND_ADD:
                            if ($permission->getFrontendAdd() && !$object->getIsTemplate()) {
                                return true;
                            }
                            break;
                        case self::OBJECT_FRONTEND_EDIT:
                            if ($permission->getFrontendEdit() && !$object->getIsTemplate()) {
                                return true;
                            }
                            break;
                        case self::OBJECT_FRONTEND_DEACTIVATE:
                            if ($permission->getFrontendDeactivate() && !$object->getIsTemplate()) {
                                return true;
                            }
                            break;
                        case self::OBJECT_MANAGE:
                            // true if you have manage permission, or when the object is part of a template when you have manage template permission
                            if (($permission->getManageContent() && !$object->getIsTemplate()) || (self::getPagePermission(self::TEMPLATE_MANAGE) && $object->getIsTemplate())) {
                                return true;
                            }
                            break;
                    }
                    // page permissions are checked on the site root
                    if ($object->isSiteRoot()) {
                        switch ($permissiontype) {
                            case self::AUTHORIZATION_MANAGE:
                                if ($permission->getManageAuthorization()) {
                                    return true;
                                }
                                break;
                            case self::LAYOUT_MANAGE:
                                if ($permission->getManageLayout()) {
                                    return true;
                                }
                                break;
                            case self::STYLE_MANAGE:
                                if ($permission->getManageStyle()) {
                                    return true;
                                }
                                break;
                            case self::STRUCTURE_MANAGE:
                                if ($permission->getManageStructure()) {
                                    return true;
                                }
                                break;
                            case self::LANGUAGE_MANAGE:
                                if ($permission->getManageLanguage()) {
                                    return true;
                                }
                                break;
                            case self::ROLE_MANAGE:
                                if ($permission->getManageRole()) {
                                    return true;
                                }
                                break;
                            case self::SYSTEM_MANAGE:
                                if ($permission->getManageSystem()) {
                                    return true;
                                }
                                break;
                            case self::TEMPLATE_MANAGE:
                                if ($permission->getManageTemplate()) {
                                    return true;
                                }
                                break;
                            case self::USER_FLUSH_ARCHIVE:
                                if ($permission->getFlushArchive()) {
                                    return true;
                                }
                                break;
                            case self::USER_MANAGE:
                                if ($permission->getManageUser()) {
                                    return true;
                                }
                                break;
                            case self::SETTING_MANAGE:
                                if ($permission->getManageSetting()) {
                                    return true;
                                }
                                break;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Check whether the user has the permission to do something with a page in the site
     * 
     * @param int permission type
     * @return boolean true if permission granted
     */
    public static function getPagePermission($permissiontype) {
        return self::objectPermission(Objects::getObject(SysCon::SITE_ROOT_OBJECT), $permissiontype);
    }

    /**
     * Check whether the user has the permission to do something with an object in the site
     * 
     * @param object the object
     * @param int permission type
     * @return boolean true if permission granted
     */
    public static function getObjectPermission($object, $permissiontype) {
        return self::objectPermission($object, $permissiontype);
    }

}