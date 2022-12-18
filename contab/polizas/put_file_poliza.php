<?php 

$url[0] = '../';
$url[1] = '../configuracion/';
$url[2] = '../asignacion/';
$url[3] = '../cuentas/';
$url[4] = '../polizas/';
$url[5] = '../conf/';
$url[6] = '../periodos/';
$url[7] = '../reportes/';

require_once '../class/poliza.class.php';
require_once '../get/get_data_xml.php';
require_once '../class/tipo_operacion_proveedor.class.php';
require_once '../class/admin.class.php';

$polizas = new Poliza();

if (!file_exists($url[4] . 'archivos' )) {
    mkdir($url[4] .'archivos', 0755);
}
if (file_exists($url[4] . 'archivos/' . ENTITY)) {

} else {
  mkdir($url[4] . 'archivos/' .ENTITY, 0755);
}

if (file_exists($url[4] . 'archivos/' . ENTITY . '/POL' . $_REQUEST['idp'] . '-' . $_REQUEST['tipo_pol'] . $_REQUEST['cons'])) {

} else {
    mkdir($url[4] . 'archivos/' . ENTITY . '/POL' . $_REQUEST['idp'] . '-' . $_REQUEST['tipo_pol'] . $_REQUEST['cons'], 0755);
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'carga') {
    $continue = false;
    $tipo = $_REQUEST['tipo_doc'];
    $dir_subida = $url[4] . 'archivos/' . ENTITY . '/POL' . $_REQUEST['idp'] . '-' . $_REQUEST['tipo_pol'] . $_REQUEST['cons'] . '/';

    $_REQUEST['uui_replace'] = isset($_REQUEST['uui_replace']) ? $_REQUEST['uui_replace']:0;

    $aux_newfilename = uniqid().'_'.date('m.d.y') . '_' . $_REQUEST['tipo_doc'] . '_' . $_FILES['docto']['name'];
    if(isset($_REQUEST['uui_replace']) && $_REQUEST['uui_replace']==1 && $tipo == 'cfdixml'){
        $newfilename = 'XMLRepetido_'.uniqid().'_'.date('m.d.y') . '_' . $_REQUEST['tipo_doc'] . '_' . $_FILES['docto']['name'];
    }else{
        $newfilename = uniqid().'_'.date('m.d.y') . '_' . $_REQUEST['tipo_doc'] . '_' . $_FILES['docto']['name'];
    }

    $fichero_subido = $dir_subida . $newfilename;

    $carpeta = 'POL' . $_REQUEST['idp'] . '-' . $_REQUEST['tipo_pol'] . $_REQUEST['cons'];
    switch ($tipo) {
        case 'cfdipdf':
            $tipo_compare = 'pdf';
            break;
        case 'cfdixml':
            $tipo_compare = 'xml';
            break;
        default:
            $tipo_compare = 'other';
            break;
    }

    if ($tipo_compare != 'other' && pathinfo($_FILES['docto']['name'], PATHINFO_EXTENSION) == $tipo_compare) {
        $continue = true;
    } else if ($tipo_compare == 'other') {
        $extensions = array('php', 'php3', 'php4', 'phtml', 'pl', 'py', 'jsp', 'asp', 'htm', 'html', 'shtml', 'sh', 'cgi');
        $continue = true;
        foreach ($extensions as $value) {
            if ($value == pathinfo($_FILES['docto']['name'], PATHINFO_EXTENSION)) {
                $continue = false;
                break;
            }
        }
    } else {
        exit( json_encode( array( 'mensaje'=>'La extesión del tipo de archivo no coincide.')));
    }

  
    $existe = is_file($fichero_subido);
    $operacion = '';

    if ($continue && move_uploaded_file($_FILES['docto']['tmp_name'], $fichero_subido)) {
        if ($tipo_compare == 'xml') {
            $continue = get_data_xml($fichero_subido);
            if($timbreFDUuid == ''){
                unlink($fichero_subido);
                exit( json_encode( array( 'mensaje'=>'El archivo XML no es correcto, no contiene una cadena UUID.')));
            } else if( ($mensaje = $polizas->search_uuid_docto($_REQUEST['idp'], $carpeta, $newfilename, $tipo,$timbreFDUuid)) && $_REQUEST['uui_replace']==0){
                if(!$existe) unlink($fichero_subido);
                exit( json_encode( array('repetido' =>  '¿Desea continuar? UUID ya registrado: ', 'pol'=> $mensaje ) ) );
            }else if($_REQUEST['uui_replace']== 1 && !($polizas->search_uuid_docto($_REQUEST['idp'], $carpeta, $newfilename, $tipo,$timbreFDUuid) )){
                $newfilename =$aux_newfilename;
                rename($fichero_subido, $dir_subida . $aux_newfilename);    
            }

            if (isset($_POST['id_typeoperation']) && isset($_POST['operation_societe'])) {
                $operacion = new Tipo_Operacion();
                $operacion = $operacion->get_id_operacion_proveedor((int)$_POST['id_typeoperation'], (int) $_POST['operation_societe']);
                if(!$operacion){
                    unlink($fichero_subido);
                    exit( json_encode( array( 'mensaje'=>'No seleccionó un tipo de operacion.')));
                }
                     
                $operacion = $operacion->rowid;
            }
            
        }
        if ($continue) {

            $id_poliza =$polizas->getPolizaId((int)$_REQUEST['idp']);
            $id_poliza =$id_poliza[0];
            $admin     =new admin();
            if($id_poliza['tipol_l'] == 1){
                $rfc = $receptorRFC;
                if ($admin->get_rfc_empresa() != $emisorRfc) {
                    unlink($fichero_subido);
                    exit( json_encode( array( 'mensaje'=>'El RFC del cliente no coincide con el de la empresa.')));
                }   
            }else{
                $rfc = $emisorRfc;
                if($admin->get_rfc_empresa() != $receptorRFC){
                    unlink($fichero_subido);
                    exit( json_encode( array( 'mensaje'=>'El RFC del receptor no coincide con el de la empresa.')));
                }
            }
            
            $crg = $polizas->insert_info_docto($_REQUEST['idp'], $carpeta, $newfilename, $tipo,$timbreFDUuid,$operacion,$rfc);

            exit( json_encode( array( 'mensaje'=>'El archivo se cargo exitosamente.')));
        } else {
            unlink($fichero_subido);
            exit( json_encode( array( 'mensaje'=>'Hay un error en el archivo XML.')));
        }
    } else {
        exit( json_encode( array( 'mensaje'=>'No fue posible cargar el archivo.')));
    }
} 


?>