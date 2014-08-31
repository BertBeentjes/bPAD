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
 * The store contains all methods connected to the storage facility.
 * For now, data is stored in MySQL.
 * 
 * Changes are made using 'action queries', selections are made using
 * 'select queries'
 * 
 * @since 0.4.0
 */
class Store {

    private static $connection;

    /**
     * connect to the data store, connection info is stored in the settings
     * 
     * @param host the hostname
     * @param user the user that has access to the db
     * @param pw the password for the user
     * @param db databasename
     * @return boolean  if connection is made
     * @throws exception when connection fails
     */
    public static function connect($host, $user, $pw, $db) {
        self::$connection = new mysqli($host, $user, $pw, $db);
        if (self::$connection->connect_errno) {
            throw new Exception(Helper::getLang(Errors::ERROR_STORE_NOT_AVAILABLE) . PHP_EOL . self::$connection->connect_error);
        }
        return true;
    }

    /**
     * check the connection
     * 
     * @return boolean  if connection is still there
     * @throws exception when connection is broken
     */
    private static function checkConnection() {
        if (self::$connection->connect_errno()) {
            throw new Exception(Helper::getLang(Errors::ERROR_STORE_NOT_AVAILABLE) . PHP_EOL . self::$connection->connect_error());
        }
        return true;
    }

    /**
     * execute an action query, this is a:
     * 
     * - update
     * - delete
     * 
     * query. The action query should be prepared and parameters bound
     * by the calling function.
     * 
     * @param stmt a prepared statement, with paramaters bound
     * @return boolean  when query executes correctly
     * @throws exception when query fails
     */
    private static function actionQuery($stmt) {
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception($stmt->error . ' @ ' . __METHOD__);
        }
        $stmt->close();
        return true;
    }

    /**
     * execute an insert query. The insert query should be prepared and parameters bound
     * by the calling function.
     * 
     * @param stmt a prepared statement, with paramaters bound
     * @return int stmt->insert_id the resulting id for the insert
     * @throws exception when query fails
     */
    private static function insertQuery($stmt) {
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception($stmt->error . ' @ ' . __METHOD__);
        }
        $newid = $stmt->insert_id;
        $stmt->close();
        return $newid;
    }

    /**
     * execute a select query. Returns a result set, wrapped by the result set
     * object. This wrapper is used to prevent specific mysqli code to enter 
     * the rest of the code set. 
     * 
     * Warning!! The function must return the NULL value if no record set
     * is returned. 
     *
     * @param query a string with the query to execute
     * @return resultset or null
     */
    private static function selectQuery($query) {
        if ($result = self::$connection->query($query)) {
            if ($result->num_rows > 0) {
                return new ResultSet($result);
            }
        }
    }

    /**
     * return the table name for templates
     * 
     * @return string table name
     */
    public static function getTableTemplates() {
        return 'templates';
    }

    /**
     * return the table name for objects
     * 
     * @return string table name
     */
    public static function getTableObjects() {
        return 'objects';
    }

    /**
     * return the table name for objectversions
     * 
     * @return string table name
     */
    public static function getTableObjectVersions() {
        return 'objectversions';
    }

    /**
     * return the table name for positions
     * 
     * @return string table name
     */
    public static function getTablePositions() {
        return 'positions';
    }

    /**
     * return the table name for positionobjects
     *
     * @return string table name
     */
    public static function getTablePositionObjects() {
        return 'positionobjects';
    }

    /**
     * return the table name for positionreferrals
     * 
     * @return string table name
     */
    public static function getTablePositionReferrals() {
        return 'positionreferrals';
    }

    /**
     * return the table name for positioninstances
     * 
     * @return string table name
     */
    public static function getTablePositionInstances() {
        return 'positioninstances';
    }

    /**
     * return the table name for positioncontentitems
     * 
     * @return string table name
     */
    public static function getTablePositionContentitems() {
        return 'positioncontentitems';
    }

    /**
     * return the table name for layouts
     * 
     * @return string table name
     */
    public static function getTableLayouts() {
        return 'layouts';
    }

    /**
     * return the table name for structures
     * 
     * @return string table name
     */
    public static function getTableStructures() {
        return 'structures';
    }

    /**
     * return the table name for styles
     * 
     * @return string table name
     */
    public static function getTableStyles() {
        return 'styles';
    }

    /**
     * return the table name for sets
     * 
     * @return string table name
     */
    public static function getTableSets() {
        return 'sets';
    }

    /**
     * return the table name for user groups
     * 
     * @return string table name
     */
    public static function getTableUserGroups() {
        return 'usergroups';
    }

    /**
     * return the table name for roles
     * 
     * @return string table name
     */
    public static function getTableRoles() {
        return 'roles';
    }

    /**
     * return the table name for users
     * 
     * @return string table name
     */
    public static function getTableUsers() {
        return 'users';
    }

    /**
     * return the table name for permissions
     * 
     * @return string table name
     */
    public static function getTablePermissions() {
        return 'permissions';
    }

    /**
     * return the table name for objectusergrouprole
     * 
     * @return string table name
     */
    public static function getTableObjectUserGroupRoles() {
        return 'objectusergrouprole';
    }

    /**
     * return the table name for contexts
     * 
     * @return string table name
     */
    public static function getTableContexts() {
        return 'contexts';
    }

    /**
     * return the table name for context groups
     * 
     * @return string table name
     */
    public static function getTableContextGroups() {
        return 'contextgroups';
    }

    /**
     * return the table name for a contexted item (a version of a layout, style, style param or structure)
     * 
     * @return string table name
     */
    public static function getTableContexted($type) {
        return $type . 'versions';
    }

    /**
     * return the table name for a moded item (a version of a file include or snippet)
     * 
     * @return string table name
     */
    public static function getTableModed($type) {
        return $type . 'versions';
    }

    /**
     * return the table name for modes
     * 
     * @return string table name
     */
    public static function getTableModes() {
        return 'modes';
    }

    /**
     * return the table name for arguments
     * 
     * @return string table name
     */
    public static function getTableArguments() {
        return 'arguments';
    }

    /**
     * return the table name for style params
     * 
     * @return string table name
     */
    public static function getTableStyleParams() {
        return 'styleparams';
    }

    /**
     * return the table name for style param versions
     * 
     * @return string table name
     */
    public static function getTableStyleParamVersions() {
        return 'styleparamversions';
    }

    /**
     * return the table name for settings
     * 
     * @return string table name
     */
    public static function getTableSettings() {
        return 'settings';
    }

    /**
     * return the table name for command log
     * 
     * @return string table name
     */
    public static function getTableCommandLog() {
        return 'commandlog';
    }

    /**
     * return the table name for event log
     * 
     * @return string table name
     */
    public static function getTableEventLog() {
        return 'eventlog';
    }

    /**
     * return the table name for file includes
     * 
     * @return string table name
     */
    public static function getTableFileIncludes() {
        return 'fileincludes';
    }

    /**
     * return the table name for snippets
     * 
     * @return string table name
     */
    public static function getTableSnippets() {
        return 'snippets';
    }

    /**
     * set changedate, changeuser for a table
     * this is called by the storedentity base class,
     * all logical objects extend the storedentity class
     * 
     * @param tablename the name of the table that has changed
     * @param int the id of the row to update
     * @return boolean  if action query succeeds
     */
    public static function setChanged($tablename, $id) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET changedate=NOW(), fk_changeuser_id=? WHERE id=?")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * get changedate, changeuser for a table
     * 
     * @param tablename the name of the table that has changed
     * @param int the id of the row to update
     * @return resultset changedate, changeuserid
     */
    public static function getChanged($tablename, $id) {
        return self::selectQuery("SELECT changedate, fk_changeuser_id changeuserid FROM " . $tablename . " WHERE id=" . $id);
    }

    /**
     * get the newer command than the current command
     * 
     * @return resultset commandid
     */
    public static function getNewerCommand($item, $command, $itemaddress, $sessionidentifier, $commandnumber) {
        return self::selectQuery("SELECT id commandid FROM commandlog WHERE sessionidentifier='" . $sessionidentifier . "' AND commandnumber>" . $commandnumber . " AND item='" . $item . "' AND command='" . $command . "' AND itemaddress=" . $itemaddress . " AND oldvalue IS NOT NULL ORDER BY id DESC LIMIT 0,1");
    }

    /**
     * check lock, or: check whether a different user has executed a command on the same item after the lastcommandid
     * 
     * @return resultset commandid
     */
    public static function getOtherCommand($item, $command, $itemaddress, $sessionidentifier, $lastcommandid) {
        return self::selectQuery("SELECT id commandid FROM commandlog WHERE sessionidentifier<>'" . $sessionidentifier . "' AND id>" . $lastcommandid . " AND item='" . $item . "' AND command='" . $command . "' AND itemaddress=" . $itemaddress . " AND oldvalue IS NOT NULL ORDER BY id DESC LIMIT 0,1");
    }

    /**
     * get the positionobject for a positionid (or null if not available)
     * 
     * @param int $positionid
     * @return resultset id, objectid, createdate, createuserid, changedate, changeuserid
     */
    public static function getPositionObject($positionid) {
        return self::selectQuery("SELECT id, fk_object_id objectid, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM positionobjects WHERE fk_position_id=" . $positionid);
    }

    /**
     * get the positioncontentitem for a positionid (or null if not available)
     * 
     * @param int $positionid
     * @return resultset id, name, body, inputtype, rootobjectid, templateid, hasinternallinks, createdate, createuserid, changedate, changeuserid
     */
    public static function getPositionContentItem($positionid) {
        return self::selectQuery("SELECT id, name, contentitembody body, inputtype, fk_rootobject_id rootobjectid, fk_template_id templateid, hasinternallinks, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM positioncontentitems WHERE fk_position_id=" . $positionid);
    }

    /**
     * get distinct body values based upon template and input type, used for
     * creating the combo boxes for content items
     * 
     * @param int $templateid
     * @param string $inputtype
     * @return resultset body
     */
    public static function getPositionContentItemDistinctBodiesByNameAndTemplateIdAndInputType($name, $templateid, $inputtype) {
        return self::selectQuery("SELECT DISTINCT positioncontentitems.contentitembody body FROM positioncontentitems WHERE positioncontentitems.name='" . $name . "' AND positioncontentitems.fk_template_id=" . $templateid . " AND positioncontentitems.inputtype='" . $inputtype . "'");
    }

    /**
     * get the positionreferral for a positionid (or null if not available)
     * 
     * @param int $positionid
     * @return resultset id, argumentid, orderby, numberofitems, createdate, createuserid, changedate, changeuserid
     */
    public static function getPositionReferral($positionid) {
        return self::selectQuery("SELECT id, fk_argument_id argumentid, orderby, numberofitems, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM positionreferrals WHERE fk_position_id=" . $positionid);
    }

    /**
     * get the positioninstance for a positionid (or null if not available)
     * 
     * @param int $positionid
     * @return resultset id, objectid, templateid, listwords, searchwords, parentid, activeitems, fillonload, useinstancecontext, orderby, groupby, createdate, createuserid, changedate, changeuserid
     */
    public static function getPositionInstance($positionid) {
        return self::selectQuery("SELECT id, object_id objectid, template_id templateid, listwords, searchwords, parent_id parentid, activeitems, fillonload, useinstancecontext, orderby, groupby, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid, outdated FROM positioninstances WHERE fk_position_id=" . $positionid);
    }

    /**
     * get the positioninstancecache for a positioninstanceid (or null if not available)
     * 
     * @param int $positioninstanceid
     * @return resultset id, positioninstanceid, objectid
     */
    public static function getPositionInstanceCache($positioninstanceid) {
        return self::selectQuery("SELECT id, fk_positioninstance_id positioninstanceid, fk_object_id objectid FROM positioninstancecache WHERE fk_positioninstance_id=" . $positioninstanceid);
    }

    /**
     * get the object cache item
     * 
     * @param int $id
     * @return resultset objectid, contextid, cachedate, cache, outdated, userid
     */
    public static function getObjectCache($id) {
        return self::selectQuery("SELECT fk_object_id objectid, fk_context_id contextid, cachedate, cache, outdated, fk_user_id userid FROM objectcache WHERE id=" . $id);
    }

    /**
     * get the object cache item
     * 
     * @param int $objectid
     * @param int $contextid
     * @param int $userid
     * @return resultset id
     */
    public static function getObjectCacheByObjectIdContextIdUserId($objectid, $contextid, $userid) {
        return self::selectQuery("SELECT id FROM objectcache WHERE fk_object_id=" . $objectid . " AND fk_context_id=" . $contextid . " AND fk_user_id=" . $userid);
    }

    /**
     * get the stylesheet cache item
     * 
     * @param int $id
     * @return resultset cachedate, cache, outdated
     */
    public static function getStylesheetCache($id) {
        return self::selectQuery("SELECT cachedate, cache, outdated FROM stylesheetcache WHERE id=" . $id);
    }

    /**
     * get the stylesheet cache items
     * 
     * @return resultset id
     */
    public static function getStylesheetCacheItems() {
        return self::selectQuery("SELECT id FROM stylesheetcache");
    }

    /**
     * get the style version ids by mode id
     * 
     * @param int $styleid
     * @param int $modeid
     * @return resultset id, styleid, contextid
     */
    public static function getStyleVersionsByModeId($modeid) {
        return self::selectQuery("SELECT id, fk_style_id styleid, fk_context_id contextid FROM styleversions WHERE fk_mode_id=" . $modeid);
    }

    /**
     * get the version item
     * 
     * @param int $id
     * @return resultset version, releasedate, releaseinfo
     */
    public static function getVersion($id) {
        return self::selectQuery("SELECT version, releasedate, releaseinfo FROM version WHERE id=" . $id);
    }

    /**
     * get the latest version item
     * 
     * @return resultset id
     */
    public static function getLatestVersion() {
        return self::selectQuery("SELECT id FROM version ORDER BY id DESC LIMIT 0,1");
    }

    /**
     * set object active or inactive
     * 
     * @param int the id of the row to update
     * @param bool the value (true or false)
     * @return boolean  if action query succeeds
     */
    public static function setObjectActive($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET active=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object new or not
     * 
     * @param int the id of the row to update
     * @param bool the value (true or false)
     * @return boolean  if action query succeeds
     */
    public static function setObjectNew($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET new=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object name
     * 
     * @param int the id of the row to update
     * @param newname the string with the new name
     * @return boolean  if action query succeeds
     */
    public static function setObjectName($id, $newname) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET name=? WHERE id=?")) {
            $stmt->bind_param("si", $newname, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object session identifier
     * 
     * @param int the id of the row to update
     * @param newsessionidentifier the string with the new session identifier
     * @return boolean  if action query succeeds
     */
    public static function setObjectSessionIdentifier($id, $newsessionidentifier) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET newsessionidentifier=? WHERE id=?")) {
            $stmt->bind_param("si", $newsessionidentifier, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object set id
     * 
     * @param int the id of the row to update
     * @param newsetid the new set id
     * @return boolean  if action query succeeds
     */
    public static function setObjectSetId($id, $newsetid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET fk_set_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newsetid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object istemplate value
     * 
     * @param int the id of the row to update
     * @param bool the new value (true or false)
     * @return boolean  if action query succeeds
     */
    public static function setObjectIsTemplate($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET istemplate=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object istemplateroot value
     * 
     * @param int the id of the row to update
     * @param bool the new value (true or false)
     * @return boolean  if action query succeeds
     */
    public static function setObjectIsTemplateRoot($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET istemplateroot=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object isobjecttemplateroot value
     * 
     * @param int the id of the row to update
     * @param bool the new value (true or false)
     * @return boolean  if action query succeeds
     */
    public static function setObjectIsObjectTemplateRoot($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET isobjecttemplateroot=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object template id
     * 
     * @param int the id of the row to update
     * @param newtemplateid the new template id
     * @return boolean  if action query succeeds
     */
    public static function setObjectTemplateId($id, $newtemplateid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objects SET fk_template_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newtemplateid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object layout id
     * 
     * @param int the id of the row to update
     * @param newlayoutid the new layout id
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionLayoutId($id, $newlayoutid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET fk_layout_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newlayoutid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object style id
     * 
     * @param int the id of the row to update
     * @param newstyleid the new style id
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionStyleId($id, $newstyleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET fk_style_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newstyleid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object argument id
     * 
     * @param int the id of the row to update
     * @param newargumentid the new argument id
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionArgumentId($id, $newargumentid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET fk_argument_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newargumentid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object argument default value
     * 
     * @param int the id of the row to update
     * @param newargumentdefault the new default value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionArgumentDefault($id, $newargumentdefault) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET argumentdefault=? WHERE id=?")) {
            $stmt->bind_param("ii", $newargumentdefault, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object inherit layout value
     * 
     * @param int the id of the row to update
     * @param bool the new inherit layout value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionInheritLayout($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET inheritlayout=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object inherit context value
     * 
     * @param int the id of the row to update
     * @param bool the new inherit context value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionInheritContext($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET inheritcontext=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object inherit style value
     * 
     * @param int the id of the row to update
     * @param bool the new inherit style value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionInheritStyle($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET inheritstyle=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object context id
     * 
     * @param int the id of the row to update
     * @param newcontextid the new context id value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionContextId($id, $newcontextid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET fk_context_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newcontextid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object template id
     * 
     * @param int the id of the row to update
     * @param newtemplateid the new template id value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionTemplateId($id, $newtemplateid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET fk_template_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newtemplateid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object order ascending value
     * 
     * @param int the id of the row to update
     * @param bool the new order ascending value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionOrderAscending($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET orderascending=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the mode value
     * 
     * @param int the id of the row to update
     * @param int the new mode value
     * @return boolean  if action query succeeds
     */
    public static function setObjectVersionMode($id, $newmodeid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectversions SET fk_mode_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newmodeid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * get the parent for this object for the specified mode
     * 
     * @param int the id of the row 
     * @param modeid the mode to use
     * @return resultset parentobjectid, parentpositionnumber
     */
    public static function getObjectParentIds($id, $modeid) {
        // the site root has no parents (well, only briefly, during a create, positionobjects can point towards the site root)
        if ($id != SysCon::SITE_ROOT_OBJECT) {
            return self::selectQuery("SELECT objectversions.fk_object_id parentobjectid, positions.number parentpositionnumber FROM objectversions INNER JOIN positions ON objectversions.id=positions.fk_objectversion_id INNER JOIN positionobjects ON positions.id=positionobjects.fk_position_id WHERE positionobjects.fk_object_id=" . $id . " AND objectversions.fk_mode_id=" . $modeid);
        }
    }

    /**
     * get the basic attributes for a template
     * 
     * @param int the id of the row
     * @return resultset name, deleted, structureid, styleid, instanceallowed, searchable, setid, isbpaddefined, createdate, createuserid, changedate, changeuserid
     */
    public static function getTemplate($id) {
        return self::selectQuery("SELECT templates.name, templates.deleted, templates.fk_structure_id structureid, templates.fk_style_id styleid, templates.instanceallowed, templates.searchable, templates.fk_set_id setid, templates.isbpaddefined, templates.createdate, templates.fk_createuser_id createuserid, templates.changedate, templates.fk_changeuser_id changeuserid FROM templates WHERE templates.id=" . $id);
    }

    /**
     * get the root object for a template
     * 
     * @param int the id of the row
     * @return resultset objectid
     */
    public static function getTemplateRootObject($id) {
        return self::selectQuery("SELECT objects.id objectid FROM templates INNER JOIN objects ON templates.id=objects.fk_template_id WHERE objects.istemplate=1 AND objects.istemplateroot=1 AND templates.id=" . $id);
    }

    /**
     * get the basic attributes for an object
     * 
     * @param int the id of the row 
     * @return resultset active, new, name, istemplate, istemplateroot, isobjecttemplateroot, templateid, setid, sessionidentifier, createdate, createuserid, changedate, changeuserid
     */
    public static function getObject($id) {
        return self::selectQuery("SELECT active, new, name, istemplate, istemplateroot, isobjecttemplateroot, fk_template_id templateid, fk_set_id setid, sessionidentifier, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM objects WHERE id=" . $id);
    }

    /**
     * get all object ids, not normally used, created for use in a script that updates to a new version of bPAD
     * 
     * @return resultset id
     */
    public static function getObjects() {
        return self::selectQuery("SELECT objects.id FROM objects");
    }

    /**
     * get objectids that can be selected by a specific argument in a certain mode
     * 
     * @param int the id of the argument
     * @param int the id of the mode
     * @param string orderby
     * @return resultset objectid
     */
    public static function getObjectIdByArgumentIdAndModeIdAndOrderBy($argumentid, $modeid, $orderby) {
        switch ($orderby) {
            case PositionReferral::POSITIONREFERRAL_ORDER_NAME_ASC: $orderby = ' ORDER BY do.name ASC ';
                break;
            case PositionReferral::POSITIONREFERRAL_ORDER_NAME_DESC: $orderby = ' ORDER BY do.name DESC ';
                break;
            case PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_ASC: $orderby = ' ORDER BY do.createdate ASC ';
                break;
            case PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_DESC: $orderby = ' ORDER BY do.createdate DESC ';
                break;
            case PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_ASC: $orderby = ' ORDER BY do.changedate ASC ';
                break;
            case PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_DESC: $orderby = ' ORDER BY do.changedate DESC ';
                break;
            case PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_ASC: $orderby = ' ORDER BY positions.number ASC ';
                break;
            case PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_DESC: $orderby = ' ORDER BY positions.number DESC ';
                break;
        }
        return self::selectQuery("SELECT positionobjects.fk_object_id objectid FROM objectversions INNER JOIN positions ON objectversions.id = positions.fk_objectversion_id INNER JOIN positionobjects ON positions.id = positionobjects.fk_position_id INNER JOIN objects do ON positionobjects.fk_object_id = do.id WHERE objectversions.fk_argument_id=" . $argumentid . " AND objectversions.fk_mode_id=" . $modeid . " " . $orderby);
    }

    /**
     * get the basic attributes for an object version
     * 
     * @param int $id the object id
     * @param modeid the current mode
     * @return resultset id, layoutid, styleid, argumentid, argumentdefault, inheritlayout, inheritstyle, createdate, createuser, changedate, changeuser, templateid
     */
    public static function getObjectVersionByMode($id, $modeid) {
        return self::selectQuery("SELECT id, fk_layout_id layoutid, fk_style_id styleid, fk_argument_id argumentid, argumentdefault, inheritlayout, inheritstyle, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid, fk_template_id templateid FROM objectversions WHERE fk_object_id=" . $id . " AND fk_mode_id=" . $modeid);
    }

    /**
     * get the basic attributes for a user group
     * 
     * @param int the id of the row 
     * @return resultset name, createdate, createuserid, changedate, changeuserid
     */
    public static function getUserGroup($id) {
        return self::selectQuery("SELECT name, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM usergroups WHERE id=" . $id);
    }

    /**
     * get the user groups a user is member of
     * 
     * @param userid the id of the user
     * @return resultset id
     */
    public static function getUserUserGroups($userid) {
        return self::selectQuery("SELECT fk_usergroup_id id FROM userusergroup WHERE fk_user_id=" . $userid);
    }

    /**
     * get the style params
     * 
     * @return resultset id
     */
    public static function getStyleParams() {
        return self::selectQuery("SELECT id, name FROM styleparams ORDER BY styleparams.name");
    }

    /**
     * get the roles
     * 
     * @return resultset id
     */
    public static function getRoles() {
        return self::selectQuery("SELECT roles.id, roles.name FROM roles ORDER BY roles.name");
    }

    /**
     * get the users
     * 
     * @return resultset id
     */
    public static function getUsers() {
        return self::selectQuery("SELECT users.id, users.name FROM users ORDER BY users.name");
    }

    /**
     * get the user groups
     * 
     * @return resultset id
     */
    public static function getUserGroups() {
        return self::selectQuery("SELECT usergroups.id, usergroups.name FROM usergroups ORDER BY usergroups.name");
    }

    /**
     * get the settings
     * 
     * @return resultset id
     */
    public static function getSettings() {
        return self::selectQuery("SELECT settings.id, settings.name FROM settings ORDER BY settings.id");
    }

    /**
     * get the file includes
     * 
     * @return resultset id
     */
    public static function getFileIncludes() {
        return self::selectQuery("SELECT fileincludes.id, fileincludes.name FROM fileincludes ORDER BY fileincludes.name");
    }

    /**
     * get the snippets
     * 
     * @return resultset id
     */
    public static function getSnippets() {
        return self::selectQuery("SELECT snippets.id, snippets.name FROM snippets ORDER BY snippets.name");
    }

    /**
     * get the basic attributes for a role
     * 
     * @param int the id of the row 
     * @return resultset name, createdate, createuserid, changedate, changeuserid
     */
    public static function getRole($id) {
        return self::selectQuery("SELECT name, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM roles WHERE id=" . $id);
    }

    /**
     * get the basic attributes for an object user group role
     * 
     * @param int the id of the row 
     * @return resultset objectid, usergroupid, roleid, inherit, createdate, createuserid, changedate, changeuserid
     */
    public static function getObjectUserGroupRole($id) {
        return self::selectQuery("SELECT fk_object_id objectid, fk_usergroup_id usergroupid, fk_role_id roleid, inherit, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM objectusergrouprole WHERE id=" . $id);
    }

    /**
     * get the object user group roles for an object
     * 
     * @param int the id of the object
     * @return resultset id
     */
    public static function getObjectObjectUserGroupRole($objectid) {
        return self::selectQuery("SELECT id FROM objectusergrouprole WHERE fk_object_id=" . $objectid);
    }

    /**
     * get the basic attributes for a set
     * 
     * @param int the id of the row 
     * @return resultset name, isbpaddefined, createdate, createuserid, changedate, changeuserid
     */
    public static function getSet($id) {
        return self::selectQuery("SELECT name, isbpaddefined, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM sets WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a user
     * 
     * @param int the id of the row 
     * @return resultset name, firstname, lastname, password, logincounter, createdate, createuserid, changedate, changeuserid
     */
    public static function getUser($id) {
        return self::selectQuery("SELECT name, firstname, lastname, password, logincounter, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM users WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a permission
     * 
     * @param int the id of the row 
     * @return resultset id, managecontent, managestyle, managestructure, fk_role_id roleid, managetemplate, flusharchive, viewobject, frontendedit, fk_createuser_id createuserid, createdate, fk_changeuser_id changeuserid, changedate, uploadfile, frontendcreatoredit, frontendadd, frontendcreatordeactivate, frontenddeactivate, showadminbar, frontendrespond, managelssversion, managelayout, managesystem, managelanguage, managesetting, manageuser, managerole, manageauthorization 
     */
    public static function getPermission($id) {
        return self::selectQuery("SELECT id, managecontent, managestyle, managestructure, fk_role_id roleid, managetemplate, flusharchive, viewobject, frontendedit, fk_createuser_id createuserid, createdate, fk_changeuser_id changeuserid, changedate, uploadfile, frontendcreatoredit, frontendadd, frontendcreatordeactivate, frontenddeactivate, showadminbar, frontendrespond, managelssversion, managelayout, managesystem, managelanguage, managesetting, manageuser, managerole, manageauthorization FROM permissions WHERE id=" . $id);
    }

    /**
     * get the permissions for a role
     * 
     * @param roleid the id of the role
     * @return resultset id
     */
    public static function getRolePermissions($roleid) {
        return self::selectQuery("SELECT id FROM permissions WHERE fk_role_id=" . $roleid);
    }

    /**
     * get the basic attributes for a context
     * 
     * @param int the id of the row 
     * @return resultset name, incss, backupcontextid, contextgroupid, createdate, createuserid, changedate, changeuserid
     */
    public static function getContext($id) {
        return self::selectQuery("SELECT name, incss, fk_backupcontext_id backupcontextid, fk_contextgroup_id contextgroupid, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM contexts WHERE id=" . $id);
    }

    /**
     * get a context by group and name
     * 
     * @param int the context group id
     * @param string the context name
     * @return resultset id
     */
    public static function getContextByGroupAndName($contextgroupid, $name) {
        return self::selectQuery("SELECT id FROM contexts WHERE fk_contextgroup_id=" . $contextgroupid . " AND name='" . $name . "'");
    }

    /**
     * get a session by the identifier
     * 
     * @param int the session identifier
     * @return resultset id
     */
    public static function getSessionByIdentifier($sessionidentifier) {
        return self::selectQuery("SELECT id FROM sessions WHERE sessionidentifier=" . $sessionidentifier);
    }

    /**
     * get the last commmand
     * 
     * @return resultset id
     */
    public static function getLastCommand() {
        return self::selectQuery("SELECT id FROM commandlog ORDER BY id DESC LIMIT 0,1");
    }

    /**
     * get orphaned objects (not a template and no parent)
     * 
     * @return resultset id
     */
    public static function getOrphanedObjects() {
        return self::selectQuery("SELECT objects.id FROM objects LEFT JOIN positionobjects ON objects.id=positionobjects.fk_object_id WHERE positionobjects.id IS NULL AND objects.istemplate = 0 AND objects.id <> " . SysCon::SITE_ROOT_OBJECT);
    }

    /**
     * get the position instance cache by position instance id
     * 
     * @param int the position instance id
     * @return resultset objectid
     */
    public static function getPositionInstanceCacheObjectsByPositionInstanceId($positioninstanceid) {
        return self::selectQuery("SELECT fk_object_id objectid, groupvalue FROM positioninstancecache WHERE fk_positioninstance_id=" . $positioninstanceid . " ORDER BY positioninstancecache.id");
    }

    /**
     * get the position instance cache by position instance id, restrict to user search
     * 
     * @param int the position instance id
     * @return resultset objectid
     */
    public static function getPositionInstanceCacheObjectsByPositionInstanceIdWithUserSearch($positioninstanceid, $usersearch) {
        return self::selectQuery("SELECT DISTINCT positioninstancecache.fk_object_id objectid, positioninstancecache.groupvalue FROM positioninstancecache INNER JOIN positioncontentitems ON positioninstancecache.fk_object_id = positioncontentitems.fk_rootobject_id WHERE positioninstancecache.fk_positioninstance_id=" . $positioninstanceid . " AND positioncontentitems.contentitembody LIKE '%" . $usersearch . "%' ORDER BY positioninstancecache.id");
    }

    /**
     * delete the position instance cache by position instance id, either the
     * position instance is outdated, or it must be deleted
     * 
     * @param int the position instance id
     * @return boolean true if success
     */
    public static function deletePositionInstanceCacheObjectsByPositionInstanceId($positioninstanceid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM positioninstancecache WHERE fk_positioninstance_id=?")) {
            $stmt->bind_param("i", $positioninstanceid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new item the position instance cache by position instance id and object id
     * 
     * @param int positioninstanceid the position instance id
     * @param int objectid the object id
     * @param string groupvalue
     * @return boolean true if success
     */
    public static function insertPositionInstanceCacheObjectsByPositionInstanceId($positioninstanceid, $objectid, $groupvalue) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO positioninstancecache (fk_positioninstance_id, fk_object_id, groupvalue) VALUES (?, ?, ?)")) {
            $stmt->bind_param("iis", $positioninstanceid, $objectid, $groupvalue);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the object addressable parent cache by object is and mode id
     * 
     * @param int the object id
     * @param int the mode id
     * @return boolean true if success
     */
    public static function deleteObjectAddressableParentCacheByObjectAndMode($objectid, $modeid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM objectaddressableparentcache WHERE fk_object_id=? AND fk_mode_id=?")) {
            $stmt->bind_param("ii", $objectid, $modeid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new item the position instance cache by position instance id and object id
     * 
     * @param int the position instance id
     * @param int the object id
     * @return boolean true if success
     */
    public static function insertObjectAddressableParentCacheByObjectAndModeAndAddressableParent($objectid, $modeid, $addressableparentobjectid, $level) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO objectaddressableparentcache (fk_object_id, fk_mode_id, fk_addressableparentobject_id, parentlevel) VALUES (?, ?, ?, ?)")) {
            $stmt->bind_param("iiii", $objectid, $modeid, $addressableparentobjectid, $level);
            return self::actionQuery($stmt);
        }
    }

    /**
     * get the addressable objects, based upon the addressable parent cache for speed
     * 
     * @return resultset id, name
     */
    public static function getAddressableObjects($modeid) {
        // TODO: this is a possible performance bottleneck, create a simpler solution (maybe a tree view in the browser, that fetches one branche at a time)
        return self::selectQuery("SELECT objects.id, CONCAT(objects.name, ' (', parent.name, ')') name FROM objects INNER JOIN positionobjects po ON objects.id=po.fk_object_id INNER JOIN positions p ON po.fk_position_id=p.id INNER JOIN objectversions ov ON p.fk_objectversion_id=ov.id INNER JOIN objectaddressableparentcache oap ON objects.id=oap.fk_object_id INNER JOIN objects parent ON oap.fk_addressableparentobject_id=parent.id WHERE ov.fk_argument_id > 1 AND oap.parentlevel=1 AND oap.fk_mode_id=" . $modeid . " AND ov.fk_mode_id=" . $modeid . " ORDER BY objects.name, parent.name");
    }

    /**
     * get the addressable parents for an object in a mode, start with the top
     * parent and descend into the object tree
     * 
     * @param int the object
     * @param int the mode
     * @return resultset addressableparentid, parentlevel
     */
    public static function getObjectAddressableParentsByObjectIdAndModeId($objectid, $modeid) {
        return self::selectQuery("SELECT fk_addressableparentobject_id addressableparentid, parentlevel FROM objectaddressableparentcache WHERE fk_object_id=" . $objectid . " AND fk_mode_id=" . $modeid . " ORDER BY parentlevel DESC");
    }

    /**
     * get the basic attributes for a context group
     * 
     * @param int the id of the row 
     * @return resultset name, createdate, createuserid, changedate, changeuserid
     */
    public static function getContextGroup($id) {
        return self::selectQuery("SELECT name, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM contextgroups WHERE id=" . $id);
    }

    /**
     * get the basic attributes for an argument
     * 
     * @param int the id of the row 
     * @return resultset name, createdate, createuserid, changedate, changeuserid
     */
    public static function getArgument($id) {
        return self::selectQuery("SELECT name, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM arguments WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a lss version check
     * 
     * @param int the id of the row 
     * @return resultset checkdate, installedversion
     */
    public static function getLSSVersionCheck($id) {
        return self::selectQuery("SELECT checkdate, installedversion FROM lssversioncheck WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a lss version
     * 
     * @param int the id of the row 
     * @return resultset createdate, versiontext
     */
    public static function getLSSVersion($id) {
        return self::selectQuery("SELECT createdate, versiontext FROM lssversions WHERE id=" . $id);
    }

    /**
     * get all sets for use in a list box
     * 
     * @return resultset id, name
     */
    public static function getSets() {
        return self::selectQuery("SELECT sets.id, sets.name FROM sets ORDER BY sets.name");
    }

    /**
     * get the basic attributes for a layout
     * 
     * @param int the id of the row 
     * @return resultset name, setid, isbpaddefined, createdate, createuserid, changedate, changeuserid
     */
    public static function getLayout($id) {
        return self::selectQuery("SELECT name, fk_set_id setid, isbpaddefined, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM layouts WHERE id=" . $id);
    }

    /**
     * get all layouts for use in a list box
     * 
     * @return resultset id, name
     */
    public static function getLayouts() {
        return self::selectQuery("SELECT layouts.id, layouts.name FROM layouts ORDER BY layouts.name");
    }

    /**
     * get all contexts for use in a list box
     * 
     * @return resultset id, name
     */
    public static function getContexts() {
        return self::selectQuery("SELECT contexts.id, contexts.name FROM contexts ORDER BY contexts.name");
    }

    /**
     * get all context groups for use in a list box
     * 
     * @return resultset id, name
     */
    public static function getContextGroups() {
        return self::selectQuery("SELECT contextgroups.id, contextgroups.name FROM contextgroups ORDER BY contextgroups.name");
    }

    /**
     * get all layouts from a certain set for use in a list box
     * 
     * @param int setid the id of the set
     * @param int layoutid the current layout, always add this one to the list
     * @return resultset id, name
     */
    public static function getLayoutsBySetId($setid, $layoutid) {
        return self::selectQuery("SELECT layouts.id, layouts.name FROM layouts WHERE layouts.fk_set_id=" . $setid . " OR layouts.id=" . $layoutid . " ORDER BY layouts.name");
    }

    /**
     * get all structures for use in a list box
     * 
     * @return resultset id, name
     */
    public static function getStructures() {
        return self::selectQuery("SELECT structures.id, structures.name FROM structures ORDER BY structures.name");
    }

    /**
     * get all structures from a certain set for use in a list box
     * 
     * @param int setid the id of the set
     * @param int structureid the current structure, always add this one to the list
     * @return resultset id, name
     */
    public static function getStructuresBySetId($setid, $structureid) {
        return self::selectQuery("SELECT structures.id, structures.name FROM structures WHERE structures.fk_set_id=" . $setid . " OR structures.id=" . $structureid . " ORDER BY structures.name");
    }

    /**
     * get all styles for use in a list box
     * 
     * @return resultset id, name
     */
    public static function getStyles() {
        return self::selectQuery("SELECT styles.id, styles.name FROM styles ORDER BY styles.name");
    }

    /**
     * get all styles by style type for use in a list box
     * 
     * @param string $styletype
     * @return resultset id, name
     */
    public static function getStylesByStyleType($styletype) {
        return self::selectQuery("SELECT styles.id, styles.name FROM styles WHERE styletype='" . $styletype . "' ORDER BY styles.name");
    }

    /**
     * get all position styles from a certain set for use in a list box
     * 
     * @param string $styletype
     * @param int $setid the id of the set
     * @param int $styleid the current style, always add this one to the list
     * @return resultset id, name
     */
    public static function getStylesBySetId($styletype, $setid, $styleid) {
        return self::selectQuery("SELECT styles.id, styles.name FROM styles WHERE styletype='" . $styletype . "' AND (styles.fk_set_id=" . $setid . " OR styles.id=" . $styleid . ") ORDER BY styles.name");
    }

    /**
     * get all templates for use in a list box
     * 
     * @param string $defaultname name to use for the default template
     * @return resultset id, name
     */
    public static function getTemplates($defaultname) {
        return self::selectQuery("SELECT templates.id, '" . $defaultname . "' as name FROM templates WHERE templates.id = " . Template::DEFAULT_TEMPLATE . " UNION SELECT templates.id, templates.name FROM templates WHERE templates.id <> " . Template::DEFAULT_TEMPLATE . " ORDER BY name");
    }

    /**
     * get all templates from a certain set for use in a list box
     * 
     * @param int setid the id of the set
     * @param int templateid the current template, always add this one to the list
     * @param boolean $showdeleted show the deleted templates (or not)
     * @return resultset id, name
     */
    public static function getTemplatesBySetId($setid, $templateid, $showdeleted) {
        if ($showdeleted) {
            $showdeleted = '';
        } else {
            $showdeleted = ' AND deleted=0 ';
        }
        return self::selectQuery("SELECT templates.id, templates.name FROM templates WHERE (templates.fk_set_id=" . $setid . " OR templates.id=" . $templateid . ") " . $showdeleted . " ORDER BY templates.name");
    }

    /**
     * get the order fields for instances for a certain template
     * 
     * @param int $templateid 
     * @param int $modeid 
     * @return resultset name
     */
    public static function getTemplateOrderFieldsByTemplateId($templateid, $modeid) {
        return self::selectQuery("SELECT pc.name FROM positioncontentitems pc INNER JOIN positions p ON pc.fk_position_id = p.id INNER JOIN objectversions ov ON p.fk_objectversion_id = ov.id INNER JOIN objects o ON ov.fk_object_id = o.id WHERE o.istemplate = 1 AND pc.inputtype = '" . PositionContentItem::INPUTTYPE_COMBOBOX . "' AND o.fk_template_id = " . $templateid . " AND ov.fk_mode_id = " . $modeid);
    }

    /**
     * get all arguments for use in a list box
     * 
     * @return resultset id, name
     */
    public static function getArguments() {
        return self::selectQuery("SELECT arguments.id, arguments.name FROM arguments ORDER BY arguments.name");
    }

    /**
     * get the basic attributes for a file include
     * 
     * @param int the id of the row 
     * @return resultset name, mimetype, createdate, createuserid, changedate, changeuserid
     */
    public static function getFileInclude($id) {
        return self::selectQuery("SELECT name, mimetype, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM fileincludes WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a snippet
     * 
     * @param int the id of the row 
     * @return resultset name, mimetype, contextgroupid, createdate, createuserid, changedate, changeuserid
     */
    public static function getSnippet($id) {
        return self::selectQuery("SELECT name, mimetype, fk_contextgroup_id contextgroupid, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM snippets WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a style
     * 
     * @param int the id of the row 
     * @return resultset name, styletype, classsuffix, setid, isbpaddefined, createdate, createuserid, changedate, changeuserid
     */
    public static function getStyle($id) {
        return self::selectQuery("SELECT name, styletype, classsuffix, fk_set_id setid, isbpaddefined, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM styles WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a style parameter
     * 
     * @param int the id of the row 
     * @return resultset name, createdate, createuserid, changedate, changeuserid
     */
    public static function getStyleParam($id) {
        return self::selectQuery("SELECT name, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM styleparams WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a structure
     * 
     * @param int the id of the row 
     * @return resultset name, setid, isbpaddefined, createdate, createuserid, changedate, changeuserid
     */
    public static function getStructure($id) {
        return self::selectQuery("SELECT name, fk_set_id setid, isbpaddefined, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM structures WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a contexted entity: a layoutversion, a structureversion, a style param version or a styleversion
     * 
     * @param int modeid the mode id
     * @param int contextid the context id
     * @param string type the type of entity, used to build the tablename and the foreign key
     * @param int parentid the id of the row 
     * @return resultset id, body, createdate, createuserid, changedate, changeuserid
     */
    public static function getContextedVersion($modeid, $contextid, $type, $parentid) {
        return self::selectQuery("SELECT id, body, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM " . $type . "versions WHERE fk_" . $type . "_id=" . $parentid . " AND fk_mode_id=" . $modeid . " AND fk_context_id=" . $contextid);
    }

    /**
     * get the basic attributes for a moded entity: a file include version or a snippet version
     * 
     * @param int modeid the mode id
     * @param string type the type of entity, used to build the tablename and the foreign key
     * @param int parentid the id of the row 
     * @return resultset id, body, createdate, createuserid, changedate, changeuserid
     */
    public static function getModedVersion($modeid, $type, $parentid) {
        return self::selectQuery("SELECT id, body, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM " . $type . "versions WHERE fk_" . $type . "_id=" . $parentid . " AND fk_mode_id=" . $modeid);
    }

    /**
     * get the positions of an object in a specific mode
     * 
     * @param objectversionid the id of the objectversion
     * @return resultset id, styleid, number, structureid, inheritstyle, inheritstructure, createdate, createuserid, changedate, changeuserid
     */
    public static function getPositions($objectversionid) {
        return self::selectQuery("SELECT id, fk_style_id styleid, number, fk_structure_id structureid, inheritstyle, inheritstructure, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM positions WHERE fk_objectversion_id=" . $objectversionid);
    }

    /**
     * get the basic attributes for a session
     * 
     * @param int $id the id of the row 
     * @return resultset id, sessionidentifier, objectid, createdate
     */
    public static function getSession($id) {
        return self::selectQuery("SELECT id, sessionidentifier, fk_object_id objectid, createdate FROM sessions WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a setting
     * 
     * @param int $id the id of the row 
     * @return resultset name, value, createdate, createuserid, changedate, changeuserid
     */
    public static function getSetting($id) {
        return self::selectQuery("SELECT name, value, createdate, fk_createuser_id createuserid, changedate, fk_changeuser_id changeuserid FROM settings WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a command log
     * 
     * @param int $id the id of the row 
     * @return resultset item, itemaddress, command, commandnumber, value, oldvalue, userid, date
     */
    public static function getCommand($id) {
        return self::selectQuery("SELECT item, itemaddress, command, commandnumber, lastcommandid, sessionidentifier, value, oldvalue, fk_user_id userid, date FROM commandlog WHERE id=" . $id);
    }

    /**
     * get the basic attributes for a event log
     * 
     * @param int $id the id of the row 
     * @return resultset item, itemaddres, event, eventnumber, userid, date
     */
    public static function getEventLog($id) {
        return self::selectQuery("SELECT item, itemaddress, event, eventnumber, userid, date FROM eventlog WHERE id=" . $id);
    }

    /**
     * get the name of a value list entity
     * 
     * @param int the id of the row 
     * @return resultset name
     */
    public static function getValueListEntityName($tablename, $id) {
        return self::selectQuery("SELECT name FROM " . $tablename . " WHERE id=" . $id);
    }

    /**
     * get a user id for the user name
     * 
     * @param string $name, the name to look for
     * @return resultset id
     */
    public static function getUserIdByName($name) {
        return self::selectQuery("SELECT id FROM users WHERE name='" . $name . "'");
    }

    /**
     * get a structure id for the structure name
     * 
     * @param string $name, the name to look for
     * @return resultset id
     */
    public static function getStructureIdByName($name) {
        return self::selectQuery("SELECT id FROM structures WHERE name='" . $name . "'");
    }

    /**
     * get a file include id for the file include name
     * 
     * @param string $name, the name to look for
     * @return resultset id
     */
    public static function getFileIncludeIdByName($name) {
        return self::selectQuery("SELECT id FROM fileincludes WHERE name='" . $name . "'");
    }

    /**
     * get a snippet by context group
     * 
     * @param int $contextgroupid, the context group id to look for
     * @return resultset id
     */
    public static function getSnippetIdByContextGroupId($contextgroupid) {
        return self::selectQuery("SELECT id FROM snippets WHERE fk_contextgroup_id=" . $contextgroupid);
    }

    /**
     * prepare a statement to update the position structure id
     * 
     * @param int the id of the row to update
     * @param newstructureid the new structure id
     * @return boolean  if action query succeeds
     */
    public static function setPositionStructureId($id, $newstructureid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positions SET fk_structure_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newstructureid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position style id
     * 
     * @param int the id of the row to update
     * @param newstyleid the new style id
     * @return boolean  if action query succeeds
     */
    public static function setPositionStyleId($id, $newstyleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positions SET fk_style_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newstyleid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position number
     * 
     * @param int the id of the row to update
     * @param newnumber the new number
     * @return boolean  if action query succeeds
     */
    public static function setPositionNumber($id, $newnumber) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positions SET number=? WHERE id=?")) {
            $stmt->bind_param("ii", $newnumber, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position inherit style value
     * 
     * @param int the id of the row to update
     * @param bool the new inherit style value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInheritStyle($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positions SET inheritstyle=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position inherit structure value
     * 
     * @param int the id of the row to update
     * @param bool the new inherit structure value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInheritStructure($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positions SET inheritstructure=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the object in the objectposition
     * 
     * @param int the id of the row to update
     * @param objectid the id of the object
     * @return boolean  if action query succeeds
     */
    public static function setPositionObjectObject($id, $objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positionobjects SET fk_object_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $objectid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position content item name
     * 
     * @param int the id of the row to update
     * @param newname the string with the new name
     * @return boolean  if action query succeeds
     */
    public static function setPositionContentItemName($id, $newname) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioncontentitems SET name=? WHERE id=?")) {
            $stmt->bind_param("si", $newname, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position content item input type
     * 
     * @param int the id of the row to update
     * @param newinputtype the string with the new input type
     * @return boolean  if action query succeeds
     */
    public static function setPositionContentItemInputType($id, $newinputtype) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioncontentitems SET inputtype=? WHERE id=?")) {
            $stmt->bind_param("si", $newinputtype, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position content item body
     * 
     * @param int the id of the row to update
     * @param newbody the string with the new body
     * @return boolean  if action query succeeds
     */
    public static function setPositionContentItemBody($id, $newbody) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioncontentitems SET contentitembody=? WHERE id=?")) {
            $stmt->bind_param("si", $newbody, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position content item root object id
     * 
     * @param int the id of the row to update
     * @param newrootobjectid new object id
     * @return boolean  if action query succeeds
     */
    public static function setPositionContentItemRootObjectId($id, $newrootobjectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioncontentitems SET fk_rootobject_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newrootobjectid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position content item template id
     * 
     * @param int the id of the row to update
     * @param newtemplateid new template
     * @return boolean  if action query succeeds
     */
    public static function setPositionContentItemTemplateId($id, $newtemplateid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioncontentitems SET fk_template_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newtemplateid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position content item hasinternallinks value
     * 
     * @param int the id of the row to update
     * @param bool the new hasinternallinks value
     * @return boolean  if action query succeeds
     */
    public static function setPositionContentItemHasInternalLinks($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioncontentitems SET hasinternallinks=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position referral order type id
     * 
     * @param int the id of the row to update
     * @param newordertypeid new order type id
     * @return boolean  if action query succeeds
     */
    public static function setPositionReferralOrderBy($id, $neworderby) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positionreferrals SET orderby=? WHERE id=?")) {
            $stmt->bind_param("si", $neworderby, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position referral number of items
     * 
     * @param int the id of the row to update
     * @param newnumberofitems new number of items
     * @return boolean  if action query succeeds
     */
    public static function setPositionReferralNumberOfItems($id, $newnumberofitems) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positionreferrals SET numberofitems=? WHERE id=?")) {
            $stmt->bind_param("ii", $newnumberofitems, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position referral argument id
     * 
     * @param int the id of the row to update
     * @param newargumentid new argument id
     * @return boolean  if action query succeeds
     */
    public static function setPositionReferralArgumentId($id, $newargumentid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positionreferrals SET fk_argument_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newargumentid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position instance object id
     * 
     * @param int the id of the row to update
     * @param newobjectid new object id
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceObjectId($id, $newobjectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET object_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newobjectid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position instance template id
     * 
     * @param int the id of the row to update
     * @param newtemplateid new template id
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceTemplateId($id, $newtemplateid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET template_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newtemplateid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position instance listwords 
     * 
     * @param  the  of the row to update
     * @param newlistwords new listwords 
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceListWords($id, $newlistwords) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET listwords=? WHERE id=?")) {
            $stmt->bind_param("si", $newlistwords, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position instance searchwords 
     * 
     * @param  the  of the row to update
     * @param newsearchwords new searchwords 
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceSearchWords($id, $newsearchwords) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET searchwords=? WHERE id=?")) {
            $stmt->bind_param("si", $newsearchwords, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set position instance parent id
     * 
     * @param int the id of the row to update
     * @param newparentid new parent id
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceParentId($id, $newparentid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET parent_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newparentid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position instance activeitems value
     * 
     * @param int the id of the row to update
     * @param bool the new activeitems value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceActiveItems($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET activeitems=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position instance fillonload value
     * 
     * @param int the id of the row to update
     * @param bool the new activeitems value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceFillOnLoad($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET fillonload=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position instance useinstancecontext value
     * 
     * @param int the id of the row to update
     * @param bool the new activeitems value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceUseInstanceContext($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET useinstancecontext=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position instance outdated value
     * 
     * @param int the id of the row to update
     * @param bool the new outdated value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceOutdated($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET outdated=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position instance orderby value
     * 
     * @param int the id of the row to update
     * @param string orderby the new orderby value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceOrderBy($id, $neworderby) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET orderby=? WHERE id=?")) {
            $stmt->bind_param("si", $neworderby, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the position instance groupby value
     * 
     * @param int the id of the row to update
     * @param bool the new groupby value
     * @return boolean  if action query succeeds
     */
    public static function setPositionInstanceGroupBy($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET groupby=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set template name
     * 
     * @param int the id of the row to update
     * @param newname the string with the new name
     * @return boolean  if action query succeeds
     */
    public static function setTemplateName($id, $newname) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE templates SET name=? WHERE id=?")) {
            $stmt->bind_param("si", $newname, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set template deleted
     * 
     * @param int the id of the row to update
     * @param bool true if deleted
     * @return boolean  if action query succeeds
     */
    public static function setTemplateDeleted($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE templates SET deleted=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set template structure id
     * 
     * @param int the id of the row to update
     * @param int newstructureid the new id
     * @return boolean  if action query succeeds
     */
    public static function setTemplateStructureId($id, $newstructureid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE templates SET fk_structure_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newstructureid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set template style id
     * 
     * @param int the id of the row to update
     * @param int newstyleid the new id
     * @return boolean  if action query succeeds
     */
    public static function setTemplateStyleId($id, $newstyleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE templates SET fk_style_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newstyleid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set template set id
     * 
     * @param int the id of the row to update
     * @param int newsetid the new id
     * @return boolean  if action query succeeds
     */
    public static function setTemplateSetId($id, $newsetid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE templates SET fk_set_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newsetid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set template instanceallowed
     * 
     * @param int the id of the row to update
     * @param bool true if instanceallowed
     * @return boolean  if action query succeeds
     */
    public static function setTemplateInstanceAllowed($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE templates SET instanceallowed=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set template searchable
     * 
     * @param int the id of the row to update
     * @param bool true if searchable
     * @return boolean  if action query succeeds
     */
    public static function setTemplateSearchable($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE templates SET searchable=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set entity name
     * 
     * @param tablename the name of the table
     * @param int the id of the row to update
     * @param newname the string with the new name
     * @return boolean  if action query succeeds
     */
    public static function setEntityName($tablename, $id, $newname) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET name=? WHERE id=?")) {
            $stmt->bind_param("si", $newname, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the entity set id
     * 
     * @param tablename the name of the table
     * @param int the id of the row to update
     * @param newsetid the new set id
     * @return boolean  if action query succeeds
     */
    public static function setEntitySetId($tablename, $id, $newsetid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET fk_set_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newsetid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set contexted entity body, a contexted item for example is a version of a layout, style or structure
     * 
     * @param int id the id of the row to update
     * @param string newbody the string with the new body
     * @param string tablename the name of the table
     * @return boolean  if action query succeeds
     */
    public static function setContextedVersionBody($id, $newbody, $tablename) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET body=? WHERE id=?")) {
            $stmt->bind_param("si", $newbody, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set contexted entity mode, a contexted item for example is a version of a layout, style or structure
     * 
     * @param int $id the id of the row to update
     * @param int $newmodeid the new mode id
     * @param string $tablename the name of the table
     * @return boolean true if action query succeeds
     */
    public static function setContextedVersionMode($id, $newmodeid, $tablename) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET fk_mode_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newmodeid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set moded entity mode
     * 
     * @param int $id the id of the row to update
     * @param int $newmodeid the new mode id
     * @param string $tablename the name of the table
     * @return boolean true if action query succeeds
     */
    public static function setModedVersionMode($id, $newmodeid, $tablename) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET fk_mode_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newmodeid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set moded entity body, a moded item for example is a version of a file include or a snippet
     * 
     * @param int id the id of the row to update
     * @param string newbody the string with the new body
     * @param string tablename the name of the table
     * @return boolean  if action query succeeds
     */
    public static function setModedVersionBody($id, $newbody, $tablename) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET body=? WHERE id=?")) {
            $stmt->bind_param("si", $newbody, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the style styletype
     * 
     * @param int the id of the row to update
     * @param newstyletype the new styletype
     * @return boolean  if action query succeeds
     */
    public static function setStyleStyleType($id, $newstyletype) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE styles SET styletype=? WHERE id=?")) {
            $stmt->bind_param("si", $newstyletype, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the style classsuffix
     * 
     * @param int the id of the row to update
     * @param string newclasssuffix the new classsuffix
     * @return boolean  if action query succeeds
     */
    public static function setStyleClassSuffix($id, $newclasssuffix) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE styles SET classsuffix=? WHERE id=?")) {
            $stmt->bind_param("si", $newclasssuffix, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the context incss value
     * 
     * @param int the id of the row to update
     * @param bool true if searchable
     * @return boolean  if action query succeeds
     */
    public static function setContextInCSS($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE contexts SET incss=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the context backupcontextid value
     * 
     * @param int the id of the row to update
     * @param bool true if searchable
     * @return boolean  if action query succeeds
     */
    public static function setContextBackupContextId($id, $newbackupcontextid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE contexts SET fk_backupcontext_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newbackupcontextid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * prepare a statement to update the context context group id value
     * 
     * @param int the id of the row to update
     * @param bool true if searchable
     * @return boolean  if action query succeeds
     */
    public static function setContextContextGroupId($id, $newcontextgroupid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE contexts SET fk_contextgroup_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newcontextgroupid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set user first name
     * 
     * @param int the id of the row to update
     * @param newfirstname the string with the new first name
     * @return boolean  if action query succeeds
     */
    public static function setUserFirstName($id, $newfirstname) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE users SET firstname=? WHERE id=?")) {
            $stmt->bind_param("si", $newfirstname, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set user last name
     * 
     * @param int the id of the row to update
     * @param newlastname the string with the new last name
     * @return boolean  if action query succeeds
     */
    public static function setUserLastName($id, $newlastname) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE users SET lastname=? WHERE id=?")) {
            $stmt->bind_param("si", $newlastname, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set user password
     * 
     * @param int the id of the row to update
     * @param newpassword the string with the new password
     * @return boolean  if action query succeeds
     */
    public static function setUserPassword($id, $newpassword) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE users SET password=? WHERE id=?")) {
            $stmt->bind_param("si", $newpassword, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set user logincounter
     * 
     * @param int the id of the row to update
     * @param newlogincounter the string with the new logincounter
     * @return boolean  if action query succeeds
     */
    public static function setUserLoginCounter($id, $newlogincounter) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE users SET logincounter=? WHERE id=?")) {
            $stmt->bind_param("ii", $newlogincounter, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set the object id for an object user group role
     * 
     * @param int the id of the row to update
     * @param int newobjectid the new object id
     * @return boolean  if action query succeeds
     */
    public static function setObjectUserGroupRoleObjectId($id, $newobjectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectusergrouprole SET fk_object_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newobjectid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set the usergroup id for an object user group role
     * 
     * @param int the id of the row to update
     * @param int newusergroupid the new usergroup id
     * @return boolean  if action query succeeds
     */
    public static function setObjectUserGroupRoleUserGroupId($id, $newusergroupid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectusergrouprole SET fk_usergroup_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newusergroupid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set the role id for an object user group role
     * 
     * @param int the id of the row to update
     * @param int newroleid the new role id
     * @return boolean  if action query succeeds
     */
    public static function setObjectUserGroupRoleRoleId($id, $newroleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectusergrouprole SET fk_role_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newroleid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set the inherit value for an object user group role
     * 
     * @param int the id of the row to update
     * @param boolean inherit the new inherit value
     * @return boolean  if action query succeeds
     */
    public static function setObjectUserGroupRoleInherit($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectusergrouprole SET inherit=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set the role id for a permission
     * 
     * @param int the id of the row to update
     * @param int newroleid the new role id
     * @return boolean  if action query succeeds
     */
    public static function setPermissionRoleId($id, $newroleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET fk_role_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newroleid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage template permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage template
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageTemplate($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managetemplate=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set view object permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for view object
     * @return boolean  if action query succeeds
     */
    public static function setPermissionViewObject($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET viewobject=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set upload file permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for upload file 
     * @return boolean  if action query succeeds
     */
    public static function setPermissionUploadFile($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET uploadfile=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * frontend respond permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for frontend respond
     * @return boolean  if action query succeeds
     */
    public static function setPermissionFrontendRespond($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET frontendrespond=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set frontend edit permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for frontend edit
     * @return boolean  if action query succeeds
     */
    public static function setPermissionFrontendEdit($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET frontendedit=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set frontend deactivate permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for frontend deactivate
     * @return boolean  if action query succeeds
     */
    public static function setPermissionFrontendDeactivate($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET frontenddeactivate=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set frontend add permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for frontend add
     * @return boolean  if action query succeeds
     */
    public static function setPermissionFrontendAdd($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET frontendadd=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set frontend creator deactivate permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for frontend creator deactivate
     * @return boolean  if action query succeeds
     */
    public static function setPermissionFrontendCreatorDeactivate($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET frontendcreatordeactivate=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set frontend creator edit permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for frontend creator edit
     * @return boolean  if action query succeeds
     */
    public static function setPermissionFrontendCreatorEdit($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET frontendcreatoredit=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage content permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage content
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageContent($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managecontent=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage layout permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage layout
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageLayout($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managelayout=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage lss version permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage lss version
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageLSSVersion($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managelssversion=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage style permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage style
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageStyle($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managestyle=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage structure permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage structure
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageStructure($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managestructure=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage system permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage system
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageSystem($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managesystem=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage language permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage language
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageLanguage($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managelanguage=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage setting permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage setting
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageSetting($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managesetting=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage user permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage user
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageUser($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET manageuser=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage role permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage role
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageRole($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET managerole=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set manage authorization permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for manage authorization
     * @return boolean  if action query succeeds
     */
    public static function setPermissionManageAuthorization($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET manageauthorization=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set show admin bar permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for show admin bar
     * @return boolean  if action query succeeds
     */
    public static function setPermissionShowAdminBar($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET showadminbar=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set lss version permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for lss version
     * @return boolean  if action query succeeds
     */
    public static function setPermissionLSSVersion($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET lssversion=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set flush archive permission
     * 
     * @param int the id of the row to update
     * @param boolean the new value for flush archive
     * @return boolean  if action query succeeds
     */
    public static function setPermissionFlushArchive($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE permissions SET flusharchive=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set lss version check check date
     * 
     * @param int the id of the row to update
     * @param datestring the new value for check date
     * @return boolean  if action query succeeds
     */
    public static function setLSSVersionCheckCheckDate($id, $newdate) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE lssversioncheck SET checkdate=? WHERE id=?")) {
            $stmt->bind_param("si", $newdate, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set lss version check installed version
     * 
     * @param int the id of the row to update
     * @param int the new value for the installed version
     * @return boolean  if action query succeeds
     */
    public static function setLSSVersionCheckInstalledVersion($id, $newinstalledversion) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE lssversioncheck SET installedversion=? WHERE id=?")) {
            $stmt->bind_param("ii", $newinstalledversion, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set lss version create date
     * 
     * @param int the id of the row to update
     * @param datestring the new value for create date
     * @return boolean  if action query succeeds
     */
    public static function setLSSVersionCreateDate($id, $newdate) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE lssversions SET createdate=? WHERE id=?")) {
            $stmt->bind_param("si", $newdate, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set lss version version text
     * 
     * @param int the id of the row to update
     * @param string the new value for the version text
     * @return boolean  if action query succeeds
     */
    public static function setLSSVersionVersionText($id, $newversiontext) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE lssversions SET versiontext=? WHERE id=?")) {
            $stmt->bind_param("si", $newversiontext, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object cache object id
     * 
     * @param int the id of the row to update
     * @param int the new value for the object id
     * @return boolean  if action query succeeds
     */
    public static function setObjectCacheObjectId($id, $newobjectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache SET fk_object_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newobjectid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object cache context id
     * 
     * @param int the id of the row to update
     * @param int the new value for the context id
     * @return boolean  if action query succeeds
     */
    public static function setObjectCacheContextId($id, $newcontextid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache SET fk_context_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newcontextid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object cache cache date
     * 
     * @param int the id of the row to update
     * @param datetimestring the new value for the cache date
     * @return boolean  if action query succeeds
     */
    public static function setObjectCacheCacheDate($id, $newcachedate) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache SET cachedate=? WHERE id=?")) {
            $stmt->bind_param("si", $newcachedate, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object cache cache
     * 
     * @param int the id of the row to update
     * @param string the new value for the cache
     * @return boolean  if action query succeeds
     */
    public static function setObjectCacheCache($id, $newcache) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache SET cache=? WHERE id=?")) {
            $stmt->bind_param("si", $newcache, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object cache outdated
     * 
     * @param int the id of the row to update
     * @param int the new value for the context id
     * @return boolean  if action query succeeds
     */
    public static function setObjectCacheOutdated($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache SET outdated=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set object cache user id
     * 
     * @param int the id of the row to update
     * @param int the new value for the user id
     * @return boolean  if action query succeeds
     */
    public static function setObjectCacheUserId($id, $newuserid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache SET fk_user_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newuserid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set session session identifier
     * 
     * @param int the id of the row to update
     * @param string the new value for the session identifier
     * @return boolean  if action query succeeds
     */
    public static function setSessionSessionIdentifier($id, $newsessionidentifier) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE sessions SET sessionidentifier=? WHERE id=?")) {
            $stmt->bind_param("si", $newsessionidentifier, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set session object id
     * 
     * @param int the id of the row to update
     * @param int the new value for the object id 
     * @return boolean  if action query succeeds
     */
    public static function setSessionObjectId($id, $newobjectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE sessions SET fk_object_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newobjectid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set session create date
     * 
     * @param int the id of the row to update
     * @param string the new value for the create date
     * @return boolean  if action query succeeds
     */
    public static function setSessionCreateDate($id, $newcreatedate) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE sessions SET createdate=? WHERE id=?")) {
            $stmt->bind_param("si", $newcreatedate, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set stylesheet cache cache date
     * 
     * @param int the id of the row to update
     * @param datetimestring the new value for the cache date
     * @return boolean  if action query succeeds
     */
    public static function setStylesheetCacheCacheDate($id, $newcachedate) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE stylesheetcache SET cachedate=? WHERE id=?")) {
            $stmt->bind_param("si", $newcachedate, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set stylesheet cache cache
     * 
     * @param int the id of the row to update
     * @param string the new value for the cache
     * @return boolean  if action query succeeds
     */
    public static function setStylesheetCacheCache($id, $newcache) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE stylesheetcache SET cache=? WHERE id=?")) {
            $stmt->bind_param("si", $newcache, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set stylesheet cache outdate
     * 
     * @param int the id of the row to update
     * @param bool the new value for outdated
     * @return boolean  if action query succeeds
     */
    public static function setStylesheetCacheOutdated($id, $bool) {
        $intbool = (int) $bool;
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE stylesheetcache SET outdated=? WHERE id=?")) {
            $stmt->bind_param("ii", $intbool, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set version version
     * 
     * @param int the id of the row to update
     * @param string the new value for version
     * @return boolean  if action query succeeds
     */
    public static function setVersionVersion($id, $newversion) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE version SET version=? WHERE id=?")) {
            $stmt->bind_param("si", $newversion, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set version release date
     * 
     * @param int the id of the row to update
     * @param datetimestring the new value for release date
     * @return boolean  if action query succeeds
     */
    public static function setVersionReleaseDate($id, $newreleasedate) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE version SET releasedate=? WHERE id=?")) {
            $stmt->bind_param("si", $newreleasedate, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set version release info
     * 
     * @param int the id of the row to update
     * @param string the new value for release info
     * @return boolean  if action query succeeds
     */
    public static function setVersionReleaseInfo($id, $newreleaseinfo) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE version SET releaseinfo=? WHERE id=?")) {
            $stmt->bind_param("si", $newreleaseinfo, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set setting value
     * 
     * @param int the id of the row to update
     * @param string the new value for setting
     * @return boolean  if action query succeeds
     */
    public static function setSettingValue($id, $newvalue) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE settings SET value=? WHERE id=?")) {
            $stmt->bind_param("si", $newvalue, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set log item
     * 
     * @param int the id of the row to update
     * @param string the new value for item
     * @return boolean  if action query succeeds
     */
    public static function setLogItem($tablename, $id, $newitem) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET item=? WHERE id=?")) {
            $stmt->bind_param("si", $newitem, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set log item address
     * 
     * @param int the id of the row to update
     * @param string the new value for item id
     * @return boolean  if action query succeeds
     */
    public static function setLogItemAddress($tablename, $id, $newitemaddress) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET itemaddress=? WHERE id=?")) {
            $stmt->bind_param("si", $newitemaddress, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set log user id
     * 
     * @param int the id of the row to update
     * @param int the new value for user id
     * @return boolean  if action query succeeds
     */
    public static function setLogUserId($tablename, $id, $newuserid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET fk_user_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newuserid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set log date
     * 
     * @param int the id of the row to update
     * @param string the new value for date
     * @return boolean  if action query succeeds
     */
    public static function setLogDate($tablename, $id, $newdate) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE " . $tablename . " SET date=? WHERE id=?")) {
            $stmt->bind_param("si", $newdate, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set command log command
     * 
     * @param int the id of the row to update
     * @param string the new value for command
     * @return boolean  if action query succeeds
     */
    public static function setCommandLogCommand($id, $newcommand) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE commandlog SET command=? WHERE id=?")) {
            $stmt->bind_param("si", $newcommand, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set command log session identifier
     * 
     * @param int the id of the row to update
     * @param string the new value for session identifier
     * @return boolean  if action query succeeds
     */
    public static function setCommandLogSessionIdentifier($id, $newsessionidentifier) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE commandlog SET sessionidentifier=? WHERE id=?")) {
            $stmt->bind_param("ii", $newsessionidentifier, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set command log last command id
     * 
     * @param int the id of the row to update
     * @param string the new value for last command id
     * @return boolean  if action query succeeds
     */
    public static function setCommandLogLastCommandId($id, $newlastcommandid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE commandlog SET lastcommandid=? WHERE id=?")) {
            $stmt->bind_param("ii", $newlastcommandid, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set command log command number
     * 
     * @param int the id of the row to update
     * @param string the new value for command number
     * @return boolean  if action query succeeds
     */
    public static function setCommandLogCommandNumber($id, $newcommandnumber) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE commandlog SET commandnumber=? WHERE id=?")) {
            $stmt->bind_param("ii", $newcommandnumber, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set command log value
     * 
     * @param int the id of the row to update
     * @param string the new value for value
     * @return boolean  if action query succeeds
     */
    public static function setCommandLogValue($id, $newvalue) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE commandlog SET value=? WHERE id=?")) {
            $stmt->bind_param("si", $newvalue, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set command log old value
     * 
     * @param int the id of the row to update
     * @param string the new value for old value
     * @return boolean  if action query succeeds
     */
    public static function setCommandLogOldValue($id, $newoldvalue) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE commandlog SET oldvalue=? WHERE id=?")) {
            $stmt->bind_param("si", $newoldvalue, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set event log event
     * 
     * @param int the id of the row to update
     * @param string the new value for event
     * @return boolean  if action query succeeds
     */
    public static function setEventLogEvent($id, $newevent) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE eventlog SET event=? WHERE id=?")) {
            $stmt->bind_param("si", $newevent, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set event log event number
     * 
     * @param int the id of the row to update
     * @param string the new value for event number
     * @return boolean  if action query succeeds
     */
    public static function setEventLogEventNumber($id, $neweventnumber) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE eventlog SET eventnumber=? WHERE id=?")) {
            $stmt->bind_param("ii", $neweventnumber, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set file include type
     * 
     * @param int the id of the row to update
     * @param string the new value for type 
     * @return boolean  if action query succeeds
     */
    public static function setFileIncludeType($id, $newtype) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE fileincludes SET mimetype=? WHERE id=?")) {
            $stmt->bind_param("si", $newtype, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set snippet context group
     * 
     * @param int the id of the row to update
     * @param int the new value for the context group id
     * @return boolean  if action query succeeds
     */
    public static function setSnippetContextGroupId($id, $newcontextgroup) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE snippets SET fk_contextgroup_id=? WHERE id=?")) {
            $stmt->bind_param("ii", $newcontextgroup, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * set snippet mime type
     * 
     * @param int the id of the row to update
     * @param int the new value for the mime type
     * @return boolean  if action query succeeds
     */
    public static function setSnippetMimeType($id, $newmimetype) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE snippets SET mimetype=? WHERE id=?")) {
            $stmt->bind_param("si", $newmimetype, $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * outdate all cached versions of an object that has been changed
     * 
     * @param int the id of the cached object
     * @return boolean  if action query succeeds
     */
    public static function outdateCachedObject($id) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache SET outdated=1 WHERE fk_object_id=?")) {
            $stmt->bind_param("i", $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * outdate all cached versions of an object that use a certain layout
     * 
     * @param int the id of the layout
     * @return boolean  if action query succeeds
     */
    public static function outdateCachedObjectsByLayout($id) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache INNER JOIN objectversions ON objectcache.fk_object_id=objectversions.fk_object_id SET objectcache.outdated=1 WHERE objectversions.fk_layout_id=?")) {
            $stmt->bind_param("i", $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * outdate all cached versions of an object that use a certain structure
     * 
     * @param int the id of the structure
     * @return boolean  if action query succeeds
     */
    public static function outdateCachedObjectsByStructure($id) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache INNER JOIN objectversions ON objectcache.fk_object_id=objectversions.fk_object_id INNER JOIN positions ON objectversions.id=positions.fk_objectversion_id SET objectcache.outdated=1 WHERE positions.fk_structure_id=?")) {
            $stmt->bind_param("i", $id);
            return self::actionQuery($stmt);
        }
    }

    /**
     * outdate all instances
     * 
     * @return boolean  if action query succeeds
     */
    public static function outdateInstances() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioninstances SET positioninstances.outdated=1")) {
            return self::actionQuery($stmt);
        }
    }

    /**
     * outdate all referrals with a certain argument
     * 
     * @param int $argumentid
     * @return boolean  if action query succeeds
     */
    public static function outdateReferrals($argumentid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache INNER JOIN objectversions ON objectcache.fk_object_id=objectversions.fk_object_id INNER JOIN positions ON objectversions.id=positions.fk_objectversion_id INNER JOIN positionreferrals ON positions.id=positionreferrals.fk_position_id SET objectcache.outdated=1 WHERE positionreferrals.fk_argument_id=" . $argumentid)) {
            return self::actionQuery($stmt);
        }
    }

    /**
     * outdate all contentitems with internal links
     * 
     * @return boolean  if action query succeeds
     */
    public static function outdateLinkedContentItems() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE objectcache INNER JOIN objectversions ON objectcache.fk_object_id=objectversions.fk_object_id INNER JOIN positions ON objectversions.id=positions.fk_objectversion_id INNER JOIN positioncontentitems ON positions.id=positioncontentitems.fk_position_id SET objectcache.outdated=1 WHERE positioncontentitems.hasinternallinks=1")) {
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new command into the Store
     * 
     * @return int the new id
     */
    public static function insertCommand() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO commandlog (fk_user_id) VALUES (?)")) {
            $stmt->bind_param("i", Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new session into the Store
     * 
     * @param int sessionidentifier
     * @param int objectid
     * @return int the new id
     */
    public static function insertSession($sessionidentifier, $objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO sessions (sessionidentifier, fk_object_id, createdate) VALUES (?, ?, NOW())")) {
            $stmt->bind_param("ii", $sessionidentifier, $objectid);
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new object cache into the Store
     * 
     * @return int the new id
     */
    public static function insertObjectCache() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO objectcache () VALUES ()")) {
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new object into the Store
     * 
     * @return int the new id
     */
    public static function insertObject() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO objects (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new argument into the Store
     * 
     * @return int the new id
     */
    public static function insertArgument() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO arguments (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new object user group role into the Store
     * in this case, directly insert the correct values, otherwise rogue permissions
     * may be in the system for a short while. 
     * 
     * @param int $objectid
     * @param int $usergroupid
     * @param int $roleid
     * @param boolean $inherit
     * @return int the new id
     */
    public static function insertObjectUserGroupRole($objectid, $usergroupid, $roleid, $inherit) {
        $inheritint = (int) $inherit;
        $stmt = self::$connection->stmt_init();
        // prevent doubles
        if ($result = self::selectQuery('SELECT id FROM objectusergrouprole WHERE fk_object_id=' . $objectid . ' AND fk_usergroup_id=' . $usergroupid . ' AND fk_role_id=' . $roleid)) {
            return;
        }
        // no double, so insert
        if ($stmt->prepare("INSERT INTO objectusergrouprole (fk_object_id, fk_usergroup_id, fk_role_id, inherit, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, ?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiiiii", $objectid, $usergroupid, $roleid, $inheritint, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete an object user group role from the Store
     * 
     * @param int $ougrid the object user group role id
     */
    public static function deleteObjectUserGroupRole($ougrid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM objectusergrouprole WHERE id=?")) {
            $stmt->bind_param("i", $ougrid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new user user group into the Store
     * 
     * @param int $userid
     * @param int $usergroupid
     */
    public static function insertUserUserGroup($userid, $usergroupid) {
        $stmt = self::$connection->stmt_init();
        // prevent doubles
        if ($result = self::selectQuery('SELECT id FROM userusergroup WHERE fk_user_id=' . $userid . ' AND fk_usergroup_id=' . $usergroupid)) {
            return;
        }
        // no double, so insert
        if ($stmt->prepare("INSERT INTO userusergroup (fk_user_id, fk_usergroup_id, createdate, fk_createuser_id) VALUES (?, ?, NOW(), ?)")) {
            $stmt->bind_param("iii", $userid, $usergroupid, Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete an user user group from the Store
     * 
     * @param int $userid
     * @param int $usergroupid
     */
    public static function deleteUserUserGroup($userid, $usergroupid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM userusergroup WHERE fk_user_id=? AND fk_usergroup_id=?")) {
            $stmt->bind_param("ii", $userid, $usergroupid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the object user group roles for an object from the Store
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectUserGroupRoles($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM objectusergrouprole WHERE fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the position objects for an object
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectPositionObjects($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE positionobjects FROM positionobjects INNER JOIN positions ON positionobjects.fk_position_id=positions.id INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id WHERE objectversions.fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the position instances for an object
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectPositionInstances($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE positioninstances FROM positioninstances INNER JOIN positions ON positioninstances.fk_position_id=positions.id INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id WHERE objectversions.fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the position referrals for an object
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectPositionReferrals($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE positionreferrals FROM positionreferrals INNER JOIN positions ON positionreferrals.fk_position_id=positions.id INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id WHERE objectversions.fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the position content items for an object
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectPositionContentItems($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE positioncontentitems FROM positioncontentitems INNER JOIN positions ON positioncontentitems.fk_position_id=positions.id INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id WHERE objectversions.fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete an object from position content items as the root, as preparation for deleting the object
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectFromPositionContentItems($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("UPDATE positioncontentitems SET positioncontentitems.fk_rootobject_id=? WHERE positioncontentitems.fk_rootobject_id=?")) {
            $defaultrootobjectid = SysCon::SITE_ROOT_OBJECT;
            $stmt->bind_param("ii", $defaultrootobjectid, $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the positions for an object
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectPositions($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE positions FROM positions INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id WHERE objectversions.fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the object versions for an object
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectVersions($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM objectversions WHERE objectversions.fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the object 
     * 
     * @param int $objectid the object id
     */
    public static function deleteObject($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM objects WHERE objects.id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete the object sessions
     * 
     * @param int $objectid the object id
     */
    public static function deleteObjectSessions($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM sessions WHERE sessions.fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new object version into the Store
     * 
     * @param int $objectid
     * @param int $modeid
     * @return int the new id
     */
    public static function insertObjectVersion($objectid, $modeid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO objectversions (fk_object_id, fk_mode_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiii", $objectid, $modeid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new position into the Store
     * 
     * @param int $objectversionid
     * @param int $number
     * @return int the new id
     */
    public static function insertPosition($objectversionid, $number) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO positions (fk_objectversion_id, number, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiii", $objectversionid, $number, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new position content item into the Store
     * 
     * @param int $positionid
     * @return int the new id
     */
    public static function insertPositionContentItem($positionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO positioncontentitems (fk_position_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iii", $positionid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new position instance into the Store
     * 
     * @param int $positionid
     * @return int the new id
     */
    public static function insertPositionInstance($positionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO positioninstances (fk_position_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iii", $positionid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new position referral into the Store
     * 
     * @param int $positionid
     * @return int the new id
     */
    public static function insertPositionReferral($positionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO positionreferrals (fk_position_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iii", $positionid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new position object into the Store
     * 
     * @param int $positionid
     * @return int the new id
     */
    public static function insertPositionObject($positionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO positionobjects (fk_position_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iii", $positionid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * Build the query to find objects that fullfill the conditions of the instance
     * and return the result set with these objects to the instance
     * 
     * This is the only major piece of logic in the Store class, to do this efficiently
     * you need a store specific method.
     * 
     * @param int $templateid
     * @param int $parentid
     * @param string $listwords
     * @param string $searchwords
     * @param int $activeitems
     * @return resultset
     */
    public static function instanceQuery($templateid, $parentid, $listwords, $searchwords, $activeitems, $orderby, $modeid) {
        // building the query from hell...
        $query = "SELECT matches.objectid ";
        if ($orderby > '') {
            switch ($orderby) {
                // several object based order types 
                case PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_ASC:
                case PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_DESC:
                    $query .= ", DATE_FORMAT(objectversions.changedate, '" . Helper::getDateFormatStore() . "') groupvalue ";
                    break;
                case PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_ASC:
                case PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_DESC:
                    $query .= ", DATE_FORMAT(objectversions.createdate, '" . Helper::getDateFormatStore() . "') groupvalue ";
                    break;
                default:
                    // it's a list field
                    $query .= ", LEFT(positioncontentitems.contentitembody, 255) groupvalue ";
                    break;
            }
        }
        $query .= "FROM ";
        $query .= "(SELECT listmatches.objectid FROM ";
        $query .= "(SELECT DISTINCT positioncontentitems.fk_rootobject_id objectid ";
        $query .= "FROM positioncontentitems ";
        $query .= "INNER JOIN objectaddressableparentcache ON positioncontentitems.fk_rootobject_id = objectaddressableparentcache.fk_object_id ";
        $query .= "INNER JOIN positions ON positions.id=positioncontentitems.fk_position_id ";
        $query .= "INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id ";
        $query .= "INNER JOIN templates ON positioncontentitems.fk_template_id = templates.id ";
        $query .= "WHERE ";
        if ($listwords > '') {
            // clean up spaces
            $listwords = str_replace(', ', ',', $listwords);
            // get the individual words
            $listwords = explode(',', $listwords);
            // now find the objects, make sure items aren't counted twice
            $listwordsselection = '';
            $objectcounted = array();
            foreach ($listwords as $listword) {
                if ($listword > '') {
                    if ($listwordsselection == '') {
                        // nothing
                    } else {
                        $listwordsselection .= ' OR ';
                    }
                    $listwordsselection .= " positioncontentitems.contentitembody='" . self::$connection->real_escape_string($listword) . "' ";
                }
            }
            if ($listwordsselection > '') {
                $query .= "(positioncontentitems.inputtype = 'INPUTTYPE_COMBOBOX' AND (" . $listwordsselection . ")) AND ";
            }
        }
        if ($templateid != Template::DEFAULT_TEMPLATE) {
            $query .= " positioncontentitems.fk_template_id = " . $templateid . " AND ";
        }
        $query .= "objectaddressableparentcache.fk_addressableparentobject_id = " . $parentid . " ";
        $query .= "AND objectaddressableparentcache.fk_mode_id = " . $modeid . " ";
        $query .= "AND objectversions.fk_mode_id = " . $modeid . " ";
        $query .= "AND templates.instanceallowed = 1 ";
        $query .= ") listmatches ";
        $query .= "INNER JOIN ";
        $query .= "(SELECT DISTINCT positioncontentitems.fk_rootobject_id objectid ";
        $query .= "FROM positioncontentitems ";
        $query .= "INNER JOIN objectaddressableparentcache ON positioncontentitems.fk_rootobject_id = objectaddressableparentcache.fk_object_id ";
        $query .= "INNER JOIN positions ON positions.id=positioncontentitems.fk_position_id ";
        $query .= "INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id ";
        $query .= "INNER JOIN templates ON positioncontentitems.fk_template_id = templates.id ";
        $query .= "WHERE ";
        if ($searchwords > '') {
            // clean up spaces
            $searchwords = str_replace(', ', ',', $searchwords);
            // get the individual words
            $searchwords = explode(',', $searchwords);
            // now find the objects, make sure items aren't counted twice
            $searchwordsselection = '';
            $objectcounted = array();
            foreach ($searchwords as $searchword) {
                if ($searchword > '') {
                    if ($searchwordsselection == '') {
                        // nothing
                    } else {
                        $searchwordsselection .= ' OR ';
                    }
                    $searchwordsselection .= " positioncontentitems.contentitembody like '%" . self::$connection->real_escape_string($searchword) . "%' ";
                }
            }
            if ($searchwordsselection > '') {
                $query .= "((positioncontentitems.inputtype = 'INPUTTYPE_INPUTBOX' OR positioncontentitems.inputtype = 'INPUTTYPE_TEXTAREA') AND (" . $searchwordsselection . ")) AND ";
            }
        }
        if ($templateid != Template::DEFAULT_TEMPLATE) {
            $query .= "positioncontentitems.fk_template_id = " . $templateid . " AND ";
        }
        $query .= "objectaddressableparentcache.fk_addressableparentobject_id = " . $parentid . " ";
        $query .= "AND objectaddressableparentcache.fk_mode_id = " . $modeid . " ";
        $query .= "AND objectversions.fk_mode_id = " . $modeid . " ";
        $query .= "AND templates.instanceallowed = 1 ";
        $query .= ") searchmatches ";
        $query .= "ON listmatches.objectid = searchmatches.objectid ";
        $query .= "INNER JOIN objects ";
        $query .= "ON listmatches.objectid = objects.id ";
        $query .= "WHERE objects.active=" . $activeitems . " ";
        $query .= "AND objects.istemplate=0) matches ";
        if ($orderby > '') {
            switch ($orderby) {
                // several object based order types 
                case PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_ASC:
                    $query .= "INNER JOIN objectversions ON matches.objectid=objectversions.fk_object_id WHERE objectversions.fk_mode_id=" . $modeid . " ORDER BY objectversions.changedate ASC ";
                    break;
                case PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_DESC:
                    $query .= "INNER JOIN objectversions ON matches.objectid=objectversions.fk_object_id WHERE objectversions.fk_mode_id=" . $modeid . " ORDER BY objectversions.changedate DESC ";
                    break;
                case PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_ASC:
                    $query .= "INNER JOIN objectversions ON matches.objectid=objectversions.fk_object_id WHERE objectversions.fk_mode_id=" . $modeid . " ORDER BY objectversions.createdate ASC ";
                    break;
                case PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_DESC:
                    $query .= "INNER JOIN objectversions ON matches.objectid=objectversions.fk_object_id WHERE objectversions.fk_mode_id=" . $modeid . " ORDER BY objectversions.createdate DESC ";
                    break;
                default:
                    // it's a list field
                    $query .= "INNER JOIN positioncontentitems ON matches.objectid=positioncontentitems.fk_rootobject_id INNER JOIN positions ON positioncontentitems.fk_position_id=positions.id INNER JOIN objectversions ON positions.fk_objectversion_id=objectversions.id WHERE objectversions.fk_mode_id=" . $modeid . " AND positioncontentitems.name = '" . self::$connection->real_escape_string($orderby) . "' AND positioncontentitems.inputtype='INPUTTYPE_COMBOBOX' ORDER BY positioncontentitems.contentitembody ASC, objectversions.changedate DESC ";
                    break;
            }
        }
        return self::selectQuery($query);
    }

    /**
     * get a record where the layout is used
     * 
     * @param int $id
     * @return resultset id
     */
    public static function getLayoutUsed($layoutid) {
        return self::selectQuery("SELECT id FROM objectversions WHERE fk_layout_id=" . $layoutid . " LIMIT 0,1");
    }

    /**
     * get a record where the structure is used
     * 
     * @param int $id
     * @return resultset id
     */
    public static function getStructureUsed($structureid) {
        return self::selectQuery("SELECT id FROM positions WHERE fk_structure_id=" . $structureid . " LIMIT 0,1");
    }

    /**
     * get a record where the style is used
     * 
     * @param int $id
     * @return resultset id
     */
    public static function getStyleUsed($styleid) {
        return self::selectQuery("SELECT id FROM objectversions WHERE fk_style_id=" . $styleid . " LIMIT 0,1 UNION SELECT id FROM positions WHERE fk_style_id=" . $styleid . " LIMIT 0,1");
    }

    /**
     * get a record where the set is used
     * 
     * @param int $id
     * @return resultset id
     */
    public static function getSetUsed($setid) {
        return self::selectQuery("SELECT id FROM objects WHERE fk_set_id=" . $setid . " LIMIT 0,1 UNION SELECT id FROM layouts WHERE fk_set_id=" . $setid . " LIMIT 0,1 UNION SELECT id FROM styles WHERE fk_set_id=" . $setid . " LIMIT 0,1 UNION SELECT id FROM structures WHERE fk_set_id=" . $setid . " LIMIT 0,1 UNION SELECT id FROM templates WHERE fk_set_id=" . $setid . " LIMIT 0,1");
    }

    /**
     * get a record where the user group is used
     * 
     * @param int $id
     * @return resultset id
     */
    public static function getUserGroupUsed($usergroupid) {
        return self::selectQuery("SELECT id FROM userusergroup WHERE fk_usergroup_id=" . $usergroupid . " LIMIT 0,1 UNION SELECT id FROM objectusergrouprole WHERE fk_usergroup_id=" . $usergroupid . " LIMIT 0,1");
    }

    /**
     * get a record where the role is used
     * 
     * @param int $id
     * @return resultset id
     */
    public static function getRoleUsed($roleid) {
        return self::selectQuery("SELECT id FROM objectusergrouprole WHERE fk_role_id=" . $roleid . " LIMIT 0,1");
    }

    /**
     * get a record where the template is used
     * 
     * @param int $id
     * @return resulttemplate id
     */
    public static function getTemplateUsed($templateid) {
        return self::selectQuery("SELECT id FROM objects WHERE fk_template_id=" . $templateid . " LIMIT 0,1 UNION SELECT id FROM objectversions WHERE fk_template_id=" . $templateid . " LIMIT 0,1");
    }

    /**
     * insert a new layout into the Store
     * 
     * @return int the new id
     */
    public static function insertLayout() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO layouts (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new layout version into the Store
     * 
     * @param int $layoutid the layout
     * @param int $modeid the mode
     * @param int $contextid the context
     * @return int the new id
     */
    public static function insertLayoutVersion($layoutid, $modeid, $contextid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO layoutversions (fk_layout_id, fk_mode_id, fk_context_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiiii", $layoutid, $modeid, $contextid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a layout version from the Store
     * 
     * @param int $layoutversionid the layout version to remove
     * @return boolean true if success
     */
    public static function deleteLayoutVersion($layoutversionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM layoutversions WHERE id=?")) {
            $stmt->bind_param("i", $layoutversionid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a layout  from the Store
     * 
     * @param int $layoutid the layout to delete
     * @return boolean true if success
     */
    public static function deleteLayout($layoutid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM layouts WHERE id=?")) {
            $stmt->bind_param("i", $layoutid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete all layout versions from the Store
     * 
     * @param int $layoutid the layout to delete the versions from
     * @return boolean true if success
     */
    public static function deleteLayoutVersions($layoutid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM layoutversions WHERE fk_layout_id=?")) {
            $stmt->bind_param("i", $layoutid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new structure into the Store
     * 
     * @return int the new id
     */
    public static function insertStructure() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO structures (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new structure version into the Store
     * 
     * @param int $structureid the structure
     * @param int $modeid the mode
     * @param int $contextid the context
     * @return int the new id
     */
    public static function insertStructureVersion($structureid, $modeid, $contextid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO structureversions (fk_structure_id, fk_mode_id, fk_context_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiiii", $structureid, $modeid, $contextid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a structure version from the Store
     * 
     * @param int $structureversionid the structure version to remove
     * @return boolean true if success
     */
    public static function deleteStructureVersion($structureversionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM structureversions WHERE id=?")) {
            $stmt->bind_param("i", $structureversionid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a structure  from the Store
     * 
     * @param int $structureid the structure to delete
     * @return boolean true if success
     */
    public static function deleteStructure($structureid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM structures WHERE id=?")) {
            $stmt->bind_param("i", $structureid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete all structure versions from the Store
     * 
     * @param int $structureid the structure to delete the versions from
     * @return boolean true if success
     */
    public static function deleteStructureVersions($structureid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM structureversions WHERE fk_structure_id=?")) {
            $stmt->bind_param("i", $structureid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new style into the Store
     * 
     * @return int the new id
     */
    public static function insertStyle() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO styles (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new style version into the Store
     * 
     * @param int $styleid the style
     * @param int $modeid the mode
     * @param int $contextid the context
     * @return int the new id
     */
    public static function insertStyleVersion($styleid, $modeid, $contextid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO styleversions (fk_style_id, fk_mode_id, fk_context_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiiii", $styleid, $modeid, $contextid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new styleparam into the Store
     * 
     * @return int the new id
     */
    public static function insertStyleParam() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO styleparams (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new style param version into the Store
     * 
     * @param int $styleparamid the style param id
     * @param int $modeid the mode
     * @param int $contextid the context
     * @return int the new id
     */
    public static function insertStyleParamVersion($styleparamid, $modeid, $contextid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO styleparamversions (fk_styleparam_id, fk_mode_id, fk_context_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiiii", $styleparamid, $modeid, $contextid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a style version from the Store
     * 
     * @param int $styleversionid the style version to remove
     * @return boolean true if success
     */
    public static function deleteStyleVersion($styleversionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM styleversions WHERE id=?")) {
            $stmt->bind_param("i", $styleversionid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a style param version from the Store
     * 
     * @param int $styleparamversionid the style param version to remove
     * @return boolean true if success
     */
    public static function deleteStyleParamVersion($styleparamversionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM styleparamversions WHERE id=?")) {
            $stmt->bind_param("i", $styleparamversionid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a style  from the Store
     * 
     * @param int $styleid the style to delete
     * @return boolean true if success
     */
    public static function deleteStyle($styleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM styles WHERE id=?")) {
            $stmt->bind_param("i", $styleid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete all style versions from the Store
     * 
     * @param int $styleid the style to delete the versions from
     * @return boolean true if success
     */
    public static function deleteStyleVersions($styleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM styleversions WHERE fk_style_id=?")) {
            $stmt->bind_param("i", $styleid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a style param from the Store
     * 
     * @param int $styleparamid the style parameter to delete
     * @return boolean true if success
     */
    public static function deleteStyleParam($styleparamid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM styleparams WHERE id=?")) {
            $stmt->bind_param("i", $styleparamid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete all style param versions from the Store
     * 
     * @param int $styleparamid the style param to delete the versions from
     * @return boolean true if success
     */
    public static function deleteStyleParamVersions($styleparamid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM styleparamversions WHERE fk_styleparam_id=?")) {
            $stmt->bind_param("i", $styleparamid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new set into the Store
     * 
     * @return int the new id
     */
    public static function insertSet() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO sets (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a set  from the Store
     * 
     * @param int $setid the set to delete
     * @return boolean true if success
     */
    public static function deleteSet($setid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM sets WHERE id=?")) {
            $stmt->bind_param("i", $setid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new user into the Store
     * 
     * @return int the new id
     */
    public static function insertUser() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO users (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a user from the Store
     * 
     * @param int $userid the set to delete
     * @return boolean true if success
     */
    public static function deleteUser($userid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM users WHERE id=?")) {
            $stmt->bind_param("i", $userid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new user group into the Store
     * 
     * @return int the new id
     */
    public static function insertUserGroup() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO usergroups (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a usergroup from the Store
     * 
     * @param int $usergroupid the set to delete
     * @return boolean true if success
     */
    public static function deleteUserGroup($usergroupid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM usergroups WHERE id=?")) {
            $stmt->bind_param("i", $usergroupid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new user group into the Store, a role always has a corresponding record in permissions
     * 
     * @return int the new id
     */
    public static function insertRole() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO roles (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            $id = self::insertQuery($stmt);
            $stmt = self::$connection->stmt_init();
            if ($stmt->prepare("INSERT INTO permissions (fk_role_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, NOW(), ?, NOW(), ?)")) {
                $stmt->bind_param("iii", $id, Authentication::getUser()->getId(), Authentication::getUser()->getId());
                self::insertQuery($stmt);
                return $id;
            }
        }
    }

    /**
     * delete a role from the Store
     * 
     * @param int $roleid the set to delete
     * @return boolean true if success
     */
    public static function deleteRole($roleid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM permissions WHERE fk_role_id=?")) {
            $stmt->bind_param("i", $roleid);
            self::actionQuery($stmt);
            $stmt = self::$connection->stmt_init();
            if ($stmt->prepare("DELETE FROM roles WHERE id=?")) {
                $stmt->bind_param("i", $roleid);
                return self::actionQuery($stmt);
            }
        }
    }

    /**
     * insert a new template into the Store
     * 
     * @return int the new id
     */
    public static function insertTemplate() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO templates (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a template from the Store
     * 
     * @param int $templateid the template to delete
     * @return boolean true if success
     */
    public static function deleteTemplate($templateid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM templates WHERE id=?")) {
            $stmt->bind_param("i", $templateid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a position content item from the Store
     * 
     * @param int $positioncontentitemid the item to delete
     * @return boolean true if success
     */
    public static function deletePositionContentItem($positioncontentitemid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM positioncontentitems WHERE id=?")) {
            $stmt->bind_param("i", $positioncontentitemid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a position object from the Store
     * 
     * @param int $positionobjectid the item to delete
     * @return boolean true if success
     */
    public static function deletePositionObject($positionobjectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM positionobjects WHERE id=?")) {
            $stmt->bind_param("i", $positionobjectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a position instance from the Store
     * 
     * @param int $positioninstanceid the item to delete
     * @return boolean true if success
     */
    public static function deletePositionInstance($positioninstanceid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM positioninstances WHERE id=?")) {
            $stmt->bind_param("i", $positioninstanceid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a position referral from the Store
     * 
     * @param int $positionreferralid the item to delete
     * @return boolean true if success
     */
    public static function deletePositionReferral($positionreferralid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM positionreferrals WHERE id=?")) {
            $stmt->bind_param("i", $positionreferralid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete a position from the Store
     * 
     * @param int $positionid the item to delete
     * @return boolean true if success
     */
    public static function deletePosition($positionid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM positions WHERE id=?")) {
            $stmt->bind_param("i", $positionid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete an object from the object cache
     * 
     * @param int $objectid the item to delete
     * @return boolean true if success
     */
    public static function deleteObjectFromCache($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM objectcache WHERE fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete an object from the object addressable parent cache
     * 
     * @param int $objectid the item to delete
     * @return boolean true if success
     */
    public static function deleteObjectFromAddressableParentCache($objectid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM objectaddressableparentcache WHERE fk_object_id=?")) {
            $stmt->bind_param("i", $objectid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * Get target objects to move another object to by set id
     * 
     * @param int $setid
     * @return resultset id
     */
    public static function getTargetObjectsBySet($setid) {
        return self::selectQuery("SELECT id FROM objects WHERE fk_set_id=" . $setid);
    }

    /**
     * delete a fileinclude  from the Store
     * 
     * @param int $fileincludeid the fileinclude to delete
     * @return boolean true if success
     */
    public static function deleteFileInclude($fileincludeid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM fileincludes WHERE id=?")) {
            $stmt->bind_param("i", $fileincludeid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete all fileinclude versions from the Store
     * 
     * @param int $fileincludeid the fileinclude to delete the versions from
     * @return boolean true if success
     */
    public static function deleteFileIncludeVersions($fileincludeid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM fileincludeversions WHERE fk_fileinclude_id=?")) {
            $stmt->bind_param("i", $fileincludeid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new fileinclude into the Store
     * 
     * @return int the new id
     */
    public static function insertFileInclude() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO fileincludes (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new fileinclude version into the Store
     * 
     * @param int $fileincludeid the fileinclude
     * @param int $modeid the mode
     * @return int the new id
     */
    public static function insertFileIncludeVersion($fileincludeid, $modeid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO fileincludeversions (fk_fileinclude_id, fk_mode_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiii", $fileincludeid, $modeid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * delete a snippet  from the Store
     * 
     * @param int $snippetid the snippet to delete
     * @return boolean true if success
     */
    public static function deleteSnippet($snippetid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM snippets WHERE id=?")) {
            $stmt->bind_param("i", $snippetid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * delete all snippet versions from the Store
     * 
     * @param int $snippetid the snippet to delete the versions from
     * @return boolean true if success
     */
    public static function deleteSnippetVersions($snippetid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("DELETE FROM snippetversions WHERE fk_snippet_id=?")) {
            $stmt->bind_param("i", $snippetid);
            return self::actionQuery($stmt);
        }
    }

    /**
     * insert a new snippet into the Store
     * 
     * @return int the new id
     */
    public static function insertSnippet() {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO snippets (createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("ii", Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

    /**
     * insert a new snippet version into the Store
     * 
     * @param int $snippetid the snippet
     * @param int $modeid the mode
     * @return int the new id
     */
    public static function insertSnippetVersion($snippetid, $modeid) {
        $stmt = self::$connection->stmt_init();
        if ($stmt->prepare("INSERT INTO snippetversions (fk_snippet_id, fk_mode_id, createdate, fk_createuser_id, changedate, fk_changeuser_id) VALUES (?, ?, NOW(), ?, NOW(), ?)")) {
            $stmt->bind_param("iiii", $snippetid, $modeid, Authentication::getUser()->getId(), Authentication::getUser()->getId());
            return self::insertQuery($stmt);
        }
    }

}
