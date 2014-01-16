<?php
/**
 * A referral type defines what selection will be done on the addressable objects
 * with the specified argument
 *
 * @since 0.4.0
 */
class ReferralType extends ValueListEntity {
    const ALLITEMS = 1; // all items
    const NEWITEMS = 2; // only the newest items, by createdate, number of items is specified in a param
    const SELECTEDITEM = 3; // a specific item, selected in a param
    const SELECTEDVALUE = 4; // with a specific value in the content, specified in a param
    
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableReferralTypes();
        $this->initAttributes();
    }
    
}

?>
