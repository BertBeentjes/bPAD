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
 * Contains all form storages
 * 
 * @since 0.4.0
 */
class FormStorages {
    private static $formstorages = array();
    
    /*
     * get a form storage by id, checks whether the form storage is loaded,
     * loads the form storage if necessary and fills it on demand with
     * further information
     * 
     * @param formstorageid the id of the form storage to get
     * @return formstorage
     */
    public static function getFormStorage ($formstorageid) {
        // return an formstorage
        if (isset(self::$formstorages[$formstorageid])) {
            return self::$formstorages[$formstorageid];
        } else {
            self::$formstorages[$formstorageid] = new FormStorage($formstorageid);
            return self::$formstorages[$formstorageid];
        }
    }
    
    /**
     * Create a new form storage
     * 
     * @param int $formhandlerid the id of the form handler this form uses
     * @return type
     */
    public static function newFormStorage($formhandlerid) {
        $formstorageid = Store::insertFormStorage($formhandlerid);

        return self::getFormStorage($formstorageid);
    }

    /**
     * remove a form storage
     * 
     * @param formstorage $formstorage
     * @return type
     */
    public static function removeFormStorage($formstorage) {
        Store::deleteFormStorage($formstorage->getId());
        unset(self::$formstorages[$formstorage->getId()]);
        return true;
    }

}