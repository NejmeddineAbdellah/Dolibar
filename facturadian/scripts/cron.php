<?php
require_once "config/myDBC.php";
include_once "config/dbconfig.php";

$cadena="SHOW DATABASES";
$link = mysqli_connect(DB_SERVER, DB_USER, DB_PASS) or die ('Error connecting to mysql: ' . mysqli_error($link).'\r\n');

//Eliminar todas las tareas actuales
$ejecutar0 = "atrm $(atq | cut -f1)";
shell_exec($ejecutar0);

if (!($result=mysqli_query($link,$cadena))) {
        printf("Error: %s\n", mysqli_error($link));
    }

while( $row = mysqli_fetch_row( $result ) ){
	if (($row[0]!="information_schema") && ($row[0]!="mysql") && ($row[0]!="tmp") && ($row[0]!="innodb") && ($row[0]!="performance_schema") && ($row[0]=="infofacturadiancom") ) {
		echo "Programando AT sobre la base de datos: ".$row[0]."\r\n";
		$dolibarr = new Dolibarr($row[0]);

		//Elimina las tareas detalles de esa base de datos
		$cadenaCronDel = "TRUNCATE TABLE ".PREFIJO."_facturadian_cronjobs";
		$dolibarr->grabardatos($cadenaCronDel);

		//leer si tiene tareas esta base de datos
		$cadenaCron = "SELECT * FROM ".PREFIJO."_facturadian_cron WHERE 1 ";
		$resultCron = $dolibarr->leerdatosarray($cadenaCron);

		if($resultCron) {

			//Leemos parametros
			$cadenaParametros = "SELECT * FROM ".PREFIJO."_facturadian_credenciales WHERE 1 LIMIT 1";
			$rowParametros = $dolibarr->leerdatos($cadenaParametros);

			while($rowCron = $resultCron->fetch_array(MYSQLI_ASSOC)) {
				$arrayDias = explode (",", $rowCron['dias']);

				if (in_array(date("N"), $arrayDias)) {

					$arrayPrefijos = explode (",", $rowCron['prefijo']);
					$tiempo=0;
					foreach ($arrayPrefijos as $prefijo) {

						//Envia
						$ejecutar = "echo 'php /var/www/html/dolibarr/htdocs/custom/facturadian/scripts/enviar_".$rowCron['documento'].".php ".$rowParametros['username']." ".$rowParametros['password']." ".$rowCron['ambiente']." ".$rowCron['cantidad']." ".$prefijo."' | at ".$rowCron['hora'].":".$rowCron['minuto']." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".PREFIJO."_facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron[rowid]','$job[1]','$prefijo','$output[1]','enviar') ";
						$dolibarr->grabardatos($cadenaDetalle);

						//Actualiza
						++$tiempo;
						$ejecutar2= "echo 'php /var/www/html/dolibarr/htdocs/custom/facturadian/scripts/update.php ".$rowParametros['username']." ".$rowParametros['password']."' | at ".$rowCron['hora'].":".$rowCron['minuto']." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar2;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar2, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".PREFIJO."_facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron[rowid]','$job[1]','$prefijo','$output[1]','actualizar') ";
						$dolibarr->grabardatos($cadenaDetalle);

						//Subes3
						$ejecutar3= "echo 'php /var/www/html/dolibarr/htdocs/custom/facturadian/scripts/pdf.php ".$rowParametros['username']." ".$rowParametros['password']."' | at ".$rowCron['hora'].":".$rowCron['minuto']." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar3;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar3, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".PREFIJO."_facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron[rowid]','$job[1]','$prefijo','$output[1]','subePdf') ";
						$dolibarr->grabardatos($cadenaDetalle);

						//Actualiza
						++$tiempo;
						$ejecutar4= "echo 'php /var/www/html/dolibarr/htdocs/custom/facturadian/scripts/update.php ".$rowParametros['username']." ".$rowParametros['password']."' | at ".$rowCron['hora'].":".$rowCron['minuto']." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar4;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar4, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".PREFIJO."_facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron[rowid]','$job[1]','$prefijo','$output[1]','actualizar') ";
						$dolibarr->grabardatos($cadenaDetalle);

						//Cliente
						$ejecutar5= "echo 'php /var/www/html/dolibarr/htdocs/custom/facturadian/scripts/cliente.php ".$rowParametros['username']." ".$rowParametros['password']." ".$rowCron['ambiente']."' | at ".$rowCron['hora'].":".$rowCron['minuto']." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar5;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar5, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".PREFIJO."_facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron[rowid]','$job[1]','$prefijo','$output[1]','envioCliente') ";
						$dolibarr->grabardatos($cadenaDetalle);

						//Actualiza
						++$tiempo;
						$ejecutar6= "echo 'php /var/www/html/dolibarr/htdocs/custom/facturadian/scripts/update.php ".$rowParametros['username']." ".$rowParametros['password']."' | at ".$rowCron['hora'].":".$rowCron['minuto']." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar6;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar6, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".PREFIJO."_facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron[rowid]','$job[1]','$prefijo','$output[1]','actualizar') ";
						$dolibarr->grabardatos($cadenaDetalle);

						//Eventos
						++$tiempo;
						$ejecutar7= "echo 'php /var/www/html/dolibarr/htdocs/custom/facturadian/scripts/eventos.php ".$rowParametros['username']." ".$rowParametros['password']."' | at ".$rowCron['hora'].":".$rowCron['minuto']." + ".++$tiempo." minutes 2>&1";
						echo "\n".$ejecutar7;
						$retval = NULL;
						$output = NULL;
						exec($ejecutar7, $output, $retval);
						$job = explode(" ",$output[1]);
						$cadenaDetalle = "INSERT INTO ".PREFIJO."_facturadian_cronjobs (cron, job, prefijo, detalle,accion) 
											VALUES ('$rowCron[rowid]','$job[1]','$prefijo','$output[1]','eventos') ";
						$dolibarr->grabardatos($cadenaDetalle);

						$tiempo = $tiempo + 3;
					}

				}


			}
		}


	}
}

?>


