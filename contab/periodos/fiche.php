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


/* JPFarber - Módulo inicial en el cual se muestran los periodos contables, así como la facilidad de crear uno que no se haya creado automaticamente y el listado de periodos que existen en la BD y que se pueden controlar desde este panel de control. */


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

sleep(2);

$res=0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
    $res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
    $res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
    $res = @include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

// Change this following line to use the correct relative path from htdocs
if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolizas.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizas.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolizas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

//Ver si la parte de la creación de archivos XML está en el directorio especificado.
$exists_crea_xml = false;
if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/crear_xml.class.php')){
	$exists_crea_xml = true;
	require_once DOL_DOCUMENT_ROOT.'/contab/class/crear_xml.class.php';
}
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
if (! $user->rights->contab->cont) {
	accessforbidden();
}

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

//Check if the current period exists
$p = new Contabperiodos($db);
$anio = date("Y");
$mes = date("m");
if (! $p->fetch_by_period($anio, $mes)) {
	$p->anio = date("Y");
	$p->mes = date("m");
	$p->estado = 1;
	$p->validado_bg = 0;
	$p->validado_bc = 0;
	$p->validado_er = 0;
	$p->validado_ld = 0;
	$p->validado_lm = 0;
	$p->create($user);
}

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$anio 		= GETPOST("anio");
$mes 		= GETPOST("mes");
$anio_selected	= GETPOST("anio");
$tipo_envio = GETPOST("envio");

dol_syslog("Action = $action Id = $id");

// Protection if external user
if ($user->societe_id > 0)
{
	//No dar acceso
}

/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

if ($action == "close_period") {
	$p = new Contabperiodos($db);
	$p->close_period($anio, $mes);
	
	if ($p->period_is_closed($anio, $mes)) {
		//$pol = new Contabpolizas($db);
		//$pol->reindexar();
		
		$mesg = "El periodo ha sido cerrado.";
	} else {
		$mesg = "El periodo no pudo cerrarse, esto se puede deber a que no se han validado los reportes.";
	}
	$action = "";
} else if ($action == "open_period") {
	$p = new Contabperiodos($db);
	$p->reopen_period($anio, $mes);
	$mesg = "El periodo fue reabierto.";
	$action = "";
} else if ($action == "new_period") {
	$p = new Contabperiodos($db);
	if (! $p->fetch_by_period($anio, $mes)) {
		$p->anio = $anio;
		$p->mes = $mes;
		$p->estado = $p::PERIODO_ABIERTO;
		$p->validado_bg = 0;
		$p->validado_bc = 0;
		$p->validado_er = 0;
		$p->validado_ld = 0;
		$p->validado_lm = 0;
		$p->create($user);
		
		$mesg = "El periodo contable ($anio - $mes), fue creado satisfactoriamente.";
	} else {
		$mesg = "El periodo contable ($anio - $mes), ya existe en la Base de datos.";
	}
	$action = "";
} else if ($action == "reindex") {
	$pol = new Contabpolizas($db);
	$pol->reindexar();
	$mesg = "El proceso de reindexación de registros de Pólizas ha terminado.";
} else if ($action == "create_catalogo_xml") {
	if ($exists_crea_xml) {
		$xml = new CrearXML($db);
		$xml->file_path = $fullpath=DOL_DATA_ROOT."/". ($conf->entity > 1 ? $conf->entity : ""). "/contab";
		
		$xml->Verify_Path();
		
		if (!$xml->error) {
			$xml->anio = $anio;
			$xml->mes = $mes;
			$xml->rfc = $conf->global->MAIN_INFO_SIREN;
$xml->xmlstr = 
<<<XML
<catalogocuentas:Catalogo xmlns:catalogocuentas="www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas/CatalogoCuentas_1_1.xsd">
</catalogocuentas:Catalogo>
XML;
			$xml->Crea_Catalogo();
			$mesg = $xml->mesg;
			$errors = $xml->errors;
			dol_syslog("===== En la clase: errors=$errors, mesg=$mesg");
			if ($errors) {
				$cta_err = $xml->cta_err;
			}
		} else {
			$errors = $xml->errors;
		}
	} else {
		$errors = "No tiene instalado el complemento de la contabilidad electronica para creacion de archivos XML";
	}
} else if ($action == "create_balanza_xml") {
	if ($exists_crea_xml) {
				$xml = new CrearXML($db);
		$xml->file_path = $fullpath=DOL_DATA_ROOT."/". ($conf->entity > 1 ? $conf->entity : ""). "/contab";
		
		$xml->Verify_Path();
		
		if (!$xml->error) {
			$xml->anio = $anio;
			$xml->mes = $mes;
			$xml->rfc = $conf->global->MAIN_INFO_SIREN;
			$xml->tipo_envio = ($tipo_envio == "normal" ? "N" : "C");
$xml->xmlstr = 
<<<XML
<BCE:Balanza xmlns:BCE="http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/BalanzaComprobacion" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/BalanzaComprobacion http://www.sat.gob.mx/esquemas/ContabilidadE/1_3/BalanzaComprobacion/BalanzaComprobacion_1_3.xsd">
</BCE:Balanza>
XML;
			$xml->Crea_Balanza();
			$mesg = $xml->mesg;
			$errors = $xml->errors;
			dol_syslog("===== En la clase: errors=$errors, mesg=$mesg");
			if ($errors) {
				$cta_err = $xml->cta_err;
			}
		} else {
			$errors = $xml->errors;
		}
	} else {
		$errors = "No tiene instalado el complemento de la contabilidad electronica para creacion de archivos XML";
	}
}else if ($action == "create_xml_polizas") {
	if ($exists_crea_xml) {
				$xml = new CrearXML($db);
		$xml->file_path = $fullpath=DOL_DATA_ROOT."/". ($conf->entity > 1 ? $conf->entity : ""). "/contab";
		
		$xml->Verify_Path();
		
		if (!$xml->error) {
			$xml->anio = $anio;
			$xml->mes = $mes;
			$xml->rfc = $conf->global->MAIN_INFO_SIREN;
			$xml->tipo_envio = "AF";///REVISAR ESTO XML
$xml->xmlstr = 
<<<XML
<PLZ:Polizas xmlns:PLZ="http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo/PolizasPeriodo_1_1.xsd">
</PLZ:Polizas>
XML;
			$xml->Crea_xml_Polizas();
			$mesg = $xml->mesg;
			$errors = $xml->errors;
			dol_syslog("===== En la clase: errors=$errors, mesg=$mesg");
			if ($errors) {
				$cta_err = $xml->cta_err;
			}
		} else {
			$errors = $xml->errors;
		}
	} else {
		$errors = "No tiene instalado el complemento de la contabilidad electronica para creacion de archivos XML";
	}
}
 /* else if ($action == "create_poliza_xml") {
	if ($exists_crea_xml) {
		$xml = new CrearXML($db);
		$xml->anio = $anio;
		$xml->mes = $mes;
		$xml->rfc = $conf->global->MAIN_INFO_SIREN;
		$xml->tipo_solicitud = ($tipo_envio == "normal" ? "N" : "C");
$xml->xmlstr = 
<<<XML
<BCE:Balanza xmlns:BCE="http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion/BalanzaComprobacion_1_1.xsd">
<PLZ:Polizas xmlns:PLZ="http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo/PolizasPeriodo_1_1.xsd">
</PLZ:Polizas>
XML;
		$xml->Crea_Polizas();
		$mesg = $xml->mesg;
		$errors = $xml->errors;
		dol_syslog("===== En la clase: errors=$errors, mesg=$mesg");
		if ($errors) {
			$cta_err = $xml->cta_err;
		}
	} else {
		$errors = "No tiene instalado el complemento de la contabilidad electrónica para creación de archivos XML";
	} 
}*/

/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/js/functions.js')) {
	$arrayofjs = array('/contab/js/functions.js');
} else {
	$arrayofjs = array('/custom/contab/js/functions.js');
}

llxHeader('','Periodos Contables','','','','',$arrayofjs,'',0,0);

$head=array();
$head = contab_prepare_head($object, $user);
dol_fiche_head($head, '0', 'Contabilidad', 0, '');

$mes = date("m");
$anio = date("Y");
$ok = false;

$per = new Contabperiodos($db);
$aanios = array();
$aanios = $per->get_anios_array();
if (! $anio_selected > 0) {
	$anio_selected = date("Y");
}
?>
<form method="get">
	<h1>Periodos Contables:</h1>
	<select name="anio" onchange="this.form.submit()">
		<option value="0">--Seleccione--</option>
<?php 
		foreach ($aanios as $i => $anio) {
			?><option value="<?=$anio;?>" <?=($anio == $anio_selected) ? 'selected="selected"' : '' ;?>><?=$anio;?></option><?php
		}
?>
	</select>
</form>
<?php 
if ($action == "create_period") {
?>
	<br>
	<form action="?action=new_period" method="post">
		<h2>Crear un nuevo Periodo Contable</h2>
		Año: <input type="text" name="anio" value="<?php print date("Y");?>" > 
		<br>
		Mes: <input type="text" name="mes" value="<?php print date("m"); ?>">
		<br>
		<br>
		<input type="submit" value="Crear Periodo" >
	</form>
	<br>
<?php 
}
//dol_fiche_end();

?>

<form action="?action=open" method="get">
<?php 
	$rs = $per->fetch_next_period($per::TODOS_LOS_PERIODOS, $anio_selected, 0);
	if ($rs > 0) {
?>
		<br>
		<h3>Relacion de Periodos Reportados en el A&ntilde;o:</h3>
		<table style="width: 100%; background-color:white" class="noborder" >
			<tr class="liste_titre">
				<th colspan="2">&nbsp;</th>
				<th colspan="10" style="text-align: center;"><strong>R E P O R T E S&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C O N T A B L E S</strong></th>
				<th colspan="2">&nbsp;</th>
<?php 
				if ($exists_crea_xml) {
?>
					<th colspan="3">&nbsp;</th>
<?php 
				}
?>
			</tr>
			<tr>
				<th>Mes:</th>
				<th>Estado</th>
				<th style="width: 12%;text-align: center;" colspan="2">Balance</th>
				<th style="width: 12%;text-align: center;" colspan="2">Balaza de</th>
				<th style="width: 12%;text-align: center;" colspan="2">Estado de</th>
				<th style="width: 12%;text-align: center;" colspan="2">Libro</th>
				<th style="width: 12%;text-align: center;" colspan="2">Cuentas</th>
				<!--  <th style="width: 12%;text-align: center;" colspan="2">Auxiliar de</th>-->
				<th>&nbsp;</th>
				<th>&nbsp;</th>
<?php 
				if ($exists_crea_xml) {
?>
					<th colspan="3">Archivos XML</th>
<?php 
				}
?>
			</tr>
			<tr>
				<th></th>
				<th></th>
				<th colspan="2">General</th>
				<th colspan="2">Comprobacion</th>
				<th colspan="2">Resultados</th>
				<th colspan="2">Diario</th>
				<th colspan="2">de Mayor</th>
				<!-- <th colspan="2">Cuentas</th>-->
				<th>Periodo</th>
				<th>&nbsp;</th>
<?php 
				if ($exists_crea_xml) {
?>
					<th>Catalogo</th>
					<th>Balanza</th>
					<th>Polizas</th>
<?php 
				}
?>
			</tr>
<?php 
		while ($rs) {
			$anio = $per->anio;
			$mes = $per->mes;
			$strmes = $per->MesToStr($mes);

			$date2 = date("U"); /* to have it in microseconds */
			$date1_stamp = mktime(0,0,0,$mes+1,"01",$anio);
			$date1 = date("U",$date1_stamp);
				
			//print $date2 >= $date1 ? "SI" : "NO";
			$dias = round (($date2 - $date1)/(3600*24));
			//echo "The difference is :" . $dias . "<br/>";
				
			//print " Dias:".$dias;
			$para_validar = 0;
			$mostrar = 1;
			$ok = 0;
			if ($per->estado == $per::PERIODO_ABIERTO) {
				if ($dias > MAX_DAYS_FOR_DELAY_AFTER_MONTH_ENDS) {
					$ok = 0;
					$para_validar = 1;
					$file_name = "red_small.png";
				} else {
					if ($date2 >= $date1) {
						$ok = 1;
						$para_validar = 1;
						$file_name = "yellow_small.png";
					} else {
						$ok = 1;
						$mostrar = 0;
						$file_name = "green_small.png";
					}
				}
			} else if ($per->estado == $per::PERIODO_CERRADO) {
				$ok = 1;
				$file_name = "green_small.png";
			} 
			
			if ($para_validar == 0 && $mostrar == 0) {
				$file_name = "blue_small.png";
			}
			dol_syslog("Para validar=$para_validar, ok=$ok, mostrar=$mostrar");
			//print "";
			//print ($per->estado == $per::PERIODO_ABIERTO) ? "Abierto" : "";
			//print ($per->estado == $per::PERIODO_CERRADO) ? "Cerrado" : "";
?>
			<tr>
				<td>
					<?=($per->estado == $per::PERIODO_ABIERTO) ? "<strong>" : "";?>
						<?=$strmes;?>
					<?=($per->estado == $per::PERIODO_ABIERTO) ? "</strong>" : "";?>
				</td>
				<td style="text-align: center;">
					<img src='<?="../images/".$file_name;?>' height='18px' width='18px'>
				</td>
<?php 
				$bg = '../lists/balance_general.php?a='.$anio.'&m='.$mes;
				$bc = '../lists/balanza_comp.php?a='.$anio.'&m='.$mes;
				$er = '../lists/edo_res.php?a='.$anio.'&m='.$mes;
				$ld = '../lists/libro_diario.php?a='.$anio.'&m='.$mes;
				$lm = '../lists/mayor.php?a='.$anio.'&m='.$mes;
				$aux = '../lists/auxiliares.php?a='.$anio.'&m='.$mes;
				
				$validado_bg = $per->validado_bg;
				$validado_bc = $per->validado_bc;
				$validado_er = $per->validado_er;
				$validado_ld = $per->validado_ld;
				$validado_lm = $per->validado_lm;

				if ($para_validar == 1) { 
?>
					<td style="text-align: center;" colspan="2">
<?php 
					if($user->rights->contab->valreportes){
						if ($validado_bg) {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' onclick="save_valida('bg', false, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' onclick="save_valida('bg', true, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						}
					}else{
						if ($validado_bg) {
							?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' ></a>
<?php 
						}
					}
?>
					
<?php
				} else {
					if ($mostrar == 1) {
?>
						<td style="text-align: center;" colspan="2"><img src="<?="../images/ok.png";?>" height='16px' width='16px'>
<?php
					} else {
?>
						<td style="text-align: center;" colspan="2">&nbsp;&nbsp;
<?php 
					}
				}
?>
					&nbsp;<a href="<?=$bg;?>" >
						<img src='<?="../images/docto.png";?>' height='16px' width='16px'>
					</a>
				</td>
<?php 
				if ($para_validar == 1) { 
?>
					<td style="text-align: center;" colspan="2">
<?php 
					if($user->rights->contab->valreportes){
						if ($validado_bc) {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' onclick="save_valida('bc', 0, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' onclick="save_valida('bc', 1, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						}
					}else{
						if ($validado_bc) {
	?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' ></a>
<?php 
						}
					}
?>
					 
<?php
				} else {
					if ($mostrar == 1) {
?>
						<td style="text-align: center;" colspan="2"><img src="<?="../images/ok.png";?>"  height='16px' width='16px'> 
<?php
					} else {
?>
						<td style="text-align: center;" colspan="2">&nbsp;&nbsp;
<?php 
					}
				}
?>
				 
					&nbsp;<a href="<?=$bc;?>" >
						<img src='<?="../images/docto.png";?>' height='16px' width='16px'>
					</a>
				</td>
<?php 
				if ($para_validar == 1) { 
?>
					<td style="text-align: center;" colspan="2">
<?php 
					if($user->rights->contab->valreportes){
						if ($validado_er) {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' onclick="save_valida('er', 0, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' onclick="save_valida('er', 1, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						}
					}else{
						if ($validado_er) {
	?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' ></a>
<?php 
						}
					}
?>
					 
<?php
				} else {
					if ($mostrar == 1) {
?>
						<td style="text-align: center;" colspan="2"><img src="<?="../images/ok.png";?>"  height='16px' width='16px'> 
<?php
					} else {
?>
						<td style="text-align: center;" colspan="2">&nbsp;&nbsp;
<?php 
					}
				}
?>
				&nbsp;<a href="<?=$er;?>" >
						<img src='<?="../images/docto.png";?>' height='16px' width='16px'>
					</a>
				</td>
<?php 
				if ($para_validar == 1) { 
?>
					<td style="text-align: center;" colspan="2">
<?php 
					if($user->rights->contab->valreportes){
						if ($validado_ld) {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' onclick="save_valida('ld', 0, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' onclick="save_valida('ld', 1, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						}
					}else{
						if ($validado_ld) {
	?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' ></a>
<?php 
						}
					}
?>
					 
<?php
				} else {
					if ($mostrar == 1) {
?>
						<td style="text-align: center;" colspan="2"><img src="<?="../images/ok.png";?>"  height='16px' width='16px'> 
<?php
					} else {
?>
						<td style="text-align: center;" colspan="2">&nbsp;&nbsp;
<?php 
					}
				}
?>
				&nbsp;<a href="<?=$ld;?>" >
						<img src='<?="../images/docto.png";?>' height='16px' width='16px'>
					</a>
				</td>
<?php 
				if ($para_validar == 1) { 
?>
					<td style="text-align: center;" colspan="2">
<?php 
					if($user->rights->contab->valreportes){
						if ($validado_lm) {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' onclick="save_valida('lm', 0, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' onclick="save_valida('lm', 1, <?=$anio;?>, <?=$mes;?>, <?=$para_validar?>);" ></a>
<?php 
						}
					}else{
						if ($validado_lm) {
	?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Validado" src="<?="../images/ok.png";?>" height='16px' width='16px' ></a>
<?php 
						} else {
?>
							<a href="?anio=<?=$anio;?>&action="><img id="image" title="Reporte Sin Validar" src="<?="../images/not_ok.png";?>" height='16px' width='16px' ></a>
<?php 
						}
					}
?>
					 
<?php
				} else {
					if ($mostrar == 1) {
?>
						<td style="text-align: center;" colspan="2"><img src="<?="../images/ok.png";?>"  height='16px' width='16px'> 
<?php
					} else {
?>
						<td style="text-align: center;" colspan="2">&nbsp;&nbsp;
<?php 
					}
				}
?>
				&nbsp;<a href="<?=$lm;?>" >
						<img src='<?="../images/docto.png";?>' height='16px' width='16px'>
					</a>
				</td>
				
				<!-- <td>&nbsp;</td>
				<td style="text-align: center;">
					<a href="<?=$aux;?>" >
						<img src='<?="../images/docto.png";?>' height='16px' width='16px'>
					</a>
				</td>-->
				
				<td id="td_<?=$anio.$mes?>">
					<strong>
<?php 
						if ($para_validar == 0 && $mostrar == 1 && $user->rights->contab->reabrirper) {
							?><a href="?action=open_period&anio=<?=$anio?>&mes=<?=$mes?>">Reabrir</a><?php 
						} else if ($para_validar == 1) {
							if($validado_bg && $validado_bc && $validado_er && $validado_ld && $validado_lm && $user->rights->contab->cerrarper){
							?><a href="?action=close_period&anio=<?=$anio?>&mes=<?=$mes?>">Cerrar</a><?php
							} else{
								if($validado_bg==0 || $validado_bc==0 || $validado_er==0 || $validado_ld==0 || $validado_lm==0){
									if($user->rights->contab->valreportes){
									?>
									<a href="?anio=<?=$anio;?>&action=" onclick="save_valida2(<?=$anio;?>, <?=$mes;?>);"> Validar Reportes</a>
									<?php 
									}
								}
							}
						}
?>
					</strong>
					
				</td>
				<td>
					<!-- <a href="?action=reindex&anio=<?=$anio?>&mes=<?=$mes?>">Reindexar</a>-->
				</td>
<?php 
				if ($exists_crea_xml) {
					if ($para_validar == 0 && $mostrar == 1 && $user->rights->contab->gesxml) {
?>
						<td>
							<a href="?action=create_catalogo_xml&anio=<?=$anio?>&mes=<?=$mes?>">Crear</a>
						</td>
						<td>
							<a href="?action=create_balanza_xml&anio=<?=$anio?>&mes=<?=$mes?>&envio=normal">Normal</a> o 
							<a href="?action=create_balanza_xml&anio=<?=$anio?>&mes=<?=$mes?>&envio=complementaria">Comp.</a> 
						</td>
						<td>
							<a href="?action=create_xml_polizas&anio=<?=$anio?>&mes=<?=$mes?>">Crear</a>
						</td>
						
<?php
					} else if ($para_validar == 1) {
?>
						<td></td>
						<td></td>
						<td></td>
						<!--  <td>Crear</td>
						<td>Nor. o Comp.</td>-->
						<!--  <td>Crear</td>   -->
						
<?php
					} 
				}
?>
			</tr>
			<tr><td colspan="19"></td></tr>
<?php 
			$anio = $per->anio;
			$mes = $per->mes;
			$rs = $per->fetch_next_period($per::TODOS_LOS_PERIODOS, $anio, $mes);
		}
?>
		</table>
		<br>
		<br>
		<?php 
		if($user->rights->contab->creaper){
		?>
		<a href="?action=create_period" class="button">Crear un nuevo periodo</a>
		<?php 
		}
		?>
		<!-- <a href="?action=create_period" class="button">Para Crear un nuevo periodo, presione aqui.</a> -->
	</form>
<?php
	} else {
		$mesg = '<div class="error">Favor de inicializar las tablas.  Vaya a Inicio / Configuración / Módulos / Contab.</div>';
	}

if ($mesg) {
	dol_htmloutput_mesg($mesg);
	print "<h4><label style='color: green'>".$mesg."</label></h4>";
}
if ($errors) {
	dol_htmloutput_errors($errors);
	
	if ($cta_err) {
		print "<h4 style='color: red'>El archivo 'balanza.xml' se creó con errores, favor de corregir y realizar de nuevo el proceso.</h4>";
		print "<h5>Las cuentas que se encontraron con error fueron:</h5>";
		foreach ($cta_err as $i => $ce) {
			print $cta_err[$i][0]." - ".$cta_err[$i][1]."<br>";
		}
	}
}
dol_htmloutput_events();
	
llxFooter();

$db->close();
?>