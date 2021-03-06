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
 * Names of layouts, styles and structures created and used by bPAD
 *
 */
class LSSNames {

    const STRUCTURE_CENTERED_TEXT = 'STRUCTURE_CENTERED_TEXT';
    const STRUCTURE_H1 = 'STRUCTURE_H1';
    const STRUCTURE_H2 = 'STRUCTURE_H2';
    const STRUCTURE_H3 = 'STRUCTURE_H3';
    const STRUCTURE_H4 = 'STRUCTURE_H4';
    const STRUCTURE_H5 = 'STRUCTURE_H5';
    const STRUCTURE_PARAGRAPH_START = 'STRUCTURE_PARAGRAPH_START';
    const STRUCTURE_PARAGRAPH_END = 'STRUCTURE_PARAGRAPH_END';
    const STRUCTURE_INTERNAL_LINK_START = 'STRUCTURE_INTERNAL_LINK_START';
    const STRUCTURE_INTERNAL_LINK_END = 'STRUCTURE_INTERNAL_LINK_END';
    const STRUCTURE_STRONG = 'STRUCTURE_STRONG';
    const STRUCTURE_ACCENT = 'STRUCTURE_ACCENT';
    const STRUCTURE_ITALIC = 'STRUCTURE_ITALIC';
    const STRUCTURE_EXTERNAL_LINK_START = 'STRUCTURE_EXTERNAL_LINK_START';
    const STRUCTURE_EXTERNAL_LINK_END = 'STRUCTURE_EXTERNAL_LINK_END';
    const STRUCTURE_LIST_ITEM = 'STRUCTURE_LIST_ITEM';
    const STRUCTURE_LIST_START = 'STRUCTURE_LIST_START';
    const STRUCTURE_LIST_END = 'STRUCTURE_LIST_END';
    const STRUCTURE_NEW_LINE = 'STRUCTURE_NEW_LINE';
    const STRUCTURE_BREADCRUMB = 'STRUCTURE_BREADCRUMB';
    const STRUCTURE_BREADCRUMB_SEPARATOR = 'STRUCTURE_BREADCRUMB_SEPARATOR';
    const STRUCTURE_LAZY_LOAD = 'STRUCTURE_LAZY_LOAD';
    const STRUCTURE_SEARCH_BOX = 'STRUCTURE_SEARCH_BOX';
    const STRUCTURE_INSTANCE_HEADER = 'STRUCTURE_INSTANCE_HEADER';
    const STRUCTURE_INSTANCE_SECTION = 'STRUCTURE_INSTANCE_SECTION';
    const STRUCTURE_CONFIG_BUTTON = 'STRUCTURE_CONFIG_BUTTON';
    const STRUCTURE_CONFIG_PANEL = 'STRUCTURE_CONFIG_PANEL';
    const STRUCTURE_BUTTON_TOGGLE = 'STRUCTURE_BUTTON_TOGGLE';
    const STRUCTURE_EDIT_BUTTON = 'STRUCTURE_EDIT_BUTTON';
    const STRUCTURE_EDIT_PANEL = 'STRUCTURE_EDIT_PANEL';
    const STRUCTURE_MOVE_BUTTON = 'STRUCTURE_MOVE_BUTTON';
    const STRUCTURE_MOVE_PANEL = 'STRUCTURE_MOVE_PANEL';
    const STRUCTURE_ERROR_MESSAGE = 'STRUCTURE_ERROR_MESSAGE';
    const STRUCTURE_MODAL = 'STRUCTURE_MODAL';
    const STRUCTURE_ADD_BUTTON = 'STRUCTURE_ADD_BUTTON';
    const STRUCTURE_ADD_PANEL = 'STRUCTURE_ADD_PANEL';
    const STRUCTURE_DEEP_LINK = 'STRUCTURE_DEEP_LINK';
    const STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD = 'STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD';
    const STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS = 'STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS';
    const STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD_NAME = 'STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD_NAME';
    const STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS_NAME = 'STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS_NAME';
    const STRUCTURE_ADMIN_TEXT_INPUT = 'STRUCTURE_ADMIN_TEXT_INPUT';
    const STRUCTURE_ADMIN_TEXT_AREA = 'STRUCTURE_ADMIN_TEXT_AREA';
    const STRUCTURE_ADMIN_CONFIG_BUTTON = 'STRUCTURE_ADMIN_CONFIG_BUTTON';
    const STRUCTURE_ADMIN_CHECKBOX = 'STRUCTURE_ADMIN_CHECKBOX';
    const STRUCTURE_ADMIN_COMBOBOX = 'STRUCTURE_ADMIN_COMBOBOX';
    const STRUCTURE_ADMIN_LISTBOX = 'STRUCTURE_ADMIN_LISTBOX';
    const STRUCTURE_ADMIN_LISTBOX_LSS = 'STRUCTURE_ADMIN_LISTBOX_LSS';
    const STRUCTURE_ADMIN_LISTBOX_OPTION = 'STRUCTURE_ADMIN_LISTBOX_OPTION';
    const STRUCTURE_ADMIN_SUB_ITEM = 'STRUCTURE_ADMIN_SUB_ITEM';
    const STRUCTURE_ADMIN_SECTION_HEADER = 'STRUCTURE_ADMIN_SECTION_HEADER';
    const STRUCTURE_ADMIN_ERROR_MESSAGE = 'STRUCTURE_ADMIN_ERROR_MESSAGE';
    const STRUCTURE_ADMIN_SECTION = 'STRUCTURE_ADMIN_SECTION';
    const STRUCTURE_ADMIN_SECTION_COLLAPSED = 'STRUCTURE_ADMIN_SECTION_COLLAPSED';
    const STRUCTURE_ADMIN_SECTION_ADD = 'STRUCTURE_ADMIN_SECTION_ADD';
    const STRUCTURE_ADMIN_SEPARATOR = 'STRUCTURE_ADMIN_SEPARATOR';
    const STRUCTURE_ADMIN_FILE_INPUT = 'STRUCTURE_ADMIN_FILE_INPUT';
    const STRUCTURE_ADMIN_UPLOAD = 'STRUCTURE_ADMIN_UPLOAD';
    const STRUCTURE_ADMIN_UPDATE_INPUT = 'STRUCTURE_ADMIN_UPDATE_INPUT';
    const STRUCTURE_ADMIN_UPDATE = 'STRUCTURE_ADMIN_UPDATE';
    const STRUCTURE_ADMIN_BUTTON = 'STRUCTURE_ADMIN_BUTTON';
    const STRUCTURE_ADMIN_MAIN_BUTTON = 'STRUCTURE_ADMIN_MAIN_BUTTON';
    const STRUCTURE_ADMIN_LINK_BUTTON = 'STRUCTURE_ADMIN_LINK_BUTTON';
    const STRUCTURE_ADMIN_MENU = 'STRUCTURE_ADMIN_MENU';
    const STRUCTURE_ADMIN_MENU_ITEM = 'STRUCTURE_ADMIN_MENU_ITEM';
    const STRUCTURE_ADMIN_BUTTON_GROUP = 'STRUCTURE_ADMIN_BUTTON_GROUP';
    const STRUCTURE_ADMIN_BUTTON_GROUP_ALT = 'STRUCTURE_ADMIN_BUTTON_GROUP_ALT';
    const STRUCTURE_BASIC = 'STRUCTURE_BASIC';
    const STRUCTURE_POSITION_INSERT = 'STRUCTURE_POSITION_INSERT';
    const STRUCTURE_INSERT_ARTICLE = 'STRUCTURE_INSERT_ARTICLE';
    const STRUCTURE_EMPTY_ITEM = 'STRUCTURE_EMPTY_ITEM';
    const STRUCTURE_MENU_ITEM = 'STRUCTURE_MENU_ITEM';
    const STRUCTURE_CONTENT_ITEM = 'STRUCTURE_CONTENT_ITEM';
    const STRUCTURE_BLOCKQUOTE = 'STRUCTURE_BLOCKQUOTE';
    const STRUCTURE_IMG_FULL_HIDDEN_SMALL = 'STRUCTURE_IMG_FULL_HIDDEN_SMALL';
    const STRUCTURE_SEARCH_SITE = 'STRUCTURE_SEARCH_SITE';
    const STRUCTURE_GLYPHICON = 'STRUCTURE_GLYPHICON';
    const STRUCTURE_IMG_CAPTION = 'STRUCTURE_IMG_CAPTION';
    const STRUCTURE_IMG = 'STRUCTURE_IMG';
    const STRUCTURE_TEXT = 'STRUCTURE_TEXT';
    const STRUCTURE_IMG_META = 'STRUCTURE_IMG_META';
    const STRUCTURE_TEXT_META = 'STRUCTURE_TEXT_META';
    const STRUCTURE_TITLE_H1 = 'STRUCTURE_TITLE_H1';
    const STRUCTURE_TITLE_H2 = 'STRUCTURE_TITLE_H2';
    const STRUCTURE_TITLE_H3 = 'STRUCTURE_TITLE_H3';
    const STRUCTURE_BANNER_TEXT = 'STRUCTURE_BANNER_TEXT';
    const STRUCTURE_HTML = 'STRUCTURE_HTML';
    const STRUCTURE_OBJECT_UNPUBLISHED_INDICATOR = 'STRUCTURE_OBJECT_UNPUBLISHED_INDICATOR';
    
    const LAYOUT_AD = 'LAYOUT_AD';
    const LAYOUT_AD_WIDE = 'LAYOUT_AD_WIDE';
    const LAYOUT_ACTION_BUTTON = 'LAYOUT_ACTION_BUTTON';
    const LAYOUT_ARTICLE_IMG_TITLE_TEXT = 'LAYOUT_ARTICLE_IMG_TITLE_TEXT';
    const LAYOUT_ARTICLE_IMG_TITLE_TEXT_WIDE = 'LAYOUT_ARTICLE_IMG_TITLE_TEXT_WIDE';
    const LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT = 'LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT';
    const LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT_WIDE = 'LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT_WIDE';
    const LAYOUT_ARTICLE_IMG_LEFT_SMALL_TEXT_RIGHT_WIDE = 'LAYOUT_ARTICLE_IMG_LEFT_SMALL_TEXT_RIGHT_WIDE';
    const LAYOUT_ARTICLE_TITLE_IMG_TEXT = 'LAYOUT_ARTICLE_TITLE_IMG_TEXT';
    const LAYOUT_ARTICLE_TITLE_TEXT = 'LAYOUT_ARTICLE_TITLE_TEXT';
    const LAYOUT_ARTICLE_TITLE_TEXT_WIDE = 'LAYOUT_ARTICLE_TITLE_TEXT_WIDE';
    const LAYOUT_ARTICLE_TITLE_TEXT_LEFT_IMG_RIGHT = 'LAYOUT_ARTICLE_TITLE_TEXT_LEFT_IMG_RIGHT';
    const LAYOUT_ARTICLE_TITLE_TEXT_FULL = 'LAYOUT_ARTICLE_TITLE_TEXT_FULL';
    const LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK = 'LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK';
    const LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_EQUAL = 'LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_EQUAL';
    const LAYOUT_ARTICLE_TITLE_BLOCK_LARGE_BLOCK = 'LAYOUT_ARTICLE_TITLE_BLOCK_LARGE_BLOCK';
    const LAYOUT_ARTICLE_TITLE_BLOCK_OVERLAY_BLOCK = 'LAYOUT_ARTICLE_TITLE_BLOCK_OVERLAY_BLOCK';
    const LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_LARGE = 'LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_LARGE';
    const LAYOUT_ARTICLE_BLOCK_BLOCK = 'LAYOUT_ARTICLE_BLOCK_BLOCK';
    const LAYOUT_ARTICLE_BLOCK_IMG_BLOCK = 'LAYOUT_ARTICLE_BLOCK_IMG_BLOCK';
    const LAYOUT_ARTICLE_BLOCK_BLOCK_IMG = 'LAYOUT_ARTICLE_BLOCK_BLOCK_IMG';
    const LAYOUT_ARTICLE_BLOCK_BLOCK_WIDE = 'LAYOUT_ARTICLE_BLOCK_BLOCK_WIDE';
    const LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK = 'LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK';
    const LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK_WIDE = 'LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK_WIDE';
    const LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK = 'LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK';
    const LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK_WIDE = 'LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK_WIDE';
    const LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY = 'LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY';
    const LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY_WIDE = 'LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY_WIDE';
    const LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE = 'LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE';
    const LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE_WIDE = 'LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE_WIDE';
    const LAYOUT_BANNER = 'LAYOUT_BANNER';
    const LAYOUT_KEYWORD = 'LAYOUT_KEYWORD';
    const LAYOUT_IN_TEXT_IMG = 'LAYOUT_IN_TEXT_IMG';
    const LAYOUT_IN_TEXT_IMG_SMALL = 'LAYOUT_IN_TEXT_IMG_SMALL';
    const LAYOUT_IMG_TEXT = 'LAYOUT_IMG_TEXT';
    const LAYOUT_IMG_COLUMN_TEXT = 'LAYOUT_IMG_COLUMN_TEXT';
    const LAYOUT_IMG_TEXT_COLUMN = 'LAYOUT_IMG_TEXT_COLUMN';
    const LAYOUT_IMG_TEXT_SNIPPET = 'LAYOUT_IMG_TEXT_SNIPPET';
    const LAYOUT_IMG_CLICKABLE = 'LAYOUT_IMG_CLICKABLE';
    const LAYOUT_IMG_CLICKABLE_FULL = 'LAYOUT_IMG_CLICKABLE_FULL';
    const LAYOUT_IMG_LANDSCAPE = 'LAYOUT_IMG_LANDSCAPE';
    const LAYOUT_IMG_CAPTION = 'LAYOUT_IMG_CAPTION';
    const LAYOUT_IMG_PORTRAIT = 'LAYOUT_IMG_PORTRAIT';
    const LAYOUT_IMG_THUMBNAIL = 'LAYOUT_IMG_THUMBNAIL';
    const LAYOUT_IMG_FULL = 'LAYOUT_IMG_FULL';
    const LAYOUT_IMG_FULL_HIDDEN_XS = 'LAYOUT_IMG_FULL_HIDDEN_XS';
    const LAYOUT_IMG_NO_CAPTION = 'LAYOUT_IMG_NO_CAPTION';
    const LAYOUT_BLOCK = 'LAYOUT_BLOCK';
    const LAYOUT_CAROUSEL = 'LAYOUT_CAROUSEL';
    const LAYOUT_CAROUSELITEM = 'LAYOUT_CAROUSELITEM';
    const LAYOUT_CAROUSELITEM_ACTIVE = 'LAYOUT_CAROUSELITEM_ACTIVE';
    const LAYOUT_CONTENT_NO_BUTTONS = 'LAYOUT_CONTENT_NO_BUTTONS';
    const LAYOUT_GLYPHICON = 'LAYOUT_GLYPHICON';
    const LAYOUT_PAGEPART = 'LAYOUT_PAGEPART';
    const LAYOUT_COLUMN_1 = 'LAYOUT_COLUMN_1';
    const LAYOUT_COLUMN_10 = 'LAYOUT_COLUMN_10';
    const LAYOUT_COLUMN_11 = 'LAYOUT_COLUMN_11';
    const LAYOUT_COLUMN_12 = 'LAYOUT_COLUMN_12';
    const LAYOUT_COLUMN_12_NO_BUTTONS = 'LAYOUT_COLUMN_12_NO_BUTTONS';
    const LAYOUT_COLUMN_2 = 'LAYOUT_COLUMN_2';
    const LAYOUT_COLUMN_3 = 'LAYOUT_COLUMN_3';
    const LAYOUT_COLUMN_3_SM_6 = 'LAYOUT_COLUMN_3_SM_6';
    const LAYOUT_COLUMN_4 = 'LAYOUT_COLUMN_4';
    const LAYOUT_COLUMN_5 = 'LAYOUT_COLUMN_5';
    const LAYOUT_COLUMN_6 = 'LAYOUT_COLUMN_6';
    const LAYOUT_COLUMN_7 = 'LAYOUT_COLUMN_7';
    const LAYOUT_COLUMN_8 = 'LAYOUT_COLUMN_8';
    const LAYOUT_COLUMN_8_SM_12 = 'LAYOUT_COLUMN_8_SM_12';
    const LAYOUT_COLUMN_9 = 'LAYOUT_COLUMN_9';
    const LAYOUT_MENU_LIST = 'LAYOUT_MENU_LIST';
    const LAYOUT_NAVPILLS = 'LAYOUT_NAVPILLS';
    const LAYOUT_NAVTOP_WIDE = 'LAYOUT_NAVTOP_WIDE';
    const LAYOUT_NAVTOP = 'LAYOUT_NAVTOP';
    const LAYOUT_NAVTOP_FIXED = 'LAYOUT_NAVTOP_FIXED';
    const LAYOUT_INSTANCE = 'LAYOUT_INSTANCE';
    const LAYOUT_PAGE = 'LAYOUT_PAGE';
    const LAYOUT_PAGESECTION = 'LAYOUT_PAGESECTION';
    const LAYOUT_SITEROOT = 'LAYOUT_SITEROOT';
    const LAYOUT_SITECONTENT = 'LAYOUT_SITECONTENT';
    const LAYOUT_SUBNAV = 'LAYOUT_SUBNAV';
    const LAYOUT_TEXT = 'LAYOUT_TEXT';
    const LAYOUT_LINE = 'LAYOUT_LINE';
    const LAYOUT_TITLE = 'LAYOUT_TITLE';
    const LAYOUT_WHITESPACE = 'LAYOUT_WHITESPACE';
    const LAYOUT_FORM = 'LAYOUT_FORM';
    const LAYOUT_FORM_EMAIL = 'LAYOUT_FORM_EMAIL';
    const LAYOUT_FORM_EMAIL_REQUIRED = 'LAYOUT_FORM_EMAIL_REQUIRED';
    const LAYOUT_FORM_TEXT = 'LAYOUT_FORM_TEXT';
    const LAYOUT_FORM_TEXT_REQUIRED = 'LAYOUT_FORM_TEXT_REQUIRED';
    const LAYOUT_FORM_TEXTAREA = 'LAYOUT_FORM_TEXTAREA';
    const LAYOUT_FORM_TEXTAREA_REQUIRED = 'LAYOUT_FORM_TEXTAREA_REQUIRED';
    const LAYOUT_FORM_PASSWORD = 'LAYOUT_FORM_PASSWORD';
    const LAYOUT_FORM_PASSWORD_REQUIRED = 'LAYOUT_FORM_PASSWORD_REQUIRED';
    const LAYOUT_FORM_CHECKBOX = 'LAYOUT_FORM_CHECKBOX';
    const LAYOUT_FORM_DATE = 'LAYOUT_FORM_DATE';
    const LAYOUT_FORM_DATE_REQUIRED = 'LAYOUT_FORM_DATE_REQUIRED';
    const LAYOUT_FORM_NUMBER = 'LAYOUT_FORM_NUMBER';
    const LAYOUT_FORM_NUMBER_REQUIRED = 'LAYOUT_FORM_NUMBER_REQUIRED';
    const LAYOUT_FORM_URL = 'LAYOUT_FORM_URL';
    const LAYOUT_FORM_URL_REQUIRED = 'LAYOUT_FORM_URL_REQUIRED';
    const LAYOUT_FORM_SUBMIT = 'LAYOUT_FORM_SUBMIT';
    const LAYOUT_FORM_SELECT = 'LAYOUT_FORM_SELECT';
    const LAYOUT_FORM_SELECT_OPTION = 'LAYOUT_FORM_SELECT_OPTION';
    const LAYOUT_FORM_SELECT_OPTION_SELECTED = 'LAYOUT_FORM_SELECT_OPTION_SELECTED';
    
    const STYLE_ACTION_BUTTON = 'STYLE_ACTION_BUTTON';
    const STYLE_AD_HORIZONTAL = 'STYLE_AD_HORIZONTAL';
    const STYLE_AD_VERTICAL = 'STYLE_AD_VERTICAL';
    const STYLE_ARTICLE_TITLE_IMG_TEXT = 'STYLE_ARTICLE_TITLE_IMG_TEXT';
    const STYLE_TITLE_IMG_TEXT_MARGIN = 'STYLE_TITLE_IMG_TEXT_MARGIN';
    const STYLE_ARTICLE_TITLE_TEXT = 'STYLE_ARTICLE_TITLE_TEXT';
    const STYLE_ARTICLE_TITLE_TEXT_BGLIGHT = 'STYLE_ARTICLE_TITLE_TEXT_BGLIGHT';
    const STYLE_ARTICLE_TITLE_BLOCK_BLOCK = 'STYLE_ARTICLE_TITLE_BLOCK_BLOCK';
    const STYLE_ARTICLE_TITLE_BLOCK_BLOCK_MARGIN = 'STYLE_ARTICLE_TITLE_BLOCK_BLOCK_MARGIN';
    const STYLE_ARTICLE_BLOCK_BLOCK = 'STYLE_ARTICLE_BLOCK_BLOCK';
    const STYLE_ARTICLE_BLOCK_BLOCK_COMPACT = 'STYLE_ARTICLE_BLOCK_BLOCK_COMPACT';
    const STYLE_ARTICLE_BLOCK_BLOCK_NO_BG = 'STYLE_ARTICLE_BLOCK_BLOCK_NO_BG';
    const STYLE_BANNER = 'STYLE_BANNER';
    const STYLE_BANNER_DARK = 'STYLE_BANNER_DARK';
    const STYLE_BANNER_TEXT = 'STYLE_BANNER_TEXT';
    const STYLE_BANNER_TEXT_DARK = 'STYLE_BANNER_TEXT_DARK';
    const STYLE_BANNER_TEXT_ACCENT = 'STYLE_BANNER_TEXT_ACCENT';
    const STYLE_IMG = 'STYLE_IMG';
    const STYLE_IMG_TEXT = 'STYLE_IMG_TEXT';
    const STYLE_IMG_TEXT_SNIPPET = 'STYLE_IMG_TEXT_SNIPPET';
    const STYLE_IMG_CLICKABLE = 'STYLE_IMG_CLICKABLE';
    const STYLE_IMG_CLICKABLE_CENTER = 'STYLE_IMG_CLICKABLE_CENTER';
    const STYLE_IMG_CLICKABLE_CENTER_FULL = 'STYLE_IMG_CLICKABLE_CENTER_FULL';
    const STYLE_IMG_CLICKABLE_FULL = 'STYLE_IMG_CLICKABLE_FULL';
    const STYLE_IMG_LEFT = 'STYLE_IMG_LEFT';
    const STYLE_IMG_MARGIN_TOP = 'STYLE_IMG_MARGIN_TOP';
    const STYLE_IMG_BORDER = 'STYLE_IMG_BORDER';
    const STYLE_IMG_MARGIN_NEGATIVE = 'STYLE_IMG_MARGIN_NEGATIVE';
    const STYLE_IMG_CAPTION = 'STYLE_IMG_CAPTION';
    const STYLE_IMG_RIGHT = 'STYLE_IMG_RIGHT';
    const STYLE_IMG_CENTER= 'STYLE_IMG_CENTER';
    const STYLE_IMG_THUMBNAIL = 'STYLE_IMG_THUMBNAIL';
    const STYLE_IMG_FULL = 'STYLE_IMG_FULL';
    const STYLE_GLYPHICON = 'STYLE_GLYPHICON';
    const STYLE_PAGEPART_RIGHT = 'STYLE_PAGEPART_RIGHT';
    const STYLE_COLUMN = 'STYLE_COLUMN';
    const STYLE_COLUMN_BLOCK_ACCENT = 'STYLE_COLUMN_BLOCK_ACCENT';
    const STYLE_COLUMN_BLOCK_DARK = 'STYLE_COLUMN_BLOCK_DARK';
    const STYLE_COLUMN_BLOCK_LIGHT = 'STYLE_COLUMN_BLOCK_LIGHT';
    const STYLE_COLUMN_THREE_COLUMNS = 'STYLE_COLUMN_THREE_COLUMNS';
    const STYLE_COLUMN_CENTER = 'STYLE_COLUMN_CENTER';
    const STYLE_COLUMN_CENTER_SMALL = 'STYLE_COLUMN_CENTER_SMALL';
    const STYLE_COLUMN_TWO_THREE_COLUMNS = 'STYLE_COLUMN_TWO_THREE_COLUMNS';
    const STYLE_COLUMN_TWO_COLUMNS = 'STYLE_COLUMN_TWO_COLUMNS';
    const STYLE_COLUMN_FRONT = 'STYLE_COLUMN_FRONT';
    const STYLE_MENU_ITEM = 'STYLE_MENU_ITEM';
    const STYLE_MENU_LIST = 'STYLE_MENU_LIST';
    const STYLE_NAV_AND_CONTENT = 'STYLE_NAV_AND_CONTENT';
    const STYLE_OBJECT_DEFAULT = 'STYLE_OBJECT_DEFAULT';
    const STYLE_INSTANCE = 'STYLE_INSTANCE';
    const STYLE_INSTANCE_NO_BG = 'STYLE_INSTANCE_NO_BG';
    const STYLE_INSTANCE_LIGHT = 'STYLE_INSTANCE_LIGHT';
    const STYLE_PAGE = 'STYLE_PAGE';
    const STYLE_PAGESECTION = 'STYLE_PAGESECTION';
    const STYLE_PAGESECTION_DARK= 'STYLE_PAGESECTION_DARK';
    const STYLE_PAGESECTION_COMPACT = 'STYLE_PAGESECTION_COMPACT';
    const STYLE_PAGESECTION_MARGIN = 'STYLE_PAGESECTION_MARGIN';
    const STYLE_POSITION_DEFAULT = 'STYLE_POSITION_DEFAULT';
    const STYLE_SITE = 'STYLE_SITE';
    const STYLE_TEXT_THREE_COLUMNS = 'STYLE_TEXT_THREE_COLUMNS';
    const STYLE_TEXT_EXTRA_LARGE = 'STYLE_TEXT_EXTRA_LARGE';
    const STYLE_TEXT_LARGE = 'STYLE_TEXT_LARGE';
    const STYLE_TEXT_LARGE_ALT = 'STYLE_TEXT_LARGE_ALT';
    const STYLE_TEXT_DARK_BG = 'STYLE_TEXT_DARK_BG';
    const STYLE_TEXT_LIGHT_BG = 'STYLE_TEXT_LIGHT_BG';
    const STYLE_TEXT_TWO_COLUMNS = 'STYLE_TEXT_TWO_COLUMNS';
    const STYLE_LINE = 'STYLE_LINE';
    const STYLE_LINE_LIGHT = 'STYLE_LINE_LIGHT';
    const STYLE_TITLE = 'STYLE_TITLE';
    const STYLE_TITLE_ARTICLE = 'STYLE_TITLE_ARTICLE';
    const STYLE_TITLE_DARK = 'STYLE_TITLE_DARK';
    const STYLE_TITLE_LEFT = 'STYLE_TITLE_LEFT';
    const STYLE_TITLE_MARGIN = 'STYLE_TITLE_MARGIN';
    const STYLE_TITLE_RIGHT = 'STYLE_TITLE_RIGHT';
    const STYLE_WHITESPACE = 'STYLE_WHITESPACE';
    const STYLE_WHITESPACE_LARGE = 'STYLE_WHITESPACE_LARGE';
    consT STYLE_FORM = 'STYLE_FORM';
    
    const SET_AD = 'SET_AD';
    const SET_ARTICLE_TITLE_IMG_TEXT = 'SET_ARTICLE_TITLE_IMG_TEXT';
    const SET_ARTICLE_TITLE_TEXT = 'SET_ARTICLE_TITLE_TEXT';
    const SET_ARTICLE_TITLE_BLOCK_BLOCK = 'SET_ARTICLE_TITLE_BLOCK_BLOCK';
    const SET_ARTICLE_BLOCK_BLOCK = 'SET_ARTICLE_BLOCK_BLOCK';
    const SET_BANNER = 'SET_BANNER';
    const SET_IMG = 'SET_IMG';
    const SET_IMG_TEXT = 'SET_IMG_TEXT';
    const SET_IMG_TEXT_SNIPPET = 'SET_IMG_TEXT_SNIPPET';
    const SET_IMG_CLICKABLE = 'SET_IMG_CLICKABLE';
    const SET_IMG_CAPTION = 'SET_IMG_CAPTION';
    const SET_CAROUSEL = 'SET_CAROUSEL';
    const SET_CAROUSELITEM = 'SET_CAROUSELITEM';
    const SET_GLYPHICON = 'SET_GLYPHICON';
    const SET_PAGEPART = 'SET_PAGEPART';
    const SET_COLUMN = 'SET_COLUMN';
    const SET_MENU_LIST = 'SET_MENU_LIST';
    const SET_NAV_AND_CONTENT = 'SET_NAV_AND_CONTENT';
    const SET_INSTANCE = 'SET_INSTANCE';
    const SET_PAGE = 'SET_PAGE';
    const SET_PAGESECTION = 'SET_PAGESECTION';
    const SET_SITE = 'SET_SITE';
    const SET_SITECONTENT = 'SET_SITECONTENT';
    const SET_SUBNAV = 'SET_SUBNAV';
    const SET_TEXT = 'SET_TEXT';
    const SET_LINE = 'SET_LINE';
    const SET_TITLE = 'SET_TITLE';
    const SET_WHITESPACE = 'SET_WHITESPACE';
    const SET_DEFAULT = 'SET_DEFAULT';
    const SET_FORM = 'SET_FORM';
    const SET_FORM_FIELD = 'SET_FORM_FIELD';
    const SET_FORM_FIELD_SELECT = 'SET_FORM_FIELD_SELECT';
    const SET_FORM_FIELD_SELECT_OPTION = 'SET_FORM_FIELD_SELECT_OPTION';

    const TEMPLATE_AD = 'TEMPLATE_AD';
    const TEMPLATE_ACTION_BUTTON = 'TEMPLATE_ACTION_BUTTON';
    const TEMPLATE_ARTICLE_TITLE_IMG_TEXT = 'TEMPLATE_ARTICLE_TITLE_IMG_TEXT';
    const TEMPLATE_ARTICLE_TITLE_TEXT = 'TEMPLATE_ARTICLE_TITLE_TEXT';
    const TEMPLATE_ARTICLE_TITLE_BLOCK_BLOCK = 'TEMPLATE_ARTICLE_TITLE_BLOCK_BLOCK';
    const TEMPLATE_ARTICLE_BLOCK_BLOCK = 'TEMPLATE_ARTICLE_BLOCK_BLOCK';
    const TEMPLATE_BANNER = 'TEMPLATE_BANNER';
    const TEMPLATE_IMG = 'TEMPLATE_IMG';
    const TEMPLATE_IMG_META = 'TEMPLATE_IMG_META';
    const TEMPLATE_IMG_TEXT = 'TEMPLATE_IMG_TEXT';
    const TEMPLATE_IMG_TEXT_SNIPPET = 'TEMPLATE_IMG_TEXT_SNIPPET';
    const TEMPLATE_IMG_CLICKABLE = 'TEMPLATE_IMG_CLICKABLE';
    const TEMPLATE_BLOCK = 'TEMPLATE_BLOCK';
    const TEMPLATE_CAROUSEL = 'TEMPLATE_CAROUSEL';
    const TEMPLATE_CAROUSELITEM = 'TEMPLATE_CAROUSELITEM';
    const TEMPLATE_FOOTER = 'TEMPLATE_FOOTER';
    const TEMPLATE_GLYPHICON = 'TEMPLATE_GLYPHICON';
    const TEMPLATE_HEADER = 'TEMPLATE_HEADER';
    const TEMPLATE_PAGEPART = 'TEMPLATE_PAGEPART';
    const TEMPLATE_COLUMN = 'TEMPLATE_COLUMN';
    const TEMPLATE_MENU_LIST = 'TEMPLATE_MENU_LIST';
    const TEMPLATE_NAV_AND_CONTENT = 'TEMPLATE_NAV_AND_CONTENT';
    const TEMPLATE_INSTANCE = 'TEMPLATE_INSTANCE';
    const TEMPLATE_PAGE = 'TEMPLATE_PAGE';
    const TEMPLATE_PAGESECTION = 'TEMPLATE_PAGESECTION';
    const TEMPLATE_SITE = 'TEMPLATE_SITE';
    const TEMPLATE_SUBNAV = 'TEMPLATE_SUBNAV';
    const TEMPLATE_TEXT = 'TEMPLATE_TEXT';
    const TEMPLATE_TEXT_META = 'TEMPLATE_TEXT_META';
    const TEMPLATE_LINE = 'TEMPLATE_LINE';
    const TEMPLATE_WHITESPACE = 'TEMPLATE_WHITESPACE';
    const TEMPLATE_DEFAULT = 'TEMPLATE_DEFAULT';
    const TEMPLATE_KEYWORD = 'TEMPLATE_KEYWORD';
    const TEMPLATE_FORM = 'TEMPLATE_FORM';
    const TEMPLATE_FORM_FIELD = 'TEMPLATE_FORM_FIELD';
    
}