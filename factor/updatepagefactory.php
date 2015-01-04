<?php
/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2014
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
 * Create the update page
 *
 * @since 0.4.0
 */
class UpdatePageFactory extends ContentFactory {
    private $status = AdminLabels::ADMIN_UPDATE_STATUS_WAITING;

    /**
     * Get the content to put in the content root position in the snippet
     * 
     * @return string
     */
    protected function getRootContent() {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_UPDATE_INPUT)->getVersion($this->getMode(), $this->getContext())->getBody();
        $id = 'Update';
        $label = Helper::getURLSafeString(Helper::getLang(AdminLabels::ADMIN_BUTTON_UPLOAD_UPDATE));
        $admin = str_replace(Terms::ADMIN_ID, $id, $structure);
        $admin = str_replace(Terms::ADMIN_LABEL, $label, $admin);                  
        // show the current filename
        $admin = str_replace(Terms::ADMIN_CURRENT_LABEL, Helper::getLang(AdminLabels::ADMIN_UPDATE_STATUS), $admin);                       
        $admin = str_replace(Terms::ADMIN_CURRENT_VALUE, Helper::getLang($this->getStatus()), $admin);
        return $admin;
    }
    
    /**
     * set the status
     * 
     * @param string $status
     * @return string
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * get the status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

}