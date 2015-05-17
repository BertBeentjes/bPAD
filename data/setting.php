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
 * Installation wide settings
 *
 * @since 0.4.0
 */
class Setting extends NamedEntity {

    const DEFAULT_SETTING = 100;

    private $value; // the value of the setting
    
    /**
     * All settings have a unique and predefined id and a predefined name
     * 
     * Settings are created centrally by update scripts, not by users,
     * though they can be set by system admins
     * 
     * The following consts define the setting id's
     */
    
    const SITE_NAME = 100; // the name of the site
    const SITE_ROOT = 101; // the root location of the site (http://www.example.com)
    const SITE_ROOTFOLDER = 102; // the subfolder, if any, of the site (/bpadroot)
    const SITE_LANGUAGE = 103; // the language, currently 'en' and 'nl' are supported
    const SITE_ADMINEMAIL = 104; // the emailaddress of the administrator, used for system mail reports
    const SITE_MAXUPLOADSIZE = 105; // the maximum size for uploads
    const SITE_UPLOAD_LOCATION = 106; // the location for uploading files, by default the 'media' folder
    const SITE_UPLOAD_LOCATION_PERMISSIONS = 107; // the permissions for the location for uploading files, by default 770
    const SITE_LOCALE = 108; // the name of the site
    
    const SECURITY_HASHALGORITHM = 200; // the hash algorithm to use for passwords
    const SECURITY_SALT = 201; // the salt to use in passwords
    const SECURITY_MAXLOGINATTEMPTS = 202; // the max number of logins allowed before the account is blocked

    const UPDATE_LSSMASTER = 300; // the master site for layouts, styles and structures
    const UPDATE_LSSPASSWORD = 301; // the password for the master site

    const CONTENT_SETMOBILEVIEWPORT = 400; // fix the mobile viewport or not
    const CONTENT_MOBILEUSEPNDEFAULT = 401; // use the #pn# default value in mobile, if not, no defaults are shown 
    const CONTENT_SHOWLIGHTBOXOBJECTNAME = 402; // show the object name in the frontend lightbox edit window
    const CONTENT_USECONTENTDIVADMINCLASS = 403; // TODO: probably obsolete in new setup, clean up if necessary
    const CONTENT_PRELOADINSTANCES = 404; // the number of instances to preload when showing an instance, the rest is loaded with a lazy load mechanism
    const CONTENT_PRELOADPNOBJECTS = 405; // the number of objects in a pn to preload when showing an instance, the rest is loaded with a lazy load mechanism
    const CONTENT_DATEFORMAT = 406; // the date format to use when presenting content

    const CONTEXT_DEFAULT = 500; // the default context
    const CONTEXT_DEFAULTMINWIDTH = 501; // the minimum screen width for using the default context
    const CONTEXT_DEFAULTMINHEIGHT = 502; // the minimum screen height for using the default context
    const CONTEXT_MOBILE = 503; // the mobile context, used if the screen is smaller than previous two settings specify
    const CONTEXT_METADATA = 504; // the context used to create page metadata
    const CONTEXT_SITEMAP = 505; // the context used to generate a site map
    const CONTEXT_INSTANCE = 506; // the context used to show content in instances 
    const CONTEXT_INSTANCEMOBILE = 507; // the mobile version of the instance context
    const CONTEXT_INLINE = 508; // show the site inline, a context used for printing the site
    const CONTEXT_SLIDE = 509; // show (part of) the site as slides
    const CONTEXT_RECYCLEBIN = 510; // used for instances that show recycle bin content
    const CONTEXT_RECYCLEBINMOBILE = 511; // mobile version of the recycle bin context
   
    const GOOGLE_ANALYTICSCODE = 600; // for google analytics

    // specify what can be seen in the frontend menu
    const FRONTENDMENU_EDITINLINE = 700;
    const FRONTENDMENU_EDITLIGHTBOX = 702;
    const FRONTENDMENU_EDITNAME = 702;
    const FRONTENDMENU_STYLES = 703;
    const FRONTENDMENU_LAYOUTS = 704;
    const FRONTENDMENU_STRUCTURES = 705;
    const FRONTENDMENU_ARGUMENT = 706;
    const FRONTENDMENU_AUTHORIZATION = 707;
    const FRONTENDMENU_MOVE = 708;
    const FRONTENDMENU_MOVEUPDOWN = 709;
    const FRONTENDMENU_PUBLISH = 710;
    const FRONTENDMENU_UPDATE = 711;
    const FRONTENDMENU_DEACTIVATE = 712;
    const FRONTENDMENU_DELETE = 713;

    // web fonts can be added in a snippet or a file include
    //const GOOGLE_WEBFONTS = 601; // webfont specs
    
    // plugins can now be added using file includes
//    const PLUGIN_CAROUSEL = 900;
//    const PLUGIN_CYCLE = 901;
//    const PLUGIN_FLASH = 902;
//    const PLUGIN_TWITTERWIDGET = 903;
//    const PLUGIN_FACEBOOKLIKE = 904;
//    const PLUGIN_TWITTERTWEET = 905;
//    const PLUGIN_GOOGLEPLUSONE = 906;
//    const PLUGIN_LIGHTBOX = 907;
//    const PLUGIN_INSTAGRAM = 908;
//    const PLUGIN_LAZYLOAD = 909;
//    const PLUGIN_CUSTOMJAVASCRIPT = 910;
//    const PLUGIN_PDF = 911;

    /**
     * Constructor, sets the basic setting attributes
     * By setting these attribs, the existence of the setting is 
     * verified
     * 
     * @param id contains the user id to get from the store
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableSettings();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getSetting($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        }
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * initialize the attributes
     * 
     * @param resultset $attr
     * @return boolean true if success
     */
    protected function initAttributes($attr) {
        $this->value = $attr->value;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the value
     * 
     * @return string the value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * setter for the value
     * 
     * @param newvalue the new value
     * @return boolean true if success
     * @throws exception if the update in the store fails
     */
    public function setValue($newvalue) {
        if (Store::setSettingValue($this->id, $newvalue) && $this->setChanged()) {
            $this->value = $newvalue;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }    
        
    /**
     * the name of a setting can't be changed
     * 
     * @throws exception 
     */
    public function setName($newname) {
        throw new Exception(Helper::getLang(Errors::ERROR_UNKNOWN_REQUEST) . ' @ ' . __METHOD__);
    }
}