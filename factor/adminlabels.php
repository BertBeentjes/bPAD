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
 * The labels used in the admin functionality
 *
 * @since 0.4.0
 */
class AdminLabels {
    const ADMIN_OBJECT_NAME = 'ADMIN_OBJECT_NAME';
    const ADMIN_OBJECT_TEMPLATE_NAME = 'ADMIN_OBJECT_TEMPLATE_NAME';
    const ADMIN_OBJECT_AUTHORIZATION = 'ADMIN_OBJECT_AUTHORIZATION';
    const ADMIN_OBJECT_INTERNAL_LINK = 'ADMIN_OBJECT_INTERNAL_LINK';
    const ADMIN_OBJECT_DEEP_LINK = 'ADMIN_OBJECT_DEEP_LINK';
    const ADMIN_OBJECT_ACTIVE = 'ADMIN_OBJECT_ACTIVE';
    const ADMIN_OBJECT_USERGROUP_ROLE = 'ADMIN_OBJECT_USERGROUP_ROLE';
    const ADMIN_OBJECT_USERGROUP_ROLE_INHERIT = 'ADMIN_OBJECT_USERGROUP_ROLE_INHERIT';
    const ADMIN_OBJECT_SET = 'ADMIN_OBJECT_SET';
    const ADMIN_OBJECT_VERSION_LAYOUT = 'ADMIN_OBJECT_VERSION_LAYOUT';
    const ADMIN_OBJECT_VERSION_STYLE = 'ADMIN_OBJECT_VERSION_STYLE';
    const ADMIN_OBJECT_VERSION_ARGUMENT_DEFAULT = 'ADMIN_OBJECT_VERSION_ARGUMENT_DEFAULT';
    const ADMIN_OBJECT_VERSION_ARGUMENT = 'ADMIN_OBJECT_VERSION_ARGUMENT';
    const ADMIN_OBJECT_VERSION_INHERIT_LAYOUT = 'ADMIN_OBJECT_VERSION_INHERIT_LAYOUT';
    const ADMIN_OBJECT_VERSION_INHERIT_STYLE = 'ADMIN_OBJECT_VERSION_INHERIT_STYLE';
    const ADMIN_OBJECT_VERSION_TEMPLATE = 'ADMIN_OBJECT_VERSION_TEMPLATE';
    const ADMIN_POSITION_STRUCTURE = 'ADMIN_POSITION_STRUCTURE';
    const ADMIN_POSITION_STYLE = 'ADMIN_POSITION_STYLE';
    const ADMIN_POSITION_INHERIT_STRUCTURE = 'ADMIN_POSITION_INHERIT_STRUCTURE';
    const ADMIN_POSITION_INHERIT_STYLE = 'ADMIN_POSITION_INHERIT_STYLE';
    const ADMIN_POSITION_REMOVE = 'ADMIN_POSITION_REMOVE';
    const ADMIN_POSITION_ADD_CONTENT_ITEM = 'ADMIN_POSITION_ADD_CONTENT_ITEM';
    const ADMIN_POSITION_ADD_OBJECT = 'ADMIN_POSITION_ADD_OBJECT';
    const ADMIN_POSITION_ADD_INSTANCE = 'ADMIN_POSITION_ADD_INSTANCE';
    const ADMIN_POSITION_ADD_REFERRAL = 'ADMIN_POSITION_ADD_REFERRAL';
    const ADMIN_POSITION_CONTENT_ITEM_NAME = 'ADMIN_POSITION_CONTENT_ITEM_NAME';
    const ADMIN_POSITION_CONTENT_ITEM_INPUT_TYPE = 'ADMIN_POSITION_CONTENT_ITEM_INPUT_TYPE';
    const ADMIN_POSITION_CONTENT_ITEM_BODY = 'ADMIN_POSITION_CONTENT_ITEM_BODY';
    const ADMIN_POSITION_CONTENT_ITEM_UPLOAD = 'ADMIN_POSITION_CONTENT_ITEM_UPLOAD';
    const ADMIN_POSITION_CONTENT_ITEM_CURRENT_VALUE = 'ADMIN_POSITION_CONTENT_ITEM_CURRENT_VALUE';
    const ADMIN_POSITION_INSTANCE_OBJECT = 'ADMIN_POSITION_INSTANCE_OBJECT';
    const ADMIN_POSITION_INSTANCE_OBJECT_DEFAULT = 'ADMIN_POSITION_INSTANCE_OBJECT_DEFAULT';
    const ADMIN_POSITION_INSTANCE_TEMPLATE = 'ADMIN_POSITION_INSTANCE_TEMPLATE';
    const ADMIN_POSITION_INSTANCE_TEMPLATE_DEFAULT = 'ADMIN_POSITION_INSTANCE_TEMPLATE_DEFAULT';
    const ADMIN_POSITION_INSTANCE_LISTWORDS = 'ADMIN_POSITION_INSTANCE_LISTWORDS';
    const ADMIN_POSITION_INSTANCE_SEARCHWORDS = 'ADMIN_POSITION_INSTANCE_SEARCHWORDS';
    const ADMIN_POSITION_INSTANCE_PARENT = 'ADMIN_POSITION_INSTANCE_PARENT';
    const ADMIN_POSITION_INSTANCE_ACTIVE_ITEMS = 'ADMIN_POSITION_INSTANCE_ACTIVE_ITEMS';
    const ADMIN_POSITION_INSTANCE_MAX_ITEMS = 'ADMIN_POSITION_INSTANCE_MAX_ITEMS';
    const ADMIN_POSITION_INSTANCE_FILL_ON_LOAD = 'ADMIN_POSITION_INSTANCE_FILL_ON_LOAD';
    const ADMIN_POSITION_INSTANCE_USE_INSTANCE_CONTEXT = 'ADMIN_POSITION_INSTANCE_USE_INSTANCE_CONTEXT';
    const ADMIN_POSITION_INSTANCE_ORDER_BY = 'ADMIN_POSITION_INSTANCE_ORDER_BY';
    const ADMIN_POSITION_INSTANCE_GROUP_BY = 'ADMIN_POSITION_INSTANCE_GROUP_BY';
    const ADMIN_POSITION_REFERRAL_ARGUMENT = 'ADMIN_POSITION_REFERRAL_ARGUMENT';
    const ADMIN_POSITION_REFERRAL_ORDER_BY = 'ADMIN_POSITION_REFERRAL_ORDER_BY';
    const ADMIN_POSITION_REFERRAL_NUMBER_OF_ITEMS = 'ADMIN_POSITION_REFERRAL_NUMBER_OF_ITEMS';
    const ADMIN_BUTTON_MOVE = 'ADMIN_BUTTON_MOVE';
    const ADMIN_BUTTON_MOVE_UP = 'ADMIN_BUTTON_MOVE_UP';
    const ADMIN_BUTTON_MOVE_DOWN = 'ADMIN_BUTTON_MOVE_DOWN';
    const ADMIN_BUTTON_PUBLISH = 'ADMIN_BUTTON_PUBLISH';
    const ADMIN_BUTTON_PUBLISH_NEW = 'ADMIN_BUTTON_PUBLISH_NEW';
    const ADMIN_BUTTON_CANCEL = 'ADMIN_BUTTON_CANCEL';
    const ADMIN_BUTTON_TO_RECYCLE_BIN = 'ADMIN_BUTTON_TO_RECYCLE_BIN';
    const ADMIN_BUTTON_FROM_RECYCLE_BIN = 'ADMIN_BUTTON_FROM_RECYCLE_BIN';
    const ADMIN_BUTTON_CLOSE = 'ADMIN_BUTTON_CLOSE';
    const ADMIN_BUTTON_UNDO = 'ADMIN_BUTTON_UNDO';
    const ADMIN_BUTTON_KEEP = 'ADMIN_BUTTON_KEEP';
    const ADMIN_BUTTON_CONFIG_MAIN = 'ADMIN_BUTTON_CONFIG_MAIN';
    const ADMIN_BUTTON_CONFIG_LAYOUTS = 'ADMIN_BUTTON_CONFIG_LAYOUTS';
    const ADMIN_BUTTON_CONFIG_STYLES = 'ADMIN_BUTTON_CONFIG_STYLES';
    const ADMIN_BUTTON_CONFIG_STYLEPARAMS = 'ADMIN_BUTTON_CONFIG_STYLE_PARAMS';
    const ADMIN_BUTTON_CONFIG_STRUCTURES = 'ADMIN_BUTTON_CONFIG_STRUCTURES';
    const ADMIN_BUTTON_CONFIG_TEMPLATES = 'ADMIN_BUTTON_CONFIG_TEMPLATES';
    const ADMIN_BUTTON_CONFIG_USERS = 'ADMIN_BUTTON_CONFIG_USERS';
    const ADMIN_BUTTON_CONFIG_USERGROUPS = 'ADMIN_BUTTON_CONFIG_USERGROUPS';
    const ADMIN_BUTTON_CONFIG_ROLES = 'ADMIN_BUTTON_CONFIG_ROLES';
    const ADMIN_BUTTON_CONFIG_SETTINGS = 'ADMIN_BUTTON_CONFIG_SETTINGS';
    const ADMIN_BUTTON_CONFIG_INCLUDE_FILES = 'ADMIN_BUTTON_CONFIG_INCLUDE_FILES';
    const ADMIN_BUTTON_CONFIG_SNIPPETS = 'ADMIN_BUTTON_CONFIG_SNIPPETS';
    const ADMIN_BUTTON_CONFIG_SETS = 'ADMIN_BUTTON_CONFIG_SETS';
    const ADMIN_BUTTON_CONFIG_UPDATES = 'ADMIN_BUTTON_CONFIG_UPDATES';
    const ADMIN_BUTTON_CONFIG_FORM_HANDLERS = 'ADMIN_BUTTON_CONFIG_FORM_HANDLERS';
    const ADMIN_BUTTON_CONFIG_FORMS = 'ADMIN_BUTTON_CONFIG_FORMS';
    const ADMIN_BUTTON_CONFIG_PRODUCTS = 'ADMIN_BUTTON_CONFIG_PRODUCTS';
    const ADMIN_BUTTON_CONFIG_ORDERS = 'ADMIN_BUTTON_CONFIG_ORDERS';
    const ADMIN_BUTTON_REMOVE_FORM = 'ADMIN_BUTTON_REMOVE_FORM';
    const ADMIN_BUTTON_REMOVE_FORM_HANDLER = 'ADMIN_BUTTON_REMOVE_FORM_HANDLER';
    const ADMIN_BUTTON_REMOVE_PRODUCT = 'ADMIN_BUTTON_REMOVE_PRODUCT';
    const ADMIN_BUTTON_REMOVE_ORDER = 'ADMIN_BUTTON_REMOVE_ORDER';
    const ADMIN_BUTTON_REMOVE_LAYOUT = 'ADMIN_BUTTON_REMOVE_LAYOUT';
    const ADMIN_BUTTON_REMOVE_STYLE = 'ADMIN_BUTTON_REMOVE_STYLE';
    const ADMIN_BUTTON_REMOVE_STYLE_PARAM = 'ADMIN_BUTTON_REMOVE_STYLE_PARAM';
    const ADMIN_BUTTON_REMOVE_STRUCTURE = 'ADMIN_BUTTON_REMOVE_STRUCTURE';
    const ADMIN_BUTTON_REMOVE_LAYOUTVERSION = 'ADMIN_BUTTON_REMOVE_LAYOUTVERSION';
    const ADMIN_BUTTON_REMOVE_STYLEVERSION = 'ADMIN_BUTTON_REMOVE_STYLEVERSION';
    const ADMIN_BUTTON_REMOVE_STYLEPARAMVERSION = 'ADMIN_BUTTON_REMOVE_STYLEPARAMVERSION';
    const ADMIN_BUTTON_REMOVE_STRUCTUREVERSION = 'ADMIN_BUTTON_REMOVE_STRUCTUREVERSION';
    const ADMIN_BUTTON_REMOVE_TEMPLATE = 'ADMIN_BUTTON_REMOVE_TEMPLATE';
    const ADMIN_BUTTON_REMOVE_SET = 'ADMIN_BUTTON_REMOVE_SET';
    const ADMIN_BUTTON_REMOVE_USER = 'ADMIN_BUTTON_REMOVE_USER';
    const ADMIN_BUTTON_REMOVE_USERGROUP = 'ADMIN_BUTTON_REMOVE_USERGROUP';
    const ADMIN_BUTTON_REMOVE_ROLE = 'ADMIN_BUTTON_REMOVE_ROLE';
    const ADMIN_BUTTON_REMOVE_INCLUDE_FILE = 'ADMIN_BUTTON_REMOVE_INCLUDE_FILE';
    const ADMIN_BUTTON_REMOVE_SNIPPET = 'ADMIN_BUTTON_REMOVE_SNIPPET';
    const ADMIN_BUTTON_ADD_FORM_HANDLER = 'ADMIN_BUTTON_ADD_FORM_HANDLER';
    const ADMIN_BUTTON_ADD_PRODUCT = 'ADMIN_BUTTON_ADD_PRODUCT';
    const ADMIN_BUTTON_ADD_LAYOUT = 'ADMIN_BUTTON_ADD_LAYOUT';
    const ADMIN_BUTTON_ADD_STYLE = 'ADMIN_BUTTON_ADD_STYLE';
    const ADMIN_BUTTON_ADD_STYLE_PARAM = 'ADMIN_BUTTON_ADD_STYLE_PARAM';
    const ADMIN_BUTTON_ADD_STRUCTURE = 'ADMIN_BUTTON_ADD_STRUCTURE';
    const ADMIN_BUTTON_ADD_LAYOUTVERSION = 'ADMIN_BUTTON_ADD_LAYOUTVERSION';
    const ADMIN_BUTTON_ADD_STYLEVERSION = 'ADMIN_BUTTON_ADD_STYLEVERSION';
    const ADMIN_BUTTON_ADD_STYLEPARAMVERSION = 'ADMIN_BUTTON_ADD_STYLEPARAMVERSION';
    const ADMIN_BUTTON_ADD_STRUCTUREVERSION = 'ADMIN_BUTTON_ADD_STRUCTUREVERSION';
    const ADMIN_BUTTON_ADD_TEMPLATE = 'ADMIN_BUTTON_ADD_TEMPLATE';
    const ADMIN_BUTTON_ADD_SET = 'ADMIN_BUTTON_ADD_SET';
    const ADMIN_BUTTON_ADD_USER = 'ADMIN_BUTTON_ADD_USER';
    const ADMIN_BUTTON_ADD_USERGROUP = 'ADMIN_BUTTON_ADD_USERGROUP';
    const ADMIN_BUTTON_ADD_ROLE = 'ADMIN_BUTTON_ADD_ROLE';
    const ADMIN_BUTTON_ADD_SETTING = 'ADMIN_BUTTON_ADD_SETTING';
    const ADMIN_BUTTON_ADD_INCLUDE_FILE = 'ADMIN_BUTTON_ADD_INCLUDE_FILE';
    const ADMIN_BUTTON_ADD_SNIPPET = 'ADMIN_BUTTON_ADD_SNIPPET';
    const ADMIN_BUTTON_PUBLISH_LAYOUTVERSION = 'ADMIN_BUTTON_PUBLISH_LAYOUTVERSION';
    const ADMIN_BUTTON_PUBLISH_STYLEVERSION = 'ADMIN_BUTTON_PUBLISH_STYLEVERSION';
    const ADMIN_BUTTON_PUBLISH_STYLEPARAMVERSION = 'ADMIN_BUTTON_PUBLISH_STYLEPARAMVERSION';
    const ADMIN_BUTTON_PUBLISH_STRUCTUREVERSION = 'ADMIN_BUTTON_PUBLISH_STRUCTUREVERSION';
    const ADMIN_BUTTON_PUBLISH_TEMPLATE = 'ADMIN_BUTTON_PUBLISH_TEMPLATE';
    const ADMIN_BUTTON_PUBLISH_FILEINCLUDEVERSION = 'ADMIN_BUTTON_PUBLISH_FILEINCLUDEVERSION';
    const ADMIN_BUTTON_PUBLISH_SNIPPETVERSION = 'ADMIN_BUTTON_PUBLISH_SNIPPETVERSION';
    const ADMIN_BUTTON_CANCEL_LAYOUTVERSION = 'ADMIN_BUTTON_CANCEL_LAYOUTVERSION';
    const ADMIN_BUTTON_CANCEL_STYLEVERSION = 'ADMIN_BUTTON_CANCEL_STYLEVERSION';
    const ADMIN_BUTTON_CANCEL_STYLEPARAMVERSION = 'ADMIN_BUTTON_CANCEL_STYLEPARAMVERSION';
    const ADMIN_BUTTON_CANCEL_STRUCTUREVERSION = 'ADMIN_BUTTON_CANCEL_STRUCTUREVERSION';
    const ADMIN_BUTTON_CANCEL_TEMPLATE = 'ADMIN_BUTTON_CANCEL_TEMPLATE';
    const ADMIN_BUTTON_DOWNLOAD_UPDATE = 'ADMIN_BUTTON_DOWNLOAD_UPDATE';
    const ADMIN_BUTTON_UPLOAD_UPDATE = 'ADMIN_BUTTON_UPLOAD_UPDATE';
    const ADMIN_UPDATE_STATUS = 'ADMIN_UPDATE_STATUS';
    const ADMIN_UPDATE_STATUS_WAITING = 'ADMIN_UPDATE_STATUS_WAITING';
    const ADMIN_UPDATE_STATUS_SUCCESS = 'ADMIN_UPDATE_STATUS_SUCCESS';
    const ADMIN_UPDATE_STATUS_FAILED = 'ADMIN_UPDATE_STATUS_FAILED';
    const ADMIN_CONFIG_FORMS = 'ADMIN_CONFIG_FORMS';
    const ADMIN_FORM_NAME = 'ADMIN_FORM_NAME';
    const ADMIN_FORM_FORM = 'ADMIN_FORM_FORM';
    const ADMIN_FORM_FORM_HANDLER = 'ADMIN_FORM_FORM_HANDLER';
    const ADMIN_CONFIG_FORM_HANDLERS = 'ADMIN_CONFIG_FORM_HANDLERS';
    const ADMIN_FORM_HANDLER_NAME = 'ADMIN_FORM_HANDLER_NAME';
    const ADMIN_FORM_HANDLER_EMAIL_TO = 'ADMIN_FORM_HANDLER_EMAIL_TO';
    const ADMIN_FORM_HANDLER_EMAIL_BCC = 'ADMIN_FORM_HANDLER_EMAIL_BCC';
    const ADMIN_FORM_HANDLER_EMAIL_TEXT = 'ADMIN_FORM_HANDLER_EMAIL_TEXT';
    const ADMIN_FORM_HANDLER_EXIT_URL = 'ADMIN_FORM_HANDLER_EXIT_URL';
    const ADMIN_FORM_HANDLER_EMAIL_FROM = 'ADMIN_FORM_HANDLER_EMAIL_FROM';
    const ADMIN_FORM_HANDLER_EMAIL_REPLY_TO = 'ADMIN_FORM_HANDLER_EMAIL_REPLY_TO';
    const ADMIN_FORM_HANDLER_EMAIL_SUBJECT = 'ADMIN_FORM_HANDLER_EMAIL_SUBJECT';
    const ADMIN_CONFIG_PRODUCTS = 'ADMIN_CONFIG_PRODUCTS';
    const ADMIN_PRODUCT_NAME = 'ADMIN_PRODUCT_NAME';
    const ADMIN_PRODUCT_DESCRIPTION = 'ADMIN_PRODUCT_DESCRIPTION';
    const ADMIN_PRODUCT_CONFIRMATION_EMAIL = 'ADMIN_PRODUCT_CONFIRMATION_EMAIL';
    const ADMIN_PRODUCT_INVOICE = 'ADMIN_PRODUCT_INVOICE';
    const ADMIN_PRODUCT_ORDER_EXIT_URL = 'ADMIN_PRODUCT_ORDER_EXIT_URL';
    const ADMIN_PRODUCT_PRODUCT_PRICE = 'ADMIN_PRODUCT_PRODUCT_PRICE';
    const ADMIN_PRODUCT_VAT = 'ADMIN_PRODUCT_VAT';
    const ADMIN_PRODUCT_COST_SHIPPING = 'ADMIN_PRODUCT_COST_SHIPPING';
    const ADMIN_PRODUCT_COST_PACKAGING = 'ADMIN_PRODUCT_COST_PACKAGING';
    const ADMIN_PRODUCT_VAT_SHIPPING_PACKAGING = 'ADMIN_PRODUCT_VAT_SHIPPING_PACKAGING';
    const ADMIN_PRODUCT_TOTAL_PRICE = 'ADMIN_PRODUCT_TOTAL_PRICE';
    const ADMIN_CONFIG_ORDERS = 'ADMIN_CONFIG_ORDERS';
    const ADMIN_ORDER_NAME = 'ADMIN_ORDER_NAME';
    const ADMIN_ORDER_PRODUCT = 'ADMIN_ORDER_PRODUCT';
    const ADMIN_ORDER_FORM_STORAGE = 'ADMIN_ORDER_FORM_STORAGE';
    const ADMIN_ORDER_INVOICE_DATE = 'ADMIN_ORDER_INVOICE_DATE';
    const ADMIN_ORDER_INVOICE_YEAR = 'ADMIN_ORDER_INVOICE_YEAR';
    const ADMIN_ORDER_INVOICE_NUMBER = 'ADMIN_ORDER_INVOICE_NUMBER';
    const ADMIN_ORDER_UNIQUE_CODE = 'ADMIN_ORDER_UNIQUE_CODE';
    const ADMIN_ORDER_PAYMENT_TYPE = 'ADMIN_ORDER_PAYMENT_TYPE';
    const ADMIN_ORDER_TRANSACTION = 'ADMIN_ORDER_TRANSACTION';
    const ADMIN_ORDER_STATUS = 'ADMIN_ORDER_STATUS';
    const ADMIN_CONFIG_LAYOUTS = 'ADMIN_CONFIG_LAYOUTS';
    const ADMIN_LAYOUT_NAME = 'ADMIN_LAYOUT_NAME';
    const ADMIN_LAYOUT_SET = 'ADMIN_LAYOUT_SET';
    const ADMIN_LAYOUT_VERSION_BODY = 'ADMIN_LAYOUT_VERSION_BODY';
    const ADMIN_CONFIG_STRUCTURES = 'ADMIN_CONFIG_STRUCTURES';
    const ADMIN_STRUCTURE_NAME = 'ADMIN_STRUCTURE_NAME';
    const ADMIN_STRUCTURE_SET = 'ADMIN_STRUCTURE_SET';
    const ADMIN_STRUCTURE_VERSION_BODY = 'ADMIN_LAYOUT_VERSION_BODY';
    const ADMIN_CONFIG_STYLES = 'ADMIN_CONFIG_STYLES';
    const ADMIN_STYLE_NAME = 'ADMIN_STYLE_NAME';
    const ADMIN_STYLE_TYPE = 'ADMIN_STYLE_TYPE';
    const ADMIN_STYLE_CLASS_SUFFIX = 'ADMIN_STYLE_CLASS_SUFFIX';
    const ADMIN_STYLE_SET = 'ADMIN_STYLE_SET';
    const ADMIN_STYLE_VERSION_BODY = 'ADMIN_LAYOUT_VERSION_BODY';
    const ADMIN_CONFIG_STYLE_PARAMS = 'ADMIN_CONFIG_STYLE_PARAMS';
    const ADMIN_STYLE_PARAM_NAME = 'ADMIN_STYLE_PARAM_NAME';
    const ADMIN_CONFIG_TEMPLATES = 'ADMIN_CONFIG_TEMPLATES';
    const ADMIN_TEMPLATE_NAME = 'ADMIN_TEMPLATE_NAME';
    const ADMIN_TEMPLATE_DELETED = 'ADMIN_TEMPLATE_DELETED';
    const ADMIN_TEMPLATE_INSTANCE_ALLOWED = 'ADMIN_TEMPLATE_INSTANCE_ALLOWED';
    const ADMIN_TEMPLATE_SEARCHABLE = 'ADMIN_TEMPLATE_SEARCHABLE';
    const ADMIN_TEMPLATE_SET = 'ADMIN_TEMPLATE_SET';
    const ADMIN_TEMPLATE_STRUCTURE = 'ADMIN_TEMPLATE_STRUCTURE';
    const ADMIN_TEMPLATE_STYLE = 'ADMIN_TEMPLATE_STYLE';
    const ADMIN_CONFIG_SETS = 'ADMIN_CONFIG_SETS';
    const ADMIN_CONFIG_USERS = 'ADMIN_CONFIG_USERS';
    const ADMIN_CONFIG_USERGROUPS = 'ADMIN_CONFIG_USERGROUPS';
    const ADMIN_CONFIG_ROLES = 'ADMIN_CONFIG_ROLES';
    const ADMIN_CONFIG_SETTINGS = 'ADMIN_CONFIG_SETTINGS';
    const ADMIN_CONFIG_INCLUDE_FILES = 'ADMIN_CONFIG_INCLUDE_FILES';
    const ADMIN_CONFIG_SNIPPETS = 'ADMIN_CONFIG_SNIPPETS';
    const ADMIN_CONFIG_UPDATES = 'ADMIN_CONFIG_UPDATES';
    const ADMIN_SET_NAME = 'ADMIN_SET_NAME';
    const ADMIN_USER_NAME = 'ADMIN_USER_NAME';
    const ADMIN_USER_PASSWORD = 'ADMIN_USER_PASSWORD';
    const ADMIN_USER_FIRST_NAME = 'ADMIN_USER_FIRST_NAME';
    const ADMIN_USER_LAST_NAME = 'ADMIN_USER_LAST_NAME';
    const ADMIN_USER_LOGIN_COUNTER = 'ADMIN_USER_LOGIN_COUNTER';
    const ADMIN_USERGROUP_NAME = 'ADMIN_USERGROUP_NAME';
    const ADMIN_ROLE_NAME = 'ADMIN_ROLE_NAME';
    const ADMIN_SETTING_NAME = 'ADMIN_SETTING_NAME';
    const ADMIN_SETTING_VALUE = 'ADMIN_SETTING_VALUE';
    const ADMIN_INCLUDE_FILE_NAME = 'ADMIN_INCLUDE_FILE_NAME';
    const ADMIN_INCLUDE_FILE_MIME_TYPE = 'ADMIN_INCLUDE_FILE_MIME_TYPE';
    const ADMIN_INCLUDE_FILE_COMMENT = 'ADMIN_INCLUDE_FILE_COMMENT';
    const ADMIN_SNIPPET_NAME = 'ADMIN_SNIPPET_NAME';
    const ADMIN_SNIPPET_MIME_TYPE = 'ADMIN_SNIPPET_MIME_TYPE';
    const ADMIN_SNIPPET_CONTEXT_GROUP = 'ADMIN_SNIPPET_CONTEXT_GROUP';
    const ADMIN_PROCESSING = 'ADMIN_PROCESSING';

    const ADMIN_PERMISSIONS_MANAGE_CONTENT = 'ADMIN_PERMISSIONS_MANAGE_CONTENT';
    const ADMIN_PERMISSIONS_MANAGE_STYLE = 'ADMIN_PERMISSIONS_MANAGE_STYLE';
    const ADMIN_PERMISSIONS_MANAGE_STRUCTURE = 'ADMIN_PERMISSIONS_MANAGE_STRUCTURE';
    const ADMIN_PERMISSIONS_FLUSH_ARCHIVE = 'ADMIN_PERMISSIONS_FLUSH_ARCHIVE';
    const ADMIN_PERMISSIONS_VIEW_OBJECT = 'ADMIN_PERMISSIONS_VIEW_OBJECT';
    const ADMIN_PERMISSIONS_FRONTEND_EDIT = 'ADMIN_PERMISSIONS_FRONTEND_EDIT';
    const ADMIN_PERMISSIONS_UPLOAD_FILE = 'ADMIN_PERMISSIONS_UPLOAD_FILE';
    const ADMIN_PERMISSIONS_FRONTEND_CREATOR_EDIT = 'ADMIN_PERMISSIONS_FRONTEND_CREATOR_EDIT';
    const ADMIN_PERMISSIONS_FRONTEND_ADD = 'ADMIN_PERMISSIONS_FRONTEND_ADD';
    const ADMIN_PERMISSIONS_FRONTEND_CREATOR_DEACTIVATE = 'ADMIN_PERMISSIONS_FRONTEND_CREATOR_DEACTIVATE';
    const ADMIN_PERMISSIONS_FRONTEND_DEACTIVATE = 'ADMIN_PERMISSIONS_FRONTEND_DEACTIVATE';
    const ADMIN_PERMISSIONS_SHOW_ADMIN_BAR = 'ADMIN_PERMISSIONS_SHOW_ADMIN_BAR';
    const ADMIN_PERMISSIONS_FRONTENT_RESPOND = 'ADMIN_PERMISSIONS_FRONTENT_RESPOND';
    const ADMIN_PERMISSIONS_MANAGE_LSS_VERSION = 'ADMIN_PERMISSIONS_MANAGE_LSS_VERSION';
    const ADMIN_PERMISSIONS_MANAGE_LAYOUT = 'ADMIN_PERMISSIONS_MANAGE_LAYOUT';
    const ADMIN_PERMISSIONS_MANAGE_SYSTEM = 'ADMIN_PERMISSIONS_MANAGE_SYSTEM';
    const ADMIN_PERMISSIONS_MANAGE_LANGUAGE = 'ADMIN_PERMISSIONS_MANAGE_LANGUAGE';
    const ADMIN_PERMISSIONS_MANAGE_SETTING = 'ADMIN_PERMISSIONS_MANAGE_SETTING';
    const ADMIN_PERMISSIONS_MANAGE_USER = 'ADMIN_PERMISSIONS_MANAGE_USER';
    const ADMIN_PERMISSIONS_MANAGE_ROLE = 'ADMIN_PERMISSIONS_MANAGE_ROLE';
    const ADMIN_PERMISSIONS_MANAGE_AUTHORIZATION = 'ADMIN_PERMISSIONS_MANAGE_AUTHORIZATION';
    const ADMIN_PERMISSIONS_MANAGE_TEMPLATE = 'ADMIN_PERMISSIONS_MANAGE_TEMPLATE';

    const ADMIN_PERMISSIONS_USER = 'ADMIN_PERMISSIONS_USER';
    const ADMIN_PERMISSIONS_EDITOR = 'ADMIN_PERMISSIONS_EDITOR';
    const ADMIN_PERMISSIONS_DESIGNER = 'ADMIN_PERMISSIONS_DESIGNER';
    const ADMIN_PERMISSIONS_ADMINISTRATOR = 'ADMIN_PERMISSIONS_ADMINISTRATOR';

}