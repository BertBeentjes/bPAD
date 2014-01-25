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
 * contains the parts of a request url
 *
 * @since 0.4.0
 */
class RequestURL {
    private $urlparts = array();
    private $filename;
    private $extension;
    
    /**
     * Constructor for the requesturl
     * 
     */
    public function __construct($urlparts, $filename, $extension) {
        $this->urlparts = $urlparts;
        $this->filename = $filename;
        $this->extension = $extension;
    }
    
    /**
     * Get the parts of the requested url
     * 
     * @return string[]
     */
    public function getURLParts() {
        return $this->urlparts;
    }
    
    /**
     * Get the first part of the requested url and shift it out of the url
     * 
     * @return string
     */
    public function getURLPartAndShift () {
        $returnvalue = $this->getFirstURLPart();
        $this->shiftURLParts();
        return $returnvalue;
    }
    
    /**
     * Remove further url parts, this url is no longer valid
     * 
     */
    public function removeURLParts () {
        unset($this->urlparts);
        $this->urlparts = array();
    }
    
    /**
     * Get the first part of the requested url
     * 
     * @return string
     */
    public function getFirstURLPart() {
        if (isset($this->urlparts[0])) {
            return $this->urlparts[0];
        } else {
            return '';
        }
    }
    
    /**
     * remove the first url part, done when this part of the url
     * has been resolved and content found
     */
    public function shiftURLParts() {
        array_shift($this->urlparts);
    }
    
    /**
     * Get the file name of the requested url
     * 
     * @return string
     */
    public function getFileName() {
        return $this->filename;
    }
    
    /**
     * Get the extension of the requested url
     * 
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }
    
    /**
     * Get the full url to the file
     * 
     * @return string
     */
    public function getFullURL() {
        return $this->getFolder() . $this->getFullName();
    }
    
    /**
     * Get the folder location of the file
     * 
     * @return string
     */
    public function getFolder() {
        $location = '';
        foreach ($this->getURLParts() as $urlpart) {
            $location .= $urlpart . '/';
        }
        return $location;
    }
    
    /**
     * Get the file name and extension
     * 
     * @return string
     */
    public function getFullName() {
        $fullname = $this->getFileName();
        if ($this->getExtension() > '') {
            $fullname .= '.' . $this->getExtension();
        }
        return $fullname;
    }
}

?>