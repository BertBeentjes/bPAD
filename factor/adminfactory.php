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
 * The admin factory factors the admin/edit functions for the frontend
 *
 * @since 0.4.0
 */
class AdminFactory extends Factory {

    /**
     * 
     * @param string $id the id to use
     * @param string $command the command to execute
     * @param string $label the label on the button
     * @return string
     */
    protected function factorMainButton($id, $command, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_MAIN_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $command, '', $label);
        return $admin;
    }

    /**
     * 
     * @param string $id the id to use
     * @param string $link the link to open
     * @param string $label the label on the button
     * @return string
     */
    protected function factorLinkButton($id, $link, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_LINK_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $link, '', $label);
        return $admin;
    }

    /**
     * 
     * @param string $id the id to use
     * @param string $command the command to execute
     * @param string $label the label on the button
     * @return string
     */
    protected function factorMenuItem($command, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_MENU_ITEM)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = str_replace(Terms::ADMIN_COMMAND, $command, $structure);
        $admin = str_replace(Terms::ADMIN_CONTENT, $label, $admin);
        return $admin;
    }

    /**
     * 
     * @param string $id the id to use
     * @param string $command the command to execute
     * @param string $label the label on the button
     * @return string
     */
    protected function factorButton($id, $command, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $command, '', $label);
        return $admin;
    }

    /**
     * Factor an admin input box
     * 
     * @param string $id the id to use
     * @param string $command the command to execute 
     * @param string $value the start value of the input
     * @param string $label the label for the input
     * @param boolean $disabled (optional)
     * @return string the complete input
     */
    protected function factorTextInput($id, $command, $value, $label, $disabled = '') {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_TEXT_INPUT)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $command, $value, $label, $disabled);
        return $admin;
    }

    /**
     * factor an admin text area input
     * 
     * @param string $id the id to use
     * @param string $command the command to execute 
     * @param string $value the start value of the input
     * @param string $label the label for the input
     * @return string the complete input
     */
    protected function factorTextArea($id, $command, $value, $label, $disabled = '') {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_TEXT_AREA)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $command, htmlspecialchars($value), $label, $disabled);
        return $admin;
    }

    /**
     * factor an admin checkbox
     * 
     * @param string $id the id to use
     * @param string $command the command to execute 
     * @param string $value the start value of the input
     * @param string $label the label for the input
     * @return string the complete input
     */
    protected function factorCheckBox($id, $command, $value, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_CHECKBOX)->getVersion($this->getMode(), $this->getContext())->getBody();
        if ($value) {
            // TODO (low prio): create a structure for the true and the false value of a checkbox, the value here is html specific (but interpretable for other uses, so only a minor issue)
            $replacevalue = 'checked';
        } else {
            $replacevalue = '';
        }
        $admin = $this->factorTerms($structure, $id, $command, $replacevalue, $label);
        return $admin;
    }

    /**
     * factor an admin combobox
     * 
     * @param string $id the id to use
     * @param string $command the command to execute 
     * @param mixed $listoptions the options for the combo box in a resultset or an array
     * @param string $value the start value of the input
     * @param string $label the label for the input
     * @return string the complete input
     */
    protected function factorComboBox($id, $command, $listoptions, $value, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_COMBOBOX)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $command, $value, $label);
        $options = '';
        if (is_a($listoptions, 'resultset')) {
            while ($listoption = $listoptions->fetchObject()) {
                $options .= $this->factorListBoxOption($listoption->id, $listoption->name, $value);
            }
        }
        if (is_array($listoptions)) {
            foreach ($listoptions as $listoption) {
                $options .= $this->factorListBoxOption($listoption[0], $listoption[1], $value);
            }
        }
        $admin = str_replace(Terms::ADMIN_OPTIONS, $options, $admin);
        return $admin;
    }

    /**
     * factor an admin listbox 
     * 
     * @param string $id the id to use
     * @param string $command the command to execute 
     * @param mixed $options resultset or array, the options for the list box
     * @param string $value the start value of the input
     * @param string $label the label for the input
     * @return string the complete input
     */
    protected function factorListBox($id, $command, $listoptions, $value, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_LISTBOX)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $command, $value, $label);
        $options = '';
        if (is_a($listoptions, 'resultset')) {
            while ($listoption = $listoptions->fetchObject()) {
                $options .= $this->factorListBoxOption(htmlspecialchars($listoption->id), htmlspecialchars($listoption->name), $value);
            }
        }
        if (is_array($listoptions)) {
            foreach ($listoptions as $listoption) {
                $options .= $this->factorListBoxOption(htmlspecialchars($listoption[0]), htmlspecialchars($listoption[1]), $value);
            }
        }
        $admin = str_replace(Terms::ADMIN_OPTIONS, $options, $admin);
        return $admin;
    }

    /**
     * factor an admin listbox for layout items
     * 
     * @param string $id the id to use
     * @param string $command the command to execute 
     * @param mixed $options resultset or array, the options for the list box
     * @param string $value the start value of the input
     * @param string $label the label for the input
     * @return string the complete input
     */
    protected function factorListBoxLSS($id, $command, $listoptions, $value, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_LISTBOX_LSS)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, $command, $value, $label);
        $options = '';
        if (is_a($listoptions, 'resultset')) {
            while ($listoption = $listoptions->fetchObject()) {
                $options .= $this->factorListBoxOption($listoption->id, $listoption->name, $value);
            }
        }
        if (is_array($listoptions)) {
            foreach ($listoptions as $listoption) {
                $options .= $this->factorListBoxOption($listoption[0], $listoption[1], $value);
            }
        }
        $admin = str_replace(Terms::ADMIN_OPTIONS, $options, $admin);
        return $admin;
    }

    /**
     * factor an admin combobox or listbox option
     * 
     * @param string $id the id to use
     * @param string $value the start value of the input
     * @param string $selected the id of the selected value
     * @return string the complete input
     */
    protected function factorListBoxOption($id, $value, $selected) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_LISTBOX_OPTION)->getVersion($this->getMode(), $this->getContext())->getBody();
        // localize the value if possible
        // no command and no label for the individual options, they are given to the listbox
        $admin = $this->factorTerms($structure, $id, '', Helper::getLang($value), '');
        // check for the selected value
        if ($id == $selected) {
            // TODO: (low prio) create a structure for the selected value
            $admin = str_replace(Terms::ADMIN_SELECTED, 'selected', $admin);
        } else {
            $admin = str_replace(Terms::ADMIN_SELECTED, '', $admin);
        }
        return $admin;
    }

    /**
     * factor an admin button group
     * 
     * @param string $value the start value of the input
     * @param string $label optional label
     * @return string the complete input
     */
    protected function factorButtonGroup($value, $label = '') {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_BUTTON_GROUP)->getVersion($this->getMode(), $this->getContext())->getBody();
        // no command and no label for the button groups
        $admin = $this->factorTerms($structure, '', '', $value, $label);
        return $admin;
    }

    /**
     * factor an alternative admin button group for situations where there is a larger number of buttons (e.g. in the add panel)
     * 
     * @param string $value the start value of the input
     * @param string $label optional label
     * @return string the complete input
     */
    protected function factorButtonGroupAlt($value, $label = '') {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_BUTTON_GROUP_ALT)->getVersion($this->getMode(), $this->getContext())->getBody();
        // no command and no label for the button groups
        $admin = $this->factorTerms($structure, '', '', $value, $label);
        return $admin;
    }

    /**
     * factor a collapsed section for the admin panel
     * 
     * @param string $id the id for the section
     * @param string $value the start value of the input
     * @param string $label label
     * @return string the complete input
     */
    protected function factorSectionCollapsed($id, $value, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_SECTION_COLLAPSED)->getVersion($this->getMode(), $this->getContext())->getBody();
        // no command and no label for the button groups
        $admin = $this->factorTerms($structure, $id, '', $value, $label);
        return $admin;
    }

    /**
     * factor a collapsed section for the admin panel
     * 
     * @param string $id the id for the section
     * @param string $value the start value of the input
     * @param string $label label
     * @return string the complete input
     */
    protected function factorSectionAdd($id, $value, $label) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_SECTION_ADD)->getVersion($this->getMode(), $this->getContext())->getBody();
        $admin = $this->factorTerms($structure, $id, '', $value, $label);
        return $admin;
    }

    /**
     * factor an admin sub item
     * 
     * @param string $value the start value of the input
     * @return string the complete input
     */
    protected function factorSubItem($value) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_SUB_ITEM)->getVersion($this->getMode(), $this->getContext())->getBody();
        // no command and no label for the subitems, they are given to the combo box
        $admin = $this->factorTerms($structure, '', '', $value, '');
        return $admin;
    }

    /**
     * factor an admin error message box
     * 
     * @return string the error message box
     */
    protected function factorErrorMessage() {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_ERROR_MESSAGE)->getVersion($this->getMode(), $this->getContext())->getBody();
        return $structure;
    }

    /**
     * factor an admin section
     * 
     * @param string $id the id to use
     * @param string $body the body of the section
     * @param string $header the header of the section
     * @return string the complete input
     */
    protected function factorSection($id, $body, $header = '') {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_SECTION)->getVersion($this->getMode(), $this->getContext())->getBody();
        // no command for the secion
        $admin = $this->factorTerms($structure, $id, '', $body, $header, '');
        return $admin;
    }

    /**
     * factor an admin section header
     * 
     * @param string $id the id to use
     * @param string $value the start value of the input
     * @return string the complete input
     */
    protected function factorSectionHeader($id, $value) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_SECTION_HEADER)->getVersion($this->getMode(), $this->getContext())->getBody();
        // no command and no label for the secion
        $admin = $this->factorTerms($structure, $id, '', $value, '');
        return $admin;
    }

    /**
     * factor an admin separator, used to separate groups of fields
     * 
     * @return string the complete input
     */
    protected function factorSeparator() {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_SEPARATOR)->getVersion($this->getMode(), $this->getContext())->getBody();
        // no terms allowed in the separator
        return $structure;
    }

    /**
     * factor an upload iframe
     * 
     * @param string $id the id to use
     * @param string $objectid the id of the object
     * @param string $positionnr the number of the position
     * @return string the complete input
     */
    protected function factorUpload($id, $objectid, $positionnr) {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_UPLOAD)->getVersion($this->getMode(), $this->getContext())->getBody();
        // special terms for the upload iframe
        $admin = str_replace(Terms::ADMIN_ID, $id, $structure);
        $admin = str_replace(Terms::ADMIN_OBJECT_ID, $objectid, $structure);
        $admin = str_replace(Terms::ADMIN_POSITION_NUMBER, $positionnr, $admin);
        $admin = str_replace(Terms::ADMIN_SITE_ROOT_FOLDER, Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue(), $admin);
        return $admin;
    }

    /**
     * Replace some genericly used admin terms with their values
     * 
     * @param structure $structure the structure to replace the terms in
     * @param string $id the id for tags in the structure
     * @param string $command the command to execute when an input changes
     * @param string $value the value of an input in the structure
     * @param string $label the text label for the input
     * @param string $disabled (optional) disable the input
     * @return string
     */
    protected function factorTerms($structure, $id, $command, $value, $label, $disabled = '') {
        $structure = str_replace(Terms::ADMIN_ID, $id, $structure);
        $structure = str_replace(Terms::ADMIN_COMMAND, $command, $structure);
        $structure = str_replace(Terms::ADMIN_LABEL, $label, $structure);
        $structure = str_replace(Terms::ADMIN_DISABLED, $disabled, $structure);

        // add the value last, to prevent terms in the value to be changed
        $structure = str_replace(Terms::ADMIN_VALUE, $value, $structure);
        return $structure;
    }

    /**
     * Create add buttons for an object
     * 
     * @param object $object
     * @param int $number
     * @param string $baseid
     * @return string
     */
    protected function factorAddButtons($object, $number, $baseid) {
        $buttons = '';
        // get the templates 
        $set = $object->getSet();
        if ($set->isDefault()) {
            $templates = Templates::getTemplates();
        } else {
            $templates = Templates::getTemplatesBySet($set);
        }
        if (isset($templates)) {
            // create add buttons for each template
            foreach ($templates as $thistemplate) {
                $template = Templates::getTemplate($thistemplate[0]);
                // TODO: find a better way to include the mode for the chained content.get command. Viewmode is now hardcoded.
                $editobject = $object->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectTemplateRootObject();
                while ($editobject->getTemplate()->getSearchable() && !$editobject->isSiteRoot()) {
                    $editobject = $editobject->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectParent()->getVersion(Modes::getMode(Mode::VIEWMODE))->getObjectTemplateRootObject();
                }
                $buttons .= $this->factorButton($baseid . $template->getId(), CommandFactory::addObjectFromTemplate($object, $template, $number, Modes::getMode(Mode::VIEWMODE), $this->getContext(), $editobject), $template->getName());
            }
        }
        return $buttons;
    }

}