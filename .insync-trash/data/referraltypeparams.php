<?php

/*
 * Contains all referraltypeparams, loads them on demand and stores them for later use.
 * 

 * @since 0.4.0
 */
class ReferralTypeParams {
    private static $referraltypeparams = array();
    
    /*
     * get a referraltypeparam by id, checks whether the referraltypeparam is loaded,
     * loads the referraltypeparam if necessary and fills it on demand with
     * further information
     * 

     * @param referraltypeparamid the id of the referraltypeparam to get
     * @return referraltypeparam
     */
    public static function getReferralTypeParam ($referraltypeparamid) {
        // return an referraltypeparam
        if (isset(self::$referraltypeparams[$referraltypeparamid])) {
            return self::$referraltypeparams[$referraltypeparamid];
        } else {
            self::$referraltypeparams[$referraltypeparamid] = new ReferralTypeParam($referraltypeparamid);
            return self::$referraltypeparams[$referraltypeparamid];
        }
    }
}

?>
