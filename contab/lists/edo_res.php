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
$mostt=2;
$chk_todas='on';
$action=GETPOST('action');
if($action=='mostrar'){
	if(GETPOST('chk_todas')){
		$chk_todas = GETPOST('chk_todas');
		$mostt=2;
	}else{
		$chk_todas = GETPOST('chk_todas');
		$mostt=1;
	}
}

$arrayofjs = array('/contab/js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

llxHeader('','Estado de Resultados','','','','',$arrayofjs,'',0,0);

$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);

?>
<h1 align="center"><?=$conf->global->MAIN_INFO_SOCIETE_NOM;?></h1>
<h1 align="center">Estado de Resultados</h1>
<h3>Periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h3>

	<table class="noborder" style="width: 100%">
		<tr class="liste_titre">
			<td colspan="4" style="text-align: right">
			<form method="post" action="edo_res.php?a=<?=$anio?>&m=<?=$mes?>&action=mostrar">
				<input type="checkbox" name="chk_todas" <?=($chk_todas ? ' checked="checked" ' : '');?> onchange="this.form.submit()">Mostrar todas las cuentas
			</form>
			</td> 
<form >
			<td colspan="1" style="text-align: right">
				<a href="edo_res_print.php?tipo=excel&a=<?=$anio;?>&m=<?=$mes;?>" target="popup">
					Descargar Excel
				</a>
			</td>
			<td colspan="1" style="text-align: right">
				<a href="edo_res_print.php?tipo=pdf&a=<?=$anio;?>&m=<?=$mes;?>" target="popup">
					Descargar PDF
				</a>
			</td>
		</tr>
		<tr class="liste_titre">
			<td colspan="3">Concepto</td>
			<td style="width: 10%; text-align:right">Saldo<br>Inicial</td>
			<td style="width: 10%; text-align:right">Movientos<br>del Mes</td>
			<td style="width: 10%; text-align:right">Saldo<br>Actual</td>
		</tr>
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
				/* if ($codagr >= 400 && $codagr <= 403) { $col = 3; }
				if ($codagr == 501) { $col = 4; }
				if ($codagr >= 502 && $codagr <= 704) { $col = 2; } */
				$ctas = new Contabcatctas($db);
				
				if ($gpos->grupo !== "Gastos") {
					$id2 = $gpo_id_min - 1;
					$cond = " nivel = 2 AND (s.rowid between ".$id_row_min." AND ".$id_row_max.") ";
					
					$s = $ctas->fetch_next2($id2, $cond);
					$id2 = $ctas->s_rowid;
					
					$sdo_cta = 0;
					$sdo_cta_ini = 0;
					
					if ($s) {
						while ($s) {
							//$ctas->fetch_saldos($ctas->s_rowid, $anio, $mes);
							$ctas->fetch_saldos2($ctas->s_rowid, $anio, $mes);
							$sdo_cta = $sdo_cta + $ctas->saldo;
							
							//$ctas->fetch_saldos_iniciales($ctas->id, $anio, $mes);
							$ctas->fetch_saldos_iniciales2($ctas->id, $anio, $mes);
							$sdo_cta_ini = $sdo_cta_ini + $ctas->saldo;
							
							$s = $ctas->fetch_next2($id2, $cond);
							$id2 = $ctas->s_rowid;
							
							dol_syslog("Se fue a ver los saldos de cuentas dependientes cta=".$ctas->id." saldo=$sdo_cta, sdoini=$sdo_cta_ini");
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
					if($mostt==1){
						if($sdo_cta_ini==0 && $sdo_cta==0){
							$mostrar='NO';
						}else{
							$mostrar='SI';
						}
					}
					if($mostrar=='SI'){
?>
					<tr <?php print $bc[$var]; ?>>
						<td colspan="3">
							<?php print $gpos->codagr_ini.' '.$gpos->grupo;?>
						</td>
						<?php 
						if($sdo_cta_ini<0){
							$aux1=" color:red;";
						}else{
							$aux1="";
						}
						?>
						<td style="text-align: right; <?=$aux1?>">
							<?php 
							if($sdo_cta==0){
							?>
							<?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta_ini,2);?>
							<?php 
							}else{?>
							<a href="mayor_saldoini.php?id=<?=$gpos->fk_codagr_ini?>&a=<?=$anio?>&m=<?=$mes?>" style="<?=$aux1?>"><img src='<?="../images/lupa.png";?>' height='11px' width='11px'><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta_ini,2);?></a>
							<?php }?>
						</td>
						<?php 
						if($sdo_cta<0){
							$aux2=" color:red;";
						}else{
							$aux2="";
						}
						?>
						<td style="text-align: right; <?=$aux2?>">
							<?php 
							if($sdo_cta==0){
							?>
							<?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta,2);?>
							<?php 
							}else{?>
								<a href="mayor2.php?id=<?=$id_row_min?>&idfin=<?=$id_row_max?>&a=<?=$anio?>&m=<?=$mes?>" style="<?=$aux2?>"><img src='<?="../images/lupa.png";?>' height='11px' width='11px'><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta,2);?></a>
							<?php }?>
						</td>
						<?php 
						if(($sdo_cta_ini + $sdo_cta)<0){
							$aux3=" color:red;";
						}else{
							$aux3="";
						}
						?>
						<td style="text-align: right; <?=$aux3?>">
							<?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_cta_ini + $sdo_cta,2);?>
						</td>
					</tr>
<?php
					}
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
					?><tr <?php $var = !$var; print $bc[$var];?>>
					<td colspan="3" style="text-align: right;"><strong>Total Gastos de Operacion:</strong></td>
						<?php 
						if($sdo_gpo_ini<0){
							$aux4=" color:red;";
						}else{
							$aux4="";
						}
						?>
						<td style="text-align: right; <?=$aux4?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2); ?></td>
						<?php 
						if($sdo_gpo<0){
							$aux5=" color:red;";
						}else{
							$aux5="";
						}
						?>
						<td style="text-align: right; <?=$aux5?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2); ?></td>
						<?php 
						if(($sdo_gpo_ini + $sdo_gpo)<0){
							$aux6=" color:red;";
						}else{
							$aux6="";
						}
						?>
						<td style="text-align: right; <?=$aux6?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2);  ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Utilidad antes de Otros Gastos y Otros Productos Financieros:</strong></td>
						<?php 
						if($sdo_tot_ini<0){
							$aux7=" color:red;";
						}else{
							$aux7="";
						}
						?>
						<td style="text-align: right; <?=$aux7?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2); ?></td>
						<?php 
						if($sdo_tot<0){
							$aux8=" color:red;";
						}else{
							$aux8="";
						}
						?>
						<td style="text-align: right; <?=$aux8?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2); ?></td>
						<?php 
						if(($sdo_tot_ini + $sdo_tot)<0){
							$aux9=" color:red;";
						}else{
							$aux9="";
						}
						?>
						<td style="text-align: right; <?=$aux9?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2); ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>></tr>
					<?php
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
					?><tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Total Gastos de Operacion:</strong></td>
						<?php 
						if($sdo_gpo_ini<0){
							$aux10=" color:red;";
						}else{
							$aux10="";
						}
						?>
						<td style="text-align: right; <?=$aux10?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2); ?></td>
						<?php 
						if($sdo_gpo<0){
							$aux11=" color:red;";
						}else{
							$aux11="";
						}
						?>
						<td style="text-align: right; <?=$aux11?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2); ?></td>
						<?php 
						if(($sdo_gpo_ini + $sdo_gpo)<0){
							$aux12=" color:red;";
						}else{
							$aux12="";
						}
						?>
						<td style="text-align: right; <?=$aux12?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2);  ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Utilidad o Perdida de Operacion:</strong></td>
						<?php 
						if($sdo_tot_ini<0){
							$aux13=" color:red;";
						}else{
							$aux13="";
						}
						?>
						<td style="text-align: right; <?=$aux13?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2); ?></td>
						<?php 
						if($sdo_tot<0){
							$aux14=" color:red;";
						}else{
							$aux14="";
						}
						?>
						<td style="text-align: right; <?=$aux14?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2); ?></td>
						<?php 
						if(($sdo_tot_ini + $sdo_tot)<0){
							$aux15=" color:red;";
						}else{
							$aux15="";
						}
						?>
						<td style="text-align: right; <?=$aux15?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2); ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>></tr>
					<?php 
						$sdo_gpo = 0;
						$sdo_gpo_ini = 0;
 					} else if ($codigo_ant >= '604' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					} else if ($codigo_ant >= '603' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					} else if ($codigo_ant >= '602' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					} else if ($codigo_ant >= '601' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {

				} else if ($codigo_ant >= '505' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					?><tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Total Otros Costos:</strong></td>
						<?php 
						if($sdo_gpo_ini<0){
							$aux16=" color:red;";
						}else{
							$aux16="";
						}
						?>
						<td style="text-align: right; <?=$aux16?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2); ?></td>
						<?php 
						if($sdo_gpo<0){
							$aux17=" color:red;";
						}else{
							$aux17="";
						}
						?>
						<td style="text-align: right; <?=$aux17?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2); ?></td>
						<?php 
						if($sdo_gpo_ini + $sdo_gpo<0){
							$aux18=" color:red;";
						}else{
							$aux18="";
						}
						?>
						<td style="text-align: right; <?=$aux18?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2); ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Utilidad Antes de Operación:</strong></td>
						<?php 
						if($sdo_tot_ini<0){
							$aux19=" color:red;";
						}else{
							$aux19="";
						}
						?>
						<td style="text-align: right; <?=$aux19?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2); ?></td>
						<?php 
						if($sdo_tot<0){
							$aux20=" color:red;";
						}else{
							$aux20="";
						}
						?>
						<td style="text-align: right; <?=$aux20?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2); ?></td>
						<?php 
						if($sdo_tot_ini + $sdo_tot<0){
							$aux21=" color:red;";
						}else{
							$aux21="";
						}
						?>
						<td style="text-align: right; <?=$aux21?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2); ?></td>
					</tr>
					<tr></tr>
					<?php 
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
				} else if ($codigo_ant >= '504' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '503' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					?><tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Compras Netas:</strong></td>
						<?php 
						if($sdo_grupos_ini<0){
							$aux22=" color:red;";
						}else{
							$aux22="";
						}
						?>
						<td style="text-align: right; <?=$aux22?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos_ini, 2); ?></td>
						<?php 
						if($sdo_grupos<0){
							$aux23=" color:red;";
						}else{
							$aux23="";
						}
						?>
						<td style="text-align: right; <?=$aux23?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos, 2); ?></td>
						<?php 
						if(($sdo_grupos_ini + $sdo_grupos)<0){
							$aux24=" color:red;";
						}else{
							$aux24="";
						}
						?>
						<td style="text-align: right; <?=$aux24?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_grupos_ini + $sdo_grupos, 2); ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Costo de Venta:</strong></td>
						<?php 
						if($sdo_gpo_ini<0){
							$aux25=" color:red;";
						}else{
							$aux25="";
						}
						?>
						<td style="text-align: right; <?=$aux25?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2); ?></td>
						<?php 
						if($sdo_gpo<0){
							$aux26=" color:red;";
						}else{
							$aux26="";
						}
						?>
						<td style="text-align: right; <?=$aux26?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2); ?></td>
						<?php 
						if(($sdo_gpo_ini + $sdo_gpo)<0){
							$aux27=" color:red;";
						}else{
							$aux27="";
						}
						?>
						<td style="text-align: right; <?=$aux27?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2); ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Utilidad Bruta:</strong></td>
						<?php 
						if($sdo_tot_ini<0){
							$aux28=" color:red;";
						}else{
							$aux28="";
						}
						?>
						<td style="text-align: right; <?=$aux28?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2); ?></td>
						<?php 
						if($sdo_tot<0){
							$aux29=" color:red;";
						}else{
							$aux29="";
						}
						?>
						<td style="text-align: right; <?=$aux29?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2); ?></td>
						<?php 
						if(($sdo_tot_ini + $sdo_tot)<0){
							$aux30=" color:red;";
						}else{
							$aux30="";
						}
						?>
						<td style="text-align: right; <?=$aux30?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2); ?></td>
					</tr>
					<tr></tr>
					<?php 
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				} else if ($codigo_ant >= '502' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
				} else if ($codigo_ant >= '501' && substr($codigo_ant,0 , 3) != substr($gpos->codagr_ini, 0, 3)) {
					?><tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="6"></td>
					</tr><?php 
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				} else if ($codigo_ant >= '400' && substr($codigo_ant,0 , 1) != substr($gpos->codagr_ini, 0, 1)) {
					?><tr <?php $var = !$var; print $bc[$var];?>>
						<td colspan="3" style="text-align: right;"><strong>Ingreso Neto:</strong></td>
						<?php 
						if($sdo_gpo_ini<0){
							$aux31=" color:red;";
						}else{
							$aux31="";
						}
						?>
						<td style="text-align: right; <?=$aux31?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2); ?></td>
						<?php 
						if($sdo_gpo<0){
							$aux32=" color:red;";
						}else{
							$aux32="";
						}
						?>
						<td style="text-align: right; <?=$aux32?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2); ?></td>
						<?php 
						if(($sdo_gpo_ini + $sdo_gpo)<0){
							$aux33=" color:red;";
						}else{
							$aux33="";
						}
						?>
						<td style="text-align: right; <?=$aux33?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2); ?></td>
					</tr>
					<tr <?php $var = !$var; print $bc[$var];?>></tr>
					<?php 
					$sdo_gpo = 0;
					$sdo_gpo_ini = 0;
					$sdo_grupos = 0;
					$sdo_grupos_ini = 0;
				}
				}
			} while ($r);
		}
		
?>
		<tr <?php $var = !$var; print $bc[$var];?>>
			<td colspan="3" style="text-align: right;"><strong>Total Otros gastos y Productos Financieros:</strong></td>
			<?php 
			if($sdo_gpo_ini<0){
				$aux34=" color:red;";
			}else{
				$aux34="";
			}
			?>
			<td style="text-align: right; <?=$aux34?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini, 2); ?></td>
			<?php 
			if($sdo_gpo<0){
				$aux35=" color:red;";
			}else{
				$aux35="";
			}
			?>
			<td style="text-align: right; <?=$aux35?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo, 2);  ?></td>
			<?php 
			if(($sdo_gpo_ini + $sdo_gpo)<0){
				$aux36=" color:red;";
			}else{
				$aux36="";
			}
			?>
			<td style="text-align: right; <?=$aux36?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_gpo_ini + $sdo_gpo, 2); $sdo_gpo_ini = 0; $sdo_gpo = 0;?></td>
		</tr>
		<tr <?php $var = !$var; print $bc[$var];?>></tr>
		<tr <?php $var = !$var; print $bc[$var];?>>
			<td colspan="3" style="text-align: right;">
				<strong>
<?php 
				if ($sdotot > 0) 	{ print "Utilidad del Ejercicio:"; }
				else 				{ print "Pérdida del Ejercicio:"; }
?>
				</strong>
			</td>
			<?php 
			if($sdo_tot_ini<0){
				$aux37=" color:red;";
			}else{
				$aux37="";
			}
			?>
			<td style="text-align: right; <?=$aux37?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini, 2); ?></td>
			<?php 
			if($sdo_tot<0){
				$aux38=" color:red;";
			}else{
				$aux38="";
			}
			?>
			<td style="text-align: right; <?=$aux38?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot, 2); ?></td>
			<?php 
			if(($sdo_tot_ini + $sdo_tot)<0){
				$aux39=" color:red;";
			}else{
				$aux39="";
			}
			?>
			<td style="text-align: right; <?=$aux39?>"><?php print $langs->getCurrencySymbol($conf->currency)." ".number_format($sdo_tot_ini + $sdo_tot, 2); ?></td>
		</tr>
	</table>
</form>
<?php 

llxFooter();

$db->close();
