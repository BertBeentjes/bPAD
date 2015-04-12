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
 * Execute requested changes
 */
class Execute {

    /**
     * Execute adding objects based upon a template
     * 
     * @param template $template the template to base the new content upon
     * @param object $object the parent object for the new content
     * @param int $number the number of the new position to add the content in (0 = at the end)
     */
    public static function addTemplateObject($template, $object, $number) {
        // check authorizations, full or respond only
        if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_ADD)) {
            // check set 
            if ($object->getSet()->isDefault() || $object->getSet()->getId() == $template->getSet()->getId()) {
                // create a new position and position object to put the copy of the template in
                $viewpositionobject = $object->getVersion(Modes::getMode(Mode::VIEWMODE))->newTemplateObjectPosition($template, $number);
                $positionobject = $object->getVersion(Modes::getMode(Mode::EDITMODE))->newTemplateObjectPosition($template, $number);
                // get the template root object
                $source = $template->getRootObject();
                // copy the object
                // create the executer
                $exec = new ExecuteObjectAction();
                // store the command success in the old value
                Request::getCommand()->setOldValue($exec->copy($source, $positionobject, true));
                // also couple the new object to the view mode position
                $viewpositionobject->setObject($positionobject->getObject());
            }
        } elseif (Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_RESPOND)) {
            // check default template 
            if ($object->getTemplate()->getId() == $template->getId()) {
                // create a new position and position object to put the copy of the template in
                $viewpositionobject = $object->getVersion(Modes::getMode(Mode::VIEWMODE))->newTemplateObjectPosition($template, $number);
                $positionobject = $object->getVersion(Modes::getMode(Mode::EDITMODE))->newTemplateObjectPosition($template, $number);
                // get the template root object
                $source = $template->getRootObject();
                // copy the object
                // create the executer
                $exec = new ExecuteObjectAction();
                // store the command success in the old value
                Request::getCommand()->setOldValue($exec->copy($source, $positionobject, true));
                // also couple the new object to the view mode position
                $viewpositionobject->setObject($positionobject->getObject());
            }
        }
    }

    /**
     * Execute a change in an object
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param object $object
     */
    public static function changeObject($object) {
        // first check authorization
        if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'objectname':
                    // validate
                    if (Validator::isObjectName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($object->getName());
                        // set the new value
                        $object->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'objectactive':
                    // template objects can't be deactivated
                    if (!$object->getIsTemplate()) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($object->getActive());
                        // set the new value
                        if ($object->getActive() == true) {
                            $object->setActiveRecursive(false);
                        } else {
                            $object->setActiveRecursiveTemplateBasedChildren(true);
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'objectset':
                    if (Validator::validSet(Request::getCommand()->getValue()) && $object->getIsTemplate() == true) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($object->getSet()->getId());
                        // set the new value
                        $object->setSet(Sets::getSet(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'publishobject':
                    // create the executer
                    $exec = new ExecuteObjectAction();
                    $exec->setObject($object);
                    // store the command success in the old value
                    Request::getCommand()->setOldValue($exec->publish());
                    break;
                case 'cancelobject':
                    // create the executer
                    $exec = new ExecuteObjectAction();
                    $exec->setObject($object);
                    // store the command success in the old value
                    Request::getCommand()->setOldValue($exec->cancel());
                    break;
                case 'cancelconfig':
                    // store the command success in the old value
                    Request::getCommand()->setOldValue(true);
                    break;
                case 'cancelmove':
                    // store the command success in the old value
                    Request::getCommand()->setOldValue(true);
                    break;
                case 'keepobject':
                    // create the executer
                    $exec = new ExecuteObjectAction();
                    $exec->setObject($object);
                    // store the command success in the old value
                    Request::getCommand()->setOldValue($exec->keep());
                    break;
                case 'moveobject':
                    // create the executer
                    $exec = new ExecuteObjectAction();
                    $exec->setObject($object);                    
                    $target = Objects::getObject(Request::getCommand()->getValue());
                    // check move conditions (permissions, right type of object, right set)
                    // both for the object and for the target
                    $mode = Modes::getMode(Mode::EDITMODE);
                    // --> these checks are also done in moveadminfactory!
                    if (!$object->getTemplate()->isDefault() && !$object->getTemplate()->getSearchable() && !$object->getIsTemplate() && $object->getIsObjectTemplateRoot() && MoveAdminFactory::checkTargetObject($target, $object, $mode) && $object->getTemplate()->getSet()->getId()==$target->getSet()->getId() && !$target->getIsTemplate() && $target->getActive() && $target->getVersion($mode)->getLayout()->isPNType() && (Authorization::getObjectPermission($target, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($target, Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($target, Authorization::OBJECT_FRONTEND_EDIT))) {
                        // store the command success in the old value
                        Request::getCommand()->setOldValue($exec->moveObject($target, $mode));
                    } else {
                    Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'moveobjectup':
                    // create the executer
                    $exec = new ExecuteObjectAction();
                    $exec->setObject($object);
                    // store the command success in the old value
                    Request::getCommand()->setOldValue($exec->moveObjectUp(Modes::getMode(Mode::EDITMODE)));
                    break;
                case 'moveobjectdown':
                    // create the executer
                    $exec = new ExecuteObjectAction();
                    $exec->setObject($object);
                    // store the command success in the old value
                    Request::getCommand()->setOldValue($exec->moveObjectDown(Modes::getMode(Mode::EDITMODE)));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an object version
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param objectversion $objectversion
     */
    public static function changeObjectVersion($objectversion) {
        $object = $objectversion->getContainer();
        // first check authorization
        if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'objectversionlayout':
                    // validate, if this is a template object or there is no inheritance, proceed
                    if (Validator::validLayout(Request::getCommand()->getValue(), $object->getSet()->getId()) && ($object->getIsTemplate() == true || $objectversion->getInheritLayout() == false)) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($objectversion->getLayout()->getId());
                        // set the new value
                        $objectversion->setLayout(Layouts::getLayout(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'objectversionstyle':
                    // validate, if this is a template object or there is no inheritance, proceed
                    if (Validator::validStyle(Request::getCommand()->getValue(), Style::POSITION_STYLE, $object->getSet()->getId()) && ($object->getIsTemplate() == true || $objectversion->getInheritStyle() == false)) {
                        // TODO: check whether the style is in the set of the object and whether it is a position style
                        // store the old value in the command
                        Request::getCommand()->setOldValue($objectversion->getStyle()->getId());
                        // set the new value
                        $objectversion->setStyle(Styles::getStyle(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'objectversionargumentdefault':
                    // only with manage object authorization
                    if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE)) {
                        // validate, if this is a template object or there is no inheritance, proceed
                        if (Validator::isNumber(Request::getCommand()->getValue()) && ($objectversion->getArgument()->getId() != Argument::DEFAULT_ARGUMENT)) {
                            // store the old value in the command
                            Request::getCommand()->setOldValue($objectversion->getArgumentDefault());
                            // set the new value
                            $objectversion->setArgumentDefault(Request::getCommand()->getValue());
                        } else {
                            Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
                    }
                    break;
                case 'objectversionargument':
                    // validate and check whether this object is part of a template
                    if (Validator::validArgument(Request::getCommand()->getValue()) && $object->getIsTemplate() == true) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($objectversion->getArgument()->getId());
                        // set the new value
                        $objectversion->setArgument(Arguments::getArgument(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'objectversioninheritlayout':
                    // if the object is part of a template
                    if ($object->getIsTemplate() == true) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($objectversion->getInheritLayout());
                        // set the new value
                        if ($objectversion->getInheritLayout() == true) {
                            $objectversion->setInheritLayout(false);
                        } else {
                            $objectversion->setInheritLayout(true);
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'objectversioninheritstyle':
                    // if the object is part of a template
                    if ($object->getIsTemplate() == true) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($objectversion->getInheritStyle());
                        // set the new value
                        if ($objectversion->getInheritStyle() == true) {
                            $objectversion->setInheritStyle(false);
                        } else {
                            $objectversion->setInheritStyle(true);
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'objectversiontemplate':
                    // if the value is valid and the object is part of a template
                    if (Validator::validTemplate(Request::getCommand()->getValue()) && $object->getIsTemplate() == true) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($objectversion->getTemplate()->getId());
                        // set the new value
                        $objectversion->setTemplate(Templates::getTemplate(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Add or remove positions from an object
     * 
     * @param object $object
     * @param int $number
     */
    public static function changeObjectPosition($object, $number) {
        // TODO: execute the add/remove command
        // first check authorization
        if (Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'objectpositionremove':
                    // validate, if this is a template object or there is no inheritance, proceed
                    if (Validator::isNumeric($number) && $object->getIsTemplate() == true) {
                        // store the result of the remove in the command
                        Request::getCommand()->setOldValue($object->getVersion(Modes::getMode(Mode::EDITMODE))->removePosition($number));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioncontentitemadd':
                    // validate, if this is a template object or there is no inheritance, proceed
                    if (Validator::isNumeric($number) && $object->getIsTemplate() == true) {
                        // store the result of the remove in the command
                        Request::getCommand()->setOldValue($object->getVersion(Modes::getMode(Mode::EDITMODE))->newPositionContentItem($number));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positionobjectadd':
                    // validate, if this is a template object or there is no inheritance, proceed
                    if (Validator::isNumeric($number) && $object->getIsTemplate() == true) {
                        // store the result of the remove in the command
                        Request::getCommand()->setOldValue($object->getVersion(Modes::getMode(Mode::EDITMODE))->newPositionObject($number));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstanceadd':
                    // validate, if this is a template object or there is no inheritance, proceed
                    if (Validator::isNumeric($number) && $object->getIsTemplate() == true) {
                        // store the result of the remove in the command
                        Request::getCommand()->setOldValue($object->getVersion(Modes::getMode(Mode::EDITMODE))->newPositionInstance($number));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positionreferraladd':
                    // validate, if this is a template object or there is no inheritance, proceed
                    if (Validator::isNumeric($number) && $object->getIsTemplate() == true) {
                        // store the result of the remove in the command
                        Request::getCommand()->setOldValue($object->getVersion(Modes::getMode(Mode::EDITMODE))->newPositionReferral($number));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }
    
    /**
     * Execute a change in a position
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param position $position
     */
    public static function changePosition($position) {
        $object = $position->getContainer()->getContainer();
        // first check authorization
        if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'positionstructure':
                    if (Validator::validStructure(Request::getCommand()->getValue(), $object->getSet()->getId()) && ($position->getInheritStructure() == false || $object->getIsTemplate() == true)) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($position->getStructure()->getId());
                        // set the new value
                        $position->setStructure(Structures::getStructure(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positionstyle':
                    if (Validator::validStyle(Request::getCommand()->getValue(), Style::POSITION_STYLE, $object->getSet()->getId()) && ($position->getInheritStyle() == false || $object->getIsTemplate() == true)) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($position->getStyle()->getId());
                        // set the new value
                        $position->setStyle(Styles::getStyle(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninheritstructure':
                    // if the object is part of a template
                    if ($object->getIsTemplate() == true) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($position->getInheritStructure());
                        // set the new value
                        if ($position->getInheritStructure() == true) {
                            $position->setInheritStructure(false);
                        } else {
                            $position->setInheritStructure(true);
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninheritstyle':
                    // if the object is part of a template
                    if ($object->getIsTemplate() == true) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($position->getInheritStyle());
                        // set the new value
                        if ($position->getInheritStyle() == true) {
                            $position->setInheritStyle(false);
                        } else {
                            $position->setInheritStyle(true);
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioncontentitemname':
                    // if the object is part of a template
                    if (Validator::isName(Request::getCommand()->getValue()) && $object->getIsTemplate() == true && $position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_CONTENTITEM) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($position->getPositionContent()->getName());
                        // set the new value
                        $position->getPositionContent()->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioncontentiteminputtype':
                    // if the object is part of a template
                    $inputtype = Request::getCommand()->getValue();
                    if (Validator::validInputType($inputtype) && $object->getIsTemplate() == true && $position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_CONTENTITEM) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($position->getPositionContent()->getInputType());
                        // set the new value
                        $position->getPositionContent()->setInputType($inputtype);
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioncontentitembody':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_CONTENTITEM) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($position->getPositionContent()->getBody());
                        // set the new value
                        $position->getPositionContent()->setBody(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstanceobject':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE && Validator::isNumeric(Request::getCommand()->getValue())) {
                        if ($object = Objects::getObject(Request::getCommand()->getValue())) {
                            if (Authorization::getObjectPermission($object, Authorization::OBJECT_VIEW)) {
                                $instance = $position->getPositionContent();
                                // store the old value in the command
                                Request::getCommand()->setOldValue($instance->getObject()->getId());
                                // set the new value
                                $instance->setObject($object);
                            }
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstancetemplate':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE && Validator::isNumeric(Request::getCommand()->getValue())) {
                        if ($template = Templates::getTemplate(Request::getCommand()->getValue())) {
                            $instance = $position->getPositionContent();
                            // store the old value in the command
                            Request::getCommand()->setOldValue($instance->getTemplate()->getId());
                            // set the new value
                            $instance->setTemplate($template);
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstancelistwords':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE && Validator::isLocaleAlfaNumeric(Request::getCommand()->getValue())) {
                        $instance = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($instance->getListWords());
                        // set the new value
                        $instance->setListWords(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstancesearchwords':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE && Validator::isLocaleAlfaNumeric(Request::getCommand()->getValue())) {
                        $instance = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($instance->getSearchWords());
                        // set the new value
                        $instance->setSearchWords(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstanceparent':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE && Validator::isNumeric(Request::getCommand()->getValue())) {
                        if ($object = Objects::getObject(Request::getCommand()->getValue())) {
                            if (Authorization::getObjectPermission($object, Authorization::OBJECT_VIEW)) {
                                $instance = $position->getPositionContent();
                                // store the old value in the command
                                Request::getCommand()->setOldValue($instance->getParent()->getId());
                                // set the new value
                                $instance->setParent($object);
                            }
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstanceactiveitems':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE) {
                        $instance = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($instance->getActiveItems());
                        // set the new value
                        $instance->setActiveItems(!$instance->getActiveItems());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstancemaxitems':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE) {
                        $instance = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($instance->getMaxItems());
                        // set the new value
                        $instance->setMaxItems(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstancefillonload':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE) {
                        $instance = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($instance->getFillOnLoad());
                        // set the new value
                        $instance->setFillOnLoad(!$instance->getFillOnLoad());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstanceuseinstancecontext':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE) {
                        $instance = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($instance->getUseInstanceContext());
                        // set the new value
                        $instance->setUseInstanceContext(!$instance->getUseInstanceContext());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstanceorderby':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE) {
                        $instance = $position->getPositionContent();
                        if (Validator::validTemplateOrderBy(Request::getCommand()->getValue(), $instance->getTemplate())) {
                            // store the old value in the command
                            Request::getCommand()->setOldValue($instance->getOrderBy());
                            // set the new value
                            $instance->setOrderBy(Request::getCommand()->getValue());
                        } else {
                            Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positioninstancegroupby':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE) {
                        $instance = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($instance->getGroupBy());
                        // set the new value
                        $instance->setGroupBy(!$instance->getGroupBy());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positionreferralargument':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_REFERRAL && Validator::validArgument(Request::getCommand()->getValue())) {
                        $referral = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($referral->getArgument()->getId());
                        // set the new value
                        $referral->setArgument(Arguments::getArgument(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positionreferralorderby':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_REFERRAL && Validator::validReferralOrderBy(Request::getCommand()->getValue())) {
                        $referral = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($referral->getOrderBy());
                        // set the new value
                        $referral->setOrderBy(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'positionreferralnumberofitems':
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_REFERRAL && Validator::isNumeric(Request::getCommand()->getValue())) {
                        $referral = $position->getPositionContent();
                        // store the old value in the command
                        Request::getCommand()->setOldValue($referral->getNumberOfItems());
                        // set the new value
                        $referral->setNumberOfItems(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an layout
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param layout $layout
     */
    public static function changeLayout($layout) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::LAYOUT_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'layoutname':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($layout->getName());
                        // set the new value
                        $layout->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'layoutset':
                    // validate
                    if (Validator::validSet(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($layout->getSet()->getId());
                        // set the new value
                        $layout->setSet(Sets::getSet(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'layoutadd':
                    // store the success value in the command
                    // add a new layout
                    Request::getCommand()->setOldValue(is_object(Layouts::newLayout()));
                    break;
                case 'layoutremove':
                    // store the success value in the command
                    // remove the specified layout
                    Request::getCommand()->setOldValue(Layouts::removeLayout($layout));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an layout version
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param layout $layout
     * @param mode $mode
     * @param context $context
     */
    public static function changeLayoutVersion($layout, $mode, $context) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::LAYOUT_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'layoutversionbody':
                    // get the layout version, always edit in edit mode
                    $layoutversion = $layout->getVersion(Modes::getMode(Mode::EDITMODE), $context);
                    // store the old value in the command
                    Request::getCommand()->setOldValue($layoutversion->getBody());
                    // set the new value
                    $layoutversion->setBody(Request::getCommand()->getValue());
                    break;
                case 'layoutversionadd':
                    // store the success value in the command
                    // add the specified version
                    Request::getCommand()->setOldValue($layout->newVersion($context));
                    break;
                case 'layoutversionremove':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($layout->removeVersion($context));
                    break;
                case 'layoutversionpublish':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($layout->publishVersion($context));
                    break;
                case 'layoutversioncancel':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($layout->cancelVersion($context));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an structure
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param structure $structure
     */
    public static function changeStructure($structure) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::STRUCTURE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'structurename':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($structure->getName());
                        // set the new value
                        $structure->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'structureset':
                    // validate
                    if (Validator::validSet(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($structure->getSet()->getId());
                        // set the new value
                        $structure->setSet(Sets::getSet(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'structureadd':
                    // store the success value in the command
                    // add a new structure
                    Request::getCommand()->setOldValue(is_object(Structures::newStructure()));
                    break;
                case 'structureremove':
                    // store the success value in the command
                    // remove the specified structure
                    Request::getCommand()->setOldValue(Structures::removeStructure($structure));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an structure version
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param structure $structure
     * @param mode $mode
     * @param context $context
     */
    public static function changeStructureVersion($structure, $mode, $context) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::STRUCTURE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'structureversionbody':
                    // get the structure version, always edit in edit mode
                    $structureversion = $structure->getVersion(Modes::getMode(Mode::EDITMODE), $context);
                    // store the old value in the command
                    Request::getCommand()->setOldValue($structureversion->getBody());
                    // set the new value
                    $structureversion->setBody(Request::getCommand()->getValue());
                    break;
                case 'structureversionadd':
                    // store the success value in the command
                    // add the specified version
                    Request::getCommand()->setOldValue($structure->newVersion($context));
                    break;
                case 'structureversionremove':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($structure->removeVersion($context));
                    break;
                case 'structureversionpublish':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($structure->publishVersion($context));
                    break;
                case 'structureversioncancel':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($structure->cancelVersion($context));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an style
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param style $style
     */
    public static function changeStyle($style) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'stylename':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($style->getName());
                        // set the new value
                        $style->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'styletype':
                    // validate
                    $styletype = Request::getCommand()->getValue();
                    if (Validator::validStyleType($styletype)) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($style->getStyleType());
                        // set the new value
                        $style->setStyleType($styletype);
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'styleclasssuffix':
                    $classsuffix = Request::getCommand()->getValue();
                    if (Validator::isClassSuffix($classsuffix)) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($style->getClassSuffix());
                        // set the new value
                        $style->setClassSuffix($classsuffix);
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'styleset':
                    // validate
                    if (Validator::validSet(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($style->getSet()->getId());
                        // set the new value
                        $style->setSet(Sets::getSet(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'styleadd':
                    // store the success value in the command
                    // add a new style
                    Request::getCommand()->setOldValue(is_object(Styles::newStyle()));
                    break;
                case 'styleremove':
                    // store the success value in the command
                    // remove the specified style
                    Request::getCommand()->setOldValue(Styles::removeStyle($style));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an style version
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param style $style
     * @param mode $mode
     * @param context $context
     */
    public static function changeStyleVersion($style, $mode, $context) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'styleversionbody':
                    // get the style version, always edit in edit mode
                    $styleversion = $style->getVersion(Modes::getMode(Mode::EDITMODE), $context);
                    // store the old value in the command
                    Request::getCommand()->setOldValue($styleversion->getBody());
                    // set the new value
                    $styleversion->setBody(Request::getCommand()->getValue());
                    break;
                case 'styleversionadd':
                    // store the success value in the command
                    // add the specified version
                    Request::getCommand()->setOldValue($style->newVersion($context));
                    break;
                case 'styleversionremove':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($style->removeVersion($context));
                    break;
                case 'styleversionpublish':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($style->publishVersion($context));
                    break;
                case 'styleversioncancel':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($style->cancelVersion($context));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in a style parameter
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param styleparam $styleparam
     */
    public static function changeStyleParam($styleparam) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'styleparamname':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($styleparam->getName());
                        // set the new value
                        $styleparam->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'styleparamadd':
                    // store the success value in the command
                    // add a new style
                    Request::getCommand()->setOldValue(is_object(StyleParams::newStyleParam()));
                    break;
                case 'styleparamremove':
                    // store the success value in the command
                    // remove the specified style
                    Request::getCommand()->setOldValue(StyleParams::removeStyleParam($styleparam));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an style parameter version
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     */
    public static function changeStyleParamVersion($styleparam, $mode, $context) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::STYLE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'styleparamversionbody':
                    // get the style version, always edit in edit mode
                    $styleparamversion = $styleparam->getVersion(Modes::getMode(Mode::EDITMODE), $context);
                    // store the old value in the command
                    Request::getCommand()->setOldValue($styleparamversion->getBody());
                    // set the new value
                    $styleparamversion->setBody(Request::getCommand()->getValue());
                    break;
                case 'styleparamversionadd':
                    // store the success value in the command
                    // add the specified version
                    Request::getCommand()->setOldValue($styleparam->newVersion($context));
                    break;
                case 'styleparamversionremove':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($styleparam->removeVersion($context));
                    break;
                case 'styleparamversionpublish':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($styleparam->publishVersion($context));
                    break;
                case 'styleparamversioncancel':
                    // store the success value in the command
                    // remove the specified version
                    Request::getCommand()->setOldValue($styleparam->cancelVersion($context));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an set
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param set $set
     */
    public static function changeSet($set) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'setname':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($set->getName());
                        // set the new value
                        $set->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'setadd':
                    // store the success value in the command
                    // add a new set
                    Request::getCommand()->setOldValue(is_object(Sets::newSet()));
                    break;
                case 'setremove':
                    // store the success value in the command
                    // remove the specified set
                    Request::getCommand()->setOldValue(Sets::removeSet($set));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in a setting
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param setting $setting
     */
    public static function changeSetting($setting) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::SETTING_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'settingvalue':
                    Request::getCommand()->setOldValue($setting->getValue());
                    // set the new value
                    $setting->setValue(Request::getCommand()->getValue());
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }
    
    /**
     * Execute a change in an usergroup
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param usergroup $usergroup
     */
    public static function changeUserGroup($usergroup) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::USER_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'usergroupname':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($usergroup->getName());
                        // set the new value
                        $usergroup->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'usergroupadd':
                    // store the success value in the command
                    // add a new usergroup
                    Request::getCommand()->setOldValue(UserGroups::newUserGroup());
                    break;
                case 'usergroupremove':
                    // store the success value in the command
                    // remove the specified usergroup
                    Request::getCommand()->setOldValue(UserGroups::removeUserGroup($usergroup));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in a role
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param role $role
     */
    public static function changeRole($role) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::ROLE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'rolename':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($role->getName());
                        // set the new value
                        $role->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'roleadd':
                    // store the success value in the command
                    // add a new role
                    Request::getCommand()->setOldValue(Roles::newRole());
                    break;
                case 'roleremove':
                    // store the success value in the command
                    // remove the specified role
                    Request::getCommand()->setOldValue(Roles::removeRole($role));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in a permission
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param permission $permission
     */
    public static function changePermission($permission) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::ROLE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case Permission::PERMISSION_FLUSH_ARCHIVE:
                    Request::getCommand()->setOldValue($permission->setFlushArchive(!$permission->getFlushArchive()));
                    break;
                case Permission::PERMISSION_FRONTEND_ADD:
                    Request::getCommand()->setOldValue($permission->setFrontendAdd(!$permission->getFrontendAdd()));
                    break;
                case Permission::PERMISSION_FRONTEND_CREATOR_DEACTIVATE:
                    Request::getCommand()->setOldValue($permission->setFrontendCreatorDeactivate(!$permission->getFrontendCreatorDeactivate()));
                    break;
                case Permission::PERMISSION_FRONTEND_CREATOR_EDIT:
                    Request::getCommand()->setOldValue($permission->setFrontendCreatorEdit(!$permission->getFrontendCreatorEdit()));
                    break;
                case Permission::PERMISSION_FRONTEND_DEACTIVATE:
                    Request::getCommand()->setOldValue($permission->setFrontendDeactivate(!$permission->getFrontendDeactivate()));
                    break;
                case Permission::PERMISSION_FRONTEND_EDIT:
                    Request::getCommand()->setOldValue($permission->setFrontendEdit(!$permission->getFrontendEdit()));
                    break;
                case Permission::PERMISSION_FRONTENT_RESPOND:
                    Request::getCommand()->setOldValue($permission->setFrontendRespond(!$permission->getFrontendRespond()));
                    break;
                case Permission::PERMISSION_MANAGE_AUTHORIZATION:
                    Request::getCommand()->setOldValue($permission->setManageAuthorization(!$permission->getManageAuthorization()));
                    break;
                case Permission::PERMISSION_MANAGE_CONTENT:
                    Request::getCommand()->setOldValue($permission->setManageContent(!$permission->getManageContent()));
                    break;
                case Permission::PERMISSION_MANAGE_LANGUAGE:
                    Request::getCommand()->setOldValue($permission->setManageLanguage(!$permission->getManageLanguage()));
                    break;
                case Permission::PERMISSION_MANAGE_LAYOUT:
                    Request::getCommand()->setOldValue($permission->setManageLayout(!$permission->getManageLayout()));
                    break;
                case Permission::PERMISSION_MANAGE_LSS_VERSION:
                    Request::getCommand()->setOldValue($permission->setManageLSSVersion(!$permission->getManageLSSVersion()));
                    break;
                case Permission::PERMISSION_MANAGE_ROLE:
                    Request::getCommand()->setOldValue($permission->setManageRole(!$permission->getManageRole()));
                    break;
                case Permission::PERMISSION_MANAGE_SETTING:
                    Request::getCommand()->setOldValue($permission->setManageSetting(!$permission->getManageSetting()));
                    break;
                case Permission::PERMISSION_MANAGE_STRUCTURE:
                    Request::getCommand()->setOldValue($permission->setManageStructure(!$permission->getManageStructure()));
                    break;
                case Permission::PERMISSION_MANAGE_STYLE:
                    Request::getCommand()->setOldValue($permission->setManageStyle(!$permission->getManageStyle()));
                    break;
                case Permission::PERMISSION_MANAGE_SYSTEM:
                    Request::getCommand()->setOldValue($permission->setManageSystem(!$permission->getManageSystem()));
                    break;
                case Permission::PERMISSION_MANAGE_TEMPLATE:
                    Request::getCommand()->setOldValue($permission->setManageTemplate(!$permission->getManageTemplate()));
                    break;
                case Permission::PERMISSION_MANAGE_USER:
                    Request::getCommand()->setOldValue($permission->setManageUser(!$permission->getManageUser()));
                    break;
                case Permission::PERMISSION_SHOW_BAR:
                    Request::getCommand()->setOldValue($permission->setShowAdminBar(!$permission->getShowAdminBar()));
                    break;
                case Permission::PERMISSION_UPLOAD_FILE:
                    Request::getCommand()->setOldValue($permission->setUploadFile(!$permission->getUploadFile()));
                    break;
                case Permission::PERMISSION_VIEW_OBJECT:
                    Request::getCommand()->setOldValue($permission->setViewObject(!$permission->getViewObject()));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an user
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param user $user
     * @param usergroup $usergroup optional
     */
    public static function changeUser($user, $usergroup = NULL) {
        // first check authorization, some people can manage all users, most only themselves
        if (Authorization::getPagePermission(Authorization::USER_MANAGE) || Authentication::getUser()->getId() == $user->getId()) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'username':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($user->getName());
                        // set the new value
                        $user->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'userpassword':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($user->getPassword());
                    // set the new value
                    $user->setPassword(Authentication::middleSalt(Request::getCommand()->getValue()));
                    break;
                case 'userfirstname':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($user->getFirstName());
                    // set the new value
                    $user->setFirstName(Request::getCommand()->getValue());
                    break;
                case 'userlastname':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($user->getLastName());
                    // set the new value
                    $user->setLastName(Request::getCommand()->getValue());
                    break;
                case 'userlogincounter':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($user->getLoginCounter());
                    // set the new value
                    $user->setLoginCounter(0);
                    break;
                case 'userusergroup':
                    $done = false;
                    // set the new value
                    if (is_object($usergroup)) {
                        $usergroups = $user->getUserGroups();
                        if (array_key_exists($usergroup->getId(), $usergroups)) {
                            // remove the user from the user group
                            $done = $usergroup->deleteUser($user);
                        } else {
                            // add the user to the user group
                            $done = $usergroup->addUser($user);
                        }
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    }
                    // store true in the command, the change will be made
                    Request::getCommand()->setOldValue($done);
                    break;
                case 'useradd':
                    // store the success value in the command
                    // add a new user
                    Request::getCommand()->setOldValue(Users::newUser());
                    break;
                case 'userremove':
                    // store the success value in the command
                    // remove the specified user
                    Request::getCommand()->setOldValue(Users::removeUser($user));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in an template
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param template $template
     */
    public static function changeTemplate($template) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'templatename':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($template->getName());
                        // set the new value
                        $template->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'templatedeleted':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($template->getDeleted());
                    // flip the bool
                    $template->setDeleted(!$template->getDeleted());
                    break;
                case 'templateinstanceallowed':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($template->getInstanceAllowed());
                    // flip the bool
                    $template->setInstanceAllowed(!$template->getInstanceAllowed());
                    break;
                case 'templatesearchable':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($template->getSearchable());
                    // flip the bool
                    $template->setSearchable(!$template->getSearchable());
                    break;
                case 'templateset':
                    // validate
                    if (Validator::validSet(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($template->getSet()->getId());
                        // set the new value
                        $template->setSet(Sets::getSet(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'templatestructure':
                    // validate
                    if (Validator::validStructure(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($template->getStructure()->getId());
                        // set the new value
                        $template->setStructure(Structures::getStructure(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'templatestyle':
                    // validate
                    if (Validator::validStyle(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($template->getStyle()->getId());
                        // set the new value
                        $template->setStyle(Styles::getStyle(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'templateadd':
                    // store the success value in the command
                    // add a new template
                    Request::getCommand()->setOldValue(Templates::newTemplate());
                    break;
                case 'templateremove':
                    // store the success value in the command
                    // remove the specified template
                    Request::getCommand()->setOldValue(Templates::removeTemplate($template));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * Execute a change in a includefile
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param fileinclude $includefile
     */
    public static function changeFileInclude($includefile) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::SYSTEM_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'includefilename':
                    // validate
                    if (Validator::isFileName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($includefile->getName());
                        // set the new value
                        $includefile->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'includefilemimetype':
                    // validate
                    if (Validator::isMimeType(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($includefile->getMimeType());
                        // set the new value
                        $includefile->setMimeType(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'includefilecomment':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($includefile->getComment());
                    // set the new value
                    $includefile->setComment(Request::getCommand()->getValue());
                    break;
                case 'includefileversionbody':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($includefile->getVersion(Modes::getMode(Mode::EDITMODE))->getBody());
                    // set the new value
                    $includefile->getVersion(Modes::getMode(Mode::EDITMODE))->setBody(Request::getCommand()->getValue());
                    break;
                case 'includefileversionpublish':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($includefile->publishVersion());
                    break;
                case 'includefileadd':
                    // store the success value in the command
                    // add a new template
                    Request::getCommand()->setOldValue(FileIncludes::newFileInclude());
                    break;
                case 'includefileremove':
                    // store the success value in the command
                    // remove the specified template
                    Request::getCommand()->setOldValue(FileIncludes::removeFileInclude($includefile));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }
    
    /**
     * Execute a change in a snippet
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param snippet $snippet
     */
    public static function changeSnippet($snippet) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::SYSTEM_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'snippetname':
                    // validate
                    if (Validator::isName(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($snippet->getName());
                        // set the new value
                        $snippet->setName(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'snippetmimetype':
                    // validate
                    if (Validator::isMimeType(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($snippet->getMimeType());
                        // set the new value
                        $snippet->setMimeType(Request::getCommand()->getValue());
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'snippetcontextgroup':
                    // validate
                    if (Validator::validContextGroup(Request::getCommand()->getValue())) {
                        // store the old value in the command
                        Request::getCommand()->setOldValue($snippet->getContextGroup()->getId());
                        // set the new value
                        $snippet->setContextGroup(ContextGroups::getContextGroup(Request::getCommand()->getValue()));
                    } else {
                        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
                    }
                    break;
                case 'snippetversionbody':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($snippet->getVersion(Modes::getMode(Mode::EDITMODE))->getBody());
                    // set the new value
                    $snippet->getVersion(Modes::getMode(Mode::EDITMODE))->setBody(Request::getCommand()->getValue());
                    break;
                case 'snippetversionpublish':
                    // store the old value in the command
                    Request::getCommand()->setOldValue($snippet->publishVersion());
                    break;
                case 'snippetadd':
                    // store the success value in the command
                    // add a new template
                    Request::getCommand()->setOldValue(Snippets::newSnippet());
                    break;
                case 'snippetremove':
                    // store the success value in the command
                    // remove the specified template
                    Request::getCommand()->setOldValue(Snippets::removeSnippet($snippet));
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }
    
    /**
     * Execute a change in a object usergrouip role
     * Check for authorization
     * Validate the value if necessary
     * 
     * @param object $object
     * @param usergroup $usergroup
     * @param role $role
     */
    public static function changeObjectUsergroupRole($object, $usergroup, $role) {
        // first check authorization
        if (Authorization::getPagePermission(Authorization::SYSTEM_MANAGE)) {
            // then validate (if necessary) and execute
            switch (Request::getCommand()->getCommandMember()) {
                case 'objectusergrouprole':
                    // store the old value in the command
                    Request::getCommand()->setOldValue(true);
                    // add or remove the object usergroup role
                    $object->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectTemplateRootObject()->setObjectUserGroupRole($usergroup, $role, false);
                    break;
                case 'objectusergrouproleinherit':
                    // store the old value in the command
                    Request::getCommand()->setOldValue(true);
                    // add or remove the object usergroup role and inherit to all sub objects
                    $object->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectTemplateRootObject()->setObjectUserGroupRole($usergroup, $role, false);
                    break;
                default:
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND));
                    break;
            }
            // TODO: create events based upon what happened
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }
}