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
 * code pour cr�er le module 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
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

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolrec.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolrec.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolrec.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolrecdet.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolrecdet.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolrecdet.class.php';
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

if (GETPOST('addenc')) {
	$action = 'addenc';
}
if (GETPOST('addline')) {
	$action = 'addline';
}
if (GETPOST('updateline')) {
	$action = 'updateline';
}
if (GETPOST('cancel')) {
	$cancel = true;
	$action = "";
}
$fecha = date("y-m-d H:i:s");

/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

$per = new Contabperiodos($db);

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
if ($action == 'addnewrec') {
	//Una p�liza ser� guardada como recurrente para poder se utilizada en otro momento
	
	$pol = new Contabpolizas($db);
	$pol->fetch($id, 0); //0 - Se busca la p�liza en cualquier periodo, 1 - Se busca en el peridodo actual 
	
	$pp = new Contabpolrec($db);
	$pp->fetch_last_by_tipo_pol2($pol->tipo_pol,$pol->anio,$pol->mes);
	
	$rec = new Contabpolrec($db);
	$rec->entity = $pol->entity;
	$rec->tipo_pol = $pol->tipo_pol;
	$rec->cons = $pp->cons + 1;
	$rec->anio = $pol->anio;
	$rec->mes = $pol->mes;
	$rec->fecha = $pol->fecha;
	$rec->concepto = $pol->concepto;
	$rec->comentario = $pol->comentario;
	//$rec->fk_facture = 0;
	$rec->fk_facture = $pol->fk_facture;
	$rec->societe_type = $pol->societe_type;
	$rec->ant_ctes = 0;
	$rec->fechahora = $pol->fechahora;
	
	$idpolrec = $rec->create($user);
	
	dol_syslog("rec.php :: idpolrec = $idpolrec");
	
	$poldet = new Contabpolizasdet($db);
	$recdet = new Contabpolrecdet($db);
	
	$cond = " t.fk_poliza = ".$id;
	
	$id = 0;
	while ($poldet->fetch_next($id, $cond)) {
		$recdet->fk_poliza = $idpolrec;
		$recdet->asiento = $poldet->asiento;
		$recdet->cuenta = $poldet->cuenta;
		$recdet->debe = $poldet->debe;
		$recdet->haber = $poldet->haber;
		$recdet->descripcion = $poldet->desc;
		$recdet->uuid = $poldet->uuid;
		
		$recdet->create($user);
		
		$id = $poldet->id;
	}
} else if ($action == 'createpol') {
	if (!$cancel) {
		dol_syslog("Estoy en CreatePol");
			
		$soc_type = GETPOST("soc_type");
		//var_dump($soc_type);
		$_POST["fecha"]=='';
		if(check_date($_POST["fecha"])){
			if(DOL_VERSION>='3.7' && DOL_VERSION<'3.9'){
				$fecha = strtotime($_POST["fecha"]);
			}else{
				$fecha = strtotime(str_replace('/', '-',$_POST["fecha"]));
			}
			$np1 = new Contabpolrec($db);
			$tp = GETPOST('tipo_pol');
				
			$np1->fetch_last_by_tipo_pol2($tp,date("Y",$fecha),date("m",$fecha));
				
			$np = new Contabpolrec($db);
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
				
			$np->create($user);
			$id = $np->id;
				
			$esfaccte = ($soc_type == 1 ? 1 : 0);
			$esfacprov = ($soc_type == 2 ? 1 : 0);
			
			$bandFile=0;
			if($_FILES["file"]["name"][0]!=''){
			//if(isset($_FILES['file']) && ($_FILES['file']['size']>0)){
			
			
				$string=' select tipo_pol, anio, mes, cons from '.MAIN_DB_PREFIX.'contab_pol_rec order by rowid desc limit 1 ';
				//$string='SELECT max(rowid) as num from llx_contab_polizas';
				$resp=$db->query($string);
				$res=$db->fetch_object($resp);
			
				$an=substr($_POST["fecha"], -2, 2);
				$mes=substr($_POST["fecha"], 3, 2);
				$tpol=addslashes($_POST['tipo_pol']);
			
				$folio= "rec".$an.$mes."-".$tpol."-".($res->cons);
			
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
						//$fil= new contabpolizas($db);
						//$fil-> insert_url_doc($conf->entity,$folio, $url);
						$string= "INSERT INTO ".MAIN_DB_PREFIX."contab_doc (entity, folio, url)
						values(".$conf->entity.",'".$folio."','".$url."')";
							//echo $string;
						$mv=$db->query($string);
					}
				}
			}
		
		}else{
			$msgerror = "Debe seleccionar una fecha valida.";
			$action = "";
		}
	}
	$action = "";
	
} else if ($action == 'update_enc' && GETPOST('updateenc')) {
	if(DOL_VERSION>='3.7' && DOL_VERSION<'3.9'){
		$fecha = strtotime($_POST["fecha"]);
	}else{
		$fecha = strtotime(str_replace('/', '-',$_POST["fecha"]));
	}

	
	$cc = new Contabpolrec($db);
	
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
	
	//print "   ".$cc->fecha." ".$cc->fecha;
	if($_FILES["file"]["name"][0]!=''){
	//if(isset($_FILES['file']) && ($_FILES['file']['size']>0)){
	
		//$string=' select tipo_pol, anio, mes, cons from llx_contab_polizas order by rowid desc limit 1 ';
		$string='SELECT rowid, tipo_pol, anio, mes, cons from '.MAIN_DB_PREFIX.'contab_pol_rec where rowid='.$id;
		$resp=$db->query($string);
		$res=$db->fetch_object($resp);
	
			
		$an=substr($res->anio, -2, 2);
		$m = ((int)$res->mes<10) ? "0".$res->mes : $res->mes ;
		$folio = "rec".$an.$m."-".$res->tipo_pol."-".$res->cons;
			
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
				//$cc-> insert_url_doc($conf->entity,$folio, $url);
				$string= "INSERT INTO ".MAIN_DB_PREFIX."contab_doc (entity, folio, url) 
						values(".$conf->entity.",'".$folio."','".$url."')";
				//echo $string;
				$mv=$db->query($string);
			}
		}
			
	}
	
	$cc->update();
} else if ($action == "newpolline") {
	$polrecdet = new Contabpolrecdet($db);
	
	$asiento = 1;
	if ($polrecdet->fetch_last_asiento_by_num_poliza($id)) {
		$asiento = $polrecdet->asiento;
	}
	
	$asiento ++;
	$polrecdet->initAsSpecimen();
	
	$polrecdet->asiento = $asiento;
	$polrecdet->fk_poliza = $id;
	$polrecdet->create($user);
	
	$action = "";
	print "<script>window.location.href='rec.php?id=".$id."&idpd=".$polrecdet->id."&action=editline'</script>";
} else if ($action == "editline") {
	$c2 = new Contabpolrecdet($db);
	$c2->fetch($idpd);
	$asiento = $c2->asiento;
	$cuenta = $c2->cuenta;
	$debe = $c2->debe;
	$haber = $c2->haber;
	$desc= $c2->descripcion;
	$uuid= $c2->uuid;
} else if ($action == 'addline') {
	if (!$cancel) {
		dol_syslog(" ******************************** ");
		$cc = new Contabpolrecdet($db);
		
		$cc->asiento = GETPOST('asiento');
		$cc->cuenta = GETPOST('cuenta');
		$cc->debe = GETPOST('debe');
		$cc->haber = GETPOST('haber');
		$cc->fk_poliza = GETPOST('id');
		$cc->create($user);
	}
} else if ($action == 'updateline') {
	if (!$cancel) {
		$cc2 = new Contabpolrecdet($db);
		$cc2->fetch($idpd);
		$cc2->asiento = GETPOST('asiento');
		$cc2->cuenta = $db->escape(GETPOST('cuenta'));
		$cc2->debe = GETPOST('debe');
		$cc2->haber = GETPOST('haber');
		$cc2->descripcion = GETPOST('desc');
		$cc2->uuid = GETPOST('uuid');
		$sqm="SELECT count( * ) as cant
									FROM ".MAIN_DB_PREFIX."contab_cat_ctas
									WHERE cta LIKE '".GETPOST('cuenta').".%'
									AND entity =".$conf->entity;
		//print $sqm."<br>";
		$rsm=$db->query($sqm);
		$rmm=$db->fetch_object($rsm);
		if($rmm->cant==0){
			$cc2->update();
		}else{
			$msg = "Error no puede agrega esta cuenta a una poliza, es una cuenta acumulativa.";
		}
	}
	$action = "";
	
} else if ($action == 'delline_confirm') {
	if (GETPOST("ddl_borrar_linea") == "S") {
		$cc2 = new Contabpolrecdet($db);
		$cc2->id = $idpd;
		if ($cc2->delete($user)) {
			$msg = "El asiento de la poliza fue borrado.";
		}
	}
	
	$action = "";
} else if ($action == "delpol_confirm") {
	if (GETPOST("ddl_borrar_poliza") == "S") {
		$pd = new Contabpolrecdet($db);
		$pd->delete_by_id_poliza($user, $id);
		
		$cc = new Contabpolrec($db);
		$id = $id;
		$anio = $per->anio;
		$mes = $per->mes;
		$cc->fetch($id, $anio, $mes);
		$msg = "La poliza de ".$cc->tipo_pol." No.: ".$cc->cons.", ha sido borrada.";
		$cc->delete($user);
	}
	$action = "";
} else if ($action == "contab_confirm") {
	if (GETPOST("ddl_contabilizar") == "S") {
		$pr = new Contabpolrec($db);
		$pr->fetch($id, 0); //0 - Se busca la p�liza en cualquier periodo, 1 - Se busca en el peridodo actual 
		
		$pp = New Contabpolizas($db);
		
		if(DOL_VERSION>='3.7' && DOL_VERSION<'3.9'){
			$fecha = strtotime($_POST["fecha"]);
		}else{
			$fecha = strtotime(str_replace('/', '-',$_POST["fecha"]));
		}
		$pp->fetch_last_by_tipo_pol2($pr->tipo_pol,date("Y",$fecha),date("m",$fecha));
		$p = new Contabpolizas($db);
		$p->entity = $pr->entity;
		$p->tipo_pol = $pr->tipo_pol;
		$p->cons = $pp->cons + 1;
		$p->anio = date("Y",$fecha);
		$p->mes = date("m",$fecha);
		$p->fecha = date("Y-m-d",$fecha);
		$p->concepto = $pr->concepto;
		$p->comentario = $pr->comentario;
		if($pr->societe_type==0){
			if(GETPOST('soc_type')==1 || GETPOST('soc_type')==2){
				$p->fk_facture = GETPOST('fk_facture');
				$p->societe_type = GETPOST('soc_type');
			}else{
				$p->fk_facture = $pr->fk_facture;
				$p->societe_type = $pr->societe_type;
			}
		}else{
			$p->fk_facture = $pr->fk_facture;
			$p->societe_type = $pr->societe_type;
		}
		$p->ant_ctes = 0;
		$p->fechahora = $pr->fechahora;
		
		$idpol = $p->create($user);
		$sqm="SELECT rowid FROM ".MAIN_DB_PREFIX."contab_polizas_log WHERE fk_user='".$user->id."'
							AND fk_poliza=".$idpol;
		$rnm=$db->query($sqm);
		$nrn=$db->num_rows($rnm);
		if($nrn==0){
			$sqm="INSERT INTO ".MAIN_DB_PREFIX."contab_polizas_log (fk_user, fk_poliza, cantmodif, creador, fechahora)
	 				VALUES('".$user->id."','".$idpol."','1','1',now())";
			$rnm=$db->query($sqm);
		}
		$folio="rec".$pr->Get_folio_poliza();
		$string= "select rowid, url from ".MAIN_DB_PREFIX."contab_doc where folio='".$folio."'";
		$que=$db->query($string);
		$qnr=$db->num_rows($que);
		if($qnr>0){
			$url='../files';
			if (!file_exists($url)) {
				mkdir($url, 0755);
			}
			$annn=date("y",$fecha);
			$folioc= $annn.$p->mes."-".$p->tipo_pol."-".($p->cons);
			$urlcopi='../files'.'/'.$folioc;
			if (!file_exists($urlcopi)) {
				mkdir($urlcopi, 0755);
			}
			while($re=$db->fetch_object($que)) {
				$dir = explode("/", $re->url);
				$docs=" ".$dir[3]." ";
				$dest=$urlcopi."/".$docs;
				if(@copy($re->url, $dest)){
					$stringq= "INSERT INTO ".MAIN_DB_PREFIX."contab_doc 
							(entity, folio, url) values(".$conf->entity.",'".$folioc."','".$dest."')";
					//echo $string;
					$qres=$db->query($stringq);
				}
			}
		}
		dol_syslog("rec.php :: idpolrec = $idpolrec");
		
		$rdet = new Contabpolrecdet($db);
		$podet = new Contabpolizasdet($db);
		
		$cond = " t.fk_poliza = ".$id;
		
		$id = 0;
		while ($rdet->fetch_next($id, $cond)) {
			$podet->fk_poliza = $idpol;
			$podet->asiento = $rdet->asiento;
			$podet->cuenta = $rdet->cuenta;
			$podet->debe = $rdet->debe;
			$podet->haber = $rdet->haber;
			//dol_syslog('Descripcion:: '.$rdet->descripcion);
			$podet->desc=$rdet->descripcion;
			$podet->uuid=$rdet->uuid;
			//dol_syslog('Descripcion:: '.$rdet->descripcion);
			$podet->create($user);
			
			$id = $rdet->id;
		}
		$msg = "Se ha contabilizado correctamente la poliza de ".$p->Get_folio_poliza()." No.: ".$p->cons; 
	}
}

/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

$arrayofjs = array('../js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

llxHeader('','','','','','',$arrayofjs,'',0,0);

$head = contab_prepare_head($object, $user);
dol_fiche_head($head, "Recurrentes", 'Contabilidad', 0, '');
if($user->rights->contab->conspol){
if ($action == "newpol") {
	print "<h3>Edicion de Poliza Recurrente</h3><br><br>";
?>
	<form method="post" action="rec.php"  enctype="multipart/form-data">
		<input type="hidden" name="action" value="createpol" />
		<input type="hidden" name="mes" value="<?=$mes?>" />
		<input type="hidden" name="anio" value="<?=$anio?>" />
		
		<table>
			<tr <?=$bc[$var]; ?>>
			<td>Fecha: </td> 
				<td><?=$form->select_date(date("Y-m-d"),'fecha','','','','update',1,0,1,0);?></td>
				<td style="text-align:right;">
					Poliza:
				</td>
				<td>
    	       		<select name="tipo_pol" id="tipo_pol" >
    	       			<option value=" " >&nbsp;</option>
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
					<input name="concepto" id="concepto" type="text" value="<?=$concepto; ?>" style="width: 500px">
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
} else if ($action == "editenc") {
	if($user->rights->contab->modifpol){
	print "<h3>Edicion de Poliza Contable</h3><br><br>";
	
	$c = new Contabpolrec($db);
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
	}

	$var=$var;
?>
	<form method="post" action="?action=update_enc"  enctype="multipart/form-data">
		<input name="id" id="id" type="hidden" value="<?=$id; ?>" >
		<input type="hidden" name="mes" value="<?=$mes?>" />
		<input type="hidden" name="anio" value="<?=$anio?>" />
		<input type="hidden" name="soc_type" value="<?=$soc_type;?>" />
		<input type="hidden" name="ant_ctes" value="<?=$ant_ctes;?>" />
		<table>
			<tr>
				<td>Fecha: </td>
				<td><?=$form->select_date($c->fecha,'fecha','','','','update',1,0,1,0);?></td>
				<td style="text-align:right;">
					Poliza:
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
			<tr>
				<td align="center" colspan="5">
					<input type="submit" name="updateenc" class="button" value="Actualizar" >
					<input type="submit" name="cancel" class="button" value="Cancelar" >
				</td>

			</tr>
		</table>	
	</form>
<?php
 }else{
 	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
 }
} else if ($action == "editline") {
	if($user->rights->contab->modifpol){
?>
		<!--<h1>Periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h1>-->
		<br>
<?php
		print "<h3>Edicion de Asiento Contable</h3>";
	
		$var=!$var;
?>
		<form method="post">
			<input type="hidden" name="id" id="id" value="<?=$id;?>" />
			<input type="hidden" name="idpd" id="idpd" value="<?=$idpd;?>" />
			<input type="hidden" name="mes" value="<?=$mes?>" />
			<input type="hidden" name="anio" value="<?=$anio?>" />
			<table>
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
        this._createShowAllButton();
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
							<input type="submit" name="updateline" class="button" value="Actualizar" >
<?php 
						}
?>
						<input type="submit" name="cancel" class="button" value="Cancelar" >
					</td>
				</tr>
			</table>	
		</form>
<?php 
	}else{
		print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
	}
}

if ($action == "sendconf") {
	$c = new Contabpolrec($db);
	$c->fetch($id, 0);
	 
	?>
		<form action="?action=contab_confirm" method="post">
		<table style="width:100%">
		<tr class="liste_titre">
			<td>Contabilizar</td>
		</tr>
		<tr><td>
			<input type="hidden" name="id" value="<?=$id;?>" />
			<input type="hidden" name="mes" value="<?=$mes?>" />
			<input type="hidden" name="anio" value="<?=$anio?>" />
			Fecha Poliza: <?=$form->select_date(date("Y-m-d"),'fecha','','','','update',1,0,1,0);?>
			<?php 
			if($c->societe_type==0){
				?>
				<br>
				Docto. Relacionado:
					<input type="radio" name="soc_type" value="0" onchange="change_tipo_rel2(0);">No relacionado 
					<input type="radio" name="soc_type" value="1" onchange="change_tipo_rel2(1);">Cliente 
					<input type="radio" name="soc_type" value="2" onchange="change_tipo_rel2(2);">Proveedor
					<div id="div_fk_facture" name="div_fk_facture"></div>
				<?php
			}
			?>
			<br>
			<strong>Realmente quieres utilizar esta poliza recurrente en la contabilidad, <?=$c->Get_folio_poliza();?> No.: <?=$c->cons;?> ?</strong> 
			&nbsp;
			&nbsp;
			<select name="ddl_contabilizar">
				<option value="N">No</option>
				<option value="S">Si</option>
			</select>
			&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Continuar" />
			<br>
			<br>
			</td>
			</tr>
			</table>
		</form>
	<?php 
} else if ($action == "delpol") {
	if($user->rights->contab->elimpol){
	$c = new Contabpolrec($db);
	$c->fetch($id, 0);
?>
	<form action="?action=delpol_confirm" method="post">
		<input type="hidden" name="id" value="<?=$id;?>" />
		<input type="hidden" name="mes" value="<?=$mes?>" />
		<input type="hidden" name="anio" value="<?=$anio?>" />
		
		<br>
		<strong>Realmente quieres eliminar la poliza <?=$c->Get_folio_poliza();?> No.: <?=$c->cons;?> ?</strong> 
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
	}else{
		print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
	}
} else if ($action == 'delline') {
	if($user->rights->contab->modifpol){
	dol_syslog("Tratando de borrar datos");
	$cc = new Contabpolrecdet($db);
	$cc->fetch($idpd);
	$idpd = $cc->id;
	$asiento = $cc->asiento;
	$c = new Contabpolrec($db);
	$c->fetch($cc->fk_poliza, 0);
	$id=$c->id;
	$tp = $c->Get_Tipo_Poliza_Desc();
	$cons = $c->cons;
	$facid = $c->fk_facture;
	//dol_syslog("===>Valores idpd=$idpd, asiento=$asiento, tp=$tp, cons=$cons");
?>
	<form action="?action=delline_confirm" method="post">
		<input type="hidden" name="id" value="<?=$id;?>" />
		<input type="hidden" name="mes" value="<?=$mes?>" />
		<input type="hidden" name="anio" value="<?=$anio?>" />
		<input type="hidden" name="idpd" value="<?=$idpd;?>" />
		
		<br>
		<strong>Realmente quieres eliminar el asiento No: <?=$asiento;?>, de la poliza de <?=$tp;?> No.: <?=$cons;?> ? </strong> 
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
	}else{
		print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
	}
}

print "<br><br><strong>Nota: <label style='color:blue'>Despues de realizar sus cambios, y para visualizar todas las polizas mostradas anteriormente, presione sobre el tab llamado 'Polizas'</label></strong>";

//dol_fiche_end();
print "<br>";
?>
<a href="rec.php?action=newpol" class="button">Nueva Poliza</a>
	<br><br>
	<input name="id" id="id" type="hidden" value="<?php print $id; ?>" >
<?php
	$var=True;
	
	$ini = 0;
	$cant = 0;

   	$tp = "";
	$c = 0;
	
   	$i = 0;
	
   	$pol = new Contabpolrec($db);
   	$polrec = new Contabpolrecdet($db);
   	$ctas = new Contabcatctas($db);
   	$rec = new Contabpolrec($db);
   	$recdet = new Contabpolrecdet($db);
	$primera_vez = true;
	$rec->anio = $anio;
	$rec->mes = $mes;
	
	$row = $rec->fetch_next(0, 0);
	if ($row <= 0) {
		//Si no hay polrec que mostrar entonces habr� algunas opciones que no deber�n estar habilitadas como la impresion y recurrentes
?>
		<table class="noborder" style="width:100%">
			<tr class="liste_titre">
				<td colspan="4">Encabezado de la Poliza Recurrente</td>
				<td style="text-align: right;">&nbsp;</td>
				<td style="text-align: right;">
					<a href="rec.php?id=<?=$rec->id; ?>&action=newpol">Nueva Poliza</a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="rec.php?id=<?=$rec->id; ?>&action=delpol">Borrar Poliza</a>
				</td>
			</tr>
		</table>
<?php 
	}
	while ($row > 0) { // = $db->fetch_array(rs)) {
?>
	<table class="noborder" style="width:100%">
		<tr class="liste_titre">
			<td colspan="6">Encabezado de la Poliza Recurrente</td>
			
			<td colspan="2" style="text-align: right;">
				<a href="rec.php?id=<?=$rec->id; ?>&action=sendconf">Contabilizar</a>
				&nbsp;
				<a href="rec.php?id=<?=$rec->id; ?>&action=delpol">Borrar Poliza</a>
			</td>
		</tr>
<?php 
		if ($tp !== $rec->tipo_pol || $c !== $rec->cons) {
			$var = !$var;
			$tp = $rec->tipo_pol;
			$c = $rec->cons;
			$facid = $rec->fk_facture;
			$ff = new FactureFournisseur($db);
			$f = new Facture($db);
			if ($rec->societe_type == 1) {
				//Es un Cliente
				$f->fetch($rec->fk_facture);
				$facnumber = $f->ref;
				$sfcid=$f->socid;
				$noms= new Societe($db);
				$noms->fetch($sfcid);
				$nomsoc=$noms->name;
				$pagina = "/compta/facture.php";
				$esfaccte=1;
			} else if($rec->societe_type == 2) {
				//Es un Proveedor
				$ff->fetch($rec->fk_facture);
				$facnumber = $ff->ref;
				$sfcid=$ff->socid;
				$noms= new Societe($db);
				$noms->fetch($sfcid);
				$nomsoc=$noms->name;
				$pagina = "/fourn/facture/fiche.php";
				if(DOL_VERSION>="3.7"){
					$pagina = "/fourn/facture/card.php";
				}
				$esfacprov=1;
			}
			else{
				$facnumber='';
			}
?>			
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "3">
					Poliza:
					<strong> 
<?php 
					print $rec->Get_folio_poliza()." Cons: ".$c;
?>
					</strong>
					<a href="rec.php?id=<?=$rec->id; ?>&action=editenc"><?=img_edit(); ?></a>
				</td>
				<td colspan = "2">Fecha: <?php print date("Y-m-d",$rec->fecha);?></td>
				<td colspan = "3">
					Documento Relacionado: <a href="<?=DOL_URL_ROOT.$pagina;?>?facid=<?=$facid;?>"><?php echo $facnumber; ?></a>
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "5">
					Concepto: <strong><?php echo substr($rec->concepto,0,150); ?></strong>
					&nbsp;
					Comentario: <strong><?php echo substr($rec->comentario,0,150); ?></strong>
				</td>
				<td colspan = "3" >

			Archivos adjuntos:<br/>
			<?php

				$folio="rec".$rec->Get_folio_poliza();
				
				$string= "select rowid, url from ".MAIN_DB_PREFIX."contab_doc where folio='".$folio."'";
				$que=$db->query($string);

				$docs="";
				while($re=$db->fetch_object($que)) {
					$dir = explode("/", $re->url);
					$docs=" ".$dir[3]." ";
					print "<a href='#' id='".$re->rowid."' class='".$docs."' onclick='deleteFile(this)'>".img_delete()."</a>";
					echo "<a target='_blank' href='".$re->url."'>".$docs."</a><br/>";

				}
			?>
			</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "8">
					Cheque a Nombre: <strong><?php echo substr($rec->anombrede,0,150); ?></strong>
					&nbsp;
					Num. Cheque: <strong><?php echo substr($rec->numcheque,0,150); ?></strong>
				</td>
			</tr>
<?php
		}
?>
		<tr class="liste_titre">
			<td width="10%">Asiento</td>
			<td width="25%" colspan="2">Cuenta</td>
			<td width="25%">Concepto</td>
			<td width="18%">UUID</td>
			<td style="text-align: right; width: 15%;">Debe</td>
			<td style="text-align: right; width: 15%;">Haber</td>
			<td style="text-align: right;width: 10%;"><a href="rec.php?id=<?=$rec->id; ?>&amp;action=newpolline&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Nuevo Asiento</a></td>
		</tr>
<?php 
		$sdodebe = 0;
		$sdohaber = 0;
		$cond = " fk_poliza = ".$rec->id;
		$rr = $recdet->fetch_next(0, $cond);
		if ($rr) {
			while ($rr) {
				$sdodebe += $recdet->debe;
				$sdohaber += $recdet->haber;
?>
<?php 
				$ctas->fetch_by_Cta($recdet->cuenta, false);
?>
				<tr <?php print $bc[$var]; ?>>
					<td><?php print $recdet->asiento; ?></td>
					<td colspan="2"><?php print $recdet->cuenta; print " ".$ctas->descta;?></td>
					<td><?php print $recdet->descripcion; ?></td>
					<td><?php print $recdet->uuid; ?></td>
					<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.($recdet->debe > 0 ? number_format($recdet->debe, 2) : "0"); ?></td>
					<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.($recdet->haber > 0 ? number_format($recdet->haber, 2) : "0"); ?></td>
<?php
		 			if ($recdet->asiento > 0) {
?>
						<td style="text-align: center;">
							<a href="rec.php?id=<?=$rec->id;?>&idpd=<?=$recdet->id; ?>&action=editline"><?=img_edit(); ?></a>&nbsp;&nbsp;
							<a href="rec.php?id=<?=$rec->id;?>&idpd=<?=$recdet->id; ?>&action=delline"><?=img_delete(); ?></a>
						</td>
<?php 
					}
?>
				</tr>

				
<?php 
				$i ++;
				$id = $recdet->id;
				$rr = $recdet->fetch_next($id, $cond);
			}
		}
		
		$color = "";
		if ($sdodebe != $sdohaber) {
			$color = "color: red";
		}
		
		//Se imprimen las sumas de debe y haber
?>
		<tr <?php print $bc[$var]; ?>>
			<td colspan="3"><td>
			<td style="text-align: right; <?=$color;?>"><strong>Total:</strong></td>
			<td style="text-align: right; <?=$color;?>"><strong><?=$langs->getCurrencySymbol($conf->currency).' '.($sdodebe > 0 ? number_format($sdodebe, 2) : "0"); ?></strong></td>
			<td style="text-align: right; <?=$color;?>"><strong><?=$langs->getCurrencySymbol($conf->currency).' '.($sdohaber > 0 ? number_format($sdohaber, 2) : "0"); ?></strong></td>
			<td></td>
			<?
			if ($sdodebe != $sdohaber) {
				$dif=str_replace('-','',number_format(($sdodebe-$sdohaber),2));
			?>
			<tr>
				<td colspan="2" align="center"></td>
				<td colspan="3" style="text-align: center; color:#FF0000">Los totales no coinciden en esta poliza por <?=$langs->getCurrencySymbol($conf->currency).' '.$dif?>, favor de verificar</td>
			</tr>
			<?
			}				
		
		$id = $rec->id;
		
		$row = $rec->fetch_next($id, 0);		
?>
		</table>
		<br><br>
<?php
	}
}else{
	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
}

print '<script>
		function deleteFile(obj) {
			if (confirm("¿Esta seguro de eliminar el archivo "+obj.className+" ?")) {
				// Create our XMLHttpRequest object
				var hr = new XMLHttpRequest();
				// Create some variables we need to send to our PHP file
				var url = "delete_file.php";
				var id = obj.id;
				var vars = "id="+id;
				hr.open("POST", url, true);
				hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				// Access the onreadystatechange event for the XMLHttpRequest object
				hr.onreadystatechange = function() {
					if(hr.readyState == 4 && hr.status == 200) {
						var return_data = hr.responseText;
						alert(return_data);
						location.reload(true);
					}
				}
				hr.send(vars);
				// Actually execute the request
			}
		}
		</script>';
llxFooter();

dol_htmloutput_mesg($msg);
dol_htmloutput_errors($msgerror);
dol_htmloutput_events();

$db->close();
?>
