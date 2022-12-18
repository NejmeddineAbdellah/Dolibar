<?php

/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
* 					JPFarber - jfarber55@hotmail.com
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* code pour créer le module 106, 117, 97, 110, b, 112, 97, 98, 108, 11, b, 102, 97, 114, 98, 101, 114
*/

/* TODO: falta por hacer que al generar las Cargas Sociales se generen pólizas automáticas.
 *
*/

/**
 * 	\file		contab/core/generate_polizas.class.php
* 	\ingroup	contab
* 	\brief		Poliza Generator Class
* 	\remarks	This Class not uses jet hooks.
*/

/**
 * Contab Poliza Generator Class
*/

if (!$res && file_exists("../main.inc.php"))
	$res = @include '../main.inc.php';     // to work if your module directory is into dolibarr root htdocs directory
if (!$res && file_exists("../../main.inc.php"))
	$res = @include '../../main.inc.php';   // to work if your module directory is into a subdir of root htdocs directory
if (!$res && file_exists("../../../main.inc.php"))
	$res = @include '../../../main.inc.php';     // Used on dev env only
if (!$res && file_exists("../../../../main.inc.php"))
	$res = @include '../../../../main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpolizas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabctassupplier.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/fournisseur.facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/fournisseur.facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/fournisseur.facture.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpaymentterm.class.php';
}

class PolizaGenerator extends CommonObject 
{
	var $db;
	
	var $facid;
	var $tipo_fac;
	
	const FACTURA_TIPO_CLIENTE = 1;
	const FACTURA_TIPO_PROVEEDOR = 2;
	
	function __construct($db)
	{
		$this->db = $db;
	
		return 1;
	}
	
	function Crear_Polizas_Proveedores($user) {
		global $conf;
		
		dol_syslog("Estoy en poliza_generator.class.php (Crear_Polizas_Proveedores)");
		
		$tipo_fac = GETPOST("tipo_fac");
		
		//Tipo factura = 1 ==> es una factura de cliente y 2 ==> Factura Proveedor
		if ($this->facid > 0 && $this->tipo_fac == $this::FACTURA_TIPO_PROVEEDOR) {
		
			$pol = new Contabpolizas($this->db);
		
			$sql = "SELECT f.rowid, s.nom, f.ref, f.datef, f.type, f.fk_cond_reglement, b.dateo, pf.amount, pa.code, pa.libelle, pai.rowid as paimid ";
			$sql .= " FROM ".MAIN_DB_PREFIX."".MAIN_DB_PREFIX."facture_fourn as f ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."paiementfourn_facturefourn as pf ON f.rowid = pf.fk_facturefourn ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."societe as s ON f.fk_soc = s.rowid ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."paiementfourn as pai ON pf.fk_paiementfourn = pai.rowid ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."bank as b on pai.fk_bank = b.rowid ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."c_paiement pa ON pai.fk_paiement = pa.id ";
			$sql .= " LEFT JOIN (Select * From ".MAIN_DB_PREFIX."contab_polizas Where societe_type = 2) as cp ON f.rowid = cp.fk_facture ";
			$sql .= " WHERE f.entity = 1 AND cp.rowid is null AND f.rowid = ".$this->facid;  // AND f.paye = 1 AND f.fk_statut = 2 
			$sql .= " ORDER BY f.ref ";
		
			dol_syslog("Se muestran las facturas sin pólizas en base al sql=$sql");
			if ($res = $this->db->query($sql)) {
				if ($obj = $this->db->fetch_object($res)) {
					
					$fac = new FactureFournisseurs($this->db);
					
					$fac->fetch($obj->rowid);
					foreach ($fac->lines as $i => $l) {
						//var_dump("*".$l->fk_product."*");
						if ($l->product_type == 1 && !$l->fk_product) {
							//Esta rutina es para agregar los datos a la tabla llx_contab_fourn_product_line
							//ya que si se instala este módulo después de haber facturas generadas, esta tabla se encontrará
							//bacía, por lo que para fines del sistema debe estar llena para todos los proveedores que sean
							//de gastos(servicios) o proveedores de activo (fijo).
							//Si la información ya existe en la tabla no hace nada, de lo contario, inserta el registro relacionado
							//en base a los datos que se le dieron de alta al proveedor.
							$this->asign_forun_product_line($fac->id, $fac->fk_soc, $l);
						}
					}
					
					//Saber si es una factura Estandar, NC o cualquier otra?
					if ($obj->type == $fac::TYPE_STANDARD) {
						
						//Saber si es una compra al contado o a crédito
						$cond_pago = 1;
						$payment = new Contabpaymentterm($this->db);
						$payment->fetch($obj->fk_cond_reglement);
						if ($payment->cond_pago) {
							$cond_pago = $payment->cond_pago;
						}
						if ($cond_pago == $payment::PAGO_AL_CONTADO) {
							dol_syslog("Es una poliza de Proveedor, Compra al Contado - facid=".$obj->rowid);
							//No se realiza el registro directamente por el o los pagos realizados.
							while ($obj) {
								dol_syslog("Se genera la póliza por el pago al contado por el monto de esta transacción: paimid=".$obj->paimid);
								$pol->Proveedor_Pago_Factura2($obj->paimid, $user, $conf);
								$obj = $this->db->fetch_object($res);
							}
						} else if ($cond_pago == $payment::PAGO_A_CREDITO) {
							dol_syslog("Es una poliza de Proveedor, Compra a Crédito - facid=".$obj->rowid);
							//var_dump($conf);
							//var_dump($user);
							//como es a credito, primero se guarda la poliza por la compra a credito.
							dol_syslog("Se genera la póliza por compra a crédito");
							$pol->Proveedor_Compra_a_Credito2($obj->rowid, $user, $conf);
							dol_syslog("Se terminó de generar la póliza por la compra a crédito");
							//Ahora se genera la poliza por el pago de la compra a credito.
							//Puede haber varios pagos, así que hay que ver esto.
							while ($obj) {
								dol_syslog("Se genera la póliza por el pago de la compra que se había realizado a credito para la trans:".$obj->paimid);
								$pol->Proveedor_Pago_Factura2($obj->paimid, $user, $conf);
								dol_syslog("Se busca si hay mas pagos realizados, para hacer la póliza correspondiente");
								$obj = $this->db->fetch_object($res);
							}
						} else if ($cond_pago ==  $payment::PAGO_EN_PARTES) {
							dol_syslog("Es una póliza de Proveedor, Pago en Partes (50/50) - facid=".$obj->rowid);
							dol_syslog("Se genera la póliza de diario por la venta a crédito del 50%");
							dol_syslog("Se genera la póliza de Egresos o Cheques por el pago al contado del 50%");
							dol_syslog("Se genera la póliza de Egresos o Cheques por el pago de la venta a crédito del 50%");
							$pol->Proveedor_Compra_a_Credito2($obj->rowid, $user, $conf);
							while ($obj) {
								dol_syslog("Del pago que se está leyendo de la BD, se realiza la póliza de ingreso por la primera mitad, que fue la compra al contado");
								$pol->Proveedor_Pago_Factura2($obj->paimid, $user, $conf);
								//dol_syslog("Del pago que se está leyendo de la BD, se realiza la póliza de ingreso por la Segunda mitad, osea la contrapartida de la póliza de diario para dejar correcto los registros contables");
								//$pol->Proveedor_Pago_Factura2($obj->paimid, $user, $conf);
								$obj = $this->db->fetch_object($res);
							}
							dol_syslog("Se terminó de generar la póliza por compra 50/50");
						}
					} else {
						//Hasta dolibarr 3.6.2 No había otro tipo de Factura.
					}
				}
			}
		}
	}
	
	function Crear_Polizas_Clientes($user) {
		global $conf;
		
		$error = 0;
		
		dol_syslog("Estoy en poliza_generator.class.php (Crear_Polizas_Clientes)");
	
		$tipo_fac = GETPOST("tipo_fac");
	
		//Tipo factura = 1 ==> es una factura de cliente y 2 ==> Factura Proveedor
		if ($this->facid > 0 && $this->tipo_fac == $this::FACTURA_TIPO_CLIENTE) {
	
			$pol = new Contabpolizas($this->db);
	
			$sql = "SELECT f.rowid, s.nom, f.facnumber, f.datef, f.type, f.fk_cond_reglement, b.dateo, pf.amount, pa.code, pa.libelle, pai.rowid as paimid ";
			$sql .= " FROM ".MAIN_DB_PREFIX."facture as f ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."paiement_facture as pf ON f.rowid = pf.fk_facture ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."societe as s ON f.fk_soc = s.rowid ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."paiement as pai ON pf.fk_paiement = pai.rowid ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."bank as b on pai.fk_bank = b.rowid ";
			$sql .= " INNER JOIN ".MAIN_DB_PREFIX."c_paiement pa ON pai.fk_paiement = pa.id ";
			$sql .= " LEFT JOIN (Select * From ".MAIN_DB_PREFIX."contab_polizas Where societe_type = 1) as cp ON f.rowid = cp.fk_facture ";
			$sql .= " WHERE f.entity = 1 AND cp.rowid is null AND f.rowid = ".$this->facid;  // AND f.paye = 1 AND f.fk_statut = 2
			$sql .= " ORDER BY f.facnumber ";
	
			dol_syslog("Se muestran las facturas sin pólizas en base al sql=$sql");
			if ($res = $this->db->query($sql)) {
				dol_syslog("Entre 1");
				if ($obj = $this->db->fetch_object($res)) {
					dol_syslog("Entre 2");
					$fac = new Factures($this->db);
					$fac->fetch($obj->rowid);
					
					//Saber si es una factura Estandar, NC o cualquier otra?
					if ($obj->type == $fac::TYPE_STANDARD) {
						
						dol_syslog("Tipo de Factura: Estandard");
	
						//Saber si es una compra al contado o a crédito
						$cond_pago = 1;
						$payment = new Contabpaymentterm($this->db);
						$payment->fetch($obj->fk_cond_reglement);
						if ($payment->cond_pago) {
							$cond_pago = $payment->cond_pago;
						}
						
						foreach ($fac->lines as $i => $line) {
							if ($line->desc == "(DEPOSIT)") {
								dol_syslog("Hay un pago anticipado que se debe de resolver primero.");
								$pol->Cliente_Saldar_Pago_Anticipado2($obj->rowid, $user, $conf);
							}
						}
						
						if ($cond_pago == $payment::PAGO_AL_CONTADO) {
							//No se realiza el registro directamente por el o los pagos realizados.
							while ($obj) {
								dol_syslog("Se genera la póliza por el pago al contado por el monto de esta transacción: paimid=".$obj->paimid);
								$pol->Pago_de_Factura2($obj->paimid, $user, $conf);
								$obj = $this->db->fetch_object($res);
							}
						} else if ($cond_pago == $payment::PAGO_A_CREDITO) {
							dol_syslog("Es una poliza a Clientes, Venta a Crédito - facid=".$obj->rowid);
							//var_dump($conf);
							//var_dump($user);
							//como es a credito, primero se guarda la poliza por la compra a credito.
							dol_syslog("Se genera la póliza por venta a crédito");
							$pol->Venta_a_Credito2($obj->rowid, $user, $conf);
							dol_syslog("Se terminó de generar la póliza por la venta a crédito");
							//Ahora se genera la poliza por el pago de la venta a credito.
							//Puede haber varios pagos, así que hay que ver esto.
							while ($obj) {
								dol_syslog("Se genera la póliza por el pago de la compra que se había realizado a credito para la trans:".$obj->paimid);
								$pol->Pago_de_Factura2($obj->paimid, $user, $conf);
								dol_syslog("Se busca si hay mas pagos realizados, para hacer la póliza correspondiente");
								$obj = $this->db->fetch_object($res);
							}
						} else if ($cond_pago ==  $payment::PAGO_EN_PARTES) {
							dol_syslog("Es una poliza a Clientes, Venta 50/50 - facid=".$obj->rowid);
							//Se genera la póliza de diario por el 50% de la venta a credito
							$pol->Venta_a_Credito2($obj->rowid, $user, $conf);
							while ($obj) {
								//Se genera la póliza del ingreso por el 50% del primer pago
								$pol->Pago_de_Factura2($obj->paimid, $user, $conf);
								//Se genera la póliza del ingreso por el 50% que había sido a crédito.
								$pol->Pago_de_Factura2($obj->paimid, $user, $conf);
							}
						}
					} else if ($obj->type == $fac::TYPE_DEPOSIT) {
						//Debe ser a fuerzas al contado por que es pago anticipado
						dol_syslog("Tipo de Factura: Pago Anticipado (DEPOSIT)");
						$cond_pago = 1;
						$payment = new Contabpaymentterm($this->db);
						$payment->fetch($obj->fk_cond_reglement);
						if ($payment->cond_pago) {
							$cond_pago = $payment->cond_pago;
						}
						
						foreach ($fac->lines as $i => $line) {
							if ($line->desc == "(DEPOSIT)") {
								dol_syslog("Hay un pago anticipado que se debe de resolver primero.");
								$pol->Cliente_Saldar_Pago_Anticipado2($obj->rowid, $user, $conf);
							}
						}
						
						if ($cond_pago == $payment::PAGO_AL_CONTADO) {
							//No se realiza el registro directamente por el o los pagos realizados.
							while ($obj) {
								dol_syslog("Se genera la póliza por el pago al contado por el monto de esta transacción: paimid=".$obj->paimid);
								$pol->Pago_de_Factura2($obj->paimid, $user, $conf);
								$obj = $this->db->fetch_object($res);
							}
						} else {
							//var_dump("Error");
							$error ++;
						}
					} else if ($obj->type == $fac::TYPE_CREDIT_NOTE) {
						dol_syslog("Tipo de Factura: Estandard");
						
						//Saber si es una compra al contado o a crédito
						$cond_pago = 1;
						$payment = new Contabpaymentterm($this->db);
						$payment->fetch($obj->fk_cond_reglement);
						if ($payment->cond_pago) {
							$cond_pago = $payment->cond_pago;
						}
							
						if ($cond_pago == $payment::PAGO_AL_CONTADO) {
							//No se realiza el registro directamente por el o los pagos realizados.
							while ($obj) {
								dol_syslog("Se genera la póliza por el pago al contado por el monto de esta transacción: paimid=".$obj->paimid);
								$pol->Pago_de_Factura2($obj->paimid, $user, $conf);
								$obj = $this->db->fetch_object($res);
							}
						}
					}
				}
			} else {
				dol_syslog("Hay que ver por que entro aqui");
			}
		}
		return $error * -1;
	}
	
	function asign_forun_product_line($facid, $socid, $line) {
		$ccs = new Contabctassupplier($this->db);
		if ($ccs->fetch_next(0, $socid)) {
			$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture = ".$facid." and rowid_line = ".$line->rowid." And fk_cat_cta = ".$ccs->fk_cta." And fourn_type = 2";
			dol_syslog("Viendo a ver si no existe este dato en la tabla. sql=$sql");
			if ($res = $this->db->query($sql)) {
				if ($row = $this->db->fetch_object($res)) {
					// Ya exite el registro por lo tanto no se hace nada.
					dol_syslog("Ya existe el registro en la tabla, no se hace nada");
				} else {
					//Hay que agregar este registro a la tabla.
					$sql = "Insert into ".MAIN_DB_PREFIX."contab_fourn_product_line (fk_facture, rowid_line, fk_cat_cta, fourn_type) ";
					$sql .= "Value (".$facid.", ".$line->rowid.", ".$ccs->fk_cta.", 2)";
					dol_syslog("Se agrega el registro a la tabla - sql=$sql");
					$res = $this->db->query($sql);
					if ($res) {
						dol_syslog("Se agregó correctamente el registro en la tabla");
					} else {
						dol_syslog("Se generó un error al querer agregar el registro");
					}
				}
			}
		}
	}
}

?>