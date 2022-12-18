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

date_default_timezone_set("America/Mexico_City");

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

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabperiodos.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabperiodos.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabsatctas.class.php';
}
if (file_exists(DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php')){
	require_once DOL_DOCUMENT_ROOT.'/contab/core/lib/contab.lib.php';
} else {
	require_once DOL_DOCUMENT_ROOT.'/custom/contab/core/lib/contab.lib.php';
}
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
if (! $user->rights->contab->cont) {
	accessforbidden();
}

// Change this following line to use the correct relative path from htdocs

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");
//print GETPOST('mes')."::".GETPOST('mes1');
// Get parameters
if(GETPOST('mes')){
	$ff=explode("-", GETPOST('mes'));
	$anio =$ff[0];
	$mes = $ff[1];
}else{
	$anio = date('Y');
	$mes = date('m');
}
$fecha=$anio."-".$mes;
if(GETPOST('mes1')){
	$ff1=explode("-", GETPOST('mes1'));
	$anio1 =$ff1[0];
	$mes1 = $ff1[1];
}else{
	$anio1 = date('Y');
	$mes1 = date('m');
}
if(GETPOST('cuenta')){
	$cuenta=GETPOST('cuenta');
}else{
	$cuenta='';
}

if(GETPOST('cuenta2')){
	$cuenta2=GETPOST('cuenta2');
}else{
	$cuenta2='';
}
//print GETPOST("mes1");
$fecha1=$anio1."-".$mes1;
$per = new Contabperiodos($db);
$per->fetch_by_period($anio, $mes);

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/js/functions.js')) {
	$arrayofjs = array('/contab/js/functions.js');
} else {
	$arrayofjs = array('/custom/contab/js/functions.js');
}

llxHeader('','Periodos Contables','','','','',$arrayofjs,'',0,0);

$head=array();
$head = contab_prepare_head($object, $user);
dol_fiche_head($head, 'Auxiliar de cuentas', 'Auxiliar de cuentas', 0, '');

global $db,$conf,$langs;
$todos='NO';
$tds='n';
$action=GETPOST('action');
if($action=='mostrar'){
	if(GETPOST('chk_todas')){
		$todos='SI';
		$tds='y';
	}else{
		$todos='NO';
		$tds='n';
	}
}
if($todos=='SI'){
	$ck='checked';
}else{
	$ck='';
}
?>
<!-- 
<h1 align="center"><?=$conf->global->MAIN_INFO_SOCIETE_NOM?></h1>-->
<h1 align="center">Auxiliar de Cuentas</h1>

<form>
Fecha Inicio: <input type="month" name="mes1" value="<?=$fecha1?>">
Fecha Fin: <input type="month" name="mes" value="<?=$fecha?>">
<!--Cuenta: <input type="text" name="cuenta" value="<?=$cuenta?>">-->
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
Cuenta Inicio:  
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
    $( "#cuenta2" ).combobox();
    $( "#toggle" ).click(function() {
      $( "#cuenta2" ).toggle();
    });
  });
  </script>
Cuenta Fin:  
	<?php 
	$sqlc="SELECT cta,descta
		FROM ".MAIN_DB_PREFIX."contab_cat_ctas
		WHERE entity=".$conf->entity;
	$resc=$db->query($sqlc);
	?>
	<select name="cuenta2" id="cuenta2" >
		<option value=""></option>
		<?php 
		while($rqc=$db->fetch_object($resc)){
			$ac='';
			if($cuenta2==$rqc->cta){
				$ac=' SELECTED';
			}
			print "<option value='".$rqc->cta."' ".$ac.">".$rqc->cta." - ".$rqc->descta."</option>";
		}
		?>
  </select>
<input type="submit" value="Consultar">
</form>

<br>
<h3>Periodo contable: <?=$per->MesToStr($mes1)." - ".$anio1;?> a <?=$per->MesToStr($per->mes)." - ".$per->anio;?></h3>
<table class="noborder" style="width: 100%">
		<tr class="liste_titre">
			<td colspan="4" style="text-align: right">
				<form action="?a=<?=$anio?>&m=<?=$mes?>&action=mostrar" method="POST">
					<input type="hidden" name="mes" value="<?=$fecha?>">
					<input type="hidden" name="mes1" value="<?=$fecha1?>">
					<input type="hidden" name="cuenta" value="<?=$cuenta?>">
					<input type="checkbox" name="chk_todas" <?=$ck?> onchange="this.form.submit()">Mostrar todas las cuentas
				</form>
			</td> 
			<td colspan="1" style="text-align: right">
				<a href="auxiliares2_print.php?tipo=excel&anio=<?=$anio;?>&mes=<?=$mes;?>&t=<?=$tds;?>&anio1=<?=$anio1;?>&mes1=<?=$mes1;?>&cuenta=<?=$cuenta?>&cuenta2=<?=$cuenta2?>" target="popup">
					Descargar Excel
				</a>
			</td>
			<td colspan="1" style="text-align: right">
				<a href="auxiliares2_print.php?tipo=pdf&anio=<?=$anio;?>&mes=<?=$mes;?>&t=<?=$tds;?>&anio1=<?=$anio1;?>&mes1=<?=$mes1;?>&cuenta=<?=$cuenta?>&cuenta2=<?=$cuenta2?>" target="popup">
					Descargar PDF
				</a>
			</td>
		</tr>
		<tr class="liste_titre">
			<td style="width: 10%">Cuenta</td>
			<td style="width: 50%">Descripcion</td>
			<td style="width: 10%;text-align: right;">Saldo Inicial</td>
			<td style="width: 10%;text-align: right;">Debe</td>
			<td style="width: 10%;text-align: right;">Haber</td>
			<td style="width: 10%;text-align: right;">Saldo</td>
		</tr>
<?php 
$mm = sprintf("%02d", $mes);
$mm1 = sprintf("%02d", $mes1);
$sql="SELECT d.rowid,d.cta,d.descta,ifnull(c.debe,0) as debe,ifnull(c.haber,0) as haber
	FROM ".MAIN_DB_PREFIX."contab_cat_ctas d 
 	LEFT JOIN (SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber
	FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b
	WHERE ((CONCAT(anio,LPAD(mes,2,'0'))<=CONCAT('$anio','$mm') AND CONCAT(anio,LPAD(mes,2,'0'))>=CONCAT('$anio1','$mm1')) OR (CONCAT(anio,LPAD(mes,2,'0'))<CONCAT('$anio','$mm'))) 
	AND entity=".$conf->entity." AND a.rowid=b.fk_poliza GROUP BY b.cuenta) c ON d.cta=c.cuenta 	
	WHERE entity=".$conf->entity." ";
if($cuenta!='' && $cuenta2!=''){
	//$sql.=" AND (d.cta LIKE '%".$cuenta."%' or d.descta LIKE '%".$cuenta."%') ";
	//$sql.=" AND (d.cta ='".$cuenta."') ";
	//$sql.=" AND (d.cta between '".$cuenta."' AND '".$cuenta2."') ";
}
//print $sql;
if($todos=='NO'){
	$sql.=" AND d.cta=c.cuenta";
}
//print $sql."";
$rq=$db->query($sql);
$mdebe=0;
$mhaber=0;
$mini=0;
$mactua=0;
$sumsalini=0;
if($cuenta!='' && $cuenta2!=''){
while($rs=$db->fetch_object($rq)){
	
	$a=explode('.',$cuenta);
	$b=explode('.',$cuenta2);
	
	$c=explode('.',$rs->cta);
	$d='';
	if($c[0]>=$a[0] && $c[0]<=$b[0]){
		if($c[0]==$a[0] && $c[0]==$b[0]){
			if($c[1]>=$a[1] && $c[1]<=$b[1]){
				if($c[1]==$a[1] && $c[1]==$b[1]){
					if($c[2]>=$a[2] && $c[2]<=$b[2]){
						$d='Si';
					}else{
						$d='No';
					}
				}else{
					if($c[1]==$a[1]){
						if($c[2]>=$a[2]){
							$d='Si';
						}else{
							$d='No';
						}
					}else{
						if($c[1]==$b[1]){
							if($c[2]<=$b[2]){
								$d='Si';
							}else{
								$d='No';
							}
						}else{
							$d='Si';
						}
					}
				}
			}else{
				$d='No';
			}
		}else{
			if($c[0]==$a[0]){
				if($c[1]>=$a[1]){
					if($c[1]==$a[1]){
						if($c[2]>=$a[2]){
							$d='Si';
						}else{
							$d='No';
						}
					}else{
						$d='Si';
					}
				}else{
					$d='No';
				}
			}else{
				if($c[0]==$b[0]){
					if($c[1]<=$b[1]){
						if($c[1]==$b[1]){
							if($c[2]<=$b[2]){
								$d='Si';
							}else{
								$d='No';
							}
						}else{
							$d='Si';
						}
					}else{
						$d='No';
					}
				}else{
					$d='Si';
				}
			}
		}
	}else{
		$d='No';
	}
	if($d=='Si'){
	$sq3="SELECT d.rowid,d.cta,d.descta,ifnull(c.debe,0) as debe,ifnull(c.haber,0) as haber 
FROM ".MAIN_DB_PREFIX."contab_cat_ctas d 
LEFT JOIN (SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber FROM ".MAIN_DB_PREFIX."contab_polizas a, 
			".MAIN_DB_PREFIX."contab_polizasdet b WHERE (CONCAT(anio,LPAD(mes,2,'0'))<=CONCAT('$anio','$mm') AND CONCAT(anio,LPAD(mes,2,'0'))>=CONCAT('$anio1','$mm1')) 
			AND entity=".$conf->entity." AND a.rowid=b.fk_poliza GROUP BY b.cuenta) c ON d.cta=c.cuenta 
WHERE entity=".$conf->entity." AND d.cta=c.cuenta AND d.cta='".$rs->cta."' ";
	$rq3=$db->query($sq3);
	$nr3=$db->num_rows($rq3);
	if($nr3>0){
		$rs3=$db->fetch_object($rq3);
		$rs->debe=$rs3->debe;
		$rs->haber=$rs3->haber;
	}else{
		$rs->debe=0;
		$rs->haber=0;
	}	
	
	$sql2="SELECT d.rowid,d.cta,d.descta,ifnull(c.debe,0) as debe,ifnull(c.haber,0) as haber
	FROM ".MAIN_DB_PREFIX."contab_cat_ctas d
 	LEFT JOIN (SELECT b.cuenta,sum(b.debe) as debe,sum(b.haber) as haber
	FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b
		WHERE (CONCAT(anio,LPAD(mes,2,'0'))<CONCAT('$anio1',LPAD('$mm1',2,'0')))
		AND entity=".$conf->entity." AND a.rowid=b.fk_poliza GROUP BY b.cuenta) c ON d.cta=c.cuenta
	WHERE entity=".$conf->entity." ";
	$sql2.=" AND d.cta ='".$rs->cta."'";
	//print $sql2."<br><br><br>";
	$bres=$db->query($sql2);
	$brs=$db->fetch_object($bres);
	$mdebe+=$rs->debe;
	$mhaber+=$rs->haber;
// 	$minicial=0;
// 	$minicial=$rs->debeini-$rs->haberini;
// 	$mini+=$minicial;
	$mact=0;
	$saldini=0;
	$satc= new Contabsatctas($db);
	$satc->fetch_by_CodAgr($rs->cta);
	if($satc->natur=='A'){
		$mact=$rs->haber-$rs->debe;
		$saldini=$brs->haber-$brs->debe;
		$mact+=$saldini;
	}else{
		$mact=$rs->debe-$rs->haber;
		$saldini=$brs->debe-$brs->haber;
		$mact+=$saldini;
	}
	$sumsalini+=$saldini;
	$mactua+=$mact;
	print "<tr>";
	print "<td><strong>".$rs->cta."</strong></td>";
	print "<td><strong>".$rs->descta."</strong></td>";
// 	print "<td style='text-align: right'><a href='aux_polizas.php?cta=".$rs->rowid."&a=".$anio."&m=".$mes."' ><img src='../images/lupa.png' height='11px' width='11px'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->debe,2)."</a></td>";
// 	print "<td style='text-align: right'><a href='aux_polizas.php?cta=".$rs->rowid."&a=".$anio."&m=".$mes."' ><img src='../images/lupa.png' height='11px' width='11px'>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->haber,2)."</a></td>";
	print "<td style='text-align: right'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($saldini,2)."</strong></td>";
	print "<td style='text-align: right'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->debe,2)."</strong></td>";
	print "<td style='text-align: right'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($rs->haber,2)."</strong></td>";
	if($mact<0){
		$aux2=" color:red;";
	}else{
		$aux2="";
	}
	print "<td style='text-align: right;".$aux2."'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mact,2)."</strong></td>";
	print "</tr>";
	print "<tr>";
		print "<td colspan='6' align='center'>";
		$sql2="SELECT c.fk_facture,c.societe_type,polid,tipo_pol,c.concepto,c.cons,c.fecha,c.anio,c.mes,d.rowid,d.cta,d.descta,ifnull(c.debe,0) as debe,ifnull(c.haber,0) as haber
				FROM ".MAIN_DB_PREFIX."contab_cat_ctas d 
				     LEFT JOIN (SELECT fk_facture,societe_type,cons,a.rowid as polid,anio,mes, tipo_pol,b.descripcion as concepto,fecha, b.cuenta,b.debe as debe,b.haber as haber FROM ".MAIN_DB_PREFIX."contab_polizas a, ".MAIN_DB_PREFIX."contab_polizasdet b 
				               WHERE (CONCAT(anio,LPAD(mes,2,'0'))<=CONCAT('$anio','$mm') AND CONCAT(anio,LPAD(mes,2,'0'))>=CONCAT('$anio1','$mm1')) 
				               AND entity=".$conf->entity." AND a.rowid=b.fk_poliza ) c ON d.cta=c.cuenta 
					WHERE entity=".$conf->entity." AND d.cta=c.cuenta AND c.cuenta='".$rs->cta."' ORDER BY anio,mes,tipo_pol,c.cons";
		//print $sql2;
		$rs2=$db->query($sql2);
		$nr2=$db->num_rows($rs2);
		if($nr2>0){
			print "<table style='width: 90%' class='noborder'>";
				print "<tr class='liste_titre'>";
					print "<td>";
						print "Poliza";
					print "</td>";
					print "<td>";
						print "Factura";
					print "</td>";
					print "<td>";
						print "Concepto";
					print "</td>";
					print "<td>";
						print "Fecha";
					print "</td>";
					print "<td style='text-align: right;'>";
						print "Debe";
					print "</td>";
					print "<td style='text-align: right;'>";
						print "Haber";
					print "</td>";
				print "</tr>";
				while($ss2=$db->fetch_object($rs2)){
					print "<tr>";
						print "<td>";
						$pol2 = new Contabpolizas($db);
						$pol2->fetch($ss2->polid,$ss2->anio);
						$as="Poliza ";
						$as.=$pol2->Get_folio_poliza();
							print "<a href='libro_diario.php?a=".$ss2->anio."&m=".$ss2->mes."&id=".$ss2->polid."' >";
							print '<img src="../images/lupa.png" height="11px" width="11px">'.$as." ".$ss2->cons."</a>";
						print "</td>";
						print "<td>";
						$ff = new FactureFournisseur($db);
						$f = new Facture($db);
						if ($ss2->societe_type == 1) {
							//Es un Cliente
							$f->fetch($ss2->fk_facture);
							$facid=$f->id;
							$facnumber = $f->ref;
							$pagina = DOL_URL_ROOT."/compta/facture.php?facid=".$facid;
						} else if($ss2->societe_type == 2) {
							//Es un Proveedor
							$ff->fetch($ss2->fk_facture);
							$facid=$ff->id;
							$facnumber = $ff->ref;
							$pagina = DOL_URL_ROOT."/fourn/facture/fiche.php?facid=".$facid;
							if(DOL_VERSION>="3.7"){
								$pagina = DOL_URL_ROOT."/fourn/facture/card.php?facid=".$facid;
							}
						}else{
							$facnumber="N/A";
							$pagina="#";
						}
							print "<a href='".$pagina."'><img src='../images/lupa.png' height='11px' width='11px'>".$facnumber."</a>";
						print "</td>";
						print "<td>";
							print $ss2->concepto;
						print "</td>";
						print "<td>";
							print $ss2->fecha;
						print "</td>";
						print "<td style='text-align: right;'>";
							print $langs->getCurrencySymbol($conf->currency)." ".number_format($ss2->debe,2);
						print "</td>";
						print "<td style='text-align: right;'>";
							print $langs->getCurrencySymbol($conf->currency)." ".number_format($ss2->haber,2);
						print "</td>";
					print "</tr>";
				}
			print "</table>";
		}
		print "</td>";
	print "</tr>";
	print "<tr><td colspan='6' style='text-align: center;'><hr></td></tr>";
  }
}
}
print "<tr>";
print "<td></td>";
print "<td style='text-align: right;'><strong>Total:</strong></td>";
print "<td style='text-align: right'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($sumsalini,2)."</strong></td>";
print "<td style='text-align: right'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mdebe,2)."</strong></td>";
print "<td style='text-align: right'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mhaber,2)."</strong></td>";
print "<td style='text-align: right'><strong>".$langs->getCurrencySymbol($conf->currency)." ".number_format($mactua,2)."</strong></td>";
print "</tr>";
?>
</table>


