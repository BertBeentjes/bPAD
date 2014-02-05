<?php
/**
 * Order type are used for ordering positions in a #pn# type layout
 *

 * @since 0.4.0
 */
class OrderType extends ValueListEntity {
    const NAMEASC = 1; // by name, ascending
    const NAMEDESC = 2; // by name, descending
    const CREATEDATEASC = 3; // by create date, ascending
    const CREATEDATEDESC = 4; // by create date, descending
    const AUTHORASC = 5; // by create user, ascending
    const AUTHORDESC = 6; // by create user, descending
    const NUMBERASC = 7; // by number, ascending
    const NUMBERDESC = 8; // by number, descending
    
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableOrderTypes();
        $this->initAttributes();
    }
    
}

?>
