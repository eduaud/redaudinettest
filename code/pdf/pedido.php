<?php

	if(isset($doc))
	{
		$sql="
			SELECT id_control_pedido,
			id_pedido,
			rac_pedidos.id_vendedor,
			CONCAT(vend_1.nombre,' ',vend_1.apellido_paterno,' ',vend_1.apellido_materno) as nombre_vendor,
			CONCAT(vend_2.nombre,' ',vend_2.apellido_paterno,' ',vend_2.apellido_materno) as nombre_vendor2,
			id_tipo_pedido,
			estatus_pedido, 
			DATE_FORMAT(fecha_hora_creacion, '%d/%m/%Y %H:%m:%s') as fec_creacion, 
			rac_clientes.id_cliente AS id_cliente_pedido,
			id_tipo_cliente,
			IF(id_cliente_directo = 0, rac_clientes.id_cliente, id_cliente_directo) id_cliente_directo,
			rac_clientes.nombre as nombre_cliente,
			rac_clientes.nombre_comercial as nombre_cliente_comercial,
			rac_clientes.email,
			telefono_1,
			telefono_2,
			numero_personas,
			dias_evento,
			factor_costo_total_producto,
			DATE_FORMAT(fecha_evento, '%d/%m/%Y') as fec_evento, 
			hora_evento,
			t1.nombre as hora_evento_nombre,
			DATE_FORMAT(fecha_entrega_articulos, '%d/%m/%Y') as fec_entrega,
			hora_entrega,
			hora_entrega2,
			t2.nombre as hora_entrega_nombre,
			t3.nombre as hora_entrega2_nombre,
			DATE_FORMAT(fecha_recoleccion, '%d/%m/%Y') as fec_recolec,
			t4.nombre as hora_recoleccion1_nombre,
			t5.nombre as hora_recoleccion2_nombre,
			hora_recoleccion,
			hora_recoleccion2,
			direccion_evento,
			observaciones_generales,
			tipo_entrega_cliente, 
			direccion_entrega,
			tipo_regreso_almacen, 
			direccion_recoleccion,
			persona_recibe,
			persona_entrega,
			
			rac_pedidos.id_tipo_evento,
			rac_tipos_eventos.descripcion as nombre_tipo_evento,
			requiere_presupuesto_flete, 
			requiere_presupuesto_motaje, 
			requiere_presupuesto_viaticos,
			monto_flete_registrado_logistica,
			
			IF(ISNULL(monto_flete_gerente_ventas), 0, monto_flete_gerente_ventas) monto_flete_gerente_ventas, 
			IF(ISNULL(monto_montaje_gerente_ventas), 0, monto_montaje_gerente_ventas) monto_montaje_gerente_ventas, 
			IF(ISNULL(monto_montaje_extraorinario_gerente_ventas), 0, monto_montaje_extraorinario_gerente_ventas) monto_montaje_extraorinario_gerente_ventas, 
			IF(ISNULL(monto_desmontaje_gerente_ventas), 0, monto_desmontaje_gerente_ventas) monto_desmontaje_gerente_ventas, 
			IF(ISNULL(monto_viaticos_gerente_ventas), 0, monto_viaticos_gerente_ventas) monto_viaticos_gerente_ventas, 
							
			id_almacen_recoge,
			monto_pagare, 
			
			id_tipo_descuento,
			
			porcentaje_descuento_subtotal,
			id_tipo_documento,
			
			descuento_tipo_cliente, 
			monto_descuento_adicional,
			
			imagen_lugar,
			subtotal_articulos,
			subtotal_otros,
			subtotal,
			iva, 
			total,
			
			mostrar_leyenda_anticipo_pago,
			porcentaje_anticipo,
			id_almacen_entrega,
			id_control_pedido_montaje,
			no_modificable
			FROM rac_pedidos
			left join rac_vendedores  as vend_1 on vend_1.id_vendedor=rac_pedidos.id_vendedor
			left join rac_vendedores  as vend_2 on vend_2.id_vendedor=rac_pedidos.id_vendedor_registro_pedido
			left join rac_clientes on rac_clientes.id_cliente=rac_pedidos.id_cliente
			left join rac_tiempos t1 on t1.id_tiempo=hora_evento
			left join rac_tiempos t2 on t2.id_tiempo=hora_entrega
			left join rac_tiempos t3 on t3.id_tiempo=hora_entrega2
			left join rac_tiempos t4 on t4.id_tiempo=hora_recoleccion
			left join rac_tiempos t5 on t5.id_tiempo=hora_recoleccion2
			left join rac_tipos_eventos on rac_tipos_eventos.id_tipo_evento=rac_pedidos.id_tipo_evento
			where id_control_pedido=" .$doc."
		";		
		$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
		$row=mysql_fetch_assoc($res);
		extract($row);
		
		$sqlIva = "
			SELECT porcentaje_iva
			FROM sys_parametros_configuracion WHERE id_parametro = 1
		";
		$resIva = mysql_query($sqlIva) or die("error en: $sql<br><br>".mysql_error());	  
		$rowIva = mysql_fetch_assoc($resIva);
		extract($rowIva);		
		
		//validamos el tipo de cliente
		if($id_tipo_cliente==1)
		{
			//vemos quien es su cliente directo si es que lo tiene
			if($id_cliente_pedido!=$id_cliente_directo && $id_cliente_directo!='' && $id_cliente_directo!='0')
			{
				//si el cliente directo 				
				//obtenemos los datos del cliente directo
					$strClienteDirecto="SELECT  CONCAT(nombre,'  - ', nombre_comercial) nombre_cliente_directo FROM rac_clientes WHERE id_cliente=".$id_cliente_directo;
					$resClienteDirecto=mysql_query($strClienteDirecto) or die("error en: $strClienteDirecto<br><br>".mysql_error());	  
					$rowClienteDirecto=mysql_fetch_assoc($resClienteDirecto);
					extract($rowClienteDirecto);
				
			}
		}
		
		if($persona_recibe != '0')
		{
			$strContacto="
				SELECT id_detalle as id_detalle_contacto,
				rac_clientes_detalle_contactos.nombre as nombre_contacto,
				apellidos as apellido_contacto, 
				cargo as cargo_contacto, 
				rac_tipos_contactos.descripcion as nombre_tipo_contacto,
				telefono1 as telefono1_contacto, 
				telefono2 as telefono2_contacto, 
				celular as celular_contacto, 
				email as mail_contacto
				FROM rac_clientes_detalle_contactos 
				left join rac_tipos_contactos on 
				rac_tipos_contactos.id_tipo_contacto=rac_clientes_detalle_contactos.id_tipo_contacto
				WHERE rac_clientes_detalle_contactos.id_detalle = " .$persona_recibe."
			";								
			$resContacto=mysql_query($strContacto) or die("error en: $sql<br><br>".mysql_error());	  
			$rowContacto=mysql_fetch_assoc($resContacto);
			if(is_array($rowContacto))
				extract($rowContacto);
		}
		
		if($direccion_evento != 0)
		{
			$strDireccionEvento="
				SELECT id_detalle as id_detalle_evento, 
				rac_eventos_tipos_lugares.nombre as tipo_lugar_evento_evento,
				nombre_lugar as nombre_lugar_evento , 
				calle as calle_evento,
				numero_exterior as numero_exterior_evento , 
				numero_interior as numero_interior_evento ,
				colonia as colonia_evento,
				rac_ciudad.nombre as nombre_ciudad_evento,
				delegacion_municipio as delegacion_municipio_evento  , 
				codigo_postal as codigo_postal_evento, 
				telefono1 as telefono1_evento, 
				telefono2 as telefono2_evento, 
				celular as celular_evento, 
				referencias as referencias_evento,
				web as web_evento,
				email as email_evento,
				tiempo_traslado as tiempo_traslado_evento, 
				google_maps as google_maps_evento, 
				comentarios as comentarios_evento
				FROM rac_clientes_detalle_direcciones
				left join rac_eventos_tipos_lugares on 
				rac_clientes_detalle_direcciones.id_tipo_lugar_evento=rac_eventos_tipos_lugares.id_tipo_lugar_evento
				left join sys_si_no on id_si_no=es_lugar_evento
				left join rac_ciudad on  rac_ciudad.id_ciudad=rac_clientes_detalle_direcciones.id_ciudad
				WHERE rac_clientes_detalle_direcciones.id_detalle = ".$direccion_evento."
			";
			$resDirEvento=mysql_query($strDireccionEvento) or die("error en: $sql<br><br>".mysql_error());	  
			$rowResEvento=mysql_fetch_assoc($resDirEvento);
			if(is_array($rowResEvento))
				extract($rowResEvento);
			
			if($direccion_entrega != $direccion_evento)
			{
				$strDireccionEntrega="
					SELECT id_detalle as id_detalle_entrega ,
					rac_eventos_tipos_lugares.nombre as  tipo_lugar_evento_entrega,
					nombre_lugar as nombre_lugar_entrega, 
					calle as calle_entrega, 
					numero_exterior as numero_exterior_entrega, 
					numero_interior as numero_interior_entrega, 
					colonia as colonia_entrega,
					rac_ciudad.nombre  as nombre_ciudad_entrega,
					delegacion_municipio as  delegacion_municipio_entrega,
					codigo_postal as codigo_postal_entrega, 
					telefono1 as telefono1_entrega, 
					telefono2 as telefono2_entrega, 
					celular as celular_entrega, 
					referencias as referencias_entrega, 
					web as web_entrega,
					email as email_entrega ,
					tiempo_traslado as tiempo_traslado_entrega, 
					google_maps as google_maps_entrega,
					comentarios as comentarios_entreta	
					FROM rac_clientes_detalle_direcciones
					left join rac_eventos_tipos_lugares 
					on rac_clientes_detalle_direcciones.id_tipo_lugar_evento = rac_eventos_tipos_lugares.id_tipo_lugar_evento
					left join sys_si_no on id_si_no=es_lugar_evento
					left join rac_ciudad on  rac_ciudad.id_ciudad=rac_clientes_detalle_direcciones.id_ciudad
					WHERE rac_clientes_detalle_direcciones.id_detalle = ".$direccion_entrega."
				";
							
				$resDirEntrega=mysql_query($strDireccionEntrega) or die("error en: $strDireccionEntrega<br><br>".mysql_error());	  
				$rowResEntrega=mysql_fetch_assoc($resDirEntrega);
				extract($rowResEntrega);
			}
		}
	}
	
	
	
	
	class PDF_Footer extends PDF
	{
		//Pie de página
		function Footer()
		{
			$this->SetY(-0.5);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,0,'Pagina '.$this->PageNo().'/{nb}',0,0,'R');
		}
	}
	
	$pdf=new PDF_Footer($orient_doc,$unid_doc,$tamano_doc);
	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',$ftam);
	$pdf->SetAutoPageBreak(false);
	
	//file x y w h tipe link
	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "logo_sup_iz.jpg", 0.5, 1.9,2.5);
	//rentandcompany
	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "logo_sup_der.jpg", 15.0, 0.5, 0);

	//$id_tipo_cliente = 2;
	//$descuento_tipo_cliente = 0;
	
	$imagenFondo = "";
	if($id_tipo_cliente == 1)
	{
		$imagenFondo = $_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "detalleMayorista.jpg";
		//$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "detalleMayorista.jpg", 0.40, 6.5,0,0);
	}
	else if($id_tipo_cliente != 1 && $descuento_tipo_cliente != 0)
	{
		$imagenFondo = $_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "detalleCliente.jpg";
		//$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "detalleCliente.jpg", 0.40, 6.5,0,0);
	}
	else
	{
		$imagenFondo = $_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "detalleClienteSinDesc.jpg";
		//$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "detalleClienteSinDesc.jpg", 0.40, 6.5,0,0);
	}
	$pdf->image($imagenFondo, 0.40, 6.5,0,0);

	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "direccion.jpg",2.2, 26.5, 0,1.2);	
	$pdf->SetTextColor(0,0,0);
	
	//Informacion de la compañia
	$pdf->celpos(0.6, 0.55, 0, 23,'Solicitud de cotización # '. $id_pedido,0,"L");	
	
	$inty=1.7;
	$intmas=0.5;
	$tamFont=9;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Fecha: ".$fec_creacion,0,"L");
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Fecha: ",0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Cliente: ". $nombre_cliente,0,"L");
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Cliente: ",0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Contacto: ". $nombre_contacto." ".$apellido_contacto,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"E-mail: ". $mail_contacto,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Tel(s): ". $telefono1_contacto." ".$telefono2_contacto,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont, "Num de personas: ".$numero_personas,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(0.5, $inty, 0, $tamFont, "Lugar y dirección del evento: ",0,"L");
	$pdf->celpos(0.5, $inty, 0, $tamFont, "Lugar y dirección del evento: ",0,"L");
	$inty=$inty+$intmas;
	$intIcol1=$inty;
	
	//LUGAR DEL EVENTO CONCATENADO CON CALLE, NUMERO Y COLONIA
	$aux1 = $nombre_lugar_evento;
	$aux1 .= "   Calle: ".$calle_evento;
	if($numero_exterior_evento != '')
		$aux1 .= "   # Exterior: ".$numero_exterior_evento;
	if($numero_interior_evento != '' && $numero_interior_evento != '0' && $numero_interior_evento != '-')
		$aux1 .= "   # Interior: ".$numero_interior_evento;
	$aux1 .= "   Colonia: ".$colonia_evento;
	$pdf->celpos(0.5, $inty, 0, $tamFont, $aux1,0,"L");
	
		
	$inty=$inty+$intmas;	
	
	$aux1 = "Del./Mun.: ".$delegacion_municipio_evento;
	$aux1 .= ", ".$nombre_ciudad_evento;
	$aux1 .= "   C.P.: ".$codigo_postal_evento;
	$aux1 .= "   Teléfono(s) : ".$telefono1_evento. " . ".$telefono2_evento ;
	$pdf->celpos(0.5, $inty, 0, $tamFont, $aux1,0,"L");
	
	
	
	
	
	
			
	$pdf->celpos(13.5, 2.0, 0, 8,"Fecha del evento:    ". $fec_evento,0,"L");	
	
	$auxHEe=" Hr: ".$hora_evento_nombre;
	if($hora_entrega != $hora_entrega2)
	{
		$auxHEe .="  a  ".$hora_entrega2_nombre;
	}
	$pdf->celpos(13.5,2.5, 0, 8,"Día de entrega:        ". $fec_entrega." ".$auxHEe,0,"L");	
	
	$auxHRec=" Hr: ".$hora_recoleccion1_nombre;
	if($hora_recoleccion != $hora_recoleccion2)
	{
		$auxHRec .="  a  ".$hora_recoleccion2_nombre;
	}
	$pdf->celpos(13.5,3, 0, 8,"Día de recolección:  ". $fec_recolec." ".$auxHRec,0,"L");	
	$pdf->celpos(13.5,3.5, 0, 8,"Tipo de Evento :  ". $nombre_tipo_evento,0,"L");
	if($nombre_cliente_directo != '')
		$pdf->celpos(13.5,4, 0, 8,"Cliente :  ". $nombre_cliente_directo,0,"L");
	
	
	if($no_modificable == 0)
	{
		$observaciones = "Los productos listados aun no han sido reservados. " . $observaciones;
	}
	

	$pdf->celpos(0.1, 21.6, 3, 8, "Observaciones",0,"C");
	if(strlen($observaciones) > 70){  
	   $cadena = cortarTexto($observaciones);
	   $cadena1 = explode("-",$cadena); 
	   $observaciones1 = $cadena1[0];
	   $observaciones2 = $cadena1[1];
	   
	   $pdf->celpos(0.6, 22.0, 50, 8, $observaciones1,0,"L");
	   $pdf->celpos(0.6, 22.4, 50, 8, $observaciones2,0,"L");
	}
	else{
	   $observaciones1 = $observaciones;
	   $observaciones2 = '';
	   $pdf->celpos(0.6, 22.0, 50, 8, $observaciones1,0,"L");
	} 

	
	
	
	
	if($id_tipo_cliente == 1)
	{
		$valorY = 21.56;
		$pdf->celpos(11.0, $valorY, 2, 8, "Subtotal arts.",0,"L");
		$pdf->celpos(16.9, $valorY, 2, 8, "Subtotal arts.",0,"L");
		$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format($subtotal_articulos + $descuento_tipo_cliente + $monto_descuento_adicional, 2), 0,"R");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($subtotal_articulos, 2), 0,"R");
		
		$valorY += 0.40;
		
		if(($descuento_tipo_cliente + $monto_descuento_adicional) > 0)
		{
			$pdf->celpos(11.0, $valorY, 2, 8, "Descuento",0,"L");
			$pdf->celpos(16.9, $valorY, 2, 8, "Descuento",0,"L");
			$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format(0, 2), 0,"R");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($descuento_tipo_cliente + $monto_descuento_adicional, 2), 0,"R");			
			$valorY += 0.40;
		}	
		
		$pdf->celpos(11.0, $valorY, 2, 8, "Flete",0,"L");
		$pdf->celpos(16.9, $valorY, 2, 8, "Flete",0,"L");
		$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format($monto_flete_gerente_ventas,2), 0,"R");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_flete_gerente_ventas,2), 0,"R");
		$valorY += 0.40;
		
		if($monto_viaticos_gerente_ventas > 0)
		{
			$pdf->celpos(11.0, $valorY, 2, 8, "Viatico",0,"L");
			$pdf->celpos(16.9, $valorY, 2, 8, "Viatico",0,"L");
			$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format($monto_viaticos_gerente_ventas,2), 0,"R");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_viaticos_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
		}
		
		if($monto_montaje_gerente_ventas + $monto_montaje_extraorinario_gerente_ventas + $monto_desmontaje_gerente_ventas
		> 0)
		{
			$pdf->celpos(11.0, $valorY, 2, 8, "Montaje",0,"L");
			$pdf->celpos(16.9, $valorY, 2, 8, "Montaje",0,"L");
			$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format($monto_montaje_gerente_ventas + $monto_montaje_extraorinario_gerente_ventas + $monto_desmontaje_gerente_ventas,2), 0,"R");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_montaje_gerente_ventas + $monto_montaje_extraorinario_gerente_ventas + $monto_desmontaje_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
		}
		
			
		$pdf->celpos(11.0, $valorY, 2, 8, "Subtotal",0,"L");
		$pdf->celpos(16.9, $valorY, 2, 8, "Subtotal",0,"L");
		$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional,2), 0,"R");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($subtotal,2), 0,"R");
		$valorY += 0.40;
		
		if($id_tipo_documento != 2)
		{
			$pdf->celpos(11.0, $valorY, 2, 8, "IVA",0,"L");
			$pdf->celpos(16.9, $valorY, 2, 8, "IVA",0,"L");
			$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format(($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional) * $porcentaje_iva/100,2), 0,"R");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($iva,2), 0,"R");
			$valorY += 0.40;
			
			$pdf->celpos(11.0, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(16.9, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format(($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional) * (1+($porcentaje_iva/100)),2), 0,"R");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($total,2), 0,"R");
			$valorY += 0.40;
		}
		else
		{
			$pdf->celpos(11.0, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(16.9, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(12.8, $valorY, 2, 8, "$ " . number_format(($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional),2), 0,"R");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($total,2), 0,"R");
			$valorY += 0.40;
		}
	}
	else if($id_tipo_cliente != 1 && $descuento_tipo_cliente != 0)
	{
		$valorY = 21.56;
		$pdf->celpos(16.9, $valorY, 2, 8, "Subtotal arts.",0,"L");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($subtotal_articulos, 2), 0,"R");
		
		$valorY += 0.40;
		
		if(($descuento_tipo_cliente + $monto_descuento_adicional) > 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Descuento",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($descuento_tipo_cliente + $monto_descuento_adicional, 2), 0,"R");			
			$valorY += 0.40;
		}	
		
		$pdf->celpos(16.9, $valorY, 2, 8, "Flete",0,"L");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_flete_gerente_ventas,2), 0,"R");
		$valorY += 0.40;
		
		if($monto_viaticos_gerente_ventas > 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Viatico",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_viaticos_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
		}
		
		if($monto_montaje_gerente_ventas + $monto_montaje_extraorinario_gerente_ventas + $monto_desmontaje_gerente_ventas
		> 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Montaje",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_montaje_gerente_ventas + $monto_montaje_extraorinario_gerente_ventas + $monto_desmontaje_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
		}
		
			
		$pdf->celpos(16.9, $valorY, 2, 8, "Subtotal",0,"L");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($subtotal,2), 0,"R");
		$valorY += 0.40;
		
		if($id_tipo_documento != 2)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "IVA",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($iva,2), 0,"R");
			$valorY += 0.40;
			
			$pdf->celpos(16.9, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($total,2), 0,"R");
			$valorY += 0.40;
		}
		else
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($total,2), 0,"R");
			$valorY += 0.40;
		}
	}		
	else if($id_tipo_cliente != 1 && $descuento_tipo_cliente == 0)
	{
		$valorY = 21.56;
		$pdf->celpos(16.9, $valorY, 2, 8, "Subtotal arts.",0,"L");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($subtotal_articulos + $descuento_tipo_cliente + $monto_descuento_adicional, 2), 0,"R");
		$valorY += 0.40;
		
		if(($descuento_tipo_cliente + $monto_descuento_adicional) > 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Descuento",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format(0, 2), 0,"R");
			$valorY += 0.40;
		}	
		
		$pdf->celpos(16.9, $valorY, 2, 8, "Flete",0,"L");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_flete_gerente_ventas,2), 0,"R");
		$valorY += 0.40;
		
		if($monto_viaticos_gerente_ventas > 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Viatico",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_viaticos_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
		}
		
		if($monto_montaje_gerente_ventas > 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Montaje",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_montaje_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
		}
		
		if($monto_montaje_extraorinario_gerente_ventas > 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Montaje Extraordinario",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_montaje_extraorinario_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
			$valorY += 0.30;
		}
		
		if($monto_desmontaje_gerente_ventas > 0)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Desmontaje",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($monto_desmontaje_gerente_ventas,2), 0,"R");
			$valorY += 0.40;
			$valorY += 0.30;
		}
		
		$pdf->celpos(16.9, $valorY, 2, 8, "Subtotal",0,"L");
		$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional,2), 0,"R");
		$valorY += 0.40;
		
		if($id_tipo_documento != 2)
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "IVA",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format(($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional) * $porcentaje_iva/100,2), 0,"R");
			$valorY += 0.40;
			
			$pdf->celpos(16.9, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format(($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional) * (1+($porcentaje_iva/100)),2), 0,"R");
			$valorY += 0.40;
		}
		else
		{
			$pdf->celpos(16.9, $valorY, 2, 8, "Total",0,"L");
			$pdf->celpos(18.9, $valorY, 2, 8, "$ " . number_format(($subtotal + $descuento_tipo_cliente + $monto_descuento_adicional),2), 0,"R");
			$valorY += 0.40;
		}
	}
	
	
	$pdf->celpos(0.5, 26.2, 0, 9, "Lider de Proyecto: ".$nombre_vendor,0,"L");
	
	$pdf->celpos(12, 26.2, 0, 9, "Ejecutivo de Ventas: ".$nombre_vendor2,0,"L");
		  
	$sql=" 
		SELECT 
		a.cantidad_surtir, 
		b.nombre, 
		'' descuento, 
		a.precio_final, a.importe_final, 
		a.precio_unitario, a.precio_unitario*a.cantidad_surtir*a.factor_costo
		FROM rac_pedidos_detalle_articulos a
		LEFT JOIN rac_articulos b ON b.id_articulo = a.id_articulo 
		WHERE a.id_control_pedido = ".$doc ."
		AND a.cantidad_surtir > 0
		
		UNION
		
		SELECT 
		a.cantidad_surtir, 
		b.nombre, 
		'' descuento, 
		a.precio_final, a.importe_final, 
		a.precio_transformacion, a.precio_transformacion*a.cantidad_surtir*a.factor_costo
		FROM rac_pedidos_detalle_articulos_transformacion_basica a
		LEFT JOIN rac_articulos b ON b.id_articulo = a.id_articulo_final
		WHERE a.id_control_pedido = ".$doc ."
		AND a.cantidad_surtir > 0
		
		UNION
		
		SELECT 
		a.cantidad_surtir, 
		b.nombre, 
		'' descuento, 
		a.precio_final, a.importe_final, 
		a.precio_transformacion, a.precio_transformacion*a.cantidad_surtir*a.factor_costo
		FROM rac_pedidos_detalle_articulos_transformacion_especial a
		LEFT JOIN rac_articulos b ON b.id_articulo = a.articulo_final
		WHERE a.id_control_pedido = ".$doc ."
		AND a.cantidad_surtir > 0
		
		UNION
		
		SELECT 
		a.cantidad_solicitada, 
		b.nombre, 
		'' descuento, 
		a.precio_final, a.importe_final, 
		a.precio_produccion, a.precio_produccion*a.cantidad_solicitada*a.factor_costo
		FROM rac_pedidos_detalle_articulos_solicitado_produccion a
		LEFT JOIN rac_articulos b ON b.id_articulo = a.id_articulo 
		WHERE a.id_control_pedido = ".$doc ." 
		AND a.cantidad_solicitada > 0
		
		UNION
		
		SELECT 
		a.cantidad_solicitada, 
		b.nombre, 
		'' descuento, 
		a.precio_final, a.importe_final, 
		a.precio_unitario, a.precio_unitario*a.cantidad_solicitada*a.factor_costo
		FROM rac_pedidos_detalle_articulos_solicitado_compra a
		LEFT JOIN rac_articulos b ON b.id_articulo = a.id_articulo 
		WHERE a.id_control_pedido = ".$doc ."
		AND a.cantidad_solicitada > 0
	";
		  
	$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
	$num=mysql_num_rows($res);
	
	$intContador=0;
	$j=0.5;
	
	$paginas= ceil($num / 30);
	$pag=1;
	
	for($i=0;$i<$num;$i++)
	{
		//vamos tomando el counto de los registros hata		
		
		if($intContador >21)
		{
			//agregamos toda la pagina
			$intContador=0;
			$pag=$pag+1;
			//agragamos una nueva hoja
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$ftam);
			$pdf->SetAutoPageBreak(false);
			$pdf->image($imagenFondo, 0, 0, 21.5);
			$j=0;
		}
		else
		{
			$j=$j+1;	
		}
				
		$row=mysql_fetch_row($res);
		$ymas=$j*0.6;
		
		if($id_tipo_cliente == 1 || ($id_tipo_cliente != 1 && $descuento_tipo_cliente != 0))
		{
			$pdf->celpos(0.8, 6.8, 0, 9, "",0,"L");	//CANTIDAD
			$pdf->celpos(6, 6.8, 0, 9, "",0,"L");	//DESCRIPCION
			$pdf->celpos(11.4, 6.8, 0, 9, "",0,"L");	//DESC.
			$pdf->celpos(12.4, 6.7, 3, 8, "",0,"C");	//PRECIO MAYORISTA
			$pdf->celpos(15, 6.8, 0, 9, "",0,"L");	//SUBTOTAL
			$pdf->celpos(16.8, 6.7, 2, 9, "",0,"C");	//PRECIO CLIENTE
			$pdf->celpos(18.4, 6.8, 3, 9, "",0,"C");	//SUBTOTAL
			
			//cantidad_surtir
			$pdf->celpos(0.8, 7.0+$ymas, 2, 8, $row[0], 0, "C");	
			//nombre del articulo
			$pdf->celpos(3, 7.0+$ymas, 8, 8, substr($row[1], 0, 110), 0, "L");	
			//DESCUENTO
			$desLinea = number_format(abs((1 - ($row[4]/$row[6])) * 100), 2);	//SE QUITA EL 10% QUE SE DA POR SER MAYORISTA
			
			$pdf->celpos( 9.6, 7.0+$ymas, 3, 8, "$ ". number_format($row[5],2), 0, "R");	
			$pdf->celpos(11.8, 7.0+$ymas, 3, 8, "$ ". number_format($row[6],2), 0, "R");	
			$pdf->celpos(13.6, 7.0+$ymas, 5, 8, $desLinea . " %", 0, "C");
			$pdf->celpos(15.8, 7.0+$ymas, 3, 8, "$ ". number_format($row[3],2), 0, "R");
			$pdf->celpos(17.9, 7.0+$ymas, 3, 8, "$ ". number_format($row[4],2), 0, "R");
			
			$intContador= $intContador+1;
		}
		else
		{
			//cantidad_surtir
			$pdf->celpos(0.8, 7.0+$ymas, 2, 8, $row[0], 0, "C");	
			//nombre del articulo
			$pdf->celpos(3, 7.0+$ymas, 13, 8, $row[1], 0, "L");	
			//DESCUENTO
			$pdf->celpos(13.4, 7.0+$ymas, 5, 8, $row[2], 0, "C");	
			
			$pdf->celpos(15.7, 7.0+$ymas, 3, 8,"$ ". number_format($row[5],2), 0, "R");
			$pdf->celpos(17.9, 7.0+$ymas,3, 8,"$ ". number_format($row[6],2), 0, "R");
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	$itera = 0;
	$posX = 0;
	$posY = 0;
	$text_restricciones = array();
	
	$strDatos = "
		SELECT a.nombre, a.imagen_foto1, a.restricciones 
		FROM rac_articulos a
		LEFT JOIN rac_pedidos_detalle_articulos b ON a.id_articulo = b.id_articulo
		WHERE b.id_control_pedido = '$doc' AND b.cantidad_surtir > 0
		UNION
		SELECT a.nombre, a.imagen_foto1, a.restricciones 
		FROM rac_articulos a
		LEFT JOIN rac_pedidos_detalle_articulos_transformacion_basica b ON a.id_articulo = b.id_articulo_final
		WHERE b.id_control_pedido = '$doc' AND b.cantidad_surtir > 0
		UNION
		SELECT a.nombre, a.imagen_foto1, a.restricciones 
		FROM rac_articulos a
		LEFT JOIN rac_pedidos_detalle_articulos_transformacion_especial b ON a.id_articulo = b.articulo_final
		WHERE b.id_control_pedido = '$doc' AND b.cantidad_surtir > 0
		UNION
		SELECT a.nombre, a.imagen_foto1, a.restricciones 
		FROM rac_articulos a
		LEFT JOIN rac_pedidos_detalle_articulos_solicitado_produccion b ON a.id_articulo = b.id_articulo
		WHERE b.id_control_pedido = '$doc' AND b.cantidad_solicitada > 0
		UNION
		SELECT a.nombre, a.imagen_foto1, a.restricciones 
		FROM rac_articulos a
		LEFT JOIN rac_pedidos_detalle_articulos_solicitado_compra b ON a.id_articulo = b.id_articulo
		WHERE b.id_control_pedido = '$doc' AND b.cantidad_solicitada > 0
	";
	
	$resDatos = mysql_query($strDatos) or die("error en: $strDireccionEntrega<br><br>".mysql_error());	  
	while($rowDatos = mysql_fetch_assoc($resDatos))
	{
		if($rowDatos['restricciones'] != '')
			array_push($text_restricciones, $rowDatos['nombre'] . ": " .$rowDatos['restricciones']);
			
		if(($itera % 12) == 0 || $itera == 0)
		{
			$posY = 3;
			$pdf->AliasNbPages();
			$pdf->AddPage();
			$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "encabezado.jpg",0, 0, 0, 2.6);	
			//$pdf->celpos(1, .5, 100, 9, 'Imagenes de Artículos', 0, "L");
			//$pdf->celpos(1, .5, 100, 9, 'Imagenes de Artículos', 0, "L");
		}
		
		if($posX == 1)
		{
			$posX = 7.5;				
		}
		else if($posX == 7.5)
		{
			$posX = 14;				
		}
		else
		{
			$posX = 1;
		}
		
		if($rowDatos['imagen_foto1'] != "")
			$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/' . str_replace('../../', '', $rowDatos['imagen_foto1']), $posX, $posY, 6, 4.7);
		else
			$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/imagenes/producto_sin_foto.jpg', $posX, $posY, 6, 4.7);
		
		$pdf->celpos($posX, $posY + 5, 200, 6, $rowDatos['nombre'], 0, "L");
		$itera++;
		
		if($posX == 14)
			$posY += 6;
	}
	
	if($imagen_lugar != "")
	{
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->celpos(1, .5, 100, 9, 'Plano del Montaje', 0, "L");
		$pdf->celpos(1, .5, 100, 9, 'Plano del Montaje', 0, "L");
		$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/' . str_replace('../../', '', $imagen_lugar), 1, 1, 20, 0);
	}
	
	
	//RESTRICCIONES
	if(count($text_restricciones) != "")
	{
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->celpos(1, 1, 100, 9, 'Restricciones', 0, "L");
		$pdf->celpos(1, 1, 100, 9, 'Restricciones', 0, "L");
		
		$i = 0;
		foreach($text_restricciones as $id => $valor)
		{
			$pdf->celpos(1, 2 + $i, 100, 9, $valor, 0, "L");
			$i += 0.5;
		}
	}
	
	
	
	function cortarTexto($texto) {
    
	$tamanoOri = strlen($texto);
    $tamano = 70; // tamaño máximo
    $textoFinal1 = ''; 
	$textoFinal2 = ''; 
 
    if (strlen($texto) < $tamano) $tamano = strlen($texto);
 
    for ($i=0; $i <= $tamano - 1; $i++) { //inicio de cadena
        $textoFinal1 .= $texto[$i];
    }
	
	for ($i=($tamano - 1); $i <= $tamanoOri; $i++) { //final de cadena
        $textoFinal2 .= $texto[$i];
    }
	
    $cade = $textoFinal1.'-'.$textoFinal2;
    
	// devolvemos el texto final
    return $cade;
 
   }
	
?>