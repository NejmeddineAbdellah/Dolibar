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
if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpolizas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpolizasdet.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabsatctas.class.php';
}

if (! $user->rights->contab->cont) {
	accessforbidden();
}

// Change this following line to use the correct relative path from htdocs

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$anio = GETPOST('a');
$mes = GETPOST('m');
$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);

llxHeader('','Polizas','','','','',$arrayofjs,'',0,0);
global $db,$conf,$langs;

$cuenta=GETPOST('cta');
//print $cuenta;
$ctas = new Contabcatctas($db);
$ctas->fetch($cuenta);
?>

<h1 align="center"><?=$conf->global->MAIN_INFO_SOCIETE_NOM?></h1>
<h1 align="center">Auxiliar de Cuentas - Polizas Cuenta:<?=$ctas->cta." ".$ctas->descta?></h1>
<h3>Saldo inicial periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h3>

<table class="noborder" style="width: 100%">
		<tr class="liste_titre">
			<!--<td colspan="6" style="text-align: right">
				<a href="aux_polizas_print.php?cta=<?=$cuenta;?>&a=<?=$anio;?>&m=<?=$mes;?>" target="popup">
					Descargar
				</a>
			</td>-->
		</tr>
		<tr class="liste_titre">
			<td style="width: 10%">Cuenta</td>
			<td  style="width: 70%" colspan="3">Descripcion</td>
			<td style="width: 10%;text-align: right">Debe</td>
			<td style="width: 10%;text-align: right">Haber</td>
		</tr>
<?php 
$mm = sprintf("%02d", $mes);
$sql="SELECT d.rowid,d.cta,d.descta, c.rowid as poliza
		FROM ".MAIN_DB_PREFIX."contab_cat_ctas d,
		(SELECT a.rowid,a.cons,b.cuenta
		FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b
		WHERE CONCAT(anio,LPAD(mes,2,'0'))<CONCAT('$anio','$mm') AND entity=".$conf->entity." AND a.rowid=b.fk_poliza AND b.cuenta=".$ctas->cta.") c 
		WHERE entity=".$conf->entity." AND d.cta=c.cuenta AND d.cta=".$ctas->cta;
//print $sql;
$r=$db->query($sql);
while($rs=$db->fetch_object($r)){
	$ff = new FactureFournisseur($db);
	$f = new Facture($db);
	
	$soc = new Societe($db);
	
	$ctas = new Contabcatctas($db);
	
	$pd = new Contabpolizasdet($db);
	
	$pol = new Contabpolizas($db);
	$pol->fetch($rs->poliza,0);
	?>
						<tr>
							<td><strong>Numero: </strong><?=$pol->id;?></td>
							<td><strong>Poliza: </strong><?=$pol->Get_folio_poliza()?> <strong>Cons: </strong><?=$pol->cons?></td>
							<td colspan= "4"></td>
						</tr>
	<?php
					if ($pol->societe_type == 1) {
						//Es un Cliente
						$f->fetch($pol->fk_facture);
						$facid=$f->id;
						$facnumber = $f->ref;
						$pagina = "/compta/facture.php";
					} else if($pol->societe_type == 2) {
						//Es un Proveedor
						$ff->fetch($pol->fk_facture);
						$facid=$ff->id;
						$facnumber = $ff->ref;
						$pagina = "/fourn/facture/fiche.php";
						if(DOL_VERSION>="3.7"){
							$pagina = "/fourn/facture/card.php";
						}
					}
						$sql2="SELECT rowid
							FROM ".MAIN_DB_PREFIX."contab_polizasdet
							WHERE fk_poliza=".$pol->id;
						$rest2=$db->query($sql2);
						while ($fg2=$db->fetch_object($rest2)) {
							$pd = new Contabpolizasdet($db);
							$pd->fetch($fg2->rowid);
							/*Cabecera*/
							$nom_soc = "";
							//Verificar primeramente si se trata de un artículo
							if (!$ctas->fetch_by_Cta($pd->cuenta, false)) {
								if ($pol->societe_type == 1) {
									if ($soc->fetch($f->socid)) {
										$nom_soc = $soc->nom;
									}
								} else if ($pol->societe_type == 2) {
									if ($soc->fetch($ff->socid)) {
										$nom_soc = $soc->nom;
									}
								}
							}
								
							if (!$nom_soc) {
								$ctas->fetch_by_Cta($pd->cuenta);
								$nom_soc = $ctas->descta;
							}
							?>
							<tr>
<?php 
								if ($pd->debe != 0) {
?>
									<td><?=$pd->cuenta;?></td>
									<td colspan="3"><?=$nom_soc?></td>
									<td style="text-align: right;"><?=($pd->debe <> 0 ? $langs->getCurrencySymbol($conf->currency)." ".number_format(abs($pd->debe), 2) : ''); ?></td>
									<td>&nbsp;</td>
<?php 
								} else if($pd->haber != 0){
?>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pd->cuenta;?></td>
									<td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$nom_soc?></td>
									<td>&nbsp;</td>
									<td style="text-align: right;"><?=($pd->haber <> 0 ? $langs->getCurrencySymbol($conf->currency)." ".number_format(abs($pd->haber), 2) : ''); ?></td>
<?php	 
								}
?>
							</tr>
							<?
							
							unset($pd);
						}
						
	
	?>
	<tr>
						<td><strong>Concepto: </strong></td><td colspan="5"><?=$pol->concepto?></td>
					</tr>
					<tr>
						<td><strong>Comentario: </strong></td><td colspan="5"><?=$pol->comentario?></td>
					</tr>
					<?php 
					if ($pol->societe_type == 1 || $pol->societe_type == 2) {
					?>
						<tr><td colspan = "2">
							Documento Relacionado: <a href="<?=DOL_URL_ROOT.$pagina;?>?facid=<?=$facid;?>"><?php echo $facnumber; ?></a>
						</td></tr>
					<?php 
					}
					?>
					<tr>
						<td colspan="6" style="text-align: center;"><?='>====================================================================================================================================='?></td>
					</tr>
	<?php
}  

?>

</table>











