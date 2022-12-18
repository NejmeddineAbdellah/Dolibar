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
if(GETPOST('tipo')=='excel'){
	header("Content-type: application/ms-excel");
	header("Content-disposition: attachment; filename=cuentas_mayor.xls");
}
if (! $user->rights->contab->cont) {
	accessforbidden();
}

// Get parameters
$anio = GETPOST('a');
$mes = GETPOST('m');

$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);

$pol = new Contabpolizas($db);

$pol->anio = $per->anio;
$pol->mes = $per->mes;

$pol->fetch_next(0, 1);

$consecutivo = $pol->id;

//$debe_total = 0;
//$haber_total = 0;

$sat = new Contabsatctas($db);
$idmayor = GETPOST("id");
$idmayorfin = GETPOST("idfin");
$ctas = new Contabcatctas($db);
if(GETPOST('tipo')=='excel'){
$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Cuentas de Mayor<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>
 
	<table style="width:98%;text-align: center;" border="1">
		<tr>
			<td style="width: 10%;"><strong>Cuenta</strong></td>
			<td style="width: 40%;"><strong>Descripci&oacute;n</strong></td>
			<td style="width: 10%;text-align: right;">Saldo Inicial</td>
			<td style="width: 10%;text-align: right;">Debe</td>
			<td style="width: 10%;text-align: right;">Haber</td>
			<td style="width: 10%;text-align: right;">Saldo</td>
		</tr>';
}else{
	$rep = '
<h3>'.$conf->global->MAIN_INFO_SOCIETE_NOM.' - Cuentas de Mayor<br>
Periodo contable: '.$per->anio.' - '.$per->MesToStr($per->mes).'</h3>
	
	<table style="width:98%;text-align: center;border-collapse: collapse;font-size:10px;" border="1">
		<tr>
			<td style="width: 10%;"><strong>Cuenta</strong></td>
			<td style="width: 40%;"><strong>Descripci&oacute;n</strong></td>
			<td style="width: 10%;text-align: right;">Saldo Inicial</td>
			<td style="width: 10%;text-align: right;">Debe</td>
			<td style="width: 10%;text-align: right;">Haber</td>
			<td style="width: 10%;text-align: right;">Saldo</td>
		</tr>';	
}	
$sql="SELECT * FROM ".MAIN_DB_PREFIX."contab_cat_ctas WHERE rowid BETWEEN '".$idmayor."' AND '".$idmayorfin."'";
$req=$db->query($sql);
while($res1=$db->fetch_object($req)){
	$idmayor=$res1->rowid;
		if ($idmayor > 0) {
			$ok = $ctas->fetch($idmayor);
			$view_cta = $ctas->cta;
		} else {
			$ok = $ctas->fetch_next_cuenta();
		}
		while ($ok == 1) {
			$c = substr($ctas->cta,0,3);
			
			$sat->fetch($ctas->fk_sat_cta);
			if (!($sat->codagr == "100" || $sat->codagr == "200" || $sat->codagr == "300")) {
				$pol->getSumDebeHaber2($ctas->cta, $anio, $mes);
				
				$debe_total = $pol->debe_total;
				$haber_total = $pol->haber_total;
				
				$var = !$var;
				if ($pol->debe_total > 0 || $pol->haber_total > 0) {
					if($mes==13){
						$mes2=12;
						$sql2="SELECT d.rowid,d.cta,d.descta,ifnull( sum( c.debe ) , 0 ) AS debe, ifnull( sum( c.haber ) , 0 ) AS haber
						FROM ".MAIN_DB_PREFIX."contab_cat_ctas d
					 	LEFT JOIN (SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber
						FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b
											WHERE CONCAT(anio,LPAD(mes,2,'0'))<=CONCAT('$anio',LPAD('$mes2',2,'0'))
											AND perajuste=1 AND entity=".$conf->entity." AND a.rowid=b.fk_poliza GROUP BY b.cuenta) c ON d.cta=c.cuenta
						WHERE entity=".$conf->entity." ";
						$sql2.=" AND d.cta ='".$ctas->cta."' ";
					}else{
						$sql2="SELECT d.rowid,d.cta,d.descta,ifnull( sum( c.debe ) , 0 ) AS debe, ifnull( sum( c.haber ) , 0 ) AS haber
						FROM ".MAIN_DB_PREFIX."contab_cat_ctas d
					 	LEFT JOIN (SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber
						FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b
											WHERE CONCAT(anio,LPAD(mes,2,'0'))<CONCAT('$anio',LPAD('$mes',2,'0'))
											AND perajuste=0 AND entity=".$conf->entity." AND a.rowid=b.fk_poliza GROUP BY b.cuenta) c ON d.cta=c.cuenta
						WHERE entity=".$conf->entity." ";
						$sql2.=" AND d.cta ='".$ctas->cta."' ";
					}
					//print $sql2."<br><br>";
					$bres=$db->query($sql2);
					$brs=$db->fetch_object($bres);
					$sact=0;
					$saldini=0;
					$satc= new Contabsatctas($db);
					$satc->fetch_by_CodAgr($ctas->cta);
					if($satc->natur=='A'){
						$sact=$pol->haber_total-$pol->debe_total;
						$saldini=$brs->haber-$brs->debe;
						$sact=$sact+$saldini;
					}else{
						$sact=$pol->debe_total-$pol->haber_total;
						$saldini=$brs->debe-$brs->haber;
						$sact=$sact+$saldini;
					}
					$rep .= '
					<tr>
						<td><strong>'.$ctas->cta.'</strong></td>
						<td><strong>'.utf8_decode($ctas->descta).'</strong></td>
						<td style="width: 10%;text-align: right;"><strong>'.$langs->getCurrencySymbol($conf->currency)." ".number_format($saldini,2).'</strong></td>
						<td style="width: 10%;text-align: right;"><strong>'.$langs->getCurrencySymbol($conf->currency)." ".number_format($pol->debe_total,2).'</strong></td>
						<td style="width: 10%;text-align: right;"><strong>'.$langs->getCurrencySymbol($conf->currency)." ".number_format($pol->haber_total,2).'</strong></td>
						<td style="width: 10%;text-align: right;"><strong>'.$langs->getCurrencySymbol($conf->currency)." ".number_format($sact,2).'</strong></td>
					</tr>
					<tr>
						<td colspan="6"  style="text-align: center;">
							<table style="border-collapse: collapse;width:100%;text-align: center;" border="1"> ';

								$arr = $pol->fetch_by_cuenta3($ctas->cta, $anio, $mes);
								$ad = array();
								$ah = array();
								if (is_array($arr)) {
									$rep .= '
									<tr>
										<td colspan="2" style="text-align: tight"; width: 50%">Debe</td>
										<td colspan="2" style="text-align: right"; width: 50%">Haber</td>
									</tr>';
									
									foreach ($arr as $i => $a) {
										if ($a["debe"] > 0) {
											$ad[] = $a;
										}
										if ($a["haber"] > 0) {
											$ah[] = $a;
										}
									}
									
									$len = (sizeof($ad) > sizeof($ah)) ? sizeof($ad) : sizeof($ah);
									
									for ($i = 0; $i < $len; $i ++) {
										$rep .= '<tr>';
										if ($i < sizeof($ad)) {
											$pol2 = new Contabpolizas($db);
											$pol2->fetch($ad[$i]["pid"],$anio);
											$as="Poliza ";
											$as.=$pol2->Get_folio_poliza();
											$rep .= '
											<td style="width: 25%">'.$as.' '.$pol2->cons.'</td>
											<td style="width: 25%; text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($ad[$i]["debe"],2).'</td>';
										} else {
											$rep .= '
											<td style="width: 25%">&nbsp;</td>
											<td style="width: 25%">&nbsp;</td>';
										}
										if ($i < sizeof($ah)) {
											$pol2 = new Contabpolizas($db);
											$pol2->fetch($ah[$i]["pid"],$anio);
											$as="Poliza ";
											$as.=$pol2->Get_folio_poliza();
											$rep .= '
											<td style="width: 25%">'.$as.' '.$pol2->cons.'</td>
											<td style="width: 25%; text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($ah[$i]["haber"],2).'</td>';
										} else {
											$rep .= '
											<td style="width: 25%">&nbsp;</td>
											<td style="width: 25%">&nbsp;</td>';
										}
										$rep .= '</tr>';
									}
									$rep .= '<tr>
									<td><strong>Total:</strong></td><td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($debe_total,2).'</td>
									<td><strong>Total:</strong></td><td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency)." ".number_format($haber_total,2).'</td></tr>';
								}
							$rep .= '
							</table>
						</td>
					</tr>
					<tr><td colspan="6">&nbsp;</td></tr>';
				}
			}
		$ok = $ctas->fetch_next_cuenta(1);
			dol_syslog("Valores encontrados=".$idmayor." - ".$view_cta." - ".$ctas->cta." - ".substr($ctas->cta, 1, strlen($view_cta)));
			if ($idmayor > 0) {
				if ($view_cta != substr($ctas->cta, 0, strlen($view_cta))) {
					$ok = 0;
				}
			}
		}
}
$rep .= '
	</table>';

if(GETPOST('tipo')=='pdf'){
	//print $rep;
	require_once '../class/dompdf/dompdf_config.inc.php';
	$dompdf = new DOMPDF();
	$dompdf->load_html($rep);
	$dompdf->render();
	$dompdf->stream("cuentas_mayor.pdf",array('Attachment'=>0));
}else{
	print $rep;
}
?>