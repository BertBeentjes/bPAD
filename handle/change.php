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
 * Execute a change, after the change, parts of the frontend are refreshed through
 * the event manager
 *
 * @since 0.4.0
 */
class Change extends Respond {

    /**
     * construct an change handler, read the command
     * 
     */
    public function __construct() {
        // execute the change
        $this->executeChange();
        // send an empty response, or in some cases an error message, to the front end
        $this->setResponse(new Response());
        $this->getResponse()->setType('text/plain');
    }

    /**
     * Execute the requested change, call the correct executor
     */
    public function executeChange() {
        if (Request::getCommand()->getMode() !== NULL) {
            if (Request::getCommand()->getMode()->getId() != Mode::EDITMODE) {
                Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                return;
            }
        }
        // check command sequence, if a newer request from this user to change the same item is found, do nothing
        if ($this->checkNewerCommand()) {
            // check for changes from other users
            if ($this->checkUnlocked()) {
                switch (Request::getCommand()->getItem()) {
                    case 'templateobject':
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        $template = Templates::getTemplate($addressparts[0]);
                        $object = Objects::getObject($addressparts[1]);
                        $number = $addressparts[2];
                        Execute::addTemplateObject($template, $object, $number);
                        break;
                    case 'object':
                        $object = Objects::getObject(Request::getCommand()->getItemAddress());
                        Execute::changeObject($object);
                        break;
                    case 'objectversion':
                        $objectversion = Objects::getObject(Request::getCommand()->getItemAddress())->getVersion(Request::getCommand()->getMode());
                        Execute::changeObjectVersion($objectversion);
                        break;
                    case 'objectposition':
                        // add or remove a position from an object
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        $object = Objects::getObject($addressparts[0]);
                        Execute::changeObjectPosition($object, $addressparts[1]);
                        break;
                    case 'position':
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        $position = Objects::getObject($addressparts[0])->getVersion(Request::getCommand()->getMode())->getPosition($addressparts[1]);
                        Execute::changePosition($position);
                        break;
                    case 'form':
                        $form = FormStorages::getFormStorage(Request::getCommand()->getItemAddress());
                        Execute::changeForm($form);
                        break;
                    case 'formhandler':
                        $formhandler = FormHandlers::getFormHandler(Request::getCommand()->getItemAddress());
                        Execute::changeFormHandler($formhandler);
                        break;
                    case 'product':
                        $product = Products::getProduct(Request::getCommand()->getItemAddress());
                        Execute::changeProduct($product);
                        break;
                    case 'order':
                        $order = Orders::getOrder(Request::getCommand()->getItemAddress());
                        Execute::changeOrder($order);
                        break;
                    case 'layout':
                        $layout = Layouts::getLayout(Request::getCommand()->getItemAddress());
                        Execute::changeLayout($layout);
                        break;
                    case 'layoutversion':
                        $layout = Layouts::getLayout(Request::getCommand()->getItemAddress());
                        $mode = Request::getCommand()->getMode();
                        $context = Request::getCommand()->getContext();
                        Execute::changeLayoutVersion($layout, $mode, $context);
                        break;
                    case 'structure':
                        $structure = Structures::getStructure(Request::getCommand()->getItemAddress());
                        Execute::changeStructure($structure);
                        break;
                    case 'structureversion':
                        $structure = Structures::getStructure(Request::getCommand()->getItemAddress());
                        $mode = Request::getCommand()->getMode();
                        $context = Request::getCommand()->getContext();
                        Execute::changeStructureVersion($structure, $mode, $context);
                        break;
                    case 'style':
                        $style = Styles::getStyle(Request::getCommand()->getItemAddress());
                        Execute::changeStyle($style);
                        break;
                    case 'styleversion':
                        $style = Styles::getStyle(Request::getCommand()->getItemAddress());
                        $mode = Request::getCommand()->getMode();
                        $context = Request::getCommand()->getContext();
                        Execute::changeStyleVersion($style, $mode, $context);
                        break;
                    case 'styleparam':
                        $styleparam = StyleParams::getStyleParam(Request::getCommand()->getItemAddress());
                        Execute::changeStyleParam($styleparam);
                        break;
                    case 'styleparamversion':
                        $styleparam = StyleParams::getStyleParam(Request::getCommand()->getItemAddress());
                        $mode = Request::getCommand()->getMode();
                        $context = Request::getCommand()->getContext();
                        Execute::changeStyleParamVersion($styleparam, $mode, $context);
                        break;
                    case 'set':
                        $set = Sets::getSet(Request::getCommand()->getItemAddress());
                        Execute::changeSet($set);
                        break;
                    case 'user':
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        if (count($addressparts) == 2) {
                            $user = Users::getUser($addressparts[0]);
                            $usergroup = UserGroups::getUserGroup($addressparts[1]);
                            Execute::changeUser($user, $usergroup);
                        } else {
                            $user = Users::getUser($addressparts[0]);
                            Execute::changeUser($user);
                        }
                        break;
                    case 'usergroup':
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        $usergroup = UserGroups::getUserGroup($addressparts[0]);
                        Execute::changeUserGroup($usergroup);
                        break;
                    case 'role':
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        $role = Roles::getRole($addressparts[0]);
                        Execute::changeRole($role);
                        break;
                    case 'permission':
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        $permission = Permissions::getPermission($addressparts[0]);
                        Execute::changePermission($permission);
                        break;
                    case 'setting':
                        $addressparts = Request::getCommand()->getItemAddressParts();
                        $setting = Settings::getSetting($addressparts[0]);
                        Execute::changeSetting($setting);
                        break;
                    case 'template':
                        $template = Templates::getTemplate(Request::getCommand()->getItemAddress());
                        Execute::changeTemplate($template);
                        break;
                    case 'includefile':
                        $includefile = FileIncludes::getFileInclude(Request::getCommand()->getItemAddress());
                        Execute::changeFileInclude($includefile);
                        break;
                    case 'snippet':
                        $snippet = Snippets::getSnippet(Request::getCommand()->getItemAddress());
                        Execute::changeSnippet($snippet);
                        break;
                    case 'objectusergrouprole':
                        $itemaddressparts = Request::getCommand()->getItemAddressParts();
                        $object = Objects::getObject($itemaddressparts[0]);
                        $usergroup = UserGroups::getUserGroup($itemaddressparts[1]);
                        $role = Roles::getRole($itemaddressparts[2]);
                        Execute::changeObjectUserGroupRole($object, $usergroup, $role);
                        break;
                    default:
                        Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                        break;
                }
            } else {
                Messages::Add(Helper::getLang(Errors::MESSAGE_ITEM_LOCKED));
            }
        }
    }

    /**
     * check whether the command is executable, or is there a newer command from the same session
     */
    private function checkNewerCommand() {
        //   1. a newer change has been made by the same user on the same device (one ajax call has taken a detour) 
        //      -> check the last command with the same characteristics for the same session identifier
        if ($result = Store::getNewerCommand(Request::getCommand()->getItem(), Request::getCommand()->getCommand(), Request::getCommand()->getItemAddress(), Request::getCommand()->getSessionIdentifier(), Request::getCommand()->getCommandNumber())) {
            if ($row = $result->fetchObject()) {
                return false;
            }
        }
        return true;
    }

    /**
     * check whether the command is executable, or is there a newer command from another session
     */
    private function checkUnlocked() {
        //   2. a change has been made by another user (or the same user on another device) that hasn't been distributed to this device/session yet
        //      -> check for commands with the same characteristics after the last command id
        if ($result = Store::getOtherCommand(Request::getCommand()->getItem(), Request::getCommand()->getCommand(), Request::getCommand()->getItemAddress(), Request::getCommand()->getSessionIdentifier(), Request::getCommand()->getLastCommandId())) {
            if ($row = $result->fetchObject()) {
                return false;
            }
        }
        return true;
    }

}