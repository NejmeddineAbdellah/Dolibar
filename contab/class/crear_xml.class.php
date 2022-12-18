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

date_default_timezone_set("America/Mexico_City");

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
require_once DOL_DOCUMENT_ROOT.'/compta/facture/class/facture.class.php';
require_once DOL_DOCUMENT_ROOT.'/fourn/class/fournisseur.facture.class.php';
if (file_exists(DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php')) {
	require_once DOL_DOCUMENT_ROOT . '/contab/class/contabsatctas.class.php';
} else {
	require_once DOL_DOCUMENT_ROOT . '/custom/contab/class/contabsatctas.class.php';
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

class CrearXML extends CommonObject
{	
	var $db;
	
	var $anio;
	var $mes;
	var $rfc;
	var $xml_version = '1.1';
	var $xmlstr;
	
	var $tipo_envio;
	
	var $tipo_solicitud;
	var $num_orden;
	
	
	var $errors;
	var $mesg;
	var $cta_err;
	
	var $file_path;
	
	function __construct($db)
	{
		$this->db = $db;
		return 1;
	}
	
	function Crea_Catalogo() {
		$xmlCatalogo = new SimpleXMLElement($this->xmlstr);
		$xmlCatalogo->addAttribute("Version", $this->xml_version);
		$xmlCatalogo->addAttribute("RFC", $this->rfc);
		//$xmlCatalogo->addAttribute("TotalCtas", $tot_ctas);
		$xmlCatalogo->addAttribute("Mes", sprintf("%02d", $this->mes));
		$xmlCatalogo->addAttribute("Anio", $this->anio);
		
		//$elem = $xmlCatalogo->Catalogo;
		
		$cta = new Contabcatctas($this->db);
		$poldet = new Contabpolizasdet($this->db);
		
		$arr = array();
		$ctas = new Contabcatctas($this->db);
		$arr = $ctas->fetch_array_by_dependede(0, $arr);
		
		$this->cta_err = array();
		
		//dol_syslog("Entrando a crear_xml.class.php:: Crea_Catalogo");
		foreach ($arr as $i => $a) {
			if ($cta->fetch2($a)) {
				if ($poldet->fetch_by_cuenta($cta->cta)) {
					dol_syslog("Se encontró la cuenta en una póliza. cta=".$cta->cta);
					
					//Busca la dependencia haber si ya está agregada al xml
					$id_cta_dep = $cta->subctade;
					$ctas->fetch2($id_cta_dep);
					
					$arr_dep = array();
					//dol_syslog("===== Se hace el while para ver si las dependencias existen en el xml:: ".$id_cta_dep);
					while ($id_cta_dep) {
						$existe = false;
						//dol_syslog("Inicio del Ciclo para el XML");
						//dol_syslog(" ------------- ");
						//dol_syslog($xmlCatalogo->Ctas);
						foreach ($xmlCatalogo->Ctas as $xmlcuenta) {
							if ($xmlcuenta[CodAgrup] == $ctas->cta) {
								$existe = true;
							}
						}
						//dol_syslog("Termina esta dependencia:: ".$ctas->cta);
						
						if (!$existe) {
							//dol_syslog("###############################");
							//dol_syslog("Se agrega esta cuenta");
							$aaa = array();
							$aaa[0] = $ctas->codagr;
							$aaa[1] = $ctas->cta;
							$aaa[2] = $ctas->descta;
							$aaa[3] = $ctas->cta_subctade;
							$aaa[4] = $ctas->nivel;
							$aaa[5] = $ctas->natur;
							
							$arr_dep[] = $aaa;
						}
						$id_cta_dep = $ctas->subctade;
						$ctas->fetch2($id_cta_dep);
					}
					
					//dol_syslog("**********************************");
					//var_dump("**********************************");
					//var_dump("**********************************");
					//var_dump($arr_dep);
					
					for ($i = sizeof($arr_dep) - 1; $i >= 0; $i--) {
						$ad = $arr_dep[$i];
						//var_dump("================");
						//var_dump($ad);
						//var_dump("+++++ TODO +++++");
						//var_dump($arr_dep);
						//dol_syslog("No existe la cuenta en el XML, por lo tanto se agrega. = ".$ad[0]."-".$ad[2]);
						if ($ad[3] != "" && $ad[4] > 0) {
							$xmlcta = $xmlCatalogo->addChild("Ctas");
							$xmlcta->addAttribute("CodAgrup", $ad[0]);
							$xmlcta->addAttribute("NumCta", $ad[1]);
							$xmlcta->addAttribute("Desc", $ad[2]);
							$xmlcta->addAttribute("SubCtaDe", $ad[3]);
							$xmlcta->addAttribute("Nivel", $ad[4]);
							$xmlcta->addAttribute("Natur", $ad[5]);
						}
					}
					dol_syslog("Finalización del Ciclo para el XML :: Naturaleza=".$cta->natur);
					
					if (!$cta->natur) {
						$this->errors = "Algunas cuentas en el Catalogo Principal no tienen asingada su Naturaleza.  Favor de Verificar";
						$ce = array();
						$ce[] = $cta->cta;
						$ce[] = $cta->descta;
						$this->cta_err[] = $ce; 
					}
					$xmlcta = $xmlCatalogo->addChild("Ctas");
					$xmlcta->addAttribute("CodAgrup", $cta->codagr);
					$xmlcta->addAttribute("NumCta", $cta->cta);
					$xmlcta->addAttribute("Desc", $cta->descta);
					$xmlcta->addAttribute("SubCtaDe", $cta->cta_subctade);
					$xmlcta->addAttribute("Nivel", $cta->nivel);
					$xmlcta->addAttribute("Natur", $cta->natur);
				} else {
					//dol_syslog("Esta cuenta no ha sido utilizada: ".$cta->cta);
				}
			}
		}
		//print_r($cta_err);
		dol_syslog("Termino el proceso :: ".$this->errors);
		//$xmlCatalogo->addAttribute("TotalCtas", sizeof($arr));
		
		$xmlCatalogo->saveXML($this->file_path."/".$this->rfc.$this->anio.sprintf("%02d", $this->mes)."ct.xml");
		
		if (!$this->errors) {
			$this->mesg = "<h2>El archivo 'catalogo.xml' se creó correctamente.</h2>";
		}
		
		return 1;
	}
	
	function Crea_Balanza() {
		$xmlBal = new SimpleXMLElement($this->xmlstr);
		$xmlBal->addAttribute("Version", '1.3');
		$xmlBal->addAttribute("RFC", $this->rfc);
		//$xmlBal->addAttribute("TotalCtas", $tot_ctas);
		$xmlBal->addAttribute("Mes", sprintf("%02d", $this->mes));
		$xmlBal->addAttribute("Anio", $this->anio);
		$xmlBal->addattribute("TipoEnvio", $this->tipo_envio);
		
		//$elem = $xmlCatalogo->Catalogo;
		
		if ($this->tipo_envio == "C") {
			$pol = new Contabpolizas($this->db);
			$pol->get_ult_fecha_modif_contable($this->anio, $this->mes);
			$xmlBal->addAttribute("FechaModBal", $pol->ult_fecha);
		}
		
		$debe_total = 0;
		$haber_total = 0;
		
		$ppal = new Contabsatctas($this->db);
		
		$ctas = new Contabcatctas($this->db);
		$ok = $ctas->fetch_next_cuenta();
		
		dol_syslog("Entrando a crear_xml.class.php:: Crea_Balanza");
		while ($ok == 1) {
			$ppal->fetch($ctas->fk_sat_cta);
			if ($ppal->nivel > 0) {
				$id = $ctas->id;
				$cta = $ctas->cta;
				$ctas->fetch_saldos_iniciales($id, $this->anio, $this->mes);
				$sdoini = $ctas->saldo;
				
				$ctas->fetch_saldos($id, $this->anio, $this->mes);
				$debe = $ctas->saldo_debe;
				$haber = $ctas->saldo_haber;
				
				dol_syslog("Saldos:: inicial=$sdoini, debe=$debe, haber=$haber, final=$sdofin");
				if ($ppal->natur == "A") {
					$sdoini = $sdoini * -1;
					$sdofin = $sdoini + $haber - $debe;
				}
				else {
					$sdofin = $sdoini + $debe - $haber;
				}
				
				if (abs($sdoini) > 0 || abs($debe) > 0 || abs($haber) > 0 || abs($sdofin) > 0) {
					$xmlcta = $xmlBal->addChild("Ctas");
					$xmlcta->addAttribute("NumCta", $cta);
					$xmlcta->addAttribute("SaldoIni",str_replace(",", "", number_format($sdoini,2)));
					$xmlcta->addAttribute("Debe", str_replace(",", "", number_format($debe,2)));
					$xmlcta->addAttribute("Haber", str_replace(",", "", number_format($haber,2)));
					$xmlcta->addAttribute("SaldoFin",str_replace(",", "",  number_format($sdofin,2)));
				}
			}
			$ok = $ctas->fetch_next_cuenta(1);
		}
		
		dol_syslog("Termino el proceso :: ".$this->errors);
		//$xmlCatalogo->addAttribute("TotalCtas", sizeof($arr));
		
		$xmlBal->saveXML($this->file_path."/".$this->rfc.$this->anio.sprintf("%02d", $this->mes)."b".strtolower($this->tipo_envio).".xml");
		
		if (!$this->errors) {
			$this->mesg = "<h2>El archivo 'balanza.xml' se creo correctamente.</h2>";
		}
	
		return 1;
	}
	function Crea_xml_Polizas() {
		global $db,$conf;
		$xmlBal = new SimpleXMLElement($this->xmlstr);
		$xmlBal->addAttribute("Version", $this->xml_version);
		$xmlBal->addAttribute("RFC", $this->rfc);
		//$xmlBal->addAttribute("TotalCtas", $tot_ctas);
		$xmlBal->addAttribute("Mes", sprintf("%02d", $this->mes));
		$xmlBal->addAttribute("Anio", $this->anio);
		$xmlBal->addattribute("TipoSolicitud", $this->tipo_envio);
		$xmlBal->addattribute("NumOrden", "PLZ".$this->anio."0".sprintf("%02d", $this->mes)."/".sprintf("%02d", $this->mes)."");
		
		$anio=$this->anio;
		$mes=$this->mes;
		
		if($mes==13){
			$mm=12;
			$sql = "SELECT t.rowid, t.tipo_pol, t.cons, t.anio, t.mes, t.fecha, t.concepto, t.comentario,
				t.anombrede, t.numcheque, t.fk_facture, t.ant_ctes, t.fechahora,societe_type
				FROM ".MAIN_DB_PREFIX."contab_polizas as t WHERE 1 AND perajuste=1  AND anio = ".$this->anio." AND mes = ".$mm." AND entity = ".$conf->entity."
				ORDER BY t.rowid ASC";
		}else{
			$sql = "SELECT t.rowid, t.tipo_pol, t.cons, t.anio, t.mes, t.fecha, t.concepto, t.comentario, 
					t.anombrede, t.numcheque, t.fk_facture, t.ant_ctes, t.fechahora,societe_type 
					FROM ".MAIN_DB_PREFIX."contab_polizas as t WHERE 1 AND perajuste=0 AND anio = ".$this->anio." AND mes = ".$this->mes." AND entity = ".$conf->entity." 
					ORDER BY t.rowid ASC";
		}
		$rest=$db->query($sql);
		while ($fg=$db->fetch_object($rest)) {
			$pol = new Contabpolizas($db);
			$soc = new Societe($db);
			$ctas = new Contabcatctas($db);
			
				$pol->fetch($fg->rowid,0);
				$ff = new FactureFournisseur($db);
				$f = new Facture($db);
				if ($pol->societe_type == 1) {
					//Es un Cliente
					$f->fetch($pol->fk_facture);
					$facnumber = $f->ref;
					$sfcid=$f->socid;
					$noms= new Societe($db);
					$noms->fetch($sfcid);
					$rcfsoc=$noms->idprof1;
					$nomsoc=$noms->name;
				} else if($pol->societe_type == 2) {
					//Es un Proveedor
					$ff->fetch($pol->fk_facture);
					$facnumber = $ff->ref;
					$sfcid=$ff->socid;
					$noms= new Societe($db);
					$noms->fetch($sfcid);
					$nomsoc=$noms->name;
				}
				$sql4="SELECT rowid
							FROM ".MAIN_DB_PREFIX."contab_polizasdet
							WHERE fk_poliza=".$pol->id;
				$rest4=$db->query($sql4);
				$fg4=$db->fetch_object($rest4);
				$pd2 = new Contabpolizasdet($db);
				$pd2->fetch($fg4->rowid);
				if($pd2->debe!= 0 || $pd2->haber!= 0){
					/*Cabecera*/
					$xmlcta = $xmlBal->addChild("Poliza");
					$xmlcta->addAttribute("Fecha", date("Y-m-d",$pol->fecha));
					$xmlcta->addAttribute("Concepto", $pol->concepto);
					$xmlcta->addAttribute("NumUnIdenPol", $pol->cons);
					//$xmlcta->addAttribute("NumUnIdenPol", $pol->cons);
					/*TIPO*/
					/* if ($pol->tipo_pol == "D") { $xmlcta->addAttribute("Tipo", 3); }
					else if($pol->tipo_pol == "E") { $xmlcta->addAttribute("Tipo", 2);}
					else if($pol->tipo_pol == "I") { $xmlcta->addAttribute("Tipo", 1); } */
					/*/TIPO*/
					
					$sql2="SELECT rowid
							FROM ".MAIN_DB_PREFIX."contab_polizasdet
							WHERE fk_poliza=".$pol->id;
					$rest2=$db->query($sql2);
					while ($fg2=$db->fetch_object($rest2)) {
					$pd = new Contabpolizasdet($db);
					  $pd->fetch($fg2->rowid);
					/*Cabecera*/
					$nom_soc = "";
					//Verificar primeramente si se trata de un artículo
					if (!$ctas->fetch_by_Cta($pd->cuenta, false)) {
						if ($pol->societe_type == 1) {
							if ($soc->fetch($f->socid)) {
								$nom_soc = $soc->nom;
							}
						} else if ($pol->societe_type == 2) {
							if ($soc->fetch($ff->socid)) {
								$nom_soc = $soc->nom;
							}
						}
					}
					
					if (!$nom_soc) {
						$ctas->fetch_by_Cta($pd->cuenta);
						$nom_soc = $ctas->descta;
					} 
					if ($pd->debe != 0) {
						$xmlctatras = $xmlcta->addChild("Transaccion");
						$xmlctatras->addAttribute("NumCta",$pd->cuenta);
						$xmlctatras->addAttribute("DesCta",$nom_soc);
						$xmlctatras->addAttribute("Concepto",$nom_soc);
						$debe=str_replace(",", "", number_format(($pd->debe), 2));
						$haber=0.00;
						$xmlctatras->addAttribute("Debe",$debe);
						$xmlctatras->addAttribute("Haber",$haber);
						if ($pol->societe_type == 1) {
							if($conf->global->MAIN_MODULE_CFDIMX){
								$sql3="SELECT uuid
									FROM ".MAIN_DB_PREFIX."cfdimx
									WHERE fk_facture=".$f->id." AND entity_id=".$conf->entity;
								$rest3=$db->query($sql3);
								$nr3=$db->num_rows($rest3);
								if($nr3>0){
									$fg3=$db->fetch_object($rest3);
									$xmlcfdi=$xmlctatras->addChild("CompNal");
									$xmlcfdi->addAttribute("UUID_CFDI",$fg3->uuid);
									$xmlcfdi->addAttribute("RFC",$rcfsoc);
									$xmlcfdi->addAttribute("MontoTotal",str_replace(",", "", number_format($f->total_ttc,2)));
								}
							}
						}
					}else if($pd->haber != 0){
						$xmlctatras = $xmlcta->addChild("Transaccion");
						$xmlctatras->addAttribute("NumCta",$pd->cuenta);
						$xmlctatras->addAttribute("DesCta",$nom_soc);
						$xmlctatras->addAttribute("Concepto",$nom_soc);
						$debe=0.00;
						$haber=str_replace(",", "", number_format(($pd->haber), 2));
						$xmlctatras->addAttribute("Debe",$debe);
						$xmlctatras->addAttribute("Haber",$haber);
						if ($pol->societe_type == 1) {
							if($conf->global->MAIN_MODULE_CFDIMX){
								$sql3="SELECT uuid
									FROM ".MAIN_DB_PREFIX."cfdimx
									WHERE fk_facture=".$f->id." AND entity_id=".$conf->entity;
								$rest3=$db->query($sql3);
								$nr3=$db->num_rows($rest3);
								if($nr3>0){
									$fg3=$db->fetch_object($rest3);
									$xmlcfdi=$xmlctatras->addChild("CompNal");
									$xmlcfdi->addAttribute("UUID_CFDI",$fg3->uuid);
									$xmlcfdi->addAttribute("RFC",$rcfsoc);
									$xmlcfdi->addAttribute("MontoTotal",str_replace(",", "", number_format($f->total_ttc,2)));
								}
							}
						}
					}
					unset($pd);
					}
					unset($pd2);
				}
				unset($pol);
				unset($soc);
				unset($ctas);
				
			}
		
		dol_syslog("Termino el proceso :: ".$this->errors);
		//$xmlCatalogo->addAttribute("TotalCtas", sizeof($arr));
		
		$xmlBal->saveXML($this->file_path."/".$this->rfc.$this->anio.sprintf("%02d", $this->mes)."PL".".xml");
		
		if (!$this->errors) {
			$this->mesg = "<h2>El archivo 'xmlpolizas.xml' se creo correctamente.</h2>";
		}
		
		return 1;
	}
	/* function Crea_Polizas() {
		$xmlBal = new SimpleXMLElement($this->xmlstr);
		$xmlBal->addAttribute("Version", $this->xml_version);
		$xmlBal->addAttribute("RFC", $this->rfc);
		//$xmlBal->addAttribute("TotalCtas", $tot_ctas);
		$xmlBal->addAttribute("Mes", sprintf("%02d", $this->mes));
		$xmlBal->addAttribute("Anio", $this->anio);
		$xmlBal->addattribute("TipoEnvio", $this->tipo_envio);
	} */
	
	function Verify_Path() {
		if (!is_dir($this->file_path)) {
			$ret = mkdir($this->file_path);
		}
		if ($ret === true || is_dir($this->file_path)) {
		
		} else {
			$this->errors = "Hubo un error al querer almacenar el archivo XML en la carpeta temporal";
			$this->error = 1;
		}
	}
}
?>
