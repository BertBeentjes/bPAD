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
 * Style parameters can be used in styles as constants that insert certain style constructs, like colours or margins
 *
 * @since 0.4.0
 */
class StyleParam extends NamedEntity {
    private $styleparamtype; // the type of style param, defines the input box for the parameter
    private $styleparamversions = array(); // the array with the versions of this style parameter
    
    /**
     * Get the style parameter from the store
     * 
     * @param int $id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->tablename = Store::getTableStyleParams();
        $this->loadAttributes();
    }
    
    /**
     * Load the attributes for the style parameter 
     * 
     * @return boolean true if success
     * @throws Exception when loading the attributes fails
     */
    private function loadAttributes() {
        if ($result = Store::getStyleParam($this->id)) {
            if ($attr = $result->fetchObject()) {
                $this->initAttributes($attr);
                return true;
            }
        } 
        throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTES_NOT_LOADING) . ': ' . $this->getId() . ' @ ' . __METHOD__);
    }
    
    /**
     * init the style parameter
     * 
     * @param type $attr
     * @return boolean true if success
     */
    protected function initAttributes ($attr) {
        $this->styleparamtype = StyleParamTypes::getStyleParamType($attr->styleparamtypeid);
        parent::initAttributes($attr);
        return true;
    }
    
    /**
     * getter for the style param version, depending on mode and context a different style param body is returned
     * 
     * @param mode $mode
     * @param context $context
     * @return contextedversion
     */
    public function getVersion($mode, $context) {
        if (isset($this->styleparamversions[$mode->getId()][$context->getId()])) {
            return $this->styleparamversions[$mode->getId()][$context->getId()];
        } else {
            $this->styleparamversions[$mode->getId()][$context->getId()] = new ContextedVersion($this, ContextedVersion::STYLEPARAM, $mode, $context);
            return $this->styleparamversions[$mode->getId()][$context->getId()];
        }
    }
    
    /**
     * The style param type
     * 
     * @return styleparamtype
     */
    public function getStyleParamType() {
        return $this->styleparamtype;
    }
    
    /**
     * set the style param type
     * 
     * @param styleparamtype the new style param type
     * @return boolean true if success
     * @throws Exception when the update fails
     */
    public function setStyleParamType($newstyleparamtype) {
        if (Store::setStyleParamStyleParamTypeId($this->getId(),  $newstyleparamtype->getId()) && $this->setChanged()) {
            $this->styleparamtype = $newstyleparamtype;
            return true;
        } else {
            throw new Exception (Helper::getLang(Errors::ERROR_ATTRIBUTE_UPDATE_FAILED) . ' @ ' . __METHOD__);
        }
    }

}

?>
