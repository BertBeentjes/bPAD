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
 * Extends StoredEntity and contains one of the types of positions, in this case
 * the referral, that contains a basic piece of content
 *
 * @since 0.4.0
 */
class PositionReferral extends StoredEntity implements PositionContent {
    private $argument; // the argument used to select content 
    private $orderby; // the type of ordering of the items 
    private $numberofitems; // the number of items to show

    const POSITIONREFERRAL_ORDER_NAME_ASC = 'POSITIONREFERRAL_ORDER_NAME_ASC';
    const POSITIONREFERRAL_ORDER_NAME_DESC = 'POSITIONREFERRAL_ORDER_NAME_DESC';
    const POSITIONREFERRAL_ORDER_NUMBER_ASC = 'POSITIONREFERRAL_ORDER_NUMBER_ASC';
    const POSITIONREFERRAL_ORDER_NUMBER_DESC = 'POSITIONREFERRAL_ORDER_NUMBER_DESC';
    const POSITIONREFERRAL_ORDER_CHANGEDATE_ASC = 'POSITIONREFERRAL_ORDER_CHANGEDATE_ASC';
    const POSITIONREFERRAL_ORDER_CHANGEDATE_DESC = 'POSITIONREFERRAL_ORDER_CHANGEDATE_DESC';
    const POSITIONREFERRAL_ORDER_CREATEDATE_ASC = 'POSITIONREFERRAL_ORDER_CREATEDATE_ASC';
    const POSITIONREFERRAL_ORDER_CREATEDATE_DESC = 'POSITIONREFERRAL_ORDER_CREATEDATE_DESC';
    
    /**
     * Construct the referral, retrieve all the attributes
     * 
     * @param position the containing position
     * @param resultset the attributes for the positionreferral
     */
    public function __construct($position, $attr) {
        $this->id = $attr->id;
        $this->tablename = Store::getTablePositionReferrals();
        $this->container = $position;
        $this->argument = Arguments::getArgument($attr->argumentid);
        $this->orderby = $attr->orderby;
        $this->numberofitems = $attr->numberofitems;
        parent::initAttributes($attr);
    }

    /**
     * Return the type of content in this position
     * 
     * @return constant
     */
    public function getType() {
        return PositionContent::POSITIONTYPE_REFERRAL;
    }
        
    /**
     * Getter for the order by
     * 
     * @return string the order type
     */
    public function getOrderBy() {
        return $this->orderby;
    }
    
    /**
     * Setter for the order by
     * 
     * @param string the new order by
     * @return boolean  if success
     */
    public function setOrderBy($neworderby) {
        if (Store::setPositionReferralOrderBy($this->id, $neworderby) && $this->setChanged()) {
            $this->orderby = $neworderby;
            return true;
        }
    }
    
    /**
     * Getter for the number of items
     * 
     * @return string the order type
     */
    public function getNumberOfItems() {
        return $this->numberofitems;
    }
    
    /**
     * Setter for the number of items
     * 
     * @param string the new number of items
     * @return boolean  if success
     */
    public function setNumberOfItems($newnumberofitems) {
        if (Store::setPositionReferralNumberOfItems($this->id, $newnumberofitems) && $this->setChanged()) {
            $this->numberofitems = $newnumberofitems;
            return true;
        }
    }
    
    /**
     * Getter for the argument
     * 
     * @return int the argument
     */
    public function getArgument() {
        return $this->argument;
    }
    
    /**
     * Setter for the argument
     * 
     * @param argument the new argument
     * @return boolean  if success
     */
    public function setArgument($newargument) {
        if (Store::setPositionReferralArgumentId($this->id, $newargument->getId()) && $this->setChanged()) {
            $this->argument = $newargument;
            return true;
        }
    }
    
    /**
     * Get the list of order by options for a list box
     * 
     * @return string[]
     */
    public static function getOrderByList() {
        $orderbylist = array();
        $orderbylist[0][0] = PositionReferral::POSITIONREFERRAL_ORDER_NAME_ASC;
        $orderbylist[0][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_NAME_ASC);
        $orderbylist[1][0] = PositionReferral::POSITIONREFERRAL_ORDER_NAME_DESC;
        $orderbylist[1][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_NAME_DESC);
        $orderbylist[2][0] = PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_ASC;
        $orderbylist[2][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_ASC);
        $orderbylist[3][0] = PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_DESC;
        $orderbylist[3][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_DESC);
        $orderbylist[4][0] = PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_ASC;
        $orderbylist[4][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_ASC);
        $orderbylist[5][0] = PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_DESC;
        $orderbylist[5][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_DESC);
        $orderbylist[6][0] = PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_ASC;
        $orderbylist[6][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_ASC);
        $orderbylist[7][0] = PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_DESC;
        $orderbylist[7][1] = Helper::getLang(PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_DESC);
        return $orderbylist;
    }
    
}

?>
