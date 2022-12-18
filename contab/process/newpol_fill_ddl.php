<?php
/* JPFarber - Actualización del div para mostrar opción de si se abre o se cierra el periodo. */

$res = "";
if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only

require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/invoice.lib.php';

require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/fourn.lib.php';

if (! $user->rights->contab->cont) {
	accessforbidden();
}
dol_syslog("Estoy en newpol_fill_ddl.php");

//Ejecutar la modificación de la base de datos y la llamada al "Enabler"
//$fecha = GETPOST("fecha");
$soc_type = GETPOST("soc_type");
$facid = GETPOST("facid");

//print "Fecha:".$fecha;
//$anio = date("Y", $fecha);
//$mes = date("m", $fecha);

$op = "";

$a_fac = array();
if ($soc_type == 0) {
	?><select><option value="0">Sin Informacion</option></select><?php
} else if ($soc_type == 1) {
	$op .= '<select name="fk_facture" id="fk_facture">';
	$op .= '<option value="0">&nbsp;</option>';
	
	$sql = 'SELECT f.rowid, f.facnumber FROM '.MAIN_DB_PREFIX.'facture f Inner Join '.MAIN_DB_PREFIX.'societe s On f.fk_soc = s.rowid and s.client = 1 WHERE f.entity = '.$conf->entity.' Order by f.rowid';
	
	dol_syslog("newpol_fill_ddl.php :: facid=$facid, sql=".$sql, LOG_DEBUG);
	
	$result = $db->query($sql);
	if ($result)
	{
		dol_syslog("Entre");
		while ($obj = $db->fetch_object($result)) {
			dol_syslog($obj->rowid." ".$obj->facnumber);
			$op .= '<option value="'.$obj->rowid.'" '.($facid == $obj->rowid ? ' selected="selected"' : '').'>'.$obj->facnumber.'</option>';
		}
	}
	$op .= '</select>';
		
} else if($soc_type == 2) {
	$op .= '<select name="fk_facture" id="fk_facture">';
	$op .= '<option value="0">&nbsp;</option>';
	
	$sql = 'SELECT f.rowid, f.ref FROM '.MAIN_DB_PREFIX.'facture_fourn f Inner Join '.MAIN_DB_PREFIX.'societe s On f.fk_soc = s.rowid and s.fournisseur = 1 WHERE f.entity = '.$conf->entity.' Order by f.rowid';
	
	dol_syslog("newpol_fill_ddl.php :: facid=$facid, sql=".$sql, LOG_DEBUG);
	
	$result = $db->query($sql);
	if ($result)
	{
		dol_syslog("Entre");
		while ($obj = $db->fetch_object($result)) {
			dol_syslog($obj->rowid." ".$obj->ref);
			$op .= '<option value="'.$obj->rowid.'" '.($facid == $obj->rowid ? ' selected="selected"' : '').'>'.$obj->ref.'</option>';
			dol_syslog($facid." ".$obj->rowid." ".($facid == $obj->rowid ? " selected='selected' " : ""));
		}
	}
	$op .= '</select>';
	dol_syslog($op);
}
print $op;
?>
