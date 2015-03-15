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

/*
 * Contains all form handlers
 * 
 * @since 0.4.0
 */
class FormHandlers {
    private static $formhandlers = array();
    
    /*
     * get a form handler by id, checks whether the form handler is loaded,
     * loads the form handler if necessary and fills it on demand with
     * further information
     * 
     * @param formhandlerid the id of the form handler to get
     * @return formhandler
     */
    public static function getFormHandler ($formhandlerid) {
        // return an formhandler
        if (isset(self::$formhandlers[$formhandlerid])) {
            return self::$formhandlers[$formhandlerid];
        } else {
            self::$formhandlers[$formhandlerid] = new FormHandler($formhandlerid);
            return self::$formhandlers[$formhandlerid];
        }
    }
    
    /**
     * Get a form handler by name
     * 
     * @param string $name
     * @return formhandler
     */
    public static function getFormHandlerByName($name) {
        if ($result = Store::getFormHandlerIdByName($name)) {
            if ($row = $result->fetchObject()) {
                return self::getFormHandler($row->id);
            }
        }
        throw new Exception (Helper::getLang(Errors::ERROR_FILE_INCLUDE_NOTFOUND) . ' @ ' . __METHOD__);
    }

    /**
     * Get all formhandlers
     * 
     * @return resultset
     */
    public static function getFormHandlers () {
        return Store::getFormHandlers();
    }
    
    /**
     * Create a new form handler
     * 
     * @return type
     */
    public static function newFormHandler() {
        $formhandlerid = Store::insertFormHandler();

        return true;
    }

    /**
     * remove a form handler
     * 
     * @param formhandler $formhandler
     * @return type
     */
    public static function removeFormHandler($formhandler) {
        Store::deleteFormHandler($formhandler->getId());
        unset(self::$formhandlers[$formhandler->getId()]);
        return true;
    }

}