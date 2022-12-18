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
 *   	\file       dev/skeletons/skeleton_page.php
 * 		\ingroup    mymodule othermodule1 othermodule2
 * 		\brief      This file is an example of a php page
 * 					Put here some comments
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
$res = 0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
    $res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
    $res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
    $res = @include '../../../../main.inc.php';   // Used on dev env only
if (!$res)
    die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
dol_include_once('/module/class/skeleton_class.class.php');

include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	$custom = 0;
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	$custom = 1;
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabctassupplier.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolizasdet.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabrelctas.class.php';
}

require_once '../functions/functions.php';

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
$langs->load("contab@contab");

// Get parameters
$id = GETPOST('id', 'int');
$action = GETPOST('action');
$socid  = GETPOST('socid', 'int');

$myparam = GETPOST('myparam', 'alpha');

if ($id == '') { $id = 0; }

// Protection if external user
if ($user->societe_id > 0) {
    //accessforbidden();
}

llxHeader('', 'Configuracion', '');

$head=array();        // Tableau des onglets

$head = contab_admin_prepare_head($object, $user);
dol_fiche_head($head, '6', 'Terceros vs. Activos', 0, '');

/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */
$deactivate = 0;
if ($action == "save") {
	$sup = new Contabctassupplier($db);
	$sup->fk_socid = GETPOST("socid");
	$sup->fk_cta = GETPOST("fk_cat_cta");
	$sup->active = 1;
	$sup->fourn_type = $sup::TIPO_PROVEEDOR_ACTIVO;
	$idnew = $sup->create($user);
	if ($idnew) {
		$msg = "Se guardó correctamente la información.";
	} else {
		$msg = "Hubo un error al querer guardar la información.  Favor de revisar los datos.";
	}
} else if ($action == "delete_conf") {
	if (GETPOST('ddl_dellall_conf') == "S") {
		//Se borrará la cuenta de la tabla
		$sup = new Contabctassupplier($db);
		$sup->id = $id;
		if ($sup->delete($user)) {
			$msg = "La relación de Cuenta vs. Tercero fue borrada de la tabla.";
		} else {
			$msg = "Hubo un error al querer borrar de la tabla la relacion de Cuenta vx. Tercero. Favor de verificar los datos.";
		}
	}
	$id = 0;
} else if($action == "edit") {
	$sup = new Contabctassupplier($db);
	$sup->fetch($id);
	$sup->active = 1;
	$sup->update();
	$id = 0;
} else if ($action == "delete") {
	$sup = new Contabctassupplier($db);
	$sup->fetch($id);
	$cta = new Contabcatctas($db);
	$cta->fetch($sup->fk_cta);
	
	$pol = new Contabpolizasdet($db);
	if ($pol->fetch_by_cuenta($cta->cta)) {
		//Solamente se desactivará la cuenta.
		$sup = new Contabctassupplier($db);
		$sup->fetch($id);
		$sup->active = 0;
		if ($sup->update()) {
			$msg = "La relación de Cuenta vs. Tercero solo fue desactivada.";
			$deactivate = 1;
		} else {
			$msg = "Hubo un error al querer desactivar la relacion de Cuenta vx. Tercero. Favor de verificar los datos.";
		}
	}
}

if ($action == "delete" && $deactivate == 0) {
?>
		<form method="post" action="?action=delete_conf">
			<input type="hidden" name="id" value="<?=$id;?>" />
			<input type="hidden" name="deactivate" value="<?=$deactivate?>" />
			Desea realmente borrar esta relación de Tercero vs. Cuenta?
			<select name='ddl_dellall_conf'>
				<option value="N">No</option>
				<option value='S'>Si</option>
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Procesar" />
		</form>
<?php 
} else if ($action == "nuevo") {
	$lok = false;
	//dol_syslog("Nuevo");
	$sql = "Select * From ".MAIN_DB_PREFIX."societe s ";  //Where fournisseur = 1
	$row = array();
	$op = "";
	if ($res = $db->query($sql)) {
		//dol_syslog("Entre al if");
		while ($row = $db->fetch_array($res)) {
			//dol_syslog("Esoy en el while=".$row['rowid']);
			$op .= '<option value="'.$row['rowid'].'"';
			if ($socid == $row['rowid']) {
				$op .= ' selected="selected" ';
			}
			$op .= '>'.$row["nom"].'</option>';
			$lok = true;
		}
	}
	if ($lok == true) {
		$rel = new Contabrelctas($db);
		
		$arr = array();
		
		//$rel->fetch_by_code("VENTAS");
		//$cta_ing = $rel->fk_cat_cta;
		
		$cta = new Contabcatctas($db);
		//$arr = $cta->fetch_array_by_dependede($cta_ing, $arr);

		$rel->fetch_by_code("ACTIVOLP");
		$cta_gtos = ($rel->fk_cat_cta > 0) ? $rel->fk_cat_cta : -1;
		
		//dol_syslog("grales=$cta_gtos_grales, vta=$cta_gtos_vta, admon=$cta_gtos_admon, fab=$cta_gtos_fab");
		
		//$cta = new Contabcatctas($db);
		$arr = $cta->fetch_array_by_dependede($cta_gtos, $arr);
		
		//print_r($arr);
		
		$lok = false;
		$op2 = "";
		$i = 0;
		while ($i < sizeof($arr)) {
			dol_syslog("Esoy en el while=".$row['rowid']);
			$cta->fetch($arr[$i]);
			$op2 .= '<option value="'.$cta->id.'">'.$cta->cta." - ".$cta->descta.'</option>';
			$lok = true;
			
			$i ++;
		}
	}
	if ($lok == true) {
?>
		<h4>Eliga al Proveedor y asigne las cuentas automaticas que le correspondan.</h4>
		Ejemplo (Mexico): Si usted adquiere una computadora (Activo Fijo) para el uso en su empresa, entonces debera elegir para este proveedor, la cuenta con la descripcion "156.01 - Equipo de Computo" que forma parte de la cuenta de mayor denominada "156 - Equipo de Computo".
		
		<br>
		<br>
		<form method="post" action="?action=save">
			Tercero: <select name="socid"><?=$op;?></select>
			<br>
			Cuenta: <select name="fk_cat_cta"><?=$op2;?></select>
			<br>
			<br>
			<input type="submit" value="Guardar" />
		</form>
<?php 
	}
} else {
?>
	<h4>Eliga al Proveedor y asigne las cuentas automáticas que le correspondan.</h4>
	Ejemplo (Mexico): Si usted adquiere una computadora (Activo Fijo) para el uso en su empresa, entonces debera elegir para este proveedor, la cuenta con la descripcion "156.01 - Equipo de Computo" que forma parte de la cuenta de mayor denominada "156 - Equipo de Computo".
	
	<br>
	<br>
	<form>
		<a href="<?php print $_SERVER["PHP_SELF"]; ?>?action=nuevo">Para dar de alta otra cuenta a un Tercero, presione aqui�.</a>
		<br><br>
		<table class="noborder">
			<tr class="liste_titre">
				<td>Proveedor</td>
				<td>Cuenta Relacionada</td>
				<td>Estatus</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
<?php
	$soc = new Societe($db);
	$cta = new Contabcatctas($db);
	
	$arr = array();
	$sup = new Contabctassupplier($db);
	$arr = $sup->fetch_array_by_socid(0, $sup::TIPO_PROVEEDOR_ACTIVO);
	foreach ($arr as $i => $a) {
		$soc->fetch($a->fk_socid);
		$cta->fetch($a->fk_cta);
		$page = DOL_URL_ROOT.($custom == 1 ? '/custom' : '').'/contab/modules/societe/fiche.php?socid='.$a->fk_socid;
?>
		<tr>
			<td><a href="<?=$page;?>"><?=$soc->nom;?></a></td>
			<td><?=$cta->cta." - ".$cta->descta?></td>
			<td><?=($a->active == 1 ? "Activado" : "Desactivado");?>
			<td><a href="?id=<?=$a->rowid;?>&action=edit"><?=img_edit_add("Reactivar"); ?></a></td>
			<td><a href="?id=<?=$a->rowid;?>&action=delete"><?=img_delete(); ?></a></td>
		</tr>
<?php 
	}
?>
		</table>
	</form>
<?php 
}

dol_htmloutput_mesg($msg);
dol_htmloutput_events();

llxFooter();
$db->close();
?>
	