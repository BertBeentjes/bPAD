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
 * General todo list:
 * 
 * Fix:
 * 
 * functional:
 * - add object from template in a specific position, create an add panel that contains adds for all positions. And when the position to add is known, open the added item also when it is in a referral, by creating the content.get command with the specified position number for the add, and the object name from the root object of the template (the default name for the new object)
 * - after creating a template based object in a referral, open the new object in edit mode, after cancelling, refresh the referral
 * - after creating a template based object that is searchable, open the addressable parent in edit mode, not the object itself
 * - add option to admin menu to remove searchable sub template objects
 * - move template based objects up/down/to position number/to parent
 * 
 * Prio:
 * 
 * - config styleparameters (if possible)
 * 
 * Later:
 * 
 * - refresh stylesheet after changing styles (use an event for this)
 * 
 * - don't factor/show an empty structure when a position object isn't shown (because it is deleted or because of permissions) -> put the factored position structure in the cache of the child object (and adjust the outdate-function accordingly)
 * 
 * - create full copy/update statements in the store in executeupdatestatements, reducing the number of update queries for speed optimization
 * 
 * - copy the copyright message to all files
 * 
 * - use searchable children indicator to also edit and publish children with their parent. This helps to 
 * create a seamless experience when adding texts, pictures, etc to a content container. (and later on also share/give)
 * 
 * - create multi upload that adds multiple searchable children
 * 
 * - handle errors and messages in the frontend, decide when to gives errors, and when messages
 * 
 * - respond (add a specified template)
 * 
 * - respond for anonymous users (update the object authorization for view/add/edit, as specified in the todo in authorization.php)
 * 
 * - full delete an object when changes are cancelled
 * 
 * - 1. stop users from asking for unlimited resized versions of images, clogging the server and eating away file system space
 * - 2. don't create unlimited copies of files when publishing an item and nothing has been changed
 * 
 * - think of a way to manage div background images
 * 
 * Big things:
 * 
 * - other admin functions: users, user groups, permissions, roles, password, file includes, snippets, settings
 * 
 * - creating masters structure for templates/lss updates
 * 
 * - creating code update structure
 * 
 * - sharing/giving of content
 * 
 * - create connector to mail (and other things)
 * 
 * - create new sites for bpadsite.nl and bpadcms.nl
 * 
 * 
 * Minor issues:
 * 
 * - if the mobile snippet isn't found, revert to the default snippet (every site should have a mobile snippet)
 * - check the existence of templates to add before showing an add button (only happens while configuring, there should be no add buttons where there are no templates)
 * 
 */

$debuglevel = 'trace';

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

?>
