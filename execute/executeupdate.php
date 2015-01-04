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
 * Receive an update and process the changes
 *
 * @since 0.4.0
 */
class ExecuteUpdate {

    /**
     * The constructor for the execution of an update
     */

    public function __construct() {
    }
    
    /**
     * Read the update file and execute the update
     * 
     * @return boolean
     */
    public function executeUpdate() {        
        $thisfile = Helper::getURLSafeString(Helper::getLang(AdminLabels::ADMIN_BUTTON_UPLOAD_UPDATE));
        // check the update file
        if ($_FILES[$thisfile]['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES[$thisfile]['tmp_name'])) {
            $updatejson = file_get_contents($_FILES[$thisfile]['tmp_name']); 
            $update = json_decode($updatejson);
            // if it contains valid json
            if (json_last_error() == JSON_ERROR_NONE) {
                $success = true;
                // TODO: log the update
                
                // execute the parts of the update
                if (is_array($update->layouts)) {
                    $success = $success && $this->updateLayouts($update->layouts);
                }
                if (is_array($update->styles)) {
                    $success = $success && $this->updateStyles($update->styles);
                }
                if (is_array($update->structures)) {
                    $success = $success && $this->updateStructures($update->structures);
                }
                return $success;
            }
            echo 'json error';
            return false;
        }        
        echo 'file upload error';       
        return false;
    }
    
    /**
     * Process the new layouts
     * 
     * @param json $layouts
     * @return boolean
     */
    private function updateLayouts($layouts) {
        // loop through the layouts
        foreach ($layouts as $newlayout) {
            if (Validator::isCanonicalName($newlayout->name)) {
                $layout = Layouts::getLayoutByName($newlayout->name);
                // if no layout has been found, create it
                if (!is_object($layout)) {
                    // create layout
                    $layout = Layouts::newLayout();
                }
                // update the layout
                if ($layout->getIsBpadDefined() != $newlayout->isbpaddefined) {
                    $layout->setIsBpadDefined($newlayout->isbpaddefined === 1);
                }
                if ($layout->getCanonicalName() != $newlayout->name) {
                    $layout->setCanonicalName($newlayout->name);
                }
                if ($layout->getSet()->getCanonicalName() != $newlayout->set) {
                    $layout->setSet($this->findSet($newlayout->set));
                }
                // update the layout versions
                $this->updateLayoutVersions($layout, $newlayout->layoutversions);
            } else {
                echo '-' . $newlayout->name . '- is not a canonical name';
            }
        }
        return true;
    }
    
    /**
     * Process the new layout versions
     * 
     * @param layout $layout
     * @param json $layoutversions
     * @return boolean
     */
    private function updateLayoutVersions($layout, $layoutversions) {
        // get the version for each context
        $contexts = Contexts::getContexts();
        $versionobjects = '';
        // loop over all contexts and add the versions
        while ($contextrow = $contexts->fetchObject()) {
            $context = Contexts::getContext($contextrow->id);
            $layoutversion = $layout->getVersion(Modes::getMode(Mode::EDITMODE), $context);
            $newlayoutversion = '';
            // loop through the new layout versions to find the new layout version
            foreach ($layoutversions as $checklayoutversion) {
                // check whether this is the same
                if ($checklayoutversion->contextgroup == $context->getContextGroup()->getCanonicalName() && $checklayoutversion->context == $context->getCanonicalName()) {
                    $newlayoutversion = $checklayoutversion;
                }
            }
            // several options:
            // the existing version is original, and there is a new version: update the original
            // the existing version is original, but there is no new version: delete the version
            // the existing version isn't original, and there is a new version: create a new version
            // the existing version isn't original, and there is no new version: do nothing
            if ($layoutversion->getOriginal()) {
                if (is_object($newlayoutversion)) {
                    if ($layoutversion->getBody() != $newlayoutversion->body) {
                        // update the body 
                        $layoutversion->setBody($newlayoutversion->body);
                        // publish the update
                        $layout->publishVersion($context);
                    }
                } else {
                    // delete the original
                    $layout->removeVersion($context);
                }
            } else {
                if (is_object($newlayoutversion)) {
                    // create a new version and update the body
                    $layout->newVersion($context);
                    $layoutversion = $layout->getVersion(Modes::getMode(Mode::EDITMODE), $context);
                    if ($layoutversion->getBody() != $newlayoutversion->body) {
                        $layoutversion->setBody($newlayoutversion->body);
                        // publish the update
                        $layout->publishVersion($context);
                    }
                } else {
                    // do nothing
                }
            }
        }
        return true;
    }
    
    /**
     * Process the new styles
     * 
     * @param json $styles
     * @return boolean
     */
    private function updateStyles($styles) {
        // loop through the styles
        foreach ($styles as $newstyle) {
            if (Validator::isCanonicalName($newstyle->name)) {
                $style = Styles::getStyleByName($newstyle->name);
                // if no style has been found, create it
                if (!is_object($style)) {
                    // create style
                    $style = Styles::newStyle();
                }
                // update the style
                if ($style->getIsBpadDefined() != $newstyle->isbpaddefined) {
                    $style->setIsBpadDefined($newstyle->isbpaddefined === 1);
                }
                if ($style->getCanonicalName() != $newstyle->name) {
                    $style->setCanonicalName($newstyle->name);
                }
                if ($style->getSet()->getCanonicalName() != $newstyle->set) {
                    $style->setSet($this->findSet($newstyle->set));
                }
                if ($style->getClassSuffix() != $newstyle->classsuffix) {
                    if (Validator::isClassSuffix($newstyle->classsuffix)) {
                        $style->setClassSuffix($newstyle->classsuffix);
                    }
                }
                if ($style->getStyleType() != $newstyle->styletype) {
                    if (Validator::validStyleType($newstyle->styletype)) {
                        $style->setStyleType($newstyle->styletype);
                    }
                }
                // update the style versions
                $this->updateStyleVersions($style, $newstyle->styleversions);
            } else {
                echo '-' . $newstyle->name . '- is not a canonical name';
            }
        }
        return true;
    }
    
    /**
     * Process the new style versions
     * 
     * @param style $style
     * @param json $styleversions
     * @return boolean
     */
    private function updateStyleVersions($style, $styleversions) {
        // get the version for each context
        $contexts = Contexts::getContexts();
        $versionobjects = '';
        // loop over all contexts and add the versions
        while ($contextrow = $contexts->fetchObject()) {
            $context = Contexts::getContext($contextrow->id);
            $styleversion = $style->getVersion(Modes::getMode(Mode::EDITMODE), $context);
            $newstyleversion = '';
            // loop through the new style versions to find the new style version
            foreach ($styleversions as $checkstyleversion) {
                // check whether this is the same
                if ($checkstyleversion->contextgroup == $context->getContextGroup()->getCanonicalName() && $checkstyleversion->context == $context->getCanonicalName()) {
                    $newstyleversion = $checkstyleversion;
                }
            }
            // several options:
            // the existing version is original, and there is a new version: update the original
            // the existing version is original, but there is no new version: delete the version
            // the existing version isn't original, and there is a new version: create a new version
            // the existing version isn't original, and there is no new version: do nothing
            if ($styleversion->getOriginal()) {
                if (is_object($newstyleversion)) {
                    if ($styleversion->getBody() != $newstyleversion->body) {
                        // update the body 
                        $styleversion->setBody($newstyleversion->body);
                        // publish the update
                        $style->publishVersion($context);
                    }
                } else {
                    // delete the original
                    $style->removeVersion($context);
                }
            } else {
                if (is_object($newstyleversion)) {
                    // create a new version and update the body
                    $style->newVersion($context);
                    $styleversion = $style->getVersion(Modes::getMode(Mode::EDITMODE), $context);
                    if ($styleversion->getBody() != $newstyleversion->body) {
                        $styleversion->setBody($newstyleversion->body);
                        // publish the update
                        $style->publishVersion($context);
                    }
                } else {
                    // do nothing
                }
            }
        }
        return true;
    }

    /**
     * Process the new structures
     * 
     * @param json $structures
     * @return boolean
     */
    private function updateStructures($structures) {
        // loop through the structures
        foreach ($structures as $newstructure) {
            if (Validator::isCanonicalName($newstructure->name)) {
                $structure = Structures::getStructureByName($newstructure->name);
                // if no structure has been found, create it
                if (!is_object($structure)) {
                    // create structure
                    $structure = Structures::newStructure();
                }
                // update the structure
                if ($structure->getIsBpadDefined() != $newstructure->isbpaddefined) {
                    $structure->setIsBpadDefined($newstructure->isbpaddefined === 1);
                }
                if ($structure->getCanonicalName() != $newstructure->name) {
                    $structure->setCanonicalName($newstructure->name);
                }
                if ($structure->getSet()->getCanonicalName() != $newstructure->name) {
                    $structure->setSet($this->findSet($newstructure->set));
                }
                // update the structure versions
                $this->updateStructureVersions($structure, $newstructure->structureversions);
            } else {
                echo '-' . $newstructure->name . '- is not a canonical name';
            }
        }
        return true;
    }
    
    /**
     * Process the new structure versions
     * 
     * @param structure $structure
     * @param json $structureversions
     * @return boolean
     */
    private function updateStructureVersions($structure, $structureversions) {
        // get the version for each context
        $contexts = Contexts::getContexts();
        $versionobjects = '';
        // loop over all contexts and add the versions
        while ($contextrow = $contexts->fetchObject()) {
            $context = Contexts::getContext($contextrow->id);
            $structureversion = $structure->getVersion(Modes::getMode(Mode::EDITMODE), $context);
            $newstructureversion = '';
            // loop through the new structure versions to find the new structure version
            foreach ($structureversions as $checkstructureversion) {
                // check whether this is the same
                if ($checkstructureversion->contextgroup == $context->getContextGroup()->getCanonicalName() && $checkstructureversion->context == $context->getCanonicalName()) {
                    $newstructureversion = $checkstructureversion;
                }
            }
            // several options:
            // the existing version is original, and there is a new version: update the original
            // the existing version is original, but there is no new version: delete the version
            // the existing version isn't original, and there is a new version: create a new version
            // the existing version isn't original, and there is no new version: do nothing
            if ($structureversion->getOriginal()) {
                if (is_object($newstructureversion)) {
                    if ($structureversion->getBody() != $newstructureversion->body) {
                        // update the body 
                        $structureversion->setBody($newstructureversion->body);
                        // publish the update
                        $structure->publishVersion($context);
                    }
                } else {
                    // delete the original
                    $structure->removeVersion($context);
                }
            } else {
                if (is_object($newstructureversion)) {
                    // create a new version and update the body
                    $structure->newVersion($context);
                    $structureversion = $structure->getVersion(Modes::getMode(Mode::EDITMODE), $context);
                    if ($structureversion->getBody() != $newstructureversion->body) {
                        $structureversion->setBody($newstructureversion->body);
                        // publish the update
                        $structure->publishVersion($context);
                    }
                } else {
                    // do nothing
                }
            }
        }
        return true;
    }

    /**
     * Find a set or create it
     * 
     * @param string $name set name
     * @return set
     */
    private function findSet($name) {
        // check the name
        if (Validator::isCanonicalName($name)) {
            // find the set
            $set = Sets::getSetByName($name);
            // if the set isn't found, create it
            if (!is_object($set)) {
                $set = Sets::newSet();
            }
        } else {
            // if the name is incorrect, return the default set
            $set = Sets::getSet(Set::DEFAULT_SET);
        }
        return $set;
    }

}