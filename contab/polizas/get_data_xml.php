<?php
error_reporting(0);
$compVersion = ''; 				
$compFecha = ''; 					
$compSello = '';				
$compImpTot = '';				
$compIppSubTot = '';			
$compCertifi = '';
$compMetPag = '';					
$compNoCertificado = '';	
$compTipoCompr = '';		
$compSerie = '';				
$compFolio = '';					
$compMoneda = '';
$emisorNomc = '';					
$emisorDomPais = '';			
$emisorDomCalle = '';		
$emisorDomEstado = '';	
$emisorDomCol = '';				
$emisorDomMunic = '';
$emisorDomNoExt = '';			
$emisorDomNoInt = '';			
$emisorDomCP = '';			
$emisorExpedPais = '';	
$emisorExpedCalle = '';		
$compFormPag = '';
$emisorExpedEstado = '';	
$emisorExpedColo = ''; 		
$emisorExpedNoExt = '';	
$emisorExpedCP = '';		
$receptorRFC = '';				
$receptorNombre = '';
$receptorDomPais = '';		
$receptorDomCalle = ''; 	
$receptorDomEstad = '';	
$receptorDomCol = '';		
$receptorDomMun = '';			
$emisorRfc = '';
$receptorDomNoExt = '';		
$receptorDomNoInt = ''; 	
$receptorDomCP = '';		
$arrConcp =  array();		
$arrTras =  array();		  
$timbreFDSelloCfd = '';
$timbreFDFechTimbra = '';	
$timbreFDUuid = ''; 		  
$timbreFDNoCertif = '';	
$timbreFDVersion = '';	
$timbreFDSelloSat = '';


function get_data_xml($file){
	$xml  =  simplexml_load_file($file);

	if (!$xml) {
	  return false;
	}

	$ns   =  $xml->getNamespaces(true);

	$xml->registerXPathNamespace('c', $ns['cfdi']);
	$xml->registerXPathNamespace('t', $ns['tfd']);

	foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante){
		global $compVersion , $compFecha , $compSello , $compImpTot , $compIppSubTot , $compCertifi , $compFormPag , $compMetPag , $compNoCertificado , $compTipoCompr , $compSerie , $compFolio , $compMoneda;
	    
	    $compVersion = $cfdiComprobante['version'];
	    $compFecha = $cfdiComprobante['fecha'];
	    $compSello = $cfdiComprobante['sello'];
	    $compImpTot = $cfdiComprobante['total'];
	    $compIppSubTot = $cfdiComprobante['subTotal'];
	    $compCertifi = $cfdiComprobante['certificado'];
	    $compFormPag = $cfdiComprobante['formaDePago'];
	    $compMetPag = $cfdiComprobante['metodoDePago'];
	    $compNoCertificado = $cfdiComprobante['noCertificado'];
	    $compTipoCompr = $cfdiComprobante['tipoDeComprobante'];
	    $compSerie = $cfdiComprobante['serie'];
	    $compFolio = $cfdiComprobante['folio'];
	    $compMoneda = $cfdiComprobante['Moneda'];

	}
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor){
	  global  $emisorRfc,$emisorNomc;
	  $emisorRfc = $Emisor['rfc'];
	  $emisorNomc = $Emisor['nombre'];

	}
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal){
	  global $emisorDomPais,$emisorDomCalle,$emisorDomEstado,$emisorDomCol,$emisorDomMunic,$emisorDomNoExt,$emisorDomNoInt,$emisorDomCP;
	  $emisorDomPais = $DomicilioFiscal['pais'];
	  $emisorDomCalle = $DomicilioFiscal['calle'];
	  $emisorDomEstado = $DomicilioFiscal['estado'];
	  $emisorDomCol = $DomicilioFiscal['colonia'];
	  $emisorDomMunic = $DomicilioFiscal['municipio'];
	  $emisorDomNoExt = $DomicilioFiscal['noExterior'];
	  $emisorDomNoInt = $DomicilioFiscal['noInterior'];
	  $emisorDomCP = $DomicilioFiscal['codigoPostal'];

	}
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn){
	 global $emisorExpedPais,$emisorExpedCalle,$emisorExpedEstado,$emisorExpedColo,$emisorExpedNoExt,$emisorExpedCP;
	  $emisorExpedPais = $ExpedidoEn['pais'];
	  $emisorExpedCalle = $ExpedidoEn['calle'];
	  $emisorExpedEstado = $ExpedidoEn['estado'];
	  $emisorExpedColo = $ExpedidoEn['colonia'];
	  $emisorExpedNoExt = $ExpedidoEn['noExterior'];
	  $emisorExpedCP = $ExpedidoEn['codigoPostal'];

	}
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor){
		global $receptorRFC ,$receptorNombre;
	  $receptorRFC 		= $Receptor['rfc'];
	  $receptorNombre = $Receptor['nombre'];
	}
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio){
		global $receptorDomPais,$receptorDomCalle,$receptorDomEstad,$receptorDomCol,$receptorDomMun,$receptorDomNoExt,$receptorDomNoInt,$receptorDomCP;
	  $receptorDomPais 		= $ReceptorDomicilio['pais'];
	  $receptorDomCalle 	= $ReceptorDomicilio['calle'];
	  $receptorDomEstad 	= $ReceptorDomicilio['estado'];
	  $receptorDomCol 		= $ReceptorDomicilio['colonia'];
	  $receptorDomMun 		= $ReceptorDomicilio['municipio'];
	  $receptorDomNoExt 	= $ReceptorDomicilio['noExterior'];
	  $receptorDomNoInt 	= $ReceptorDomicilio['noInterior'];
	  $receptorDomCP 		= $ReceptorDomicilio['codigoPostal'];
	}

	$i=0;
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto){
	  global $arrConcp;
	  $arrConcp[$i]['unidad'] 					= $Concepto['unidad'];
	  $arrConcp[$i]['importe'] 					= $Concepto['importe'];
	  $arrConcp[$i]['cantidad'] 				= $Concepto['cantidad'];
	  $arrConcp[$i]['descripcion'] 			= $Concepto['descripcion'];
	  $arrConcp[$i]['valorUnitario'] 		= $Concepto['valorUnitario'];
	  $arrConcp[$i]['noIdentificacion'] = $Concepto['noIdentificacion'];
	  $i++;
	}
	$i=0;
	foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado){
		global $arrTras;
	  $arrTras[$i]['tasa']     = $Traslado['tasa'];
	  $arrTras[$i]['importe']  = $Traslado['importe'];
	  $arrTras[$i]['impuesto'] = $Traslado['impuesto'];
	    $i++;
	}
	foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
       global $timbreFDSelloCfd ,$timbreFDFechTimbra ,$timbreFDUuid ,$timbreFDNoCertif ,$timbreFDVersion ,$timbreFDSelloSat;
		$timbreFDSelloCfd   = $tfd['selloCFD'];
		$timbreFDFechTimbra = $tfd['FechaTimbrado'];
		$timbreFDUuid       = $tfd['UUID'];
		$timbreFDNoCertif   = $tfd['noCertificadoSAT'];
		$timbreFDVersion    = $tfd['version'];
		$timbreFDSelloSat   = $tfd['selloSAT'];
	}

	return true;

}
?>
