<?php

/*
 * Contains all order types, loads them on demand and stores them for later use.
 * 

 * @since 0.4.0
 */
class OrderTypes {
    private static $ordertypes = array();
    
    /*
     * get an ordertype by id, checks whether the ordertype is loaded,
     * loads the ordertype if necessary and fills it on demand with
     * further information
     * 

     * @param ordertypeid the id of the ordertype to get
     * @return ordertype
     */
    public static function getOrderType ($ordertypeid) {
        // return an ordertype
        if (isset(self::$ordertypes[$ordertypeid])) {
            return self::$ordertypes[$ordertypeid];
        } else {
            self::$ordertypes[$ordertypeid] = new OrderType($ordertypeid);
            return self::$ordertypes[$ordertypeid];
        }
    }
}

?>
