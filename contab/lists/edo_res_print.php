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

if(GETPOST('tipo')=='excel'){
	header("Content-type: application/ms-excel");
	header("Content-disposition: attachment; filename=estado_resultados.xls");
}

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

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabgrupos.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabgrupos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabgrupos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabperiodos.class.php';
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

if(GETPOST('tipo')=='excel'){
$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Estado de Resultados<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>

	<table border="1">
		<tr>
			<td colspan="3">Concepto</td>
			<td style="text-align:right">Saldo Inicial</td>
			<td style="text-align:right">Movimientos del Mes</td>
			<td style="text-align:right">Saldo Actual</td>
		</tr>';
		
		$edo_financiero = 2;
		
		$sumtot = 0;
		
		$pd = new Contabpolizasdet($db);
		
		$id = 0;
		$gpos = new Contabgrupos($db);

		$sdotot = 0;
		$sdo_gpo = 0;
		$sdo_grupos = 0;
		
		$sdotot_ini = 0;
		$sdo_gpo_ini = 0;
		$sdo_grupos_ini = 0;
		
		//Se obtiene el grupo i
		$r = $gpos->fetch_next($id, $edo_financiero, 1);
		
		$id = $gpos->id;
		
		//Este grupo abarca desde el codigo de agrupación ini hasta el codigo de agrupación fin.
		$id_row_min = $gpos->fk_codagr_ini;
		$id_row_max = $gpos->fk_codagr_fin;
		
		if ($r) {
			
			do {
				$codagr = $gpos->codagr_rel;
				$ctas = new Contabcatctas($db);
				
				if ($gpos->grupo !== "Gastos") {
					$id2 = $gpo_id_min - 1;
					$cond = " nivel = 2 AND (s.rowid between ".$id_row_min." AND ".$id_row_max.") ";
					
					$s = $ctas->fetch_next2($id2, $cond);
					$id2 = $ctas->id;
					
					$sdo_cta = 0;
					$sdo_cta_ini = 0;
					
					if ($s) {
						while ($s) {
							//$ctas->fetch_saldos($ctas->id, $anio, $mes);
							$ctas->fetch_saldos2($ctas->id, $anio, $mes);
							$sdo_cta = $sdo_cta + $ctas->saldo;
							
							//$ctas->fetch_saldos_iniciales($ctas->id, $anio, $mes);
							$ctas->fetch_saldos_iniciales2($ctas->id, $anio, $mes);
							$sdo_cta_ini = $sdo_cta_ini + $ctas->saldo;
							
							$s = $ctas->fetch_next2($id2, $cond);
							$id2 = $ctas->id;
							
						}
					}
					$sdo_tot += $sdo_cta;
					$sdo_gpo += $sdo_cta;
					$sdo_grupos += $sdo_cta;
					
					$sdo_tot_ini += $sdo_cta_ini;
					$sdo_gpo_ini += $sdo_cta_ini;
					$sdo_grupos_ini += $sdo_cta_ini;
					
					$codigo_ant = $gpos->codagr_ini;
					$var = !$var;
					
					$rep .= '
					<tr>
						<td colspan="3">'.utf8_decode($gpos->grupo).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta_ini,2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta,2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta_ini + $sdo_cta,2).'</td>
					</tr>';

				}
				$sdotot += $sdo_cta;

				$r = $gpos->fetch_next($id, $edo_financiero, 1);
				
				$id = $gpos->id;
				
				//Este grupo abarca desde el codigo de agrupación ini hasta el codigo de agrupación fin.
				$id_row_min = $gpos->fk_codagr_ini;
				$id_row_max = $gpos->fk_codagr_fin;
				
				if ($gpos->grupo !== "Gastos") {
				if ($codigo_ant >= '615' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '614' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .= '
					<tr>
					<td colspan="3" style="text-align: right;"><strong>Total:</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad antes de Otros Gastos y Otros Productos Financieros:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>
					<tr></tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;

				} else if ($codigo_ant >= '613' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '612' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '611' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '610' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '609' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '608' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '607' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '606' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '605' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .='
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Total Gastos de Operaci&oacute;n:</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad o P&eacute;rdida de Operaci&oacute;n:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>
					<tr></tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
				} else if ($codigo_ant >= '604' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '603' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '602' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '601' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {

				} else if ($codigo_ant >= '505' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .= '
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Total Otros Costos:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad Antes de Operaci&oactue;n:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>
					<tr></tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
				} else if ($codigo_ant >= '504' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '503' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .= '
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Compras Netas:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos_ini + $sdo_grupos, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Costo de Venta:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad Bruta:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>
					<tr></tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				} else if ($codigo_ant >= '502' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '501' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .= '
					<tr>
						<td colspan="6"></td>
					</tr>'; 
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				} else if ($codigo_ant >= '400' && substr($codigo_ant,0 , 1) != substr($gpos->codagr_ini, 0, 1)) {
					$rep .= '
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Ingreso Neto:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr></tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				}
				}
			} while ($r);
		}
		
		if ($sdotot > 0) 	{ $etiqueta = "Utilidad del Ejercicio:"; }
		else 				{ $etiqueta = "P&eacute;rdida del Ejercicio:"; }
		
		$rep .= '
		<tr>
			<td colspan="3" style="text-align: right;"><strong>Total Otros gastos y Productos Financieros:</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
		</tr>
		<tr></tr>
		<tr>
			<td colspan="3" style="text-align: right;"><strong>'.$etiqueta.'</strong></td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
		</tr>
	</table>
';
}else{
	$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Estado de Resultados<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>
	
	<table border="1"  style="border-collapse: collapse;width: 95%;font-size:10px;" align="center">
		<tr>
			<td colspan="3" style="width: 50%">Concepto</td>
			<td style="text-align:right;width: 16%;">Saldo Inicial</td>
			<td style="text-align:right;width: 16%;">Movimientos del Mes</td>
			<td style="text-align:right;width: 16%;">Saldo Actual</td>
		</tr>';
	
	$edo_financiero = 2;
	
	$sumtot = 0;
	
	$pd = new Contabpolizasdet($db);
	
	$id = 0;
	$gpos = new Contabgrupos($db);
	
	$sdotot = 0;
	$sdo_gpo = 0;
	$sdo_grupos = 0;
	
	$sdotot_ini = 0;
	$sdo_gpo_ini = 0;
	$sdo_grupos_ini = 0;
	
	//Se obtiene el grupo i
	$r = $gpos->fetch_next($id, $edo_financiero, 1);
	
	$id = $gpos->id;
	
	//Este grupo abarca desde el codigo de agrupación ini hasta el codigo de agrupación fin.
	$id_row_min = $gpos->fk_codagr_ini;
	$id_row_max = $gpos->fk_codagr_fin;
	
	if ($r) {
			
		do {
			$codagr = $gpos->codagr_rel;
			$ctas = new Contabcatctas($db);
	
			if ($gpos->grupo !== "Gastos") {
				$id2 = $gpo_id_min - 1;
				$cond = " nivel = 2 AND (s.rowid between ".$id_row_min." AND ".$id_row_max.") ";
					
				$s = $ctas->fetch_next($id2, $cond);
				$id2 = $ctas->id;
					
				$sdo_cta = 0;
				$sdo_cta_ini = 0;
					
				if ($s) {
					while ($s) {
						//$ctas->fetch_saldos($ctas->id, $anio, $mes);
						$ctas->fetch_saldos2($ctas->id, $anio, $mes);
						$sdo_cta = $sdo_cta + $ctas->saldo;
							
						//$ctas->fetch_saldos_iniciales($ctas->id, $anio, $mes);
						$ctas->fetch_saldos_iniciales2($ctas->id, $anio, $mes);
						$sdo_cta_ini = $sdo_cta_ini + $ctas->saldo;
							
						$s = $ctas->fetch_next($id2, $cond);
						$id2 = $ctas->id;
							
					}
				}
				$sdo_tot += $sdo_cta;
				$sdo_gpo += $sdo_cta;
				$sdo_grupos += $sdo_cta;
					
				$sdo_tot_ini += $sdo_cta_ini;
				$sdo_gpo_ini += $sdo_cta_ini;
				$sdo_grupos_ini += $sdo_cta_ini;
					
				$codigo_ant = $gpos->codagr_ini;
				$var = !$var;
					
				$rep .= '
					<tr>
						<td colspan="3">'.utf8_decode($gpos->grupo).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta_ini,2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta,2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta_ini + $sdo_cta,2).'</td>
					</tr>';
	
			}
			$sdotot += $sdo_cta;
	
			$r = $gpos->fetch_next($id, $edo_financiero, 1);
	
			$id = $gpos->id;
	
			//Este grupo abarca desde el codigo de agrupación ini hasta el codigo de agrupación fin.
			$id_row_min = $gpos->fk_codagr_ini;
			$id_row_max = $gpos->fk_codagr_fin;
	
			if ($gpos->grupo !== "Gastos") {
				if ($codigo_ant >= '615' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '614' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .= '
					<tr>
					<td colspan="3" style="text-align: right;"><strong>Total:</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad antes de Otros Gastos y Otros Productos Financieros:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
	
				} else if ($codigo_ant >= '613' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '612' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '611' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '610' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '609' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '608' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '607' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '606' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '605' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .='
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Total Gastos de Operaci&oacute;n:</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad o P&eacute;rdida de Operaci&oacute;n:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
				} else if ($codigo_ant >= '604' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '603' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '602' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '601' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
	
				} else if ($codigo_ant >= '505' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .= '
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Total Otros Costos:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad Antes de Operaci&oactue;n:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
				} else if ($codigo_ant >= '504' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '503' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					$rep .= '
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Compras Netas:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos_ini + $sdo_grupos, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Costo de Venta:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Utilidad Bruta:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
					</tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				} else if ($codigo_ant >= '502' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '501' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
// 					$rep .= '
// 					<tr>
// 						<td colspan="6"></td>
// 					</tr>';
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				} else if ($codigo_ant >= '400' && substr($codigo_ant,0 , 1) != substr($gpos->codagr_ini, 0, 1)) {
					$rep .= '
					<tr>
						<td colspan="3" style="text-align: right;"><strong>Ingreso Neto:</strong></td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
						<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
					</tr>';
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				}
			}
		} while ($r);
	}
	
	if ($sdotot > 0) 	{ $etiqueta = "Utilidad del Ejercicio:"; }
	else 				{ $etiqueta = "P&eacute;rdida del Ejercicio:"; }
	
	$rep .= '
		<tr>
			<td colspan="3" style="text-align: right;"><strong>Total Otros gastos y Productos Financieros:</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2).'</td>
		</tr>
		<tr>
			<td colspan="3" style="text-align: right;"><strong>'.$etiqueta.'</strong></td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
		</tr>
	</table>
';
}


if(GETPOST('tipo')=='pdf'){
	require_once '../class/dompdf/dompdf_config.inc.php';
	$dompdf = new DOMPDF();
	$dompdf->load_html($rep);
	$dompdf->render();
	$dompdf->stream("estado_resultados.pdf",array('Attachment'=>0));
}else{
	print $rep;
}
?>
