<?php

$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include '../../../main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../main.inc.php")) $res=@include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

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

/* if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/facture.class.php';
} */

/* if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/fournisseur.facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/fournisseur.facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/fournisseur.facture.class.php';
} */

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
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';

if(GETPOST('tipo')!='pdf'){
header("Content-type: application/ms-excel");
header("Content-disposition: attachment; filename=poliza.xls");
}
if (! $user->rights->contab->cont) {
	accessforbidden();
}

$var=True;

$id = addslashes($_GET["id"]);

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

dol_syslog("Impimiendo al poliza numero: $id");
$row = $pol->fetch($id, 0);

$poliza = "<h3>".$conf->global->MAIN_INFO_SOCIETE_NOM."";

if ($row > 0) { // = $db->fetch_array(rs)) {
	$tp = $pol->tipo_pol;
	$c = $pol->cons;
	$facid = $pol->fk_facture;
	$nomsoc='';
	
	if ($pol->societe_type == 1) {
		//Es un Cliente
		$f->fetch($pol->fk_facture);
		$facnumber = $f->ref;
		$sfcid=$f->socid;
		$noms= new Societe($db);
		$noms->fetch($sfcid);
		$nomsoc=$noms->name;
	} else if($pol->societe_type == 2) {
		//Es un Proveedor
		$ff->fetch($pol->fk_facture);
		$facnumber = $ff->ref;
		$sfcid=$ff->socid;
		$noms= new Societe($db);
		$noms->fetch($sfcid);
		$nomsoc=$noms->name;
	}
	$poliza .= '
	<table border="1"  style="border-collapse: collapse;width: 95%;font-size:9px" align="center">
	<tr>
		<td colspan="6">Encabezado de la P&oacute;liza</td>
	</tr>
	<tr><td colspan="6">Empresa: '.$conf->global->MAIN_INFO_SOCIETE_NOM.'</td></tr>
	<tr>
		<td colspan = "2">
			P&oacute;liza:  <strong>'.$pol->Get_folio_poliza().' Cons: '.$c.'</strong>
		</td>
		<td colspan = "2">Fecha: '.date("Y-m-d",$pol->fecha).'</td>
		<td colspan = "2">
			Documento Relacionado: '.$facnumber.'
		</td>
	</tr>';
	if($pol->pol_ajuste==1){
	$poliza .= '<tr>
				<td colspan = "6">
					<strong>Poliza del periodo de ajuste</strong>
				</td>
			</tr>';
	}
	if($nomsoc!=''){
		$poliza.=' <tr>
				<td colspan = "6">
					Tercero: '.$nomsoc.'</strong>
				</td>
				</tr>';
	}
	$poliza .= '
	<tr>
		<td colspan = "3">
			Concepto: <strong>'.utf8_decode(substr($pol->concepto,0,150)).'</strong>
		</td>
		<td colspan = "3">
			Comentario: <strong>'.utf8_decode(substr($pol->comentario,0,150)).'</strong>
		</td>
	</tr>
	<tr>
		<td>Asiento</td>
		<td>Cuenta</td>
		<td>Concepto</td>
	    <td>UUID</td>
		<td>Debe</td>
		<td>Haber</td>
	</tr>';
	
	$cond = " fk_poliza = ".$id;
	$rr = $poldet->fetch_next(0, $cond);
	if ($rr) {
		$totdebe=0;
		$tothaber=0;
		while ($rr) {
			//Verificar primeramente si se trata de un artículo
			$nom = '';
			if ($pol->societe_type == 1 || $pol->societe_type == 2) {
				if (!$ctas->fetch_by_Cta($poldet->cuenta, false)) {
					if ($soc->fetch($f->socid)) {
						dol_syslog("Societe Type = ".$pol->societe_type);
						$nom = $soc->nom;
					}
				} else {
					$nom = $ctas->descta;
				}
			}
			if ($nom) {
				//print $nom_soc;
			}else {
				$ctas->fetch_by_Cta($poldet->cuenta, false);
				$nom=$ctas->descta;
			}
			$totdebe+=$poldet->debe;
			$tothaber+=$poldet->haber;
			$poliza .= "<tr>
				<td style='text-align: left'>".$poldet->asiento."</td>
				<td width='50%' style='text-align: left'>".$poldet->cuenta." - ".$nom."</td>
				<td width='15%'>".$poldet->desc."</td>
				<td width='15%'>".$poldet->uuid."</td>
				<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($poldet->debe, 2)."</td>
				<td style='text-align: right'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($poldet->haber, 2)."</td>
			</tr>";

			$i ++;
			$id = $poldet->id;
			$rr = $poldet->fetch_next($id, $cond);
		}
		$poliza.="<tr>
						<td colspan='4' align='right'>
						<strong>Total</strong>
						</td>
						<td style='text-align: right;'>".$langs->getCurrencySymbol($conf->currency).' '.number_format($totdebe, 2)."</td>
						<td style='text-align: right;'>".$langs->getCurrencySymbol($conf->currency).' '.number_format($tothaber, 2)."</td>
					</tr>";
		
		$sqm="SELECT a.cantmodif, a.fechahora, b.lastname, b.firstname,a.creador
			FROM ".MAIN_DB_PREFIX."contab_polizas_log a, ".MAIN_DB_PREFIX."user b
			WHERE fk_poliza=".$pol->id." AND a.fk_user=b.rowid ORDER BY a.fechahora DESC";
		$mrq=$db->query($sqm);
		$mnr=$db->num_rows($mrq);
		if($mnr>0){
			$poliza.="
					<tr>
					<td colspan='6'>
						<table class='border' style='border-collapse: collapse;font-size:9px'>
							<tr>
								<td>Usuario</td>
								<td>Modificaciones</td>
								<td>Fecha Ult. modificacion</td>
							</tr>";
							
							while($mrs=$db->fetch_object($mrq)){
								$stro='';
								if($mrs->creador==1){
									$poliza.="<tr>
										<td><strong>".$mrs->firstname." ".$mrs->lastname."</strong></td>
										<td align='center'>".$mrs->cantmodif."</td>
										<td>".$mrs->fechahora."</td>
									</tr>";
								}else{
								  $poliza.="<tr>
									<td>".$mrs->firstname." ".$mrs->lastname."</td>
									<td align='center'>".$mrs->cantmodif."</td>
									<td>".$mrs->fechahora."</td>
								</tr>";
								}
							}
							$poliza.="
						</table>
					</td>
					</tr>";
					}
	}
	$poliza .= '
	</table>
	<br><br>';
}
if(GETPOST('tipo')=='pdf'){
	//print $poliza;
	require_once '../class/dompdf/dompdf_config.inc.php';
	$dompdf = new DOMPDF();
	$dompdf->load_html($poliza);
	$dompdf->render();
	$dompdf->stream("poliza.pdf",array('Attachment'=>0));
}else{
	print $poliza;
}
?>