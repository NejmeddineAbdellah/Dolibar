<?php
/* JPFarber - Actualización del div para mostrar opción de si se abre o se cierra el periodo. */

if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabrelctas.class.php';
}

if (! $user->rights->contab->cont) {
	accessforbidden();
}

dol_syslog("Estoy en newsupplier_fill_ddl.php");

//Ejecutar la modificación de la base de datos y la llamada al "Enabler"
$soc_type = GETPOST("soc_type");

//print "Fecha:".$fecha;
$anio = date("Y", $fecha);
$mes = date("m", $fecha);

$a_fac = array();
if ($soc_type == 0) {
	?><select><option value="0">Sin Información</option></select><?php
} else if ($soc_type == 1) {

	?>
		<select name="fk_facture" id="fk_facture">
		<option value="0">&nbsp;</option>
	<?php
	
	$sql = 'SELECT * FROM '.MAIN_DB_PREFIX.'facture f Inner Join '.MAIN_DB_PREFIX.'societe s On f.fk_soc = s.rowid and s.client = 1 WHERE f.entity = '.$conf->entity;
	
	dol_syslog("newpol_fill_ddl.php :: sql=".$sql, LOG_DEBUG);
	
	$result = $db->query($sql);
	if ($result)
	{
		dol_syslog("Entre");
		while ($obj = $db->fetch_object($result)) {
			dol_syslog($obj->rowid." ".$obj->facnumber);
			?><option value="<?=$obj->rowid?>"><?=$obj->facnumber;?></option><?php
		}
	}
	?></select><?php
		
		
} else if($soc_type == 2) {
?>
		<select name="fk_facture" id="fk_facture">
		<option value="0">&nbsp;</option>
	<?php
	
	$sql = 'SELECT * FROM '.MAIN_DB_PREFIX.'facture_fourn f Inner Join '.MAIN_DB_PREFIX.'societe s On f.fk_soc = s.rowid and s.fournisseur = 1 WHERE f.entity = '.$conf->entity;
	
	dol_syslog("newpol_fill_ddl.php :: sql=".$sql, LOG_DEBUG);
	
	$result = $db->query($sql);
	if ($result)
	{
		dol_syslog("Entre");
		while ($obj = $db->fetch_object($result)) {
			dol_syslog($obj->rowid." ".$obj->ref);
			?><option value="<?=$obj->rowid?>"><?=$obj->ref;?></option><?php
		}
	}
	?></select><?php
}
?>
