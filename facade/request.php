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
 * The request class reads the request, validates it, defines the request type.
 * This class is static, there is only one request and it's globally available.
 * 
 * @since 0.4.0
 */
class Request {

    private static $type; // the type of request, see the constants below
    private static $url; // what the request is all about
    private static $command; // what the request is all about

    const HOME = 'home'; // the home page
    const PAGE = 'page'; // basic page, passed as url
    const FILE = 'file'; // file, passed as url starting with _file and after that the object number
    const INCLUDEFILE = 'includefile'; // include a file, passed as url starting with _includefile
    const CONTENT = 'content'; // part of the page content, passed as command in AJAX post request
    const ADMIN = 'admin'; // admin functionality, passed as command in AJAX post request
    const LOGIN = 'login'; // login request, passed as a command in an AJAX post request or as a url (e.g. from a form post)
    const UPLOAD = 'upload'; // upload request
    const CHANGE = 'change'; // change, passed as command in AJAX post request
    const UNKNOWN = 'unknown';

    /**
     * Constructor, read the request, validate it and store it as request parameters
     * 
     * @since 0.4.0
     */
    public static function init() {
        self::$type = self::HOME;
        if (isset($_GET['url'])) {
            // a url has been passed
            self::$url = self::parseURL($_GET['url']);
        } else {
            // set the empty url
            self::$url = new RequestURL(array(), '', '');
        }
        // a command has two attributes: the command and the value.
        if (isset($_POST['command'])) {
            // if there is no value, create an empty value
            if (!isset($_POST['value'])) {
                $_POST['value'] = '';
            }
            // a command has been passed
            self::parseCommand($_POST['command'], $_POST['value']);
        }
    }

    /**
     * parse the request url and return an object with the parts
     * 
     * @param string $url
     * @return requesturl the bits of the request url
     */
    private static function parseURL($url) {
        if (Validator::isURL($url)) {
            $urlparts = explode('/', $url);
            // if the url started with a /, remove the first element of the parts array
            if ($urlparts[0] == '') {
                array_shift($urlparts);
            }
            // dissect the url
            // first get the filename and extension
            $lastpart = count($urlparts) - 1;
            if (strrpos($urlparts[$lastpart], '.') === false) {
                $extension = '';
                $filename = $urlparts[$lastpart];
            } else {
                $extension = substr(strrchr($urlparts[$lastpart], '.'), 1);
                $filename = substr($urlparts[$lastpart], 0, strrpos($urlparts[$lastpart], '.'));
            }
            switch ($urlparts[0]) {
                case '_' . self::FILE:
                    if (Validator::isFileURL($urlparts)) {
                        self::$type = self::FILE;
                        // ok, now drop the first part of the url, it's no longer necessary
                        array_shift($urlparts);
                        // the last part is stored in filename/extension
                        array_pop($urlparts);
                    } else {
                        throw new Exception(Helper::getLang(Errors::ERROR_URL_SYNTAX));
                    }
                    break;
                case '_' . self::INCLUDEFILE:
                    self::$type = self::INCLUDEFILE;
                    // ok, now drop the first and last part of the url, it's no longer necessary
                    array_shift($urlparts);
                    array_pop($urlparts);
                    break;
                case '_' . self::UPLOAD:
                    if (Validator::isUploadURL($urlparts)) {
                        self::$type = self::UPLOAD;
                        // ok, now drop the first and last part of the url, it's no longer necessary
                        array_shift($urlparts);
                        array_pop($urlparts);
                    } else {
                        throw new Exception(Helper::getLang(Errors::ERROR_URL_SYNTAX));
                    }
                    break;
                default:
                    self::$type = self::PAGE;
                    // no file to get, just a path:
                    $urlparts[$lastpart] = $filename;
                    $filename = '';
                    $extension = '';
            }
            return new RequestURL($urlparts, $filename, $extension);
        } else {
            // incorrect characters in url
            throw new Exception(Helper::getLang(Errors::ERROR_URL_SYNTAX));
        }
    }

    /**
     * parse the parts of the command and validate the parameters
     * 
     * @param string $command the command
     * @param string $value the new value
     * @return command the parts of the request command
     */
    public static function parseCommand($command, $value) {
        /**
         * command syntax content.get (used by menus and internal links to load content):
         *   item,parentpositionid.objectid.positionid.name/parentpositionid.objectid.positionid.name/...,commandgroup.command.mode.context,sessionid.lastcommandid.commandnumber
         * 
         * command syntax content.load (used by instances to lazy load content):
         *   item,targetcontainerid.objectid.name,commandgroup.command.mode.context,sessionid.lastcommandid.commandnumber
         * 
         * command syntax content.instance (used by search boxes to reload the instance after user input):
         *   item,positionid.objectid.positionnumber,commandgroup.command.mode.context,sessionid.lastcommandid.commandnumber
         * 
         * command syntax admin.edit (used by the edit button to load the content for the edit panel):
         *   item,targetcontainerid.objectid.name,commandgroup.command.mode.context,sessionid.lastcommandid.commandnumber
         * 
         * command syntax change.@@@ (used for editing attributes, the value is in value):
         *   item,itemid,commandgroup.command,sessionid.lastcommandid.commandnumber
         */
        $commandparts = explode(',', $command);

        if (count($commandparts) == 4 && Validator::isLCaseChars($commandparts[0]) && Validator::isAddress($commandparts[1]) && Validator::isCommand($commandparts[2]) && Validator::isCommandNumber($commandparts[3])) {
            $commandnumberparts = explode('.', $commandparts[3]);
            if (count($commandnumberparts) == 3) {
                self::$command = Command::newCommand();
                self::$command->setItem($commandparts[0]);
                self::$command->setItemAddress($commandparts[1]);
                self::$command->setCommand($commandparts[2]);
                self::$command->setCommandNumber($commandnumberparts[2]);
                self::$command->setLastCommandId($commandnumberparts[1]);
                self::$command->setSessionIdentifier($commandnumberparts[0]);
                self::$command->setUser(Authentication::getUser());
                self::$command->setDate(Helper::getDateTime());
                self::$command->setValue($value);
                switch (self::$command->getCommandGroup()) {
                    case 'content' :
                        self::$type = self::CONTENT;
                        $itemaddressparts = explode('/', $commandparts[1]);
                        $count = count($itemaddressparts);
                        for ($i = 0; $i < $count; $i++) {
                            $part = explode('.', $itemaddressparts[$i]);
                            if (Validator::isNumeric($part[2]) && Validator::isURLSafeName($part[3])) {
                                // store the position number and object name in the url,
                                // this is done for the event that two objects have the same 
                                // name. The url will not work correctly in that event, but 
                                // the ajax calls will. Normally two objects in the same
                                // part of the tree shouldn't have the same name, but this may
                                // happen when building the site and is annoying when it doesn't
                                // work
                                $itemaddressparts[$i] = $part[2] . '_' . $part[3];
                            } else {
                                throw new Exception(Helper::getLang(Errors::ERROR_COMMAND_SYNTAX) . ' @ ' . __METHOD__ . '_1');
                            }
                        }
                        // store the item address in the request url, for use in the factory
                        self::$url = new RequestURL($itemaddressparts, '', '');
                        break;
                    case 'admin' :
                        self::$type = self::ADMIN;
                        break;
                    case 'change' :
                        self::$type = self::CHANGE;
                        break;
                    case 'login' :
                        self::$type = self::LOGIN;
                        // encrypt the password before store
                        $value = Authentication::middleSalt($value);
                        self::$command->setValue($value);
                        if (isset($_POST['itemaddress']) && Validator::isAddress($_POST['itemaddress'])) {
                            // in a simple login form, the item address (or user name) is stored outside the command
                            self::$command->setItemAddress($_POST['itemaddress']);
                        }
                        break;
                    default:
                        // incorrect command
                        throw new Exception(Helper::getLang(Errors::ERROR_COMMAND_SYNTAX) . ' @ ' . __METHOD__ . '_1');
                }
            } else {
                // incorrect number of command number parts
                throw new Exception(Helper::getLang(Errors::ERROR_COMMAND_SYNTAX) . ' @ ' . __METHOD__ . '_3');
            }
        } else {
            // incorrect number of command parts or invalid characters in command
            throw new Exception(Helper::getLang(Errors::ERROR_COMMAND_SYNTAX) . ' @ ' . __METHOD__ . '_2');
        }
    }

    /**
     * Get the parts, filename, extension of the request url
     * 
     * @return requesturl the info on the requested url
     */
    public static function getURL() {
        return self::$url;
    }

    /**
     * Get the parts request command
     * 
     * @return command the info on the requested command
     */
    public static function getCommand() {
        return self::$command;
    }

    /**
     * Get the type of request done
     * 
     * @return string the type of request
     */
    public static function getType() {
        return self::$type;
    }

}

?>
