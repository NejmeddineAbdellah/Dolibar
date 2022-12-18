<?php
/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *   	\file       dev/Contabpolizass/Contabpolizas_page.php
 *		\ingroup    mymodule othermodule1 othermodule2
 *		\brief      This file is an example of a php page
 *					Initialy built by build_class_from_table on 2015-02-26 02:24
 */

//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)

// Change this following line to use the correct relative path (../, ../../, etc)

date_default_timezone_set("America/Mexico_City");

$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include '../../../main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../main.inc.php")) $res=@include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/invoice.lib.php';

require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fourn.lib.php';

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolizas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolizas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolizasdet.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';

require_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';

if (! $user->rights->contab->cont) {
	accessforbidden();
}
// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
$langs->load("bills");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$myparam	= GETPOST('myparam','alpha');
$asiento 	= GETPOST('asiento');
$ref 		= GETPOST('ref');
$esfaccte	= GETPOST('fc');
$esfacprov	= GETPOST('fp');
$facid 		= GETPOST('facid','int');
$idpd 		= GETPOST('idpd', 'int');
$soc_type	= GETPOST("soc_type");
$socid 		= GETPOST("socid","int");

//print "Fecha: ".GETPOST("fecha")." == ".time(GETPOST("fecha"))." ///  ";

$anio = 0; $mes = 0;
if (GETPOST('anio')) {
	$anio = GETPOST('anio');
}
if (GETPOST('mes')) {
	$mes = GETPOST('mes');
}
if ($anio > 0 || $mes > 0) {

	dol_syslog("anio=$anio, mes=$mes");
	$per = new Contabperiodos($db);
	if ($per->fetch_by_period($anio, $mes)) {
		$periodo_estado = $per->estado;
	}
	
} else {
	$per = new Contabperiodos($db);
	if (! $per->fetch_open_period()) {
		dol_syslog("Se supone que no hay un periodo abierto.   <script>window.location = ".DOL_DOCUMENT_ROOT."/contab/index.php"."</script>");
		//$db->close();
		if (file_exists(DOL_DOCUMENT_ROOT.'/contab/periodos/fiche.php')) {
			print "<script>window.location = '".DOL_URL_ROOT."/contab/periodos/fiche.php';"."</script>";
		} else {
			print "<script>window.location = '".DOL_URL_ROOT."/custom/contab/periodos/fiche.php';"."</script>";
		}
	}
	$periodo_estado = $per->estado;
	$anio = $per->anio;
	$mes = $per->mes;
}

if (GETPOST('addenc')) {
	$action = 'addenc';
}
if (GETPOST('addline')) {
	$action = 'addline';
}
/* if (GETPOST('updateenc')) {
	$action = 'updateenc';
} */
if (GETPOST('updateline')) {
	$action = 'updateline';
}
if (GETPOST('cancel')) {
	$cancel = true;
	$action = "";
}
$fecha = date("y-m-d h:i:s");

dol_syslog("anio=$anio, mes=$mes,  action = ".$action.", id=$id idpd=$idpd--------------- esfac=$esfaccte, $esfacprov");
/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

$form = new Form($db);



function check_date($str){ 
	//echo $str;
    trim($str); 
    $trozos = explode ("/", $str); 
    $año=$trozos[2]; 
    $mes=$trozos[1]; 
    $dia=$trozos[0];  

    foreach ($trozos as $cad) {
    	if(!ctype_digit($cad)){
    		return false;
   		}
    }    

    if((sizeof($año)>0) && (sizeof($año)>0)  && (sizeof($año)>0) ){
    	if(checkdate ($mes,$dia,$año)){ 
	    	return true; 
	    } 
	    else{ 
	   		 return false; 
	    } 
    }else{
    	return false; 
    }          
} 

if ($action == 'createpol') {
	if (!$cancel) {
		
		if(DOL_VERSION>='3.7' && DOL_VERSION<'3.9'){
			$fecha = strtotime($_POST["fecha"]);
		}else{
			$fecha = strtotime(str_replace('/', '-',$_POST["fecha"]));
		}
		$anio = date("Y",$fecha);
		$mes = date("m",$fecha);
		$sqn = "SELECT t.rowid, t.anio, t.mes, t.estado, t.validado_bg, t.validado_bc, t.validado_er,";
		$sqn.= " t.validado_ld, t.validado_lm FROM ".MAIN_DB_PREFIX."contab_periodos as t ";
    	$sqn .= " WHERE 1  AND t.anio = $anio  AND t.mes = $mes "; 
    	$sqn.= " AND entity = ".$conf->entity;
    	$fr=$db->query($sqn);
    	$fnrw=$db->num_rows($fr);
    	$mos=1;
    	if($fnrw>0){
    		$frs=$db->fetch_object($fr);
    		if($frs->estado==1){
    			$mos=1;
    		}else{
    			$mos=3;
    		}
    	}else{
    		$mos=2;
    	}
		//print $periodo_estado." :: ".$per::PERIODO_ABIERTO;exit();
		if ($mos==1/* $periodo_estado == $per::PERIODO_ABIERTO */) {
			

			$auxfecha=date("d/m/Y",$fecha);
			if(check_date($auxfecha)){
				//print "<script>alert('Fecha valida');</script>";
						

			dol_syslog("Estoy en CreatePol");
			
			$soc_type = GETPOST("soc_type");
			//var_dump($soc_type);
			$_POST["fecha"]=='';
			if($_POST["fecha"]==''){
				$msg = "Debe seleccionar una fecha valida.";
				$action = "";
			}else{
		    
			    if(DOL_VERSION>='3.7' && DOL_VERSION<'3.9'){
					$fecha = strtotime($_POST["fecha"]);	
				}else{
					$fecha = strtotime(str_replace('/', '-',$_POST["fecha"]));
				}
		
				$np1 = new Contabpolizas($db);
				$tp = GETPOST('tipo_pol');
				
				$np1->fetch_last_by_tipo_pol2($tp,date("Y",$fecha),date("m",$fecha));
				
				if (! $soc_type > 0) {
					if ($esfaccte == 1) { 
						$soc_type = 1; 
					} else if ($esfacprov == 1) {
						 $soc_type = 2; 
					} 
				}

				$np = new Contabpolizas($db);
				$np->tipo_pol = $tp;
				$np->cons = $np1->cons + 1;
				$np->fecha = date("Y-m-d",$fecha);
				$np->anio = date("Y",$fecha);
				$np->mes = date("m",$fecha);
				$np->comentario = GETPOST("comentario");
				$np->concepto = GETPOST("concepto");
				$np->anombrede = GETPOST("anombrede");
				$np->numcheque = GETPOST("numcheque");
				$np->fk_facture = GETPOST("fk_facture");
				$np->ant_ctes = "";
				$np->societe_type = $soc_type;

				if(GETPOST('pol_ajuste')){
					$np->pol_ajuste=1;
					
				}
				if(GETPOST('pol_ajuste') && date("m",$fecha)!=12){
					$msg = "Solo puede crear polizas del Periodo de ajuste con fecha del mes de Diciempre.";
					$action = "";
				}else{
					$np->create($user);
					$id = $np->id;
					
					$sqm="SELECT rowid FROM ".MAIN_DB_PREFIX."contab_polizas_log WHERE fk_user='".$user->id."' 
							AND fk_poliza=".$id;
					$rnm=$db->query($sqm);
					$nrn=$db->num_rows($rnm);
					if($nrn==0){
					$sqm="INSERT INTO ".MAIN_DB_PREFIX."contab_polizas_log (fk_user, fk_poliza, cantmodif, creador, fechahora) 
		 				VALUES('".$user->id."','".$id."','1','1',now())";
					$rnm=$db->query($sqm);
					}
					$esfaccte = ($soc_type == 1 ? 1 : 0);
					$esfacprov = ($soc_type == 2 ? 1 : 0); 
					
				}
			}

			$bandFile=0;
			if($_FILES["file"]["name"][0]!=''){
			//if(isset($_FILES['file']) && ($_FILES['file']['size']>0)){
		
				
				$string=' select tipo_pol, anio, mes, cons from '.MAIN_DB_PREFIX.'contab_polizas order by rowid desc limit 1 ';
				//$string='SELECT max(rowid) as num from llx_contab_polizas';
				$resp=$db->query($string);
				$res=$db->fetch_object($resp);

				$an=substr($_POST["fecha"], -2, 2); 
				$mes=substr($_POST["fecha"], 3, 2); 
				$tpol=addslashes($_POST['tipo_pol']);
				
				$folio= $an.$mes."-".$tpol."-".($res->cons); 
				
				$url='../files';	

				if (!file_exists($url)) {
				    mkdir($url, 0755);
				}				
						//datos del arhivo 
				
				$tot = count($_FILES["file"]["name"]);
				for ($i = 0; $i < $tot; $i++){
					$tipo_archivo = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);			
					
					//compruebo si las características del archivo son las que deseo 
					$banType=0;
					if (strcmp($tipo_archivo, "pdf")==0){ 
						$banType=1;
					}else{
						if (strcmp($tipo_archivo, "xml")==0) { 
							$banType=1;
						}
					}

					if ($banType==0) { 
					   $msg .= " ERROR: Al subir el archivo ". $_FILES['file']['name'][$i] ." .Solamente se permiten archivos XML y PDF";
						$action = "";
						$bandFile=0;
					}else{ 				

						$url='../files'.'/'.$folio;	
						if (!file_exists($url)) {
							mkdir($url, 0755);
						} 

						$url='../files'.'/'.$folio.'/'.$_FILES['file']['name'][$i]; 
						// Check if file already exists
						if (file_exists($url)) {
						    $msg = "El archivo ". $_FILES['file']['name'][$i] ."ya existe";
							$action = "";
							$bandFile=0;
						}else{
							if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $url)){ 
						      	//$msg = "El archivo ha sido cargado correctamente."; 
						      	$bandFile=1;
						   	}else{ 
						      	$msg = "Ocurrió algún error al subir el fichero ". $_FILES['file']['name'][$i] ." No pudo guardarse."; 
						      	$bandFile=0;
						   	} 
						}
					   	
					}
					if($bandFile==1){
						$fil= new contabpolizas($db);
						$fil-> insert_url_doc($conf->entity,$folio, $url);
					} 
				}
			}					
			}else{
				print "<script>alert('Fecha invalida'); window.location.href = '".DOL_URL_ROOT."/contab/polizas/poliza.php?action=newpol';"."</script>";
			}
		} else {
			if($mos==3){
				$msg = "No se puede crear una poliza en un perdiodo contable ya cerrado.";
			}else{
				$msg = "No se puede crear una poliza en un perdiodo contable que no existe.";
			}
			$action = "newpol";
		}
	}
	$action = "";
} else if ($action == 'update_enc' && GETPOST('updateenc')) {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		
		$bandFile=1;
		$cc = new Contabpolizas($db);

		if(DOL_VERSION>='3.7' && DOL_VERSION<'3.9'){
			$fecha = strtotime($_POST["fecha"]);	
		}else{
			$fecha = strtotime(str_replace('/', '-',$_POST["fecha"]));
		}
		$auxfecha=date("d/m/Y",$fecha);
		if(check_date($auxfecha)){
			//print "<script>alert('Fecha valida');</script>";
		

		if($_FILES["file"]["name"][0]!=''){
	 	//if(isset($_FILES['file']) && ($_FILES['file']['size']>0)){
	
			//$string=' select tipo_pol, anio, mes, cons from llx_contab_polizas order by rowid desc limit 1 ';
			$string='SELECT rowid, tipo_pol, anio, mes, cons from '.MAIN_DB_PREFIX.'contab_polizas where rowid='.$id;
			$resp=$db->query($string);
			$res=$db->fetch_object($resp);

			
			$an=substr($res->anio, -2, 2); 			
			$m = ((int)$res->mes<10) ? "0".$res->mes : $res->mes ;
			$folio = $an.$m."-".$res->tipo_pol."-".$res->cons; 
			
			$url='../files';	
			

			if (!file_exists($url)) {
			    mkdir($url, 0755);
			}				
					//datos del arhivo 
			$tot = count($_FILES["file"]["name"]);
			for ($i = 0; $i < $tot; $i++){
				$tipo_archivo = pathinfo($_FILES['file']['name'][$i], PATHINFO_EXTENSION);			
				//compruebo si las características del archivo son las que deseo 
				$banType=0;
				if (strcmp($tipo_archivo, "pdf")==0){ 
					$banType=1;
				}else{
					if (strcmp($tipo_archivo, "xml")==0) { 
						$banType=1;
					}
				}

				if ($banType==0) { 
				    $msg .= " ERROR: Al subir el archivo ". $_FILES['file']['name'][$i] ." .Solamente se permiten archivos XML y PDF";
					$action = "";
					$bandFile=1;
				}else{ 				

					$url='../files'.'/'.$folio;	
					if (!file_exists($url)) {
						mkdir($url, 0755);
					} 

					$url='../files'.'/'.$folio.'/'.$_FILES['file']['name'][$i]; 
					// Check if file already exists
					if (file_exists($url)) {
					     $msg .= " ERROR: El archivo ". $_FILES['file']['name'][$i] ." ya existe";
						$action = "";
						$bandFile=1;
					}else{
						if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $url)){ 
					      	//$msg = "El archivo ha sido cargado correctamente."; 
					      		$bandFile=0;
					   	}else{ 					      	
					      	 $msg .= " ERROR: Ocurrió algún error al subir el archivo ". $_FILES['file']['name'][$i] ." .No pudo guardarse";
					      	$bandFile=1;
					   	} 
					}
				   	
				}
				if($bandFile==0){
					//$fil= new contabpolizas($db);
					$cc-> insert_url_doc($conf->entity,$folio, $url);
				} 
			}
			
		}		

		if(DOL_VERSION>='3.7' && DOL_VERSION<'3.9'){
			$fecha = strtotime($_POST["fecha"]);	
		}else{
			$fecha = strtotime(str_replace('/', '-',$_POST["fecha"]));
		}
		//print "Fecha:$fecha, ".date("d m Y",$fecha)."- == ".gettype($fecha)." ***  ";
		
		if (! $socid > 0) {
			if (! $soc_type > 0) {
				if ($esfaccte == 1) { $soc_type = 1; } else if ($esfacprov == 1) { $soc_type = 2; }
			}
		}
		
		//$cc = new Contabpolizas($db);
		
		$cc->id = $id;
		$cc->tipo_pol = $db->escape(GETPOST('tipo_pol'));
		$cc->cons = GETPOST('cons');
		
		$cc->fecha = date("Y-m-d",$fecha);
		$cc->anio = date("Y",$fecha);
		$cc->mes = date("m",$fecha);
		$cc->concepto = $db->escape(GETPOST('concepto'));
		$cc->comentario = $db->escape(GETPOST('comentario'));
		$fk = GETPOST('fk_facture');
		if ($fk) {	$cc->fk_facture = GETPOST('fk_facture');	}
		$cc->societe_type = GETPOST('soc_type');
		$cc->ant_ctes = GETPOST("ant_ctes");
		
		$cc->anombrede = GETPOST('anombrede');
		$cc->numcheque = GETPOST('numcheque');
		if(GETPOST('pol_ajuste') && date("m",$fecha)==12){
		$cc->pol_ajuste=1;
		}else{
			$cc->pol_ajuste=0;
		}
		//print "   ".$cc->fecha." ".$cc->fecha;
 
		if($cc->update()){
			$sqm="SELECT count(rowid) as existe FROM ".MAIN_DB_PREFIX."contab_polizas_log 
					WHERE fk_user=".$user->id." AND fk_poliza=".$id;
			$mrq=$db->query($sqm);
			$mrs=$db->fetch_object($mrq);
			if($mrs->existe==0){
				$sqm="INSERT INTO ".MAIN_DB_PREFIX."contab_polizas_log 
						(fk_user, fk_poliza, cantmodif, creador, fechahora) 
  					VALUES('".$user->id."','".$id."','1','0',now())";
				$mrq=$db->query($sqm);
			}else{
				$sqm="UPDATE ".MAIN_DB_PREFIX."contab_polizas_log 
						SET cantmodif=cantmodif+1, fechahora=now() 
						WHERE fk_user=".$user->id." AND fk_poliza=".$id;
				$mrq=$db->query($sqm);
			}
			print "<script>window.location.href='fiche.php?cambio_fecha=1&anio=".GETPOST('anio')."&mes=".GETPOST('mes')."'</script>";
		}
		}else{
			print "<script>alert('Fecha invalida. Datos no modificados'); window.location = '".DOL_URL_ROOT."/contab/polizas/fiche.php';"."</script>";
		}
	} else {
		$msg = "No se pueden hacer cambios en polizas de perdiodos contables ya cerrados.";
		$action = "";
	}
} else if ($action == "newpolline") {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		$poldet = new Contabpolizasdet($db);
		
		$asiento = 1;
		if ($poldet->fetch_last_asiento_by_num_poliza($id)) {
			$asiento = $poldet->asiento;
		}
		
		$asiento ++;
		
		$poldet->initAsSpecimen();
		
		$poldet->asiento = $asiento;
		$poldet->fk_poliza = $id;
		$poldet->create($user);
		//$poldet->id;
		
		print "<script>window.location.href='poliza.php?id=".$id."&idpd=$poldet->id&action=editline&facid=".$facid."&anio=".$anio."&mes=".$mes."'</script>";
	} else {
		$msg = "No se pueden hacer cambios en polizas de perdiodos contables ya cerrados.";
		$action = "";
	}	
	$action = "";
/* } else if ($action == "filterfac") {
	$ilterfact = $id; */
} else if ($action == "editline") {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		$c2 = new Contabpolizasdet($db);
		$c2->fetch($idpd);
		$asiento= $c2->asiento;
		$cuenta= $c2->cuenta;
		$debe= $c2->debe;
		$haber= $c2->haber;
		$desc= $c2->desc;
		$uuid= $c2->uuid;
		
	} else {
		$msg = "No se pueden hacer cambios en pólizas de perdiodos contables ya cerrados.";
		$action = "";
	}
} else if ($action == 'addenc') {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		dol_syslog(" ******************************** ");
		$cc = new Contabpolizas($db);
		
		if (! $soc_type > 0) {
			if ($esfaccte == 1) { $soc_type = 1; } else if ($esfacprov == 1) { $soc_type = 2; }
		}
		
		$cc->tipo_pol = $db->escape(GETPOST('tipo_pol'));
		$cc->cons = GETPOST('cons');
		$cc->fecha = date("d-M-Y h:i:s");
		$cc->concepto = $db->escape(GETPOST('concepto'));
		$cc->comentario = $db->escape(GETPOST('comentario'));
		$cc->anombrede = $db->escape(GETPOST('anombrede'));
		$cc->numcheque = $db->escape(GETPOST('numcheque'));
		$fk = GETPOST('fk_facture');
		if ($fk) { $cc->fk_facture = GETPOST('fk_facture'); } 
		$cc->societe_type = $soc_type;
		$cc->create($user);
	} else {
		$msg = "No se pueden hacer cambios en pólizas de perdiodos contables ya cerrados.";
		$action = "";
	}	
} else if ($action == 'addline') {
	if (!$cancel) {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		dol_syslog(" ******************************** ");
		$cc = new Contabpolizasdet($db);
		
		$cc->asiento = GETPOST('asiento');
		$cc->cuenta = GETPOST('cuenta');
		$cc->debe = GETPOST('debe');
		$cc->haber = GETPOST('haber');
		$cc->desc = GETPOST('desc');
		$cc->uuid = GETPOST('uuid');
		$cc->fk_poliza = GETPOST('id');
		$cc->create($user);
	} else {
		$msg = "No se pueden hacer cambios en polizas de perdiodos contables ya cerrados.";
		$action = "";
	}
	}
} else if ($action == 'updateline') {
	if (!$cancel) {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		$cc2 = new Contabpolizasdet($db);
		$cc2->fetch($idpd);
		
		$cc2->cuenta = $db->escape(GETPOST('cuenta'));
		$cc2->debe = GETPOST('debe');
		$cc2->haber = GETPOST('haber');
		$cc2->desc = GETPOST('desc');
		$cc2->uuid = GETPOST('uuid');
		//print $cc2->desc.'<---'; exit();
		$sqm="SELECT count( * ) as cant
									FROM ".MAIN_DB_PREFIX."contab_cat_ctas
									WHERE cta LIKE '".GETPOST('cuenta').".%'
									AND entity =".$conf->entity;
		//print $sqm."<br>";
		$rsm=$db->query($sqm);
		$rmm=$db->fetch_object($rsm);
		if($rmm->cant==0){
			$sqm="SELECT count(rowid) as existe FROM ".MAIN_DB_PREFIX."contab_polizas_log
					WHERE fk_user=".$user->id." AND fk_poliza=".$id;
			$mrq=$db->query($sqm);
			$mrs=$db->fetch_object($mrq);
			if($mrs->existe==0){
				$sqm="INSERT INTO ".MAIN_DB_PREFIX."contab_polizas_log
						(fk_user, fk_poliza, cantmodif, creador, fechahora)
  					VALUES('".$user->id."','".$id."','1','0',now())";
				$mrq=$db->query($sqm);
			}else{
				$sqm="UPDATE ".MAIN_DB_PREFIX."contab_polizas_log
						SET cantmodif=cantmodif+1, fechahora=now()
						WHERE fk_user=".$user->id." AND fk_poliza=".$id;
				$mrq=$db->query($sqm);
			}
			$cc2->update();
		}else{
			$msg = "Error no puede agrega esta cuenta a una poliza, es una cuenta acumulativa.";
			$action='editline';
		}
	} else {
		$msg = "No se pueden hacer cambios en polizas de perdiodos contables ya cerrados.";
	}
	}
	$action = "";
	
} else if ($action == 'delline_confirm') {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		if (GETPOST("ddl_borrar_linea") == "S") {
			$cc2 = new Contabpolizasdet($db);
			$cc2->id = $idpd;
			if ($cc2->delete($user)) {
				$sqm="SELECT count(rowid) as existe FROM ".MAIN_DB_PREFIX."contab_polizas_log
					WHERE fk_user=".$user->id." AND fk_poliza=".$id;
				$mrq=$db->query($sqm);
				$mrs=$db->fetch_object($mrq);
				if($mrs->existe==0){
					$sqm="INSERT INTO ".MAIN_DB_PREFIX."contab_polizas_log
						(fk_user, fk_poliza, cantmodif, creador, fechahora)
  					VALUES('".$user->id."','".$id."','1','0',now())";
					$mrq=$db->query($sqm);
				}else{
					$sqm="UPDATE ".MAIN_DB_PREFIX."contab_polizas_log
						SET cantmodif=cantmodif+1, fechahora=now()
						WHERE fk_user=".$user->id." AND fk_poliza=".$id;
					$mrq=$db->query($sqm);
				}
				$msg = "El asiento de la póliza fue borrado.";
			}
		}
	} else {
		$msg = "No se pueden hacer cambios en polizas de perdiodos contables ya cerrados.";
	}
	
	$action = "";
} else if ($action == "delpol_confirm") {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		if (GETPOST("ddl_borrar_poliza") == "S") {
			$pd = new Contabpolizasdet($db);
			$pd->delete_by_id_poliza($user, $id);
			
			$cc = new Contabpolizas($db);
			$id = $id;
			$anio = $per->anio;
			$mes = $per->mes;
			$cc->fetch($id, $anio, $mes);
			$msg = "La poliza de ".$cc->tipo_pol." No.: ".$cc->cons.", ha sido borrada.";
			$cc->delete($user);
			print "<script>window.location.href='fiche.php'</script>";
		}
	} else {
		$msg = "No se pueden hacer cambios en polizas de perdiodos contables ya cerrados.";
	}
	$action = "";
}
/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

//$arrayofjs = array('../js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

llxHeader('','','','','','','','',0,0);
$facn='';
if ($socid > 0) {
	$object = new Societe($db);
	$object->fetch($socid);
	
	//if (! empty($conf->notification->enabled)) $langs->load("mails");
	
	$head = societe_prepare_head($object);
	//dol_fiche_head($head, "tabthirdpol", $langs->trans("ThirdParty"), 0);
	dol_fiche_head($head, 'tabthirdpol', $langs->trans("ThirdParty"), 0 ,'company');
	
	print '<table class="border" width="100%">';
	
	// Name
	print '<tr><td width="25%">'.$langs->trans('ThirdPartyName').'</td>';
	print '<td colspan="3">';
	print $form->showrefnav($object, 'socid', '', ($user->societe_id?0:1), 'rowid', 'nom');
	print '</td>';
	print '</tr>';
	
	// Logo+barcode
	$rowspan=6;
	if (! empty($conf->global->SOCIETE_USEPREFIX)) $rowspan++;
	if (! empty($object->client)) $rowspan++;
	if (! empty($conf->fournisseur->enabled) && $object->fournisseur && ! empty($user->rights->fournisseur->lire)) $rowspan++;
	if (! empty($conf->barcode->enabled)) $rowspan++;
	if (empty($conf->global->SOCIETE_DISABLE_STATE)) $rowspan++;
	$htmllogobar='';
	if ($showlogo || $showbarcode)
	{
		$htmllogobar.='<td rowspan="'.$rowspan.'" style="text-align: center;" width="25%">';
		if ($showlogo)   $htmllogobar.=$form->showphoto('societe',$object);
		if ($showlogo && $showbarcode) $htmllogobar.='<br><br>';
		if ($showbarcode) $htmllogobar.=$form->showbarcode($object);
		$htmllogobar.='</td>';
	}
	
	// Prefix
	if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
	{
		print '<tr><td>'.$langs->trans('Prefix').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">'.$object->prefix_comm.'</td>';
		print $htmllogobar; $htmllogobar='';
		print '</tr>';
	}
	
	// Customer code
	if ($object->client)
	{
		print '<tr><td>';
		print $langs->trans('CustomerCode').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">';
		print $object->code_client;
		if ($object->check_codeclient() <> 0) print ' <font class="error">('.$langs->trans("WrongCustomerCode").')</font>';
		print '</td>';
		print $htmllogobar; $htmllogobar='';
		print '</tr>';
	}
	
	// Supplier code
	if (! empty($conf->fournisseur->enabled) && $object->fournisseur && ! empty($user->rights->fournisseur->lire))
	{
		print '<tr><td>';
		print $langs->trans('SupplierCode').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">';
		print $object->code_fournisseur;
		if ($object->check_codefournisseur() <> 0) print ' <font class="error">('.$langs->trans("WrongSupplierCode").')</font>';
		print '</td>';
		print $htmllogobar; $htmllogobar='';
		print '</tr>';
	}
	
	print "</table>";
} else if ($esfaccte == 1) {
	dol_syslog("EsFacCte y quiero ver cuanto vale el id = ".$id);
	$object = new Facture($db);
	$object->fetch($facid,$ref);
	$facn=$object->ref;
	
	$head = facture_prepare_head($object);
	dol_fiche_head($head, "tabcustpolizas", $langs->trans("InvoiceCustomer"), 0);
} else if ($esfacprov == 1){
	$object = new FactureFournisseur($db);
	$object->fetch($facid,$ref);
	$facn=$object->ref;
    $result=$object->fetch_thirdparty();
    if ($result < 0) dol_print_error($db);

    $head = facturefourn_prepare_head($object);
    dol_fiche_head($head, "tabsuppolizas", $langs->trans('SupplierInvoice'), 0);
} else {
	$head = contab_prepare_head($object, $user);
	dol_fiche_head($head, "Polizas", 'Contabilidad', 0, '');
}

dol_syslog("Datos de configuración: ".$per->anio." ".$per->mes." ".$per->MesToStr($per->mes)." facid=$facid");
if($facn!=''){
	print 'Generando desde la factura: <a href="'.DOL_MAIN_URL_ROOT.'/compta/facture.php?facid='.$facid.'">'.$facn.'</a>';
}
if ($action == "newpol") {
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		print "<h3>Edicion de Poliza Contable</h3><br><br>";
		//Obtener las facturas de clientes, del anio y mes especificado
		//Obtener las facturas del proveedor, del anio y mes especificado
		if (! $soc_type > 0) {
			if ($esfaccte == 1) { $soc_type = 1; } else if ($esfacprov == 1) { $soc_type = 2; }
		}
?>
<?php 
	print "<script>
	window.onkeydown=tecla;
	function tecla(event){
		num = event.keyCode;
		if(num==113){ 
			//113==F2 Agregar
			document.forms['createpol'].submit();
			event.preventDefault();
		}
	}
	</script>";

	?>



			
		<form method="post" action="?action=createpol" id="createpol" enctype="multipart/form-data">
<?php 
			if ($socid > 0) { 
?>
					<input type="hidden" name="socid" value="<?=$socid;?>" />
<?php 
			} else if ($esfaccte == 1) { 
?>
				<input type="hidden" name="fc" value="<?=$esfaccte?>">
				<input type="hidden" id="facid" name="facid" value="<?=$facid;?>">
<?php 
			} else if ($esfacprov == 1) {
?>
			 	<input type="hidden" name="fp" value="<?=$esfacprov?>">
				<input type="hidden" id="facid" name="facid" value="<?=$facid;?>">
<?php 
			} else {
				?><input type="hidden" id="facid" name="facid" value="0"><?php 
			}
?>
			<input type="hidden" name="mes" value="<?=$mes?>" />
			<input type="hidden" name="anio" value="<?=$anio?>" />

			<?php 
			/*print '<script>
				$( document ).ready(function() {
					document.getElementById("fecha").readOnly = true;
				});
				
			</script>';*/
			?>
			

			<table>
				<tr <?=$bc[$var]; ?>>
					<td>Fecha de la Factura: </td>
					<td><?=$form->select_date(date("Y-m-d"),'fecha','','','','update',1,0,1,0);?></td>
					<td style="text-align:right;">
						Poliza:
					</td>
					<td>
	    	       		<select name="tipo_pol" id="tipo_pol" required>
	    	       			<!-- <option value=" " >&nbsp;</option> -->
			               	<option value="D">Diario</option>
	    		            <option value="E">Egreso</option>
	    		            <option value="C">Cheque</option>
	    	    	        <option value="I">Ingreso</option>
	    	    		</select>
	    	    	</td>
	    	    	<td colspan="3">&nbsp;</td> 
				</tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Concepto:
					</td>
					<td colspan='3'> 
						<input name="concepto" id="concepto" type="text" value="<?=$concepto; ?>" style="width: 500px" required>
					</td>
				</tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Comentario:
					</td>
					<td colspan='3'> 
						<input name="comentario" id="comentario" type="text" value="<?=$comentario; ?>" style="width: 500px">
					</td>
				</tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Docto. Relacionado:
					</td>
					<td>
<?php 
						if ($soc_type == 0) {
?>
							<input type="radio" name="soc_type" value="0" onchange="change_tipo_rel(0);">No relacionado 
							<input type="radio" name="soc_type" value="1" onchange="change_tipo_rel(1);">Cliente 
							<input type="radio" name="soc_type" value="2" onchange="change_tipo_rel(2);">Proveedor
<?php 
						} else if ($soc_type == 1) {
?>
							<input type="radio" name="soc_type" value="1" onchange="change_tipo_rel(1);">Cliente 
<?php 
						} else if ($soc_type == 2) {
?>
							<input type="radio" name="soc_type" value="2" onchange="change_tipo_rel(2);">Proveedor 
<?php 
						}
?>
					</td>
					<td colspan='2'>
						<div id="div_fk_facture"></div>
					</td>
				</tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Cheque a nombre de:
					</td>
					<td> 
						<input name="anombrede" id="anombrede" type="text" value="<?=$anombrede; ?>" >
					</td>
					<td>
						Cheque Numero:
					</td>
					<td> 
						<input name="numcheque" id="numcheque" type="text" value="<?=$numcheque; ?>" >
					</td>
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td>
						Adjuntar archivos:
					</td>
					<td>
						<input type="file" name="file[]" multiple />
					</td>
				</tr>
				<?php
				$sqm="SELECT count(*) as existe
						FROM ".MAIN_DB_PREFIX."contab_periodos
						WHERE mes=13 AND entity=".$conf->entity;
				$rqm=$db->query($sqm); 
				$rsm=$db->fetch_object($rqm);
				if($rsm->existe>0){
				?>
				<tr>
					<td>Periodo de Ajuste</td>
					<td colspan="7"><input type="checkbox" name="pol_ajuste" value="1" ></td>
				</tr>
				<?php 
				}
				?>
				<tr>
					<td colspan="8">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" colspan="8">
						<input type="submit" name="create" class="button" value="Agregar" >
						<input type="submit" name="cancel" class="button" value="Cancelar" >
					</td>
	
				</tr>
				<tr>
					<td colspan="8">&nbsp;</td>
				</tr>
			</table>	
		</form>
<?php 	
	} else {
		$msg = "No se puede hacer cambios en perdiodos contables ya cerrados.";
		$action = "";
	}
} else if ($action == "editenc") {
	if($user->rights->contab->modifpol){
	 if ($periodo_estado == $per::PERIODO_ABIERTO) {



		print "<h3>Edición de Póliza Contable</h3><br><br>";
		
		$c = new Contabpolizas($db);
		if ($c->fetch($id, 0)) {
			$tipo_pol = $c->tipo_pol;
			$cons = $c->cons;
			$fecha = date("d-m-Y", $c->fecha);
			//var_dump($fecha);
			$concepto = $c->concepto;
			$comentario = $c->comentario;
			$anombrede = $c->anombrede;
			$numcheque = $c->numcheque;
			$fk_facture = $c->fk_facture;
			$soc_type = $c->societe_type;
			$ant_ctes = $c->ant_ctes;
			$polajuste = $c->pol_ajuste;
		}

		$var=$var;
		
		//if (! ($esfaccte == 1 || $esfacprov == 1 || $socid > 0)) {
?>
			<!-- <h1>Periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h1>
			<br> -->
<?php 
		//}
?>
		<form method="post" action="?action=update_enc"  enctype="multipart/form-data">
			<input name="id" id="id" type="hidden" value="<?=$id; ?>" >
<?php 
				if ($socid > 0) { 
?>
					<input type="hidden" name="socid" value="<?=$socid;?>" />
<?php 
				} else if ($soc_type == 1) { 
?>
					<input type="hidden" name="fc" value="<?=$esfaccte?>">
					<input type="hidden" id="facid" name="facid" value="<?=$facid;?>">
<?php
				 } else if ($soc_type == 2) {
?>
				 	<input type="hidden" name="fp" value="<?=$esfacprov?>">
					<input type="hidden" id="facid" name="facid" value="<?=$facid;?>">
<?php 
				} else  { 
					?><input type="hidden" id="facid" name="facid" value="0"><?php
				} 
?>
				
			<input type="hidden" name="mes" value="<?=$mes?>" />
			<input type="hidden" name="anio" value="<?=$anio?>" />
			<input type="hidden" name="soc_type" value="<?=$soc_type;?>" />
			<input type="hidden" name="ant_ctes" value="<?=$ant_ctes;?>" />

			<?php /*
			print '<script>
				$( document ).ready(function() {
					document.getElementById("fecha").readOnly = true;
				});
				
			</script>';*/
			?>
			
			
			<table>
				<tr>
					<td>Fecha de la Factura: </td>
					<td><?=$form->select_date($c->fecha,'fecha','','','','update',1,0,1,0);?></td>
					<td style="text-align:right;">
						Póliza:
						<select name="tipo_pol" id="tipo_pol" >
	    	       			<option value=" " >&nbsp;</option>
			               	<option value="D" <?=($tipo_pol =="D" ? print " selected='selected' " : ""); ?>>Diario</option>
	    		            <option value="E" <?=($tipo_pol =="E" ? print " selected='selected' " : ""); ?>>Egreso</option>
	    		            <option value="C" <?=($tipo_pol =="C" ? print " selected='selected' " : ""); ?>>Cheque</option>
	    	    	        <option value="I" <?=($tipo_pol =="I" ? print " selected='selected' " : ""); ?>>Ingreso</option>
	    	    		</select>
	    	    	</td>
	    	    	<td colspan='2'>Cons: <input type="text" name="cons" value="<?=$cons;?>" /></td>
	    	    </tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Concepto:
					</td>
					<td colspan='4'> 
						<input name="concepto" id="concepto" type="text" value="<?=$concepto; ?>" style="width: 500px">
					</td>
				</tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Comentario:
					</td>
					<td colspan='4'> 
						<input name="comentario" id="comentario" type="text" value="<?=$comentario; ?>" style="width: 500px">
					</td>
				</tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Docto. Relacionado:
					</td>
<?php 
					if ($soc_type == 0) {
?>
						<td colspan="2">
							<input type="radio" name="soc_type" value="0" onchange="change_tipo_rel(0);">No relacionado 
							<input type="radio" name="soc_type" value="1" onchange="change_tipo_rel(1);">Cliente 
							<input type="radio" name="soc_type" value="2" onchange="change_tipo_rel(2);">Proveedor
						</td>
						<td colspan='2'>
							<div id="div_fk_facture"></div>
						</td>
<?php 
					} else if ($soc_type == 1) {
?>
						<td colspan='4'>
							<select name="fk_facture" id="fk_facture">
							<option value="0">&nbsp;</option>
<?php
								$sql = 'SELECT f.rowid, f.facnumber FROM '.MAIN_DB_PREFIX.'facture f Inner Join '.MAIN_DB_PREFIX.'societe s On f.fk_soc = s.rowid and s.client = 1 WHERE f.entity = '.$conf->entity;
								dol_syslog("newpol_fill_ddl.php :: sql=".$sql, LOG_DEBUG);
								$result = $db->query($sql);
								if ($result)
								{
									dol_syslog("Entre");
									while ($obj = $db->fetch_object($result)) {
										dol_syslog($obj->rowid." ".$obj->facnumber);
										?><option value="<?=$obj->rowid?>" <?=($obj->rowid == $fk_facture ? " selected='selected' " : "");?>><?=$obj->facnumber;?></option><?php
									}
								}
?>
							</select>
						</td>
<?php
					} else if($soc_type == 2) {
?>
						<td colspan='4'>
							<select name="fk_facture" id="fk_facture">
								<option value="0">&nbsp;</option>
<?php	
								$sql = 'SELECT f.rowid, f.ref FROM '.MAIN_DB_PREFIX.'facture_fourn f Inner Join '.MAIN_DB_PREFIX.'societe s On f.fk_soc = s.rowid and s.fournisseur = 1 WHERE f.entity = '.$conf->entity;
								dol_syslog("newpol_fill_ddl.php :: sql=".$sql, LOG_DEBUG);
								$result = $db->query($sql);
								if ($result)
								{
									dol_syslog("Entre");
									while ($obj = $db->fetch_object($result)) {
										dol_syslog($obj->rowid." ".$obj->ref);
										?><option value="<?=$obj->rowid?>" <?=($obj->rowid == $fk_facture ? " selected='selected' " : "");?>><?=$obj->ref;?></option><?php
									}
								}
?>
							</select>
						</td>
<?php
					}
?>
				</tr>
				<tr <?=$bc[$var]; ?>>
					<td>
						Cheque a nombre de:
					</td>
					<td> 
						<input name="anombrede" id="anombrede" type="text" value="<?=$anombrede; ?>" >
					</td>
					<td>
						Cheque Numero:
					</td>
					<td> 
						<input name="numcheque" id="numcheque" type="text" value="<?=$numcheque; ?>" >
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						Adjuntar archivos:
					</td>
					<td>
						<input type="file" name="file[]" multiple />
					</td>
				</tr>
				
				<?php
				$sqm="SELECT count(*) as existe
						FROM ".MAIN_DB_PREFIX."contab_periodos
						WHERE mes=13 AND entity=".$conf->entity;
				$rqm=$db->query($sqm); 
				$rsm=$db->fetch_object($rqm);
				if($rsm->existe>0){
					$aa='';
					if($polajuste==1){
						$aa=' checked';
					}
				?>
				<tr>
					<td>Periodo de Ajuste</td>
					<td colspan="7"><input type="checkbox" name="pol_ajuste" value="1" <?=$aa?>></td>
				</tr>
				<?php 
				}
				?>
				<tr>
					<td align="center" colspan="5">
						<input type="submit" name="updateenc" class="button" value="Actualizar" >
						<input type="submit" name="cancel" class="button" value="Cancelar" >
					</td>
	
				</tr>
			</table>	
		</form>
<?php
	} else {
		$msg = "No se puede hacer cambios en perdiodos contables ya cerrados.";
		$action = "";
	}
  }else{
  	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
  }
} else if ($action == "editline") {
	if($user->rights->contab->modifpol){
	 if ($periodo_estado == $per::PERIODO_ABIERTO) {
		if (! ($esfaccte == 1 || $esfacprov == 1 || $socid > 0)) {
?>
			<h1>Periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h1>
			<br>
<?php
		}
		print "<h3>Edicion de Asiento Contable</h3>";
	
		$var=!$var;
?>
		<form method="post">
			<input type="hidden" name="id" id="id" value="<?=$id;?>" />
			<input type="hidden" name="idpd" id="idpd" value="<?=$idpd;?>" />
			<input type="hidden" name="mes" value="<?=$mes?>" />
			<input type="hidden" name="anio" value="<?=$anio?>" />
			
<?php 
			if ($socid > 0) { 
?>
				<input type="hidden" name="socid" value="<?=$socid;?>" />
<?php 
			} else if ($esfaccte == 1) { 
?>
				<input type="hidden" name="fc" value="<?=$esfaccte?>" />
				<input type="hidden" name="facid" value="<?=$facid;?>" /> 
<?php 
			} else if ($esfacprov == 1) { 
?>
				<input type="hidden" name="fp" value="<?=$esfacprov?>" />
				<input type="hidden" name="facid" value="<?=$facid;?>" /> 
<?php 
			}
?>

			<table >
				<tr <?=$bc[$var]; ?>>
					<td>
						Asiento:
					</td>
					<td> 
						<input name="asiento" id="asiento" type="text" value="<?=$asiento; ?>" >
					</td>

					<td>
						Cuenta:
<script>
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        //this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      /* _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Mostrar todas las cuentas" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      }, */
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#cuenta" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#cuenta" ).toggle();
    });
  });
  </script>
 
					</td>
					<td > 
						<!--<input name="cuenta" id="cuenta" type="text" value="<?=$cuenta; ?>" >-->
						<?php 
						$sqlc="SELECT cta,descta
							FROM ".MAIN_DB_PREFIX."contab_cat_ctas
							WHERE entity=".$conf->entity;
						$resc=$db->query($sqlc);
						?>
						<select name="cuenta" id="cuenta" >
							<option value=""></option>
							<?php 
							while($rqc=$db->fetch_object($resc)){
								$ac='';
								if($cuenta==$rqc->cta){
									$ac=' SELECTED';
								}
								/* $sqm="SELECT count( * ) as cant
									FROM ".MAIN_DB_PREFIX."contab_cat_ctas
									WHERE cta LIKE '".$rqc->cta."%'
									AND entity =".$conf->entity;
								$rsm=$db->query($sqm);
								$rmm=$db->fetch_object($rsm);
								if($rmm->cant==1){ */
									print "<option value='".$rqc->cta."' ".$ac.">".$rqc->cta." - ".$rqc->descta."</option>";
								//}
							}
							?>
					  </select> 
					</td>
					<td>
						Debe: 
					</td>
					<td>
						<input name="debe" id="debe" type="text" value="<?=$debe; ?>" >
					</td>
					<td>
						Haber:
					</td> 
					<td>
						<input name="haber" id="haber" type="text" value="<?=$haber; ?>" >
					</td>
				</tr>
				<tr>
					<td>Concepto: </td>
					<td colspan="7"><input name="desc" id="desc" type="text" size="125" value="<?=$desc; ?>" ></td>
				</tr>
				<tr>
					<td>UUID: </td>
					<td colspan="7"><input name="uuid" id="uuid" type="text" size="125" value="<?=$uuid; ?>" ></td>
				</tr>
				
				<tr>
					<td align="center" colspan="8">
<?php
						if ($action != "editline") { 
?>
							<input type="submit" name="addline" class="button" value="Agregar" >
<?php 
						} else {
?>
							<input type="submit" name="updateline" id="updateline" class="button" value="Actualizar" >
<?php 
						}
?>
						<input type="submit" name="cancel" class="button" value="Cancelar" >
					</td>
				</tr>
			</table>	
		</form>
		
<?php 
	} else {
		$msg = "No se puede hacer cambios en perdiodos contables ya cerrados.";
		$action = "";
	}
 }else{
 	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
 }
}

if ($action == "delpol") {
	if($user->rights->contab->elimpol){
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		$c = new Contabpolizas($db);
		$c->fetch($id, 0);
?>
		<form action="?action=delpol_confirm" method="post">
			<input type="hidden" name="fc" value="<?=($esfaccte == 1 ? $esfaccte : 0);?>" />
			<input type="hidden" name="fp" value="<?=($esfacprov == 1 ? $esfacprov : 0);?>" />
			<input type="hidden" name="facid" value="<?=$facid;?>" />
			<input type="hidden" name="id" value="<?=$id;?>" />
			<input type="hidden" name="mes" value="<?=$mes?>" />
			<input type="hidden" name="anio" value="<?=$anio?>" />
			<input type="hidden" name="socid" value="<?=($socid > 0 ? $socid : 0);?>" />
			
			<br>
			<strong>Realmente quieres eliminar la póliza <?=$c->Get_folio_poliza();?> No.: <?=$c->cons;?> ?</strong> 
			&nbsp;
			&nbsp;
			<select name="ddl_borrar_poliza">
				<option value="N">No</option>
				<option value="S">Si</option>
			</select>
			&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Continuar" />
			<br>
			<br>
		</form>
<?php 
	} else {
		$msg = "No se puede hacer cambios en perdiodos contables ya cerrados.";
		$action = "";
	}
	}else{
		print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
	}
} else if ($action == 'delline') {
	if($user->rights->contab->modifpol){
	if ($periodo_estado == $per::PERIODO_ABIERTO) {
		dol_syslog("Tratando de borrar datos");
		$cc = new Contabpolizasdet($db);
		$cc->fetch($idpd);
		$idpd = $cc->id;
		$asiento = $cc->asiento;
		$c = new Contabpolizas($db);
		$c->fetch($cc->fk_poliza, 0);
		$id=$c->id;
		$tp = $c->Get_Tipo_Poliza_Desc();
		$cons = $c->cons;
		$facid = $c->fk_facture;
		//dol_syslog("===>Valores idpd=$idpd, asiento=$asiento, tp=$tp, cons=$cons");
?>
		<form action="?action=delline_confirm" method="post">
			<input type="hidden" name="fc" value="<?=($esfaccte == 1 ? $esfaccte : 0);?>" />
			<input type="hidden" name="fp" value="<?=($esfacprov == 1 ? $esfacprov : 0);?>" />
			<input type="hidden" name="facid" value="<?=$facid;?>" />
			<input type="hidden" name="id" value="<?=$id;?>" />
			<input type="hidden" name="mes" value="<?=$mes?>" />
			<input type="hidden" name="anio" value="<?=$anio?>" />
			<input type="hidden" name="idpd" value="<?=$idpd;?>" />
			<input type="hidden" name="socid" value="<?=($socid > 0 ? $socid : 0);?>" />
			
			<br>
			<strong>Realmente quieres eliminar el asiento No: <?=$asiento;?>, de la póliza de <?=$tp;?> No.: <?=$cons;?> ? </strong> 
			&nbsp;
			&nbsp;
			<select name="ddl_borrar_linea">
				<option value="N">No</option>
				<option value="S">Si</option>
			</select>
			&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Continuar" />
			<br>
			<br>
		</form>
<?php 
	} else {
		$msg = "No se puede hacer cambios en perdiodos contables ya cerrados.";
		$action = "";
	}
  }else{
  	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
  }
}

print "<br><br><strong>Nota: <label style='color:blue'>Después de realizar sus cambios, y para visualizar todas las pólizas mostradas anteriormente, presione sobre el tab llamado 'Pólizas'</label></strong>";
dol_fiche_end();

?>
<br>
<input name="id" id="id" type="hidden" value="<?=$id; ?>" >
<?php 
if($socid > 0) {
?>
	<input type="hidden" name="socid" value="<?=$socid;?>" />
<?php 
} else if ($esfaccte == 1) { 
?>
	<input type="hidden" name="fc" value="<?=$esfaccte?>">
	<input type="hidden" name="facid" value="<?=$facid;?>"> 
<?php 
} else if ($esfacprov == 1) { 
?>
	<input type="hidden" name="fp" value="<?=$esfacprov?>">
	<input type="hidden" name="facid" value="<?=$facid;?>"> 
<?php 
} 

$var=True;

$ini = 0;
$cant = 0;

$tp = "";
$c = 0;

$i = 0;

$pol = new Contabpolizas($db);
$poldet = new Contabpolizasdet($db);
$ctas = new Contabcatctas($db);
$ff = new FactureFournisseur($db);
$f = new Facture($db);
$soc = new Societe($db);

$primera_vez = true;
$pol->anio = $anio;
$pol->mes = $mes;

$row = 0;
dol_syslog("El valor del id=$id");
if ($id > 0) {
	$row = $pol->fetch($id, 0);
	if($action!='editenc' && $action!='editline' && $pol->id <= 0){
?>
	<table class="noborder" style="width:100%">
		<tr class="liste_titre">
			<td colspan="4">Encabezado de la Póliza</td>
			<td style="text-align: right;">&nbsp;</td>
			<td style="text-align: right;">
				<a href="<?=$_SERVER["PHP_SELF"]; ?>?action=newpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Nueva Póliza</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="<?= $_SERVER["PHP_SELF"]; ?>?id=<?=$pol->id; ?>&amp;action=delpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Borrar Póliza</a>
			</td>
		</tr>
	</table>
<?php 
	}
}
if ($pol->id > 0 && $action!='editenc') {
?>
<a href="poliza.php?action=newpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><button>Nueva Póliza</button></a>
	<?php 
	$urnew="poliza.php?action=newpol";
	$urlexcel="print.php?tipo=excel&id=".$pol->id;
	$urlpdf="print.php?tipo=pdf&id=".$pol->id;
	$urladdcuenta="addcuenta.php?tpenvio=pol&id=".$id;
	$urlnewasiento="poliza.php?id=".$pol->id."&action=newpolline&anio=".$anio."&mes=".$mes;
	//print $urlnewasiento;
	print "<script>
	window.onkeydown=tecla;
	function tecla(event){
		num = event.keyCode;
		if(num==112){ 
			//112==F1 Nueva poliza
			window.location.href='".$urnew."';
			event.preventDefault();
		}
 		if(num==114){ 
			//114==F3 Agregar cuenta
			window.location.href='".$urladdcuenta."';
			event.preventDefault();
		}
 		if(num==118){ 
			//118==F7 Agregar asiento
			window.location.href='".$urlnewasiento."';
			event.preventDefault();
		}
 		if(num==119){ 
			//119==F8 Agregar
			//alert('F8');
			document.getElementById('updateline').click();
			//event.preventDefault();
		}
 		if(num==120){
 			//120==F9 Descarga PDF
 			window.open('".$urlpdf."','_blank');
			event.preventDefault();
		}
 		if(num==121){
 			//121==F10 Descarga Excel
 			window.open('".$urlexcel."','_blank');
			event.preventDefault();
		}
	}
	</script>";
	?>
	<br><br>
	<table class="noborder" style="width:100%">
	<tr class="liste_titre">
		<td colspan="3">Encabezado de la Póliza</td>
		<td style="text-align: right;">
			<a href="print.php?tipo=excel&id=<?=$pol->id;?>" target="popup">
				Descargar Excel
			</a>
		</td>
		<td style="text-align: right;">
			<a href="print.php?tipo=pdf&id=<?=$pol->id;?>" target="popup">
				Descargar PDF
			</a>
		</td>
		<td style="text-align: right;">
				<a href="addcuenta.php?tpenvio=pol&id=<?=$id?>" >Agregar cuenta</a>
		</td>
		<td style="text-align: right;">
			<a href="poliza.php?id=<?=$pol->id; ?>&amp;action=delpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Borrar Póliza</a>
		</td>
	</tr>
<?php 
	if ($tp !== $pol->tipo_pol || $c !== $pol->cons) {
		$var = !$var;
		$tp = $pol->tipo_pol;
		$c = $pol->cons;
		$facid = $pol->fk_facture;
		$nomsoc='';
		if ($pol->societe_type == 1) {
			//Es un Cliente
			$f->fetch($pol->fk_facture);
			$facnumber = $f->ref;
			$sfcid=$f->socid;
			$noms= new Societe($db);
			$noms->fetch($sfcid);
			$nomsoc=$noms->name;
			$pagina = "/compta/facture.php";
		} else if($pol->societe_type == 2) {
			//Es un Proveedor
			$ff->fetch($pol->fk_facture);
			$facnumber = $ff->ref;
			$sfcid=$ff->socid;
			$noms= new Societe($db);
			$noms->fetch($sfcid);
			$nomsoc=$noms->name;
			$pagina = "/fourn/facture/fiche.php";
		}
?>			
		<tr <?=$bc[$var]; ?>>
			<td colspan = "3">
				Póliza:
				<strong> 
<?php 
					print $pol->Get_folio_poliza();
?>
				</strong>
				<a href="poliza.php?id=<?=$pol->id; ?>&action=editenc<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_edit(); ?></a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
			</td>
			<td colspan = "2">Fecha: <?=date("Y-m-d",$pol->fecha);?></td>
			<td colspan = "2">
				Documento Relacionado: <a href="<?=DOL_URL_ROOT.$pagina;?>?facid=<?=$facid;?>"><?php echo $facnumber; ?></a>
			</td>
		</tr>
		<?php 
			if($nomsoc!=''){
				?>
				<tr <?php print $bc[$var]; ?>>
				<td colspan = "7">
					Tercero: <strong><?php echo $nomsoc; ?></strong>
				</td>
				</tr>
				<?php
			}
			?>
		<tr <?=$bc[$var]; ?>>
			<td colspan = "4">
				Concepto: <strong><?=substr($pol->concepto,0,150); ?></strong>
				&nbsp;
				Comentario: <strong><?=substr($pol->comentario,0,150); ?></strong>
			</td>
			<td colspan = "3" >

			Archivos adjuntos:<br/>
			<?php

				$folio=$pol->Get_folio_poliza();
				
				$string= "select url from ".MAIN_DB_PREFIX."contab_doc where folio='".$folio."'";
				$que=$db->query($string);

				$docs="";
				while($re=$db->fetch_object($que)) {
					$dir = explode("/", $re->url);
					$docs=" ".$dir[3]." ";
					echo "<a target='_blank' href='".$re->url."'>".$docs."</a><br/>";

				}
			?>
			</td>
		</tr>
		<tr <?=$bc[$var]; ?>>
			<td colspan = "7">
				Cheque a Nombre: <strong><?=substr($pol->anombrede,0,150); ?></strong>
				&nbsp;
				Núm. Cheque: <strong><?=substr($pol->numcheque,0,150); ?></strong>
			</td>
		</tr>
		<?php
		if($pol->pol_ajuste==1){ 
		?>
		<tr <?=$bc[$var]; ?>>
			<td colspan = "6">
				<strong>Poliza del periodo de ajuste</strong>
			</td>
		</tr>
		<?php 
		}
		?>
<?php
	}
?>
	<tr class="liste_titre">
		<td>Asiento</td>
		<td>Cuenta</td>
		<td>Concepto</td>
		<td>UUID</td>
		<td style="text-align: right; width: 10%;">Debe</td>
		<td style="text-align: right; width: 10%;">Haber</td>
		<td colspan="2" " style="text-align: right;"><a href="poliza.php?id=<?=$pol->id; ?>&amp;action=newpolline<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$pol->anio?>&mes=<?=$pol->mes?>">Nuevo Asiento</a> </td>
	</tr>
<?php 
	$cond = " fk_poliza = ".$pol->id;
	$rr = $poldet->fetch_next(0, $cond);
	//print_r($poldet);
	if ($rr) {
		$totdebe=0;
		$tothaber=0;
		while ($rr) {
			$nom_soc = "";
			//Verificar primeramente si se trata de un artículo
			if (!$ctas->fetch_by_Cta($poldet->cuenta, false)) {
				if ($pol->societe_type == 1) {
					if ($soc->fetch($f->socid)) {
						dol_syslog("1. Societe Type = ".$pol->societe_type);
						$nom_soc = $soc->nom;
					}
				} else if ($pol->societe_type == 2) {
					if ($soc->fetch($ff->socid)) {
						dol_syslog("2. Societe Type = ".$pol->societe_type);
						$nom_soc = $soc->nom;
					}
				}
			}	
?>
			<tr <?=$bc[$var]; ?>>
				<td><?=$poldet->asiento; ?></td>
				<td><? print $poldet->cuenta; 
				if ($nom_soc) {
					print " ".$nom_soc;
				}else {
					$ctas->fetch_by_Cta($poldet->cuenta, false);
				 	print " ".$ctas->descta;
				}
				$totdebe+=$poldet->debe;
				$tothaber+=$poldet->haber;
				?></td>
				<td><?=$poldet->desc; ?></td>
				<td><?=$poldet->uuid; ?></td>
				<td style="text-align: right;"><?=($poldet->debe > 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format($poldet->debe, 2) : ""); ?></td>
				<td style="text-align: right;"><?=($poldet->haber > 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format($poldet->haber, 2) : ""); ?></td>
<?php
		 		if ($poldet->asiento > 0) {
?>
					<td style="text-align: center;">
						<?php "fc=$esfaccte, fp=$esfacprov"?>
						<a href="poliza.php?id=<?=$pol->id;?>&idpd=<?=$poldet->id; ?>&amp;action=editline<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_edit(); ?></a>&nbsp;&nbsp;
						<a href="poliza.php?id=<?=$pol->id;?>&idpd=<?=$poldet->id; ?>&amp;action=delline<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_delete(); ?></a>
					</td>
<?php 
				}
?>
			</tr>
<?php 
			
			
			$i ++;
			$id = $poldet->id;
			$rr = $poldet->fetch_next($id, $cond);
		}
		?>
					<tr>
						<td colspan="4" align="right">
						<strong>Total</strong>
						</td>
						<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($totdebe, 2)?></td>
						<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($tothaber, 2)?></td>
					</tr>
					<?
					if ( number_format($totdebe,2) != number_format($tothaber,2) ) {
						$dif=str_replace('-','',number_format(($totdebe-$tothaber),2));
					?>
					<tr>
						<td colspan="3" align="center"></td>
						<td colspan="3" style="text-align: center; color:#FF0000">Los totales no coinciden en esta poliza por <?=$langs->getCurrencySymbol($conf->currency).' '.$dif?>, favor de verificar</td>
					</tr>
					<?
					}
					$sqm="SELECT a.cantmodif, a.fechahora, b.lastname, b.firstname,a.creador
			FROM ".MAIN_DB_PREFIX."contab_polizas_log a, ".MAIN_DB_PREFIX."user b
			WHERE fk_poliza=".$pol->id." AND a.fk_user=b.rowid ORDER BY a.fechahora DESC";
					$mrq=$db->query($sqm);
					$mnr=$db->num_rows($mrq);
					if($mnr>0){
						?>
								<tr>
								<td colspan="6">
									<table class='border'>
										<tr>
											<td>Usuario</td>
											<td>Modificaciones</td>
											<td>Fecha Ult. modificacion</td>
										</tr>
										<?php 
										while($mrs=$db->fetch_object($mrq)){
											$stro='';
											if($mrs->creador==1){
												?>
												<tr>
													<td><strong><?=$mrs->firstname." ".$mrs->lastname?></strong></td>
													<td align='center'><?=$mrs->cantmodif?></td>
													<td><?=$mrs->fechahora?></td>
												</tr>
												<?
											}else{
												?>
											<tr>
												<td><?=$mrs->firstname." ".$mrs->lastname?></td>
												<td align='center'><?=$mrs->cantmodif?></td>
												<td><?=$mrs->fechahora?></td>
											</tr>
											<?php 
											}
										}
										?>
									</table>
								</td>
								</tr>
								<?
								}
	}
?>
	</table>
	<br><br>
<?php 
}
	
llxFooter();

dol_htmloutput_mesg($msg);
dol_htmloutput_events();

$db->close();
?>
