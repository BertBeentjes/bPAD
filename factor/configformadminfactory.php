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
 * Factor the form storage configuration and administration interface
 *
 * @since 0.4.4
 */
class ConfigFormAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific form is requested, show this one, otherwise open with
        // the default form
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validForm(Request::getCommand()->getValue())) {
                $form = FormStorages::getFormStorage(Request::getCommand()->getValue());
            }
        } else {
            $forms = FormStorages::getFormStorages();
            $row = $forms->fetchObject();
            $form = FormStorages::getFormStorage($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the forms
        $forms = FormStorages::getFormStorages();
        $section .= $this->factorListBox($baseid . '_formlist', CommandFactory::configForm($this->getObject(), $this->getMode(), $this->getContext()), $forms, $form->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_FORMS));
        // close button
        $section .= $this->factorButtonGroup($this->factorCloseButton($baseid));        
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_FORMS));
        // factor the default form
        $content = '';
        // open the first form
        $content = $this->factorConfigFormContent($form);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the form config edit content 
     * 
     * @param formstorage $form
     * @return string
     */
    private function factorConfigFormContent($form) {
        $baseid = 'CP' . $this->getObject()->getId() . '_form';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $this->factorTextInput($baseid . '_name', '', htmlspecialchars(substr($form->getForm(),0,45)), Helper::getLang(AdminLabels::ADMIN_FORM_NAME), 'disabled');
        // remove button 
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeForm($this->getObject(), $form, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_FORM)));
        $admin .= $section;
        $section = '';
        // get the form
        $section .= $this->factorTextArea($baseid . '_form_data', '', $form->getForm(), Helper::getLang(AdminLabels::ADMIN_FORM_FORM), "disabled");
        $section .= $this->factorTextInput($baseid . '_form_handler', '', $form->getFormHandler()->getName(), Helper::getLang(AdminLabels::ADMIN_FORM_FORM_HANDLER), "disabled");
        $admin .= $this->factorSubItem($section);
        $admin = $this->factorSection($baseid . '_section' . $form->getId(), $admin, $sectionheader);
        return $admin;
    }

}