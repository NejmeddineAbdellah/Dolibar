<?php
ini_set('max_execution_time', 300);
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
setlocale(LC_MONETARY, 'es_MX');


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
	header("Content-disposition: attachment; filename=balance_general.xls");
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabsatctas.class.php';
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

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/functions/functions.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/functions/functions.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/functions/functions.php';
}

if (! $user->rights->contab->cont) {
	accessforbidden();
}
// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$anio = GETPOST('a');
$mes = GETPOST('m');
$chk_todas = GETPOST('t');

$tipo_edo_financiero = 1; //Balance General

$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);

if(GETPOST('tipo')=='excel'){
$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Balance General<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>
	<table border="1" class="border" style="width: 100%">
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="3" style="text-align: center"><strong>S A L D O S</strong></td>
		</tr>
		<tr>
			<td><strong>Concepto</strong></td>
			<td></td>
			<td></td>
			<td style="text-align: center"><strong>Inicial</strong></td>
			<td style="text-align: center"><strong>Mes</strong></td>
			<td style="text-align: center"><strong>Actual</strong></td>
		</tr>';
}else{
	$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Balance General<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>
	<table border="1"  style="border-collapse: collapse;width: 95%;font-size:9px" align="center" >
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="3" style="text-align: center"><strong>S A L D O S</strong></td>
		</tr>
		<tr>
			<td><strong>Concepto</strong></td>
			<td></td>
			<td></td>
			<td style="text-align: center"><strong>Inicial</strong></td>
			<td style="text-align: center"><strong>Mes</strong></td>
			<td style="text-align: center"><strong>Actual</strong></td>
		</tr>';
}

	$tipo_edo_financiero = 1;

	$pd = new Contabpolizasdet($db);
	$ctas = new Contabcatctas($db);

	$var = true;

	$sumtot = 0;
	$sumtotini = 0;

	$id = 0;
	$gpos = new Contabgrupos($db);
	// Se deberán de tomar únicamente los id de las cuentas de Definición (Activo, Pasivo y Captial)
	// La primera o el primer registro se supone que es Activo por lo mismo se deja en 0 para que agarre el primer registro.
	$r = $gpos->fetch_next($id, $tipo_edo_financiero);
	$aa=0;
	$sactivoini=0;
	$sactivoact=0;
	$sactivofin=0;
	$bb=0;
	$spasivoini=0;
	$spasivoact=0;
	$spasivofin=0;
	$cc=0;
	$sccontableini=0;
	$sccontableact=0;
	$sccontablefin=0;
	$mosacc=0;
	$mosacf=0;
	while ($r) {
		if($gpos->grupo=='Activo'){
			$aa=1;
			$bb=0;
			$cc=0;
		}
		if($gpos->grupo=='Pasivo'){
			$aa=0;
			$bb=1;
			$cc=0;
		}
		if($gpos->grupo=='Capital Contable'){
			$aa=0;
			$bb=0;
			$cc=1;
		}
		$rep .= '
		<tr>
			<td colspan="6">'.$gpos->grupo.'</td>
		</tr>';

		$sdotot = 0;
		$sdototini = 0;

		//Aqui se van a presentar las cuentas de Mayor que vienen debajo de las de Definición que son Act CP, Act LP, Pas CP, etc.
		$id_row_min = $gpos->fk_codagr_ini;
		$id_row_max = $gpos->fk_codagr_fin;

		$id = $gpos->id;
		$r = $gpos->fetch_next($id, $tipo_edo_financiero);

		if ($r) {
			while ($gpos->fk_codagr_ini <= $id_row_max && $r) {
				$imp = '
				<tr <'.$bc[$var].'>
					<td>&nbsp;</td>
					<td colspan="5">'.$gpos->grupo.'</td>
				</tr>';

				$gpo_id_min = $gpos->fk_codagr_ini;
				$gpo_id_max = $gpos->fk_codagr_fin;

				$id2 = $gpo_id_min - 1;
				$id3 = $id2 + 1;
				$cond = " nivel = 2 AND s.rowid <= ".$gpo_id_max." ";

				$sqm2="SELECT cta FROM ".MAIN_DB_PREFIX."contab_cat_ctas a
 						WHERE a.rowid=".$gpo_id_min." OR a.rowid=".$gpo_id_max;
				$rqs2=$db->query($sqm2);
				$rms2=$db->fetch_object($rqs2);
				$cta1=$rms2->cta;
				$rms2=$db->fetch_object($rqs2);
				$cta2=$rms2->cta;
				$cta1=explode('.', $cta1);
				$cta1=$cta1[0]-1;
				//print $cta1." :: ".$cta2."<br>";

				$sqm3="SELECT cta, descta
				FROM llx_contab_cat_ctas
				WHERE cta NOT LIKE '%.%' AND cta BETWEEN '".$cta1."' AND '".$cta2."'";
				$rqs3=$db->query($sqm3);
				while($rms3=$db->fetch_object($rqs3)){
					if($mes==13){
						$mm=$mes-1;
						$sqm4="SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber,natur
						FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b,
							 ".MAIN_DB_PREFIX."contab_cat_ctas c, ".MAIN_DB_PREFIX."contab_sat_ctas d
						WHERE a.anio=".$anio." AND a.mes=".$mm." AND a.rowid=b.fk_poliza AND b.cuenta=c.cta
						AND c.fk_sat_cta=d.rowid AND b.cuenta LIKE '".$rms3->cta."%' AND perajuste=1
						GROUP BY b.cuenta";
					}else{
					$sqm4="SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber,natur
						FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b,
							 ".MAIN_DB_PREFIX."contab_cat_ctas c, ".MAIN_DB_PREFIX."contab_sat_ctas d
						WHERE a.anio=".$anio." AND a.mes=".$mes." AND a.rowid=b.fk_poliza AND b.cuenta=c.cta
						AND c.fk_sat_cta=d.rowid AND b.cuenta LIKE '".$rms3->cta."%' AND perajuste=0
						GROUP BY b.cuenta";
					}
					//print $sqm4."<br>";
					$rqs4=$db->query($sqm4);
					$saldocta=0;
					while($rms4=$db->fetch_object($rqs4)){
						if($rms4->natur=='D'){
							$sald=$rms4->debe-$rms4->haber;
						}else{
							$sald=$rms4->haber-$rms4->debe;
						}
						$saldocta+=$sald;
						//print $rms3->cta."::".$rms4->cuenta."::".$sald."<br>";
					}
					//print $saldocta."<br>";
					$mm = sprintf("%02d", $mes);
					if($mes==13){
						$mm=$mes-1;
						$mm = sprintf("%02d", $mm);
						$sqm5="SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber,natur
							FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b,
								 ".MAIN_DB_PREFIX."contab_cat_ctas c, ".MAIN_DB_PREFIX."contab_sat_ctas d
														 WHERE CONCAT(a.anio,LPAD(a.mes,2,'0')) <= CONCAT('$anio','$mm') AND a.rowid=b.fk_poliza AND b.cuenta=c.cta
														 AND perajuste=0 AND c.fk_sat_cta=d.rowid AND b.cuenta LIKE '".$rms3->cta."%'
							GROUP BY b.cuenta";
					}else{
					$mm = sprintf("%02d", $mes);
					$sqm5="SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber,natur
							FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b,
								 ".MAIN_DB_PREFIX."contab_cat_ctas c, ".MAIN_DB_PREFIX."contab_sat_ctas d
													 WHERE CONCAT(a.anio,LPAD(a.mes,2,'0')) < CONCAT('$anio','$mm') AND a.rowid=b.fk_poliza AND b.cuenta=c.cta
													 AND c.fk_sat_cta=d.rowid AND b.cuenta LIKE '".$rms3->cta."%'
							GROUP BY b.cuenta";
					}
					$rqs5=$db->query($sqm5);
					$saldoini=0;
					//print $sqm4."<br>";
					//print $saldoini." :: ";
					while($rms5=$db->fetch_object($rqs5)){
						if($rms5->natur=='D'){
							$sald2=$rms5->debe-$rms5->haber;
						}else{
							if($rms5->natur=='A'){
								$sald2=$rms5->haber-$rms5->debe;
							}else{
								$sald2=$rms5->debe-$rms5->haber;
							}
						}
						//print $rms4->natur."=".$sald2." :: ";
						$saldoini+=$sald2;
					}
					if($saldocta!=0 || $saldoini!=0){

						//print "<br>";
						$salfin=$saldoini+$saldocta;
						$sdototini+=$saldoini;
						$sdotot+=$saldocta;
						if($aa==1 && $mosacc==0 && ($rms3->cta>='101' && $rms3->cta<='121.01')){
							$mosacc=1;
							$meini=0;
							$memed=0;
							$mefin=0;
							$rep .= "<tr><td></td><td colspan='5'>Activo Circulante</td></tr>";
							/* $mosacc=0;
							 $mosacf=0; */
						}
						if($aa==1 && $mosacf==0 && ($rms3->cta>='151' && $rms3->cta<='191.01')){
							$mosacf=1;
							$maini=0;
							$mamed=0;
							$mafin=0;
							$rep.='<tr>
							<td colspan="3" style="text-align: right;">Total Activo Circulante:</td>
							<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($meini, 2).'</td>
							<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($memed, 2).'</td>
							<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($mefin, 2).'</td>
							</tr>';
							$rep .= "<tr><td></td><td colspan='5'>Activo Fijo</td></tr>";
							/* $mosacc=0;
							 $mosacf=0; */
						}
						$meini+=$saldoini;
						$memed+=$saldocta;
						$mefin+=$salfin;
						
						$maini+=$saldoini;
						$mamed+=$saldocta;
						$mafin+=$salfin;
						$rep .= '
 						 <tr>
 						 <td colspan="2">&nbsp;</td>
 						 <td>'.$rms3->cta." ".utf8_decode($rms3->descta).'</td>
 						 <td align="right">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($saldoini,2).'</td>
 						 <td align="right">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($saldocta,2).'</td>
 						 <td align="right">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($salfin,2).'</td>
 						 </tr>';
					}
				}

				$s = $ctas->fetch_next($id2, $cond);
				$id = $gpos->id;
				$r = $gpos->fetch_next($id, $tipo_edo_financiero);
			}
		} else {
			// Si no se encuentr información relacionada hay que agarrar el siguiente registro para que no se cicle.
			$id = $gpos->id;
			$r = $gpos->fetch_next($id, $tipo_edo_financiero);
		}
		if($aa==1){
			$sactivoini+=$sdototini;
			$sactivoact+=$sdotot;
			$sactivofin+=$sdototini + $sdotot;
			$texto = "Activo";
		}
		if($bb==1){
			$spasivoini+=$sdototini;
			$spasivoact+=$sdotot;
			$spasivofin+=$sdototini + $sdotot;
			$texto = "Pasivo";
		}
		if($cc==1){
			$sccontableini+=$sdototini;
			$sccontableact+=$sdotot;
			$sccontablefin+=$sdototini + $sdotot;
			$texto = "Capital";
		}
		if($aa==1){
			$rep.='<tr>
			<td colspan="3" style="text-align: right;">Total Activo Fijo:</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($maini, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($mamed, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($mafin, 2).'</td>
			</tr>';
		}
		$rep .= '
		<tr>
			<td colspan="3"  style="text-align: right;">Total '.$texto.':</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdototini, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdotot, 2).'</td>
			<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdototini + $sdotot, 2).'</td>
		</tr>';

		$sumtot += $sdotot;
		$sumtotini += $sdototini;
		if($aa==1){
			$totalactivo = number_format($sdototini, 2);
		}
		if($bb==1){
			$totalpasivo = $sdototini + $sdotot;
		}
		if($cc==1){
			$totalcapital = $sdototini + $sdotot;
		}
	}
	$sumtotini=$sactivoini-($spasivoini+$sccontableini);
	$sumtot=$sactivoact-($spasivoact+$sccontableact);
	$sumtotfin=$sactivofin-($spasivofin+$sccontablefin);
	//Estado de resultados Inicio
	?>
			<?php
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
				dol_syslog("a. Grupo=".$gpos->grupo);
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
							$id2 = $ctas->s_rowid;
							$sdo_cta = 0;
							$sdo_cta_ini = 0;
							if ($s) {
								while ($s) {
									$ctas->fetch_saldos2($ctas->s_rowid, $anio, $mes);
									$sdo_cta = $sdo_cta + $ctas->saldo;
									$ctas->fetch_saldos_iniciales2($ctas->id, $anio, $mes);
									$sdo_cta_ini = $sdo_cta_ini + $ctas->saldo;
									$s = $ctas->fetch_next($id2, $cond);
									$id2 = $ctas->s_rowid;
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
							$mostrar='SI'; 
						}
						$sdotot += $sdo_cta;
		
						$r = $gpos->fetch_next($id, $edo_financiero, 1);
						$id = $gpos->id;
						
						//Este grupo abarca desde el codigo de agrupación ini hasta el codigo de agrupación fin.
						$id_row_min = $gpos->fk_codagr_ini;
						$id_row_max = $gpos->fk_codagr_fin;
						
					} while ($r);
				}	
	$rep .= '	<tr>
					<td colspan="3" >';
						if ($sdotot >= 0) 	{ $rep .="Utilidad del Ejercicio:"; }
						else 				{ $rep .="Pérdida del Ejercicio:"; }
						$utilidad=$sdo_tot_ini + $sdo_tot;
	$rep .= '		</td>
					<td style="text-align: right;"> </td>
					<td style="text-align: right;"> </td>';
	$rep .= '		<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2).'</td>
				</tr>';
	$rep.= '<tr >
			<td colspan="3" >
				Capital Contable + Utilidad del Ejercicio
			</td>
			<td style="text-align: right; "> </td>
			<td style="text-align: right; "> </td>';
			$totalcapital=$totalcapital+$utilidad;
	$rep.=	'<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($totalcapital, 2).'</td>
		</tr>';
			//Estado de resultados FIN
	$rep .= '
		<tr><td colspan="6"></td></tr>
		<tr>
			<td colspan="3">Pasivo + Capital</td>';
	$rep .= '<td></td>';
	$rep .= '<td></td>';
	//$rep .= '<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sumtotini, 2).'</td>';
	//$rep .= '<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sumtot, 2).'</td>';
	$rep .= '<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($totalpasivo+$totalcapital, 2).'</td>';
	//$rep .= '<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sumtotfin, 2).'</td>';
	$rep .= '</tr>';
	
$rep .= '	</table>
';

if(GETPOST('tipo')=='pdf'){
	//print $rep;
	require_once '../class/dompdf/dompdf_config.inc.php';
	$dompdf = new DOMPDF();
	$dompdf->load_html($rep);
	$dompdf->render();
	$dompdf->stream("balance_general.pdf",array('Attachment'=>0));
}else{
	print $rep;
}
?>