<?php
date_default_timezone_set("America/Mexico_City");

$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include '../../../main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../main.inc.php")) $res=@include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

if(GETPOST('tipo')!='pdf'){
 header("Content-type: application/ms-excel");
 header("Content-disposition: attachment; filename=polizas.xls");
}
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/invoice.lib.php';

require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fourn.lib.php';

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolizas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolizas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolizasdet.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}

require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';

require_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';

if (! $user->rights->contab->cont) {
	accessforbidden();
}

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
$langs->load("bills");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$myparam	= GETPOST('myparam','alpha');
//$id 		= GETPOST("id");
$asiento 	= GETPOST('asiento');
$ref 		= GETPOST('ref');
$esfaccte	= GETPOST('fc');
$esfacprov	= GETPOST('fp');
$facid 		= GETPOST('facid','int');
$idpd 		= GETPOST('idpd', 'int');
$soc_type	= GETPOST("soc_type");
$socid 		= GETPOST("socid","int");

//print "Fecha: ".GETPOST("fecha")." == ".time(GETPOST("fecha"))." ///  ";
$anio = 0; $mes = 0;
if (GETPOST('anio')) {
	$anio = GETPOST('anio');
}
if (GETPOST('mes')) {
	$mes = GETPOST('mes');
}

// $per = new Contabperiodos($db);
// if ($per->fetch_by_period($anio, $mes) != 1) {
// 	if ($per->fetch_next_period(1, $anio) == 1) {
// 		$mes = $per->mes;
// 	}
// }

if ($anio > 0 || $mes > 0) {

	dol_syslog("anio=$anio, mes=$mes");
	$per = new Contabperiodos($db);
	if ($per->fetch_by_period($anio, $mes)) {
		$periodo_estado = $per->estado;
	}
	
} else {
	$per = new Contabperiodos($db);
	if (! $per->fetch_open_period()) {
		dol_syslog("Se supone que no hay un periodo abierto.   <script>window.location = ".DOL_DOCUMENT_ROOT."/contab/index.php"."</script>");
		//$db->close();
		if (file_exists(DOL_DOCUMENT_ROOT.'/contab/periodos/fiche.php')) {
			print "<script>window.location = '".DOL_URL_ROOT."/contab/periodos/fiche.php';"."</script>";
		} else {
			print "<script>window.location = '".DOL_URL_ROOT."/custom/contab/periodos/fiche.php';"."</script>";
		}
	}
	$periodo_estado = $per->estado;
	$anio = $per->anio;
	$mes = $per->mes;
}

$fecha = date("y-m-d h:i:s");

dol_syslog("anio=$anio, mes=$mes,  action = ".$action.", id=$id idpd=$idpd--------------- esfac=$esfaccte, $esfacprov");

$form = new Form($db);

$arrayofjs = array('../js/functions.js');

//llxHeader('','','','','','',$arrayofjs,'',0,0);
$facn='';


$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);
if(GETPOST('fecini') && GETPOST('fecfin')){
	$fecini=GETPOST('fecini');
	$fecfin=GETPOST('fecfin');
}else{
	$fecini='';
	$fecfin='';
}
$html= "<h3>".$conf->global->MAIN_INFO_SOCIETE_NOM."";
if($fecini!='' && $fecfin!=''){
	$html.= "<br>Periodo contable: ".$fecini." - ".$fecfin."</h3>";
}else{
$html.= "<br>Periodo contable: ".$per->anio." - ".$per->MesToStr($per->mes)."</h3>";
}
	$var=True;
	
	$ini = 0;
	$cant = 0;

   	$tp = "";
	$c = 0;
	
   	$i = 0;
	
   	$pol = new Contabpolizas($db);
   	$poldet = new Contabpolizasdet($db);
   	$ctas = new Contabcatctas($db);
   	$ff = new FactureFournisseur($db);
   	$f = new Facture($db);
   	$soc = new Societe($db);
   	
	$primera_vez = true;
	$pol->anio = $anio;
	$pol->mes = $mes;
	
	dol_syslog("2. DATOS = esfacprov=$esfacprov, esfaccte=$esfaccte");
	
	if ($esfaccte == 1 || $esfacprov == 1) {
		$soc_type = ($esfaccte == 1) ? 1 : 2;
		$row = $pol->fetch_next_by_facture_id(0, $facid, $soc_type);
	} else if($socid > 0) {
		$row = $pol->fetch_next_by_societe_id(0, $socid);
	} else {
		$row = $pol->fetch_next(0, 1);
	}
	if ($row <= 0) {
	$html.='
		<table border="1"  style="border-collapse: collapse;width: 95%;font-size:9px" align="center" >
		<tr class="liste_titre">
			<td colspan="4">Encabezado de la Poliza</td>
			<td style="text-align: right;">&nbsp;</td>
			<td style="text-align: right;">
			</td>
		</tr>
	</table>';
	}
	
	if(GETPOST('numpoli')!='' && GETPOST('numpolifin')!=''){
		$numpoli=GETPOST('numpoli');
		$numpolifin=GETPOST('numpolifin');
	}else{
		$numpoli='';
		$numpolifin='';
	}
	if($row > 0){
	if($fecini!='' && $fecfin!=''){
		$sql="SELECT rowid
		FROM ".MAIN_DB_PREFIX."contab_polizas
		WHERE fecha between '".$fecini."' AND '".$fecfin."' AND entity=".$conf->entity." ";
	}else{
		$sql="SELECT rowid
		FROM ".MAIN_DB_PREFIX."contab_polizas
		WHERE anio=".$anio." AND mes=".$mes." AND entity=".$conf->entity." ";
	}
	$filtro=$_REQUEST['filtro'];
	if($_REQUEST['filtro']){
		if($filtro==1){
		}else{
			if($filtro==2){
				$sql.=" AND tipo_pol='I' ";
			}else{
				if($filtro==3){
					$sql.=" AND tipo_pol='D' ";
				}else{
					if($filtro==4){
						$sql.=" AND tipo_pol='C' ";
					}else{
						if($filtro==5){
							$sql.=" AND tipo_pol='E' ";
						}else{
							if($filtro==6){
								$sql="SELECT rowid,fk_facture ,societe_type
										FROM ".MAIN_DB_PREFIX."contab_polizas ,
										(SELECT fk_facture as facdoc,count(rowid) as contar,societe_type as soctyp
										FROM ".MAIN_DB_PREFIX."contab_polizas GROUP BY fk_facture,societe_type) as conta
										WHERE anio=".$anio." AND mes=".$mes." AND entity=".$conf->entity." AND tipo_pol='D'
										AND contar=1 AND fk_facture=facdoc AND societe_type=soctyp";
							}
						}
					}
				}
			}
		}
	}
	if($numpoli!='' && $numpolifin!=''){
				$sql.=" AND cons between '".$numpoli."' AND '".$numpolifin."' ";
			}
			if($_REQUEST['filt']=='fac'){
				$sql.=" ORDER BY fk_facture,societe_type,tipo_pol,cons DESC";
			}else{
				if($_REQUEST['filtro']=='' && $_REQUEST['filt']=='' && $numpoli=='' && $numpolifin==''){
					$sql.=" ORDER BY fecha DESC";
				}else{
					$sql.=" ORDER BY tipo_pol,cons DESC";
				}
			}
			if($_REQUEST['filtro']=='' && $_REQUEST['filt']=='' && $numpoli=='' && $numpolifin==''){
				$sql.=" Limit 5";
			}
	//print $sql."<br>";
	$rqs=$db->query($sql);
	unset($pol);
	while ($rqm=$db->fetch_object($rqs)) { // = $db->fetch_array(rs)) {
		$pol = new Contabpolizas($db);
		$pol->fetch($rqm->rowid,0);
		$html.='
		<table border="1"  style="border-collapse: collapse;width: 95%;font-size:9px" align="center" >
		<tr class="liste_titre">
			<td colspan="5">Encabezado de la Poliza</td>			
		</tr>';
		if ($tp !== $pol->tipo_pol || $c !== $pol->cons) {
			$var = !$var;
			$tp = $pol->tipo_pol;
			$c = $pol->cons;
			$facid = $pol->fk_facture;
			$nomsoc='';
			$esfaccte=0;
			$esfacprov=0;
			if ($pol->societe_type == 1) {
				//Es un Cliente
				$f->fetch($pol->fk_facture);
				$facnumber = $f->ref;
				$sfcid=$f->socid;
				$noms= new Societe($db);
				$noms->fetch($sfcid);
				$nomsoc=$noms->name;
				$pagina = "/compta/facture.php";
				$esfaccte=1;
			} else if($pol->societe_type == 2) {
				//Es un Proveedor
				$ff->fetch($pol->fk_facture);
				$facnumber = $ff->ref;
				$sfcid=$ff->socid;
				$noms= new Societe($db);
				$noms->fetch($sfcid);
				$nomsoc=$noms->name;
				$pagina = "/fourn/facture/fiche.php";
				if(DOL_VERSION>="3.7"){
					$pagina = "/fourn/facture/card.php";
				}
				$esfacprov=1;
			}else{
			  $facnumber='';
			}
$html.='
			<tr>
				<td colspan = "2">
					Poliza:
					<strong> ';
					$html.= $pol->Get_folio_poliza().": ".$c;

$html.='				</strong>
					
				</td>
				<td colspan = "1">Fecha: '.date("Y-m-d",$pol->fecha).'</td>
				<td colspan = "2">
					Documento Relacionado: '.$facnumber.'
				</td>
			</tr>';
			
			if($nomsoc!=''){
				$html.='<tr>
				<td colspan = "5">
					Tercero: <strong>'.$nomsoc.'</strong>
				</td>
				</tr>';
				
			}
			
			$html.='<tr >
				<td colspan = "5">
					Concepto: <strong>'.substr($pol->concepto,0,150).'</strong>
					&nbsp;
					Comentario: <strong>'.substr($pol->comentario,0,150).'</strong>
				</td>
			</tr>
			<tr >
				<td colspan = "5">
					Cheque a Nombre: <strong>'.substr($pol->anombrede,0,150).'</strong>
					&nbsp;
					Num. Cheque: <strong>'.substr($pol->numcheque,0,150).'</strong>
				</td>
			</tr>';
			if($pol->pol_ajuste==1){
				$html .= '<tr>
				<td colspan = "5">
					<strong>Poliza del periodo de ajuste</strong>
				</td>
			</tr>';
			}
		}

	$html.='<tr class="liste_titre">
			<td width="15%">Asiento</td>
			<td width="50%">Cuenta</td>
			<td width="15%">Concepto</td>
			<td style="text-align: right; width: 10%;">Debe</td>
			<td style="text-align: right; width: 10%;">Haber</td> 
		</tr>';

		$cond = " fk_poliza = ".$pol->id;
		$rr = $poldet->fetch_next(0, $cond);
		if ($rr) {
			$totdebe=0;
			$tothaber=0;
			while ($rr) {	

				$html.='<tr >
					<td>'.$poldet->asiento.'</td>
					<td>'.$poldet->cuenta; 
					$nom_soc = "";
					//Verificar primeramente si se trata de un artículo
					if (!$ctas->fetch_by_Cta($poldet->cuenta, false)) {
						if ($pol->societe_type == 1) {
							if ($soc->fetch($f->socid)) {
								dol_syslog("1. Societe Type = ".$pol->societe_type);
								$nom_soc = $soc->nom;
							}
						} else if ($pol->societe_type == 2) {
							if ($soc->fetch($ff->socid)) {
								dol_syslog("2. Societe Type = ".$pol->societe_type);
								$nom_soc = $soc->nom;
							}
						}
					}
					if ($nom_soc) {
						$html.= $nom_soc;
					}else {
						$ctas->fetch_by_Cta($poldet->cuenta, false);
						$html.= '&nbsp;&nbsp;&nbsp;'.$ctas->descta;
					}
					$totdebe+=$poldet->debe;
					$tothaber+=$poldet->haber;
					$html.='</td>
					<td>'.$poldet->desc.'</td>
					<td style="text-align: right;">'.($poldet->debe > 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format($poldet->debe, 2) : "").'</td>
					<td style="text-align: right;">'.($poldet->haber > 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format($poldet->haber, 2) : "").'</td>';
 

				$html.='</tr>';
				
				 
				$i ++;
				$id = $poldet->id;
				$rr = $poldet->fetch_next($id, $cond);
			}
			
			$html.='<tr>
				<td colspan="3" align="right">
				<strong>Total</strong>
				</td>
				<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency).' '.number_format($totdebe, 2).'</td>
				<td style="text-align: right;">'.$langs->getCurrencySymbol($conf->currency).' '.number_format($tothaber, 2).'</td>
			</tr>';
			
		}
		/* $id = $pol->id;
		
		if ($esfaccte == 1 || $esfacprov == 1) {
			$soc_type = ($esfaccte == 1) ? 1 : 2;
			$row = $pol->fetch_next_by_facture_id($id, $facid, $soc_type);
			dol_syslog("1. Se regresa este valor del Fetch_Next=".$row);
		} else if($socid > 0) {
			$row = $pol->fetch_next_by_societe_id($id, $socid);
			dol_syslog("2. Se regresa este valor del Fetch_Next=".$row);
		} else {
			$row = $pol->fetch_next($id, 1);
			dol_syslog("3. Se regresa este valor del Fetch_Next=".$row);
		} */
		unset($pol);

		$html.='</table>
		<br><hr><br>';

	}
  }

if(GETPOST('tipo')=='pdf'){
// 	print $html;
	require_once '../class/dompdf/dompdf_config.inc.php';
	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->render();
	$dompdf->stream("polizas.pdf",array('Attachment'=>0));
}else{
	print $html;
}
?>