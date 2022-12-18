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

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpolizasdet.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpolizasdet.class.php';
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

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabperiodos.class.php';
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
if (GETPOST('btnfind')) {
	$text_to_search = GETPOST("find_desc");
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
	$cta = "";
	$descta = "";
	$fk_sat_cta = "";
	$subctade = "";
	
} else if ($action == "edit") {
	$c = new Contabcatctas($db);
	if ($c->fetch($rowid)) {
		$cta = $c->cta;
		$descta = $c->descta;
		$fk_sat_cta = $c->fk_sat_cta;
		$subctade = $c->subctade;
	}
} else if ($action == 'add') {
	if (GETPOST("cta") == "" ) {
		$mesg='<div class="error">Favor de no dejar campos en blanco o vacios.</div>';
	} else {
		$c = new Contabcatctas($db);
		$c->fetch_by_Cta(GETPOST("cta"), true);
		if ($c->id > 0) {
			$mesg='<div class="error">Este numero de Cuenta, ya existe en el Catalogo.</div>';
			$cta = $c->cta;
			$descta = $c->descta;
			$fk_sat_cta = $c->fk_sat_cta;
			$subctade = $c->subctade;
		} else {
			if ($c->cta != GETPOST('cta')){
				$cc = new Contabcatctas($db);
				
				$cc->cta = GETPOST('cta');
				$cc->descta = $db->escape(GETPOST('descta'));
				$cc->fk_sat_cta = GETPOST('fk_sat_cta');
				$cc->subctade = GETPOST('subctade');
				
				$cc->create($user);
			}
		}
	}
		
} else if ($action == 'update') {
	$cc = new Contabcatctas($db);
	
	$cc->id = $rowid;
	$cc->cta =GETPOST('cta');
	$cc->descta = $db->escape(GETPOST('descta'));
	$cc->fk_sat_cta = $db->escape(GETPOST('fk_sat_cta'));
	$cc->subctade = GETPOST('subctade');
	
	$cc->update();
	
} else if ($action == "delete_confirm")  {
	//Buscar si hay cuentas utilizadas en alguna póliza
	$cc = new Contabcatctas($db);
	$cc->fetch($id);
	
	$pol = new Contabpolizasdet($db);
	$res = $pol->fetch_by_cuenta($cc->cta);
	
	if ($res == 0) {
		if (GETPOST("ddl_borrar_cta") == "S")
		{
			$cc = new Contabcatctas($db);
			$cc->id = $id;
			$cc->delete($user);
			$mesg = "La cuenta fue borrada.";
		}
	} else if ($res == 1) {
		$mesg = "No se puede eliminar esta cuenta ya que esta ligada a una o varias polizas.";
	} else {
		$mesg = "Hubo un error durante el proceso.";
	}
} else if ($action == 'dellall_conf') {
	$borrar = GETPOST('ddl_dellall_conf');
	if ($borrar == "S") {
		$cc = new Contabcatctas($db);
		$cc->delete_all($user);
	}
} else if ($action == 'reindex') {
	$cc = new Contabcatctas($db);
	$cc->reindexar();
} 

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
dol_fiche_head($head, 3, 'Contabilidad', 0, ''); */

$max_rows = get_max_rows_per_page($db);

$i = 1;
$option = "";
while ($i <= 10) {
	$option .= '<option value="'.($i * 50).'"';
	if ($i*50 == $max_rows) {
		$option .= 'selected="selected"';
	}
	$option .= '>'.($i * 50).'</option>';
	$i ++;
}

/* 
 * Para la Paginación
 */
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


if($action=='conscuenta'){
	if(GETPOST('cuenta')){
		$cuenta=GETPOST('cuenta');
	}else{
		$cuenta='';
	}
}

$c = new Contabcatctas($db);
if($cuenta==''){
	$sql = "Select count(*) From ".MAIN_DB_PREFIX."contab_cat_ctas "; //$c->get_sql_string(0);
	if (! $text_to_search == "" ) {
		$sql .= " WHERE descta LIKE '%$text_to_search%'";
	}
}else{
	$sql = "Select count(*) From ".MAIN_DB_PREFIX."contab_cat_ctas ";
	$sql .= "WHERE (cta LIKE '%".$cuenta."%' or descta LIKE '%".$cuenta."%')";
}

if ($res = $db->query($sql)) {
	if ($row = $db->fetch_array($res)) {
		$tot_recs = $row[0];
		$total_pages = ceil($tot_recs / $max_rows);

		if ($page > $total_pages) { $page = 1; }
		$start_from = ($page-1) * $max_rows;
	}
}


/*
 * Termina el cálculo de la paginación.
 */

dol_syslog("Cuatro action=$action, sql=$sql, tot_recs=$tot_recs, total_pages=$total_pages, page=$page, start_from=$start_from");

?>
<form method="post" action="../admin/cuentas.php?mod=4">
	<input type="hidden" name="page" value="1" >
	<table>
		<tr>
			<!-- <td>
				Descripción de la cuenta: <input type="text" name="find_desc" >&nbsp;&nbsp;
				<input type="submit" name="btnfind" class="button" value="Buscar Cuenta">
			</td>  -->
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
				<select name="ddlmax_rows" onchange="save_max_rows_per_page(this.value, 1);" >
					<?=$option;?>
				</select>
			</td>
			<td>
				Filtrar cuenta: <input type="text" name="cuenta" value="<?=$cuenta?>"> &nbsp;
				<input type="submit" name="conscuenta" class="button" value="Filtrar">
				<a class="button" href="cuentas.php?mod=4">Quitar filtro</a> 
			</td>
		</tr>
	</table>
</form>

<?php

if ($action == 'delete') {
	if($user->rights->contab->elimcuentas){
	$c = new Contabcatctas($db);
	$c->fetch($id);
?>
	<form action="../admin/cuentas.php?mod=4&action=delete_confirm" method="post">
		<input type="hidden" name="id" value="<?php print $id;?>" />
		<br>
		<strong>Cuenta: <?=$c->cta." - ".$c->descta;?></strong><br>
		<strong>Realmente quieres eliminar esta cuenta de su catálogo de usuario ?</strong> 
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
	<form method="post" action="../admin/cuentas.php?mod=4&action=dellall_conf">
		Desea realmente borrar todo el contenido del catálogo de cuentas Personalizado?
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
	//$var=!$var;
?>
	Captura del Catálogo de Cuentas
	<br><br>
	<div align="center">
	<script>
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        //this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " " )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#cuenta" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#cuenta" ).toggle();
    });
  });
  </script>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Cuentas existentes: &nbsp;&nbsp;
						<?php 
						$sqlc="SELECT cta,descta
							FROM ".MAIN_DB_PREFIX."contab_cat_ctas
							WHERE entity=".$conf->entity;
						$resc=$db->query($sqlc);
						?>
						<select name="cuenta" id="cuenta" >
							<option value=""></option>
							<?php 
							while($rqc=$db->fetch_object($resc)){
								$ac='';
								if($cuenta==$rqc->cta){
									$ac=' SELECTED';
								}
								print "<option value='".$rqc->cta."' ".$ac.">".$rqc->cta." - ".$rqc->descta."</option>";
							}
							?>
					  </select>
</div>	
	<form method="post" action="../admin/cuentas.php?mod=4">
		<input name="rowid" id="rowid" type="hidden" value="<?php print $rowid; ?>" >
		<table>
			<tr>
				<td>
					Cuenta:
				</td>
				<td>
   	        		<input name="cta" id="cta" type="text" value="<?php print $cta; ?>" >
   	     		</td>
			</tr>
			<tr>
				<td>
					Descripcion:
				</td>
				<td> 
					<input name="descta" id="descta" type="text" style="width: 650px;" value="<?php print $descta; ?>" >
				</td>
			</tr>
			<tr>
				<td>
					Codigo Agrupador:
				</td>
				<td> 
					<select name="fk_sat_cta"  style="width: 650px" >
						<?php print $config->create_List_SAT_Cat($fk_sat_cta); ?>
   					</select>
				</td>
			</tr>
			<tr>
				<td>
					Depende De:
				</td>
				<td> 
					<select name="subctade"  style="width: 650px" >
						<?php print $config->create_List_Cat_Ctas($subctade); ?>
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
}
dol_syslog("Cinco max_row=$max_rows");
dol_syslog(!($action == "edit" || $action == "newcta") ? 1 : 0);
if (!($action == "edit") && !($action == "newcta")) {
?>
	<form method="post" action="../admin/cuentas.php?mod=4">
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
	    
	<form method="post" action="../admin/cuentas.php?mod=4">
		<br />
		
		<div id="catalogo">
			<input type="hidden" id="total_pages" name="total_pages" value="<?=$total_pages?>">
			<table class="noborder" style="width:100%">
				<tr class="liste_titre">
					<td>Cuenta</td>
					<td>Descripcion de la Cuenta</td>
					<td>Cod. Agr. del<br>Cat. Principal</td>
					<td>Depende De</td>
					<td>&nbsp;</td>
				</tr>
<?php 
			$per = new Contabperiodos($db);
			$per->fetch_open_period();
			$anio = $per->anio;
			$mes = $per->mes;

			$var=True;
			
			$ctas = new Contabcatctas($db);
			//$init = 1;
			if($cuenta!=''){
				$arr = $ctas->fetch_array_by_dependede2($cuenta);
				//print_r($arr);
			}else{
				$arr = $ctas->fetch_array_by_dependede(0, array());  //, $start_from, $max_rows);
			}
			$ii = 0;
			foreach ($arr as $i => $val) {
				dol_syslog("$i=$i, val=$val");
				if  ($i >= $start_from && $ii < $max_rows) {
					$ctas->fetch2($val);
					
					//if (strpos($ctas->descta, $text_to_search) > 0) {
						$var = !$var;
?>	
						<tr <?php print $bc[$var]; ?>>
							<td><a href="../lists/mayor.php?id=<?=$ctas->id;?>&a=<?=$anio;?>&m=<?=$mes;?>" target="_blank"><?php print $ctas->cta; ?></a></td>
							<td title="<?php print $ctas->descta; ?>"><?php print substr($ctas->descta, 0, 135); ?></td>
							<td><?php print $ctas->codagr; ?></td>
							<td><?php print $ctas->cta_subctade; ?></td>
			
							<td style="text-align: center;">
								<a href="../admin/cuentas.php?mod=4&rowid=<?php print $ctas->id; ?>&amp;action=edit"><?php print img_edit(); ?></a>&nbsp;&nbsp;
								<a href="../admin/cuentas.php?mod=4&id=<?php print $ctas->id; ?>&amp;action=delete"><?php print img_delete(); ?></a>
							</td>
						</tr>
<?php
						$ii ++;
					//}
				}
				if ($ii >= $max_rows) {
					break;
				}
				//$i ++;
			}
?>
			</table>
		</div>
		<br />
		
		<table>
	    	<tr>
				<td>
	 				<label>Registros por Pagina: </label>
				</td>
	    	    <td>
	    	    	<label id="pagina">Pagina: <?=$page." de ".$total_pages." pág(s).";?></label>
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

dol_htmloutput_mesg($mesg);
dol_htmloutput_events();

$db->close();