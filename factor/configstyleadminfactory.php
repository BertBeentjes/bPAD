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
 * Factor the style configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigStyleAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific style is requested, show this one, otherwise open with
        // the default style
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validStyle(Request::getCommand()->getValue())) {
                $style = Styles::getStyle(Request::getCommand()->getValue());
            }
        } else {
            $styles = Styles::getStyles();
            $row = $styles->fetchObject();
            $style = Styles::getStyle($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = $this->factorErrorMessage();
        $section = '';
        // factor the styles
        $styles = Styles::getStyles();
        $section .= $this->factorListBox($baseid . '_stylelist', CommandFactory::configStyle($this->getObject(), $this->getMode(), $this->getContext()), $styles, $style->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_STYLES));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addStyle($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_STYLE)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_STYLES));
        // factor the default style
        $content = '';
        // open the first style
        $content = $this->factorConfigStyleContent($style);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the style config edit content 
     * 
     * @param style $style
     * @return string
     */
    private function factorConfigStyleContent($style) {
        $baseid = 'CP' . $this->getObject()->getId() . '_style';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $style->getName();
        // style name
        if ($style->getIsBpadDefined()) {
            // can't change the name when it's bpad defined
            // TODO: replace disable by a structure or something that makes it html independent
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editStyleName($style), $style->getName(), Helper::getLang(AdminLabels::ADMIN_STYLE_NAME), 'disabled');
        } else {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editStyleName($style), $style->getName(), Helper::getLang(AdminLabels::ADMIN_STYLE_NAME));
        }
        // style type
        if ($style->isUsed()) {
            // can't change the style type when it's in use
            // TODO: replace disable by a structure or something that makes it html independent
            $section .= $this->factorTextInput($baseid . '_styletype', CommandFactory::editStyleType($style), Helper::getLang($style->getStyleType()), Helper::getLang(AdminLabels::ADMIN_STYLE_TYPE), 'disabled');
        } else {
            // create a list box
            // get input type list
            $list = array();
            $list[0][0] = Style::OBJECT_STYLE;
            $list[0][1] = Helper::getLang(Style::OBJECT_STYLE);
            $list[1][0] = Style::POSITION_STYLE;
            $list[1][1] = Helper::getLang(Style::POSITION_STYLE);
            // create input type list box
            $section .= $this->factorListBox($baseid . '_styletype', CommandFactory::editStyleType($style), $list, $style->getStyleType(), Helper::getLang(AdminLabels::ADMIN_STYLE_TYPE));
        }
        $section .= $this->factorTextInput($baseid . '_classsuffix', CommandFactory::editStyleClassSuffix($style), $style->getClassSuffix(), Helper::getLang(AdminLabels::ADMIN_STYLE_CLASS_SUFFIX));
        $sets = Sets::getSets();
        $section .= $this->factorListBox($baseid . '_setlist', CommandFactory::editStyleSet($style), $sets, $style->getSet()->getId(), Helper::getLang(AdminLabels::ADMIN_STYLE_SET));
        // remove button 
        if ($style->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeStyle($this->getObject(), $style, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_STYLE)));
        }
        $admin .= $section;
        $section = '';
        // get the contexts
        $contexts = Contexts::getContexts();
        while ($row = $contexts->fetchObject()) {
            $context = Contexts::getContext($row->id);
            $section = '';
            // get the styleversions for the context (if there is one)
            $styleversion = $style->getVersion($this->getMode(), $context);
            if ($styleversion->getOriginal()) {
                // show the body for the style (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $styleversion->getId(), CommandFactory::editStyleVersionBody($style, $this->getMode(), $context), $styleversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName());
                // add publish, cancel buttons for the version
                $buttons = '';
                $buttons .= $this->factorButton($baseid . '_publishversion_c' . $context->getId(), CommandFactory::publishStyleVersion($this->getObject(), $style, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_STYLEVERSION));
                $buttons .= $this->factorButton($baseid . '_cancelversion_c' . $context->getId(), CommandFactory::cancelStyleVersion($this->getObject(), $style, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL_STYLEVERSION));
                // show the delete button when there is a style, and the style is not the default default
                if (!($context->getContextGroup()->isDefault() && $context->isDefault())) {
                    $buttons .= $this->factorButton($baseid . '_removeversion_c' . $context->getId(), CommandFactory::removeStyleVersion($this->getObject(), $style, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_STYLEVERSION));
                }
                $section .= $this->factorButtonGroup($buttons);
            } else {
                // show the body for the style (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $styleversion->getId(), CommandFactory::editStyleVersionBody($style, $this->getMode(), $context), $styleversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName(), 'disabled');
                // show the add button when there is no style
                $this->factorButtonGroup($section .= $this->factorButton($baseid . '_addversion_c' . $context->getId(), CommandFactory::addStyleVersion($this->getObject(), $style, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_STYLEVERSION)));
            }
            $admin .= $this->factorSubItem($section);
        }
        $admin = $this->factorSection($baseid . '_section' . $style->getId(), $admin, $sectionheader);
        return $admin;
    }

}