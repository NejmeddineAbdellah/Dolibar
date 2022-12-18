<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jpfarber@auribox.com
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
 *  \file       dev/skeletons/contabperiodos.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-04-01 20:40
 */

// Put here all includes required by your class file
//require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *	Put here description of your class
 */
class Contabperiodos extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabperiodos';			//!< Id that identify managed objects
	var $table_element='contabperiodos';		//!< Name of table without prefix where object is stored

    var $id;
    
    var $entity;
	var $anio;
	var $mes;
	var $estado;
	var $validado_bg;
	var $validado_bc;
	var $validado_er;
	var $validado_ld;
	var $validado_lm;
	
	const TODOS_LOS_PERIODOS = 0;
	const PERIODO_ABIERTO = 1;
	const PERIODO_CERRADO = 2;
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
		if (isset($this->anio)) $this->anio=trim($this->anio);
		if (isset($this->mes)) $this->mes=trim($this->mes);
		if (isset($this->estado)) $this->estado=trim($this->estado);
		if (isset($this->validado_bg)) $this->validado_bg=trim($this->validado_bg);
		if (isset($this->validado_bc)) $this->validado_bc=trim($this->validado_bc);
		if (isset($this->validado_er)) $this->validado_er=trim($this->validado_er);
		if (isset($this->validado_ld)) $this->validado_ld=trim($this->validado_ld);
		if (isset($this->validado_lm)) $this->validado_lm=trim($this->validado_lm);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_periodos(";
		
		$sql.= "entity,";
		$sql.= "anio,";
		$sql.= "mes,";
		$sql.= "estado,";
		$sql.= "validado_bg,";
		$sql.= "validado_bc,";
		$sql.= "validado_er,";
		$sql.= "validado_ld,";
		$sql.= "validado_lm";

        $sql.= ") VALUES (";
        
		$sql.= " ".(! isset($this->entity)?'NULL':"'".$this->entity."'").",";
		$sql.= " ".(! isset($this->anio)?'NULL':"'".$this->anio."'").",";
		$sql.= " ".(! isset($this->mes)?'NULL':"'".$this->mes."'").",";
		$sql.= " ".(! isset($this->estado)?'NULL':"'".$this->estado."'").",";
		$sql.= " ".(! isset($this->validado_bg)?'NULL':"'".$this->validado_bg."'").",";
		$sql.= " ".(! isset($this->validado_bc)?'NULL':"'".$this->validado_bc."'").",";
		$sql.= " ".(! isset($this->validado_er)?'NULL':"'".$this->validado_er."'").",";
		$sql.= " ".(! isset($this->validado_ld)?'NULL':"'".$this->validado_ld."'").",";
		$sql.= " ".(! isset($this->validado_lm)?'NULL':"'".$this->validado_lm."'")."";

		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_periodos");

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
    
    function get_anios_array() {
    	global $langs,$conf;
    	 
    	$a = array();
    	 
    	$sql = "SELECT";
    	 
    	$sql.= " t.anio";
    	 
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_periodos as t";
    	$sql.= " WHERE 1 ";
    	$sql.= " AND entity = ".$conf->entity;
    	$sql.= " GROUP BY t.anio ";
    	$sql.= " ORDER BY t.anio DESC ";
    	 
    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		while ($obj = $this->db->fetch_object($resql))
    		{
    			$this->id    = $obj->rowid;
    			 
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->estado = $obj->estado;
    			 
    			$a[] = $this->anio;
    		}
    		$this->db->free($resql);
    		 
    		return $a;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
    		return a;
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
		
		$sql.= " t.anio,";
		$sql.= " t.mes,";
		$sql.= " t.estado,";
		$sql.= " t.validado_bg,";
		$sql.= " t.validado_bc,";
		$sql.= " t.validado_er,";
		$sql.= " t.validado_ld,";
		$sql.= " t.validado_lm";

        $sql.= " FROM ".MAIN_DB_PREFIX."contab_periodos as t";
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
                
				$this->anio = $obj->anio;
				$this->mes = $obj->mes;
				$this->estado = $obj->estado;
				$this->validado_bg = $obj->validado_bg;
				$this->validado_bc = $obj->validado_bc;
				$this->validado_er = $obj->validado_er;
				$this->validado_ld = $obj->validado_ld;
				$this->validado_lm = $obj->validado_lm;  
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

    function fetch_by_period($anio=0,$mes=0)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.estado,";
		$sql.= " t.validado_bg,";
		$sql.= " t.validado_bc,";
		$sql.= " t.validado_er,";
		$sql.= " t.validado_ld,";
		$sql.= " t.validado_lm";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_periodos as t ";
    	$sql .= " WHERE 1 ";
    	if ($anio > 0) {
	    	$sql .= " AND t.anio = $anio ";
    	}
    	if ($anio > 0) { $sql .= " AND t.mes = $mes "; }
    	$sql.= " AND entity = ".$conf->entity;
    	$sql .= " LIMIT 1 ";
    	dol_syslog(get_class($this)."::fetch_by_period sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->estado = $obj->estado;
    			$this->validado_bg = $obj->validado_bg;
    			$this->validado_bc = $obj->validado_bc;
    			$this->validado_er = $obj->validado_er;
    			$this->validado_ld = $obj->validado_ld;
    			$this->validado_lm = $obj->validado_lm;
    
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
    		dol_syslog(get_class($this)."::fetch_by_period ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_next_by_id($id=0)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.estado,";
		$sql.= " t.validado_bg,";
		$sql.= " t.validado_bc,";
		$sql.= " t.validado_er,";
		$sql.= " t.validado_ld,";
		$sql.= " t.validado_lm";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_periodos as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id." ";
    	}
    	$sql.= " AND entity = ".$conf->entity;
    	$sql .= "ORDER BY t.rowid LIMIT 1";
    	dol_syslog(get_class($this)."::fetch_next_by_id sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->estado = $obj->estado;
    			$this->validado_bg = $obj->validado_bg;
    			$this->validado_bc = $obj->validado_bc;
    			$this->validado_er = $obj->validado_er;
    			$this->validado_ld = $obj->validado_ld;
    			$this->validado_lm = $obj->validado_lm;
    		}
    		$this->db->free($resql);
    
    		return 1;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next_by_id ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_next_period($tipo_estado,$anio,$mes=0)
    {
    	/* $otro_anio = 0;
    	if ($mes == 12) {
    		$otro_anio = 1;
    	} */
    	 
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.estado,";
		$sql.= " t.validado_bg,";
		$sql.= " t.validado_bc,";
		$sql.= " t.validado_er,";
		$sql.= " t.validado_ld,";
		$sql.= " t.validado_lm";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_periodos as t ";
    	$sql.= " WHERE t.anio = $anio";
    	if ($mes) {
    		$sql .= " AND mes < $mes ";
    	}
    	if ($tipo_estado > 0) {
    		$sql.= " AND t.estado = ".$tipo_estado;
    	}
    	$sql.= " AND entity = ".$conf->entity;
    	$sql .= " ORDER BY t.anio DESC, t.mes DESC LIMIT 1 ";
    	dol_syslog(get_class($this)."::fetch_next_by_period sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->estado = $obj->estado;
    			$this->validado_bg = $obj->validado_bg;
    			$this->validado_bc = $obj->validado_bc;
    			$this->validado_er = $obj->validado_er;
    			$this->validado_ld = $obj->validado_ld;
    			$this->validado_lm = $obj->validado_lm;
    			 
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
    		dol_syslog(get_class($this)."::fetch_next_by_period ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_open_period()
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.estado,";
    	$sql.= " t.validado_bg,";
    	$sql.= " t.validado_bc,";
    	$sql.= " t.validado_er,";
    	$sql.= " t.validado_ld,";
    	$sql.= " t.validado_lm";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_periodos as t ";
    	$sql.= " WHERE t.estado = ".$this::PERIODO_ABIERTO;
    	$sql.= " AND entity = ".$conf->entity;
    	$sql .= " ORDER BY t.anio DESC, t.mes DESC LIMIT 1 ";
    	dol_syslog(get_class($this)."::fetch_open_period sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->estado = $obj->estado;
    			$this->validado_bg = $obj->validado_bg;
    			$this->validado_bc = $obj->validado_bc;
    			$this->validado_er = $obj->validado_er;
    			$this->validado_ld = $obj->validado_ld;
    			$this->validado_lm = $obj->validado_lm;
    
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
    		dol_syslog(get_class($this)."::fetch_open_period ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function close_period($anio, $mes)
    {
    	global $conf, $langs;
    	$error=0;
    
    	// Update request
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_periodos SET estado = ".$this::PERIODO_CERRADO;
    	$sql.= " WHERE estado = ".$this::PERIODO_ABIERTO." AND anio = ".$anio." AND mes = ".$mes;
    	$sql.= " AND validado_bg = 1 AND validado_bc = 1 AND validado_er = 1 AND validado_ld = 1 AND validado_lm = 1";
    	$sql.= " AND entity = ".$conf->entity;
    	
    	$this->db->begin();
    
    	dol_syslog(get_class($this)."::close_period sql=".$sql, LOG_DEBUG);
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
    			dol_syslog(get_class($this)."::close_period ".$errmsg, LOG_ERR);
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
    
    function reopen_period($anio, $mes)
    {
    	global $conf, $langs;
    	$error=0;
    
    	// Update request
    	$sql = "UPDATE ".MAIN_DB_PREFIX."contab_periodos SET estado = ".$this::PERIODO_ABIERTO;
    	$sql.= " WHERE estado = ".$this::PERIODO_CERRADO." AND anio = ".$anio." AND mes = ".$mes;
    	$sql.= " AND entity = ".$conf->entity;
    
    	$this->db->begin();
    
    	dol_syslog(get_class($this)."::open_period sql=".$sql, LOG_DEBUG);
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
    			dol_syslog(get_class($this)."::open_period ".$errmsg, LOG_ERR);
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
    
    function period_is_closed($anio, $mes) {
    	global $conf, $langs;
    	$error=0;
    	
    	// Update request
    	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."contab_periodos ";
    	$sql.= " WHERE estado = ".$this::PERIODO_CERRADO." AND anio = ".$anio." AND mes = ".$mes;
    	$sql.= " AND entity = ".$conf->entity;
    	
    	dol_syslog(get_class($this)."::period_is_closed sql=".$sql, LOG_DEBUG);
    	$resql = $this->db->query($sql);
    	
    	if ($row = $this->db->fetch_row($resql)) {
    		return 1;
    	} else {
    		return 0;
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
        
		if (isset($this->anio)) $this->anio=trim($this->anio);
		if (isset($this->mes)) $this->mes=trim($this->mes);
		if (isset($this->estado)) $this->estado=trim($this->estado);
		if (isset($this->validado_bg)) $this->validado_bg=trim($this->validado_bg);
		if (isset($this->validado_bc)) $this->validado_bc=trim($this->validado_bc);
		if (isset($this->validado_er)) $this->validado_er=trim($this->validado_er);
		if (isset($this->validado_ld)) $this->validado_ld=trim($this->validado_ld);
		if (isset($this->validado_lm)) $this->validado_lm=trim($this->validado_lm);

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_periodos SET";
        
		$sql.= " anio=".(isset($this->anio)?$this->anio:"null").",";
		$sql.= " mes=".(isset($this->mes)?$this->mes:"null").",";
		$sql.= " estado=".(isset($this->estado)?$this->estado:"null").",";
		$sql.= " validado_bg=".(isset($this->validado_bg)?$this->validado_bg:"null").",";
		$sql.= " validado_bc=".(isset($this->validado_bc)?$this->validado_bc:"null").",";
		$sql.= " validado_er=".(isset($this->validado_er)?$this->validado_er:"null").",";
		$sql.= " validado_ld=".(isset($this->validado_ld)?$this->validado_ld:"null").",";
		$sql.= " validado_lm=".(isset($this->validado_lm)?$this->validado_lm:"null")."";

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
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_periodos";
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

		$object=new Contabperiodos($this->db);

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
		$this->anio='';
		$this->mes='';
		$this->estado='';
		$this->validado_bg='';
		$this->validado_bc='';
		$this->validado_er='';
		$this->validado_ld='';
		$this->validado_lm='';
	}
	
	function MesToStr($mes) {
		if ($mes == 1) $strmes = "Enero";
		if ($mes == 2) $strmes = "Febrero";
		if ($mes == 3) $strmes = "Marzo";
		if ($mes == 4) $strmes = "Abril";
		if ($mes == 5) $strmes = "Mayo";
		if ($mes == 6) $strmes = "Junio";
		if ($mes == 7) $strmes = "Julio";
		if ($mes == 8) $strmes = "Agosto";
		if ($mes == 9) $strmes = "Septiembre";
		if ($mes == 10) $strmes = "Octubre";
		if ($mes == 11) $strmes = "Noviembre";
		if ($mes == 12) $strmes = "Diciembre";
		if ($mes == 13) $strmes = "Periodo de Ajuste";
		return $strmes;
	}
	
	function MesToStr3($mes) {
		return substr($this->MesToStr($mes), 0, 3);
	}
}

?>