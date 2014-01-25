<?php

/*
 * Contains all referraltypes, loads them on demand and stores them for later use.
 * 

 * @since 0.4.0
 */
class ReferralTypes {
    private static $referraltypes = array();
    
    /*
     * get a referraltype by id, checks whether the referraltype is loaded,
     * loads the referraltype if necessary and fills it on demand with
     * further information
     * 

     * @param referraltypeid the id of the referraltype to get
     * @return referraltype
     */
    public static function getReferralType ($referraltypeid) {
        // return an referraltype
        if (isset(self::$referraltypes[$referraltypeid])) {
            return self::$referraltypes[$referraltypeid];
        } else {
            self::$referraltypes[$referraltypeid] = new ReferralType($referraltypeid);
            return self::$referraltypes[$referraltypeid];
        }
    }
}

?>
