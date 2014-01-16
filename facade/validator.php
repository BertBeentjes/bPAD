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
 * functions that validate user input
 * 
 * @since 0.4.0
 */
Class Validator {

    /**
     * validate the set id, valid if a set with this id exists in the store
     * 
     * @param setid the id to validate
     * @return boolean  if valid
     * @throws exception when store not available
     */
    public static function validSet($setid) {
        if (self::isNumeric($setid)) {
            if (Store::getSet($setid)) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the template id
     * 
     * @param int templateid the id to validate
     * @return boolean  if valid
     * @throws exception when store not available
     */
    public static function validTemplate($templateid) {
        if (self::isNumeric($templateid)) {
            if (Store::getTemplate($templateid)) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the order by for an instance
     * 
     * @param string $orderby the order by clause to validate
     * @param template $template
     * @return boolean true if valid
     * @throws exception when store not available
     */
    public static function validTemplateOrderBy($orderby, $template) {
        $validorders = Templates::getTemplateOrderFieldsByTemplate($template);
        foreach ($validorders as $order) {
            if ($orderby === $order[0]) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the order by for a referral
     * 
     * @param string $orderby the order by clause to validate
     * @return boolean true if valid
     * @throws exception when store not available
     */
    public static function validReferralOrderBy($orderby) {
        $validorders = PositionReferral::getOrderByList();
        foreach ($validorders as $order) {
            if ($orderby === $order[0]) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the style id
     * 
     * @param int styleid the id to validate
     * @param string styletype the style type
     * @param int setid the id of the set the style must be in
     * @return boolean  if valid
     * @throws exception when store not available
     */
    public static function validStyle($styleid, $styletype = NULL, $setid = Set::DEFAULT_SET) {
        if (self::isNumeric($styleid)) {
            if (Store::getStyle($styleid)) {
                // check the set for the style
                $style = Styles::getStyle($styleid);
                if (isset($styletype)) {
                    if ($style->getStyleType() === $styletype && ($setid == Set::DEFAULT_SET || $style->getSet()->getId() === $setid)) {
                        return true;
                    }
                }
                if ($setid == Set::DEFAULT_SET || $style->getSet()->getId() === $setid) {
                    return true;
                }
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the layout id
     * 
     * @param int layoutid the id to validate
     * @param int setid the id of the set the style must be in
     * @return boolean  if valid
     * @throws exception when store not available
     */
    public static function validLayout($layoutid, $setid = Set::DEFAULT_SET) {
        if (self::isNumeric($layoutid)) {
            if ($result = Store::getLayout($layoutid)) {
                // check the set for the layout
                $layout = Layouts::getLayout($layoutid);
                if ($setid == Set::DEFAULT_SET || $layout->getSet()->getId() === $setid) {
                    return true;
                }
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the structure id
     * 
     * @param int structureid the id to validate
     * @param int setid the id of the set the style must be in
     * @return boolean  if valid
     * @throws exception when store not available
     */
    public static function validStructure($structureid, $setid = Set::DEFAULT_SET) {
        if (self::isNumeric($structureid)) {
            if (Store::getStructure($structureid)) {
                // check the set for the layout
                $structure = Structures::getStructure($structureid);
                if ($setid == Set::DEFAULT_SET || $structure->getSet()->getId() === $setid) {
                    return true;
                }
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the argument id
     * 
     * @param int argumentid the id to validate
     * @return boolean  if valid
     * @throws exception when store not available
     */
    public static function validArgument($argumentid) {
        if (self::isNumeric($argumentid)) {
            if (Store::getArgument($argumentid)) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the mode id, valid if a mode with this id exists in the store
     * 
     * @param modeid the id to validate
     * @return boolean  if valid
     * @throws exception when store not available
     */
    public static function validMode($modeid) {
        if (Validator::isNumeric($modeid)) {
            if ($modeid == Mode::NOMODE || $modeid == Mode::ARCHIVEMODE || $modeid == Mode::VIEWMODE || $modeid == Mode::EDITMODE) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * validate the input type of a position content item, valid if a known input type
     * 
     * @param string inputtype the inputtype to validate
     * @return boolean  if valid
     */
    public static function validInputType($inputtype) {
        if (Validator::isType($inputtype)) {
            if ($inputtype == PositionContentItem::INPUTTYPE_TEXTAREA || $inputtype == PositionContentItem::INPUTTYPE_INPUTBOX || $inputtype == PositionContentItem::INPUTTYPE_COMBOBOX || $inputtype == PositionContentItem::INPUTTYPE_UPLOADEDFILE) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * Check whether it is a valid style type
     * 
     * @param string $str
     */
    public static function validStyleType($str) {
        if (Validator::isType($str)) {
            if ($str == Style::OBJECT_STYLE || $str == Style::POSITION_STYLE) {
                return true;
            }
        }
        throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
    }

    /**
     * Check whether it is a valid name
     * 
     * @param string $str
     */
    public static function isName($str) {
        return preg_match('/^[a-zA-Z0-9\ ]+$/', $str);
    }

    /**
     * Check whether a string consists purely of lower case letters
     * 
     * @param string $str
     */
    public static function isLCaseChars($str) {
        return preg_match('/^[a-z]+$/', $str);
    }

    /**
     * Check whether a string consists purely of two rows of lower case letters separated by a dot
     * 
     * @param string $str
     */
    public static function isCommand($str) {
        return preg_match('/^[a-z]+\.[a-z]+(\.[0-9]+)*$/', $str);
    }

    /**
     * Check whether a string consists purely of numbers
     * 
     * @param string $str
     */
    public static function isNumeric($str) {
        return preg_match('/^[0-9]+$/', $str);
    }

    /**
     * Check whether a string consists purely of capitals and underscores
     * 
     * @param string $str
     */
    public static function isType($str) {
        return preg_match('/^[A-Z_]+$/', $str);
    }

    /**
     * Check whether a string consists purely of numbers
     * 
     * @param string $str
     */
    public static function isCommandNumber($str) {
        return preg_match('/^[0-9\.]+$/', $str);
    }

    /**
     * Check whether a string contains an address, consisting of numbers and
     * dots:
     * 
     * 123.32.2.1.17
     * 
     * @param string $str
     */
    public static function isAddress($str) {
        return preg_match('/^[a-zA-Z0-9\-\/\.]+$/', $str);
    }

    /**
     * check whether the string contains locale specific alfanumeric characters
     * 
     * @param string $str
     */
    public static function isLocaleAlfaNumeric($str) {
        // TODO: find something better than alnum, maybe a regex with \p{L} and \p{N} will do it
        if ($str > '') {
            return ctype_alnum($str);
        } else {
            return true;
        }
    }

    /**
     * Check whether a string contains a url, containing only letters, numbers, . and /
     * 
     * @param string $str
     */
    public static function isURL($str) {
        return preg_match('/^\/?_?[a-zA-Z0-9\.\/\- ]+$/', $str);
    }

    /**
     * Check whether a the urlparts are ok for a file:
     * /media/##/##/object####/##/##/file.ext
     * media - millions - thousands - object objectid - mode - positionnumber - filename.extension
     * 
     * @param array()
     */
    public static function isFileURL($urlparts) {
        return count($urlparts) === 7 && $urlparts[1] === 'media' && Validator::isNumeric($urlparts[2]) && Validator::isNumeric($urlparts[3]) && substr($urlparts[4], 0, 6) === 'object' && Validator::isNumeric(substr($urlparts[4], 6)) && Validator::isNumeric($urlparts[5]);
    }

    /**
     * Check whether a the urlparts are ok for an upload:
     * /####/####/upload.html
     * 
     * @param array()
     */
    public static function isUploadURL($urlparts) {
        return count($urlparts) === 4 && $urlparts[3] === 'upload.html' && Validator::isNumeric($urlparts[1]) && Validator::isNumeric($urlparts[2]);
    }

}

?>
