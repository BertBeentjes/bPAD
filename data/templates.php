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
 * Contains all templates, loads them on demand and stores them for later use.
 *
 */
class Templates {

    private static $templates = array();

    /**
     * get an template by id, checks whether the template is loaded,
     * or loads the template if necessary and fills it on demand with
     * further information
     * 
     * @param templateid the id of the template to get
     * @return template
     */
    public static function getTemplate($templateid) {
        if (Validator::isNumeric($templateid)) {
            // return an object
            // return a template
            if (isset(self::$templates[$templateid])) {
                return self::$templates[$templateid];
            } else {
                self::$templates[$templateid] = new Template($templateid);
                return self::$templates[$templateid];
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get a list of templates 
     * 
     * @param string $defaultname name to use for the default template
     * @return resultset
     */
    public static function getTemplates($defaultname = NULL) {
        if (isset($defaultname)) {
            return Store::getTemplates($defaultname);
        }
        $name = self::getTemplate(Template::DEFAULT_TEMPLATE)->getName();
        return Store::getTemplates(Helper::getLang($name));
    }

    /**
     * Get a list of templates in a set, and add a specific template if necessary,
     * for use in list boxes
     * 
     * @param set $set
     * @param template $template
     * @return resultset
     */
    public static function getTemplatesBySet($set, $template = NULL, $showdeleted = false) {
        if (isset($template)) {
            return Store::getTemplatesBySetId($set->getId(), $template->getId(), $showdeleted);
        }
        // a bit dirty... but -1 does no harm in the query, just no extra template selected
        return Store::getTemplatesBySetId($set->getId(), -1, $showdeleted);
    }

    /**
     * Get the order fields, based upon a template
     * 
     * @param template $template
     */
    public static function getTemplateOrderFieldsByTemplate($template) {
        $fields = array();
        // create id/name pairs, even though in this case they are the same, it should
        // be done for creating list box options
        $fields[0][0] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_ASC);
        $fields[0][1] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_ASC);
        $fields[1][0] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_DESC);
        $fields[1][1] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_DESC);
        $fields[2][0] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_ASC);
        $fields[2][1] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_ASC);
        $fields[3][0] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_DESC);
        $fields[3][1] = Helper::getLang(PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_DESC);
        $counter = 4;
        if ($template->getId() != Template::DEFAULT_TEMPLATE) {
            // always look for order by fields in view mode
            if ($flex = Store::getTemplateOrderFieldsByTemplateId($template->getId(), Mode::VIEWMODE)) {
                while ($field = $flex->fetchObject()) {
                    $fields[$counter][0] = $field->name;
                    $fields[$counter][1] = $field->name;
                    $counter = $counter + 1;
                }
            }
        }
        return $fields;
    }
    
    /**
     * Create a new template
     * 
     * @return type
     */
    public static function newTemplate() {
        $template = Templates::getTemplate(Store::insertTemplate());
        // initialize the template
        $template->setStyle(Styles::getStyle(Style::DEFAULT_POSITION_STYLE));
        // create the root object for the template
        $object = Objects::newObject();
        // mind the order, any other order will result in inconsistency and endless looping of setchanged
        $object->setIsTemplateRoot(true);
        $object->setIsTemplate(true);
        $object->setTemplate($template);
        // add default permissions to the object, copy the permissions from the root object of the default template
        $source = Templates::getTemplate(Template::DEFAULT_TEMPLATE)->getRootObject();
        $newroles = $source->getObjectUserGroupRoles();
        foreach ($newroles as $newrole) {
            // only copy inheritable permissions
            if ($newrole->getInherit() == true) {
                $object->newObjectUserGroupRole($object, $newrole->getUserGroup(), $newrole->getRole(), $newrole->getInherit());
            }
        }
        return true;
    }

    /**
     * remove a template, you can only remove templates that aren't used, and you can't remove templates defined by bPAD
     * 
     * @param template $template
     * @return type
     */
    public static function removeTemplate($template) {
        if ($template->isRemovable()) {
            Store::deleteTemplate($template->getId());
            unset(self::$templates[$template->getId()]);
            return true;
        }
        return false;
    }

}

?>
