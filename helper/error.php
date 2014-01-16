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
 * Creates an error message, depends on debug level
 *
 * @since 0.4.0
 */
class Error {
    /**
     * return the error message
     * 
     * @param Exception $e the exception
     */
    public static function showMessage($e) {
        global $debuglevel;
        $message = Helper::getLang(Errors::ERROR_SOMETHING_WENT_WRONG);
        switch ($debuglevel) {
            case 'trace': $message = $e->getTraceAsString() . PHP_EOL . $e->getMessage(); break;
            case 'errors': $message = $e->getMessage(); break;
        }
        return $message;
    }
}

?>
