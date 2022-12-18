<?php
if (file_exists(DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/admin/Configuration.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabsatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabgrupos.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabgrupos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabgrupos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

require_once '../functions/functions.php';

$config = new Configuration($db);
if(GETPOST('fechini') && GETPOST('fechfin')){
	$fecini=GETPOST('fechini');
	$fecfin=GETPOST('fechfin');
}else{
	$fecini=date('Y-m-d');
	$fecfin=date('Y-m-d');
}
print "<form method='POST' action='exportar_cvs.php' target='_blank'>";
print "Polizas entre ";
print "Fecha Inicio: <input type='date' name='fechini' id='fechini' value='".$fecini."'>";
print " y Fecha Fin: <input type='date' name='fechfin' id='fechfin' value='".$fecfin."'>";
print " <input type='submit' value='Exportar'>";
print "</form>";

//print $fecini." :: ".$fecfin;

