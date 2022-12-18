<?php
/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jfarber55@hotmail.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * 
 * code pour cr�er le module 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
 */

/**
 *   	\file       dev/Contabpolizass/Contabpolizas_page.php
 *		\ingroup    mymodule othermodule1 othermodule2
 *		\brief      This file is an example of a php page
 *					Initialy built by build_class_from_table on 2015-02-26 02:24
 */

//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)

// Change this following line to use the correct relative path (../, ../../, etc)

date_default_timezone_set("America/Mexico_City");

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

//if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/facture.class.php')) {
//	require_once DOL_DOCUMENT_ROOT.'/contab/class/facture.class.php';
//} else {
//	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/facture.class.php';
//}

//if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/fournisseur.facture.class.php')) {
//	require_once DOL_DOCUMENT_ROOT.'/contab/class/fournisseur.facture.class.php';
//} else {
//	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/fournisseur.facture.class.php';
//}

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
	$anio = date('Y');
	$mes = date('m');
}

$fecha = date("y-m-d h:i:s");

dol_syslog("anio=$anio, mes=$mes,  action = ".$action.", id=$id idpd=$idpd--------------- esfac=$esfaccte, $esfacprov");
/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

$form = new Form($db);

/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

//$arrayofjs = array('../js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

llxHeader('','','','','','','','',0,0);
$facn='';
print '<script> 
		function deleteFile(obj) {
			if (confirm("¿Esta seguro de eliminar el archivo "+obj.className+" ?")) {
				// Create our XMLHttpRequest object
				var hr = new XMLHttpRequest();
				// Create some variables we need to send to our PHP file
				var url = "delete_file.php";
				var id = obj.id;
				var vars = "id="+id;
				hr.open("POST", url, true);
				hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				// Access the onreadystatechange event for the XMLHttpRequest object
				hr.onreadystatechange = function() {
					if(hr.readyState == 4 && hr.status == 200) {
						var return_data = hr.responseText;
						alert(return_data);
						location.reload(true);
					}
				}
				hr.send(vars); 
				// Actually execute the request
			}
		}
		</script>';
if ($esfaccte == 1) {
	dol_syslog("Es FacCte y quiero ver cuanto vale el id = ".$id);
	$object = new Facture($db);
	$object->fetch($facid,$ref);
	$facn=$object->ref;
	$paginar = "/compta/facture.php";
	$statuts="";
	$sql = 'SELECT pf.amount';
	$sql .= ' FROM ' . MAIN_DB_PREFIX . 'paiement_facture as pf';
	$sql .= ' WHERE pf.fk_facture = ' . $object->id;
	$result = $db->query($sql);
	if ($result) {
		$ii = 0;
		$num = $db->num_rows($result);
		while ($ii < $num) {
			$objp = $db->fetch_object($result);
			$totalpaye += $objp->amount;
			$ii ++;
		}
	}
	$statuts="<br><strong>Estatus: </strong>";
	$statuts.=$object->getLibStatut(4, $totalpaye);
	$statuts.=" <strong>Importe total: </strong>".$langs->getCurrencySymbol($conf->currency).' '.number_format($object->total_ttc,2);
	$head = facture_prepare_head($object);
	dol_fiche_head($head, "tabcustpolizas", $langs->trans("InvoiceCustomer"), 0);
} else if ($esfacprov == 1){
	$object = new FactureFournisseur($db);
	$object->fetch($facid,$ref);
	$facn=$object->ref;
	$paginar = "/fourn/facture/fiche.php";
	if(DOL_VERSION>="3.7"){
		$paginar = "/fourn/facture/card.php";
	}
	$alreadypaid=$object->getSommePaiement();
	$statuts="<br><strong>Estatus: </strong>";
	$statuts.=$object->getLibStatut(4, $alreadypaid);
	$statuts.=" <strong>Importe total: </strong>".$langs->getCurrencySymbol($conf->currency).' '.number_format($object->total_ttc,2);
    $result=$object->fetch_thirdparty();
    if ($result < 0) dol_print_error($db);

    $head = facturefourn_prepare_head($object);
    dol_fiche_head($head, "tabsuppolizas", $langs->trans('SupplierInvoice'), 0);
} else if ($socid > 0) {
	$object = new Societe($db);
	$object->fetch($socid);
	
	//if (! empty($conf->notification->enabled)) $langs->load("mails");
	
	$head = societe_prepare_head($object);
	//dol_fiche_head($head, "tabthirdpol", $langs->trans("ThirdParty"), 0);
	dol_fiche_head($head, 'tabthirdpol', $langs->trans("ThirdParty"), 0 ,'company');
	
	print '<table class="border" width="100%">';
	
	// Name
	print '<tr><td width="25%">'.$langs->trans('ThirdPartyName').'</td>';
	print '<td colspan="3">';
	print $form->showrefnav($object, 'socid', '', ($user->societe_id?0:1), 'rowid', 'nom');
	print '</td>';
	print '</tr>';
	
	// Logo+barcode
	$rowspan=6;
	if (! empty($conf->global->SOCIETE_USEPREFIX)) $rowspan++;
	if (! empty($object->client)) $rowspan++;
	if (! empty($conf->fournisseur->enabled) && $object->fournisseur && ! empty($user->rights->fournisseur->lire)) $rowspan++;
	if (! empty($conf->barcode->enabled)) $rowspan++;
	if (empty($conf->global->SOCIETE_DISABLE_STATE)) $rowspan++;
	$htmllogobar='';
	if ($showlogo || $showbarcode)
	{
		$htmllogobar.='<td rowspan="'.$rowspan.'" style="text-align: center;" width="25%">';
		if ($showlogo)   $htmllogobar.=$form->showphoto('societe',$object);
		if ($showlogo && $showbarcode) $htmllogobar.='<br><br>';
		if ($showbarcode) $htmllogobar.=$form->showbarcode($object);
		$htmllogobar.='</td>';
	}
	
	// Prefix
	if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
	{
		print '<tr><td>'.$langs->trans('Prefix').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">'.$object->prefix_comm.'</td>';
		print $htmllogobar; $htmllogobar='';
		print '</tr>';
	}
	
	// Customer code
	if ($object->client)
	{
		print '<tr><td>';
		print $langs->trans('CustomerCode').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">';
		print $object->code_client;
		if ($object->check_codeclient() <> 0) print ' <font class="error">('.$langs->trans("WrongCustomerCode").')</font>';
		print '</td>';
		print $htmllogobar; $htmllogobar='';
		print '</tr>';
	}
	
	// Supplier code
	if (! empty($conf->fournisseur->enabled) && $object->fournisseur && ! empty($user->rights->fournisseur->lire))
	{
		print '<tr><td>';
		print $langs->trans('SupplierCode').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">';
		print $object->code_fournisseur;
		if ($object->check_codefournisseur() <> 0) print ' <font class="error">('.$langs->trans("WrongSupplierCode").')</font>';
		print '</td>';
		print $htmllogobar; $htmllogobar='';
		print '</tr>';
	}
	
	print "</table>";
	
	print "<br>";
	print "<strong>Contabilidad Electronica</strong>";
	print "<br>";
	
} else {
	$head = contab_prepare_head($object, $user);
	dol_fiche_head($head, "Polizas", 'Contabilidad', 0, '');
}
if($user->rights->contab->conspol){
//dol_fiche_end();
if($facn!=''){
	print 'Polizas de la factura: <a href="'.DOL_MAIN_URL_ROOT.$paginar.'?facid='.$facid.'">'.$facn.'</a>';
	print '&nbsp;&nbsp;&nbsp; '.$statuts;
}
dol_syslog("Datos de configuraci�n: esfacprov=$esfacprov, esfaccte=$esfaccte, ".$per->anio." ".$per->mes." ".$per->MesToStr($per->mes)." facid=$facid");

if (! $action) {
?>
	<form>
		<h4>Presentacion, Alta, Captura y Edicion de Polizas</h4>
		<input type="hidden" id="cambio_fecha" name="cambio_fecha" value="1" />
<?php 
	if (! ($esfaccte == 1 || $esfacprov == 1 || $socid > 0)) {
		$sel1 = "<select name='anio' id='anio' onchange='this.form.submit();' >";
		$res = $db->query("Select anio From ".MAIN_DB_PREFIX."contab_polizas where entity=".$conf->entity." Group by anio DESC");
		$sel1 .= '<option value="" >Seleccione</option>';
		while ($obj = $db->fetch_object($res)) {
			$sel1 .= "<option value='".$obj->anio."'";
			if ($obj->anio == $anio && isset($_GET['anio'])) {
				$sel1 .= " selected='selected' ";
			}
			$sel1 .= ">".$obj->anio."</option>";
		}
		/* $sel1 .= "<option value='2014'";
		if ($anio == 2014) {
			$sel1.=" selected='selected' ";
		}
		$sel1.=">2014</option>"; */
		$sel1 .= "</select>";
		$sel2 = "<select name='mes' id='mes' onchange='this.form.submit();' >";
		$sel2.= "<option value='0'>Mes...</option>";
		$res = $db->query("Select mes from ".MAIN_DB_PREFIX."contab_polizas WHERE anio = $anio AND entity=".$conf->entity." Group by mes DESC ");
		if ($res) {
			while ($row = $db->fetch_object($res)) {
				$sel2 .= "<option value='".$row->mes."'";
				if ($row->mes == $mes) {
					$sel2 .= " selected='selected' ";
				}
				$sel2 .= ">".$per->MesToStr3($row->mes)."</option>";
			}
		}
		$sel2 .= "</select>";
?>
		<h4>Periodo contable: <?=$sel1." - ".$sel2;?></h4>
		<a href="pol_sin_per.php" style="text-decoration: underline;"><img src='<?="../images/lupa.png";?>' height='11px' width='11px'>Mostrar polizas que se encuentren fuera de algun periodo valido.</a><br><br>
<?php 
	}
?>
	</form>
<?php
}

dol_syslog("1. DATOS = esfacprov=$esfacprov, esfaccte=$esfaccte");

?>
<a href="poliza.php?action=newpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><button>Nueva Poliza</button></a>
<?php
/*MV*/
if ($esfaccte == 1) { 
	$facm = new Facture($db);
	$facm->fetch(GETPOST("facid"));
	/* $sqlm = "SELECT f.rowid, s.nom, f.facnumber, f.datef, b.dateo, pf.amount, pa.code, pa.libelle, pf.rowid as paimid ";
	$sqlm .= " FROM ".MAIN_DB_PREFIX."facture as f ";
	$sqlm .= " INNER JOIN ".MAIN_DB_PREFIX."paiement_facture as pf ON f.rowid = pf.fk_facture ";
	$sqlm .= " INNER JOIN ".MAIN_DB_PREFIX."societe as s ON f.fk_soc = s.rowid ";
	$sqlm .= " INNER JOIN ".MAIN_DB_PREFIX."paiement as pai ON pf.fk_paiement = pai.rowid ";
	$sqlm .= " INNER JOIN ".MAIN_DB_PREFIX."bank as b on pai.fk_bank = b.rowid ";
	$sqlm .= " INNER JOIN ".MAIN_DB_PREFIX."c_paiement pa ON pai.fk_paiement = pa.id ";
	$sqlm .= " LEFT JOIN (Select * From ".MAIN_DB_PREFIX."contab_polizas Where societe_type = 1) as cp ON f.rowid = cp.fk_facture ";
	$sqlm .= " WHERE f.entity = ".$conf->entity." AND cp.rowid is null AND f.rowid=".$facm->id." ";  // AND f.paye = 1 AND f.fk_statut = 2
	$sqlm .= " ORDER BY f.facnumber ";  */
	$sqlm="SELECT f.rowid, s.nom, f.facnumber, f.datef, b.dateo, pf.amount, pa.code, pa.libelle, pf.rowid as paimid
		 FROM ".MAIN_DB_PREFIX."facture as f
     		LEFT JOIN ".MAIN_DB_PREFIX."paiement_facture as pf ON f.rowid = pf.fk_facture
     		INNER JOIN ".MAIN_DB_PREFIX."societe as s ON f.fk_soc = s.rowid
     		LEFT JOIN ".MAIN_DB_PREFIX."paiement as pai ON pf.fk_paiement = pai.rowid
     		LEFT JOIN ".MAIN_DB_PREFIX."bank as b on pai.fk_bank = b.rowid
     		LEFT JOIN ".MAIN_DB_PREFIX."c_paiement pa ON pai.fk_paiement = pa.id
     		LEFT JOIN (Select a.*,debe From ".MAIN_DB_PREFIX."contab_polizas a,".MAIN_DB_PREFIX."contab_polizasdet
          		 Where societe_type = 1  AND tipo_pol='I' AND a.rowid=fk_poliza AND debe!=0 )
				as cp ON f.rowid = cp.fk_facture AND cp.debe=pf.amount
		WHERE f.entity = ".$conf->entity." AND cp.rowid is NULL AND f.rowid=".$facm->id." AND (f.fk_statut=1 || f.fk_statut=2) ORDER BY f.facnumber";
	//print $sqlm;
	$rm=$db->query($sqlm);
	$rsm=$db->num_rows($rm);
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php')) {
		require_once DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php';
	} else {
		require_once DOL_DOCUMENT_ROOT.'/custom/contab/admin/Configuration.class.php';
	}
	$config = new Configuration($db);
	$cond_pago = $config->getCondiciones_de_Pago();
	$name = "cond_pago_".$facm->cond_reglement_id;
	$cp = $cond_pago[$name];
	$sqlv="SELECT sum(amount) as total
							FROM ".MAIN_DB_PREFIX."paiement_facture
							WHERE fk_facture=".$facm->id;
	$rv=$db->query($sqlv);
	$rsv=$db->fetch_object($rv);
	if(($rsm>0 && $facm->statut!=1)||($cp==2 && $rsv->total>0 && $facm->cond_reglement_id==7)){
		print '<a href="'.$_SERVER["PHP_SELF"].'?fc=1&action=simuladorcli&tf=1&facid='.$facid.'"><button>Contabilizar</button></a>';
	}else{
		$objectm = new Facture($db);
		$objectm->fetch($facid,$ref);
		$statuts=$objectm->statut;
		$cond_pago = 1;
		$payment = new Contabpaymentterm($db);
		$payment->fetch_by_cond_reglement($objectm->cond_reglement_id);
		if ($payment->cond_pago) {
			$cond_pago = $payment->cond_pago;
		}
		//print $objectm->cond_reglement_id."::".$payment::PAGO_A_CREDITO."::".$cond_pago;
		$sqlp="SELECT count(*) as exist
			FROM ".MAIN_DB_PREFIX."contab_polizas
			WHERE fk_facture=".$facid." AND societe_type=1";
		$resultl = $db->query($sqlp);
		$resqm = $db->fetch_object($resultl);
		if($resqm->exist==0 && $statuts==1 && ($cond_pago == $payment::PAGO_A_CREDITO || $cond_pago ==  $payment::PAGO_EN_PARTES)){
			print '<a href="'.$_SERVER["PHP_SELF"].'?fc=1&action=simuladorcli2&tf=1&facid='.$facid.'"><button>Generar Poliza de Diario</button></a>';
		}
	}
} else if ($esfacprov == 1) { 
	$facm = new FactureFournisseur($db);
	$facm->fetch(GETPOST("facid"));
	$sqlm="SELECT f.rowid, s.nom, f.ref, f.datef, b.dateo, pf.amount, pa.code, pa.libelle, pf.rowid as paimid 
		FROM ".MAIN_DB_PREFIX."facture_fourn as f 
		     INNER JOIN ".MAIN_DB_PREFIX."paiementfourn_facturefourn as pf ON f.rowid = pf.fk_facturefourn 
		     INNER JOIN ".MAIN_DB_PREFIX."societe as s ON f.fk_soc = s.rowid 
		     INNER JOIN ".MAIN_DB_PREFIX."paiementfourn as pai ON pf.fk_paiementfourn = pai.rowid 
		     INNER JOIN ".MAIN_DB_PREFIX."bank as b on pai.fk_bank = b.rowid 
		     INNER JOIN ".MAIN_DB_PREFIX."c_paiement pa ON pai.fk_paiement = pa.id 
		     LEFT JOIN (Select * From ".MAIN_DB_PREFIX."contab_polizas Where societe_type = 2) as cp ON f.rowid = cp.fk_facture 
		WHERE f.entity = ".$conf->entity." AND cp.rowid is null AND f.rowid=".$facm->id." ORDER BY f.ref";
	$rm=$db->query($sqlm);
	$rsm=$db->num_rows($rm);
	if($rsm>0){
		print '<a href="'.$_SERVER["PHP_SELF"].'?fp=1&action=simuladorprov&tf=2&facid='.$facid.'"><button>Contabilizar</button></a>';
	}else{
		$objectm =new FactureFournisseur($db);
		$objectm->fetch($facid,$ref);
		$statuts=$objectm->statut;
		$cond_pago = 1;
		$payment = new Contabpaymentterm($db);
		$payment->fetch_by_cond_reglement($objectm->cond_reglement_id);
		if ($payment->cond_pago) {
			$cond_pago = $payment->cond_pago;
		}
		//print $objectm->cond_reglement_id."::".$payment::PAGO_A_CREDITO."::".$cond_pago;
		$sqlp="SELECT count(*) as exist
			FROM ".MAIN_DB_PREFIX."contab_polizas
			WHERE fk_facture=".$facid." AND societe_type=2";
		$resultl = $db->query($sqlp);//
		$resqm = $db->fetch_object($resultl);
		if($resqm->exist==0 &&$statuts==1 && ($cond_pago == $payment::PAGO_A_CREDITO || $cond_pago ==  $payment::PAGO_EN_PARTES)){
			print '<a href="'.$_SERVER["PHP_SELF"].'?fp=1&action=simuladorprov2&tf=2&facid='.$facid.'"><button>Generar Poliza de Diario</button></a>';
		}
	}
}
if ($action == "simuladorcli") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$error = 0;
	$fac = new Facture($db);
	$fac->fetch(GETPOST("facid"));
	if ($fac->type != $fac::TYPE_CREDIT_NOTE) {
		if ($fac->cond_reglement_id == 0) {
			$errors = "La Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
			$error ++;
		}
	}
	if ($error == 0) {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		print '<p></p>
				<div>
				<div style="width:900px; border:solid 1px; background-color:#FFC; padding-top:10px, color:#C00">
				<strong><table><tr><td>Se generara la poliza con la siguiente afectacion de cuentas.</td>
				<td></td>
				<td></td></tr></table><br></strong><div style="padding-right: 10px; padding-left: 10px;">';
		$err = $pg->Simulacion_Polizas_Clientes($user);
		print '</div><p><strong><table><tr><td><strong>Desea continuar?</strong></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fc=1&facid='.GETPOST("facid").'&action=proc_onecli&tf=1"><input type="submit" value="Si"></form></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fc=1&facid='.GETPOST("facid").'"><input type="submit" value="No"></form></td></tr></table><br></strong></p>';
		print '
				</div></div><p></p>';
		if ($err >=0 ) {
			//$mesg = "La(s) Poliza(s) relacionada(s) con la Factura: ".$fac->ref.", Fue(ron) generada(s).";
		} else if ($err == -1) {
			$errors = "La factura de Pago de Anticipo debe estar especificada con Condicion de Pago Al Contado";
		}
	}
}
if ($action == "simuladorcli2") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$error = 0;
	$fac = new Facture($db);
	$fac->fetch(GETPOST("facid"));
	if ($fac->type != $fac::TYPE_CREDIT_NOTE) {
		if ($fac->cond_reglement_id == 0) {
			$errors = "La Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
			$error ++;
		}
	}
	if ($error == 0) {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		print '<p></p>
				<div>
				<div style="width:900px; border:solid 1px; background-color:#FFC; padding-top:10px, color:#C00">
				<strong><table><tr><td>Se generara la poliza con la siguiente afectacion de cuentas.</td>
				<td></td>
				<td></td></tr></table><br></strong><div style="padding-right: 10px; padding-left: 10px;">';
		$pol = new Contabpolizas($db);
		$err = $pol->Simula_Venta_a_Credito2($fac->id, $user, $conf);
		//$err = $pg->Simulacion_Polizas_Clientes($user);
		print '</div><p><strong><table><tr><td><strong>Desea continuar?</strong></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fc=1&facid='.GETPOST("facid").'&action=proc_onecli2&tf=1"><input type="submit" value="Si"></form></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fc=1&facid='.GETPOST("facid").'"><input type="submit" value="No"></form></td></tr></table><br></strong></p>';
		print '
				</div></div><p></p>';
		if ($err >=0 ) {
			//$mesg = "La(s) Poliza(s) relacionada(s) con la Factura: ".$fac->ref.", Fue(ron) generada(s).";
		} else if ($err == -1) {
			$errors = "La factura de Pago de Anticipo debe estar especificada con Condicion de Pago Al Contado";
		}
	}
}
if ($action == "simuladorprov") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$facm = new FactureFournisseur($db);
	$facm->fetch(GETPOST("facid"));
	if ($facm->cond_reglement_id == 0) {
		$errors = "A la Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
		$error ++;
	} else {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		print '<p></p>
				<div>
				<div style="width:800px; border:solid 1px; background-color:#FFC; padding-top:10px, color:#C00">
				<strong><table><tr><td>Se generara la poliza con la siguiente afectacion de cuentas.</td>
				<td></td>
				<td></td></tr></table><br></strong><div style="padding-right: 10px; padding-left: 10px;">';
		$pg->Simular_Crear_Polizas_Proveedores($user);
		print '</div><p><strong><table><tr><td><strong>Desea continuar?</strong></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fp=1&facid='.GETPOST("facid").'&action=proc_oneprov&tf=2"><input type="submit" value="Si"></form></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fp=1&facid='.GETPOST("facid").'"><input type="submit" value="No"></form></td></tr></table></strong></p>';
		print '
				</div></div><p></p>';
	}
}
if ($action == "simuladorprov2") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$facm = new FactureFournisseur($db);
	$facm->fetch(GETPOST("facid"));
	if ($facm->cond_reglement_id == 0) {
		$errors = "A la Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
		$error ++;
	} else {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		print '<p></p>
				<div>
				<div style="width:800px; border:solid 1px; background-color:#FFC; padding-top:10px, color:#C00">
				<strong><table><tr><td>Se generara la poliza con la siguiente afectacion de cuentas.</td>
				<td></td>
				<td></td></tr></table><br></strong><div style="padding-right: 10px; padding-left: 10px;">';
		//$pg->Simular_Crear_Polizas_Proveedores($user);
				$pol = new Contabpolizas($db);
				$pol->Simular_Proveedor_Compra_a_Credito2($facm->id, $user, $conf);
		print '</div><p><strong><table><tr><td><strong>Desea continuar?</strong></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fp=1&facid='.GETPOST("facid").'&action=proc_oneprov2&tf=2"><input type="submit" value="Si"></form></td>
				<td><form method="POST" action="'.$_SERVER["PHP_SELF"].'?fp=1&facid='.GETPOST("facid").'"><input type="submit" value="No"></form></td></tr></table></strong></p>';
		print '
				</div></div><p></p>';
	}
}
if ($action == "proc_onecli") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$error = 0;
	$fac = new Facture($db);
	$fac->fetch(GETPOST("facid"));
	if ($fac->type != $fac::TYPE_CREDIT_NOTE) {
		//dol_syslog($fac->id."::TIPO::".$fac->cond_reglement_id." - ".$fac->fk_cond_reglement);
		if ($fac->cond_reglement_id == 0) {
			$errors = "La Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
			$error ++;
		}
	}
	if ($error == 0) {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		$err = $pg->Crear_Polizas_Clientes($user);
		//var_dump($err);
		if ($err >=0 ) {
			$mesg = "La(s) Poliza(s) relacionada(s) con la Factura: ".$fac->ref.", Fue(ron) generada(s).";
		} else if ($err == -1) {
			$errors = "La factura de Pago de Anticipo debe estar especificada con Condicion de Pago Al Contado";
		}
		print "<script>window.location = 'fiche.php?fc=1&facid=".$fac->id."';</script>";
	}
}
if ($action == "proc_onecli2") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$error = 0;
	$fac = new Facture($db);
	$fac->fetch(GETPOST("facid"));
	if ($fac->type != $fac::TYPE_CREDIT_NOTE) {
		//dol_syslog($fac->id."::TIPO::".$fac->cond_reglement_id." - ".$fac->fk_cond_reglement);
		if ($fac->cond_reglement_id == 0) {
			$errors = "La Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
			$error ++;
		}
	}
	if ($error == 0) {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		$pol = new Contabpolizas($db);
		$err = $pol->Venta_a_Credito2($fac->id, $user, $conf);
		//$err = $pg->Crear_Polizas_Clientes($user);
		//var_dump($err);
		if ($err >=0 ) {
			$mesg = "La(s) Poliza(s) relacionada(s) con la Factura: ".$fac->ref.", Fue(ron) generada(s).";
		} else if ($err == -1) {
			$errors = "La factura de Pago de Anticipo debe estar especificada con Condicion de Pago Al Contado";
		}
		print "<script>window.location = 'fiche.php?fc=1&facid=".$fac->id."';</script>";
	}
}
if ($action == "proc_oneprov") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$facm = new FactureFournisseur($db);
	$facm->fetch(GETPOST("facid"));
	if ($facm->cond_reglement_id == 0) {
		$errors = "A la Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
		$error ++;
	} else {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		$pg->Crear_Polizas_Proveedores($user);
		$mesg = "La(s) Poliza(s) relacionada(s) con la Factura: ".$facm->ref.", Fue(ron) generada(s).";
		print "<script>window.location = 'fiche.php?fp=1&facid=".$facm->id."';</script>";
	}
}
if ($action == "proc_oneprov2") {
	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php')) {
		include_once DOL_DOCUMENT_ROOT.'/contab/class/poliza_generator.class.php';
	} else {
		include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/poliza_generator.class.php';
	}
	$facm = new FactureFournisseur($db);
	$facm->fetch(GETPOST("facid"));
	if ($facm->cond_reglement_id == 0) {
		$errors = "A la Factura, al momento de ser generada, no se le especifico una Condicion de Pago (Contado, Credito, 50/50, etc).";
		$error ++;
	} else {
		$pg = new PolizaGenerator($db);
		$pg->facid = GETPOST("facid");
		$pg->tipo_fac = GETPOST("tf");
		//$pg->Crear_Polizas_Proveedores($user);
		$pol = new Contabpolizas($db);
		$pol->Proveedor_Compra_a_Credito2($facm->id, $user, $conf);
		$mesg = "La(s) Poliza(s) relacionada(s) con la Factura: ".$facm->ref.", Fue(ron) generada(s).";
		print "<script>window.location = 'fiche.php?fp=1&facid=".$facm->id."';</script>";
	}
}
dol_htmloutput_mesg($mesg);
dol_htmloutput_errors($errors);
dol_htmloutput_events();
/*MV*/
if(GETPOST('numpoli')!='' && GETPOST('numpolifin')!=''){
	$numpoli=GETPOST('numpoli');
	$numpolifin=GETPOST('numpolifin');
}else{
	$numpoli='';
	$numpolifin='';
}
if(GETPOST('fecini') && GETPOST('fecfin')){
	$fecini=GETPOST('fecini');
	$fecfin=GETPOST('fecfin');
}else{
	$fecini='';
	$fecfin='';
}
if($esfaccte!=1 && $esfacprov!=1 && $socid==''){
	print '<a href="fiche_print.php?tipo=excel&anio='.$anio.'&mes='.$mes.'&filtro='.$_REQUEST['filtro'].'&filt='.$_REQUEST['filt'].'&fecini='.$fecini.'&fecfin='.$fecfin.'&numpoli='.$numpoli.'&numpolifin='.$numpolifin.'" target="popup"><button>Descargar Excel</button></a> ';
	print '<a href="fiche_print.php?tipo=pdf&anio='.$anio.'&mes='.$mes.'&filtro='.$_REQUEST['filtro'].'&filt='.$_REQUEST['filt'].'&fecini='.$fecini.'&fecfin='.$fecfin.'&numpoli='.$numpoli.'&numpolifin='.$numpolifin.'" target="popup"><button>Descargar PDF</button></a> ';
	print ' <a href="fiche.php?anio='.$anio.'&mes='.$mes.'&filt=fac" ><button>Filtrar por Factura</button></a>';
}
if(1){
//if($esfaccte!=1 && $esfacprov!=1 && $socid==''){
	if($_REQUEST['filtro']){
		$filtro=$_REQUEST['filtro'];
		if($filtro==1){
			$a1=" SELECTED";
			$a2="";
			$a3="";
			$a4="";
			$a5="";
			$a6="";
		}else{
			if($filtro==2){
				$a1="";
				$a2=" SELECTED";
				$a3="";
				$a4="";
				$a5="";
				$a6="";
			}else{
				if($filtro==3){
					$a1="";
					$a2="";
					$a3=" SELECTED";
					$a4="";
					$a5="";
					$a6="";
				}else{
					if($filtro==4){
						$a1="";
						$a2="";
						$a3="";
						$a4=" SELECTED";
						$a5="";
						$a6="";
					}else{
						if($filtro==5){
							$a1="";
							$a2="";
							$a3="";
							$a4="";
							$a5=" SELECTED";
							$a6="";
						}else{
							if($filtro==6){
								$a1="";
								$a2="";
								$a3="";
								$a4="";
								$a5="";
								$a6=" SELECTED";
							}
						}
					}
				}
			}
		}
	}else{
		$a1=" SELECTED";
		$a2="";
		$a3="";
		$a4="";
		$a5="";
		$a6="";
	}
	if($esfaccte!=1 && $esfacprov!=1 && $socid==''){
		print '<form action="?anio='.$anio.'&mes='.$mes.'" method="POST">';
	}
	if($esfaccte==1 ){
		print '<form action="?fc=1&facid='.$facid.'" method="POST">';
	}
	if($esfacprov==1){
		print '<form action="?fp=1&facid='.$facid.'" method="POST">';
	}
	if($socid>0){
		print '<form action="?socid='.$socid.'" method="POST">';
	}
	
	print '		<br><select name="filtro">
				<option value="1" '.$a1.'>Todas</option>
				<option value="2" '.$a2.'>Ingreso</option>
				<option value="3" '.$a3.'>Diario</option>
				<option value="6" '.$a6.'>Diario sin movimientos</option>
				<option value="4" '.$a4.'>Cheques</option>
				<option value="5" '.$a5.'>Egreso</option>
			</select> &nbsp;&nbsp;Numero Poliza inicial:<input type="text" name="numpoli" value="'.$numpoli.'"> 
				 &nbsp;&nbsp;Numero Poliza final:<input type="text" name="numpolifin" value="'.$numpolifin.'">
				<br>Fecha inicio: <input type="date" name="fecini" placeholder="yyyy-mm-dd" value="'.$fecini.'">
                Fecha fin: <input type="date" name="fecfin"  placeholder="yyyy-mm-dd" value="'.$fecfin.'">
				<input type="submit" value="Filtrar" class="button">
			</form>';
} 
?>
<br>
	
	<br>
	<input name="id" id="id" type="hidden" value="<?php print $id; ?>" >
<?php 
	if ($esfaccte == 1) { 
?>
		<input type="hidden" name="fc" value="<?=$esfaccte?>" />
		<input type="hidden" name="facid" value="<?=$facid;?>" /> 
<?php 
	} else if ($esfacprov == 1) { 
?>
		<input type="hidden" name="fp" value="<?=$esfacprov?>" />
		<input type="hidden" name="facid" value="<?=$facid;?>" /> 
<?php 
	} else if($socid > 0) {
?>
		<input type="hidden" name="socid" value="<?=$socid;?>" />
<?php 
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
?>
		<table class="noborder" style="width:100%">
		<tr class="liste_titre">
			<td colspan="4">Encabezado de la Poliza</td>
			<td style="text-align: right;">&nbsp;</td>
			<td style="text-align: right;">
				<a href="poliza.php?action=newpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Nueva Poliza</a>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="poliza.php?id=<?=$pol->id; ?>&amp;action=delpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Borrar Poliza</a>
			</td>
		</tr>
	</table>
<?php 
	}
	if($row > 0){
		if (GETPOST('fc') || GETPOST('fp')) {
			$typ=0;
			if(GETPOST('fc')){$typ=1;}
			if(GETPOST('fp')){$typ=2;}
			$sql="SELECT rowid
				FROM ".MAIN_DB_PREFIX."contab_polizas
				WHERE fk_facture=".$facid." AND societe_type=".$typ;
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
										WHERE fk_facture=".$facid." AND societe_type=".$typ." AND entity=".$conf->entity." AND tipo_pol='D'
										AND contar=1 AND fk_facture=facdoc AND societe_type=soctyp";
									}
								}
							}
						}
					}
				}
			}
			$rqs=$db->query($sql);
		} else if($socid > 0) {
			$sql="SELECT rowid
			FROM ".MAIN_DB_PREFIX."contab_polizas
			WHERE ((fk_facture IN (SELECT f.rowid FROM ".MAIN_DB_PREFIX."facture f WHERE f.fk_soc = ".$socid." ) 
					AND societe_type=1) 
			    OR (fk_facture IN (SELECT ff.rowid FROM ".MAIN_DB_PREFIX."facture_fourn ff WHERE ff.fk_soc = ".$socid." ) 
					AND societe_type=2)) ";
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
										WHERE ((fk_facture IN (SELECT f.rowid FROM ".MAIN_DB_PREFIX."facture f WHERE f.fk_soc = ".$socid." ) 
										AND societe_type=1) 
									    OR (fk_facture IN (SELECT ff.rowid FROM ".MAIN_DB_PREFIX."facture_fourn ff WHERE ff.fk_soc = ".$socid." ) 
										AND societe_type=2)) AND entity=".$conf->entity." AND tipo_pol='D'
										AND contar=1 AND fk_facture=facdoc AND societe_type=soctyp";
									}
								}
							}
						}
					}
				}
			}
			$rqs=$db->query($sql);
		} else {
			if($fecini!='' && $fecfin!=''){
				$sql="SELECT rowid
				FROM ".MAIN_DB_PREFIX."contab_polizas
				WHERE fecha between '".$fecini."' AND '".$fecfin."' AND entity=".$conf->entity." ";
			}else{
				$sql="SELECT rowid
				FROM ".MAIN_DB_PREFIX."contab_polizas
				WHERE anio=".$anio." AND mes=".$mes." AND entity=".$conf->entity." ";
			}
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
			//print $sql;
			$rqs=$db->query($sql);
		}	
	unset($pol);
	while ($rqm=$db->fetch_object($rqs)) { // = $db->fetch_array(rs)) {
		$pol = new Contabpolizas($db);
		$pol->fetch($rqm->rowid,0);
?>
		<table class="noborder" style="width:100%">
		<tr class="liste_titre">
			<td colspan="2">Encabezado de la Poliza</td>
			<td style="text-align: right;">
				<a href="print.php?tipo=excel&id=<?=$pol->id;?>" target="popup">
					Descargar Excel
				</a>
			</td>
			<td  style="text-align: right;">
				<a href="print.php?tipo=pdf&id=<?=$pol->id;?>" target="popup">
					Descargar PDF
				</a>
			</td>
			<td style="text-align: right;">
			<a href="rec.php?id=<?=$pol->id; ?>&action=addnewrec" target="_blank">Convertir en Recurrente</a>
			</td>
			<td style="text-align: right;">
				<a href="addcuenta.php?tpenvio=fichepol&anio=<?=$anio?>&mes=<?=$mes?>" >Agregar cuenta</a>
			</td>
			<td style="text-align: right;">
				<a href="poliza.php?id=<?=$pol->id; ?>&amp;action=delpol<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Borrar Poliza</a>
			</td>
		</tr>
<?php 
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
			}
			else{
				$facnumber='';
			}
?>			
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "2">
					Poliza:
					<strong> 
<?php 
				//	print $pol->Get_Tipo_Poliza_Desc().": ".$c;
					print $pol->Get_folio_poliza()." Cons: ".$c;
?>
					</strong>
					<a href="poliza.php?id=<?=$pol->id; ?>&action=editenc<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_edit(); ?></a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<!-- <a href="poliza.php?action=filterfac<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_view("Filtrar por Factura"); ?></a> -->
				</td>
				<td colspan = "2">Fecha: <?php print date("Y-m-d",$pol->fecha);?></td>
				<td colspan = "2">
					Documento Relacionado: <a href="<?=DOL_URL_ROOT.$pagina;?>?facid=<?=$facid;?>"><?php echo $facnumber; ?></a>
				</td>
				
			</tr>
			<?php 
			if($nomsoc!=''){
				?>
				<tr <?php print $bc[$var]; ?>>
				<td colspan = "6">
					Tercero: <strong><?php echo $nomsoc; ?></strong>
				</td>
				</tr>
				<?php
			}
			?>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "4">
					Concepto: <strong><?php echo substr($pol->concepto,0,150); ?></strong>
					&nbsp;
					Comentario: <strong><?php echo substr($pol->comentario,0,150); ?></strong>
				</td>
				<td colspan = "3" >
					Archivos adjuntos:<br/>
				<?php

					$folio=$pol->Get_folio_poliza();
					
					$string= "SELECT rowid, url FROM ".MAIN_DB_PREFIX."contab_doc WHERE folio='".$folio."'";
					$que=$db->query($string);

					$docs="";
					while($re=$db->fetch_object($que)) {
						$dir = explode("/", $re->url);
						$docs=" ".$dir[3]." ";
						print "<a href='#' id='".$re->rowid."' class='".$docs."' onclick='deleteFile(this)'>".img_delete()."</a>";
						echo "<a target='_blank' href=".$re->url.">".$docs."</a><br/>";
					}
				?>
					
				</td>

			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "6">
					Cheque a Nombre: <strong><?php echo substr($pol->anombrede,0,150); ?></strong>
					&nbsp;
					Num. Cheque: <strong><?php echo substr($pol->numcheque,0,150); ?></strong>
				</td>
			</tr>
			<?php
			if($pol->pol_ajuste==1){ 
			?>
			<tr <?=$bc[$var]; ?>>
				<td colspan = "6">
					<strong>Poliza del periodo de ajuste</strong>
				</td>
			</tr>
			<?php 
			}
			?>
<?php
		}
?>
		<tr class="liste_titre">
			<td>Asiento</td>
			<td>Cuenta</td>
			<td>Concepto</td>
			<td>UUID</td>
			<td style="text-align: right; width: 10%;">Debe</td>
			<td style="text-align: right; width: 10%;">Haber</td>
			<td colspan='2' style="text-align: right;"><a href="poliza.php?id=<?=$pol->id; ?>&amp;action=newpolline<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Nuevo Asiento</a> </td>
		</tr>
<?php 
		$cond = " fk_poliza = ".$pol->id;
		$rr = $poldet->fetch_next(0, $cond);
		if ($rr) {
			$totdebe=0;
			$tothaber=0;
			while ($rr) {	
?>
				<tr <?php print $bc[$var]; ?>>
					<td><?php print $poldet->asiento; ?></td>
					<td><?php print $poldet->cuenta; 
					$nom_soc = "";
					//Verificar primeramente si se trata de un art�culo
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
						print $nom_soc;
					}else {
						$ctas->fetch_by_Cta($poldet->cuenta, false);
						print '&nbsp;&nbsp;&nbsp;'.$ctas->descta;
					}
					$totdebe+=$poldet->debe;
					$tothaber+=$poldet->haber
					?></td>
					<td><?php print $poldet->desc; ?></td>
					<td><?php print $poldet->uuid; ?></td>
					<td style="text-align: right;"><?=($poldet->debe != 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format($poldet->debe, 2) : ""); ?></td>
					<td style="text-align: right;"><?=($poldet->haber != 0 ? $langs->getCurrencySymbol($conf->currency).' '.number_format($poldet->haber, 2) : ""); ?></td>
<?php
		 			if ($poldet->asiento > 0) {
?>
						<td colspan='2' style="text-align: right;">
							<?php "fc=$esfaccte, fp=$esfacprov"?>
							<a href="poliza.php?id=<?=$pol->id;?>&idpd=<?=$poldet->id; ?>&amp;action=editline<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_edit(); ?></a>&nbsp;&nbsp;
							<a href="poliza.php?id=<?=$pol->id;?>&idpd=<?=$poldet->id; ?>&amp;action=delline<?=($esfaccte == 1 ? '&fc='.$esfaccte : '');?><?=($esfacprov == 1 ? '&fp='.$esfacprov : '');?><?=($socid > 0 ? '&socid='.$socid : '');?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?=img_delete(); ?></a>
						</td>
<?php 
					}
?>
				</tr>
				
<?php 
				 
				$i ++;
				$id = $poldet->id;
				$rr = $poldet->fetch_next($id, $cond);
			}
			?>
			<tr>
				<td colspan='3' align="right">
				<strong>Total</strong>
				</td>
				<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($totdebe, 2)?></td>
				<td style="text-align: right;"><?=$langs->getCurrencySymbol($conf->currency).' '.number_format($tothaber, 2)?></td>
			</tr>
			<?
			if ( number_format($totdebe,2) != number_format($tothaber,2) ) {
				$dif=str_replace('-','',number_format(($totdebe-$tothaber),2));
			?>
			<tr>
				<td colspan="2" align="center"></td>
				<td colspan="3" style="text-align: center; color:#FF0000">Los totales no coinciden en esta poliza por <?=$langs->getCurrencySymbol($conf->currency).' '.$dif?>, favor de verificar</td>
			</tr>
			<?
			}
			?>
			
			<?
			$sqm="SELECT a.cantmodif, a.fechahora, b.lastname, b.firstname,a.creador 
			FROM ".MAIN_DB_PREFIX."contab_polizas_log a, ".MAIN_DB_PREFIX."user b 
			WHERE fk_poliza=".$pol->id." AND a.fk_user=b.rowid ORDER BY a.fechahora DESC";
			$mrq=$db->query($sqm);
			$mnr=$db->num_rows($mrq);
			if($mnr>0){
			?>
			<tr>
			<td colspan="5">
				<table class='border'>
					<tr>
						<td>Usuario</td>
						<td>Modificaciones</td>
						<td>Fecha Ult. modificacion</td>
					</tr>
					<?php 
					while($mrs=$db->fetch_object($mrq)){
						$stro='';
						if($mrs->creador==1){
							?>
							<tr>
								<td><strong><?=$mrs->firstname." ".$mrs->lastname?></strong></td>
								<td align='center'><?=$mrs->cantmodif?></td>
								<td><?=$mrs->fechahora?></td>
							</tr>
							<?
						}else{
							?>
						<tr>
							<td><?=$mrs->firstname." ".$mrs->lastname?></td>
							<td align='center'><?=$mrs->cantmodif?></td>
							<td><?=$mrs->fechahora?></td>
						</tr>
						<?php 
						}
					}
					?>
				</table>
			</td>
			</tr>
			<?
			}
		}
		/* $id = $pol->id;
		if (GETPOST('fp') == 1 || GETPOST('fc') == 1) {
			$soc_type = ($esfaccte == 1) ? 1 : 2;
			$row = $pol->fetch_next_by_facture_id($id, $facid, $soc_type);
			dol_syslog("1. Se regresa este valor del Fetch_Next=".$row);
		}else{
			if ($esfaccte == 1 || $esfacprov == 1 && (GETPOST('fp') == 1 || GETPOST('fc') == 1)) {
				$soc_type = ($esfaccte == 1) ? 1 : 2;
				$row = $pol->fetch_next_by_facture_id($id, $facid, $soc_type);
				dol_syslog("1. Se regresa este valor del Fetch_Next=".$row);
			} else if($socid > 0) {
				$row = $pol->fetch_next_by_societe_id($id, $socid);
				dol_syslog("2. Se regresa este valor del Fetch_Next=".$row);
			} else {
				$row = $pol->fetch_next($id, 1);
				dol_syslog("3. Se regresa este valor del Fetch_Next=".$row);
			}
		} */
		unset($pol);
		
?>
		</table>
		<br><hr><br>
<?php 
	}
   }
}else{
	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
}
llxFooter();

dol_htmloutput_mesg($msg);
dol_htmloutput_events();

$db->close();
?>