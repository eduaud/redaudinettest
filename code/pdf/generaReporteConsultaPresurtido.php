<?php
	

	if(isset($doc))
	{
		
	}
	
	
	$sqlOrdenServicio="SELECT id_control_orden_servicio,
															id_orden_servicio, 
															CONCAT(rac_clientes.nombre,'  ', nombre_comercial) as cliente,
															CONCAT(DATE_FORMAT(fecha_hora_creacion, '%d/%m/%Y') ,' ',rac_tiempos.nombre) as fecha_creacion,
															CONCAT(DATE_FORMAT(fecha_entrega_articulos, '%d/%m/%Y') ,' ',rac_tiempos.nombre) as fecha_entrega,
															CONCAT(DATE_FORMAT(fecha_recoleccion, '%d/%m/%Y') ,' ',rac_tiempos.nombre) as fecha_recoleccion,
															rac_clientes.id_cliente
													FROM rac_ordenes_servicio 
													LEFT JOIN rac_clientes ON rac_clientes.id_cliente=rac_ordenes_servicio.id_cliente 
													left join rac_tiempos on id_tiempo=hora_entrega
													where id_orden_servicio='".$id_orden_servicio."' 
													AND estatus_orden = 1";
													
	$res=mysql_query($sqlOrdenServicio) or die("error en: $sqlOrdenServicio<br><br>".mysql_error());	  
	$row=mysql_fetch_assoc($res);
	extract($row);
	
	

	$pdf=new PDF($orient_doc,$unid_doc,$tamano_doc);
	$pdf->AddPage();
	$pdf->SetFont('Helvetica','',$ftam);
	$pdf->SetAutoPageBreak(false);
	$pdf->SetRightMargin(.5);
	
	//file x y w h tipe link
	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "logo_sup_iz.jpg", 0.5, 1.9,2.5);
	//rentandcompany
	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "logo_sup_der.jpg", 15.0, 0.5, 0);

	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "detallePresurtido.jpg", 0.40, 6.5,0,0);

	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "direccion.jpg",2.2, 26.5, 0,1.2);
	
	$pdf->SetTextColor(0,0,0);
	
	//Informacion de la compañia
	$pdf->celpos(0.6, 0.55, 0, 23,'Artículos por Orden de Surtido: '. $id_control_orden_servicio,0,"L");	
	
	$inty=1.7;
	$intmas=0.5;
	$tamFont=9;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Fecha de realización: ".$fecha_creacion,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Fecha de entrega: ". $fecha_entrega,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Fecha de recolección: ". $fecha_recoleccion,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Cotización: ". $id_orden_servicio,0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Cliente: ". $cliente,0,"L");
	$inty=$inty+$intmas;
	$inty=$inty+$intmas;
	$intIcol1=$inty;
	
	/*
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
	if($nombre_cliente_directo!='')
		$pdf->celpos(13.5,4, 0, 8,"Cliente :  ". $nombre_cliente_directo,0,"L");
	
	*/
	
	
	$pdf->celpos(00.8, 7, 0, 9, "ARTÍCULO",0,"L");
	$pdf->celpos(00.8, 7, 0, 9, "ARTÍCULO",0,"L");		
	$pdf->celpos(03.4,7, 0, 9, "DESCRIPCIÓN",0,"L");	
	$pdf->celpos(03.4, 7, 0, 9, "DESCRIPCIÓN",0,"L");
	$pdf->celpos(06.6, 6.8, 0, 9, "CANTIDAD \nSOLICITADA",0,"L");	
	$pdf->celpos(06.6, 6.8, 0, 9, "CANTIDAD \nSOLICITADA",0,"L");	
	$pdf->celpos(09.4, 6.7, 0, 9, "CANTIDAD \nPENDIENTE \nPOR ENTREGAR",0,"L");
	$pdf->celpos(09.4, 6.7, 0, 9, "CANTIDAD \nPENDIENTE \nPOR ENTREGAR",0,"L");
	$pdf->celpos(12.6, 7, 0, 9, "EXISTENCIA",0,"L");
	$pdf->celpos(12.6, 7, 0, 9, "EXISTENCIA",0,"L");
	$pdf->Line(15, 6.6, 15,25.8);
	$pdf->celpos(15.6, 7, 0, 9, "ALMACEN",0,"L");
	$pdf->celpos(15.6, 7, 0, 9, "ALMACEN",0,"L");
	$pdf->celpos(18.1, 6.8, 0, 9, "CANTIDAD A SURTIR",0,"L");
	$pdf->celpos(18.1, 6.8, 0, 9, "CANTIDAD A SURTIR",0,"L");
	
	/*
	$pdf->celpos(0.1, 24, 3, 8, "Observaciones",0,"C");
	if(strlen($observaciones_generales) > 80){  
	   $cadena = cortarTexto($observaciones_generales);
	   $cadena1 = explode("-",$cadena); 
	   $observaciones1 = $cadena1[0];
	   $observaciones2 = $cadena1[1];
	}
	else{
	   $observaciones1 = $observaciones_generales;
	   $observaciones2 = '';
	} 

	
	$pdf->celpos(0.5, 26.2, 0, 9, "Lider de Proyecto: ".$nombre_vendor,0,"L");
	
	$pdf->celpos(12, 26.2, 0, 9, "Ejecutivo de Ventas: ".$nombre_vendor,0,"L");
	*/

	$sqlDetalleOrdenServicio=" 
		SELECT id_detalle,
						id_control_orden_servicio,
						id_articulo_det, 
						codigo_articulo, 
						nombre, 
						cantidad_solicitada,
						if(existencia is null,0,existencia), 
						if(cantidad_entregada is null,0,cantidad_entregada),
						cantidad_solicitada-if(cantidad_entregada is null,0,cantidad_entregada)
						as faltanteSurtir 
					FROM (
								SELECT id_detalle,
									id_control_orden_servicio,
									detalle.id_articulo as id_articulo_det,
									codigo_articulo,nombre,
									cantidad_solicitada,
										(SELECT sum(cantidad) 
											FROM na_movimientos_almacen_detalle
											left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
											where no_modificable =1 and id_almacen = 1 and id_articulo=detalle.id_articulo) 
										as existencia,
											(SELECT sum(cantidad) 
												FROM na_movimientos_almacen_detalle
													left join rac_movimientos_almacen on rac_movimientos_almacen_detalle.id_control_movimiento=rac_movimientos_almacen.id_control_movimiento
													where no_modificable =1 and id_almacen = 1 and id_articulo=detalle.id_articulo and id_subtipo_movimiento=130) 
											as cantidad_entregada
								FROM rac_ordenes_servicio_detalle_articulos detalle
								left join rac_articulos on detalle.id_articulo=rac_articulos.id_articulo
								where id_control_orden_servicio=".$id_control_orden_servicio."
					) as dat";
		  
	$res=mysql_query($sqlDetalleOrdenServicio) or die("error en: $sqlDetalleOrdenServicio<br><br>".mysql_error());	  
	$num=mysql_num_rows($res);
	
	$intContador=0;

			$ymas = 0;
			for($i=0;$i<$num;$i++)
			{			
				$row=mysql_fetch_row($res);
				$ymas = $ymas + $i * 1;
				
				$pdf->celpos(00.8, 8+$ymas, 5, 8, $row[3], 0, "L");
				$pdf->celpos(03.4, 8+$ymas, 3, 8, $row[4], 0, "L");
				$pdf->celpos(06.6, 8+$ymas, 5, 8, $row[5],0,"L");	
				$pdf->celpos(09.4, 8+$ymas, 1.8, 8, $row[8],0,"L");
				$pdf->celpos(12.6, 8+$ymas, 1.8, 8, $row[6],0,"L");

					// Esto va en otro for para iterar en los almacenes
					//$pdf->celpos(15.6 + 10.3, 7.8+$ymas, 1.8, 8, $row[8],0,"C");
					
				$id_articulo= $row[2];
				$cantidadSolicitada = $row[5];
				$existencia = $row[6];
				
				$sqlAlmacenesNeteables = "SELECT id_almacen,nombre FROM rac_almacenes WHERE id_tipo_almacen = 1";
				$oAlmacenesNeteables = mysql_query($sqlAlmacenesNeteables) or die("Error en:<br><i>$sqlAlmacenesNeteables</i><br><br>Descripcion:<br><b>".mysql_error());
				
				//Buscamos la existencia restante de cada articulo en los diferentes almacenes neteables
				$contador = 0;
				while(($aAlmacenesNeteables=mysql_fetch_row($oAlmacenesNeteables)) && $cantidadSolicitada > 0 ){

						$sqlExistenciasAlmacen = "SELECT COALESCE(sum(cantidad*signo),0) existencia
															FROM na_movimientos_almacen_detalle
															LEFT JOIN rac_movimientos_almacen ON rac_movimientos_almacen_detalle.id_control_movimiento=rac_movimientos_almacen.id_control_movimiento
															WHERE id_articulo=". $row[2] . "
															AND id_almacen=" . $aAlmacenesNeteables[0];
						

						$oExistencias=mysql_query($sqlExistenciasAlmacen) or die("Error en:<br><i>$sqlExistenciasAlmacen</i><br><br>Descripcion:<br><b>".mysql_error());
						$aExistenciasAlmacen = mysql_fetch_row($oExistencias);
						
						if($aExistenciasAlmacen[0] >= $cantidadSolicitada){
							$cantidadSurtirAlmacen = $cantidadSolicitada;
							$cantidadSolicitada = 0;
						}
						else{
							$cantidadSurtirAlmacen = $aExistenciasAlmacen[0];
							$cantidadSolicitada = $cantidadSolicitada - $aExistenciasAlmacen[0];
						}
					
					//echo mysql_num_rows($oExistencias);
					//exit();
					
					/*
					$pdf->celpos(15.6, 8+$ymas, 0, 8, "ALMACEN",0,"L");
					$pdf->celpos(17.8, 8+$ymas, 2, 9, "CANTIDAD A SURTIR",0,"L");
					*/
					
					$pdf->celpos(15.6, 8+$ymas + $contador, 0, 9, $aAlmacenesNeteables[1],0,"L");
					$pdf->celpos(18.1, 8+$ymas + $contador, 0, 9, $cantidadSurtirAlmacen,0,"C");
					
					$contador = $contador + 1;
					
				}
				//echo $contador;
					//exit();
				//$pdf->Line(00.8, 10+$ymas, 20.7,10+$ymas);
				$ymas = $ymas + $contador;
					
			}
	/*	
	
	for($i=0;$i<$num;$i++)
	{			
		$row=mysql_fetch_row($res);
		$ymas = $i * 1;
		
		if($row[3] == "")
		{
			$pdf->celpos(00.8, 7.8+$ymas, 5, 8, $row[1] . ". " . $row[4], 0, "L");
			$pdf->celpos(11.6, 7.8+$ymas, 1.8, 8, $row[0], 0, "C");
			$pdf->celpos(13.7 + 10.3, 7.8+$ymas, 5, 8, $row[3],0,"L");	
			$pdf->celpos(08.7 + 10.3, 7.8+$ymas, 1.8, 8, $row[2],0,"C");
		}
		else
		{
			$pdf->celpos(00.8, 7.8+$ymas, 5, 8, $row[3] . ". " . $row[4], 0, "L");
			$pdf->celpos(11.6, 7.8+$ymas, 1.8, 8, $row[2], 0, "C");
			$pdf->celpos(13.7 + 10.3, 7.8+$ymas, 5, 8, $row[1],0,"L");	
			$pdf->celpos(08.7 + 10.3, 7.8+$ymas, 1.8, 8, $row[0],0,"C");
		}
	}
	
	
	//////////////////////////////////////INICIA PARA CLIENTE
	$pdf->AddPage();
	$pdf->SetFont('Helvetica','',$ftam);
	$pdf->SetAutoPageBreak(false);
	$pdf->SetRightMargin(.5);
	
	//file x y w h tipe link
	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "logo_sup_iz.jpg", 0.5, 1.9,2.5);
	//rentandcompany
	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "logo_sup_der.jpg", 15.0, 0.5, 0);

	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "rayasOrdenCliente.jpg", 0.40, 6.5,0,0);

	$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/code/pdf/' . "direccion.jpg",2.2, 26.5, 0,1.2);
	
	$pdf->SetTextColor(0,0,0);
	
	//Informacion de la compañia
	$pdf->celpos(0.6, 0.55, 0, 23,'Orden de servicio # '. $id_orden_servicio,0,"L");	
	
	$inty=1.7;
	$intmas=0.5;
	$tamFont=9;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Fecha: ".$fec_creacion,0,"L");
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Fecha: ",0,"L");
	$inty=$inty+$intmas;
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Empresa: ". $nombre_cliente,0,"L");
	$pdf->celpos(3.3, $inty, 0, $tamFont,"Empresa: ",0,"L");
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
	if($nombre_cliente_directo!='')
		$pdf->celpos(13.5,4, 0, 8,"Cliente :  ". $nombre_cliente_directo,0,"L");
	
	
	$pdf->celpos(00.8, 6.8, 0, 9, "ARTÍCULO A SURTIR",0,"L");
	$pdf->celpos(08.7 + 10.3, 6.8, 0, 9, "CANTIDAD",0,"L");	
	
	
	

	$pdf->celpos(0.1, 24, 3, 8, "Observaciones",0,"C");
	if(strlen($observaciones_generales) > 80){  
	   $cadena = cortarTexto($observaciones_generales);
	   $cadena1 = explode("-",$cadena); 
	   $observaciones1 = $cadena1[0];
	   $observaciones2 = $cadena1[1];
	}
	else{
	   $observaciones1 = $observaciones_generales;
	   $observaciones2 = '';
	} 

	
	$pdf->celpos(0.5, 26.2, 0, 9, "Lider de Proyecto: ".$nombre_vendor,0,"L");
	
	$pdf->celpos(12, 26.2, 0, 9, "Ejecutivo de Ventas: ".$nombre_vendor,0,"L");
	




	
	$sql=" 
		SELECT 
		a.cantidad_solicitada, 
		b.nombre
		FROM rac_ordenes_servicio_detalle_articulos a
		LEFT JOIN rac_articulos b ON b.id_articulo = a.id_articulo
		WHERE a.id_control_orden_servicio = ".$doc ."		
	";
		  
	$res=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  
	$num=mysql_num_rows($res);
	
	$intContador=0;
		
	for($i=0;$i<$num;$i++)
	{			
		$row=mysql_fetch_row($res);
		$ymas = $i * 1;
		
		$pdf->celpos(00.8, 7.8+$ymas, 15, 8, $row[1] . ". " . $row[4], 0, "L");
		$pdf->celpos(08.7 + 10.3, 7.8+$ymas, 1.8, 8, $row[0],0,"C");
	}
	//////////////////////////////////////TERMINA PARA CLIENTE
	
	
	$itera = 0;
	$posX = 0;
	$posY = 0;
	$strDatos = "
		SELECT a.nombre, a.imagen_foto1 
		FROM rac_articulos a
		LEFT JOIN rac_ordenes_servicio_detalle_articulos b ON a.id_articulo = b.id_articulo
		WHERE b.id_control_orden_servicio = '$doc'
	";
	
	$resDatos = mysql_query($strDatos) or die("error en: $strDireccionEntrega<br><br>".mysql_error());	  
	while($rowDatos = mysql_fetch_assoc($resDatos))
	{
		if(($itera % 6) == 0 || $itera == 0)
		{
			$posY = 1;
			$pdf->AddPage();
		}
		
		if($posX == 1)
		{
			$posX = 11;				
		}
		else
		{
			$posX = 1;
		}
		
		if($rowDatos['imagen_foto1'] != "")
			$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/' . str_replace('../../', '', $rowDatos['imagen_foto1']), $posX, $posY, 9.4, 6.7);
		else
			$pdf->image($_SERVER['DOCUMENT_ROOT'] . '/rc/imagenes/producto_sin_foto.jpg', $posX, $posY, 9.4, 6.7);
		
		$pdf->celpos($posX, $posY + 7, 300, 9, $rowDatos['nombre'], 0, "L");
		$itera++;
		
		if($posX == 11)
			$posY += 9;
	}
	
	*/
	
	
	function cortarTexto($texto) {
    
	$tamanoOri = strlen($texto);
    $tamano = 60; // tamaño máximo
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