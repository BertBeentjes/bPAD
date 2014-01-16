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
 * miscellaneous helper functions
 * 
 * @since 0.4.0
 */
class Helper {

    /**
     * escape the # symbol in content. Because the # is used as a special 
     * character in bPAD, it must be escaped in content.
     * 
     * used in index.php (and maybe moved to paramfunction.php), where
     * content enters the system
     * 
     * @param content the string containing the content to be escaped
     * @return string the resulting string with the escaped hash
     */
    public static function escapeContentHash($content) {
            return str_replace("#", "/#/", $content);
    }

    /**
     * de-escape the # symbol in content. Because the # is used as a special 
     * character in bPAD, it must be escaped in content.
     * 
     * used only in index.php and snippets.php, just before content leaves the
     * system
     * 
     * @param content the string containing the content to be de-escaped
     * @return string the resulting string with the de-escaped hash
     */
    public static function deEscapeContentHash($content) {
            return str_replace("/#/", "#", $content);
    }

    /**
     * get the currect date / time in a format that the Store can handle
     * 
     * @return string the date and time string
     */
    public static function getDateTime() {
        return date('Y-m-d H:i:s');
    }

    /**
     * format string to format a date time
     * TODO: localize the formatting
     * 
     * @return string the format for the date and time string
     */
    public static function getDateTimeFormat() {
        return 'Y-m-d H:i:s';
    }
    
    /**
     * format string to format a date in the store
     * TODO: localize the formatting
     * 
     * @return string the format for the date string
     */
    public static function getDateFormatStore() {
        return '%e %M %Y';
    }
    
    /**
     * Convert a string to something that is safe to use in a url
     * spaces to dashes
     * all that is no alfanumeric character by nothing
     * double dashes by a single dash
     * 
     * @param string $input
     * @return string
     */
    public static function getURLSafeString ($input) {
        $output = $input;
        $output = preg_replace('/\s/', '-', $output);
        $output = preg_replace('/_/', '-', $output);
        $output = preg_replace('/[^a-zA-Z0-9\-]/', '', $output);
        $output = preg_replace('/\-\-+/', '-', $output);
        return $output;
    }
    
    /**
     * return the localized language string for the given language constant
     * 
     * @global array $lang
     * @param string $stringname
     * @return string
     */
    public static function getLang($stringname) {
        global $lang;
        if (isset($lang[$stringname])) {
            // return the localized string
            return $lang[$stringname];
        }
        // no localization, so return the string back
        return $stringname;
    }    

}
?>
