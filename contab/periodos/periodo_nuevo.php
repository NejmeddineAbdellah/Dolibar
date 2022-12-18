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
$page = "1";

if ($action == "new_period") {
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
}
/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

$arrayofjs = array('..//js/functions.js');

llxHeader('','Polizas','','','','',$arrayofjs,'',0,0);

$head=array();        // Tableau des onglets

$h = 0;
// Élément décrivant un onglet. Il y aura autant de $h que d'onglets à afficher
$head[$h][0]="panel.php"; // Url de la page affichée quand on clique sur l'onglet
$head[$h][1]="Todos"; // Titre de l'ongLet
$head[$h][2]="todos";
$h++;

// Élément décrivant un onglet. Il y aura autant de $h que d'onglets à afficher
$head[$h][0]="periodo_nuevo.php"; // Url de la page affichée quand on clique sur l'onglet
$head[$h][1]="Nuevo"; // Titre de l'ongLet
$head[$h][2]="nuevo";

dol_fiche_head($head, $page, 'Periodos', 0, '');

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

dol_htmloutput_mesg($mesg);
dol_htmloutput_events();

llxFooter();

$db->close();
?>