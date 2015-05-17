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
 * Factor the form handler configuration and administration interface
 *
 * @since 0.4.4
 */
class ConfigFormHandlerAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific form handler is requested, show this one, otherwise open with
        // the default form handler
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validFormHandler(Request::getCommand()->getValue())) {
                $formhandler = FormHandlers::getFormHandler(Request::getCommand()->getValue());
            }
        } else {
            $formhandlers = FormHandlers::getFormHandlers();
            $row = $formhandlers->fetchObject();
            $formhandler = FormHandlers::getFormHandler($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the form handlers
        $formhandlers = FormHandlers::getFormHandlers();
        $section .= $this->factorListBox($baseid . '_formhandlerlist', CommandFactory::configFormHandler($this->getObject(), $this->getMode(), $this->getContext()), $formhandlers, $formhandler->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_FORM_HANDLERS));
        // close button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addFormHandler($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_FORM_HANDLER)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_FORM_HANDLERS));
        // factor the default form handler
        $content = '';
        // open the first form handler
        $content = $this->factorConfigFormHandlerContent($formhandler);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the form handler config edit content 
     * 
     * @param formhandler $formhandler
     * @return string
     */
    private function factorConfigFormHandlerContent($formhandler) {
        $baseid = 'CP' . $this->getObject()->getId() . '_formhandler';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $this->factorTextInput($baseid . '_name', CommandFactory::editFormHandlerName($formhandler), $formhandler->getName(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_NAME));
        // remove button 
        if ($formhandler->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeFormHandler($this->getObject(), $formhandler, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_FORM_HANDLER)));
        }
        $admin .= $section;
        $section = '';
        // get the form
        $section .= $this->factorTextInput($baseid . '_formhandler_emailto', CommandFactory::editFormHandlerEmailTo($formhandler), $formhandler->getEmailTo(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_EMAIL_TO));
        $section .= $this->factorTextInput($baseid . '_formhandler_emailbcc', CommandFactory::editFormHandlerEmailBCC($formhandler), $formhandler->getEmailBCC(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_EMAIL_BCC));
        $section .= $this->factorTextInput($baseid . '_formhandler_emailfrom', CommandFactory::editFormHandlerEmailFrom($formhandler), $formhandler->getEmailFrom(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_EMAIL_FROM));
        $section .= $this->factorTextInput($baseid . '_formhandler_emailreplyto', CommandFactory::editFormHandlerEmailReplyTo($formhandler), $formhandler->getEmailReplyTo(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_EMAIL_REPLY_TO));
        $section .= $this->factorTextInput($baseid . '_formhandler_emailsubject', CommandFactory::editFormHandlerEmailSubject($formhandler), $formhandler->getEmailSubject(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_EMAIL_SUBJECT));
        $section .= $this->factorTextArea($baseid . '_formhandler_emailtext', CommandFactory::editFormHandlerEmailText($formhandler), $formhandler->getEmailText(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_EMAIL_TEXT));
        $section .= $this->factorTextInput($baseid . '_formhandler_exiturl', CommandFactory::editFormHandlerExitURL($formhandler), $formhandler->getExitURL(), Helper::getLang(AdminLabels::ADMIN_FORM_HANDLER_EXIT_URL));
        $admin .= $this->factorSubItem($section);
        $admin = $this->factorSection($baseid . '_section' . $formhandler->getId(), $admin, $sectionheader);
        return $admin;
    }

}