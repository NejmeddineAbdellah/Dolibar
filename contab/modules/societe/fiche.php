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
include_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';

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

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

if (! $user->rights->contab->cont) {
	accessforbidden();
}

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$anio 		= GETPOST("anio");
$mes 		= GETPOST("mes");
$anio_selected	= GETPOST("anio");
$socid		= GETPOST("socid", "int");

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

if ($action == "") {

}
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

$form = new Form($db);

llxHeader('','Cuentas','','','','',$arrayofjs,'',0,0);

dol_syslog("Contab - Modules- Societe - Fiche - socid=$socid, action=$action");

$object = new Societe($db);
if ($socid > 0)
{
	/*
	 * Affichage onglets
	*/
	
	$object->fetch($socid);
	
	if (! empty($conf->notification->enabled)) $langs->load("mails");

	$head = societe_prepare_head($object);
	dol_fiche_head($head, 'tabcustctas', $langs->trans("ThirdParty"), 0 ,'company');


	print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
	print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';

	print '<table class="border" width="100%">';

	print '<tr><td width="25%">'.$langs->trans('ThirdPartyName').'</td>';
	print '<td colspan="3">';
	print $form->showrefnav($object,'socid','',($user->societe_id?0:1),'rowid','nom');
	print '</td></tr>';

	if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
	{
		print '<tr><td>'.$langs->trans('Prefix').'</td><td colspan="3">'.$object->prefix_comm.'</td></tr>';
	}
	
	if ($object->client)
	{
		print '<tr><td>';
		print $langs->trans('CustomerCode').'</td><td colspan="3">';
		print $object->code_client;
		if ($object->check_codeclient() <> 0) print ' <font class="error">('.$langs->trans("WrongCustomerCode").')</font>';
		print '</td></tr>';
	}
	
	if ($object->fournisseur)
	{
		print '<tr><td>';
		print $langs->trans('SupplierCode').'</td><td colspan="3">';
		print $object->code_fournisseur;
		if ($object->check_codefournisseur() <> 0) print ' <font class="error">('.$langs->trans("WrongSupplierCode").')</font>';
		print '</td></tr>';
	}
	
	print "</table>";
	
	if ($object->client)
	{
		print "<br>";
		print "<strong>Contabilidad Electrónica</strong>";
		print "<br>";
		print "La Asignación de Cuentas automáticas solamente está disponible para proveedores de servicios o proveedores de Activo Fijo.";
	}
	
	if ($object->fournisseur)
	{
		print "<br>";
		print "<strong>Contabilidad Electrónica</strong>";
		print "<br>";
		print "Asignación de Cuentas automáticas como proveedor de Gastos o proveedores de Activo Fijo.";
		
	
		dol_fiche_end();
	
		$cta = new Contabcatctas($db);
		$dep = new Contabcatctas($db);
		
		$sup = new Contabctassupplier($db);
		$res = $sup->fetch_next($id, $socid);
		
		$ctamayor = 0;
		
		print "<br>";
		print '<a href="../../admin/config_tercero.php?action=nuevo_activo&socid='.$socid.'">Asignar otra cuenta de Activo a este Tercero, presione aquí.</a>, o ';
		print '<a href="../../admin/config_tercero.php?action=nuevo_gasto&socid='.$socid.'">Asignar otra cuenta de Gasto a este Tercero, presione aquí.</a>';
		print "<br><br>";
		print "<strong>Cuenta(s) Relacionada(s):</strong><br>";
		while ($res) {
			$cta->fetch($sup->fk_cta);
			$dep->fetch($cta->subctade);
			//print "dep=".$dep->id." ctamayor=$ctamayor";
			if (!($ctamayor == $dep->id)) {
				print "<br>Cuenta de Mayor: ".$dep->cta." - ".$dep->descta."    ";
				print "<br>";
				$ctamayor = $dep->id;
			} 
			print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cuenta: ".$cta->cta." - ".$cta->descta;
			
			print "<br>";
			$id = $sup->id;
			$res = $sup->fetch_next($id, $socid);
		}
	}
}
dol_htmloutput_mesg($mesg);
dol_htmloutput_events();
	
llxFooter();

$db->close();
?>