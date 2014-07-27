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
 * Factor a referral, a referral is more or less the same as a menu item
 *
 * @since 0.4.0
 */
class ReferralFactory extends Factory {
    private $object; // the object to use for filling in the structure
    
    /**
     * Construct the factory
     * 
     * @param string $content
     * @param object $object
     * @param context $context
     * @param mode $mode
     */
    public function __construct($content, $object, $context, $mode) {
        $this->setContent($content);
        $this->setObject($object);
        $this->setContext($context);
        $this->setMode($mode);
    }

    /**
     * set the object for the factory
     * 
     * @param object $newobject
     */
    public function setObject($newobject) {
        $this->object = $newobject;
    }

    /**
     * Get the object for this factory
     * 
     * @return object
     */
    public function getObject() {
        return $this->object;
    }

    /**
     * factor the referral
     */
    public function factor() {
        // create the content for the referral
        $this->replaceTerm(Terms::POSITION_CONTENT, $this->getObject()->getName());
        // factor the referral terms
        $this->factorTerms();
    }
    
    /**
     * factor the terms for the referral
     */
    private function factorTerms() {
        if ($this->hasTerm(Terms::POSITION_REFERRAL)) {
            $this->replaceTerm(Terms::POSITION_REFERRAL, CommandFactory::getObject($this->getObject(), $this->getMode(), $this->getContext()));
        }
        if ($this->hasTerm(Terms::POSITION_REFERRAL_URL)) {
            $this->replaceTerm(Terms::POSITION_REFERRAL_URL, $this->getObject()->getSEOURL($this->getMode()));
        }
        if ($this->hasTerm(Terms::POSITION_REFERRAL_OBJECT_ID)) {
            $this->replaceTerm(Terms::POSITION_REFERRAL_OBJECT_ID, $this->getObject()->getId());
        }
    }
}