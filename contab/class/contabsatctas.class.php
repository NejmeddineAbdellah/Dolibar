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
 * 
 * code pour créer le module 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
 */

/**
 *  \file       dev/skeletons/contabsatctas.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-02-26 01:39
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *	Put here descripcionription of your class
 */
class Contabsatctas extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabsatctas';			//!< Id that identify managed objects
	var $table_element='contabsatctas';		//!< Name of table without prefix where object is stored

    var $id;
    
	var $nivel;
	var $codagr;
	var $descripcion;
	var $natur;
    
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
        
		if (isset($this->nivel)) $this->nivel=trim($this->nivel);
		if (isset($this->codagr)) $this->codagr=trim($this->codagr);
		if (isset($this->descripcion)) $this->descripcion=trim($this->descripcion);
		if (isset($this->natur)) $this->natur=trim($this->natur);
        
		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_sat_ctas(";
		
		$sql.= "rowid,";
		$sql.= "nivel,";
		$sql.= "codagr,";
		$sql.= "descripcion,";
		$sql.= "natur";
		
        $sql.= ") VALUES (";
        
		$sql.= " ".(! isset($this->rowid)?'NULL':"'".$this->rowid."'").",";
		$sql.= " ".(! isset($this->nivel)?'NULL':"'".$this->nivel."'").",";
		$sql.= " ".(! isset($this->codagr)?'NULL':"'".$this->db->escape($this->codagr)."'").",";
		$sql.= " ".(! isset($this->descripcion)?'NULL':"'".$this->db->escape($this->descripcion)."'").",";
		$sql.= " ".(! isset($this->natur)?'NULL':"'".$this->db->escape($this->natur)."'")."";
        
		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_sat_ctas");

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
		
		$sql.= " t.nivel,";
		$sql.= " t.codagr,";
		$sql.= " t.descripcion,";
		$sql.= " t.natur";
		
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_sat_ctas as t";
        $sql.= " WHERE t.rowid = ".$id;

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
				$this->nivel = $obj->nivel;
				$this->codagr = $obj->codagr;
				$this->descripcion = $obj->descripcion;
				$this->natur = $obj->natur;
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
    
    function fetch_next($id=0, $nivel=-1)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.nivel,";
    	$sql.= " t.codagr,";
    	$sql.= " t.descripcion,";
    	$sql.= " t.natur";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_sat_ctas as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id;
    	}
    	if ($nivel == 1) {
    		$sql .= " AND t.nivel between 0 AND 1";
    	} elseif ($nivel == 2) {
    		$sql .= " AND t.nivel = ".$nivel;
    	}
    	$sql .= " ORDER BY rowid LIMIT 1";
    	
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    		
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->nivel = $obj->nivel;
    			$this->codagr = $obj->codagr;
    			$this->descripcion = $obj->descripcion;
    			$this->natur = $obj->natur;
    			
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
    
    function fetch_next2m($id=0, $nivel=-1,$mm)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.nivel,";
    	$sql.= " t.codagr,";
    	$sql.= " t.descripcion,";
    	$sql.= " t.natur";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_sat_ctas as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} 
    	else {
    		$sql.= " WHERE t.rowid > ".$id;
    	}
    	if($mm){
    		$sql .= " AND (codagr LIKE '%".$mm."%' or descripcion LIKE '%".$mm."%')";
    	}
    	$sql .= " ORDER BY rowid LIMIT 1";
    	//print $sql."<br>"; 
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->nivel = $obj->nivel;
    			$this->codagr = $obj->codagr;
    			$this->descripcion = $obj->descripcion;
    			$this->natur = $obj->natur;
    			 
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
    
    function fetch_array() {
    	 
    	$sql = "Select * From ".MAIN_DB_PREFIX."contab_sat_ctas";
    	$arr = array();
    	 
    	dol_syslog(get_class($this)."::fetch_array sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($nr = $this->db->num_rows($resql))
    		{
    			$jj = 0;
    			while ($jj < $nr) {
    				
    				$obj = $this->db->fetch_array($resql);
    
    				$arr[] = $obj;
    
    				$jj = $jj + 1;
    			}
    			 
    			$this->db->free($resql);
    			return $arr;
    		}
    		else
    		{
    			return $arr;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_array ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    /* function fetch_next_by_codagr($codagr='', $nivel=-1)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.nivel,";
    	$sql.= " t.codagr,";
    	$sql.= " t.descripcion,";
    	$sql.= " t.natur";
    
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_sat_ctas as t";
    	if ($codagr == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.codagr > ".$codagr;
    	}
    	if ($nivel == 1) {
    		$sql .= " AND t.nivel between 0 AND 1";
    	} elseif ($nivel == 2) {
    		$sql .= " AND t.nivel = ".$nivel;
    	}
    	$sql .= " ORDER BY codagr LIMIT 1";
    	 
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->nivel = $obj->nivel;
    			$this->codagr = $obj->codagr;
    			$this->descripcion = $obj->descripcion;
    			$this->natur = $obj->natur;
    			 
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
    } */
    
    function fetch_by_CodAgr($codagr) //, $exact = false)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.nivel,";
    	$sql.= " t.codagr,";
    	$sql.= " t.descripcion,";
    	$sql.= " t.natur";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_sat_ctas as t";
    	$sql.= " WHERE t.codagr = '$codagr'";
    
    	dol_syslog(get_class($this)."::fetch_by_CodAgr sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->nivel = $obj->nivel;
    			$this->codagr = $obj->codagr;
    			$this->descripcion = $obj->descripcion;
    			$this->natur = $obj->natur;
    			
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
    		dol_syslog(get_class($this)."::fetch_by_CodAgr ".$this->error, LOG_ERR);
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
        
		if (isset($this->nivel)) $this->nivel=trim($this->nivel);
		if (isset($this->codagr)) $this->codagr=trim($this->codagr);
		if (isset($this->descripcion)) $this->descripcion=trim($this->descripcion);
		if (isset($this->natur)) $this->natur=trim($this->natur);

        

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_sat_ctas SET";
        
		$sql.= " nivel=".(isset($this->nivel)?$this->nivel:"null").",";
		$sql.= " codagr=".(isset($this->codagr)?"'".$this->db->escape($this->codagr)."'":"null").",";
		$sql.= " descripcion=".(isset($this->descripcion)?"'".$this->db->escape($this->descripcion)."'":"null").",";
		$sql.= " natur=".(isset($this->natur)?"'".$this->db->escape($this->natur)."'":"null")."";
        
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
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_sat_ctas";
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
	
	function delete_all($user, $notrigger=0)
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
			$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_sat_ctas";
			//$sql.= " WHERE rowid=".$this->id;
	
			dol_syslog(get_class($this)."::delete_all sql=".$sql);
			$resql = $this->db->query($sql);
			if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }
		}
	
		// Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
				dol_syslog(get_class($this)."::delete_all ".$errmsg, LOG_ERR);
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
	
	function reindexar() {
		$resql = $this->db->query("SELECT * FROM ".MAIN_DB_PREFIX."contab_sat_ctas");
		if($resql){
			//Se crea la tabla temporal de encabezados y detalle
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_sat_ctas_tmp LIKE ".MAIN_DB_PREFIX."contab_sat_ctas");
			
			$p = new Contabsatctas($this->db);
				
			// Colocar todo el contenido de la tabla Temporal a la tabla original
			$this->db->query("INSERT INTO ".MAIN_DB_PREFIX."contab_sat_ctas_tmp (nivel, codagr, descripcion, natur, import_key) SELECT nivel, codagr, descripcion, natur, '' FROM ".MAIN_DB_PREFIX."contab_sat_ctas");
				
			//eliminamos la tabla principal
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_sat_ctas");
				
			//Se crea la tabla original de encabezados y detalles
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_sat_ctas LIKE ".MAIN_DB_PREFIX."contab_sat_ctas_tmp");
				
			//Movemos las cuentas a la tabla principal
			$this->db->query("INSERT INTO ".MAIN_DB_PREFIX."contab_sat_ctas (nivel, codagr, descripcion, natur, import_key) SELECT nivel, codagr, descripcion, natur, '' FROM ".MAIN_DB_PREFIX."contab_sat_ctas_tmp");
	
			//eliminamos la tabla temporal
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_sat_ctas_tmp");
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

		$object=new Contabsatctas($this->db);

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
		
		$this->nivel='';
		$this->codagr='';
		$this->descripcion='';
		$this->natur='';
	}

}
