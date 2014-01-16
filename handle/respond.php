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
 * Functions for responding from a handler
 *
 * @since 0.4.0
 */
abstract class Respond implements Handler{
    private $response; // the response to send
    private $contextgroup; // the context group to use
    private $mode; // the mode to use
    private $deescapecontenthash = true; // whether to deescape the content hash or not, for example don't use for file includes
    
    /**
     * Return the response created by the handler
     * 
     * @return response
     */
    public function respond() {
        if (is_a($this->response, 'Response')) {
            try {
                // set the correct header
                header('Content-type: ' . $this->response->getType());
                if ($this->getDeEscapeContentHash()) {
                    // de-escape the hash sign used in content
                    echo Helper::deEscapeContentHash($this->response->getContent()) . Messages::Show();
                } else {
                    // do not deescape, do not show messages
                    echo $this->response->getContent() . Messages::Show();
                }
                return true;
            } catch (Exception $e) {
                exit (Error::showMessage($e));
            }
        } else {
            // no response defined in this handler
            return true;
        }
    }
    
    /**
     * return the context group to use when formatting content
     * 
     * @return contextgroup
     */
    protected function getContextGroup() {
        return $this->contextgroup;
    }

    /**
     * set the context group to use when formatting content
     * 
     * @param contextgroup the new context group
     * @return boolean true if success
     */
    protected function setContextGroup($newcontextgroup) {
        $this->contextgroup = $newcontextgroup;
        return true;
    }

    /**
     * return the mode to use when formatting content
     * 
     * @return mode
     */
    protected function getMode() {
        return $this->mode;
    }

    /**
     * set the mode to use when formatting content
     * 
     * @param mode the new mode
     * @return boolean true if success
     */
    protected function setMode($newmode) {
        $this->mode = $newmode;
        return true;
    }

    /**
     * return the value for deescapecontenthash
     * 
     * @return boolean
     */
    protected function getDeEscapeContentHash() {
        return $this->deescapecontenthash;
    }

    /**
     * set the deescapecontenthash value
     * 
     * @param boolean the new deescapecontenthash value
     * @return boolean true if success
     */
    protected function setDeEscapeContentHash($newdeescape) {
        $this->deescapecontenthash = $newdeescape;
        return true;
    }

    /**
     * return the response 
     * 
     * @return response
     */
    protected function getResponse() {
        return $this->response;
    }

    /**
     * set the response
     * 
     * @param response the new response
     * @return boolean true if success
     */
    protected function setResponse($newresponse) {
        $this->response = $newresponse;
    }

}

?>
