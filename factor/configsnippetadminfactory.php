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
 * Factor the snippet configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigSnippetAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific snippet is requested, show this one, otherwise open with
        // the default snippet
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validSnippet(Request::getCommand()->getValue())) {
                $snippet = Snippets::getSnippet(Request::getCommand()->getValue());
            }
        } else {
            $snippets = Snippets::getSnippets();
            $row = $snippets->fetchObject();
            $snippet = Snippets::getSnippet($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the snippets
        $snippets = Snippets::getSnippets();
        $section .= $this->factorListBox($baseid . '_snippetlist', CommandFactory::configSnippet($this->getObject(), $this->getMode(), $this->getContext()), $snippets, $snippet->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_SNIPPETS));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addSnippet($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_SNIPPET)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_SNIPPETS));
        // factor the default snippet
        $content = '';
        // open the first snippet
        $content = $this->factorConfigSnippetContent($snippet);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the snippet config edit content 
     * 
     * @param snippet $snippet
     * @return string
     */
    private function factorConfigSnippetContent($snippet) {
        $baseid = 'CP' . $this->getObject()->getId() . '_snippet';
        $section = '';
        $admin = '';
        // snippet name
        $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editSnippetName($snippet), $snippet->getName(), Helper::getLang(AdminLabels::ADMIN_SNIPPET_NAME));
        // add the text input for the mime type
        $section .= $this->factorTextInput($baseid . '_mime', CommandFactory::editSnippetMimeType($snippet), $snippet->getMimeType(), Helper::getLang(AdminLabels::ADMIN_SNIPPET_MIME_TYPE));
        // TODO: add context group selector
        $contextgroups = ContextGroups::getContextGroups();
        $section .= $this->factorListBox($baseid . '_contextgrouplist', CommandFactory::editSnippetContextGroup($snippet), $contextgroups, $snippet->getContextGroup()->getId(), Helper::getLang(AdminLabels::ADMIN_SNIPPET_CONTEXT_GROUP));
        // remove button 
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeSnippet($this->getObject(), $snippet, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_SNIPPET)));
        $admin .= $this->factorSection($baseid . '_header', $section);
        $section = '';        
        // add publish button above
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_publish', CommandFactory::publishSnippetVersion($snippet), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_SNIPPETVERSION)));
        // add the text area for editing the file
        $snippetversion = $snippet->getVersion(Modes::getMode(Mode::EDITMODE));
        $section .= $this->factorTextArea($baseid . '_body' . $snippetversion->getId(), CommandFactory::editSnippetVersionBody($snippet), $snippetversion->getBody(), $snippet->getName());
        // add publish button below
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_publish', CommandFactory::publishSnippetVersion($snippet), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_SNIPPETVERSION)));
        $admin .= $this->factorSection($baseid . '_header', $section);       
        return $admin;
    }

}