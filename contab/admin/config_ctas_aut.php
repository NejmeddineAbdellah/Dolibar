<?php
// /* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
//  * Copyright (C) ---Put here your own copyright and developer email---
//  * 					JPFarber - jpfarber@auribox.com, jfarber55@hotmail.com
//  *
//  * This program is free software; you can redistribute it and/or modify
//  * it under the terms of the GNU General Public License as published by
//  * the Free Software Foundation; either version 3 of the License, or
//  * (at your option) any later version.
//  *
//  * This program is distributed in the hope that it will be useful,
//  * but WITHOUT ANY WARRANTY; without even the implied warranty of
//  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  * GNU General Public License for more details.
//  *
//  * You should have received a copy of the GNU General Public License
//  * along with this program. If not, see <http://www.gnu.org/licenses/>.
//  * 
//  * module créé par 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
//  */

// /**
//  *   	\file       dev/skeletons/skeleton_page.php
//  * 		\ingroup    mymodule othermodule1 othermodule2
//  * 		\brief      This file is an example of a php page
//  * 					Put here some comments
//  */
// //if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
// //if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
// //if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
// //if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
// //if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
// //if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
// //if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
// //if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
// //if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
// //if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
// //if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)
// // Change this following line to use the correct relative path (../, ../../, etc)
// $res = 0;
// if (!$res && file_exists("../main.inc.php"))
//     $res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
// if (!$res && file_exists("../../main.inc.php"))
//     $res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
// if (!$res && file_exists("../../../main.inc.php"))
//     $res = @include '../../../main.inc.php';     // Used on dev env only
// if (!$res && file_exists("../../../../main.inc.php"))
//     $res = @include '../../../../main.inc.php';   // Used on dev env only
// if (!$res)
//     die("Include of main fails");
// // Change this following line to use the correct relative path from htdocs
// dol_include_once('/module/class/skeleton_class.class.php');

// // Load traductions files requiredby by page
// $langs->load("companies");
// $langs->load("other");
// $langs->load("contab@contab");

// // Get parameters
// $id = GETPOST('id', 'int');
// $action = "view";
// if (GETPOST('action')) {
// 	$action = GETPOST('action', 'alpha');
// }
// $myparam = GETPOST('myparam', 'alpha');

// dol_syslog("Dol url root=".DOL_URL_ROOT);

// // Protection if external user
// if ($user->societe_id > 0) {
//     //accessforbidden();
// }

// /* * *****************************************************************
//  * ACTIONS
//  *
//  * Put here all code to do according to value of "action" parameter
//  * ****************************************************************** */
// $valores = array();

// if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php')) {
// 	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php';
// } else {
// 	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabrelctas.class.php';
// }

// if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php')) {
// 	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php';
// } else {
// 	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabsatctas.class.php';
// }

// if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
// 	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
// } else {
// 	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
// }

// require_once '../functions/functions.php';

// llxHeader('', 'Configuracion', '');
// $title="Configuracion";
// $linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
// print_fiche_titre($title,$linkback,'setup');
// $head=array();        // Tableau des onglets

// $head = contab_admin_prepare_head($object, $user);
// dol_fiche_head($head, '3', 'Configuracion', 0, '');

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
if ($action == "ccffo") {
	$op = $_POST["ddlConfig"];
	if ($op == "S") {
		$g = new Contabrelctas($db);
		$g->create_from_firstone();
	}
}

$rel = new Contabrelctas($db);
$arr_rel = $rel->fetch_array();
$recargar = 0;

$hay_datos = false;
if (!$arr_rel) {
?>
	<form method="post" action="cuentas.php?mod=1&action=ccffo">
		Desea cargar la configuracion por default de las cuentas automaticas?
		<select name="ddlConfig">
			<option value="N">No</option>
			<option value="S">Si</option>
		</select>
		<input type="submit" name="btnCargar" value=" Cargar Conf. Inicial ">
	</form>
	<br/>
	<br/>
<?php 
}
?>
<form method="post" action="cuentas.php?mod=1&action=save">
<table class="noborder" style="width: 100%">
	<tbody>
		<tr class="liste_titre">
			<th>Descripción</th>
			<th>Relacionado con el siguiente Codigo de Agr. en el Catalogo Princiapl</th>
		</tr>
<?php
if ($action == "save") {	
	if ($_POST) {
		foreach($_POST as $key => $value) {
			//print "<br>POST - key=".$key." value=".$value." - ".$_POST["ddl_efectivo"];
 			if($rel->fetch_by_code($key)) {
				//print "<br>fk_sat_cta=".$rel->fk_sat_cta." value=$value";
				if ($value == $rel->fk_sat_cta ) {
				} else {
					$rel->fk_sat_cta = $value;
					$rel->update_fk_sat_cta();
					$recargar = 1;
				}
			}
		}	
	}
}

if ($recargar == 1) {
	$arr_rel = $rel->fetch_array();
}

$sat = new Contabsatctas($db);
$arr_sat = $sat->fetch_array();

//print_r($arr_sat);

foreach ($arr_rel as $i => $a) {
	$code = strtolower($a->code);
?>
	<tr>
		<td><?=$a->description;?></td>
	    <td>
	       	<select name="<?=$code;?>"  style="width: 435px" >
	       		<option value="0" selected="selected">-- Seleccione --</option>
<?php 
				foreach($arr_sat as $j => $as) {
					?><option value="<?=$as["rowid"];?>" <?=($as["rowid"] == $a->fk_sat_cta) ? 'selected="selected"' : ''; ?>><?=$as["codagr"]." - ".$as["descripcion"];?></option><?php
				}
?>
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

/* $msg = "";
if (!$config->HayRegistros_CatCtas() > 0){
	$msg = "Favor de realizar la importación de su catálogo de cuentas o la creación de su catálogo de cuentas en el Sistema.";
} */

// End of page
llxFooter();
$db->close();
?>
