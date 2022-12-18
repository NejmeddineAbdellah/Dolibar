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

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabpaymentterm.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabpaymentterm.class.php';
}

if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabctassupplier.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabctassupplier.class.php';
}

require_once DOL_DOCUMENT_ROOT . '/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT . '/compta/paiement/class/paiement.class.php';
require_once DOL_DOCUMENT_ROOT . '/fourn/class/fournisseur.facture.class.php';
require_once DOL_DOCUMENT_ROOT . '/fourn/class/paiementfourn.class.php';
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
        $this->picto = 'generic';
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
    	global $db;
    	$sql="SELECT pautomaticas
		FROM ".MAIN_DB_PREFIX."contab_tercero_poliza_automatica
		WHERE entity=".$conf->entity;
    	$rqs =$db->query($sql);
    	$rnr=$db->num_rows($rqs);
    	$rq=$db->fetch_object($rqs);
    	if($rnr>0 && $rq->pautomaticas==1){
        // Bills
		if ($action == 'BILL_VALIDATE') {///////////////////********************
            dol_syslog("Trigger " . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            $res = $this->Venta_a_Credito($object, $user, $conf);
            if ($res == -1) {
            	$error++;
            	$this->errors[] = '<div class="error">Para facturas con condiciones de pagos 50/50, no se permiten articulos con Tasa de Impuestos Difdrentes.  Favor de corregir.</div>';
            	//$this->mesg[] = "No se ";
            	return $res;
            }
        } elseif ($action == 'BILL_PAYED') {///////////////////**********************
        	//dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	//$this->Cliente_Saldar_Pago_Anticipado($object, $user, $conf);
        } elseif ($action == 'BILL_SUPPLIER_VALIDATE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
        	$res = $this->Proveedor_Compra_a_Credito($object, $user, $conf);
        	dol_syslog("Error de retorno: $res");
        	if ($res == -1) {
        		dol_syslog("No se permiten pagos diferidos en facturas con productos con tasa de impuestos diferentes.");
        		$error++;
        		$this->errors[] = '<div class="error">No se permiten pagos diferidos para facturas que tienen varios articulos con Tasa de Impuestos Difdrentes.  Favor de cambiar la condicion de pago.</div>';
        		//$this->mesg[] = "No se ";
        		return $res;
        	} else if ($res == -2) {
        		dol_syslog("No se permiten facturas de servicios como de Credito o de 50/50");
        		$error++;
        		$this->errors[] = '<div class="error">Las Facturas de Servicio no pueden ser a Credito o 50/50.  Favor de cambiar la condicion de pago.</div>';
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
        elseif ($action == 'PAYMENT_CUSTOMER_CREATE') {////********************************************
        	//dol_syslog("MV PAYMENT_CUSTOMER_CREATE::".$object->client);
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            $this->Pago_de_Factura($object, $user, $conf);
        } elseif ($action == 'PAYMENT_SUPPLIER_CREATE') {
        	dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            $this->Proveedor_Pago_Factura($object, $user, $conf, $_POST["accountid"]);
        } elseif ($action == 'PAYMENT_ADD_TO_BANK') {//////***********************************
            dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id);
            
            $this->errors = array();
            $rsp=-1;
            $error = 0;
            if($conf->global->MAIN_MODULE_POS){
            	$rsp=1;
            }
            foreach ($_POST as $key => $value) {
            	if (substr($key,0,7) == 'amount_'){
            		$cursorfacid = substr($key,7);
            		$amounts[$cursorfacid] = $_POST[$key];
            		//var_dump($amounts);
            		if (! empty($amounts[$cursorfacid])){
            			dol_syslog("Payment_add_to_bank = Object Element = ".$object->element);
            			if($object->element=='payment_supplier'){
            				// Facture Fourn
            				$sql = "Select * From ".MAIN_DB_PREFIX."facture_fourn_det Where fk_facture_fourn = ".$cursorfacid;
            			}else if ($object->element == 'payment') {
            				// Facture
            				$sql = "Select * From ".MAIN_DB_PREFIX."facturedet Where fk_facture = ".$cursorfacid;
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
						$mismo_iva=true;
            			dol_syslog("mismo_iva=".($mismo_iva?"true":"false")." Total amount=$total_ammount, amounts=".$amounts[$cursorfacid]." diferencia=".($total_ammount - $amounts[$cursorfacid]));
            			if ((!$mismo_iva) && ($total_ammount - $amounts[$cursorfacid] > 0.009)) {
            				$rsp = -1;
            				$error++;
            				$this->errors[] = '<div class="error">Para facturas que tienen varios articulos con Tasa de Impuestos Difdrentes, el pago debe realizarse en su totalidad en una sola exhibicion.</div>';
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
    	}
        return 0;
    }
    
    public function Factura_Nueva_Linea($object, $user, $conf) {
    	//var_dump($object);
    	dol_syslog("MV Factura_Nueva_Linea:: ID::".$object->id." SOCID::".$object->socid);
    	
    	if (GETPOST('ddl_contab_id_cuenta') > 0) {
    		//Get id from new line
    		$id = $object->id;
    		$rowid = $object->rowid;
    		$idcta = GETPOST("ddl_contab_id_cuenta");
    		$socid = $object->socid;
    		
    		$sup = new Contabctassupplier($this->db);
    		//print "dos";
    		if ($sup->fetch_by_idcta_socid($idcta, $socid) > 0) {
    			$fourn_type = $sup->fourn_type;
    			$sql = "Insert Into ".MAIN_DB_PREFIX."contab_fourn_product_line (fk_facture, rowid_line, fk_cat_cta, fourn_type) Values ('$id', '$rowid', '$idcta', '$fourn_type')";
    			$this->db->query($sql);
    		}
    		dol_syslog("Contab - Interface:: Factura_Nueva_Linea:: sql = $sql");
    	}
    }
    
    public function Factura_Delete_Line($object, $user, $conf) {
    	dol_syslog("MV Factura_Delete_Line:: ID::".$object->id." SOCID::".$object->socid);
    	dol_syslog("Factura_Delete_Line");
    	//var_dump($object);
    	$id = $object->id;
    	$rowid = GETPOST('lineid');
    	
    	dol_syslog("id=$id, rowid=$rowid, idcta=$idcta");
    	$this->db->query("Delete From ".MAIN_DB_PREFIX."contab_fourn_product_line Where rowid_line = '$rowid' and fk_facture = '$id' and fourn_type = 1");
    }
    
    public function Factura_Update_Line($object, $user, $conf) {
    	//dol_syslog("MV Factura_Update_Line:: ID::".$object->id." SOCID::".$object->socid);
    	dol_syslog("Factura_Update_Line");
    	//var_dump($object);
    	$id = $object->id;
    	$rowid = $object->rowid;
    	$idcta = GETPOST("ddl_contab_id_cuenta");
    	
    	$cmd = "Select * From ".MAIN_DB_PREFIX."contab_fourn_product_line Where fk_cat_cta = '$idcta' Where fk_facture = '$id' And rowid_line = '$rowid' and fourn_type = 1";
    	if ($res = $this->db->query($cmd)) {
    		if ($this->db->num_rows($res) > 0) {
    			$sihay = true;
    		} else {
    			$sihay = false;
    		}
    	}
    	if ($sihay) {
	    	$sql = "Update ".MAIN_DB_PREFIX."contab_fourn_product_line SET fk_cat_cta = '$idcta' Where fk_facture = '$id' And rowid_line = '$rowid' and fourn_type = 1";
    		dol_syslog("id=$id, rowid=$rowid, idcta=$idcta, sql=$sql");
    		$this->db->query($sql);
    	} else {
    		$this->Factura_Nueva_Linea($object, $user, $conf);
    	}
    }
    
	public function Venta_a_Credito($object, $user, $conf) {
		//dol_syslog("MV Venta_a_Credito:: ID::".$object->id." SOCID::".$object->socid); //MV Venta_a_Credito:: ID::12 SOCID::3
		global $db;
		$sql="SELECT count(fk_societe) as exist
			FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza
			WHERE fk_societe=".$object->socid;
		$rq=$db->query($sql);
		$rs=$db->fetch_object($rq);
		if($rs->exist==0){
			$pol = new Contabpolizas($this->db);
			$ret = $pol->Venta_a_Credito($object, $user, $conf);
			
			return $ret;
		}else{
			return false;
		}
    }
    
    public function Cliente_Saldar_Pago_Anticipado($object, $user, $conf) {
    	//dol_syslog("MV Cliente_Saldar_Pago_Anticipado:: ID::".$object->id." SOCID::".$object->socid);
    	$pol = new Contabpolizas($this->db);
    	$pol->Cliente_Saldar_Pago_Anticipado($object, $user, $conf);
    }
    
    public function Pago_de_Factura($object, $user, $conf) {
    	//dol_syslog("MV Pago_de_Factura:: ID::".$object->id." SOCID::".$object->socid);//Pago_de_Factura:: ID::14 SOCID::
    	global $db,$conf;
    	$sql="SELECT f.rowid as facid, s.nom as name, @a:=s.rowid as socid,
			(SELECT count(fk_societe) as exist
			FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza
			WHERE fk_societe=socid) nopoliza
			FROM ".MAIN_DB_PREFIX."paiement_facture as pf,".MAIN_DB_PREFIX."facture as f,".MAIN_DB_PREFIX."societe as s
			WHERE pf.fk_facture = f.rowid
			AND f.fk_soc = s.rowid
			AND f.entity = ".$conf->entity."
		AND pf.fk_paiement =".$object->id;
    	$rq=$db->query($sql);
    	$rs=$db->fetch_object($rq);
    	if($rs->nopoliza==0){
	    	$pol = new Contabpolizas($this->db);
	    	$pol->Pago_de_Factura($object, $user, $conf);
    	}
    }
    
    public function Proveedor_Compra_a_Credito($object, $user, $conf) {
    	//dol_syslog("MV Proveedor_Compra_a_Credito:: ID::".$object->id." SOCID::".$object->socid); //MV Proveedor_Compra_a_Credito:: ID::6 SOCID::2
    	global $db;
    	$sql="SELECT count(fk_societe) as exist
			FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza
			WHERE fk_societe=".$object->socid;
    	$rq=$db->query($sql);
    	$rs=$db->fetch_object($rq);
    	if($rs->exist==0){
	    	$pol = new Contabpolizas($this->db);
	    	$ret = $pol->Proveedor_Compra_a_Credito($object, $user, $conf);
	    	return $ret;
    	}else{
    		return false;
    	}
    }
    
    public function Proveedor_Pago_Factura($object, $user, $conf, $accountid) {
    	//dol_syslog("MV Proveedor_Pago_Factura:: ID::".$object->id." SOCID::".$object->socid); //MV Proveedor_Pago_Factura:: ID::3 SOCID::
    	global $db,$conf;
    	$sql="SELECT f.rowid, f.ref, f.rowid as facid, s.nom as name, s.rowid as socid, 
			(SELECT count(fk_societe) as exist
				FROM ".MAIN_DB_PREFIX."contab_tercero_nopoliza
				WHERE fk_societe=socid) nopoliza
			FROM ".MAIN_DB_PREFIX."paiementfourn_facturefourn as pf,".MAIN_DB_PREFIX."facture_fourn as f,".MAIN_DB_PREFIX."societe as s 
			WHERE pf.fk_facturefourn = f.rowid AND f.fk_soc = s.rowid AND pf.fk_paiementfourn =".$object->id;
    	$rq=$db->query($sql);
    	$rs=$db->fetch_object($rq);
    	if($rs->nopoliza==0){
	    	$pol = new Contabpolizas($this->db);
	    	$pol->Proveedor_Pago_Factura($object, $user, $conf, $accountid);
    	}
    }
}
