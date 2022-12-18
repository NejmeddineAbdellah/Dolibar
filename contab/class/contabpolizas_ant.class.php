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
 *  \file       dev/skeletons/contabpolizas.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-02-27 15:50
 */

date_default_timezone_set("America/Mexico_City");

// Put here all includes required by your class file
if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabrelctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabrelctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabrelctas.class.php';
}

/* if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpolizas.class.php';
} */

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpolizasdet.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/facture.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpaymentterm.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/paiment.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/paiment.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/paiment.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/fournisseur.facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/fournisseur.facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/fournisseur.facture.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/paimentfourn.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/paimentfourn.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/paimentfourn.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabctassupplier.class.php';
}

require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 *	Put here description of your class
 */
class Contabpolizas extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='contabpolizas';			//!< Id that identify managed objects
	var $table_element='contabpolizas';		//!< Name of table without prefix where object is stored

    var $id;
    
    var $tipo_pol;
    var $cons;
    var $anio;
    var $mes;
	var $fecha='';
	var $concepto;
	var $comentario;
	
	var $anombrede;
	var $numcheque;
	
	var $fk_facture;
	
	var $ant_ctes;
	var $fechahora;
	
	var $societe_type;

    var $line;
    var $lines = array();
    
    var $debe_total;
    var $haber_total;
    
    //var $periodo_anio;
    //var $periodo_mes;
    
    const POLIZA_DE_DIARIO = "D";
    const POLIZA_DE_INGRESO = "I";
    const POLIZA_DE_EGRESO = "E";
    const POLIZA_DE_CHEQUES = "C";

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
    function create($user, $notrigger=0, $tbl = 'contab_polizas')
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->tipo_pol)) $this->tipo_pol=trim($this->tipo_pol);
		if (isset($this->cons)) $this->cons=trim($this->cons);
		if (isset($this->anio)) $this->anio=trim($this->anio);
		if (isset($this->mes)) $this->mes=trim($this->mes);
		if (isset($this->concepto)) $this->concepto=trim($this->concepto);
		if (isset($this->comentario)) $this->comentario=trim($this->comentario);
		if (isset($this->anombrede)) $this->anombrede=trim($this->anombrede);
		if (isset($this->numcheque)) $this->numcheque=trim($this->numcheque);
		if (isset($this->fk_facture)) $this->fk_facture=trim($this->fk_facture);
		if (isset($this->ant_ctes)) $this->ant_ctes=trim($this->ant_ctes);
		if (isset($this->societe_type)) $this->societe_type=trim($this->societe_type);

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX.$tbl." (";
		
		$sql.= "tipo_pol,";
		$sql.= "cons,";
		$sql.= "anio,";
		$sql.= "mes,";
		$sql.= "fecha,";
		$sql.= "concepto,";
		$sql.= "comentario,";
		$sql.= "anombrede,";
		$sql.= "numcheque,";
		$sql.= "fk_facture,";
		$sql.= "ant_ctes,";
		$sql.= "societe_type,";
		$sql.= "fechahora";
		
		if (isset($this->fechahora) && $this->fechahora) {
			//var_dump("Parece que si está activa: ".(isset($this->fechahora) ? "esta activa:".$this->fechahora : "no esta activa"));
		} else {
			$this->fechahora = time();
		}
		
        $sql.= ") VALUES (";
        
        $sql.= " ".(! isset($this->tipo_pol)?'NULL':"'".$this->db->escape($this->tipo_pol)."'").",";
        $sql.= " ".(! isset($this->cons)?'NULL':"'".$this->cons."'").",";
        $sql.= " ".(! isset($this->anio)?'NULL':"'".$this->anio."'").",";
        $sql.= " ".(! isset($this->mes)?'NULL':"'".$this->mes."'").",";
		$sql.= " ".(! isset($this->fecha) || dol_strlen($this->fecha)==0?'NULL':"'".$this->fecha."'").",";
		$sql.= " ".(! isset($this->concepto)?'NULL':"'".$this->db->escape($this->concepto)."'").",";
		$sql.= " ".(! isset($this->comentario)?'NULL':"'".$this->db->escape($this->comentario)."'").",";
		$sql.= " ".(! isset($this->anombrede)?'NULL':"'".$this->db->escape($this->anombrede)."'").",";
		$sql.= " ".(! isset($this->numcheque)?'NULL':"'".$this->db->escape($this->numcheque)."'").",";
		$sql.= " ".(! isset($this->fk_facture)?'NULL':"'".$this->fk_facture."'").",";
		$sql.= " ".(! isset($this->ant_ctes)?'NULL':"'".$this->ant_ctes."'").",";
		$sql.= " ".(! isset($this->societe_type)?'NULL':"'".$this->societe_type."'");
		$sql.= " ".(! isset($this->fechahora)?'':",'".date("Y-m-d H:m:s",$this->fechahora)."'");
        
		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."contab_polizas");
            
            if ($tbl != 'contab_polizas') {
				$lines = $this->lines;
				foreach ($lines as $i => $l)
				{
					dol_syslog("asiento=".$l->asiento." cuenta=".$l->cuenta." debe=".$l->debe." haber=".$l->haber);
					
					//$result = $this->addline_tmp($lines[$i]->asiento, $lines[$i]->cuenta, $lines[$i]->debe, $lines[$i]->haber);
					
					$poldet = new Contabpolizasdet($this->db);
					
					$poldet->asiento = $l->asiento;
					$poldet->cuenta = $l->cuenta;
					$poldet->debe = $l->debe;
					$poldet->haber = $l->haber;
					$poldet->fk_poliza = $this->id;
					
					$poldet->create($user, 0, 'contab_polizasdet_tmp');
					
					if ($result < 0)
					{
						$this->error=$this->db->lasterror();
						dol_print_error($this->db);
						$this->db->rollback();
						return -1;
					}
				}
			} 
			
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
    
    function create_next() {
    	$tp = $this->tipo_pol;
    	$cons = $this->cons;
    	$anio =$this->anio;
    	$mes = $this->mes;
    	$fecha = $this->fecha;
    	$concepto = $this->concepto;
    	$com = $this->comentario;
    	$anombre = $this->anombrede;
    	$numch = $this->numcheque;
    	$fk_facture = $this->fk_facture;
    	$soc_type = $this->societe_type;
    	
    	$r = $this->fetch_last_by_tipo_pol($tp);
    	
    	$cons = 0;
    	if ($this->cons > 0) {
    		$cons = $this->cons;
    	}
    	
    	$cons ++;
    	 
    	$this->initAsSpecimen();
    	$this->tipo_pol = $tp;
    	$this->cons = $cons;
    	
    	$this->anio=$anio;
    	$this->mes=$mes;
    	$this->fecha=$fecha;
    	$this->concepto=$concepto;
    	$this->comentario=$com;
    	$this->anombrede=$anombre;
    	$this->numcheque=$numch;
    	$this->fk_facture=$fk_facture;
    	$this->societe_type=$soc_type;
    	
    	if ($this->create($user)) {
    		
    		$det = new Contabpolizasdet($this->db);
    		$det->initAsSpecimen();
    		
    		$det->fk_poliza = $this->id;
    		$det->asiento = 1;
    		
    		if ($det->create($user)) {
    			return 1;
    		} else {
    			return -6;
    		}
    	} else {
    		return -4;
    	}
    }
    
    function addline($asiento, $cuenta, $debe, $haber) {
    	dol_syslog(get_class($this)."::addline polid=$this->id, asiento=$asiento, cuenta=$cuenta, debe=$debe, haber=$haber", LOG_DEBUG);
    	
    	// Clean parameters
		if (empty($asiento)) $asiento="";
		if (empty($cuenta)) $cuenta="";
		if (empty($debe)) $debe=0;
		if (empty($haber)) $haber=0;
		
		// Insert line
		$this->line=new Contabpolizasdet($this->db);
		$this->line->asiento = $asiento;
		$this->line->cuenta = $cuenta;
		$this->line->debe = $debe;
		$this->line->haber = $haber;
		$this->line->fk_poliza = $this->id;
		
		$result=$this->line->insert();
		if ($result > 0)
		{
			$this->db->commit();
			return $this->line->rowid;
		}
		else
		{
			$this->error=$this->line->error;
			$this->db->rollback();
			return -2;
		}
    }
    
    function addline_tmp($asiento, $cuenta, $debe, $haber) {
    	dol_syslog(get_class($this)."::addline polid=$this->id, asiento=$asiento, cuenta=$cuenta, debe=$debe, haber=$haber", LOG_DEBUG);
    	 
    	// Clean parameters
    	if (empty($asiento)) $asiento="";
    	if (empty($cuenta)) $cuenta="";
    	if (empty($debe)) $debe=0;
    	if (empty($haber)) $haber=0;
    
    	// Insert line
    	$this->line=new Contabpolizasdet($this->db);
    	$this->line->asiento = $asiento;
    	$this->line->cuenta = $cuenta;
    	$this->line->debe = $debe;
    	$this->line->haber = $haber;
    	$this->line->fk_poliza = $this->id;
    
    	$result=$this->line->insert();
    	if ($result > 0)
    	{
    		$this->db->commit();
    		return $this->line->rowid;
    	}
    	else
    	{
    		$this->error=$this->line->error;
    		$this->db->rollback();
    		return -2;
    	}
    }

    /**
     *  Load object in memory from the database
     *
     *  @param	int		$id    Id object
     *  @return int          	<0 if KO, >0 if OK
     */
    function fetch($id, $use_period=1)
    {
    	global $langs;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.tipo_pol,";
		$sql.= " t.cons,";
		$sql.= " t.anio,";
		$sql.= " t.mes,";
		$sql.= " t.fecha,";
		$sql.= " t.concepto,";
		$sql.= " t.comentario,";
		$sql.= " t.anombrede,";
		$sql.= " t.numcheque,";
		$sql.= " t.fk_facture,";
		$sql.= " t.ant_ctes,";
		$sql.= " t.fechahora,";
		$sql.= "societe_type";
		
        $sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas as t";
        $sql.= " WHERE t.rowid = ".$id;
        if ($use_period == 1) {
        	$sql .= " AND mes = ".$this->periodo_mes." AND anio = ".$this->periodo_anio;
        }

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql) > 0)
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
                $this->tipo_pol = $obj->tipo_pol;
                $this->cons = $obj->cons;
                $this->anio = $obj->anio;
                $this->mes = $obj->mes;
                
				$this->fecha = $this->db->jdate($obj->fecha);
				$this->concepto = $obj->concepto;
				$this->comentario = $obj->comentario;
				$this->anombrede = $obj->anombrede;
				$this->numcheque = $obj->numcheque;
				$this->fk_facture = $obj->fk_facture;
				$this->ant_ctes = $obj->ant_ctes;
				$this->fechahora = $this->db->jdate($obj->fechahora);
				$this->societe_type = $obj->societe_type;
            }
            
            $this->lines  = array();
            
            //$result=$this->fetch_lines();
            if ($result < 0)
            {
            	$this->error=$this->db->error();
            	dol_syslog(get_class($this)."::fetch Error ".$this->error, LOG_ERR);
            	return -3;
            }
            
            return 1;
        }
        else
        {
      	    $this->error="Error ".$this->db->lasterror();
            dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
            return -1;
        }
    }
    
//     function fetch_polizas_det_ctas_terceros($id)
//     {
//     	if ($this->periodo_anio == 0 || $this->periodo_mes == 0) {
//     		dol_syslog(get_class($this)."::fetch - Error en los datos del Periodo.  El anio y/o el mes están informando un valor de cero (0)", LOG_DEBUG);
//     		return -1;
//     	}
//     	global $langs;

//     	$sql = "SELECT";
// 	    $sql.= " t.rowid,";
	    
// 	    $sql.= " t.tipo_pol,";
// 	    $sql.= " t.cons,";
// 	    $sql.= " IFNULL(td.asiento,0) asiento,";
// 	    $sql.= " IFNULL(td.cuenta,0) cuenta,";
// 	    $sql.= " IFNULL(c.descta, IFNULL(s1.nom, IFNULL(s2.nom, '' ))) descta_nomsoc, ";   
// 	    $sql.= " t.fecha,";
// 	    $sql.= " IFNULL(td.debe,0) debe,";
// 	    $sql.= " IFNULL(td.haber,0) haber,";
// 	    $sql.= " t.concepto,";
// 	    $sql.= " t.comentario,";
// 	    $sql.= " t.anombrede,";
// 	    $sql.= " t.numcheque,";
// 	    $sql.= " t.fechahora,";
// 	    $sql.= " IFNULL(t.fk_facture, 0) fk_facture,";
// 	    $sql.= " IFNULL(td.fk_poliza,t.rowid) fk_poliza, ";
// 	    $sql.= " IFNULL(td.rowid,0) poldetid, ";
// 	    $sql.= " IFNULL(f.facnumber, '') facnumber, ";
// 	    $sql.= " IFNULL(s1.client, 0) client, IFNULL(s2.fournisseur, 0) fournisseur ";
	    
// 	    $sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas as t ";
// 	    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."contab_polizasdet as td ";
// 	    $sql.= " ON t.rowid = td.fk_poliza ";
// 	    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."contab_cat_ctas c ";
// 	    $sql.= " ON td.cuenta = c.cta ";
// 	    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe s1 ";
// 	    $sql.= " ON td.cuenta = s1.code_compta  ";
// 	    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."societe s2 ";
// 	    $sql.= " ON td.cuenta = s2.code_compta_fournisseur   ";
// 	    $sql.= " LEFT JOIN ".MAIN_DB_PREFIX."facture f ";
// 	    $sql.= " ON t.fk_facture = f.rowid ";
// 	    if ($action == "filterfac" && $ref) {
// 			$sql .= " WHERE t.fk_facture = '".$ref."'";
// 		} else {
// 			$sql .= " WHERE 1 ";
// 		}
// 		$sql.= " AND t.anio = ".$cfg->anio." AND t.mes = ".$cfg->mes;
// 	    $sql.= " ORDER BY t.tipo_pol, t.cons, asiento ";
// 	    if ($ini > 0) {
// 	    	$sql .= " Limit ".$ini;
// 	    	if ($cant > 0)  {
// 				$sql .= ", ".$cant;
// 	    	}
// 	    }
    
//     	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
//     	$resql=$this->db->query($sql);
//     	if ($resql)
//     	{
//     		if ($this->db->num_rows($resql) > 0)
//     		{
//     			$obj = $this->db->fetch_object($resql);
    
//     			$this->id    = $obj->rowid;
    
//     			$this->tipo_pol = $obj->tipo_pol;
//     			$this->cons = $obj->cons;
//     			$this->fecha = $this->db->jdate($obj->fecha);
//     			$this->concepto = $obj->concepto;
//     			$this->comentario = $obj->comentario;
//     			$this->anombrede = $obj->anombrede;
//     			$this->numcheque = $obj->numcheque;
//     			$this->fk_facture = $obj->fk_facture;
//     			$this->ant_ctes = $obj->ant_ctes;
//     			$this->fechahora = $this->db->jdate($obj->fechahora);
    			
//     			$this->asiento
//     			$this->cuenta
//     			$this->debe
//     			$this->haber
//     			$this->poldtid
//     		}
    
//     		$this->lines  = array();
    
//     		$result=$this->fetch_lines();
//     		if ($result < 0)
//     		{
//     			$this->error=$this->db->error();
//     			dol_syslog(get_class($this)."::fetch Error ".$this->error, LOG_ERR);
//     			return -3;
//     		}
    
//     		return 1;
//     	}
//     	else
//     	{
//     		$this->error="Error ".$this->db->lasterror();
//     		dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
//     		return -1;
//     	}
//     }
    
    function fetch_next($id = 0, $periodo=0)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.tipo_pol,";
    	$sql.= " t.cons,";
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.fecha,";
    	$sql.= " t.concepto,";
    	$sql.= " t.comentario,";
    	$sql.= " t.anombrede,";
    	$sql.= " t.numcheque,";
    	$sql.= " t.fk_facture,";
    	$sql.= " t.ant_ctes,";
    	$sql.= " t.fechahora,";
		$sql.= "societe_type";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id;
    	}
    	if ($periodo == 1) {
    		if ($this->anio > 0) {
    			$sql .= " AND anio = ".$this->anio;
    		}
    		if ($this->mes > 0) {
    			$sql .= " AND mes = ".$this->mes;
    		}
    	}
    	//$sql.= " AND t.mes = ".$this->periodo_mes." AND t.anio = ".$this->periodo_anio;
    	$sql.= " ORDER BY t.rowid ASC LIMIT 1";
    	
    	dol_syslog(get_class($this)."::fetch_next sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$num_rows = $this->db->num_rows($resql);
    		//dol_syslog("Numero de Registros: $num_rows");
    		if ($num_rows > 0)
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
    			$this->numcheque = $obj->numcheque;
    			$this->fk_facture = $obj->fk_facture;
    			$this->ant_ctes = $obj->ant_ctes;
    			$this->fechahora = $this->db->jdate($obj->fechahora);
    			$this->societe_type = $obj->societe_type;
    			
	    		$this->lines  = array();
    	
    			//$result = $this->fetch_lines();
    			/* if ($result < 0)
	    		{
    				$this->error=$this->db->error();
    				dol_syslog(get_class($this)."::fetch_next Error ".$this->error, LOG_ERR);
    				return -3;
	    		} */
    	
 	   			return 1;
    		} else {
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
    
    function fetch_next_by_facture_id($id = 0, $facid = 0, $soc_type=0)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.tipo_pol,";
    	$sql.= " t.cons,";
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.fecha,";
    	$sql.= " t.concepto,";
    	$sql.= " t.comentario,";
    	$sql.= " t.anombrede,";
    	$sql.= " t.numcheque,";
    	$sql.= " t.fk_facture,";
    	$sql.= " t.ant_ctes,";
    	$sql.= " t.fechahora,";
    	$sql.= " t.societe_type";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id;
    	}
    	if ($soc_type > 0) {
    		$sql .= " AND t.societe_type = ".$soc_type;
    	}
   		$sql .= " AND t.fk_facture = ".$facid;
    	$sql.= " ORDER BY t.rowid ASC LIMIT 1";
    	 
    	dol_syslog(get_class($this)."::fetch_next_by_facture_id sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$num_rows = $this->db->num_rows($resql);
    		if ($num_rows > 0)
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			 
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
    			$this->numcheque = $obj->numcheque;
    			$this->fk_facture = $obj->fk_facture;
    			$this->ant_ctes = $obj->ant_ctes;
    			$this->fechahora = $this->db->jdate($obj->fechahora);
    			$this->societe_type = $obj->societe_type;
    			 
    			$this->lines  = array();
    			 
    			//$result = $this->fetch_lines();
    			/* if ($result < 0)
    			{
    				$this->error=$this->db->error();
    				dol_syslog(get_class($this)."::fetch_next_by_facture_id Error ".$this->error, LOG_ERR);
    				return -3;
    			} */
    			 
    			return 1;
    		} else {
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next_by_facture_id ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_next_by_societe_id($id = 0, $socid = 0)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.tipo_pol,";
    	$sql.= " t.cons,";
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.fecha,";
    	$sql.= " t.concepto,";
    	$sql.= " t.comentario,";
    	$sql.= " t.anombrede,";
    	$sql.= " t.numcheque,";
    	$sql.= " t.fk_facture,";
    	$sql.= " t.ant_ctes,";
    	$sql.= " t.fechahora,";
    	$sql.= " t.societe_type";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id;
    	}
    	if ($socid > 0) {
    		$sql .= " AND ((t.fk_facture in (Select f.rowid From ".MAIN_DB_PREFIX."facture f Where f.fk_soc = $socid )) OR (t.fk_facture in (Select ff.rowid From ".MAIN_DB_PREFIX."facture_fourn ff Where ff.fk_soc = $socid ))) ";
    	}
    	$sql.= " ORDER BY t.rowid ASC LIMIT 1";
    
    	dol_syslog(get_class($this)."::fetch_next_by_societe_id sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$num_rows = $this->db->num_rows($resql);
    		if ($num_rows > 0)
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
    			$this->numcheque = $obj->numcheque;
    			$this->fk_facture = $obj->fk_facture;
    			$this->ant_ctes = $obj->ant_ctes;
    			$this->fechahora = $this->db->jdate($obj->fechahora);
    			$this->societe_type = $obj->societe_type;
    
    			$this->lines  = array();
    
    			//$result = $this->fetch_lines();
    			/* if ($result < 0)
    			 {
    			$this->error=$this->db->error();
    			dol_syslog(get_class($this)."::fetch_next_by_facture_id Error ".$this->error, LOG_ERR);
    			return -3;
    			} */
    
    			return 1;
    		} else {
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next_by_societe_id ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_next_tmp($id = 0)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.tipo_pol,";
    	$sql.= " t.cons,";
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.fecha,";
    	$sql.= " t.concepto,";
    	$sql.= " t.comentario,";
    	$sql.= " t.anombrede,";
    	$sql.= " t.numcheque,";
    	$sql.= " t.fk_facture,";
    	$sql.= " t.ant_ctes,";
    	$sql.= " t.fechahora,";
    	$sql.= "societe_type";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas_tmp as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id;
    	}
    	//$sql.= " AND t.mes = ".$this->periodo_mes." AND t.anio = ".$this->periodo_anio;
    	$sql.= " ORDER BY t.anio, t.mes, t.rowid ASC LIMIT 1";
    	 
    	dol_syslog(get_class($this)."::fetch_next_tmp sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$num_rows = $this->db->num_rows($resql);
    		//dol_syslog("Numero de Registros: $num_rows");
    		if ($num_rows > 0)
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			 
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
    			$this->numcheque = $obj->numcheque;
    			$this->fk_facture = $obj->fk_facture;
    			$this->ant_ctes = $obj->ant_ctes;
    			$this->fechahora = $this->db->jdate($obj->fechahora);
    			$this->societe_type = $obj->societe_type;
    			 
    			$this->lines  = array();
    			 
    			//$result = $this->fetch_lines_tmp();
    			//if ($result < 0)
    			//{
    			//	$this->error=$this->db->error();
    			//	dol_syslog(get_class($this)."::fetch_next_tmp Error ".$this->error, LOG_ERR);
    			//	return -3;
    			//}
    			 
    			return 1;
    		} else {
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
    
    function fetch_next2($id = 0)
    {
    	global $langs;
    	$sql = "SELECT";
    	$sql.= " t.rowid,";
    
    	$sql.= " t.tipo_pol,";
    	$sql.= " t.cons,";
    	$sql.= " t.anio,";
    	$sql.= " t.mes,";
    	$sql.= " t.fecha,";
    	$sql.= " t.concepto,";
    	$sql.= " t.comentario,";
    	$sql.= " t.anombrede,";
    	$sql.= " t.numcheque,";
    	$sql.= " t.fk_facture,";
    	$sql.= " t.ant_ctes,";
    	$sql.= " t.fechahora,";
    	$sql.= "societe_type";
    
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas as t";
    	if ($id == 0) {
    		$sql.= " WHERE 1 ";
    	} else {
    		$sql.= " WHERE t.rowid > ".$id;
    	}
    	$sql .= " ORDER BY t.rowid ASC LIMIT 1";
    	
    	dol_syslog(get_class($this)."::fetch_next2 sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql) > 0)
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
    			$this->numcheque = $obj->numcheque;
    			$this->fk_facture = $obj->fk_facture;
    			$this->ant_ctes = $obj->ant_ctes;
    			$this->fechahora = $this->db->jdate($obj->fechahora);
    			$this->societe_type = $obj->societe_type;

    			$this->lines  = array();
    	
    			$result=$this->fetch_lines();
    			if ($result < 0)
    			{
    				$this->error=$this->db->error();
    				dol_syslog(get_class($this)."::fetch_next2 Error ".$this->error, LOG_ERR);
    				return -3;
    			}
    
   				return 1;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_next2 ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_lines()
    {
    	//$this->lines=array();
    	
    	$res = 0;
    
    	$sql = 'SELECT rowid, asiento, cuenta, debe, haber, fk_poliza ';
    	$sql.= ' FROM '.MAIN_DB_PREFIX.'contab_polizasdet l ';
    	$sql.= ' WHERE l.fk_poliza = '.$this->id;
    	
    	dol_syslog(get_class($this).'::fetch_lines sql='.$sql, LOG_DEBUG);
    	$result = $this->db->query($sql);
    	if ($result)
    	{
    		$num = $this->db->num_rows($result);
    		$i = 0;
    		dol_syslog("Numero de Registros leidos fetch_lines = $num");
    		while ($i < $num)
    		{
    			$objp = $this->db->fetch_object($result);
    			//var_dump($objp);
    			$line = new Contabpolizasdet($this->db);
    
    			$line->rowid		= $objp->rowid;
    			$line->asiento		= $objp->asiento;
    			$line->cuenta		= $objp->cuenta;
    			$line->debe			= $objp->debe;
    			$line->haber		= $objp->haber;
    			$line->fk_poliza	= $objp->fk_poliza;
    			
    			//var_dump($line);
    			$this->lines[$i] = $line;
    
    			$i++;
    			
    			$res = 1;
    		}
    		$this->db->free($result);
    	}
    	else
    	{
    		$this->error=$this->db->error();
    		dol_syslog(get_class($this).'::fetch_lines '.$this->error,LOG_ERR);
    		$res = -3;
    	}
    	return $res;
    }
    
    function fetch_lines_tmp()
    {
    	$this->lines=array();
    	 
    	$res = 0;
    
    	$sql = 'SELECT rowid, asiento, cuenta, debe, haber, fk_poliza ';
    	$sql.= ' FROM '.MAIN_DB_PREFIX.'contab_polizasdet_tmp l ';
    	$sql.= ' WHERE l.fk_poliza = '.$this->id;
    	 
    	dol_syslog(get_class($this).'::fetch_lines_tmp sql='.$sql, LOG_DEBUG);
    	$result = $this->db->query($sql);
    	if ($result)
    	{
    		$num = $this->db->num_rows($result);
    		$i = 0;
    		//dol_syslog("Numero de Registros Fetch_Lines = $num");
    		while ($i < $num)
    		{
    			$objp = $this->db->fetch_object($result);
    			$line = new Contabpolizasdet($this->db);
    
    			$line->rowid		= $objp->rowid;
    			$line->asiento		= $objp->asiento;
    			$line->cuenta		= $objp->cuenta;
    			$line->debe			= $objp->debe;
    			$line->haber		= $objp->haber;
    			$line->fk_poliza	= $objp->fk_poliza;
    			 
    			$this->lines[$i] = $line;
    
    			$i++;
    			 
    			$this->db->free($result);
    			$res = 1;
    		}
    	}
    	else
    	{
    		$this->error=$this->db->error();
    		dol_syslog(get_class($this).'::fetch_lines_tmp '.$this->error,LOG_ERR);
    		$res = -3;
    	}
    	return $res;
    }
    
    function fetch_last_by_tipo_pol($tp)
    {
    	global $langs;
    	$sql = "Select * From ".MAIN_DB_PREFIX."contab_polizas ";
    	$sql.= " Where tipo_pol = '".$tp."'";
    	$sql.= " Order by cons DESC Limit 1;";
    
    	dol_syslog(get_class($this)."::fetch_last_by_tipo_pol sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
				$this->numcheque = $obj->numcheque;
				$this->fk_facture = $obj->fk_facture;
				$this->ant_ctes = $obj->ant_ctes;
				$this->fechahora = $this->db->jdate($obj->fechahora);
				$this->societe_type = $obj->societe_type;
				
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
    		dol_syslog(get_class($this)."::fetch_last_by_tipo_pol ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_by_tp_cons($tp, $cons)
    {
    	global $langs;
    	$sql = "Select * From ".MAIN_DB_PREFIX."contab_polizas ";
    	$sql.= " Where tipo_pol = '".$tp."' And cons = ".$cons;
    	$sql.= " Limit 1;";
    
    	dol_syslog(get_class($this)."::fetch_by_tp_cons sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
				$this->numcheque = $obj->numcheque;
				$this->fk_facture = $obj->fk_facture;
				$this->ant_ctes = $obj->ant_ctes;
				$this->fechahora = $this->db->jdate($obj->fechahora);
				$this->societe_type = $obj->societe_type;
    		}
    		$this->db->free($resql);
    
    		return 1;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch_by_tp_cons ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_by_factura_Y_TipoPoliza($facid, $tp, $soc_type, $ant_ctes = 0)
    {
    	global $langs;
    	$sql = "Select * From ".MAIN_DB_PREFIX."contab_polizas ";
    	$sql.= " Where fk_facture = ".$facid." And tipo_pol = '".$tp."' AND societe_type = ".$soc_type;
    	if ($ant_ctes >= 0) {
    		$sql .= " AND ant_ctes = ".$ant_ctes." ";
    	}
    	$sql.= " Limit 1;";
    
    	dol_syslog(get_class($this)."::fetch_by_factura_Y_TipoPoliza sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		//$this->initAsSpecimen();
    
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    
    			$this->id    = $obj->rowid;
    
    			$this->tipo_pol = $obj->tipo_pol;
    			$this->cons = $obj->cons;
    			$this->anio = $obj->anio;
    			$this->mes = $obj->mes;
    			$this->fecha = $this->db->jdate($obj->fecha);
    			$this->concepto = $obj->concepto;
    			$this->comentario = $obj->comentario;
    			$this->anombrede = $obj->anombrede;
				$this->numcheque = $obj->numcheque;
				$this->fk_facture = $obj->fk_facture;
				$this->ant_ctes = $obj->ant_ctes;
				$this->fechahora = $this->db->jdate($obj->fechahora);
				$this->societe_type = $obj->societe_type;
				
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
    		dol_syslog(get_class($this)."::fetch_by_factura_Y_TipoPoliza ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function get_ult_fecha_modif_contable($anio, $mes) {
    	$sql = "SELECT MAX(t.fecha) as ult_fecha ";
    	
    	$sql.= " FROM ".MAIN_DB_PREFIX."contab_polizas as t";
    	$sql.= " WHERE t.anio = $anio AND t.mes = $mes ";
    	
    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		if ($this->db->num_rows($resql) > 0)
    		{
    			$obj = $this->db->fetch_object($resql);
    			$this->ult_fecha = $obj->ult_fecha;
	    		return 1;
    		} else {
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function getSumDebeHaber($cuenta, $anio, $mes) 
    {
    	global $langs;
    	$sql = "Select Sum(debe) as debe_total, Sum(haber) as haber_total ";
    	$sql .= " From ".MAIN_DB_PREFIX."contab_polizas p ";
    	$sql .= " Inner Join ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    	$sql .= " ON p.rowid = pd.fk_poliza ";
    	$sql .= " Where if(Locate('.', cuenta, Locate('.', cuenta) + 1) > 0, LEFT(cuenta, Locate('.', cuenta, Locate('.', cuenta) + 1) - 1), cuenta) = '$cuenta'";
    	$sql .= " And mes = ".$mes." AND anio = ".$anio;
    	
    	dol_syslog(get_class($this)."::getSumDebeHaber sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    	
    		if ($this->db->num_rows($resql))
    		{
    			$obj = $this->db->fetch_object($resql);
    	
    			$this->debe_total = $obj->debe_total;
    			$this->haber_total = $obj->haber_total;
    		}
    		$this->db->free($resql);
    	
    		return 1;
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::getSumDebeHaber ".$this->error, LOG_ERR);
    		return -1;
    	}
    }
    
    function fetch_by_cuenta($cuenta, $anio, $mes)
    {
    	global $langs;
    	
    	$arr = array();
    	
    	$sql = "Select p.rowid as pid, pd.rowid as pdid, pd.cuenta, pd.debe, pd.haber ";
    	$sql .= " From ".MAIN_DB_PREFIX."contab_polizas p ";
    	$sql .= " Inner Join ".MAIN_DB_PREFIX."contab_polizasdet pd ";
    	$sql .= " ON p.rowid = pd.fk_poliza ";
    	$sql .= " Where if(Locate('.', cuenta, Locate('.', cuenta) + 1) > 0, LEFT(cuenta, Locate('.', cuenta, Locate('.', cuenta) + 1) - 1), cuenta) = '$cuenta'";
    	$sql .= " And mes = ".$mes." AND anio = ".$anio;
    	 
    	dol_syslog(get_class($this)."::getSumDebeHaber sql=".$sql, LOG_DEBUG);
    	$resql=$this->db->query($sql);
    	if ($resql)
    	{
    		$this->initAsSpecimen();
    		
    		$r = $this->db->num_rows($resql);
    		if ($r)
    		{
    			while ($obj = $this->db->fetch_array($resql)) {
					$arr[] = $obj;
    			}
    		}
    		$this->db->free($resql);
    		
    		if ($r) {
    			return $arr;
    		} else {
    			return 0;
    		}
    	}
    	else
    	{
    		$this->error="Error ".$this->db->lasterror();
    		dol_syslog(get_class($this)."::getSumDebeHaber ".$this->error, LOG_ERR);
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
        
		if (isset($this->tipo_pol)) $this->tipo_pol=trim($this->tipo_pol);
		if (isset($this->cons)) $this->cons=trim($this->cons);
		if (isset($this->concepto)) $this->concepto=trim($this->concepto);
		if (isset($this->comentario)) $this->comentario=trim($this->comentario);
		if (isset($this->fk_facture)) $this->fk_facture=trim($this->fk_facture);
		//if (isset($this->ant_ctes)) $this->ant_ctes=trim($this->ant_ctes);

		// Check parameters
		// Put here code to add a control on parameters values
		//var_dump($this->fecha);
		//var_dump($this->db->idate($this->fecha));
        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."contab_polizas SET";
        
        $sql.= " tipo_pol=".(isset($this->tipo_pol)?"'".$this->db->escape($this->tipo_pol)."'":"null").",";
        $sql.= " cons=".(isset($this->cons)?$this->cons:"null").",";
        $sql.= " anio=".(isset($this->anio)?$this->anio:"null").",";
        $sql.= " mes=".(isset($this->mes)?$this->mes:"null").",";
        $sql.= " fecha=".(isset($this->fecha)?"'".$this->fecha."'":"null").",";
		$sql.= " concepto=".(isset($this->concepto)?"'".$this->db->escape($this->concepto)."'":"null").",";
		$sql.= " comentario=".(isset($this->comentario)?"'".$this->db->escape($this->comentario)."'":"null").",";
		$sql.= " anombrede=".(isset($this->anombrede)?"'".$this->db->escape($this->anombrede)."'":"null").",";
		$sql.= " numcheque=".(isset($this->numcheque)?"'".$this->db->escape($this->numcheque)."'":"null").",";
		//$sql.= " fk_facture=".(isset($this->fk_facture)?"'".$this->db->escape($this->fk_facture)."'":"null")."";
		$sql.= " fk_facture=".(isset($this->fk_facture)?$this->fk_facture:"null").",";
		$sql.= " ant_ctes=".(isset($this->ant_ctes)?$this->ant_ctes:false).",";
		$sql.= " societe_type=".(isset($this->societe_type)?$this->societe_type:"null");
		
        $sql.= " WHERE rowid=".$this->id;

		$this->db->begin();

		dol_syslog(get_class($this)."::update sql=".$sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
		{
			/* foreach($poliza->lines as $i => $line)
			{
				$polizaline = new Contabpolizasdet($this->db);
				$polizaline->id = $poliza[$i]->id;
				$polizaline->asiento = $poliza[$i]->asiento;
				$polizaline->cuenta = $poliza[$i]->cuenta;
				$polizaline->debe = $poliza[$i]->debe;
				$polizaline->haber = $poliza[$i]->debe;
				$polizaline->fk_poliza = $poliza[$i]->fk_polizad;
				
				$polizaline->update();
			} */
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
			/* $sql = 'DELETE FROM '.MAIN_DB_PREFIX.'contab_polizasdet WHERE fk_facture = '.$rowid;
			if ($this->db->query($sql) && $this->delete_linked_contact())
			{ */
				$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_polizas";
    			$sql.= " WHERE rowid=".$this->id;

    			dol_syslog(get_class($this)."::delete sql=".$sql);
    			$resql = $this->db->query($sql);
        		if (! $resql) { 
        			$error++; $this->errors[]="Error ".$this->db->lasterror(); 
        		} else {
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
			/* } else {
				$this->error=$this->db->lasterror()." sql=".$sql;
				dol_syslog(get_class($this)."::delete ".$this->error, LOG_ERR);
				$this->db->rollback();
				return -6;
			}   */      		
		} else {
			$this->error=$this->db->lasterror()." sql=".$sql;
			dol_syslog(get_class($this)."::delete ".$this->error, LOG_ERR);
			$this->db->rollback();
			return -6;
		}  
	}
	
	function delete_by_facture($user, $facid, $notrigger=0)
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
			/* $sql = 'DELETE FROM '.MAIN_DB_PREFIX.'contab_polizasdet WHERE fk_facture = '.$rowid;
				if ($this->db->query($sql) && $this->delete_linked_contact())
				{ */
			$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_polizas";
			$sql.= " WHERE fk_facture=".$facid;
	
			dol_syslog(get_class($this)."::delete_by_facture sql=".$sql);
			$resql = $this->db->query($sql);
			if (! $resql) {
				$error++; $this->errors[]="Error ".$this->db->lasterror();
			} else {
				// Commit or rollback
				if ($error)
				{
					foreach($this->errors as $errmsg)
					{
						dol_syslog(get_class($this)."::delete_by_facture ".$errmsg, LOG_ERR);
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
			/* } else {
			 $this->error=$this->db->lasterror()." sql=".$sql;
			dol_syslog(get_class($this)."::delete ".$this->error, LOG_ERR);
			$this->db->rollback();
			return -6;
			}   */
		} else {
			$this->error=$this->db->lasterror()." sql=".$sql;
			dol_syslog(get_class($this)."::delete_by_facture ".$this->error, LOG_ERR);
			$this->db->rollback();
			return -6;
		}
	}
	
	function reindexar() {
		$resql = $this->db->query("SELECT * FROM ".MAIN_DB_PREFIX."contab_polizas");
		if($resql){
			//Se crea la tabla temporal de encabezados y detalle
			//$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_polizas_tmp");
			//$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_polizasdet_tmp");
			
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_polizas_tmp LIKE ".MAIN_DB_PREFIX."contab_polizas");
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_polizasdet_tmp LIKE ".MAIN_DB_PREFIX."contab_polizasdet");

			$p = new Contabpolizas($this->db);
			$pd = new Contabpolizasdet($this->db);
			
			$pt = new contabpolizas($this->db);
			
			$id = 0;
			while ($rs = $p->fetch_next2($id)) {
				$pt->tipo_pol = $p->tipo_pol;
				$pt->cons = $p->cons;
				$pt->anio = $p->anio;
				$pt->mes = $p->mes;
				$pt->fecha = date("Y-m-d",$p->fecha);
				//$pt->fecha = $p->fecha;
				$pt->concepto = $p->concepto;
				$pt->comentario = $p->comentario;
				$pt->anombrede = $p->anombrede;
				$pt->numcheque = $p->numcheque;
				$pt->ant_ctes = $p->ant_ctes;
				$pt->fechahora = $p->fechahora;
				$pt->societe_type = $p->societe_type;
				$pt->fk_facture = $p->fk_facture;
				
				$pt->lines = array();
				
				//var_dump($p->lines);
				foreach ($p->lines as $i => $l) {
					$pt->lines[] = $l;
				}
				
				$pt->create($user, 0, 'contab_polizas_tmp');
				
				$id = $p->id;
			}
			
			//eliminamos la tabla temporal
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_polizas");
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_polizasdet");
			
			//Se crea la tabla original de encabezados y detalles
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_polizas LIKE ".MAIN_DB_PREFIX."contab_polizas_tmp");
			$this->db->query("CREATE TABLE ".MAIN_DB_PREFIX."contab_polizasdet LIKE ".MAIN_DB_PREFIX."contab_polizasdet_tmp");
			
			// Colocar todo el contenido de la tabla Temporal a la tabla original
			$this->db->query("INSERT INTO ".MAIN_DB_PREFIX."contab_polizas SELECT * FROM ".MAIN_DB_PREFIX."contab_polizas_tmp");
			$this->db->query("INSERT INTO ".MAIN_DB_PREFIX."contab_polizasdet SELECT * FROM ".MAIN_DB_PREFIX."contab_polizasdet_tmp");
			
			//eliminamos la tabla temporal
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_polizas_tmp");
			$this->db->query("DROP TABLE ".MAIN_DB_PREFIX."contab_polizasdet_tmp");
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

		$object=new Contabpolizas($this->db);

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
		dol_syslog("Init as Specimen");
		$this->id=0;
		
		$this->tipo_pol='';
		$this->cons='';
		$this->anio='';
		$this->mes='';
		$this->fecha='';
		$this->concepto='';
		$this->comentario='';
		$this->anombrede='';
		$this->numcheque='';
		$this->fk_facture='';
		$this->ant_ctes='';
		$this->fechahora='';
		$this->societe_type='';
		
		$this->debe_total = 0;
		$this->haber_total = 0;
	}
	
	function Get_Tipo_Poliza_Desc() {
		if ($this->tipo_pol == "D") { $r = "Diario"; }
		else if($this->tipo_pol == "E") { $r = "Egreso"; }
		else if($this->tipo_pol == "C") { $r = "Cheques"; }
		else if($this->tipo_pol == "I") { $r = "Ingreso"; }
		return $r;
	}

	function Get_folio_poliza() {
		//$r = "No Definido";
		if(empty($this->tipo_pol)){
			$r = "No Definido";
		}else{
			$an=substr($this->anio, -2, 2); 			
			$m = ((int)$this->mes<10) ? "0".$this->mes : $this->mes ;
			$r = $an.$m."-".$this->tipo_pol."-".$this->id; 
		}
		
		return $r;
	}
	
	// De esta parte en adelante es donde se incluyen los métodos para la creación de pólizas directamente de facturas
	// que ya hayan sido capturadas previamente y que no contaban con el módulo de contabilidad.
	
	// TODO: checar si es requerido o no realizar esta función.  la tenía pensada para el manejo, control o algo así de la cuenta del IVA
	// y sus consecuencias en la generación de las pólizas, creo más bien que no estaba concentrado para saber que hacer con esta función
	// o en su defecto se me ocurrió pero realmente no la necesito, por eso hay que revisar si es requerida esta función. (Creo que NO se req.!!).
	/* public function Tasas_de_Impuestos() {
		$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
		$country_id=$tmp[0];
		
		$sql = "SELECT t.rowid, t.taux, t.localtax1_type, t.localtax1, t.localtax2_type, t.localtax2, p.libelle as country, p.code as country_code, t.fk_pays as country_id, t.recuperableonly, t.note, t.active, t.accountancy_code_sell, t.accountancy_code_buy FROM llx_c_tva as t, llx_c_pays as p WHERE t.fk_pays=p.rowid AND t.fk_pays = $country_id ORDER BY country ASC, taux ASC, recuperableonly ASC, localtax1 ASC, localtax2 ASC";
		
	} */
	
	public function Venta_a_Credito($object, $user, $conf) {
		$ret = $this->Venta_a_Credito2($object->id, $user, $conf);
		return $ret;
	}
	
	public function Venta_a_Credito2($facid, $user, $conf) {
		$fac = new Factures($this->db);
		$fac->fetch($facid);
		
		$facref = $fac->ref;
		$facid = $fac->id;
		$socid = $fac->socid;
		
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
		
		$mismo_iva = true;
		foreach ($fac->lines as $i => $line) {
			dol_syslog("Valor de i = $i");
			if ($i == 0) {
				$tva_tx = $line->tva_tx;
			} else {
				if ($tva_tx != $line->tva_tx) {
					$mismo_iva = false;
				}
			}
			dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
		}
			
		$cond_pago = 1;
		$payment = new Contabpaymentterm($this->db);
		$payment->fetch($fac->fk_cond_reglement);
		if ($payment->cond_pago) {
			$cond_pago = $payment->cond_pago;
		}
			
		//Que tipo de poliza se trata?
		dol_syslog("VENTA A CREDITO");
		dol_syslog("Tipo Factura=".$fac->type.", fk_cond_reglement=".$fac->fk_cond_reglement.", cond_pago=".$cond_pago);
		$tipo_pol = "";
		$concepto = "";
			
		if ($fac->type == $fac::TYPE_STANDARD) {
			if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
				//la Venta es a Credito
				// 2. El cliente paga a 30 dias.
				// 3. Paga a 30 dias Final del Mes.
				// 4. Paga a 60 dias.
				// 5. Paga a 60 dias Final del Mes.
				$tp = $this::POLIZA_DE_DIARIO;
				$concepto = "Venta al Cliente a Crédito, Según Factura ".$facref;
			} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
				//La venta es 50% a credito y 50% al contado.
				//8. El cliente paga la mitad ahorita y la mitad después.
				$tp = $this::POLIZA_DE_DIARIO;
				$concepto = "Venta al Cliente, 50% a Crédito y 50% al Contado, Según Factura ".$facref;
					
				if (!$mismo_iva) {
					$ret = -1;
					return $ret;
				}
			}
		}
		
		$exists = $this->fetch_by_factura_Y_TipoPoliza($facid, $tp, 1);
			
		if($fac->type == $fac::TYPE_STANDARD && ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO || $cond_pago == Contabpaymentterm::PAGO_EN_PARTES )) {
		
			if ($exists) {
		
			} else {
		
				$this->fetch_last_by_tipo_pol($tp);
				$cons = $this->cons + 1;
				 
				$this->initAsSpecimen();
				 
				$this->anio = date("Y", $fac->date);
				$this->mes = date("m", $fac->date);
				$this->fecha = date("Y-m-d",$fac->date);
				$this->concepto = $concepto;
				$this->comentario = "Factura a Cliente con fecha del ".date("Y-m-d", $fac->date);
				$this->tipo_pol = $tp;
				$this->cons = $cons;
				$this->fk_facture = $facid;
				$this->societe_type = 1;
				 
				$this->create($user);
				$polid = $this->id;
				 
				/* $dscto_ht = 0;
				$dscto_tva = 0;
				$dscto_ttc = 0;
				// Se busca el descuento que fue aplicado a la factura
				$sql = 'SELECT SUM(amount_ht) amount_ht, SUM(amount_tva) amount_tva, SUM(amount_ttc) amount_ttc ';
				$sql.= 'FROM llx_societe_remise_except sr ';
				$sql.= 'INNER JOIN llx_facturedet fd ';
				$sql.= 'ON sr.fk_facture_line = fd.rowid AND fd.fk_facture = '.$facid;
				 
				dol_syslog("Venta a Credito - sql:".$sql);
				 
				if ($res = $this->db->query($sql)) {
					if ($row = $this->db->fetch_object($res)) {
						$dscto_ht = $row->amount_ht;
						$dscto_tva = $row->amount_tva;
						$dscto_ttc = $row->amount_ttc;
					}
				} */
				 
				//Ahora se crearán los asientos contables para la póliza.
				$ln = array();
				foreach ($fac->lines as $j => $line) {
					dol_syslog("remise_excent=".$fac->lines[$j]->fk_remise_except);
					//if (! $fac->lines[$j]->fk_remise_except > 0) {
						if ($fac->lines[$j]->product_type == 0) {
							//Es un producto
							//Analizando si hay descuento sobre compras
		
							$sub_total = $line->subprice * $line->qty;
							$descto = ($sub_total * $line->remise_percent / 100); //+ $dscto_ht;
							$total = $sub_total - $descto;
							$iva = $total * $line->tva_tx / 100;
							
							dol_syslog("Line id = ".$line->rowid." - ".$fac->lines[$j]->rowid." subtotal=$sub_total, descto=$descto, total=$total, iva=$iva");
							
							/* $dscto_ht = 0;
							$dscto_tva = 0;
							$dscto_ttc = 0; */
		
							$cuenta = "";
							$codagr = "";
							//Es poliza de Ingreso cuando se hace una venta al contado o con cheque al contado.
							//Es poliza de Egresos cuando se hace un pago con Cheque o se emite un cheque.
							//Es de Diario cuando se trata de cualquier otro tipo de poliza.
							if($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
								dol_syslog("PAGO A CREDITO");
								//La venta es 100% a credito.
								//2 al 5. El cliente compra todo a crédito, 30, 60 días.
		
								//la Venta es 100% a Crédito
								// Se registra una Venta a Credito y 10% de Desc.
		
								// Póliza de Diario
								// Clientes				104.40
								// Descto s/vtas		 10.00
								// 		Ventas					100.00
								//		IVA x Trasladar			 14.40
		
								// Es una póliza de Diario (se trata de una venta a crédito.
								if ($cuenta = $this->Get_Cliente_Proveedor_Cta($socid)) {
									//Se recibe el pago contra el cliente (NO Caja, No Bancos)
									//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
									//El cliente tiene definida una cuenta para referenciar acientos contables
									$codagr = "";
									// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
								} else {
									//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
									$cuenta = $this->Get_Cliente_Cuenta($socid, $conf);
								}
								$debe = $total + $iva;
								$haber = 0;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
								//Analizando si hay descuento sobre la venta
								// Se registra el Descuento sobre Ventas
								if ($line->remise_percent > 0 || $descto > 0) {
									if ($line->tva_tx > 0) {
										$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
										//$codagr = "402.01"; //Dscto a Tasa General
									} else {
										$rel->fetch_by_code("DEV_VTA_TASA_0");
										//$codagr = "402.02"; //Dscto a Tasa 0%
									}
									$cat->fetch($rel->fk_cat_cta);
									$cuenta = $cat->cta;
									$debe = $descto;
									$haber = 0;
									$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
								}
		
								// Se registra la Ventas
								if ($line->tva_tx > 0) {
									$rel->fetch_by_code("VENTAS_TASA_GRAL");
									//$codagr = "401.01"; //Venta a Tasa General
								} else {
									$rel->fetch_by_code("VENTAS_TASA_0");
									//$codagr = "401.04"; //Venta a Tasa 0%
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $sub_total;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
								//Se registra el IVA Trasladado No Cobrado por que fue a Crédito
								$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
								/* $codagr = "209.01"; //Iva Trasladado No Cobrado*/
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $iva;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
									
							} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
								dol_syslog("PAGO EN PARTES");
								//la Venta es 50% al Contado y 50% a crédito
								// Se registra una Venta 50% al Contado y 50% a Credito y 10% de Desc.
		
								// Póliza de Diario
								// Clientes				52.20
								// Descto s/vtas		 5.00
								// 		Ventas					50.00
								//		IVA x Trasladar			 7.20
		
								//Se registra el ingreso a Clientes
								if ($cuenta = $this->Get_Cliente_Proveedor_Cta($socid)) {
									//Se recibe el pago contra el cliente (NO Caja, No Bancos)
									//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
									//El cliente tiene definida una cuenta para referenciar acientos contables
									$codagr = "";
									// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
								} else {
									//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
									$cuenta = $this->Get_Cliente_Cuenta($socid, $conf);
								}
								//Se divide ya que es la mitad a credito y la mitad a contado, cada uno en Póliza separada
								$debe = ($total + $iva) / 2;
								$haber = 0;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
									
								// Se registra el Descuento sobre Ventas
								if ($line->remise_percent > 0) {
									if ($line->tva_tx > 0) {
										$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
										//$codagr = "402.01"; //Dscto a Tasa General
									} else {
										$rel->fetch_by_code("DEV_VTA_TASA_0");
										//$codagr = "402.02"; //Dscto a Tasa 0%
									}
									$cat->fetch($rel->fk_cat_cta);
									$cuenta = $cat->cta;
									$debe = $descto / 2;
									$haber = 0;
									$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
								}
		
								// Se registra la Ventas
								if ($line->tva_tx > 0) {
									$rel->fetch_by_code("VENTAS_TASA_GRAL");
									//$codagr = "401.01"; //Venta a Tasa General
								} else {
									$rel->fetch_by_code("VENTAS_TASA_0");
									//$codagr = "401.04"; //Venta a Tasa 0%
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								//Se divide ya que es la mitad a credito y la mitad a contado, cada uno en Póliza separada
								$haber = $sub_total / 2;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
								//Se registra el IVA
								$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
								//$codagr = "209.01"; //Iva Trasladado No Cobrado  por ser Venta a Crédito 50%
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								//Se divide ya que es la mitad a credito y la mitad a contado, cada uno en Póliza separada
								$haber = $iva / 2; //(($line->subprice * $line->qty) - $debe_dscto) * $line->tva_tx / 100;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							}
						} else if ($fac->lines[$j]->product_type == 1) {
							//Se trata de una venta a credito de Servicio
							/*
							 * TODO: Realizar la parte de Servicios, específicamente cuando haya retenciones por parte de nuestros
							* 		Clientes cuando les hagamos algun trabajo o servicio y nos tengan que hacer las retenciones
							* 		correspondientes de IVA y de ISR.
							*/
						}
					//} else { dol_syslog("Esta línea no se debe procesar"); }
				}
				$jj = 0;
				while ($jj < sizeof($ln[$tp])) {
					$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
					$jj++;
				}
			}
		}
	}
	
	public function Cliente_Saldar_Pago_Anticipado($object, $user, $conf) {
		$this->Cliente_Saldar_Pago_Anticipado2($object->id, $user, $conf);
	}
	
	public function Cliente_Saldar_Pago_Anticipado2($facid, $user, $conf) {
		global $conf;
		
		dol_syslog("Cliente_Saldar_Pago_Anticipado ==> Datos: facid = ".$facid);
		
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
			
		$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
		$country_id=$tmp[0];
			
		//con esta instrucción se obtiene el primero registro del detalle donde se encuentra el pago anticipado.
		$sql = "SELECT re.fk_facture_source ";
		$sql .= "FROM ".MAIN_DB_PREFIX."facture f ";
		$sql .= "INNER JOIN ".MAIN_DB_PREFIX."facturedet fd ";
		$sql .= "ON f.rowid = fd.fk_facture ";
		$sql .= "INNER JOIN ".MAIN_DB_PREFIX."societe_remise_except re ";
		$sql .= "ON fd.rowid = re.fk_facture_line ";
		$sql .= "WHERE re.description = '(DEPOSIT)' AND f.rowid = ".$facid;
		
		$rs = $this->db->query($sql);
		dol_syslog("Esta es la factura pagada por anticipado, sql=$sql");
		
		if ($rw = $this->db->fetch_object($rs)) {
			if (!$pp = $this->fetch_by_factura_Y_TipoPoliza($facid, $this::POLIZA_DE_DIARIO, 1, 1)) {
				dol_syslog("Si entro al if");
				//No se ha creado la Póliza para cancelar saldos de la póliza de ingresos por Venta Pagada por Anticipado.
				 
				//Notas:
				//1. La factura a afectar con la Factura Anticipada, deberá de tener los mismos datos tanto en productos, cantidades y descuentos.
				//2. Si al momento de entregar los productos y hacer la factura que quedó afectada por la factura anticipada, el cliente
				//		desea más atriculos o productos, se deberán de cargar dichos productos extras (que no fueron contemplados en la factura anticipada)
				//		en otra factura nueva, osea se deberá hacer otra factura una vez que se haya concluido con la captura de la factura actual.
		
				//Cuando una factura se paga con el saldo de una factura pagada por anticipado, la factura no tiene los datos de la venta
				//hay que ir a los datos de cada producto para que, sumando obtengamos los datos de la venta.
				$fac = new Factures($this->db);
				//$fac->fetch($facid);
				$fac->fetch($rw->fk_facture_source);
				 
				$fk_soc = $fac->socid;
				
				$facref = $fac->ref;
				 
				dol_syslog("Tipo Factura=".$fac->type.", fk_cond_reglement=".$fac->fk_cond_reglement.", cond_pago=".$cond_pago.", fac-socid=".$fac->socid." facid=".$facid." facref=".$facref);
				 
				$tp = $this::POLIZA_DE_DIARIO;
				$concepto = "Venta pagada por Anticipado, Según Factura ".$facref;
				$comentario = "Pago de Factura a Cliente con fecha del ".date("Y-m-d", $fac->date);
				 
				$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fac->date, 1, true);
				 
				$ln = array();
				 
				foreach ($fac->lines as $i => $line) {
					//La linea que no tiene info en fk_product, es la linea que da información sobre el pago realizado con la factura anticipada.
					if ($line->fk_product > 0 && $line->product_type == 0) {
						// Se trata de un producto No de un servicio.
							
						$sub_total = $line->subprice * $line->qty;
						$descto = $sub_total * $line->remise_percent / 100;
						$total = $sub_total - $descto;
						$iva = $total * $line->tva_tx / 100;
		
						$cuenta = "";
						$codagr = "";
							
						// Se registra la Ventas anticipada
						// Se requiere saber si es un cliente nacional o extranjero.
						$asiento = 1;
						if ($this->Get_Cliente_Proveedor_Pais($fk_soc) == $country_id) {
							$rel->fetch_by_code("ANT_CTE_NAL");
							//$codagr = "206.01"; //Nacional
						} else {
							$rel->fetch_by_code("ANT_CTE_EXT");
							//$codagr = "206.02"; //Extranjero
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = $total + $iva;
						$haber = 0;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							
						//Analizando si hay descuento sobre Ventas
						if ($descto > 0) {
							$asiento ++;
							if ($line->tva_tx > 0) {
								$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
								//$codagr = "402.01"; //Dscto a Tasa General
							} else {
								$rel->fetch_by_code("DEV_VTA_TASA_0");
								//$codagr = "402.02"; //Dscto a Tasa 0%
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = $descto;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
							
						//Se registra la Venta
						$asiento ++;
						if ($line->tva_tx > 0) {
							$rel->fetch_by_code("VENTAS_TASA_GRAL");
							//$codagr = "401.01"; //Venta a tasa normal
						} else {
							$rel->fetch_by_code("VENTAS_TASA_0");
							//$codagr = "401.04"; //Venta a tasa 0
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = 0;
						$haber = $sub_total;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
						if ($line->tva_tx > 0) {
							//Se registra el IVA Trasladado NO Cobrado por que fue al contado
							$asiento ++;
							$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
							//$codagr = "209.01"; //Iva Trasladado NO Cobrado
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = 0;
							$haber = $iva;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
					} else if ($line->fk_product > 0 && $line->product_type == 1) {
						// Se tata de un Servicio No de un Producto.
						/* TODO: ver si se va a realizar la generación de una póliza automática por el pago anticipado de servicio
						 * 		de arrendamiento o servicios profesionales.
						*/
					} else if ($line->description = "(DEPOSIT)") {
						// Como es un descuento o mas bien una factura de pago adelantada que se está utilizando para pagar en esta factura
						// hay que hacer lo mismo que en las lineas anteriores, pero con su saldo negativo para que afinal de cuentas quede
						// todo sumado correctamente.
						
						// Se trata de un producto No de un servicio, pero que se tomará como descuento.
							
						$sub_total = $line->subprice * $line->qty;
						$descto = $sub_total * $line->remise_percent / 100;
						$total = $sub_total - $descto;
						$iva = $total * $line->tva_tx / 100;
						
						$cuenta = "";
						$codagr = "";
							
						// Se registra la Ventas anticipada
						// Se requiere saber si es un cliente nacional o extranjero.
						$asiento = 1;
						if ($this->Get_Cliente_Proveedor_Pais($fk_soc) == $country_id) {
							$rel->fetch_by_code("ANT_CTE_NAL");
							//$codagr = "206.01"; //Nacional
						} else {
							$rel->fetch_by_code("ANT_CTE_EXT");
							//$codagr = "206.02"; //Extranjero
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = $total + $iva;
						$haber = 0;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							
						//Analizando si hay descuento sobre Ventas
						if ($descto > 0) {
							$asiento ++;
							if ($line->tva_tx > 0) {
								$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
								//$codagr = "402.01"; //Dscto a Tasa General
							} else {
								$rel->fetch_by_code("DEV_VTA_TASA_0");
								//$codagr = "402.02"; //Dscto a Tasa 0%
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = $descto;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
							
						//Se registra la Venta
						$asiento ++;
						if ($line->tva_tx > 0) {
							$rel->fetch_by_code("VENTAS_TASA_GRAL");
							//$codagr = "401.01"; //Venta a tasa normal
						} else {
							$rel->fetch_by_code("VENTAS_TASA_0");
							//$codagr = "401.04"; //Venta a tasa 0
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = 0;
						$haber = $sub_total;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						
						if ($line->tva_tx > 0) {
							//Se registra el IVA Trasladado NO Cobrado por que fue al contado
							$asiento ++;
							$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
							//$codagr = "209.01"; //Iva Trasladado NO Cobrado
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = 0;
							$haber = $iva;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
					}
				}
				$jj = 0;
				while ($jj < sizeof($ln[$tp])) {
					$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
					$jj++;
				}
			}
		}
		dol_syslog("Termina de Procesar Cliente_Saldar_Pago_Anticipado ==> Datos: facid = ".$facid);
	}
	
	public function Pago_de_Factura($object, $user, $conf) {
		$this->Pago_de_Factura2($object->id, $user, $conf);
	}
	
	public function Pago_de_Factura2($paimid, $user, $conf) {
		$object;
		$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
		$country_id=$tmp[0];
			
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
		
		dol_syslog("Pago_de_Factura - Búsqueda de Paiments - getBillsArray(), Object->id = ".$paimid);
		
		$paim = new Paiements($this->db);
		$paim->fetch($paimid);
		$fecha = $paim->datepaye;
		$mode_reglement = $paim->id_paiment;
			
		$paim->id = $paimid;
		$a_fac = $paim->getBillsArray();
			
		foreach ($a_fac as $idx => $value) {
		
			//dol_syslog("Ir a ver si se tiene que generar una póliza por pago anticipado");
			
			$facid = $value;
			
			//$this->Cliente_Saldar_Pago_Anticipado2($facid, $user, $conf);
			
			dol_syslog("Se inicia con la verificacón de la factura=$facid");
			$fac = new Factures($this->db);
			$fac->fetch($facid);
			$facref = $fac->ref;
			$facid = $fac->id;
			
			$mismo_iva = true;
			foreach ($fac->lines as $i => $line) {
				dol_syslog("Valor de i = $i");
				if ($i == 0) {
					$tva_tx = $line->tva_tx;
				} else {
					if ($tva_tx != $line->tva_tx) {
						$mismo_iva = false;
					}
				}
				dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
			}
			
			foreach ($fac->lines as $i => $line) {
				if ($line->desc == "(DEPOSIT)") {
					dol_syslog("Hay un pago anticipado que se debe de resolver primero.");
					$this->Cliente_Saldar_Pago_Anticipado2($facid, $user, $conf);
				}
			}
			
			//Se obtienen los ids de cada una de las lineas de la factura ( o sea los ids de factura detalle)
			$a_lines_ids = array();
			foreach ($fac->lines as $i => $line) {
				$a_lines_ids[] = $line->rowid;
			}
			$str_lines_ids = implode(",", $a_lines_ids);
			
			dol_syslog("Cliente_Crear_Poliza");
			
			dol_syslog("FACREF = ".$facref." FACID=".$facid." Fac Type=".$fac->type.", fk_cond_reglement=".$fac->fk_cond_reglement);
			
			if ($fac->type == $fac::TYPE_STANDARD) {
			
				dol_syslog("FACTURA STANDARD =======>");
				
				//Obtener todos los pagos realizados a la Factura Original, para saber a que cuentas se deberá de afectar la Devolución.
				// Payments already done (from payment on this invoice)
				/* $sql = 'SELECT SUM(pf.amount) as amount';
				$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
				$sql .= ' WHERE pf.fk_facture = ' . $facid . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_paiement = '.$paimid;
				$sql .= ' ORDER BY p.datep, p.tms'; */
				
				$sql = 'SELECT pf.amount as amount, ifnull(ba.account_number, "") as account_number ';
				$sql .= ' FROM '.MAIN_DB_PREFIX.'c_paiement as c, '.MAIN_DB_PREFIX.'paiement_facture as pf, '.MAIN_DB_PREFIX.'paiement as p ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank as b ON p.fk_bank = b.rowid ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank_account as ba ON b.fk_account = ba.rowid ';
				$sql .= ' WHERE p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_facture = '.$facid.' AND pf.fk_paiement = '.$paimid;
				
				dol_syslog("Pago_de_Factura - Factura Standard: sql=".$sql);
				$amount = 0;
				$bank_account_number = "";
				$result = $this->db->query($sql);
				if ($result) {
					$objp = $this->db->fetch_object($result);
					if ($objp) {
						$amount = $objp->amount;
						$bank_account_number = $objp->account_number;
					}
					$this->db->free($result);
				}
				
				//Parte que están pagando manejada en %
				$percent = $amount / $fac->total_ttc;
				dol_syslog("Percent=$percent, amount=$amount, total_ttc=".$fac->total_ttc.", Cond Reglement:".$fac->fk_cond_reglement.", Mode Reglement:$mode_reglement");
				
				//Ver si el pago es al contado, credito, cobro anticipado, 50 y 50.
				$payment = new Contabpaymentterm($this->db);
				$payment->fetch($fac->fk_cond_reglement);
				$cond_pago = $payment->cond_pago;
				dol_syslog("Condición de Pago: $cond_pago");
				//Que tipo de poliza se trata?
				$tp = "";
				$concepto = "";
		
				dol_syslog("La Póliza no existe, se tiene que crear.  total=$total, amount=$amount, iva=$iva, tva_tx=$fac->lines[0]->tva_tx");
		
				if ($cond_pago == Contabpaymentterm::PAGO_AL_CONTADO) {
		
					dol_syslog("Pago al Contado - ".$cond_pago);
					
					// Pago a la recepción de la factura, lo cual indica que es al contado por que se le de ahí mismo la factura al cliente.
					//la Venta es al Contado
					// 1. El cliente paga a la recepción de la factura
					$tp = $this::POLIZA_DE_INGRESO;
					$concepto = "Ingreso por Venta a Cliente al Contado, Según Factura ".$facref;
					$comentario = "Pago de Factura a Cliente con fecha del ".date("Y-m-d", $fecha);
						
					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
						
					$cuenta = "";
					$codagr = "";
						
					//Esta parte se ejecuta para cuando son ivas iguales
					$sub_total = 0;
					$total = 0;
					$descto = 0;
					$iva = 0;
					foreach($fac->lines as $j => $l) {
						dol_syslog("remise = ".$l->fk_remise_except." total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
						
						$sub_total = ($l->subprice * $l->qty) * $percent;
						$descto = $sub_total * $l->remise_percent / 100;
						$total_si = $sub_total - $descto;
						$iva = $total_si * $l->tva_tx / 100;
						$total_ci = $total_si + $iva;
						
						if ($bank_account_number) {
							$cuenta = $bank_account_number;
						} else {
							if ($mode_reglement == 4) {
								// Se recibe el pago en Efectivo
								$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
							} else {
								// Cualquier otro valor se tomar como pago Bancario
								$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
						}
						$debe = $total_ci;
						$haber = 0;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						
						if ($descto > 0 ) {
							$asiento ++;
							if ($l->tva_tx == 0) {
								$rel->fetch_by_code("DEV_VTA_TASA_0");		//$codagr = "402.01"; //Dscto a Tasa General
							} else {
								$rel->fetch_by_code("DEV_VTA_TASA_GRAL");	//$codagr = "402.02"; //Dscto a Tasa 0%
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = $descto;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
						
						if ($l->tva_tx == 0) {
							$rel->fetch_by_code("VENTAS_TASA_0");		//$codagr = "401.01"; //Venta a Tasa General
						} else {
							$rel->fetch_by_code("VENTAS_TASA_GRAL");	//$codagr = "401.04"; //Venta a Tasa 0%
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = 0;
						$haber = $sub_total;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						
						if ($l->tva_tx > 0) {
							//Se registra el IVA Trasladado Cobrado por que fue al contado
							$asiento ++;
							$rel->fetch_by_code("IVA_TRAS_COBRADO");		//$codagr = "208.01"; //Iva Trasladado Cobrado
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = 0;
							$haber = $iva;
						
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
						
					}
					
					$jj = 0;
					while ($jj < sizeof($ln[$tp])) {
						$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
						$jj++;
					}
				} else if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
					if (!$mismo_iva) {
						dol_syslog("Pago a credito IVAS Diferentes - ".$cond_pago);
							
						// Al momento de la Facturación, La venta que fue registrada, se realizó de la siguiente forma:
						// Póliza de Diario
						// Clientes				104.40
						// Descto s/vtas		 10.00
						// 		Ventas					100.00
						//		IVA x Trasladar			 14.40
		
						// En este momento de Recibir el Pago, Se debe crear una póliza con la siguiente estructura:
						// Póliza de Ingreso
						// Caja o Bancos		104.40
						// IVA x Trasladar		 14.40
						// 		Clientes				100.00
						//		IVA x Trasladado		 14.40
							
						$ln = array();
							
						$tp = $this::POLIZA_DE_INGRESO;
						$concepto = "Ingreso por cobro de Venta a Crédito, Según Factura ".$facref;
						$comentario = "Pago de Factura a Cliente con fecha del ".date("Y-m-d", $fecha);
							
						$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
							
						$cuenta = "";
						$codagr = "";
							
						$sub_total = 0;
						$total = 0;
						$descto = 0;
						$iva = 0;
						foreach($fac->lines as $j => $l) {
							$sub_total = ($l->subprice * $l->qty) * $percent;
							$descto = $sub_total * $l->remise_percent / 100;
							$total_si = $sub_total - $descto;
							$iva = $total_si * $l->tva_tx / 100;
							$total_ci = $total_si + $iva;
		
							//Se registra el ingreso Caja o BANCOS
							$asiento = 1;
							if ($bank_account_number) {
								$cuenta = $bank_account_number;
							} else {
								if ($mode_reglement == 4) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = $total_ci;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							//Se registra el IVA Trasladado No Cobrado por que fue a Crédito
							$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
							//$codagr = "209.01"; //Iva Trasladado No Cobrado
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = $iva;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							// Se registra la Ventas
							if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
								dol_syslog("El cliente si tiene cuenta asingada=".$cuenta);
								//Se recibe el pago contra el cliente (NO Caja, No Bancos)
								//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
								//El cliente tiene definida una cuenta para referenciar acientos contables
								$codagr = "";
								// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
							} else {
								dol_syslog("El cliente no tiene cuenta asignada");
								//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
								$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
							}
							$debe = 0;
							$haber = $total_ci;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							//Se registra el IVA Trasladado Cobrado por que fue a Crédito
							$rel->fetch_by_code("IVA_TRAS_COBRADO");
							//$codagr = "208.01"; //Iva Trasladado Cobrado
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = 0;
							$haber = $iva;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
							
						$jj = 0;
						while ($jj < sizeof($ln[$tp])) {
							$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
							$jj++;
						}
							
					} else if($mismo_iva) {
						dol_syslog("Pago a credito IVAS Iguales - ".$cond_pago);
		
						$tp = $this::POLIZA_DE_INGRESO;
						$concepto = "Ingreso por cobro de Venta a Crédito, Según Factura ".$facref;
						$comentario = "Pago de Factura a Cliente con fecha del ".date("Y-m-d", $fecha);
		
						$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
						
						$sub_total = 0;
						$total = 0;
						$descto = 0;
						$iva = 0;
						foreach($fac->lines as $j => $l) {
							$sub_total = ($l->subprice * $l->qty) * $percent;
							$descto = $sub_total * $l->remise_percent / 100;
							$total_si = $sub_total - $descto;
							$iva = $total_si * $l->tva_tx / 100;
							$total_ci = $total_si + $iva;
						
							//Se registra el ingreso Caja o BANCOS
							if ($bank_account_number) {
								$cuenta = $bank_account_number;
							} else {
								if ($mode_reglement == 4) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = $total_ci;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						
							//Se registra el IVA Trasladado No Cobrado por que fue a Crédito
							$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
							//$codagr = "209.01"; //Iva Trasladado No Cobrado
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = $iva;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						
							// Se registra la Ventas
							if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
								dol_syslog("El cliente si tiene cuenta asingada=".$cuenta);
								//Se recibe el pago contra el cliente (NO Caja, No Bancos)
								//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
								//El cliente tiene definida una cuenta para referenciar acientos contables
								$codagr = "";
								// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
							} else {
								dol_syslog("El cliente no tiene cuenta asignada");
								//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
								$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
							}
							$debe = 0;
							$haber = $total_ci;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						
							//Se registra el IVA Trasladado Cobrado por que fue a Crédito
							$rel->fetch_by_code("IVA_TRAS_COBRADO");
							//$codagr = "208.01"; //Iva Trasladado Cobrado
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = 0;
							$haber = $iva;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
						}
							
						$jj = 0;
						while ($jj < sizeof($ln[$tp])) {
							$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
							$jj++;
						}
						
						/* //Se registra el ingreso Caja o BANCOS
						if ($bank_account_number) {
							$cuenta = $bank_account_number;
						} else {
							if ($mode_reglement == 4) {
								// Se recibe el pago en Efectivo
								$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
							} else {
								// Cualquier otro valor se tomar como pago Bancario
								$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
						}
						$debe = $amount;
						$haber = 0;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
						//Se registra el IVA Trasladado No Cobrado por que fue a Crédito
						$asiento ++;
						$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
						//$codagr = "209.01"; //Iva Trasladado No Cobrado
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = ($amount / (1 + ($fac->lines[0]->tva_tx / 100))) * $fac->lines[0]->tva_tx / 100;
						$haber = 0;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
						// Se registra la Ventas
						$asiento ++;
						if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
							dol_syslog("El cliente si tiene cuenta asingada=".$cuenta);
							//Se recibe el pago contra el cliente (NO Caja, No Bancos)
							//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
							//El cliente tiene definida una cuenta para referenciar acientos contables
							$codagr = "";
							// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
						} else {
							dol_syslog("El cliente no tiene cuenta asignada");
							//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
							$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
						}
						$debe = 0;
						$haber = $amount;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
						//Se registra el IVA Trasladado Cobrado por que fue a Crédito
						$asiento ++;;
						$rel->fetch_by_code("IVA_TRAS_COBRADO");
						//$codagr = "208.01"; //Iva Trasladado Cobrado
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = 0;
						$haber = ($amount / (1 + ($fac->lines[0]->tva_tx / 100))) * $fac->lines[0]->tva_tx / 100;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid); */
					}
				} else if ($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
					if (!$mismo_iva) {
						dol_syslog("Pago En Partes con IVAS Iguales- ".$cond_pago);
						dol_syslog("Se supone que no debío haber llegado aquí por que hay un detente en el triguer para facturas 50/50 con IVAS diferentes.");
					} else if ($mismo_iva) {
						dol_syslog("Pago En Partes con IVAS Iguales- ".$cond_pago);
							
						$tp = $this::POLIZA_DE_INGRESO;
						$concepto = "Ingreso por cobro de Venta a Crédito, Según Factura ".$facref;
						$comentario = "Pago de Factura a Cliente con fecha del ".date("Y-m-d", $fecha);
		
						$res = $this->fetch_by_factura_Y_TipoPoliza($facid, $tp, 1);
						if ($res == 1) {
							//La póliza ya existe por lo tanto se tiene que generar la otra póliza de Ingreso que cancele la póliza de diario
		
							dol_syslog("Se genera la Póliza de Ingreso que Cancela a la de Diario");
							$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
		
							$cuenta = "";
							$codagr = "";
		
							$sub_total = 0;
							$total = 0;
							$descto = 0;
							$iva = 0;
							foreach($fac->lines as $j => $l) {
								dol_syslog("remise = ".$l->fk_remise_except." total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
								if ($l->fk_remise_except > 0) {
									$descto += abs($l->total_ht);
								} else {
									$sub_total += $l->subprice * $l->qty;
									$descto += $l->subprice * $l->qty * $l->remise_percent / 100;
								}
								if ($l->tva_tx > 0) {
									$tasa_gral = $l->tva_tx / 100;
								} else {
									$tasa_gral = 0;
								}
								dol_syslog("sub_total=$sub_total, descto=$descto");
							}
							$total_si =  $sub_total - $descto;
							$iva = $total_si * $tasa_gral;
							$total_ci = $total_si + $iva;
		
							//Se registra el ingreso Caja o Bancos
							$asiento = 1;
							if ($bank_account_number) {
								$cuenta = $bank_account_number;
							} else {
								if ($mode_reglement == 4) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = $total_ci / 2;
							$haber = 0;
							$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
							if ($tasa_gral > 0) {
								//Se registra el IVA Trasladado NO Cobrado por que fue al contado
								$asiento ++;
								$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
								//$codagr = "209.01"; //Iva Trasladado NO Cobrado
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = $iva / 2;
								$haber = 0;
								$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
							}
		
							// Se registra la Ventas anticipada
							// Se requiere saber si es un cliente nacional o extranjero.
							$asiento ++;
							dol_syslog("fac-socid=".$fac->socid);
							//Se registra el ingreso a Clientes
							if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
								//Se recibe el pago contra el cliente (NO Caja, No Bancos)
								//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
								//El cliente tiene definida una cuenta para referenciar acientos contables
								$codagr = "";
								// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
							} else {
								//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
								$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
							}
							$debe = 0;
							$haber = $total_ci / 2;
							$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
							if ($fac->lines[0]->tva_tx > 0) {
								//Se registra el IVA Trasladado Cobrado por que fue al contado
								$asiento ++;
								$rel->fetch_by_code("IVA_TRAS_COBRADO");
								//$codagr = "208.01"; //Iva Trasladado Cobrado
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $iva /2;
								$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
							}
						} else {
							//La póliza de Ingreso no existe por lo tanto es el primer pago realizad por la mitad.
							dol_syslog("Se genera la Póliza de Ingreso que Cubre la Mitad inicial.  Lo otro fue a Crédito");
							$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
		
							$cuenta = "";
							$codagr = "";
		
							$sub_total = 0;
							$total = 0;
							$descto = 0;
							$iva = 0;
							foreach($fac->lines as $j => $l) {
								dol_syslog("remise = ".$l->fk_remise_except." total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
								if ($l->fk_remise_except > 0) {
									$descto += abs($l->total_ht);
								} else {
									$sub_total += $l->subprice * $l->qty;
									$descto += $l->subprice * $l->qty * $l->remise_percent / 100;
								}
								if ($l->tva_tx > 0) {
									$dscto_tasa_gral = 1;
									$tasa_gral = $l->tva_tx / 100;
								} else {
									$tasa_gral = 0;
									$dscto_tasa_gral = 0;
								}
								dol_syslog("sub_total=$sub_total, descto=$descto");
							}
							$total_si =  $sub_total - $descto;
							$iva = $total_si * $tasa_gral;
							$total_ci = $total_si + $iva;
		
							//Ya fue pagada la mitad?  ==> es que esa mitad se supone que fue al contado, por lo tanto mientras no se haya
							//								pagado esa mitad, todo lo que se cobre será enviado como ingreso por venta al contado.
							//	SI, ya fue pagada => todo lo que se cobre, será enviado como contrapartida para la poliza de Diario que se generó
							//      al momento de validar la factura.
							//$poldet = new Contabpolizasdet($this->db);
							//$poldet->Get_Pagos_Registrados_En_Polizas_Asiento1($facid, $tp);
							//$debe_total = $poldet->debe_total;
		
							//La venta es 50% a credito y 50% al contado.
							//8. El cliente paga la mitad ahorita y la mitad después.
		
							//la Venta es 50% al Contado y 50% a crédito
							// Se registra una Venta 50% al Contado y 50% a Credito y 10% de Desc.
		
							// Póliza de Diario
							// Clientes				52.20
							// Descto s/vtas		 5.00
							// 		Ventas					50.00
							//		IVA x Trasladar			 7.20
		
							// print "Tipo poliza=".$tp." cons=".$cons;
		
							//Se registra el ingreso Caja o Bancos
							// Es una póliza de Ingreso (se trata de una venta a crédito por la Mital (50 / 50)
							$asiento = 1;
							if ($bank_account_number) {
								$cuenta = $bank_account_number;
							} else {
								if ($mode_reglement == 4) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = $total_ci / 2;
							$haber = 0;
							$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
							// Se registra el Descuento sobre Ventas
							dol_syslog("Remise percent=".$line->remise_percent);
							if ($descto > 0) {
								$asiento ++;
								if ($dscto_tasa_gral == 1) {
									$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
									//$codagr = "402.01"; //Dscto a Tasa General
								} else {
									$rel->fetch_by_code("DEV_VTA_TASA_0");
									//$codagr = "402.02"; //Dscto a Tasa 0%
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = $descto / 2;
								$haber = 0;
								$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
							}
		
							// Se registra la Ventas
							$asiento ++;
							if ($dscto_tasa_gral == 1) {
								$rel->fetch_by_code("VENTAS_TASA_GRAL");
								//$codagr = "401.01"; //Venta a Tasa General
							} else {
								$rel->fetch_by_code("VENTAS_TASA_0");
								//$codagr = "401.04"; //Venta a Tasa 0%
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = 0;
							$haber = $sub_total / 2;
							$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
							if ($tasa_gral > 0) {
								//Se registra el IVA Trasladado Cobrado por que fue al contado
								$asiento ++;
								$rel->fetch_by_code("IVA_TRAS_COBRADO");
								//$codagr = "208.01"; //Iva Trasladado Cobrado
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $iva / 2;
								$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
							}
						}
					}
				}
			} else if ($fac->type == $fac::TYPE_CREDIT_NOTE) {
		
				dol_syslog("FACTURA NOTA DE CREDITO CLIENTE =======>");
		
				//$total_nc = abs($fac->total_ttc);
		
				//Obtener los datos de la factura original sobre la que se está haciendo la Nota de Crédito o Devolución.
				$fac_source = new factures($this->db);
				$fac_source->fetch($fac->fk_facture_source);
		
				dol_syslog("Mode Reglement: ".$mode_reglement);
					
				// NOTA DE CRÉDITO
				$tp = $this::POLIZA_DE_DIARIO;
				$concepto = "Devoluciones sobre Ventas, Según Nota de Crédit:  ".$facref.". Factura: ".$fac_source->ref;
		
				//Obtener todos los pagos realizados a la Factura Original, para saber a que cuentas se deberá de afectar la Devolución.
				$sql = 'SELECT SUM(pf.amount) as amount';
				$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
				$sql .= ' WHERE pf.fk_facture = ' . $fac_source->id . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid';
				$sql .= ' ORDER BY p.datep, p.tms';
		
				$pagos_realizados = 0;
				$result = $this->db->query($sql);
				if ($result) {
					$objp = $this->db->fetch_object($result);
					if ($objp) {
						$pagos_realizados = abs($objp->amount);
					}
					$this->db->free($result);
				}
		
				//Obtener el pago realizado o los datos de la NC
				/* $sql = 'SELECT SUM(pf.amount) as amount';
				$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
				$sql .= ' WHERE pf.fk_facture = ' . $facid . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_paiement = '.$paimid;
				$sql .= ' ORDER BY p.datep, p.tms';
					
				$amount = 0;
				$result = $this->db->query($sql);
				if ($result) {
					$objp = $this->db->fetch_object($result);
					if ($objp) {
						$amount = abs($objp->amount);
					}
					$this->db->free($result);
				} */
				
				$sql = 'SELECT pf.amount as amount, ifnull(ba.account_number, "") as account_number ';
				$sql .= ' FROM '.MAIN_DB_PREFIX.'c_paiement as c, '.MAIN_DB_PREFIX.'paiement_facture as pf, '.MAIN_DB_PREFIX.'paiement as p ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank as b ON p.fk_bank = b.rowid ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank_account as ba ON b.fk_account = ba.rowid ';
				$sql .= ' WHERE p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_facture = '.$facid.' AND pf.fk_paiement = '.$paimid;
				
				$amount = 0;
				$bank_account_number = "";
				$result = $this->db->query($sql);
				if ($result) {
					$objp = $this->db->fetch_object($result);
					if ($objp) {
						$amount = abs($objp->amount);
						$bank_account_number = $objp->account_number;
					}
					$this->db->free($result);
				}
				
				$total = $amount / (1 + ($fac_source->lines[0]->tva_tx) / 100);
				$iva = $amount - $total;
		
				$falta_pagar = $fac_source->total_ttc - $pagos_realizados;
		
				dol_syslog("Busquda de Pagos = ".$sql);
		
				//Ver si el pago es al contado, credito, cobro anticipado, 50 y 50.
				$payment = new Contabpaymentterm($this->db);
				$payment->fetch($fac_source->fk_cond_reglement);
				$cond_pago = $payment->cond_pago;
		
				$this->fetch_last_by_tipo_pol($tp);
				$cons = $this->cons + 1;
					
				$this->initAsSpecimen();
		
				$this->fecha = date("Y-m-d", $fecha);
				$this->anio = date("Y",$fecha);
				$this->mes = date("m",$fecha);
				$this->concepto = $concepto;
				$this->comentario = "Nota de Crédito al Cliente con fecha del ".date("Y-m-d",$fecha);
				$this->tipo_pol = $tp;
				$this->cons = $cons;
				$this->fk_facture = $facid;
				$this->societe_type = 1;
					
				$this->create($user);
					
				$polid = $this->id;
		
				//Ahora se crearán los asientos contables para la póliza.
				$ln = array();
				//foreach ($fac->lines as $j => $line) {
					
				$cuenta = "";
				$codagr = "";
					
				if($cond_pago == $payment::PAGO_AL_CONTADO || $cond_pago == $payment::PAGO_ANTICIPADO) {
					//La venta fue 100% a credito.
		
					// Se registra el Descuento sobre Ventas
					dol_syslog("Pago en al Contado o Anticipado - ".$cond_pago);
		
					$asiento = 1;
					$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
					//$codagr = "402.01"; //Devolución sobre Venta a Tasa General
					$cat->fetch($rel->fk_cat_cta);
					$cuenta = $cat->cta;
					$debe = $total;
					$haber = 0;
					//$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
					if ($fac_source->lines[0]->tva_tx > 0) {
						//Se registra el IVA Trasladado Cobrado por que fue al Contado
						$asiento ++;
						$rel->fetch_by_code("IVA_TRAS_COBRADO");
						//$codagr = "208.01"; //Iva Trasladado Cobrado
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = $iva;
						$haber = 0;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
					}
		
					$asiento ++;
					if ($bank_account_number) {
						$cuenta = $bank_account_number;
					} else {
						if ($mode_reglement == 4) {
							// Se recibe el pago en Efectivo
							$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
						} else {
							// Cualquier otro valor se tomar como pago Bancario
							$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
					}
					$debe = 0;
					$haber = $amount;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
				} else if($cond_pago == $payment::PAGO_A_CREDITO || $cond_pago == $payment::PAGO_EN_PARTES) {
					//La venta fue 100% a credito.
		
					// Se registra el Descuento sobre Ventas
		
					dol_syslog("Pago a Acredito o En Partes - ".$cond_pago);
		
					$monto_credito = 0;
					$monto_efectivo = 0;
					if ($falta_pagar >= $amount) {
						$monto_credito = $amount;
					} else {
						$monto_efectivo = $falta_pagar;
						$monto_credito = $amount - $monto_efectivo;
					}
		
					dol_syslog("Datos total factura=".$fac_source->total_ttc.", total pagado=$pagos_realizados, falta pagar=$falta_pagar, cantidad NC=$amount, Total=$total, iva=$iva, monto efvo=$monto_efectivo, monto cred=$monto_credito");
		
					$asiento = 1;
					$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
					//$codagr = "402.01"; //Devolución sobre Venta a Tasa General
					$cat->fetch($rel->fk_cat_cta);
					$cuenta = $cat->cta;
					$debe = $total;
					$haber = 0;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
					if ($iva > 0) {
						//Se registra el IVA Trasladado Cobrado por que fue al contado
						$asiento ++;
						$rel->fetch_by_code("IVA_TRAS_COBRADO"); //$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
						//$codagr = "209.01"; //Iva Trasladado No Cobrado
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = $iva;
						$haber = 0;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
					}
		
					if ($monto_efectivo > 0) {
						$asiento ++;
						if ($bank_account_number) {
							$cuenta = $bank_account_number;
						} else {
							if ($mode_reglement == 4) {
								// Se recibe el pago en Efectivo
								$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
							} else {
								// Cualquier otro valor se tomar como pago Bancario
								$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
						}
						$debe = 0;
						$haber = $monto_efectivo;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
					}
		
					$asiento ++;
					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
						//El cliente tiene definida una cuenta para referenciar acientos contables
						$codagr = "";
						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
					} else {
						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
						$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
					}
					$debe = 0;
					$haber = $monto_credito;
					//$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
				}
			} if ($fac->type == $fac::TYPE_DEPOSIT) {
				
				dol_syslog("FACTURA DE PAGO ANTICIPADO:: ".$fac->fk_mode_reglement.", tva_tx=".$fac->lines[0]->tva_tx.", Socid=".$fac->socid);
				
				/* $sql = 'SELECT SUM(pf.amount) as amount';
				$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
				$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
				$sql .= ' WHERE pf.fk_facture = ' . $facid . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_paiement = '.$paimid;
				$sql .= ' ORDER BY p.datep, p.tms';
				
				$amount = 0;
				$result = $this->db->query($sql);
				if ($result) {
					$objp = $this->db->fetch_object($result);
					if ($objp) {
						$amount = abs($objp->amount);
					}
					$this->db->free($result);
				} */
				
				$sql = 'SELECT pf.amount as amount, ifnull(ba.account_number, "") as account_number ';
				$sql .= ' FROM '.MAIN_DB_PREFIX.'c_paiement as c, '.MAIN_DB_PREFIX.'paiement_facture as pf, '.MAIN_DB_PREFIX.'paiement as p ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank as b ON p.fk_bank = b.rowid ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank_account as ba ON b.fk_account = ba.rowid ';
				$sql .= ' WHERE p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_facture = '.$facid.' AND pf.fk_paiement = '.$paimid;
				
				$amount = 0;
				$bank_account_number = "";
				$result = $this->db->query($sql);
				if ($result) {
					$objp = $this->db->fetch_object($result);
					if ($objp) {
						$amount = abs($objp->amount);
						$bank_account_number = $objp->account_number;
					}
					$this->db->free($result);
				}
				
				$total = $amount / (1 + ($fac->lines[0]->tva_tx) / 100);
				$iva = $amount - $total;
					
				$cuenta = "";
				$codagr = "";
		
				//La venta es cobrada por Adelantado.
		
				// print "Tipo poliza=".$tp." cons=".$cons;
		
				$tp = $this::POLIZA_DE_INGRESO;
				$concepto = "Ingreso por Venta a Cliente cobrada por Adelantado, Según Factura ".$facref;
				$comentario = "Pago de Factura a Cliente con fecha del ".date("Y-m-d", $fecha);
		
				$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
		
				$cuenta = "";
				$codagr = "";
				
				//Se registra el ingreso Caja o Bancos
				$asiento = 1;
				if ($bank_account_number) {
					$cuenta = $bank_account_number;
				} else {
					if ($mode_reglement == 4) {
						// Se recibe el pago en Efectivo
						$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
					} else {
						// Cualquier otro valor se tomar como pago Bancario
						$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
					}
					$cat->fetch($rel->fk_cat_cta);
					$cuenta = $cat->cta;
				}
				$debe = $amount;
				$haber = 0;
				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
				if ($fac->lines[0]->tva_tx > 0) {
					//Se registra el IVA Trasladado NO Cobrado por que fue al contado
					$asiento ++;
					$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
					//$codagr = "209.01"; //Iva Trasladado NO Cobrado
					$cat->fetch($rel->fk_cat_cta);
					$cuenta = $cat->cta;
					$debe = $iva;
					$haber = 0;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
				}
		
				// Se registra la Ventas anticipada
				// Se requiere saber si es un cliente nacional o extranjero.
				$asiento ++;
				dol_syslog("fac-socid=".$fac->socid);
				if ($this->Get_Cliente_Proveedor_Pais($fac->socid) == $country_id) {
					$rel->fetch_by_code("ANT_CTE_NAL");
					//$codagr = "206.01"; //Nacional
				} else {
					$rel->fetch_by_code("ANT_CTE_EXT");
					//$codagr = "206.02"; //Extranjero
				}
				$cat->fetch($rel->fk_cat_cta);
				$cuenta = $cat->cta;
				$debe = 0;
				$haber = $amount;
				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
				if ($fac->lines[0]->tva_tx > 0) {
					//Se registra el IVA Trasladado Cobrado por que fue al Contado
					$asiento ++;
					$rel->fetch_by_code("IVA_TRAS_COBRADO");
					//$codagr = "208.01"; //Iva Trasladado Cobrado
					$cat->fetch($rel->fk_cat_cta);
					$cuenta = $cat->cta;
					$debe = 0;
					$haber = $iva;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
				}
		
			}
		}
	}
	
	public function Proveedor_Compra_a_Credito($object, $user, $conf) {
		//var_dump($object);
		 $ret = $this->Proveedor_Compra_a_Credito2($object->id, $user, $conf);
		 return $ret;
	}
	
	public function Proveedor_Compra_a_Credito2($facid, $user, $conf) {
		dol_syslog("Estoy en Proveedor_Compra_a_Credito2");
		
		$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
		$country_id=$tmp[0];
			
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
		$prod = new Product($this->db);
		
		$fac = new FactureFournisseurs($this->db);
		//var_dump($facid);
		$fac->fetch($facid);
		$facref = $fac->ref;
		$socid = $fac->so;
			
		$mismo_iva = true;
		foreach ($fac->lines as $i => $line) {
			dol_syslog("Valor de i = $i");
			if ($i == 0) {
				$tva_tx = $line->tva_tx;
			} else {
				if ($tva_tx != $line->tva_tx) {
					$mismo_iva = false;
				}
			}
			dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
		}
			
		$cond_pago = 1;
		$payment = new Contabpaymentterm($this->db);
		$payment->fetch($fac->cond_reglement_id);
		if ($payment->cond_pago) {
			$cond_pago = $payment->cond_pago;
		}
		
		//Que tipo de poliza se trata?
		dol_syslog("COMPRA A CREDITO - Proveedor_Compra_a_Credito() - Fecha Fact=".$fac->date);
		dol_syslog("Tipo Factura=".$fac->type.", cond_reglement_id=".$fac->cond_reglement_id.", cond_pago=".$cond_pago);
		$tipo_pol = "";
		$concepto = "";
			
		if ($fac->type == $fac::TYPE_STANDARD) {
			if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
				//la Compra es a Credito
				// 2. Pago al Proveedor en 30 dias.
				// 3. Pago en 30 dias Final del Mes.
				// 4. Pago en 60 dias.
				// 5. Pago en 60 dias Final del Mes.
				$tp = $this::POLIZA_DE_DIARIO;
				$concepto = "Compra al Proveedor a Crédito, Según Factura ".$facref;
			} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
				//La venta es 50% a credito y 50% al contado.
				//8. El cliente paga la mitad ahorita y la mitad después.
				$tp = $this::POLIZA_DE_DIARIO;
				$concepto = "Compra al Proveedor, 50% a Crédito y 50% al Contado, Según Factura ".$facref;
					
				if (!$mismo_iva) {
					$ret = -1;
					return $ret;
				}
			}
			$es_fact_servicio = false;
			foreach($fac->lines as $j => $l) {
				if ($l->product_type == 1){
					$es_fact_servicio = true;
				}
			}
			if ($es_fact_servicio) {
				dol_syslog("Es una factura para el pago de servicios: ".$fac->ref);
				if ($cond_pago == $payment::PAGO_A_CREDITO || $cond_pago == $payment::PAGO_EN_PARTES) {
					dol_syslog("Como es para el pago de servicio no puede ser pagada a credito o 50/50 ");
					$ret = -2;
					return $ret;
				}
			}
		}
		$exists = $this->fetch_by_factura_Y_TipoPoliza($facid, $tp, 2);
		dol_syslog("Existe la Póliza: ".($exists ? 'Si Existe' : 'No Existe'));
		if (! $exists) {
			dol_syslog("No existe la póliza...");
			if($fac->type == $fac::TYPE_STANDARD && ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO || $cond_pago == Contabpaymentterm::PAGO_EN_PARTES )) {
				//$pol = new Contabpolizas($this->db);
				$this->fetch_last_by_tipo_pol($tp);
				$cons = $this->cons + 1;
				// print "<br><br> ******* Se obtiene el consecutivo = ".$cons;
		
				$this->initAsSpecimen();
		
				$this->fecha = date("Y-m-d", $fac->date);
				$this->anio = date("Y", $fac->date);
				$this->mes = date("m",$fac->date);
				$this->concepto = $concepto;
				$this->comentario = "Factura a Proveedor con fecha del ".date("Y-m-d", $fac->date); //,$fac->date);
				$this->tipo_pol = $tp;
				$this->cons = $cons;
				$this->fk_facture = $facid;
				$this->societe_type = 2;
					
				$this->create($user);
				$polid = $this->id;
		
				//Ahora se crearán los asientos contables para la póliza.
				$ln = array();
				foreach ($fac->lines as $j => $line) {
					dol_syslog("remise_excent".$fac->lines[$j]->fk_remise_except);
					if (! $fac->lines[$j]->fk_remise_except > 0) {
						dol_syslog("Line id = ".$line->rowid." - ".$fac->lines[$j]->rowid);
		
						//Analizando si hay descuento sobre compras
						$sub_total = $line->pu_ht * $line->qty;
						$descto = ($sub_total * $line->remise_percent / 100); // + $dscto_ht;
						$total = $sub_total - $descto;
						$iva = $total * $line->tva_tx / 100;
							
						$cuenta = "";
						$codagr = "";
						//Es poliza de Ingreso cuando se hace una venta al contado o con cheque al contado.
						//Es poliza de Egresos cuando se hace un pago con Cheque o se emite un cheque.
						//Es de Diario cuando se trata de cualquier otro tipo de poliza.
						if($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
							dol_syslog("PAGO A CREDITO");
							//La compra es 100% a credito.
		
							// Póliza de Diario
							// Compras				104.40
							// IVA Acred Pagado		 10.00
							// 		Proveedores				100.00
							//		IVA Pend. Pago			 14.40
		
							// print "Tipo poliza=".$tp." cons=".$cons;
							
							$cta_activo = false;
							$prod->fetch($line->fk_product);
							$num_cta = $prod->accountancy_code_buy;
							if ($num_cta) {
								$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture = $facid And rowid_line = ".$line->rowid;
								dol_syslog("Buscando la factura y linea que se está procesando, sql=$sql");
								if ($res = $this->db->query($sql)) {
									dol_syslog("Entre al RES");
									if ($row = $this->db->fetch_object($res)) {
										dol_syslog("Entre al ROW");
										if ($cat->fetch($row->fk_cat_cta)) {
											dol_syslog("Se obtiene del catálogo de cuentas los datos obtenidos de la factura y linea.  num_cta=$num_cta, cat->cta=".$cat->cta);
											if (substr($num_cta, 0, strlen($cat->cta)) == $cat->cta) {
												dol_syslog("El producto de la factura/Linea capturados, si pertenece al proveedor de activo relacionado con el query anterior.");
												//Con esto nos indica que debemos de relacionar la compra con Activo Fijo, no con Compras Nacionales o Compras al Extranjero.
												$cta_activo = true;
											}
										}
									}
								}
							}
							
							// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
							if ($cta_activo) {
								$cuenta = $num_cta;
							} else {
								if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
									$rel->fetch_by_code("COMP_NAL");
									//$codagr = "502.01";
								} else {
									$rel->fetch_by_code("COMP_EXT");
									//$codagr = "502.03";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = $sub_total;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							//Se registra el IVA Acred No Pagado
							$asiento ++;
							if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
								$rel->fetch_by_code("IVA_PEND_PAGO");
								//$codagr = "119.01";
							} else {
								$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
								//$codagr = "119.02";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = $iva;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
								//Se recibe el pago contra el cliente (NO Caja, No Bancos)
								//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
								//El cliente tiene definida una cuenta para referenciar acientos contables
								$codagr = "";
								// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
							} else {
								//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
								$cuenta = $this->Get_Proveedor_Cuenta($fac->fk_soc, $conf);
							}
							$debe = 0;
							$haber = $total + $iva;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							//Analizando si hay descuento sobre compras
							if ($descto > 0 ) {
								$asiento ++;
								$rel->fetch_by_code("DEV_COMP");
								//$codagr = "503.01";
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $descto;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							}
		
						} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
							dol_syslog("PAGO EN PARTES");
							//la Venta es 50% al Contado y 50% a crédito
							// Se registra una Venta 50% al Contado y 50% a Credito y 10% de Desc.
		
							// Póliza de Diario
							// Compras				52.20
							// IVA Acred Pag		 5.00
							// 		Proveedores				50.00
							//		Dev y Rev s/vtas		 7.20
		
							//Se registra el ingreso a Clientes
							$cta_activo = false;
							$prod->fetch($line->fk_product);
							$num_cta = $prod->accountancy_code_buy;
							if ($num_cta) {
								$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture = $facid And rowid_line = $line->rowid";
								dol_syslog("Buscando la factura y linea que se está procesando, sql=$sql");
								if ($res = $this->db->query($sql)) {
									dol_syslog("Entre al RES");
									if ($row = $this->db->fetch_object($res)) {
										dol_syslog("Entre al ROW");
										if ($cat->fetch($row->fk_cat_cta)) {
											dol_syslog("Se obtiene del catálogo de cuentas los datos obtenidos de la factura y linea.");
											if (substr($num_cta, 0, strlen($cat->cta)) == $cat->cta) {
												dol_syslog("El producto de la factura/Linea capturados, si pertenece al proveedor de activo relacionado con el query anterior.");
												//Con esto nos indica que debemos de relacionar la compra con Activo Fijo, no con Compras Nacionales o Compras al Extranjero.
												$cta_activo = true;
											}
										}
									}
								}
							}
							
							// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
							if ($cta_activo) {
								$cuenta = $num_cta;
							} else {
								if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
									$rel->fetch_by_code("COMP_NAL");
									//$codagr = "502.01";
								} else {
									$rel->fetch_by_code("COMP_EXT");
									//$codagr = "502.03";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = $sub_total /2;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							//Se registra el IVA Acred No Pagado
		
							$asiento ++;
							if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
								$rel->fetch_by_code("IVA_PEND_PAGO");
								//$codagr = "119.01";
							} else {
								$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
								//$codagr = "119.02";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
							$debe = $iva / 2;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
								//Se recibe el pago contra el cliente (NO Caja, No Bancos)
								//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
								//El cliente tiene definida una cuenta para referenciar acientos contables
								$codagr = "";
								// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
							} else {
								//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
								$cuenta = $this->Get_Cliente_Cuenta($fac->fk_soc, $conf);
							}
							$debe = 0;
							$haber = ($total + $iva) / 2;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		
							//Analizando si hay descuento sobre compras
							if ($descto > 0 ) {
								$asiento ++;
								$rel->fetch_by_code("DEV_COMP");
								//$codagr = "503.01";
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $descto / 2;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							}
						}
					} else { dol_syslog("Esta línea no se debe procesar"); }
				}
					
				$jj = 0;
				while ($jj < sizeof($ln[$tp])) {
					$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
					$jj++;
				}
			}
		}
	}
	
	public function Proveedor_Pago_Factura($object, $user, $conf) {
		$this->Proveedor_Pago_Factura2($object->id, $user, $conf);
	}
	
	public function Proveedor_Pago_Factura2($paimid, $user, $conf) {
		dol_syslog("Función: Proveedor_Pago_Factura");
			
		dol_syslog("Búsqueda de Paiments - getBillsArray(), paimid = ".$paimid);
			
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
		$prod = new Product($this->db);
			
		$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
		$country_id=$tmp[0];
			
		$pago_efectivo = false;
		$paim = new PaiementFourns($this->db);
		$paim->fetch($paimid);
		if ($paim->type_code == "LIQ") {
			$pago_efectivo = true;
		}
		$monto = $paim->montant;
		
		dol_syslog("Pago en efectivo=".($pago_efectivo ? 'efectivo' : 'bancos')." por el monto de = $monto");
		$fecha = $paim->date;
		
		$paim->id = $paimid;
		$a_fac = $paim->getBillsArray();
		
		foreach ($a_fac as $idx => $value) {
		
			dol_syslog("Búsqueda de la Factura a Proveedor - country_id=$country_id");
			
			$facid = $value;
			
			$fac = new FactureFournisseurs($this->db);
			$fac->fetch($facid);
			$facref = $fac->ref;
			$facid = $fac->id;
			
			//$mismo_iva = Ver_si_los_items_tienen_mismo_iva($fac);
			
			$mismo_iva = true;
			foreach ($fac->lines as $i => $line) {
				dol_syslog("Valor de i = $i");
				if ($i == 0) {
					$tva_tx = $line->tva_tx;
				} else {
					if ($tva_tx != $line->tva_tx) {
						$mismo_iva = false;
					}
				}
				dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
			}
			
			$sup = new Contabctassupplier($this->db);
			$a_sup = $sup->fetch_array_by_socid($fac->fk_soc);
			
			//Se obtienen los ids de cada una de las lineas de la factura ( o sea los ids de factura detalle)
			$a_lines_ids = array();
			foreach ($fac->lines as $i => $line) {
				$a_lines_ids[] = $line->rowid;
			}
			$str_lines_ids = implode(",", $a_lines_ids);
			
			dol_syslog("FACREF = ".$facref." FACID=".$facid." Fac Type=".$fac->type.", cond_reglement_id=".$fac->cond_reglement_id);
			
			if ($fac->type == $fac::TYPE_STANDARD) {
				
				dol_syslog("Función: Proveedor_Pago_Factura:: FACTURA STANDARD");
				
				//Obtener todos los pagos realizados a la Factura Original, para saber a que cuentas se deberá de afectar la Devolución.
				// Payments already done (from payment on this invoice)
				/* $sql = "SELECT SUM(pf.amount) AS amount ";
				$sql .= "FROM ".MAIN_DB_PREFIX."c_paiement AS c, ".MAIN_DB_PREFIX."paiementfourn_facturefourn AS pf, ".MAIN_DB_PREFIX."paiementfourn AS p ";
				$sql .= "WHERE pf.fk_facturefourn = ".$facid." AND p.fk_paiement = c.id AND pf.fk_paiementfourn = p.rowid AND pf.fk_paiementfourn = ".$paimid;
				$sql .= ' ORDER BY p.datep, p.tms'; */
				
				$sql = 'SELECT pf.amount AS amount, ifnull(ba.account_number,"") as account_number ';
				$sql .= ' FROM '.MAIN_DB_PREFIX.'c_paiement AS c, '.MAIN_DB_PREFIX.'paiementfourn_facturefourn AS pf, '.MAIN_DB_PREFIX.'paiementfourn AS p ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank as b ON p.fk_bank = b.rowid ';
				$sql .= ' LEFT JOIN '.MAIN_DB_PREFIX.'bank_account as ba ON b.fk_account = ba.rowid ';
				$sql .= ' WHERE p.fk_paiement = c.id AND pf.fk_paiementfourn = p.rowid AND pf.fk_facturefourn = '.$facid.' AND pf.fk_paiementfourn = '.$paimid;
				
				dol_syslog("Obtrención de los Pagos realizados a la Fact. Original - sql:".$sql);
				$amount = 0;
				$bank_account_number = '';
				$result = $this->db->query($sql);
				if ($result) {
					$objp = $this->db->fetch_object($result);
					if ($objp) {
						$amount = $objp->amount;
						$bank_account_number = $objp->account_number;
					}
					$this->db->free($result);
				}
				
				$fac = new FactureFournisseurs($this->db);
				$fac->fetch($facid);
				$facref = $fac->ref;
				$facid = $fac->id;
				
				$anombrede = "";
				$numcheque = "";
					
				if ($fac->mode_reglement_id == 4) {
					// Se hace el pago en Efectivo
					$tp = $this::POLIZA_DE_EGRESO;
				} else {
					// Cualquier otro valor se tomar como pago Bancario
					$tp = $this::POLIZA_DE_CHEQUES;
					$anombrede = "";
					$numcheque = "";
				}
				
				//Ver si el pago es al contado, credito, cobro anticipado, 50 y 50.
				$payment = new Contabpaymentterm($this->db);
				$payment->fetch($fac->cond_reglement_id);
				$cond_pago = $payment->cond_pago;
				
				//$total = $amount / (1 + ($fac->lines[0]->tva_tx) / 100);
				//$iva = $amount - $total;
				
				dol_syslog("amount=$amount, tva_tx=".$fac->lines[0]->tva_tx);
				
				if ($cond_pago == Contabpaymentterm::PAGO_AL_CONTADO) {
					
					$ln = array();
					
					dol_syslog("Función: Proveedor_Pago_Factura:: PAGO_AL_CONTADO - ".$cond_pago);
					
					$es_fact_servicio = false;
					foreach($fac->lines as $j => $l) {
						dol_syslog("====Tipo de Producto=".$l->product_type." --- ".($l->product_type == 1 ? "Es uno" : "Es otro valor"));
						if ($l->product_type == 1){
							dol_syslog("Esta entrando a cambiar a true el varlo de es_fact_servicio.........");
							$es_fact_servicio = true;
						}
					}
					
					if (! $es_fact_servicio) {
						//La factura ya fue pagada en su totalidad?
						
						$pol_exists = false;
						if ($this->fetch_next_by_facture_id(0, $fac->id)) {
							//La factura al ser al contado puede tener pagos de varios tipos (Efectivo, Cheque, Trans, Tarjeta, etc)
							//Solo hay que actualizar el dato del asiento de salida de efectivo o bancos según sea el caso.
							$pol_exists = true;
							$polid = $this->id;
						}
						if ($pol_exists) {
							dol_syslog("Función:: Proveedor_Pago_Factura:: Pago de Mercancía - Poliza YA Existe, así que solo se crea el asiento que hace falta");
							//La poliza ya existe por que pudieron haber existido varios pagos a la misma factura por diferentes montos
							//para saldar la factura, ya que pudo haber pagado con tarjeta, cheque, etc.
							if ($bank_account_number) {
								$cuenta = $bank_account_number;
							} else {
								if ($fac->mode_reglement_id == 4 || $pago_efectivo) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = 0;
							$haber = $monto;
							
							$poldet = new Contabpolizasdet($this->db);
							$poldet->fetch_last_asiento_by_num_poliza($polid);
							$poldet->asiento += 1;
							$poldet->cuenta = $cuenta;
							$poldet->debe = $debe;
							$poldet->haber = $haber;
							$poldet->fk_poliza = $polid;
							
							$poldet->create($user);
						} else {
							//La póliza no existe.
							// Se debe obtener el monto por el cual se va a realizar el cargo a la cuenta de Efectivo o Cheques.
							//Es el pago de una compra de Mercancía al Proveedor
							dol_syslog("Función:: Proveedor_Pago_Factura:: Pago de Mercancía - Poliza no Existe");
							foreach($fac->lines as $j => $l) {
								
								$sub_total = $l->pu_ht * $l->qty;
								$descto = $l->pu_ht * $l->qty * $l->remise_percent / 100;
								$tasa_gral = $l->tva_tx / 100;
								$iva = ($sub_total - $descto) * $tasa_gral;
								$total_si =  $sub_total - $descto;
								$total_ci = $total_si + $iva; 
								
								dol_syslog("sub_total=$sub_total, descto=$descto, tasa_gral=$tasa_gral, iva=$iva, pu_ht=".$l->pu_ht.", qty=".$l->qty." remise_perc=".$l->remise_percent);
								
								dol_syslog("Pago de Factura, Compra de mercancía");
								
								if ($j == 0) {
									$concepto = "Egreso por Compra a Proveedor al Contado, Según Factura ".$facref;
									$comentario = "Pago de Factura a Proveedor con fecha del ".date("Y-m-d", $fecha);
									
									$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, $anombrede, $numcheque, $fecha, 2);
								}
								
								//Se va a verificar si este producto está relacionado con un proveedor de activo
								
								$cta_activo = false;
								$prod->fetch($l->fk_product);
								$num_cta = $prod->accountancy_code_buy;
								if ($num_cta) {
									$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture = $facid And rowid_line = $l->rowid";
									dol_syslog("Buscando la factura y linea que se está procesando, sql=$sql");
									if ($res = $this->db->query($sql)) {
										dol_syslog("Entre al RES");
										if ($row = $this->db->fetch_object($res)) {
											dol_syslog("Entre al ROW");
											if ($cat->fetch($row->fk_cat_cta)) {
												dol_syslog("Se obtiene del catálogo de cuentas los datos obtenidos de la factura y linea.");
												if (substr($num_cta, 0, strlen($cat->cta)) == $cat->cta) {
													dol_syslog("El producto de la factura/Linea capturados, si pertenece al proveedor de activo relacionado con el query anterior.");
													//Con esto nos indica que debemos de relacionar la compra con Activo Fijo, no con Compras Nacionales o Compras al Extranjero.
													$cta_activo = true;
												}
											}
										}
									}
								}
								
								// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
								if ($cta_activo == true) {
									$cuenta = $num_cta;
								} else {
									if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
										$rel->fetch_by_code("COMP_NAL");
										//$codagr = "502.01";
									} else {
										$rel->fetch_by_code("COMP_EXT");
										//$codagr = "502.03";
									}
									$cat->fetch($rel->fk_cat_cta);
									$cuenta = $cat->cta;
								}
								$debe = $sub_total;
								$haber = 0;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
								
								dol_syslog("El valor del iva es del : $iva");
								if ($iva > 0) {
									//Se registra el IVA Acred Pagado
									if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
										$rel->fetch_by_code("IVA_ACRED_PAG");
										//$codagr = "118.01";
									} else {
										$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
										//$codagr = "118.02";
									}
									$cat->fetch($rel->fk_cat_cta);
									$cuenta = $cat->cta;
									$debe = $iva;
									$haber = 0;
									$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
								}
								
								/* //Se va a hacer el cargo por el total de lo que se haya pagado, no de lo que se está calculado,
								//Esto debido a que si hay varios pagos a la misma factura, cada tipo de pago diferente se pueda
								//ir generando en la misma póliza en su cuenta correspondiente.
								if ($fac->mode_reglement_id == 4 || $pago_efectivo) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");
									//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");
									//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $monto;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber); */
								
								//Analizando si hay descuento sobre compras
								if ($descto > 0 ) {
									$rel->fetch_by_code("DEV_COMP");
									//$codagr = "503.01";
									$cat->fetch($rel->fk_cat_cta);
									$cuenta = $cat->cta;
									$debe = 0;
									$haber = $descto;
									$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
								}
							}
							
							//Esto lo saco del ciclo por que me di cuenta que dentro del ciclo, va a ejecutar esta instrucción varias veces, una por cada artículo vendido
							//y solamente quiero que se ejectue una sola vez, por el monto que marca el pago realizado.
							
							//Se va a hacer el cargo por el total de lo que se haya pagado, no de lo que se está calculado,
							//Esto debido a que si hay varios pagos a la misma factura, cada tipo de pago diferente se pueda
							//ir generando en la misma póliza en su cuenta correspondiente.
							if ($bank_account_number) {
								$cuenta = $bank_account_number;
							} else {
								if ($fac->mode_reglement_id == 4 || $pago_efectivo) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = 0;
							$haber = $monto;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							
							$jj = 0;
							while ($jj < sizeof($ln[$tp])) {
								$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
								$jj++;
							}
						}
					} else {
						//Es un pago a un Proveedor de Servicios
						//var_dump("object");
						//var_dump($object);
						dol_syslog("Pago de factura, Pago de Servicios");
							
						$concepto = "Egreso por Pago al Contado de Servicios, Según Factura ".$facref;
						$comentario = "Pago realizado al día ".date("Y-m-d", $fecha);
		
						$ln = array();
							
						$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, $anombrede, $numcheque, $fecha, 2);
							
						//Pero primero antes que todo capturamos el dato del pago al banco o por medio de caja.
						if ($bank_account_number) {
							$cuenta = $bank_account_number;
						} else {
							if ($fac->mode_reglement_id == 4 || $pago_efectivo) {
								// Se recibe el pago en Efectivo
								$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
							} else {
								// Cualquier otro valor se tomar como pago Bancario
								$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
						}
						$debe = 0;
						$haber = $fac->total_ttc;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							
						foreach($fac->lines as $j => $l) {
							//var_dump("J=".$j);
							//var_dump ($l);
							//Buscar el numero de cuenta correspondiente a esta linea
							//Con la referencia del producto, podemos obtener la cuenta a proveedor relacionada
							//De otra forma Buscar en la tabla llx_contab_fourn_product_line y obtener el numero de cuenta contable.
							//Si aún así no hay numero de cuenta,
							//			entonces la factura se creó de una forma que no estaba planeada por este módulo
							
							$sub_total = $l->pu_ht * $l->qty;
							$descto = $l->pu_ht * $l->qty * $l->remise_percent / 100;
							$tasa_gral = $l->tva_tx / 100;
							$iva = ($sub_total - $descto) * $tasa_gral;
							$total_si =  $sub_total - $descto;
							$total_ci = $total_si + $iva;
		
							if ($l->fk_product > 0) {
								$prod->fetch($l->fk_product);
								$num_cta = $prod->accountancy_code_buy;
								if ($cat->fetch_by_Cta($num_cta) > 0) {
									//Ok parece que esta línea de servicio si tiene su numero de cuenta en la referencia del producto.
									//Obtenemos la información para generar este asiento contable.
									dol_syslog("Es la linea de servicios y tiene un numero de cuenta: $num_cta");
									$cuenta = $cat->cta;
									if ($sub_total < 0) {
										$debe = 0;
										$haber = abs($sub_total);
									} else {
										$debe = abs($sub_total);
										$haber = 0; 
									}
									$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
								} else {
									dol_syslog("*** Es linea de servicios y NO tiene un numero de cuenta asignado ****");
								}
							} else {
								//La cuenta habrá que buscarla en la tabla llx_contab_fourn_product_line
								$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture=".$facid." And rowid_line = '$l->rowid' And fourn_type = 2 ";
								$res = $this->db->query($sql);
								dol_syslog("Proveedor_Pago_Factura de Servicio:: sql:".$sql);
								if ($res) {
									//Ok si se encontró el indice que nos dará la cuenta relacionada.
									$row = $this->db->fetch_row($res);
									if ($row) {
										dol_syslog("Se encontró el indice que nos dar[a la cuenta relacionada: ".$row[3]);
										$cat->fetch($row[3]);
										$cuenta = $cat->cta;
										$debe = $sub_total;
										$haber = 0;
										$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
									} else {
										dol_syslog("*** Hubo un problema detectado, ya que no se encotró el indice ***");
									}
								} else {
									//Acaray, aqui tengo un detalle ==> El módulo no detecta este tipo de lineas en la factura realizada
									dol_syslog("*** El módulo de contabilidad tiene un bug.  No se encontró una forma de procesar esta petición.  Datos de la factura: N0.:$facid, Linea:".$l->rowid.", monto:".$l->pu_ht."*** ");
								}
							}
							if ($descto > 0 ) {
								$rel->fetch_by_code("DEV_COMP");
								//$codagr = "503.01";
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $descto;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							}
							if ($iva > 0) {
								$rel->fetch_by_code("IVA_ACRED_PAG");
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;  //$cuenta = "118.01"; //IVA Acreditable Pagado
								$debe = $iva;
								$haber = 0;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							}
						}
							
						$jj = 0;
						while ($jj < sizeof($ln[$tp])) {
							$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
							$jj++;
						}
					}
				} else if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
		
					dol_syslog("Función: Proveedor_Pago_Factura - Pago a Credito - ".$cond_pago);
		
					$concepto = "Pago de Factura a Proveedor por Compra a Crédito, Según Factura ".$facref;
					$comentario = "Pago de Factura a Proveedor con fecha del ".date("Y-m-d", $fecha);
		
					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 2);
		
					$cuenta = "";
					$codagr = "";
		
					// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
		
					//la Compra es a Crédito
					// Se registra una Venta al Contado con el 10% de Descuento
					// Compras				100.00
					// IVA Pend. Pago		 14.40
					// 		Proveedores				104.40
					//		Dev s/compra			 10.00
		
					// En este momento de Recibir el Pago, Se debe crear una póliza con la siguiente estructura:
					// Proveedores			104.40
					// IVA Acreditable		 10.00
					// 		Efectivo			100.00
					//		IVA Pend Pago		 14.40
		
					/* $sub_total = 0;
					 $total = 0;
					$descto = 0;
					$iva = 0; */
		
					/* foreach($fac->lines as $j => $l) {
					 dol_syslog("total_ht=".$l->total_ht." subprice=".$l->pu_ht." qty=".$l->qty." remise_perc=".$l->remise_percent);
					$sub_total += $l->pu_ht * $l->qty;
					$descto += $l->pu_ht * $l->qty * $l->remise_percent / 100;
					$tasa_gral = $l->tva_tx / 100;
					dol_syslog("sub_total=$sub_total, descto=$descto");
					} */
		
					/* $total_si =  $sub_total - $descto;
					 $iva = $total_si * $tasa_gral;
					 $total_ci = $total_si + $iva;  */
					$iva = 0;
					if ($mismo_iva) {
						$iva = ($amount / (1 + (16 / 100))) * 16 / 100;
					} else {
						foreach ($fac->lines as $i => $line) {
							$iva += $line->total_tva;
						}
					}
					dol_syslog("Algun dato interesante arriba? mismo iva=$mismo_iva, iva=$iva");
		
					$asiento = 1;
					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
						//El cliente tiene definida una cuenta para referenciar acientos contables
						$codagr = "";
						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
					} else {
						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
						$cuenta = $this->Get_Cliente_Cuenta($fac->fk_soc, $conf);
					}
					$debe = $amount;
					$haber = 0;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
					//Se registra el IVA Acred Pagado
					$asiento ++;
					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
						$rel->fetch_by_code("IVA_ACRED_PAG");
						//$codagr = "118.01";
					} else {
						$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
						//$codagr = "118.02";
					}
					$cat->fetch($rel->fk_cat_cta);
					$cuenta = $cat->cta;
					$debe = $iva; //($amount / (1 + (16 / 100))) * 16 / 100;
					$haber = 0;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
					$asiento ++;
					if ($bank_account_number) {
						$cuenta = $bank_account_number;
					} else {
						if ($fac->mode_reglement_id == 4 || $pago_efectivo) {
							// Se recibe el pago en Efectivo
							$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
						} else {
							// Cualquier otro valor se tomar como pago Bancario
							$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
					}
					$debe = 0;
					$haber = $amount;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
					//IVA Pendiente Pago.
					$asiento ++;
					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
						$rel->fetch_by_code("IVA_PEND_PAGO");
						//$codagr = "119.01";
					} else {
						$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
						//$codagr = "119.02";
					}
					$cat->fetch($rel->fk_cat_cta);
					$cuenta = $cat->cta;
					$debe = 0;
					$haber = $iva; //($amount / (1 + (16 / 100))) * 16 / 100;
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
				} else if ($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
		
					dol_syslog("Función: Proveedor_Pago_Factura - Pago En Partes - ".$cond_pago);
		
					$res = $this->fetch_by_factura_Y_TipoPoliza($facid, $tp, 2);
					if ($res == 1) {
						//La póliza ya existe por lo tanto se tiene que generar la otra póliza que cancele la póliza de diario
						
						$concepto = "Pago de Factura a Proveedor del 50% a crédito, Según Factura ".$facref;
						$comentario = "Pago de Factura a Proveedor con fecha del ".date("Y-m-d", $fecha);
		
						dol_syslog("Se genera la Póliza de Egreso que Cancela a la de Diario");
						$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 2);
		
						$cuenta = "";
						$codagr = "";
							
						$sub_total = 0;
						$total = 0;
						$descto = 0;
						$iva = 0;
						foreach($fac->lines as $j => $l) {
							dol_syslog("total_ht=".$l->total_ht." pu_ht=".$l->pu_ht." qty=".$l->qty." remise_perc=".$l->remise_percent);
							if ($l->fk_remise_except > 0) {
								$descto += abs($l->total_ht);
							} else {
								$sub_total += $l->pu_ht * $l->qty;
								$descto += $l->pu_ht * $l->qty * $l->remise_percent / 100;
							}
							if ($l->tva_tx > 15) {
								$dscto_tasa_gral = 1;
								$tasa_gral = $l->tva_tx / 100;
								$codagr = "402.01"; //Dscto a Tasa General
							} else {
								$tasa_gral = 0;
								$dscto_tasa_gral = 0;
								$codagr = "402.02"; //Dscto a Tasa 0%
							}
							dol_syslog("sub_total=$sub_total, descto=$descto");
						}
						$total_si =  $sub_total - $descto;
						$iva = $total_si * $tasa_gral;
						$total_ci = $total_si + $iva;
						
						/* Esta parte no esta implementada y se tiene que poner ya que si se puede adquirir activo fijo 50/50.
						// Para ello debo de hacer que todo esto este dentro del foreach de arriba.
						$cta_activo = false;
						$prod->fetch($l->fk_product);
						$num_cta = $prod->accountancy_code_buy;
						if ($num_cta) {
							$sql = "Select * From llx_contab_fourn_product_line Where fk_facture = $facid And rowid_line = $l->rowid";
							dol_syslog("Buscando la factura y linea que se está procesando, sql=$sql");
							if ($res = $this->db->query($sql)) {
								dol_syslog("Entre al RES");
								if ($row = $this->db->fetch_object($res)) {
									dol_syslog("Entre al ROW");
									if ($cat->fetch($row->fk_cat_cta)) {
										dol_syslog("Se obtiene del catálogo de cuentas los datos obtenidos de la factura y linea.");
										if (substr($num_cta, 0, strlen($cat->cta)) == $cat->cta) {
											dol_syslog("El producto de la factura/Linea capturados, si pertenece al proveedor de activo relacionado con el query anterior.");
											//Con esto nos indica que debemos de relacionar la compra con Activo Fijo, no con Compras Nacionales o Compras al Extranjero.
											$cta_activo = true;
										}
									}
								}
							}
						}
						
						// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
						if ($cta_activo) {
							$cuenta = $num_cta;
						} else {
							if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
								$rel->fetch_by_code("COMP_NAL");
								//$codagr = "502.01";
							} else {
								$rel->fetch_by_code("COMP_EXT");
								//$codagr = "502.03";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
						}
						$debe = $sub_total;
						$haber = 0;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber); */
						
						
						$asiento = 1;
						if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
							//Se recibe el pago contra el cliente (NO Caja, No Bancos)
							//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
							//El cliente tiene definida una cuenta para referenciar acientos contables
							$codagr = "";
							// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
						} else {
							//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
							$cuenta = $this->Get_Cliente_Cuenta($fac->fk_soc, $conf);
						}
						$debe = $total_ci / 2;
						$haber = 0;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
							
						//Se registra el IVA Acred Pagado
						$asiento ++;
						if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
							$rel->fetch_by_code("IVA_ACRED_PAG");
							//$codagr = "118.01";
						} else {
							$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
							//$codagr = "118.02";
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = $iva / 2;
						$haber = 0;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		
						if ($bank_account_number) {
							$cuenta = $bank_account_number;
						} else {
							if ($fac->mode_reglement_id == 4 || $pago_efectivo) {
								// Se recibe el pago en Efectivo
								$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
							} else {
								// Cualquier otro valor se tomar como pago Bancario
								$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
							}
							$cat->fetch($rel->fk_cat_cta);
							$cuenta = $cat->cta;
						}
						$debe = 0;
						$haber = $total_ci / 2;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
							
						//Se registra el IVA Acred Pend Pagao
						$asiento ++;
						if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
							$rel->fetch_by_code("IVA_PEND_PAGO");
							//$codagr = "119.01";
						} else {
							$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
							//$codagr = "119.02";
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = 0;
						$haber = $iva / 2;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
					} else {
						//La póliza de Ingreso no existe por lo tanto es el primer pago realizad por la mitad.
						dol_syslog("Se genera la Póliza de Egresos o Cheque que Cubre la Mitad inicial.  Lo otro fue a Crédito");
						
						$concepto = "Pago de Factura a Proveedor del 50% al contado, Según Factura ".$facref;
						$comentario = "Pago de Factura a Proveedor con fecha del ".date("Y-m-d", $fecha);
						
						$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 2);
						
						$cuenta = "";
						$codagr = "";
						
						$sub_total = 0;
						$total = 0;
						$descto = 0;
						$iva = 0;
						foreach($fac->lines as $j => $l) {
							$sub_total = $l->pu_ht * $l->qty;
							$descto = $l->pu_ht * $l->qty * $l->remise_percent / 100;
							$tasa_gral = $l->tva_tx / 100;
							$iva = ($sub_total - $descto) * $tasa_gral;
							$total_si =  $sub_total - $descto;
							$total_ci = $total_si + $iva;
							
							//Se va a verificar si este producto está relacionado con un proveedor de activo
							$cta_activo = false;
							$prod->fetch($l->fk_product);
							$num_cta = $prod->accountancy_code_buy;
							if ($num_cta) {
								$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture = $facid And rowid_line = $l->rowid";
								dol_syslog("Buscando la factura y linea que se está procesando, sql=$sql");
								if ($res = $this->db->query($sql)) {
									dol_syslog("Entre al RES");
									if ($row = $this->db->fetch_object($res)) {
										dol_syslog("Entre al ROW");
										if ($cat->fetch($row->fk_cat_cta)) {
											dol_syslog("Se obtiene del catálogo de cuentas los datos obtenidos de la factura y linea.");
											if (substr($num_cta, 0, strlen($cat->cta)) == $cat->cta) {
												dol_syslog("El producto de la factura/Linea capturados, si pertenece al proveedor de activo relacionado con el query anterior.");
												//Con esto nos indica que debemos de relacionar la compra con Activo Fijo, no con Compras Nacionales o Compras al Extranjero.
												$cta_activo = true;
											}
										}
									}
								}
							}
							
							// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
							if ($cta_activo == true) {
								$cuenta = $num_cta;
							} else {
								if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
									$rel->fetch_by_code("COMP_NAL");
									//$codagr = "502.01";
								} else {
									$rel->fetch_by_code("COMP_EXT");
									//$codagr = "502.03";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = $sub_total / 2;
							$haber = 0;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							
							dol_syslog("El valor del iva es del : $iva");
							if ($iva > 0) {
								//Se registra el IVA Acred Pagado
								if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
									$rel->fetch_by_code("IVA_ACRED_PAG");
									//$codagr = "118.01";
								} else {
									$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
									//$codagr = "118.02";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = $iva / 2;
								$haber = 0;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);

							}
							
							if ($bank_account_number) {
								$cuenta = $bank_account_number;
							} else {
								if ($fac->mode_reglement_id == 4 || $pago_efectivo) {
									// Se recibe el pago en Efectivo
									$rel->fetch_by_code("EFECTIVO");	//$codagr = "101.01";
								} else {
									// Cualquier otro valor se tomar como pago Bancario
									$rel->fetch_by_code("BANCOS_NAL");	//$codagr = "102.01";
								}
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
							}
							$debe = 0;
							$haber = $total_ci / 2;
							$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							
							//Analizando si hay descuento sobre compras
							if ($descto > 0 ) {
								$asiento ++;
								$rel->fetch_by_code("DEV_COMP");
								//$codagr = "503.01";
								$cat->fetch($rel->fk_cat_cta);
								$cuenta = $cat->cta;
								$debe = 0;
								$haber = $descto / 2;
								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
							}
						}
						$jj = 0;
						while ($jj < sizeof($ln[$tp])) {
							$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
							$jj++;
						}
					}
				}
			} else if ($fac->type == $fac::TYPE_CREDIT_NOTE) {
				// Hasta la Versión 3.6.2 Dolibarr no tenía implementaddo este tipo de facturas para Proveedores
			} if ($fac->type == $fac::TYPE_DEPOSIT) {
				// Hasta la Versión 3.6.2 Dolibarr no tenía implementaddo este tipo de facturas para Proveedores
			}
		}
	}
	
	public function Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, $anombrede='', $numcheque='', $fecha='', $st='', $ant_ctes = 0) {
		dol_syslog("Crea_Poliza_Enc mes:".$cfg->mes." anio:".$cfg->anio);
		$this->fetch_last_by_tipo_pol($tp);
		$cons = $this->cons + 1;
	
		$this->initAsSpecimen();
	
		$this->anio = date("Y",$fecha);
		$this->mes = date("m",$fecha);
		$this->fecha = date("Y-m-d", $fecha);
		$this->concepto = $concepto;
		$this->comentario = $comentario;
		$this->tipo_pol = $tp;
		$this->cons = $cons;
		$this->anombrede = $anombrede;
		$this->numcheque = $numcheque;
		$this->fk_facture = $facid;
		$this->societe_type = $st;
		if ($ant_ctes > 0) {
			$this->ant_ctes = $ant_ctes; 
		}
	
		$polid = -1;
		 
		if ($this->create($user)) {
			$polid = $this->id;
		}
		 
		return $polid;
	}
	
	public function Cliente_Borrar_Poliza($object, $user) {
		dol_syslog("Cliente_Borra_Poliza");
	
		$fac = new Factures($this->db);
		$fac->fetch($object->id);
		$facref = $fac->ref;
		$facid = $fac->id;
		 
		dol_syslog("** Factura encontrada: ".$fac->ref);
		$poldet = new Contabpolizasdet($this->db);
		if ($poldet->delete_by_facture($user, $facid)) {
			dol_syslog("** Se borró el Detalle de la póliza ligada a esta factura");
			if ($this->delete_by_facture($user, $facid)) {
				dol_syslog("** Se borro el Encabezado de la póliza ligada a esta factura");
			} else {
				dol_syslog("** No se pudo borrar el Encabezado de la póliza ligada a esta factura");
			}
		} else {
			dol_syslog("** No se pudo borrar el Detalle de la póliza ligada a esta factura");
		}
	}
	
	public function Cliente_Proveedor_Almacena_Poliza_Det($user, $fk_pol, $ln, $tp, $cuenta, $debe, $haber, $tasa_iva=-1) {
		dol_syslog("Cliente_Proveedor_Almacena_Poliza_Det: fk_pol=$fk_pol, tp=$tp, cuenta=$cuenta, debe=$debe, haber=$haber");
		$asiento = 0;
		if (sizeof($ln[$tp]) > 0) {
			foreach ($ln[$tp] as $jj => $line) {
				if ($asiento < $line['asiento']) {
					$asiento = $line['asiento'];
				}
			}
		}
		$asiento ++;
		 
		$jj = 0;
		$found = false;
		while ($jj < sizeof($ln[$tp])) {
			if ($ln[$tp][$jj]['cuenta'] == $cuenta) {
				$found = true;
				$j = $jj;
			}
			$jj ++;
		}
		if (! $found) {
			$ln_aux = array();
			$ln_aux['asiento'] = $asiento;
			$ln_aux['cuenta'] = $cuenta;
			$ln_aux['debe'] = $debe;
			$ln_aux['haber'] = $haber;
			$ln_aux['fk_poliza'] = $fk_pol;
			$ln_aux['tasa_iva'] = $tasa_iva;
			$ln[$tp][] = $ln_aux;
		} else {
			$ln[$tp][$j]['debe'] = $ln[$tp][$j]['debe'] + $debe;
			$ln[$tp][$j]['haber'] = $ln[$tp][$j]['haber'] + $haber;
		}
		return $ln;
	}
	
	public function Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln) {
		dol_syslog("Cliente_Proveedor_Crea_Poliza_Det_From_Array");
		/* require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php'; */
		 
		$poldet = new Contabpolizasdet($this->db);
		$poldet->asiento = $ln['asiento'];
		$poldet->cuenta = $ln['cuenta'];
		$poldet->debe = $ln['debe'];
		$poldet->haber = $ln['haber'];
		$poldet->fk_poliza = $ln['fk_poliza'];
	
		$poldet->create($user);
	}
	
	public function Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid) {
		dol_syslog("Cliente_Proveedor_Crea_Poliza_Det");
		/* require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php'; */
	
		dol_syslog("asiento=$asiento, cuenta=$cuenta, debe=$debe, haber=$haber, polid=$polid");
		$poldet = new Contabpolizasdet($this->db);
		$poldet->asiento = $asiento;
		$poldet->cuenta = $cuenta;
		$poldet->debe = $debe;
		$poldet->haber = $haber;
		$poldet->fk_poliza = $polid;
	
		$poldet->create($user);
	}
	
	public function Get_Cliente_Proveedor_Cta($socid) {
		dol_syslog("Get_Cliente_Proveedor_Cta=".$socid);
		/* require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php'; */
		$soc = new Societe($this->db);
		$soc->fetch($socid);
		if ($soc->client == 1) { $res = $soc->code_compta; }
		else if($soc->fournisseur == 1) { $res = $soc->code_compta_fournisseur; }
		dol_syslog("El número de cuenta cte/prov = ".$res);
		return $res;
	}
	
	public function Get_Cliente_Cuenta($socid, $conf) {
		dol_syslog("Get_Cliente_Cuenta=".$socid);
		$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
		$country_id=$tmp[0];
		 
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
		if ($this->Get_Cliente_Proveedor_Pais($socid) == $country_id) {
			$rel->fetch_by_code("CLIENTES_NAL"); // "105.01";
		} else {
			$rel->fetch_by_code("CLIENTES_EXT"); //"105.02";
		}
		$id = $rel->fk_cat_cta;
		$cat->fetch($id);
		$res = $cat->cta;
		return $res;
	}
	
	public function Get_Proveedor_Cuenta($socid, $conf) {
		dol_syslog("Get_Proveedor_Cuenta=".$socid);
		$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
		$country_id=$tmp[0];
		 
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
		if ($this->Get_Cliente_Proveedor_Pais($socid) == $country_id) {
			$rel->fetch_by_code("PROVEEDORES_NAL"); // $res = "201.01";
		} else {
			$rel->fetch_by_code("PROVEEDORES_EXT"); // $res = "201.02";
		}
		$id = $rel->fk_cat_cta;
		$cat->fetch($id);
		$res = $cat->cta;
		return $res;
	}
	
	public function Get_Cliente_Proveedor_Pais($socid) {
		dol_syslog("Get_Cliente_Proveedor_Pais = ".$socid);
		/* require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php'; */
		$soc = new Societe($this->db);
		$soc->fetch($socid);
		$res = $soc->country_id;
		dol_syslog("country_id=".$res);
		return $res;
	}
	
	public function Get_Tipo_Cliente_Proveedor($socid) {
		dol_syslog("Get_Tipo_Cliente_Proveedor = ".$socid);
		/* require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php'; */
		$soc = new Societe($this->db);
		$soc->fetch($socid);
		$res = $soc->typent_id;
		dol_syslog("typent_id=".$res);
		return $res;
	}

}
