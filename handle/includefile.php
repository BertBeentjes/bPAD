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
 * Returns a file include, a piece of javascript or css stored in the database
 *
 * @since 0.4.0
 */
class IncludeFile extends Respond {
    
    /**
     * Construct the file include handler, read the requesturl
     * 
     */
    public function __construct() {
        // get the context group to use. A page always uses default context group settings using mobiledetect.
        $this->setContextGroup($this->chooseContextGroup());
        // set the mode, pages always start in view mode
        $this->setMode(Modes::getMode(Mode::VIEWMODE));
        // initialize response
        $fileinclude = $this->getFileIncludeByName(Request::getURL()->getFileName() . '.' . Request::getURL()->getExtension());
        $this->setResponse(new Response());
        $this->getResponse()->setType($fileinclude->getMimeType());
        $this->getResponse()->setContent($fileinclude->getVersion(Modes::getMode(Mode::VIEWMODE))->getBody());
        // resolve the bPAD terms in the snippet (this will fetch content and everything else)
        $this->factorResponse();
    }

    /**
     * Get the requested file include 
     * 

     * @param string $filename
     * @return fileinclude
     */
    private function getFileIncludeByName($filename) {
        return FileIncludes::getFileIncludeByName($filename);
    }

    /**
     * Set the context group to use when formatting the content
     * 

     */
    protected function chooseContextGroup() {
        $mobiledetect = new Mobile_Detect();
        if ($mobiledetect->isMobile()) {
            return ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_MOBILE);
        } else {
            return ContextGroups::getContextGroup(ContextGroup::CONTEXTGROUP_DEFAULT);
        }
    }
    
    /**
     * factor bPAD terms in the file include
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

?>
