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
 * Factor the admin functions for editing content
 *
 * @since 0.4.0
 */
class EditAdminFactory extends AdminFactory {

    private $containerobject; // the object that contains the factored object and will be refreshed after some admin activities

    /**
     * Factor the admin/edit functions
     * 
     * @param object $object (optional) the object to factor, in most situations taken from the request
     * @param object $containerobject (optional) the container object in the frontend to refresh
     */

    public function factor($object = NULL, $containerobject = NULL) {
        if (!isset($object)) {
            $addressparts = Request::getCommand()->getItemAddressParts();
            $object = Objects::getObject($addressparts[1]);
        }
        if (isset($containerobject)) {
            $this->containerobject = $containerobject;
        } else {
            $this->containerobject = $object;
        }
        // always start editing at the root of the template based object structure
        $object = $object->getVersion($this->getMode())->getObjectTemplateRootObject();
        // factor the object
        $this->setContent($this->factorObject($object, true));
    }

    /**
     * Get the container object to refresh
     * 
     * @return object
     */
    private function getContainerObject() {
        return $this->containerobject;
    }

    /**
     * factor the admin items for an object
     * 
     * @param object $object the object to factor
     * @param boolean $isroot is this object the root or not, roots show the object header and buttons and stuff, other objects don't
     * @return string
     */
    private function factorObject($object, $isroot) {
        // To edit an object, you must have one of several permissions:
        //   - managecontent: general permission to manage content in the site
        //   - frontendedit: edit the frontend of the site, basic general edit permission
        //   - frontendcreatoredit: similar to frontendedit, but now only for items you have created yourself
        // And the object must be either template root or part of a template
        //
        // check authorization and rootness
        if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT)) {
            $admin = '';
            $section = '';
            $sectionheader = '';
            $baseid = 'A' . $object->getId();
            if ($isroot) {
                $headercontent = '';
                // factor the header for the object 
                $headercontent .= $this->factorObjectHeader($object, $baseid);
                // factor the buttons for this object
                $headercontent .= $this->factorButtons($baseid . '_head', $object);
                $sectionheader = $this->factorSectionHeader($baseid . '_objhead', $headercontent);
            }
            // factor an additional header for the object if it is in a template
            if ($object->getIsTemplate()) {
                $section .= $this->factorTemplateObjectHeader($object, $baseid);
            }
            // factor the object version 
            $objectversion = $object->getVersion($this->getMode());
            // factor the object version for the object 
            $section .= $this->factorObjectVersion($objectversion, $baseid);
            // factor positions
            // TODO: create add buttons for each position, and create add buttons
            // for missing positions in templates
            $positions = $objectversion->getPositions();
            $curnumber = 1;
            foreach ($positions as $position) {
                // insert add buttons for missing positions in templates
                while ($position->getNumber() > $curnumber) {
                    $section .= $this->factorPositionNumberAddButtons($object, $baseid, $curnumber);
                    $curnumber = $curnumber + 1;
                }
                // factor the add buttons between the last position and this one
                $section .= $this->factorPositionNumberAddButtons($object, $baseid, $curnumber);
                // factor the position currently here
                $section .= $this->factorPosition($position, $baseid . '_P' . $position->getId());
                $curnumber = $curnumber + 1;
            }
            // create add button for new positions after the last one
            $section .= $this->factorPositionNumberAddButtons($object, $baseid, $curnumber, true);
            // wrap the section for roots and add buttons
            if ($isroot) {
                $section .= $this->factorButtons($baseid . '_foot', $object);
                $admin .= $this->factorSection($baseid, $section, $sectionheader);
            } else {
                // if this is a searchable subobject, add delete, move up, move down buttons
                if ($object->getIsObjectTemplateRoot() && $object->getTemplate()->getSearchable()) {
                    $subitemname = '';
                    $section .= $this->factorSubItemButtons($baseid, $object);
                    $subitemname .= $this->factorTextInput($baseid . '_name', CommandFactory::editObjectName($object), $object->getName(), Helper::getLang(AdminLabels::ADMIN_OBJECT_NAME));
                    $admin .= $this->factorSectionCollapsed($object->getId(), $subitemname . $section, $object->getName());
                } else {
                    $admin .= $this->factorSubItem($section);
                }
            }
            // wrap the section
            // return the result
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
        return $admin;
    }

    /**
     * Decide what type of add buttons to show
     * 
     * @param object $object
     * @param string $baseid
     * @param int $curnumber
     */
    private function factorPositionNumberAddButtons($object, $baseid, $curnumber, $createlast = false) {
        $section = '';
        $buttonadded = false;
        if ($object->getIsTemplate()) {
            // for #pn# type layouts (unlimited positions)
            if ($object->getVersion($this->getMode())->getLayout()->isPNType()) {
                $section .= $this->factorPositionAddButtons($baseid . '_posadd', $object, $curnumber);
            } else {
                // for p# layouts
                $positions = $object->getVersion($this->getMode())->getLayout()->getAllPositions($this->getMode());
                foreach ($positions as $position) {
                    // if this is the requested position, or we are adding at the end ($curnumber == 0)
                    if ($position == $curnumber || ($position >= $curnumber && $createlast)) {
                        // if this position doesn't exist
                        if (!$object->getVersion($this->getMode())->hasPosition($curnumber)) {
                            $section .= $this->factorPositionAddButtons($baseid . '_posadd' . $position, $object, $position);
                        }
                    }
                }
            }
        } else {
            // does this object allow adding 
            if ($object->getVersion($this->getMode())->hasAvailablePositions()) {
                // is the user authorized to do something with this object
                if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_ADD)) {
                    // find the position
                    if ($object->getVersion($this->getMode())->hasPosition($curnumber)) {
                        $position = $object->getVersion($this->getMode())->getPosition($curnumber);
                        if (isset($position)) {
                            // is there an object in the position
                            if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_OBJECT) {
                                $childobject = $position->getPositionContent()->getObject();
                                // is the object visible
                                if ($childobject->isVisible($this->getMode(), $this->getContext())) {
                                    // factor the template add buttons for a normal object  
                                    $addbuttons = $this->factorAddButtons($object, $curnumber, $baseid . '_A' . $curnumber . '_T');
                                    // if there are buttons, add a section
                                    if ($addbuttons > '') {
                                        $addbuttonsection = $this->factorButtonGroupAlt($addbuttons);
                                        $addbuttonsection = $this->factorSectionAdd($baseid . '_SC' . $curnumber, $addbuttonsection, Helper::getLang(LSSNames::STRUCTURE_ADD_BUTTON));
                                        $section .= $addbuttonsection;
                                    }
                                    // add the move/edit/cancel buttons
                                    if ($childobject->getIsObjectTemplateRoot() && !$childobject->getIsTemplate() && !$childobject->getTemplate()->getSearchable()) {
                                        $subitem = '';
                                        $subitem = $childobject->getName();
                                        $subitem .= $this->factorSubItemButtons($baseid . 'CO' . $childobject->getId(), $childobject);
                                        $section .= $this->factorSubItem($subitem);
                                    }
                                    $buttonadded = true;
                                }
                            }
                        }
                    } elseif ($createlast) {
                        // factor the template add buttons
                        $addbuttonsection = $this->factorButtonGroupAlt($this->factorAddButtons($object, $curnumber, $baseid . '_A' . $curnumber . '_T'));
                        $addbuttonsection = $this->factorSectionAdd($baseid . '_SC' . $curnumber, $addbuttonsection, Helper::getLang(LSSNames::STRUCTURE_ADD_BUTTON));
                        $section .= $addbuttonsection;
                        $buttonadded = true;
                    }
                }
                if ($createlast && !$buttonadded) {
                    // add respond button
                    if (Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_RESPOND)) {
                        if ($object->getVersion($this->getMode())->getLayout()->isPNType()) {
                            // TODO: factor the template respond button
                            $section .= '<span style="color: black;">RESPOND BUTTON</span>';
                        }
                    }
                }
            }
        }
        return $section;
    }

    /**
     * Create a section with add buttons for a position, number should be given
     * when a position has a specific number, for pn type layouts, omit the number
     * to insert at the end, or give the number to insert in the given
     * position number (and move existing content a position up)
     * 
     * @param string $baseid
     * @param object $object
     * @param int $number (optional)
     * @return string
     */
    private function factorPositionAddButtons($baseid, $object, $number = 0) {
        // create a section with the add buttons for the position
        $section = '';
        $section .= $this->factorButton($baseid . '_positionadd_contentitem', CommandFactory::addPositionContentItem($this->getContainerObject(), $object, $number, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_POSITION_ADD_CONTENT_ITEM));
        $section .= $this->factorButton($baseid . '_positionadd_contentitem', CommandFactory::addPositionObject($this->getContainerObject(), $object, $number, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_POSITION_ADD_OBJECT));
        $section .= $this->factorButton($baseid . '_positionadd_contentitem', CommandFactory::addPositionInstance($this->getContainerObject(), $object, $number, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_POSITION_ADD_INSTANCE));
        $section .= $this->factorButton($baseid . '_positionadd_contentitem', CommandFactory::addPositionReferral($this->getContainerObject(), $object, $number, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_POSITION_ADD_REFERRAL));
        $section = $this->factorButtonGroupAlt($section);
        return $section;
    }

    /**
     * Factor the buttons for the root object
     * 
     * @param string $baseid
     * @param object $object
     * @return string
     */
    private function factorButtons($baseid, $object) {
        $section = '';
        // add publish button -> add 
        // after publishing, a content.get command is chained (hence the viewmode)
        // TODO: find a better solution for the mode, hardcoding viewmode may haunt this code later on
        if ($object->getNew()) {
            $section .= $this->factorButton($baseid . '_publish', CommandFactory::editObjectPublish($object, Modes::getMode(Mode::VIEWMODE), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_NEW));
        } else {
            if ($object->getActive()) {
                $section .= $this->factorButton($baseid . '_publish', CommandFactory::editObjectPublish($object, Modes::getMode(Mode::VIEWMODE), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH));
            }
        }

        // TODO: add an undo button, that does an undo based upon the command log 
        // $section .= $this->factorButton($baseid, CommandFactory::editObjectUndo($object), Helper::getLang(AdminLabels::ADMIN_BUTTON_UNDO));
        
        // add a keep button, keep is the natural state, so it only needs to close the admin section
        if (!$object->getNew()) {
            $section .= $this->factorButton($baseid . '_keep', CommandFactory::editObjectKeep($object, Modes::getMode(Mode::VIEWMODE), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_KEEP));
        }
        if ($object->getActive() && !$object->getNew()) {
            // add a cancel button
            $section .= $this->factorButton($baseid . '_cancel', CommandFactory::editObjectCancel($object, Modes::getMode(Mode::VIEWMODE), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL));
        }
        if ($object->getNew()) {
            // add a cancel button
            $section .= $this->factorButton($baseid . '_cancel', CommandFactory::addObjectCancel($object), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL));
        }
        if (!$object->getIsTemplate()) {
            // 'recycle bin' button
            $section .= $this->factorRecycleBinButton($baseid, $object);
        }

        $section = $this->factorButtonGroup($section);

        // add a button to show/hide the add buttons
        // add a button to show/hide layouts, styles, structures
        if (!$object->getIsTemplate()) {       
            $toggles = '';

            $toggles .= $this->factorButtonToggleAdd();
            $toggles .= $this->factorButtonToggleLSS();

            $section .= $this->factorButtonGroup($toggles);
        }
                
        return $section;
    }

    /**
     * Factor the buttons for a subobject
     * 
     * @param string $baseid
     * @param object $object
     * @return string
     */
    private function factorSubItemButtons($baseid, $object) {
        $section = '';
        // move up button
        if ($object->getVersion($this->getMode())->isMoveableUp()) {
            $section .= $this->factorButton($baseid . '_moveup', CommandFactory::editObjectMoveUp($object, $this->getContainerObject(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_MOVE_UP));
        }
        // move down button
        if ($object->getVersion($this->getMode())->isMoveableDown()) {
            $section .= $this->factorButton($baseid . '_movedown', CommandFactory::editObjectMoveDown($object, $this->getContainerObject(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_MOVE_DOWN));
        }
        // if the object isn't searchable, add an edit button
        if (!$object->getTemplate()->getSearchable()) {
            $section .= $this->factorEditButton($object);
        }
        // if the object is new and searchable, add a cancel button
        if ($object->getTemplate()->getSearchable() && $object->getNew()) {
            // add a cancel button
            $section .= $this->factorButton($baseid . '_cancel', CommandFactory::editObjectCancel($object, Modes::getMode(Mode::VIEWMODE), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL));
        }
        // 'recycle bin' button
        $section .= $this->factorRecycleBinButton($baseid, $object);
        $section = $this->factorButtonGroup($section);
        return $section;
    }

    /**
     * Factor the recycle bin button, depending on the active status
     * 
     * @param string $baseid
     * @param object $object
     * @return string
     */
    private function factorRecycleBinButton($baseid, $object) {
        $button = '';
        if (!$object->getNew()) {
            if ($object->getActive()) {
                $button .= $this->factorButton($baseid . '_recycle', CommandFactory::editObjectActive($object, $this->getContainerObject(), Modes::getMode(Mode::VIEWMODE), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_TO_RECYCLE_BIN));
            } else {
                $button .= $this->factorButton($baseid . '_recycle', CommandFactory::editObjectActiveFromBin($object, Modes::getMode(Mode::VIEWMODE), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_FROM_RECYCLE_BIN));
            }
        }
        return $button;
    }

    /**
     * Create the edit button for this object
     * 
     * @param object $object
     * @return string
     */
    private function factorEditButton($object) {
        $edit = Structures::getStructureByName(LSSNames::STRUCTURE_EDIT_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $edit = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_EDIT_BUTTON), $edit);
        $edit = str_replace(Terms::OBJECT_ITEM_COMMAND, CommandFactory::editObject($object->getVersion($this->getMode())->getObjectTemplateRootObject(), $this->getContext()), $edit);
        return $edit;
    }

    /**
     * Create the toggle button for add items
     * 
     * @return string
     */
    private function factorButtonToggleAdd() {
        $edit = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD)->getVersion($this->getMode(), $this->getContext())->getBody();
        $edit = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD_NAME), $edit);
        $edit = str_replace(Terms::OBJECT_ITEM_COMMAND, 'showhide-add', $edit);
        return $edit;
    }

    /**
     * Create the toggle button for lss items
     * 
     * @return string
     */
    private function factorButtonToggleLSS() {
        $edit = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS)->getVersion($this->getMode(), $this->getContext())->getBody();
        $edit = str_replace(Terms::OBJECT_ITEM_CONTENT, Helper::getLang(LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS_NAME), $edit);
        $edit = str_replace(Terms::OBJECT_ITEM_COMMAND, 'showhide-lss', $edit);
        return $edit;
    }

    /**
     * Create the header for editing an object, and edit the object name and active bit
     * 
     * @param object $object
     * @param string baseid the basis for the id of the input fields
     * @return string section
     */
    private function factorObjectHeader($object, $baseid) {
        $section = '';
        $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editObjectName($object), $object->getName(), Helper::getLang(AdminLabels::ADMIN_OBJECT_NAME));
        // show the template name for object roots
        if ($object->getIsObjectTemplateRoot() && !$object->getTemplate()->isDefault()) {
            $section .= $this->factorTextInput($baseid . '_templatename', '', $object->getTemplate()->getName(), Helper::getLang(AdminLabels::ADMIN_OBJECT_TEMPLATE_NAME), 'disabled');
        }
        // show the link for addressable objects
        if ($object->isAddressable($this->getMode())) {
            $section .= $this->factorTextInput($baseid . '_link', '', Terms::object_internal_link($object), Helper::getLang(AdminLabels::ADMIN_OBJECT_INTERNAL_LINK), 'disabled');
        }
        // show a deep link for instanciable objects
        if ($object->getTemplate()->getInstanceAllowed() && !$object->isAddressable($this->getMode())) {
            $section .= $this->factorTextInput($baseid . '_deeplink', '', $object->getDeepLink($this->getMode()), Helper::getLang(AdminLabels::ADMIN_OBJECT_DEEP_LINK), 'disabled');
        }
        return $section;
    }

    /**
     * Create extra header thingies for editing an object when it is in a template
     * 
     * @param object $object
     * @param string baseid the basis for the id of the input fields
     * @return string section
     */
    private function factorTemplateObjectHeader($object, $baseid) {
        // factor the set selection box
        $sets = Sets::getSets();
        $section = $this->factorListBox($baseid . '_set', CommandFactory::editObjectSet($object, $this->getContainerObject(), $this->getMode(), $this->getContext()), $sets, $object->getSet()->getId(), Helper::getLang(AdminLabels::ADMIN_OBJECT_SET));
        return $section;
    }

    /**
     * Create the edit functions for object versions
     * 
     * @param objectversion $objectversion
     * @param string $baseid
     * @return string
     */
    private function factorObjectVersion($objectversion, $baseid) {
        $section = '';
        // edit layout, style: when not inheriting from the template or the object is part of the template
        if ($objectversion->getInheritLayout() == false || $objectversion->getContainer()->getIsTemplate() == true) {
            if ($objectversion->getContainer()->getSet()->isDefault()) {
                $layouts = Layouts::getLayouts();
            } else {
                $layouts = Layouts::getLayoutsBySet($objectversion->getContainer()->getSet(), $objectversion->getLayout());
            }
            if ($objectversion->getContainer()->getIsTemplate()) {
                $section .= $this->factorListBox($baseid . '_layout', CommandFactory::editTemplateObjectVersionLayout($objectversion, $this->getContainerObject(), $this->getMode(), $this->getContext()), $layouts, $objectversion->getLayout()->getId(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_LAYOUT));
            } else {
                $section .= $this->factorListBoxLSS($baseid . '_layout', CommandFactory::editObjectVersionLayout($objectversion), $layouts, $objectversion->getLayout()->getId(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_LAYOUT));
            }
        }
        if ($objectversion->getInheritStyle() == false || $objectversion->getContainer()->getIsTemplate() == true) {
            if ($objectversion->getContainer()->getSet()->isDefault()) {
                $styles = Styles::getStylesByStyleType(Style::OBJECT_STYLE);
            } else {
                $styles = Styles::getStylesBySet(Style::OBJECT_STYLE, $objectversion->getContainer()->getSet(), $objectversion->getStyle());
            }
            if ($objectversion->getContainer()->getIsTemplate()) {
                $section .= $this->factorListBox($baseid . '_style', CommandFactory::editObjectVersionStyle($objectversion), $styles, $objectversion->getStyle()->getId(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_STYLE));
            } else {
                $section .= $this->factorListBoxLSS($baseid . '_style', CommandFactory::editObjectVersionStyle($objectversion), $styles, $objectversion->getStyle()->getId(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_STYLE));
            }
        }
        // edit argumentdefault: with managecontent permission only
        if (Authorization::getObjectPermission($objectversion->getContainer(), Authorization::OBJECT_MANAGE)) {
            // no use setting the default if there is no argument (i.e. the argument is the default)
            if ($objectversion->getArgument()->getId() != Argument::DEFAULT_ARGUMENT) {
                $section .= $this->factorTextInput($baseid . '_argdef', CommandFactory::editObjectVersionArgumentDefault($objectversion), $objectversion->getArgumentDefault(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT_DEFAULT));
            }
        }
        // edit argument, inheritlayout, inheritstyle, template: when in a template
        if ($objectversion->getContainer()->getIsTemplate()) {
            // edit argument
            $arguments = Arguments::getArguments();
            $section .= $this->factorListBox($baseid . '_argument', CommandFactory::editObjectVersionArgument($objectversion), $arguments, $objectversion->getArgument()->getId(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT));
            // edit inheritlayout
            $section .= $this->factorCheckBox($baseid . '_inheritlayout', CommandFactory::editObjectVersionInheritLayout($objectversion), $objectversion->getInheritLayout(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_LAYOUT));
            // edit inheritstyle
            $section .= $this->factorCheckBox($baseid . '_inheritstyle', CommandFactory::editObjectVersionInheritStyle($objectversion), $objectversion->getInheritStyle(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_STYLE));
            // edit template (this is the default template to use for new children)
            $templates = Templates::getTemplatesBySet($objectversion->getContainer()->getSet(), $objectversion->getTemplate());
            $section .= $this->factorListBox($baseid . '_template', CommandFactory::editObjectVersionTemplate($objectversion), $templates, $objectversion->getTemplate()->getId(), Helper::getLang(AdminLabels::ADMIN_OBJECT_VERSION_TEMPLATE));
        }
        return $section;
    }

    /**
     * Create the edit functions for object versions
     * 
     * @param position $position
     * @param string $baseid
     * @return string
     */
    private function factorPosition($position, $baseid) {
        $object = $position->getContainer()->getContainer();
        $section = '';
        // edit style & structure if template or if not inherited
        if ($position->getInheritStructure() == false || $object->getIsTemplate() == true) {
            if ($object->getSet()->isDefault()) {
                $structures = Structures::getStructures();
            } else {
                $structures = Structures::getStructuresBySet($object->getSet(), $position->getStructure());
            }
            if ($object->getIsTemplate()) {
                $section .= $this->factorListBox($baseid . '_structure', CommandFactory::editPositionStructure($position), $structures, $position->getStructure()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_STRUCTURE));
            } else {
                $section .= $this->factorListBoxLSS($baseid . '_structure', CommandFactory::editPositionStructure($position), $structures, $position->getStructure()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_STRUCTURE));
            }
        }
        if ($position->getInheritStyle() == false || $object->getIsTemplate() == true) {
            if ($object->getSet()->isDefault()) {
                $styles = Styles::getStylesByStyleType(Style::POSITION_STYLE);
            } else {
                $styles = Styles::getStylesBySet(Style::POSITION_STYLE, $object->getSet(), $position->getStyle());
            }
            if ($object->getIsTemplate()) {
                $section .= $this->factorListBox($baseid . '_style', CommandFactory::editPositionStyle($position), $styles, $position->getStyle()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_STYLE));
            } else {
                $section .= $this->factorListBoxLSS($baseid . '_style', CommandFactory::editPositionStyle($position), $styles, $position->getStyle()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_STYLE));
            }
        }
        // edit inherit style & structure if part of a template
        if ($object->getIsTemplate()) {
            // edit inheritlayout
            $section .= $this->factorCheckBox($baseid . '_inheritlayout', CommandFactory::editPositionInheritStructure($position), $position->getInheritStructure(), Helper::getLang(AdminLabels::ADMIN_POSITION_INHERIT_STRUCTURE));
            // edit inheritstyle
            $section .= $this->factorCheckBox($baseid . '_inheritstyle', CommandFactory::editPositionInheritStyle($position), $position->getInheritStyle(), Helper::getLang(AdminLabels::ADMIN_POSITION_INHERIT_STYLE));
        }
        // a position contains one of the types below
        switch ($position->getPositionContent()->getType()) {
            case PositionContent::POSITIONTYPE_CONTENTITEM:
                // position content item
                $section .= $this->factorPositionContentItem($position->getPositionContent(), $baseid . '_C' . $position->getPositionContent()->getId());
                break;
            case PositionContent::POSITIONTYPE_INSTANCE:
                // position instance
                $section .= $this->factorPositionInstance($position->getPositionContent(), $baseid . '_I' . $position->getPositionContent()->getId());
                break;
            case PositionContent::POSITIONTYPE_OBJECT:
                // position object (recurse into admin objects, if this object belongs here)
                $section .= $this->factorPositionObject($position->getPositionContent(), $baseid . '_O' . $position->getPositionContent()->getId());
                break;
            case PositionContent::POSITIONTYPE_REFERRAL:
                // position referral
                $section .= $this->factorPositionReferral($position->getPositionContent(), $baseid . '_R' . $position->getPositionContent()->getId());
                break;
            default:
                break;
        }
        // for templates, create a remove position button
        if ($object->getIsTemplate()) {
            // execute the command, put the right value in value in core.js and create a delete function for positions
            $section .= $this->factorButton($baseid . '_positionremove', CommandFactory::removeObjectPosition($this->getContainerObject(), $object, $position, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_POSITION_REMOVE));
        }
        if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_CONTENTITEM) {
            $section = $this->factorSubItem($section);
        }
        return $section;
    }

    /**
     * Factor the content item in a certain position
     * 
     * @param positioncontentitem $contentitem
     * @param string $baseid
     * @return string
     */
    private function factorPositionContentItem($contentitem, $baseid) {
        $section = '';
        $object = $contentitem->getContainer()->getContainer()->getContainer();
        // name, inputtype; templates only
        if ($object->getIsTemplate() == true) {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editPositionContentItemName($contentitem), $contentitem->getName(), Helper::getLang(AdminLabels::ADMIN_POSITION_CONTENT_ITEM_NAME));
            // get input type list
            $list = array();
            $list[0][0] = PositionContentItem::INPUTTYPE_INPUTBOX;
            $list[0][1] = Helper::getLang(PositionContentItem::INPUTTYPE_INPUTBOX);
            $list[1][0] = PositionContentItem::INPUTTYPE_TEXTAREA;
            $list[1][1] = Helper::getLang(PositionContentItem::INPUTTYPE_TEXTAREA);
            $list[2][0] = PositionContentItem::INPUTTYPE_COMBOBOX;
            $list[2][1] = Helper::getLang(PositionContentItem::INPUTTYPE_COMBOBOX);
            $list[3][0] = PositionContentItem::INPUTTYPE_UPLOADEDFILE;
            $list[3][1] = Helper::getLang(PositionContentItem::INPUTTYPE_UPLOADEDFILE);
            // create input type list box
            $section .= $this->factorListBox($baseid . '_inputtype', CommandFactory::editPositionContentItemInputType($contentitem), $list, $contentitem->getInputType(), Helper::getLang(AdminLabels::ADMIN_POSITION_CONTENT_ITEM_INPUT_TYPE));
        }
        // contentitembody
        // if this is a template, the label is 'body', otherwise the
        // label is the content item name
        if ($object->getIsTemplate() == true) {
            $label = Helper::getLang(AdminLabels::ADMIN_POSITION_CONTENT_ITEM_BODY);
        } else {
            // localize if possible
            $label = Helper::getLang($contentitem->getName());
        }
        switch ($contentitem->getInputType()) {
            case PositionContentItem::INPUTTYPE_COMBOBOX:
                // get the values for the combo box
                // TODO: maybe rebuild into a cached table with values. Should be faster, but may be less flexible and more complex. Have to think about it.
                $items = Store::getPositionContentItemDistinctBodiesByNameAndTemplateIdAndInputType($contentitem->getName(), $contentitem->getTemplate()->getId(), $contentitem->getInputType());
                $list = array();
                $counter = 0;
                while ($item = $items->fetchObject()) {
                    $list[$counter][0] = $item->body;
                    $list[$counter][1] = $item->body;
                    $counter = $counter + 1;
                }
                // create the combo box
                $section .= $this->factorComboBox($baseid . '_body', CommandFactory::editPositionContentItemBody($contentitem), $list, $contentitem->getBody(), $label);
                break;
            case PositionContentItem::INPUTTYPE_INPUTBOX:
                // create the input box
                $section .= $this->factorTextInput($baseid . '_body', CommandFactory::editPositionContentItemBody($contentitem), Helper::escapeContentQuote($contentitem->getBody()), $label);
                break;
            case PositionContentItem::INPUTTYPE_TEXTAREA:
                // create the text area
                $section .= $this->factorTextArea($baseid . '_body', CommandFactory::editPositionContentItemBody($contentitem), $contentitem->getBody(), $label);
                break;
            case PositionContentItem::INPUTTYPE_UPLOADEDFILE:
                // create the file uploader
                $section .= $this->factorUpload($baseid . '_body', $object->getId(), $contentitem->getContainer()->getNumber());
                break;
            default: break;
        }
        return $section;
    }

    /**
     * Factor the instance in a certain position
     * 
     * @param positioninstance $instance
     * @param string $baseid
     * @return string
     */
    private function factorPositionInstance($instance, $baseid) {
        // factor instance
        $section = '';
        // get objects in view mode
        $objects = Objects::getAddressableObjects(Modes::getMode(Mode::VIEWMODE));
        // change the name for the first object (default value)
        $objects[0][1] = Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_OBJECT_DEFAULT);
        $section .= $this->factorListBox($baseid . '_object', CommandFactory::editPositionInstanceObject($instance), $objects, $instance->getObject()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_OBJECT));
        // get templates
        $templates = Templates::getTemplates(Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_TEMPLATE_DEFAULT));
        $section .= $this->factorListBox($baseid . '_template', CommandFactory::editPositionInstanceTemplate($instance), $templates, $instance->getTemplate()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_TEMPLATE));
        // listwords
        $section .= $this->factorTextInput($baseid . '_listwords', CommandFactory::editPositionInstanceListWords($instance), $instance->getListWords(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_LISTWORDS));
        // searchwords
        $section .= $this->factorTextInput($baseid . '_searchwords', CommandFactory::editPositionInstanceSearchWords($instance), $instance->getSearchWords(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_SEARCHWORDS));
        // parent
        // get objects in view mode
        $objects = Objects::getAddressableObjects(Modes::getMode(Mode::VIEWMODE));
        // change the name for the first object (default value)
        $objects[0][1] = Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_OBJECT_DEFAULT);
        $section .= $this->factorListBox($baseid . '_object', CommandFactory::editPositionInstanceParent($instance), $objects, $instance->getParent()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_PARENT));
        // activeitems
        $section .= $this->factorCheckBox($baseid . '_activeitems', CommandFactory::editPositionInstanceActiveItems($instance), $instance->getActiveItems(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_ACTIVE_ITEMS));
        // fillonload
        $section .= $this->factorCheckBox($baseid . '_fillonload', CommandFactory::editPositionInstanceFillOnLoad($instance), $instance->getFillOnLoad(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_FILL_ON_LOAD));
        // useinstancecontext
        $section .= $this->factorCheckBox($baseid . '_useinstancecontext', CommandFactory::editPositionInstanceUseInstanceContext($instance), $instance->getUseInstanceContext(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_USE_INSTANCE_CONTEXT));
        // order by
        $orderby = Templates::getTemplateOrderFieldsByTemplate($instance->getTemplate());
        $section .= $this->factorListBox($baseid . '_orderby', CommandFactory::editPositionInstanceOrderBy($instance), $orderby, $instance->getOrderBy(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_ORDER_BY));
        // group by
        $section .= $this->factorCheckBox($baseid . '_groupby', CommandFactory::editPositionInstanceGroupBy($instance), $instance->getGroupBy(), Helper::getLang(AdminLabels::ADMIN_POSITION_INSTANCE_GROUP_BY));
        return $section;
    }

    /**
     * Factor the object in a certain position
     * 
     * @param positionobject $positionobject
     * @param string $baseid
     * @return string
     */
    private function factorPositionObject($positionobject, $baseid) {
        // factor object (recursive call)
        $object = $positionobject->getObject();
        $section = '';
        // if this object is part of a template, or it is based upon the same template as the current one and isn't a new root
        // or if it is active and not new and based on a searchable template (which means it is treated as part of the parent)
        if ($object->getIsTemplate() == 1 || ($object->getIsObjectTemplateRoot() == false && $object->getTemplate()->getId() == $positionobject->getContainer()->getContainer()->getContainer()->getTemplate()->getId()) || (!$object->getTemplate()->isDefault() && $object->getTemplate()->getSearchable() && ($object->getActive() || $object->getNew()))) {
            // recurse
            $section = $this->factorObject($object, false);
        }
        return $section;
    }

    /**
     * Factor the referral in a certain position
     * 
     * @param positionreferral $referral
     * @param string $baseid
     * @return string
     */
    private function factorPositionReferral($referral, $baseid) {
        $section = '';
        // factor referral
        // argument: list box with arguments
        $arguments = Arguments::getArguments();
        $section .= $this->factorListBox($baseid . '_argument', CommandFactory::editPositionReferralArgument($referral), $arguments, $referral->getArgument()->getId(), Helper::getLang(AdminLabels::ADMIN_POSITION_REFERRAL_ARGUMENT));
        // order by: list box with options
        $orderby = PositionReferral::getOrderByList();
        $section .= $this->factorListBox($baseid . '_orderby', CommandFactory::editPositionReferralOrderBy($referral), $orderby, $referral->getOrderBy(), Helper::getLang(AdminLabels::ADMIN_POSITION_REFERRAL_ORDER_BY));
        // number of items: input box
        $section .= $this->factorTextInput($baseid . '_numberofitems', CommandFactory::editPositionReferralNumberOfItems($referral), $referral->getNumberOfItems(), Helper::getLang(AdminLabels::ADMIN_POSITION_REFERRAL_NUMBER_OF_ITEMS));
        return $section;
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

}