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

if(GETPOST('tipo')=='excel'){
	header("Content-type: application/ms-excel");
	header("Content-disposition: attachment; filename=libro_diario.xls");
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

// Get parameters
$anio = GETPOST('a');
$mes = GETPOST('m');
$id = GETPOST('id');

$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);
if(GETPOST('tipo')=='excel'){
$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Libro Diario<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>
 
	<table class="border" border="1" WIDTH="98%" >
		<tr>
			<td ><strong>Cuenta</strong></td>
			<td style="colspan="1"><strong>Descripci&oacute;n</strong></td>
			<td style="colspan="1"><strong>Concepto</strong></td>
			<td style="colspan="1"><strong>UUID</strong></td>
			<td style="text-align: right"><strong>Debe</strong></td>
			<td style="text-align: right"><strong>Haber</strong></td>
		</tr>';
}else{
	$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Libro Diario<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>
	
	<table class="border" border="1" WIDTH="98%" style="border-collapse: collapse;font-size:10px;">
		<tr>
			<td ><strong>Cuenta</strong></td>
			<td style="colspan="1"><strong>Descripci&oacute;n</strong></td>
			<td style="colspan="1"><strong>Concepto</strong></td>
			<td style="colspan="1"><strong>UUID</strong></td>
			<td style="text-align: right"><strong>Debe</strong></td>
			<td style="text-align: right"><strong>Haber</strong></td>
		</tr>';	
}
		$ff = new FactureFournisseur($db);
		$f = new Facture($db);
		
		$soc = new Societe($db);

		$ctas = new Contabcatctas($db);
		
		$pd = new Contabpolizasdet($db);
		
		$pol = new Contabpolizas($db);
		$pol->anio = $anio;
		$pol->mes = $mes;
		if(GETPOST('m')==13){
			
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
		
		if ($res) {
			while ($res > 0) {
				if($mes==13){
					$mm=12;
				}else{
					$mm=$mes;
				}
				if ($pol->anio == $anio && $pol->mes == $mm) {
					
					$rep .= '
					<tr>
						<td><strong>N&uacute;mero: </strong> '.$pol->id.'</td>
						<td><strong>P&oacute;liza: </strong> '.$pol->Get_folio_poliza().' <strong>Cons: </strong>'.$pol->cons.'</td>						
						<td colspan= "4">&nbsp;</td>
					</tr>';
					
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
					}
					
					$cond = " t.fk_poliza = ".$pol->id." ";
					$res2 = $pd->fetch_next(0, $cond);
					$idpoldet = $pd->id;
					if ($res2) {
						while ($res2) {
							//$ctas->fetch_by_Cta($pd->cuenta);
							
							$nom_soc = "";
							//Verificar primeramente si se trata de un artículo
							if (!$ctas->fetch_by_Cta($pd->cuenta, true)) {
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
							
							/* $rep .= '
							<tr>
							<td>'.$pd->cuenta.'</td>
							<td></td>
							<td>'.utf8_decode($nom_soc).'</td>
							<td></td>
							<td style="text-align: right;">'.($pd->debe <> 0 ? number_format(abs($pd->debe), 2) : '').'</td>
							<td style="text-align: right;">'.($pd->haber <> 0 ? number_format(abs($pd->haber), 2) : '').'</td>
							</tr>'; */
							
							
							if ($pd->debe != 0) {
								$rep .= "<tr>";
								$rep .= '
									<td>'.$pd->cuenta.'</td>
									<td>'.utf8_decode($nom_soc).'</td>
									<td>'.$pd->desc.'</td>
									<td>'.$pd->uuid.'</td>
									<td style="text-align: right;">'.($pd->debe <> 0 ? $langs->getCurrencySymbol($conf->currency)." ".number_format(abs($pd->debe), 2) : '').'</td>
									<td>&nbsp;</td>';
								$rep .= '</tr>';
							} else if($pd->haber != 0) {
								$rep .= "<tr>";
								$rep .= '
									<td>'.$pd->cuenta.'</td>
									<td>'.utf8_decode($nom_soc).'</td>
									<td>'.$pd->desc.'</td>
									<td>'.$pd->uuid.'</td>
									<td>&nbsp;</td>
									<td style="text-align: right;">'.($pd->haber <> 0 ? $langs->getCurrencySymbol($conf->currency)." ".number_format(abs($pd->haber), 2) : '').'</td>';
								$rep .= '</tr>';
							}
							
							if(GETPOST('m')==13){
								$res2 = $pd->fetch_next($idpoldet, $cond,13);
							}else{
								$res2 = $pd->fetch_next($idpoldet, $cond);
							}
							$idpoldet = $pd->id;
						}
					}
					$rep .= '
					<tr>
						<td colspan="6"><strong>Concepto: </strong>'.utf8_decode($pol->concepto).'.</td>
					</tr>
					<tr>
						<td colspan="6"><strong>Comentario: </strong>'.utf8_decode($pol->comentario).'</td>
					</tr>
					<tr>
						<td colspan="6"><strong>Fecha: </strong>'.date('Y-m-d',$pol->fecha).'</td>
					</tr>
					<tr>
						<td colspan="6" style="text-align: center;"><hr></td>
					</tr>';
				}
				$res = $pol->fetch_next($rowid, 1);
				dol_syslog("Res = $res");
				$rowid = $pol->id;
				
				if ($solo_uno) { $res = false; }
			}
		}
$rep .= '
	</table> 
';

if(GETPOST('tipo')=='pdf'){
	//print $rep;
 	require_once '../class/dompdf/dompdf_config.inc.php';
	$dompdf = new DOMPDF();
	$dompdf->load_html($rep);
	$dompdf->render();
	$dompdf->stream("libro_diario.pdf",array('Attachment'=>0));
}else{
	print $rep;
}
?>