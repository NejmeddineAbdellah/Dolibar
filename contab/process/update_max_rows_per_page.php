<?php
/* JPFarber - Rutina para mostrar mediante ajax, la paginación en el catalogo de cuentas.*/

$res=0;
if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/admin/Configuration.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/admin/Configuration.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabsatctas.class.php';
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

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/functions/functions.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/functions/functions.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/functions/functions.php';
}

if (! $user->rights->contab->cont) {
	accessforbidden();
}

//Ejecutar la modificación de la base de datos y la llamada al "Enabler"

$r = addslashes($_POST["r"]);
$tipo_cat = addslashes($_POST["tipo_cat"]);


//$id = left($id, strlen($id) - 5);
//$val = ($value == true) ? 1 : 0;
$sql = "UPDATE ".MAIN_DB_PREFIX."const SET value = $r WHERE name = 'CONTAB_MAX_ROWS_PER_PAGE' AND entity = ".$conf->entity;
$db->query($sql);
//dol_syslog("Se supone que actualizó los datos de Max Rows Per Page, sql=".$sql);

$max_rows = get_max_rows_per_page($db);

if ($page < 0) { $page = 1; }

$sql = "";

$c = new Contabcatctas($db);
if ($tipo_cat == 1) {
	dol_syslog("Entre por ".MAIN_DB_PREFIX."contab_cat_ctas");
	$sql = "Select count(*) From ".MAIN_DB_PREFIX."contab_cat_ctas WHERE entity = ".$conf->entity; //$c->get_sql_string(0);
} else if ($tipo_cat == 2) {
	dol_syslog("Entre por ".MAIN_DB_PREFIX."contab_sat_ctas");
	$sql = "Select count(*) From ".MAIN_DB_PREFIX."contab_sat_ctas"; //$c->get_sql_string(0);
}
if ($res = $db->query($sql)) {
	if ($row = $db->fetch_array($res)) {
		$tot_recs = $row[0];
		$total_pages = ceil($tot_recs / $max_rows);

		if ($page > $total_pages) { $page = 1; }
		$start_from = ($page-1) * $max_rows;
	}
}
if ($tipo_cat == 1) {
?>
		<input type="hidden" id="total_pages" name="total_pages" value="<?=$total_pages?>">
		<table class="noborder" style="width:100%">
			<tr class="liste_titre">
				<td>Cuenta</td>
				<td>Descripción de la Cuenta</td>
				<td>Cód. Agr. del<br>Cat. Principal</td>
				<td>Depende De</td>
				<td>&nbsp;</td>
			</tr>
<?php 
		$var=True;
		
		$ctas = new Contabcatctas($db);
		//$init = 1;
		$arr = $ctas->fetch_array_by_dependede(0, array());  //, $start_from, $max_rows);
		
		$ii = 0;
		foreach ($arr as $i => $val) {
			dol_syslog("$i=$i, val=$val");
			if  ($i >= $start_from && $ii < $max_rows) {
				$ctas->fetch2($val);
				
				$var = !$var;
?>
				<tr <?php print $bc[$var]; ?>>
					<td><?php print $ctas->cta; ?></td>
					<td title="<?php print $ctas->descta; ?>"><?php print substr($ctas->descta, 0, 135); ?></td>
					<td><?php print $ctas->codagr; ?></td>
					<td><?php print $ctas->cta_subctade; ?></td>
	
					<td style="text-align: center;">
						<a href="<?php print $_SERVER["PHP_SELF"]; ?>?rowid=<?php print $ctas->id; ?>&amp;action=edit"><?php print img_edit(); ?></a>&nbsp;&nbsp;
						<a href="<?php print $_SERVER["PHP_SELF"]; ?>?rowid=<?php print $ctas->id; ?>&amp;action=delete"><?php print img_delete(); ?></a>
					</td>
				</tr>
<?php
				$ii ++;
			} 
			$i ++;
		}
?>
		</table>
<?php 
} else if ($tipo_cat == 2) {
?>
		<input type="hidden" id="total_pages" name="total_pages" value="<?=$total_pages?>">
		<table class="noborder" style="width:100%">
			<tr class="liste_titre">
				<td>Nivel</td>
				<td>Código de<br>Agrupación</td>
				<td>Descripción de la cuenta</td>
				<td>Naturaleza</td>
				<td>&nbsp;</td>
			</tr>
<?php 
		$var=True;
		
		$ctas = new Contabsatctas($db);
		$res = $ctas->fetch_next(0);
		
		$i = 1;
		$ii = 0;
		while ($res) {
			
			if  ($i >= $start_from && $ii < $max_rows) {
				$var = !$var;
?>
				<tr <?php print $bc[$var]; ?>>
					<td><?php print $ctas->nivel; ?></td>
					<td><?php print $ctas->codagr; ?></td>
					<td><?php print $ctas->descripcion; ?></td>
					<td><?php print $ctas->natur; ?></td>
					
					<td style="text-align: center;">
						<a href="<?php print $_SERVER["PHP_SELF"]; ?>?rowid=<?php print $ctas->id; ?>&amp;action=edit"><?php print img_edit(); ?></a>&nbsp;&nbsp;
						<a href="<?php print $_SERVER["PHP_SELF"]; ?>?rowid=<?php print $ctas->id; ?>&amp;action=delete"><?php print img_delete(); ?></a>
					</td>
				</tr>
<?php 
				$ii ++;
			}
			if ($ii >= $max_rows) {
				break;
			}
			$i ++;
			$id = $ctas->id;
			$res = $ctas->fetch_next($id);
		}
?>
		</table>
<?php 
}
?>	