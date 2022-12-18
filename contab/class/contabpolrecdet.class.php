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
 *  \file       dev/skeletons/contabpolrecdet.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2016-01-28 19:17
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");


/**
 *	Put here description of your class
 */
class Contabpolrecdet extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabpolrecdet';			//!< Id that identify managed objects
	var $table_element='contabpolrecdet';		//!< Name of table without prefix where object is stored

    var $id;
    
	var $fk_poliza;
	var $asiento;
	var $cuenta;
	var $debe;
	var $haber;
	var $descripcion;
	var $uuid;

    


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
        
		if (isset($this->fk_poliza)) $this->fk_poliza=trim($this->fk_poliza);
		if (isset($this->asiento)) $this->asiento=trim($this->asiento);
		if (isset($this->cuenta)) $this->cuenta=trim($this->cuenta);
		if (isset($this->debe)) $this->debe=trim($this->debe);
		if (isset($this->haber)) $this->haber=trim($this->haber);
		if (isset($this->descripcion)) $this->descripcion=trim($this->descripcion);
		if (isset($this->uuid)) $this->uuid=trim($this->uuid);

        

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_pol_recdet(";
		
		$sql.= "fk_poliza,";
		$sql.= "asiento,";
		$sql.= "cuenta,";
		$sql.= "debe,";
		$sql.= "haber,";
		$sql.= "descripcion,";
		$sql.= "uuid";
        $sql.= ") VALUES (";
        
		$sql.= " ".(! isset($this->fk_poliza)?'NULL':"'".$this->fk_poliza."'").",";
		$sql.= " ".(! isset($this->asiento)?'NULL':"'".$this->asiento."'").",";
		$sql.= " ".(! isset($this->cuenta)?'NULL':"'".$this->db->escape($this->cuenta)."'").",";
		@$sql.= " ".(! isset($this->debe)?'NULL':"'".str_replace(",", "", number_format($this->debe,2))."'").",";
		@$sql.= " ".(! isset($this->haber)?'NULL':"'".str_replace(",", "", number_format($this->haber,2))."'").",";
		$sql.= " ".(! isset($this->descripcion)?'NULL':"'".$this->db->escape($this->descripcion)."'").",";
		$sql.= " ".(! isset($this->uuid)?'NULL':"'".$this->db->escape($this->uuid)."'")."";
        
		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_pol_recdet");

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
    	global $langs;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.fk_poliza,";
		$sql.= " t.asiento,";
		$sql.= " t.cuenta,";
		$sql.= " t.debe,";
		$sql.= " t.haber,";
		$sql.= " t.descripcion,";
		$sql.= " t.uuid";
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_pol_recdet as t";
        $sql.= " WHERE t.rowid = ".$id;

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
				$this->fk_poliza = $obj->fk_poliza;
				$this->asiento = $obj->asiento;
				$this->cuenta = $obj->cuenta;
				$this->debe = $obj->debe;
				$this->haber = $obj->haber;
				$this->descripcion = $obj->descripcion;
				$this->uuid = $obj->uuid;

                
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
    	$sql.= " t.fk_poliza,";
    	$sql.= " t.descripcion,";
    	$sql.= " t.uuid";
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_pol_recdet as t";
    	$sql .= " INNER JOIN ".MAIN_DB_PREFIX."contab_pol_rec as p";
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
    			$this->fk_poliza = $obj->fk_poliza;
    			$this->descripcion = $obj->descripcion;
    			$this->uuid = $obj->uuid;
    			 
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
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_pol_recdet as t";
    	$sql.= " WHERE t.fk_poliza = ".$fk_pol;
    	$sql.= " ORDER BY t.asiento DESC LIMIT 1";
    	//print $sql;
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
        
		if (isset($this->fk_poliza)) $this->fk_poliza=trim($this->fk_poliza);
		if (isset($this->asiento)) $this->asiento=trim($this->asiento);
		if (isset($this->cuenta)) $this->cuenta=trim($this->cuenta);
		if (isset($this->debe)) $this->debe=trim($this->debe);
		if (isset($this->haber)) $this->haber=trim($this->haber);

        

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_pol_recdet SET";
        
		$sql.= " fk_poliza=".(isset($this->fk_poliza)?$this->fk_poliza:"null").",";
		$sql.= " asiento=".(isset($this->asiento)?$this->asiento:"null").",";
		$sql.= " cuenta=".(isset($this->cuenta)?"'".$this->db->escape($this->cuenta)."'":"null").",";
		$sql.= " debe=".(isset($this->debe)?str_replace(",", "", number_format($this->debe,2)):"0").",";
		$sql.= " haber=".(isset($this->haber)?str_replace(",", "", number_format($this->haber,2)):"0").",";
		$sql.= " descripcion='".(isset($this->descripcion)?$this->descripcion:"null")."' ,";
		$sql.= " uuid='".(isset($this->uuid)?$this->uuid:"null")."'";
        
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
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_pol_recdet";
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
			$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_pol_recdet";
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

		$object=new Contabpolrecdet($this->db);

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
		
		$this->fk_poliza='';
		$this->asiento='';
		$this->cuenta='';
		$this->debe='';
		$this->haber='';

		
	}

}

