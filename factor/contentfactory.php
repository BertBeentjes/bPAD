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
 * The content factory creates content at the highest level, the object factory
 * and position factory are underlying factories.
 * 
 * The content factory is used in pages and file includes
 *
 * @since 0.4.0
 */
class ContentFactory extends Factory {

    /**
     * factor content, find all terms and replace them with the specified content
     * 
     * @return boolean true if success
     */
    public function factor() {
        if (is_string($this->getContent()) && is_object($this->getContext()) && is_object($this->getMode())) {
            // check for terms
            // replace the session id term by a new session id
            if (strstr($this->getContent(), Terms::CONTENT_SESSION_ID)) {
                $this->replaceTerm(Terms::CONTENT_SESSION_ID, Session::newSession()->getSessionIdentifier(), $this->getContent());
            }
            // replace the bpad version term by the last command id
            if (strstr($this->getContent(), Terms::CONTENT_COMMAND_ID)) {
                $this->replaceTerm(Terms::CONTENT_COMMAND_ID, Commands::getLastCommand()->getId(), $this->getContent());
            }
            // replace the content root term by the content
            if (strstr($this->getContent(), Terms::CONTENT_ROOT)) {
                $this->replaceTerm(Terms::CONTENT_ROOT, $this->getRootContent(), $this->getContent());
            }
            // replace the error message term by the error message container
            if (strstr($this->getContent(), Terms::CONTENT_ERROR_MESSAGE)) {
                $this->replaceTerm(Terms::CONTENT_ERROR_MESSAGE, $this->getErrorMessageContainer(), $this->getContent());
            }
            // replace the modal term by the modal container
            if (strstr($this->getContent(), Terms::CONTENT_MODAL)) {
                $this->replaceTerm(Terms::CONTENT_MODAL, $this->getModalContainer(), $this->getContent());
            }
            // replace the styles term by the styles
            if (strstr($this->getContent(), Terms::CONTENT_STYLES)) {
                $this->replaceTerm(Terms::CONTENT_STYLES, CacheStyles::getCacheStyles($this->getMode(), $this->getContext()), $this->getContent());
            }
            // add the sub folder for this site to file includes
            if (strstr($this->getContent(), Terms::CONTENT_SITE_ROOT)) {
                $this->replaceTerm(Terms::CONTENT_SITE_ROOT, Settings::getSetting(Setting::SITE_ROOT)->getValue(), $this->getContent());
            }
            // add the sub folder for this site to file includes
            if (strstr($this->getContent(), Terms::CONTENT_SITE_ROOT_FOLDER)) {
                $this->replaceTerm(Terms::CONTENT_SITE_ROOT_FOLDER, Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue(), $this->getContent());
            }
            // replace the settings term by the settings (used in frontend javascript)
            if (strstr($this->getContent(), Terms::CONTENT_SETTINGS)) {
                $this->replaceTerm(Terms::CONTENT_SETTINGS, $this->factorSettings(), $this->getContent());
            }
            // replace the processing term by the processing language string (used in frontend javascript)
            if (strstr($this->getContent(), Terms::ADMIN_PROCESSING)) {
                $this->replaceTerm(Terms::ADMIN_PROCESSING, Helper::getLang(AdminLabels::ADMIN_PROCESSING), $this->getContent());
            }
            // replace the bpad version term by the bpad version
            if (strstr($this->getContent(), Terms::CONTENT_BPAD_VERSION)) {
                $this->replaceTerm(Terms::CONTENT_BPAD_VERSION, Versions::getLatestVersion()->getVersion(), $this->getContent());
            }
            // replace the bpad language term by the bpad language
            if (strstr($this->getContent(), Terms::CONTENT_BPAD_LANGUAGE)) {
                $this->replaceTerm(Terms::CONTENT_BPAD_LANGUAGE, Settings::getSetting(Setting::SITE_LANGUAGE)->getValue(), $this->getContent());
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_FACTORY_NOT_INITIALIZED_CORRECTLY) . ' @ ' . __METHOD__);
        }
        return true;
    }
    
    /**
     * Get the content to put in the content root position
     * 
     * @return string
     */
    protected function getRootContent() {
        $rootobject = Objects::getObject(syscon::SITE_ROOT_OBJECT);
        $rootcontent = CacheObjects::getCacheObject($rootobject, $this->getContext(), $this->getMode());
        return $rootcontent;
    }
    
    /**
     * Get the error message container
     * 
     * @return string
     */
    protected function getErrorMessageContainer() {
        // get the container
        return Structures::getStructureByName(LSSNames::STRUCTURE_ERROR_MESSAGE)->getVersion($this->getMode(), $this->getContext())->getBody();
    }
    
    /**
     * Get the modal message container
     * 
     * @return string
     */
    protected function getModalContainer() {
        // get the container
        return Structures::getStructureByName(LSSNames::STRUCTURE_MODAL)->getVersion($this->getMode(), $this->getContext())->getBody();
    }
    
    /**
     * create a json settings string for the frontend
     */
    private function factorSettings() {
        $settings = '{';
        $settings .= 'SITE_ROOT_OBJECT : "' . SysCon::SITE_ROOT_OBJECT . '", ';
        $settings .= 'SITE_ROOT : "' . Settings::getSetting(Setting::SITE_ROOT)->getValue() . '", ';
        $settings .= 'SITE_ROOTFOLDER : "' . Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue() . '", ';
        $settings .= 'GOOGLE_ANALYTICSCODE : "' . Settings::getSetting(Setting::GOOGLE_ANALYTICSCODE)->getValue() . '"';
        $settings .= '}';
        return $settings;
    }

}