<?php
require_once "config/myDBC.php";
include_once "config/dbconfig.php";

define("USERAPP", $argv[1]);
define("PASSWORDAPP", $argv[2]);
define("AMBIENTE", $argv[3]);
define("DB_NAME", strtolower(preg_replace('/[^a-zA-Z0-9]/', '', USERAPP)));
$dolibarr = new Dolibarr(DB_NAME);

//FACTURAS A ENVIAR POR EMAIL
$cadena = "
SELECT fac.ref AS facnumber,fac.datef AS datef,extra.zipkey AS zipkey,soc.email AS ReceptorEmail
FROM ".PREFIJO."_facture AS fac
INNER JOIN ".PREFIJO."_societe AS soc
ON fac.fk_soc = soc.rowid
INNER JOIN ".PREFIJO."_facture_extrafields AS extra
ON fac.rowid = extra.fk_object
WHERE extra.envio AND extra.isvalid = 'true' AND extra.pdf21 = 'true' AND extra.messageid <> 'true' ORDER BY fac.ref
";

$primeravez = true;

$result = $dolibarr->leerdatosarray($cadena);
while($row = $result->fetch_array(MYSQLI_ASSOC)) {
	if($primeravez) {
		echo "************ OBTENIENDO TOKEN *************** \n\n";
		$token = shell_exec("curl --request POST -d '{ \"username\":\"".USERAPP."\", \"password\":\"".PASSWORDAPP."\"}' ".URLTOKEN);
		echo "\n\n";
		$primeravez = false;
	}

	//VARIABLES CALCULADAS
	$prefijonumero = explode("-",$row['facnumber']);
	$prefijo = $prefijonumero[0];
	$numero = $prefijonumero[1];
	
	$curl = 'curl --request POST -H "Authorization: '.$token.'" -H "Content-Type:application/json" -d \'{';
	$curl.= '"Invoice":"'.$prefijo.$numero;
	$curl.= '","IssueDate":"'.$row['datef'];
	$curl.= '","Ambiente":"'.AMBIENTE;
	$curl.= '","ReceptorEmail":"'.$row['ReceptorEmail'];
	$curl.='" }\' '.URLEMAIL;
	
	//echo $curl;

	$json = shell_exec($curl." > /dev/null 2>/dev/null &");
	echo "<br />===> Enviando documento: ".$prefijo.$numero." al email: ".$row['ReceptorEmail']."\n";
	//echo $json;
}

	
?>


