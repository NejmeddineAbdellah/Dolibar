<?php
require_once "config/myDBC.php";
include_once "config/dbconfig.php";

define("USERAPP", $argv[1]);
define("PASSWORDAPP", $argv[2]);
define("DB_NAME", strtolower(preg_replace('/[^a-zA-Z0-9]/', '', USERAPP)));
$dolibarr = new Dolibarr(DB_NAME);

//Elimina prefijos actuales
$cadena = "TRUNCATE ".PREFIJO."_facturadian_cargarprefijos ";
$dolibarr->grabardatos($cadena);
	
echo "************ OBTENIENDO TOKEN *************** \n\n <br /><br />";
$token = shell_exec("curl --request POST -d '{ \"username\":\"".USERAPP."\", \"password\":\"".PASSWORDAPP."\"}' ".URLTOKEN);
echo "\n\n";

$curl = 'curl --request POST -H "Authorization: '.$token.'" '.URLPREFIJOS;

$json = shell_exec($curl);
$json2 = json_decode($json,true);
$obj = json_decode($json2);

for($i=0; $i< count($obj); $i++){

	$cadena2 = " 
	INSERT INTO ".PREFIJO."_facturadian_cargarprefijos 
	(prefijo,clavetecnica,pindian,nit,ambiente) VALUES (
	'{$obj[$i]->Prefijo}',
	'{$obj[$i]->ClaveTecnica}',
	'{$obj[$i]->PinDian}',
	'{$obj[$i]->Nit}',
	'{$obj[$i]->Ambiente}'
	)";
	//echo $cadena2;
	$dolibarr->grabardatos($cadena2);	

	echo "<br />===> Insertado prefijo : ".$obj[$i]->Prefijo;

}

?>


