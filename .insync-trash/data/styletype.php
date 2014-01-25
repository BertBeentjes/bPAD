<?php
/**
 * A style is either of the object type or of the position type, the object type
 * is applied to layouts, the position type to structures.
 *

 * @since 0.4.0
 */
class StyleType extends ValueListEntity {
    const OBJECTSTYLE = 1; // style used for layouts in objects
    const POSITIONSTYLE = 2; // style used for structures in positions
    
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableStyleTypes();
        $this->initAttributes();
    }
    
}

?>
