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
 * File handler, returns a file from the file system
 *
 * @since 0.4.0
 */
class File extends Respond {

    private $filefound = false; // has the requested file been found?
    private $file; // the file to show, can be a resized version of the original

    /**
     * Construct the file handler, read the request
     * TODO: plug the hole where people can fill the disk and tax the cpu
     * by requesting more and more images with different sizes.  
     */
    public function __construct() {
        $urlparts = Request::getURL()->getURLParts();
        $object = Objects::getObject(substr($urlparts[3], 6));
        if (Authorization::getObjectPermission($object, Authorization::OBJECT_VIEW)) {
            $this->file = Request::getURL()->getFullURL();
            if (file_exists($this->file)) {
                if ($this->isImage()) {
                    $this->filefound = $this->checkImageSize();
                } else {
                    $this->filefound = true;
                }
            } else {
                Messages::Add(Helper::getLang(Errors::MESSAGE_FILE_NOT_FOUND) . ': ' . Request::getURL()->getFullURL());
            }
        } else {
            Messages::Add(Helper::getLang(Errors::MESSAGE_NOT_AUTHORIZED));
        }
    }

    /**
     * send the file to the client
     */
    public function respond() {
        if ($this->filefound) {
            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for i.e.
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            header("Content-Type: " . finfo_file($finfo, $this->file));
            finfo_close($finfo);
            header("Content-Length:" . filesize($this->file));
            header('Content-Disposition: inline; filename="' . $this->file . '"');
            readfile($this->file);
            // the stream has been created, so die now
            die();
        } else {
            header("Content-Type: text/plain");
            echo Messages::Show();
        }
    }

    /**
     * check whether alternative sizes are requested
     */
    private function checkImageSize() {
        $height = 0;
        $width = 0;
        if (isset($_GET['height'])) {
            $height = $_GET['height'];
        }
        if (isset($_GET['width'])) {
            $width = $_GET['width'];
        }
        if ($height > 0 || $width > 0) {
            $source = Request::getURL()->getFullURL();
            $folder = Request::getURL()->getFolder();
            $filename = Request::getURL()->getFileName();
            $extension = Request::getURL()->getExtension();
            $resize = 'none';
            if ($width > 0 && Validator::isNumeric($width)) {
                $filename .= '_w' . $width;
                $resize = 'width';
            }
            if ($height > 0 && Validator::isNumeric($height)) {
                $filename .= '_h' . $height;
                if ($resize == 'none') {
                    $resize = 'height';
                } else {
                    $resize = 'box';
                }
            }
            $this->file = $folder . $filename . '.' . $extension;
            if (file_exists($this->file)) {
                // the version of the file has been created before
                return true;
            } else {
                $image = new SimpleImage();
                $image->load($source);
                switch ($resize) {
                    case 'width':
                        $image->resizeToWidth($width);
                        $image->save($this->file);
                        break;
                    case 'height':
                        $image->resizeToHeight($height);
                        $image->save($this->file);
                        break;
                    case 'box':
                        $image->maxarea($width, $height);
                        $image->save($this->file);
                        break;
                }
            }
        }
        return true;
    }

    /**
     * is a sizeable image requested?
     */
    private function isImage() {
        $filetype = exif_imagetype(Request::getURL()->getFullURL());
        if ($filetype == IMAGETYPE_JPEG || $filetype == IMAGETYPE_PNG || $filetype == IMAGETYPE_GIF) {
            return true;
        }
        return false;
    }

}

?>
