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
 * Contains all orders
 * 
 * @since 0.4.4
 */
class Orders {
    
    /*
     * get an order by id
     *  
     * @param orderid the id of the order to get
     * @return order
     */
    public static function getOrder ($orderid) {
        return new Order($orderid);
    }
    
    /**
     * Get all orders
     * 
     * @return resultset
     */
    public static function getOrders () {
        return Store::getOrders();
    }
    
    /**
     * Create a new order
     * 
     * @param formstorage $form the form containing the information belonging to the order
     * @return type
     */
    public static function newOrder($form) {
        $orderid = Store::insertOrder($form->getId());
        return true;
    }

    /**
     * remove an order
     * 
     * @param order $order
     * @return type
     */
    public static function removeOrder($order) {
        Store::deleteOrder($order->getId());
        return true;
    }

}