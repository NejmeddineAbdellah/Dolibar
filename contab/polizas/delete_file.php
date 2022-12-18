<?php 
	require '../../main.inc.php';

	$id = $_POST['id'];
	$respuesta = 'Hubo un error, intente más tarde.';

	$sql = "SELECT url FROM ".MAIN_DB_PREFIX."contab_doc WHERE rowid=".$id;
	$res = $db->query($sql);
	$obj = $db->fetch_object($res);

	$file = $obj->url;

	if ( unlink($file) ) {

		$sql = "DELETE FROM ".MAIN_DB_PREFIX."contab_doc WHERE rowid=".$id;
		$result = $db->query($sql);
		if ($result) {
		   $respuesta = 'El archivo ha sido eliminado con éxito.';
		}
	}

	print $respuesta;

?>