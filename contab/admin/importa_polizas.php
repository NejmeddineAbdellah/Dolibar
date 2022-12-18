<?php
if (file_exists(DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/admin/Configuration.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabsatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabgrupos.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabgrupos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabgrupos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

require_once '../functions/functions.php';

$config = new Configuration($db);

$action='';
if(GETPOST('action')){
	$action=GETPOST('action');
}
//print_r($_POST);
//print $action;
if($action=='cargar'){
	// Recoge el nombre del fichero que se habrá indicado en el formulario
	$fichero = $_FILES["fichero"]["name"];
	// Recoge la ubicación temporal del fichero en el servidor
	$fichero_tmp = $_FILES["fichero"]["tmp_name"];
	$fichero_tipo = $_FILES["fichero"]["type"];
	//print "<br>>> ".$fichero." :: ".$fichero_tmp." :: ".$fichero_tipo."<br>";
	// Comprueba que se ha indicado un fichero en el formulario
	if ($fichero == "") {
		echo "Error: No se ha especificado ningun fichero";
		return;
	}
	
	// Ruta completa (incluido el nombre del fichero), necesaria para usar fopen; hemos creado una carpeta denominada ficheros
	//La carpeta ficheros está en la misma ubicación que el script
	$destino = "../admin/temp/" . $fichero;
	
	// Copia el fichero al directorio de nuestro servidor, cogiéndolo de la ubicación temporal
	if (move_uploaded_file($fichero_tmp, $destino)) {
		//print "<br> ../admin/temp/".$fichero;
		if($fp = fopen("../admin/temp/".$fichero, "r")){
			//print '<table>';
			$contador=0;
			$idpol=0;
			while (($datos = fgetcsv($fp, 1000, ",")) !== FALSE) {
				//print_r($datos);
				//print "<br>";
				if($datos[0]=='Pol'){
// 					print_r($datos);
// 					print "<br>"; 
					$fecha=str_replace("/", "-", $datos[6]);
					$fecha=date('Y-m-d',strtotime($fecha));
					//$fechahora=str_replace("/", "-", $datos[13]);
					$sql="INSERT INTO ".MAIN_DB_PREFIX."contab_polizas (entity,tipo_pol,cons,anio,
								mes,fecha,concepto,comentario,
							fk_facture, anombrede,numcheque,ant_ctes,
							fechahora,societe_type) 
						  VALUES('".$conf->entity."','".$datos[2]."','".$datos[3]."','".$datos[4]."'
						 ,'".$datos[5]."','".$fecha."','".$db->escape($datos[7])."','".$db->escape($datos[8])."'
						  	,'0','".$datos[10]."','0','".$datos[12]."'
						  	,now(),'0')";
					//print $sql."<br>";
					if($req=$db->query($sql)){
						$contador++;
						$sqm="SELECT @@identity AS id";
						$rq=$db->query($sqm);
						$rs=$db->fetch_object($rq);
						$idpol=$rs->id;
					}else{
						$idpol=0;
					}
				}else{
					if($datos[0]=='Poldet'){
// 						print_r($datos);
// 						print "<br>";
						if($idpol!=0){
							$sql2="INSERT INTO ".MAIN_DB_PREFIX."contab_polizasdet (fk_poliza,asiento,cuenta,
									debe,haber,descripcion) 
									VALUES('".$idpol."','".$datos[2]."','".$datos[3]."'
											,'".$datos[4]."','".$datos[5]."','".$db->escape($datos[6])."')";
							$rqs=$db->query($sql2);
							//print $sql2."<br>";
						}
					}else{
						if($datos[0]=='PolLog'){
// 							print_r($datos);
// 							print "<br>";
							if($idpol!=0){
								$sq3="SELECT rowid FROM ".MAIN_DB_PREFIX."user
										 WHERE login='".$datos[4]."'";
								$rf=$db->query($sq3);
								$nrw=$db->num_rows($rf);
								if($nrw>0){
									$rl=$db->fetch_object($rf);
									$sq4="INSERT INTO ".MAIN_DB_PREFIX."contab_polizas_log 
											(fk_user, fk_poliza, cantmodif, creador, fechahora) 
										  VALUES('".$rl->rowid."','".$idpol."','".$datos[2]."','".$datos[1]."','".$datos[3]."')";
									//print $sq4."<br>";
									$rqs4=$db->query($sq4);
								}
							}
						}
					}
				}
			}
			//print '</table>';
			print "Total de polizas importadas: ".$contador;
		}else{
			//print "NO";
		}
		
		 fclose($fp);
	}
}else{
	print "<form action='exportar.php?mod=2&action=cargar' method='POST' enctype='multipart/form-data'>";
	print "Cargar archivo CVS:<br>";
	print "<input type='file' name='fichero' id='fichero' size='40'> ";
	print "<input type='submit' name='submit' value='Cargar'></form>";
}
?>