<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jfarber55@hotmail.com
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
 *  \file       dev/skeletons/contabrelctas.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-04-14 15:49
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");


/**
 *	Put here description of your class
 */
class Contabrelctas extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabrelctas';			//!< Id that identify managed objects
	var $table_element='contabrelctas';		//!< Name of table without prefix where object is stored

    var $id;
    
    var $entity;
	var $code;
	var $description;
	var $fk_sat_cta;
	var $fk_cat_cta;

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
        
		if (isset($this->entity)) { 
			$this->entity=trim($this->entity);
		} else {
			$this->entity=$conf->entity; 
		}
		if (isset($this->code)) $this->code=trim($this->code);
		if (isset($this->description)) $this->description=trim($this->description);
		if (isset($this->fk_sat_cta)) $this->fk_sat_cta=trim($this->fk_sat_cta);
		if (isset($this->fk_cat_cta)) $this->fk_cat_cta=trim($this->fk_cat_cta);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_rel_ctas(";
		
		$sql.= "entity,";
		$sql.= "code,";
		$sql.= "description,";
		$sql.= "fk_sat_cta,";
		$sql.= "fk_cat_cta";

        $sql.= ") VALUES (";
        
        $sql.= " ".(! isset($this->entity)?'NULL':"'".$this->db->escape($this->entity)."'").",";
		$sql.= " ".(! isset($this->code)?'NULL':"'".$this->db->escape($this->code)."'").",";
		$sql.= " ".(! isset($this->description)?'NULL':"'".$this->db->escape($this->description)."'").",";
		$sql.= " ".(! isset($this->fk_sat_cta)?'NULL':"'".$this->db->escape($this->fk_sat_cta)."'").",";
		$sql.= " ".(! isset($this->fk_cat_cta)?'NULL':"'".$this->db->escape($this->fk_cat_cta)."'")."";
        
		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_rel_ctas");

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
    
    function create_from_firstone() {
    	global $conf;
    	$sql = "CREATE TABLE ".MAIN_DB_PREFIX."contab_rel_ctas_tmp LIKE ".MAIN_DB_PREFIX."contab_rel_ctas";
    	dol_syslog("create_from_firstone - paso 1 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_rel_ctas_tmp SELECT * FROM ".MAIN_DB_PREFIX."contab_rel_ctas WHERE entity = 1";
    	dol_syslog("create_from_firstone - paso 2 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_rel_ctas_tmp SET entity = ".$conf->entity." WHERE entity = 1";
    	dol_syslog("create_from_firstone - paso 3 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_rel_ctas (entity, code, description, fk_sat_cta, fk_cat_cta) SELECT entity, code, description, fk_sat_cta, fk_cat_cta FROM ".MAIN_DB_PREFIX."contab_rel_ctas_tmp WHERE entity = ".$conf->entity;
    	dol_syslog("create_from_firstone - paso 4 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "DROP TABLE ".MAIN_DB_PREFIX."contab_rel_ctas_tmp";
    	dol_syslog("create_from_firstone - paso 5 de 5 :: sql=".$sql);
    	$this->db->query($sql);
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
		
		$sql.= " t.code,";
		$sql.= " t.description,";
		$sql.= " t.fk_sat_cta,";
		$sql.= " t.fk_cat_cta";
		
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_rel_ctas as t";
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
                
				$this->code = $obj->code;
				$this->description = $obj->description;
				$this->fk_sat_cta = $obj->fk_sat_cta;
				$this->fk_cat_cta = $obj->fk_cat_cta;
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
    
    function fetch_by_code($code)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.code,";
    	$sql.= " t.description,";
    	$sql.= " t.fk_sat_cta,";
    	$sql.= " t.fk_cat_cta";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_rel_ctas as t";
    	$sql.= " WHERE t.code = '$code'";
    	$sql.= " AND entity = ".$conf->entity;
    	
    	dol_syslog(get_class($this)."::fetch_by_code sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->code = $obj->code;
    			$this->description = $obj->description;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->fk_cat_cta = $obj->fk_cat_cta;
    			
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
    		dol_syslog(get_class($this)."::fetch_by_code ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_next($id=0)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.code,";
    	$sql.= " t.description,";
    	$sql.= " t.fk_sat_cta,";
    	$sql.= " t.fk_cat_cta";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_rel_ctas as t";
    	if ($id == 0) {
	    	$sql.= " WHERE t.rowid = ".$id;
    	} else {
    		$sql.= "WHERE t.rowid > $id";
    	}
    	$sql.= " AND entity = ".$conf->entity;
    	
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->code = $obj->code;
    			$this->description = $obj->description;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->fk_cat_cta = $obj->fk_cat_cta;
    			
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
    
    function fetch_array()
    {
    	global $langs,$conf;
    	
    	$arr = array();
    	
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.code,";
    	$sql.= " t.description,";
    	$sql.= " t.fk_sat_cta,";
    	$sql.= " t.fk_cat_cta";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_rel_ctas as t";
    	//$sql.= " WHERE t.rowid = ".$id;
    	$sql.= " WHERE 1 ";
    	$sql.= " AND entity = ".$conf->entity;
    	
    	dol_syslog(get_class($this)."::fetch_array sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		while ($obj = $this->db->fetch_object($resql))
    		{	
    			/*
    			$this->id    = $obj->rowid;
    
    			$this->code = $obj->code;
    			$this->description = $obj->description;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->fk_cat_cta = $obj->fk_cat_cta;
				*/
    			
    			$arr[] = $obj;
    		}
    		$this->db->free($resql);
    
    		return $arr;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_array ".$this->error, LOG_ERR);
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
        
		if (isset($this->code)) $this->code=trim($this->code);
		if (isset($this->description)) $this->description=trim($this->description);
		if (isset($this->fk_sat_cta)) $this->fk_sat_cta=trim($this->fk_sat_cta);
		if (isset($this->fk_cat_cta)) $this->fk_cat_cta=trim($this->fk_cat_cta);
        
		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_rel_ctas SET";
        
		$sql.= " code=".(isset($this->code)?"'".$this->db->escape($this->code)."'":"null").",";
		$sql.= " description=".(isset($this->description)?"'".$this->db->escape($this->description)."'":"null").",";
		$sql.= " fk_sat_cta=".(isset($this->fk_sat_cta)?"'".$this->db->escape($this->fk_sat_cta)."'":"null").",";
		$sql.= " fk_cat_cta=".(isset($this->fk_cat_cta)?"'".$this->db->escape($this->fk_cat_cta)."'":"null")."";
        
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
    
    function update_fk_sat_cta($user=0, $notrigger=0)
    {
    	global $conf, $langs;
    	$error=0;
    
    	// Clean parameters
    
    	if (isset($this->code)) $this->code=trim($this->code);
    	if (isset($this->fk_sat_cta)) $this->fk_sat_cta=trim($this->fk_sat_cta);
    
    	// Check parameters
    	// Put here code to add a control on parameters values
    
    	// Update request
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_rel_ctas SET";
    	$sql.= " fk_sat_cta=".(isset($this->fk_sat_cta)?"'".$this->db->escape($this->fk_sat_cta)."'":"null")."";
    
    	$sql.= " WHERE rowid=".$this->id;
    	$sql.= " AND entity = ".$conf->entity;
    	
    	$this->db->begin();
    
    	dol_syslog(get_class($this)."::update_fk_sat_cta sql=".$sql, LOG_DEBUG);
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
    			dol_syslog(get_class($this)."::update_fk_sat_cta ".$errmsg, LOG_ERR);
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
    
    function update_fk_cat_cta($user=0, $notrigger=0)
    {
    	global $conf, $langs;
    	$error=0;
    
    	// Clean parameters
    
    	if (isset($this->code)) $this->code=trim($this->code);
    	if (isset($this->fk_sat_cta)) $this->fk_sat_cta=trim($this->fk_sat_cta);
    
    	// Check parameters
    	// Put here code to add a control on parameters values
    
    	// Update request
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_rel_ctas SET";
    	$sql.= " fk_cat_cta=".(isset($this->fk_cat_cta)?"'".$this->db->escape($this->fk_cat_cta)."'":"null")."";
    
    	$sql.= " WHERE rowid=".$this->id;
    	$sql.= " AND entity = ".$conf->entity;
    	
    	$this->db->begin();
    
    	dol_syslog(get_class($this)."::update_fk_cat_cta sql=".$sql, LOG_DEBUG);
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
    			dol_syslog(get_class($this)."::update_fk_cat_cta ".$errmsg, LOG_ERR);
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
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_rel_ctas";
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

		$object=new Contabrelctas($this->db);

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
		
		$this->entity='';
		$this->code='';
		$this->description='';
		$this->fk_sat_cta='';
		$this->fk_cat_cta='';
	}
}
