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
 * Contains all file includes, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class FileIncludes {
    private static $fileincludes = array();
    
    /*
     * get a file include by id, checks whether the file include is loaded,
     * loads the file include if necessary and fills it on demand with
     * further information
     * 
     * @param fileincludeid the id of the file include to get
     * @return fileinclude
     */
    public static function getFileInclude ($fileincludeid) {
        // return an fileinclude
        if (isset(self::$fileincludes[$fileincludeid])) {
            return self::$fileincludes[$fileincludeid];
        } else {
            self::$fileincludes[$fileincludeid] = new FileInclude($fileincludeid);
            return self::$fileincludes[$fileincludeid];
        }
    }
    
    /**
     * Get a file include by name
     * 
     * @param string $name
     * @return fileinclude
     */
    public static function getFileIncludeByName($name) {
        if ($result = Store::getFileIncludeIdByName($name)) {
            if ($row = $result->fetchObject()) {
                return self::getFileInclude($row->id);
            }
        }
        throw new Exception (Helper::getLang(Errors::ERROR_FILE_INCLUDE_NOTFOUND) . ' @ ' . __METHOD__);
    }

    /**
     * Get all fileincludes
     * 
     * @return resultset
     */
    public static function getFileIncludes () {
        return Store::getFileIncludes();
    }
    
    /**
     * Create a new fileinclude
     * 
     * @return type
     */
    public static function newFileInclude() {
        $fileincludeid = Store::insertFileInclude();
        // a fileinclude must always have an edit and view version
        Store::insertFileIncludeVersion($fileincludeid, Mode::VIEWMODE);
        Store::insertFileIncludeVersion($fileincludeid, Mode::EDITMODE);

        return true;
    }

    /**
     * remove a fileinclude
     * 
     * @param fileinclude $fileinclude
     * @return type
     */
    public static function removeFileInclude($fileinclude) {
        Store::deleteFileIncludeVersions($fileinclude->getId());
        Store::deleteFileInclude($fileinclude->getId());
        unset(self::$fileincludes[$fileinclude->getId()]);
        return true;
    }

}