<?php 
if (! $user->rights->contab->cont) {
	accessforbidden();
}
?>
	<br>
	<input name="id" id="id" type="hidden" value="<?php print $id; ?>" >
	<?php if ($esfaccte == 1) { ?>
		<input type="hidden" name="fc" value="<?=$esfaccte?>">
		<input type="hidden" name="facid" value="<?=$facid;?>"> 
	<?php } else if ($esfacprov == 1) { ?>
		<input type="hidden" name="fp" value="<?=$esfacprov?>">
		<input type="hidden" name="facid" value="<?=$facid;?>"> 
	<?php } 
 
	$var=True;
	
	$ini = 0;
	$cant = 0;

   	$tp = "";
	$c = 0;
	
   	$i = 0;
	
   	$pol = new Contabpolizas($db);
   	$poldet = new Contabpolizasdet($db);
   	$ctas = new Contabcatctas($db);
   	$ff = new FactureFournisseurs($db);
   	$f = new Factures($db);
   	$soc = new Societe($db);
   	
	$primera_vez = true;
	$pol->anio = $per->anio;
	$pol->mes = $per->mes;
	if ($esfaccte == 1 || $esfacprov == 1) {
		$soc_type = ($esfaccte == 1) ? 1 : 2;
		$row = $pol->fetch_next_by_facture_id(0, $facid, $soc_type);
	} else {
		$row = $pol->fetch_next(0, 1);
	}
	while ($row) { // = $db->fetch_array(rs)) {
?>
		<table class="noborder" style="width:100%">
		<tr class="liste_titre">
			<td colspan="6">Encabezado de la Póliza</td>
			<td><a href="<?=$_SERVER["PHP_SELF"]; ?>?action=newpol<?=($esfaccte == 1) ? '&fc='.$esfaccte : '';?><?=($esfacprov == 1) ? '&fp='.$esfacprov : '';?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>&anio=<?=$anio?>&mes=<?=$mes?>">Agregar Nueva Póliza</a></td>
		</tr>
<?php 
		if ($tp !== $pol->tipo_pol || $c !== $pol->cons) {
			$var = !$var;
			$tp = $pol->tipo_pol;
			$c = $pol->cons;
			$facid = $pol->fk_facture;
						
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
?>			
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "3">
					Póliza:
					<strong> 
<?php 
					print $pol->Get_Tipo_Poliza_Desc().": ".$c;
					/* if ($tp == "D") { echo "Diario: "; }
					else if($tp == "E") { print "Egreso: "; }
					else if($tp == "C") { print "Cheques: "; }
					else if($tp == "I") { print "Ingreso: "; } */
					//print " " . $c; 
?>
					</strong>
					<a href="<?php print $_SERVER["PHP_SELF"]; ?>?id=<?=$pol->id; ?>&amp;action=editenc<?=($esfaccte == 1) ? '&fc='.$esfaccte : '';?><?=($esfacprov == 1) ? '&fp='.$esfacprov : '';?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?php print img_edit(); ?></a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?php print $_SERVER["PHP_SELF"]; ?>?action=filterfac<?=($esfaccte == 1) ? '&fc='.$esfaccte : '';?><?=($esfacprov == 1) ? '&fp='.$esfacprov : '';?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?php print img_view("Filtrar por Factura"); ?></a>
				</td>
				<td colspan = "3">
					Documento Relacionado: <a href="<?=DOL_URL_ROOT.$pagina;?>?facid=<?=$facid;?>"><?php echo $facnumber; ?></a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?php print $_SERVER["PHP_SELF"]; ?>?id=<?=$pol->id; ?>&amp;action=newpolline<?=($esfaccte == 1) ? '&fc='.$esfaccte : '';?><?=($esfacprov == 1) ? '&fp='.$esfacprov : '';?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Nuevo Asiento</a> 
<?php 
					if ($row["asiento"] == 0) {
?>
						/&nbsp;  <a href="<?php print $_SERVER["PHP_SELF"]; ?>?id=<?=$pol->id; ?>&amp;action=delpol<?=($esfaccte == 1) ? '&fc='.$esfaccte : '';?><?=($esfacprov == 1) ? '&fp='.$esfacprov : '';?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>">Borrar Póliza</a>
<?php 
					}
?>
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "6">
					Concepto: <strong><?php echo substr($pol->concepto,0,150); ?></strong>
					&nbsp;
					Comentario: <strong><?php echo substr($pol->comentario,0,150); ?></strong>
				</td>
			</tr>
			<tr <?php print $bc[$var]; ?>>
				<td colspan = "6">
					Cheque a Nombre: <strong><?php echo substr($pol->anombrede,0,150); ?></strong>
					&nbsp;
					Núm. Cheque: <strong><?php echo substr($pol->numcheque,0,150); ?></strong>
				</td>
			</tr>
<?php
		}
?>
		<tr class="liste_titre">
			<td width="15%"></td>
			<td width="5%">Asiento</td>
			<td width="10%">Cuenta</td>
			<td width="10%">Debe</td>
			<td width="10%">Haber</td>
			<td>&nbsp;</td>
		</tr>
<?php 
		$cond = " fk_poliza = ".$pol->id;
		$rr = $poldet->fetch_next(0, $cond);
		if ($rr) {
			while ($rr) {	
?>
				<tr <?php print $bc[$var]; ?>>
					<td></td>
					<td><?php print $poldet->asiento; ?></td>
					<td><?php print $poldet->cuenta; ?></td>
					<td><?php print ($poldet->debe > 0) ? round($poldet->debe, 2) : "" ; ?></td>
					<td><?php print ($poldet->haber > 0) ? round($poldet->haber, 2) : "" ; ?></td>
<?php
		 			if ($poldet->asiento > 0) {
?>
						<td style="text-align: center;">
							<?php "fc=$esfaccte, fp=$esfacprov"?>
							<a href="<?php print $_SERVER["PHP_SELF"]; ?>?idpd=<?php print $poldet->id; ?>&amp;action=editline<?=($esfaccte == 1) ? '&fc='.$esfaccte : '';?><?=($esfacprov == 1) ? '&fp='.$esfacprov : '';?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?php print img_edit(); ?></a>&nbsp;&nbsp;
							<a href="<?php print $_SERVER["PHP_SELF"]; ?>?idpd=<?php print $poldet->id; ?>&amp;action=delline<?=($esfaccte == 1) ? '&fc='.$esfaccte : '';?><?=($esfacprov == 1) ? '&fp='.$esfacprov : '';?>&facid=<?=$facid;?>&anio=<?=$anio?>&mes=<?=$mes?>"><?php print img_delete(); ?></a>
						</td>
<?php 
					}
?>
				</tr>
<?php 
				$nom_soc = "";
				if ($pol->societe_type == 1) {
					if (!$ctas->fetch_by_Cta($poldet->cuenta, true)) {
						if ($soc->fetch($f->socid)) {	
							dol_syslog("Societe Type = 1");
							$nom_soc = $soc->nom;
						}
					}
				} else if($pol->societe_type == 2) {
					if (!$ctas->fetch_by_Cta($poldet->cuenta, true)) {
						if ($soc->fetch($ff->socid)) {
							dol_syslog("Societe Type = 2");
							$nom_soc = $soc->nom;
						}
					}
				}
				if ($nom_soc) {
?>
					<tr <?php print $bc[$var]; ?>>
						<td></td>
						<td colspan="5"><?php print $nom_soc;?></td>
					</tr>
<?php 
				} else {
					$ctas->fetch_by_Cta($poldet->cuenta);
?>
					<tr <?php print $bc[$var]; ?>>
						<td></td>
						<td colspan="5"><?php print $ctas->descta;?></td>
					</tr>
<?php 
				}
				$i ++;
				$id = $poldet->id;
				$rr = $poldet->fetch_next($id, $cond);
			}
		}
		$id = $pol->id;
		
		if ($esfaccte == 1 || $esfacprov == 1) {
			$soc_type = ($esfaccte == 1) ? 1 : 2;
			$row = $pol->fetch_next_by_facture_id($id, $facid, $soc_type);
		} else {
			$row = $pol->fetch_next($id, 1);
			dol_syslog("Se regresa este valor del Fetch_Next=".$row);
		}
?>
		</table>
		<br><br>
<?php 
	}
?>