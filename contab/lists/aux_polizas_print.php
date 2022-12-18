<?php
date_default_timezone_set("America/Mexico_City");
setlocale(LC_MONETARY, 'es_MX');

header("Content-type: application/ms-excel");
header("Content-disposition: attachment; filename=auxiliares_polizas.xls");

$res=0;
if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabsatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpolizas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpolizasdet.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabgrupos.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabgrupos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabgrupos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/functions/functions.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/functions/functions.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/functions/functions.php';
}

if (! $user->rights->contab->cont) {
	accessforbidden();
}
global $db,$conf,$langs;
// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$anio = GETPOST('a');
$mes = GETPOST('m');
$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);
 

$cuenta=GETPOST('cta');
//print $cuenta;
$ctas = new Contabcatctas($db);
$ctas->fetch($cuenta);

$table= "<h1>".$conf->global->MAIN_INFO_SOCIETE_NOM." - Auxiliar de Cuentas<br>Polizas Cuenta:".$ctas->cta." ".$ctas->descta."</h1>";
$table.= "<h1>Periodo contable: ".$per->anio." - ".$per->MesToStr($per->mes)."</h1>";

$table.='<table border="1" class="border" >
		<tr class="liste_titre">
			<td style="width: 10%"><strong>Cuenta</strong></td>
			<td  style="width: 70%" colspan="3"><strong>Descripcion</strong></td>
			<td style="width: 10%;text-align: right"><strong>Debe</strong></td>
			<td style="width: 10%;text-align: right"><strong>Haber</strong></td>
		</tr>';
 $sql="SELECT d.rowid,d.cta,d.descta, c.rowid as poliza
		FROM ".MAIN_DB_PREFIX."contab_cat_ctas d,
		(SELECT a.rowid,a.cons,b.cuenta
		FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b
		WHERE anio=".$anio." AND mes=".$mes." AND entity=".$conf->entity." AND a.rowid=b.fk_poliza AND b.cuenta=".$ctas->cta.") c 
		WHERE entity=".$conf->entity." AND d.cta=c.cuenta AND d.cta=".$ctas->cta;
//print $sql;
$r=$db->query($sql);
while($rs=$db->fetch_object($r)){
	$ff = new FactureFournisseur($db);
	$f = new Facture($db);
	
	$soc = new Societe($db);
	
	$ctas = new Contabcatctas($db);
	
	$pd = new Contabpolizasdet($db);
	
	$pol = new Contabpolizas($db);
	$pol->fetch($rs->poliza,0);
	
	$table.="<tr>
				<td><strong>Numero: </strong>".$pol->id."</td>
					<td><strong>Poliza: </strong>".$pol->Get_folio_poliza()." <strong>Cons: </strong>".$pol->cons."</td>
							<td colspan= '4'></td>
			</tr>";
	
			if ($pol->societe_type == 1) {
				//Es un Cliente
				$f->fetch($pol->fk_facture);
				$facnumber = $f->ref;
				$pagina = "/compta/facture.php";
			} else if($pol->societe_type == 2) {
				//Es un Proveedor
				$ff->fetch($pol->fk_facture);
				$facnumber = $ff->ref;
				$pagina = "/fourn/facture/fiche.php";
			}
			$sql2="SELECT rowid
				FROM ".MAIN_DB_PREFIX."contab_polizasdet
				WHERE fk_poliza=".$pol->id;
			$rest2=$db->query($sql2);
			while ($fg2=$db->fetch_object($rest2)) {
				$pd = new Contabpolizasdet($db);
				$pd->fetch($fg2->rowid);
				/*Cabecera*/
				$nom_soc = "";
				//Verificar primeramente si se trata de un artículo
				if (!$ctas->fetch_by_Cta($pd->cuenta, false)) {
					if ($pol->societe_type == 1) {
						if ($soc->fetch($f->socid)) {
							$nom_soc = $soc->nom;
						}
					} else if ($pol->societe_type == 2) {
						if ($soc->fetch($ff->socid)) {
							$nom_soc = $soc->nom;
						}
					}
				}
								
			if (!$nom_soc) {
				$ctas->fetch_by_Cta($pd->cuenta);
				$nom_soc = $ctas->descta;
			}

			$table.="<tr>";
			if ($pd->debe != 0) {
				$table.='<td>'.$pd->cuenta.'</td>
						<td colspan="3">'.$nom_soc.'</td>
						<td style="text-align: right;">'.($pd->debe <> 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format(abs($pd->debe), 2) : "").'</td>
						<td>&nbsp;</td>';
			} else if($pd->haber != 0){
				$table.='<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$pd->cuenta.'</td>
						<td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nom_soc.'</td>
						<td>&nbsp;</td>
						<td style="text-align: right;">'.($pd->haber <> 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format(abs($pd->haber), 2) : "").'</td>';
			}
			$table.='</tr>';
							
							unset($pd);
		}
						
	$table.='<tr>
				<td><strong>Concepto: </strong></td><td colspan="5">'.$pol->concepto.'</td>
					</tr>
					<tr>
						<td><strong>Comentario: </strong></td><td colspan="5">'.$pol->comentario.'</td>
					</tr>
					<tr>
						<td colspan="6" style="text-align: center;">&nbsp;&nbsp;</td>
					</tr>';
	
}  

$table.='</table>';
print $table;

