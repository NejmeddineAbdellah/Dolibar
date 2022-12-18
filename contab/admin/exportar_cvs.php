<?php
$res = 0;
if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only
if (!$res)
	die("Include of main fails");


if(GETPOST('fechini') && GETPOST('fechfin')){
	$fecini=GETPOST('fechini');
	$fecfin=GETPOST('fechfin');
}else{
	$fecini=date('Y-m-d');
	$fecfin=date('Y-m-d');
}
$sql="SELECT rowid,tipo_pol,cons,anio,mes,fecha,concepto,comentario, 
		fk_facture,anombrede,numcheque,ant_ctes,fechahora,societe_type
	FROM ".MAIN_DB_PREFIX."contab_polizas 
	WHERE entity=".$conf->entity." AND fecha between '".$fecini."' AND '".$fecfin."' ORDER BY rowid";
//print $sql."<br>";
//print $fecini." :: ".$fecfin."<br>";
header("Content-type: application/CSV");
header("Content-disposition: attachment; filename=polizas.csv");
$req=$db->query($sql);

while ($res=$db->fetch_object($req)){
	print "Pol,";
	print $res->rowid.",";
	print $res->tipo_pol.",";
	print $res->cons.",";
	print $res->anio.",";
	print $res->mes.",";
	print $res->fecha.",";
	print $res->concepto.",";
	print $res->comentario.",";
	print $res->fk_facture.",";
	print $res->anombrede.",";
	print $res->numcheque.",";
	print $res->ant_ctes.",";
	print $res->fechahora.",";
	print $res->societe_type.",";
	print "\n";
	$sqm="SELECT fk_poliza,asiento,cuenta,debe,haber,descripcion
		FROM ".MAIN_DB_PREFIX."contab_polizasdet
		WHERE fk_poliza=".$res->rowid;
	//print "<br>".$sqm."<br>";
	$rq=$db->query($sqm);
	while ($rs=$db->fetch_object($rq)){
		print "Poldet,";
		print $rs->fk_poliza.",";
		print $rs->asiento.",";
		print $rs->cuenta.",";
		print $rs->debe.",";
		print $rs->haber.",";
		print $rs->descripcion.",";
		print "\n";
	}
	$sqn="SELECT a.creador,a.cantmodif,a.fechahora,a.fk_user,b.login
		FROM ".MAIN_DB_PREFIX."contab_polizas_log a, ".MAIN_DB_PREFIX."user b
		WHERE a.fk_poliza=".$res->rowid." AND a.fk_user=b.rowid";
	$rl=$db->query($sqn);
	while($rss=$db->fetch_object($rl)){
		print "PolLog,";
		print $rss->creador.",";
		print $rss->cantmodif.",";
		print $rss->fechahora.",";
		print $rss->login.",";
		print "\n";
	}
	//print "<br><br>";
}

?>
