<?php

/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2014	   Juanjo Menent		<jmenent@2byte.es>
 * Copyright (C) 2015 Gilles Lengy / Artaban Communication <gilles.lengy@artaban.fr>
 * Copyright (C) 2015 Gilles Dumont / Artaban Communication <gilles@artaban.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       \htdocs\internalnotification\class\internalnotification.class.php
 *  \ingroup    internal notifications
 *  \brief      This is CRUD class file (Create/Read/Update/Delete) for internal notifications
 */
// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT . "/core/class/commonobject.class.php");

/**
 * 	Handle the Internalnotification object
 */
class Internalnotification extends CommonObject {

    var $db;       //!< To store db handler
    var $error;       //!< To return error code (or message)
    var $errors = array();    //!< To return several error codes (or messages)
    var $element = 'internalnotification';   //!< Id that identify managed objects
    var $table_element = 'internal_notification';  //!< Name of table without prefix where object is stored
    var $id;
    var $action;
    var $receiver_email;
    var $subject;
    var $body;
    var $checkbox_1;

    /**
     *  Constructor
     *
     *  @param	DoliDb		$db      Database handler
     */
    function __construct($db) {
        $this->db = $db;
        return 1;
    }

    /**
     *  Create object into database
     *
     *  @param	User	$user        User that creates
     *  @param  int		$notrigger   0=launch triggers after, 1=disable triggers
     *  @return int      		   	 <0 if KO, Id of created object if OK
     */
    function create($user, $notrigger = 0) {
        global $conf, $langs;
        $error = 0;

        // Clean parameters

        if (isset($this->action))
            $this->action = trim($this->action);
        if (isset($this->receiver_email))
            $this->receiver_email = trim($this->receiver_email);
        if (isset($this->subject))
            $this->subject = trim($this->subject);
        if (isset($this->body))
            $this->body = trim($this->body);
        if (isset($this->checkbox_1))
            $this->checkbox_1 = trim($this->checkbox_1);



        // Check parameters
        // Put here code to add control on parameters values
        // Insert request
        $sql = "INSERT INTO " . MAIN_DB_PREFIX . $this->table_element . "(";

        $sql .= "action,";
        $sql .= "receiver_email,";
        $sql .= "subject,";
        $sql .= "body,";
        $sql .= "checkbox_1";


        $sql .= ") VALUES (";

        $sql .= " " . (!isset($this->action) ? 'NULL' : "'" . $this->db->escape($this->action) . "'") . ",";
        $sql .= " " . (!isset($this->receiver_email) ? 'NULL' : "'" . $this->db->escape($this->receiver_email) . "'") . ",";
        $sql .= " " . (!isset($this->subject) ? 'NULL' : "'" . $this->db->escape($this->subject) . "'") . ",";
        $sql .= " " . (!isset($this->body) ? 'NULL' : "'" . $this->db->escape($this->body) . "'") . ",";
        $sql .= " " . (!isset($this->checkbox_1) ? 'NULL' : "'" . $this->db->escape($this->checkbox_1) . "'") . "";


        $sql .= ")";

        $this->db->begin();

        dol_syslog(__METHOD__, LOG_DEBUG);
        $resql = $this->db->query($sql);
        if (!$resql) {
            $error++;
            $this->errors[] = "Error " . $this->db->lasterror();
        }

        if (!$error) {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . $this->table_element);

            if (!$notrigger) {
                // Uncomment this and change MYOBJECT to your own tag if you
                // want this action calls a trigger.
                //// Call triggers
                //$result=$this->call_trigger('MYOBJECT_CREATE',$user);
                //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
                //// End call triggers
            }
        }

        // Commit or rollback
        if ($error) {
            foreach ($this->errors as $errmsg) {
                dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
                $this->error .= ($this->error ? ', ' . $errmsg : $errmsg);
            }
            $this->db->rollback();
            return -1 * $error;
        } else {
            $this->db->commit();
            return $this->id;
        }
    }

    /**
     *  Load object in memory from the database
     *
     *  @param	int		$id    	Id object
     *  @param	string	$ref	Ref
     *  @return int          	<0 if KO, >0 if OK
     */
    function fetch($id, $ref = '', $id_is_action = false) {
        global $langs;
        $sql = "SELECT";
        $sql .= " t.rowid,";

        $sql .= " t.action,";
        $sql .= " t.receiver_email,";
        $sql .= " t.subject,";
        $sql .= " t.body,";
        $sql .= " t.checkbox_1";


        $sql .= " FROM " . MAIN_DB_PREFIX . $this->table_element . " as t";
        if ($ref) {
            $sql .= " WHERE t.ref = '" . $ref . "'";
        }
        if ($id_is_action) {
            $sql .= " WHERE t.action LIKE '" . $id . "'";
        } else {
            $sql .= " WHERE t.rowid = " . $id;
        }

        dol_syslog(get_class($this) . "::fetch : " . $sql);
        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql)) {
                $obj = $this->db->fetch_object($resql);

                $this->id = $obj->rowid;

                $this->action = $obj->action;
                $this->receiver_email = $obj->receiver_email;
                $this->subject = $obj->subject;
                $this->body = $obj->body;
                $this->checkbox_1 = $obj->checkbox_1;
            }
            $this->db->free($resql);

            return 1;
        } else {
            $this->error = "Error " . $this->db->lasterror();
            return -1;
        }
    }

    /**
     *  Update object into database
     *
     *  @param	User	$user        User that modifies
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
     *  @return int     		   	 <0 if KO, >0 if OK
     */
    function update($user, $notrigger = 0) {
        global $conf, $langs;
        $error = 0;

        // Clean parameters

        if (isset($this->action))
            $this->action = trim($this->action);
        if (isset($this->receiver_email))
            $this->receiver_email = trim($this->receiver_email);
        if (isset($this->subject))
            $this->subject = trim($this->subject);
        if (isset($this->body))
            $this->body = trim($this->body);

        if (isset($this->checkbox_1))
            $this->checkbox_1 = trim($this->checkbox_1);



        // Check parameters
        // Put here code to add a control on parameters values
        // Update request
        $sql = "UPDATE " . MAIN_DB_PREFIX . $this->table_element . " SET";

        $sql .= " action=" . (isset($this->action) ? "'" . $this->db->escape($this->action) . "'" : "null") . ",";
        $sql .= " receiver_email=" . (isset($this->receiver_email) ? "'" . $this->db->escape($this->receiver_email) . "'" : "null") . ",";
        $sql .= " subject=" . (isset($this->subject) ? "'" . $this->db->escape($this->subject) . "'" : "null") . ",";
        $sql .= " body=" . (isset($this->body) ? "'" . $this->db->escape($this->body) . "'" : "null") . ",";
        $sql .= " checkbox_1=" . (isset($this->checkbox_1) ? "'" . $this->db->escape($this->checkbox_1) . "'" : "null") . "";


        $sql .= " WHERE rowid=" . $this->id;

        $this->db->begin();

        dol_syslog(__METHOD__);
        $resql = $this->db->query($sql);
        if (!$resql) {
            $error++;
            $this->errors[] = "Error " . $this->db->lasterror();
        }

        if (!$error) {
            if (!$notrigger) {
                // Uncomment this and change MYOBJECT to your own tag if you
                // want this action calls a trigger.
                //// Call triggers
                //$result=$this->call_trigger('MYOBJECT_MODIFY',$user);
                //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
                //// End call triggers
            }
        }

        // Commit or rollback
        if ($error) {
            foreach ($this->errors as $errmsg) {
                dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
                $this->error .= ($this->error ? ', ' . $errmsg : $errmsg);
            }
            $this->db->rollback();
            return -1 * $error;
        } else {
            $this->db->commit();
            return 1;
        }
    }

    /**
     *  Delete object in database
     *
     * 	@param  User	$user        User that deletes
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
     *  @return	int					 <0 if KO, >0 if OK
     */
    function delete($user, $notrigger = 0) {
        global $conf, $langs;
        $error = 0;

        $this->db->begin();

        if (!$error) {
            if (!$notrigger) {
                // Uncomment this and change MYOBJECT to your own tag if you
                // want this action calls a trigger.
                //// Call triggers
                //$result=$this->call_trigger('MYOBJECT_DELETE',$user);
                //if ($result < 0) { $error++; //Do also what you must do to rollback action if trigger fail}
                //// End call triggers
            }
        }

        if (!$error) {
            $sql = "DELETE FROM " . MAIN_DB_PREFIX . $this->table_element;
            $sql .= " WHERE rowid=" . $this->id;

            dol_syslog(__METHOD__);
            $resql = $this->db->query($sql);
            if (!$resql) {
                $error++;
                $this->errors[] = "Error " . $this->db->lasterror();
            }
        }

        // Commit or rollback
        if ($error) {
            foreach ($this->errors as $errmsg) {
                dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
                $this->error .= ($this->error ? ', ' . $errmsg : $errmsg);
            }
            $this->db->rollback();
            return -1 * $error;
        } else {
            $this->db->commit();
            return 1;
        }
    }

    /**
     * 	Load an object from its id and create a new one in database
     *
     * 	@param	int		$fromid     Id of object to clone
     * 	@return	int					New id of clone
     */
    function createFromClone($fromid) {
        global $user, $langs;

        $error = 0;

        $object = new Internalnotification($this->db);

        $this->db->begin();

        // Load source object
        $object->fetch($fromid);
        $object->id = 0;
        $object->statut = 0;

        // Clear fields
        // ...
        // Create clone
        $result = $object->create($user);

        // Other options
        if ($result < 0) {
            $this->error = $object->error;
            $error++;
        }

        if (!$error) {
            
        }

        // End
        if (!$error) {
            $this->db->commit();
            return $object->id;
        } else {
            $this->db->rollback();
            return -1;
        }
    }

    /**
     * 	Initialise object with example values
     * 	Id must be 0 if object instance is a specimen
     *
     * 	@return	void
     */
    function initAsSpecimen() {
        $this->id = 0;

        $this->action = '';
        $this->receiver_email = '';
        $this->subject = '';
        $this->body = '';
    }

}
