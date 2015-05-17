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
 * Factor the order configuration and administration interface
 *
 * @since 0.4.4
 */
class ConfigOrderAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific order is requested, show this one, otherwise open with
        // the default order
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validOrder(Request::getCommand()->getValue())) {
                $order = Orders::getOrder(Request::getCommand()->getValue());
            }
        } else {
            $orders = Orders::getOrders();
            $row = $orders->fetchObject();
            $order = Orders::getOrder($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the orders
        $orders = Orders::getOrders();
        $section .= $this->factorListBox($baseid . '_orderlist', CommandFactory::configOrder($this->getObject(), $this->getMode(), $this->getContext()), $orders, $order->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_ORDERS));
        // close button
        $section .= $this->factorButtonGroup($this->factorCloseButton($baseid));        
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_ORDERS));
        // factor the default order
        $content = '';
        // open the first order
        $content = $this->factorConfigOrderContent($order);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the order config edit content 
     * 
     * @param order $order
     * @return string
     */
    private function factorConfigOrderContent($order) {
        $baseid = 'CP' . $this->getObject()->getId() . '_order';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $this->factorTextInput($baseid . '_name', '', $order->getName(), Helper::getLang(AdminLabels::ADMIN_ORDER_NAME), 'disabled');
        // remove button 
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeOrder($this->getObject(), $order, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_ORDER)));
        $admin .= $section;
        $section = '';
        // get the order
        $section .= $this->factorTextInput($baseid . '_product', '', $order->getProduct()->getName(), Helper::getLang(AdminLabels::ADMIN_ORDER_PRODUCT), "disabled");
        $section .= $this->factorTextInput($baseid . '_formstorage', '', $order->getFormStorage()->getName(), Helper::getLang(AdminLabels::ADMIN_ORDER_FORM_STORAGE), "disabled");
        $section .= $this->factorTextInput($baseid . '_invoiceyear', '', $order->getInvoiceYear(), Helper::getLang(AdminLabels::ADMIN_ORDER_INVOICE_YEAR), "disabled");
        $section .= $this->factorTextInput($baseid . '_invoicedate', '', $order->getInvoiceDate(), Helper::getLang(AdminLabels::ADMIN_ORDER_INVOICE_DATE), "disabled");
        $section .= $this->factorTextInput($baseid . '_invoicenumber', '', $order->getInvoiceNumber(), Helper::getLang(AdminLabels::ADMIN_ORDER_INVOICE_NUMBER), "disabled");
        $section .= $this->factorTextInput($baseid . '_uniquecode', '', $order->getUniqueCode(), Helper::getLang(AdminLabels::ADMIN_ORDER_UNIQUE_CODE), "disabled");
        $section .= $this->factorTextInput($baseid . '_paymenttype', '', $order->getPaymentType(), Helper::getLang(AdminLabels::ADMIN_ORDER_PAYMENT_TYPE), "disabled");
        $section .= $this->factorTextInput($baseid . '_transaction', '', $order->getTransaction(), Helper::getLang(AdminLabels::ADMIN_ORDER_TRANSACTION), "disabled");
        $section .= $this->factorTextInput($baseid . '_status', '', $order->getStatus(), Helper::getLang(AdminLabels::ADMIN_ORDER_STATUS), "disabled");
        $admin .= $this->factorSubItem($section);
        $admin = $this->factorSection($baseid . '_section' . $order->getId(), $admin, $sectionheader);
        return $admin;
    }

}