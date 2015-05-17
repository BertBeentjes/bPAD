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
 * Contains all products
 * 
 * @since 0.4.4
 */
class Products {
    private static $products = array();
    
    /*
     * get a product by id, checks whether the product is loaded,
     * loads the product if necessary and fills it on demand with
     * further information
     * 
     * @param productid the id of the product to get
     * @return product
     */
    public static function getProduct ($productid) {
        // return an product
        if (isset(self::$products[$productid])) {
            return self::$products[$productid];
        } else {
            self::$products[$productid] = new Product($productid);
            return self::$products[$productid];
        }
    }
    
    /**
     * Get a product by name
     * 
     * @param string $name
     * @return product
     */
    public static function getProductByName($name) {
        if ($result = Store::getProductIdByName($name)) {
            if ($row = $result->fetchObject()) {
                return self::getProduct($row->id);
            }
        }
        throw new Exception (Helper::getLang(Errors::ERROR_FILE_INCLUDE_NOTFOUND) . ' @ ' . __METHOD__);
    }

    /**
     * Get all products
     * 
     * @return resultset
     */
    public static function getProducts () {
        return Store::getProducts();
    }
    
    /**
     * Create a new product
     * 
     * @return type
     */
    public static function newProduct() {
        $productid = Store::insertProduct();
        return true;
    }

    /**
     * remove a product
     * 
     * @param product $product
     * @return type
     */
    public static function removeProduct($product) {
        if ($product->isRemovable()) {
            Store::deleteProduct($product->getId());
            unset(self::$products[$product->getId()]);
            return true;
        }
        return false;
    }

}