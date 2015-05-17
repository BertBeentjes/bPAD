<?php
/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2015
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
 * Functions to provide the user interface with language strings
 *
 * @since 0.4.0
 */
class Errors {
    const ERROR_ATTRIBUTES_NOT_LOADING = 0; // failed to load attributes from the store
    const ERROR_ATTRIBUTE_UPDATE_FAILED = 1; // failed to update an attribute in the store
    const ERROR_CONTEXT_NO_BACKUP_CONTEXT_AVAILABLE = 2; // there is no version of something for this context and the context has no backup context
    const ERROR_ATTRIBUTE_IS_DEFINED_BY_BPAD = 3; // tried to change an attribute that is defined by bPAD and can only be changed with an update script
    const ERROR_SETCHANGED_FAILED = 4; // setting the change user/date failed
    const ERROR_VALIDATION_FAILED = 5; // validating a request parameter failed
    const ERROR_STORE_NOT_AVAILABLE = 6; // failure to connect to the store
    const ERROR_SOMETHING_WENT_WRONG = 7; // single error at default debug level, 'sorry, something went wrong'
    const ERROR_UNKNOWN_REQUEST = 8; // single error at default debug level, 'sorry, something went wrong'
    const ERROR_COMMAND_SYNTAX = 9; // the syntax of the command passed to the server is incorrect, the command can't be executed
    const ERROR_URL_SYNTAX = 10; // the syntax of the url passed to the server is incorrect, the command can't be executed
    const ERROR_UNAUTHORIZED_PAGE_REQUEST = 11; // user isn't authorized to request a page
    const ERROR_SNIPPET_NOTFOUND = 12; // the snippet to build a page or the home can't be found
    const ERROR_FACTORY_NOT_INITIALIZED_CORRECTLY = 13; // a factory isn't initialized correctly, so it can't factor
    const MESSAGE_MAX_LOGIN_ATTEMPTS_REACHED = 14; // the maximum number of login attempts for the user has been reached, contact admin
    const MESSAGE_LOGIN_FAILED_FOR_USER = 15; // incorrect password
    const MESSAGE_LOGIN_FAILED = 16; // incorrect user
    const ERROR_STYLESHEET_CACHE_CORRUPT = 17; // an error retrieving the stylesheet cache
    const ERROR_FILE_INCLUDE_NOTFOUND = 18; // the file include isn't in the database
    const MESSAGE_FILE_NOT_FOUND = 19; // a requested uploaded file hasn't been found
    const MESSAGE_NOT_AUTHORIZED = 20; // the user isn't authorized for this 
    const ERROR_POSITION_NUMBER_NOT_FOUND = 21; // the requested position number isn't found
    const ERROR_COMMAND_CONTENT = 22; // the content of the command passed to the server is incorrect, the command can't be executed
    const MESSAGE_INVALID_COMMAND = 23; // the command is invalid 
    const MESSAGE_VALUE_NOT_ALLOWED = 24; // the value of the command isn't allowed
    const MESSAGE_ITEM_LOCKED = 25; // the item the users tries to edit is locked by himself or another user
    const MESSAGE_FILE_TO_BIG = 26; // the item the users tries to edit is locked by himself or another user
    const ERROR_ALREADY_EXISTS = 27; // trying to create something that is already there
    
}