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
 * Log in the user, check for a cookie, read the values, reset the cookie
 *
 * @since 0.4.0
 */
class Authentication {
    private static $user; // the user that has logged in, or the public user

    /**
     * In the initialization routines, updates or other changes may occur. At
     * this time, the admin user is used. 
     */
    public static function adminUser() {
        self::$user = Users::getUser(User::USER_ADMINISTRATOR);
    }
    
    /**
     * check the cookie or the login credentials
     * 
     * @return boolean true when cookie login succeeded
     */
    public static function cookieLogin() {
        if (isset($_COOKIE['ID_bPAD'])) {
            if (Validator::isLocaleAlfaNumeric($_COOKIE['ID_bPAD'])) {
                if (self::$user = Users::getUser($_COOKIE['ID_bPAD'])) {
                    if (isset($_COOKIE['Key_bPAD'])) {
                        if ($_COOKIE['Key_bPAD'] == self::$user->getPassword()) {
                            // update the cookie expiry time
                            $hour = time() + 50000000;
                            setcookie('ID_bPAD', self::$user->getId(), $hour);
                            setcookie('Key_bPAD', self::$user->getPassword(), $hour);
                            return true;
                        }
                    }
                }
            }
        }
        // login didn't succeed, use public user
        self::$user = Users::getUserByName(SysCon::PUBLIC_USER);
        // disable this cookie, it's no longer correct
        setcookie("ID_bPAD", "", time() - 10000);
        setcookie("Key_bPAD", "", time() - 10000);
        return false;
    }

    /**
     * Return true when the public user is used
     * 
     * @return boolean true if public
     */
    public static function isPublicUser() {
        return self::$user->getName() == SysCon::PUBLIC_USER;
    }

    /**
     * Get the current logged in user
     * 
     * @return user
     */
    public static function getUser() {
        return self::$user;
    }

    /**
     * Logout the user and reset the cookie
     * 
     */
    public static function logout() {
        setcookie("ID_bPAD", "", time() - 10000);
        setcookie("Key_bPAD", "", time() - 10000);
        header("Location: " . $bpad['site']['root']);
    }

    /**
     * Login for users
     * 
     * @param string $username
     * @param string $password
     */
    public static function login($username, $password) {
        // retrieve the user by username
        if (self::$user = users::getUserByName($username)) {
            //gives error if the password is wrong
            if (self::$user->getLoginCounter() >= Settings::getSetting(Setting::SECURITY_MAXLOGINATTEMPTS)->getValue()) {
                // too many login attempts
                Messages::Add(Helper::getLang(MESSAGE_MAX_LOGIN_ATTEMPTS_REACHED));
            } elseif ($password != self::$user->getPassword()) {
                // disable this cookie, it's no longer correct
                // TODO: create cookie manager
                setcookie("ID_bPAD", "", time() - 10000);
                setcookie("Key_bPAD", "", time() - 10000);
                // update the logincounter
                self::$user->setLoginCounter(self::$user->getLoginCounter() + 1);
                // send notification to site administrator 
                // TODO: make notification object, instead of creating a mail here
                if (Settings::getSetting(Setting::SITE_ADMINEMAIL)->getValue() != "" && self::$user->getLoginCounter() == Settings::getSetting(Setting::SECURITY_MAXLOGINATTEMPTS)->getValue()) {
                    $to = Settings::getSetting(Setting::SITE_ADMINEMAIL)->getValue();
                    $subject = 'User blocked';
                    $message = 'The user ' . $username . ' has been blocked. Too many login attempts.';
                    $headers = 'From: ' . Settings::getSetting(Setting::SITE_ADMINEMAIL)->getValue() . "\r\n" .
                            'Reply-To: ' . Settings::getSetting(Setting::SITE_ADMINEMAIL)->getValue() . "\r\n" .
                            'X-Mailer: PHP/' . phpversion();
                    mail($to, $subject, $message, $headers);
                }
                global $debuglevel;
                switch ($debuglevel) {
                    case 'trace': 
                        throw new Exception (Helper::getLang(Errors::MESSAGE_LOGIN_FAILED_FOR_USER) . (Settings::getSetting(Setting::SECURITY_MAXLOGINATTEMPTS)->getValue() - self::$user->getLoginCounter()) . ', ' . $password);
                        break;
                    default:
                        throw new Exception (Helper::getLang(Errors::MESSAGE_LOGIN_FAILED_FOR_USER) . (Settings::getSetting(Setting::SECURITY_MAXLOGINATTEMPTS)->getValue() - self::$user->getLoginCounter()));
                        break;
                }
            } else {
                // if login is ok then we add a cookie
                // TODO: create cookie manager
                $hour = time() + 864000000;
                setcookie('ID_bPAD', self::$user->getId(), $hour);
                setcookie('Key_bPAD', $password, $hour);
                // reset login counter
                self::$user->setLoginCounter(0);
                // and open the home page 
                // TODO: open any page, based upon the url
                header("Location: index.php");
                exit;
            }
        }
        // disable this cookie, it's no longer correct
        // TODO: create cookie manager
        setcookie("ID_bPAD", "", time() - 10000);
        setcookie("Key_bPAD", "", time() - 10000);
        Messages::Add(Errors::MESSAGE_LOGIN_FAILED);
    }

    /**
     * Salt and encrypt the password
     * 
     * @param string $toHash
     * @return string
     */
    public static function middleSalt($toHash) {
        $partlength = floor((strlen($toHash) / 2) + 1);
        $part1 = substr($toHash, 0, $partlength);
        $part2 = substr($toHash, $partlength);
        $hash = hash(Settings::getSetting(Setting::SECURITY_HASHALGORITHM)->getValue(), $part1 . Settings::getSetting(Setting::SECURITY_SALT)->getValue() . $part2);
        return $hash;
    }

}