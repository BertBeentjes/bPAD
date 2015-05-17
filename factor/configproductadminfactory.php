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
 * Factor the product configuration and administration interface
 *
 * @since 0.4.4
 */
class ConfigProductAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific product is requested, show this one, otherwise open with
        // the default product
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validProduct(Request::getCommand()->getValue())) {
                $product = Products::getProduct(Request::getCommand()->getValue());
            }
        } else {
            $products = Products::getProducts();
            $row = $products->fetchObject();
            $product = Products::getProduct($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the products
        $products = Products::getProducts();
        $section .= $this->factorListBox($baseid . '_productlist', CommandFactory::configProduct($this->getObject(), $this->getMode(), $this->getContext()), $products, $product->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_PRODUCTS));
        // close button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addProduct($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_PRODUCT)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_PRODUCTS));
        // factor the default product
        $content = '';
        // open the first product
        $content = $this->factorConfigProductContent($product);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the product config edit content 
     * 
     * @param product $product
     * @return string
     */
    private function factorConfigProductContent($product) {
        $baseid = 'CP' . $this->getObject()->getId() . '_product';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $this->factorTextInput($baseid . '_name', CommandFactory::editProductName($product), $product->getName(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_NAME));
        // remove button 
        if ($product->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeProduct($this->getObject(), $product, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_PRODUCT)));
        }
        $admin .= $section;
        $section = '';
        // get the form
        $section .= $this->factorTextArea($baseid . '_product_description', CommandFactory::editProductDescription($product), $product->getDescription(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_DESCRIPTION));
        $section .= $this->factorTextArea($baseid . '_product_confirmationemail', CommandFactory::editProductConfirmationEmail($product), $product->getConfirmationEmail(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_CONFIRMATION_EMAIL));
        $section .= $this->factorTextArea($baseid . '_product_invoice', CommandFactory::editProductInvoice($product), $product->getInvoice(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_INVOICE));
        $section .= $this->factorTextInput($baseid . '_product_orderexiturl', CommandFactory::editProductOrderExitURL($product), $product->getOrderExitURL(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_ORDER_EXIT_URL));
        $section .= $this->factorTextInput($baseid . '_product_productprice', CommandFactory::editProductProductPrice($product), $product->getProductPrice(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_PRODUCT_PRICE));
        $section .= $this->factorTextInput($baseid . '_product_vat', CommandFactory::editProductVAT($product), $product->getVAT(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_VAT));
        $section .= $this->factorTextInput($baseid . '_product_costshipping', CommandFactory::editProductCostShipping($product), $product->getCostShipping(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_COST_SHIPPING));
        $section .= $this->factorTextInput($baseid . '_product_costpackaging', CommandFactory::editProductCostPackaging($product), $product->getCostPackaging(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_COST_PACKAGING));
        $section .= $this->factorTextInput($baseid . '_product_vatshippingpackaging', CommandFactory::editProductVATShippingPackaging($product), $product->getVATShippingPackaging(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_VAT_SHIPPING_PACKAGING));
        $section .= $this->factorTextInput($baseid . '_product_totalprice', CommandFactory::editProductTotalPrice($product), $product->getTotalPrice(), Helper::getLang(AdminLabels::ADMIN_PRODUCT_TOTAL_PRICE));
        $admin .= $this->factorSubItem($section);
        $admin = $this->factorSection($baseid . '_section' . $product->getId(), $admin, $sectionheader);
        return $admin;
    }

}