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

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

require_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';

if (! $user->rights->contab->cont) {
	accessforbidden();
}
// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
$langs->load("bills");

/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

$form = new Form($db);

/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

//$arrayofjs = array('../js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

llxHeader('','','','','','',$arrayofjs,'',0,0);

$head = contab_prepare_head($object, $user);
dol_fiche_head($head, "Polizas", 'Contabilidad', 0, '');

print "<br><br><strong>Nota: <label style='color:blue'>Después de realizar sus cambios, y para visualizar todas las pólizas mostradas anteriormente, presione sobre el tab llamado 'Pólizas'</label></strong>";
dol_fiche_end();

$tp = "";
$cons = "";

$pol = new Contabpolizas($db);
$poldet = new Contabpolizasdet($db);
$ctas = new Contabcatctas($db);

$arr = $pol->fetch_outof_period();

//var_dump($arr);

$vuelta = 0;

foreach ($arr as $i => $idpol) {
	$pol->fetch($idpol, 0);
?>
	<table class="noborder" style="width:100%">
		<tr class="liste_titre">
			<td colspan="4">Encabezado de la Poliza</td>
			<td style="text-align: right;">
				<a href="poliza.php?id=<?=$pol->id; ?>&amp;action=delpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Borrar Poliza</a>
			</td>
		</tr>
<?php 
		if ($tp !== $pol->tipo_pol || $cons !== $pol->cons) {
			$ff = new FactureFournisseur($db);
			$f = new Facture($db);
			$soc = new Societe($db);
			$var = !$var;
			$tp = $pol->tipo_pol;
			$cons = $pol->cons;
			$facid = $pol->fk_facture;
						
			if ($pol->societe_type == 1) {
				//Es un Cliente
				$f->fetch($pol->fk_facture);
				$facnumber = $f->ref;
				$pagina = "/compta/facture.php";
			} else if($pol->societe_type == 2) {
				//Es un Proveedor
				$ff->fetch($pol->fk_facture);
				$facnumber = $ff->ref;
				$pagina = "/fourn/facture/fiche.php";
				if(DOL_VERSION>="3.7"){
					$pagina = "/fourn/facture/card.php";
				}
			}
?>			
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "2">
					Poliza: <strong><?php print $pol->Get_folio_poliza().": ".$cons; ?></strong>
					<a href="poliza.php?id=<?=$pol->id; ?>&action=editenc<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_edit(); ?></a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
				<td colspan = "2">Fecha: <?php print date("Y-m-d",$pol->fecha);?></td>
				<td colspan = "2">
					Documento Relacionado: <a href="<?=DOL_URL_ROOT.$pagina;?>?facid=<?=$facid;?>"><?php echo $facnumber; ?></a>
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "6">
					Concepto: <strong><?php echo substr($pol->concepto,0,150); ?></strong>
					&nbsp;
					Comentario: <strong><?php echo substr($pol->comentario,0,150); ?></strong>
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "6">
					Cheque a Nombre: <strong><?php echo substr($pol->anombrede,0,150); ?></strong>
					&nbsp;
					Num. Cheque: <strong><?php echo substr($pol->numcheque,0,150); ?></strong>
				</td>
			</tr>
			
<?php
		}
?>
	<tr class="liste_titre">
			<td width="15%">Asiento</td>
			<td width="50%">Cuenta</td>
			<td style="text-align: right; width: 10%;">Debe</td>
			<td style="text-align: right; width: 10%;">Haber</td>
			<td colspan='1' style="text-align: right;"></td>
		</tr>
<?php 
		$cond = " fk_poliza = ".$pol->id;
		$rr = $poldet->fetch_next(0, $cond);
		$totdebe=0;
		$tothaber=0;
		if ($rr) {
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
				<tr <?php print $bc[$var]; ?>>
					<td><?php print $poldet->asiento; ?></td>
					<td><?php print $poldet->cuenta." "; 
					if ($nom_soc) {
						print $nom_soc;
					} else {
						$ctas->fetch_by_Cta($poldet->cuenta, false);
						print $ctas->descta;
					}
					$totdebe+=$poldet->debe;
					$tothaber+=$poldet->haber;
					?></td>
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
		}
		?>
		<tr>
				<td colspan='2' align="right">
				<strong>Total</strong>
				</td>
				<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($totdebe, 2)?></td>
				<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($tothaber, 2)?></td>
			</tr>
		<?php
		$id = $pol->id;
		
		if ($esfaccte == 1 || $esfacprov == 1) {
			$soc_type = ($esfaccte == 1) ? 1 : 2;
			$row = $pol->fetch_next_by_facture_id($id, $facid, $soc_type);
			dol_syslog("1. Se regresa este valor del Fetch_Next=".$row);
		} else if($socid > 0) {
			$row = $pol->fetch_next_by_societe_id($id, $socid);
			dol_syslog("2. Se regresa este valor del Fetch_Next=".$row);
		} else {
			$row = $pol->fetch_next($id, 1);
			dol_syslog("3. Se regresa este valor del Fetch_Next=".$row);
		}
		
		$vuelta = 1;
		
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