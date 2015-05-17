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
            // replace the site name term by the site name
            if (strstr($this->getContent(), Terms::CONTENT_SITE_NAME)) {
                $this->replaceTerm(Terms::CONTENT_SITE_NAME, Settings::getSetting(Setting::SITE_NAME)->getValue(), $this->getContent());
            }
            // replace the site locale term by the site locale
            if (strstr($this->getContent(), Terms::CONTENT_SITE_LOCALE)) {
                $this->replaceTerm(Terms::CONTENT_SITE_LOCALE, Settings::getSetting(Setting::SITE_LOCALE)->getValue(), $this->getContent());
            }
            // replace the content root term by the content
            if (strstr($this->getContent(), Terms::CONTENT_ROOT)) {
                $this->replaceTerm(Terms::CONTENT_ROOT, $this->getRootContent(), $this->getContent());
            }
            // replace the content root term by the content
            if (strstr($this->getContent(), Terms::CONTENT_METADATA)) {
                $this->replaceTerm(Terms::CONTENT_METADATA, $this->getMetaData(), $this->getContent());
            }
            // replace the content root term by the content
            if (strstr($this->getContent(), Terms::CONTENT_SITEMAP)) {
                $this->replaceTerm(Terms::CONTENT_SITEMAP, $this->getSiteMap(), $this->getContent());
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
            // add the site root url
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
            // add a fool proof config button (well... almost fool proof, only fools like me that destroy admin layouts or structures can still get stuck)
            if ($this->hasTerm(Terms::ADMIN_CONFIG_BUTTON)) {
                if (Authorization::getPagePermission(Authorization::AUTHORIZATION_MANAGE) || Authorization::getPagePermission(Authorization::LANGUAGE_MANAGE) || Authorization::getPagePermission(Authorization::ROLE_MANAGE) || Authorization::getPagePermission(Authorization::SYSTEM_MANAGE) || Authorization::getPagePermission(Authorization::TEMPLATE_MANAGE) || Authorization::getPagePermission(Authorization::USER_FLUSH_ARCHIVE) || Authorization::getPagePermission(Authorization::USER_MANAGE) || Authorization::getPagePermission(Authorization::SETTING_MANAGE)) {
                    $this->replaceTerm(Terms::ADMIN_CONFIG_BUTTON, $this->factorConfigButton());
                    $this->replaceTerm(Terms::ADMIN_CONFIG_PANEL, $this->factorConfigPanel());
                }
            }
            $this->clearTerm(Terms::ADMIN_CONFIG_BUTTON);
            $this->clearTerm(Terms::ADMIN_CONFIG_PANEL);
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
     * Get the metadata to put in the content metadata position
     * 
     * @return string
     */
    protected function getMetaData() {
        // recalc the request, so that the right metadata is used
        Request::init();
        // get the metadata content
        $rootobject = Objects::getObject(syscon::SITE_ROOT_OBJECT);
        $rootcontent = CacheObjects::getCacheObject($rootobject, Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_METADATA), Context::CONTEXT_DEFAULT), $this->getMode());        
        return $rootcontent;
    }
    
    /**
     * Get the sitemap to put in the content sitemap position
     * 
     * @return string
     */
    protected function getSiteMap() {
        $rootobject = Objects::getObject(syscon::SITE_ROOT_OBJECT);
        $rootcontent = CacheObjects::getCacheObject($rootobject, Contexts::getContextByGroupAndName(ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_SITEMAP), Context::CONTEXT_DEFAULT), $this->getMode());
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

    /**
     * Create the config button, the config is site global, but can be integrated 
     * in the site in different locations/objects and different ways,
     * depending on authorization 
     * 
     * @return string
     */
    private function factorConfigButton() {
        $config = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_CONFIG_BUTTON)->getVersion($this->getMode(), $this->getContext())->getBody();
        $config = str_replace(Terms::ADMIN_VALUE, Helper::getLang(LSSNames::STRUCTURE_ADMIN_CONFIG_BUTTON), $config);
        // factor the config panel
        $configadminfactory = new ConfigAdminFactory;
        $configadminfactory->setContext($this->getContext());
        $configadminfactory->setMode($this->getMode());
        $configadminfactory->setObject(Objects::getObject(SysCon::SITE_ROOT_OBJECT));
        $configadminfactory->factor();
        $config = str_replace(Terms::OBJECT_ITEM_CONTENT, $configadminfactory->getContent(), $config);
        return $config;
    }

    /**
     * Create the config panel for this object
     * 
     * @return string
     */
    private function factorConfigPanel() {
        // get the panel
        $config = Structures::getStructureByName(LSSNames::STRUCTURE_CONFIG_PANEL)->getVersion($this->getMode(), $this->getContext())->getBody();
        // insert the panel id
        $config = str_replace(Terms::OBJECT_ITEM_ID, 'CP' . Objects::getObject(SysCon::SITE_ROOT_OBJECT)->getId(), $config);
        // empty by default
        $config = str_replace(Terms::ADMIN_CONTENT, '', $config);
        return $config;
    }

}