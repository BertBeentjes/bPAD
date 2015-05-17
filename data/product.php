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
 * A product
 *
 * @since 0.4.4
 */
class Product extends NamedEntity {

    const DEFAULT_PRODUCT = 1;

    private $description; // product description
    private $confirmationemail; // the text for the confirmation email
    private $invoice; // the text for the invoice
    private $orderexiturl; // the url to open after the order has been confirmed
    private $productprice; // the price of the product (incl. VAT, in cents)
    private $vat; // the vat (in cents)
    private $costshipping; // additional shipping costs (incl. VAT, in cents)
    private $costpackaging; // additional packaging costs (incl. VAT, in cents)
    private $vatshippingpackaging; // the vat for shipping and packaging (in cents)
    private $totalprice; // the price for the complete package (incl. VAT, in cents)
    
    /**
     * Construct the product
     * 
     * @param int the id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableProducts();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes
     * 
     * @return boolean true if success,
     * @throws Exception when store not available
     */
    private function loadAttributes() {
        if ($result = Store::getProduct($this->id)) {
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
        $this->description = $attr->description;
        $this->confirmationemail = $attr->confirmationemail;
        $this->invoice = $attr->invoice;
        $this->orderexiturl = $attr->orderexiturl;
        $this->productprice = $attr->productprice;
        $this->vat = $attr->vat;
        $this->costshipping = $attr->costshipping;
        $this->costpackaging = $attr->costpackaging;
        $this->vatshippingpackaging = $attr->vatshippingpackaging;
        $this->totalprice = $attr->totalprice;
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * Get the description to of the product
     * 
     * @return string type
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Set the description to value for the product
     * 
     * @param string $newdescription the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setDescription($newdescription) {
        if (Store::setProductDescription($this->id, $newdescription) && $this->setChanged()) {
            $this->description = $newdescription;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the confirmationemail to of the product
     * 
     * @return string type
     */
    public function getConfirmationEmail() {
        return $this->confirmationemail;
    }
    
    /**
     * Set the confirmationemail to value for the product
     * 
     * @param string $newconfirmationemail the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setConfirmationEmail($newconfirmationemail) {
        if (Store::setProductConfirmationEmail($this->id, $newconfirmationemail) && $this->setChanged()) {
            $this->confirmationemail = $newconfirmationemail;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the invoice to of the product
     * 
     * @return string type
     */
    public function getInvoice() {
        return $this->invoice;
    }
    
    /**
     * Set the invoice to value for the product
     * 
     * @param string $newinvoice the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setInvoice($newinvoice) {
        if (Store::setProductInvoice($this->id, $newinvoice) && $this->setChanged()) {
            $this->invoice = $newinvoice;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the orderexiturl to of the product
     * 
     * @return string type
     */
    public function getOrderExitURL() {
        return $this->orderexiturl;
    }
    
    /**
     * Set the orderexiturl to value for the product
     * 
     * @param string $neworderexiturl the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setOrderExitURL($neworderexiturl) {
        if (Store::setProductOrderExitURL($this->id, $neworderexiturl) && $this->setChanged()) {
            $this->orderexiturl = $neworderexiturl;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the productprice to of the product
     * 
     * @return string type
     */
    public function getProductPrice() {
        return $this->productprice;
    }
    
    /**
     * Set the productprice to value for the product
     * 
     * @param string $newproductprice the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setProductPrice($newproductprice) {
        if (Store::setProductProductPrice($this->id, $newproductprice) && $this->setChanged()) {
            $this->productprice = $newproductprice;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the vat to of the product
     * 
     * @return string type
     */
    public function getVAT() {
        return $this->vat;
    }
    
    /**
     * Set the vat to value for the product
     * 
     * @param string $newvat the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setVAT($newvat) {
        if (Store::setProductVAT($this->id, $newvat) && $this->setChanged()) {
            $this->vat = $newvat;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the costshipping to of the product
     * 
     * @return string type
     */
    public function getCostShipping() {
        return $this->costshipping;
    }
    
    /**
     * Set the costshipping to value for the product
     * 
     * @param string $newcostshipping the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setCostShipping($newcostshipping) {
        if (Store::setProductCostShipping($this->id, $newcostshipping) && $this->setChanged()) {
            $this->costshipping = $newcostshipping;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the costpackaging to of the product
     * 
     * @return string type
     */
    public function getCostPackaging() {
        return $this->costpackaging;
    }
    
    /**
     * Set the costpackaging to value for the product
     * 
     * @param string $newcostpackaging the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setCostPackaging($newcostpackaging) {
        if (Store::setProductCostPackaging($this->id, $newcostpackaging) && $this->setChanged()) {
            $this->costpackaging = $newcostpackaging;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the vatshippingpackaging to of the product
     * 
     * @return string type
     */
    public function getVATShippingPackaging() {
        return $this->vatshippingpackaging;
    }
    
    /**
     * Set the vatshippingpackaging to value for the product
     * 
     * @param string $newvatshippingpackaging the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setVATShippingPackaging($newvatshippingpackaging) {
        if (Store::setProductVATShippingPackaging($this->id, $newvatshippingpackaging) && $this->setChanged()) {
            $this->vatshippingpackaging = $newvatshippingpackaging;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Get the totalprice to of the product
     * 
     * @return string type
     */
    public function getTotalPrice() {
        return $this->totalprice;
    }
    
    /**
     * Set the totalprice to value for the product
     * 
     * @param string $newtotalprice the new value
     * @return boolean true if success
     * @throws Exception when update fails
     */
    public function setTotalPrice($newtotalprice) {
        if (Store::setProductTotalPrice($this->id, $newtotalprice) && $this->setChanged()) {
            $this->totalprice = $newtotalprice;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }
    
    /**
     * Is the product used somewhere?
     * 
     * @return boolean true if used
     */
    public function isUsed() {
        if ($result = Store::getProductUsed($this->getId())) {
            return true;
        }
        return false;
    }

    /**
     * Is the product removable?
     * 
     * @return boolean true if removable
     */
    public function isRemovable() {
        return (!$this->isUsed() && !($this->getId() == self::DEFAULT_PRODUCT));
    }

    /**
     * remove a product
     * 
     * @param product $product
     * @return boolean true if success
     */
    public static function removeProduct($product) {
        Store::deleteProduct($form->getId());
        return true;
    }
        
}