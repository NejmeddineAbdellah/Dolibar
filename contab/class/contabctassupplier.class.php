<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 *  \file       dev/skeletons/contabctassupplier.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-04-17 22:55
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");


/**
 *	Put here description of your class
 */
class Contabctassupplier extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabctassupplier';			//!< Id that identify managed objects
	var $table_element='contabctassupplier';		//!< Name of table without prefix where object is stored

    var $id;
    
    var $entity;
	var $fk_cta;
	var $fk_socid;
	var $active;
	var $fourn_type;

	const TIPO_PROVEEDOR_ACTIVO = 1;
	const TIPO_PROVEEDOR_GASTOS = 2;
	
    /**
     *  Constructor
     *
     *  @param	DoliDb		$db      Database handler
     */
    function __construct($db)
    {
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
    function create($user, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		//if (isset($this->entity)) $this->entity=trim($this->entity);
		if (isset($this->entity)) {
			$this->entity=trim($this->entity);
		} else {
			$this->entity=$conf->entity;
		}
		if (isset($this->fk_cta)) $this->fk_cta=trim($this->fk_cta);
		if (isset($this->fk_socid)) $this->fk_socid=trim($this->fk_socid);
		if (isset($this->active)) $this->active=trim($this->active);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_ctas_supplier(";
		
		$sql.= "entity,";
		$sql.= "fk_cta,";
		$sql.= "fk_socid,";
		$sql.= "active,";
		$sql.= "fourn_type";

        $sql.= ") VALUES (";
        
        $sql.= " ".(! isset($this->entity)?'NULL':"'".$this->entity."'").",";
		$sql.= " ".(! isset($this->fk_cta)?'NULL':"'".$this->fk_cta."'").",";
		$sql.= " ".(! isset($this->fk_socid)?'NULL':"'".$this->fk_socid."'").",";
		$sql.= " ".(! isset($this->active)?'NULL':"'".$this->active."'").",";
		$sql.= " ".(! isset($this->fourn_type)?'NULL':"'".$this->fourn_type."'")."";

		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_ctas_supplier");

			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
	            //$interface=new Interfaces($this->db);
	            //$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
	            //if ($result < 0) { $error++; $this->errors=$interface->errors; }
	            //// End call triggers
			}
        }

        // Commit or rollback
        if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::create ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
            return $this->id;
		}
    }

    /**
     *  Load object in memory from the database
     *
     *  @param	int		$id    Id object
     *  @return int          	<0 if KO, >0 if OK
     */
    function fetch($id)
    {
    	global $langs,$conf;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.fk_cta,";
		$sql.= " t.fk_socid,";
		$sql.= " t.active,";
		$sql.= " t.fourn_type";
		
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_ctas_supplier as t";
        $sql.= " WHERE t.rowid = ".$id;
        $sql.= " AND entity = ".$conf->entity;

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
				$this->fk_cta = $obj->fk_cta;
				$this->fk_socid = $obj->fk_socid;
				$this->active = $obj->active;
                $this->fourn_type = $obj->fourn_type;
            }
            $this->db->free($resql);

            return 1;
        }
        else
        {
      	    $this->error="Error ".$this->db->lasterror();
            dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
            return -1;
        }
    }
    
    function fetch_next($id=0, $socid=0)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.fk_cta,";
    	$sql.= " t.fk_socid,";
    	$sql.= " t.active,";
    	$sql.= " t.fourn_type";
    	
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_ctas_supplier as t";
    	if ($id == 0) {
	    	$sql.= " WHERE 1 ";
    	} else {
    		$sql .= " WHERE t.rowid > ".$id;
    	}
    	if ($socid > 0) {
    		$sql .= " AND t.fk_socid = $socid ";
    	}
    	$sql.=" AND active = 1 ";
    	$sql.= " AND entity = ".$conf->entity;
    	$sql .= " ORDER BY t.rowid LIMIT 1 ";
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->fk_cta = $obj->fk_cta;
    			$this->fk_socid = $obj->fk_socid;
    			$this->active = $obj->active;
    			$this->fourn_type = $obj->fourn_type;
    			
    			$this->db->free($resql);
    			return 1;
    			 
    		} else {
	    		$this->db->free($resql);
	    		return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_by_idcta_socid($fk_cta, $socid)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.fk_cta,";
    	$sql.= " t.fk_socid,";
    	$sql.= " t.active,";
    	$sql.= " t.fourn_type";
    	 
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_ctas_supplier as t";
    	$sql.= " WHERE t.fk_cta = '$fk_cta' AND t.fk_socid = '$socid' ";
    	$sql.= " AND entity = ".$conf->entity;
    	
    	dol_syslog(get_class($this)."::fetch_by_idcta_socid sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->fk_cta = $obj->fk_cta;
    			$this->fk_socid = $obj->fk_socid;
    			$this->active = $obj->active;
    			$this->fourn_type = $obj->fourn_type;
    			 
    			$this->db->free($resql);
    			return 1;
    
    		} else {
    			$this->db->free($resql);
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_by_idcta_socid ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_array_by_socid($socid = 0, $st = 0)
    {
    	global $conf;
    	
    	$arr = array();
    	
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.fk_cta,";
    	$sql.= " t.fk_socid,";
    	$sql.= " t.active,";
    	$sql.= " t.fourn_type";
    	
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_ctas_supplier as t ";
    	$sql .= " WHERE 1 ";
    	if ($socid > 0) {
    		$sql .= " AND t.fk_socid = $socid ";
    	}
    	if ($st > 0) {
    		$sql .= " AND t.fourn_type = ".$st;
    	}
    	$sql.= " AND entity = ".$conf->entity;
    	
    	$sql .= " ORDER BY t.fk_socid, t.rowid";
    	dol_syslog(get_class($this)."::fetch_array_by_socid sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		while ($obj = $this->db->fetch_object($resql))
    		{    
    			$arr[] = $obj;
    		}
    			 
   			$this->db->free($resql);
   			return $arr;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_array_by_socid ".$this->error, LOG_ERR);
    		return array();
    	}
    }
    
    /**
     *  Update object into database
     *
     *  @param	User	$user        User that modifies
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
     *  @return int     		   	 <0 if KO, >0 if OK
     */
    function update($user=0, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->fk_cta)) $this->fk_cta=trim($this->fk_cta);
		if (isset($this->fk_socid)) $this->fk_socid=trim($this->fk_socid);
		if (isset($this->active)) $this->active=trim($this->active);

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_ctas_supplier SET";
        
		$sql.= " fk_cta=".(isset($this->fk_cta)?$this->fk_cta:"null").",";
		$sql.= " fk_socid=".(isset($this->fk_socid)?$this->fk_socid:"null").",";
		$sql.= " active=".(isset($this->active)?$this->active:"null").",";
		$sql.= " fourn_type=".(isset($this->fourn_type)?$this->fourn_type:"null")."";

        $sql.= " WHERE rowid=".$this->id;
        $sql.= " AND entity = ".$conf->entity;
        
		$this->db->begin();

		dol_syslog(get_class($this)."::update sql=".$sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
		{
			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
	            //$interface=new Interfaces($this->db);
	            //$result=$interface->run_triggers('MYOBJECT_MODIFY',$this,$user,$langs,$conf);
	            //if ($result < 0) { $error++; $this->errors=$interface->errors; }
	            //// End call triggers
	    	}
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::update ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
    }

 	/**
	 *  Delete object in database
	 *
     *	@param  User	$user        User that deletes
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
	 *  @return	int					 <0 if KO, >0 if OK
	 */
	function delete($user, $notrigger=0)
	{
		global $conf, $langs;
		$error=0;

		$this->db->begin();

		if (! $error)
		{
			if (! $notrigger)
			{
				// Uncomment this and change MYOBJECT to your own tag if you
		        // want this action calls a trigger.

		        //// Call triggers
		        //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
		        //$interface=new Interfaces($this->db);
		        //$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
		        //if ($result < 0) { $error++; $this->errors=$interface->errors; }
		        //// End call triggers
			}
		}

		if (! $error)
		{
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_ctas_supplier";
    		$sql.= " WHERE rowid=".$this->id;
    		$sql.= " AND entity = ".$conf->entity;

    		dol_syslog(get_class($this)."::delete sql=".$sql);
    		$resql = $this->db->query($sql);
        	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::delete ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
	}

	/**
	 *	Load an object from its id and create a new one in database
	 *
	 *	@param	int		$fromid     Id of object to clone
	 * 	@return	int					New id of clone
	 */
	function createFromClone($fromid)
	{
		global $user,$langs;

		$error=0;

		$object=new Contabctassupplier($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		$object->id=0;
		$object->statut=0;
		$object->fourn_type=0;

		// Clear fields
		// ...

		// Create clone
		$result=$object->create($user);

		// Other options
		if ($result < 0)
		{
			$this->error=$object->error;
			$error++;
		}

		if (! $error)
		{	}

		// End
		if (! $error)
		{
			$this->db->commit();
			return $object->id;
		}
		else
		{
			$this->db->rollback();
			return -1;
		}
	}

	/**
	 *	Initialise object with example values
	 *	Id must be 0 if object instance is a specimen
	 *
	 *	@return	void
	 */
	function initAsSpecimen()
	{
		$this->id=0;
		
		$this->entity='';
		$this->fk_cta='';
		$this->fk_socid='';
		$this->active='';
		$this->fourn_type='';
	}
}
