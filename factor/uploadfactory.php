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
 * Create the upload page
 *
 * @since 0.4.0
 */
class UploadFactory extends ContentFactory {
    
    /**
     * Get the content to put in the content root position in the snippet
     * 
     * @return string
     */
    protected function getRootContent() {
        $structure = Structures::getStructureByName(LSSNames::STRUCTURE_ADMIN_FILE_INPUT)->getVersion($this->getMode(), $this->getContext())->getBody();
        $urlparts = Request::getURL()->getURLParts();
        $objectid = $urlparts[0];
        $positionnr = $urlparts[1];
        // check several things and then fill in the terms
        if ($object = Objects::getObject($objectid)) {
            if ($position = $object->getVersion($this->getMode())->getPosition($positionnr)) {
                if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_CONTENTITEM) {
                    $contentitem = $position->getPositionContent();
                    if ($contentitem->getInputType() == PositionContentItem::INPUTTYPE_UPLOADEDFILE) {
                        $id = 'U' . $object->getId() . '_P' . $position->getNumber();
                        $command = CommandFactory::editPositionContentItemBody($contentitem);
                        $value = $contentitem->getBody();
                        // use a safe version of the label, because it is used as an input name in the post
                        $label = Helper::getURLSafeString(Helper::getLang(AdminLabels::ADMIN_POSITION_CONTENT_ITEM_UPLOAD));
                        $admin = str_replace(Terms::ADMIN_ID, $id, $structure);
                        $admin = str_replace(Terms::ADMIN_OBJECT_ID, $object->getId(), $admin);
                        $admin = str_replace(Terms::ADMIN_POSITION_NUMBER, $position->getNumber(), $admin);
                        $admin = str_replace(Terms::ADMIN_COMMAND, $command, $admin);
                        $admin = str_replace(Terms::ADMIN_VALUE, $value, $admin);
                        $admin = str_replace(Terms::ADMIN_LABEL, $label, $admin);                       
                        // show the current filename
                        $admin = str_replace(Terms::ADMIN_CURRENT_LABEL, Helper::getLang(AdminLabels::ADMIN_POSITION_CONTENT_ITEM_CURRENT_VALUE), $admin);                       
                        $admin = str_replace(Terms::ADMIN_CURRENT_VALUE, $value, $admin);                        
                        return $admin;
                    }
                }
            }
        }
        Messages::Add(Helper::getLang(Errors::MESSAGE_VALUE_NOT_ALLOWED));
        return '';
    }
}