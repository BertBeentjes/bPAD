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
$lang['SETTINGS_SITE_LOCALE'] = 'Locale';

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
$lang['SETTINGS_CONTENT_PRELOADPNOBJECTS'] = 'The number of objects in a #pn# layout to load immediately';

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
$lang[LSSNames::STRUCTURE_DEEP_LINK] = 'Deep link';
$lang[LSSNames::STRUCTURE_EDIT_PANEL] = 'Edit panel';
$lang[LSSNames::STRUCTURE_MOVE_BUTTON] = 'Move';
$lang[LSSNames::STRUCTURE_MOVE_PANEL] = 'Move panel';
$lang[LSSNames::STRUCTURE_ERROR_MESSAGE] = 'Error message';
$lang[LSSNames::STRUCTURE_MODAL] = 'Modal message';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD] = 'Administrator - show add buttons';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS] = 'Administrator - show layout items';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD_NAME] = 'Add buttons';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS_NAME] = 'Lay-out items';
$lang[LSSNames::STRUCTURE_ADMIN_TEXT_INPUT] = 'Administrator - text input';
$lang[LSSNames::STRUCTURE_ADMIN_CONFIG_BUTTON] = 'Configure';
$lang[LSSNames::STRUCTURE_ADMIN_CHECKBOX] = 'Administrator - checkbox';
$lang[LSSNames::STRUCTURE_ADMIN_COMBOBOX] = 'Administrator - combobox';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX] = 'Administrator - listbox';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX_LSS] = 'Administrator - listbox layout items';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX_OPTION] = 'Administrator - listbox option';
$lang[LSSNames::STRUCTURE_ADMIN_ERROR_MESSAGE] = 'Administrator - error message';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION] = 'Administrator - section';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION_COLLAPSED] = 'Administrator - section collapsed';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION_ADD] = 'Administrator - section add';
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
$lang[LSSNames::STRUCTURE_BASIC] = 'Default';
$lang[LSSNames::STRUCTURE_POSITION_INSERT] = 'Insert position';
$lang[LSSNames::STRUCTURE_INSERT_ARTICLE] = 'Insert article';
$lang[LSSNames::STRUCTURE_EMPTY_ITEM] = 'Empty position';
$lang[LSSNames::STRUCTURE_MENU_ITEM] = 'Menu item';
$lang[LSSNames::STRUCTURE_CONTENT_ITEM] = 'Content item';
$lang[LSSNames::STRUCTURE_IMG] = 'Image';
$lang[LSSNames::STRUCTURE_TEXT] = 'Text';
$lang[LSSNames::STRUCTURE_IMG_META] = 'Image for metadata';
$lang[LSSNames::STRUCTURE_TEXT_META] = 'Text for metadata';
$lang[LSSNames::STRUCTURE_BLOCKQUOTE] = 'Blockquote';
$lang[LSSNames::STRUCTURE_IMG_FULL_HIDDEN_SMALL] = 'Image full, hidden on small screens';
$lang[LSSNames::STRUCTURE_SEARCH_SITE] = 'Search the site';
$lang[LSSNames::STRUCTURE_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::STRUCTURE_IMG_CAPTION] = 'Image caption';
$lang[LSSNames::STRUCTURE_TITLE_H1] = 'Title H1';
$lang[LSSNames::STRUCTURE_TITLE_H2] = 'Title H2';
$lang[LSSNames::STRUCTURE_TITLE_H3] = 'Title H3';
$lang[LSSNames::STRUCTURE_BANNER_TEXT] = 'Banner text';
$lang[LSSNames::STRUCTURE_HTML] = 'HTML';
$lang[LSSNames::STRUCTURE_OBJECT_UNPUBLISHED_INDICATOR] = 'Object unpublished changes';

$lang[LSSNames::LAYOUT_AD] = 'Ad';
$lang[LSSNames::LAYOUT_AD_WIDE] = 'Ad wide';
$lang[LSSNames::LAYOUT_ACTION_BUTTON] = 'Action button';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_TITLE_TEXT] = 'Article image - title - text';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_TITLE_TEXT_WIDE] = 'Article image - title - text - wide';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT] = 'Article image left - text right';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT_WIDE] = 'Article image left - text right - wide';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_LEFT_SMALL_TEXT_RIGHT_WIDE] = 'Article image left small - text right - wide';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_IMG_TEXT] = 'Article title - image - text';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT] = 'Article title - text';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT_WIDE] = 'Article title - text - wide';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT_LEFT_IMG_RIGHT] = 'Article title - text left - img right';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT_FULL] = 'Article title - text - full width';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK] = 'Article title - two blocks';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_EQUAL] = 'Article title - two blocks equal';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_LARGE_BLOCK] = 'Article title - block large left - block small right';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_OVERLAY_BLOCK] = 'Article title - block overlay - block';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_LARGE] = 'Article title - block small left - block large right';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK] = 'Article two blocks';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_IMG_BLOCK] = 'Article block image - block';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_IMG] = 'Article block - block image';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_WIDE] = 'Article two blocks - wide';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK] = 'Article block large left - block small right';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK_WIDE] = 'Article block large left - block small right - wide';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK] = 'Article block with overlay - block';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK_WIDE] = 'Article block with overlay - block - wide';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY] = 'Article block - block with overlay';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY_WIDE] = 'Article block - block with overlay - wide';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE] = 'Article block small left - block large right';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE_WIDE] = 'Article block small left - block large right - wide';
$lang[LSSNames::LAYOUT_BANNER] = 'Banner';
$lang[LSSNames::LAYOUT_KEYWORD] = 'Keyword';
$lang[LSSNames::LAYOUT_IN_TEXT_IMG] = 'Image in text';
$lang[LSSNames::LAYOUT_IN_TEXT_IMG_SMALL] = 'Image in text - small';
$lang[LSSNames::LAYOUT_IMG_TEXT] = 'Image and text';
$lang[LSSNames::LAYOUT_IMG_COLUMN_TEXT] = 'Image in column and text';
$lang[LSSNames::LAYOUT_IMG_TEXT_COLUMN] = 'Image and text in column';
$lang[LSSNames::LAYOUT_IMG_TEXT_SNIPPET] = 'Image and text snippet';
$lang[LSSNames::LAYOUT_IMG_CLICKABLE] = 'Image clickable';
$lang[LSSNames::LAYOUT_IMG_CLICKABLE_FULL] = 'Image clickable - full';
$lang[LSSNames::LAYOUT_IMG_LANDSCAPE] = 'Image landscape';
$lang[LSSNames::LAYOUT_IMG_CAPTION] = 'Image caption';
$lang[LSSNames::LAYOUT_IMG_PORTRAIT] = 'Image portrait';
$lang[LSSNames::LAYOUT_IMG_THUMBNAIL] = 'Image thumbnail';
$lang[LSSNames::LAYOUT_IMG_FULL] = 'Image - full';
$lang[LSSNames::LAYOUT_IMG_FULL_HIDDEN_XS] = 'Image - full - hidden in one column layout';
$lang[LSSNames::LAYOUT_IMG_NO_CAPTION] = 'Image no caption';
$lang[LSSNames::LAYOUT_BLOCK] = 'Block';
$lang[LSSNames::LAYOUT_CAROUSEL] = 'Carousel';
$lang[LSSNames::LAYOUT_CAROUSELITEM] = 'Carousel item';
$lang[LSSNames::LAYOUT_CAROUSELITEM_ACTIVE] = 'Carousel item active';
$lang[LSSNames::LAYOUT_CONTENT_NO_BUTTONS] = 'Content - no menu';
$lang[LSSNames::LAYOUT_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::LAYOUT_PAGEPART] = 'Pagepart';
$lang[LSSNames::LAYOUT_COLUMN_1] = 'Column width 1';
$lang[LSSNames::LAYOUT_COLUMN_10] = 'Column width 10';
$lang[LSSNames::LAYOUT_COLUMN_11] = 'Column width 11';
$lang[LSSNames::LAYOUT_COLUMN_12] = 'Column width 12';
$lang[LSSNames::LAYOUT_COLUMN_12_NO_BUTTONS] = 'Column width 12 - no menu';
$lang[LSSNames::LAYOUT_COLUMN_2] = 'Column width 2';
$lang[LSSNames::LAYOUT_COLUMN_3] = 'Column width 3';
$lang[LSSNames::LAYOUT_COLUMN_3_SM_6] = 'Column width 3 - small 6';
$lang[LSSNames::LAYOUT_COLUMN_4] = 'Column width 4';
$lang[LSSNames::LAYOUT_COLUMN_5] = 'Column width 5';
$lang[LSSNames::LAYOUT_COLUMN_6] = 'Column width 6';
$lang[LSSNames::LAYOUT_COLUMN_7] = 'Column width 7';
$lang[LSSNames::LAYOUT_COLUMN_8] = 'Column width 8';
$lang[LSSNames::LAYOUT_COLUMN_8_SM_12] = 'Column width 8 - small 12';
$lang[LSSNames::LAYOUT_COLUMN_9] = 'Column width 9';
$lang[LSSNames::LAYOUT_MENU_LIST] = 'Menu list';
$lang[LSSNames::LAYOUT_NAVPILLS] = 'Navigation pills';
$lang[LSSNames::LAYOUT_NAVTOP_WIDE] = 'Navigation top wide';
$lang[LSSNames::LAYOUT_NAVTOP] = 'Navigation top';
$lang[LSSNames::LAYOUT_NAVTOP_FIXED] = 'Navigation top fixed';
$lang[LSSNames::LAYOUT_INSTANCE] = 'Instance';
$lang[LSSNames::LAYOUT_PAGE] = 'Page';
$lang[LSSNames::LAYOUT_PAGESECTION] = 'Page section';
$lang[LSSNames::LAYOUT_SITEROOT] = 'Site root';
$lang[LSSNames::LAYOUT_SITECONTENT] = 'Site content';
$lang[LSSNames::LAYOUT_SUBNAV] = 'Sub navigation';
$lang[LSSNames::LAYOUT_TEXT] = 'Text';
$lang[LSSNames::LAYOUT_LINE] = 'Line';
$lang[LSSNames::LAYOUT_TITLE] = 'Title';
$lang[LSSNames::LAYOUT_WHITESPACE] = 'Whitespace';

$lang[LSSNames::STYLE_ACTION_BUTTON] = 'Action button';
$lang[LSSNames::STYLE_AD_HORIZONTAL] = 'Ad horizontal';
$lang[LSSNames::STYLE_AD_VERTICAL] = 'Ad vertical';
$lang[LSSNames::STYLE_ARTICLE_TITLE_IMG_TEXT] = 'Article title - image - text';
$lang[LSSNames::STYLE_TITLE_IMG_TEXT_MARGIN] = 'Article title - image - text - large margins';
$lang[LSSNames::STYLE_ARTICLE_TITLE_TEXT] = 'Article title - text';
$lang[LSSNames::STYLE_ARTICLE_TITLE_TEXT_BGLIGHT] = 'Article title - text - light background';
$lang[LSSNames::STYLE_ARTICLE_TITLE_BLOCK_BLOCK] = 'Article title - two blocks';
$lang[LSSNames::STYLE_ARTICLE_TITLE_BLOCK_BLOCK_MARGIN] = 'Article title - two blocks - large margins';
$lang[LSSNames::STYLE_ARTICLE_BLOCK_BLOCK] = 'Article two blocks';
$lang[LSSNames::STYLE_ARTICLE_BLOCK_BLOCK_COMPACT] = 'Article two blocks compact';
$lang[LSSNames::STYLE_ARTICLE_BLOCK_BLOCK_NO_BG] = 'Article two blocks - no background';
$lang[LSSNames::STYLE_BANNER] = 'Banner';
$lang[LSSNames::STYLE_BANNER_DARK] = 'Banner dark';
$lang[LSSNames::STYLE_BANNER_TEXT] = 'Banner text';
$lang[LSSNames::STYLE_BANNER_TEXT_DARK] = 'Banner text dark';
$lang[LSSNames::STYLE_BANNER_TEXT_ACCENT] = 'Banner text accent';
$lang[LSSNames::STYLE_IMG] = 'Image';
$lang[LSSNames::STYLE_IMG_TEXT] = 'Image and text';
$lang[LSSNames::STYLE_IMG_TEXT_SNIPPET] = 'Image and text snippet';
$lang[LSSNames::STYLE_IMG_CLICKABLE] = 'Image clickable';
$lang[LSSNames::STYLE_IMG_CLICKABLE_CENTER] = 'Image clickable centered';
$lang[LSSNames::STYLE_IMG_CLICKABLE_CENTER_FULL] = 'Image clickable center full';
$lang[LSSNames::STYLE_IMG_CLICKABLE_FULL] = 'Image clickable full';
$lang[LSSNames::STYLE_IMG_LEFT] = 'Image left';
$lang[LSSNames::STYLE_IMG_MARGIN_TOP] = 'Image margin top';
$lang[LSSNames::STYLE_IMG_BORDER] = 'Image with border';
$lang[LSSNames::STYLE_IMG_CENTER] = 'Image centered';
$lang[LSSNames::STYLE_IMG_MARGIN_NEGATIVE] = 'Image negative margin top';
$lang[LSSNames::STYLE_IMG_CAPTION] = 'Image caption';
$lang[LSSNames::STYLE_IMG_RIGHT] = 'Image right';
$lang[LSSNames::STYLE_IMG_THUMBNAIL] = 'Image thumbnail';
$lang[LSSNames::STYLE_IMG_FULL] = 'Image full';
$lang[LSSNames::STYLE_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::STYLE_PAGEPART_RIGHT] = 'Pagepart right';
$lang[LSSNames::STYLE_COLUMN] = 'Column';
$lang[LSSNames::STYLE_COLUMN_BLOCK_ACCENT] = 'Column block accent';
$lang[LSSNames::STYLE_COLUMN_BLOCK_DARK] = 'Column block dark';
$lang[LSSNames::STYLE_COLUMN_BLOCK_LIGHT] = 'Column block light';
$lang[LSSNames::STYLE_COLUMN_THREE_COLUMNS] = 'Column - three column layout';
$lang[LSSNames::STYLE_COLUMN_CENTER] = 'Column centered';
$lang[LSSNames::STYLE_COLUMN_CENTER_SMALL] = 'Column centered on small screens';
$lang[LSSNames::STYLE_COLUMN_TWO_THREE_COLUMNS] = 'Column - two or three column layout';
$lang[LSSNames::STYLE_COLUMN_TWO_COLUMNS] = 'Column - two column layout';
$lang[LSSNames::STYLE_COLUMN_FRONT] = 'Column foreground';
$lang[LSSNames::STYLE_MENU_ITEM] = 'Menu item';
$lang[LSSNames::STYLE_MENU_LIST] = 'Menu list';
$lang[LSSNames::STYLE_NAV_AND_CONTENT] = 'Navigation and content';
$lang[LSSNames::STYLE_OBJECT_DEFAULT] = 'Object default';
$lang[LSSNames::STYLE_INSTANCE] = 'Instance';
$lang[LSSNames::STYLE_INSTANCE_NO_BG] = 'Instance no background';
$lang[LSSNames::STYLE_INSTANCE_LIGHT] = 'Instance light';
$lang[LSSNames::STYLE_PAGE] = 'Page';
$lang[LSSNames::STYLE_PAGESECTION] = 'Page section';
$lang[LSSNames::STYLE_PAGESECTION_DARK] = 'Page section dark';
$lang[LSSNames::STYLE_PAGESECTION_COMPACT] = 'Page section compact';
$lang[LSSNames::STYLE_PAGESECTION_MARGIN] = 'Page section large margins';
$lang[LSSNames::STYLE_POSITION_DEFAULT] = 'Position default';
$lang[LSSNames::STYLE_SITE] = 'Site';
$lang[LSSNames::STYLE_TEXT_THREE_COLUMNS] = 'Text - three column layout';
$lang[LSSNames::STYLE_TEXT_EXTRA_LARGE] = 'Text extra large';
$lang[LSSNames::STYLE_TEXT_LARGE] = 'Text large';
$lang[LSSNames::STYLE_TEXT_LARGE_ALT] = 'Text large alt';
$lang[LSSNames::STYLE_TEXT_DARK_BG] = 'Text on dark background';
$lang[LSSNames::STYLE_TEXT_LIGHT_BG] = 'Text on light background';
$lang[LSSNames::STYLE_TEXT_TWO_COLUMNS] = 'Text - two column layout';
$lang[LSSNames::STYLE_LINE] = 'Line on light background';
$lang[LSSNames::STYLE_LINE_LIGHT] = 'Line on dark background';
$lang[LSSNames::STYLE_TITLE] = 'Title';
$lang[LSSNames::STYLE_TITLE_ARTICLE] = 'Title article position';
$lang[LSSNames::STYLE_TITLE_DARK] = 'Title dark';
$lang[LSSNames::STYLE_TITLE_LEFT] = 'Title left';
$lang[LSSNames::STYLE_TITLE_MARGIN] = 'Title large margins';
$lang[LSSNames::STYLE_TITLE_RIGHT] = 'Title right';
$lang[LSSNames::STYLE_WHITESPACE] = 'Whitespace';
$lang[LSSNames::STYLE_WHITESPACE_LARGE] = 'Whitespace large';

$lang[LSSNames::SET_AD] = 'Ad';
$lang[LSSNames::SET_ARTICLE_TITLE_IMG_TEXT] = 'Article title - image - text';
$lang[LSSNames::SET_ARTICLE_TITLE_TEXT] = 'Article title - text';
$lang[LSSNames::SET_ARTICLE_TITLE_BLOCK_BLOCK] = 'Article title - two blocks';
$lang[LSSNames::SET_ARTICLE_BLOCK_BLOCK] = 'Article two blocks';
$lang[LSSNames::SET_BANNER] = 'Banner';
$lang[LSSNames::SET_IMG] = 'Image';
$lang[LSSNames::SET_IMG_TEXT] = 'Image and text';
$lang[LSSNames::SET_IMG_TEXT_SNIPPET] = 'Image and text snippet';
$lang[LSSNames::SET_IMG_CLICKABLE] = 'Image clickable';
$lang[LSSNames::SET_IMG_CAPTION] = 'Image caption';
$lang[LSSNames::SET_CAROUSEL] = 'Carousel';
$lang[LSSNames::SET_CAROUSELITEM] = 'Carousel item';
$lang[LSSNames::SET_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::SET_PAGEPART] = 'Page part';
$lang[LSSNames::SET_COLUMN] = 'Column';
$lang[LSSNames::SET_MENU_LIST] = 'Menu list';
$lang[LSSNames::SET_NAV_AND_CONTENT] = 'Navigation and content';
$lang[LSSNames::SET_INSTANCE] = 'Instance';
$lang[LSSNames::SET_PAGE] = 'Page';
$lang[LSSNames::SET_PAGESECTION] = 'Page section';
$lang[LSSNames::SET_SITE] = 'Site';
$lang[LSSNames::SET_SITECONTENT] = 'Site content';
$lang[LSSNames::SET_SUBNAV] = 'Sub navigation';
$lang[LSSNames::SET_TEXT] = 'Text';
$lang[LSSNames::SET_LINE] = 'Line';
$lang[LSSNames::SET_TITLE] = 'Title';
$lang[LSSNames::SET_WHITESPACE] = 'Whitespace';
$lang[LSSNames::SET_DEFAULT] = '_default';

$lang[LSSNames::TEMPLATE_AD] = 'Ad';
$lang[LSSNames::TEMPLATE_ACTION_BUTTON] = 'Action button';
$lang[LSSNames::TEMPLATE_ARTICLE_TITLE_IMG_TEXT] = 'Article title - image - text';
$lang[LSSNames::TEMPLATE_ARTICLE_TITLE_TEXT] = 'Article title - text';
$lang[LSSNames::TEMPLATE_ARTICLE_TITLE_BLOCK_BLOCK] = 'Article title - two blocks';
$lang[LSSNames::TEMPLATE_ARTICLE_BLOCK_BLOCK] = 'Article two blocks';
$lang[LSSNames::TEMPLATE_BANNER] = 'Banner';
$lang[LSSNames::TEMPLATE_IMG] = 'Image';
$lang[LSSNames::TEMPLATE_IMG_META] = 'Image for metadata';
$lang[LSSNames::TEMPLATE_IMG_TEXT] = 'Image and text';
$lang[LSSNames::TEMPLATE_IMG_TEXT_SNIPPET] = 'Image and text snippet';
$lang[LSSNames::TEMPLATE_IMG_CLICKABLE] = 'Image clickable';
$lang[LSSNames::TEMPLATE_BLOCK] = 'Block';
$lang[LSSNames::TEMPLATE_CAROUSEL] = 'Carousel';
$lang[LSSNames::TEMPLATE_CAROUSELITEM] = 'Carousel item';
$lang[LSSNames::TEMPLATE_FOOTER] = 'Footer';
$lang[LSSNames::TEMPLATE_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::TEMPLATE_HEADER] = 'Header';
$lang[LSSNames::TEMPLATE_PAGEPART] = 'Page part';
$lang[LSSNames::TEMPLATE_COLUMN] = 'Column';
$lang[LSSNames::TEMPLATE_MENU_LIST] = 'Menu list';
$lang[LSSNames::TEMPLATE_NAV_AND_CONTENT] = 'Navigation and content';
$lang[LSSNames::TEMPLATE_INSTANCE] = 'Instance';
$lang[LSSNames::TEMPLATE_PAGE] = 'Page';
$lang[LSSNames::TEMPLATE_PAGESECTION] = 'Page section';
$lang[LSSNames::TEMPLATE_SITE] = 'Site';
$lang[LSSNames::TEMPLATE_SUBNAV] = 'Sub navigation';
$lang[LSSNames::TEMPLATE_TEXT] = 'Text';
$lang[LSSNames::TEMPLATE_TEXT_META] = 'Text for metadata';
$lang[LSSNames::TEMPLATE_LINE] = 'Line';
$lang[LSSNames::TEMPLATE_WHITESPACE] = 'Whitespace';
$lang[LSSNames::TEMPLATE_DEFAULT] = 'No template';
$lang[LSSNames::TEMPLATE_KEYWORD] = 'Keyword';

$lang[AdminLabels::ADMIN_OBJECT_NAME] = 'Name';
$lang[AdminLabels::ADMIN_OBJECT_TEMPLATE_NAME] = 'Based upon template';
$lang[AdminLabels::ADMIN_OBJECT_INTERNAL_LINK] = 'Link to this item';
$lang[AdminLabels::ADMIN_OBJECT_DEEP_LINK] = 'Deep link to this item';
$lang[AdminLabels::ADMIN_OBJECT_ACTIVE] = 'Active';
$lang[AdminLabels::ADMIN_OBJECT_SET] = 'Set';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_LAYOUT] = 'Layout';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_STYLE] = 'Style';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT_DEFAULT] = 'Argument default value';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT] = 'Argument';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_LAYOUT] = 'Fixed layout';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_STYLE] = 'Fixed style';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_TEMPLATE] = 'Template for child items';
$lang[AdminLabels::ADMIN_POSITION_STRUCTURE] = 'Position structure';
$lang[AdminLabels::ADMIN_POSITION_STYLE] = 'Position style';
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
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_MAX_ITEMS] = 'Number of instances (0 is all)';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_FILL_ON_LOAD] = 'Fill on load';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_USE_INSTANCE_CONTEXT] = 'Show as list';
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
$lang[AdminLabels::ADMIN_POSITION_REFERRAL_NUMBER_OF_ITEMS] = 'Number of menu-items (0 is all)';
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
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_USERS] = 'Users';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_USERGROUPS] = 'User groups';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_ROLES] = 'Roles';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_SETTINGS] = 'Settings';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_INCLUDE_FILES] = 'Include files';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_SNIPPETS] = 'Snippets';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUT] = 'Add layout';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUTVERSION] = 'Add version';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SET] = 'Add set';
$lang[AdminLabels::ADMIN_BUTTON_ADD_USER] = 'Add user';
$lang[AdminLabels::ADMIN_BUTTON_ADD_USERGROUP] = 'Add user group';
$lang[AdminLabels::ADMIN_BUTTON_ADD_ROLE] = 'Add role';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SETTING] = 'Add setting';
$lang[AdminLabels::ADMIN_BUTTON_ADD_INCLUDE_FILE] = 'Add include file';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SNIPPET] = 'Add snippet';
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
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_USER] = 'Remove user';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_USERGROUP] = 'Remove user group';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_ROLE] = 'Remove role';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_INCLUDE_FILE] = 'Remove include file';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_SNIPPET] = 'Remove snippet';
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
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_FILEINCLUDEVERSION] = 'Publish version';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_SNIPPETVERSION] = 'Publish version';
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
$lang[AdminLabels::ADMIN_CONFIG_USERS] = 'Users';
$lang[AdminLabels::ADMIN_CONFIG_USERGROUPS] = 'User groups';
$lang[AdminLabels::ADMIN_CONFIG_ROLES] = 'Roles';
$lang[AdminLabels::ADMIN_CONFIG_SETTINGS] = 'Settings';
$lang[AdminLabels::ADMIN_CONFIG_INCLUDE_FILES] = 'Include files';
$lang[AdminLabels::ADMIN_CONFIG_SNIPPETS] = 'Snippets';
$lang[AdminLabels::ADMIN_SET_NAME] = 'Name';
$lang[AdminLabels::ADMIN_USER_NAME] = 'Name';
$lang[AdminLabels::ADMIN_USER_PASSWORD] = 'Password';
$lang[AdminLabels::ADMIN_USER_FIRST_NAME] = 'First name';
$lang[AdminLabels::ADMIN_USER_LAST_NAME] = 'Last name';
$lang[AdminLabels::ADMIN_USER_LOGIN_COUNTER] = 'Reset number of incorrect login attempts: ';
$lang[AdminLabels::ADMIN_USERGROUP_NAME] = 'Name';
$lang[AdminLabels::ADMIN_ROLE_NAME] = 'Name';
$lang[AdminLabels::ADMIN_SETTING_NAME] = 'Name';
$lang[AdminLabels::ADMIN_SETTING_VALUE] = 'Value';
$lang[AdminLabels::ADMIN_INCLUDE_FILE_NAME] = 'Name';
$lang[AdminLabels::ADMIN_INCLUDE_FILE_MIME_TYPE] = 'Mime type';
$lang[AdminLabels::ADMIN_INCLUDE_FILE_COMMENT] = 'Comment';
$lang[AdminLabels::ADMIN_SNIPPET_NAME] = 'Name';
$lang[AdminLabels::ADMIN_SNIPPET_MIME_TYPE] = 'Mime type';
$lang[AdminLabels::ADMIN_SNIPPET_CONTEXT_GROUP] = 'Context group';
$lang[AdminLabels::ADMIN_PROCESSING] = 'Processing...';

$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_CONTENT] = 'Manage content';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_STYLE] = 'Manage styles';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_STRUCTURE] = 'Manage position layouts';
$lang[AdminLabels::ADMIN_PERMISSIONS_FLUSH_ARCHIVE] = 'Flush archive';
$lang[AdminLabels::ADMIN_PERMISSIONS_VIEW_OBJECT] = 'View object';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_EDIT] = 'Edit';
$lang[AdminLabels::ADMIN_PERMISSIONS_UPLOAD_FILE] = 'Upload file';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_CREATOR_EDIT] = 'Edit own content';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_ADD] = 'Add content';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_CREATOR_DEACTIVATE] = 'Deactivate own content';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_DEACTIVATE] = 'Deactivate content';
$lang[AdminLabels::ADMIN_PERMISSIONS_SHOW_ADMIN_BAR] = 'Show admin buttons';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTENT_RESPOND] = 'Respond to content';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_LSS_VERSION] = 'Create theme';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_LAYOUT] = 'Manage layouts';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_SYSTEM] = 'Manage system';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_LANGUAGE] = 'Manage languages';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_SETTING] = 'Manage settings';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_USER] = 'Manage users';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_ROLE] = 'Manage roles';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_AUTHORIZATION] = 'Manage object authorization';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_TEMPLATE] = 'Manage templates';

$lang[AdminLabels::ADMIN_PERMISSIONS_USER] = 'User level permissions';
$lang[AdminLabels::ADMIN_PERMISSIONS_EDITOR] = 'Editor level permissions';
$lang[AdminLabels::ADMIN_PERMISSIONS_DESIGNER] = 'Designer level permissions';
$lang[AdminLabels::ADMIN_PERMISSIONS_ADMINISTRATOR] = 'Administrator level permissions';
