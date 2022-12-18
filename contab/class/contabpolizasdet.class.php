<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jfarber55@hotmail.com, jpfarber@gmail.com
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
 * 
 * code pour créer le module 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
 */

/**
 *  \file       dev/skeletons/contabpolizasdet.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-02-27 15:55
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *	Put here description of your class
 */
class Contabpolizasdet extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabpolizasdet';			//!< Id that identify managed objects
	var $table_element='contabpolizasdet';		//!< Name of table without prefix where object is stored

    var $id;
    
	var $asiento;
	var $cuenta;
	var $debe;
	var $haber;
	var $fk_poliza;
	var $desc;
	var $uuid;
	
	var $debe_total;
	var $haber_total;

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
    function create($user, $notrigger=0, $tbl = 'contab_polizasdet')
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->asiento)) $this->asiento=trim($this->asiento);
		if (isset($this->cuenta)) $this->cuenta=trim($this->cuenta);
		if (isset($this->debe)) $this->debe=trim($this->debe);
		if (isset($this->haber)) $this->haber=trim($this->haber);
		if (isset($this->fk_poliza)) $this->fk_poliza=trim($this->fk_poliza);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX.$tbl." (";
		
		$sql.= "asiento,";
		$sql.= "cuenta,";
		$sql.= "debe,";
		$sql.= "haber,";
        $sql.= "descripcion,";
        $sql.= "uuid,";
		$sql.= "fk_poliza";
		
        $sql.= ") VALUES (";
        
        $sql.= " ".(! isset($this->asiento)?'NULL':"'".$this->asiento."'").",";
		$sql.= " ".(! isset($this->cuenta)?'NULL':"'".$this->db->escape($this->cuenta)."'").",";
		$sql.= " ".(! isset($this->debe)?'NULL':"'".$this->debe."'").",";
		$sql.= " ".(! isset($this->haber)?'NULL':"'".$this->haber."'").",";
        $sql.= " ".(! isset($this->desc)?'NULL':"'".$this->desc."'").",";
        $sql.= " ".(! isset($this->uuid)?'NULL':"'".$this->uuid."'").",";
		$sql.= " ".(! isset($this->fk_poliza)?'NULL':"'".$this->fk_poliza."'")."";

		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_polizasdet");

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
    
    function insert()
    {
    	global $conf, $langs;
    	$error=0;
    
    	// Clean parameters
    
    	if (isset($this->asiento)) $this->asiento=trim($this->asiento);
    	if (isset($this->cuenta)) $this->cuenta=trim($this->cuenta);
    	if (isset($this->debe)) $this->debe=trim($this->debe);
    	if (isset($this->haber)) $this->haber=trim($this->haber);
    	if (isset($this->fk_poliza)) $this->fk_poliza=trim($this->fk_poliza);
    
    	// Check parameters
    	// Put here code to add control on parameters values
    
    	// Insert request
    	$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_polizasdet(";
    
    	$sql.= "asiento,";
    	$sql.= "cuenta,";
    	$sql.= "debe,";
    	$sql.= "haber,";
    	$sql.= "fk_poliza";
    
    	$sql.= ") VALUES (";
    
    	$sql.= " ".(! isset($this->asiento)?'NULL':"'".$this->asiento."'").",";
    	$sql.= " ".(! isset($this->cuenta)?'NULL':"'".$this->db->escape($this->cuenta)."'").",";
    	$sql.= " ".(! isset($this->debe)?'NULL':"'".str_replace(",", "", number_format($this->debe,2))."'").",";
    	$sql.= " ".(! isset($this->haber)?'NULL':"'".str_replace(",", "", number_format($this->haber,2))."'").",";
    	$sql.= " ".(! isset($this->fk_poliza)?'NULL':"'".$this->fk_poliza."'")."";
    
    	$sql.= ")";
    
    	$this->db->begin();
    
    	dol_syslog(get_class($this)."::insert sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }
    
    	if (! $error)
    	{
    		$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_polizasdet");
    
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
    			dol_syslog(get_class($this)."::insert ".$errmsg, LOG_ERR);
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
    	global $langs;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.asiento,";
		$sql.= " t.cuenta,";
		$sql.= " t.debe,";
		$sql.= " t.haber,";
        $sql.= " t.descripcion,";
        $sql.= " t.uuid,";
		$sql.= " t.fk_poliza";
		
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_polizasdet as t";
        $sql.= " WHERE t.rowid = ".$id;

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
				$this->asiento = $obj->asiento;
				$this->cuenta = $obj->cuenta;
				$this->debe = $obj->debe;
				$this->haber = $obj->haber;
                $this->desc = $obj->descripcion;
                $this->uuid = $obj->uuid;
				$this->fk_poliza = $obj->fk_poliza;
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
    
    function fetch_next($id=0, $cond='')
    {
    	global $langs,$conf;
    	
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.asiento,";
    	$sql.= " t.cuenta,";
    	$sql.= " t.debe,";
    	$sql.= " t.haber,";
        $sql.= " t.descripcion,";
        $sql.= " t.uuid,";
    	$sql.= " t.fk_poliza";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizasdet as t";
    	$sql .= " INNER JOIN ".MAIN_DB_PREFIX."contab_polizas as p";
    	$sql .= " ON p.rowid = t.fk_poliza";
    	$sql.= " WHERE p.entity=".$conf->entity;
    	if ($id == 0) {
	    	$sql.= " ";
    	} else {
    		$sql.= " AND t.rowid > ".$id." ";
    	}
    	if ($cond) {
    		$sql.=" AND ".$cond." ";
    	}
    	$sql .= " Order by t.rowid Limit 1";
    
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id = $obj->rowid;
    
    			$this->asiento = $obj->asiento;
    			$this->cuenta = $obj->cuenta;
    			$this->debe = $obj->debe;
    			$this->haber = $obj->haber;
                $this->desc = $obj->descripcion;
                $this->uuid = $obj->uuid;
    			$this->fk_poliza = $obj->fk_poliza;
    			
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
    
    /*
    function fetch_next_tmp($id=0, $cond='')
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.asiento,";
    	$sql.= " t.cuenta,";
    	$sql.= " t.debe,";
    	$sql.= " t.haber,";
    	$sql.= " t.fk_poliza";
    	
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizasdet_tmp as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id." ";
    	}
    	if ($cond) {
    		$sql.=" AND ".$cond." ";
    	}
    	$sql .= " Order by t.rowid Limit 1";
    
    	dol_syslog(get_class($this)."::fetch_next_tmp sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->asiento = $obj->asiento;
    			$this->cuenta = $obj->cuenta;
    			$this->debe = $obj->debe;
    			$this->haber = $obj->haber;
    			$this->fk_poliza = $obj->fk_poliza;
    			 
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
    		dol_syslog(get_class($this)."::fetch_next_tmp ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    */
    
    function fetch_last_asiento_by_num_poliza($fk_pol)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.asiento,";
    	$sql.= " t.cuenta,";
    	$sql.= " t.debe,";
    	$sql.= " t.haber,";
    	$sql.= " t.fk_poliza";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizasdet as t";
    	$sql.= " WHERE t.fk_poliza = ".$fk_pol;
    	$sql.= " ORDER BY t.asiento DESC LIMIT 1";
    
    	dol_syslog(get_class($this)."::fetch_last_asiento_by_num_poliza sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    		
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->asiento = $obj->asiento;
    			$this->cuenta = $obj->cuenta;
    			$this->debe = $obj->debe;
    			$this->haber = $obj->haber;
    			$this->fk_poliza = $obj->fk_poliza;
    		}
    		$this->db->free($resql);
    
    		return 1;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_last_asiento_by_num_poliza ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_by_cuenta($cta)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.asiento,";
    	$sql.= " t.cuenta,";
    	$sql.= " t.debe,";
    	$sql.= " t.haber,";
    	$sql.= " t.fk_poliza";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizasdet as t";
    	$sql.= " INNER JOIN ".MAIN_DB_PREFIX."contab_polizas as p";
    	$sql.= " ON p.rowid = t.fk_poliza";
    	$sql.= " WHERE t.cuenta = '$cta' AND p.entity = ".$conf->entity;
    
    	dol_syslog(get_class($this)."::fetch_by_cuenta sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->asiento = $obj->asiento;
    			$this->cuenta = $obj->cuenta;
    			$this->debe = $obj->debe;
    			$this->haber = $obj->haber;
    			$this->fk_poliza = $obj->fk_poliza;
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
    		dol_syslog(get_class($this)."::fetch_by_cuenta ".$this->error, LOG_ERR);
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
    function update($user=0, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->asiento)) $this->asiento=trim($this->asiento);
		if (isset($this->cuenta)) $this->cuenta=trim($this->cuenta);
		if (isset($this->debe)) $this->debe=trim($this->debe);
		if (isset($this->haber)) $this->haber=trim($this->haber);
        if (isset($this->desc)) $this->desc=trim($this->desc);
        if (isset($this->uuid)) $this->uuid=trim($this->uuid);
		if (isset($this->fk_poliza)) $this->fk_poliza=trim($this->fk_poliza);

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_polizasdet SET";
        
		$sql.= " asiento=".(isset($this->asiento)?$this->asiento:"null").",";
		$sql.= " cuenta=".(isset($this->cuenta)?"'".$this->db->escape($this->cuenta)."'":"null").",";
		$sql.= " debe=".(isset($this->debe)?str_replace(",", "", number_format($this->debe,2)):"null").",";
		$sql.= " haber=".(isset($this->haber)?str_replace(",", "", number_format($this->haber,2)):"null").",";
        $sql.= " descripcion='".(isset($this->desc)?$this->desc:"null")."',";
        $sql.= " uuid='".(isset($this->uuid)?$this->uuid:"null")."',";
		$sql.= " fk_poliza=".(isset($this->fk_poliza)?$this->fk_poliza:"null")."";

        $sql.= " WHERE rowid=".$this->id;

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
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_polizasdet";
    		$sql.= " WHERE rowid=".$this->id;

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
	
	function delete_by_id_poliza($user, $polid, $notrigger=0)
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
			$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_polizasdet";
			$sql.= " WHERE fk_poliza = ".$polid;
	
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

		$object=new Contabpolizasdet($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		$object->id=0;
		$object->statut=0;

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
		{
		}

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
		
		$this->asiento='';
		$this->cuenta='';
		$this->debe='';
		$this->haber='';
		$this->fk_poliza='';
	}

	/*
	 function fetch_asiento($a)
	 {
	 global $langs;
	 $sql = "SELECT";
	 $sql.= " t.rowid,";
	
	 $sql.= " t.asiento,";
	 $sql.= " t.cuenta,";
	 $sql.= " t.debe,";
	 $sql.= " t.haber,";
	 $sql.= " t.fk_poliza";
	
	 $sql.= " FROM ".MAIN_DB_PREFIX."contab_polizasdet as t";
	 $sql.= " WHERE t.asiento = ".$a;
	 $sql.= " ORDER BY asiento DESC LIMIT 1";
	
	 dol_syslog(get_class($this)."::fetch_asiento sql=".$sql, LOG_DEBUG);
	 $resql=$this->db->query($sql);
	 if ($resql)
	 {
	 if ($this->db->num_rows($resql))
	 {
	 $obj = $this->db->fetch_object($resql);
	
	 $this->id    = $obj->rowid;
	
	 $this->asiento = $obj->asiento;
	 $this->cuenta = $obj->cuenta;
	 $this->debe = $obj->debe;
	 $this->haber = $obj->haber;
	 $this->fk_poliza = $obj->fk_poliza;
	 }
	 $this->db->free($resql);
	
	 return 1;
	 }
	 else
	 {
	 $this->error="Error ".$this->db->lasterror();
	 dol_syslog(get_class($this)."::fetch_asiento ".$this->error, LOG_ERR);
	 return -1;
	 }
	 }
	
	 function fetch_asiento_by_id($id)
	 {
	 global $langs;
	 $sql = "SELECT";
	 $sql.= " t.rowid,";
	  
	 $sql.= " t.asiento,";
	 $sql.= " t.cuenta,";
	 $sql.= " t.debe,";
	 $sql.= " t.haber,";
	 $sql.= " t.fk_poliza";
	  
	  
	 $sql.= " FROM ".MAIN_DB_PREFIX."contab_polizasdet as t";
	 $sql.= " WHERE t.rowid = ".$id;
	 $sql.= " ORDER BY asiento DESC LIMIT 1";
	  
	 dol_syslog(get_class($this)."::fetch_asiento_by_id sql=".$sql, LOG_DEBUG);
	 $resql=$this->db->query($sql);
	 if ($resql)
	 {
	 if ($this->db->num_rows($resql))
	 {
	 $obj = $this->db->fetch_object($resql);
	  
	 $this->id    = $obj->rowid;
	  
	 $this->asiento = $obj->asiento;
	 $this->cuenta = $obj->cuenta;
	 $this->debe = $obj->debe;
	 $this->haber = $obj->haber;
	 $this->fk_poliza = $obj->fk_poliza;
	 }
	 $this->db->free($resql);
	  
	 return 1;
	 }
	 else
	 {
	 $this->error="Error ".$this->db->lasterror();
	 dol_syslog(get_class($this)."::fetch_asiento_by_id ".$this->error, LOG_ERR);
	 return -1;
	 }
	 }
	
	 function fetch_asiento_by_tp_cons($tp, $cons, $asiento)
	 {
	 global $langs,$conf;
	 $sql = "SELECT d.rowid, d.asiento, d.cuenta, d.debe, d.haber, d.fk_poliza  ";
	 $sql.= " FROM llx_contab_polizas l INNER Join llx_contab_polizasdet d ON l.rowid = d.fk_poliza ";
	 $sql.= " WHERE l.tipo_pol = '".$tp."' And l.cons = ".$cons." And d.asiento = ".$asiento." And l.entity = ".$conf->entity;
	 $sql.= " ORDER BY d.asiento DESC LIMIT 1";
	
	 dol_syslog(get_class($this)."::fetch_asiento_by_tp_cons sql=".$sql, LOG_DEBUG);
	 $resql=$this->db->query($sql);
	 if ($resql)
	 {
	 if ($this->db->num_rows($resql))
	 {
	 $obj = $this->db->fetch_object($resql);
	
	 $this->id    = $obj->rowid;
	
	 $this->asiento = $obj->asiento;
	 $this->cuenta = $obj->cuenta;
	 $this->debe = $obj->debe;
	 $this->haber = $obj->haber;
	 $this->fk_poliza = $obj->fk_poliza;
	 }
	 $this->db->free($resql);
	  
	 return 1;
	 }
	 else
	 {
	 $this->error="Error ".$this->db->lasterror();
	 dol_syslog(get_class($this)."::fetch_asiento_by_tp_cons ".$this->error, LOG_ERR);
	 return -1;
	 }
	 }
	
	 function fetch_first_pol_diario_by_factura($fk_poliza) {
	 //Se supone que si es automatico todos los asientos #1 tienen asignada la parte del Ingreso total pagada por el Cliente
	 // Ejemplo    asiento 1... Caja o Efectivo Debe de 116.00 pesos, fue un pago de un cliente en efectivo por 100.00 + IVA
	 global $conf;
	  
	 $sql = "SELECT d.* ";
	 $sql.= " FROM llx_contab_polizas l INNER Join llx_contab_polizasdet d ON l.rowid = d.fk_poliza ";
	 $sql.= " WHERE l.tipo_pol = 'D' AND d.asiento = 1 AND l.entity = ".$conf->entity;
	 $sql.= " GROUP BY l.tipo_pol ";
	 $sql.= " ORDER BY d.asiento DESC LIMIT 1";
	  
	 dol_syslog(get_class($this)."::fetch_first_pol_diario_by_factura sql=".$sql, LOG_DEBUG);
	 $resql=$this->db->query($sql);
	 if ($resql)
	 {
	 if ($this->db->num_rows($resql))
	 {
	 $obj = $this->db->fetch_object($resql);
	
	 $this->id    = $obj->rowid;
	
	 $this->asiento = $obj->asiento;
	 $this->cuenta = $obj->cuenta;
	 $this->debe = $obj->debe;
	 $this->haber = $obj->haber;
	 $this->fk_poliza = $obj->fk_poliza;
	
	 }
	 $this->db->free($resql);
	  
	 return 1;
	 }
	 else
	 {
	 $this->error="Error ".$this->db->lasterror();
	 dol_syslog(get_class($this)."::fetch_first_pol_diario_by_factura ".$this->error, LOG_ERR);
	 return -1;
	 }
	 }
	
	 function Get_Pagos_Registrados_En_Polizas_Asiento1($fk_facture, $tp) {
	 //Se supone que si es automatico todos los asientos #1 tienen asignada la parte del Ingreso total pagada por el Cliente
	 // Ejemplo    asiento 1... Caja o Efectivo Debe de 116.00 pesos, fue un pago de un cliente en efectivo por 100.00 + IVA
	 global $conf;
	 $sql = "SELECT sum(d.debe) as debe_total, sum(d.haber) as haber_total  ";
	 $sql.= " FROM llx_contab_polizas l INNER Join llx_contab_polizasdet d ON l.rowid = d.fk_poliza ";
	 $sql.= " WHERE l.fk_facture = ".$fk_facture." AND l.tipo_pol = '".$tp."' AND d.asiento = 1 AND l.entity = ".$conf->entity;
	 $sql.= " GROUP BY l.tipo_pol ";
	 $sql.= " ORDER BY d.asiento DESC LIMIT 1";
	  
	 $this->debe_total = 0;
	 $this->haber_total = 0;
	  
	 dol_syslog(get_class($this)."::Get_Pagos_Registrados_En_Polizas_Asiento1 sql=".$sql, LOG_DEBUG);
	 $resql=$this->db->query($sql);
	 if ($resql)
	 {
	 if ($this->db->num_rows($resql))
	 {
	 $obj = $this->db->fetch_object($resql);
	  
	 $this->id    = $obj->rowid;
	  
	 $this->asiento = $obj->asiento;
	 $this->cuenta = $obj->cuenta;
	 $this->debe = $obj->debe;
	 $this->haber = $obj->haber;
	 $this->fk_poliza = $obj->fk_poliza;
	  
	 $this->debe_total = $this->debe_total + $this->debe;
	 $this->haber_total = $this->haber_total + $this->haber;
	 }
	 $this->db->free($resql);
	  
	 return 1;
	 }
	 else
	 {
	 $this->error="Error ".$this->db->lasterror();
	 dol_syslog(get_class($this)."::Get_Pagos_Registrados_En_Polizas_Asiento1 ".$this->error, LOG_ERR);
	 return -1;
	 }
	 }
	 */
}
