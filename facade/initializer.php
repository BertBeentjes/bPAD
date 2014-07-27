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
 * Initialize things before handling the request
 *
 * @since 0.4.0
 */
class Initializer {

    /**
     * Connect to the Store
     */
    public static function openStore() {
        try {
            global $database;
            // connect the store using the new store object
            Store::Connect($database['host'], $database['user'], $database['password'], $database['name']);
            // use the admin user while checking and possibly updating the bPAD version
            Authentication::adminUser();
            // check the bPAD version
            self::checkVersion(Versions::getLatestVersion());
        } catch (Exception $e) {
            exit(Error::showMessage($e));
        }
    }

    /**
     * Read the language setting and open the file with the language strings
     * 
     * @return string[] $lang language strings
     */
    public static function getLanguageStrings() {
        try {
            // now load the language file required by the settings
            require ('language/language_' . Settings::getSetting(Setting::SITE_LANGUAGE)->getValue() . '.php');
            return $lang;
        } catch (Exception $e) {
            exit(Error::showMessage($e));
        }
    }

    /**
     * Set the timezone
     */
    public static function setTimeZone() {
        date_default_timezone_set('UTC');
    }

    /**
     * Check the version of bPAD and apply updates if necessary
     * 
     * @param Version $version
     * @return boolean true if success
     */
    private static function checkVersion($version) {
        $success = true;
        if ($version->getVersion() == '0.3.7') {
            // 0.3.7 is the last version before the OO rebuild
            $success = self::getUpdateFile($_SERVER['DOCUMENT_ROOT'] . Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue() . 'update_0_4_0/updatescript_0_4_0');
            $version = Versions::getLatestVersion();
        }
        if ($version->getVersion() == '0.4.0') {
            // 0.4.1 is coupled to 0.4.0 and converts data in the database, the script only contains a version update
            // in this case: update searchable parents and root objects, create parent cache for use in instances
            self::updateToVersion_0_4_1();
            // TODO: fix file update for dev setup
            $success = self::getUpdateFile($_SERVER['DOCUMENT_ROOT'] . Settings::getSetting(Setting::SITE_ROOTFOLDER)->getValue() . 'update_0_4_0/updatescript_0_4_1');
            $version = Versions::getLatestVersion();
        }
        // after 0.4.1, updates are done with file downloads instead of files in the file system
        return $success;
    }

    /**
     * Run a mysql batch file
     * 
     * @param string $batch
     * @param string $delimiter
     * @return boolean true if success
     */
    private static function runMySQLBatch($batch, $delimiter) {
        $sqlArray = explode($delimiter, $batch);
        foreach ($sqlArray as $stmt) {
            if (strlen($stmt) > 3 && substr(ltrim($stmt), 0, 2) != '/*') {
                $stmt->execute();
                if ($stmt->error) {
                    throw new Exception($stmt->error . ' @ ' . __METHOD__);
                }
                $stmt->close();
            }
        }
        return true;
    }

    /**
     * Get and run the update file 
     * 
     * @param string $sqlFileToExecute
     * @return boolean true if success
     */
    private static function getUpdateFile($sqlFileToExecute) {
        // read the sql file
        $f = fopen($sqlFileToExecute, "r");
        $sqlFile = fread($f, filesize($sqlFileToExecute));
        return self::runMySQLBatch($sqlFile, ';;;');
    }

    /**
     * Special update function to create some caching info in the database
     */
    private static function updateToVersion_0_4_1() {
        // Update searchparent and rootobject for contentitems using searchable/instanceallowed templates for use in instances
        // first: get all objects
        if ($result = Store::getObjects()) {
            while ($row = $result->fetchObject()) {
                // then touch the object, to update all caches
                $object = Objects::getObject($row->id);
                $object->getVersion(Modes::getMode(Mode::VIEWMODE))->setChanged();
                $object->getVersion(Modes::getMode(Mode::EDITMODE))->setChanged();
            }
        }        
    }
}