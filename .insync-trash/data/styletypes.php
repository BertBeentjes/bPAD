<?php

/*
 * Contains all styletypes, loads them on demand and stores them for later use.
 * 

 * @since 0.4.0
 */
class StyleTypes {
    private static $styletypes = array();
    
    /*
     * get a styletype by id, checks whether the styletype is loaded,
     * loads the styletype if necessary and fills it on demand with
     * further information
     * 

     * @param styletypeid the id of the styletype to get
     * @return styletype
     */
    public static function getStyleType ($styletypeid) {
        // return an styletype
        if (isset(self::$styletypes[$styletypeid])) {
            return self::$styletypes[$styletypeid];
        } else {
            self::$styletypes[$styletypeid] = new StyleType($styletypeid);
            return self::$styletypes[$styletypeid];
        }
    }
}

?>
