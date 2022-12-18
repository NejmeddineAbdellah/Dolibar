<?php

/**
 * Descripción de Configuración
 * Juan Pablo Farber - Modulo de configuración.
 */


if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabpaymentterm.class.php')) {
	require_once DOL_DOCUMENT_ROOT.'/contab/class/contabpaymentterm.class.php';
} else {
	include_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabpaymentterm.class.php';
}
class Configuration {

    private $db;

    function __construct($db) {
        $this->db = $db;
    }
    
    /* Esta función ayuda para saber si el usuario ya ha cargado su Catalogo de Cuentas al Sistema o 
     * ya ha capturado su Catálogo en el sistema
    */
    public function HayRegistros_CatCtas() {
    	global $conf;
    	$sql = "Select count(*) From ".MAIN_DB_PREFIX."contab_cat_ctas Where entity = ".$conf->entity;
    	if ($res = $this->db->query($sql)) {
    		$row = $this->db->fetch_array($res);
    	}
    	return $row[0];
    }
    
    /* Esta función ayuda para saber si el usuario ya ha cargado su Catalogo de Cuentas Principal al Sistema o
     * ya ha capturado su Catálogo Principal en el sistema
     */
    public function HayRegistros_SatCtas() {
    	$sql = "Select count(*) From ".MAIN_DB_PREFIX."contab_sat_ctas";
    	if ($res = $this->db->query($sql)) {
    		$row = $this->db->fetch_array($res);
    	}
    	return $row[0];
    }
    
     /* Esta función ayuda a generar el dropdown list necesario para mostrar las cuentas del SAT
     * 
     * @param - $val  - Valor previamente seleccionado.  Mantiene el valor.
    */
    
    public function create_List_Cat_Ctas($val = 0) {
    	global $conf;
    	$str = "";
    	if ($this->HayRegistros_CatCtas() > 0) {
	    	$str = "<option value=0>--Seleccione--</option>";
    		
    		$sql = "Select * From ".MAIN_DB_PREFIX."contab_cat_ctas Where entity = ".$conf->entity;
	    	$res = $this->db->query($sql);
    		if ($res) {
    			while ($row = $this->db->fetch_array($res)) {
    				$str .= "<option ";
	    			if ($row[0] == $val) {
    					$str .= " selected='selected' ";
    				}
    				$str .= "value='".$row[0]."'>".$row[2]."-".$row[3]."</option>";
    			}
    		}
    	}
    	return $str;
    }
    
    /*
     * Esta función guarda los cambios realizados a la configuración de Contab utilizando
    *
    * @param - $params - Cabeceras POST recibidas a procesar.
    * 		ej.   $_POST = { 101, sat_contado }, {1, cat_contado}, {102, sat_bancos}, {2, cat_bancos}, .....}
    * 						{CodAgr, Cta del SAt}, { rowid, Cat del Usuario} .....
    */
    
    public function saveSettings($prm, $proceso) {
    	global $user, $conf;
    	
    	if ($proceso == 1) {
    		$pmt = new Contabpaymentterm($this->db);
    		$pmt->update_all_paymentterm_values(0);

	    	foreach ($prm as $key => $value) {
	    		dol_syslog("prm[key] = ".$prm[$key]." key=".$key." value=".$value);
	    		if (substr($key, 0, 9) == "cond_pago") {
	    			$i = substr($key, 10);
	    			$pmt->fetch($i);
	    			if ($pmt->fk_payment_term == $i) {
		    			$pmt->fk_payment_term = $i;
		    			$pmt->cond_pago = $value;
		    			$pmt->update();
	    			} else {
	    				$pmt->fk_payment_term = $i;
	    				$pmt->cond_pago = $value;
	    				$pmt->create($user);
	    			}
	    			dol_syslog("Valor de key=$key, i=$i");
	    			
	    			dol_syslog("Guardado de datos en ".MAIN_DB_PREFIX."contab_payment_term key=$key, fk_payment_term=$i, val=".$value);
	    			
	    		}
	    	}
    	} else {
			$sql = "Update ".MAIN_DB_PREFIX."contab_rel_ctas SET fk_sat_cta = 0 And entity = ".$conf->entity;
			$this->db->query($sql);
			$this->db->commit();
		    $desc1 = "";
			$desc2 = "";
			foreach ($prm as $key => $value) {
				$descta = substr($key, 4, strlen($key) - 4);
				$sat = "sat_".$descta;
				$cat = "cat_".$descta;
				dol_syslog("Descta=".$descta." - sat=".$sat." - cat=".$cat);
				if ($desc1 != $sat && $desc2 != $cat && $prm[$sat] != "" && $prm[$cat] > 0) {
					$sql = "UPDATE ".MAIN_DB_PREFIX."contab_rel_ctas SET fk_sat_cta = (Select rowid From ".MAIN_DB_PREFIX."contab_sat_ctas Where codagr = '". $prm[$sat] . "') WHERE rowid = '" . $prm[$cat] . "'  And entity = ".$conf->entity;
					//print $sql."<br>";
					$this->db->query($sql);
					$this->db->commit();
					
					$desc1 = $sat;
					$desc2 = $cat;
					dol_syslog("    desc1=".$desc1." - desc2=".$desc2." - ".$sql);
				}
			}
	    }
    }
    
    public function getCuenta_Has_Assigned_SAT_id($CodAgr) {
    	global $conf;
    	$id = 0;
    	$sql = "Select rowid From ".MAIN_DB_PREFIX."contab_cat_ctas Where fk_sat_cta = (Select rowid From ".MAIN_DB_PREFIX."contab_sat_ctas Where codagr = '". $CodAgr . "') And entity = ".$conf->entity;
    	if ($res = $this->db->query($sql)) {
    		if ($row = $this->db->fetch_array($res)) {
    			$id = $row[0];
    		}
    	}
    	dol_syslog("getCuenta_Has_Assigned_STA_id = ".$CodAgr." SQL = ".$sql);
    	return $id;
    }
    
    public function getCondiciones_de_Pago() {
    	global $conf;
    	$a = array();
    	$sql = "Select * FROM ".MAIN_DB_PREFIX."contab_payment_term Where entity = ".$conf->entity;
    	if ($res = $this->db->query($sql)) {
    		while ($row = $this->db->fetch_array($res)) {
    			$val = $row["cond_pago"];
    			$a["cond_pago_".$row["fk_payment_term"]] = $val;
    		}
    	}
    	dol_syslog("getCondiciones_de_Pago sql=".$sql);
    	return $a;
    }
    
    public function getCond_Pago() {
    	$pmt = new Contabpaymentterm($this->db);
    	$a_pmt = $pmt->fetch_array();
    	return $a_pmt;
    }
    
    public function create_List_Cond_Pago($val = 0) {
    	global $conf;
    	
    	if ($row[2] == 1) { $st = "contado"; }
    	if ($row[2] == 2) { $st = "credito"; }
    	if ($row[2] == 3) { $st = "anticipo"; }
    	if ($row[2] == 4) { $st = "mitad"; }
    	
    	$sql = "Select * From ".MAIN_DB_PREFIX."contab_payment_term Where fk_payment_term = $val And entity = ".$conf->entity;
    	dol_syslog("create_List_Cond_Pago = ".$val." - ".$sql);
    	$res = $this->db->query($sql);
    	if ($res) {
    		if ($row = $this->db->fetch_array($res)) {
    			if ($row[1] == $val) {
    				$str .= " selected='selected' ";
    			}
    		}
    		dol_syslog("create_List_Cond_Pago = ".$str);
    	}
    	$str = "<option value=0>--Seleccione--</option>";
    	$str .= "<option ";
    	$str .= "value='".$row[0]."'>".$st."</option>";
    	return $str;
    }
    
    /* Esta función ayuda a generar el dropdown list necesario para mostrar las cuentas del Cliente
     *
    * @param - $val  - Valor previamente seleccionado.  Mantiene el valor.
    */
    
    public function create_List_SAT_Cat($val = 0) {
    	$str = "";
    	if ($this->HayRegistros_SatCtas() > 0) {
    		$str = "<option value=0>--Seleccione--</option>";
    
    		$sql = "Select * From ".MAIN_DB_PREFIX."contab_sat_ctas";
    		dol_syslog("create_List_SAT_Cat = ".$val." - ".$sql);
    		$res = $this->db->query($sql);
    		if ($res) {
    			while ($row = $this->db->fetch_array($res)) {
    				$str .= "<option ";
    				if ($row[0] == $val) {
    					$str .= " selected='selected' ";
    				}
    				$str .= "value='".$row[0]."'>".$row[2]."-".$row[3]."</option>";
    			}
    		}
    		dol_syslog("create_List_SAT_Cat = ".$str);
    	}
    	return $str;
    }
    
    public function cargar_grupos() {
    	if (file_exists(DOL_DOCUMENT_ROOT.'/contab/class/contabgrupos.class.php')) {
    		require_once DOL_DOCUMENT_ROOT.'/contab/class/contabgrupos.class.php';
    	} else {
    		require_once DOL_DOCUMENT_ROOT.'/custom/contab/class/contabgrupos.class.php';
    	}
    	
    	$agpo = array();
    	
    	$gpo = new Contabgrupos($this->db);
    	if ($r = $gpo->fetch_next()) {
    		$ag = array();
    		$ag["id"] = $gpo->id;
    		$ag["grupo"] = $gpo->grupo;
    		$ag["fk_codagr_rel"] = $gpo->fk_codagr_rel;
    		$ag["fk_codagr_ini"] = $gpo->fk_codagr_ini;
    		$ag["fk_codagr_fin"] = $gpo->fk_codagr_fin;
    		
    		$agpo[] = $ag;
    	}
    	
    	return $agpo;
    }
}

/* Juan Pablo Farber - Modulo de configuración. */