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

/*
 * Contains all arguments, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Arguments {
    private static $arguments = array();
    
    /**
     * get a argument by id, checks whether the argument is loaded,
     * loads the argument if necessary and fills it on demand with
     * further information
     * 
     * @param argumentid the id of the argument to get
     * @return argument
     */
    public static function getArgument ($argumentid) {
        // return an argument
        if (isset(self::$arguments[$argumentid])) {
            return self::$arguments[$argumentid];
        } else {
            self::$arguments[$argumentid] = new Argument($argumentid);
            return self::$arguments[$argumentid];
        }
    }
    
    /**
     * get all arguments
     * 
     * @return resultset
     */
    public static function getArguments() {
        return Store::getArguments();
    }
    
    /**
     * Create a new argument
     * 
     * @return argument
     */
    public static function newArgument() {
        $argumentid = Store::insertArgument();
        $argument = self::getArgument($argumentid);
        $argument->setName('Arg' . $argument->getId());                
        return $argument;
    }
    
}