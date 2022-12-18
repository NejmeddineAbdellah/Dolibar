<?php
require_once "config/myDBC.php";
include_once "config/dbconfig.php";

define("USERAPP", $argv[1]);
define("PASSWORDAPP", $argv[2]);
define("DB_NAME", strtolower(preg_replace('/[^a-zA-Z0-9]/', '', USERAPP)));
define("DOCUMENTOS", "@/documents/".DB_NAME."/facture/");
$dolibarr = new Dolibarr(DB_NAME);

//PDF DE FACTURAS PARA SUBIR A S3
$cadena = " 
SELECT fac.datef AS datef, fac.rowid, fac.ref AS facnumber
FROM ".PREFIJO."_facture AS fac
INNER JOIN ".PREFIJO."_facture_extrafields AS extra
ON fac.rowid = extra.fk_object
WHERE extra.envio AND extra.isvalid = 'true' AND extra.pdf21 = '-' ORDER BY fac.ref
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

	$curl = 'curl --request POST -H "Authorization: '.$token.'" -H "Content-Type:application/octet-stream" --data-binary "'.DOCUMENTOS.'"'.$row['facnumber'].'"/"'.$row['facnumber'].'".pdf" "'.URLSUBIRS3.'?invoice="'.$prefijo.$numero.'"&carpeta="'.substr($row['datef'],0,7).'" " ';

	//echo $curl;

	$json = shell_exec($curl." > /dev/null 2>/dev/null &");
	echo "===> Subiendo pdf a S3 del documento: ".$prefijo.$numero."\n";
	//echo $json;

}


?>
