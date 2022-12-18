<?php
global $langs;

/* $res = 0;
if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only
if (!$res)
	die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
dol_include_once('/module/class/skeleton_class.class.php'); */

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
$langs->load("contab@contab");
$langs->load('bills');
// Get parameters
$id = GETPOST('id', 'int');

if (GETPOST('action')) {
	$action = GETPOST('action');
}
$myparam = GETPOST('myparam', 'alpha');

dol_syslog("Dol url root=".DOL_URL_ROOT);

// Protection if external user
if ($user->societe_id > 0) {
	//accessforbidden();
}

/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */
$valores = array();

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/admin/Configuration.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpaymentterm.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpaymentterm.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabrelctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabsatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

require_once '../functions/functions.php';

/* llxHeader('', 'Configuracion', '');
$title="Configuracion";
$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($title,$linkback,'setup');
$head=array();        // Tableau des onglets

$head = contab_admin_prepare_head($object, $user);
dol_fiche_head($head, '6', 'Terceros Poliza', 0, ''); */


if ($action == 'new')
{   
	$idplanestudio= GETPOST('rowidp','int');
	//$nmaterias=array();
	//$nmaterias	= GETPOST('nmaterias','int');
	$_POST['tercerossi'];
	$total=count($_POST['tercerossi']);
	if($total>0){
		for($i=0;$i<$total;$i++){
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."contab_tercero_nopoliza (fk_societe) 
				VALUES('".$_POST['tercerossi'][$i]."')";
	    $resql =$db->query($sql);
		}
		print "<script>window.location.href='terceros.php?mod=1'</script>";
	}else{
		print "<script>window.location.href='terceros.php?mod=1'</script>";
	}
}

if ($action == 'addall')
{
	$sql="SELECT rowid, nom, client, fournisseur
		FROM ".MAIN_DB_PREFIX."societe
		WHERE rowid NOT IN (SELECT fk_societe FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza) AND entity=".$conf->entity."
		ORDER BY nom";
	$resql =$db->query($sql);
	while($objp = $db->fetch_object($resql)){
		$sql2 = "INSERT INTO ".MAIN_DB_PREFIX."contab_tercero_nopoliza (fk_societe) 
				VALUES('".$objp->rowid."')";
		$resql2 =$db->query($sql2);
	}
	print "<script>window.location.href='terceros.php?mod=1'</script>";
}
if ($action == 'quitar')
{
	$_POST['tercerosno'];
	$total=count($_POST['tercerosno']);
	if($total>0){
		$i=0;
		for($i=0;$i<$total;$i++){
			$sql="DELETE FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza WHERE fk_societe='".$_POST['tercerosno'][$i]."'";
			//echo $sql;break;
			$resql =$db->query($sql);
		}
	}
	print "<script>window.location.href='terceros.php?mod=1'</script>";
}

if ($action == 'quitall')
{
	$sql="SELECT rowid, nom, client, fournisseur
		FROM ".MAIN_DB_PREFIX."societe
		WHERE rowid IN (SELECT fk_societe FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza) AND entity=".$conf->entity."
		ORDER BY nom";
	$resql =$db->query($sql);
	while($objp = $db->fetch_object($resql)){
		$sql2 = "DELETE FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza WHERE fk_societe='".$objp->rowid."'";
		$resql2 =$db->query($sql2);
	}
	print "<script>window.location.href='terceros.php?mod=1'</script>";
}

if($action=='activar'){
	if(GETPOST('permpolaut')){
		$sql="UPDATE ".MAIN_DB_PREFIX."contab_tercero_poliza_automatica SET pautomaticas=1 WHERE entity=".$conf->entity;
		$rqs =$db->query($sql);
	}else{
		$sql="UPDATE ".MAIN_DB_PREFIX."contab_tercero_poliza_automatica SET pautomaticas=2 WHERE entity=".$conf->entity;
		$rqs =$db->query($sql);
	}
	print "<script>window.location.href='terceros.php?mod=1'</script>";
}
$sql="SELECT count(*) as exist
FROM ".MAIN_DB_PREFIX."contab_tercero_poliza_automatica
WHERE entity=".$conf->entity;
$rqs =$db->query($sql);
$rq=$db->fetch_object($rqs);
if($rq->exist==0){
	$sql="INSERT INTO ".MAIN_DB_PREFIX."contab_tercero_poliza_automatica (pautomaticas,entity)
			VALUES (2,".$conf->entity.")";
	$rqs =$db->query($sql);
}
print "<table width='100%' class='noborder'>
		<tr class='liste_titre'><td align='center' colspan='2' >Activar polizas automaticas</td></tr>";
$sql="SELECT pautomaticas
FROM ".MAIN_DB_PREFIX."contab_tercero_poliza_automatica
WHERE entity=".$conf->entity;
$rqs =$db->query($sql);
$rq=$db->fetch_object($rqs);
if($rq->pautomaticas==2){
	$a='';
}else{
	if($rq->pautomaticas==1){
		$a='checked';
	}
}
print "<tr><td width='20%'>Permitir</td><td><form method='POST' action='terceros.php?mod=1&action=activar'>";
print '<input type="checkbox" name="permpolaut" '.$a.'>&nbsp;&nbsp;&nbsp;<input type="submit" value="Guardar"</td></form></tr></table>';
print "<br>";

if($rq->pautomaticas==1){
print "<table width='100%' class='noborder'><tr class='liste_titre'><td colspan='3' align='center'>Seleccion de Terceros que no generan Poliza</td></tr>";
print "<tr class='liste_titre'><td>Disponibles</td><td></td><td>Asignados</td></tr>";
print '<form enctype="multipart/form-data" action="terceros.php?mod=1&action=new" method="POST">';
$sql="SELECT rowid, nom, client, fournisseur
		FROM ".MAIN_DB_PREFIX."societe
		WHERE rowid NOT IN (SELECT fk_societe FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza) AND entity=".$conf->entity."
		ORDER BY nom";
$res =$db->query($sql);
print "<tr><td rowspan='4' width='45%'><select name='tercerossi[]' multiple style='height:400px;width:100%;'>";
while($obj = $db->fetch_object($res)){
	$ter='';
	if($obj->client==1){
		$ter.=' -Cliente';
	}
	if($obj->client==2){
		$ter.=' -Cliente potencial';
	}
	if($obj->client==3){
		$ter.=' -Cliente -Cliente potencial';
	}
	if($obj->fournisseur==1){
		$ter.=' -Proveedor';
	}
	print "<option value='".$obj->rowid."'>".$obj->nom." (".$ter.")</option>";
}
print "</select></td></tr>";
print "<td align='center'><input type='submit' class='button' value='>' title='Mover seleccionados'><br><br>";
print "</form>";

print '<form enctype="multipart/form-data" action="terceros.php?mod=1&action=addall" method="POST">';
print "<input type='submit' class='button' value='>>' title='Mover todos'><br><br>";
print "</form>";

print '<form enctype="multipart/form-data" action="terceros.php?mod=1&action=quitall" method="POST">';
print "<input type='submit' class='button' value='<<' title='Mover todos'><br><br>";
print "</form>";

print '<form enctype="multipart/form-data" action="terceros.php?mod=1&action=quitar" method="POST">';
print "<input type='submit' class='button' value='<' title='Mover seleccionados'></td>";
print "<td rowspan='4' width='45%'>";
$sql2="SELECT rowid, nom, client, fournisseur
		FROM ".MAIN_DB_PREFIX."societe
		WHERE rowid IN (SELECT fk_societe FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza) AND entity=".$conf->entity."
		ORDER BY nom";
$res2 =$db->query($sql2);
print "<select name='tercerosno[]' multiple style='height:400px;width:100%;'>";
while($obj2 = $db->fetch_object($res2)){
	$ter='';
	if($obj2->client==1){
		$ter.=' -Cliente';
	}
	if($obj2->client==2){
		$ter.=' -Cliente potencial';
	}
	if($obj2->client==3){
		$ter.=' -Cliente -Cliente potencial';
	}
	if($obj2->fournisseur==1){
		$ter.=' -Proveedor';
	}
	print "<option value='".$obj2->rowid."'>".$obj2->nom." (".$ter.")</option>";
}
print "</select>";
print "</td>";
print "</form>";
print "</table>";
}