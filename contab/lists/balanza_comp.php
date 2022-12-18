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

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabsatctas.class.php';
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
$chk_todas = GETPOST('chk_todas');

$arrayofjs = array('../js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

llxHeader('','Balanza de Comprobacion','','','','',$arrayofjs,'',0,0);

$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);
?>
<h1 align="center"><?=$conf->global->MAIN_INFO_SOCIETE_NOM?></h1>
<h1 align="center">Balanza de Comprobacion</h1>
<h3>Periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h3>
<form>
	<input type="hidden" name="a" value=<?=$anio;?> />
	<input type="hidden" name="m" value=<?=$mes;?> />
	<table class="noborder" width="100%">
		<tr class="liste_titre">
			<td colspan="2">&nbsp;</td>
			<td colspan="2" style="text-align: right">
				<input type="checkbox" name="chk_todas" <?=($chk_todas ? ' checked="checked" ' : '');?> onchange="this.form.submit()">Mostrar todas las cuentas
			</td> 
			<td style="text-align: right">
				<a href="balanza_comp_print.php?tipo=excel&a=<?=$anio;?>&m=<?=$mes;?>&t=<?=$chk_todas?>" target="popup">
					Descargar Excel
				</a>
			</td>
			<td style="text-align: right">
				<a href="balanza_comp_print.php?tipo=pdf&a=<?=$anio;?>&m=<?=$mes;?>&t=<?=$chk_todas?>" target="popup">
					Descargar PDF
				</a>
			</td>
		</tr>
		<tr class="liste_titre">
			<td style="width: 60%" colspan="2">Cuenta</td>
			<td style="width: 10%; text-align:right">Saldo<br>Inicial</td>
			<td style="width: 10%;text-align:right">Debe</td>
			<td style="width: 10%;text-align:right">Haber</td>
			<td style="width: 10%; text-align:right">Saldo<br>Actual</td>
		</tr>

<?php 
		$debe_total = 0;
		$haber_total = 0;
		$saldo_ini_tot = 0;

		$pol = new Contabpolizas($db);
		
		$ctas = new Contabcatctas($db);
		
		$sat = new Contabsatctas($db);
		//$ok = $sat->fetch_next(0);
		$sql="SELECT @a:=cta as cta,descta,rowid as id,
 				(SELECT count(*) FROM ".MAIN_DB_PREFIX."contab_cat_ctas 
 					WHERE entity=".$conf->entity." AND cta LIKE CONCAT (@a,'.%')) as cant
				FROM ".MAIN_DB_PREFIX."contab_cat_ctas 
				WHERE entity=".$conf->entity." ORDER BY cta";
		//print $sql."<br>";
		$rqs=$db->query($sql);
		//$ctas = new Contabcatctas($db);
		//$ok = $ctas->fetch_next_cuenta(0);
		while ($ctas2=$db->fetch_object($rqs)) {
		//while ($ok == 1) {
			$pol2 = new Contabpolizas($db);
			$pol2->getSumDebeHaber2($ctas2->cta, $anio, $mes);
			$debe2 = $pol2->debe_total;
			$haber2 = $pol2->haber_total;
			if($debe2!=0 || $haber2!=0){
				$ctas2->cant=0;
			}
			$c = substr($sat->codagr,0,3);
			
			if (1) {
			//if (!($c == "100" || $c == "200" || $c == "300")) {
				//print $c." :: ".$sat->codagr."<br>";
				if (1) {
				//if ($ctas->fetch_by_CodAgr2($sat->codagr)) {
					if($ctas2->cant==0){
					$pol->getSumDebeHaber2($ctas2->cta, $anio, $mes);
					$debe = $pol->debe_total;
					$haber = $pol->haber_total;
					
					$debe_total += $pol->debe_total;
					$haber_total += $pol->haber_total;
					$ctas->fetch_saldos_iniciales4($ctas2->cta, $anio, $mes);
					$sdo_ini = $ctas->saldo;
					$sdo_ini_tot += $sdo_ini;
					//var_dump($ctas->descta." ".$sdo_ini);
					if ($sdo_ini != 0 || $debe != 0 || $haber != 0 || $chk_todas) {
?>	
						<tr <?php print $bc[$var]; $var = !$var; ?>>
							<td colspan="2"><?php print $ctas2->cta; print " ".$ctas2->descta;?></td>
							<?php 
							if($sdo_ini!=0){
								if($sdo_ini<0){
									$aux1=" color:red;";
								}else{
									$aux1="";
								}
							?>
								<td style="text-align: right;"><a href="mayor_saldoini.php?id=<?=$ctas2->id;?>&a=<?=$anio;?>&m=<?=$mes;?>" style="<?=$aux1?>"><img src='<?="../images/lupa.png";?>' height='11px' width='11px'><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($sdo_ini, 2);?></a></td>
							<?php 
							}else{
							?>
								<td style="text-align: right;"><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($sdo_ini, 2);?></td>
							<?php 
							}
							?>
							<td style="text-align: right;"><a href="mayor.php?id=<?=$ctas2->id;?>&a=<?=$anio;?>&m=<?=$mes;?>" ><img src='<?="../images/lupa.png";?>' height='11px' width='11px'> <?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($debe, 2); ?></a></td>
							<td style="text-align: right;"><a href="mayor.php?id=<?=$ctas2->id;?>&a=<?=$anio;?>&m=<?=$mes;?>" ><img src='<?="../images/lupa.png";?>' height='11px' width='11px'> <?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($haber, 2); ?></a></td>
							<?php 
							$daldofin=$sdo_ini + $debe - $haber;
							if(($daldofin)<0){
								$aux2=" color:red;";
							}else{
								$aux2="";
							}
							?>
							<td style="text-align: right; <?=$aux2?>"><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($daldofin, 2);?></td>
						</tr>
<?php	 
					}
					}else{
						$pol->getSumDebeHaber3($ctas2->cta, $anio, $mes);
						$debe = $pol->debe_total;
						$haber = $pol->haber_total;
							
						$ctas->fetch_saldos_iniciales3($ctas2->cta, $anio, $mes);
						$sdo_ini = $ctas->saldo;
						//var_dump($ctas->descta." ".$sdo_ini);
						if ($sdo_ini != 0 || $debe != 0 || $haber != 0 || $chk_todas) {
							?>
							<tr <?php print $bc[$var]; $var = !$var; ?>>
								<td colspan="2"><strong><?php print $ctas2->cta; print " ".$ctas2->descta;?></strong></td>
								<?php 
								if($sdo_ini!=0){
									if($sdo_ini<0){
										$aux1=" color:red;";
									}else{
										$aux1="";
									}
								?>
									<td style="text-align: right;"><a href="mayor_saldoini.php?id=<?=$ctas2->id;?>&a=<?=$anio;?>&m=<?=$mes;?>" style="<?=$aux1?>"><img src='<?="../images/lupa.png";?>' height='11px' width='11px'><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($sdo_ini, 2);?></a></td>
								<?php 
								}else{
								?>
									<td style="text-align: right;"><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($sdo_ini, 2);?></td>
								<?php 
								}
								?>
								<td style="text-align: right;"><a href="mayor.php?id=<?=$ctas2->id;?>&a=<?=$anio;?>&m=<?=$mes;?>" ><img src='<?="../images/lupa.png";?>' height='11px' width='11px'> <?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($debe, 2); ?></a></td>
								<td style="text-align: right;"><a href="mayor.php?id=<?=$ctas2->id;?>&a=<?=$anio;?>&m=<?=$mes;?>" ><img src='<?="../images/lupa.png";?>' height='11px' width='11px'> <?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($haber, 2); ?></a></td>
								<?php 
								$daldofin=$sdo_ini + $debe - $haber;
								if(($daldofin)<0){
									$aux2=" color:red;";
								}else{
									$aux2="";
								}
								?>
								<td style="text-align: right; <?=$aux2?>"><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($daldofin, 2);?></td>
							</tr>
						<?php	 
						}	
					}
				}
			}
			//$ok = $sat->fetch_next($sat->id);
		}
?>
		<tr <?php print $bc[$var]; $var = !$var; ?>>
			<td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
			<td style="text-align: right;"><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($sdo_ini_tot, 2);?></td>
			<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($debe_total, 2);?></td>
			<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($haber_total, 2);?></td>
			<td style="text-align: right;"><?php print $langs->getCurrencySymbol($conf->currency).' '.number_format($sdo_ini_tot + $debe_total - $haber_total, 2);?></td>
		</tr>
	</table>
</form>
<?php 

llxFooter();

$db->close();
