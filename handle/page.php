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
 * A page gives back the complete page for a url
 *
 * @since 0.4.0
 */
class Page extends Respond {
    private $factory; // the page factory

    /**
     * Construct the page handler, read the request
     * 
     */
    public function __construct() {
        // create an empty response
        $response = new Response();
        $this->setResponse($response);
    }

    /**
     * Get the page, store it in the response
     * 
     */
    public function getPage() {
        // check authorization -> if public user, only fetch a page when there is
        // anonymous access to the site or the user is authenticated
        if (Authorization::getPagePermission(Authorization::PAGE_VIEW)) {
            // get the context group to use. A page always uses default context group settings using mobiledetect.
            $this->setContextGroup(self::chooseContextGroup());
            // set the mode, pages always start in view mode
            $this->setMode(Modes::getMode(Mode::VIEWMODE));
            // start the response with the snippet for this context group
            // a page is always loaded in viewmode, editing takes places with commands
            if ($snippet = Snippets::getSnippetByContextGroup($this->getContextGroup())) {
                $this->getResponse()->setType($snippet->getMimeType());
                $this->getResponse()->setContent($snippet->getVersion($this->getMode())->getBody());
            } else {
                throw new Exception(Helper::getLang(Errors::ERROR_SNIPPET_NOTFOUND) . ' @ ' . __METHOD__);
            }
            // resolve the bPAD terms in the snippet (this will fetch content and everything else)
            $this->factorResponse();
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_UNAUTHORIZED_PAGE_REQUEST) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Set the context group to use when formatting the content
     * 
     */
    public static function chooseContextGroup() {
        // check the kind of request
        if (Request::getURL()->getFullURL() == Request::SITEMAP) {
            // this is a sitemap
            return ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_SITEMAP);
        } else {
            // this is a normal page, now select mobile or not
            $mobiledetect = new Mobile_Detect();
            if ($mobiledetect->isMobile()) {
                return ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_MOBILE);
            } else {
                return ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT);
            }
        }
    }
    
    /**
     * factor bPAD terms in the snippet
     * 
     */
    protected function factorResponse() {
        // initialize the factory
        $this->factory = new ContentFactory();
        $this->factory->setContent($this->getResponse()->getContent());
        $this->factory->setContextForContextGroup($this->getContextGroup());
        $this->factory->setMode($this->getMode());
        // factor
        $this->factory->factor();
        // store the result in the response
        $this->getResponse()->setContent($this->factory->getContent());
    }

}