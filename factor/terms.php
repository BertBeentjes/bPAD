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
 * The terms used by bPAD in content, objects or positions. 
 *
 * @since 0.4.0
 */
class Terms {
    const CONTENT_BPAD_VERSION = '#bpadversion#'; // insert the bPAD version number here
    const CONTENT_BPAD_LANGUAGE = '#bpadlanguage#'; // insert the bPAD language here
    const CONTENT_ROOT = '#root#'; // insert content starting with the site root here in a snippet 
    const CONTENT_STYLES = '#styles#'; // insert styles 
    const CONTENT_SITE_ROOT = '#siteroot#'; // insert site root 
    const CONTENT_SITE_ROOT_FOLDER = '#siterootfolder#'; // insert site root folder (sub folder)
    const CONTENT_SETTINGS = '"#settings#"'; // ==> EXTRA "" to prevent javascript error in dev environment!! Insert settings relevant for the front end
    const CONTENT_SESSION_ID = '#sessionid#'; // the session id for this client session, used to process commands in the right order
    const CONTENT_COMMAND_ID = '#commandid#'; // the last command processed before returning this content, used for optimistic locking
    const OBJECT_PN = '#pn#'; // insert all positions here
    const OBJECT_UID = '#uid#'; // insert a unique id here, can be used multiple times in one object for multiple unique id's
    const OBJECT_BUTTON_TOGGLE = '#buttontoggle#'; // toggle edit/add/config buttons on or off
    const OBJECT_MENU = '#menu#'; // a full menu, starting with an item with the object name, and all admin options in the dropdown
    const OBJECT_EDIT_BUTTON = '#edit#'; // edit button(s)
    const OBJECT_EDIT_PANEL = '#editpanel#'; // edit panel
    const OBJECT_ADD_BUTTON = '#add#'; // add button(s)
    const OBJECT_ADD_PANEL = '#addpanel#'; // add panel
    const OBJECT_CONFIG_BUTTON = '#config#'; // config button(s)
    const OBJECT_CONFIG_PANEL = '#configpanel#'; // config panel
    const OBJECT_BREADCRUMBS = '#breadcrumbs#'; // insert bread crumbs, using the predefined structures bread crumb and bread crumb separator
    const OBJECT_ID = '#objectid#'; // the id of the object
    const OBJECT_ITEM_ID = '#id#'; // the id of an item connected to the object, e.g. the edit panel, the add panel or the config panel
    const OBJECT_ITEM_CONTENT = '#co#'; // the content of an item connected to the object, e.g. the edit button name or the edit panel content
    const OBJECT_ITEM_COMMAND = '#command#'; // a command used in this object
    const OBJECT_NAME = '#objectname#'; // the name of the object
    const OBJECT_URL_NAME = '#objecturlname#'; // the name of the object as can be used in the url (addressable objects only)
    const OBJECT_ROOT_NAME = '#rootobjectname#'; // the name of the root object of this template based object
    const OBJECT_CREATE_DATE = '#createdate#'; // the create date of this object
    const OBJECT_ROOT_CREATE_DATE = '#rootcreatedate#'; // the create date of the root object
    const OBJECT_CHANGE_DATE = '#changedate#'; // the change date of this object
    const OBJECT_ROOT_CHANGE_DATE = '#rootchangedate#'; // the change date of the root object
    const OBJECT_ARGUMENT_NAME = '#argumentname#'; // the name of the argument used in this template
    const CLASS_SUFFIX = '#c#'; // the suffix that defines the css class to use for the current context
    const POSITION_UID = '#uid#'; // insert a unique id here, can be used multiple times in one position, for multiple unique id's
    const POSITION_MODE_ID = '#modeid#'; // the id of the current mode
    const POSITION_SEO_URL = '#seourl#'; // the seo friendly url of this position
    const POSITION_PARENT_SEO_URL = '#parentseourl#'; // the seo friendly url of the direct parent of this object, used by addressable objects to create a url one level up
    const POSITION_ROOT_CREATOR = '#creator#'; // the user that created this position (and the root object for this position)
    const POSITION_ROOT_EDITOR = '#editor#'; // the user that last changed the root object for this position
    const POSITION_ROOT_CREATE_DATE = '#createdate#'; // the create date for the root object for this position
    const POSITION_ROOT_CHANGE_DATE = '#changedate#'; // the change date for the root object for this position
    const POSITION_ID = '#id#'; // the id of the position
    const POSITION_OBJECT_ID = '#objectid#'; // the id of the object that contains this position
    const POSITION_OBJECT_NAME = '#objectname#'; // the name of the object that contains this position
    const POSITION_ROOT_OBJECT_NAME = '#rootobjectname#'; // the name of the root object for this template based structure
    const POSITION_FILE_DIR_NAME = '#dirname#'; // the directory name of the file in this position
    const POSITION_FILE_NAME = '#filename#'; // the name of the file in this position
    const POSITION_FILE_EXTENSION = '#extension#'; // the extension of the file in this position
    const POSITION_CONTENT = '#co#'; // the content for this position
    const POSITION_CONTENT_SHORT = '#coshort#'; // the content for this position, maximized to 200 chars, ONLY for contentitems
    const POSITION_CONTENT_PLAIN = '#coplain#'; // the content for this position, with markup, ONLY for contentitems
    const POSITION_REFERRAL_URL = '#hr#'; // the url to the item for a referral
    const POSITION_REFERRAL = '#re#'; // the code to open an item
    const POSITION_REFERRAL_OBJECT_ID = '#refid#'; // the id of the item a referral is pointing to
    const POSITION_SEARCH_BOX = '#searchbox#'; // insert a search box, used to search in an instance
    const POSITION_SEARCH_COMMAND = '#searchcommand#'; // insert a search command, used to search in an instance
    const ADMIN_ID = '#id#'; // the id in a structure used for admin functions
    const ADMIN_OBJECT_ID = '#objectid#'; // the id of the object
    const ADMIN_POSITION_NUMBER = '#positionnumber#'; // the number of the position
    const ADMIN_SITE_ROOT_FOLDER = '#siterootfolder#'; // insert site root folder (sub folder)
    const ADMIN_COMMAND = '#command#'; // the command in a structure used for admin functions
    const ADMIN_CONTENT = '#co#'; // content for admin items like an edit panel
    const ADMIN_VALUE = '#value#'; // the value in a structure used for admin functions
    const ADMIN_OPTIONS = '#options#'; // the options for a list or combo box
    const ADMIN_LABEL = '#label#'; // the label for an admin input
    const ADMIN_DISABLED = '#disabled#'; // disabled or not
    const ADMIN_SELECTED = '#selected#'; // selected or not
    const ADMIN_CURRENT_VALUE = '#currentvalue#'; // the current value for a content item, used in the upload function
    const ADMIN_CURRENT_LABEL = '#currentlabel#'; // the label for the current value, used in the upload function
    const ADMIN_PROCESSING = '#processing#'; // insert processing message (language string), used in the frontend
    
    /**
     * A numbered position
     * 
     * @param int $number
     * @return string
     */
    static public function object_p($number) {
        return '#p' . $number . '#';
    }
    
    /**
     * An object placeholder, used in a position, to be filled from the cache
     * 
     * @param object $object
     * @param context $context
     * @return string
     */
    static public function object_placeholder($object, $context) {
        return '#' . $object->getId() . '|' . $context->getId() . '#';
    }

    /**
     * A style param placeholder
     * 
     * @param styleparam $styleparam
     * @return string
     */
    static public function styleparam_placeholder($styleparam) {
        return '#' . $styleparam->getName() . '#';
    }
    
}

?>
