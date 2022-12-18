<?php
require_once "config/myDBC.php";
include_once "config/dbconfig.php";

define("USERAPP", $argv[1]);
define("PASSWORDAPP", $argv[2]);
define("DB_NAME", strtolower(preg_replace('/[^a-zA-Z0-9]/', '', USERAPP)));
$dolibarr = new Dolibarr(DB_NAME);

echo "************ OBTENIENDO TOKEN *************** \n\n";
$token = shell_exec("curl --request POST -d '{ \"username\":\"".USERAPP."\", \"password\":\"".PASSWORDAPP."\"}' ".URLTOKEN);
echo "\n\n";

$curl = 'curl --request POST -H "Authorization: '.$token.'" '.URLEVENTOS;

$json = shell_exec($curl);
$json2 = json_decode($json,true);
$obj = json_decode($json2);

for($i=0; $i< count($obj); $i++){
	$cadena2 = " 
	UPDATE ".PREFIJO."_facture_extrafields SET 
	evento = '{$obj[$i]->evento}',
	dateevento='{$obj[$i]->eventTime}',
	condicion='{$obj[$i]->condicion}',
	condicionmsg='{$obj[$i]->condicionmsg}'
	WHERE fk_object = '{$obj[$i]->facturasistema}'
	";
	$dolibarr->grabardatos($cadena2);	

	echo "===> Actualizando EVENTOS desde dynamodb del documento: ".$obj[$i]->invoice."\n";
	
}


?>


