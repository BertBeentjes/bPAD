<?php

/**
 * Contains all objects, loads them on demand and stores them for later use.
 * 
 * @since 0.4.0
 */
class Objects {

    private static $objects = array();

    /**
     * get an object by id, checks whether the object is loaded,
     * loads the object if necessary and fills it on demand with
     * further information
     * 
     * @param int objectid the id of the object to get
     * @return Object
     */
    public static function getObject($objectid) {
        if (Validator::isNumeric($objectid)) {
            // return an object
            if (isset(self::$objects[$objectid])) {
                return self::$objects[$objectid];
            } else {
                self::$objects[$objectid] = new Object($objectid);
                if (is_object(self::$objects[$objectid])) {
                    return self::$objects[$objectid];
                } else {
                    throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
                }
            }
        } else {
            throw new Exception(Helper::getLang(Errors::ERROR_VALIDATION_FAILED) . ' @ ' . __METHOD__);
        }
    }

    /**
     * Get the objects that can be selected by a certain argument and mode, used to create referrals
     * 
     * @param argument $argument
     * @param mode $mode
     * @param string $orderby
     * @return Object[] the array with the objects
     */
    public static function getObjectsByArgumentAndModeAndOrderBy($argument, $mode, $orderby) {
        $objects = array();
        if ($result = Store::getObjectIdByArgumentIdAndModeIdAndOrderBy($argument->getId(), $mode->getId(), $orderby)) {
            while ($row = $result->fetchObject()) {
                $objects[] = self::getObject($row->objectid);
            }
        }
        return $objects;
    }

    /**
     * Get all addressable objects the user has access to
     * 
     * @param mode $mode
     */
    public static function getAddressableObjects($mode) {
        // TODO: this seems rather cumbersome, find a better way to do this (see also comment in Store method)
        $objects = array();
        $counter = 0;
        // add the site root in the first position
        $objects[$counter][0] = SysCon::SITE_ROOT_OBJECT;
        $objects[$counter][1] = Objects::getObject(SysCon::SITE_ROOT_OBJECT)->getName();
        $counter = $counter + 1;
        if ($list = Store::getAddressableObjects($mode->getId())) {
            while ($item = $list->fetchObject()) {
                $object = Objects::getObject($item->id);
                if (Authorization::getObjectPermission($object, Authorization::OBJECT_VIEW)) {
                    $objects[$counter][0] = $item->id;
                    $objects[$counter][1] = $item->name;
                    $counter = $counter + 1;
                }
            }
        }
        return $objects;
    }

    /**
     * Create a new object
     * 
     * @return object
     */
    public static function newObject() {
        $objectid = Store::insertObject();
        $object = self::getObject($objectid);
        // an object must always have an edit and a view version, so create them
        $object->newVersions();
        // initially, an object is inactive (Store does this too, but just to be sure :))
        $object->setActive(false);
        // the object is empty, but can now be used without problems
        $object->setName('Object' . $object->getId());
        return $object;
    }

    /**
     * Delete objects that are orphaned (they aren't templates and there is no
     * containing position). Objects are orphaned by deleting the positions
     * they are in, or by deleting archived versions.
     * 
     * For speed: using one query per table, bypassing the normal object structure.
     */
    public static function removeOrphanedObjects() {
        // get the orphaned objects 
        while ($objects = Store::getOrphanedObjects()) {
            while ($row = $objects->fetchObject()) {
                $object = Objects::getObject($row->id);
                // delete sessions relating to the object
                Store::deleteObjectSessions($object->getId());
                // delete the caches for the object (addressable parent, object cache)
                CacheObjects::removeObjectFromCache($object);
                CacheObjectAddressableParentObjects::removeObjectFromCache($object);                
                // delete the object user group roles
                Store::deleteObjectUserGroupRoles($object->getId());
                // delete the position content
                Store::deleteObjectPositionContentItems($object->getId());
                Store::deleteObjectPositionInstances($object->getId());
                Store::deleteObjectPositionObjects($object->getId());
                Store::deleteObjectPositionReferrals($object->getId());                
                // delete the positions
                Store::deleteObjectPositions($object->getId());
                // delete the object versions
                Store::deleteObjectVersions($object->getId());
                // delete the object from other objects
                Store::deleteObjectFromPositionContentItems($object->getId());
                // delete the object
                Store::deleteObject($object->getId());
            }
        }
    }

    /**
     * Get target objects to move an object to, based upon the set of the object
     * to move
     * 
     * @param set $set
     * @return resultset
     */
    public static function getTargetObjectBySet($set) {
        $targets = Store::getTargetObjectsBySet($set->getId());
        return $targets;
    }

}

?>
