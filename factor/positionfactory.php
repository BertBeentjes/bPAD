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
 * Factors a position
 */
class PositionFactory extends Factory {

    private $position; // the position to factor
    private $containerobject; // the object that contains the position (in a version)

    /**
     * initialize the position factory
     * 
     * @param position $position
     * @param context $context
     * @param mode $mode
     */

    public function __construct($position, $context, $mode) {
        // do some input checking
        if (is_object($position) && is_object($context) && is_object($mode)) {
            $this->setContext($context);
            $this->setMode($mode);
            $this->setPosition($position);
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_FACTORY_NOT_INITIALIZED_CORRECTLY) . ' @ ' . __METHOD__);
        }
    }

    /**
     * set the position for the factory
     * 
     * @param position $newposition
     */
    public function setPosition($newposition) {
        $this->position = $newposition;
        $this->containerobject = $this->getPosition()->getContainer()->getContainer();
    }

    /**
     * Get the position for this factory
     * 
     * @return position
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Get the object that contains this position
     * 
     * @return object
     */
    public function getContainerObject() {
        return $this->containerobject;
    }

    /**
     * factor an object position
     * 
     * @return boolean true if success
     */
    public function factor() {
        // start by getting the structure for this position
        $this->setContent($this->getPosition()->getStructure()->getVersion($this->getMode(), $this->getContext())->getBody());
        // check for terms and resolve them
        $this->factorTerms();
        // fill in the content
        $positioncontent = '';
        $positionshortcontent = '';
        switch ($this->getPosition()->getPositionContent()->getType()) {
            case PositionContent::POSITIONTYPE_CONTENTITEM:
                // factor contentitem
                $positioncontentitem = new ContentItemFactory($this->getPosition()->getPositionContent(), $this->getContext(), $this->getMode());
                $positioncontentitem->factor();
                if ($positioncontentitem->getContentItem()->getInputType() == PositionContentItem::INPUTTYPE_UPLOADEDFILE) {
                    // factor file related terms, the file location is now
                    // in the $positioncontent. These terms are used to transfer
                    // (parts of) the filename to scripts or hrefs, or change 
                    // the filename for adaptive designs.
                    $positioncontent = '';
                    $this->factorFileTerms($positioncontentitem->getFolder(), $positioncontentitem->getFileName(), $positioncontentitem->getExtension());
                } else {
                    $positioncontent = $positioncontentitem->getContent();
                    $positionshortcontent = $positioncontentitem->getShortContent();
                }
                break;
            case PositionContent::POSITIONTYPE_INSTANCE:
                // get the instance context for the current context group
                if ($this->getPosition()->getPositionContent()->getActiveItems()) {
                    $instancecontext = Contexts::getContextByGroupAndName($this->getContext()->getContextGroup(), Context::CONTEXT_INSTANCE);
                } else {
                    $instancecontext = Contexts::getContextByGroupAndName($this->getContext()->getContextGroup(), Context::CONTEXT_RECYCLEBIN);
                }
                // factor the search box, if there
                $this->factorInstanceTerms();
                // get the instance, it contains placeholders for objects and a load more function
                $positioncontent = $this->factorInstance($instancecontext);
                break;
            case PositionContent::POSITIONTYPE_OBJECT:
                // create object placeholder
                $positioncontent = Terms::object_placeholder($this->getPosition()->getPositionContent()->getObject(), $this->getContext());
                break;
            case PositionContent::POSITIONTYPE_REFERRAL:
                // factor referral
                $positioncontent = $this->factorReferral();
                break;
            default:
                // no content found of a known type, apparently an empty position
                break;
        }
        $this->replaceTerm(Terms::POSITION_CONTENT, $positioncontent);
        $this->replaceTerm(Terms::POSITION_CONTENT_SHORT, $positionshortcontent);
        $this->replaceTerm(Terms::POSITION_CONTENT_PLAIN, strip_tags($positioncontent));
        // ready!
        return true;
    }

    /**
     * factor the referrals in this position
     * 
     * @return string
     */
    private function factorReferral() {
        // duplicate the positioncontent for use in each referral
        $referralstructure = $this->getContent();
        $positioncontent = '';
        $this->setContent(Terms::POSITION_CONTENT);
        // get the referrals
        $argument = $this->getPosition()->getPositionContent()->getArgument();
        $orderby = $this->getPosition()->getPositionContent()->getOrderBy();
        $objects = Objects::getObjectsByArgumentAndModeAndOrderBy($argument, $this->getMode(), $orderby);
        // factor the referrals
        // create a factory for the referral
        $referralfactory = new ReferralFactory('', NULL, $this->getContext(), $this->getMode());
        foreach ($objects as $object) {
            // check visibility for the objects, objects can be invisible in view mode
            if ($object->isVisible($this->getMode(), $this->getContext())) {
                // (re)initialize the factory
                $referralfactory->setContent($referralstructure);
                $referralfactory->setObject($object);
                // factor
                $referralfactory->factor();
                // and get the factored content
                $positioncontent .= $referralfactory->getContent();
            } else {
                // if it is a new object, check this one in edit mode
                if ($object->getNew()) {
                    if ($object->isVisible(Modes::getMode(Mode::EDITMODE), $this->getContext())) {
                        // (re)initialize the factory
                        $referralfactory->setContent($referralstructure);
                        $referralfactory->setObject($object);
                        // show this one in edit mode
                        $referralfactory->setMode(Modes::getMode(Mode::EDITMODE));
                        // factor
                        $referralfactory->factor();
                        // and get the factored content
                        $positioncontent .= $referralfactory->getContent();
                        // return the mode to normal 
                        $referralfactory->setMode($this->getMode());
                    }
                }
            }
        }
        return $positioncontent;
    }

    /**
     * factor the instance in this position
     * 
     * @param context instancecontext
     * @param string [usersearch] optional, passes an additional user search string for this instance
     * @return string
     */
    public function factorInstance($instancecontext, $usersearch = '') {
        // Check whether the instance shows one specific object, or contains a selection
        // by default, the instance object is the site root (the site root can't be selected in an instance)
        if ($this->getPosition()->getPositionContent()->getObject()->isSiteRoot()) {
            // add the objects
            if ($usersearch > '') {
                $objects = $this->getPosition()->getPositionContent()->getUserSearchObjects($usersearch);
            } else {
                $objects = $this->getPosition()->getPositionContent()->getObjects();
            }
            $returnvalue = '';
            $lastgroupvalue = '';
            $instancecontent = '';
            if (is_array($objects)) {
                $lazyloadstructure = Structures::getStructureByName(LSSNames::STRUCTURE_LAZY_LOAD)->getVersion($this->getMode(), $this->getContext())->getBody();
                $number = 0;
                foreach ($objects as $objectvalues) {
                    $object = $objectvalues['object'];
                    $groupvalue = $objectvalues['groupvalue'];
                    // check authorization for this object and show it
                    if ($object->isVisible($this->getMode(), $instancecontext)) {
                        // create a lazy load scenario for each object, a front end script lazy loads the content with the supplied command
                        $number = $number + 1;
                        // preload the first objects and lazy load the rest
                        if ($number <= Settings::getSetting(Setting::CONTENT_PRELOADINSTANCES)->getValue()) {
                            $load = Terms::object_placeholder($object, $instancecontext);
                        } else {
                            $containerid = $this->getPosition()->getId() . '-' . $number;
                            $load = str_replace(Terms::POSITION_REFERRAL, CommandFactory::loadObject($object, $containerid, $this->getMode(), $instancecontext), $lazyloadstructure);
                            $load = str_replace(Terms::POSITION_UID, 'I' . $containerid, $load);
                        }
                        // create grouped sections
                        if ($this->getPosition()->getPositionContent()->getGroupBy()) {
                            // if there is a new groupvalue, insert a section and a header
                            if ($groupvalue != $lastgroupvalue) {
                                if ($instancecontent > '') {
                                    $instancecontent = str_replace(Terms::POSITION_CONTENT, $instancecontent, Structures::getStructureByName(LSSNames::STRUCTURE_INSTANCE_SECTION)->getVersion($this->getMode(), $this->getContext())->getBody());
                                }
                                $instancecontent = $instancecontent . str_replace(Terms::POSITION_CONTENT, $groupvalue, Structures::getStructureByName(LSSNames::STRUCTURE_INSTANCE_HEADER)->getVersion($this->getMode(), $this->getContext())->getBody());
                                $returnvalue .= $instancecontent;
                                $instancecontent = '';
                                $lastgroupvalue = $groupvalue;
                            }
                        }
                        $instancecontent .= $load;
                    }
                }
                // add the last (or maybe only one) section to the content
                if ($instancecontent > '') {
                    $instancecontent = str_replace(Terms::POSITION_CONTENT, $instancecontent, Structures::getStructureByName(LSSNames::STRUCTURE_INSTANCE_SECTION)->getVersion($this->getMode(), $this->getContext())->getBody());
                }
                $returnvalue .= $instancecontent;
            }
            return $returnvalue;
        } else {
            // return the selected object in the current context (in this case, do not use the instance context, because it is more or less a 'copy' of the object)
            // the reason to use this method can be to use a widget or block in multiple locations on a site, but only administer it once.
            return Terms::object_placeholder($this->getPosition()->getPositionContent()->getObject(), $this->getContext());
        }
    }

    /**
     * check which terms are used in the position structure, and factor content for 
     * these terms
     */
    private function factorTerms() {
        // replace terms
        if ($this->hasTerm(Terms::POSITION_ID)) {
            $this->replaceTerm(Terms::POSITION_ID, 'P' . $this->getPosition()->getId());
        }
        if ($this->hasTerm(Terms::POSITION_MODE_ID)) {
            $this->replaceTerm(Terms::POSITION_MODE_ID, $this->getMode()->getId());
        }
        if ($this->hasTerm(Terms::POSITION_OBJECT_ID)) {
            $this->replaceTerm(Terms::POSITION_OBJECT_ID, $this->getContainerObject()->getId());
        }
        if ($this->hasTerm(Terms::POSITION_OBJECT_NAME)) {
            $this->replaceTerm(Terms::POSITION_OBJECT_NAME, $this->getContainerObject()->getName());
        }
        if ($this->hasTerm(Terms::POSITION_PARENT_SEO_URL)) {
            $this->replaceTerm(Terms::POSITION_PARENT_SEO_URL, $this->getContainerObject()->getObjectVersion($this->getMode())->getObjectParent()->getSEOURL($this->getMode()));
        }
        if ($this->hasTerm(Terms::POSITION_ROOT_CHANGE_DATE)) {
            $this->replaceTerm(Terms::POSITION_ROOT_CHANGE_DATE, $this->getContainerObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getChangeDate()->format(Helper::getDateTimeFormat()));
        }
        if ($this->hasTerm(Terms::POSITION_ROOT_CREATE_DATE)) {
            $this->replaceTerm(Terms::POSITION_ROOT_CREATE_DATE, $this->getContainerObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getCreateDate()->format(Helper::getDateTimeFormat()));
        }
        if ($this->hasTerm(Terms::POSITION_ROOT_CREATOR)) {
            $this->replaceTerm(Terms::POSITION_ROOT_CREATOR, $this->getContainerObject()->getCreateUser()->getFullName());
        }
        if ($this->hasTerm(Terms::POSITION_ROOT_EDITOR)) {
            $this->replaceTerm(Terms::POSITION_ROOT_EDITOR, $this->getContainerObject()->getChangeUser()->getFullName());
        }
        if ($this->hasTerm(Terms::POSITION_ROOT_OBJECT_NAME)) {
            $this->replaceTerm(Terms::POSITION_ROOT_OBJECT_NAME, $this->getContainerObject()->getVersion($this->getMode())->getObjectTemplateRootObject()->getName());
        }
        if ($this->hasTerm(Terms::POSITION_SEO_URL)) {
            $this->replaceTerm(Terms::POSITION_SEO_URL, $this->getContainerObject()->getSEOURL($this->getMode()));
        }
        $number = 1;
        while ($this->hasTerm(Terms::POSITION_UID)) {
            $this->replaceTerm(Terms::POSITION_UID, 'UP' . $this->getPosition()->getId() . '_' . $number);
        }
        // insert the class suffices
        $style = $this->getPosition()->getStyle();
        $this->replaceTerm(Terms::CLASS_SUFFIX, $style->getClassSuffix() . "_" . $style->getVersion($this->getMode(), $this->getContext())->getContext()->getContextGroup()->getShortName() . '_' . $style->getVersion($this->getMode(), $this->getContext())->getContext()->getShortName());
    }

    /**
     * check which terms are used in the position structure, and factor content for 
     * these terms
     */
    private function factorFileTerms($folder, $filename, $extension) {
        // replace terms
        if ($this->hasTerm(Terms::POSITION_FILE_DIR_NAME)) {
            $this->replaceTerm(Terms::POSITION_FILE_DIR_NAME, Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue() . '_' . Request::FILE . $folder);
        }
        if ($this->hasTerm(Terms::POSITION_FILE_EXTENSION)) {
            $this->replaceTerm(Terms::POSITION_FILE_EXTENSION, $extension);
        }
        if ($this->hasTerm(Terms::POSITION_FILE_NAME)) {
            $this->replaceTerm(Terms::POSITION_FILE_NAME, $filename);
        }
    }

    private function factorInstanceTerms() {
        // the default structure for the search box contains the search command term, so test this one before the search command
        if ($this->hasTerm(Terms::POSITION_SEARCH_BOX)) {
            $this->replaceTerm(Terms::POSITION_SEARCH_BOX, Structures::getStructureByName(LSSNames::STRUCTURE_SEARCH_BOX)->getVersion($this->getMode(), $this->getContext())->getBody());
        }
        // this one is used in the default search box structure, so should be tested after the search box itself
        if ($this->hasTerm(Terms::POSITION_SEARCH_COMMAND)) {
            $this->replaceTerm(Terms::POSITION_SEARCH_COMMAND, CommandFactory::searchPosition($this->getPosition(), $this->getMode(), $this->getContext()));
        }
    }

}

?>
