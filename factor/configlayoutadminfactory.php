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
 * Factor the layout configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigLayoutAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific layout is requested, show this one, otherwise open with
        // the default layout
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validLayout(Request::getCommand()->getValue())) {
                $layout = Layouts::getLayout(Request::getCommand()->getValue());
            }
        } else {
            $layouts = Layouts::getLayouts();
            $row = $layouts->fetchObject();
            $layout = Layouts::getLayout($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = '';
        $section = '';
        // factor the layouts
        $layouts = Layouts::getLayouts();
        $section .= $this->factorListBox($baseid . '_layoutlist', CommandFactory::configLayout($this->getObject(), $this->getMode(), $this->getContext()), $layouts, $layout->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_LAYOUTS));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addLayout($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_LAYOUT)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_LAYOUTS));
        // factor the default layout
        $content = '';
        // open the first layout
        $content = $this->factorConfigLayoutContent($layout);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the layout config edit content 
     * 
     * @param layout $layout
     * @return string
     */
    private function factorConfigLayoutContent($layout) {
        $baseid = 'CP' . $this->getObject()->getId() . '_layout';
        $section = '';
        $admin = '';
        // section header
        $sectionheader = $layout->getName();
        // layout name
        if ($layout->getIsBpadDefined()) {
            // can't change the name when it's bpad defined
            // TODO: replace disable by a structure or something that makes it html independent
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editLayoutName($layout), $layout->getName(), Helper::getLang(AdminLabels::ADMIN_LAYOUT_NAME), 'disabled');
        } else {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editLayoutName($layout), $layout->getName(), Helper::getLang(AdminLabels::ADMIN_LAYOUT_NAME));
        }
        $sets = Sets::getSets();
        $section .= $this->factorListBox($baseid . '_setlist', CommandFactory::editLayoutSet($layout), $sets, $layout->getSet()->getId(), Helper::getLang(AdminLabels::ADMIN_LAYOUT_SET));
        // remove button 
        if ($layout->isRemovable()) {
            $section .= $this->factorButtonGroup($this->factorButton($baseid . '_remove', CommandFactory::removeLayout($this->getObject(), $layout, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUT)));
        }
        $admin .= $section;
        $section = '';
        // get the contexts
        $contexts = Contexts::getContexts();
        while ($row = $contexts->fetchObject()) {
            $context = Contexts::getContext($row->id);
            $section = '';
            // get the layoutversions for the context (if there is one)
            $layoutversion = $layout->getVersion($this->getMode(), $context);
            if ($layoutversion->getOriginal()) {
                // show the body for the layout (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $layoutversion->getId(), CommandFactory::editLayoutVersionBody($layout, $this->getMode(), $context), $layoutversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName());
                // add publish, cancel buttons for the version
                $buttons = '';
                $buttons .= $this->factorButton($baseid . '_publishversion_c' . $context->getId(), CommandFactory::publishLayoutVersion($this->getObject(), $layout, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_PUBLISH_LAYOUTVERSION));
                $buttons .= $this->factorButton($baseid . '_cancelversion_c' . $context->getId(), CommandFactory::cancelLayoutVersion($this->getObject(), $layout, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_CANCEL_LAYOUTVERSION));
                // show the delete button when there is a layout, and the layout is not the default default
                if (!($context->getContextGroup()->isDefault() && $context->isDefault())) {
                    $buttons .= $this->factorButton($baseid . '_removeversion_c' . $context->getId(), CommandFactory::removeLayoutVersion($this->getObject(), $layout, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUTVERSION));
                }
                $section .= $this->factorButtonGroup($buttons);
            } else {
                // show the body for the layout (if there is one)
                $section .= $this->factorTextArea($baseid . '_body_c' . $context->getId() . $layoutversion->getId(), CommandFactory::editLayoutVersionBody($layout, $this->getMode(), $context), $layoutversion->getBody(), $context->getContextGroup()->getName() . ' - ' . $context->getName(), 'disabled');
                // show the add button when there is no layout
                $section .= $this->factorButtonGroup($this->factorButton($baseid . '_addversion_c' . $context->getId(), CommandFactory::addLayoutVersion($this->getObject(), $layout, $this->getMode(), $context, $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_LAYOUTVERSION)));
            }
            $admin .= $this->factorSubItem($section);
        }
        $admin = $this->factorSection($baseid . '_section' . $layout->getId(), $admin, $sectionheader);
        return $admin;
    }

}