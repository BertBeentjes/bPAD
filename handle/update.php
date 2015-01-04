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
 * Create or process an update
 *
 * @since 0.4.0
 */
class Update extends Respond {

    /**
     * construct an change handler, read the command
     * 
     */
    public function __construct() {
        // do something
        $update = new UpdateFactory();
        $update->factor();
        
        // set the header to attachment
        header("Content-disposition: attachment; filename=update.json");        
        
        // send an empty response, or in some cases an error message, to the front end
        $this->setResponse(new Response());
        $this->getResponse()->setType('application/json');
        $this->getResponse()->setContent($update->getContent());
    }

}