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

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabperiodos.class.php';
}

if (! $user->rights->contab->cont) {
	accessforbidden();
}

$anio = addslashes($_POST["anio"]);
$per = new Contabperiodos($db);

$sql = "Select mes From ".MAIN_DB_PREFIX."contab_periodos Where anio = $anio AND entity = ".$conf->entity." Group by mes DESC ";
dol_syslog("Sql=$sql");

$op = "";
$res = $db->query($sql);
if ($res) {
	while ($row = $db->fetch_object($res)) {
		$op .= "<option value='".$row->mes."'>".$per->MesToStr3($row->mes)."</option>";
	}
}
print $op;
dol_syslog("Se supone que esta actualizando el ddl del mes...");
?>
