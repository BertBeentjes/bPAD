<?php
/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2015
 * http://www.bertbeentjes.nl, http://www.bpadcms.nl
 * 
 * This file is part of the bPAD content management system.
 * 
 * bPAD is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * bPAD is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with bPAD.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Objects have versions in different modes: editmode, viewmode, archivemode
 *
 * @since 0.4.0
 */
class Mode extends ValueListEntity {

    const NOMODE = 0; // used to check authorizations independent of mode
    const ARCHIVEMODE = 1; // archived items, no longer active
    const VIEWMODE = 2; // standard mode, used for viewing content
    const EDITMODE = 3; // used for editing content    

    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableModes();
        $this->initAttributes();
    }
    
}