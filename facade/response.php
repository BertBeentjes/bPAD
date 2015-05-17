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
 * The response to a request
 *
 * @since 0.4.0
 */
class Response {
    private $content; // the response content to echo
    private $type; // the type of content, for the response header
    
    const TEXTPLAIN = 'text/plain';
    const TEXTHTML = 'text/html';
    const TEXTJAVASCRIPT = 'text/javascript';
    const TEXTCSS= 'text/css';

    /**
     * construct a response, a response consists of content and has a content
     * type for use in the response header
     * 
     */
    public function __construct() {
    }

    /**
     * set the content for the response
     * 
     */
    public function setContent($content) {
        $this->content = $content;
    }
    
    /**
     * add content to the response
     * 
     */
    public function addContent($content) {
        $this->content .= $content;
    }
    
    /**
     * return the response content
     * 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * return the response type
     * 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * set the response type
     * 
     * @param $newtype
     */
    public function setType($newtype) {
        $this->type = $newtype;
    }

}