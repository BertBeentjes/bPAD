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
 * Create commands for all sorts of stuff. Commands are triggered by events in 
 * the front end and execute things in the back end.
 *
 */
class CommandFactory {

    /**
     * Compose the command to get this object from the frontend, this command
     * is used to load the complete tree of content leading to this object. 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function getObject($object, $mode, $context) {
        return 'object,' . $object->getAddress($mode) . ',content.get.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to get this object deep linked from the frontend, this command
     * is used to load the complete tree of content leading to this object. 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function getObjectDeepLink($object, $mode, $context) {
        $address = $object->getAddress($mode);
        // deep links are only allowed when instances are allowed and the object isn't addressable by itself and the parent of the object has a pn layout
        if ($object->getTemplate()->getInstanceAllowed() && $object->getVersion($mode)->getObjectParent()->getVersion($mode)->getLayout()->isPNType() && !$object->isAddressable($mode)) {
            $deepaddress = $object->getDeepAddress($mode);
            if ($address > '') {
                $address .= '/';
            }
            $address .= $deepaddress;
        } 
        return 'object,' . $address . ',content.get.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load a specific object, used for refreshing an object
     * after changes have been made.
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function getSpecificObject($object, $mode, $context) {
        $parentpositionid = 1;
        $parentrootid = 1;
        $parentid = 1;
        $parentposition = $object->getVersion($mode)->getObjectParent()->getVersion($mode)->getObjectTemplateRootObject()->getVersion($mode)->getPositionParent();
        $parentroot = $object->getVersion($mode)->getObjectParent()->getVersion($mode)->getObjectTemplateRootObject();
        $parent = $object->getVersion($mode)->getPositionParent();
        // if the parent is the template root, there is no position, otherwise take the position id
        if (isset($parentposition)) {
            $parentpositionid = $parentposition->getId();
        }
        if (isset($parentposition)) {
            $parentrootid = $parentroot->getId();
        }
        if (isset($parent)) {
            $parentid = $parent->getId();
        }
        return 'object,' . $parentpositionid . '.' . $parentrootid . '.' . $parentid . '.' . Helper::getURLSafeString($object->getName()) . ',content.refresh.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to get this object in edit mode, while the rest of the
     * site is in view mode
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function getObjectForEdit($object, $mode, $context) {
        if ($object->isAddressable($mode)) {
            $returnvalue = self::getObject($object, $mode, $context);
        } else {
            $returnvalue = self::getSpecificObject($object, $mode, $context);
        }
        $returnvalue .= '|' . 'object,' . $object->getVersion($mode)->getPositionParent()->getId() . '.' . $object->getId() . '.' . $object->getVersion(Modes::getMode(Mode::EDITMODE))->getPositionParent()->getId() . '.' . Helper::getURLSafeString($object->getName()) . ',content.get.' . Mode::EDITMODE . '.' . $context->getId();
        return $returnvalue;
    }

    /**
     * Compose the command to load this object in a specific place in the frontend,
     * used for example for lazy loading in instances. Use getCommandGet to load the
     * object and the content tree it lives in.
     * 
     * @param object $object
     * @param string $containerid
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function loadObject($object, $containerid, $mode, $context) {
        return 'object,' . $containerid . '.' . $object->getId() . '.' . Helper::getURLSafeString($object->getVersion($mode)->getObjectTemplateRootObject()->getName()) . ',content.load' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the edit panel with the edit functions for this object
     * 
     * @param object $object
     * @param context $context
     * @return string
     */
    public static function editObject($object, $context) {
        // editing is done in edit mode
        $mode = Modes::getMode(Mode::EDITMODE);
        return 'object,' . $object->getId() . '.' . $object->getVersion($mode)->getObjectTemplateRootObject()->getId() . '.' . Helper::getURLSafeString($object->getVersion($mode)->getObjectTemplateRootObject()->getName()) . ',admin.edit' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the move panel with the move functions for this object
     * 
     * @param object $object
     * @param context $context
     * @return string
     */
    public static function moveObject($object, $context) {
        // moving is done in edit mode
        $mode = Modes::getMode(Mode::EDITMODE);
        return 'object,' . $object->getId() . '.' . $object->getVersion($mode)->getObjectTemplateRootObject()->getId() . '.' . Helper::getURLSafeString($object->getVersion($mode)->getObjectTemplateRootObject()->getName()) . ',admin.move' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the position instance with a user search parameter
     * 
     * @param position $position
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function searchPosition($position, $mode, $context) {
        return 'position,' . $position->getId() . '.' . $position->getContainer()->getContainer()->getId() . '.' . $position->getNumber() . ',content.instance.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to edit the object name
     * 
     * @param object $object
     * @return string
     */
    public static function editObjectName($object) {
        return 'object,' . $object->getId() . ',change.objectname';
    }

    /**
     * Compose the command to edit the object active value
     * 
     * @param object $object
     * @param object $editobject
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editObjectActive($object, $editobject, $mode, $context) {
        return 'object,' . $object->getId() . ',change.objectactive' . '|' . self::getSpecificObject($editobject, $mode, $context) . '|' . self::editObject($editobject, $context);
    }

    /**
     * Compose the command to edit the object active value from the recycle bin, 
     * in this case chaining the edit command isn't necessary
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editObjectActiveFromBin($object, $mode, $context) {
        return 'object,' . $object->getId() . ',change.objectactive' . '|' . self::getSpecificObject($object, $mode, $context);
    }

    /**
     * Compose the command to edit the object set value, chain a reload, changing
     * the set affects the layouts, styles, structures for the object
     * 
     * @param object $object
     * @param object $containerobject
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editObjectSet($object, $containerobject, $mode, $context) {
        return 'object,' . $object->getId() . ',change.objectset' . '|' . self::configTemplate($containerobject, $mode, $context);
    }

    /**
     * Compose the command to publish an object
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editObjectPublish($object, $mode, $context) {
        return 'object,' . $object->getId() . ',change.publishobject' . '|' . self::getSpecificObject($object, $mode, $context);
    }

    /**
     * Compose the command to move an object
     * 
     * @param object $object
     * @return string
     */
    public static function editObjectMove($object) {
        // TODO: chain getobject command for the old location
        // TODO: find a way to move to the new location
        return 'object,' . $object->getId() . ',change.moveobject';
    }

    /**
     * Compose the command to move an object a position up in the parent
     * 
     * @param object $object
     * @param object $editobject
     * @param context $context
     * @return string
     */
    public static function editObjectMoveUp($object, $editobject, $context) {
        // TODO: chain the refresh command. 
        // one for the view objects (refresh the object paren, refresh referrals)
        // one for the edit objects in case of a searchable construction
        return 'object,' . $object->getId() . ',change.moveobjectup' . '|' . self::editObject($editobject, $context);
    }

    /**
     * Compose the command to move an object a position down in the parent
     * 
     * @param object $object
     * @param object $editobject
     * @param context $context
     * @return string
     */
    public static function editObjectMoveDown($object, $editobject, $context) {
        // TODO: chain the refresh command. 
        // one for the view objects (refresh the object paren, refresh referrals)
        // one for the edit objects in case of a searchable construction
        return 'object,' . $object->getId() . ',change.moveobjectdown' . '|' . self::editObject($editobject, $context);
    }

    /**
     * Compose the command to cancel an object
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editObjectCancel($object, $mode, $context) {
        return 'object,' . $object->getId() . ',change.cancelobject' . '|' . self::getSpecificObject($object, $mode, $context);
    }

    /**
     * Compose the command to stop editing an object, but keep changes
     * 
     * @param object $object
     * @return string
     */
    public static function editObjectKeep($object, $mode, $context) {
        return 'object,' . $object->getId() . ',change.keepobject' . '|' . self::getSpecificObject($object, $mode, $context);
    }

    /**
     * Compose the command to edit the object version layout, chain a refresh command,
     * to show the buttons for the positions in the layout
     * 
     * @param objectversion $objectversion
     * @param object $containerobject
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editTemplateObjectVersionLayout($objectversion, $containerobject, $mode, $context) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversionlayout' . '.' . $objectversion->getMode()->getId() . '|' . self::configTemplate($containerobject, $mode, $context);
    }

    /**
     * Compose the command to edit the object version layout
     * 
     * @param objectversion $objectversion
     * @return string
     */
    public static function editObjectVersionLayout($objectversion) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversionlayout' . '.' . $objectversion->getMode()->getId();
    }

    /**
     * Compose the command to edit the object version style
     * 
     * @param objectversion $objectversion
     * @return string
     */
    public static function editObjectVersionStyle($objectversion) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversionstyle' . '.' . $objectversion->getMode()->getId();
    }

    /**
     * Compose the command to edit the object version argument default
     * 
     * @param objectversion $objectversion
     * @return string
     */
    public static function editObjectVersionArgumentDefault($objectversion) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversionargumentdefault' . '.' . $objectversion->getMode()->getId();
    }

    /**
     * Compose the command to edit the object version argument
     * 
     * @param objectversion $objectversion
     * @return string
     */
    public static function editObjectVersionArgument($objectversion) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversionargument' . '.' . $objectversion->getMode()->getId();
    }

    /**
     * Compose the command to edit the object version inherit layout bit
     * 
     * @param objectversion $objectversion
     * @return string
     */
    public static function editObjectVersionInheritLayout($objectversion) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversioninheritlayout' . '.' . $objectversion->getMode()->getId();
    }

    /**
     * Compose the command to edit the object version inherit style bit
     * 
     * @param objectversion $objectversion
     * @return string
     */
    public static function editObjectVersionInheritStyle($objectversion) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversioninheritstyle' . '.' . $objectversion->getMode()->getId();
    }

    /**
     * Compose the command to edit the object version template, this sets the default template for children of this object
     * 
     * @param objectversion $objectversion
     * @return string
     */
    public static function editObjectVersionTemplate($objectversion) {
        return 'objectversion,' . $objectversion->getContainer()->getId() . ',change.objectversiontemplate' . '.' . $objectversion->getMode()->getId();
    }

    /**
     * Compose the command to edit the position structure
     * 
     * @param position $position
     * @return string
     */
    public static function editPositionStructure($position) {
        return 'position,' . $position->getContainer()->getContainer()->getId() . '.' . $position->getNumber() . ',change.positionstructure' . '.' . $position->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position style
     * 
     * @param position $position
     * @return string
     */
    public static function editPositionStyle($position) {
        return 'position,' . $position->getContainer()->getContainer()->getId() . '.' . $position->getNumber() . ',change.positionstyle' . '.' . $position->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position inherit structure
     * 
     * @param position $position
     * @return string
     */
    public static function editPositionInheritStructure($position) {
        return 'position,' . $position->getContainer()->getContainer()->getId() . '.' . $position->getNumber() . ',change.positioninheritstructure' . '.' . $position->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position inherit style
     * 
     * @param position $position
     * @return string
     */
    public static function editPositionInheritStyle($position) {
        return 'position,' . $position->getContainer()->getContainer()->getId() . '.' . $position->getNumber() . ',change.positioninheritstyle' . '.' . $position->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position content item name, the content item 
     * is found through the position (0/1 to 1 relation)
     * 
     * @param positioncontentitem $positioncontentitem
     * @return string
     */
    public static function editPositionContentItemName($positioncontentitem) {
        return 'position,' . $positioncontentitem->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioncontentitem->getContainer()->getNumber() . ',change.positioncontentitemname' . '.' . $positioncontentitem->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position content item input type, the content item 
     * is found through the position (0/1 to 1 relation)
     * 
     * @param positioncontentitem $positioncontentitem
     * @return string
     */
    public static function editPositionContentItemInputType($positioncontentitem) {
        return 'position,' . $positioncontentitem->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioncontentitem->getContainer()->getNumber() . ',change.positioncontentiteminputtype' . '.' . $positioncontentitem->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position content item body
     * 
     * @param positioncontentitem $positioncontentitem
     * @return string
     */
    public static function editPositionContentItemBody($positioncontentitem) {
        return 'position,' . $positioncontentitem->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioncontentitem->getContainer()->getNumber() . ',change.positioncontentitembody' . '.' . $positioncontentitem->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance object
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceObject($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstanceobject' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance template
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceTemplate($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstancetemplate' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance listwords
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceListWords($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstancelistwords' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance search words
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceSearchWords($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstancesearchwords' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance parent
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceParent($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstanceparent' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance active items
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceActiveItems($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstanceactiveitems' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance max items
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceMaxItems($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstancemaxitems' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance fill on load
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceFillOnLoad($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstancefillonload' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance use instance context
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceUseInstanceContext($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstanceuseinstancecontext' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance order by
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceOrderBy($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstanceorderby' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position instance group by
     * 
     * @param positioninstance $positioninstance
     * @return string
     */
    public static function editPositionInstanceGroupBy($positioninstance) {
        return 'position,' . $positioninstance->getContainer()->getContainer()->getContainer()->getId() . '.' . $positioninstance->getContainer()->getNumber() . ',change.positioninstancegroupby' . '.' . $positioninstance->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position referral argument
     * 
     * @param positionreferral $positionreferral
     * @return string
     */
    public static function editPositionReferralArgument($positionreferral) {
        return 'position,' . $positionreferral->getContainer()->getContainer()->getContainer()->getId() . '.' . $positionreferral->getContainer()->getNumber() . ',change.positionreferralargument' . '.' . $positionreferral->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position referral order by
     * 
     * @param positionreferral $positionreferral
     * @return string
     */
    public static function editPositionReferralOrderBy($positionreferral) {
        return 'position,' . $positionreferral->getContainer()->getContainer()->getContainer()->getId() . '.' . $positionreferral->getContainer()->getNumber() . ',change.positionreferralorderby' . '.' . $positionreferral->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to edit the position referral number of items
     * 
     * @param positionreferral $positionreferral
     * @return string
     */
    public static function editPositionReferralNumberOfItems($positionreferral) {
        return 'position,' . $positionreferral->getContainer()->getContainer()->getContainer()->getId() . '.' . $positionreferral->getContainer()->getNumber() . ',change.positionreferralnumberofitems' . '.' . $positionreferral->getContainer()->getContainer()->getMode()->getId();
    }

    /**
     * Compose the command to load the add panel with templates to choose from to add content
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addContent($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.add' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to add a new object based upon a template
     * 
     * @param object $object the object to add a new position to
     * @param object $editobject the object to edit
     * @param template $template the template to use
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addObjectFromTemplate($object, $template, $number, $mode, $context, $editobject = NULL) {
        // create an object from template and open it for editing
        if (isset($editobject)) {
            // open a parent object for editing (used for a new searchable template based object, searchable objects are edited in the context of their parents)
            return 'templateobject,' . $template->getId() . '.' . $object->getId() . '.' . $number . ',change.add' . '|' . self::editObject($editobject, $context);
        }
        // open the containing object (new template based object)
        return 'templateobject,' . $template->getId() . '.' . $object->getId() . '.' . $number . ',change.add' . '|' . self::getObjectForEdit($object, $mode, $context);
    }

    /**
     * Compose the command to move an object to another object
     * 
     * @param object $object the object to move
     * @param object $target the object to move the object to
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function moveObjectToObject($object, $target, $mode, $context) {
        // move the object and refresh
        return 'object,' . $object->getId() . '.' . $target->getId() . ',change.moveobject' . '|' . self::getObject($target, $mode, $context);
    }

    /**
     * Compose the command to cancel an add
     * 
     * @param object $object
     * @return string
     */
    public static function addObjectCancel($object) {
        return 'object,' . $object->getId() . ',change.cancelobject';
    }

    /**
     * Compose the command to cancel a move
     * 
     * @param object $object
     * @return string
     */
    public static function moveObjectCancel($object) {
        return 'object,' . $object->getId() . ',change.cancelmove';
    }

    /**
     * Compose the command to load the config panel, the config panel
     * is loaded from within an object (hence the reference to the object)
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configSite($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.config' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the layout configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configLayouts($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configlayouts' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual layout configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configLayout($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configlayout' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the structure configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configStructures($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configstructures' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual structure configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configStructure($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configstructure' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the style configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configStyles($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configstyles' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the style param configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configStyleParams($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configstyleparams' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual style configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configStyle($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configstyle' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual style param configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configStyleParam($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configstyleparam' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the set configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configSets($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configsets' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual set configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configSet($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configset' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the template configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configTemplates($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configtemplates' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the users configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configUsers($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configusers' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual user configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configUser($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configuser' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the user groups configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configUserGroups($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configusergroups' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual user group configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configUserGroup($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configusergroup' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the roles configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configRoles($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configroles' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual role configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configRole($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configrole' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the settings configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configSettings($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configsettings' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the indivudual setting configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configSetting($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configsetting' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the include files configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configIncludeFiles($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configincludefiles' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the indivudual include file configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configIncludeFile($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configincludefile' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the snippets configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configSnippets($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configsnippets' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the update configurator in the config panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configUpdate($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configupdate' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual snippet configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configSnippet($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configsnippet' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to load the individual template configurator in the config detail panel
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function configTemplate($object, $mode, $context) {
        return 'object,' . $object->getId() . ',admin.configtemplate' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to cancel a config panel
     * 
     * @param object $object
     * @return string
     */
    public static function configCancel($object) {
        return 'object,' . $object->getId() . ',change.cancelconfig';
    }

    /**
     * Compose the command to edit the layout name
     * 
     * @param layout $layout
     * @return string
     */
    public static function editLayoutName($layout) {
        return 'layout,' . $layout->getId() . ',change.layoutname';
    }

    /**
     * Compose the command to edit the layout set
     * 
     * @param layout $layout
     * @return string
     */
    public static function editLayoutSet($layout) {
        return 'layout,' . $layout->getId() . ',change.layoutset';
    }

    /**
     * Compose the command to remove a layout 
     * 
     * @param object $object
     * @param layout $layout
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeLayout($object, $layout, $mode, $context) {
        return 'layout,' . $layout->getId() . ',change.layoutremove' . '|' . self::configLayouts($object, $mode, $context);
    }

    /**
     * Compose the command to add a layout 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addLayout($object, $mode, $context) {
        return 'layout,' . Layout::DEFAULT_LAYOUT . ',change.layoutadd' . '|' . self::configLayouts($object, $mode, $context);
    }

    /**
     * Compose the command to remove a layout version
     * 
     * @param object $object
     * @param layout $layout
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function removeLayoutVersion($object, $layout, $mode, $context, $showcontext) {
        return 'layoutversion,' . $layout->getId() . ',change.layoutversionremove' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configLayout($object, $mode, $showcontext);
    }

    /**
     * Compose the command to publish a layout version
     * 
     * @param object $object
     * @param layout $layout
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function publishLayoutVersion($object, $layout, $mode, $context, $showcontext) {
        return 'layoutversion,' . $layout->getId() . ',change.layoutversionpublish' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configLayout($object, $mode, $showcontext);
    }

    /**
     * Compose the command to cancel a layout version
     * 
     * @param object $object
     * @param layout $layout
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function cancelLayoutVersion($object, $layout, $mode, $context, $showcontext) {
        return 'layoutversion,' . $layout->getId() . ',change.layoutversioncancel' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configLayout($object, $mode, $showcontext);
    }

    /**
     * Compose the command to add a layout version
     * 
     * @param object $object
     * @param layout $layout
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function addLayoutVersion($object, $layout, $mode, $context, $showcontext) {
        return 'layoutversion,' . $layout->getId() . ',change.layoutversionadd' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configLayout($object, $mode, $showcontext);
    }

    /**
     * Compose the command to edit the layout version body
     * 
     * @param layout $layout
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editLayoutVersionBody($layout, $mode, $context) {
        return 'layoutversion,' . $layout->getId() . ',change.layoutversionbody' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to edit the structure name
     * 
     * @param structure $structure
     * @return string
     */
    public static function editStructureName($structure) {
        return 'structure,' . $structure->getId() . ',change.structurename';
    }

    /**
     * Compose the command to edit the structure set
     * 
     * @param structure $structure
     * @return string
     */
    public static function editStructureSet($structure) {
        return 'structure,' . $structure->getId() . ',change.structureset';
    }

    /**
     * Compose the command to remove a structure 
     * 
     * @param object $object
     * @param structure $structure
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeStructure($object, $structure, $mode, $context) {
        return 'structure,' . $structure->getId() . ',change.structureremove' . '|' . self::configStructures($object, $mode, $context);
    }

    /**
     * Compose the command to add a structure 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addStructure($object, $mode, $context) {
        return 'structure,' . Structure::DEFAULT_STRUCTURE . ',change.structureadd' . '|' . self::configStructures($object, $mode, $context);
    }

    /**
     * Compose the command to remove a structure version
     * 
     * @param object $object
     * @param structure $structure
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function removeStructureVersion($object, $structure, $mode, $context, $showcontext) {
        return 'structureversion,' . $structure->getId() . ',change.structureversionremove' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStructure($object, $mode, $showcontext);
    }

    /**
     * Compose the command to publish a structure version
     * 
     * @param object $object
     * @param structure $structure
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function publishStructureVersion($object, $structure, $mode, $context, $showcontext) {
        return 'structureversion,' . $structure->getId() . ',change.structureversionpublish' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStructure($object, $mode, $showcontext);
    }

    /**
     * Compose the command to cancel a structure version
     * 
     * @param object $object
     * @param structure $structure
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function cancelStructureVersion($object, $structure, $mode, $context, $showcontext) {
        return 'structureversion,' . $structure->getId() . ',change.structureversioncancel' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStructure($object, $mode, $showcontext);
    }

    /**
     * Compose the command to add a structure version
     * 
     * @param object $object
     * @param structure $structure
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function addStructureVersion($object, $structure, $mode, $context, $showcontext) {
        return 'structureversion,' . $structure->getId() . ',change.structureversionadd' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStructure($object, $mode, $showcontext);
    }

    /**
     * Compose the command to edit the structure version body
     * 
     * @param structure $structure
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editStructureVersionBody($structure, $mode, $context) {
        return 'structureversion,' . $structure->getId() . ',change.structureversionbody' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to edit the style name
     * 
     * @param style $style
     * @return string
     */
    public static function editStyleName($style) {
        return 'style,' . $style->getId() . ',change.stylename';
    }

    /**
     * Compose the command to edit the style param name
     * 
     * @param styleparam $styleparam
     * @return string
     */
    public static function editStyleParamName($styleparam) {
        return 'styleparam,' . $styleparam->getId() . ',change.styleparamname';
    }

    /**
     * Compose the command to edit the style type
     * 
     * @param style $style
     * @return string
     */
    public static function editStyleType($style) {
        return 'style,' . $style->getId() . ',change.styletype';
    }

    /**
     * Compose the command to edit the style class suffix
     * 
     * @param style $style
     * @return string
     */
    public static function editStyleClassSuffix($style) {
        return 'style,' . $style->getId() . ',change.styleclasssuffix';
    }

    /**
     * Compose the command to edit the style set
     * 
     * @param style $style
     * @return string
     */
    public static function editStyleSet($style) {
        return 'style,' . $style->getId() . ',change.styleset';
    }

    /**
     * Compose the command to remove a style 
     * 
     * @param object $object
     * @param style $style
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeStyle($object, $style, $mode, $context) {
        return 'style,' . $style->getId() . ',change.styleremove' . '|' . self::configStyles($object, $mode, $context);
    }

    /**
     * Compose the command to remove a style param
     * 
     * @param object $object
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeStyleParam($object, $styleparam, $mode, $context) {
        return 'styleparam,' . $styleparam->getId() . ',change.styleparamremove' . '|' . self::configStyleParams($object, $mode, $context);
    }

    /**
     * Compose the command to add a style 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addStyle($object, $mode, $context) {
        return 'style,' . Style::DEFAULT_STYLE . ',change.styleadd' . '|' . self::configStyles($object, $mode, $context);
    }

    /**
     * Compose the command to add a style param
     * 
     * @param object $object
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addStyleParam($object, $styleparam, $mode, $context) {
        return 'styleparam,' . $styleparam->getId() . ',change.styleparamadd' . '|' . self::configStyleParams($object, $mode, $context);
    }

    /**
     * Compose the command to remove a style version
     * 
     * @param object $object
     * @param style $style
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function removeStyleVersion($object, $style, $mode, $context, $showcontext) {
        return 'styleversion,' . $style->getId() . ',change.styleversionremove' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyle($object, $mode, $showcontext);
    }

    /**
     * Compose the command to remove a style param version
     * 
     * @param object $object
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function removeStyleParamVersion($object, $styleparam, $mode, $context, $showcontext) {
        return 'styleparamversion,' . $styleparam->getId() . ',change.styleparamversionremove' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyleParam($object, $mode, $showcontext);
    }

    /**
     * Compose the command to publish a style version
     * 
     * @param object $object
     * @param style $style
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function publishStyleVersion($object, $style, $mode, $context, $showcontext) {
        return 'styleversion,' . $style->getId() . ',change.styleversionpublish' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyle($object, $mode, $showcontext);
    }

    /**
     * Compose the command to publish a style param version
     * 
     * @param object $object
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function publishStyleParamVersion($object, $styleparam, $mode, $context, $showcontext) {
        return 'styleparamversion,' . $styleparam->getId() . ',change.styleparamversionpublish' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyleParam($object, $mode, $showcontext);
    }

    /**
     * Compose the command to cancel a style version
     * 
     * @param object $object
     * @param style $style
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function cancelStyleVersion($object, $style, $mode, $context, $showcontext) {
        return 'styleversion,' . $style->getId() . ',change.styleversioncancel' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyle($object, $mode, $showcontext);
    }

    /**
     * Compose the command to cancel a style param version
     * 
     * @param object $object
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function cancelStyleParamVersion($object, $styleparam, $mode, $context, $showcontext) {
        return 'styleparamversion,' . $styleparam->getId() . ',change.styleparamversioncancel' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyleParam($object, $mode, $showcontext);
    }

    /**
     * Compose the command to add a style version
     * 
     * @param object $object
     * @param style $style
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function addStyleVersion($object, $style, $mode, $context, $showcontext) {
        return 'styleversion,' . $style->getId() . ',change.styleversionadd' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyle($object, $mode, $showcontext);
    }

    /**
     * Compose the command to add a style param version
     * 
     * @param object $object
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     * @param context $showcontext
     * @return string
     */
    public static function addStyleParamVersion($object, $styleparam, $mode, $context, $showcontext) {
        return 'styleparamversion,' . $styleparam->getId() . ',change.styleparamversionadd' . '.' . $mode->getId() . '.' . $context->getId() . '|' . self::configStyleParam($object, $mode, $showcontext);
    }

    /**
     * Compose the command to edit the style version body
     * 
     * @param style $style
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editStyleVersionBody($style, $mode, $context) {
        return 'styleversion,' . $style->getId() . ',change.styleversionbody' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to edit the style param version body
     * 
     * @param styleparam $styleparam
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editStyleParamVersionBody($styleparam, $mode, $context) {
        return 'styleparamversion,' . $styleparam->getId() . ',change.styleparamversionbody' . '.' . $mode->getId() . '.' . $context->getId();
    }

    /**
     * Compose the command to edit the set name
     * 
     * @param set $set
     * @return string
     */
    public static function editSetName($set) {
        return 'set,' . $set->getId() . ',change.setname';
    }

    /**
     * Compose the command to edit the setting value
     * 
     * @param setting $setting
     * @return string
     */
    public static function editSettingValue($setting) {
        return 'setting,' . $setting->getId() . ',change.settingvalue';
    }

    /**
     * Compose the command to edit the user name
     * 
     * @param user $user
     * @return string
     */
    public static function editUserName($user) {
        return 'user,' . $user->getId() . ',change.username';
    }

    /**
     * Compose the command to edit the user password
     * 
     * @param user $user
     * @return string
     */
    public static function editUserPassword($user) {
        return 'user,' . $user->getId() . ',change.userpassword';
    }

    /**
     * Compose the command to edit the user first name
     * 
     * @param user $user
     * @return string
     */
    public static function editUserFirstName($user) {
        return 'user,' . $user->getId() . ',change.userfirstname';
    }

    /**
     * Compose the command to edit the user last name
     * 
     * @param user $user
     * @return string
     */
    public static function editUserLastName($user) {
        return 'user,' . $user->getId() . ',change.userlastname';
    }

    /**
     * Compose the command to edit the user user group
     * 
     * @param user $user
     * @param usergroup $usergroup
     * @return string
     */
    public static function editUserUserGroup($user, $usergroup) {
        return 'user,' . $user->getId() . '.' . $usergroup->getId() . ',change.userusergroup';
    }

    /**
     * Compose the command to reset the user login counter
     * 
     * @param object $object
     * @param user $user
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function editUserLoginCounter($object, $user, $mode, $context) {
        return 'user,' . $user->getId() . ',change.userlogincounter' . '|' . self::configUser($object, $mode, $context);
    }

    /**
     * Compose the command to edit the user group name
     * 
     * @param usergroup $usergroup
     * @return string
     */
    public static function editUserGroupName($usergroup) {
        return 'usergroup,' . $usergroup->getId() . ',change.usergroupname';
    }

    /**
     * Compose the command to edit the role name
     * 
     * @param role $role
     * @return string
     */
    public static function editRoleName($role) {
        return 'role,' . $role->getId() . ',change.rolename';
    }

    /**
     * Compose the command to edit the includefile name
     * 
     * @param fileinclude $includefile
     * @return string
     */
    public static function editIncludeFileName($includefile) {
        return 'includefile,' . $includefile->getId() . ',change.includefilename';
    }

    /**
     * Compose the command to edit the includefile file type
     * 
     * @param fileinclude $includefile
     * @return string
     */
    public static function editIncludeFileMimeType($includefile) {
        return 'includefile,' . $includefile->getId() . ',change.includefilemimetype';
    }

    /**
     * Compose the command to edit the includefile comment 
     * 
     * @param fileinclude $includefile
     * @return string
     */
    public static function editIncludeFileComment($includefile) {
        return 'includefile,' . $includefile->getId() . ',change.includefilecomment';
    }

    /**
     * Compose the command to edit the includefile version body
     * 
     * @param fileinclude $includefile
     * @return string
     */
    public static function editIncludeFileVersionBody($includefile) {
        return 'includefile,' . $includefile->getId() . ',change.includefileversionbody';
    }

    /**
     * Compose the command to edit the includefile mime type
     * 
     * @param fileinclude $includefile
     * @return string
     */
    public static function publishIncludeFileVersion($includefile) {
        return 'includefile,' . $includefile->getId() . ',change.includefileversionpublish';
    }

    /**
     * Compose the command to edit the snippet name
     * 
     * @param snippet $snippet
     * @return string
     */
    public static function editSnippetName($snippet) {
        return 'snippet,' . $snippet->getId() . ',change.snippetname';
    }

    /**
     * Compose the command to edit the snippet name
     * 
     * @param snippet $snippet
     * @return string
     */
    public static function editSnippetMimeType($snippet) {
        return 'snippet,' . $snippet->getId() . ',change.snippetmimetype';
    }

    /**
     * Compose the command to edit the snippet context group
     * 
     * @param snippet $snippet
     * @return string
     */
    public static function editSnippetContextGroup($snippet) {
        return 'snippet,' . $snippet->getId() . ',change.snippetcontextgroup';
    }

    /**
     * Compose the command to edit the snippet version body
     * 
     * @param snippet $snippet
     * @return string
     */
    public static function editSnippetVersionBody($snippet) {
        return 'snippet,' . $snippet->getId() . ',change.snippetversionbody';
    }

    /**
     * Compose the command to edit the snippet mime type
     * 
     * @param snippet $snippet
     * @return string
     */
    public static function publishSnippetVersion($snippet) {
        return 'snippet,' . $snippet->getId() . ',change.snippetversionpublish';
    }

    /**
     * Compose the command to remove a set 
     * 
     * @param object $object
     * @param set $set
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeSet($object, $set, $mode, $context) {
        return 'set,' . $set->getId() . ',change.setremove' . '|' . self::configSets($object, $mode, $context);
    }

    /**
     * Compose the command to remove a user 
     * 
     * @param object $object
     * @param user $user
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeUser($object, $user, $mode, $context) {
        return 'user,' . $user->getId() . ',change.userremove' . '|' . self::configUsers($object, $mode, $context);
    }

    /**
     * Compose the command to remove a user group
     * 
     * @param object $object
     * @param usergroup $usergroup
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeUserGroup($object, $usergroup, $mode, $context) {
        return 'usergroup,' . $usergroup->getId() . ',change.usergroupremove' . '|' . self::configUserGroups($object, $mode, $context);
    }

    /**
     * Compose the command to remove a role 
     * 
     * @param object $object
     * @param role $role
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeRole($object, $role, $mode, $context) {
        return 'role,' . $role->getId() . ',change.roleremove' . '|' . self::configRoles($object, $mode, $context);
    }

    /**
     * Compose the command to remove a includefile 
     * 
     * @param object $object
     * @param includefile $includefile
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeIncludeFile($object, $includefile, $mode, $context) {
        return 'includefile,' . $includefile->getId() . ',change.includefileremove' . '|' . self::configIncludeFiles($object, $mode, $context);
    }

    /**
     * Compose the command to remove a snippet 
     * 
     * @param object $object
     * @param snippet $snippet
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeSnippet($object, $snippet, $mode, $context) {
        return 'snippet,' . $snippet->getId() . ',change.snippetremove' . '|' . self::configSnippets($object, $mode, $context);
    }

    /**
     * Compose the command to add a set 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addSet($object, $mode, $context) {
        return 'set,' . Set::DEFAULT_SET . ',change.setadd' . '|' . self::configSets($object, $mode, $context);
    }

    /**
     * Compose the command to add a user 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addUser($object, $mode, $context) {
        return 'user,' . User::DEFAULT_USER . ',change.useradd' . '|' . self::configUsers($object, $mode, $context);
    }

    /**
     * Compose the command to add a user group
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addUserGroup($object, $mode, $context) {
        return 'usergroup,' . UserGroup::DEFAULT_USERGROUP . ',change.usergroupadd' . '|' . self::configUserGroups($object, $mode, $context);
    }

    /**
     * Compose the command to add a role 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addRole($object, $mode, $context) {
        return 'role,' . Role::DEFAULT_ROLE . ',change.roleadd' . '|' . self::configRoles($object, $mode, $context);
    }

    /**
     * Compose the command to add a included file 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addIncludeFile($object, $mode, $context) {
        return 'includefile,' . FileInclude::DEFAULT_FILE_INCLUDE . ',change.includefileadd' . '|' . self::configIncludeFiles($object, $mode, $context);
    }

    /**
     * Compose the command to add a snippet 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addSnippet($object, $mode, $context) {
        return 'snippet,' . Snippet::DEFAULT_SNIPPET . ',change.snippetadd' . '|' . self::configSnippets($object, $mode, $context);
    }

    /**
     * Compose the command to edit the template name
     * 
     * @param template $template
     * @return string
     */
    public static function editTemplateName($template) {
        return 'template,' . $template->getId() . ',change.templatename';
    }

    /**
     * Compose the command to edit the template deleted
     * 
     * @param template $template
     * @return string
     */
    public static function editTemplateDeleted($template) {
        return 'template,' . $template->getId() . ',change.templatedeleted';
    }

    /**
     * Compose the command to edit the template searchable
     * 
     * @param template $template
     * @return string
     */
    public static function editTemplateSearchable($template) {
        return 'template,' . $template->getId() . ',change.templatesearchable';
    }

    /**
     * Compose the command to edit the template instance allowed
     * 
     * @param template $template
     * @return string
     */
    public static function editTemplateInstanceAllowed($template) {
        return 'template,' . $template->getId() . ',change.templateinstanceallowed';
    }

    /**
     * Compose the command to edit the template set
     * 
     * @param template $template
     * @return string
     */
    public static function editTemplateSet($template) {
        return 'template,' . $template->getId() . ',change.templateset';
    }

    /**
     * Compose the command to edit the template structure
     * 
     * @param template $template
     * @return string
     */
    public static function editTemplateStructure($template) {
        return 'template,' . $template->getId() . ',change.templatestructure';
    }

    /**
     * Compose the command to edit the template style
     * 
     * @param template $template
     * @return string
     */
    public static function editTemplateStyle($template) {
        return 'template,' . $template->getId() . ',change.templatestyle';
    }

    /**
     * Compose the command to remove a template 
     * 
     * @param object $object
     * @param template $template
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function removeTemplate($object, $template, $mode, $context) {
        return 'template,' . $template->getId() . ',change.templateremove' . '|' . self::configTemplates($object, $mode, $context);
    }

    /**
     * Compose the command to add a template 
     * 
     * @param object $object
     * @param mode $mode
     * @param context $context
     * @return string
     */
    public static function addTemplate($object, $mode, $context) {
        return 'template,' . Template::DEFAULT_TEMPLATE . ',change.templateadd' . '|' . self::configTemplates($object, $mode, $context);
    }

    /**
     * Compose the command to remove a position
     * 
     * @param object $containerobject
     * @param object $object
     * @param position $position
     * @param mode $mode
     * @return string
     */
    public static function removeObjectPosition($containerobject, $object, $position, $mode, $context) {
        return 'objectposition,' . $object->getId() . '.' . $position->getNumber() . ',change.objectpositionremove' . '|' . self::configTemplate($containerobject, $mode, $context);
    }

    /**
     * Compose the command to add a position content item
     * 
     * @param object $containerobject
     * @param object $object
     * @param int $number
     * @param mode $mode
     * @return string
     */
    public static function addPositionContentItem($containerobject, $object, $number, $mode, $context) {
        return 'objectposition,' . $object->getId() . '.' . $number . ',change.positioncontentitemadd' . '|' . self::configTemplate($containerobject, $mode, $context);
    }

    /**
     * Compose the command to add a position object
     * 
     * @param object $containerobject
     * @param object $object
     * @param int $number
     * @param mode $mode
     * @return string
     */
    public static function addPositionObject($containerobject, $object, $number, $mode, $context) {
        return 'objectposition,' . $object->getId() . '.' . $number . ',change.positionobjectadd' . '|' . self::configTemplate($containerobject, $mode, $context);
    }

    /**
     * Compose the command to add a position instance
     * 
     * @param object $containerobject
     * @param object $object
     * @param int $number
     * @param mode $mode
     * @return string
     */
    public static function addPositionInstance($containerobject, $object, $number, $mode, $context) {
        return 'objectposition,' . $object->getId() . '.' . $number . ',change.positioninstanceadd' . '|' . self::configTemplate($containerobject, $mode, $context);
    }

    /**
     * Compose the command to add a position referral
     * 
     * @param object $containerobject
     * @param object $object
     * @param int $number
     * @param mode $mode
     * @return string
     */
    public static function addPositionReferral($containerobject, $object, $number, $mode, $context) {
        return 'objectposition,' . $object->getId() . '.' . $number . ',change.positionreferraladd' . '|' . self::configTemplate($containerobject, $mode, $context);
    }

    /**
     * Compose the command to edit a permission
     * 
     * @param permission $permission
     * @param string $permissiontype
     * @return string
     */
    public static function editPermission($permission, $permissiontype) {
        return 'permission,' . $permission->getId() . ',change.' . $permissiontype;
    }

    /**
     * Compose the command to download a file with database updates
     * 
     * @param object $object
     * @return string
     */
    public static function downloadUpdate($object) {
        return 'update,' . $object->getId() . ',update.download';
    }

}