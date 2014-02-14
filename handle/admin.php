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
 * Return an admin function to the frontend
 *
 * @since 0.4.0
 */
class Admin extends Respond {

    /**
     * construct an admin handler, read the command
     * 
     */
    public function __construct() {
        // default response, for debug purposes
        $this->setResponse(new Response());
        $this->getResponse()->setType('text/plain');
        switch (Request::getCommand()->getCommandMember()) {
            // create the content for the edit panel
            case 'edit':
                $addressparts = Request::getCommand()->getItemAddressParts();
                $object = Objects::getObject($addressparts[1]);
                if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT)) {
                    $editadminfactory = new EditAdminFactory();
                    // initialize the admin factory
                    $editadminfactory->setContext(Request::getCommand()->getContext());
                    $editadminfactory->setMode(Request::getCommand()->getMode());
                    $editadminfactory->setObject($object);
                    // factor the content
                    $editadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($editadminfactory->getContent());
                } else {
                    Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
                }
                break;
            // create the content for the move panel
            case 'move':
                $addressparts = Request::getCommand()->getItemAddressParts();
                $object = Objects::getObject($addressparts[1]);
                if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT)) {
                    $moveadminfactory = new MoveAdminFactory();
                    // initialize the admin factory
                    $moveadminfactory->setContext(Request::getCommand()->getContext());
                    $moveadminfactory->setMode(Request::getCommand()->getMode());
                    $moveadminfactory->setObject($object);
                    // factor the content
                    $moveadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($moveadminfactory->getContent());
                } else {
                    Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
                }
                break;
            // create the content for the add panel with templates to add based upon the set
            case 'add':
                $object = Objects::getObject(Request::getCommand()->getItemAddress());
                if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_ADD) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_RESPOND)) {
                    // create the add panel
                    $addadminfactory = new AddAdminFactory();
                    // initialize the admin factory
                    $addadminfactory->setContext(Request::getCommand()->getContext());
                    $addadminfactory->setMode(Request::getCommand()->getMode());
                    $addadminfactory->setObject($object);
                    // factor the content
                    $addadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($addadminfactory->getContent());
                } else {
                    Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
                }
                break;
            // create the content for the config panel
            case 'config':
                $object = Objects::getObject(Request::getCommand()->getItemAddress());
                if (Authorization::getPagePermission(Authorization::AUTHORIZATION_MANAGE) || Authorization::getPagePermission(Authorization::LANGUAGE_MANAGE) || Authorization::getPagePermission(Authorization::ROLE_MANAGE) || Authorization::getPagePermission(Authorization::SYSTEM_MANAGE) || Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE) || Authorization::getPagePermission(Authorization::USER_FLUSH_ARCHIVE) || Authorization::getPagePermission(Authorization::USER_MANAGE) || Authorization::getPagePermission(Authorization::SETTING_MANAGE)) {
                    // create the config panel
                    $configadminfactory = new ConfigAdminFactory();
                    // initialize the admin factory
                    $configadminfactory->setContext(Request::getCommand()->getContext());
                    $configadminfactory->setMode(Request::getCommand()->getMode());
                    // factor the config panel
                    $configadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($configadminfactory->getContent());
                } else {
                    Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
                }
                break;
            // create the content for the config panel, there are two versions of the command:
            // one general, that loads all layouts, styles, etc
            // one specific, that loads a specific layout, style, etc
            case 'configlayouts':
            case 'configlayout':
                if (Authorization::getPagePermission(Authorization::LAYOUT_MANAGE)) {
                    // create the config panel
                    $configadminfactory = new ConfigLayoutAdminFactory();
                    // initialize the admin factory
                    $configadminfactory->setContext(Request::getCommand()->getContext());
                    // show the layouts in edit mode
                    $configadminfactory->setMode(Modes::getMode(Mode::EDITMODE));
                    // factor the config panel
                    $configadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($configadminfactory->getContent());
                }
                break;
            case 'configstyles':
            case 'configstyle':
                if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
                    // create the config panel
                    $configadminfactory = new ConfigStyleAdminFactory();
                    // initialize the admin factory
                    $configadminfactory->setContext(Request::getCommand()->getContext());
                    $configadminfactory->setMode(Modes::getMode(Mode::EDITMODE));
                    // factor the config panel
                    $configadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($configadminfactory->getContent());
                }
                break;
            case 'configstyleparams':
            case 'configstyleparam':
                if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
                    // create the config panel
                    $configadminfactory = new ConfigStyleParamAdminFactory();
                    // initialize the admin factory
                    $configadminfactory->setContext(Request::getCommand()->getContext());
                    $configadminfactory->setMode(Modes::getMode(Mode::EDITMODE));
                    // factor the config panel
                    $configadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($configadminfactory->getContent());
                }
                break;
            case 'configstructures':
            case 'configstructure':
                if (Authorization::getPagePermission(Authorization::STRUCTURE_MANAGE)) {
                    // create the config panel
                    $configadminfactory = new ConfigStructureAdminFactory();
                    // initialize the admin factory
                    $configadminfactory->setContext(Request::getCommand()->getContext());
                    $configadminfactory->setMode(Modes::getMode(Mode::EDITMODE));
                    // factor the config panel
                    $configadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($configadminfactory->getContent());
                }
                break;
            case 'configsets':
            case 'configset':
                if (Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE)) {
                    // create the config panel
                    $configadminfactory = new ConfigSetAdminFactory();
                    // initialize the admin factory
                    $configadminfactory->setContext(Request::getCommand()->getContext());
                    $configadminfactory->setMode(Modes::getMode(Mode::EDITMODE));
                    // factor the config panel
                    $configadminfactory->factor();
                    // get the factored item
                    $this->getResponse()->setContent($configadminfactory->getContent());
                }
                break;
            case 'configtemplates':
            case 'configtemplate':
                // create the config panel
                $configadminfactory = new ConfigTemplateAdminFactory();
                // initialize the admin factory
                $configadminfactory->setContext(Request::getCommand()->getContext());
                $configadminfactory->setMode(Modes::getMode(Mode::EDITMODE));
                // factor the config panel
                $configadminfactory->factor();
                // get the factored item
                $this->getResponse()->setContent($configadminfactory->getContent());
                break;
            default:
                throw new Exception(Helper::getLang(Errors::ERROR_COMMAND_CONTENT) . ': ' . Request::getCommand()->getItem() . ' @ ' . __METHOD__);
                break;
        }
    }

}

?>
