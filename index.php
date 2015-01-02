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

$debuglevel = 'production';

// load the required class that auto loads all classes
require 'helper/required.php';

// register autoloader
spl_autoload_register('Required::autoLoad');

// load global variable stuff
require 'helper/globals.php';

// connect to the Store
Initializer::openStore();

// initialize the time zone
Initializer::setTimeZone();

// get the language strings
$lang = Initializer::getLanguageStrings();

// log in the user by cookie, if there is one, otherwise it will return the 'public' user
try {
    Authentication::cookieLogin();
} catch (Exception $e) {
    exit (Error::showMessage($e));
}

// now read the request and find the appropriate handler
try {
    // read the request
    Request::init();
    // create a handler for the request
    switch (Request::getType()) {
        CASE Request::HOME: // requests a complete page, call the home handler (same as page, but without a requesturl)
            $handler = new Home();
            $handler->getPage();
            break;
        CASE Request::PAGE: // requests a complete page, call the page handler for standard pages
            $handler = new Page();
            $handler->getPage();
            break;
        CASE Request::UPLOAD: // requests an upload page, loaded in an iframe on the frontend
            // TODO: (later) create an option to load multiple files into new template based objects, select the template from a set, based upon the file extension
            $handler = new Upload(); 
            // if a file is posted, store the file. The upload is the only request
            // not using ajax, so here post and response are combined
            if (isset($_FILES[Helper::getURLSafeString(Helper::getLang(AdminLabels::ADMIN_POSITION_CONTENT_ITEM_UPLOAD))])) {
                $handler->executeUpload();
            }
            // get the return page
            $handler->getPage();
            break;
        CASE Request::FILE: // requests a file stored on the server (uploaded by a user), call the file handler
            $handler = new File(); 
            break;
        CASE Request::INCLUDEFILE: // requests a file include to load in a page, call the file include handler
            $handler = new IncludeFile(); 
            break;
        CASE Request::LOGIN: // requests a login
            $handler = new Login(); 
            break;
        CASE Request::CONTENT: // requests a piece of content in an AJAX call, call the content handler
            $handler = new Content(); 
            break;
        CASE Request::ADMIN: // requests a bit of admin interface in an AJAX call, call the admin handler
            $handler = new Admin(); 
            break;
        CASE Request::CHANGE: // requests the execution of a change in an AJAX call, call the change handler
            $handler = new Change(); 
            break;
        DEFAULT: 
            throw new Exception(Helper::getLang(Errors::ERROR_UNKNOWN_REQUEST)); 
            break;
    }
    // send the response
    $handler->respond();
} catch (Exception $e) {
    exit (Error::showMessage($e));
}