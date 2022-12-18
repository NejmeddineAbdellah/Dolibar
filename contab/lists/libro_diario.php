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

require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';

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

/* if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/facture.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/fournisseur.facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/fournisseur.facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/fournisseur.facture.class.php';
} */

require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';

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
$id = GETPOST('id');

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

$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);
?>
<h1 align="center"><?=$conf->global->MAIN_INFO_SOCIETE_NOM?></h1>
<h1 align="center">Libro Diario</h1>
<h3>Periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h3>
<form>
	<table class="noborder" width="100%">
		<tr class="liste_titre">
			<?
			if($id>0){
			?>
			<td colspan="7" style="text-align: right">
				<a href="libro_diario_print.php?tipo=excel&a=<?=$anio;?>&m=<?=$mes;?>&id=<?=$id?>" target="popup">
					Descargar Excel
				</a>
			</td>
			<td colspan="1" style="text-align: right">
				<a href="libro_diario_print.php?tipo=pdf&a=<?=$anio;?>&m=<?=$mes;?>&id=<?=$id?>" target="popup">
					Descargar PDF
				</a>
			</td>
			<?php 
			}else{
			?>
			<td colspan="7" style="text-align: right">
				<a href="libro_diario_print.php?tipo=excel&a=<?=$anio;?>&m=<?=$mes;?>" target="popup">
					Descargar Excel
				</a>
			</td>
			<td colspan="1" style="text-align: right">
				<a href="libro_diario_print.php?tipo=pdf&a=<?=$anio;?>&m=<?=$mes;?>" target="popup">
					Descargar PDF
				</a>
			</td>
			<?php 
			}
			?>
		</tr>

<?php
		$ff = new FactureFournisseur($db);
		$f = new Facture($db);

		$soc = new Societe($db);

		$ctas = new Contabcatctas($db);
		
		$pd = new Contabpolizasdet($db);
		
		$pol = new Contabpolizas($db);
		$pol->anio = $anio;
		$pol->mes = $mes;
		//print $mes;
		if(GETPOST('m')==13){
			//$pol->fetch_next(0, 1,13);
		}else{
			$pol->fetch_next(0, 1);
		}
		//$rowid_ini = $pol->id;
		
		if ($id > 0) { $solo_uno = true; $id = $id - 1; }
		
		if(GETPOST('m')==13){
			if($id!=0){
				$res = $pol->fetch_next($id, 1,13);
			}else{
			$res = $pol->fetch_next(0, 1,13);
			}
		}else{
			$res = $pol->fetch_next($id, 1);
		}
		$rowid = $pol->id;
		
		$numero = 1;
		
		if ($res) {
			while ($res > 0) {
				//print GETPOST('m')."::<br>";
				if($mes==13){
					$mm=12;
				}else{
					$mm=$mes;
				}
				if ($pol->anio == $anio && $pol->mes == $mm) {
					//print_r($pol);print "<br><br>";
					$sqln="SELECT count(*) as nump
						FROM ".MAIN_DB_PREFIX."contab_polizasdet
						WHERE fk_poliza=".$pol->id;
					$nsq=$db->query($sqln);
					$nrq=$db->fetch_object($nsq);
					if($nrq->nump>0){
?>
					<tr class="liste_titre">
						<td colspan="8">Encabezado de la poliza</td>
					</tr>
					<tr>
						<td><strong>Numero: </strong><?=$pol->id;?></td>						
						<td><strong>Poliza: </strong><?=$pol->Get_folio_poliza()?> <strong> Cons: </strong><?=$pol->cons?></td>
						<td colspan= "6"></td>
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
?>
					<tr>
						<td><strong>Concepto: </strong></td><td colspan="8"><?=$pol->concepto?></td>
					</tr>
					<tr>
						<td><strong>Comentario: </strong></td><td colspan="8"><?=$pol->comentario?></td>
					</tr>
					<tr>
						<td><strong>Fecha: </strong></td><td colspan="8"><?=date('Y-m-d',$pol->fecha)?></td>
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
					<tr class="liste_titre">
						<td>Cuenta</td>
						<td colspan="3">Descripcion</td>
						<td colspan="1">Concepto</td>
						<td colspan="1">UUID</td>
						<td style="text-align: right">Debe</td>
						<td style="text-align: right">Haber</td>
					</tr>
<?php 
					$cond = " t.fk_poliza = ".$pol->id." ";
					$res2 = $pd->fetch_next(0, $cond);
					$idpoldet = $pd->id;
					if ($res2) {
						$totdebe=0;
						$tothaber=0;
						while ($res2) {
							//$ctas->fetch_by_Cta($pd->cuenta);
							
							$nom_soc = "";
							//Verificar primeramente si se trata de un artículo
							if (!$ctas->fetch_by_Cta($pd->cuenta, false)) {
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
							dol_syslog("nom_soc=$nom_soc");
							if (!$nom_soc) {
								dol_syslog("Estoy viendo la cuenta");
								$ctas->fetch_by_Cta($pd->cuenta,true);
								$nom_soc = $ctas->descta;
							}
							
							//$descta = $ctas->descta;
							$idcta = $ctas->id;
							$totdebe+=$pd->debe;
							$tothaber+=$pd->haber;
?> 
							<tr>
<?php 
								if ($pd->debe != 0) {
?>
									<td><?=$pd->cuenta;?></td>
									<td colspan="3"><?=$nom_soc?></td>
									<td colspan="1"><?=$pd->desc?></td>
									<td colspan="1"><?=$pd->uuid?></td>
									<td style="text-align: right;"><?=($pd->debe <> 0 ? $langs->getCurrencySymbol($conf->currency)." ".number_format(abs($pd->debe), 2) : ''); ?></td>
									<td>&nbsp;</td>
<?php 
								} else if($pd->haber != 0){
?>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pd->cuenta;?></td>
									<td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$nom_soc?></td>
									<td colspan="1"><?=$pd->desc?></td>
									<td colspan="1"><?=$pd->uuid?></td>
									<td>&nbsp;</td>
									<td style="text-align: right;"><?=($pd->haber <> 0 ? $langs->getCurrencySymbol($conf->currency)." ".number_format(abs($pd->haber), 2) : ''); ?></td>
<?php	 
								}
?>
							</tr>
<?php 
								$res2 = $pd->fetch_next($idpoldet, $cond);
							$idpoldet = $pd->id;
						}
					}
?>
					<tr>
						<td colspan="6" style="text-align: right;"><strong>Total</strong></td>
						<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency)." ".number_format(abs($totdebe), 2);?></td>
						<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency)." ".number_format(abs($tothaber), 2);?></td>
					</tr>
					<?php 
					if($id){}else{
					?>
					<tr>
						<td colspan="8" style="text-align: center;"><hr></td>
					</tr>
					<?php }?>
<?php
				  }
				}
				if(GETPOST('m')==13){
					$res = $pol->fetch_next($rowid, 1,13);
				}else{
					$res = $pol->fetch_next($rowid, 1);
				}
				//dol_syslog("Res = $res");
				$rowid = $pol->id;
				
				if ($solo_uno) { $res = false; }
			}
		}
?>		
	</table>
</form>
<?php 

llxFooter();

$db->close();