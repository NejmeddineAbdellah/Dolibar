<?php
/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jfarber55@hotmail.com, jpfarber@gmail.com
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

global $langs;

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
$langs->load('bills');
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

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/admin/Configuration.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpaymentterm.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpaymentterm.class.php';
}

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

llxHeader('', 'Configuracion', '');
$title="Configuracion";
$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($title,$linkback,'setup');

$head=array();        // Tableau des onglets

$head = contab_admin_prepare_head($object, $user);
dol_fiche_head($head, '0', 'Configuracion', 0, '');

?>
<style>
    h1{
        text-align: center;
    }
    .critical{
        width: 80%;
        min-height: 30px;
        border: 3px solid red;
        background-color: #fe7c7c;
        display:block;
        position: relative;
        margin: 0 auto;
    }
    .helpLink{
        text-align: center;
        width: 100%;
        position: relative;
        display:block;
    }
</style>

<?php 
if ($action == "cfgpayment") {
	$op = $_POST["ddlcreategroupfromfirstone"];
	if ($op == "S") {
		$g = new Contabpaymentterm($db);
		$g->create_from_firstone();
	}
	$action = "view";
}

if ($conf->entity > 1) {
	$gg = new Contabpaymentterm($db);
	if (! $arr_conf = $gg->fetch_array()) {
?>
	<table style="width: 100%;"> 
	<tr>
		<td style="width: 50%; text-align: right;">Desea cargar las codiciones de pago predeterminadas del modulo contab?</td>
		<td style="width: 20%;">
			<form method="post">
				<input type="hidden" name="action" value="cfgpayment" >
				<select name="ddlcreategroupfromfirstone">
					<option value="N">No</option>
					<option value="S">Si</option>
				</select>
				<input type="submit" name="btnSavePaymentCond" value=" Crear Grupos " >
			</form>
		</td>						 
<?php 
	} else {
?>
		<td style="width: 70%; text-align: right;">.</td>
<?php 
	}
} else {
?>
		<td style="width: 70%; text-align: right;">.</td>
	</tr>
	</table>
<?php 
}

if($user->rights->contab->ccpagos){
?>
<form method="post" action="?action=save">
<table class="noborder" style="width: 100%">
	<tbody>
		<tr class="liste_titre">
			<th style="width: 50%" colspan="2">Condicion de Pago de una Factura:</th>
			<th style="width: 50%">Se Considera como:</th>
		</tr>

<?php
$pmt = new Contabpaymentterm($db);
$arr_conf = $pmt->fetch_array();

$recargar = 0;

$config = new Configuration($db);

if ($action == "save") {
	if ($_POST) {
		$config->saveSettings($_POST, 1);
	}
	$cond_pago = $_POST;
} else {
	$cond_pago = $config->getCondiciones_de_Pago();
}

$form = new Form($db);
$form->load_cache_conditions_paiements();

$count = sizeof($form->cache_conditions_paiements);
foreach ($form->cache_conditions_paiements as $i => $arr) {		
	$code = strtolower($arr['code']);
	
	$name = "cond_pago_".$i;
	$cp = $cond_pago[$name];
	
	$option = "<option value='1'". ($cp == 1 ? " selected = 'selected' " : "") .">Contado</option>";
	$option .= "<option value='2'". ($cp == 2 ? " selected = 'selected' " : "") .">Credito</option>";
	$option .= "<option value='3'". ($cp == 3 ? " selected = 'selected' " : "") .">Anticipo</option>";
	$option .= "<option value='4'". ($cp == 4 ? " selected = 'selected' " : "") .">50/50</option>";
	
	dol_syslog("Opciones a mostrar en el codigo=$code: ".$option);
?>
	<tr>
		<td><?=$arr['code'];?></td>
		<td><?=$arr['label'];?></td>
	    <td>
			<select class="flat" name="<?=$name;?>" >
	       		<option value="0" selected="selected">-- Seleccione --</option>
	       		<?php print $option; ?>
	       	</select>
	    </td>
	</tr>
<?php
}
?>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="Guardar Cambios" style="width: 135px" ></td>
	</tr>
</tbody>
</table>
</form>
<?php 
}else{
	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
	
}
// End of page
llxFooter();
$db->close();
?>
