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
 * Description of object factory
 *
 * @since 0.4.0
 */
class ObjectFactory extends Factory {

    private $object;
    private $cacheable = true; // is this object cacheable

    /**
     * construct the object factory
     * 
     * @param object $object
     * @param context $context
     * @param mode $mode
     * @throws Exception
     */

    public function __construct($object, $context, $mode) {
        // do some input checking
        if (is_object($object) && is_object($context) && is_object($mode)) {
            $this->setContext($context);
            $this->setMode($mode);
            $this->setObject($object);
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_FACTORY_NOT_INITIALIZED_CORRECTLY) . ' @ ' . __METHOD__);
        }
    }

    /**
     * factor an object
     * 
     * @return string
     */
    public function factor() {
        // isVisible checks whether the object should be shown (depending on authorizations and other criteria)
        if ($this->getObject()->isVisible($this->getMode(), $this->getContext())) {
            // initialize the content with the active layout for this object in this mode and context
            $this->setContent($this->getObject()->getVersion($this->getMode())->getLayout()->getVersion($this->getMode(), $this->getContext())->getBody());
            // factor the terms for this object
            $this->factorTerms();
            // factor the positions for this object
            $this->factorPositions();
        } else {
            if ($this->getObject()->isNewAndEditable()) {
                // go to edit mode
                $this->setMode(Modes::getMode(Mode::EDITMODE));
                // check some more (but should be true)
                if ($this->getObject()->isVisible($this->getMode(), $this->getContext())) {
                    // initialize the content with the active layout for this object in this mode and context
                    $this->setContent($this->getObject()->getVersion($this->getMode())->getLayout()->getVersion($this->getMode(), $this->getContext())->getBody());
                    // factor the terms for this object
                    $this->factorTerms();
                    // factor the positions for this object
                    $this->factorPositions();
                }
            }
            // this object can't be factored, but that can happen (visibility and object level authorization is checked here)
        }
        return true;
    }

    /**
     * Set the object string to factor
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

    /**
     * check which terms are used in the object layout, and factor content for
     * these terms
     * 
     */
    private function factorTerms() {
        // check the terms and factor them
        if ($this->hasTerm(Terms::OBJECT_ARGUMENT_NAME)) {
            $this->replaceTerm(Terms::OBJECT_ARGUMENT_NAME, $this->getObject()->getArgumentName($this->getMode()));
        }
        if ($this->hasTerm(Terms::OBJECT_CHANGE_DATE)) {
            $this->replaceTerm(Terms::OBJECT_CHANGE_DATE, $this->getObject()->getChangeDate()->format(Helper::getDateTimeFormat()));
        }
        if ($this->hasTerm(Terms::OBJECT_CREATE_DATE)) {
            $this->replaceTerm(Terms::OBJECT_CREATE_DATE, $this->getObject()->getCreateDate()->format(Helper::getDateTimeFormat()));
        }
        if ($this->hasTerm(Terms::OBJECT_BUTTON_TOGGLE)) {
            if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_EDIT)) {
                $this->replaceTerm(Terms::OBJECT_BUTTON_TOGGLE, $this->factorButtonToggle());
            }
        }
        $this->clearTerm(Terms::OBJECT_BUTTON_TOGGLE);
        $hasmenu = false;
        if ($this->hasTerm(Terms::OBJECT_MENU)) {
            if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_ADD)) {
                $this->replaceTerm(Terms::OBJECT_MENU, $this->factorMenu());
                $hasmenu = true;
            }
        }
        $this->clearTerm(Terms::OBJECT_MENU);
        if ($this->hasTerm(Terms::OBJECT_EDIT_BUTTON) || $this->hasTerm(Terms::OBJECT_EDIT_PANEL)) {
            if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_EDIT)) {
                $this->replaceTerm(Terms::OBJECT_EDIT_BUTTON, $this->factorEditButton());
                $this->replaceTerm(Terms::OBJECT_EDIT_PANEL, $this->factorEditPanel());
            }
        }
        $this->clearTerm(Terms::OBJECT_EDIT_BUTTON);
        $this->clearTerm(Terms::OBJECT_EDIT_PANEL);
        if ($this->hasTerm(Terms::OBJECT_MOVE_BUTTON) || $this->hasTerm(Terms::OBJECT_MOVE_PANEL)) {
            if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_EDIT)) {
                $this->replaceTerm(Terms::OBJECT_MOVE_BUTTON, $this->factorMoveButton());
                $this->replaceTerm(Terms::OBJECT_MOVE_PANEL, $this->factorMovePanel());
            }
        }
        $this->clearTerm(Terms::OBJECT_MOVE_BUTTON);
        $this->clearTerm(Terms::OBJECT_MOVE_PANEL);
        if ($this->hasTerm(Terms::OBJECT_ADD_BUTTON) || $this->hasTerm(Terms::OBJECT_ADD_PANEL)) {
            if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_ADD)) {
                // only when there are available positions, show the add button
                if ($this->getObject()->getVersion($this->getMode())->hasAvailablePositions()) {
                    $this->replaceTerm(Terms::OBJECT_ADD_BUTTON, $this->factorAddButton());
                    $this->replaceTerm(Terms::OBJECT_ADD_PANEL, $this->factorAddPanel());
                }
            } else {
                // if a user only has front end respond rights
                if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_RESPOND)) {
                    // only when there are available positions, show the add button
                    if ($this->getObject()->getVersion($this->getMode())->hasAvailablePositions()) {
                        // add the button for adding the default template
                        // create the add panel
                        $addadminfactory = new AddAdminFactory();
                        // initialize the admin factory
                        $addadminfactory->setContext($this->getContext());
                        $addadminfactory->setMode($this->getMode());
                        $addadminfactory->setObject($this->getObject());
                        // factor the content
                        $addadminfactory->factor();
                        // get the factored item
                        $this->replaceTerm(Terms::OBJECT_ADD_BUTTON, $addadminfactory->getContent());
                        // add item is launched in the edit panel
                        // TODO: check whether this works
                        $this->replaceTerm(Terms::OBJECT_ADD_PANEL, $this->factorEditPanel());
                    }
                }
            }
        }
        $this->clearTerm(Terms::OBJECT_ADD_BUTTON);
        $this->clearTerm(Terms::OBJECT_ADD_PANEL);
        if ($this->hasTerm(Terms::OBJECT_CONFIG_BUTTON)) {
            if (Authorization::getPagePermission(Authorization::AUTHORIZATION_MANAGE) || Authorization::getPagePermission(Authorization::LANGUAGE_MANAGE) || Authorization::getPagePermission(Authorization::ROLE_MANAGE) || Authorization::getPagePermission(Authorization::SYSTEM_MANAGE) || Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE) || Authorization::getPagePermission(Authorization::USER_FLUSH_ARCHIVE) || Authorization::getPagePermission(Authorization::USER_MANAGE) || Authorization::getPagePermission(Authorization::SETTING_MANAGE)) {
                $this->replaceTerm(Terms::OBJECT_CONFIG_BUTTON, $this->factorConfigButton());
                $this->replaceTerm(Terms::OBJECT_CONFIG_PANEL, $this->factorConfigPanel());
            }
        }
        $this->clearTerm(Terms::OBJECT_CONFIG_BUTTON);
        $this->clearTerm(Terms::OBJECT_CONFIG_PANEL);
        if ($this->hasTerm(Terms::OBJECT_ID)) {
            $this->replaceTerm(Terms::OBJECT_ID, $this->getObject()->getId());
        }
        if ($this->hasTerm(Terms::OBJECT_ROOT_OBJECT_ID)) {
            $this->replaceTerm(Terms::OBJECT_ROOT_OBJECT_ID, $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getId());
        }
        if ($this->hasTerm(Terms::OBJECT_PARENT_ROOT_OBJECT_ID)) {
            $this->replaceTerm(Terms::OBJECT_PARENT_ROOT_OBJECT_ID, $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getVersion($this->getMode())->getObjectParent()->getVersion($this->getMode())->getObjectTemplateRootObject()->getId());
        }
        if ($this->hasTerm(Terms::OBJECT_NAME)) {
            $this->replaceTerm(Terms::OBJECT_NAME, $this->getObject()->getName());
        }
        if ($this->hasTerm(Terms::OBJECT_URL_NAME)) {
            // only for addressable objects
            if ($this->getObject()->isAddressable($this->getMode())) {
                $name = Helper::getURLSafeString($this->getObject()->getName());
            } else {
                $name = '';
            }
            $this->replaceTerm(Terms::OBJECT_URL_NAME, $name);
        }
        if ($this->hasTerm(Terms::OBJECT_ROOT_CHANGE_DATE)) {
            $this->replaceTerm(Terms::OBJECT_ROOT_CHANGE_DATE, $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getChangeDate()->format(Helper::getDateTimeFormat()));
        }
        if ($this->hasTerm(Terms::OBJECT_ROOT_CREATE_DATE)) {
            $this->replaceTerm(Terms::OBJECT_ROOT_CREATE_DATE, $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getCreateDate()->format(Helper::getDateTimeFormat()));
        }
        if ($this->hasTerm(Terms::OBJECT_ROOT_NAME)) {
            $this->replaceTerm(Terms::OBJECT_ROOT_NAME, $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getName());
        }
        if ($this->hasTerm(Terms::OBJECT_BREADCRUMBS)) {
            $this->replaceTerm(Terms::OBJECT_BREADCRUMBS, $this->factorBreadCrumbs());
        }
        if ($this->hasTerm(Terms::OBJECT_PARENT_SEO_URL)) {
            $this->replaceTerm(Terms::OBJECT_PARENT_SEO_URL, $this->getObject()->getVersion($this->getMode())->getObjectParent()->getSEOURL($this->getMode()));
        }
        if ($this->hasTerm(Terms::OBJECT_SEO_URL)) {
            $this->replaceTerm(Terms::OBJECT_SEO_URL, $this->getObject()->getSEOURL($this->getMode()));
        }
        if ($this->hasTerm(Terms::OBJECT_DEEP_LINK)) {
            $this->replaceTerm(Terms::OBJECT_DEEP_LINK, $this->getObject()->getDeepLink($this->getMode()));
        }
        if ($this->hasTerm(Terms::OBJECT_DEEP_LINK_COMMAND)) {
            $context = Contexts::getContextByGroupAndName($this->getContext()->getContextGroup(), context::CONTEXT_DEFAULT);
            $this->replaceTerm(Terms::OBJECT_DEEP_LINK_COMMAND, CommandFactory::getObjectDeepLink($this->getObject(), $this->getMode(), $context));
        }
        $number = 1;
        while ($this->hasTerm(Terms::OBJECT_UID)) {
            $this->replaceTermOnce(Terms::OBJECT_UID, 'UO' . $this->getObject()->getId() . '_' . $number);
        }
        // insert the class suffix
        $style = $this->getObject()->getVersion($this->getMode())->getStyle();
        $context = $this->getContext();
        // find an original style, other styles aren't in the css
        while (!$style->getVersion($this->getMode(), $context)->getOriginal()) {
            $context = $context->getBackupContext();
        }
        $this->replaceTerm(Terms::CLASS_SUFFIX, $style->getClassSuffix() . "_" . $style->getVersion($this->getMode(), $context)->getContext()->getContextGroup()->getShortName() . '_' . $style->getVersion($this->getMode(), $context)->getContext()->getShortName());
    }

    /**
     * factor the positions for the object, check for a pn layout and if so check
     * for the value of the argument
     * 
     * @return boolean true if success
     */
    private function factorPositions() {
        // If the layout is of the #pn# type (has an undefined number of positions), 
        // if the object has an argument, the position(s) to show depend on the value of the argument
        if ($this->getObject()->getVersion($this->getMode())->getLayout()->isPNType()) {
            // pn types aren't cacheable because of deep linking
            $this->cacheable = false;
            // check for an argument or the sitemap
            if ($this->getObject()->getVersion($this->getMode())->getArgument()->isDefault() || $this->getContext()->getContextGroup()->isSiteMap()) {
                $objectfound = false;
                // check for a deep linked object id
                if (Request::getURL()->checkDeepLink($this->getObject(), $this->getMode())) {
                    $object = Request::getURL()->getDeepLinkObject($this->getObject(), $this->getMode());
                    $position = $object->getVersion($this->getMode())->getPositionParent();
                    // if the position is part of the current object
                    if ($position->getContainer()->getContainer()->getId() == $this->getObject()->getId()) {
                        // if the object is instanciable and not addressable it can be deep linked
                        if (!$object->isAddressable($this->getMode()) && $object->getTemplate()->getInstanceAllowed() && $object->isVisible($this->getMode(), $this->getContext())) {
                            $showposition = $position->getNumber();
                            // the deep linked object has been found,
                            // empty the url
                            Request::getURL()->removeURLParts();
                            $objectfound = true;
                        }
                    }
                }
                if ($objectfound) {
                    $this->replacePNDeepLink($showposition, $object->getId()); // show the position found in a deep link
                } else {
                    // explode #pn#
                    $this->explodePN(); // show all positions
                }
            } else {
                // default position(s) to show
                $showposition = $this->getObject()->getVersion($this->getMode())->getArgumentDefault();
                // get the next object name from the request url
                $showobject = Request::getURL()->getURLPartAndShift();
                if ($showobject > '') {
                    $objectfound = false; // check...
                    // find the specified position
                    $positions = $this->getObject()->getVersion($this->getMode())->getPositions();
                    foreach ($positions as $position) {
                        // if the position contains an object
                        if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                            $object = $position->getPositionContent()->getObject();
                            // and the object name is equal to the name of the object to show
                            $showobjectparts = explode('_', $showobject);
                            if (count($showobjectparts) == 1) {
                                // check the name
                                if (Helper::getURLSafeString($object->getName()) == $showobject && $object->isVisible($this->getMode(), $this->getContext())) {
                                    $showposition = $position->getNumber();
                                    $objectfound = true;
                                }
                            }
                            if (count($showobjectparts) == 3) {
                                // only check the position id, the name may have changed
                                if ($position->getId() == $showobjectparts[1]) {
                                    $showposition = $position->getNumber();
                                    $objectfound = true;
                                }
                            }
                        }
                    }
                    if (!$objectfound) {
                        // this url is outdated and can no longer be fetched
                        // empty the rest of the url
                        Request::getURL()->removeURLParts();
                    }
                }
                // switch
                switch ($showposition) {
                    case Argument::DEFAULT_SHOW_ALL:
                        $this->explodePN(); // show all (default_show_all is -2)
                        break;
                    case Argument::DEFAULT_SHOW_HIGHEST:
                        $positionnr = 1;
                        $positions = $this->getObject()->getVersion($this->getMode())->getPositions();
                        foreach ($positions as $position) {
                            // if the position contains an object
                            if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                                $object = $position->getPositionContent()->getObject();
                                if ($object->isVisible($this->getMode(), $this->getContext())) {
                                    $positionnr = $position->getNumber();
                                }
                            }
                        }
                        $this->replacePN($positionnr); // show the highest position (default_show_highest is -1)
                        break;
                    case Argument::DEFAULT_SHOW_LOWEST:
                        $positionnr = 1;
                        $positionfound = false;
                        $positions = $this->getObject()->getVersion($this->getMode())->getPositions();
                        foreach ($positions as $position) {
                            // if the position contains an object
                            if (!$positionfound && $position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                                $object = $position->getPositionContent()->getObject();
                                if ($object->isVisible($this->getMode(), $this->getContext())) {
                                    $positionnr = $position->getNumber();
                                    $positionfound = true;
                                }
                            }
                        }
                        $this->replacePN($positionnr); // show the first position (default_show_lowest is 0)
                        break;
                    default:
                        $this->replacePN($showposition); // show the position found
                        break;
                }
            }
        }
        // show all positions
        $this->showAllPositions();
        // delete empty position markers
        $this->clearP();
    }

    /**
     * Clear empty position markers
     */
    private function clearP() {
        $this->setContent(preg_replace("/#p[0-9]+#/", "", $this->getContent()));
    }

    /**
     * explode the #pn# code to the required number of positions
     * 
     */
    private function explodePN() {
        // get the required number of positions
        $positioncount = $this->getObject()->getVersion($this->getMode())->getPositionCount();
        $code = '';
        // create the p codes
        for ($number = 1; $number <= $positioncount; $number++) {
            $code .= Terms::object_p($number);
        }
        // replace the pn code
        $this->replaceTerm(Terms::OBJECT_PN, $code);
    }

    /**
     * replace the #pn# code by the required position
     * 
     * @param int $number the position number
     */
    private function replacePN($number) {
        $this->replaceTerm(Terms::OBJECT_PN, Terms::object_p($number));
    }

    /**
     * replace the #pn# code by the required position in a deep link
     * 
     * @param int $number the position number
     * @param int $objectid the object to deep link to
     */
    private function replacePNDeepLink($number, $objectid) {
        $deeplinkstructure = Structures::getStructureByName(LSSNames::STRUCTURE_DEEP_LINK)->getVersion($this->getMode(), $this->getContext())->getBody();
        $deeplink = str_replace(Terms::ADMIN_CONTENT, Terms::object_p($number), $deeplinkstructure);
        $deeplink = str_replace(Terms::ADMIN_OBJECT_ID, $objectid, $deeplink);
        $this->replaceTerm(Terms::OBJECT_PN, $deeplink);
    }

    /**
     * show all positions in their numbered positions, 
     * 
     */
    private function showAllPositions() {
        $positions = $this->getObject()->getVersion($this->getMode())->getPositions();
        $objectsshown = 0; // check the number of objects shown, if too much, lazy load the rest
        foreach ($positions as $position) {
            if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_REFERRAL) {
                $this->cacheable = false;
            }
            if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_INSTANCE) {
                $this->cacheable = false;
            }
            if ($this->hasTerm(Terms::object_p($position->getNumber()))) {
                // if the position contains an object, check visibility for this object
                $showposition = true;
                if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                    $object = $position->getPositionContent()->getObject();
                    if (!$object->isVisible($this->getMode(), $this->getContext())) {
                        $showposition = false;
                    }
                }
                // show the position
                if ($showposition) {
                    $lazyload = false;
                    // if there is an object in this position
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                        // if the object is instanciable
                        $object = $position->getPositionContent()->getObject();
                        if ($object->getIsTemplateBased()) {
                            if ($object->getTemplate()->getInstanceAllowed()) {
                                // count the number of instanciable objects
                                $objectsshown = $objectsshown + 1;
                            }
                        }
                        // if there are more than x instanciable objects
                        if ($objectsshown > Settings::getSetting(Setting::CONTENT_PRELOADPNOBJECTS)->getValue()) {
                            // lazy load the rest
                            $lazyload = true;
                        }
                    }
                    // show the position with or without the lazy load for instanciable objects
                    $positionfactory = new PositionFactory($position, $this->getContext(), $this->getMode());
                    $positionfactory->factor($lazyload, $objectsshown);
                    $this->replaceTerm(Terms::object_p($position->getNumber()), $positionfactory->getContent());
                } else {
                    $this->replaceTerm(Terms::object_p($position->getNumber()), '');
                }
            } else {
                // no place found in the layout for this position, can be intentional (e.g. some content
                // isn't shown in mobile contexts)
            }
        }
    }

    /**
     * Is this object cacheable? Objects with referrals aren't.
     * Only has the correct value after the object has been factored.
     * 
     * @return boolean
     */
    public function getCacheable() {
        return $this->cacheable;
    }

    /**
     * create the bread crumb trail for this object
     * 
     * @return string
     */
    private function factorBreadCrumbs() {
        $parents = CacheObjectAddressableParentObjects::getObjectAddressableParentsByMode($this->getObject(), $this->getMode());
        $breadcrumbstructure = Structures::getStructureByName(LSSNames::STRUCTURE_BREADCRUMB)->getVersion($this->getMode(), $this->getContext())->getBody();
        $breadcrumbseparatorstructure = Structures::getStructureByName(LSSNames::STRUCTURE_BREADCRUMB_SEPARATOR)->getVersion($this->getMode(), $this->getContext())->getBody();
        $breadcrumbs = '';
        foreach ($parents as $parent) {
            if (!$parent->isSiteRoot()) {
                if ($breadcrumbs > '') {
                    $breadcrumbs .= $breadcrumbseparatorstructure;
                }
                $breadcrumb = str_replace(Terms::POSITION_CONTENT, $parent->getName(), $breadcrumbstructure);
                $breadcrumb = str_replace(Terms::POSITION_REFERRAL, CommandFactory::getObject($parent, $this->getMode(), Contexts::getContextByGroupAndName($this->getContext()->getContextGroup(), Context::CONTEXT_DEFAULT)), $breadcrumb);
                $breadcrumb = str_replace(Terms::POSITION_REFERRAL_URL, $parent->getBaseSEOURL($this->getMode()), $breadcrumb);
                $breadcrumbs .= $breadcrumb;
            }
        }
        return $breadcrumbs;
    }

    /**
     * Create the menu for this object
     * 
     * @return string
     */
    private function factorMenu() {
        $menu = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_MENU)->getVersion($this->getMode(), $this->getContext())->getBody();
        $menuitem = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_MENU_ITEM)->getVersion($this->getMode(), $this->getContext())->getBody();
        // add the object name
        $menu = str_replace(Terms::OBJECT_ROOT_NAME, $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getName(), $menu);
        // add the unpublished indicator
        if ($this->getObject()->hasChanged()) {
            $objectunpublished = Structures::getStructureByName(LSSNames::STRUCTURE_OBJECT_UNPUBLISHED_INDICATOR)->getVersion($this->getMode(), $this->getContext())->getBody();
            $menu = str_replace(Terms::OBJECT_UNPUBLISHED_INDICATOR, $objectunpublished, $menu);
        } 
        // remove the indicator when it isn't used
        $menu = str_replace(Terms::OBJECT_UNPUBLISHED_INDICATOR, "", $menu);            
        // add the menu items
        $edit = '';
        $add = '';
        // edit option
        if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_EDIT)) {
            $edit = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_EDIT_BUTTON), $menuitem);
            $edit = str_replace(Terms::OBJECT_ITEM_COMMAND, CommandFactory::editObject($this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject(), $this->getContext()), $edit);
        }
        // move option
        if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_CREATOR_EDIT) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_EDIT)) {
            $move = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_MOVE_BUTTON), $menuitem);
            $move = str_replace(Terms::OBJECT_ITEM_COMMAND, CommandFactory::moveObject($this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject(), $this->getContext()), $move);
        }
        // add option
//        if (Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($this->getObject(), Authorization::OBJECT_FRONTEND_ADD)) {
//            $add = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_ADD_BUTTON), $menuitem);
//            $add = str_replace(Terms::OBJECT_ITEM_COMMAND, CommandFactory::addContent($this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject(), $this->getMode(), $this->getContext()), $add);
//        }
        $menu = str_replace(Terms::OBJECT_ITEM_CONTENT, $edit . $move . $add, $menu);
        return $menu;
    }

    /**
     * Create the edit button for this object
     * 
     * @return string
     */
    private function factorEditButton() {
        $edit = Structures::getStructureByName(LSSNames::STRUCTURE_EDIT_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $edit = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_EDIT_BUTTON), $edit);
        $edit = str_replace(Terms::OBJECT_ITEM_COMMAND, CommandFactory::editObject($this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject(), $this->getContext()), $edit);
        // add the unpublished indicator
        if ($this->getObject()->hasChanged()) {
            $objectunpublished = Structures::getStructureByName(LSSNames::STRUCTURE_OBJECT_UNPUBLISHED_INDICATOR)->getVersion($this->getMode(), $this->getContext())->getBody();
            $edit = str_replace(Terms::OBJECT_UNPUBLISHED_INDICATOR, $objectunpublished, $edit);
        } 
        // remove the indicator when it isn't used
        $edit = str_replace(Terms::OBJECT_UNPUBLISHED_INDICATOR, "", $edit);            
        return $edit;
    }

    /**
     * Create the move button for this object
     * 
     * @return string
     */
    private function factorMoveButton() {
        $edit = Structures::getStructureByName(LSSNames::STRUCTURE_MOVE_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $edit = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_MOVE_BUTTON), $edit);
        $edit = str_replace(Terms::OBJECT_ITEM_COMMAND, CommandFactory::moveObject($this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject(), $this->getContext()), $edit);
        return $edit;
    }

    /**
     * Create the toggle button 
     * 
     * @return string
     */
    private function factorButtonToggle() {
        $edit = Structures::getStructureByName(LSSNames::STRUCTURE_BUTTON_TOGGLE)->getVersion($this->getMode(), $this->getContext())->getBody();
        $edit = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_BUTTON_TOGGLE), $edit);
        $edit = str_replace(Terms::OBJECT_ITEM_COMMAND, 'buttontoggle', $edit);
        return $edit;
    }

    /**
     * Create the edit panel for this object
     * 
     * @return string
     */
    private function factorEditPanel() {
        $edit = Structures::getStructureByName(LSSNames::STRUCTURE_EDIT_PANEL)->getVersion($this->getMode(), $this->getContext())->getBody();
        $edit = str_replace(Terms::OBJECT_ITEM_ID, 'EP' . $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getId(), $edit);
        // for new objects, fill the edit panel
        if ($this->getObject()->getNew()) {
            $editadminfactory = new EditAdminFactory();
            // initialize the admin factory
            $editadminfactory->setContext($this->getContext());
            $editadminfactory->setMode($this->getMode());
            // factor the object edit panel
            $editadminfactory->factor($this->getObject());
            $edit = str_replace(Terms::ADMIN_CONTENT, $editadminfactory->getContent(), $edit);
        }
        $edit = str_replace(Terms::ADMIN_CONTENT, '', $edit);
        return $edit;
    }

    /**
     * Create the move panel for this object
     * 
     * @return string
     */
    private function factorMovePanel() {
        $move = Structures::getStructureByName(LSSNames::STRUCTURE_MOVE_PANEL)->getVersion($this->getMode(), $this->getContext())->getBody();
        $move = str_replace(Terms::OBJECT_ITEM_ID, 'MP' . $this->getObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getId(), $move);
        $move = str_replace(Terms::ADMIN_CONTENT, '', $move);
        return $move;
    }

    /**
     * Create the add button for this object
     * 
     * @return string
     */
    private function factorAddButton() {
        $add = Structures::getStructureByName(LSSNames::STRUCTURE_ADD_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $add = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_ADD_BUTTON), $add);
        $add = str_replace(Terms::OBJECT_ITEM_COMMAND, CommandFactory::addContent($this->getObject(), $this->getMode(), $this->getContext()), $add);
        return $add;
    }

    /**
     * Create the add panel for this object
     * 
     * @return string
     */
    private function factorAddPanel() {
        $add = Structures::getStructureByName(LSSNames::STRUCTURE_ADD_PANEL)->getVersion($this->getMode(), $this->getContext())->getBody();
        $add = str_replace(Terms::OBJECT_ITEM_ID, 'AP' . $this->getObject()->getId(), $add);
        $add = str_replace(Terms::ADMIN_CONTENT, '', $add);
        return $add;
    }

    /**
     * Create the config button, the config is site global, but can be integrated 
     * in the site in different locations/objects and different ways,
     * depending on authorization 
     * 
     * @return string
     */
    private function factorConfigButton() {
        $config = Structures::getStructureByName(LSSNames::STRUCTURE_CONFIG_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $config = str_replace(Terms::ADMIN_VALUE, Helper::getLang(LSSNames::STRUCTURE_CONFIG_BUTTON), $config);
        // factor the config panel
        $configadminfactory = new ConfigAdminFactory;
        $configadminfactory->setContext($this->getContext());
        $configadminfactory->setMode($this->getMode());
        $configadminfactory->setObject($this->getObject());
        $configadminfactory->factor();
        $config = str_replace(Terms::OBJECT_ITEM_CONTENT, $configadminfactory->getContent(), $config);
        return $config;
    }

    /**
     * Create the config panel for this object
     * 
     * @return string
     */
    private function factorConfigPanel() {
        // get the panel
        $config = Structures::getStructureByName(LSSNames::STRUCTURE_CONFIG_PANEL)->getVersion($this->getMode(), $this->getContext())->getBody();
        // insert the panel id
        $config = str_replace(Terms::OBJECT_ITEM_ID, 'CP' . $this->getObject()->getId(), $config);
        // empty by default
        $config = str_replace(Terms::ADMIN_CONTENT, '', $config);
        return $config;
    }

}