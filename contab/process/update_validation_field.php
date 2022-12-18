<?php
/* JPFarber - Rutina para validar que algun reporte contable ya se haya verificado su estado y asegurarse que esté bien.*/

if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only

if (! $user->rights->contab->cont) {
	accessforbidden();
}

//Ejecutar la modificación de la base de datos y la llamada al "Enabler"

$id = addslashes($_POST["id"]);
$value = addslashes($_POST["value"]);
$anio = addslashes($_POST["anio"]);
$mes = addslashes($_POST["mes"]);

//$id = left($id, strlen($id) - 5);
//$val = ($value == true) ? 1 : 0;

$sql = "UPDATE ".MAIN_DB_PREFIX."contab_periodos SET validado_$id = $value WHERE anio = $anio and mes = $mes AND entity = ".$conf->entity;
$db->query($sql);
dol_syslog("Se supone que actualizó los datos, sql=".$sql);
?>