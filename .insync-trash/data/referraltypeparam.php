<?php
/**
 * Parameters for a referral type, used to create the actual parameters for a
 * position referral
 *

 * @since 0.4.0
 */
class ReferralTypeParam {
    private $id; // the id
    private $name; // the name for the parameter
    private $referraltype; // the referral type this is a parameter for
    private $referraltypeparamtype; // the type of parameter this is 
    
    /**
     * Get the referral param from the store
     * 

     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes for the referral type param
     * 

     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getReferralTypeParam($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->id . ' @ ' . __METHOD__);
    }
    
    /**
     * init the referral type param
     * 

     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->name =  $attr->name;
        $this->referraltype = ReferralTypes::getReferralType($attr->referraltypeid);
        $this->referraltypeparamtype = ReferralTypeParamTypes::getReferralTypeParamType($attr->referraltypeparamtypeid);
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * The name of the referral param
     * 

     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * set the name for the referral param
     * 

     * @param string $newname
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setName($newname) {
        if (Store::setReferralTypeParamName($this->id,  $newname)) {
            $this->name = $newname;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * The referral type of the referral type param
     * 

     * @return referraltype
     */
    public function getReferralType() {
        return $this->referraltype;
    }
    
    /**
     * set the referral type 
     * 

     * @param referraltype
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setReferralType($newreferraltype) {
        if (Store::setReferralTypeParamReferralTypeId($this->id, $newreferraltype->getId())) {
            $this->referraltype = $newreferraltype;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * The referral type param type of the referral param
     * 

     * @return referraltypeparamtype
     */
    public function getReferralTypeParamType() {
        return $this->referraltypeparamtype;
    }
    
    /**
     * set the referral type param type
     * 

     * @param referraltypeparamtype
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setReferralTypeParamType($newreferraltypeparamtype) {
        if (Store::setReferralTypeParamReferralTypeParamTypeId($this->id, $newreferraltypeparamtype->getId()) && parent::setChanged()) {
            $this->referraltypeparamtype = $newreferraltypeparamtype;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}

?>
