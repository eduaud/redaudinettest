<?php
include("../../conect.php");
include("../../code/general/funciones.php");
extract($_GET);
extract($_POST);
$RFC=htmlspecialchars("ALEJANDRO&");

	
$nameXml=htmlspecialchars_decode($RFC.$anio.$mes);

$arrayAtributosPadre=array();
$documento = new DOMDocument('1.1','UTF-8');
if($opcion==1){
	$arrayAtributosPadre=atributosXMLS($RFC,$mes,$anio,$Sello,$noCertificado,$Certificado,'','','','','','','','','','','',$opcion);
	$arrayAtributosPadre[17]['xsi:schemaLocation']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas/CatalogoCuentas_1_1.xsd';
	$arrayAtributosPadre[18]['xmlns:xsi']='http://www.w3.org/2001/XMLSchema-instance';
	$arrayAtributosPadre[19]['xmlns:catalogocuentas']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/CatalogoCuentas';
	$nameXml.='CT';
	$Catalogo = $documento->createElement('catalogocuentas:Catalogo');
	AgregaAtributos($arrayAtributosPadre,$Catalogo);
	
	//obtener los datos de las cuentas contables
	$sqlCtas="SELECT con_cuentas_agrupadoras_sat.cuenta_sat AS CodAgrup,cuenta_contable AS NumCta,scfdi_cuentas_contables.nombre AS 'Desc',IF(cuenta_superior_sat=con_cuentas_agrupadoras_sat.cuenta_sat,'',cuenta_superior_sat) AS SubCtaDe,con_cuentas_agrupadoras_sat.nivel_cuenta_sat AS Nivel,IF(es_deudora=1,'D','A') AS Natur 
	FROM scfdi_cuentas_contables 
	LEFT JOIN con_cuentas_agrupadoras_sat 
	ON scfdi_cuentas_contables.id_cuenta_sat=con_cuentas_agrupadoras_sat.id_cuenta_sat 
	LEFT JOIN scfdi_generos_cuentas_contables 
	ON scfdi_cuentas_contables.id_genero_cuenta_contable=scfdi_generos_cuentas_contables.id_genero_cuenta_contable";
	$datosCtas = mysql_query($sqlCtas);
	
	while($ctas=mysql_fetch_array($datosCtas)){
		$arrayCuentas=array();
		$arrayCuentas['CodAgrup']=htmlspecialchars($ctas['CodAgrup']);
		$arrayCuentas['NumCta']=htmlspecialchars($ctas['NumCta']);
		$arrayCuentas['Desc']=htmlspecialchars($ctas['Desc']);
		$arrayCuentas['Nivel']=htmlspecialchars($ctas['Nivel']);
		$arrayCuentas['SubCtaDe']=htmlspecialchars($ctas['SubCtaDe']);
		$arrayCuentas['Natur']=htmlspecialchars($ctas['Natur']);
		//nodos hijos de las cuentas
		$Cuentas = $documento->createElement('catalogocuentas:Ctas');
		foreach($arrayCuentas as $atributo=>$valor){
			AgregaAtributosHijo($atributo,$valor,$Cuentas);
		}
		$Catalogo->appendChild($Cuentas);
	}
	$documento->appendChild($Catalogo);
}
else if($opcion==2){
	$arrayAtributosPadre=atributosXMLS($RFC,$mes,$anio,$Sello,$noCertificado,$Certificado,$TipoEnvio,$FechaModBal,$TipoSolicitud,$NumOrden,$NumTramite,$Folio,$FechaSello,$Sello,$noCertificadoSAT,$CertificadoSAT,$selloSAT,$opcion);
	$arrayAtributosPadre[17]['xsi:schemaLocation']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion/BalanzaComprobacion_1_1.xsd';
	$arrayAtributosPadre[18]['xmlns:xsi']='http://www.w3.org/2001/XMLSchema-instance';
	$arrayAtributosPadre[19]['xmlns:BCE']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/BalanzaComprobacion';
	
	$Balanza = $documento->createElement('BCE:Balanza');
	AgregaAtributos($arrayAtributosPadre,$Balanza);
	$Ctas = $documento->createElement('BCE:Ctas');
	$Balanza->appendChild($Ctas);
	$documento->appendChild($Balanza);
	
	
}
else if($opcion==3){
	if($tipoSolicitud=='AF'||$tipoSolicitud=='FC')
		$NumOrden='NumOrden';
	else 
		$NumOrden='';
	if($tipoSolicitud=='DE'||$tipoSolicitud=='CO')
		$NumTramite='NumTramite';
	else
		$NumTramite='';
	
	$arrayAtributosPadre=atributosXMLS($RFC,$mes,$anio,$Sello,$noCertificado,$Certificado,$TipoEnvio,$FechaModBal,$tipoSolicitud,$NumOrden,$NumTramite,$Folio,$FechaSello,$Sello,$noCertificadoSAT,$CertificadoSAT,$selloSAT,$opcion);
	$arrayAtributosPadre[17]['xsi:schemaLocation']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo/PolizasPeriodo_1_1.xsd';
	$arrayAtributosPadre[18]['xmlns:xsi']='http://www.w3.org/2001/XMLSchema-instance';
	$arrayAtributosPadre[19]['xmlns:PLZ']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/PolizasPeriodo';
	
	$Polizas = $documento->createElement('PLZ:Polizas');
	AgregaAtributos($arrayAtributosPadre,$Polizas);
	
	$sqlPolizas="SELECT id_poliza AS NumUnIdenPol,fecha AS Fecha,concepto AS Concepto FROM ad_polizas";
	$datosPolizas = mysql_query($sqlPolizas);
	
	while($pol=mysql_fetch_array($datosPolizas)){
		$arrayPolizas=array();
		$arrayPolizas['NumUnIdenPol']=htmlspecialchars($pol['NumUnIdenPol']);
		$arrayPolizas['Fecha']=htmlspecialchars($pol['Fecha']);
		$arrayPolizas['Concepto']=htmlspecialchars($pol['Concepto']);
		$Poliza = $documento->createElement('PLZ:Poliza');
		foreach($arrayPolizas as $atributo=>$valor){
			AgregaAtributosHijo($atributo,$valor,$Poliza);
		}
		$sqlPolizasDetalle="SELECT scfdi_cuentas_contables.cuenta_contable AS NumCuenta,nombre AS DesCta,concepto AS Concepto,cargo AS Debe,abono AS Haber FROM ad_polizas_detalle 
		LEFT JOIN scfdi_cuentas_contables ON ad_polizas_detalle.id_cuenta_contable=scfdi_cuentas_contables.id_cuenta_contable 
		WHERE id_poliza=".$arrayPolizas['NumUnIdenPol'];
		$resultDetalle=mysql_query($sqlPolizasDetalle);
		while($detalle=mysql_fetch_array($resultDetalle)){
			$arrayPolizasDetalle=array();
			$arrayPolizasDetalle['NumCuenta']=htmlspecialchars($detalle['NumCuenta']);
			$arrayPolizasDetalle['DesCta']=htmlspecialchars($detalle['DesCta']);
			$arrayPolizasDetalle['Concepto']=htmlspecialchars($detalle['Concepto']);
			$arrayPolizasDetalle['Debe']=htmlspecialchars($detalle['Debe']);
			$arrayPolizasDetalle['Haber']=htmlspecialchars($detalle['Haber']);
			
			$Transaccion = $documento->createElement('PLZ:Transaccion');
			
			foreach($arrayPolizasDetalle as $atributo=>$valor){
				AgregaAtributosHijo($atributo,$valor,$Transaccion);
			}
			$Poliza->appendChild($Transaccion);
		}
		$Polizas->appendChild($Poliza);
	}

	$documento->appendChild($Polizas);
	
	/*
	$CompNal = $documento->createElement('PLZ:CompNal');
	$CompNalOtr = $documento->createElement('PLZ:CompNalOtr');
	$CompNalExt = $documento->createElement('PLZ:CompNalExt');
	$Cheque = $documento->createElement('PLZ:Cheque');
	$Transferencia = $documento->createElement('PLZ:Transferencia');
	$OtrMetodoPago = $documento->createElement('PLZ:OtrMetodoPago');
	$arrayAtributosPadre['TipoSolicitud']=$tipoSolicitud;
	if($tipoSolicitud=='AF'||$tipoSolicitud=='FC')
		$arrayAtributosPadre['NumOrden']='';
	if($tipoSolicitud=='DE'||$tipoSolicitud=='CO')
		$arrayAtributosPadre['NumTramite']='';
	
	$arrayAtributosPadre['Sello']='';
	$arrayAtributosPadre['noCertificado']='';
	$arrayAtributosPadre['Certificado']='';
	$Poliza->appendChild($Transaccion);
	$Transaccion->appendChild($CompNal);
	$Transaccion->appendChild($CompNalOtr);
	$Transaccion->appendChild($CompNalExt);
	$Transaccion->appendChild($Cheque);
	$Transaccion->appendChild($Transferencia);
	$Transaccion->appendChild($OtrMetodoPago);
	*/
}
else if($opcion==4){
	$arrayAtributosPadre=atributosXMLS($RFC,$mes,$anio,$Sello,$noCertificado,$Certificado,$TipoEnvio,$FechaModBal,$TipoSolicitud,$NumOrden,$NumTramite,$Folio,$FechaSello,$Sello,$noCertificadoSAT,$CertificadoSAT,$selloSAT,$opcion);
	$arrayAtributosPadre[17]['xsi:schemaLocation']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/AuxiliarFolios/AuxiliarFolios_1_2.xsd';
	$arrayAtributosPadre[18]['xmlns:xsi']='http://www.w3.org/2001/XMLSchema-instance';
	$arrayAtributosPadre[19]['xmlns:RepAux']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/AuxiliarFolios';
	$RepAuxFol = $documento->createElement('RepAux:RepAuxFol');
	
	$arrayAtributosPadre['TipoSolicitud']=$tipoSolicitud;
	if($tipoSolicitud=='AF'||$tipoSolicitud=='FC')
		$arrayAtributosPadre['NumOrden']='';
	
	if($tipoSolicitud=='DE'||$tipoSolicitud=='CO')
		$arrayAtributosPadre['NumTramite']='';
	
	AgregaAtributos($arrayAtributosPadre,$RepAuxFol);
	$documento->appendChild($RepAuxFol);
}
else if($opcion==5){
	$arrayAtributosPadre=atributosXMLS($RFC,$mes,$anio,$Sello,$noCertificado,$Certificado,$TipoEnvio,$FechaModBal,$TipoSolicitud,$NumOrden,$NumTramite,$Folio,$FechaSello,$Sello,$noCertificadoSAT,$CertificadoSAT,$selloSAT,$opcion);
	$arrayAtributosPadre[17]['xsi:schemaLocation']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/AuxiliarCtas/AuxiliarCtas_1_1.xsd';
	$arrayAtributosPadre[18]['xmlns:xsi']='http://www.w3.org/2001/XMLSchema-instance';
	$arrayAtributosPadre[19]['xmlns:AuxiliarCtas']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/AuxiliarCtas';
	$AuxiliarCtas = $documento->createElement('AuxiliarCtas:AuxiliarCtas');
	AgregaAtributos($arrayAtributosPadre,$AuxiliarCtas);
	$documento->appendChild($AuxiliarCtas);
}
else if($opcion==6){
	$arrayAtributosPadre=atributosXMLS($RFC,$mes,$anio,$Sello,$noCertificado,$Certificado,$TipoEnvio,$FechaModBal,$TipoSolicitud,$NumOrden,$NumTramite,$Folio,$FechaSello,$Sello,$noCertificadoSAT,$CertificadoSAT,$selloSAT,$opcion);
	$arrayAtributosPadre[17]['xsi:schemaLocation']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/SelloDigitalContElec/SelloDigitalContElec.xsd';
	$arrayAtributosPadre[18]['xmlns:xsi']='http://www.w3.org/2001/XMLSchema-instance';
	$arrayAtributosPadre[19]['xmlns:sellodigital']='http://www.sat.gob.mx/esquemas/ContabilidadE/1_1/SelloDigitalContElec';
	$SelloDigitalContElec = $documento->createElement('sellodigital:SelloDigitalContElec');
	AgregaAtributos($arrayAtributosPadre,$SelloDigitalContElec);
	$documento->appendChild($SelloDigitalContElec);
}
$documento->save($nameXml.'.xml');
ArmaZIP($nameXml);
function AgregaAtributos($arrayAtributosPadre,$objeto){
	foreach($arrayAtributosPadre as $atributo){
		foreach($atributo as $att=>$valor){
			if($valor!=''){
				$valor=validaLongitud($valor,$att);
				$objeto->setAttribute($att,htmlspecialchars_decode($valor));
			}
		}
	}
}
function AgregaAtributosHijo($atributo,$valor,$objeto){
		$objeto->setAttribute($atributo,htmlspecialchars_decode($valor));
}
function ArmaZIP($nameXml){
	$zip = new ZipArchive;
	if($zip->open("xmlsFacElec/$nameXml.zip",ZipArchive::CREATE)===TRUE){
		$zip->addFile("$nameXml.xml");
		$zip->close();
	}
	$archivo="xmlsFacElec/$nameXml.zip";
	unlink($nameXml.'.xml');
	DescargaZIP($archivo);
}
function DescargaZIP($archivo){
	header("Content-type: application/zip");
	header("Content-Disposition: attachment; filename=$archivo");
	header("Content-Transfer-Encoding: binary");
	readfile($archivo);
	unlink($archivo);
}
function validaLongitud($valor,$atributo){
	if($atributo=='Mes'){
		if($valor>0&&$valor<10){
			$valor='0'.$valor;
		}
	}
	return $valor;
}
function atributosXMLS($RFC,$mes,$anio,$Sello,$noCertificado,$Certificado,$TipoEnvio,$FechaModBal,$TipoSolicitud,$NumOrden,$NumTramite,$Folio,$FechaSello,$Sello,$noCertificadoSAT,$CertificadoSAT,$selloSAT,$opcion){
	$array_atributos=array();
	if($opcion==1||$opcion==2||$opcion==3||$opcion==4||$opcion==5){
		$array_atributos[0]['Version']='1.1';
		$array_atributos[1]['RFC']=$RFC;
		$array_atributos[2]['Mes']=$mes;
		$array_atributos[3]['Anio']=$anio;
		$array_atributos[4]['Sello']='Sello';
		$array_atributos[5]['noCertificado']='noCertificado';
		$array_atributos[6]['Certificado']='Certificado';
		if($opcion==2){
			$array_atributos[7]['TipoEnvio']='TipoEnvio';
			$array_atributos[8]['FechaModBal']='FechaModBal';
		}else if($opcion==3||$opcion==4||$opcion==5){
			$array_atributos[9]['TipoSolicitud']=$TipoSolicitud;
			$array_atributos[10]['NumOrden']=$NumOrden;
			$array_atributos[11]['NumTramite']=$NumTramite;
		}
	}else if($opcion==6){
		$array_atributos[0]['Version']='1.1';
		$array_atributos[1]['RFC']=$RFC;
		$array_atributos[12]['Folio']='Folio';
		$array_atributos[13]['FechaSello']='FechaSello';
		$array_atributos[14]['sello']='sello';
		$array_atributos[15]['noCertificadoSAT']='noCertificadoSAT';
		$array_atributos[16]['selloSat']='selloSAT';
	}
	return $array_atributos;
}
?>