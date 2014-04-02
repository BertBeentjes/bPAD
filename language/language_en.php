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
 * Language strings in English. The strings with a numeric id are referenced
 * in the code. The strings with the text id are in the database, mostly as
 * name fields of value list tables (and are in used in the table with the
 * same name as the first part of the text id).
 * 
 */
$lang = array();
$lang[0] = 'Attributes not loading';
$lang[1] = 'Attribute update failed';
$lang[2] = 'There is no back-up context available for this context, ask your administrator to check back-up context settings';
$lang[3] = 'This attribute is defined by bPAD and cannot be changed';
$lang[4] = 'Updating the change date and user failed';
$lang[5] = 'Validating a system parameter failed';
$lang[6] = 'Store is unavailable at the moment, check settings or try again later';
$lang[7] = 'Sorry, something went wrong';
$lang[8] = 'The request is incomplete or inconsistent and cannot be processed';
$lang[9] = 'The command syntax is incorrect';
$lang[10] = 'The URL syntax is incorrect';
$lang[11] = 'Insufficient authorization to request a page';
$lang[12] = 'The snippet for this context group is not found';
$lang[13] = 'The factory is initialized incorrectly and can´t factor';
$lang[14] = 'The maximum number of incorrect login attempts for this user has been reached, contact the site administrator.';
$lang[15] = 'Incorrect password for this user, number of attempts left: ';
$lang[16] = 'The combination of user name and password is unknown.';
$lang[17] = 'The stylesheet cache is corrupt.';
$lang[18] = 'The requested file include is not available.';
$lang[19] = 'The requested file has not been found';
$lang[20] = 'Insufficient authorization.';
$lang[21] = 'The requested position was not found.';
$lang[22] = 'The command contains unknown content and can\'t be executed.';
$lang[23] = 'The command is invalid and can\'t be executed';
$lang[24] = 'The provided value is not allowed for this field.';
$lang[25] = 'This item has been edited by another user, refresh the page to edit the item';
$lang[26] = 'The file is larger than the limit for uploading files';
$lang[27] = 'The item to be created already exists';

$lang['SETTINGS_SITE_NAME'] = 'Name';
$lang['SETTINGS_SITE_ROOT'] = 'Root url';
$lang['SETTINGS_SITE_ROOTFOLDER'] = 'Root folder';
$lang['SETTINGS_SITE_LANGUAGE'] = 'Language';
$lang['SETTINGS_SITE_ADMINEMAIL'] = 'Administrator email';
$lang['SETTINGS_SITE_MAXUPLOADSIZE'] = 'Maximum upload size';
$lang['SETTINGS_SITE_UPLOAD_LOCATION'] = 'Location for uploaded files';
$lang['SETTINGS_SITE_UPLOAD_LOCATION_PERMISSIONS'] = 'Permissions for the location for uploaded files';

$lang['SETTINGS_SECURITY_HASHALGORITHM'] = 'Hash algorithm';
$lang['SETTINGS_SECURITY_SALT'] = 'Salt';
$lang['SETTINGS_SECURITY_MAXLOGINATTEMPTS'] = 'Maximum login attempts';

$lang['SETTINGS_UPDATE_LSSMASTER'] = 'Master site for layout/style/structure (position layout) versions';
$lang['SETTINGS_UPDATE_LSSPASSWORD'] = 'Password for lss master site';

$lang['SETTINGS_CONTENT_SETMOBILEVIEWPORT'] = 'Set the mobile view port';
$lang['SETTINGS_CONTENT_MOBILEUSEPNDEFAULT'] = 'Use #pn# default values for mobile contexts';
$lang['SETTINGS_CONTENT_SHOWLIGHTBOXOBJECTNAME'] = 'Show the object name in front end editor';
$lang['SETTINGS_CONTENT_USECONTENTDIVADMINCLASS'] = 'Use the content div admin class';
$lang['SETTINGS_CONTENT_PRELOADINSTANCES'] = 'The number of search results to load immediately';

$lang['SETTINGS_CONTEXT_DEFAULTMINWIDTH'] = 'Minimum screen width for default context';
$lang['SETTINGS_CONTEXT_DEFAULTMINHEIGHT'] = 'Minimum screen height for default context';

$lang['SETTINGS_GOOGLE_ANALYTICSCODE'] = 'Google Analytics code';

$lang['SETTINGS_FRONTENDMENU_EDITINLINE'] = 'Edit inline';
$lang['SETTINGS_FRONTENDMENU_EDITLIGHTBOX'] = 'Edit in lightbox';
$lang['SETTINGS_FRONTENDMENU_EDITNAME'] = 'Edit object name';
$lang['SETTINGS_FRONTENDMENU_STYLES'] = 'Change styles';
$lang['SETTINGS_FRONTENDMENU_LAYOUTS'] = 'Change layouts';
$lang['SETTINGS_FRONTENDMENU_STRUCTURES'] = 'Change position layouts';
$lang['SETTINGS_FRONTENDMENU_ARGUMENT'] = 'Change argument';
$lang['SETTINGS_FRONTENDMENU_AUTHORIZATION'] = 'Change authorization';
$lang['SETTINGS_FRONTENDMENU_MOVE'] = 'Move';
$lang['SETTINGS_FRONTENDMENU_MOVEUPDOWN'] = 'Move up/down';
$lang['SETTINGS_FRONTENDMENU_PUBLISH'] = 'Publish';
$lang['SETTINGS_FRONTENDMENU_UPDATE'] = 'Update';
$lang['SETTINGS_FRONTENDMENU_DEACTIVATE'] = 'Deactivate';
$lang['SETTINGS_FRONTENDMENU_DELETE'] = 'Delete';

$lang['CONTEXTGROUP_DEFAULT'] = 'default';
$lang['CONTEXTGROUP_MOBILE'] = 'mobile';
$lang['CONTEXTGROUP_METADATA'] = 'metadata';
$lang['CONTEXTGROUP_SITEMAP'] = 'sitemap';

$lang['CONTEXTGROUP_DEFAULT_SHORT'] = 'def';
$lang['CONTEXTGROUP_MOBILE_SHORT'] = 'mob';
$lang['CONTEXTGROUP_METADATA_SHORT'] = 'mtd';
$lang['CONTEXTGROUP_SITEMAP_SHORT'] = 'sit';

$lang['CONTEXT_DEFAULT'] = 'default';
$lang['CONTEXT_INSTANCE'] = 'instance';
$lang['CONTEXT_RECYCLEBIN'] = 'recyclebin';
$lang['CONTEXT_INLINE'] = 'inline';
$lang['CONTEXT_SLIDE'] = 'slide';

$lang['CONTEXT_DEFAULT_SHORT'] = 'def';
$lang['CONTEXT_INSTANCE_SHORT'] = 'ins';
$lang['CONTEXT_RECYCLEBIN_SHORT'] = 'reb';
$lang['CONTEXT_INLINE_SHORT'] = 'inl';
$lang['CONTEXT_SLIDE_SHORT'] = 'sli';

$lang['INPUTTYPE_TEXTAREA'] = 'Text';
$lang['INPUTTYPE_INPUTBOX'] = 'Input box';
$lang['INPUTTYPE_COMBOBOX'] = 'Combo box';
$lang['INPUTTYPE_UPLOADEDFILE'] = 'File';

$lang['POSITION_STYLE'] = 'For position layouts';
$lang['OBJECT_STYLE'] = 'For layouts';

$lang[PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_ASC] = 'Change date - oldest first';
$lang[PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_DESC] = 'Change date - newest first';
$lang[PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_ASC] = 'Create date - oldest first';
$lang[PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_DESC] = 'Create date - newest first';

$lang[PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_ASC] = 'Change date - oldest first';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_DESC] = 'Change date - newest first';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_ASC] = 'Create date - oldest first';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_DESC] = 'Create date - newest first';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NAME_ASC] = 'Name - A-Z';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NAME_DESC] = 'Name - Z-A';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_ASC] = 'Number - up';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_DESC] = 'Number - down';

$lang[LSSNames::STRUCTURE_CENTERED_TEXT] = 'Centered text';
$lang[LSSNames::STRUCTURE_H1] = 'Heading 1';
$lang[LSSNames::STRUCTURE_H2] = 'Heading 2';
$lang[LSSNames::STRUCTURE_H3] = 'Heading 3';
$lang[LSSNames::STRUCTURE_H4] = 'Heading 4';
$lang[LSSNames::STRUCTURE_H5] = 'Heading 5';
$lang[LSSNames::STRUCTURE_PARAGRAPH_START] = 'Paragraph start';
$lang[LSSNames::STRUCTURE_PARAGRAPH_END] = 'Paragraph end';
$lang[LSSNames::STRUCTURE_INTERNAL_LINK_START] = 'Internal link start';
$lang[LSSNames::STRUCTURE_INTERNAL_LINK_END] = 'Internal link end';
$lang[LSSNames::STRUCTURE_STRONG] = 'Strong';
$lang[LSSNames::STRUCTURE_ACCENT] = 'Accent colour';
$lang[LSSNames::STRUCTURE_ITALIC] = 'Italic';
$lang[LSSNames::STRUCTURE_EXTERNAL_LINK_START] = 'External link start';
$lang[LSSNames::STRUCTURE_EXTERNAL_LINK_END] = 'External link end';
$lang[LSSNames::STRUCTURE_LIST_ITEM] = 'List item';
$lang[LSSNames::STRUCTURE_LIST_START] = 'List start';
$lang[LSSNames::STRUCTURE_LIST_END] = 'List end';
$lang[LSSNames::STRUCTURE_NEW_LINE] = 'New line';
$lang[LSSNames::STRUCTURE_BREADCRUMB] = 'Breadcrumb';
$lang[LSSNames::STRUCTURE_BREADCRUMB_SEPARATOR] = 'Breadcrumb separator';
$lang[LSSNames::STRUCTURE_LAZY_LOAD] = 'Lazy load';
$lang[LSSNames::STRUCTURE_SEARCH_BOX] = 'Search box';
$lang[LSSNames::STRUCTURE_INSTANCE_HEADER] = 'Search result header';
$lang[LSSNames::STRUCTURE_INSTANCE_SECTION] = 'Search result section';
$lang[LSSNames::STRUCTURE_CONFIG_BUTTON] = 'Configure';
$lang[LSSNames::STRUCTURE_ADD_BUTTON] = 'Add';
$lang[LSSNames::STRUCTURE_BUTTON_TOGGLE] = 'Buttons';
$lang[LSSNames::STRUCTURE_EDIT_BUTTON] = 'Edit';
$lang[LSSNames::STRUCTURE_CONFIG_PANEL] = 'Config panel';
$lang[LSSNames::STRUCTURE_ADD_PANEL] = 'Add panel';
$lang[LSSNames::STRUCTURE_EDIT_PANEL] = 'Edit panel';
$lang[LSSNames::STRUCTURE_MOVE_BUTTON] = 'Move';
$lang[LSSNames::STRUCTURE_MOVE_PANEL] = 'Move panel';
$lang[LSSNames::STRUCTURE_ERROR_MESSAGE] = 'Error message';
$lang[LSSNames::STRUCTURE_MODAL] = 'Modal message';
$lang[LSSNames::STRUCTURE_ADMIN_TEXT_INPUT] = 'Administrator - text input';
$lang[LSSNames::STRUCTURE_ADMIN_CHECKBOX] = 'Administrator - checkbox';
$lang[LSSNames::STRUCTURE_ADMIN_COMBOBOX] = 'Administrator - combobox';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX] = 'Administrator - listbox';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX_OPTION] = 'Administrator - listbox option';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION] = 'Administrator - section';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION_COLLAPSED] = 'Administrator - section collapsed';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION_HEADER] = 'Administrator - section header';
$lang[LSSNames::STRUCTURE_ADMIN_SEPARATOR] = 'Administrator - separator';
$lang[LSSNames::STRUCTURE_ADMIN_SUB_ITEM] = 'Administrator - sub item';
$lang[LSSNames::STRUCTURE_ADMIN_FILE_INPUT] = 'Administrator - file input';
$lang[LSSNames::STRUCTURE_ADMIN_TEXT_AREA] = 'Administrator - text area';
$lang[LSSNames::STRUCTURE_ADMIN_UPLOAD] = 'Administrator - upload file';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON] = 'Administrator - button';
$lang[LSSNames::STRUCTURE_ADMIN_MAIN_BUTTON] = 'Administrator - menubutton';
$lang[LSSNames::STRUCTURE_ADMIN_MENU] = 'Administrator - menu';
$lang[LSSNames::STRUCTURE_ADMIN_MENU_ITEM] = 'Administrator - menuitem';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_GROUP] = 'Administrator - button group';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_GROUP_ALT] = 'Administrator - button group alternative';

$lang[AdminLabels::ADMIN_OBJECT_NAME] = 'Name';
$lang[AdminLabels::ADMIN_OBJECT_INTERNAL_LINK] = 'Link to this item';
$lang[AdminLabels::ADMIN_OBJECT_ACTIVE] = 'Active';
$lang[AdminLabels::ADMIN_OBJECT_SET] = 'Set';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_LAYOUT] = 'Layout';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_STYLE] = 'Style';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT_DEFAULT] = 'Argument default value';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT] = 'Argument';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_LAYOUT] = 'Fixed layout';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_STYLE] = 'Fixed style';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_TEMPLATE] = 'Template for child items';
$lang[AdminLabels::ADMIN_POSITION_INHERIT_STRUCTURE] = 'Fixed position layout';
$lang[AdminLabels::ADMIN_POSITION_INHERIT_STYLE] = 'Fixed position style';
$lang[AdminLabels::ADMIN_POSITION_REMOVE] = 'Delete position';
$lang[AdminLabels::ADMIN_POSITION_ADD_CONTENT_ITEM] = 'Add text item';
$lang[AdminLabels::ADMIN_POSITION_ADD_OBJECT] = 'Add an object';
$lang[AdminLabels::ADMIN_POSITION_ADD_INSTANCE] = 'Add an instance';
$lang[AdminLabels::ADMIN_POSITION_ADD_REFERRAL] = 'Add a menu';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_NAME] = 'Name';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_INPUT_TYPE] = 'Input field type';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_BODY] = 'Default content';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_UPLOAD] = 'File';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_CURRENT_VALUE] = 'Current';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_ACTIVE_ITEMS] = 'Active items';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_GROUP_BY] = 'Group';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_LISTWORDS] = 'Terms';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_OBJECT] = 'Specific object';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_OBJECT_DEFAULT] = 'Default (none)';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_ORDER_BY] = 'Order by';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_PARENT] = 'Subitem of';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_SEARCHWORDS] = 'Search';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_TEMPLATE] = 'Based on template';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_TEMPLATE_DEFAULT] = 'Default (none)';
$lang[AdminLabels::ADMIN_POSITION_REFERRAL_ARGUMENT] = 'Menu name';
$lang[AdminLabels::ADMIN_POSITION_REFERRAL_NUMBER_OF_ITEMS] = 'Number of items (0 is all)';
$lang[AdminLabels::ADMIN_POSITION_REFERRAL_ORDER_BY] = 'Order by';
$lang[AdminLabels::ADMIN_BUTTON_MOVE] = 'Move';
$lang[AdminLabels::ADMIN_BUTTON_MOVE_UP] = 'Move up';
$lang[AdminLabels::ADMIN_BUTTON_MOVE_DOWN] = 'Move down';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH] = 'Publish changes';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_NEW] = 'Publish new item';
$lang[AdminLabels::ADMIN_BUTTON_UNDO] = 'Undo';
$lang[AdminLabels::ADMIN_BUTTON_KEEP] = 'Close';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL] = 'Cancel';
$lang[AdminLabels::ADMIN_BUTTON_TO_RECYCLE_BIN] = 'To recycle bin';
$lang[AdminLabels::ADMIN_BUTTON_FROM_RECYCLE_BIN] = 'Restore';
$lang[AdminLabels::ADMIN_BUTTON_CLOSE] = 'Close';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_MAIN] = 'Configuration menu';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_LAYOUTS] = 'Layouts';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_STYLES] = 'Styles';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_STYLEPARAMS] = 'Style parameter settings';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_STRUCTURES] = 'Position layouts';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_SETS] = 'Sets';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_TEMPLATES] = 'Templates';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUT] = 'Add layout';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUTVERSION] = 'Add version';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SET] = 'Add set';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STRUCTURE] = 'Add position layout';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STRUCTUREVERSION] = 'Add version';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLE] = 'Add style';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLE_PARAM] = 'Add style parameter';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLEVERSION] = 'Add version';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLEPARAMVERSION] = 'Add version';
$lang[AdminLabels::ADMIN_BUTTON_ADD_TEMPLATE] = 'Add template';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUT] = 'Remove layout';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUTVERSION] = 'Remove version';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_SET] = 'Remove set';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTURE] = 'Remove position layout';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTUREVERSION] = 'Remove version';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLE] = 'Remove style';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLE_PARAM] = 'Remove style parameter';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLEVERSION] = 'Remove version';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLEPARAMVERSION] = 'Remove version';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_TEMPLATE] = 'Remove template';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_LAYOUTVERSION] = 'Publish version';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STRUCTUREVERSION] = 'Publish version';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STYLEVERSION] = 'Publish version';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STYLEPARAMVERSION] = 'Publish version';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_TEMPLATE] = 'Publish template';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_LAYOUTVERSION] = 'Cancel version';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STRUCTUREVERSION] = 'Cancel version';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STYLEVERSION] = 'Cancel version';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STYLEPARAMVERSION] = 'Cancel version';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_TEMPLATE] = 'Cancel template';
$lang[AdminLabels::ADMIN_CONFIG_LAYOUTS] = 'Layouts';
$lang[AdminLabels::ADMIN_LAYOUT_NAME] = 'Name';
$lang[AdminLabels::ADMIN_LAYOUT_SET] = 'Set';
$lang[AdminLabels::ADMIN_LAYOUT_VERSION_BODY] = 'Layout';
$lang[AdminLabels::ADMIN_CONFIG_STRUCTURES] = 'Position layouts';
$lang[AdminLabels::ADMIN_STRUCTURE_NAME] = 'Name';
$lang[AdminLabels::ADMIN_STRUCTURE_SET] = 'Set';
$lang[AdminLabels::ADMIN_STRUCTURE_VERSION_BODY] = 'Position layout';
$lang[AdminLabels::ADMIN_CONFIG_STYLES] = 'Styles';
$lang[AdminLabels::ADMIN_STYLE_NAME] = 'Name';
$lang[AdminLabels::ADMIN_STYLE_SET] = 'Set';
$lang[AdminLabels::ADMIN_STYLE_TYPE] = 'Type';
$lang[AdminLabels::ADMIN_STYLE_CLASS_SUFFIX] = 'Css class addition';
$lang[AdminLabels::ADMIN_STYLE_VERSION_BODY] = 'Style';
$lang[AdminLabels::ADMIN_CONFIG_STYLE_PARAMS] = 'Style parameter settings';
$lang[AdminLabels::ADMIN_STYLE_PARAM_NAME] = 'Name';
$lang[AdminLabels::ADMIN_CONFIG_TEMPLATES] = 'Templates';
$lang[AdminLabels::ADMIN_TEMPLATE_NAME] = 'Name';
$lang[AdminLabels::ADMIN_TEMPLATE_DELETED] = 'Disabled';
$lang[AdminLabels::ADMIN_TEMPLATE_INSTANCE_ALLOWED] = 'Visible in instances';
$lang[AdminLabels::ADMIN_TEMPLATE_SEARCHABLE] = 'Belongs to parent';
$lang[AdminLabels::ADMIN_TEMPLATE_SET] = 'Set';
$lang[AdminLabels::ADMIN_TEMPLATE_STRUCTURE] = 'Position layout when adding';
$lang[AdminLabels::ADMIN_TEMPLATE_STYLE] = 'Style when adding';
$lang[AdminLabels::ADMIN_CONFIG_SETS] = 'Sets';
$lang[AdminLabels::ADMIN_SET_NAME] = 'Name';
$lang[AdminLabels::ADMIN_PROCESSING] = 'Processing...';
?>