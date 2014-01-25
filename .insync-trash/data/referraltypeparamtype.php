<?php
/**
 * A referral type param type defines the type of content the referral type
 * param takes
 *

 * @since 0.4.0
 */
class ReferralTypeParamType extends ValueListEntity {
    const CHECKBOX = 1; // a checkbox
    const STRUCTURESELECT = 2; // select a structure
    const OBJECTSELECT = 3; // select an object
    const NUMBER = 4; // a number, int
    const TEXT = 5; // a string (max 255 chars)
    
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableReferralTypeParamTypes();
        $this->initAttributes();
    }
    
}

?>
