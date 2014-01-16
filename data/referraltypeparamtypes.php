<?php

/*
 * Contains all referraltypeparamtypes, loads them on demand and stores them for later use.
 * 

 * @since 0.4.0
 */
class ReferralTypeParamTypes {
    private static $referraltypeparamtypes = array();
    
    /*
     * get a referraltypeparamtype by id, checks whether the referraltypeparamtype is loaded,
     * loads the referraltypeparamtype if necessary and fills it on demand with
     * further information
     * 

     * @param referraltypeparamtypeid the id of the referraltypeparamtype to get
     * @return referraltypeparamtype
     */
    public static function getReferralTypeParamType ($referraltypeparamtypeid) {
        // return an referraltypeparamtype
        if (isset(self::$referraltypeparamtypes[$referraltypeparamtypeid])) {
            return self::$referraltypeparamtypes[$referraltypeparamtypeid];
        } else {
            self::$referraltypeparamtypes[$referraltypeparamtypeid] = new ReferralTypeParamType($referraltypeparamtypeid);
            return self::$referraltypeparamtypes[$referraltypeparamtypeid];
        }
    }
}

?>
