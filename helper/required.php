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
 * Require loads all required classes and plugins
 *
 * @since 0.4.0
 */
class Required {

    /**
     * Auto load the classfiles. Because classes are distributed over multiple 
     * folders, the autoloader is rather large
     * 
     * @param string $class
     */
    
    // NOTE: the require is not really necessary, just to prevent a php-bug from
    // happening with the upload function...
    public static function autoLoad($class) {
        switch ($class) {
            // helper classes, general helper methods and classes used throughout the code
            case 'Helper': require ('helper/helper.php'); break;
            case 'Errors': require ('helper/errors.php'); break;
            case 'Error': require ('helper/error.php'); break;
            case 'SysCon': require ('helper/syscon.php'); break;// constants defined by the system that refer to specific items in the Store
            // storage classes: store and retrieve data from the Store, clean abstract interface to the storage
            case 'ResultSet': require ('store/resultset.php'); break;
            case 'Store': require ('store/store.php'); break;

            // data classes: create the logical data model, contain only the structured data, no logic
            // data interfaces
            case 'PositionContent': require ('data/positioncontent.php'); break;
            // abstract data classes
            case 'StoredEntity': require ('data/storedentity.php'); break;
            case 'NamedEntity': require ('data/namedentity.php'); break;
            case 'SettedEntity': require ('data/settedentity.php'); break;
            case 'ValueListEntity': require ('data/valuelistentity.php'); break;
            case 'Log': require ('data/log.php');
            // data loaders
            case 'Arguments': require_once ('data/arguments.php'); break; // loads argument
            case 'Commands': require ('data/commands.php'); break; // loads command
            case 'Contexts': require ('data/contexts.php'); break; // loads context
            case 'ContextGroups': require ('data/contextgroups.php'); break; // loads context group
            case 'FileIncludes': require ('data/fileincludes.php'); break; // loads file include
            case 'Layouts': require ('data/layouts.php'); break; // loads layout -> getVersion($mode, $context) -> ContextedVersion
            case 'Modes': require ('data/modes.php'); break; // loads mode
            case 'Objects': require ('data/objects.php'); break; // loads object -> getVersion($mode) -> getPositions()/getPositionNumber($number) -> Position -> PositionObject/PositionInstance/PositionContentItem/PositionReferral
            case 'Permissions': require ('data/permissions.php'); break; // loads permissions
            case 'Roles': require ('data/roles.php'); break; // loads role
            case 'Sessions': require ('data/sessions.php'); break; // loads session
            case 'Sets': require ('data/sets.php'); break; // loads set
            case 'Settings': require ('data/settings.php'); break; // loads setting
            case 'Snippets': require ('data/snippets.php'); break; // loads snippet
            case 'Structures': require ('data/structures.php'); break; // loads structure -> getVersion($mode, $context) -> ContextedVersion
            case 'StyleParams': require ('data/styleparams.php'); break; // load styleparam
            case 'Styles': require ('data/styles.php'); break; // loads style -> getVersion($mode, $context) -> ContextedVersion
            case 'StyleTypes': require ('data/styletypes.php'); break; // loads styletype
            case 'Templates': require ('data/templates.php'); break; // loads template
            case 'UserGroups': require ('data/usergroups.php'); break; // loads usergroup
            case 'Users': require ('data/users.php'); break; // loads user
            case 'Versions': require ('data/versions.php'); break; // loads version
            // data classes
            case 'Argument': require ('data/argument.php'); break;
            case 'Command': require ('data/command.php'); break;
            case 'Context': require ('data/context.php'); break;
            case 'ContextedVersion': require ('data/contextedversion.php'); break;
            case 'ContextGroup': require ('data/contextgroup.php'); break;
            case 'Event': require ('data/event.php'); break;
            case 'FileInclude': require ('data/fileinclude.php'); break;
            case 'Layout': require ('data/layout.php'); break;
            case 'LSSVersion': require ('data/lssversion.php'); break;
            case 'LSSVersionCheck': require ('data/lssversioncheck.php'); break;
            case 'Mode': require ('data/mode.php'); break;
            case 'ModedVersion': require ('data/modedversion.php'); break;
            case 'Object': require ('data/object.php'); break;
            case 'ObjectCache': require ('data/objectcache.php'); break;
            case 'ObjectUserGroupRole': require ('data/objectusergrouprole.php'); break;
            case 'ObjectVersion': require ('data/objectversion.php'); break;
            case 'Permission': require ('data/permission.php'); break;
            case 'Position': require ('data/position.php'); break;
            case 'PositionContentItem': require ('data/positioncontentitem.php'); break;
            case 'PositionInstance': require ('data/positioninstance.php'); break;
            case 'PositionObject': require ('data/positionobject.php'); break;
            case 'PositionReferral': require ('data/positionreferral.php'); break;
            case 'PositionEmpty': require ('data/positionempty.php'); break;
            case 'Role': require ('data/role.php'); break;
            case 'Session': require ('data/session.php'); break;
            case 'Set': require ('data/set.php'); break;
            case 'Setting': require ('data/setting.php'); break;
            case 'Snippet': require ('data/snippet.php'); break;
            case 'Structure': require ('data/structure.php'); break;
            case 'Style': require ('data/style.php'); break;
            case 'StyleParam': require ('data/styleparam.php'); break;
            case 'StyleSheetCache': require ('data/stylesheetcache.php'); break;
            case 'StyleType': require ('data/styletype.php'); break;
            case 'Template': require ('data/template.php'); break;
            case 'User': require ('data/user.php'); break;
            case 'UserGroup': require ('data/usergroup.php'); break;
            case 'Version': require ('data/version.php'); break;

            // factory classes, factories create content from data; use cache and data classes 
            case 'AdminLabels': require ('factor/adminlabels.php'); break;
            case 'LSSNames': require ('factor/lssnames.php'); break;
            case 'Factory': require ('factor/factory.php'); break;
            case 'AdminFactory': require ('factor/adminfactory.php'); break;
            case 'AddAdminFactory': require ('factor/addadminfactory.php'); break;
            case 'EditAdminFactory': require ('factor/editadminfactory.php'); break;
            case 'ConfigAdminFactory': require ('factor/configadminfactory.php'); break;
            case 'ConfigLayoutAdminFactory': require ('factor/configlayoutadminfactory.php'); break;
            case 'ConfigStructureAdminFactory': require ('factor/configstructureadminfactory.php'); break;
            case 'ConfigStyleAdminFactory': require ('factor/configstyleadminfactory.php'); break;
            case 'ConfigStyleParamAdminFactory': require ('factor/configstyleparamadminfactory.php'); break;
            case 'ConfigSetAdminFactory': require ('factor/configsetadminfactory.php'); break;
            case 'ConfigTemplateAdminFactory': require ('factor/configtemplateadminfactory.php'); break;
            case 'ContentFactory': require ('factor/contentfactory.php'); break;
            case 'ContentItemFactory': require ('factor/contentitemfactory.php'); break;
            case 'CommandFactory': require ('factor/commandfactory.php'); break;
            case 'MoveAdminFactory': require ('factor/moveadminfactory.php'); break;
            case 'ObjectFactory': require ('factor/objectfactory.php'); break;
            case 'PositionFactory': require ('factor/positionfactory.php'); break;
            case 'ReferralFactory': require ('factor/referralfactory.php'); break;
            case 'StyleFactory': require ('factor/stylefactory.php'); break;
            case 'Terms': require ('factor/terms.php'); break;
            case 'UploadFactory': require ('factor/uploadfactory.php'); break;
            
            // cache classes, the caches contain factored content; used by factory classes
            case 'CacheObjects': require ('cache/cacheobjects.php'); break;
            case 'CacheStyles': require ('cache/cachestyles.php'); break;
            case 'CachePositionInstances': require ('cache/cachepositioninstances.php'); break;
            case 'CacheObjectAddressableParentObjects': require ('cache/cacheobjectaddressableparentobjects.php'); break;
            
            // execute classes, these classes execute changes in the data; uses data classes
            case 'Execute': require ('execute/execute.php'); break;
            case 'ExecuteUpload': require ('execute/executeupload.php'); break;
            case 'ExecuteObjectAction': require ('execute/executeobjectaction.php'); break;
            
            // handler interfaces, describe the generic functions of a handler
            case 'Handler': require ('handle/handler.php'); break;
            // handler abstract classes, implement the generic functions of a handler
            case 'Respond': require ('handle/respond.php'); break;
            // handle classes, the handlers check authorizations and call the caches or executers to execute a command, and fire events, formulate a response to the facade; use cache, execute and admin classes
            case 'Admin': require ('handle/admin.php'); break;
            case 'Authorization': require ('handle/authorization.php'); break;
            case 'Change': require ('handle/change.php'); break;
            case 'Content': require ('handle/content.php'); break;
            case 'File': require ('handle/file.php'); break;
            case 'Page': require ('handle/page.php'); break; // must load before the home class (home extends page)
            case 'Home': require ('handle/home.php'); break;
            case 'Upload': require ('handle/upload.php'); break;
            case 'IncludeFile': require ('handle/includefile.php'); break;
            case 'Login': require ('handle/login.php'); break;

            // facade classes, the facade recieves requests from the frontend, validates it, record commands in the command log and distributes the request to handlers
            case 'Initializer': require ('facade/initializer.php'); break;
            case 'Authentication': require ('facade/authentication.php'); break;
            case 'Request': require ('facade/request.php'); break;
            case 'RequestURL': require ('facade/requesturl.php'); break;
            case 'Response': require ('facade/response.php'); break;
            case 'Validator': require ('facade/validator.php'); break;
            case 'Messages': require ('facade/messages.php'); break;

            // libraries
            case 'Mobile_Detect': require ('libs/mobiledetect/Mobile_Detect.php'); break;
            case 'SimpleImage': require ('libs/simpleimage/SimpleImage.php'); break;
        }
    }

}

?>
