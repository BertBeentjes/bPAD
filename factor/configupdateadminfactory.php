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
class ConfigUpdateAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // the download button
        $section .= $this->factorLinkButton($baseid . '_download', '_update/update.json', Helper::getLang(AdminLabels::ADMIN_BUTTON_DOWNLOAD_UPDATE));
        // the upload form
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_UPDATE)->getVersion(Modes::getMode(Mode::VIEWMODE), $this->getContext())->getBody();
        // special terms for the upload iframe
        $section .= str_replace(Terms::ADMIN_SITE_ROOT_FOLDER, Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue(), $structure);
        // header
        $section .= $this->factorButtonGroup($this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_UPDATES));
        $this->setContent($admin);
    }
    
}