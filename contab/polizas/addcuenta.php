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
// Change this following line to use the correct relative path from htdocs
dol_include_once('/module/class/skeleton_class.class.php');

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
$langs->load("contab@contab");

// Get parameters
$id=GETPOST('id');
$anio = GETPOST('anio', 'int');
$mes = GETPOST('mes', 'int');
if (GETPOST('action')) {
	$action = GETPOST('action', 'alpha');
}
if (GETPOST('add')) {
	$action = 'add';
}
if (GETPOST('newcta')) {
	$action = 'newcta';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabrelctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabrelctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabsatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}
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
require_once '../functions/functions.php';

llxHeader('', 'Agregar cuenta', '');
print_fiche_titre('Agregar cuenta','','setup');
dol_fiche_head('', '', 'Agregar cuenta', 0, '');

$mesg='';
if ($action =="newcta") {
	$cta = "";
	$descta = "";
	$fk_sat_cta = "";
	$subctade = "";

}
if ($action == 'add') {
	if (GETPOST("cta") == "" ) {
		$mesg='<div class="error">Favor de no dejar campos en blanco o vacios.</div>';
	} else {
		$c = new Contabcatctas($db);
		$c->fetch_by_Cta(GETPOST("cta"), true);
		if ($c->id > 0) {
			$mesg='<div class="error">Este numero de Cuenta, ya existe en el Catalogo.</div>';
			/* $cta = $c->cta;
			$descta = $c->descta;
			$fk_sat_cta = $c->fk_sat_cta;
			$subctade = $c->subctade; */
		} else {
			if ($c->cta != GETPOST('cta')){
				$cc = new Contabcatctas($db);

				$cc->cta = GETPOST('cta');
				$cc->descta = $db->escape(GETPOST('descta'));
				$cc->fk_sat_cta = GETPOST('fk_sat_cta');
				$cc->subctade = GETPOST('subctade');

				$cc->create($user);
				$mesg='<div>Cuenta creada en el catalogo.</div>';
			}
		}
	}

}
$config = new Configuration($db);

if($mesg!=''){
	dol_htmloutput_mesg($mesg);
}
?>
<div align="right">
<?php 
if(GETPOST('tpenvio')=='fichepol'){
?>
	<a href="fiche.php?cambio_fecha=1&anio=<?=$anio?>&mes=<?=$mes?>">Volver a polizas</a>
<?php 
}
?>
<?php 
if(GETPOST('tpenvio')=='pol'){
?>
	<a href="poliza.php?id=<?=$id?>">Volver a poliza</a>
<?php 
}
?>
</div>
<?php 
if($user->rights->contab->altcuentas){
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
<?php 
	if(GETPOST('tpenvio')=='fichepol'){
		$urlvover="fiche.php?cambio_fecha=1&anio=".$anio."&mes=".$mes;
	}
	if(GETPOST('tpenvio')=='pol'){
		$urlvover="poliza.php?id=".$id;
	}
	print "<script>
	window.onkeydown=tecla;
	function tecla(event){
		num = event.keyCode;
		if(num==116){ 
			//116==F5 Nueva poliza
			window.location.href='".$urlvover."';
			event.preventDefault();
		}
 		if(num==115){ 
			//115==F4 Agregar
			//alert('F4');
			document.getElementById('add').click();
			//event.preventDefault();
		}

	}
	</script>";
	?> 
	<form method="post" action="">
		<input name="rowid" id="rowid" type="hidden" value="<?php print $rowid; ?>" >
		<input name="anio" id="anio" type="hidden" value="<?php print $anio; ?>" >
		<input name="mes" id="mes" type="hidden" value="<?php print $mes; ?>" >
		<input name="id" id="id" type="hidden" value="<?php print $id; ?>" >
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
						<input type="submit" name="add" id="add" class="button" value="Agregar" >
					<input type="submit" name="cancel" class="button" value="Cancelar" >
				</td>
			</tr>
		</table>
	</form>
<?php 
}else{
	print '<div class="error">Acceso denegado.<br>Intenta acceder a una página, área o funcionalidad de un módulo desactivado o sin una sesión auntenticada o no permitida a su usuario</div>';
}
/*
//print $anio." ".$mes;
$mesg='';
if ($action == 'add') {
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
				$mesg='<div>Cuenta creada en el catalogo.</div>';
				$cc = new Contabsatctas($db);

				$cc->nivel = GETPOST('nivel');
				$cc->codagr = GETPOST('codagr');
				$cc->descripcion = GETPOST("descripcion");
				$cc->natur = GETPOST('natur');

				$cc->create($user);
			}
		}
	}
}
if($mesg!=''){
	dol_htmloutput_mesg($mesg);
}

?>
<div align="right">
<a href="fiche.php?cambio_fecha=1&anio=<?=$anio?>&mes=<?=$mes?>">Volver a polizas</a>
</div>
<h3>Captura del Catalogo de Cuentas Principal</h3>
<form method="post">
<input name="rowid" id="rowid" type="hidden" value="<?php print $rowid; ?>" >
<input name="anio" id="anio" type="hidden" value="<?php print $anio; ?>" >
<input name="mes" id="mes" type="hidden" value="<?php print $mes; ?>" >
<table>
			<tr <?php print $bc[$var]; ?>>
				<td>
					Nivel:
				</td>
				<td>
   	        		<input name="nivel" id="nivel" type="text">
   	     	</td>
			</tr>
			<tr>
				<td>
					Codigo Agr.:
				</td>
				<td> 
					<input name="codagr" id="codagr" type="text" style="width: 150px;" >
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td>
					Descripcion:
				</td>
				<td> 
					<input name="descripcion" id="descripcion" type="text" style="width: 650px;" >
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td>
					Naturaleza:
				</td>
				<td> 
					<select name="natur"  style="width: 150px" >
						<option value="D" >Deudora</option>
						<option value="A" >Acreedora</option>
            	    </select>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="8">
					<input type="submit" name="add" class="button" value="Agregar" >
					<input type="submit" name="cancel" class="button" value="Cancelar" >
				</td>
			</tr>
		</table>
	</form>
	
*/

