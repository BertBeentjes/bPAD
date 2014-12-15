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
 * Load a command
 *
 */
class Commands {
    
    /**
     * Get the last command
     * 
     * @return command
     */
    public static function getLastCommand() {
        if ($result = Store::getLastCommand()) {
            if ($row = $result->fetchObject()) {
                return new Command($row->id);
            }
        }
    }
    
    /**
     * create a new command in the store
     * 
     */
    public static function newCommand() {
        return new Command(Store::insertCommand());
    }
    
    /**
     * create a new command in the store at once with all attributes
     * 
     * @param $item string
     * @param $itemaddress string
     * @param $command string
     * @param $commandnumber integer
     * @param $lastcommandid integer
     * @param $sessionidentifier string
     * @param $user user
     * @param $date date
     * @param $value string
     * @return command
     */
    public static function newCommandFull($item, $itemaddress, $command, $commandnumber, $lastcommandid, $sessionidentifier, $user, $date, $value) {
        return new Command(Store::insertCommandFull($item, $itemaddress, $command, $commandnumber, $lastcommandid, $sessionidentifier, $user->getId(), $date, $value));
    }
        
}