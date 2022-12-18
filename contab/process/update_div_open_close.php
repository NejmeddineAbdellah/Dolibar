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

if (! $user->rights->contab->cont) {
	accessforbidden();
}

//Ejecutar la modificación de la base de datos y la llamada al "Enabler"

$anio = addslashes($_POST["anio"]);
$mes = addslashes($_POST["mes"]);
$pa_val = addslashes($_POST["pa_val"]);

if ($para_validar == 0) {
	?><a href="?action=open_period&anio=<?=$anio?>&mes=<?=$mes?>">Reabrir Periodo</a><?php
} else {
	?><a href="?action=close_period&anio=<?=$anio?>&mes=<?=$mes?>">Cerrar Periodo</a><?php 
}
					
dol_syslog("Se supone que muestra datos en el td actualizados");
?>
