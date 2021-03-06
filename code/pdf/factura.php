<?php

	//include_once("phpqrcode/qrlib.php");
	include_once("qrcode.php");
	include_once("../general/funciones.php");

	
	
	//borrar al subir a prod
	//define('FPDF_FONTPATH','fpdf/font/');

	//vemos el ttipo si es factura o es nota de credito para hacer el join con la tabla correspondiente
	
	if(isset($doc))
	{
		//excepcion especial para nasser en clientes que no requieren factura y se coloca el rfc generico
		
		$strSQL="SELECT if(id_fiscales_cliente=0,0,1) as requiereFactura FROM ".$tabla." where id_control_factura=".$doc;
		$resID=valBuscador($strSQL);
		$requiereFactura= $resID[0];
		//echo $strSQL;
		if($tabla=='ad_facturas')
			$tabla_detalle='ad_facturas_detalle';
		else
			$tabla_detalle='ad_facturas_audicel_detalle';
		if($requiereFactura=='1')
		{
			if($tabla=='ad_facturas_audicel'){
				$sql="SELECT
					certificado,
					sello,
					CONCAT(DATE_FORMAT(fecha_aplicacion_sello, '%Y-%m-%d'), 'T', TIME_FORMAT(fecha_aplicacion_sello, '%H:%i:%s')) as fecha,
					FORMAT(total, 2) AS total,
					FORMAT(subtotal, 2) AS subtotal,
					FORMAT(descuento, 2) AS descuento,  
					FORMAT(retencion_iva, 2) AS retencion_iva,  
					FORMAT(retencion_isr, 2) AS retencion_isr,  
					FORMAT(ieps, 2) AS ieps,
					no_certificado, 

					c.rfc,  
					c.razon_social,
					c.calle,
					c.no_exterior,  
					c.no_interior,
					c.colonia,
					c.delegacion_municipio,  
					(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=c.id_ciudad) as estado,
					c.cp,
					cl.rfc as rfc_r,  
					cl.nombre_razon_social as razon_social_r,
					cl.calle as calle_r,  
					cl.numero_interior as no_interior_r,
					cl.numero_exterior as no_exterior_r,
					cl.colonia as colonia_r,
					cl.delegacion_municipio as delegacion_municipio_r,
					(SELECT CONCAT(cd.nombre ,' ,',e.nombre )FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=cl.id_ciudad) as estado_r,
					cl.cp as cp_r ,

					f.folio_cfd,
					f.timbrado,
					id_factura as folio,
					DATE_FORMAT(f.fecha_y_hora, '%Y-%m-%d') as fecha_emision,
					f.cadena_original,
					f.observaciones,
					FORMAT(f.iva,2) as iva1,
					f.id_compania as compania,
					total as tot,
					 fp.descripcion as metodo_pago,
					f.cuenta as  cuenta,
					c.regimen as regimen,
					c.lugar_expedicion,
					f.id_moneda as moneda,
					f.id_sucursal as id_sucursal,
					SC.nombre as nombre_sucursal,
					SC.calle as calle_sucursal,
					SC.numero_exterior as no_ext_sucursal,
					SC.numero_interior as no_int_sucursal,
					SC.colonia  as colonia_sucursal,
					SC.delegacion as  delegacion_sucursal,
					(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=SC.id_ciudad) as estado_sucursal,
					SC.codigo_postal as  cp_sucursal,
					f.retencion_iva as retencion,
					ftcf.nombre_corto tipo_cfd
					FROM ad_facturas_audicel f
					LEFT JOIN sys_companias c ON f.id_compania = c.id_compania
					LEFT JOIN ad_clientes_datos_fiscales cl ON f.id_fiscales_cliente= cl.id_cliente_dato_fiscal
					join scfdi_formas_de_pago_sat  as fp ON fp.id_forma_pago_sat=f.id_forma_pago_sat
					left join  ad_sucursales SC on  SC.id_sucursal=f.id_sucursal
					LEFT JOIN scfdi_tipo_comprobante_fiscal ftcf ON ftcf.id_tipo_comprobante_fiscal = f.id_tipo_comprobante_fiscal
					WHERE id_control_factura=".$doc;
			}else if($tabla=='ad_facturas'){
				$sql="SELECT
					certificado,
					sello,
					CONCAT(DATE_FORMAT(fecha_aplicacion_sello, '%Y-%m-%d'), 'T', TIME_FORMAT(fecha_aplicacion_sello, '%H:%i:%s')) as fecha,
					FORMAT(total, 2) AS total,
					FORMAT(subtotal, 2) AS subtotal,
					FORMAT(descuento, 2) AS descuento,  
					FORMAT(retencion_iva, 2) AS retencion_iva,  
					FORMAT(retencion_isr, 2) AS retencion_isr,  
					FORMAT(ieps, 2) AS ieps,
					no_certificado, 

					c.rfc,  
					c.nombre_razon_social as razon_social,
					c.calle,
					c.numero_exterior,  
					c.numero_interior,
					c.colonia,
					c.delegacion_municipio,  
					(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=c.id_ciudad) as estado,
					c.cp,
					cl.rfc as rfc_r,  
					cl.razon_social as razon_social_r,
					cl.calle as calle_r,  
					cl.no_interior as no_interior_r,
					cl.no_exterior as no_exterior_r,
					cl.colonia as colonia_r,
					cl.delegacion_municipio as delegacion_municipio_r,
					(SELECT CONCAT(cd.nombre ,' ,',e.nombre )FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=cl.id_ciudad) as estado_r,
					cl.cp as cp_r ,

					f.folio_cfd,
					f.timbrado,
					id_factura as folio,
					DATE_FORMAT(f.fecha_y_hora, '%Y-%m-%d') as fecha_emision,
					f.cadena_original,
					f.observaciones,
					FORMAT(f.iva,2) as iva1,
					f.id_compania as compania,
					total as tot,
					 fp.descripcion as metodo_pago,
					f.cuenta as  cuenta,
					c.regimen as regimen,
					c.lugar_expedicion,
					f.id_moneda as moneda,
					f.id_sucursal as id_sucursal,
					SC.nombre as nombre_sucursal,
					SC.calle as calle_sucursal,
					SC.numero_exterior as no_ext_sucursal,
					SC.numero_interior as no_int_sucursal,
					SC.colonia  as colonia_sucursal,
					SC.delegacion as  delegacion_sucursal,
					(SELECT e.nombre FROM sys_estados e JOIN sys_ciudades cd ON e.id_estado = cd.id_estado WHERE cd.id_ciudad=SC.id_ciudad) as estado_sucursal,
					SC.codigo_postal as  cp_sucursal,
					f.retencion_iva as retencion,
					ftcf.nombre_corto tipo_cfd
					FROM ad_facturas f
					LEFT JOIN ad_clientes_datos_fiscales c ON f.id_compania = c.id_cliente
					LEFT JOIN sys_companias cl ON f.id_fiscales_cliente= cl.id_compania
					join scfdi_formas_de_pago_sat  as fp ON fp.id_forma_pago_sat=f.id_forma_pago_sat
					left join  ad_sucursales SC on  SC.id_sucursal=f.id_sucursal
					LEFT JOIN scfdi_tipo_comprobante_fiscal ftcf ON ftcf.id_tipo_comprobante_fiscal = f.id_tipo_comprobante_fiscal
					WHERE id_control_factura=".$doc;

			}
				
		}
		else
		{
			//para clientes que no requieren factura colocamos los valores 
			$sql="SELECT certificado, sello, CONCAT(DATE_FORMAT(fecha_aplicacion_sello, '%Y-%m-%d'), 'T', TIME_FORMAT(fecha_aplicacion_sello, '%H:%i:%s')) as fecha, FORMAT(f.total, 2) AS total, FORMAT(f.subtotal, 2) AS subtotal, FORMAT(f.descuento, 2) AS descuento, FORMAT(f.retencion_iva, 2) AS retencion_iva, FORMAT(f.retencion_isr, 2) AS retencion_isr, FORMAT(f.ieps, 2) AS ieps, #no_certificado#, c.rfc, c.razon_social, c.calle, c.no_exterior, c.no_interior, c.colonia, c.delegacion_municipio,
			(SELECT e.nombre FROM sys_estados e 
			JOIN sys_ciudades cd ON e.id_estado = cd.id_estado 
			WHERE cd.id_ciudad=c.id_ciudad) as estado, c.cp, 'XAXX010101000' as rfc_r, CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', if(ad_clientes.apellido_materno is null,'',apellido_materno)) as razon_social_r, ad_clientes.calle_fiscal as calle_r, ad_clientes.numero_interior_fiscal as no_interior_r, ad_clientes.numero_exterior_fiscal as no_exterior_r, ad_clientes.colonia_fiscal as colonia_r, ad_clientes.delegacion_municipio_fiscal as delegacion_municipio_r,
			(SELECT CONCAT(cd.nombre ,' ,',e.nombre )
			FROM sys_estados e 
			JOIN sys_ciudades cd ON e.id_estado = cd.id_estado 
			WHERE cd.id_ciudad=ad_clientes.id_ciudad_fiscal) as estado_r, ad_clientes.codigo_postal_fiscal as cp_r , f.folio_cfd, f.timbrado, id_factura as folio, DATE_FORMAT(f.fecha_y_hora, '%Y-%m-%d') as fecha_emision, f.cadena_original, f.observaciones, FORMAT(f.iva,2) as iva1, f.id_compania as compania, f.total as tot, fp.descripcion as metodo_pago, f.cuenta as cuenta, c.regimen as regimen,c.lugar_expedicion, f.id_moneda as moneda, f.id_sucursal as id_sucursal, SC.nombre as nombre_sucursal, SC.calle as calle_sucursal, SC.numero_exterior as no_ext_sucursal, SC.numero_interior as no_int_sucursal, SC.colonia as colonia_sucursal, SC.delegacion as delegacion_sucursal,
			(SELECT e.nombre 
			FROM sys_estados e 
			JOIN sys_ciudades cd ON e.id_estado = cd.id_estado 
			WHERE cd.id_ciudad=SC.id_ciudad) as estado_sucursal, SC.codigo_postal as cp_sucursal, f.retencion_iva as retencion, ftcf.nombre_corto tipo_cfd
			FROM ".$tabla." f 
			LEFT JOIN sys_companias c ON f.id_compania = c.id_compania 
			left join ad_pedidos on f.id_control_pedido=ad_pedidos.id_control_pedido 
			left join ad_clientes on ad_clientes.id_cliente=f.id_cliente 
			join scfdi_formas_de_pago_sat as fp ON fp.id_forma_pago_sat=f.id_forma_pago_sat 
			left join ad_sucursales SC on SC.id_sucursal=f.id_sucursal 
			LEFT JOIN scfdi_tipo_comprobante_fiscal ftcf ON ftcf.id_tipo_comprobante_fiscal = f.id_tipo_comprobante_fiscal 
			WHERE f.id_control_factura=".$doc;
			
		}
		$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
		$row=mysql_fetch_assoc($res);
		extract($row);
	}
	$pdf=new PDF($orient_doc,$unid_doc,$tamano_doc);
	$pdf->AddPage();
	$pdf->SetFont('Arial','',$ftam);
	$pdf->SetAutoPageBreak(false);
	
	
	
	$sqlDetalle="
		SELECT
		fd.cantidad,
		un.nombre AS unidad,
		p.nombre,
		fd.descripcion,
		FORMAT(fd.valor_unitario,2) AS valor_unitario,
		FORMAT(fd.valor_unitario*fd.cantidad,2) as importe
		,1
		FROM ".$tabla_detalle." fd
		LEFT JOIN cl_productos_servicios p ON fd.id_producto = p.id_producto_servicio
		LEFT JOIN cl_clasificacion_productos cp ON cp.id_clasificacion_producto = p.id_producto_servicio
		LEFT JOIN scfdi_unidades un ON un.id_unidad = cp.id_unidad
		WHERE fd.id_control_factura = '$doc'		
	";
	$resDetalle=mysql_query($sqlDetalle) or die("error en: $sql<br><br>".mysql_error());	  
	$numDetalle=mysql_num_rows($resDetalle);
		
	$intContador=0;
	$j=-1;
		
	//HACEMOS UN ESTIMADO PARA SABER CUANTAS PAGINAS SERAN
	$pag=1;
	for($i = 0; $i < $numDetalle; $i++)
	{
		$rowDetalle = mysql_fetch_assoc($resDetalle);
		if($intContador > 30-14)
		{
			$intContador=0;
			$pag=$pag+1;
		}
		$lineasProd = round(strlen($rowDetalle['descripcion']) / 66, 0);
		$ymas_linea = $ymas_linea + $lineasProd * 0.22;
		$intContador= $intContador+1 + round($lineasProd * 0.7);
	}
	$paginaMax = $pag;
	
	
	
	
	//imgen dependiendo de la sucursal

	//echo $logo_sucursales;
	
	

	
	$pdf->SetTextColor(0,0,0);
	
	
	//logo 
	$id_compania = $row['compania'];
		


	 //buscamos los datos de la sucursal si el nombre es diferenta a matriz
	 //y solo de las compa�ias que deseamos ver la impresi�n de la sucursal
	 /*
		
	if($id_compania==1)
	{
		 $ubica = '1.jpg';
	}
	*/
			$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/sysaudinetmaster/code/pdf/' .$pre."factura".$pos.".jpg", 0, 0, 21.49);
	/*
	if(file_exists("factura.jpg"))
		$ubica ="factura.jpg";
	
	$pdf->Image($ubica,0, 0, 21.5);*/
	
	//$pdf->image("factura.jpg", 0, 0, 21.5);
	$logo_sucursales='../../imagenes/header_logo.png';
	if($paginaMax == 1)
	{
		if(file_exists(	$logo_sucursales))
			$pdf->image($logo_sucursales, 15.6,0.7,5,2.8);
	}
	else
	{
		if(file_exists(	$logo_sucursales))
			$pdf->image($logo_sucursales, 15.6,0.7,5,2.8);
	}	
	
	
	
	//Informacion de la compa�ia
	$pdf->celpos(0.8, 0.9, 20, 10, $razon_social,0,"L");	
	$pdf->celpos(0.8, 0.9, 20, 10, $razon_social,0,"L");	
	$aux=$calle;
	
	if($no_exterior)
		$aux.=" No. ".$no_exterior;	
	
	if($no_interior)
		$aux.=", ".$no_interior;
	
	$pdf->celpos(0.8, 1.3, 20, 8, $aux,0,"L");	

	$aux="";
	if($colonia)
		$aux=$colonia.", ";
	
	$aux.="C.P. ".$cp." M�xico";
	
	if($estado)
		$aux.=" ,".$estado;		
	
	if($id_compania==14){
	
		if($delegacion_municipio)
			$aux11=$delegacion_municipio;	
		
	}
	else
	{
		if($delegacion_municipio)
			$aux.=" ,".$delegacion_municipio;	
	
	}	
	$pdf->celpos(0.8, 1.6, 20, 8, $aux,0,"L");	
	
	//excepcion especial para el cliente colonia no cabe en un solo renglon
	if($id_compania==14)
		$pdf->celpos(0.8, 1.9, 20, 8, $aux11."     RFC: ".$rfc,0,"L");		/*Receptor del comprobante*/
	else
		$pdf->celpos(0.8, 1.9, 20, 8, "RFC: ".$rfc,0,"L");		/*Receptor del comprobante*/
	
	
	//PARA EL REGIMEN TOMAMOS LOS DTOS
	
	$pdf->celpos(0.8, 2.25, 20, 8, 'REGIMEN: ' . $regimen);

	//INICIO BLOQUE DE SUCURSAL
	
				$pdf->celpos(0.8, 2.7, 7.6, 8, "Sucursal:",0,"L");	
				$pdf->celpos(0.8, 2.7, 7.6, 8, "Sucursal:",0,"L");	
				$auxSuc=$calle_sucursal;
				if($no_ext_sucursal)
					$auxSuc.=" Ext. ".$no_ext_sucursal;
				if($no_int_sucursal)
					$auxSuc.=" Int ".$no_int_sucursal;	
				if($colonia_sucursal)
					$auxSuc.=" ,".$colonia_sucursal;	

				$pdf->celpos(0.8, 3, 20, 7, $auxSuc,0,"L");	

				$auxSuc="C.P. ".$cp_sucursal." M�xico";		
				if($estado_sucursal)
					$auxSuc.=" ,".$estado_sucursal;		
				if($delegacion_sucursal)
					$auxSuc.=" ,".$delegacion_sucursal;			
				$pdf->celpos(0.8, 3.3, 20, 7, $auxSuc,0,"L");
			
	//FIN BLOQUE DE SUCURSAL
	
	
	//INICIO BLOQUE RECEPTOR
		$pdf->celpos(0.8, 3.8, 20, 10, $razon_social_r,0,"L");	
		$pdf->celpos(0.8, 3.8, 20, 10, $razon_social_r,0,"L");
		$pdf->celpos(0.8, 3.8, 20, 10, $razon_social_r,0,"L");
		
		$aux=$calle_r;
		if($no_exterior_r)
			$aux.=" Ext. ".$no_exterior_r;	
		if($no_interior_r)
			$aux.=" No. ".$no_interior_r;
		if($colonia_r)
			$aux.=" ,".$colonia_r;	
		$pdf->celpos(0.8, 4.2, 20, 8, substr($aux, 0, 87), 0,"L");
		
		$aux= ltrim(substr($aux, 87) . " C.P. ".$cp_r." M�xico");
		if($estado_r)
			$aux.=" ,".$estado_r;		
		if($delegacion_municipio_r)
			$aux.=" ,".$delegacion_municipio_r;			
		$pdf->celpos(0.8, 4.5, 20, 8, $aux,0,"L");	
		$pdf->celpos(0.8, 4.8, 20, 8, "RFC: ".$rfc_r,0,"L");	 
		
		$pdf->celpos(0.8, 5.1, 20, 8,"METODO DE PAGO: ".$metodo_pago."     CUENTA: ".$cuenta,0,"L");
	//FIN BLOQUE RECEPTOR
	
	
	
	//INICIO DATOS FISCALES
		$ax=explode('noCertificadoSAT="', $timbrado);	
		$ax=explode('"', $ax[1]);
		$pdf->celpos(0.8, 5.6, 16, 8,"Folio Fiscal", 0, "L");
		$pdf->celpos(0.8, 5.9, 16, 8,"$folio_cfd", 0, "L");
		
		$pdf->celpos(7.2, 5.6, 16, 8," No. Certificado Digital", 0, "L");
		$pdf->celpos(7.2, 5.9, 16, 8," $no_certificado", 0, "L");
		
		$pdf->celpos(11, 5.6, 16, 8," No. Serie Certificado SAT", 0, "L");
		$pdf->celpos(11, 5.9, 16, 8," " . $ax[0], 0, "L");
	//FIN DATOS FISCALES
	
	
	
	
	
	
	
	
	
	//montos
	$pdf->SetTextColor(255,255,255);
	$pdf->celpos(16.2-.8, 3.8+.02, 4.4, 9, $tipo_cfd . " $folio",0,"C");	
		
	$pdf->SetTextColor(0,0,0);
	$ax=explode('FechaTimbrado="', $timbrado);
	$ax=explode('"', $ax[1]);
	$pdf->celpos(16.2-0.4, 4.8, 4.4, 8, $ax[0],0,"C");	
	$pdf->celpos(16.2-0.4, 5.6, 4.3, 8, "M�xico, DF ".$fecha_emision,0,"C");	
	
	
	if($paginaMax == 1)
	{
		$pdf->celpos(16.6, 19.3, 3.8, 8, "$ ".$subtotal,0,"R");	
		//$pdf->celpos(16.6, 20-0.36, 3.8, 8, "$ ".$ieps,0,"R");	
		$iva = $row['iva1'];
		$pdf->celpos(16.6, 20.39-0.36, 3.8, 8, "$ ".$iva,0,"R");	 //iva
		$retencion = $row['retencion_iva'];
		//$pdf->celpos(16.6, 20.8-.35, 3.8, 8, "$ ".$retencion,0,"R");	 //retencion iva
		$retencion_isr = $row['retencion_isr'];
		//$pdf->celpos(16.6, 20.8+.05, 3.8, 8, "$ ".$retencion_isr,0,"R");	 //retencion isr
		$total = $row['total'];
		$pdf->celpos(16.6, 21.23, 3.8, 8, "$ ".$total,0,"R");	 //total
	}
		
	$pdf->SetTextColor(255,255,255);
		
	$pdf->SetTextColor(0,0,0);
	
	$aux="||";
	$ax=explode('version="', $timbrado);	
	$ax=explode('"', $ax[1]);
	$aux.=$ax[0]."|";
	$ax=explode('UUID="', $timbrado);	
	$ax=explode('"', $ax[1]);
	$uuid=$ax[0];
	$aux.=$uuid."|";
	$ax=explode('FechaTimbrado="', $timbrado);	
	$ax=explode('"', $ax[1]);
	$aux.=$ax[0]."|";
	$ax=explode('selloCFD="', $timbrado);	
	$ax=explode('"', $ax[1]);
	$aux.=$ax[0]."||";
	$pdf->celpos(0.8, 22.4, 20, 6, $aux,0 ,"L");	
	$pdf->celpos(4.2, 24, 16.5, 6, $sello, 0, "L");	
	$ax=explode('selloSAT="', $timbrado);	
	$ax=explode('"', $ax[1]);
	$pdf->celpos(4.2, 25.6, 16.5, 6, $ax[0],0,"L");
	
/*	if(strlen($observaciones) > 70){  
	   $cadena = cortarTexto($observaciones);
	   $cadena1 = explode("-",$cadena); 
	   $observaciones1 = $cadena1[0];
	   $observaciones2 = $cadena1[1];
	}
	else{
	   $observaciones1 = $observaciones;
	   $observaciones2 = '';
	} */
	if($paginaMax ==1)
	{
		$pdf->celpos(1, 27.5, 16.5, 7, "ESTA ES UNA REPRESENTACI�N IMPRESA DE UN CFDI",0,"L");
		$pdf->celpos(17, 27.5, 16.5, 7, "EFECTOS FISCALES AL PAGO",0,"L");
		$pdf->celpos(3.8, 19.8, 15, 8, "PAGO EN UNA SOLA EXHIBICI�N",0,"L");
		$pdf->celpos(3.8, 20.2, 11, 7, $observaciones,0,"L");
		//$pdf->celpos(3.8, 20.6, 15, 7, $observaciones2,0,"L");
		
		//obtenemos la cantidad en letra	
		if($moneda=='1')
			$letra= num2texto($tot, 'PESOS', 'PESO');
		else
			$letra= num2texto($tot, 'DOLARES', 'DOLAR');
	//$letra= "123456789101234567892124567899312345678911234567891234567899123456789123 1236541 123456789 132456789 123456977";
	
		$pdf->celpos(3.8, 21, 10, 8,$letra,0,"L");
	
	}
	//$total = $row['tot'];
	
	
	$qr = new qrcode();
	$qr->text("?re=$rfc&rr=$rfc_r&tt=$total&id=$uuid");
	
	$ar=fopen($rutaImagen . "FAC_$folio.png", "wb");
	fwrite($ar, $qr->get_image());
	fclose($ar);
	
	
	
	
	$pdf->image($rutaImagen . "FAC_$folio.png", 0.8, 23.2, 3.3);
	
	$sql="
				SELECT
				fd.cantidad,
				un.nombre AS unidad,
				p.nombre,
				fd.descripcion,
				FORMAT(fd.valor_unitario,2) AS valor_unitario,
				FORMAT(fd.valor_unitario*fd.cantidad,2) as importe
				,1
				FROM ".$tabla_detalle." fd
				LEFT JOIN cl_productos_servicios p ON fd.id_producto = p.id_producto_servicio
				LEFT JOIN cl_clasificacion_productos clp ON clp.id_clasificacion_producto = p.id_clasificacion
				LEFT JOIN scfdi_unidades un ON un.id_unidad = clp.id_unidad
				WHERE fd.id_control_factura = '$doc'";	
	
	

	
	
	$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
	$num=mysql_num_rows($res);
		
	$intContador=0;
	$j=-1;
		
	
	
	//HACEMOS UN ESTIMADO PARA SABER CUANTAS PAGINAS SERAN
	$pag=1;
	for($i=0;$i<$num;$i++)
	{
		$rowDetalle = mysql_fetch_assoc($resDetalle);
		if($intContador > 30-14)
		{
			$intContador=0;
			$pag=$pag+1;
		}
		$lineasProd = round(strlen($rowDetalle['descripcion']) / 66, 0);
		$ymas_linea = $ymas_linea + $lineasProd * 0.22;
		$intContador= $intContador+1 + round($lineasProd * 0.7);
	}
	$paginaMax = $pag;
	$paginas= $pag; //ceil($num / 30);
	
	
	$intContador=0;
	$j=-1;
	$pag=1;
	if($paginas>1)
		$pdf->celpos(16.2, 0.5, 4.4, 9, "Pag. 1 de ".$paginas,0,"R");	
	
	$ymas_linea = 0;
	for($i=0;$i<$num;$i++)
	{
		//vamos tomando el count de los registros hata		
		
		if($intContador > 30-14)
		{
			//agregamos toda la pagina
			$intContador=0;
			$ymas_linea = 0;
			$pag=$pag+1;
			//agragamos una nueva hoja
			$pdf->AddPage();
			
			
			
			if($paginaMax == $pag)
			{
				if(file_exists("factura.jpg"))
					$pdf->image("factura.jpg", 0, 0, 21.5);
				else
					$pdf->image($ruta . "../pdf/factura.jpg", 0, 0, 21.5);
			}
			else
			{
				/*if(file_exists("factura_sintotal.jpg"))
					$pdf->image("factura_sintotal.jpg", 0, 0, 21.5);
				else
					$pdf->image($ruta . "../pdf/factura_sintotal.jpg", 0, 0, 21.5);*/
					
					if(file_exists("factura.jpg"))
					$pdf->image("factura.jpg", 0, 0, 21.5);
				else
					$pdf->image($ruta . "../pdf/factura.jpg", 0, 0, 21.5);
	
			}
			
			
			if(file_exists(	$logo_sucursales))
					$pdf->image($logo_sucursales, 15.8,0.7,5,2.9);
			
			
			$pdf->SetTextColor(0,0,0);
			
			
			//logo 
			$id_compania = $row['compania'];

			//Informacion de la compa�ia
			$pdf->celpos(0.8, 0.9, 20, 10, $razon_social,0,"L");	
			$pdf->celpos(0.8, 0.9, 20, 10, $razon_social,0,"L");	
			$aux=$calle;
			if($no_interior)
				$aux.=" No. ".$no_interior;
			if($no_exterior)
				$aux.=" Ext. ".$no_exterior;	
			if($colonia)
				$aux.=" ,".$colonia;	
			$pdf->celpos(0.8, 1.3, 20, 8, $aux,0,"L");	

			$aux="C.P. ".$cp." M�xico";
			
			if($estado)
				$aux.=" ,".$estado;		
			
			if($id_compania==14){
			
				if($delegacion_municipio)
					$aux11=$delegacion_municipio;	
				
			}
			else
			{
				if($delegacion_municipio)
					$aux.=" ,".$delegacion_municipio;	
			
			}	
			$pdf->celpos(0.8, 1.6, 20, 8, $aux,0,"L");	
			
			//excepcion especial para el cliente colonia no cabe en un solo renglon
			if($id_compania==14)
				$pdf->celpos(0.8, 1.9, 20, 8, $aux11."     RFC: ".$rfc,0,"L");		/*Receptor del comprobante*/
			else
				$pdf->celpos(0.8, 1.9, 20, 8, "RFC: ".$rfc,0,"L");		/*Receptor del comprobante*/
			 
			
			$pdf->celpos(0.8, 2.25, 20, 8, 'REGIMEN: ' . $regimen);
			
	
			
	
						$pdf->celpos(0.8, 2.7, 7.6, 8, "Sucursal:",0,"L");	
						$pdf->celpos(0.8, 2.7, 7.6, 8, "Sucursal:",0,"L");	
						$auxSuc=$calle_sucursal;
						if($no_ext_sucursal)
							$auxSuc.=" Ext. ".$no_ext_sucursal;
						if($no_int_sucursal)
							$auxSuc.=" Int ".$no_int_sucursal;	
						if($colonia_sucursal)
							$auxSuc.=" ,".$colonia_sucursal;	

						$pdf->celpos(0.8, 3, 20, 7, $auxSuc,0,"L");	

						$auxSuc="C.P. ".$cp_sucursal." M�xico";		
						if($estado_sucursal)
							$auxSuc.=" ,".$estado_sucursal;		
						if($delegacion_sucursal)
							$auxSuc.=" ,".$delegacion_sucursal;			
						$pdf->celpos(0.8, 3.3, 20, 7, $auxSuc,0,"L");
					
			//FIN BLOQUE DE SUCURSAL
			
			
			//INICIO BLOQUE RECEPTOR
				$pdf->celpos(0.8, 3.8, 20, 10, $razon_social_r,0,"L");	
				$pdf->celpos(0.8, 3.8, 20, 10, $razon_social_r,0,"L");
				$pdf->celpos(0.8, 3.8, 20, 10, $razon_social_r,0,"L");
				
				$aux=$calle_r;
				if($no_exterior_r)
					$aux.=" Ext. ".$no_exterior_r;	
				if($no_interior_r)
					$aux.=" No. ".$no_interior_r;
				if($colonia_r)
					$aux.=" ,".$colonia_r;	
				$pdf->celpos(0.8, 4.2, 20, 8, substr($aux, 0, 87), 0,"L");
				
				$aux= ltrim(substr($aux, 87) . " C.P. ".$cp_r." M�xico");
				if($estado_r)
					$aux.=" ,".$estado_r;		
				if($delegacion_municipio_r)
					$aux.=" ,".$delegacion_municipio_r;			
				$pdf->celpos(0.8, 4.5, 20, 8, $aux,0,"L");	
				$pdf->celpos(0.8, 4.8, 20, 8, "RFC: ".$rfc_r,0,"L");	 
				
				$pdf->celpos(0.8, 5.1, 20, 8,"METODO DE PAGO: ".$metodo_pago." CUENTA: ".$cuenta,0,"L");
			//FIN BLOQUE RECEPTOR
			
			
			
			//INICIO DATOS FISCALES
				$ax=explode('noCertificadoSAT="', $timbrado);	
				$ax=explode('"', $ax[1]);
				$pdf->celpos(0.8, 5.6, 16, 8,"Folio Fiscal", 0, "L");
				$pdf->celpos(0.8, 5.9, 16, 8,"$folio_cfd", 0, "L");
				
				$pdf->celpos(7.2, 5.6, 16, 8," No. Certificado Digital", 0, "L");
				$pdf->celpos(7.2, 5.9, 16, 8," $no_certificado", 0, "L");
				
				$pdf->celpos(11, 5.6, 16, 8," No. Serie Certificado SAT", 0, "L");
				$pdf->celpos(11, 5.9, 16, 8," " . $ax[0], 0, "L");
			//FIN DATOS FISCALES
			
			
			//montos X
			$pdf->SetTextColor(255,255,255);
			$pdf->celpos(16.2-.8, 3.8+.02, 4.4, 9, $tipo_cfd . " $folio",0,"C");	
				
			$pdf->SetTextColor(0,0,0);
			$ax=explode('FechaTimbrado="',$timbrado);
			$ax=explode('"', $ax[1]);
			$pdf->celpos(16.2-0.4, 4.8, 4.4, 8, $ax[0],0,"C");	
			$pdf->celpos(16.2-0.4, 5.6, 4.3, 8, "M�xico, DF ".$fecha_emision,0,"C");	
			
			
			if($paginaMax == $pag)
			{
				$pdf->celpos(16.6, 19.3, 3.8, 8, "$ ".$subtotal,0,"R");	
				//$pdf->celpos(16.6, 20-0.36, 3.8, 8, "$ ".$ieps,0,"R");	
				$iva = $iva1;
				$pdf->celpos(16.6, 20.40-0.36, 3.8, 8, "$ ".$iva,0,"R");	 //iva
				$retencion = $retencion_iva;
				//$pdf->celpos(16.6, 20.8-.35, 3.8, 8, "$ ".$retencion,0,"R");	 //retencion iva
				//$retencion_isr = $retencion_isr;
				//$pdf->celpos(16.6, 20.8+.05, 3.8, 8, "$ ".$retencion_isr,0,"R");	 //retencion isr
				$total = $total;
				$pdf->celpos(16.6, 21.23, 3.8, 8, "$ ".$total,0,"R");	 //total
			}
			
			$j=0;	
						
			$pdf->SetTextColor(0,0,0);
			
			$aux="||";
			$ax=explode('version="', $timbrado);	
			$ax=explode('"', $ax[1]);
			$aux.=$ax[0]."|";
			$ax=explode('UUID="', $timbrado);	
			$ax=explode('"', $ax[1]);
			$uuid=$ax[0];
			$aux.=$uuid."|";
			$ax=explode('FechaTimbrado="', $timbrado);	
			$ax=explode('"', $ax[1]);
			$aux.=$ax[0]."|";
			$ax=explode('selloCFD="', $timbrado);	
			$ax=explode('"', $ax[1]);
			$aux.=$ax[0]."||";
			$pdf->celpos(0.8, 22.4, 20, 6, $aux,0 ,"L");	
			$pdf->celpos(4.2, 24, 16.5, 6, $sello, 0, "L");	
			$ax=explode('selloSAT="', $timbrado);	
			$ax=explode('"', $ax[1]);
			$pdf->celpos(4.2, 25.6, 16.5, 6, $ax[0],0,"L");
			/*
			if(strlen($observaciones) > 70){  
			   $cadena = cortarTexto($observaciones);
			   $cadena1 = explode("-",$cadena); 
			   $observaciones1 = $cadena1[0];
			   $observaciones2 = $cadena1[1];
			}
			else{
			   $observaciones1 = $observaciones;
			   $observaciones2 = '';
			} */
			
			$pdf->celpos(1, 27.5, 16.5, 7, "ESTA ES UNA REPRESENTACI�N IMPRESA DE UN CFDI",0,"L");
			$pdf->celpos(17, 27.5, 16.5, 7, "EFECTOS FISCALES AL PAGO",0,"L");
			
			if($paginaMax == $pag)
			{
				$pdf->celpos(3.8, 19.8, 15, 8, "PAGO EN UNA SOLA EXHIBICI�N",0,"L");
				$pdf->celpos(3.8, 20.2, 11, 7, $observaciones,0,"L");
				//$pdf->celpos(3.8, 20.6, 15, 7, $observaciones2,0,"L");
			}
			
			//$total = $tot;
			if($paginaMax == $pag)
			{
				if($moneda=='1')
					$letra= num2texto($tot, 'PESOS', 'PESO');
				else
					$letra= num2texto($tot, 'DOLARES', 'DOLAR');
			
				$pdf->celpos(3.8, 21, 10, 8,$letra,0,"L");
			
			}
			
			/*$cadenaCodigoBarras = "?re=LOAD850511SX3&rr=LOAD850511SX3&tt=0000000123.123456&id=ad662d33-6934-459c-a128-BDf0393f0f44";
			QRcode::png($cadenaCodigoBarras, 'test.png', 'L', 4, 2);*/
			
			
			$qr = new qrcode();
			$qr->text("?re=$rfc&rr=$rfc_r&tt=$total&id=$uuid");
			
			
			$ar=fopen($rutaImagen . "FAC_$folio.png", "wb");
			fwrite($ar, $qr->get_image());
			fclose($ar);
			
			
			$pdf->image($rutaImagen . "FAC_$folio.png", 0.8, 23.2, 3.3);
	
			$pdf->celpos(16.2, 0.5, 4.4, 9, "Pag. ".$pag." de ".$paginas,0,"R");	
			
			/**/
			
		}
		else
		{
			$j=$j+1;	
		}
		
			
		$row=mysql_fetch_row($res);
		$descProd = "" . $row[3];
		$ymas=$j*0.6;
		$pdf->celpos(0.8, 7.0 + $ymas + $ymas_linea, 2, 8, $row[0], 0, "C");	
		$pdf->celpos(2.8, 7.0+$ymas + $ymas_linea, 2.6, 8, $row[1], 0, "C");	
		$pdf->celpos(5.6, 7.0+$ymas + $ymas_linea, 8.8, 8, $row[2] . "\n" . $descProd, 0, "L");	
		$pdf->celpos(14.4, 7.0+$ymas + $ymas_linea, 3, 8, "$ ".$row[4], 0, "R");	
		$pdf->celpos(17.4, 7.0+$ymas + $ymas_linea, 3, 8, "$ ".$row[5], 0, "R");	
		
		$lineasProd = round(strlen($descProd) / 66, 0);
		$ymas_linea = $ymas_linea + $lineasProd * 0.22;
		$intContador= $intContador+1 + round($lineasProd * 0.7);
	}
	
	
	
	
?>
