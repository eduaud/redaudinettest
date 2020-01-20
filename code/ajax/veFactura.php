<?php

	extract($_GET);


	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");

	//$ruta=dirname(__FILE__);

	//$ruta.="/../../CFD/Facturas/".$fact;

	//primero buscamos si existe el archivo xml guardado
	$tabla=base64_decode($tabla);
	$xmlBdd="";
	//obtebemos el numero de la factura	
	$strSQL="SELECT str_xml,timbrado,folio_cfd FROM ".$tabla." WHERE id_control_factura ='".$fact."' ";
	$res2=mysql_query($strSQL) or die("Error en \n$strSQL\n\nDescripcion:\n".mysql_error());
	$rowFacXML=mysql_fetch_row($res2);
	if($rowFacXML[0] != '')
	{
		$xmlBdd=$rowFacXML[0];
		$timbrado=$rowFacXML[1];
		$folio_cfd=$rowFacXML[2];
	}
	header('Content-Disposition: attachment; filename="'.$folio_cfd.'.xml"');		

	if($xmlBdd!="")
	{
		$sqlTipoCliente="
		SELECT id_tipo_cliente_proveedor 
		FROM ad_clientes 
		LEFT JOIN ad_facturas_audicel ON ad_clientes.id_cliente=ad_facturas_audicel.id_cliente 
		WHERE id_control_factura=".$fact;
		$resTipoCliente=mysql_query($sqlTipoCliente) or die("Error en \n$strSQL\n\nDescripcion:\n".mysql_error("error"));
		$rowTipoCliente=mysql_fetch_row($resTipoCliente);



		if($rowTipoCliente[0]=='6'){
			$xmlBdd2=str_replace('</cfdi:Comprobante>','',$xmlBdd);
			$xmlBdd2.="\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>"."\n".'<cfdi:Addenda> <SKY_RecepcionFacturas xmlns="http://repository.edicomnet.com/schemas/mx/cfd/addenda"> <NumAcreedor>40015</NumAcreedor> <TipoFacturaProveedor>master</TipoFacturaProveedor> <CodigoFacturacion>CM00000232</CodigoFacturacion> <Sistema>SAP</Sistema> <PersonaContacto>ERIKA</PersonaContacto> <NumRefSis>-</NumRefSis> </SKY_RecepcionFacturas>'."\n </cfdi:Addenda>";
			$xmlBdd2.="\n</cfdi:Comprobante>";	
		}else{
			$xmlBdd2=str_replace('</cfdi:Comprobante>','',$xmlBdd);
			$xmlBdd2.="\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>\n</cfdi:Comprobante>";
		}	
		echo mb_convert_encoding($xmlBdd2,"UTF-8", "ISO-8859-1");
	}


?>
