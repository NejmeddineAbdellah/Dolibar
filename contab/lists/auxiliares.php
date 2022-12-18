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
$todos='NO';
$tds='n';
$action=GETPOST('action');
if($action=='mostrar'){
	if(GETPOST('chk_todas')){
		$todos='SI';
		$tds='y';
	}else{
		$todos='NO';
		$tds='n';
	}
}
if($todos=='SI'){
	$ck='checked';
}else{
	$ck='';
}
?>
<h1 align="center"><?=$conf->global->MAIN_INFO_SOCIETE_NOM?></h1>
<h1 align="center">Auxiliar de Cuentas</h1>
<h3>Periodo contable: <?=$per->anio." - ".$per->MesToStr($per->mes);?></h3>
<table class="noborder" style="width: 100%">
		<tr class="liste_titre">
			<td colspan="5" style="text-align: right">
				<form action="?a=<?=$anio?>&m=<?=$mes?>&action=mostrar" method="POST">
					<input type="checkbox" name="chk_todas" <?=$ck?> onchange="this.form.submit()">Mostrar todas las cuentas
				</form>
			</td> 
			<td colspan="1" style="text-align: right">
				<a href="auxiliares_print.php?a=<?=$anio;?>&m=<?=$mes;?>&t=<?=$tds;?>" target="popup">
					Descargar
				</a>
			</td>
		</tr>
		<tr class="liste_titre">
			<td style="width: 10%">Cuenta</td>
			<td style="width: 50%">Descripcion</td>
			<td style="width: 10%;text-align: right;">Saldo Inicial</td>
			<td style="width: 10%;text-align: right;">Debe</td>
			<td style="width: 10%;text-align: right;">Haber</td>
			<td style="width: 10%;text-align: right;">Saldo Actual</td>
		</tr>
<?php 
$mm = sprintf("%02d", $mes);
$sql="SELECT d.rowid,d.cta,d.descta,ifnull(c.debe,0) as debe,ifnull(c.haber,0) as haber,ifnull(e.debeini,0) as debeini,ifnull(e.haberini,0) as haberini
	FROM ".MAIN_DB_PREFIX."contab_cat_ctas d LEFT JOIN
	(SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber
	FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b
	WHERE anio=".$anio." AND mes=".$mes." AND entity=".$conf->entity." AND a.rowid=b.fk_poliza GROUP BY b.cuenta) c ON d.cta=c.cuenta
 	LEFT JOIN (SELECT f.cuenta,sum(f.debe) as debeini,sum(f.haber) as haberini 
               FROM ".MAIN_DB_PREFIX."contab_polizas g, ".MAIN_DB_PREFIX."contab_polizasdet f 
               WHERE CONCAT(anio,LPAD(mes,2,'0'))<CONCAT('$anio','$mm') AND entity=".$conf->entity." AND g.rowid=f.fk_poliza 
     GROUP BY f.cuenta ) e ON d.cta=e.cuenta 	
	WHERE entity=".$conf->entity;
if($todos=='NO'){
	$sql.=" AND d.cta=c.cuenta";
}
//print $sql."";
$rq=$db->query($sql);
$mdebe=0;
$mhaber=0;
$mini=0;
$mactua=0;
while($rs=$db->fetch_object($rq)){
	$mdebe+=$rs->debe;
	$mhaber+=$rs->haber;
	$minicial=0;
	$minicial=$rs->debeini-$rs->haberini;
	$mini+=$minicial;
	$mact=0;
	$mact=$minicial+$rs->debe-$rs->haber;
	$mactua+=$mact;
	print "<tr>";
	print "<td>".$rs->cta."</td>";
	print "<td>".$rs->descta."</td>";
	if($minicial<0){
		$aux1=" color:red;";
	}else{
		$aux1="";
	}
	if($minicial!=0){
		print "<td style='text-align: right;'><a href='aux_polizas_salini.php?cta=".$rs->rowid."&a=".$anio."&m=".$mes."' style='".$aux1."'><img src='../images/lupa.png' height='11px' width='11px'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($minicial,2)."</a></td>";
	}else{
		print "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($minicial,2)."</td>";
	}
	print "<td style='text-align: right'><a href='aux_polizas.php?cta=".$rs->rowid."&a=".$anio."&m=".$mes."' ><img src='../images/lupa.png' height='11px' width='11px'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->debe,2)."</a></td>";
	print "<td style='text-align: right'><a href='aux_polizas.php?cta=".$rs->rowid."&a=".$anio."&m=".$mes."' ><img src='../images/lupa.png' height='11px' width='11px'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->haber,2)."</a></td>";
	if($mact<0){
		$aux2=" color:red;";
	}else{
		$aux2="";
	}
	print "<td style='text-align: right;".$aux2."'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mact,2)."</td>";
	print "</tr>";
}
print "<tr>";
print "<td></td>";
print "<td style='text-align: right;'>Total:</td>";
print "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mini,2)."</td>";
print "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mdebe,2)."</td>";
print "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mhaber,2)."</td>";
print "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mactua,2)."</td>";
print "</tr>";
?>
</table>









