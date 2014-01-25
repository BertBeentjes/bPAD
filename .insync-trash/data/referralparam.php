<?php
/**
 * Parameters for a referral, that define the objects to show in the referral
 * For each position referral, all possible parameters are defined, but only
 * the ones for the current referral type are shown and editable. When the 
 * referral type is changed by the user, the alternate parameters are shown.
 *

 * @since 0.4.0
 */
class ReferralParam extends StoredEntity{
    private $value; // the value for the parameter
    private $referraltypeparam; // the referral type for this parameter
    private $positionreferral; // the position referral this parameter is used for
    
    /**
     * Get the referral param from the store
     * 

     * @param int $id
     */
    public function __construct($positionreferral, $attr) {
        $this->id = $attr->id;
        $this->positionreferral = $positionreferral;
        $this->tablename = Store::getTableReferralParam();
        $this->initAttributes($attr);
    }
    
    /**
     * init the referral param
     * 

     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->value =  $attr->value;
        $this->referraltypeparam = ReferralTypeParams::getReferralTypeParam($attr->referraltypeparamid);
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * The value of the referral param
     * 

     * @return string
     */
    public function getValue() {
        return $this->value;
    }
    
    /**
     * set the value for the referral param
     * 

     * @param string $newvalue
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setValue($newvalue) {
        if (Store::setReferralParamValue($this->id,  $newvalue) && parent::setChanged()) {
            $this->value = $newvalue;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * The referral type param of the referral param
     * 

     * @return referraltypeparam
     */
    public function getReferralTypeParam() {
        return $this->referraltypeparam;
    }
    
    /**
     * set the referral type param 
     * 

     * @param referraltypeparam
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setReferralTypeParam($newreferraltypeparam) {
        if (Store::setReferralParamReferralTypeParamId($this->id,  $newreferraltypeparam->getId()) && parent::setChanged()) {
            $this->referraltypeparam = $newreferraltypeparam;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * The position referral of the referral param
     * 

     * @return positionreferral
     */
    public function getPositionReferral() {
        return $this->positionreferral;
    }
    
    /**
     * set the position referral 
     * 

     * @param positionreferral
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setPositionReferral($newpositionreferral) {
        if (Store::setReferralParamPositionReferralId($this->id,  $newpositionreferral->getId()) && parent::setChanged()) {
            $this->positionreferral = $newpositionreferral;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}

?>
