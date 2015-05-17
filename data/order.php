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
 * Orders for products, submitted by forms
 *
 * @since 0.4.4
 */
class Order {

    private $id; // the id
    private $product; // the product the order is for
    private $formstorage; // the form storage
    private $invoicedate; // the date for the invoice
    private $invoiceyear; // the year for the invoice
    private $invoicenumber; // the number of the invoice
    private $uniquecode; // a unique code (not guessable), identifying the invoice for web access
    private $paymenttype; // the type of payment for the order
    private $transaction; // the transaction info for the payment provider
    private $status; // the status of the payment
    
    /**
     * Construct the order
     * 
     * @param int the id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getOrder($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * Initialize the attributes
     * 
     * @return boolean true if success,
     */
    protected function initAttributes($attr) {
        $this->product = Products::getProduct($attr->productid);
        $this->formstorage = FormStorages::getFormStorage($attr->formstorageid);
        $this->invoicedate = $attr->invoicedate;
        $this->invoiceyear = $attr->invoiceyear;
        $this->invoicenumber = $attr->invoicenumber;
        $this->uniquecode = $attr->uniquecode;
        $this->paymenttype = $attr->paymenttype;
        $this->transaction = $attr->transaction;
        $this->status = $attr->status;
        return true;
    }
    
    /**
     * Get the order id
     * 
     * @return int 
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Get the order name
     * 
     * @return int 
     */
    public function getName() {
        return $this->getInvoiceYear() . '-' . $this->getInvoiceDate() . '-' . $this->getInvoiceNumber()  . ' ' . $this->getProduct()->getName();
    }
    
    /**
     * Get the form storage
     * 
     * @return formstorage 
     */
    public function getFormStorage() {
        return $this->formstorage;
    }
    
    /**
     * Set the form storage for the order
     * 
     * @param formstorage $newformstorage the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setFormStorage($newformstorage) {
        if (Store::setOrderFormStorage($this->id, $newformstorage->getId())) {
            $this->formstorage = $newformstorage;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
    /**
     * Get the product
     * 
     * @return product
     */
    public function getProduct() {
        return $this->product;
    }
    
    /**
     * Set the product for the order
     * 
     * @param product $newproduct the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setProduct($newproduct) {
        if (Store::setOrderProduct($this->id, $newproduct->getId())) {
            $this->product = $newproduct;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
    /**
     * Get the invoice date
     * 
     * @return string
     */
    public function getInvoiceDate() {
        return $this->invoicedate;
    }
    
    /**
     * Set the invoice date
     * 
     * @param string $newinvoicedate the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setInvoiceDate($newinvoicedate) {
        if (Store::setOrderInvoiceDate($this->id, $newinvoicedate)) {
            $this->invoicedate = $newinvoicedate;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the invoice year
     * 
     * @return string
     */
    public function getInvoiceYear() {
        return $this->invoiceyear;
    }
    
    /**
     * Set the invoice year
     * 
     * @param string $newinvoiceyear the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setInvoiceYear($newinvoiceyear) {
        if (Store::setOrderInvoiceYear($this->id, $newinvoiceyear)) {
            $this->invoiceyear = $newinvoiceyear;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
    /**
     * Get the invoice number
     * 
     * @return string
     */
    public function getInvoiceNumber() {
        return $this->invoicenumber;
    }
    
    /**
     * Set the invoice number
     * 
     * @param string $newinvoicenumber the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setInvoiceNumber($newinvoicenumber) {
        if (Store::setOrderInvoiceNumber($this->id, $newinvoicenumber)) {
            $this->invoicenumber = $newinvoicenumber;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
    /**
     * Get the unique code
     * 
     * @return string
     */
    public function getUniqueCode() {
        return $this->uniquecode;
    }
    
    /**
     * Set the unique code
     * 
     * @param string $newuniquecode the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setUniqueCode($newuniquecode) {
        if (Store::setOrderUniqueCode($this->id, $newuniquecode)) {
            $this->uniquecode = $newuniquecode;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
    /**
     * Get the payment type
     * 
     * @return string
     */
    public function getPaymentType() {
        return $this->paymenttype;
    }
    
    /**
     * Set the payment type
     * 
     * @param string $newpaymenttype the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setPaymentType($newpaymenttype) {
        if (Store::setOrderPaymentType($this->id, $newpaymenttype)) {
            $this->paymenttype = $newpaymenttype;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
    /**
     * Get the transaction
     * 
     * @return string
     */
    public function getTransaction() {
        return $this->transaction;
    }
    
    /**
     * Set the transaction
     * 
     * @param string $newtransaction the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setTransaction($newtransaction) {
        if (Store::setOrderTransaction($this->id, $newtransaction)) {
            $this->transaction = $newtransaction;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
    /**
     * Get the status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Set the status
     * 
     * @param string $newstatus the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setStatus($newstatus) {
        if (Store::setOrderStatus($this->id, $newstatus)) {
            $this->status = $newstatus;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
        
 }