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
 * Factor the structure configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigStructureAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific structure is requested, show this one, otherwise open with
        // the default structure
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validStructure(Request::getCommand()->getValue())) {
                $structure = Structures::getStructure(Request::getCommand()->getValue());
            }
        } else {
            $structures = Structures::getStructures();
            $firststructure = $structures[0];
            $structure = Structures::getStructure($firststructure[0]);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the structures
        $structures = Structures::getStructures();
        $section .= $this->factorListBox($baseid . '_structurelist', CommandFactory::configStructure($this->getObject(), $this->getMode(), $this->getContext()), $structures, $structure->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_STRUCTURES));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addStructure($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_STRUCTURE)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_STRUCTURES));
        // factor the default structure
        $content = '';
        // open the first structure
        $content = $this->factorConfigStructureContent($structure);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the structure config edit content 
     * 
     * @param structure $structure
     * @return string
     */
    private function factorConfigStructureContent($structure) {
        $baseid = 'CP' . $this->getObject()->getId() . '_structure';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $structure->getName();
        // structure name
        if ($structure->getIsBpadDefined()) {
            // can't change the name when it's bpad defined
            // TODO: replace disable by a structure or something that makes it html independent
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editStructureName($structure), $structure->getName(), Helper::getLang(AdminLabels::ADMIN_STRUCTURE_NAME), 'disabled');
        } else {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editStructureName($structure), $structure->getName(), Helper::getLang(AdminLabels::ADMIN_STRUCTURE_NAME));
        }
        // structure set
        $sets = Sets::getSets();
        $section .= $this->factorListBox($baseid . '_setlist', CommandFactory::editStructureSet($structure), $sets, $structure->getSet()->getId(), Helper::getLang(AdminLabels::ADMIN_STRUCTURE_SET));
        // remove button 
        if ($structure->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeStructure($this->getObject(), $structure, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTURE)));
        }
        $admin .= $section;
        $section = '';
        // get the contexts
        $contexts = Contexts::getContexts();
        while ($row = $contexts->fetchObject()) {
            $context = Contexts::getContext($row->id);
            $section = '';
            // get the structureversions for the context (if there is one)
            $structureversion = $structure->getVersion($this->getMode(), $context);
            if ($structureversion->getOriginal()) {
                // show the body for the structure (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $structureversion->getId(), CommandFactory::editStructureVersionBody($structure, $this->getMode(), $context), $structureversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName());
                // add publish, cancel buttons for the version
                $buttons = '';
                $buttons .= $this->factorButton($baseid . '_publishversion_c' . $context->getId(), CommandFactory::publishStructureVersion($this->getObject(), $structure, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_STRUCTUREVERSION));
                $buttons .= $this->factorButton($baseid . '_cancelversion_c' . $context->getId(), CommandFactory::cancelStructureVersion($this->getObject(), $structure, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL_STRUCTUREVERSION));
                // show the delete button when there is a structure, and the structure is not the default default
                if (!($context->getContextGroup()->isDefault() && $context->isDefault())) {
                    $buttons .= $this->factorButton($baseid . '_removeversion_c' . $context->getId(), CommandFactory::removeStructureVersion($this->getObject(), $structure, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTUREVERSION));
                }
                $section .= $this->factorButtonGroup($buttons);
            } else {
                // show the body for the structure (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $structureversion->getId(), CommandFactory::editStructureVersionBody($structure, $this->getMode(), $context), $structureversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName(), 'disabled');
                // show the add button when there is no structure
                $section .= $this->factorButtonGroup($this->factorButton($baseid . '_addversion_c' . $context->getId(), CommandFactory::addStructureVersion($this->getObject(), $structure, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_STRUCTUREVERSION)));
            }
            $admin .= $this->factorSubItem($section);
        }
        $admin = $this->factorSection($baseid . '_section' . $structure->getId(), $admin, $sectionheader);
        return $admin;
    }

}