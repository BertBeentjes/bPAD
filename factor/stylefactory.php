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
 * Factor the styles
 *
 * @since 0.4.0
 */
class StyleFactory extends Factory {

    /**
     * construct the style factory
     * 
     * @param mode $mode
     * @throws Exception
     */
    public function __construct($mode, $context) {
        // do some input checking
        if (is_object($mode) && is_object($context)) {
            $this->setMode($mode);
            $this->setContext($context);
            $this->setContent('');
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_FACTORY_NOT_INITIALIZED_CORRECTLY) . ' @ ' . __METHOD__);
        }
    }    

    /**
     * factor styles, get all styles from the store and combine them
     * 
     * @return boolean true if success
     */
    public function factor() {
        if (is_string($this->getContent()) && is_object($this->getMode())) {
            // get all styles from the database and put them in the content string
            $styleversions = Styles::getStyleVersions($this->getMode());
            $styleparams = StyleParams::getStyleParams();
            foreach ($styleversions as $styleversion) {
                // show the style name
                $this->setContent($this->getContent() . '/* ' . $styleversion->getContainer()->getName() . ' */' . PHP_EOL . PHP_EOL);
                // get the style versions
                // first replace the class suffix (has to be done now, because each style version has it's own context)
                $styleversionbody =  $this->replaceTermInString($styleversion->getBody(), Terms::CLASS_SUFFIX, $styleversion->getContainer()->getClassSuffix() . "_" . $styleversion->getContext()->getContextGroup()->getShortName() . '_' . $styleversion->getContext()->getShortName());
                // replace the style parameters by their values for the specific context of the style version
                // use a maxed loop to replace style paramaters used within style parameters (for colours, fonts, etc)
                // TODO: use recursion with loop detection, on a rainy afternoon
                $styleparamfound = true;
                $level = 0;
                while ($styleparamfound && $level < 100) {
                    $styleparamfound = false;
                    $level = $level + 1;
                    foreach ($styleparams as $styleparam) {
                        if (strstr($styleversionbody, Terms::styleparam_placeholder($styleparam))) {
                            $styleparamfound = true;
                            $styleversionbody = str_replace(Terms::styleparam_placeholder($styleparam), $styleparam->getVersion($this->getMode(), $styleversion->getContext())->getBody(), $styleversionbody);
                        }
                    }
                }
                $showstyleparams = '*/' . PHP_EOL . PHP_EOL;
                // add the styleversionbody to the content
                $this->setContent($this->getContent() . $styleversionbody . PHP_EOL . PHP_EOL);
            }
            if ($this->getMode()->getId() === Mode::EDITMODE) {
                // now show the style parameters in edit mode
                $showstyleparams = '/*' . PHP_EOL;
                foreach ($styleparams as $styleparam) {
                    $showstyleparams .= $styleparam->getName() . ' = ' . $styleparam->getVersion($this->getMode(), $this->getContext())->getBody() . PHP_EOL;
                }
                $showstyleparams .= '*/' . PHP_EOL . PHP_EOL;
                // add the style param values to the css file in edit mode for convenience
                $this->setContent($showstyleparams . $this->getContent());
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_FACTORY_NOT_INITIALIZED_CORRECTLY) . ' @ ' . __METHOD__);
        }
        return true;
    }

}