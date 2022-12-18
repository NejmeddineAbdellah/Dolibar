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
 *  \file       dev/skeletons/contabpaymentterm.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-03-05 20:02
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *	Put here description of your class
 */
class Contabpaymentterm extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabpaymentterm';			//!< Id that identify managed objects
	var $table_element='contabpaymentterm';		//!< Name of table without prefix where object is stored

    var $id;
    
    var $entity;
	var $fk_payment_term;
	var $cond_pago;
	
	/**
	 * Condición de Pago en Efectivo
	 */
	const PAGO_AL_CONTADO = 1;
	
	/**
	 * Condición de Pago a Crédito
	 */
	const PAGO_A_CREDITO = 2;
	
	/**
	 * Condición de Pago Anticipado del Cliente
	 */
	const PAGO_ANTICIPADO = 3;
	
	/**
	 * Condición de Pago en Partes ej. 50% a Crédito y 50% al Contado
	 */
	const PAGO_EN_PARTES = 4;
	
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
		if (isset($this->fk_payment_term)) $this->fk_payment_term=trim($this->fk_payment_term);
		if (isset($this->cond_pago)) $this->cond_pago=trim($this->cond_pago);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_payment_term(";
		
		$sql.= "rowid,";
		$sql.= "entity,";
		$sql.= "fk_payment_term,";
		$sql.= "cond_pago";

		
        $sql.= ") VALUES (";
        
		$sql.= " ".(! isset($this->rowid)?'NULL':"'".$this->rowid."'").",";
		$sql.= " ".(! isset($this->entity)?'NULL':"'".$this->entity."'").",";
		$sql.= " ".(! isset($this->fk_payment_term)?'NULL':"'".$this->fk_payment_term."'").",";
		$sql.= " ".(! isset($this->cond_pago)?'NULL':"'".$this->cond_pago."'")."";

		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_payment_term");

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
    	dol_syslog("ContabPaymentTerm Class:: create_from_firstone");
    	$sql = "CREATE TABLE ".MAIN_DB_PREFIX."contab_payment_term_tmp LIKE ".MAIN_DB_PREFIX."contab_payment_term";
    	dol_syslog("create_from_firstone - paso 1 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_payment_term_tmp SELECT * FROM ".MAIN_DB_PREFIX."contab_payment_term WHERE entity = 1";
    	dol_syslog("create_from_firstone - paso 2 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_payment_term_tmp SET entity = ".$conf->entity." WHERE entity = 1";
    	dol_syslog("create_from_firstone - paso 3 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_payment_term (entity, fk_payment_term, cond_pago) SELECT entity, fk_payment_term, cond_pago FROM ".MAIN_DB_PREFIX."contab_payment_term_tmp WHERE entity = ".$conf->entity;
    	dol_syslog("create_from_firstone - paso 4 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    	 
    	$sql = "DROP TABLE ".MAIN_DB_PREFIX."contab_payment_term_tmp";
    	dol_syslog("create_from_firstone - paso 5 de 5 :: sql=".$sql);
    	$this->db->query($sql);
    }

    function fetch_array() {
    	global $langs, $conf;
    	
    	$arr = array();
    	
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    	
    	$sql.= " t.fk_payment_term,";
    	$sql.= " t.cond_pago";
    	
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_payment_term as t";
    	$sql.= " WHERE 1 ";
    	$sql.= " AND entity = ".$conf->entity;
    	dol_syslog(get_class($this)."::fetch_array sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	
    	if ($resql)
    	{
    		while ($obj = $this->db->fetch_object($resql)) {
    			$arr[] = $obj;
    		}
    		$this->db->free($resql);
    	
    		return $arr;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_array ".$this->error, LOG_ERR);
    		return array();
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
    	global $langs, $conf;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.fk_payment_term,";
		$sql.= " t.cond_pago";
		
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_payment_term as t";
        $sql.= " WHERE t.rowid = ".$id;
        $sql.= " AND entity = ".$conf->entity;
        
    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        
        if ($resql)
        {
            if ($nr = $this->db->num_rows($resql) > 0)
            {
                $obj = $this->db->fetch_object($resql);

                $this->id = $obj->rowid;
                
				$this->fk_payment_term = $obj->fk_payment_term;
				$this->cond_pago = $obj->cond_pago;
            }
            $this->db->free($resql);

            return $nr;
        }
        else
        {
      	    $this->error="Error ".$this->db->lasterror();
            dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
            return -1;
        }
    }
    
    function fetch_by_cond_reglement($id)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.fk_payment_term,";
    	$sql.= " t.cond_pago";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_payment_term as t";
    	$sql.= " WHERE t.fk_payment_term = ".$id;
    	$sql.= " AND entity = ".$conf->entity;
    	
    	dol_syslog(get_class($this)."::fetch_by_cond_reglement sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    
    	if ($resql)
    	{
    		if ($nr = $this->db->num_rows($resql) > 0)
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id = $obj->rowid;
    
    			$this->fk_payment_term = $obj->fk_payment_term;
    			$this->cond_pago = $obj->cond_pago;
    		}
    		$this->db->free($resql);
    
    		return $nr;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_by_cond_reglement ".$this->error, LOG_ERR);
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
    
    function update_all_paymentterm_values($new_value = 0) {
    	global $conf, $langs;
    	$error=0;
    	
    	// Update request
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_payment_term ";
    	$sql.= " SET fk_payment_term=0 ";
    	$sql.= " WHERE 1 ";
    	$sql.= " AND entity = ".$conf->entity;
    	
    	$this->db->begin();
    	
    	dol_syslog(get_class($this)."::update_all_paymentterm_values sql=".$sql, LOG_DEBUG);
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
    
    function update($user=0, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->fk_payment_term)) $this->fk_payment_term=trim($this->fk_payment_term);
		if (isset($this->cond_pago)) $this->cond_pago=trim($this->cond_pago);
		
		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_payment_term SET";
		$sql.= " fk_payment_term=".(isset($this->fk_payment_term)?$this->fk_payment_term:"null").",";
		$sql.= " cond_pago=".(isset($this->cond_pago)?$this->cond_pago:"null")."";
		
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
    
    function update_fk_payment_term($user=0, $notrigger=0)
    {
    	global $conf, $langs;
    	$error=0;
    
    	// Clean parameters
    
    	if (isset($this->fk_payment_term)) $this->fk_payment_term=trim($this->fk_payment_term);
    
    	// Check parameters
    	// Put here code to add a control on parameters values
    
    	// Update request
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_payment_term SET";
    	$sql.= " fk_payment_term=".(isset($this->fk_payment_term)?"'".$this->db->escape($this->fk_payment_term)."'":"null")."";
    
    	$sql.= " WHERE rowid=".$this->id;
    	$sql.= " AND entity = ".$conf->entity;
    
    	$this->db->begin();
    
    	dol_syslog(get_class($this)."::update_fk_payment_term sql=".$sql, LOG_DEBUG);
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
    			dol_syslog(get_class($this)."::update_fk_payment_term ".$errmsg, LOG_ERR);
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
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_payment_term";
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

		$object=new Contabpaymentterm($this->db);

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
		$this->fk_payment_term='';
		$this->cond_pago='';

		
	}

}
