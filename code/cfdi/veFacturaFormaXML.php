<?php

	extract($_GET);
	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	
	//$ruta=dirname(__FILE__);
	
	//$ruta.="/../../CFD/Facturas/".$fact;
$newdatos = array();
		$sql2="SELECT 
				a.id_sucursal,  
				b.descripcion as forma_pago, 
				a.no_cuenta as no_cuenta,
				a.id_compania
				FROM anderp_facturas a
				LEFT JOIN anderp_formas_pagos_sat b ON b.id_control_forma_pago_sat = a.forma_pago_sat 
				WHERE id_control_factura=".$fact;
			
			$res2=mysql_query(utf8_decode($sql2)) or die(mysql_error());
		    $num2=mysql_num_rows($res2);
			if($num2>0){
					$row2=mysql_fetch_array($res2);
					$newdatos[0] = $row2[1];   //forma de pago sat
					$newdatos[1] = $row2[2];   //numero de cuenta
					$id_sucursalFact = $row2[0];  
				    $compania =  $row2[3];
				
				
			}//fin if num
			$aux_suc='';
			$sql3 = "SELECT 
					  c3.nombre as sucursal,
					  c3.calle_no as calle_no_sucursal,
					  c3.numero_ext as no_ext_sucursal,
					  c3.numero_int as no_int_sucursal,
					  c3.colonia as colonia_sucursal,
					  c3.del_mpo as del_mpo_sucursal,
					  c3.cp as cp_sucursal,
					  (SELECT DISTINCT  e.nombre
						FROM anderp_estados e 
						WHERE e.id_estado = c3.id_localidad) as estado_sucursal
	                FROM  sys_sucursales c3 WHERE  c3.id_sucursal=".$id_sucursalFact;
			$res3=mysql_query(utf8_decode($sql3)) or die(mysql_error());		
			$row3=mysql_fetch_assoc($res3);
		    extract($row3);
	
			
			if($no_ext_sucursal)
			   $aux_suc.=" No. ".$no_ext_sucursal;
			if($no_int_sucursal)
				$aux_suc.=" Int. ".$no_int_sucursal;
			if($colonia_sucursal)
			   $aux_suc.=" ,".$colonia_sucursal;	
		       $aux_suc.= "C.P. ".$cp_sucursal;	
			if($estado_sucursal)
		       $aux_suc.=" ,".$estado_sucursal;
			if($del_mpo_sucursal)
		       $aux_suc.=" ,".$del_mpo_sucursal;
		
			 $dir_sucursal = $calle_no_sucursal.$aux_suc;
		     $newdatos[2] =  $dir_sucursal;               //Lugar de Expedicion
			 
			 //get regimen 
             $sql4 = "SELECT regimen FROM anderp_companias WHERE id_compania=".$compania;
			  $res4=mysql_query(utf8_decode($sql4)) or die(mysql_error());		
			  $row4=mysql_fetch_assoc($res4);
		      extract($row4);
			  $regimen_fiscal = $regimen;
			  
			  $newdatos[3] = $regimen_fiscal;      //Regimen Fiscal
			  

//------------------------------------------------------//
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
              (SELECT e.nombre FROM anderp_estados e JOIN anderp_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=c.id_ciudad) as estado,  
              c.cp,  
              cl.rfc as rfc_r,  
              cl.razon_social as razon_social_r,  
              cl.calle as calle_r,  
              cl.no_interior as no_interior_r,  
              cl.no_exterior as no_exterior_r,  
              cl.colonia as colonia_r,  
              cl.delegacion_municipio as delegacion_municipio_r,  
              (SELECT e.nombre FROM anderp_estados e JOIN anderp_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=cl.id_ciudad) as estado_r,  
              cl.cp as cp_r ,
			  f.id_factura as folio_cfd,
			  f.timbrado,
			  f.prefijo as serie,
			  f.consecutivo as folio,
			  c3.numero_aprobacion as numero_aprobacion,
			  c3.anio_aprobacion as anio_aprobacion
              FROM anderp_facturas f  
              JOIN anderp_companias c ON f.id_compania = c.id_compania  
              JOIN anderp_clientes cl ON f.id_cliente= cl.id_cliente  
			  JOIN anderp_folios c3 ON c3.folio_inicial <= f.consecutivo
              WHERE id_control_factura=".$fact;
			  
		$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
		$row=mysql_fetch_assoc($res);
		extract($row);
		
		
		/*$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<cfdi:Comprobante xmlns:cfdi=\"http://www.sat.gob.mx/cfd/3\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv3.xsd\" version=\"3.0\" fecha=\"" . $fecha . "\" sello=\"" . $sello . "\" formaDePago=\"\" noCertificado=\"" . $no_certificado . "\" certificado=\"" . $certificado . "\" subTotal=\"" . $subtotal . "\" descuento=\"" . $descuento . "\" total=\"" . $total . "\" tipoDeComprobante=\"ingreso\" > 
<cfdi:Emisor rfc=\"" . $rfc . "\" nombre=\"" . $razon_social . "\"> 
<cfdi:DomicilioFiscal calle=\"" . $calle . "\" ";*/
		
		
		
	$xml = "<?xml version=\"2.2\" encoding=\"UTF-8\"?>
<Comprobante  xsi:schemaLocation=\"http://www.sat.gob.mx/cfd/2 http://www.sat.gob.mx/sitio_internet/cfd/2/cfdv2.xsd \" xmlns=\"http://www.sat.gob.mx/cfd/2\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" version=\"2.0\" serie=\"".$serie."\"  folio=\"".$folio."\" fecha=\"".$fecha ."\" sello=\"" . $sello . "\" noAprobacion=\"".$numero_aprobacion."\" anoAprobacion=\"".$anio_aprobacion."\" tipoDeComprobante=\"ingreso\" LugarExpedicion=\"".$newdatos[2]."\" metodoDePago=\"".$newdatos[0]."\"   NumCtaPago=\"".$newdatos[1]."\" formaDePago=\"PAGO EN UNA SOLA EXHIBICION\" noCertificado=\"" . $no_certificado . "\" certificado=\"" . $certificado . "\" subTotal=\"" . $subtotal . "\" descuento=\"" . $descuento . "\" total=\"" . $total . "\"  	 > 
\n<RegimenFiscal Regimen='".$newdatos[3]."' >\n<Emisor rfc=\"" . $rfc . "\" nombre=\"" . $razon_social . "\"> 
<DomicilioFiscal calle=\"" . $calle . "\" ";


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
</Emisor> 
<Receptor rfc=\"" . $rfc_r . "\" nombre=\"" . $razon_social_r . "\"> 
<Domicilio calle=\"" . $calle_r . "\"";

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
</Receptor> 
<Conceptos>";


		$sql = "SELECT 
				 IF(fd.id_unidad_venta=1,fd.kilos_netos,fd.cantidad) as cantidad,
                IF(fd.id_unidad_venta=1,'KG','Piezas') as 'unidad venta', 
                p.id_producto, 
                REPLACE(REPLACE(p.nombre, 'Ñ', ''), 'ñ', ''), 
                REPLACE(FORMAT(fd.valor, 4), ',', ''), 
            REPLACE(FORMAT(fd.importe,2), ',', '')
                FROM  anderp_facturas_detalles fd 
                JOIN anderp_productos p ON fd.id_producto = p.id_producto 
                WHERE p.id_sucursal=".$_SESSION["USR"]->sucursalid." AND fd.id_control_factura = " . $fact;
				
		$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
		$num=mysql_num_rows($res);
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);		
			
			$xml.= "\n<Concepto cantidad=\"" . $row[0] . "\" unidad=\"" . strtolower($row[1]) . "\" noIdentificacion=\"" . $row[2]. "\" descripcion=\"" . $row[3] . "\" valorUnitario=\"" . $row[4] . "\" importe=\"" . $row[5] . "\" /> ";
			
		}	
		
		$xml .= "\n</Conceptos>";
		
		
		$sql = "(SELECT 'IVA', iva_tasa, REPLACE(FORMAT(SUM(iva_monto), 2), ',', '') FROM anderp_facturas_detalles fd WHERE fd.id_control_factura='" . $fact . "' And iva_tasa > 0 GROUP BY iva_tasa )
                UNION ALL
                (SELECT 'IEPS', ieps_tasa, REPLACE(FORMAT(SUM(ieps_monto), 2), ',', '') FROM anderp_facturas_detalles fd WHERE fd.id_control_factura='" . $fact . "' AND ieps_tasa > 0 GROUP BY iva_tasa)";
				

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
            $xml.= "\n<Impuestos totalImpuestosTrasladados=\"0.00\">\n<Traslados>\n<Traslado impuesto=\"IVA\" importe=\"0.00\" tasa=\"0.00\"  />\n</Traslados>\n</Impuestos>";
        else
		{
            $xml.= "\n<Impuestos totalImpuestosTrasladados=\"" . $aux . "\">
\n<Traslados>";
            for($i=0;$i<$num;$i++)
			{
				mysql_data_seek($res, $i);
				$res=mysql_fetch_row($res);
                $xml.= "\n<Traslado impuesto=\"" . $row[0] . "\" tasa=\"" . $row[1] . "\" importe=\"" . $row[2] . "\" /> ";
            }

            $xml.= "\n</Traslados>\n</Impuestos>";
        }

			  
		
		$xml.="\n</Comprobante>";	  
		
		
		header('Content-Disposition: attachment; filename="'.$folio_cfd.'.xml"');		
		echo $xml;

?>