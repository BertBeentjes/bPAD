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
 * small helper class that wraps mysqli specific functions
 * and returns a result object from the Store 
 * 
 * @since 0.4.0
 */
class ResultSet {

    private $result;

    /*
     * Constructor
     * 
     * @param mysqli_result $result the result set to be wrapped
     */
    public function __construct($result) {
        $this->result = $result;
    }

    /*
     * fetches the next object from this result set
     * 
     * @returns object containing the values from the mysqli row
     */

    public function fetchObject() {
        if (is_object($this->result)) {
            return $this->result->fetch_object();
        }
    }

}

?>
