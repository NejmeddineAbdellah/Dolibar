<?php
$res = 0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
    $res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
    $res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
    $res = @include '../../../../main.inc.php';   // Used on dev env only  

//Ejecutar la modificaci�n de la base de datos y la llamada al "Enabler"

print $metodo;
$db->query("UPDATE `".MAIN_DB_PREFIX."cont_config` SET `metodo_ctrl_almacen` = 2");
$db->commit();

header("Location: configuration.php");
?>