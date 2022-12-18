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
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizas.class.php';
require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php';

if (! $user->rights->contab->cont) {
	accessforbidden();
}

// Change this following line to use the correct relative path from htdocs

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters

// Protection if external user
if ($user->societe_id > 0)
{
	//accessforbidden();
}

/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/


/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

$arrayofjs = array('/contab/js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

llxHeader('','Libro_Diario','','','','',$arrayofjs,'',0,0);

?>
<h1>Periodo contable: <?=$cfg->anio." - ".$cfg->MesToStr($cfg->mes);?></h1>
<form>
	<h1>Libro Diario</h1>
	<table class="noborder">
		<tr class="liste_titre">
			<td style="width: 10%">Cuenta</td>
			<td style="width: 60%">Descripción</td>
			<td style="width: 10%">Debe</td>
			<td style="width: 10%">Haber</td>
		</tr>

<?php 
		$pol = new Contabpolizas($db);
		$res = $pol->fetch_next();
		$rowid = $pol->id;
		if ($res) {
			while ($res > 0) {
?>
				<tr>
					<td>Consecutivo: <?php $pol->id - $rowid + 1; ?></td>
				</tr>
<?php
				foreach ($pol->lines as $i => $l) {
?> 
					<tr>
<?php 
						if ($l->debe > 0) {
?>
							<td>Aqui va la cuenta de cargo</td>
							<td>&nbsp;</td>
							<td><?php print round($l->debe, 2); ?></td>
							<td>&nbsp;</td>
<?php 
						} else {
?>
							<td>&nbsp;</td>
							<td>Aqui va la cuenta de abono</td>
							<td>&nbsp;</td>
							<td><?php print round($l->debe, 2); ?></td>
<?php 
						}
?>
					</tr>
<?php 
				}
?>
				<tr>
					<td><?php $pol->comentario;?>
				</td>
<?php 
				$res = $pol->fetch_next($rowid);
				dol_syslog("Res = $res");
				$rowid = $pol->id;
			}
		}
?>		
	</table>
</form>
<?php 

llxFooter();

$db->close();