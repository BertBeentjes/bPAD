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
 * Factor the template configuration and administration interface
 *
 * @since 0.4.0
 */
class ConfigTemplateAdminFactory extends ConfigAdminFactory {

    /**
     * Factor the admin/config functions
     */
    public function factor() {
        $this->setObject(Objects::getObject(Request::getCommand()->getItemAddress()));
        // if a specific template is requested, show this one, otherwise open with
        // the default template
        if (Request::getCommand()->getValue() > '') {
            if (Validator::validTemplate(Request::getCommand()->getValue())) {
                $template = Templates::getTemplate(Request::getCommand()->getValue());
            }
        } else {
            $templates = Templates::getTemplates();
            $row = $templates->fetchObject();
            $template = Templates::getTemplate($row->id);
        }
        $baseid = 'CP' . $this->getObject()->getId();
        $admin = '';
        $section = '';
        // factor the templates
        $templates = Templates::getTemplates();
        $section .= $this->factorListBox($baseid . '_templatelist', CommandFactory::configTemplate($this->getObject(), $this->getMode(), $this->getContext()), $templates, $template->getId(), Helper::getLang(AdminLabels::ADMIN_CONFIG_TEMPLATES));
        // add button
        $section .= $this->factorButtonGroup($this->factorButton($baseid . '_add', CommandFactory::addTemplate($this->getObject(), $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_ADD_TEMPLATE)) . $this->factorCloseButton($baseid));
        $admin .= $this->factorSection($baseid . 'header', $section, Helper::getLang(AdminLabels::ADMIN_CONFIG_TEMPLATES));
        // factor the default template
        $content = '';
        // open the first template
        $content = $this->factorConfigTemplateContent($template);
        // add a detail panel
        $admin .= $this->factorConfigDetailPanel($baseid, $content);
        $this->setContent($admin);
    }

    /**
     * Get the template config edit content 
     * 
     * @param template $template
     * @return string
     */
    private function factorConfigTemplateContent($template) {
        $baseid = 'CP' . $this->getObject()->getId() . '_template';
        $section = '';
        $admin = '';
        // template name
        if ($template->isDefault()) {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editTemplateName($template), $template->getName(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_NAME), 'disabled');
        } else {
            $section .= $this->factorTextInput($baseid . '_name', CommandFactory::editTemplateName($template), $template->getName(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_NAME));
        }
        // deleted
        $section .= $this->factorCheckBox($baseid . '_deleted', CommandFactory::editTemplateDeleted($template), $template->getDeleted(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_DELETED));
        // instanceallowed
        $section .= $this->factorCheckBox($baseid . '_instanceallowed', CommandFactory::editTemplateInstanceAllowed($template), $template->getInstanceAllowed(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_INSTANCE_ALLOWED));
        // searchable
        $section .= $this->factorCheckBox($baseid . '_searchable', CommandFactory::editTemplateSearchable($template), $template->getSearchable(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_SEARCHABLE));
        // set
        $sets = Sets::getSets();
        $section .= $this->factorListBox($baseid . '_setlist', CommandFactory::editTemplateSet($template), $sets, $template->getSet()->getId(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_SET));
        // structure
        $structures = Structures::getStructures();
        $section .= $this->factorListBox($baseid . '_setlist', CommandFactory::editTemplateStructure($template), $structures, $template->getStructure()->getId(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_STRUCTURE));
        // style
        $styles = Styles::getStyles();
        $section .= $this->factorListBox($baseid . '_setlist', CommandFactory::editTemplateStyle($template), $styles, $template->getStyle()->getId(), Helper::getLang(AdminLabels::ADMIN_TEMPLATE_STYLE));
        // remove button 
        if ($template->isRemovable()) {
            $section .= $this->factorButton($baseid . '_remove', CommandFactory::removeTemplate($this->getObject(), $template, $this->getMode(), $this->getContext()), Helper::getLang(AdminLabels::ADMIN_BUTTON_REMOVE_TEMPLATE));
        }
        
        // put this in a section and start a new section
        $admin .= $this->factorSection($baseid . '_header', $section);
        $section = '';
        
        // objects belonging to the template
        // get current template root object
        $object = NULL;
        if ($result = Store::getTemplateRootObject($template->getId())) {
            if ($row = $result->fetchObject()) {
                $object = Objects::getObject($row->objectid);
            }
        }
        
        // show object
        if (isset($object)) {
            $objectfactory = new EditAdminFactory();
            $objectfactory->setMode($this->getMode());
            $objectfactory->setContext($this->getContext());
            $objectfactory->factor($object, $this->getObject());
            // TODO: when factoring, show add/delete positionobject/instance/referral/contentitem buttons (add or delete position+positionitem in one)
            $section .= $objectfactory->getContent();
        }
        
        $admin .= $this->factorSection($baseid . '_body', $section);
        
        return $admin;
    }

}

?>
