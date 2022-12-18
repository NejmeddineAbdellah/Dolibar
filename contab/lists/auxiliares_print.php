<?php
date_default_timezone_set("America/Mexico_City");
setlocale(LC_MONETARY, 'es_MX');

header("Content-type: application/ms-excel");
header("Content-disposition: attachment; filename=auxiliares.xls");

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
global $db,$conf,$langs;
// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$anio = GETPOST('a');
$mes = GETPOST('m');
$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);
$tds = GETPOST('t');

$table="<h1>".$conf->global->MAIN_INFO_SOCIETE_NOM." - Auxiliar de Cuentas</h1>";
$table.="<h1>Periodo contable: ".$per->anio." - ".$per->MesToStr($per->mes)."</h1>";
$table.='<table border="1" class="border" style="width: 100%">
		<tr class="liste_titre">
			<td style="width: 10%"><strong>Cuenta</strong></td>
			<td style="width: 60%"><strong>Descripcion</strong></td>
			<td style="width: 10%;text-align: right;">Saldo Inicial</td>
			<td style="width: 10%;text-align: right;"><strong>Debe</strong></td>
			<td style="width: 10%;text-align: right;"><strong>Haber</strong></td>
			<td style="width: 10%;text-align: right;">Saldo Actual</td>
		</tr>';
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
if($tds=='n'){
	$sql.=" AND d.cta=c.cuenta";
}
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
	$table.= "<tr>";
	$table.= "<td>".$rs->cta."</td>";
	$table.= "<td>".$rs->descta."</td>";
	$table.= "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($minicial,2)."</td>";
	$table.= "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->debe,2)."</td>";
	$table.= "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->haber,2)."</td>";
	$table.= "<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mact,2)."</td>";
	$table.="</tr>";
}
$table.= "<tr>";
$table.="<td></td>";
$table.="<td style='text-align: right;'>Total:</td>";
$table.="<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mini,2)."</td>";
$table.="<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mdebe,2)."</td>";
$table.="<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mhaber,2)."</td>";
$table.="<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mactua,2)."</td>";
$table.="</tr>";
$table.="</table>";

print $table;
