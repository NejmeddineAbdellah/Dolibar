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
 *  \file       dev/skeletons/contabcatctas.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-02-26 01:38
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//include_once DOL_DOCUMENT_ROOT.'/contab/class/contabconfig.class.php';
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *	Put here description of your class
 */
class Contabcatctas extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabcatctas';			//!< Id that identify managed objects
	var $table_element='contabcatctas';		//!< Name of table without prefix where object is stored

    var $id;
    
    var $entity;
	var $cta;
	var $descta;
	var $fk_sat_cta;
	var $subctade;
	
	var $codagr;

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
		if (isset($this->cta)) $this->cta=trim($this->cta);
		if (isset($this->descta)) $this->descta=trim($this->descta);
		if (isset($this->fk_sat_cta)) $this->fk_sat_cta=trim($this->fk_sat_cta);
		if (isset($this->subctade)) $this->subctade=trim($this->subctade);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_cat_ctas(";
		
		$sql.= "entity,";
		$sql.= "cta,";
		$sql.= "descta,";
		$sql.= "fk_sat_cta,";
		$sql.= "subctade";
		
        $sql.= ") VALUES (";
        $sql.= " ".(! isset($this->entity)?'NULL':"'".$this->db->escape($this->entity)."'").",";
		$sql.= " ".(! isset($this->cta)?'NULL':"'".$this->db->escape($this->cta)."'").",";
		$sql.= " ".(! isset($this->descta)?'NULL':"'".$this->db->escape($this->descta)."'").",";
		$sql.= " ".(! isset($this->fk_sat_cta)?'NULL':"'".$this->fk_sat_cta."'").",";
		$sql.= " ".(! isset($this->subctade)?'NULL':"'".$this->db->escape($this->subctade)."'")."";
        
		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_cat_ctas");

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
		
		$sql.= " t.cta,";
		$sql.= " t.descta,";
		$sql.= " t.fk_sat_cta,";
		$sql.= " t.subctade";

        $sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas as t";
        $sql.= " WHERE t.rowid = ".$id;
        $sql.= " AND t.entity = ".$conf->entity;

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
                $this->entity = $obj->entity;
				$this->cta = $obj->cta;
				$this->descta = $obj->descta;
				$this->fk_sat_cta = $obj->fk_sat_cta;
				$this->subctade = $obj->subctade;
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
    
    function fetch_saldos($id, $anio=0, $mes=0)
    {	//dol_syslog('LLEGAAAAAAAAAAAAAAAAAAA');
    	global $langs,$conf;
    	if($mes==13){
    		$mm=12;
    		$sql = "SELECT";
    		$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo, SUM(pd.debe) as saldo_debe, SUM(pd.haber) as saldo_haber ";
    		$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		$sql.= " WHERE p.perajuste=1 AND p.mes = ".$mm." AND p.anio = ".$anio." AND ";
    		$sql.= " p.rowid = pd.fk_poliza AND ";
    		$sql.= "  c.fk_sat_cta = ".$id." AND ";
    		$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
    		$sql.= " AND c.entity = p.entity";
    		$sql.= " AND c.entity = ".$conf->entity;
    	}else{
    	$sql = "SELECT";
    	$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo, SUM(pd.debe) as saldo_debe, SUM(pd.haber) as saldo_haber ";
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
    	$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    	$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    	if ($anio > 0 && $mes > 0) {
			$sql.= " WHERE perajuste=0 AND p.mes = ".$mes." AND p.anio = ".$anio." AND ";
    	} else {
    		$sql .= " WHERE 1 AND ";
    	}
    	$sql.= " p.rowid = pd.fk_poliza AND ";
    	$sql.= " c.fk_sat_cta = ".$id." AND ";
    	$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
    	$sql.= " AND c.entity = p.entity";
    	$sql.= " AND c.entity = ".$conf->entity;
    	}
    	//dol_syslog("ESTE22::".$sql);
    	dol_syslog(get_class($this)."::fetch_saldos sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->saldo    = $obj->saldo;
    			$this->saldo_debe = $obj->saldo_debe;
    			$this->saldo_haber = $obj->saldo_haber;
    
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
    		dol_syslog(get_class($this)."::fetch_saldos ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    function fetch_saldos2($id, $anio=0, $mes=0)
    {
    	global $langs,$conf;

    	if($mes==13){
    		$mes=$mes-1;
    		$sql = "SELECT";
    		$sql.= " SUM(pd.haber) - SUM(pd.debe) as saldo, SUM(pd.debe) as saldo_debe, SUM(pd.haber) as saldo_haber ";
    		//$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo, SUM(pd.debe) as saldo_debe, SUM(pd.haber) as saldo_haber ";
    		$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		if ($anio > 0 && $mes > 0) {
    			$sql.= " WHERE p.perajuste=1 AND p.mes = ".$mes." AND p.anio = ".$anio." AND ";
    		} else {
    			$sql .= " WHERE 1 AND ";
    		}
    		$sql.= " p.rowid = pd.fk_poliza AND ";
    		$sql.= " c.fk_sat_cta = ".$id." AND ";
    		$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
    		$sql.= " AND c.entity = p.entity";
    		$sql.= " AND c.entity = ".$conf->entity;
    	}else{
	    	$sql = "SELECT";
	    	$sql.= " SUM(pd.haber) - SUM(pd.debe) as saldo, SUM(pd.debe) as saldo_debe, SUM(pd.haber) as saldo_haber ";
	    	//$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo, SUM(pd.debe) as saldo_debe, SUM(pd.haber) as saldo_haber ";
	    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
	    	$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
	    	$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
	    	if ($anio > 0 && $mes > 0) {
	    		$sql.= " WHERE p.perajuste=0 AND p.mes = ".$mes." AND p.anio = ".$anio." AND ";
	    	} else {
	    		$sql .= " WHERE 1 AND ";
	    	}
	    	$sql.= " p.rowid = pd.fk_poliza AND ";
	    	$sql.= " c.fk_sat_cta = ".$id." AND ";
	    	$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
	    	$sql.= " AND c.entity = p.entity";
	    	$sql.= " AND c.entity = ".$conf->entity;
    	}
    //print $sql."<br>";
    	dol_syslog(get_class($this)."::fetch_saldos sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->saldo    = $obj->saldo;
    			$this->saldo_debe = $obj->saldo_debe;
    			$this->saldo_haber = $obj->saldo_haber;
    
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
    		dol_syslog(get_class($this)."::fetch_saldos ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_saldos_iniciales($id, $anio=0, $mes=0)
    {	
    	global $langs,$conf;
    	
    	if($mes==13){
    		$mm=$mes-1;
    		$sql = "SELECT";
    		$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";
    		$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		if ($anio > 0 && $mm > 0) {
    			$mm = sprintf("%02d", $mm);
    			$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) <= CONCAT('$anio','$mm') AND ";
    		} else {
    			$sql .= " WHERE 1 AND ";
    		}
    		$sql.= " p.rowid = pd.fk_poliza AND ";
    		$sql.= " c.fk_sat_cta = ".$id." AND p.perajuste=0 AND ";
    		$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
    		$sql.= " AND c.entity = ".$conf->entity;
    		$sql.= " AND p.entity = ".$conf->entity;
    	}else{
	    	$sql = "SELECT";
	    	$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";    
	    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
	    	$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
	    	$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
	    	if ($anio > 0 && $mes > 0) {
				$mm = sprintf("%02d", $mes);
				$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) < CONCAT('$anio','$mm') AND ";
	    	} else {
	    		$sql .= " WHERE 1 AND ";
	    	}
	    	$sql.= " p.rowid = pd.fk_poliza AND ";
	    	$sql.= " c.fk_sat_cta = ".$id." AND";
	    	$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
	    	$sql.= " AND c.entity = ".$conf->entity;
	    	$sql.= " AND p.entity = ".$conf->entity;
    	}
    	dol_syslog(get_class($this)."::fetch_saldos_iniciales sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->saldo    = $obj->saldo;
    
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
    		dol_syslog(get_class($this)."::fetch_saldos_iniciales ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    function fetch_saldos_iniciales4($id, $anio=0, $mes=0)
    {
    	global $langs,$conf;
    	 
    	if($mes==13){
    		$mm=$mes-1;
    		$sql = "SELECT";
    		$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";
    		$sql.= " FROM ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		if ($anio > 0 && $mm > 0) {
    			$mm = sprintf("%02d", $mm);
    			$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) <= CONCAT('$anio','$mm') AND ";
    		} else {
    			$sql .= " WHERE 1 AND ";
    		}
    		$sql.= " p.rowid = pd.fk_poliza AND ";
    		$sql.= " p.perajuste=0 AND ";
    		$sql.= " pd.cuenta = '$id' ";
    		$sql.= " AND p.entity = ".$conf->entity;
    	}else{
    		$sql = "SELECT";
    		$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";
    		$sql.= " FROM ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		if ($anio > 0 && $mes > 0) {
    			$mm = sprintf("%02d", $mes);
    			$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) < CONCAT('$anio','$mm') AND ";
    		} else {
    			$sql .= " WHERE 1 AND ";
    		}
    		/*if($mm==12){
    			$sql.= " p.rowid = pd.fk_poliza  AND p.perajuste=0 AND ";
    		}else{
    			$sql.= " p.rowid = pd.fk_poliza  AND ";
    		}*/

            $sql.= " p.rowid = pd.fk_poliza  AND ";

    		$sql.= " pd.cuenta = '$id' ";
    		$sql.= " AND p.entity = ".$conf->entity;
    	}
    	//print $sql."<br>";
    	dol_syslog(get_class($this)."::fetch_saldos_iniciales sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->saldo    = $obj->saldo;
    
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
    		dol_syslog(get_class($this)."::fetch_saldos_iniciales ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    function fetch_saldos_iniciales2($id, $anio=0, $mes=0)
    {
    	global $langs,$conf;
    	if($mes==13){
    		$mes=$mes-1;
    		$sql = "SELECT";
    		$sql.= " SUM(pd.haber) - SUM(pd.debe) as saldo ";
    		//$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";
    		$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		if ($anio > 0 && $mes > 0) {
    			$mm = sprintf("%02d", $mes);
    			$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) <= CONCAT('$anio','$mm') AND ";
    		} else {
    			$sql .= " WHERE 1 AND ";
    		}
    		$sql.= " p.perajuste=0 AND p.rowid = pd.fk_poliza AND ";
    		$sql.= " c.fk_sat_cta = ".$id." AND ";
    		$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
    		$sql.= " AND c.entity = ".$conf->entity;
    		$sql.= " AND p.entity = ".$conf->entity;
    	}else{
	    	$sql = "SELECT";
	    	$sql.= " SUM(pd.haber) - SUM(pd.debe) as saldo ";
	    	//$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";
	    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c, ";
	    	$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
	    	$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
	    	if ($anio > 0 && $mes > 0) {
	    		$mm = sprintf("%02d", $mes);
	    		$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) < CONCAT('$anio','$mm') AND ";
	    	} else {
	    		$sql .= " WHERE 1 AND ";
	    	}
	    	$sql.= " p.rowid = pd.fk_poliza AND ";
	    	$sql.= " c.fk_sat_cta = ".$id." AND ";
	    	$sql.= " if(Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) > 0, LEFT(pd.cuenta, Locate('.', pd.cuenta, Locate('.', pd.cuenta) + 1) - 1), pd.cuenta) = c.cta ";
	    	$sql.= " AND c.entity = ".$conf->entity;
	    	$sql.= " AND p.entity = ".$conf->entity;
    	}
    	 
    	dol_syslog(get_class($this)."::fetch_saldos_iniciales sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->saldo    = $obj->saldo;
    
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
    		dol_syslog(get_class($this)."::fetch_saldos_iniciales ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_saldos_iniciales3($id, $anio=0, $mes=0)
    {
    	global $langs,$conf;
    	 
    	if($mes==13){
    		$mm=$mes-1;
    		$sql = "SELECT";
    		$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";
    		$sql.= " FROM  ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		if ($anio > 0 && $mm > 0) {
    			$mm = sprintf("%02d", $mm);
    			$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) <= CONCAT('$anio','$mm') AND ";
    		} else {
    			$sql .= " WHERE 1 AND ";
    		}
    		$sql.= " p.rowid = pd.fk_poliza AND ";
    		$sql.= " p.perajuste=0 AND ";
    		$sql.= " pd.cuenta LIKE '$id%' ";
    		$sql.= " AND p.entity = ".$conf->entity;
    	}else{
    		$sql = "SELECT";
    		$sql.= " SUM(pd.debe) - SUM(pd.haber) as saldo ";
    		$sql.= " FROM ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizas p, ";
    		$sql.= " ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    		if ($anio > 0 && $mes > 0) {
    			$mm = sprintf("%02d", $mes);
    			$sql.= " WHERE CONCAT(p.anio,LPAD(p.mes,2,'0')) < CONCAT('$anio','$mm') AND ";
    		} else {
    			$sql .= " WHERE 1 AND ";
    		}
    		/*if($mm==12){
    			$sql.= " p.perajuste=0 AND p.rowid = pd.fk_poliza AND ";
    		}else{
    			$sql.= " p.rowid = pd.fk_poliza AND ";
    		}*/
            $sql.= " p.rowid = pd.fk_poliza AND ";

    		$sql.= " pd.cuenta LIKE '$id%' ";
    		$sql.= " AND p.entity = ".$conf->entity;
    	}
        /*print '<pre>';
       	print $sql."<br>";
        print '</pre>';*/
    	dol_syslog(get_class($this)."::fetch_saldos_iniciales sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->saldo    = $obj->saldo;
    
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
    		dol_syslog(get_class($this)."::fetch_saldos_iniciales ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch2($id)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
	    $sql.= " t.rowid,";
	    
	    $sql.= " t.cta,";
	    $sql.= " t.descta,";
	    $sql.= " t.fk_sat_cta,";
	    $sql.= " t.subctade, ";
	    $sql.= " IFNULL(s.codagr,'') as codagr, ";
	    $sql.= " IFNULL(d.cta, '') as cta_subctade, ";
	    $sql.= " IFNULL(s.nivel, -1) as nivel, ";
	    $sql.= " IFNULL(s.natur, -1) as natur ";
	    
	    $sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas as t ";
	    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."contab_sat_ctas as s ";
	    $sql.= " ON t.fk_sat_cta = s.rowid ";
	    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."contab_cat_ctas d ";
	    $sql.= " ON t.subctade = d.rowid ";
	    $sql.= " WHERE t.rowid = ".$id;
	    $sql.= " AND t.entity = ".$conf->entity;
    	//print $sql."<br>";
    	dol_syslog(get_class($this)."::fetch2 sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->cta = $obj->cta;
    			$this->descta = $obj->descta;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->subctade = $obj->subctade;
    			$this->cta_subctade = $obj->cta_subctade;
    			
    			$this->codagr = $obj->codagr;
    			$this->cta_subctade = $obj->cta_subctade;
    			$this->nivel = $obj->nivel;
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
    		dol_syslog(get_class($this)."::fetch2 ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_by_Cta($cta, $exact = false)
    {
    	global $langs,$conf;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.cta,";
    	$sql.= " t.descta,";
    	$sql.= " t.fk_sat_cta,";
    	$sql.= " t.subctade";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas as t";
    	//$sql.= " WHERE t.cta = '".$cta."'";
    	if ($exact) {
    		$sql.= " WHERE t.cta = '$cta' AND t.entity = ".$conf->entity;
    	} else {
	    	//$sql.= " WHERE if(Locate('.', '$cta', Locate('.', '$cta') + 1) > 0, LEFT('$cta', Locate('.', '$cta', Locate('.', '$cta') + 1) - 1), '$cta') = t.cta ";
	    	/* $sql .= " WHERE
						CASE WHEN Locate('.', '$cta', Locate('.', '$cta', Locate('.', '$cta') + 1) + 1) = 0 THEN 
								'$cta' = t.cta 
							WHEN Locate('.', '$cta', Locate('.', '$cta', Locate('.', '$cta') + 1) + 1) > 0 AND Locate('.', '$cta', Locate('.', '$cta') + 1) > 0 THEN 
								LEFT('$cta', Locate('.', '$cta', Locate('.', '$cta', Locate('.', '$cta') + 1) + 1) - 1) = t.cta 
							ELSE 
								CASE WHEN Locate('.', '$cta', Locate('.', '$cta') + 1) = 0 THEN 
									'$cta' = t.cta 
								WHEN Locate('.', '$cta', Locate('.', '$cta') + 1) > 0 THEN 
									LEFT('$cta', Locate('.', '$cta', Locate('.', '$cta') + 1) - 1) = t.cta 
								ELSE 
									'$cta' = t.cta 
								END
						END"; */
    		$sql .= " WHERE t.cta = '$cta' OR LEFT('$cta', LENGTH(t.cta)) = t.cta AND t.entity = ".$conf->entity." ORDER BY t.cta DESC LIMIT 1 ";
    		//MV//$sql .= " WHERE INSTR(t.cta, '$cta') > 0 OR LEFT('$cta', LENGTH(t.cta)) = t.cta AND t.entity = ".$conf->entity." ORDER BY t.cta DESC LIMIT 1 ";
    	}
    	//$sql.= " AND entity = ".$conf->entity;
    	//print $sql;
    	dol_syslog(get_class($this)."::fetch_by_Cta sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->cta = $obj->cta;
    			$this->descta = $obj->descta;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->subctade = $obj->subctade;
    			
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
    		dol_syslog(get_class($this)."::fetch_by_Cta ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_by_CodAgr($codagr)
    {
    	global $langs,$conf;
    	$sql = "SELECT * ";
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c ";
    	$sql.= " INNER JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s ";
    	$sql.= " ON c.fk_sat_cta = s.rowid ";
    	$sql.= " WHERE codagr = '".$codagr."'";
    	$sql.= " AND entity = ".$conf->entity;
    	//print $sql;
    	dol_syslog(get_class($this)."::fetch_by_CodAgr sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->cta = $obj->cta;
    			$this->descta = $obj->descta;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->subctade = $obj->subctade;
    			
    			$this->db->free($resql);
    			
    			return 1;
    		} 
    		else 
    		{
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
    
    function fetch_by_CodAgr2($codagr)
    {
    	global $langs,$conf;
    	$sql = "SELECT * ";
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c ";
    	$sql.= " INNER JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s ";
    	$sql.= " ON c.cta = s.codagr ";
    	$sql.= " WHERE codagr = '".$codagr."'";
    	$sql.= " AND entity = ".$conf->entity;
    	//print $sql;
    	dol_syslog(get_class($this)."::fetch_by_CodAgr sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->cta = $obj->cta;
    			$this->descta = $obj->descta;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->subctade = $obj->subctade;
    			 
    			$this->db->free($resql);
    			 
    			return 1;
    		}
    		else
    		{
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
    
    function fetch_next($id=0, $cond='')	//,$anivel=2
    {
    	global $langs,$conf;
    	$sql = "SELECT c.rowid, c.cta, c.descta, c.fk_sat_cta, c.subctade, s.rowid as s_rowid,natur ";
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c ";
    	$sql .= " INNER JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s ";
    	$sql .= " ON c.fk_sat_cta = s.rowid ";
    	
    	if ($id == 0) {
    		//Que traiga el primer registro
    		if ($cond) {
    			$sql .= " WHERE 1 AND ".$cond. " ";
    		}
    	} else {
    		$sql.= " WHERE s.rowid > ".$id;
    		if ($cond) {
    			$sql .= " AND ".$cond. " ";
    		}
    	}
    	$sql .= " AND c.cta = s.codagr ";
    	$sql .= " AND c.entity = ".$conf->entity;
    	$sql .= " ORDER BY s.rowid LIMIT 1";
    
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->cta = $obj->cta;
    			$this->descta = $obj->descta;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->subctade = $obj->subctade;
    			//$this->codagr = $obj->codagr;
    			$this->s_rowid = $obj->s_rowid;
    
    			$this->db->free($resql);
    
    			return 1;
    		}
    		else
    		{
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
    
    function fetch_next2($id=0, $cond='')	//,$anivel=2
    {
    	global $langs,$conf;
    	$sql = "SELECT c.rowid, c.cta, c.descta, c.fk_sat_cta, c.subctade, s.rowid as s_rowid,natur ";
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c ";
    	$sql .= " INNER JOIN ".MAIN_DB_PREFIX."contab_sat_ctas s ";
    	$sql .= " ON c.fk_sat_cta = s.rowid ";
    	 
    	if ($id == 0) {
    		//Que traiga el primer registro
    		if ($cond) {
    			$sql .= " WHERE 1 AND ".$cond. " ";
    		}
    	} else {
    		$sql.= " WHERE s.rowid > ".$id;
    		if ($cond) {
    			$sql .= " AND ".$cond. " ";
    		}
    	}
    	//$sql .= " AND c.cta = s.codagr ";
    	$sql .= " AND c.entity = ".$conf->entity;
    	$sql .= " ORDER BY s.rowid LIMIT 1";
    
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->cta = $obj->cta;
    			$this->descta = $obj->descta;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->subctade = $obj->subctade;
    			//$this->codagr = $obj->codagr;
    			$this->s_rowid = $obj->s_rowid;
    
    			$this->db->free($resql);
    
    			return 1;
    		}
    		else
    		{
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
    
    // $recno 	= 0 => Toma el primer registro
    // 			= 1 => Toma el registro que sigue.
    
    // $anivel	= 0 => Toma todas las cuentas.
    //			= 1 => Toma las cuentas de nivel = 1
    //			= 2 => Toma las cuentas de nivel >= 1
    function fetch_next_cuenta($recno=0)	//,$anivel=2
    {
    	global $langs,$conf;
    	$sql = "SELECT * ";
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_cat_ctas c ";
    	
    	if ($recno == 0) {
    		//Que traiga el primer registro
    		$sql.= " WHERE 1 ";
    	} else if ($recno == 1) {
    		$sql.= " WHERE cta > '".$this->cta."'";
    	} else if ($recno == 2) {
    		$sql .= " WHERE cta = '".$this->cta."'";
    	}
    	$sql.= " AND entity = ".$conf->entity;
    	//$sql.= " ORDER BY codagr LIMIT 1";
    	$sql.= " ORDER BY cta LIMIT 1";
    
    	dol_syslog(get_class($this)."::fetch_next_cuenta sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->cta = $obj->cta;
    			$this->descta = $obj->descta;
    			$this->fk_sat_cta = $obj->fk_sat_cta;
    			$this->subctade = $obj->subctade;
    			//$this->codagr = $obj->codagr;
    			 
    			$this->db->free($resql);
    			 
    			return 1;
    		}
    		else
    		{
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next_cuenta ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
	function fetch_array() {
    	
		global $conf;
    	$sql = "Select * From ".MAIN_DB_PREFIX."contab_cat_ctas";
    	$sql.= " WHERE 1 "; 
    	$sql.= " AND entity = ".$conf->entity;
    	$a = array();
    	
    	dol_syslog(get_class($this)."::fetch_array sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($nr = $this->db->num_rows($resql))
    		{
    			$jj = 0;
    			while ($jj < $nr) {
    				
    				$obj = $this->db->fetch_object($resql);
    				
    				$a[] = $obj;
    				
    				$jj = $jj + 1;
    			}
    			
    			$this->db->free($resql);
    			return $a;
    		}
    		else
    		{
    			return $a;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_array ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_array_by_dependede($sctade=0, $a=array()) {
    	
    	global $conf;
    	$sql = "Select rowid From ".MAIN_DB_PREFIX."contab_cat_ctas c ";
    	$sql .= "WHERE subctade = ".$sctade;
    	$sql.= " AND entity = ".$conf->entity;
    	$sql .= " ORDER BY cta ";
    	//print $sql."<br>";
    	dol_syslog(get_class($this)."::fetch_array_by_dependede sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($nr = $this->db->num_rows($resql))
    		{
    			$jj = 0;
    			while ($jj < $nr) {
    				
    				$obj = $this->db->fetch_object($resql);
    				
	    			$this->id    = $obj->rowid;
//     				$this->cta = $obj->cta;
//     				$this->descta = $obj->descta;
//     				$this->fk_sat_cta = $obj->fk_sat_cta;
//     				$this->subctade = $obj->subctade;
    				
    				$a[] = $this->id;
    				
    				//dol_syslog("DEPENDIENTE ::: jj=$jj, nr=$nr, id=".$this->id." ".$this->cta." ".$this->descta);
    				
    				$a = $this->fetch_array_by_dependede($this->id, $a);
    				
    				$jj = $jj + 1;
    			}
    			
    			$this->db->free($resql);
    			return $a;
    		}
    		else
    		{
    			return $a;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_array_by_dependede ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_array_by_dependede2($cuenta) {
    	 
    	global $conf;
    	$sql = "Select rowid From ".MAIN_DB_PREFIX."contab_cat_ctas c ";
    	$sql .= "WHERE (cta LIKE '%".$cuenta."%' or descta LIKE '%".$cuenta."%')";
    	$sql.= " AND entity = ".$conf->entity;
    	$sql .= " ORDER BY cta ";
    	//print $sql."<br>";
    	dol_syslog(get_class($this)."::fetch_array_by_dependede sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($nr = $this->db->num_rows($resql))
    		{
    			$jj = 0;
    			while ($obj = $this->db->fetch_object($resql)) {
    
    				
    
    				$this->id    = $obj->rowid;

    				$a[] = $this->id;

    				//$a = $this->fetch_array_by_dependede($this->id, $a);
    
    				$jj = $jj + 1;
    			}
    			 
    			$this->db->free($resql);
    			return $a;
    		}
    		else
    		{
    			return $a;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_array_by_dependede ".$this->error, LOG_ERR);
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
        
		if (isset($this->cta)) $this->cta=trim($this->cta);
		if (isset($this->descta)) $this->descta=trim($this->descta);
		if (isset($this->fk_sat_cta)) $this->fk_sat_cta=trim($this->fk_sat_cta);
		if (isset($this->subctade)) $this->subctade=trim($this->subctade);

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_cat_ctas SET";
        
		$sql.= " cta=".(isset($this->cta)?"'".$this->db->escape($this->cta)."'":"null").",";
		$sql.= " descta=".(isset($this->descta)?"'".$this->db->escape($this->descta)."'":"null").",";
		$sql.= " fk_sat_cta=".(isset($this->fk_sat_cta)?$this->fk_sat_cta:"null").",";
		$sql.= " subctade=".(isset($this->subctade)?"'".$this->db->escape($this->subctade)."'":"null")."";
        
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
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_cat_ctas";
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
			$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_cat_ctas ";
			$sql.= "WHERE 1 ";
			//$sql.= " WHERE rowid=".$this->id;
			$sql.= " AND entity = ".$conf->entity;
	
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
		dol_syslog("Inicia la reindexación de datos");
		$resql = $this->db->query("SELECT * FROM ".MAIN_DB_PREFIX."contab_cat_ctas ORDER BY entity, rowid");
		if($resql){
			dol_syslog("Se crea la tabla temporal");
			//Se crea la tabla temporal de encabezados y detalle
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_cat_ctas_tmp LIKE ".MAIN_DB_PREFIX."contab_cat_ctas");
			
			$p = new Contabcatctas($this->db);

			dol_syslog("Se pasa toda la información a la tabla temporal");
			// Colocar todo el contenido de la tabla Temporal a la tabla original
			$this->db->query("INSERT INTO ".MAIN_DB_PREFIX."contab_cat_ctas_tmp (entity, cta, descta, fk_sat_cta, subctade, import_key) SELECT entity, cta, descta, fk_sat_cta, subctade, '' FROM ".MAIN_DB_PREFIX."contab_cat_ctas ORDER BY entity, rowid");
				
			dol_syslog("Se borra la tabla principal");
			//eliminamos la tabla principal
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_cat_ctas");
				
			dol_syslog("Se crea la tabla principal en base a la temporal");
			//Se crea la tabla original de encabezados y detalles
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_cat_ctas LIKE ".MAIN_DB_PREFIX."contab_cat_ctas_tmp");
				
			dol_syslog("Se pasa toda la información de la temporal a la principal");
			//Movemos las cuentas a la tabla principal
			$this->db->query("INSERT INTO ".MAIN_DB_PREFIX."contab_cat_ctas (entity, cta, descta, fk_sat_cta, subctade, import_key) SELECT entity, cta, descta, fk_sat_cta, subctade, '' FROM ".MAIN_DB_PREFIX."contab_cat_ctas_tmp ORDER BY entity, rowid");
			
			dol_syslog("Se borra la temporal");
			//eliminamos la tabla temporal
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_cat_ctas_tmp");
			
			$this->update_depende_de();
		}
	}
	
	function update_depende_de() {
		$sql = "SELECT min(rowid) - 1 as minid, entity FROM ".MAIN_DB_PREFIX."contab_cat_ctas WHERE entity > 1 GROUP BY entity";
		if ($res = $this->db->query($sql)) {
			while ($obj = $this->db->fetch_object($res)) {
				$minid = $obj->minid;
				$ent = $obj->entity;
				$sql = "UPDATE ".MAIN_DB_PREFIX."contab_cat_ctas SET subctade = subctade + ".$minid." WHERE subctade > 0 AND entity = ".$ent;
				dol_syslog(get_class($this)."::update_depende_de - sql = ".$sql);
				$this->db->query($sql);
			}
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

		$object=new Contabcatctas($this->db);

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
		$this->cta='';
		$this->descta='';
		$this->fk_sat_cta=0;
		$this->subctade=0;
	}

}

