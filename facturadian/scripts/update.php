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

$curl = 'curl --request POST -H "Authorization: '.$token.'" '.URLUPDATE;

$json = shell_exec($curl);
$json2 = json_decode($json,true);
$obj = json_decode($json2);

for($i=0; $i< count($obj); $i++){
	if(strlen($obj[$i]->MessageId) > 20) { $messageid = 'true'; }
	$cadena2 = " 
	UPDATE ".PREFIJO."_facture_extrafields SET 
	cufe = '{$obj[$i]->Uuid}',
	zipkey='{$obj[$i]->ZipKey}',
	isvalid='{$obj[$i]->IsValid}',
	statuscode='{$obj[$i]->StatusCode}',
	statusdescription='{$obj[$i]->StatusDescription}',
	errormessage='".str_replace( '\"', '', json_encode($obj[$i]->ErrorMessage))."',
	success='{$obj[$i]->Success}',
	processedmessage='{$obj[$i]->ProcessedMessage}',
	pdf21='{$obj[$i]->pdf}',
	messageid='$messageid'
	WHERE fk_object = '{$obj[$i]->facturasistema}'
	";
	echo $cadena2;
	$dolibarr->grabardatos($cadena2);	

	echo "===> Actualizando desde la DIAN datos del documento: ".$obj[$i]->invoice."\n";
	
}


?>


