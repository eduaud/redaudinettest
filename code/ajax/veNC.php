<?php

	extract($_GET);
	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	
	//$ruta=dirname(__FILE__);
	
	//$ruta.="/../../CFD/Facturas/".$fact;


		
		
		
		$sql="SELECT 
              certificado,
              sello,
              CONCAT(DATE_FORMAT(fecha_aplicacion_sello, '%Y-%m-%d'), 'T', TIME_FORMAT(fecha_aplicacion_sello, '%H:%i:%s')) as fecha,
              REPLACE(FORMAT(total, 2), ',', '') AS total, 
              REPLACE(FORMAT(subtotal, 2), ',', '') AS subtotal,  
              REPLACE(FORMAT(descuento, 2), ',', '') AS descuento,  
              no_certificado,  
              c.rfc,  
              c.razon_social,  
              c.calle,  
              c.no_exterior,  
              c.no_interior,  
              c.colonia,  
              c.delegacion_municipio,  
              (SELECT e.nombre FROM felec_estados e JOIN felec_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=c.id_ciudad) as estado,  
              c.cp,  
              cl.rfc as rfc_r,  
              cl.razon_social as razon_social_r,  
              cl.calle as calle_r,  
              cl.no_interior as no_interior_r,  
              cl.no_exterior as no_exterior_r,  
              cl.colonia as colonia_r,  
              cl.delegacion_municipio as delegacion_municipio_r,  
              (SELECT e.nombre FROM felec_estados e JOIN felec_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=cl.id_ciudad) as estado_r,  
              cl.cp as cp_r ,
			  f.folio_cfd,
			  f.timbrado
              FROM felec_notas_credito f  
              JOIN felec_companias c ON f.id_compania = c.id_compania  
              JOIN felec_clientes cl ON f.id_cliente= cl.id_cliente  
              WHERE id_control_nota_credito=".$fact;
			  
		$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
		$row=mysql_fetch_assoc($res);
		extract($row);
		
		
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<cfdi:Comprobante xmlns:cfdi=\"http://www.sat.gob.mx/cfd/3\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv3.xsd\" version=\"3.0\" fecha=\"" . $fecha . "\" sello=\"" . $sello . "\" formaDePago=\"\" noCertificado=\"" . $no_certificado . "\" certificado=\"" . $certificado . "\" subTotal=\"" . $subtotal . "\" descuento=\"" . $descuento . "\" total=\"" . $total . "\" tipoDeComprobante=\"egreso\" > 
<cfdi:Emisor rfc=\"" . $rfc . "\" nombre=\"" . $razon_social . "\"> 
<cfdi:DomicilioFiscal calle=\"" . $calle . "\" ";
        if($no_exterior !=  "")
            $xml.= " noExterior=\"" . $no_exterior . "\"";
        
        if($no_interior != "")
            $xml.= " noInterior=\"" . $no_interior . "\"";
        
        if($colonia <> "")
            $xml.= " colonia=\"" . $colonia . "\"";
        
        if($delegacion_municipio != "")
            $xml.= " municipio=\"" . $delegacion_municipio . "\"";
        
        if($estado != "")
            $xml.= " estado=\"" . $estado . "\"";
        
        $xml.= " pais=\"MEXICO\"  codigoPostal=\"" . $cp . "\"/> 
</cfdi:Emisor> 
<cfdi:Receptor rfc=\"" . $rfc_r . "\" nombre=\"" . $razon_social_r . "\"> 
<cfdi:Domicilio calle=\"" . $calle_r . "\"";

        if($no_exterior_r != "")
            $xml.= " noExterior=\"" . $no_exterior_r . "\"";
        
        if($no_interior_r != "")
            $xml.= " noInterior=\"" . $no_interior_r . "\"";
        
        if($colonia_r != "")
            $xml.= " colonia=\"" . $colonia_r . "\"";
        
        if($delegacion_municipio_r != "")
            $xml.= " municipio=\"" . $delegacion_municipio_r . "\"";
        
        if($estado_r != "")
            $xml.= " estado=\"" . $estado_r . "\"";
        

        $xml.= " pais=\"MEXICO\"  codigoPostal=\"" . $cp_r . "\"/>  
</cfdi:Receptor> 
<cfdi:Conceptos>";


		$sql = "SELECT 
                fd.cantidad, 
                p.unidad, 
                p.id_producto, 
                REPLACE(REPLACE(p.nombre, 'Ñ', ''), 'ñ', ''), 
                REPLACE(FORMAT(fd.valor_unitario, 2), ',', ''), 
                REPLACE(FORMAT(fd.cantidad*fd.valor_unitario, 2), ',', '') 
                FROM felec_notas_credito_detalles fd 
                JOIN felec_productos p ON fd.id_producto = p.id_producto 
                WHERE fd.id_control_nota_credito = " . $fact;
				
		$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
		$num=mysql_num_rows($res);
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);		
			
			$xml.= "\n<cfdi:Concepto cantidad=\"" . $row[0] . "\" unidad=\"" . $row[1] . "\" noIdentificacion=\"" . $row[2]. "\" descripcion=\"" . $row[3] . "\" valorUnitario=\"" . $row[4] . "\" importe=\"" . $row[5] . "\" /> ";
			
		}	
		
		$xml .= "\n</cfdi:Conceptos>";
		
		
		$sql = "(SELECT 'IVA', iva_tasa, REPLACE(FORMAT(SUM(iva_monto), 2), ',', '') FROM felec_facturas_detalles fd WHERE fd.id_control_factura='" . $fact . "' And iva_tasa > 0 GROUP BY iva_tasa )
                UNION ALL
                (SELECT 'IEPS', ieps_tasa, REPLACE(FORMAT(SUM(ieps_monto), 2), ',', '') FROM felec_facturas_detalles fd WHERE fd.id_control_factura='" . $fact . "' AND ieps_tasa > 0 GROUP BY iva_tasa)";
				

		$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
		$num=mysql_num_rows($res);
		$totimp=0;
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			$totimp+=$row[2];
		}
		
		$auxArr = explode(".", $totimp);
        if(sizeof($auxArr) == 1)
            $aux = $auxArr[0] . ".00";
        else
		{
            if(strlen($auxArr[1]) == 1)
                $aux = $auxArr[0] . "." . $auxArr[1] . "0";
            else
                $aux = $auxArr[0] . "." . $auxArr[1];           
        }
		
		if($num == 0)
            $xml.= "\n<cfdi:Impuestos totalImpuestosTrasladados=\"0.00\">\n<cfdi:Traslados>\n<cfdi:Traslado impuesto=\"IVA\" tasa=\"0.00\" importe=\"0.00\" />\n</cfdi:Traslados>\n</cfdi:Impuestos>";
        else
		{
            $xml.= "\n<cfdi:Impuestos totalImpuestosTrasladados=\"" . $aux . "\">
\n<cfdi:Traslados>";
            for($i=0;$i<$num;$i++)
			{
				mysql_data_seek($res, $i);
				$res=mysql_fetch_row($res);
                $xml.= "\n<cfdi:Traslado impuesto=\"" . $row[0] . "\" tasa=\"" . $row[1] . "\" importe=\"" . $row[2] . "\" /> ";
            }

            $xml.= "\n</cfdi:Traslados>\n</cfdi:Impuestos>";
        }

			  
		
		$xml.="\n<cfdi:Complemento>\n".$timbrado."\n</cfdi:Complemento>\n</cfdi:Comprobante>";	  
		
		
		header('Content-Disposition: attachment; filename="'.$folio_cfd.'.xml"');		
		echo $xml;

?>