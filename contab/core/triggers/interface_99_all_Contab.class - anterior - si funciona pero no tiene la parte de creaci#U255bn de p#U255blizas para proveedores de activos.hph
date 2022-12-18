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
 * 	\file		core/triggers/interface_99_modMyodule_Mytrigger.class.php
 * 	\ingroup	mymodule
 * 	\brief		Sample trigger
 * 	\remarks	You can create other triggers by copying this one
 * 				- File name should be either:
 * 					interface_99_modMymodule_Mytrigger.class.php
 * 					interface_99_all_Mytrigger.class.php
 * 				- The file must stay in core/triggers
 * 				- The class name must be InterfaceMytrigger
 * 				- The constructor method must be named InterfaceMytrigger
 * 				- The name property name must be Mytrigger
 */

/**
 * Trigger class
 */

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabcatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabcatctas.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabrelctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabrelctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabrelctas.class.php';
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

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/facture.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpaymentterm.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/paiment.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/paiment.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/paiment.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/fournisseur.facture.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/fournisseur.facture.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/fournisseur.facture.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/paimentfourn.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/paimentfourn.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/paimentfourn.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabctassupplier.class.php';
}

require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

class InterfaceContab {

    private $db;
    //private $thirdPartyAutoCodes = 0;
    //private $productAutocodes = 0;
    //private $lineDesglose = 0;

    /**
     * Constructor
     *
     * 	@param		DoliDB		$db		Database handler
     */
    public function __construct($db) {
        $this->db = $db;

        $this->name = preg_replace('/^Interface/i', '', get_class($this));
        $this->family = "accountancy";
        $this->description = "Triggers de Contab";
        // 'development', 'experimental', 'dolibarr' or version
        $this->version = '1.1.0';
        $this->picto = 'mymodule@mymodule';
    }

    /**
     * Trigger name
     *
     * 	@return		string	Name of trigger file
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Trigger description
     *
     * 	@return		string	Description of trigger file
     */
    public function getDesc() {
        return $this->description;
    }

    /**
     * Trigger version
     *
     * 	@return		string	Version of trigger file
     */
    public function getVersion() {
        global $langs;
        $langs->load("admin");

        if ($this->version == 'development') {
            return $langs->trans("Development");
        } elseif ($this->version == 'experimental')
            return $langs->trans("Experimental");
        elseif ($this->version == 'dolibarr')
            return DOL_VERSION;
        elseif ($this->version)
            return $this->version;
        else {
            return $langs->trans("Unknown");
        }
    }

    /**
     * Function called when a Dolibarrr business event is done.
     * All functions "run_trigger" are triggered if file
     * is inside directory core/triggers
     *
     * 	@param		string		$action		Event action code
     * 	@param		Object		$object		Object
     * 	@param		User		$user		Object user
     * 	@param		Translate	$langs		Object langs
     * 	@param		conf		$conf		Object conf
     * 	@return		int						<0 if KO, 0 if no triggered ran, >0 if OK
     */
    public function run_trigger($action, $object, $user, $langs, $conf) {
    	dol_syslog("Contab :: run_Trigger - action=$action");
        // Put here code you want to execute when a Dolibarr business events occurs.
        // Data and type of action are stored into $object and $action
        // Users
/*         if ($action == 'USER_LOGIN') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_UPDATE_SESSION') {
            // Warning: To increase performances, this action is triggered only if
            // constant MAIN_ACTIVATE_UPDATESESSIONTRIGGER is set to 1.
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_CREATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_CREATE_FROM_CONTACT') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_MODIFY') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_NEW_PASSWORD') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_ENABLEDISABLE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_LOGOUT') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_SETINGROUP') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'USER_REMOVEFROMGROUP') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } 
 */
        // Groups
/*        elseif ($action == 'GROUP_CREATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'GROUP_MODIFY') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'GROUP_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        }

        // Companies
        elseif ($action == 'COMPANY_CREATE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'COMPANY_MODIFY') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'COMPANY_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        }

        // Contacts
       elseif ($action == 'CONTACT_CREATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'CONTACT_MODIFY') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'CONTACT_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        }

        // Products
        elseif ($action == 'PRODUCT_CREATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'PRODUCT_MODIFY') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'PRODUCT_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        }

        // Customer orders
        elseif ($action == 'ORDER_CREATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_CLONE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_VALIDATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_BUILDDOC') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_SENTBYMAIL') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'LINEORDER_INSERT') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'LINEORDER_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'LINEBILL_INSERT') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	//$this->Cliente_Paga_Factura_Con_NC($object, $user);
        }

        // Supplier orders
        elseif ($action == 'ORDER_SUPPLIER_CREATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_SUPPLIER_VALIDATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_SUPPLIER_SENTBYMAIL') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'SUPPLIER_ORDER_BUILDDOC') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'ORDER_SUPPLIER_APPROVE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        }
 */
        // Bills
		if ($action == 'BILL_VALIDATE') {
            dol_syslog("Trigger " . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            $res = $this->Venta_a_Credito($object, $user, $conf);
            if ($res == -1) {
            	$error++;
            	$this->errors[] = '<div class="error">Para facturas con condiciones de pagos 50/50, no se permiten artículos con Tasa de Impuestos Difdrentes.  Favor de corregir.</div>';
            	//$this->mesg[] = "No se ";
            	return $res;
            }
        } elseif ($action == 'BILL_PAYED') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	$this->Cliente_Saldar_Pago_Anticipado($object, $user, $conf);
        } elseif ($action == 'BILL_SUPPLIER_VALIDATE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	$res = $this->Proveedor_Compra_a_Credito($object, $user, $conf);
        	if ($res == -1) {
        		$error++;
        		$this->errors[] = '<div class="error">No se permiten pagos diferidos para facturas que tienen varios artículos con Tasa de Impuestos Difdrentes.  Favor de cambiar la condición de pago.</div>';
        		//$this->mesg[] = "No se ";
        		return $res;
        	}
        } elseif ($action == 'BILL_SUPPLIER_PAYED') {//Verificar si es acreedor o proveedor 
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id); 
        	//$this->Proveedor_Saldar_Pago_Anticipado($object, $user);
        } elseif ($action == 'LINEBILL_SUPPLIER_CREATE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	$this->Factura_Nueva_Linea($object, $user, $conf);
        } elseif ($action == 'LINEBILL_SUPPLIER_DELETE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	$this->Factura_Delete_Line($object, $user, $conf);
        } else if ($action == 'LINEBILL_SUPPLIER_UPDATE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	$this->Factura_Update_Line($object, $user, $conf);
        }
        
        // Payments
        elseif ($action == 'PAYMENT_CUSTOMER_CREATE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            $this->Pago_de_Factura($object, $user, $conf);
        } elseif ($action == 'PAYMENT_SUPPLIER_CREATE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            $this->Proveedor_Pago_Factura($object, $user, $conf);
        } elseif ($action == 'PAYMENT_ADD_TO_BANK') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            
            $this->errors = array();
            $rsp=-1;
            $error = 0;
            foreach ($_POST as $key => $value){
            	if (substr($key,0,7) == 'amount_'){
            		$cursorfacid = substr($key,7);
            		$amounts[$cursorfacid] = $_POST[$key];
            		//var_dump($amounts);
            		if (! empty($amounts[$cursorfacid])){
            			dol_syslog("Payment_add_to_bank = Object Element = ".$object->element);
            			if($object->element=='payment_supplier'){
            				// Facture Fourn
            				$sql = "Select * From llx_facture_fourn_det Where fk_facture_fourn = ".$cursorfacid;
            			}else if ($object->element == 'payment') {
            				// Facture
            				$sql = "Select * From llx_facturedet Where fk_facture = ".$cursorfacid;
            			}
            			dol_syslog("OBJECT: '".$object->element."'");
            			//$str.=$cursorfacid.'-'.$divisa[$cursorfacid].'\n';
                        
            			//print_r("amounts->cursorfacid=".$amounts);
            			
            			dol_syslog("doActions:: action=$action, context=$context, sql=$sql");
            			$mismo_iva = true;
            			$total_ammount = 0;
            			$i = 0;
            			if ($res = $this->db->query($sql)) {
            				while ($obj = $this->db->fetch_object($res)) {
            					$total_ammount += $obj->total_ttc;
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
            			dol_syslog("mismo_iva=".($mismo_iva?"true":"false")." Total amount=$total_ammount, amounts=".$amounts[$cursorfacid]." diferencia=".($total_ammount - $amounts[$cursorfacid]));
            			if ((!$mismo_iva) && ($total_ammount - $amounts[$cursorfacid] > 0.009)) {
            				$rsp = -1;
            				$error++;
            				$this->errors[] = '<div class="error">Para facturas que tienen varios artículos con Tasa de Impuestos Difdrentes, el pago debe realizarse en su totalidad en una sola exhibición.</div>';
            				//$this->mesg[] = "No se ";
            				return $rsp;
            				break;
            			} else {
            				$rsp = 1;
            			}
            		}
            	}
            }
            dol_syslog("REGRESA ESTO ".$rsp);
            return $rsp;
        } 
/*        } elseif ($action == 'PAYMENT_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } 

        // File
         elseif ($action == 'FILE_UPLOAD') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } elseif ($action == 'FILE_DELETE') {
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        } */
        return 0;
    }
    
    public function Factura_Nueva_Linea($object, $user, $conf) {
    	//var_dump($object);
    	if (GETPOST('ddl_contab_id_cuenta') > 0) {
    		//Get id from new line
    		$id = $object->id;
    		$rowid = $object->rowid;
    		$idcta = GETPOST("ddl_contab_id_cuenta");
    		$socid = $object->socid;
    		
    		$sup = new Contabctassupplier($this->db);
    		//print "dos";
    		if ($sup->fetch_by_idcta_socid($idcta, $socid) > 0) {
    			$soc_type = $sup->soc_type;
    			$sql = "Insert Into llx_contab_fourn_product_line (fk_facture, rowid_line, fk_cat_cta, soc_type) Values ('$id', '$rowid', '$idcta', '$soc_type')";
    			$this->db->query($sql);
    		}
    		dol_syslog("Contab - Interface:: Factura_Nueva_Linea:: sql = $sql");
    	}
    }
    
    public function Factura_Delete_Line($object, $user, $conf) {
    	dol_syslog("Factura_Delete_Line");
    	//var_dump($object);
    	$id = $object->id;
    	$rowid = GETPOST('lineid');
    	
    	dol_syslog("id=$id, rowid=$rowid, idcta=$idcta");
    	$this->db->query("Delete From llx_contab_fourn_product_line Where rowid_line = '$rowid' and fk_facture = '$id' and soc_type = 1");
    }
    
    public function Factura_Update_Line($object, $user, $conf) {
    	dol_syslog("Factura_Update_Line");
    	//var_dump($object);
    	$id = $object->id;
    	$rowid = $object->rowid;
    	$idcta = GETPOST("ddl_contab_id_cuenta");
    	
    	$sql = "Update llx_contab_fourn_product_line SET fk_cat_cta = '$idcta' Where fk_facture = '$id' And rowid_line = '$rowid' and soc_type = 1";
    	dol_syslog("id=$id, rowid=$rowid, idcta=$idcta, sql=$sql");
    	$this->db->query($sql);
    }
    
	public function Venta_a_Credito($object, $user, $conf) {
		
		$rel = new Contabrelctas($this->db);
		$cat = new Contabcatctas($this->db);
		
    	$fac = new Factures($this->db);
    	$fac->fetch($object->id);
    	$facref = $fac->ref;
    	$facid = $fac->id;
    	$socid = $fac->socid;
    	
    	$mismo_iva = true;
    	foreach ($fac->lines as $i => $line) {
    		dol_syslog("Valor de i = $i");
    		if ($i == 0) {
    			$tva_tx = $line->tva_tx;
    		} else {
    			if ($tva_tx != $line->tva_tx) {
    				$mismo_iva = false;
    			}
    		}
    		dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
    	}
    	
    	$cond_pago = 1;
    	$payment = new Contabpaymentterm($this->db);
    	$payment->fetch($fac->fk_cond_reglement);
    	if ($payment->cond_pago) {
    		$cond_pago = $payment->cond_pago;
    	}
    	
    	//Que tipo de poliza se trata?
    	dol_syslog("VENTA A CREDITO");
    	dol_syslog("Tipo Factura=".$fac->type.", fk_cond_reglement=".$fac->fk_cond_reglement.", cond_pago=".$cond_pago);
    	$tipo_pol = "";
    	$concepto = "";
    	
    	if ($fac->type == $fac::TYPE_STANDARD) {
	    	if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
    			//la Venta es a Credito
    			// 2. El cliente paga a 30 dias.
    			// 3. Paga a 30 dias Final del Mes.
    			// 4. Paga a 60 dias.
    			// 5. Paga a 60 dias Final del Mes.
    			$tp = Contabpolizas::POLIZA_DE_DIARIO;
    			$concepto = "Venta al Cliente a Crédito, Según Factura ".$facref;
    		} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
    			//La venta es 50% a credito y 50% al contado.
    			//8. El cliente paga la mitad ahorita y la mitad después.
    			$tp = Contabpolizas::POLIZA_DE_DIARIO;
    			$concepto = "Venta al Cliente, 50% a Crédito y 50% al Contado, Según Factura ".$facref;
    			
    			if (!$mismo_iva) {
    				$ret = -1;
    				return $ret;
    			}
    		}
    	}
    	
   		$pol = new Contabpolizas($this->db);
   		$exists = $pol->fetch_by_factura_Y_TipoPoliza($facid, $tp, 1);
   		
   		if($fac->type == $fac::TYPE_STANDARD && ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO || $cond_pago == Contabpaymentterm::PAGO_EN_PARTES )) {
			
			if ($exists) {
   			
	   		} else {

	   			$pol->fetch_last_by_tipo_pol($tp);
	   			$cons = $pol->cons + 1;
	   			
	   			$pol->initAsSpecimen();
	   			
	   			$pol->anio = date("Y", $fac->date);
	   			$pol->mes = date("m", $fac->date);
	   			$pol->fecha = $fac->date;
	   			$pol->concepto = $concepto;
	   			$pol->comentario = "Factura a Cliente con fecha del ".date("d-M-Y",$fac->date);
	   			$pol->tipo_pol = $tp;
	   			$pol->cons = $cons;
	   			$pol->fk_facture = $facid;
	   			$pol->societe_type = 1;
	   			
	   			$pol->create($user);
	   			$polid = $pol->id;
	   			
	   			$dscto_ht = 0;
	   			$dscto_tva = 0;
	   			$dscto_ttc = 0;
	   			// Se busca el descuento que fue aplicado a la factura
	   			$sql = 'SELECT SUM(amount_ht) amount_ht, SUM(amount_tva) amount_tva, SUM(amount_ttc) amount_ttc ';
	   			$sql.= 'FROM llx_societe_remise_except sr ';
	   			$sql.= 'INNER JOIN llx_facturedet fd ';
	   			$sql.= 'ON sr.fk_facture_line = fd.rowid AND fd.fk_facture = '.$facid;
	   			
	   			dol_syslog("Venta a Credito - sql:".$sql);
	   			
	   			if ($res = $this->db->query($sql)) {
	   				if ($row = $this->db->fetch_object($res)) {
	   					$dscto_ht = $row->amount_ht;
	   					$dscto_tva = $row->amount_tva;
	   					$dscto_ttc = $row->amount_ttc;
	   				}
	   			}
	   			
	   			//Ahora se crearán los asientos contables para la póliza.
	   			$ln = array();
	   			foreach ($fac->lines as $j => $line) {
	   				dol_syslog("remise_excent".$fac->lines[$j]->fk_remise_except);
	   				if (! $fac->lines[$j]->fk_remise_except > 0) {
	   					if ($fac->lines[$j]->product_type == 0) {

	   						//Se trata de Un Producto
	   						
			   				dol_syslog("Line id = ".$line->rowid." - ".$fac->lines[$j]->rowid);
			   				//Analizando si hay descuento sobre compras
			   				
			   				$sub_total = $line->subprice * $line->qty;
			   				$descto = ($sub_total * $line->remise_percent / 100) + $dscto_ht;
			   				$total = $sub_total - $descto;
			   				$iva = $total * $line->tva_tx / 100;
			   				
			   				$dscto_ht = 0;
			   				$dscto_tva = 0;
			   				$dscto_ttc = 0;
			   				
			   				$cuenta = "";
			   				$codagr = "";
			   				//Es poliza de Ingreso cuando se hace una venta al contado o con cheque al contado.
			   				//Es poliza de Egresos cuando se hace un pago con Cheque o se emite un cheque.
			   				//Es de Diario cuando se trata de cualquier otro tipo de poliza.
			   				if($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
			   					dol_syslog("PAGO A CREDITO");
			   					//La venta es 100% a credito.
			   					//2 al 5. El cliente compra todo a crédito, 30, 60 días.
			   						
			   					//la Venta es 100% a Crédito
			   					// Se registra una Venta a Credito y 10% de Desc.
			   						
			   					// Póliza de Diario
			   					// Clientes				104.40
			   					// Descto s/vtas		 10.00
			   					// 		Ventas					100.00
			   					//		IVA x Trasladar			 14.40
			   						
			   				// Es una póliza de Diario (se trata de una venta a crédito.
			  					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($socid)) {
			  						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
			  						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
			  						//El cliente tiene definida una cuenta para referenciar acientos contables
			  						$codagr = "";
			  						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
			  					} else {
			  						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
			  						$cuenta = $this->Get_Cliente_Cuenta($socid, $conf);
			  					}
			   					$debe = $total + $iva;
			   					$haber = 0;
			   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   						
			   					//Analizando si hay descuento sobre la venta
			   					// Se registra el Descuento sobre Ventas
			   					if ($line->remise_percent > 0 || $dscto > 0) {
			   						if ($line->tva_tx > 15) {
			   							$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
			   							//$codagr = "402.01"; //Dscto a Tasa General
			   						} else {
			   							$rel->fetch_by_code("DEV_VTA_TASA_0");
			   							//$codagr = "402.02"; //Dscto a Tasa 0%
			   						}
			   						$cat->fetch($rel->fk_cat_cta);
			   						$cuenta = $cat->cta;
			   						$debe = $descto;
			   						$haber = 0;
			   						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   					}
			   						
			   					// Se registra la Ventas
			   					if ($line->tva_tx > 15) {
			   						$rel->fetch_by_code("VENTAS_TASA_GRAL");
			   						//$codagr = "401.01"; //Venta a Tasa General
			   					} else {
			   						$rel->fetch_by_code("VENTAS_TASA_0");
			   						//$codagr = "401.04"; //Venta a Tasa 0%
			   					}
			   					$cat->fetch($rel->fk_cat_cta);
			   					$cuenta = $cat->cta;
			   					$debe = 0;
			   					$haber = $sub_total;
			   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   						
			   					//Se registra el IVA Trasladado No Cobrado por que fue a Crédito
								$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
								/* $codagr = "209.01"; //Iva Trasladado No Cobrado*/
								$cat->fetch($rel->fk_cat_cta);
			   					$cuenta = $cat->cta;
			   					$debe = 0;
			   					$haber = $iva;
			   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   					
			   				} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
			   					dol_syslog("PAGO EN PARTES");
			   					//la Venta es 50% al Contado y 50% a crédito
			   					// Se registra una Venta 50% al Contado y 50% a Credito y 10% de Desc.
			   						
			   					// Póliza de Diario
			   					// Clientes				52.20
			   					// Descto s/vtas		 5.00
			   					// 		Ventas					50.00
			   					//		IVA x Trasladar			 7.20
			   						
			   					//Se registra el ingreso a Clientes
			   					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($socid)) {
			   						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
			   						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
			   						//El cliente tiene definida una cuenta para referenciar acientos contables
			   						$codagr = "";
			   						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
			   					} else {
			   						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
			   						$cuenta = $this->Get_Cliente_Cuenta($socid, $conf);
			   					}
			   					//Se divide ya que es la mitad a credito y la mitad a contado, cada uno en Póliza separada
			   					$debe = ($total + $iva) / 2;
			   					$haber = 0;
			   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   					
			   					// Se registra el Descuento sobre Ventas
			   					if ($line->remise_percent > 0) {
			   						if ($line->tva_tx > 15) {
			   							$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
			   							//$codagr = "402.01"; //Dscto a Tasa General
			   						} else {
			   							$rel->fetch_by_code("DEV_VTA_TASA_0");
			   							//$codagr = "402.02"; //Dscto a Tasa 0%
			   						}
			   						$cat->fetch($rel->fk_cat_cta);
			   						$cuenta = $cat->cta;
			   						$debe = $descto / 2;
			   						$haber = 0;
			   						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   					}
			   						
			   					// Se registra la Ventas
			   					if ($line->tva_tx > 15) {
			   						$rel->fetch_by_code("VENTAS_TASA_GRAL");
			   						//$codagr = "401.01"; //Venta a Tasa General
			   					} else {
			   						$rel->fetch_by_code("VENTAS_TASA_0");
			   						//$codagr = "401.04"; //Venta a Tasa 0%
			   					}
			   					$cat->fetch($rel->fk_cat_cta);
		   						$cuenta = $cat->cta;
			   					$debe = 0;
			   					//Se divide ya que es la mitad a credito y la mitad a contado, cada uno en Póliza separada
			   					$haber = $sub_total / 2;
			   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   						
			   					//Se registra el IVA 
			   					$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
			   					//$codagr = "209.01"; //Iva Trasladado No Cobrado  por ser Venta a Crédito 50%
			   					$cat->fetch($rel->fk_cat_cta);
		   						$cuenta = $cat->cta;
			   					$debe = 0;
			   					//Se divide ya que es la mitad a credito y la mitad a contado, cada uno en Póliza separada
			   					$haber = $iva / 2; //(($line->subprice * $line->qty) - $debe_dscto) * $line->tva_tx / 100;
			   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			   				}
		   				} else if ($fac->lines[$j]->product_type == 1) {
		   					//Se trata de un Servicio
		   					/*
		   					 * TODO: Realizar la parte de Servicios, específicamente cuando haya retenciones por parte de nuestros 
		   					 * 		Clientes cuando les hagamos algun trabajo o servicio y nos tengan que hacer las retenciones
		   					 * 		correspondientes de IVA y de ISR.
		   					 */
		   				}
	   				} else { dol_syslog("Esta línea no se debe procesar"); }
	   			}
   			}
   			
   			$jj = 0;
   			while ($jj < sizeof($ln[$tp])) {
   				$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
   				$jj++;
   			}
   		}
    }
    
    public function Cliente_Saldar_Pago_Anticipado($object, $user, $conf) {
    	global $conf;
   	
    	dol_syslog("Cliente_Saldar_Pago_Anticipado ==> Datos: Object->id = ".$object->id);
    	
    	$rel = new Contabrelctas($this->db);
    	$cat = new Contabcatctas($this->db);
    	
    	$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
    	$country_id=$tmp[0];
    	
    	//con esta instrucción se obtiene el primero registro del detalle donde se encuentra el pago anticipado.
    	$sql = "SELECT * ";
    	$sql .= "FROM ".MAIN_DB_PREFIX."facture f ";
    	$sql .= "INNER JOIN ".MAIN_DB_PREFIX."facturedet fd ";
    	$sql .= "ON f.rowid = fd.fk_facture ";
    	$sql .= "INNER JOIN ".MAIN_DB_PREFIX."societe_remise_except re ";
    	$sql .= "ON fd.rowid = re.fk_facture_line ";
    	$sql .= "WHERE re.description = '(DEPOSIT)' AND f.rowid = ".$object->id;
    	
    	$rs = $this->db->query($sql);
    	if ($rw = $this->db->fetch_object($rs)) {
    	
	    	$pol = new Contabpolizas($this->db);
	    	if (! $pol->fetch_by_factura_Y_TipoPoliza($object->id, Contabpolizas::POLIZA_DE_DIARIO, 1)) {
	    		//No se ha creado la Póliza para cancelar saldos de la póliza de ingresos por Venta Pagada por Anticipado.
	    		
	    		//Notas:  
	    		//1. La factura a afectar con la Factura Anticipada, deberá de tener los mismos datos tanto en productos, cantidades y descuentos.
	    		//2. Si al momento de entregar los productos y hacer la factura que quedó afectada por la factura anticipada, el cliente
	    		//		desea más atriculos o productos, se deberán de cargar dichos productos extras (que no fueron contemplados en la factura anticipada) 
	    		//		en otra factura nueva, osea se deberá hacer otra factura una vez que se haya concluido con la captura de la factura actual.
	
	    		//Cuando una factura se paga con el saldo de una factura pagada por anticipado, la factura no tiene los datos de la venta
	    		//hay que ir a los datos de cada producto para que, sumando obtengamos los datos de la venta.
	    		$fac = new Factures($this->db);
	    		$fac->fetch($object->id);
	    		
	    		$fk_soc = $fac->socid;
	    		
	    		$facid = $object->id;
	    		$facref = $fac->ref;
	    		
	    		dol_syslog("Tipo Factura=".$fac->type.", fk_cond_reglement=".$fac->fk_cond_reglement.", cond_pago=".$cond_pago);
	    		
	    		dol_syslog("fac-socid=".$fac->socid." facid=".$facid." facref=".$facref);
	    		
	    		$tp = Contabpolizas::POLIZA_DE_DIARIO;
	    		$concepto = "Venta pagada por Anticipado, Según Factura ".$facref;
	    		$comentario = "Pago de Factura a Cliente con fecha del ".date("d-M-Y", $fac->date);
	    		
	    		$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fac->date, 1);
	    		
	    		$ln = array();
	    		
	    		foreach ($fac->lines as $i => $line) {
	    			//La linea que no tiene info en fk_product, es la linea que da información sobre el pago realizado con la factura anticipada.
	    			if ($line->fk_product > 0 && $line->product_type == 0) {
	    				// Se trata de un producto No de un servicio.
	    				
	    				$sub_total = $line->subprice * $line->qty;
	    				$descto = $sub_total * $line->remise_percent / 100;
	    				$total = $sub_total - $descto;
	    				$iva = $total * $line->tva_tx / 100;
			    		 
			    		$cuenta = "";
			    		$codagr = "";
			    		
			    		// Se registra la Ventas anticipada
			    		// Se requiere saber si es un cliente nacional o extranjero.
			    		$asiento = 1;
			    		if ($this->Get_Cliente_Proveedor_Pais($fk_soc) == $country_id) {
		    				$rel->fetch_by_code("ANT_CTE_NAL");
		    				//$codagr = "206.01"; //Nacional
			    		} else {
			    			$rel->fetch_by_code("ANT_CTE_EXT");
			    			//$codagr = "206.02"; //Extranjero
			    		}
			    		$cat->fetch($rel->fk_cat_cta);
   						$cuenta = $cat->cta;
			    		$debe = $total + $iva;
			    		$haber = 0;
			    		$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			    		
			    		//Analizando si hay descuento sobre Ventas
			    		if ($descto > 0) {
			    			$asiento ++;
			    			if ($line->tva_tx > 15) {
			    				$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
			    				//$codagr = "402.01"; //Dscto a Tasa General
			    			} else {
			    				$rel->fetch_by_code("DEV_VTA_TASA_0");
			    				//$codagr = "402.02"; //Dscto a Tasa 0%
			    			}
			    			$cat->fetch($rel->fk_cat_cta);
	   						$cuenta = $cat->cta;
			    			$debe = $descto;
			    			$haber = 0;
			    			$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
			    		}
			    				
						//Se registra la Venta
						$asiento ++;
						if ($line->tva_tx > 15) {
							$rel->fetch_by_code("VENTAS_TASA_GRAL");
							//$codagr = "401.01"; //Venta a tasa normal
						} else {
							$rel->fetch_by_code("VENTAS_TASA_0");
							//$codagr = "401.04"; //Venta a tasa 0
						}
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = 0;
						$haber = $sub_total;
						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		    				
		    			if ($line->tva_tx > 15) {
	    					//Se registra el IVA Trasladado NO Cobrado por que fue al contado
	    					$asiento ++;
	    					$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
	    					//$codagr = "209.01"; //Iva Trasladado NO Cobrado
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = 0;
	    					$haber = $iva;
	    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    				}
	    			} else if ($line->fk_product > 0 && $line->product_type == 1) {
	    				// Se tata de un Servicio No de un Producto.
	    				/* TODO: ver si se va a realizar la generación de una póliza automática por el pago anticipado de servicio
	    				 * 		de arrendamiento o servicios profesionales.
	    				 */
	    			}
	    		}
	    		$jj = 0;
	    		while ($jj < sizeof($ln[$tp])) {
	    			$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
	    			$jj++;
	    		}
	    	}
    	}
    }
    
    public function Pago_de_Factura($object, $user, $conf) {
    	
    	$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
    	$country_id=$tmp[0];
    	
    	$rel = new Contabrelctas($this->db);
    	$cat = new Contabcatctas($this->db);
    	
    	$paim = new Paiements($this->db);
    	$paim->fetch($object->id);
    	$fecha = $paim->datepaye;
    	$mode_reglement = $paim->id_paiment;
    	
    	$paim->id = $object->id;
    	$a_fac = $paim->getBillsArray();
    	
    	dol_syslog("Pago_de_Factura - Búsqueda de Paiments - getBillsArray(), Object->id = ".$object->id." paiment:".$object->fk_paiement." facture:".$object->fk_facture." amount".$object->amount." date=".$fecha);
    	
    	foreach ($a_fac as $idx => $value) {
    	
    		dol_syslog("Búsqueda de la Factura");
    	
    		$facid = $value;
    	
    		$fac = new Factures($this->db);
    		$fac->fetch($facid);
    		$facref = $fac->ref;
    		$facid = $fac->id;
    		
    		$mismo_iva = true;
    		foreach ($fac->lines as $i => $line) {
    			dol_syslog("Valor de i = $i");
    			if ($i == 0) {
    				$tva_tx = $line->tva_tx;
    			} else {
    				if ($tva_tx != $line->tva_tx) {
    					$mismo_iva = false;
    				}
    			}
    			dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
    		}
    	
    		//Se obtienen los ids de cada una de las lineas de la factura ( o sea los ids de factura detalle)
    		$a_lines_ids = array();
    		foreach ($fac->lines as $i => $line) {
    			$a_lines_ids[] = $line->rowid;
    		}
    		$str_lines_ids = implode(",", $a_lines_ids);
			
    		dol_syslog("Cliente_Crear_Poliza: tipo=".$fac->type." cond pago=".$cond_pago);
			
    		dol_syslog("FACREF = ".$facref." FACID=".$facid." amount=".$object->total_ttc." Fac Type=".$fac->type);
			
    		dol_syslog("Tipo Factura=".$fac->type.", fk_cond_reglement=".$fac->fk_cond_reglement.", cond_pago=".$cond_pago);
			
    		if ($fac->type == $fac::TYPE_STANDARD) {
    			 
    			dol_syslog("FACTURA STANDARD =======>");
    			
    			//Obtener todos los pagos realizados a la Factura Original, para saber a que cuentas se deberá de afectar la Devolución.
    			// Payments already done (from payment on this invoice)
    			$sql = 'SELECT SUM(pf.amount) as amount';
    			$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
    			$sql .= ' WHERE pf.fk_facture = ' . $facid . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_paiement = '.$object->id;
    			$sql .= ' ORDER BY p.datep, p.tms';
    			
    			dol_syslog("Pago_de_Factura - Factura Standard: sql=".$sql);
    			$amount = 0;
    			$result = $this->db->query($sql);
    			if ($result) {
    				$objp = $this->db->fetch_object($result);
    				if ($objp) {
    					$amount = $objp->amount;
    				}
    				$this->db->free($result);
    			}
    			
    			/* $fac = new Factures($this->db);
    			$fac->fetch($facid);
    			$facref = $fac->ref;
    			$facid = $fac->id; */
    			
    			dol_syslog("Mode Reglement: ".$mode_reglement);
    			
    			//Ver si el pago es al contado, credito, cobro anticipado, 50 y 50.
    			$payment = new Contabpaymentterm($this->db);
    			$payment->fetch($fac->fk_cond_reglement);
    			$cond_pago = $payment->cond_pago;
    			
    			//Que tipo de poliza se trata?
    			$tp = "";
    			$concepto = "";
    			 
    			dol_syslog("La Póliza no existe, se tiene que crear.  total=$total, amount=$amount, iva=$iva, tva_tx=$fac->lines[0]->tva_tx");
    			 
    			if ($cond_pago == Contabpaymentterm::PAGO_AL_CONTADO) {
    	
    				dol_syslog("Pago al Contado - ".$cond_pago);
					
    				if (!$mismo_iva) {
    					//Esta parte se ejecuta para cuando son ivas diferentes
    					
    					dol_syslog("IVAS Diferentes");
	    				// Pago a la recepción de la factura, lo cual indica que es al contado por que se le de ahí mismo la factura al cliente.
	    				//la Venta es al Contado
	    				// 1. El cliente paga a la recepción de la factura
	    				$tp = Contabpolizas::POLIZA_DE_INGRESO;
	    				$concepto = "Ingreso por Venta a Cliente al Contado, Según Factura ".$facref;
	    				$comentario = "Pago de Factura a Cliente con fecha del ".date("d-M-Y", $fecha);
	    	
	    				$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
	    	
	    				$cuenta = "";
	    				$codagr = "";
	    				
	    				$sub_total = 0;
	    				$total = 0;
	    				$descto = 0;
	    				$iva = 0;
	    				foreach($fac->lines as $j => $l) {
	    					/* dol_syslog("remise = ".$l->fk_remise_except." total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
	    					if ($l->fk_remise_except > 0) {
	    						$descto += abs($l->total_ht);
	    					} else {
	    						$sub_total += $l->subprice * $l->qty;
	    						$descto += $l->subprice * $l->qty * $l->remise_percent / 100;
	    						$iva += ($sub_total - $descto) * $l->tva_tx / 100;
	    					}
	    					if ($l->tva_tx > 15) {
	    						$dscto_tasa_gral = 1;
	    						$tasa_gral = $l->tva_tx / 100;
	    						$codagr = "402.01"; //Dscto a Tasa General
	    					} else {
	    						$tasa_gral = 0;
	    						$dscto_tasa_gral = 0;
	    						$codagr = "402.02"; //Dscto a Tasa 0%
	    					}
	    					$total_si =  $sub_total - $descto;
	    					$iva = $total_si * $tasa_gral;
		    				$total_ci = $total_si + $iva;
	    					dol_syslog("sub_total=$sub_total, descto=$descto"); */
	    					
	    					$sub_total = $l->subprice * $l->qty;
	    					$descto = $sub_total * $l->remise_percent / 100;
	    					$total_si = $sub_total - $descto;
	    					$iva = $total_si * $l->tva_tx / 100;
	    					$total_ci = $total_si + $iva;
	    					 
	    					if ($mode_reglement == 4) {
	    						// Se recibe el pago en Efectivo
	    						$rel->fetch_by_code("EFECTIVO");
	    						//$codagr = "101.01";
	    					} else {
	    						// Cualquier otro valor se tomar como pago Bancario
	    						$rel->fetch_by_code("BANCOS_NAL");
	    						//$codagr = "102.01";
	    					}
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = $total_ci;
	    					$haber = 0;
	    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    					
	    					if ($descto > 0 ) {
	    						$asiento ++;
	    						if ($l->tva_tx == 0) {
	    							$rel->fetch_by_code("DEV_VTA_TASA_0");
	    							//$codagr = "402.01"; //Dscto a Tasa General
	    						} else {
	    							$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
	    							//$codagr = "402.02"; //Dscto a Tasa 0%
	    						}
	    						$cat->fetch($rel->fk_cat_cta);
	    						$cuenta = $cat->cta;
	    						$debe = $descto;
	    						$haber = 0;
	    						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    					}
	    					
	    					if ($l->tva_tx == 0) {
	    						$rel->fetch_by_code("VENTAS_TASA_0");
	    						//$codagr = "401.01"; //Venta a Tasa General
	    					} else {
	    						$rel->fetch_by_code("VENTAS_TASA_GRAL");
	    						//$codagr = "401.04"; //Venta a Tasa 0%
	    					}
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = 0;
	    					$haber = $sub_total;
	    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    					
	    					if ($l->tva_tx > 0) {
	    						//Se registra el IVA Trasladado Cobrado por que fue al contado
	    						$asiento ++;
	    						$rel->fetch_by_code("IVA_TRAS_COBRADO");
	    						//$codagr = "208.01"; //Iva Trasladado Cobrado
	    						$cat->fetch($rel->fk_cat_cta);
	    						$cuenta = $cat->cta;
	    						$debe = 0;
	    						$haber = $iva;
	    					
	    						$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    					}
	    				}
	    				
	    				$jj = 0;
	    				while ($jj < sizeof($ln[$tp])) {
	    					$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
	    					$jj++;
	    				}
    				} else if ($mismo_iva) {
    					
    					dol_syslog("IVAS Iguales");
    					
    					$tp = Contabpolizas::POLIZA_DE_INGRESO;
    					$concepto = "Ingreso por Venta a Cliente al Contado, Según Factura ".$facref;
    					$comentario = "Pago de Factura a Cliente con fecha del ".date("d-M-Y", $fecha);
    					
    					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
    					
    					$cuenta = "";
    					$codagr = "";
    					
    					//Esta parte se ejecuta para cuando son ivas iguales
	    				$sub_total = 0;
	    				$total = 0;
	    				$descto = 0;
	    				$iva = 0;
	    				foreach($fac->lines as $j => $l) {
	    					dol_syslog("remise = ".$l->fk_remise_except." total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
	    					if ($l->fk_remise_except > 0) {
	    						$descto += abs($l->total_ht);
	    					} else {
	    						$sub_total += $l->subprice * $l->qty;
	    						$descto += $l->subprice * $l->qty * $l->remise_percent / 100;
	    					}
	    					if ($l->tva_tx > 15) {
	    						$dscto_tasa_gral = 1;
	    						$tasa_gral = $l->tva_tx / 100;
	    						$codagr = "402.01"; //Dscto a Tasa General
	    					} else {
	    						$tasa_gral = 0;
	    						$dscto_tasa_gral = 0;
	    						$codagr = "402.02"; //Dscto a Tasa 0%
	    					}
	    					dol_syslog("sub_total=$sub_total, descto=$descto");
	    				}
	    				$total_si =  $sub_total - $descto;
	    				$iva = $total_si * $tasa_gral;
	    				$total_ci = $total_si + $iva;
	    				
	    				dol_syslog("Algun dato interesante arriba?");
	
	    				// Pago a la recepción de la factura, lo cual indica que es al contado por que se le de ahí mismo la factura al cliente.
	    	
	    				//la Venta es al Contado
	    				// 1. El cliente paga a la recepción de la factura
	    	
	    				// Se registra una Venta al Contado con el 10% de Descuento
	    				// Efectivo o Bancos	104.40
	    				// Descto s/vtas		 10.00
	    				// 		Ventas					100.00
	    				//		IVA Trasladado			 14.40
	    	
	    				//Se registra el ingreso Caja o Bancos
	    				$asiento = 1;
	    				if ($mode_reglement == 4) {
	    					// Se recibe el pago en Efectivo
	    					$rel->fetch_by_code("EFECTIVO");
	    					//$codagr = "101.01";
	    				} else {
	    					// Cualquier otro valor se tomar como pago Bancario
	    					$rel->fetch_by_code("BANCOS_NAL");
	    					//$codagr = "102.01";
	    				}
	    				$cat->fetch($rel->fk_cat_cta);
	    				$cuenta = $cat->cta;
	    				$debe = $total_ci;
	    				$haber = 0;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    	
	    				//Analizando si hay descuento sobre compras
	    				$debe_dscto = 0;
	    				// Se registra el Descuento sobre Ventas
	    				// Se busca el descuento que fue aplicado a la factura
	    				if ($descto > 0 ) {
	    					$asiento ++;
	    					if ($dscto_tasa_gral == 1) {
	    						$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
	    						//$codagr = "402.01"; //Dscto a Tasa General
	    					} else {
	    						$rel->fetch_by_code("DEV_VTA_TASA_0");
	    						//$codagr = "402.02"; //Dscto a Tasa 0%
	    					}
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = $descto;
	    					$haber = 0;
	    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    				}
	    	
	    				// Se registra la Ventas
	    				$asiento ++;
	    				if ($dscto_tasa_gral == 1) {
	    					$rel->fetch_by_code("VENTAS_TASA_GRAL");
	    					//$codagr = "401.01"; //Venta a Tasa General
	    				} else {
	    					$rel->fetch_by_code("VENTAS_TASA_0");
	    					//$codagr = "401.04"; //Venta a Tasa 0%
	    				}
	    				$cat->fetch($rel->fk_cat_cta);
	    				$cuenta = $cat->cta;
	    				$debe = 0;
	    				$haber = $sub_total;;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    	
	    				if ($dscto_tasa_gral == 1) {
	    					//Se registra el IVA Trasladado Cobrado por que fue al contado
	    					$asiento ++;
	    					$rel->fetch_by_code("IVA_TRAS_COBRADO");
	    					//$codagr = "208.01"; //Iva Trasladado Cobrado
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = 0;
	    					$haber = $iva;
	    						
	    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    				}
    				}
    			} else if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
    				if (!$mismo_iva) {
	    				dol_syslog("Pago a credito IVAS Diferentes - ".$cond_pago);
	    				
	    				// Al momento de la Facturación, La venta que fue registrada, se realizó de la siguiente forma:
	    				// Póliza de Diario
	    				// Clientes				104.40
	    				// Descto s/vtas		 10.00
	    				// 		Ventas					100.00
	    				//		IVA x Trasladar			 14.40
	    					
	    				// En este momento de Recibir el Pago, Se debe crear una póliza con la siguiente estructura:
	    				// Póliza de Ingreso
	    				// Caja o Bancos		104.40
	    				// IVA x Trasladar		 14.40
	    				// 		Clientes				100.00
	    				//		IVA x Trasladado		 14.40
	    				
	    				$ln = array();
	    				
	    				$tp = Contabpolizas::POLIZA_DE_INGRESO;
	    				$concepto = "Ingreso por cobro de Venta a Crédito, Según Factura ".$facref;
	    				$comentario = "Pago de Factura a Cliente con fecha del ".date("d-M-Y", $fecha);
	    				
	    				$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
	    				
	    				$cuenta = "";
	    				$codagr = "";
	    				
	    				$sub_total = 0;
	    				$total = 0;
	    				$descto = 0;
	    				$iva = 0;
	    				foreach($fac->lines as $j => $l) {
	    					$sub_total = $l->subprice * $l->qty;
	    					$descto = $sub_total * $l->remise_percent / 100;
	    					$total_si = $sub_total - $descto;
	    					$iva = $total_si * $l->tva_tx / 100;
	    					$total_ci = $total_si + $iva;
	    					
	    					//Se registra el ingreso Caja o BANCOS
	    					$asiento = 1;
	    					if ($mode_reglement == 4) {
	    						// Se recibe el pago en Efectivo
	    						$rel->fetch_by_code("EFECTIVO");
	    						//$codagr = "101.01";
	    					} else {
	    						// Cualquier otro valor se tomar como pago Bancario
	    						$rel->fetch_by_code("BANCOS_NAL");
	    						//$codagr = "102.01";
	    					}
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = $total_ci;
	    					$haber = 0;
	    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    					
	    					//Se registra el IVA Trasladado No Cobrado por que fue a Crédito
	    					$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
	    					//$codagr = "209.01"; //Iva Trasladado No Cobrado
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = $iva;
	    					$haber = 0;
	    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    					
	    					// Se registra la Ventas
	    					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
	    						dol_syslog("El cliente si tiene cuenta asingada=".$cuenta);
	    						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
	    						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
	    						//El cliente tiene definida una cuenta para referenciar acientos contables
	    						$codagr = "";
	    						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
	    					} else {
	    						dol_syslog("El cliente no tiene cuenta asignada");
	    						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
	    						$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
	    					}
	    					$debe = 0;
	    					$haber = $total_ci;
	    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    					
	    					//Se registra el IVA Trasladado Cobrado por que fue a Crédito
	    					$rel->fetch_by_code("IVA_TRAS_COBRADO");
	    					//$codagr = "208.01"; //Iva Trasladado Cobrado
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = 0;
	    					$haber = $iva;
	    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    				}
	    				
	    				$jj = 0;
	    				while ($jj < sizeof($ln[$tp])) {
	    					$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
	    					$jj++;
	    				}
	    				
    				} else if($mismo_iva) {    					
	    				dol_syslog("Pago a credito IVAS Iguales - ".$cond_pago);
    				    
	    				$tp = Contabpolizas::POLIZA_DE_INGRESO;
	    				$concepto = "Ingreso por cobro de Venta a Crédito, Según Factura ".$facref;
	    				$comentario = "Pago de Factura a Cliente con fecha del ".date("d-M-Y", $fecha);
	    				 
	    				$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
	    				
	    				//Se registra el ingreso Caja o BANCOS
	    				$asiento = 1;
	    				if ($mode_reglement == 4) {
	    					// Se recibe el pago en Efectivo
	    					$rel->fetch_by_code("EFECTIVO");
	    					//$codagr = "101.01";
	    				} else {
	    					// Cualquier otro valor se tomar como pago Bancario
	    					$rel->fetch_by_code("BANCOS_NAL");
	    					//$codagr = "102.01";
	    				}
	    				$cat->fetch($rel->fk_cat_cta);
	    				$cuenta = $cat->cta;
	    				$debe = $amount;
	    				$haber = 0;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    	
	    				//Se registra el IVA Trasladado No Cobrado por que fue a Crédito
	    				$asiento ++;
	    				$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
	    				//$codagr = "209.01"; //Iva Trasladado No Cobrado
	    				$cat->fetch($rel->fk_cat_cta);
	    				$cuenta = $cat->cta;
	    				$debe = ($amount / (1 + ($fac->lines[0]->tva_tx / 100))) * $fac->lines[0]->tva_tx / 100;
	    				$haber = 0;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    					
	    				// Se registra la Ventas
	    				$asiento ++;
	    				if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
	    					dol_syslog("El cliente si tiene cuenta asingada=".$cuenta);
	    					//Se recibe el pago contra el cliente (NO Caja, No Bancos)
	    					//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
	    					//El cliente tiene definida una cuenta para referenciar acientos contables
	    					$codagr = "";
	    					// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
	    				} else {
	    					dol_syslog("El cliente no tiene cuenta asignada");
	    					//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
	    					$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
	    				}
	    				$debe = 0;
	    				$haber = $amount;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    	
	    				//Se registra el IVA Trasladado Cobrado por que fue a Crédito
	    				$asiento ++;;
	    				$rel->fetch_by_code("IVA_TRAS_COBRADO");
	    				//$codagr = "208.01"; //Iva Trasladado Cobrado
	    				$cat->fetch($rel->fk_cat_cta);
	    				$cuenta = $cat->cta;
	    				$debe = 0;
	    				$haber = ($amount / (1 + ($fac->lines[0]->tva_tx / 100))) * $fac->lines[0]->tva_tx / 100;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    				}
    			} else if ($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
    				if (!$mismo_iva) {
    					dol_syslog("Pago En Partes con IVAS Iguales- ".$cond_pago);
    					dol_syslog("Se supone que no debío haber llegado aquí por que hay un detente en el triguer para facturas 50/50 con IVAS diferentes.");
    				} else if ($mismo_iva) {
	    				dol_syslog("Pago En Partes con IVAS Iguales- ".$cond_pago);
	    				
	    				$tp = Contabpolizas::POLIZA_DE_INGRESO;
	    				$concepto = "Ingreso por cobro de Venta a Crédito, Según Factura ".$facref;
	    				$comentario = "Pago de Factura a Cliente con fecha del ".date("d-M-Y", $fecha);
	    				
	    				$pol = new Contabpolizas($this->db);
	    				$res = $pol->fetch_by_factura_Y_TipoPoliza($facid, $tp, 1);
	    				if ($res == 1) {
	    					//La póliza ya existe por lo tanto se tiene que generar la otra póliza de Ingreso que cancele la póliza de diario
	    					
	    					dol_syslog("Se genera la Póliza de Ingreso que Cancela a la de Diario");
	    					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
	    					
	    					$cuenta = "";
	    					$codagr = "";
	    					 
	    					$sub_total = 0;
	    					$total = 0;
	    					$descto = 0;
	    					$iva = 0;
	    					foreach($fac->lines as $j => $l) {
	    						dol_syslog("remise = ".$l->fk_remise_except." total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
	    						if ($l->fk_remise_except > 0) {
	    							$descto += abs($l->total_ht);
	    						} else {
	    							$sub_total += $l->subprice * $l->qty;
	    							$descto += $l->subprice * $l->qty * $l->remise_percent / 100;
	    						}
	    						if ($l->tva_tx > 15) {
	    							$tasa_gral = $l->tva_tx / 100;
	    						} else {
	    							$tasa_gral = 0;
	    						}
	    						dol_syslog("sub_total=$sub_total, descto=$descto");
	    					}
	    					$total_si =  $sub_total - $descto;
	    					$iva = $total_si * $tasa_gral;
	    					$total_ci = $total_si + $iva;
	    					
	    					//Se registra el ingreso Caja o Bancos
	    					$asiento = 1;
	    					if ($mode_reglement == 4) {
	    						// Se recibe el pago en Efectivo
	    						$rel->fetch_by_code("EFECTIVO");
	    						//$codagr = "101.01";
	    					} else {
	    						// Cualquier otro valor se tomar como pago Bancario
	    						$rel->fetch_by_code("BANCOS_NAL");
	    						//$codagr = "102.01";
	    					}
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = $total_ci / 2;
	    					$haber = 0;
	    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    						
	    					if ($tasa_gral > 0) {
	    						//Se registra el IVA Trasladado NO Cobrado por que fue al contado
	    						$asiento ++;
	    						$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
	    						//$codagr = "209.01"; //Iva Trasladado NO Cobrado
	    						$cat->fetch($rel->fk_cat_cta);
	    						$cuenta = $cat->cta;
	    						$debe = $iva / 2;
	    						$haber = 0;
	    						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    					}
	    						
	    					// Se registra la Ventas anticipada
	    					// Se requiere saber si es un cliente nacional o extranjero.
	    					$asiento ++;
	    					dol_syslog("fac-socid=".$fac->socid);
	    					//Se registra el ingreso a Clientes
	    					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->socid)) {
	    						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
	    						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
	    						//El cliente tiene definida una cuenta para referenciar acientos contables
	    						$codagr = "";
	    						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
	    					} else {
	    						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
	    						$cuenta = $this->Get_Cliente_Cuenta($fac->socid, $conf);
	    					}
	    					$debe = 0;
	    					$haber = $total_ci / 2;
	    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    						
	    					if ($fac->lines[0]->tva_tx > 15) {
	    						//Se registra el IVA Trasladado Cobrado por que fue al contado
	    						$asiento ++;
	    						$rel->fetch_by_code("IVA_TRAS_COBRADO");
	    						//$codagr = "208.01"; //Iva Trasladado Cobrado
	    						$cat->fetch($rel->fk_cat_cta);
	    						$cuenta = $cat->cta;
	    						$debe = 0;
	    						$haber = $iva /2;
	    						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    					}
	    				} else {
	    					//La póliza de Ingreso no existe por lo tanto es el primer pago realizad por la mitad.
	    					dol_syslog("Se genera la Póliza de Ingreso que Cubre la Mitad inicial.  Lo otro fue a Crédito");
		    				$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
		    	
		    				$cuenta = "";
		    				$codagr = "";
		    				
		    				$sub_total = 0;
		    				$total = 0;
		    				$descto = 0;
		    				$iva = 0;
		    				foreach($fac->lines as $j => $l) {
		    					dol_syslog("remise = ".$l->fk_remise_except." total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
		    					if ($l->fk_remise_except > 0) {
		    						$descto += abs($l->total_ht);
		    					} else {
		    						$sub_total += $l->subprice * $l->qty;
		    						$descto += $l->subprice * $l->qty * $l->remise_percent / 100;
		    					}
		    					if ($l->tva_tx > 15) {
		    						$dscto_tasa_gral = 1;
		    						$tasa_gral = $l->tva_tx / 100;
		    					} else {
		    						$tasa_gral = 0;
		    						$dscto_tasa_gral = 0;
		    					}
		    					dol_syslog("sub_total=$sub_total, descto=$descto");
		    				}
		    				$total_si =  $sub_total - $descto;
		    				$iva = $total_si * $tasa_gral;
		    				$total_ci = $total_si + $iva;
		    				
		    				//Ya fue pagada la mitad?  ==> es que esa mitad se supone que fue al contado, por lo tanto mientras no se haya
		    				//								pagado esa mitad, todo lo que se cobre será enviado como ingreso por venta al contado.
		    				//	SI, ya fue pagada => todo lo que se cobre, será enviado como contrapartida para la poliza de Diario que se generó
		    				//      al momento de validar la factura.
		    				//$poldet = new Contabpolizasdet($this->db);
		    				//$poldet->Get_Pagos_Registrados_En_Polizas_Asiento1($facid, $tp);
		    				//$debe_total = $poldet->debe_total;
		    	
		    				//La venta es 50% a credito y 50% al contado.
		    				//8. El cliente paga la mitad ahorita y la mitad después.
		    	
		    				//la Venta es 50% al Contado y 50% a crédito
		    				// Se registra una Venta 50% al Contado y 50% a Credito y 10% de Desc.
		    	
		    				// Póliza de Diario
		    				// Clientes				52.20
		    				// Descto s/vtas		 5.00
		    				// 		Ventas					50.00
		    				//		IVA x Trasladar			 7.20
		    	
		    				// print "Tipo poliza=".$tp." cons=".$cons;
		    	
		    				//Se registra el ingreso Caja o Bancos
		    				// Es una póliza de Ingreso (se trata de una venta a crédito por la Mital (50 / 50)
		    				$asiento = 1;
		    				if ($mode_reglement == 4) {
		    					// Se recibe el pago en Efectivo
		    					$rel->fetch_by_code("EFECTIVO");
		    					//$codagr = "101.01";
		    				} else {
		    					// Cualquier otro valor se tomar como pago Bancario
		    					$rel->fetch_by_code("BANCOS_NAL");
		    					//$codagr = "102.01";
		    				}
		    				$cat->fetch($rel->fk_cat_cta);
		    				$cuenta = $cat->cta;
		    				$debe = $total_ci / 2;
		    				$haber = 0;
		    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		    	
		    				// Se registra el Descuento sobre Ventas
		    				dol_syslog("Remise percent=".$line->remise_percent);
		    				if ($descto > 0) {
		    					$asiento ++;
		    					if ($dscto_tasa_gral == 1) {
		    						$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
		    						//$codagr = "402.01"; //Dscto a Tasa General
		    					} else {
		    						$rel->fetch_by_code("DEV_VTA_TASA_0");
		    						//$codagr = "402.02"; //Dscto a Tasa 0%
		    					}
		    					$cat->fetch($rel->fk_cat_cta);
		    					$cuenta = $cat->cta;
		    					$debe = $descto / 2;
		    					$haber = 0;
		    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		    				}
		    	
		    				// Se registra la Ventas
		    				$asiento ++;
		    				if ($dscto_tasa_gral == 1) {
		    					$rel->fetch_by_code("VENTAS_TASA_GRAL");
		    					//$codagr = "401.01"; //Venta a Tasa General
		    				} else {
		    					$rel->fetch_by_code("VENTAS_TASA_0");
		    					//$codagr = "401.04"; //Venta a Tasa 0%
		    				}
		    				$cat->fetch($rel->fk_cat_cta);
		    				$cuenta = $cat->cta;
		    				$debe = 0;
		    				$haber = $sub_total / 2;
		    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		    	
		    				if ($tasa_gral > 0) {
		    					//Se registra el IVA Trasladado Cobrado por que fue al contado
		    					$asiento ++;
		    					$rel->fetch_by_code("IVA_TRAS_COBRADO");
		    					//$codagr = "208.01"; //Iva Trasladado Cobrado
		    					$cat->fetch($rel->fk_cat_cta);
		    					$cuenta = $cat->cta;
		    					$debe = 0;
		    					$haber = $iva / 2;
		    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		    				}
	    				}
    				}
    			}
    		} else if ($fac->type == $fac::TYPE_CREDIT_NOTE) {
    			 
    			dol_syslog("FACTURA NOTA DE CREDITO CLIENTE =======>");
    				
    			//$total_nc = abs($fac->total_ttc);
    			 
    			//Obtener los datos de la factura original sobre la que se está haciendo la Nota de Crédito o Devolución.
    			$fac_source = new factures($this->db);
    			$fac_source->fetch($fac->fk_facture_source);

    			dol_syslog("Mode Reglement: ".$mode_reglement);
    			
    			// NOTA DE CRÉDITO
    			$tp = Contabpolizas::POLIZA_DE_DIARIO;
    			$concepto = "Devoluciones sobre Ventas, Según Nota de Crédit:  ".$facref.". Factura: ".$fac_source->ref;
    				
    			/* $pol = new Contabpolizas($this->db);
    			 $exists = $pol->fetch_by_factura_Y_TipoPoliza($object->id, $tp, 1); */
    				
    			//Obtener todos los pagos realizados a la Factura Original, para saber a que cuentas se deberá de afectar la Devolución.
    			$sql = 'SELECT SUM(pf.amount) as amount';
    			$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
    			$sql .= ' WHERE pf.fk_facture = ' . $fac_source->id . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid';
    			$sql .= ' ORDER BY p.datep, p.tms';
    			 
    			$pagos_realizados = 0;
    			$result = $this->db->query($sql);
    			if ($result) {
    				$objp = $this->db->fetch_object($result);
    				if ($objp) {
    					$pagos_realizados = abs($objp->amount);
    				}
    				$this->db->free($result);
    			}
    			 
    			//Obtener el pago realizado o los datos de la NC
    			$sql = 'SELECT SUM(pf.amount) as amount';
    			$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
    			$sql .= ' WHERE pf.fk_facture = ' . $facid . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid AND pf.fk_paiement = '.$object->id;
    			$sql .= ' ORDER BY p.datep, p.tms';
    	
    			$amount = 0;
    			$result = $this->db->query($sql);
    			if ($result) {
    				$objp = $this->db->fetch_object($result);
    				if ($objp) {
    					$amount = abs($objp->amount);
    				}
    				$this->db->free($result);
    			}
    			 
    			$total = $amount / (1 + ($fac_source->lines[0]->tva_tx) / 100);
    			$iva = $amount - $total;
    			 
    			$falta_pagar = $fac_source->total_ttc - $pagos_realizados;
    			 
    			dol_syslog("Busquda de Pagos = ".$sql);
    				
    			//Ver si el pago es al contado, credito, cobro anticipado, 50 y 50.
    			$payment = new Contabpaymentterm($this->db);
    			$payment->fetch($fac_source->fk_cond_reglement);
    			$cond_pago = $payment->cond_pago;
    				
    			$pol = new Contabpolizas($this->db);
    			$pol->fetch_last_by_tipo_pol($tp);
    			$cons = $pol->cons + 1;
    	
    			$pol->initAsSpecimen();
    				
    			$pol->fecha = $fecha;
    			$pol->anio = date("Y",$fecha);
    			$pol->mes = date("m",$fecha);
    			$pol->concepto = $concepto;
    			$pol->comentario = "Nota de Crédito al Cliente con fecha del ".date("d-M-Y",$fecha);
    			$pol->tipo_pol = $tp;
    			$pol->cons = $cons;
    			$pol->fk_facture = $facid;
    			$pol->societe_type = 1;
    	
    			$pol->create($user);
    	
    			$polid = $pol->id;
    				
    			//Ahora se crearán los asientos contables para la póliza.
    			$ln = array();
    			//foreach ($fac->lines as $j => $line) {
    	
    			$cuenta = "";
    			$codagr = "";
    	
    			if($cond_pago == $payment::PAGO_AL_CONTADO || $cond_pago == $payment::PAGO_ANTICIPADO) {
    				//La venta fue 100% a credito.
					
					// Se registra el Descuento sobre Ventas
    	    		dol_syslog("Pago en al Contado o Anticipado - ".$cond_pago);
    	    		
    	    		$asiento = 1;
    				$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
    				//$codagr = "402.01"; //Devolución sobre Venta a Tasa General
    				$cat->fetch($rel->fk_cat_cta);
    				$cuenta = $cat->cta;
    				$debe = $total;
    				$haber = 0;
    				//$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    	    		
    	    		if ($fac_source->lines[0]->tva_tx > 15) {
   						//Se registra el IVA Trasladado Cobrado por que fue al Contado
   						$asiento ++;
   						$rel->fetch_by_code("IVA_TRAS_COBRADO");
   						//$codagr = "208.01"; //Iva Trasladado Cobrado
   						$cat->fetch($rel->fk_cat_cta);
   						$cuenta = $cat->cta;
   						$debe = $iva;
   						$haber = 0;
   	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
   					}
					
   					$asiento ++;
    				if ($mode_reglement == 4) {
		    			// Se recibe el pago en Efectivo
		    			$rel->fetch_by_code("EFECTIVO");
		    			//$codagr = "101.01";
		    		} else {
		    			// Cualquier otro valor se tomar como pago Bancario
		    			$rel->fetch_by_code("BANCOS_NAL");
		    			//$codagr = "102.01";
		    		}
		    		$cat->fetch($rel->fk_cat_cta);
		    		$cuenta = $cat->cta;
   					$debe = 0;
   					$haber = $amount;
   					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
   						
   				} else if($cond_pago == $payment::PAGO_A_CREDITO || $cond_pago == $payment::PAGO_EN_PARTES) {
   					//La venta fue 100% a credito.
   								
   					// Se registra el Descuento sobre Ventas
   					
   					dol_syslog("Pago a Acredito o En Partes - ".$cond_pago);
   					
   					$monto_credito = 0;
   					$monto_efectivo = 0;
   					if ($falta_pagar >= $amount) {
   						$monto_credito = $amount;
   					} else {
   						$monto_efectivo = $falta_pagar;
   						$monto_credito = $amount - $monto_efectivo;
   					}
   					
   					dol_syslog("Datos total factura=".$fac_source->total_ttc.", total pagado=$pagos_realizados, falta pagar=$falta_pagar, cantidad NC=$amount, Total=$total, iva=$iva, monto efvo=$monto_efectivo, monto cred=$monto_credito");
   					
   					$asiento = 1;
   					$rel->fetch_by_code("DEV_VTA_TASA_GRAL");
   					//$codagr = "402.01"; //Devolución sobre Venta a Tasa General
   					$cat->fetch($rel->fk_cat_cta);
   					$cuenta = $cat->cta;
   					$debe = $total;
   					$haber = 0;
   					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);

					if ($iva > 0) {
						//Se registra el IVA Trasladado Cobrado por que fue al contado
						$asiento ++;
						$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
						//$codagr = "209.01"; //Iva Trasladado No Cobrado
						$cat->fetch($rel->fk_cat_cta);
						$cuenta = $cat->cta;
						$debe = $iva;
						$haber = 0;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
					}
					
					if ($monto_efectivo > 0) {
						$asiento ++;
						if ($mode_reglement == 4) {
		    				// Se recibe el pago en Efectivo
		    				$rel->fetch_by_code("EFECTIVO");
		    				//$codagr = "101.01";
		    			} else {
		    				// Cualquier otro valor se tomar como pago Bancario
		    				$rel->fetch_by_code("BANCOS_NAL");
		    				//$codagr = "102.01";
		    			}
						//$codagr = "101.01";
		    			$cat->fetch($rel->fk_cat_cta);
		    			$cuenta = $cat->cta;
						$debe = 0;
						$haber = $monto_efectivo;
						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
					}
					
					$asiento ++;
					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($object->socid)) {
						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
						//El cliente tiene definida una cuenta para referenciar acientos contables
						$codagr = "";
						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
					} else {
						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
						$cuenta = $this->Get_Cliente_Cuenta($object->socid, $conf);
					}
					$debe = 0;
					$haber = $monto_credito;
					//$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
				}
    		} if ($fac->type == $fac::TYPE_DEPOSIT) {
    				
    			dol_syslog("FACTURA DE PAGO ANTICIPADO =======>");
    			
    			dol_syslog("Mode Reglement: ".$fac->fk_mode_reglement);
    	
    			//Obtener todos los pagos realizados a la Factura Original, para saber a que cuentas se deberá de afectar la Devolución.
    			$sql = 'SELECT SUM(pf.amount) as amount';
    			$sql .= ' FROM ' . MAIN_DB_PREFIX . 'c_paiement as c, ' . MAIN_DB_PREFIX . 'paiement_facture as pf, ' . MAIN_DB_PREFIX . 'paiement as p';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank as b ON p.fk_bank = b.rowid';
    			$sql .= ' LEFT JOIN ' . MAIN_DB_PREFIX . 'bank_account as ba ON b.fk_account = ba.rowid';
    			$sql .= ' WHERE pf.fk_facture = ' . $facid . ' AND p.fk_paiement = c.id AND pf.fk_paiement = p.rowid';
    			$sql .= ' ORDER BY p.datep, p.tms';
    	
    			$pagos_realizados = 0;
    			$result = $this->db->query($sql);
    			if ($result) {
    				$objp = $this->db->fetch_object($result);
    				if ($objp) {
    					$pagos_realizados = abs($objp->amount);
    				}
    				$this->db->free($result);
    			}
    				
    			$amount = $pagos_realizados;
    				
    			$total = $amount / (1 + ($fac->lines[0]->tva_tx) / 100);
    			$iva = $amount - $total;
    	
    			$pol = new Contabpolizas($this->db);
    				
    			$cuenta = "";
    			$codagr = "";
    				
    			dol_syslog("Pago Anticipado - ".$cond_pago);
    				
    			//La venta es cobrada por Adelantado.
    				
    			// print "Tipo poliza=".$tp." cons=".$cons;
    				
    			$tp = Contabpolizas::POLIZA_DE_INGRESO;
    			$concepto = "Ingreso por Venta a Cliente cobrada por Adelantado, Según Factura ".$facref;
    			$comentario = "Pago de Factura a Cliente con fecha del ".date("d-M-Y", $fecha);
    				
    			$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 1);
    				
    			$cuenta = "";
    			$codagr = "";
    				
    			dol_syslog("mode_regl = ".$fac->fk_mode_reglement." tva_tx=".$fac->lines[0]->tva_tx." Socid=".$fac->socid);
    				
    			//Se registra el ingreso Caja o Bancos
    			$asiento = 1;
    			if ($mode_reglement == 4) {
    				// Se recibe el pago en Efectivo
    				$rel->fetch_by_code("EFECTIVO");
    				//$codagr = "101.01";
    			} else {
    				// Cualquier otro valor se tomar como pago Bancario
    				$rel->fetch_by_code("BANCOS_NAL");
    				//$codagr = "102.01";
    			}
    			$cat->fetch($rel->fk_cat_cta);
    			$cuenta = $cat->cta;
    			$debe = $amount;
    			$haber = 0;
    			$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    				
    			if ($fac->lines[0]->tva_tx > 15) {
    				//Se registra el IVA Trasladado NO Cobrado por que fue al contado
    				$asiento ++;
    				$rel->fetch_by_code("IVA_TRAS_NO_COBRADO");
    				//$codagr = "209.01"; //Iva Trasladado NO Cobrado
    				$cat->fetch($rel->fk_cat_cta);
    				$cuenta = $cat->cta;
    				$debe = $iva;
    				$haber = 0;
    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    			}
    				
    			// Se registra la Ventas anticipada
    			// Se requiere saber si es un cliente nacional o extranjero.
    			$asiento ++;
    			dol_syslog("fac-socid=".$fac->socid);
    			if ($this->Get_Cliente_Proveedor_Pais($fac->socid) == $country_id) {
    				$rel->fetch_by_code("ANT_CTE_NAL");
    				//$codagr = "206.01"; //Nacional
    			} else {
    				$rel->fetch_by_code("ANT_CTE_EXT");
    				//$codagr = "206.02"; //Extranjero
    			}
    			$cat->fetch($rel->fk_cat_cta);
    			$cuenta = $cat->cta;
    			$debe = 0;
    			$haber = $amount;
    			$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    				
    			if ($fac->lines[0]->tva_tx > 15) {
    				//Se registra el IVA Trasladado Cobrado por que fue al Contado
    				$asiento ++;
    				$rel->fetch_by_code("IVA_TRAS_COBRADO");
    				//$codagr = "208.01"; //Iva Trasladado Cobrado
    				$cat->fetch($rel->fk_cat_cta);
    				$cuenta = $cat->cta;
    				$debe = 0;
    				$haber = $iva;
    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    			}
    				
    		}
    	}
    }
    
    public function Proveedor_Compra_a_Credito($object, $user, $conf) {
    	
    	$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
    	$country_id=$tmp[0];
    	
    	$rel = new Contabrelctas($this->db);
    	$cat = new Contabcatctas($this->db);
    	
    	$fac = new FactureFournisseurs($this->db);
    	$fac->fetch($object->id);
    	$facref = $fac->ref;
    	$facid = $fac->id;
    	$socid = $fac->so;
    	
    	$mismo_iva = true;
    	foreach ($fac->lines as $i => $line) {
    		dol_syslog("Valor de i = $i");
    		if ($i == 0) {
    			$tva_tx = $line->tva_tx;
    		} else {
    			if ($tva_tx != $line->tva_tx) {
    				$mismo_iva = false;
    			}
    		}
    		dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
    	}
    	
    	$cond_pago = 1;
    	$payment = new Contabpaymentterm($this->db);
    	$payment->fetch($fac->cond_reglement_id);
    	if ($payment->cond_pago) {
    		$cond_pago = $payment->cond_pago;
    	}
    	 
    	//Que tipo de poliza se trata?
    	dol_syslog("COMPRA A CREDITO - Proveedor_Compra_a_Credito() - Fecha Fact=".$fac->date);
    	dol_syslog("Tipo Factura=".$fac->type.", cond_reglement_id=".$fac->cond_reglement_id.", cond_pago=".$cond_pago);
    	$tipo_pol = "";
    	$concepto = "";
    	 
    	if ($fac->type == $fac::TYPE_STANDARD) {
    		if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
    			//la Compra es a Credito
    			// 2. Pago al Proveedor en 30 dias.
    			// 3. Pago en 30 dias Final del Mes.
    			// 4. Pago en 60 dias.
    			// 5. Pago en 60 dias Final del Mes.
    			$tp = Contabpolizas::POLIZA_DE_DIARIO;
    			$concepto = "Compra al Proveedor a Crédito, Según Factura ".$facref;
    		} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
    			//La venta es 50% a credito y 50% al contado.  
    			//8. El cliente paga la mitad ahorita y la mitad después.
    			$tp = Contabpolizas::POLIZA_DE_DIARIO;
    			$concepto = "Compra al Proveedor, 50% a Crédito y 50% al Contado, Según Factura ".$facref;
    			
    			if (!$mismo_iva) {
    				$ret = -1;
    				return $ret;
    			}
    		}
    	}
    	 
    	$pol = new Contabpolizas($this->db);
    	$exists = $pol->fetch_by_factura_Y_TipoPoliza($facid, $tp, 2);
    	 
    	if (! $exists) {
    		dol_syslog("No existe la póliza...");
    		if($fac->type == $fac::TYPE_STANDARD && ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO || $cond_pago == Contabpaymentterm::PAGO_EN_PARTES )) {
    			//$pol = new Contabpolizas($this->db);
    			$pol->fetch_last_by_tipo_pol($tp);
    			$cons = $pol->cons + 1;
    			// print "<br><br> ******* Se obtiene el consecutivo = ".$cons;
    			 
    			$pol->initAsSpecimen();
    			 
    			$pol->fecha = $fac->date;
    			$pol->anio = date("Y", $fac->date);
    			$pol->mes = date("m",$fac->date);
    			$pol->concepto = $concepto;
    			$pol->comentario = "Factura a Proveedor con fecha del ".date("d-M-Y", $fac->date); //,$fac->date);
    			$pol->tipo_pol = $tp;
    			$pol->cons = $cons;
    			$pol->fk_facture = $facid;
    			$pol->societe_type = 2;
    			
    			$pol->create($user);
    			$polid = $pol->id;
    			 
    			//Ahora se crearán los asientos contables para la póliza.
    			$ln = array();
    			foreach ($fac->lines as $j => $line) {
    				dol_syslog("remise_excent".$fac->lines[$j]->fk_remise_except);
    				if (! $fac->lines[$j]->fk_remise_except > 0) {
    					dol_syslog("Line id = ".$line->rowid." - ".$fac->lines[$j]->rowid);
						
    					//Analizando si hay descuento sobre compras
   						$sub_total = $line->pu_ht * $line->qty;
    					$descto = ($sub_total * $line->remise_percent / 100); // + $dscto_ht;
    					$total = $sub_total - $descto;
    					$iva = $total * $line->tva_tx / 100;
    					
    					$cuenta = "";
    					$codagr = "";
    					//Es poliza de Ingreso cuando se hace una venta al contado o con cheque al contado.
    					//Es poliza de Egresos cuando se hace un pago con Cheque o se emite un cheque.
    					//Es de Diario cuando se trata de cualquier otro tipo de poliza.
    					if($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
    						dol_syslog("PAGO A CREDITO");
    						//La compra es 100% a credito.
    							
    						// Póliza de Diario
    						// Compras				104.40
    						// IVA Acred Pagado		 10.00
    						// 		Proveedores				100.00
    						//		IVA Pend. Pago			 14.40
    							
    						// print "Tipo poliza=".$tp." cons=".$cons;
    							
    						$asiento = 1;
		    				if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
		    					$rel->fetch_by_code("COMP_NAL");
		    					//$codagr = "502.01";
		    				} else {
		    					$rel->fetch_by_code("COMP_EXT");
		    					//$codagr = "502.03";
		    				}
		    				$cat->fetch($rel->fk_cat_cta);
		    				$cuenta = $cat->cta;
		    				$debe = $sub_total;
		    				$haber = 0;
		    				$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		    				
		   					//Se registra el IVA Acred No Pagado
		   					$asiento ++;
		   					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
		   						$rel->fetch_by_code("IVA_PEND_PAGO");
		    					//$codagr = "119.01";
		   					} else {
		   						$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
		    					//$codagr = "119.02";
		   					}
		   					$cat->fetch($rel->fk_cat_cta);
		   					$cuenta = $cat->cta;
		   					$debe = $iva;
		   					$haber = 0;
		   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		    				
    						if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
    							//Se recibe el pago contra el cliente (NO Caja, No Bancos)
    							//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
    							//El cliente tiene definida una cuenta para referenciar acientos contables
    							$codagr = "";
    							// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
    						} else {
    							//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
    							$cuenta = $this->Get_Proveedor_Cuenta($fac->fk_soc, $conf);
    						}
		    				$debe = 0;
		    				$haber = $total + $iva;
		    				$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		    				 
		    				//Analizando si hay descuento sobre compras
		    				if ($descto > 0 ) {
		    					$asiento ++;
		    					$rel->fetch_by_code("DEV_COMP");
		    					//$codagr = "503.01";
		    					$cat->fetch($rel->fk_cat_cta);
		    					$cuenta = $cat->cta;
		    					$debe = 0;
		    					$haber = $descto;
		    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		    				}
    						
    					} else if($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
    						dol_syslog("PAGO EN PARTES");
    						//la Venta es 50% al Contado y 50% a crédito
    						// Se registra una Venta 50% al Contado y 50% a Credito y 10% de Desc.
    							
    						// Póliza de Diario
    						// Compras				52.20
    						// IVA Acred Pag		 5.00
    						// 		Proveedores				50.00
    						//		Dev y Rev s/vtas		 7.20
    							
    						//Se registra el ingreso a Clientes
    						if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
		    					$rel->fetch_by_code("COMP_NAL");
		    					//$codagr = "502.01";
		    				} else {
		    					$rel->fetch_by_code("COMP_EXT");
		    					//$codagr = "502.03";
		    				}
		    				$cat->fetch($rel->fk_cat_cta);
		    				$cuenta = $cat->cta;
		    				$debe = $sub_total / 2;
		    				$haber = 0;
		    				$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
    						
    						//Se registra el IVA Acred No Pagado
    						
		   					$asiento ++;
		   					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
		   						$rel->fetch_by_code("IVA_PEND_PAGO");
		    					//$codagr = "119.01";
		   					} else {
		   						$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
		    					//$codagr = "119.02";
		   					}
		   					$cat->fetch($rel->fk_cat_cta);
		   					$cuenta = $cat->cta;
		   					$debe = $iva / 2;
		   					$haber = 0;
		   					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
    							
    						if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
    							//Se recibe el pago contra el cliente (NO Caja, No Bancos)
    							//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
    							//El cliente tiene definida una cuenta para referenciar acientos contables
    							$codagr = "";
    							// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
    						} else {
    							//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
    							$cuenta = $this->Get_Cliente_Cuenta($fac->fk_soc, $conf);
    						}
		    				$debe = 0;
		    				$haber = ($total + $iva) / 2;
		    				$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		    				 
		    				//Analizando si hay descuento sobre compras
		    				if ($descto > 0 ) {
		    					$asiento ++;
		    					$rel->fetch_by_code("DEV_COMP");
		    					//$codagr = "503.01";
		    					$cat->fetch($rel->fk_cat_cta);
		    					$cuenta = $cat->cta;
		    					$debe = 0;
		    					$haber = $descto / 2;
		    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
		    				}
    					}
    				} else { dol_syslog("Esta línea no se debe procesar"); }
    			}
    			
    			$jj = 0;
    			while ($jj < sizeof($ln[$tp])) {
    				$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
    				$jj++;
    			}
    		}
    	}
    }
    
    public function Proveedor_Pago_Factura($object, $user, $conf) {
    	
    	dol_syslog("Función: Proveedor_Pago_Factura");
    	
    	dol_syslog("Búsqueda de Paiments - getBillsArray(), Object->id = ".$object->id." paiment:".$object->fk_paiement." facture:".$object->fk_facture." amount".$object->amount);
    	
    	$rel = new Contabrelctas($this->db);
    	$cat = new Contabcatctas($this->db);
    	$prod = new Product($this->db);
    	
    	$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
    	$country_id=$tmp[0];
    	
    	$paim = new PaiementFourns($this->db);
    	$paim->fetch($object->id);
    	$fecha = $paim->date;
    	
    	$paim->id = $object->id;
    	$a_fac = $paim->getBillsArray();
    	 
    	foreach ($a_fac as $idx => $value) {
    		 
    		dol_syslog("Búsqueda de la Factura a Proveedor - country_id=$country_id");

    		$facid = $value;
    		
    		$fac = new FactureFournisseurs($this->db);
    		$fac->fetch($facid);
    		$facref = $fac->ref;
    		$facid = $fac->id;
    		
    		//$mismo_iva = Ver_si_los_items_tienen_mismo_iva($fac);
    		
    		$mismo_iva = true;
    		foreach ($fac->lines as $i => $line) {
    			dol_syslog("Valor de i = $i");
    			if ($i == 0) {
    				$tva_tx = $line->tva_tx;
    			} else {
    				if ($tva_tx != $line->tva_tx) {
    					$mismo_iva = false;
    				}
    			}
    			dol_syslog("tva_tx=$tva_tx, obj->tva_tx=".$line->tva_tx);
    		}
    		
    		$sup = new Contabctassupplier($this->db);
    		$a_sup = $sup->fetch_array_by_socid($fac->fk_soc);
    		
    		//Se obtienen los ids de cada una de las lineas de la factura ( o sea los ids de factura detalle)
    		$a_lines_ids = array();
    		foreach ($fac->lines as $i => $line) {
    			$a_lines_ids[] = $line->rowid;
    		}
    		$str_lines_ids = implode(",", $a_lines_ids);
    			
    		dol_syslog("FACREF = ".$facref." FACID=".$facid." amount=".$object->total_ttc." Fac Type=".$fac->type.", cond_reglement_id=".$fac->cond_reglement_id);

    		if ($fac->type == $fac::TYPE_STANDARD) {
    	
    			dol_syslog("Función: Proveedor_Pago_Factura:: FACTURA STANDARD");
    			 
    			//Obtener todos los pagos realizados a la Factura Original, para saber a que cuentas se deberá de afectar la Devolución.
    			// Payments already done (from payment on this invoice)
    			$sql = "SELECT SUM(pf.amount) AS amount ";
    			$sql .= "FROM ".MAIN_DB_PREFIX."c_paiement AS c, ".MAIN_DB_PREFIX."paiementfourn_facturefourn AS pf, ".MAIN_DB_PREFIX."paiementfourn AS p ";
    			$sql .= "WHERE pf.fk_facturefourn = ".$facid." AND p.fk_paiement = c.id AND pf.fk_paiementfourn = p.rowid AND pf.fk_paiementfourn = ".$object->id;
    			$sql .= ' ORDER BY p.datep, p.tms';
    			 
    			dol_syslog("Obtrención de los Pagos realizados a la Fact. Original - sql:".$sql);
    			$amount = 0;
    			$result = $this->db->query($sql);
    			if ($result) {
    				$objp = $this->db->fetch_object($result);
    				if ($objp) {
    					$amount = $objp->amount;
    				}
    				$this->db->free($result);
    			}
    	
    			$fac = new FactureFournisseurs($this->db);
    			$fac->fetch($facid);
    			$facref = $fac->ref;
    			$facid = $fac->id;
    			
    			$anombrede = "";
    			$numcheque = "";
    			
    			if ($fac->mode_reglement_id == 4) {
    				// Se hace el pago en Efectivo
    				$tp = Contabpolizas::POLIZA_DE_EGRESO;
    			} else {
    				// Cualquier otro valor se tomar como pago Bancario
    				$tp = Contabpolizas::POLIZA_DE_CHEQUES;
    				$anombrede = "";
    				$numcheque = "";
    			}
    			
    			//Ver si el pago es al contado, credito, cobro anticipado, 50 y 50.
    			$payment = new Contabpaymentterm($this->db);
    			$payment->fetch($fac->cond_reglement_id);
    			$cond_pago = $payment->cond_pago;
    			 
    			//$total = $amount / (1 + ($fac->lines[0]->tva_tx) / 100);
    			//$iva = $amount - $total;
    			 
    			dol_syslog("La Póliza no existe, se tiene que crear.  total=$total, amount=$amount, iva=$iva, tva_tx=$fac->lines[0]->tva_tx");
    	
    			if ($cond_pago == Contabpaymentterm::PAGO_AL_CONTADO) {
    				
    				dol_syslog("Función: Proveedor_Pago_Factura:: PAGO_AL_CONTADO - ".$cond_pago);

    				if ($fac->lines[0]->product_type == 0) {
    					//Es el pago de una compra de Mercancía al Proveedor
    					
    					dol_syslog("Pago de Factura, Compra de mercancía");
	    				// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
    					//la Compra es al Contado
    					// 1. Se paga al Proveedor a la recepción de la factura
    					
    					$concepto = "Egreso por Compra a Proveedor al Contado, Según Factura ".$facref;
    					$comentario = "Pago de Factura a Proveedor con fecha del ".date("d-M-Y", $fecha);
    					 
    					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, $anombrede, $numcheque, $fecha, 2);
    					
    					$cuenta = "";
    					$codagr = "";
    		
    					$sub_total = 0;
    					$total = 0;
    					$descto = 0;
    					$iva = 0;
    					foreach($fac->lines as $j => $l) {
    						dol_syslog("total_ht=".$l->total_ht." subprice=".$l->subprice." qty=".$l->qty." remise_perc=".$l->remise_percent);
    						$sub_total += $l->pu_ht * $l->qty;
    						$descto += $l->pu_ht * $l->qty * $l->remise_percent / 100;
    						$tasa_gral = $l->tva_tx / 100;
    						$iva += ($sub_total - $descto) * $tasa_gral;
    						dol_syslog("sub_total=$sub_total, descto=$descto");
    					}
    					$total_si =  $sub_total - $descto;
    					//$iva = $total_si * $tasa_gral;
    					$total_ci = $total_si + $iva; 
    					
    					dol_syslog("Algun dato interesante arriba?");
    		
    					// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
    					 
    					$asiento = 1;
    					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
    						$rel->fetch_by_code("COMP_NAL");
    						//$codagr = "502.01";
    					} else {
    						$rel->fetch_by_code("COMP_EXT");
    						//$codagr = "502.03";
    					}
    					$cat->fetch($rel->fk_cat_cta);
    					$cuenta = $cat->cta;
    					$debe = $sub_total;
    					$haber = 0;
    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    					
   						//Se registra el IVA Acred Pagado
   						$asiento ++;
    					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
   							$rel->fetch_by_code("IVA_ACRED_PAG");
   							//$codagr = "118.01";
   						} else {
   							$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
   							//$codagr = "118.02";
   						}
   						$cat->fetch($rel->fk_cat_cta);
   						$cuenta = $cat->cta;
   						$debe = $iva;
   						$haber = 0;
   						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    					
   						$asiento ++;
    					if ($fac->mode_reglement_id == 4) {
    						// Se recibe el pago en Efectivo
    						$rel->fetch_by_code("EFECTIVO");
    						//$codagr = "101.01";
    					} else {
    						// Cualquier otro valor se tomar como pago Bancario
    						$rel->fetch_by_code("BANCOS_NAL");
    						//$codagr = "102.01";
    					}
    					$cat->fetch($rel->fk_cat_cta);
    					$cuenta = $cat->cta;
    					$debe = 0;
    					$haber = $total_ci;
    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    					 
    					//Analizando si hay descuento sobre compras
    					if ($descto > 0 ) {
    						$asiento ++;
    						$rel->fetch_by_code("DEV_COMP");
    						//$codagr = "503.01";
    						$cat->fetch($rel->fk_cat_cta);
    						$cuenta = $cat->cta;
    						$debe = 0;
    						$haber = $descto;
    						$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    					}
    				} else {
    					//Es un pago a un Proveedor de Servicios
    					//var_dump("object");
    					//var_dump($object);
    					dol_syslog("Pago de factura, Pago de Servicios");
    					
    					$concepto = "Egreso por Pago al Contado de Servicios, Según Factura ".$facref;
    					$comentario = "Pago realizado al día ".date("d-M-Y", $fecha);
    					
    					/* if ($this->Get_Tipo_Cliente_Proveedor($socid) == 8) {
    						//Es un particular
    						if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
    							//Proveedor Particular Nacional
    						} else {
    							//Proveedor Particular Extranjero
    						}
    					} else {
    						//Es una empresa
    						if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
    							//Proveedor Empresa Nacional
    						} else {
    							//Proveedor Empresa Extranjera
    						}
    					} */

    					$ln = array();
    					
    					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, $anombrede, $numcheque, $fecha, 2);
    					
    					//Pero primero antes que todo capturamos el dato del pago al banco o por medio de caja.
    					if ($fac->mode_reglement_id == 4) {
    						// Se recibe el pago en Efectivo
    						$rel->fetch_by_code("EFECTIVO");
    						//$codagr = "101.01";
    					} else {
    						// Cualquier otro valor se tomar como pago Bancario
    						$rel->fetch_by_code("BANCOS_NAL");
    						//$codagr = "102.01";
    					}
    					$cat->fetch($rel->fk_cat_cta);
    					$cuenta = $cat->cta;
    					$debe = 0;
    					$haber = $fac->total_ttc;
    					$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
    					
    					foreach($fac->lines as $j => $l) {
    						//var_dump("J=".$j);
    						//var_dump ($l);
    						//Buscar el numero de cuenta correspondiente a esta linea
    						//Con la referencia del producto, podemos obtener la cuenta a proveedor relacionada
    						//De otra forma Buscar en la tabla llx_contab_fourn_product_line y obtener el numero de cuenta contable.
    						//Si aún así no hay numero de cuenta,
    						//			entonces la factura se creó de una forma que no estaba planeada por este módulo
    						
    						if ($l->fk_product > 0) {
	    						$prod->fetch($l->fk_product);
    							$num_cta = $prod->accountancy_code_buy;
    							if ($cat->fetch_by_Cta($num_cta) > 0) {
    								//Ok parece que esta línea de servicio si tiene su numero de cuenta en la referencia del producto.
    								//Obtenemos la información para generar este asiento contable.
    								
    								$cuenta = $cat->cta;
    								$debe = 0;
    								$haber = abs($l->pu_ht);
    								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
    							}
    						} else {
    							//La cuenta habrá que buscarla en la tabla llx_contab_fourn_product_line
    							$sql = "Select * From llx_contab_fourn_product_line Where fk_facture=".$facid." And rowid_line = '$l->rowid' And soc_type = 1 ";
    							$res = $this->db->query($sql);
    							dol_syslog("Proveedor_Pago_Factura de Servicio:: sql:".$sql);
    							if ($res) {
    								//Ok si se encontró el indice que nos dará la cuenta relacionada.
    								$row = $this->db->fetch_row($res);
    								if ($row) {
    									$cat->fetch($row[3]);
	    								$cuenta = $cat->cta;
	    								$debe = $l->pu_ht;
	    								$haber = 0;
	    								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
	    								
	    								$cuenta = "118.01"; //IVA Acreditable Pagado
	    								$debe = $l->pu_ht * $l->tva_tx / 100;
	    								$haber = 0;
	    								$ln = $this->Cliente_Proveedor_Almacena_Poliza_Det($user, $polid, $ln, $tp, $cuenta, $debe, $haber);
    								}
    							} else {
    								//Acaray, aqui tengo un detalle ==> El módulo no detecta este tipo de lineas en la factura realizada
    								dol_syslog("El módulo de contabilidad tiene un bug.  No se encontró una forma de procesar esta petición.  Datos de la factura: N0.:$facid, Linea:".$l->rowid.", monto:".$l->pu_ht);
    							}
    						}
    					}
    					
    					$jj = 0;
    					while ($jj < sizeof($ln[$tp])) {
    						$this->Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln[$tp][$jj]);
    						$jj++;
    					}
    				}
    			} else if ($cond_pago == Contabpaymentterm::PAGO_A_CREDITO) {
    				
    				dol_syslog("Función: Proveedor_Pago_Factura - Pago a Credito - ".$cond_pago);
    				
    				$concepto = "Pago de Factura a Proveedor por Compra a Crédito, Según Factura ".$facref;
    				$comentario = "Pago de Factura a Proveedor con fecha del ".date("d-M-Y", $fecha);
    				
    				$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 2);
    				
    				$cuenta = "";
    				$codagr = "";
    				
    				// Pago a la recepción de la factura, lo cual indica que es en al contado por que se le de ahí mismo la factura al cliente.
    				
    				//la Compra es a Crédito
    				// Se registra una Venta al Contado con el 10% de Descuento
    				// Compras				100.00
    				// IVA Pend. Pago		 14.40
    				// 		Proveedores				104.40
    				//		Dev s/compra			 10.00
    					
    				// En este momento de Recibir el Pago, Se debe crear una póliza con la siguiente estructura:
    				// Proveedores			104.40
    				// IVA Acreditable		 10.00
    				// 		Efectivo			100.00
    				//		IVA Pend Pago		 14.40
    					
    				/* $sub_total = 0;
    				$total = 0;
    				$descto = 0;
    				$iva = 0; */
    				
    				/* foreach($fac->lines as $j => $l) {
    					dol_syslog("total_ht=".$l->total_ht." subprice=".$l->pu_ht." qty=".$l->qty." remise_perc=".$l->remise_percent);
    					$sub_total += $l->pu_ht * $l->qty;
    					$descto += $l->pu_ht * $l->qty * $l->remise_percent / 100;
    					$tasa_gral = $l->tva_tx / 100;
    					dol_syslog("sub_total=$sub_total, descto=$descto");
    				} */
    				
    				/* $total_si =  $sub_total - $descto;
    				$iva = $total_si * $tasa_gral;
    				$total_ci = $total_si + $iva;  */
    				$iva = 0;
    				if ($mismo_iva) {
    					$iva = ($amount / (1 + (16 / 100))) * 16 / 100;
    				} else {
    					foreach ($fac->lines as $i => $line) {
    						$iva += $line->total_tva;
    					}
    				}
    				dol_syslog("Algun dato interesante arriba? mismo iva=$mismo_iva, iva=$iva");
    	
    				$asiento = 1;
    				if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
    					//Se recibe el pago contra el cliente (NO Caja, No Bancos)
    					//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
    					//El cliente tiene definida una cuenta para referenciar acientos contables
    					$codagr = "";
    					// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
    				} else {
    					//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
    					$cuenta = $this->Get_Cliente_Cuenta($fac->fk_soc, $conf);
    				}
    				$debe = $amount;
    				$haber = 0;
    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    				
   					//Se registra el IVA Acred Pagado
   					$asiento ++;
   					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
   						$rel->fetch_by_code("IVA_ACRED_PAG");
   						//$codagr = "118.01";
   					} else {
   						$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
   						//$codagr = "118.02";
   					}
   					$cat->fetch($rel->fk_cat_cta);
   					$cuenta = $cat->cta;
   					$debe = $iva; //($amount / (1 + (16 / 100))) * 16 / 100;
   					$haber = 0;
   					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    				
   					$asiento ++;
    				if ($fac->mode_reglement_id == 4) {
    					// Se recibe el pago en Efectivo
    					$rel->fetch_by_code("EFECTIVO");
    					//$codagr = "101.01";
    				} else {
    					// Cualquier otro valor se tomar como pago Bancario
    					$rel->fetch_by_code("BANCOS_NAL");
    					//$codagr = "102.01";
    				}
    				$cat->fetch($rel->fk_cat_cta);
    				$cuenta = $cat->cta;
    				$debe = 0;
    				$haber = $amount;
    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		    		
    				//IVA Pendiente Pago.
    				$asiento ++;
   					if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
   						$rel->fetch_by_code("IVA_PEND_PAGO");
    					//$codagr = "119.01";
   					} else {
   						$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
    					//$codagr = "119.02";
   					}
   					$cat->fetch($rel->fk_cat_cta);
   					$cuenta = $cat->cta;
   					$debe = 0;
   					$haber = $iva; //($amount / (1 + (16 / 100))) * 16 / 100;
   					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    				
    			} else if ($cond_pago == Contabpaymentterm::PAGO_EN_PARTES) {
    				
    				dol_syslog("Función: Proveedor_Pago_Factura - Pago En Partes - ".$cond_pago);
    				
    				$concepto = "Pago de Factura a Proveedor por Compra a Crédito, Según Factura ".$facref;
    				$comentario = "Pago de Factura a Proveedor con fecha del ".date("d-M-Y");
    				
    				$pol = new Contabpolizas($this->db);
    				$res = $pol->fetch_by_factura_Y_TipoPoliza($facid, $tp, 2);
    				if ($res == 1) {
    					//La póliza ya existe por lo tanto se tiene que generar la otra póliza de  que cancele la póliza de diario
    						
    					dol_syslog("Se genera la Póliza de Egreso que Cancela a la de Diario");
    					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 2);
    						
    					$cuenta = "";
    					$codagr = "";
    	
    					$sub_total = 0;
    					$total = 0;
    					$descto = 0;
    					$iva = 0;
    					foreach($fac->lines as $j => $l) {
    						dol_syslog("total_ht=".$l->total_ht." pu_ht=".$l->pu_ht." qty=".$l->qty." remise_perc=".$l->remise_percent);
    						if ($l->fk_remise_except > 0) {
    							$descto += abs($l->total_ht);
    						} else {
    							$sub_total += $l->pu_ht * $l->qty;
    							$descto += $l->pu_ht * $l->qty * $l->remise_percent / 100;
    						}
    						if ($l->tva_tx > 15) {
    							$dscto_tasa_gral = 1;
    							$tasa_gral = $l->tva_tx / 100;
    							$codagr = "402.01"; //Dscto a Tasa General
    						} else {
    							$tasa_gral = 0;
    							$dscto_tasa_gral = 0;
    							$codagr = "402.02"; //Dscto a Tasa 0%
    						}
    						dol_syslog("sub_total=$sub_total, descto=$descto");
    					}
    					$total_si =  $sub_total - $descto;
    					$iva = $total_si * $tasa_gral;
    					$total_ci = $total_si + $iva;
    					
    					$asiento = 1;
    					if ($cuenta = $this->Get_Cliente_Proveedor_Cta($fac->fk_soc)) {
    						//Se recibe el pago contra el cliente (NO Caja, No Bancos)
    						//No se hace nada por que lo único que necesito ya está en la instrucción del IF (el número de cuenta).
    						//El cliente tiene definida una cuenta para referenciar acientos contables
    						$codagr = "";
    						// print "	ESTE ES EL NUMERO DE CUENTA:".$cuenta;
    					} else {
    						//El cliente no tiene una cuenta asignada, por lo que se toma la que esté con el codagr preseleccionado.
    						$cuenta = $this->Get_Cliente_Cuenta($fac->fk_soc, $conf);
    					}
		    			$debe = $total_ci / 2;
		    			$haber = 0;
		    			$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    					
		    			//Se registra el IVA Acred Pagado
		    			$asiento ++;
		    			if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
		    				$rel->fetch_by_code("IVA_ACRED_PAG");
		    				//$codagr = "118.01";
		    			} else {
		    				$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
		    				//$codagr = "118.02";
		    			}
		    			$cat->fetch($rel->fk_cat_cta);
		    			$cuenta = $cat->cta;
		    			$debe = $iva / 2;
		    			$haber = 0;
		    			$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    						
		    			if ($fac->mode_reglement_id == 4) {
		    				// Se recibe el pago en Efectivo
		    				$rel->fetch_by_code("EFECTIVO");
		    				//$codagr = "101.01";
		    			} else {
		    				// Cualquier otro valor se tomar como pago Bancario
		    				$rel->fetch_by_code("BANCOS_NAL");
		    				//$codagr = "102.01";
		    			}
		    			$cat->fetch($rel->fk_cat_cta);
		    			$cuenta = $cat->cta;
		    			$debe = 0;
		    			$haber = $total_ci / 2;
		    			$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
		    			
		    			//Se registra el IVA Acred Pend Pagao
		   				$asiento ++;
		   				if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
		   					$rel->fetch_by_code("IVA_PEND_PAGO");
		    				//$codagr = "119.01";
		   				} else {
		   					$rel->fetch_by_code("IVA_IMP_PEND_PAGO");
		    				//$codagr = "119.02";
		   				}
		   				$cat->fetch($rel->fk_cat_cta);
		   				$cuenta = $cat->cta;
		   				$debe = 0;
		   				$haber = $iva / 2;
		   				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
    				} else {
    					//La póliza de Ingreso no existe por lo tanto es el primer pago realizad por la mitad.
    					dol_syslog("Se genera la Póliza de Egresos o Cheque que Cubre la Mitad inicial.  Lo otro fue a Crédito");
    					$polid = $this->Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, "", "", $fecha, 2);
    					
    					$cuenta = "";
    					$codagr = "";
    		    
    					$sub_total = 0;
    					$total = 0;
    					$descto = 0;
    					$iva = 0;
    					foreach($fac->lines as $j => $l) {
    						dol_syslog("total_ht=".$l->total_ht." pu_ht=".$l->pu_ht." qty=".$l->qty." remise_perc=".$l->remise_percent);
    						if ($l->fk_remise_except > 0) {
    							$descto += abs($l->total_ht);
    						} else {
    							$sub_total += $l->pu_ht * $l->qty;
    							$descto += $l->pu_ht * $l->qty * $l->remise_percent / 100;
    						}
    						if ($l->tva_tx > 15) {
    							$tasa_gral = $l->tva_tx / 100;
    						} else {
    							$tasa_gral = 0;
    						}
    						dol_syslog("sub_total=$sub_total, descto=$descto");
    					}
    					$total_si =  $sub_total - $descto;
    					$iva = $total_si * $tasa_gral;
    					$total_ci = $total_si + $iva;
    		    
    					//Ya fue pagada la mitad?  ==> es que esa mitad se supone que fue al contado, por lo tanto mientras no se haya
    					//								pagado esa mitad, todo lo que se cobre será enviado como ingreso por venta al contado
    					//	SI, ya fue pagada => todo lo que se cobre, será enviado como contrapartida para la poliza de Diario que se generó
    					//      al momento de validar la factura.
    					   	
    					//La Venta es 50% a credito y 50% al contado.
    					//8. Se paga al provedor la mitad ahorita y la mitad después.
    	
    					// Póliza de Cheque o de Egresos
    					// Compras				50.00
    					// IVA Acred Pag		 7.20
    					// 		Efectivo					52.20
    					//		Dev. y Rev s/comp			 5.00
    	
    					// print "Tipo poliza=".$tp." cons=".$cons;
    	
    					//Se registra la Compra Nacional o Extranjera
	    				$asiento = 1;
	    				if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
	    					$rel->fetch_by_code("COMP_NAL");
	    					//$codagr = "502.01";
	    				} else {
	    					$rel->fetch_by_code("COMP_EXT");
	    					//$codagr = "502.03";
	    				}
	    				$cat->fetch($rel->fk_cat_cta);
	    				$cuenta = $cat->cta;
	    				$debe = $sub_total / 2;
	    				$haber = 0;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    				
	   					//Se registra el IVA Acred Pagado
	   					$asiento ++;
	    				if ($this->Get_Cliente_Proveedor_Pais($fac->fk_soc) == $country_id) {
	   						$rel->fetch_by_code("IVA_ACRED_PAG");
	    					//$codagr = "118.01";
	   					} else {
	   						$rel->fetch_by_code("IVA_ACRED_IMP_PAG");
	    					//$codagr = "118.02";
	   					}
	   					$cat->fetch($rel->fk_cat_cta);
	   					$cuenta = $cat->cta;
	   					$debe = $iva / 2;
	   					$haber = 0;
	   					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    				
	    				if ($fac->mode_reglement_id == 4) {
	    					// Se recibe el pago en Efectivo
	    					$rel->fetch_by_code("EFECTIVO");
	    					//$codagr = "101.01";
	    				} else {
	    					// Cualquier otro valor se tomar como pago Bancario
	    					$rel->fetch_by_code("BANCOS_NAL");
	    					//$codagr = "102.01";
	    				}
	    				$cat->fetch($rel->fk_cat_cta);
	    				$cuenta = $cat->cta;
	    				$debe = 0;
	    				$haber = $total_ci / 2;
	    				$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    				 
	    				//Analizando si hay descuento sobre compras
	    				if ($descto > 0 ) {
	    					$asiento ++;
	    					$rel->fetch_by_code("DEV_COMP");
	    					//$codagr = "503.01";
	    					$cat->fetch($rel->fk_cat_cta);
	    					$cuenta = $cat->cta;
	    					$debe = 0;
	    					$haber = $descto / 2;
	    					$this->Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid);
	    				}
    				}
    			}
    		} else if ($fac->type == $fac::TYPE_CREDIT_NOTE) {
    			// Hasta la Versión 3.6.2 Dolibarr no tenía implementaddo este tipo de facturas para Proveedores
    		} if ($fac->type == $fac::TYPE_DEPOSIT) {
    			// Hasta la Versión 3.6.2 Dolibarr no tenía implementaddo este tipo de facturas para Proveedores
    		}
    	}
    }
    
    /* public function Ver_si_los_items_tienen_mismo_iva($fac) {
    	$mismo_iva = true;
    	foreach ($fac->lines as $i => $line) {
    		if ($i == 0) {
    			$tva_tx = $obj->tva_tx;
    		} else {
    			if ($tva_tx != $obj->tva_tx) {
    				$mismo_iva = false;
    			}
    		}
    	}
    	return $mismo_iva;
    } */
    
    public function Crea_Poliza_Enc($tp, $concepto, $comentario, $facid, $anombrede='', $numcheque='', $fecha='', $st='') {    	
    	dol_syslog("Crea_Poliza_Enc mes:".$cfg->mes." anio:".$cfg->anio);
    	$pol = new Contabpolizas($this->db);
    	$pol->fetch_last_by_tipo_pol($tp);
    	$cons = $pol->cons + 1;
    	 
    	$pol->initAsSpecimen();

    	$pol->anio = date("Y",$fecha);
    	$pol->mes = date("m",$fecha);
    	$pol->fecha = fecha;
    	$pol->concepto = $concepto;
    	$pol->comentario = $comentario;
    	$pol->tipo_pol = $tp;
    	$pol->cons = $cons;
    	$pol->anombrede = $anombrede;
    	$pol->numcheque = $numcheque;
    	$pol->fk_facture = $facid;
    	$pol->societe_type = $st;

    	$polid = -1;
    	
    	if ($pol->create($user)) {
    		$polid = $pol->id;
    	}
    	
    	return $polid;
    }
    
    public function Cliente_Borrar_Poliza($object, $user) {
    	dol_syslog("Cliente_Borra_Poliza");

    	$fac = new Factures($this->db);
    	$fac->fetch($object->id);
    	$facref = $fac->ref;
    	$facid = $fac->id;
    	
    	dol_syslog("** Factura encontrada: ".$fac->ref);
    	$poldet = new Contabpolizasdet($this->db);
    	if ($poldet->delete_by_facture($user, $facid)) {
    		dol_syslog("** Se borró el Detalle de la póliza ligada a esta factura");
	    	$pol = new Contabpolizas($this->db);
    		if ($pol->delete_by_facture($user, $facid)) {
    			dol_syslog("** Se borro el Encabezado de la póliza ligada a esta factura");
    		} else {
    			dol_syslog("** No se pudo borrar el Encabezado de la póliza ligada a esta factura");
    		}
    	} else {
    		dol_syslog("** No se pudo borrar el Detalle de la póliza ligada a esta factura");
    	}
    }
    
    public function Cliente_Proveedor_Almacena_Poliza_Det($user, $fk_pol, $ln, $tp, $cuenta, $debe, $haber, $tasa_iva=-1) {
    	dol_syslog("Cliente_Proveedor_Almacena_Poliza_Det: fk_pol=$fk_pol, tp=$tp, cuenta=$cuenta, debe=$debe, haber=$haber");
    	$asiento = 0;
    	if (sizeof($ln[$tp]) > 0) {
	    	foreach ($ln[$tp] as $jj => $line) {
    			if ($asiento < $line['asiento']) {
   	 				$asiento = $line['asiento'];
    			}
	    	}
    	}
    	$asiento ++;
   		
    	$jj = 0;
    	$found = false;
    	while ($jj < sizeof($ln[$tp])) {
    		if ($ln[$tp][$jj]['cuenta'] == $cuenta) {
    			$found = true;
    			$j = $jj;
    		}
    		$jj ++;
    	}
    	if (! $found) {
	    	$ln_aux = array();
	    	$ln_aux['asiento'] = $asiento;
	    	$ln_aux['cuenta'] = $cuenta;
	    	$ln_aux['debe'] = $debe;
	    	$ln_aux['haber'] = $haber;
	    	$ln_aux['fk_poliza'] = $fk_pol;
	    	$ln_aux['tasa_iva'] = $tasa_iva;
	    	$ln[$tp][] = $ln_aux;
		} else {
    		$ln[$tp][$j]['debe'] = $ln[$tp][$j]['debe'] + $debe;
    		$ln[$tp][$j]['haber'] = $ln[$tp][$j]['haber'] + $haber;
    	}
    	return $ln;
    }
    
    public function Cliente_Proveedor_Crea_Poliza_Det_From_Array($user, $ln) {
    	dol_syslog("Cliente_Proveedor_Crea_Poliza_Det_From_Array");
    	/* require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php'; */
    	
    	$poldet = new Contabpolizasdet($this->db);
    	$poldet->asiento = $ln['asiento'];
    	$poldet->cuenta = $ln['cuenta'];
    	$poldet->debe = $ln['debe'];
    	$poldet->haber = $ln['haber'];
    	$poldet->fk_poliza = $ln['fk_poliza'];
    
    	$poldet->create($user);
    }
    
    public function Cliente_Proveedor_Crea_Poliza_Det($user, $asiento, $cuenta, $debe, $haber, $polid) {
    	dol_syslog("Cliente_Proveedor_Crea_Poliza_Det");
    	/* require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpolizasdet.class.php'; */
    	 
    	dol_syslog("asiento=$asiento, cuenta=$cuenta, debe=$debe, haber=$haber, polid=$polid");
    	$poldet = new Contabpolizasdet($this->db);
    	$poldet->asiento = $asiento;
    	$poldet->cuenta = $cuenta;
    	$poldet->debe = $debe;
    	$poldet->haber = $haber;
    	$poldet->fk_poliza = $polid;
    
    	$poldet->create($user);
    }
    
    public function Get_Cliente_Proveedor_Cta($socid) {
    	dol_syslog("Get_Cliente_Proveedor_Cta=".$socid);
    	/* require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php'; */
    	$soc = new Societe($this->db);
    	$soc->fetch($socid);
    	if ($soc->client == 1) { $res = $soc->code_compta; }
    	else if($soc->fournisseur == 1) { $res = $soc->code_compta_fournisseur; }
    	dol_syslog("El número de cuenta cte/prov = ".$res);
    	return $res;
    }
    
    public function Get_Cliente_Cuenta($socid, $conf) {
    	dol_syslog("Get_Cliente_Cuenta=".$socid);
    	$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
    	$country_id=$tmp[0];
    	
    	$rel = new Contabrelctas($this->db);
    	$cat = new Contabcatctas($this->db);
    	if ($this->Get_Cliente_Proveedor_Pais($socid) == $country_id) {
    		$rel->fetch_by_code("CLIENTES_NAL"); // "105.01";
    	} else {
    		$rel->fetch_by_code("CLIENTES_EXT"); //"105.02";
    	}
    	$id = $rel->fk_cat_cta;
    	$cat->fetch($id);
    	$res = $cat->cta;
    	return $res;
    }
    
    public function Get_Proveedor_Cuenta($socid, $conf) {
    	dol_syslog("Get_Proveedor_Cuenta=".$socid);
    	$tmp=explode(':',$conf->global->MAIN_INFO_SOCIETE_COUNTRY);
    	$country_id=$tmp[0];
    	
    	$rel = new Contabrelctas($this->db);
    	$cat = new Contabcatctas($this->db);
    	if ($this->Get_Cliente_Proveedor_Pais($socid) == $country_id) {
    		$rel->fetch_by_code("PROVEEDORES_NAL"); // $res = "201.01";
    	} else {
    		$rel->fetch_by_code("PROVEEDORES_EXT"); // $res = "201.02";
    	}
    	$id = $rel->fk_cat_cta;
    	$cat->fetch($id);
    	$res = $cat->cta;
    	return $res;
    }
    
    public function Get_Cliente_Proveedor_Pais($socid) {
    	dol_syslog("Get_Cliente_Proveedor_Pais = ".$socid);
    	/* require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php'; */
    	$soc = new Societe($this->db);
    	$soc->fetch($socid);
    	$res = $soc->country_id;
    	dol_syslog("country_id=".$res);
    	return $res;
    }
    
    public function Get_Tipo_Cliente_Proveedor($socid) {
    	dol_syslog("Get_Tipo_Cliente_Proveedor = ".$socid);
    	/* require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php'; */
    	$soc = new Societe($this->db);
    	$soc->fetch($socid);
    	$res = $soc->typent_id;
    	dol_syslog("typent_id=".$res);
    	return $res;
    }
}
