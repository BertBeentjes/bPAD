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
 * Factor the style paramaters configuration functions
 *
 * @since 0.4.0
 */
class ConfigStyleParamAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific style parameter is requested, show this one, otherwise open with
        // the default style parameter
        $styleparams = StyleParams::getStyleParamList();
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validStyleParam(Request::getCommand()->getValue())) {
                $styleparam = StyleParams::getStyleParam(Request::getCommand()->getValue());
            }
        } else {
            $row = $styleparams->fetchObject();
            $styleparam = StyleParams::getStyleParam($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = '';
        $section = '';
        // factor the style params
        $styleparams = StyleParams::getStyleParamList();
        $section .= $this->factorListBox($baseid . '_styleparamlist', CommandFactory::configStyleParam($this->getObject(), $this->getMode(), $this->getContext()), $styleparams, $styleparam->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_STYLE_PARAMS));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addStyleParam($this->getObject(), $styleparam, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_STYLE_PARAM)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_STYLE_PARAMS));
        // factor the default style param
        $content = '';
        // open the first style param
        $content = $this->factorConfigStyleParamContent($styleparam);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the style config edit content 
     * 
     * @param styleparam $styleparam
     * @return string
     */
    private function factorConfigStyleParamContent($styleparam) {
        $baseid = 'CP' . $this->getObject()->getId() . '_styleparam';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $styleparam->getName();
        // style name
        $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editStyleParamName($styleparam), $styleparam->getName(), Helper::getLang(AdminLabels::ADMIN_STYLE_PARAM_NAME));
        // remove button 
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeStyleParam($this->getObject(), $styleparam, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_STYLE_PARAM)));
        $admin .= $section;
        $section = '';
        // get the contexts
        $contexts = Contexts::getContexts();
        while ($row = $contexts->fetchObject()) {
            $context = Contexts::getContext($row->id);
            $section = '';
            // get the styleversions for the context (if there is one)
            $styleparamversion = $styleparam->getVersion($this->getMode(), $context);
            if ($styleparamversion->getOriginal()) {
                // show the body for the style param (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $styleparamversion->getId(), CommandFactory::editStyleParamVersionBody($styleparam, $this->getMode(), $context), $styleparamversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName());
                // add publish, cancel buttons for the version
                $buttons = '';
                $buttons .= $this->factorButton($baseid . '_publishversion_c' . $context->getId(), CommandFactory::publishStyleParamVersion($this->getObject(), $styleparam, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_STYLEPARAMVERSION));
                $buttons .= $this->factorButton($baseid . '_cancelversion_c' . $context->getId(), CommandFactory::cancelStyleParamVersion($this->getObject(), $styleparam, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL_STYLEPARAMVERSION));
                // show the delete button when there is a style, and the style is not the default default
                if (!($context->getContextGroup()->isDefault() && $context->isDefault())) {
                    $buttons .= $this->factorButton($baseid . '_removeversion_c' . $context->getId(), CommandFactory::removeStyleParamVersion($this->getObject(), $styleparam, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_STYLEPARAMVERSION));
                }
                $section .= $this->factorButtonGroup($buttons);
            } else {
                // show the body for the style param (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $styleparamversion->getId(), CommandFactory::editStyleParamVersionBody($styleparam, $this->getMode(), $context), $styleparamversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName(), 'disabled');
                // show the add button when there is no style
                $this->factorButtonGroup($section .= $this->factorButton($baseid . '_addversion_c' . $context->getId(), CommandFactory::addStyleParamVersion($this->getObject(), $styleparam, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_STYLEPARAMVERSION)));
            }
            $admin .= $this->factorSubItem($section);
        }
        $admin = $this->factorSection($baseid . '_section' . $styleparam->getId(), $admin, $sectionheader);
        return $admin;
    }
}

?>
