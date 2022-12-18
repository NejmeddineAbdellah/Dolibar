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
 * code pour créer le module 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
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
/* $res=0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
    $res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
    $res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
    $res = @include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails"); */

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
$config = new Configuration($db);

$per = new Contabperiodos($db);
if (! $per->fetch_open_period()) {
	if (file_exists(DOL_DOCUMENT_ROOT."/contab/periodos/fiche.php")) {
		print "<script>window.location = '".DOL_URL_ROOT."/contab/periodos/fiche.php';"."</script>";
	} else {
		print "<script>window.location = '".DOL_URL_ROOT."/custom/contab/periodos/fiche.php';"."</script>";
	}
}

// Change this following line to use the correct relative path from htdocs

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$myparam	= GETPOST('myparam','alpha');
$rowid 		= GETPOST("rowid");

if (GETPOST('add')) {
	$action = 'add';
}
if (GETPOST('update')) {
	$action = 'update';
}
if (GETPOST('newcta')) {
	$action = 'newcta';
} 
if (GETPOST('dellall')) {
	$action = 'dellall';
}
if (GETPOST('reindex')) {
	$action = 'reindex';
}
if(GETPOST('conscuenta')){
	$action = 'conscuenta';
}
// Protection if external user
if ($user->societe_id > 0)
{
	//accessforbidden();
}

/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/
$mesg = "";

if ($action =="newcta") {
	$nivel = "";
	$codagr = "";
	$descripcion = "";
	$natur = "";
	
} else if ($action == "edit") {
	$c = new Contabsatctas($db);
	if ($c->fetch($rowid)) {
		$nivel = $c->nivel;
		$codagr = $c->codagr;
		$descripcion = $c->descripcion;
		$natur = $c->natur;
	}
} else if ($action == 'add') {
	if (GETPOST("nivel") == "" || GETPOST('codagr') == "" || GETPOST('descripcion') == "" || GETPOST('natur') == "") {
		$mesg='<div class="error">Favor de no dejar campos en blanco o vacíos.</div>';
	} else {
		$c = new Contabsatctas($db);
		$c->fetch_by_CodAgr(GETPOST("codagr"), true);
		if ($c->id > 0) {
			$mesg='<div class="error">Este numero de Cuenta, ya existe en el Catalogo.</div>';
			$nivel = $c->nivel;
			$codagr = $c->codagr;
			$descripcion = $c->descripcion;
			$natur = $c->natur;
		} else {
			if ($c->cta != GETPOST('codagr')){
				$cc = new Contabsatctas($db);
				
				$cc->nivel = GETPOST('nivel');
				$cc->codagr = GETPOST('codagr');
				$cc->descripcion = GETPOST("descripcion");
				$cc->natur = GETPOST('natur');
				
				$cc->create($user);
			}
		}
	}
		
} else if ($action == 'update') {
	$cc = new Contabsatctas($db);
	
	$cc->id = $rowid;
	$cc->nivel = GETPOST('nivel');
	$cc->codagr = GETPOST('codagr');
	$cc->descripcion = GETPOST("descripcion");
	$cc->natur = GETPOST('natur');
	
	$cc->update();
	
} else if ($action == "delete_confirm")  {
	//Buscar si hay cuentas utilizadas en alguna póliza
	$cc = new Contabsatctas($db);
	$cc->fetch($id);
	
	$cat = new Contabcatctas($db);
	$res = $cat->fetch_by_CodAgr($cc->codagr);
	
	if ($res == 0) {
		if (GETPOST("ddl_borrar_cta") == "S")
		{
			$cc = new Contabsatctas($db);
			$cc->id = $id;
			$cc->delete($user);
			$mesg = "La cuenta fue borrada.";
		}
	} else if ($res == 1) {
		$mesg = "No se elimino la cuenta ya que esta ligada al catalogo del usuario.";
	} else {
		$mesg = "Hubo un error durante el proceso.";
	}
	 
} else if ($action == 'dellall_conf') {
	dol_syslog("Entro");
	$borrar = GETPOST('ddl_dellall_conf');
	if ($borrar == "S") {
		dol_syslog("Entro otra vez");
		$cc = new Contabsatctas($db);
		$cc->delete_all($user);
	}
} else if ($action == 'reindex') {
	$cc = new Contabsatctas($db);
	$cc->reindexar();
}
if($action=='conscuenta'){
	if(GETPOST('cuenta')){
		$cuenta=GETPOST('cuenta');
	}else{
		$cuenta='';
	}
}
dol_syslog("action = $action, borrar=$borrar *".GETPOST('ddl_dellall_conf')."*");

/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

//$arrayofjs = array('/contab/js/functions.js');
//$arrayofcss = array('/doliconta/includes/jquery/chosen/chosen.min.css','/doliconta/css/styles.css');

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/js/functions.js')) {
	$arrayofjs = array('/contab/js/functions.js');
} else {
	$arrayofjs = array('/custom/contab/js/functions.js');
}

/* llxHeader('','Cuentas','','','','',$arrayofjs,'',0,0);

$head = contab_prepare_head($object, $user);
dol_fiche_head($head, 2, 'Contabilidad', 0, ''); */

$max_rows = get_max_rows_per_page($db);

dol_htmloutput_mesg($mesg);
dol_htmloutput_events();

?>
<form method="post" action="../admin/cuentas.php?mod=3">
	<input type="hidden" name="page" value=1 >
	<table>
		<tr>
			<td>
				<input type="submit" name="newcta" class="button" value="Agregar Nueva Cuenta">
			</td>
			<td>
				<input type="submit" name="dellall" class="button" value="Quitar todas las Cuentas">
			</td>
			<td>
<!-- 				<input type="submit" name="reindex" class="button" value="Reindexar Cuentas"> -->
			</td>
			<td>
				Registros a mostrar: 
				<select name="ddlmax_rows" onchange="save_max_rows_per_page(this.value, 2);" >
<?php 
					$i = 1;
					while ($i <= 10){
?>
						<option value=<?=$i * 50;?> <?=($i*50 == $max_rows) ? "selected='selected'" : ""?> >
							<?=$i * 50;?>
						</option> 
<?php
						$i ++;
					}
?>
				</select>
			</td>
			<td>
				Filtrar cuenta: <input type="text" name="cuenta" value="<?=$cuenta?>"> &nbsp;
				<input type="submit" name="conscuenta" class="button" value="Filtrar">
				<a class="button" href="cuentas.php?mod=3">Quitar filtro</a> 
			</td>
		</tr>
	</table>
</form>

<?php 
dol_syslog("Valor del page antes de: ".GETPOST("page"));
$page=1;

if (GETPOST("btnprev_page")) {
	dol_syslog("Prev Page: page=$page");
	$page = GETPOST("prev_page");
} else if (GETPOST("btnfirst_page")) {
	dol_syslog("First Page: page=$page");
	$page = 1;
} else if (GETPOST("btnlast_page")) {
	dol_syslog("Last Page: page=$page");
	$page = GETPOST("last_page");
} else if (GETPOST("btnnext_page")) {
	dol_syslog("Next Page: page=$page");
	$page = GETPOST("next_page");
} else if (GETPOST("page")) {
	$page = GETPOST("page");
}

if ($page < 0) { $page = 1; }

$c = new Contabsatctas($db);
if($cuenta!=''){
	$sql = "Select count(*) From ".MAIN_DB_PREFIX."contab_sat_ctas ";
	$sql .= "WHERE (codagr LIKE '%".$cuenta."%' or descripcion LIKE '%".$cuenta."%')";
}else{
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

dol_syslog("Cuatro sql=$sql, tot_recs=$tot_recs, total_pages=$total_pages, page=$page, start_from=$start_from");

if ($action == 'delete') {
	if($user->rights->contab->elimcuentas){
	$c = new Contabsatctas($db);
	$c->fetch($id);
?>
	<form action="../admin/cuentas.php?mod=3&action=delete_confirm" method="post">
		<input type="hidden" name="id" value="<?php print $id;?>" />
		<br>
		<strong>Cuenta: <?=$c->codagr." - ".$c->descripcion;?></strong><br>
		<strong>Realmente quieres eliminar este codigo de agrupacion del catalogo principal ?</strong> 
		&nbsp;
		&nbsp;
		<select name="ddl_borrar_cta">
			<option value="N">No</option>
			<option value="S">Si</option>
		</select>
		&nbsp;&nbsp;&nbsp;
		<input type="submit" value="Continuar" />
		<br>
	</form>
<?php
	}else{
		print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
	}
} else if ($action == 'dellall') {
?>
	<form method="post" action="../admin/cuentas.php?mod=3&action=dellall_conf">
		Desea realmente borrar todo el contenido del catalogo de cuentas Principal?
		<select name='ddl_dellall_conf'>
			<option value="N">No</option>
			<option value='S'>Si</option>
		</select>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value="Procesar" />
	</form>
<?php 
} else if ($action == "edit" || $action == "newcta") {
	if($user->rights->contab->altcuentas){
	$var=!$var;
?>
	<br>
	<h3>Captura del Catalogo de Cuentas Principal</h3>
	<form method="post" action="../admin/cuentas.php?mod=3">
		<input name="rowid" id="rowid" type="hidden" value="<?php print $rowid; ?>" >
		<table>
			<tr <?php print $bc[$var]; ?>>
				<td>
					Nivel:
				</td>
				<td>
   	        		<input name="nivel" id="nivel" type="text" value="<?php print $nivel; ?>" >
   	     	</td>
			</tr>
			<tr>
				<td>
					Codigo Agr.:
				</td>
				<td> 
					<input name="codagr" id="codagr" type="text" style="width: 150px;" value="<?php print $codagr; ?>" >
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td>
					Descripcion:
				</td>
				<td> 
					<input name="descripcion" id="descripcion" type="text" style="width: 650px;" value="<?php print $descripcion; ?>" >
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td>
					Naturaleza:
				</td>
				<td> 
					<select name="natur"  style="width: 150px" >
						<option value="D" <?=($natur == "D") ? 'selected="selected"' : "";?>>Deudora</option>
						<option value="A" <?=($natur == "A") ? 'selected="selected"' : "";?>>Acreedora</option>
            	    </select>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="8">
<?php	
					if ($action != "edit") { 
?>
						<input type="submit" name="add" class="button" value="Agregar" >
<?php 
					} else {
?>
						<input type="submit" name="update" class="button" value="Actualizar" >
<?php 
					}
?>
					<input type="submit" name="cancel" class="button" value="Cancelar" >
				</td>
			</tr>
		</table>
	</form>
<?php 
	}else{
		print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
	}
} else if (!($action == "edit" || $action == "newcta")) {
?>
	<form method="post" action="../admin/cuentas.php?mod=3">
		<br />
		<br />
		
		<input type="hidden" name="token" value="<?php print $_SESSION['newtoken']; ?>">
		
	<div id="catalogo_ppal">
		<input type="hidden" id="total_pages" name="total_pages" value="<?=$total_pages?>">
		<table class="noborder" style="width:100%">
			<tr class="liste_titre">
				<td>Nivel</td>
				<td>Codigo de<br>Agrupacion</td>
				<td>Descripcion de la cuenta</td>
				<td>Naturaleza</td>
				<td>&nbsp;</td>
			</tr>
<?php 
		$var=True;
		
		$ctas = new Contabsatctas($db);
		if($cuenta!=''){
			$res = $ctas->fetch_next2m(0,-1,$cuenta);
		}else{
			$res = $ctas->fetch_next(0);
		}
		
		$i = 0;
		$ii = 0;
		while ($res) {
			
			if  ($i >= $start_from && $ii < $max_rows) {
				$var = !$var;
?>
				<tr <?php print $bc[$var]; ?>>
					<td><?php print $ctas->nivel; ?></td>
					<td><?php print $ctas->codagr; ?></td>
					<td><?php print substr($ctas->descripcion, 0, 135); ?></td>
					<td><?php print $ctas->natur; ?></td>
					
					<td style="text-align: center;">
						<a href="../admin/cuentas.php?mod=3&rowid=<?php print $ctas->id; ?>&amp;action=edit"><?php print img_edit(); ?></a>&nbsp;&nbsp;
						<a href="../admin/cuentas.php?mod=3&id=<?php print $ctas->id; ?>&amp;action=delete"><?php print img_delete(); ?></a>
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
			//print $id."<br>";
			if($cuenta!=''){
				$res = $ctas->fetch_next2m($id,-1,$cuenta);
			}else{
				$res = $ctas->fetch_next($id);
			}
		}
?>
		</table>
		<br />
	</div>
		<br />
		
		<table>
	    <tr>
			<td>
	 			<label>Registros por Pagina: </label>
			</td>
	        <td>
	        	<label id="pagina">Pagina: <?=$page." de ".$total_pages." pag(s).";?></label>
	        </td>
	        <td>
				<input type="hidden" name="first_page" value="1" />
	            <input type="submit" name="btnfirst_page" value="Primera" />
	        </td>
	        <td>
<?php
				if ($page > 1) {
?>
					<input type="hidden" name="prev_page" value="<?=$page - 1;?>" />
	                <input type="submit" name="btnprev_page" value="Anterior" />
<?php
				} else {
?>
					<input type="hidden" name="prev_page" value="<?=$page;?>" />
	                <input type="submit" name="btnprev_page" value="Anterior" />
<?php
				}
?>
	        </td>
	        <td>
				<select name="page" id="page" onchange="this.form.submit()">
<?php
				for ($i=1; $i<=$total_pages; $i++) {
?> 
					<option value="<?=$i;?>" <?=($page == $i) ? "selected='selected'":"";?>><?=$i;?></option>
<?php
				}
?>
				</select>
	        </td>
	        <td>
<?php
				if ($page < $total_pages) {
?>
					<input type="hidden" name="next_page" value="<?=$page + 1;?>" />
	                <input type="submit" name="btnnext_page" value="Siguiente" />
<?php
				} else {
?>
					<input type="hidden" name="next_page" value="<?=$page;?>" />
	                <input type="submit" name="btnnext_page" value="Siguiente" />
<?php
				}
?>
	        </td>
	        <td>
				<input type="hidden" name="last_page" value="<?=$total_pages;?>" />
	            <input type="submit" name="btnlast_page" value="Última" />
	        </td>
	    </tr>
	    </table>
	</form>
<?php 
}

llxFooter();

$db->close();