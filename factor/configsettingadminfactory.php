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
 * Factor the setting configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigSettingAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific setting is requested, show this one, otherwise open with
        // the default setting
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validSetting(Request::getCommand()->getValue())) {
                $setting = Settings::getSetting(Request::getCommand()->getValue());
            }
        } else {
            $settings = Settings::getSettings();
            $row = $settings->fetchObject();
            $setting = Settings::getSetting($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the settings
        $settings = Settings::getSettings();
        $section .= $this->factorListBox($baseid . '_settinglist', CommandFactory::configSetting($this->getObject(), $this->getMode(), $this->getContext()), $settings, $setting->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_SETTINGS));
        // no add button, settings can't be created by the user
        $section .= $this->factorButtonGroup($this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_SETTINGS));
        // factor the default setting
        $content = '';
        // open the first setting
        $content = $this->factorConfigSettingContent($setting);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the setting config edit content 
     * 
     * @param setting $setting
     * @return string
     */
    private function factorConfigSettingContent($setting) {
        $baseid = 'CP' . $this->getObject()->getId() . '_setting';
        $section = '';
        $admin = '';
        // setting name
        $section .= $this->factorTextInput($baseid . '_name', '', $setting->getName(), Helper::getLang(AdminLabels::ADMIN_SETTING_NAME), 'disabled');
        // no remove button, settings can't be removed by the user
        // add setting value
        $section .= $this->factorTextInput($baseid . '_value', CommandFactory::editSettingValue($setting), $setting->getValue(), Helper::getLang(AdminLabels::ADMIN_SETTING_VALUE));
        $admin .= $this->factorSection($baseid . '_header', $section);
        $section = '';
        
        return $admin;
    }

}