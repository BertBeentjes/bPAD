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
 * Receive an uploaded file and store it in the right place, create versions
 * with smaller sizes
 *
 * @since 0.4.0
 */
class ExecuteUpload {

    var $mode; // the mode
    var $uploadfieldname; // the name of the upload field
    var $target; // the target folder and filename
    var $folder; // the folder

    /**
     * The constructor for the execution of an upload
     */

    public function __construct() {
        $this->uploadfieldname = Helper::getURLSafeString(Helper::getLang(AdminLabels::ADMIN_POSITION_CONTENT_ITEM_UPLOAD));
    }

    /**
     * upload a file and create versions with different sizes
     */
    public function uploadFile() {
        $urlparts = Request::getURL()->getURLParts();
        $objectid = $urlparts[0];
        $positionnr = $urlparts[1];
        // get the object, if it exists
        if ($object = Objects::getObject($objectid)) {
            // check object authorization
            if (Authorization::getObjectPermission($object, Authorization::OBJECT_MANAGE) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_EDIT) || Authorization::getObjectPermission($object, Authorization::OBJECT_FRONTEND_CREATOR_EDIT)) {
                // get the position, if it exists
                if ($position = $object->getVersion($this->getMode())->getPosition($positionnr)) {
                    // check that the position is a content item
                    if ($position->getPositionContent()->getType() == PositionContent::POSITIONTYPE_CONTENTITEM) {
                        $contentitem = $position->getPositionContent();
                        // check that the position is a file upload
                        if ($contentitem->getInputType() == PositionContentItem::INPUTTYPE_UPLOADEDFILE) {
                            // verify the file
                            $this->folder = $contentitem->calculateFolder();
                            if ($this->verifyFile()) {
                                // try to move the file
                                if ($this->moveFile()) {
                                    // Success!
                                    $this->doChange($contentitem, $position);
                                    return true;
                                }
                                return;
                            }
                            Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 6');
                            return;
                        }
                        Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 5');
                        return;
                    }
                    Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 4');
                    return;
                }
                Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 3');
                return;
            }
            Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 2');
            return;
        }
        Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 1');
        return;
    }

    /**
     * Store the file upload in a command and in the content item
     * 
     * @param positioncontentitem $contentitem
     * @param position $position
     */
    private function doChange($contentitem, $position) {
        // get the command to change the content item body
        $command = CommandFactory::editPositionContentItemBody($contentitem) . ',0.0.0';
        // TODO: create events, one event must deactivate uploads for the same position on other systems when an upload is done, because the command number doesn't work here
        
        // dirty bit: store it in the request. Not exactly pretty, but it works :) 
        Request::parseCommand($command, $this->target);
        // now do the changes
        Execute::changePosition($position);
    }

    /**
     * Move the uploaded file to the correct location
     * 
     * @return boolean true if success
     */
    private function moveFile() {
        if (is_uploaded_file($_FILES[$this->uploadfieldname]['tmp_name'])) {
            // clean the folder
            $files = glob($this->folder . '/*'); // get all file names
            if (isset($files)) {
                if (count($file) > 0) {
                    foreach ($files as $file) { // iterate files
                        if (is_file($file)) {
                            unlink($file); // delete file
                        }
                    }
                }
            }
            // add the new file to the folder
            if (move_uploaded_file($_FILES[$this->uploadfieldname]['tmp_name'], $this->folder . "/" . $this->target)) {
                return true;
            }
            Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 9');
        }
        Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 10 ' . $_FILES[$this->uploadfieldname]['tmp_name']);
        return false;
    }

    /**
     * Verify that the file is correct
     * 
     * @return boolean true if success
     */
    private function verifyFile() {
        $filename = Helper::getURLSafeString(pathinfo($_FILES[$this->uploadfieldname]['name'], PATHINFO_FILENAME));
        $extension = '.' . Helper::getURLSafeString(pathinfo($_FILES[$this->uploadfieldname]['name'], PATHINFO_EXTENSION));
        $this->target = $filename . $extension;
        if (!is_dir($this->folder)) {
            // create the directory for this object
            mkdir($this->folder, intval(Settings::getSetting(Setting::SITE_UPLOAD_LOCATION_PERMISSIONS)->getValue(), 8), true);
        }
        if ($_FILES[$this->uploadfieldname]["size"] > Settings::getSetting(Setting::SITE_MAXUPLOADSIZE)->getValue()) {
            Messages::Add(Helper::getLang(Errors::MESSAGE_INVALID_COMMAND) . ' @ 8');
            return false;
        }
        return true;
    }

    /**
     * Return the mode
     * 
     * @return mode
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * Set the mode
     * 
     * @param mode $mode
     */
    public function setMode($mode) {
        $this->mode = $mode;
    }

}

?>
