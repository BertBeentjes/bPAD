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
 * Create updates
 *
 * @since 0.4.0
 */
class UpdateFactory extends Factory {

    /**
     * factor the content for an update
     * 
     * @return string
     */
    public function factor() {
        $content = '';
        // add update info
        $content .= $this->updateHeader();
        $content .= $this->updateLayouts();
        $content .= $this->updateStyles();
        $content .= $this->updateStructures();
        // put all json in a record
        $this->setContent($this->jsonObject($content));  
    }
    
    /**
     * create the header info for the update
     * 
     * @return string
     */
    private function updateHeader() {
        $content = '';
        $time = new DateTime('NOW');
        $content .= $this->jsonAVPair('update', $time->format(DateTime::RSS));
        return $content;
    }    
    
    /**
     * create the json for the layouts
     * 
     * @return string
     */
    private function updateLayouts() {
        $content = '';
        $layouts = Layouts::getLayouts();
        $layoutobjects = '';
        // get all layouts
        while ($layoutrow = $layouts->fetchObject()) {
            $layoutobject = '';
            $layout = Layouts::getLayout($layoutrow->id);
            // only add bpad defined layouts to the update
            if ($layout->getIsBpadDefined()) {
                $layoutobject .= $this->jsonAVPair('name', $layout->getCanonicalName());
                $layoutobject .= $this->jsonNewLineAVPair('isbpaddefined', $layout->getIsBpadDefined(), false);
                $layoutobject .= $this->jsonNewLineAVPair('set', $layout->getSet()->getCanonicalName());
                $layoutobject .= $this->jsonNewLine();
                // get the version for each context
                $contexts = Contexts::getContexts();
                $versionobjects = '';
                // loop over all contexts and add the versions
                while ($contextrow = $contexts->fetchObject()) {
                    $context = Contexts::getContext($contextrow->id);
                    $versionobject = $this->updateLayoutVersion($layout, $context);
                    if (!empty($versionobject)) {
                        if (!empty($versionobjects)) {
                            $versionobjects .= $this->jsonNewLine();
                        }                
                        $versionobjects .= $versionobject;
                    }
                }
                $layoutobject .= $this->jsonAVPair("layoutversions", $this->jsonArray($versionobjects), false);
                if (!empty($layoutobjects)) {
                    $layoutobjects .= $this->jsonNewLine();
                }
                $layoutobjects .= $this->jsonObject($layoutobject);
            }
        }
        $content = $this->jsonNewLineAVPair('layouts', $this->jsonArray($layoutobjects), false);
        return $content;
    }    

    /**
     * create the json for a layout version
     * 
     * @param layout $layout
     * @param context $context
     * @return string
     */
    private function updateLayoutVersion ($layout, $context) {
        $versionobject = '';
        $layoutversion = $layout->getVersion(Modes::getMode(Mode::VIEWMODE), $context);
        // only add original versions (other versions inherite from the originals)
        if ($layoutversion->getOriginal()) {
            $versionobject .= $this->jsonAVPair('body', $layoutversion->getBody());
            $versionobject .= $this->jsonNewLineAVPair('contextgroup', $layoutversion->getContext()->getContextGroup()->getCanonicalName());
            $versionobject .= $this->jsonNewLineAVPair('context', $layoutversion->getContext()->getCanonicalName());
            $versionobject = $this->jsonObject($versionobject);
        }
        return $versionobject;
    }    
    
    /**
     * create the json for the styles
     * 
     * @return string
     */
    private function updateStyles() {
        $content = '';
        $styles = Styles::getStyles();
        $styleobjects = '';
        // get all styles
        while ($stylerow = $styles->fetchObject()) {
            $styleobject = '';
            $style = Styles::getStyle($stylerow->id);
            // only add bpad defined styles to the update
            if ($style->getIsBpadDefined()) {
                $styleobject .= $this->jsonAVPair('name', $style->getCanonicalName());
                $styleobject .= $this->jsonNewLineAVPair('isbpaddefined', $style->getIsBpadDefined(), false);
                $styleobject .= $this->jsonNewLineAVPair('set', $style->getSet()->getCanonicalName());
                $styleobject .= $this->jsonNewLineAVPair('styletype', $style->getStyleType());
                $styleobject .= $this->jsonNewLineAVPair('classsuffix', $style->getClassSuffix());
                $styleobject .= $this->jsonNewLine();
                // get the version for each context
                $contexts = Contexts::getContexts();
                $versionobjects = '';
                // loop over all contexts and add the versions
                while ($contextrow = $contexts->fetchObject()) {
                    $context = Contexts::getContext($contextrow->id);
                    $versionobject = $this->updateStyleVersion($style, $context);
                    if (!empty($versionobject)) {
                        if (!empty($versionobjects)) {
                            $versionobjects .= $this->jsonNewLine();
                        }                
                        $versionobjects .= $versionobject;
                    }
                }
                $styleobject .= $this->jsonAVPair("styleversions", $this->jsonArray($versionobjects), false);
                if (!empty($styleobjects)) {
                    $styleobjects .= $this->jsonNewLine();
                }
                $styleobjects .= $this->jsonObject($styleobject);
            }
        }
        $content = $this->jsonNewLineAVPair('styles', $this->jsonArray($styleobjects), false);
        return $content;
    }    

    /**
     * create the json for a style version
     * 
     * @param style $style
     * @param context $context
     * @return string
     */
    private function updateStyleVersion ($style, $context) {
        $versionobject = '';
        $styleversion = $style->getVersion(Modes::getMode(Mode::VIEWMODE), $context);
        // only add original versions (other versions inherite from the originals)
        if ($styleversion->getOriginal()) {
            $versionobject .= $this->jsonAVPair('body', $styleversion->getBody());
            $versionobject .= $this->jsonNewLineAVPair('contextgroup', $styleversion->getContext()->getContextGroup()->getCanonicalName());
            $versionobject .= $this->jsonNewLineAVPair('context', $styleversion->getContext()->getCanonicalName());
            $versionobject = $this->jsonObject($versionobject);
        }
        return $versionobject;
    }    
    
    /**
     * create the json for the structures
     * 
     * @return string
     */
    private function updateStructures() {
        $content = '';
        $structures = Structures::getStructures();
        $structureobjects = '';
        // get all structures
        while ($structurerow = $structures->fetchObject()) {
            $structureobject = '';
            $structure = Structures::getStructure($structurerow->id);
            // only add bpad defined structures to the update
            if ($structure->getIsBpadDefined()) {
                $structureobject .= $this->jsonAVPair('name', $structure->getCanonicalName());
                $structureobject .= $this->jsonNewLineAVPair('isbpaddefined', $structure->getIsBpadDefined(), false);
                $structureobject .= $this->jsonNewLineAVPair('set', $structure->getSet()->getCanonicalName());
                $structureobject .= $this->jsonNewLine();
                // get the version for each context
                $contexts = Contexts::getContexts();
                $versionobjects = '';
                // loop over all contexts and add the versions
                while ($contextrow = $contexts->fetchObject()) {
                    $context = Contexts::getContext($contextrow->id);
                    $versionobject = $this->updateStructureVersion($structure, $context);
                    if (!empty($versionobject)) {
                        if (!empty($versionobjects)) {
                            $versionobjects .= $this->jsonNewLine();
                        }                
                        $versionobjects .= $versionobject;
                    }
                }
                $structureobject .= $this->jsonAVPair("structureversions", $this->jsonArray($versionobjects), false);
                if (!empty($structureobjects)) {
                    $structureobjects .= $this->jsonNewLine();
                }
                $structureobjects .= $this->jsonObject($structureobject);
            }
        }
        $content = $this->jsonNewLineAVPair('structures', $this->jsonArray($structureobjects), false);
        return $content;
    }    

    /**
     * create the json for a structure version
     * 
     * @param structure $structure
     * @param context $context
     * @return string
     */
    private function updateStructureVersion ($structure, $context) {
        $versionobject = '';
        $structureversion = $structure->getVersion(Modes::getMode(Mode::VIEWMODE), $context);
        // only add original versions (other versions inherite from the originals)
        if ($structureversion->getOriginal()) {
            $versionobject .= $this->jsonAVPair('body', $structureversion->getBody());
            $versionobject .= $this->jsonNewLineAVPair('contextgroup', $structureversion->getContext()->getContextGroup()->getCanonicalName());
            $versionobject .= $this->jsonNewLineAVPair('context', $structureversion->getContext()->getCanonicalName());
            $versionobject = $this->jsonObject($versionobject);
        }
        return $versionobject;
    }    
    
    /**
     * Create a json object
     * 
     * @param string $object the object to add
     * @return string
     */
    private function jsonObject($object) {
        return "{" . $object . "}";
    }
    
    /**
     * Create a json array
     * 
     * @param string $array the array to add
     * @return string
     */
    private function jsonArray($array) {
        return "[" . $array . "]";
    }
    
    /**
     * add a new line and create a json attribute value pair 
     * 
     * @param string $attribute the attribute name
     * @param string $value the value
     * @param boolean $isstring default true, the value is a string
     * @return string
     */
    private function jsonNewLineAVPair($attribute, $value, $isstring = true) {
        return $this->jsonNewLine() . $this->jsonAVPair($attribute, $value, $isstring);
    }
    
    /**
     * create a json attribute value pair
     * 
     * @param string $attribute the attribute name
     * @param string $value the value
     * @param boolean $isstring default true, the value is a string
     * @return string
     */
    private function jsonAVPair($attribute, $value, $isstring = true) {
        // when the value is a string, encode the string and add double quotes
        if ($isstring) {
            return '"' . $attribute . '" : ' . json_encode($value);
        }
        return '"' . $attribute . '" : ' . $value;        
    }
    
    /**
     * Get the content to put in the content root position in the snippet
     * 
     * @param string $attribute the attribute name
     * @param string $value the value
     * @return string
     */
    private function jsonNewLine() {
        return ", \n";
    }
    
}