<?php
$xmlBdd = "";
$sqlFactura = "SELECT str_xml,timbrado,folio_cfd,cancelada,str_xml_cancelacion FROM ad_facturas WHERE id_control_factura = ".$idControlFactura;
$datosFac = mysql_query($sqlFactura);
$resultFac = mysql_fetch_array($datosFac);
if($resultFac[0] != ""){
	$xmlBdd = $resultFac[0];
	$timbrado = $resultFac[1];
	$folio_cfd = $resultFac[2];
}
	
$sqlProveedor = "
	SELECT ad_proveedores.id_proveedor,rfc 	
	FROM ad_proveedores 
	LEFT JOIN ad_cuentas_por_pagar_operadora ON ad_cuentas_por_pagar_operadora.id_proveedor=ad_proveedores.id_proveedor
	WHERE ad_cuentas_por_pagar_operadora.id_control_factura = ".$idControlFactura;
$result = new consultarTabla($sqlProveedor);
$datosProveedor = $result -> obtenerLineaRegistro();

$rutaXml = "../../../audicel_archivos/cfdicxp/".$datosProveedor['rfc']."/";
$rutaPDF = "../../../audicel_archivos/cfdicxp/".$datosProveedor['rfc']."/";
$nombre_archivo = $rutaXml.$resultFac['folio_cfd'].'.xml';
unlink($nombre_archivo);
$nombre_archivoPDF = $rutaXml.$resultFac['folio_cfd'].'.pdf';
unlink($nombre_archivoPDF);
if($xmlBdd != ""){
	$sqlTipoCliente = "
		SELECT id_tipo_cliente_proveedor 
		FROM ad_clientes 
		LEFT JOIN ad_facturas ON ad_clientes.id_cliente = ad_facturas.id_cliente 
		WHERE id_control_factura = ".$idControlFactura;
	$resTipoCliente = mysql_query($sqlTipoCliente) or die("Error en \n$strSQL\n\nDescripcion:\n".mysql_error("error"));
	$rowTipoCliente = mysql_fetch_row($resTipoCliente);



	if($rowTipoCliente[0] == '6'){
		$xmlBdd2 = str_replace('</cfdi:Comprobante>','',$xmlBdd);
		$xmlBdd2 .= "\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>"."\n".'<cfdi:Addenda> <SKY_RecepcionFacturas xmlns="http://repository.edicomnet.com/schemas/mx/cfd/addenda"> <NumAcreedor>40015</NumAcreedor> <TipoFacturaProveedor>master</TipoFacturaProveedor> <CodigoFacturacion>CM00000232</CodigoFacturacion> <Sistema>SAP</Sistema> <PersonaContacto>ERIKA</PersonaContacto> <NumRefSis>-</NumRefSis> </SKY_RecepcionFacturas>'."\n </cfdi:Addenda>";
		$xmlBdd2 .= "\n</cfdi:Comprobante>";	
	}else{
		$xmlBdd2 = str_replace('</cfdi:Comprobante>','',$xmlBdd);
		$xmlBdd2 .= "\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>\n</cfdi:Comprobante>";
	}	
	//echo mb_convert_encoding($xmlBdd2,"UTF-8", "ISO-8859-1");
}
if(!file_exists($rutaXml)){
	mkdir($rutaXml);
	chmod($rutaXml,0777);
}
if($archivo = fopen($nombre_archivo, "a")){
	fwrite($archivo,$xmlBdd2);
	fclose($archivo);
}
$factura = $idControlFactura;
$caso = '1';
include("../../code/pdf/imprimeFactura_audicel.php");
$pdf -> Output($nombre_archivoPDF);
$updateCXP = "	
	UPDATE ad_cuentas_por_pagar_operadora 
	SET nombre_xml = '".$resultFac['folio_cfd'].'.xml'."',
	referencia_xml = '".$nombre_archivo."', 
	nombre_pdf = '".$resultFac['folio_cfd'].'.pdf'."',
	referencia_pdf = '".$nombre_archivoPDF."' 
	WHERE id_control_factura = ".$idControlFactura;
mysql_query($updateCXP) or die('error en: '.$updateCXP. mysql_error());
?>
