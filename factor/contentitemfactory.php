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
 * Factory for content items, the actual textual and graphic content for the site
 *
 * @since 0.4.0
 */
class ContentItemFactory extends Factory {
    private $contentitem; // the content item to factor
    private $filename; // file name for a file
    private $extension; // extension for a file
    private $folder; // folder location for a file

    /**
     * initialize the content item factory
     * 
     * @param positioncontentitem $contentitem
     * @param context $context
     * @param mode $mode
     */
    public function __construct($contentitem, $context, $mode) {
        // do some input checking
        if (is_object($contentitem) && is_object($context) && is_object($mode)) {
            $this->setContext($context);
            $this->setMode($mode);
            $this->setContentItem($contentitem);
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_FACTORY_NOT_INITIALIZED_CORRECTLY) . ' @ ' . __METHOD__);
        }
    }

    /**
     * set the content item for the factory
     * 
     * @param positioncontentitem $newcontentitem
     */
    public function setContentItem($newcontentitem) {
        $this->contentitem = $newcontentitem;
    }

    /**
     * Get the content item for this factory
     * 
     * @return positioncontentitem
     */
    public function getContentItem() {
        return $this->contentitem;
    }

    /**
     * factor a content item
     * 
     * @return boolean true if success
     */
    public function factor() {
        $this->setContent($this->getContentItem()->getBody());
        // if there is content return the content
        if ($this->getContentItem()->getInputType() == PositionContentItem::INPUTTYPE_UPLOADEDFILE) {
            $this->factorFileName();
        } else {
            $this->factorContent();
        }
        return true;
    }
    
    /**
     * Get a shortened version of the content
     * 
     * @return string
     */
    function getShortContent() {
        $content = $this->getContent();
        $content = str_replace("\r\n", "\n", $content);
        $content = str_replace("\r", "\n", $content);
        $content = str_replace("\n", " ", $content);
        $content = str_replace("  ", " ", $content);
        $content = strip_tags($content);
        if (strlen($content) > 250) {
            $content = substr($content, 0, 250);
            $lastspace = strrpos($content, ' ');
            if ($lastspace > 230) {
                $content = substr($content, 0, $lastspace);
            }
            $content .= '...';
        }
        $content = str_replace('"', "'", $content);
        $content = str_replace('....', '...', $content);
        return $content;
    }

    /**
     * return the file name for an uploaded file
     * 
     * @return string
     */
    public function getFileName() {
        return $this->filename;
    }

    /**
     * set the file name for an uploaded file
     * 
     * @param string
     */
    private function setFileName($newfilename) {
        $this->filename = $newfilename;
    }

    /**
     * return the extension for an uploaded file
     * 
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * set the file name for an uploaded file
     * 
     * @param string
     */
    private function setExtension($newextension) {
        $this->extension = $newextension;
    }

    /**
     * return the folder location for an uploaded file
     * 
     * @return string
     */
    public function getFolder() {
        return $this->folder;
    }

    /**
     * set the folder location for an uploaded file
     * 
     * @param string
     */
    private function setFolder($newfolder) {
        $this->folder = $newfolder;
    }

    /**
     * factor a file name
     * 
     */
    private function factorFileName() {
        if ($this->getContent() > '') {
            // don't change the body for uploaded files, it contains the url to the file
            if (substr($this->getContent(), 0, 1) != '/') {
                // if the file location is defined relative, make it absolute
                // otherwise it won't work for seo urls
                $this->setContent('/' . $this->getContent());
            }
            $this->setFolder('/' . $this->getContentItem()->calculateFolder() . '/');
            $this->setFileName(pathinfo($this->getContent(), PATHINFO_FILENAME));
            $this->setExtension('.' . pathinfo($this->getContent(), PATHINFO_EXTENSION));
        }
    }

    /**
     * factor the content in the content item
     * 
     */
    private function factorContent() {       
        // escape some special html characters, at this moment there is no html
        // in the content
        // TODO: this is a html specific function, maybe this function must be context
        // sensitive, and only be called when the output is html
        $this->setContent(Helper::escapeHTMLSpecialChars($this->getContent()));
        // escape some special bPAD marker characters
        $this->escapeNonMarkers();
        // explode into paragraphs
        $paragraphs = explode("\n\n", $this->getContent());
        $this->setContent($this->factorParagraphs($paragraphs));
        $this->deescapeNonMarkers();
    }
    
    /**
     * factor the content in the paragraphs
     * 
     * @param array paragraphs
     * @return string content
     */
    private function factorParagraphs($paragraphs) {
        $returnvalue = "";
        $arraycount = count($paragraphs);
        // do the paragraphs
        $specialmarkup = false; // ????
        foreach ($paragraphs as $paragraph) {
            // if there is more than one paragraph, or the input is a text area
            // do paragraph markup. This excludes paragraph markup for single line
            // content
            if ($arraycount > 1 || $this->getContentItem()->getInputType() == PositionContentItem::INPUTTYPE_TEXTAREA) {
                $specialmarkup = false;
                if (strpos($paragraph, '{') === 0 || strpos($paragraph, '+') === 1) {
                    if (strpos($paragraph, '{') === 0) {
                        $paragraph = $this->replaceMarkers('/(^\{)(.+)(\}$)/', LSSNames::STRUCTURE_CENTERED_TEXT, '$2', $paragraph, "");
                        $specialmarkup = true;
                    }
                    if (strpos($paragraph, '1+') === 0) {
                        $paragraph = $this->replaceMarkers('/(^1\+)(.+)/', LSSNames::STRUCTURE_H1, '$2', $paragraph, "");
                        $specialmarkup = true;
                    }
                    if (strpos($paragraph, '2+') === 0) {
                        $paragraph = $this->replaceMarkers('/(^2\+)(.+)/', LSSNames::STRUCTURE_H2, '$2', $paragraph, "");
                        $specialmarkup = true;
                    }
                    if (strpos($paragraph, '3+') === 0) {
                        $paragraph = $this->replaceMarkers('/(^3\+)(.+)/', LSSNames::STRUCTURE_H3, '$2', $paragraph, "");
                        $specialmarkup = true;
                    }
                    if (strpos($paragraph, '4+') === 0) {
                        $paragraph = $this->replaceMarkers('/(^4\+)(.+)/', LSSNames::STRUCTURE_H4, $paragraph, "");
                        $specialmarkup = true;
                    }
                    if (strpos($paragraph, '5+') === 0) {
                        $paragraph = $this->replaceMarkers('/(^5\+)(.+)/', LSSNames::STRUCTURE_H5, '$2', $paragraph, "");
                        $specialmarkup = true;
                    }
                }
                // if there is no special markup, apply the default paragraph markup
                if ($specialmarkup == false) {
                    $returnvalue .= $this->getStructureBodyByName(LSSNames::STRUCTURE_PARAGRAPH_START);
                }
            }
            $lines = explode("\n", $paragraph);
            $firstline = true;
            // $ulon is used to trace the start/end of unordered lists
            // TODO: create lists with multiple levels, the current solution only supports lists of one level deep
            $ulon = false;
            foreach ($lines as $line) {
                // format the line
                $addbr = true;
                if (strpos($line, '*') > -1) {
                    $line = $this->replaceMarkers("/([*])([^*]+)([*])/", LSSNames::STRUCTURE_STRONG, '$2', $line, "");
                }
                if (strpos($line, '^') > -1) {
                    $line = $this->replaceMarkers("/([\^])([^\^]+)([\^])/", LSSNames::STRUCTURE_ACCENT, '$2', $line, "");
                }
                if (strpos($line, '_') > -1) {
                    $line = $this->replaceMarkers("/([_])([^_]+)([_])/", LSSNames::STRUCTURE_ITALIC, '$2', $line, "");
                }
                if (strpos($line, '[') > -1) {
                    $line = $this->replaceMarkers("/([\[])([0-9]+)([\|])/", LSSNames::STRUCTURE_INTERNAL_LINK_START, '$2', $line, "");
                    $line = $this->replaceMarkers("/([\|][\|])([^\]^\[^\|]+)([\]])/", LSSNames::STRUCTURE_INTERNAL_LINK_END, '$2', $line, "");
                }
                if (strpos($line, '[') > -1) {
                    $line = $this->replaceMarkers("/([\[])([^\|^\[^\]]+)([\|])/", LSSNames::STRUCTURE_EXTERNAL_LINK_START, '$2', $line, "");
                    $line = $this->replaceMarkers("/([\|][\|])([^\]^\[^\|]+)([\]])/", LSSNames::STRUCTURE_EXTERNAL_LINK_END, '$2', $line, "");
                }
                if (preg_match('/(^\-)(.+)/', $line) > 0) {
                    $line = $this->replaceMarkers('/(^\-)(.+)/', LSSNames::STRUCTURE_LIST_ITEM, '$2', $line, "");
                    $addbr = false;
                    if (!$ulon) {
                        $line =  $this->getStructureBodyByName(LSSNames::STRUCTURE_LIST_START) . $line;
                        $ulon = true;
                    }
                } else {
                    if ($ulon) {
                        $ulon = false;
                        $line = $this->getStructureBodyByName(LSSNames::STRUCTURE_LIST_END) . $line;
                        $addbr = false;
                    }
                }
                if ($firstline) {
                    $firstline = false;
                } else {
                    if ($addbr) {
                        $line = $this->getStructureBodyByName(LSSNames::STRUCTURE_NEW_LINE) . $line;
                    }
                }
                $returnvalue .= $line;
            }
            if ($ulon) {
                $ulon = false;
                $returnvalue .= $this->getStructureBodyByName(LSSNames::STRUCTURE_LIST_END);
            }
            if ($specialmarkup == false && ($arraycount > 1 || $this->getContentItem()->getInputType() == PositionContentItem::INPUTTYPE_TEXTAREA)) {
                $returnvalue .= $this->getStructureBodyByName(LSSNames::STRUCTURE_PARAGRAPH_END);
            }
        }
        return $returnvalue;
    }

    /**
     * get the body of the structure that has the markup for this item, based upon the structure name
     * 
     * @param string $name
     * @return string
     */
    private function getStructureBodyByName ($name) {
        $structurebody = Structures::getStructureByName($name)->getVersion($this->getMode(), $this->getContext())->getBody();
        return $structurebody;
    }
    
    /**
     * escape user input that are no markers (double chars)
     * 
     */
    private function escapeNonMarkers() {
        $trans = array('**' => '###1###', '[[' => '###2###', '||' => '###3###', ']]' => '###4###', '__' => '###5###', '^^' => '###6###', '{{' => '###7###', '}}' => '###8###', '++' => '###9###');
        $this->setContent(strtr($this->getContent(), $trans));
    }

    /**
     * and return the user input to the required char
     * 
     */
    private function deescapeNonMarkers() {
        // clean up stuff
        $trans = array('[' => '', ']' => '', '|' => '');
        $this->setContent(strtr($this->getContent(), $trans));

        // deescape
        $trans = array('###1###' => '*', '###2###' => '[', '###3###' => '|', '###4###' => ']', '###5###' => '_', '###6###' => '^', '###7###' => '{', '###8###' => '}', '###9###' => '+');
        $this->setContent(strtr($this->getContent(), $trans));
    }

    /**
     * replace markers with a structure, to create the required markup
     *
     */
    private function replaceMarkers($pattern, $replacementname, $part, $line, $postfix) {
        global $characterstructures;
        $arguments = array();
        if (preg_match($pattern, $line) > 0) {
            $line .= $postfix;
            $replacement = $this->getStructureBodyByName($replacementname);
            if (preg_match(Terms::POSITION_CONTENT, $replacement) > 0) {
                $replacement = str_replace(Terms::POSITION_CONTENT, $part, $replacement);
                $line = preg_replace($pattern, $replacement, $line);
            } else {
                // for internal links do something special with the first part
                if ($replacementname == LSSNames::STRUCTURE_INTERNAL_LINK_START) {
                    while (preg_match($pattern, $line, $matches) > 0) {
                        reset($matches);
                        while (next($matches)) {
                            if (Validator::isNumeric(current($matches))) {
                                $objectid = current($matches);
                                // try to get the object, if an exception occurred,
                                // the object isn't available and silently remove the object
                                // reference 
                                try {
                                    $object = Objects::getObject($objectid);
                                    if ($object->isVisible($this->getMode(), $this->getContext())) {
                                        // create the reference to the object
                                        $thisreplacement = str_replace(Terms::POSITION_REFERRAL, CommandFactory::getObject($object, $this->getMode(), $this->getContext()), $replacement);
                                        $thisreplacement = str_replace(Terms::POSITION_REFERRAL_URL, $object->getSEOURL($this->getMode()), $thisreplacement);
                                        $line = str_replace("[" . $objectid . "|", $thisreplacement, $line);
                                    } else {
                                        $line = str_replace("[" . $objectid . "|", "", $line);
                                    }
                                } catch (Exception $e) {
                                    $line = str_replace("[" . $objectid . "|", "", $line);
                                }
                            }
                        }
                    }
                }
            }
        }
        return $line;
    }

}