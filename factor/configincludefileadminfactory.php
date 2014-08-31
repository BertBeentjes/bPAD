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
 * Factor the includefile configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigIncludeFileAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific includefile is requested, show this one, otherwise open with
        // the default includefile
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validIncludeFile(Request::getCommand()->getValue())) {
                $includefile = FileIncludes::getFileInclude(Request::getCommand()->getValue());
            }
        } else {
            $includefiles = FileIncludes::getFileIncludes();
            $row = $includefiles->fetchObject();
            $includefile = FileIncludes::getFileInclude($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = '';
        $section = '';
        // factor the includefiles
        $includefiles = FileIncludes::getFileIncludes();
        $section .= $this->factorListBox($baseid . '_includefilelist', CommandFactory::configIncludeFile($this->getObject(), $this->getMode(), $this->getContext()), $includefiles, $includefile->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_INCLUDE_FILES));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addIncludeFile($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_INCLUDE_FILE)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_INCLUDE_FILES));
        // factor the default includefile
        $content = '';
        // open the first includefile
        $content = $this->factorConfigIncludeFileContent($includefile);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the includefile config edit content 
     * 
     * @param fileinclude $includefile
     * @return string
     */
    private function factorConfigIncludeFileContent($includefile) {
        $baseid = 'CP' . $this->getObject()->getId() . '_includefile';
        $section = '';
        $admin = '';
        // includefile name
        $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editIncludeFileName($includefile), $includefile->getName(), Helper::getLang(AdminLabels::ADMIN_INCLUDE_FILE_NAME));
        // add the text input for the mime type
        $section .= $this->factorTextInput($baseid . '_mime', CommandFactory::editIncludeFileMimeType($includefile), $includefile->getMimeType(), Helper::getLang(AdminLabels::ADMIN_INCLUDE_FILE_MIME_TYPE));
        // remove button 
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeIncludeFile($this->getObject(), $includefile, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_INCLUDE_FILE)));
        $admin .= $this->factorSection($baseid . '_header', $section);
        $section = '';        
        // add publish button above
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_publish', CommandFactory::publishIncludeFileVersion($includefile), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_FILEINCLUDEVERSION)));
        // add the text area for editing the file
        $includefileversion = $includefile->getVersion(Modes::getMode(Mode::EDITMODE));
        $section .= $this->factorTextArea($baseid . '_body' . $includefileversion->getId(), CommandFactory::editIncludeFileVersionBody($includefile), $includefileversion->getBody(), $includefile->getName());
        // add publish button below
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_publish', CommandFactory::publishIncludeFileVersion($includefile), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_FILEINCLUDEVERSION)));
        $admin .= $this->factorSection($baseid . '_header', $section);       
        return $admin;
    }

}