<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 * 					JPFarber - jfarber55@hotmail.com, jpfarber@gmail.com
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
 
if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpaymentterm.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabctassupplier.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
	include_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
}

require_once DOL_DOCUMENT_ROOT . '/fourn/class/fournisseur.facture.class.php';
class ActionsContab
{
	
	private $helper;
	private $db;
	
	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function doActions($parameters, &$object, &$action, $hookmanager)
	{
		$this->db = $hookmanager->db;
		
		dol_syslog("action=$action");
		//require_once DOL_DOCUMENT_ROOT . '/doliconta/class/EntriesHelper.class.php';
		
		//$this->helper = new EntriesHelper($hookmanager->db, null);
		/*
		if (in_array('invoicecard', explode(':', $parameters['context']))) {
			//
		}
		$error = 0; // Error counter
		$myvalue = 'test'; // A result value
		
		if (in_array('somecontext', explode(':', $parameters['context'])))
		{
			// do something only for the context 'somecontext'
		}

		if (! $error)
		{
			$this->results = array('myreturn' => $myvalue);
			$this->resprints = 'A text to show';
			return 0; // or return 1 to replace standard code
		}
		else
		{
			$this->errors[] = 'Error message';
			return -1;
		} */
		
/* 		if ($action == "create") {
			if ($context == "paymentsupplier") {
				
					
				$sql = "Select * From llx_facture_fourn_det Where fk_facture_fourn = ".GETPOST("facid");
				dol_syslog("doActions:: action=$action, context=$context, $sql=$sql");
				$mismo_iva = true;
				$total_ammount = 0;
				if ($res = $this->db->query($sql)) {
					$obj = $this->db->fetch_object($res);
					$tva_tx = $obj->tva_tx;
					while ($obj) {
						//var_dump($obj);
						$total_ammount += $obj->total_ttc;
						if ($obj->tva_tx !== $tva_tx) {
							$mismo_iva = false;
						}
						$obj = $this->db->fetch_object($res);
					}
				}
				dol_syslog("mismo_iva=".($mismo_iva?"true":"false").", total_amount=$total_ammount, amount_1=".GETPOST("amount_1"));
				if ((!$mismo_iva) && (GETPOST("amount_1") !== $total_ammount)) {
					dol_syslog("ERROR========");
					$this->errors[] = 'En facturas con articulos que estén a Tasa de Impuesos Diferentes, el pago debe de realizarse en una sola exhibición';
					$this->mesg[] = "No se si sirva esto";
					return -1;
				} else {
					return 0;
				}
			}
		}
		*/
		if (in_array('invoicecard', explode(':', $parameters['context']))) {
			if ($action == "valid") {
				dol_syslog("Facture Id = ".$object->id);
				// var_dump($parameters);
				//var_dump($object);
				$mismo_iva = true;
				$sql = "Select * From ".MAIN_DB_PREFIX."facturedet Where fk_facture = ".$object->id;
				dol_syslog("actions_contab:: doActions - action=$action, sql=$sql");
				if ($res = $this->db->query($sql)) {
					$i = 0;
					while ($obj = $this->db->fetch_object($res)) {
						dol_syslog("Analizando datos del detalle de la factura, rowid=".$obj->rowid);
						if ($i == 0) {
							dol_syslog("i=$i");
							$tva_tx = $obj->tva_tx;
							$i ++;
						} else {
							dol_syslog("i=$i");
							if ($tva_tx != $obj->tva_tx) {
								$mismo_iva = false;
							}
						}
					}
				}
				$this->error = 0;
				//$this->errors = array();
				dol_syslog("actions_contab:: doActions mismo_iva=".($mismo_iva?"true":"false"));
				if ((!$mismo_iva)) { // && (GETPOST("amount_1") !== $total_ammount)) {
					/* dol_syslog("ERROR========");
					 $this->errors[] = 'En facturas con articulos que estén a Tasa de Impuesos Diferentes, el pago debe de realizarse en una sola exhibición';
					return 0; */
					$this->error++;
					$this->errors = "<div>NOTA: <text style='color:#f2d003;'>Para facturas con condicion de pago 50/50, no se permiten productos con tasas de impuesto diferentes.</div>";
					$this->resaction = -1;
					return -1;
				} else {
					return 0;
				}
			}
		}
		if (in_array('invoicesuppliercard', explode(':', $parameters['context']))) {
			if ($action == "add_paiement") {  //Pago a un Proveedor
						
				$sql = "Select * From ".MAIN_DB_PREFIX."facture_fourn_det Where fk_facture_fourn = ".GETPOST("facid");
				dol_syslog("doActions:: action=$action, context=$context, $sql=$sql");
				$mismo_iva = true;
				$total_ammount = 0;
				if ($res = $this->db->query($sql)) {
					$obj = $this->db->fetch_object($res);
					$tva_tx = $obj->tva_tx;
					while ($obj) {
						//var_dump($obj);
						$total_ammount += $obj->total_ttc;
						if ($obj->tva_tx !== $tva_tx) {
							$mismo_iva = false;
						}
						$obj = $this->db->fetch_object($res);
					}
				}
				dol_syslog("mismo_iva=".($mismo_iva?"true":"false").", total_amount=$total_ammount, amount_1=".GETPOST("amount_1"));
				if ((!$mismo_iva) && (GETPOST("amount_1") !== $total_ammount)) {
					dol_syslog("ERROR========");
					$this->errors[] = 'En facturas con articulos que estén a Tasa de Impuesos Diferentes, el pago debe de realizarse en una sola exhibición';
					$this->mesg[] = "No se si sirva esto";
					return -1;
				} else {
					return 0;
				}
			}
		}
	} 
	
	function printObjectLine($parameters, &$object, &$action, $hookmanager) {
		//var_dump($parameters);
		//var_dump($object);
		//var_dump($action);
		///var_dump($hookmanager); 
		
		if (in_array('invoicesuppliercard', explode(':', $parameters['context']))) {
			//var_dump($parameters);
			$es_fac_servicio = false;
			$mismo_iva = true;
			$tva_tx = 0;
			$sql = "Select * From ".MAIN_DB_PREFIX."facture_fourn_det Where fk_facture_fourn = ".$object->id;
			dol_syslog("Invoicesuppliercard - actions_contab:: printObjectLine sql=$sql");
			if ($res = $this->db->query($sql)) {
				$i = 0;
				$obj = $this->db->fetch_object($res);
				dol_syslog("rowid=".$obj->rowid.", param=".$parameters["line"]->rowid);
				if ($obj->rowid == $parameters["line"]->rowid) {
					while ($obj) {
						//Para ver si es una factura de servicio o de productos.
						if ($obj->product_type == 1) {
							$es_fac_servicio = true;
							//$fk_facture_fourn = $obj->fk_facture_forun;
						}
						dol_syslog("Analizando datos del detalle de la factura, rowid=".$obj->rowid);
						if ($i == 0) {
							dol_syslog("i=$i");
							$tva_tx = $obj->tva_tx;
							$i ++;
						} else {
							dol_syslog("i=$i");
							if ($tva_tx != $obj->tva_tx) {
								$mismo_iva = false;
							}
						}
						$obj = $this->db->fetch_object($res);
					}
				}
				dol_syslog("actions_contab:: printObjectLine mismo_iva=".($mismo_iva?"true":"false"));
				if ((!$mismo_iva)) { // && (GETPOST("amount_1") !== $total_ammount)) {
					/* dol_syslog("ERROR========");
					 $this->errors[] = 'En facturas con articulos que estén a Tasa de Impuesos Diferentes, el pago debe de realizarse en una sola exhibición';
					return 0; */
					print "<br><strong>NOTA: <text style='color:#f2d003;'>Para facturas a Proveedor que tienen varios articulos con Tasa de Impuestos Difdrentes, el pago debe realizarse en su totalidad en una sola exhibicion.</text></strong><br><br>";
				} 
				if ($es_fac_servicio) {
					dol_syslog("actions_contab:: es factura de servicio=".($es_fac_servicio ? 'si' : 'no'));
					$fac = new FactureFournisseur($this->db); 
					$fac->fetch($object->id);
					
					dol_syslog("La condición de pago de la factura es:".$fac->cond_reglement_id." - ".$fac->cond_reglement_code);
					$payment = new Contabpaymentterm($this->db);
					$payment->fetch_by_cond_reglement($fac->cond_reglement_id);
					//$payment->fetch($fac->cond_reglement_id);
					$cond_pago = $payment->cond_pago;
					
					if ($cond_pago != $payment::PAGO_AL_CONTADO) {
						print "<br><strong>NOTA: <text style='color:#f2d003;'>Las Facturas de Servicio no pueden ser a Credito o 50/50.</text></strong><br><br>";
					}
				}
			}
			return 0;
		} else if (in_array('invoicecard', explode(':', $parameters['context']))) {
			$mismo_iva = true;
			$tva_tx = 0;
			$sql = "Select * From ".MAIN_DB_PREFIX."facturedet Where fk_facture = ".$object->facid;
			dol_syslog("Invoicecard - actions_contab:: printObjectLine sql=$sql");
			if ($res = $this->db->query($sql)) {
				$i = 0;
				while ($obj = $this->db->fetch_object($res)) {
					dol_syslog("Analizando datos del detalle de la factura, rowid=".$obj->rowid);
					if ($i == 0) {
						dol_syslog("i=$i");
						$tva_tx = $obj->tva_tx;
						$i ++;
					} else {
						dol_syslog("i=$i");
						if ($tva_tx != $obj->tva_tx) {
							$mismo_iva = false;
						}
					}
				}
			}
			dol_syslog("actions_contab:: printObjectLine mismo_iva=".($mismo_iva?"true":"false"));
			if ((!$mismo_iva)) { // && (GETPOST("amount_1") !== $total_ammount)) {
				/* dol_syslog("ERROR========");
				 $this->errors[] = 'En facturas con articulos que estén a Tasa de Impuesos Diferentes, el pago debe de realizarse en una sola exhibición';
				return 0; */
				print "<br><br>	<strong>NOTA: <text style='color:#f2d003;'>Para facturas a Clientes que tienen varios articulos con Tasa de Impuestos Difdrentes, el pago debe realizarse en su totalidad en una sola exhibicion.</text></strong><br><br>";
				return -1;
			} else {
				return 0;
			}
		} else if (in_array('paiementcard', explode(':', $parameters['context']))) {
			//var_dump($object);
			//var_dump($parameters);
			$mismo_iva = true;
			$sql = "Select * From ".MAIN_DB_PREFIX."facturedet Where fk_facture = ".$object->facid;
			dol_syslog("Paiementcard - actions_contab:: printObjectLine sql=$sql");
			if ($res = $this->db->query($sql)) {
				$i = 0;
				while ($obj = $this->db->fetch_object($res)) {
					dol_syslog("Analizando datos del detalle de la factura, rowid=".$obj->rowid);
					if ($i == 0) {
						dol_syslog("i=$i");
						$tva_tx = $obj->tva_tx;
						$i ++;
					} else {
						dol_syslog("i=$i");
						if ($tva_tx != $obj->tva_tx) {
							$mismo_iva = false;
						}
					}
				}
			}
			dol_syslog("actions_contab:: printObjectLine mismo_iva=".($mismo_iva?"true":"false"));
			if ((!$mismo_iva)) { // && (GETPOST("amount_1") !== $total_ammount)) {
				/* dol_syslog("ERROR========");
				 $this->errors[] = 'En facturas con articulos que estén a Tasa de Impuesos Diferentes, el pago debe de realizarse en una sola exhibición';
				return 0; */
				print "<br><br>	<strong>NOTA: <text style='color:#f2d003;'>Para facturas a Clientes que tienen varios articulos con Tasa de Impuestos Difdrentes, el pago debe realizarse en su totalidad en una sola exhibicion.</text></strong><br><br>";
				return -1;
			} else {
				return 0;
			}
		}
		return 0;
	}
	
	function paymentsupplierinvoices($parameters, &$object, &$action, $hookmanager) {
		/* var_dump("Parameters");
		var_dump($parameters);
		var_dump("Post");
		var_dump($_POST);
		var_dump("hookmanager");
		var_dump($hookmanager);  */
		global $conf;
		
		$sql = "SELECT f.rowid as facid, f.ref, f.ref_supplier, f.total_ht, f.total_ttc, f.datef as df, ";
		$sql .= "SUM(pf.amount) as am "; 
		$sql .= "FROM ".MAIN_DB_PREFIX."facture_fourn as f ";
		$sql .= "LEFT JOIN ".MAIN_DB_PREFIX."paiementfourn_facturefourn as pf ON pf.fk_facturefourn = f.rowid ";
		$sql .= "WHERE f.entity = ".$conf->entity." AND f.fk_soc = ".$object->socid." AND f.paye = 0 AND f.fk_statut = 1 "; 
		$sql .= "GROUP BY f.rowid, f.ref, f.ref_supplier, f.total_ht, f.total_ttc, f.datef";
		
		//$sql .= "WHERE f.rowid = ".$parameters["facid"]." AND f.paye = 0 AND f.fk_statut = 1 ";
		
		dol_syslog("doActions:: action=$action, context=$context, sql=$sql");
		$mismo_iva = true;

		/* $total_ammount = 0;
		if ($res = $this->db->query($sql)) {
			$obj = $this->db->fetch_object($res);
			$tva_tx = $obj->tva_tx;
			while ($obj) {
				//var_dump($obj);
				$total_ammount += $obj->total_ttc;
				if ($obj->tva_tx !== $tva_tx) {
					$mismo_iva = false;
				}
				$obj = $this->db->fetch_object($res);
			}
		} */
		dol_syslog("a. actions_contab.php - paymentsupplierinvoices:: sql=$sql");
		$i = 0;
		if ($res = $this->db->query($sql)) {
			while ($obj = $this->db->fetch_object($res)) {
				$i = 0;
				$sql = "Select * From ".MAIN_DB_PREFIX."facture_fourn_det Where fk_facture_fourn = ".$obj->facid;
				dol_syslog("b. actions_contab.php - paymentsupplierinvoices:: sql=$sql");
				if ($rs = $this->db->query($sql)) {
					while ($oj = $this->db->fetch_object($rs)) {
						if ($i == 0) {
							dol_syslog("i=$i");
							$tva_tx = $oj->tva_tx;
							$i ++;
						} else {
							dol_syslog("i=$i, tva_tx=$tva_tx, oj->tva_tx=".$oj->tva_tx);
							if ($tva_tx != $oj->tva_tx) {
								$mismo_iva = false;
							}
						}
					}
				}
			}
		}
		
		dol_syslog("mismo_iva=".($mismo_iva?"true":"false"));
		if ((!$mismo_iva)) { // && (GETPOST("amount_1") !== $total_ammount)) {
			/* dol_syslog("ERROR========");
			$this->errors[] = 'En facturas con articulos que estén a Tasa de Impuesos Diferentes, el pago debe de realizarse en una sola exhibición';
			return 0; */
			print "<br><br>	<strong>NOTA: <text style='color:#f2d003;'>Para el pago de facturas a Proveedor que tienen varios articulos con Tasa de Impuestos Difdrentes, el pago debe realizarse en su totalidad en una sola exhibicion.</text></strong><br><br>";
			return 0;
		} else {
			return 0;
		}
	}
	
	/* function formCreateProductOptions($parameters, &$object, &$action, $hookmanager) {
			
		dol_syslog("formCreateProductOptions =========>");
		if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php')) {
			include_once DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php';
		} else {
			include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabctassupplier.class.php';
		}
		
		if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
			include_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
		} else {
			include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
		}
			
		$this->db = $hookmanager->db;
		
		//Obtenemos el id del tercero.
		$socid = $object->socid;
		//Buscamos las cuentas que están relacionadas con el tercero.
		$op = "";
		$id = 0;
		//print "uno";
		$sup = new Contabctassupplier($this->db);
		//print "dos";
		if ($sup->fetch_next($id, $socid) > 0) {
			//print "tres";
			$cat = new Contabcatctas($this->db);
			//print "cuatro";
			$cat->fetch($sup->fk_cta);
			//print "cinco";
			$op .= "<option value='".$cat->id."'>".$cat->cta." - ".$cat->descta."</option>";
			$id = $sup->rowid;
		}
		//print "seis";
		print "<br>Cuenta de Servicio: <select name='ddl_contab_id_cuenta'><option value='0'></option>".$op."</select>";
		
		return 0;
	} */
	
/* 	function formViewProductOptions($parameters, &$object, &$action, $hookmanager) {
		if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php')) {
			include_once DOL_DOCUMENT_ROOT.'/contab/class/contabctassupplier.class.php';
		} else {
			include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabctassupplier.class.php';
		}
	
		if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php')) {
			include_once DOL_DOCUMENT_ROOT.'/contab/class/contabcatctas.class.php';
		} else {
			include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabcatctas.class.php';
		}
	
		$this->db = $hookmanager->db;
		
		//var_dump($parameters);
		//var_dump($object);
		
		//Obtenemos el id del tercero.
		$socid = $object->socid;
		//Buscamos la cuenta que fue seleccionada en la captura de la linea de factura
		$rowid = $parameters["line"]->rowid;
		$sql = "Select * From llx_contab_fourn_product_line Where fk_facture=".$object->id." And rowid_line = '$rowid'";
		$res = $this->db->query($sql);
		dol_syslog("formViewProductOptions:: sql:".$sql);
		if ($res) {
			$row = $this->db->fetch_row($res);
			if ($row[1] > 0) {
				$sup = new Contabctassupplier($this->db);
				$arr_sup = $sup->fetch_array_by_socid($socid);
				foreach ($arr_sup as $i => $ss) {
					if ($row[3] == $ss->fk_cta) {
						$cat = new Contabcatctas($this->db);
						$cat->fetch($row[3]);
						print "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$cat->cta." - ".$cat->descta;
					}
				}
			}
		}
		return 0;
	} */
	
	function formViewProductSupplierOptions($parameters, &$object, &$action, $hookmanager) {
		$this->db = $hookmanager->db;
		
		//var_dump($parameters);
		//var_dump($object);
		
		//Obtenemos el id del tercero.
		$socid = $object->socid;
		//Buscamos la cuenta que fue seleccionada en la captura de la linea de factura
		$rowid = $parameters["line"]->rowid;
		$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture=".$object->id." And rowid_line = '$rowid'";
		$res = $this->db->query($sql);
		dol_syslog("formViewProductOptions:: sql:".$sql);
		if ($res) {
			$row = $this->db->fetch_row($res);
			if ($row[1] > 0) {
				$sup = new Contabctassupplier($this->db);
				$arr_sup = $sup->fetch_array_by_socid($socid);
				foreach ($arr_sup as $i => $ss) {
					if ($row[3] == $ss->fk_cta) {
						$cat = new Contabcatctas($this->db);
						$cat->fetch($row[3]);
						print "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$cat->cta." - ".$cat->descta;
					}
				}
			}
		}
		return 0;
	}
	
 	function formCreateProductSupplierOptions($parameters, &$object, &$action, $hookmanager) {
 		//var_dump("===== POST::formCreateProductSupplierOptions =====");
 		//var_dump($_POST);
 		
 		dol_syslog("formCreateProductSupplierOptions =========>");
 		
 		$this->db = $hookmanager->db;
		
		//Obtenemos el id del tercero.
		$socid = $object->socid;
		//Buscamos las cuentas que están relacionadas con el tercero.
		$op = "";
		$id = 0;
		//print "uno";
		$sup = new Contabctassupplier($this->db);
		//print "dos";
		while ($sup->fetch_next($id, $socid) > 0) {
			//print "tres";
			$cat = new Contabcatctas($this->db);
			//print "cuatro";
			$cat->fetch($sup->fk_cta);
			//print "cinco";
			$op .= "<option value='".$cat->id."'>".$cat->cta." - ".$cat->descta."</option>";
			$id = $sup->id;
		}
		//print "seis";
		//print "<input type='text' name='fourn_type' value='".$a."' />";
		print "<br>Cuenta de Servicio: <select name='ddl_contab_id_cuenta'><option value='0'></option>".$op."</select>";
		
		return 0;
	}
	
	function formObjectOptions($parameters, &$object, &$action, $hookmanager) {  //formViewProductSupplierOptions
		global $conf;
		
		$this->db = $hookmanager->db;
		
		if (in_array('invoicecard', explode(':', $parameters['context']))) {
			dol_syslog("formObjectOptions - invoicecard");
			$mismo_iva = true;
			$sql = "Select * From ".MAIN_DB_PREFIX."c_payment_term Where rowid = ".$object->cond_reglement_id; //." AND entity = ".$conf->entity;
			dol_syslog("Buscando a ver si es un pago 50/50");
			if ($res = $this->db->query($sql)) {
				if ($obj = $this->db->fetch_object($res)) {
					if ($obj->code == "PT_5050") {
						dol_syslog("Si es una factura 50/50, entonces ver que todos los IVA's sean iguales");
						
						if ($parameters[line]->product_type == 0) {
							//Es la captura de productos.  se supone que todas la lineas serían productos también.
							//No se puede capturar productos y servicios en la misma factura.
							foreach ($object->lines as $i => $line) {
								//var_dump($line);
								if ($i == 0) {
									$tva_tx = $line->tva_tx;
								} else {
									if ($line->tva_tx !== $tva_tx) {
										$mismo_iva = false;
									}
								}
							}
							dol_syslog("mismo_iva=$mismo_iva");
							if (!$mismo_iva) {
								print "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;===> <text style='color:#f2d003;'><strong>En una factura con condicion de pago 50/50, no pueden existir productos con tasas de IVA diferentes. !!!! FAVOR DE CORREGIR</strong></text> <===<br><br>";
							}
						}
						
					} else {
						dol_syslog("No es una factura 50/50, así que no hay problema con el pago.");
					}
				}
			}
			
			$sql = "Select * From ".MAIN_DB_PREFIX."facturedet Where fk_facture = ".GETPOST("facid");
			dol_syslog("Verificando tasas de iva diferentes");
			$mismo_iva = true;
			$total_ammount = 0;
			if ($res = $this->db->query($sql)) {
				$obj = $this->db->fetch_object($res);
				$tva_tx = $obj->tva_tx;
				while ($obj) {
					//var_dump($obj);
					if ($obj->tva_tx !== $tva_tx) {
						$mismo_iva = false;
					}
					$obj = $this->db->fetch_object($res);
				}
			}
			dol_syslog("mismo_iva=".($mismo_iva?'true':'false'));
			if (!$mismo_iva) {
				print "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;===> <text style='color:#f2d003;'><strong>En facturas con productos con tasas de impuestos diferentes, el pago debe realizarse al contado y en una sola exhibicion.</strong></text> <===<br><br>";
				return -1;
			} else {
				return 0;
			}
		}
		
		/* if (in_array('invoicesuppliercard', explode(':', $parameters['context']))) {
			dol_syslog("formObjectOptions - invoicesuppliercard");
			//var_dump($object);
			//var_dump($parameters);
			$socid = $object->socid;
			//Buscamos la cuenta que fue seleccionada en la captura de la linea de factura
			$rowid = $parameters["line"]->rowid;
			$sql = "Select * From llx_contab_fourn_product_line Where fk_facture=".$object->id." And rowid_line = '$rowid'";
			$res = $this->db->query($sql);
			dol_syslog("formObjectOptions:: sql:".$sql);
			if ($res) {
				$row = $this->db->fetch_row($res);
				if ($row[1] > 0) {
					$sup = new Contabctassupplier($this->db);
					$arr_sup = $sup->fetch_array_by_socid($socid);
					foreach ($arr_sup as $i => $ss) {
						//dol_syslog("row=".$row[0]."-".$row[1]."-".$row[2]."-".$row[3]);
						if ($row[3] == $ss->fk_cta) {
							$cat = new Contabcatctas($this->db);
							$cat->fetch($row[3]);
							print "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$cat->cta." - ".$cat->descta;
						}
					}
				}
			}
		} */
		return 0;
	}
	
	function formEditProductOptions($parameters, &$object, &$action, $hookmanager) {
		dol_syslog("formCreateProductSupplierOptions =========>");

		$this->db = $hookmanager->db;
		
		$sql = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_facture='".GETPOST('id')."' And rowid_line = '".GETPOST('lineid')."'";
		$res = $this->db->query($sql);
		if ($res) {
			$row = $this->db->fetch_row($res);
			if ($row) {
				$id_cta = $row[3];
			}
		}
		
		//Obtenemos el id del tercero.
		$socid = $object->socid;
		//Buscamos las cuentas que están relacionadas con el tercero.
		$op = "";
		$id = 0;
		//print "uno";
		$sup = new Contabctassupplier($this->db);
		//print "dos";
		if ($sup->fetch_next($id, $socid) > 0) {
			//print "tres";
			$cat = new Contabcatctas($this->db);
			//print "cuatro";
			$cat->fetch($sup->fk_cta);
			//print "cinco";
			$op .= "<option value='".$cat->id."'";
			if ($sup->fk_cta == $id_cta) {
				$op .= "selected='selected'";
			}
			$op .= ">".$cat->cta." - ".$cat->descta."</option>";
			$id = $sup->rowid;
		}
		//print "seis";
		print "<br>Cuenta de Servicio: <select name='ddl_contab_id_cuenta'><option value='0'></option>".$op."</select>";
		
		return 0;
	}
	
	
 	function formAddObjectLine($parameters, &$object, &$action, $hookmanager) {
 		global $conf;
 		
 		//var_dump($object);
 		$this->db = $hookmanager->db;
 		
 		if ($parameters[line]->product_type == 0) {
			$sql = "Select * From ".MAIN_DB_PREFIX."c_payment_term Where rowid = ".$object->cond_reglement_id; //." AND entity = ".$conf->entity;
			dol_syslog("Buscando a ver si es un pago 50/50");
			if ($res = $this->db->query($sql)) {
				if ($obj = $this->db->fetch_object($res)) {
					if ($obj->code == "PT_5050") {
						dol_syslog("Si es una factura 50/50, entonces ver que todos los IVA's sean iguales");
							
						if ($parameters[line]->product_type == 0) {
							//Es la captura de productos.  se supone que todas la lineas serían productos también.
							//No se puede capturar productos y servicios en la misma factura.
							$mismo_iva = true;
							foreach ($object->lines as $i => $line) {
								//var_dump($line);
								if ($i == 0) {
									$tva_tx = $line->tva_tx;
								} else {
									if ($line->tva_tx !== $tva_tx) {
										$mismo_iva = false;
									}
								}
							}
							if (!$mismo_iva) {
								print "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;===> <text style='color:#f2d003;'><strong>En una factura con condicion de pago 50/50, no pueden existir productos con tasas de IVA diferentes. !!!! FAVOR DE CORREGIR</strong></text> <===<br><br>";
							}
						}
							
					} else {
						dol_syslog("No es una factura 50/50, así que no hay problema con el pago.");
					}
				}
			}
 		}
		return 0;
	} 
	
	/* function addMoreActionsButtons($parameters, &$object, &$action, $hookmanager) {
		//var_dump($parameters);
		//var_dump($object);
		dol_syslog("addMoreActionsButtons - Facture Id = ".$object->id);
		// var_dump($parameters);
		//var_dump($object);
		$mismo_iva = true;
		$sql = "Select * From llx_facturedet Where fk_facture = ".$object->id;
		dol_syslog("actions_contab:: addMoreActionsButtons - action=$action, sql=$sql");
		if ($res = $this->db->query($sql)) {
			$i = 0;
			while ($obj = $this->db->fetch_object($res)) {
				dol_syslog("Analizando datos del detalle de la factura, rowid=".$obj->rowid);
				if ($i == 0) {
					dol_syslog("i=$i");
					$tva_tx = $obj->tva_tx;
					$i ++;
				} else {
					dol_syslog("i=$i");
					if ($tva_tx != $obj->tva_tx) {
						$mismo_iva = false;
					}
				}
			}
		}
		$this->error = 0;
		//$this->errors = array();
		dol_syslog("actions_contab:: addMoreActionsButtons mismo_iva=".($mismo_iva?"true":"false"));
		if ((!$mismo_iva)) { // && (GETPOST("amount_1") !== $total_ammount)) {
			$this->error++;
			$this->errors = "<div>NOTA: <text style='color:red'>Para facturas con condición de pago 50/50, no se permiten productos con tasas de impuesto diferentes.</div>";
			$this->resaction = -1;
			return -1;
		} else {
			return 0;
		}
	} */
	
 	function formBuilddocOptions($parameters, &$object, &$action, $hookmanager) {
 		global $conf;
 		
		//var_dump($parameters);
		//var_dump($object);
		dol_syslog("Facture ref = ".$object->ref);
		// var_dump($parameters);
		//var_dump($object);
		$mismo_iva = true;
		$sql = "Select * From ".MAIN_DB_PREFIX."facture_fourn_det Where fk_facture_fourn = (Select rowid From ".MAIN_DB_PREFIX."facture_fourn Where ref='".$object->ref."' AND entity = ".$conf->entity.")";
		dol_syslog("actions_contab:: formBuilddocOptions - action=$action, sql=$sql");
		if ($res = $this->db->query($sql)) {
			$i = 0;
			while ($obj = $this->db->fetch_object($res)) {
				dol_syslog("Analizando datos del detalle de la factura, rowid=".$obj->rowid);
				if ($i == 0) {
					dol_syslog("i=$i");
					$tva_tx = $obj->tva_tx;
					$i ++;
				} else {
					dol_syslog("i=$i");
					if ($tva_tx != $obj->tva_tx) {
						$mismo_iva = false;
					}
				}
			}
		}
		$this->error = 0;
		//$this->errors = array();
		dol_syslog("actions_contab:: formBuilddocOptions mismo_iva=".($mismo_iva?"true":"false"));
		if ((!$mismo_iva)) { // && (GETPOST("amount_1") !== $total_ammount)) {
			
			$this->error++;
			$this->errors = "<div>NOTA: <text style='color:#f2d003;'>Para facturas con condicion de pago 50/50, no se permiten productos con tasas de impuesto diferentes.</div>";
			$this->resaction = -1;
			return -1;
		} else {
			return 0;
		}
	}
}
?>