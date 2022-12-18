<?php

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) { $i--; $j--; }
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
dol_include_once('/facturadian/class/cronjobs.class.php');
dol_include_once('/facturadian/lib/facturadian_cronjobs.lib.php');

// Load translation files required by the page
$langs->loadLangs(array("facturadian@facturadian", "other"));

// Get parameters
$idx = GETPOST('id', 'int');
$ref        = GETPOST('ref', 'alpha');
$action = GETPOST('action', 'aZ09');
$confirm    = GETPOST('confirm', 'alpha');
$cancel     = GETPOST('cancel', 'aZ09');
$contextpage = GETPOST('contextpage', 'aZ') ?GETPOST('contextpage', 'aZ') : 'cronjobscard'; // To manage different context of search
$backtopage = GETPOST('backtopage', 'alpha');
$backtopageforcancel = GETPOST('backtopageforcancel', 'alpha');
//$lineid   = GETPOST('lineid', 'int');

// Initialize technical objects
$object = new cronjobs($db);
$extrafields = new ExtraFields($db);
$diroutputmassaction = $conf->facturadian->dir_output.'/temp/massgeneration/'.$user->idx;
$hookmanager->initHooks(array('cronjobscard', 'globalcard')); // Note that conf->hooks_modules contains array


// Load object
include DOL_DOCUMENT_ROOT.'/core/actions_fetchobject.inc.php'; // Must be include, not include_once.


if ($action == 'grilla')
{
	//********************************************************************************

	$page = $_REQUEST['page'];
	$limit = $_REQUEST['rows'];
	$sidx = $_REQUEST['sidx'];
	$sord = $_REQUEST['sord'];
	if(!$sidx) $sidx =1;

	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."facturadian_cron WHERE 1";
	//$sql.= $db->plimit(10, 0);
	$resql = $db->query($sql);
	if ($resql)
	{
		$count = $db->num_rows($resql);
	}

	if( $count > 0 ) $total_pages = ceil($count/$limit);
	else $total_pages = 0;

	if ($page > $total_pages) $page=$total_pages;

	$start = $limit*$page - $limit;
	if($start <0) $start = 0;

	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
				  header("Content-type: application/xhtml+xml;charset=utf-8"); 
	} else {
			  header("Content-type: text/xml;charset=utf-8");
	}
	print "<rows>";
	print "<page>".$page."</page>";
	print "<total>".$total_pages."</total>";
	print "<records>".$count."</records>";

	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."facturadian_cron WHERE 1";
	//$sql.= $db->plimit(10, 0);
	$resql = $db->query($sql);
	if ($resql) 
	{
		$num = $db->num_rows($resql);
		$i = 0;

		if ($num)
		{
			while ($i < $num)
			{
				$objp = $db->fetch_object($resql);
				print "<row id='".$objp->rowid."'>";
					print "<cell></cell>";
					print "<cell>".$objp->documento."</cell>";
					print "<cell>".$objp->prefijo."</cell>";
					print "<cell>".$objp->dias."</cell>";
					print "<cell>".$objp->hora."</cell>";
					print "<cell>".$objp->minuto."</cell>";
					print "<cell>".$objp->ambiente."</cell>";
					print "<cell>".$objp->cantidad."</cell>";
					print "<cell>".$objp->rowid."</cell>";
				print "</row>";
				$i++;
			}
			print "</rows>";
			$db->free($resql);
		}
	}
	
	//********************************************************************************
}

if ($action == 'subgrid')
{
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
				  header("Content-type: application/xhtml+xml;charset=utf-8"); 
	} else {
			  header("Content-type: text/xml;charset=utf-8");
	}

	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."facturadian_cronjobs WHERE cron = '{$_POST['id']}' ORDER BY job ASC";
	
	$resql = $db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
		$i = 0;

		if ($num)
		{
			print "<rows>";
			while ($i < $num)
			{
				$objp = $db->fetch_object($resql);
				print "<row>";
					print "<cell>".$objp->job."</cell>";
					print "<cell>".$objp->prefijo."</cell>";
					print "<cell>".$objp->detalle."</cell>";
					print "<cell>".$objp->accion."</cell>";
 
				print "</row>";
				$i++;
			}
			print "</rows>";
			$db->free($resql);
		}
	}

}

if ($action == 'editar')
{
	if ($_REQUEST['oper']=="" OR $_REQUEST['oper']=="edit")
	{
		$sql = "UPDATE ".MAIN_DB_PREFIX."facturadian_cron SET 
				documento='{$_POST['documento']}',
				prefijo='{$_POST['prefijo']}',
				dias='{$_POST['dias']}',
				hora='{$_POST['hora']}',
				minuto='{$_POST['minuto']}',
				ambiente='{$_POST['ambiente']}',
				cantidad='{$_POST['cantidad']}'
				WHERE rowid='{$_POST['id']}' ";
		$db->query($sql);	
	}
	if ($_REQUEST['oper']=="del")
	{
		$sql = "DELETE FROM ".MAIN_DB_PREFIX."facturadian_cron WHERE rowid=".$_POST['id'];
		$db->query($sql);
	}
	if ($_REQUEST['oper']=="add")
	{
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cron 
				(documento,prefijo,dias,hora,minuto,ambiente,cantidad) VALUES (
				'{$_POST['documento']}',
				'{$_POST['prefijo']}',
				'{$_POST['dias']}',
				'{$_POST['hora']}',
				'{$_POST['minuto']}',
				'{$_POST['ambiente']}',
				'{$_POST['cantidad']}'
				) ";
		$db->query($sql);
	}
}

if ($action == 'lanzar')
{
	//leer si tiene tareas este item especifico
	$cadenaCron = "SELECT * FROM ".MAIN_DB_PREFIX."facturadian_cron WHERE rowid='$_POST[cron]' ";
	$resultCron = $db->query($cadenaCron);
		
	if ($resultCron)
	{
		$num = $db->num_rows($resultCron);
		$i = 0;

		if ($num)
		{
			//Leemos parametros
			$cadenaParametros = "SELECT * FROM ".MAIN_DB_PREFIX."facturadian_credenciales WHERE 1 LIMIT 1";
			$resqlParametros = $db->query($cadenaParametros);
			if ($resqlParametros)
			{
				$rowParametros = $db->fetch_object($resqlParametros);
			}			
			while ($i < $num)
			{
				$rowCron = $db->fetch_object($resultCron);
				$arrayDias = explode (",", $rowCron->dias);
				if (in_array(date("N"), $arrayDias)) {

					$arrayPrefijos = explode (",", $rowCron->prefijo);
					$tiempo=0;
					foreach ($arrayPrefijos as $prefijo) {

						//Envia
						$ejecutar = "echo 'php ./scripts/enviar_".$rowCron->documento.".php ".$rowParametros->username." ".$rowParametros->password." ".$rowCron->ambiente." ".$rowCron->cantidad." ".$prefijo."' | at ".$rowCron->hora.":".$rowCron->minuto." + ".++$tiempo." minutes 2>&1";
						$retval = NULL;
						$output = NULL;
						exec($ejecutar, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron->rowid','$job[1]','$prefijo','$output[1]','enviar') ";
						$db->query($cadenaDetalle);

						//Actualiza
						++$tiempo;
						$ejecutar2= "echo 'php ./scripts/update.php ".$rowParametros->username." ".$rowParametros->password."' | at ".$rowCron->hora.":".$rowCron->minuto." + ".++$tiempo." minutes 2>&1";
						$retval = NULL;
						$output = NULL;
						exec($ejecutar2, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron->rowid','$job[1]','$prefijo','$output[1]','actualizar') ";
						$db->query($cadenaDetalle);

						//Subes3
						$ejecutar3= "echo 'php ./scripts/pdf.php ".$rowParametros->username." ".$rowParametros->password."' | at ".$rowCron->hora.":".$rowCron->minuto." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar3;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar3, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron->rowid','$job[1]','$prefijo','$output[1]','subePdf') ";
						$db->query($cadenaDetalle);

						//Actualiza
						++$tiempo;
						$ejecutar4= "echo 'php ./scripts/update.php ".$rowParametros->username." ".$rowParametros->password."' | at ".$rowCron->hora.":".$rowCron->minuto." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar4;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar4, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron->rowid','$job[1]','$prefijo','$output[1]','actualizar') ";
						$db->query($cadenaDetalle);

						//Cliente
						$ejecutar5= "echo 'php ./scripts/cliente.php ".$rowParametros->username." ".$rowParametros->password." ".$rowCron->ambiente."' | at ".$rowCron->hora.":".$rowCron->minuto." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar5;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar5, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron->rowid','$job[1]','$prefijo','$output[1]','envioCliente') ";
						$db->query($cadenaDetalle);

						//Actualiza
						++$tiempo;
						$ejecutar6= "echo 'php ./scripts/update.php ".$rowParametros->username." ".$rowParametros->password."' | at ".$rowCron->hora.":".$rowCron->minuto." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar6;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar6, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron->rowid','$job[1]','$prefijo','$output[1]','actualizar') ";
						$db->query($cadenaDetalle);

						//Eventos
						++$tiempo;
						$ejecutar7= "echo 'php ./scripts/eventos.php ".$rowParametros->username." ".$rowParametros->password."' | at ".$rowCron->hora.":".$rowCron->minuto." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar7;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar7, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".MAIN_DB_PREFIX."facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron->rowid','$job[1]','$prefijo','$output[1]','eventos') ";
						$db->query($cadenaDetalle);

						$tiempo = $tiempo + 3;
					}					

				}

				$i++;
			}
		
		}

	}
}


if ($action == 'eliminar')
{
	$sql = "SELECT * FROM ".MAIN_DB_PREFIX."facturadian_cronjobs WHERE cron = '$_POST[cron]' ORDER BY job ASC";
	
	$resql = $db->query($sql);
	if ($resql)
	{
		$num = $db->num_rows($resql);
		$i = 0;

		if ($num)
		{
			// cancela las tareas de linux
			while ($i < $num)
			{
				$objp = $db->fetch_object($resql);

				$ejecutar = "atrm $objp->job  2>&1";
				shell_exec($ejecutar);
				
				$i++;
			}
			// borra las tareas de mysql
			$sql = "DELETE FROM ".MAIN_DB_PREFIX."facturadian_cronjobs WHERE cron = '$_POST[cron]' ";
			$db->query($sql);
		}
	}
}


$db->close();
