<?php
/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jpfarber@auribox.com, jfarber55@hotmail.com
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
 * module créé par 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
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

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
$langs->load("contab@contab");

// Get parameters
$id = GETPOST('id', 'int');
$action = "view";
if (GETPOST('action')) {
	$action = GETPOST('action', 'alpha');
}
$myparam = GETPOST('myparam', 'alpha');

dol_syslog("Dol url root=".DOL_URL_ROOT);

// Protection if external user
if ($user->societe_id > 0) {
    //accessforbidden();
}

/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */
$valores = array();

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabrelctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabsatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

require_once '../functions/functions.php';

llxHeader('', 'Terceros', '');
$title="Configuracion";
$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($title,$linkback,'setup');
$head=array();        // Tableau des onglets

$head = contab_admin_prepare_head($object, $user);
dol_fiche_head($head, '4', 'Configuracion', 0, '');

$head2=array();        // Tableau des onglets
$h2 = 0;
$head2[$h2][0]="exportar.php?mod=1"; // Url de la page affichée quand on clique sur l'onglet
$head2[$h2][1]="Exportar Polizas"; // Titre de l'ongLet
$head2[$h2][2]="Exportar Polizas";
$h2++;
$head2[$h2][0]="exportar.php?mod=2"; // Url de la page affichée quand on clique sur l'onglet
$head2[$h2][1]="Importar Polizas"; // Titre de l'ongLet
$head2[$h2][2]="Importar Polizas";
$h2++;
if(GETPOST('mod')==1){
	$mod=1;
}else{
	if(GETPOST('mod')==2){
		$mod=2;
	}else{
		$mod=1;
	}
}
if($mod==1){
	dol_fiche_head($head2, '0', 'Exportar/Importar', 0, '');
}else{
	if($mod==2){
		dol_fiche_head($head2, '1', 'Exportar/Importar', 0, '');
	}
}
if($user->rights->contab->expimppol){
	if($mod==1){
		include "exporta_polizas.php";
	}else{
		if($mod==2){
			include "importa_polizas.php";
		}
	}
}else{
	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
}