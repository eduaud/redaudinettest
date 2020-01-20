<?php


//DADO LA ULTIMA ENTRADA AL ALMACEN CREAMOS LOTES 
function creaAsignaLotesEntrada($id_control_movimiento_entrada,$id_costeo_productos,$idODC)
{
	//BUSCAMOS TODOS LOS PRODUCTOS INGRESADOS EL EL ALMACEN
	//PARA CADA PRODUCTO ENCONTRADO VEMOS LA ENTRADA AL ALMACEN
	//-------------------------
	$strSQL="select id_detalle,
			id_control_movimiento,
			id_producto,
			id_lote
			id_costeo_productos
			id_costeo_producto_detalle from ad_movimientos_almacen_detalle where
			id_control_movimiento='".$id_control_movimiento_entrada."'";

	$rs = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
	while($row = mysql_fetch_assoc($rs)){			
		//vamos armando el lote
	
		 //$row['id_forma_pago'];
		
		$id_lote=obtenIDLote($strLote);
		if($id_lote ==0)
		{
			//lo insertamos en la base de datos				
		}
		
		
	}		
	
	//-------------------------
	
}

//obtenbemos o asignamos lote  apartir de  una orden de  commporA
function obtenAsignaCreaLote($id_producto,$id_control_movimiento_entrada,$id_costeo_productos,$idODC,$pedimento,$pedimentoFecha,$id_aduana)
{
	
	if($idODC<>'')
	{
		$tipo='OC';
	}
	
	//CREAMOS EL LOTE CON LOS DATOS ASIGNADOS
	$strLote=creaCadenaLote($id_producto,$id_costeo_productos,$idODC,$tipo);
	

	
	//DE LA CADENA CREADA DEL LOTE VEMOS SI NO ESTA YA EN LA BASE DE DATOS
	$strSQL="SELECT if(count(id_lote)=0,0,id_lote) FROM ad_lotes where lote='".$strLote."' LIMIT 1";
	$arrlote=valBuscador($strSQL);

	$id_lote=$arrlote[0];

	//si traemos fecha de pedimento la convertimos a formato anio mes y dia
	
	if($pedimentoFecha!='')
	{
		$pedimentoFecha=convertDate($pedimentoFecha);
	}
	else
	{
		$pedimentoFecha='0000-00-00';
	}
	
	
	//obtenemos la fecha de la orden de compra
	
	//si la orden de compra diferente de vacio
	if($idODC=='')
	{
		$idODC=0;
		$fecha_orden_compra ='0000-00-00';
	}
	else
	{
		//SI TENEMOS ORDEN DE COMPRA BUSCAMOS LA FECHA DE LA ORDEN DE COMPRA
		$strSQL="SELECT fecha_creacion FROM na_ordenes_compra where id_orden_compra=".$idODC." LIMIT 1";
		$arrfecha_orden_compra=valBuscador($strSQL);
		$fecha_orden_compra=$arrfecha_orden_compra[0];
	
			
	}
	
	if($id_costeo_productos =='')
	{
		$id_costeo_productos=0;
	}
	
	
	if($id_lote=='0')
	{
		//realizamos un insert en la base de datos
		$strInsert="INSERT INTO ad_lotes (id_lote, lote, id_producto, id_orden_compra, fecha_orden_compra, fecha_registro_sistema, pedimento_no, fecha_entrada_pedimento, id_aduana, costo_unitario, fecha_actualizacion, activo, id_costeo_productos) 
		            VALUES (NULL, '".$strLote."', '".$id_producto."', '".$idODC."', '".$fecha_orden_compra."', NOW(), '".$pedimento."', '".$pedimentoFecha."', '".$id_aduana."', 0, NOW(), 1, '".$id_costeo_productos."')";
			
		mysql_query($strInsert) or die("Error en consulta $strInsert\nDescripcion:".mysql_error());	
		
		//OBTENEMOS EL ID CREADO
		$id_lote=mysql_insert_id();
	
	}
	
	return $id_lote;
	
	 
}

//cramos la cadena del lote
//$tipo indica si proviene del proceso de un costeo
function creaCadenaLote($id_producto,$id_costeo_productos,$idODC,$tipo)
{
	//si generamos el lote aparttir de una orden de compra  entoncces colocamos los numero de orden de compra y de costeo de productos
	$medFijo="SOC";
	
	$fecha=date("Ymd");
	
	if($tipo=='OC')
	{
		$medFijo="OC_".$idODC;
	}
	
	$strLote=$fecha."_";
	
	//colocmos el idproducto
	$strLote .= $id_producto."_";
	
	$strSQL="SELECT sku FROM na_productos where id_producto=".$id_producto." LIMIT 1";
	$arrsku=valBuscador($strSQL);
	
	//colocamos el sku
	$strLote .= $arrsku[0]."_";
	
	//colocamos si el $medFijo OC o SO (sin orden)
	$strLote .=$medFijo;
	//retornamos la cadena
	return $strLote;
	
}

//OBTENEMOS LA EXISTENCIAS DE UN PRODUCTO POR LOTE POR ALMACEN POR SUBTIPO DE MOVIMIENTO
function obtenLotesProductosAlmacenSubtipoMov($id_producto,$id_almacen,$id_subtipomoval){
	//VEMOS SI EL SUBTIPO DE MOVIMIENTO ADMITE LOTE MANUAL O AUTOMATICO
	
	//SI ES AUTOMATICO - OBTENEMOS TODA LA EXISTENCIA DEL ALMACEN
	
	//SI NO ES AUTOMATICO - OBTENEMOS TODOS LOS LOTES ASICIADOS A EL
	$opcionesLote = "";
	$strSQL="SELECT genera_lote_automatico FROM na_subtipos_movimientos WHERE id_subtipo_movimiento = '".$id_subtipomoval."'";
	$arrGLA=valBuscador($strSQL);
	$generaLoteAutomatico=$arrGLA[0];
	//si genera lote en utomatico mostramos silo el lote en automatico y la existencia de ese producto en el almacen
	if($generaLoteAutomatico==1){
		//OBTENEMOS LA EXISTENCIA
						
				$strSQL="SELECT ad_lotes.id_lote ,CONCAT(lote,':' ,if(SUM(cantidad * signo) is null,0,SUM(cantidad * signo))) as lote
						FROM ad_movimientos_almacen_detalle
						LEFT JOIN ad_movimientos_almacen ON ad_movimientos_almacen_detalle.id_control_movimiento=ad_movimientos_almacen.id_control_movimiento
						right JOIN ad_lotes ON ad_lotes.id_lote=0
						WHERE  ad_movimientos_almacen.no_modificable=1 and ad_movimientos_almacen_detalle.id_lote<>0 AND ad_movimientos_almacen.id_almacen='".$id_almacen."' 
						AND ad_movimientos_almacen_detalle.id_producto='".$id_producto."' 
						";
						
				$resultLote = new consultarTabla($strSQL);
				$datosLote = $resultLote -> obtenerRegistros();
				foreach($datosLote as $dato){
						$opcionesLote .= '<option value="' . $dato -> id_lote . '">' . utf8_encode($dato -> lote) . '</option>';
						}
			}
	else{
			$strSQL="SELECT ad_movimientos_almacen_detalle.id_lote ,lote ,CONCAT(lote,':' ,SUM(cantidad * signo)) as lote 
					FROM ad_movimientos_almacen_detalle
					LEFT JOIN ad_movimientos_almacen ON ad_movimientos_almacen_detalle.id_control_movimiento=ad_movimientos_almacen.id_control_movimiento
					LEFT JOIN ad_lotes ON ad_movimientos_almacen_detalle.id_lote=ad_lotes.id_lote
					WHERE ad_movimientos_almacen.no_modificable=1 and ad_movimientos_almacen_detalle.id_lote<>0 AND ad_movimientos_almacen.id_almacen='".$id_almacen."' 
					AND ad_movimientos_almacen_detalle.id_producto='".$id_producto."'
					GROUP BY ad_movimientos_almacen_detalle.id_lote having SUM(cantidad * signo)> 0 ORDER BY fecha_registro_sistema";
			$resultLote = new consultarTabla($strSQL);
			$datosLote = $resultLote -> obtenerRegistros();
			foreach($datosLote as $dato){
					$opcionesLote .= '<option value="' . $dato -> id_lote . '">' . utf8_encode($dato -> lote) . '</option>';
					}
		
			}
	return $opcionesLote;
	}

function asignaLoteDetalles($id_control_movimiento,$conSinBeginTrans)
{
	
	$conSinBeginTrans=0;
	//vemos los datos del movimiento
	$strSQL="SELECT id_subtipo_movimiento,id_tipo_movimiento,id_almacen FROM ad_movimientos_almacen where id_control_movimiento='".$id_control_movimiento."'";
	$arrSubMov=valBuscador($strSQL);
	
	$id_subtipomoval=$arrSubMov[0];
	$id_tipomov=$arrSubMov[1];
	$id_almacen=$arrSubMov[2];
	
	$strSQL="SELECT genera_lote_automatico FROM ad_subtipos_movimientos WHERE id_subtipo_movimiento = '".$id_subtipomoval."'";
	$arrGLA=valBuscador($strSQL);
	$generaLoteAutomatico=$arrGLA[0];
	
	//no genera lote en automatico se sale
	if($generaLoteAutomatico==0)
	{
		//YA DEBE VE VENIR EL LOTE ASIGNADO EN LA ENTRADA , SO N
	}
	else //SI GENERAR LOTE EN ATOMATICO, VEMOS SI ES ENTRADA O SALIDA
	{
		//---->>ENTRADA CON LOTE EN AUTOMATICO
		if($id_tipomov==1)
		{
			// A ESTOS LOTES DEBE ASIGNARLES UN VALOR 
			generaEntradaLoteAutomaticoControlMovimiento($id_control_movimiento,$id_almacen,$conSinBeginTrans);
		}
		else
		{
			//SI ES SALIDA EN AUTOMATICO BUSCAMOS DE QUE LOTES GENERAMOS LA SALIDA
			generaSalidaLoteAutomaticoControlMovimiento($id_control_movimiento,$id_almacen,$conSinBeginTrans);
			
		}
		
	}
	
	//si el movimimiento del almacen 
}
//entradas con lote en automatico para los productos que no son de ordenes de compra
function generaEntradaLoteAutomaticoControlMovimiento($id_control_movimiento,$id_almacen,$conSinBeginTrans)
{
	$strSQL="SELECT id_detalle,id_producto,cantidad
			FROM ad_movimientos_almacen_detalle where id_control_movimiento='".$id_control_movimiento."'";
	
	
	if(!($resource = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
	while($row0 = mysql_fetch_row($resource))
	{
		//para el primer producto obtenemos los lotes que lo conforman
		$id_detalle=$row0[0];
		$id_producto=$row0[1];
		$cantidad=$row0[2];
		
		$id_lote = obtenAsignaCreaLote($id_producto,$id_control_movimiento,'','','','',0);
		
		$strUpdate="UPDATE ad_movimientos_almacen_detalle set id_lote ='".$id_lote."' WHERE id_detalle='".$id_detalle."'";
		if(!(mysql_query($strUpdate)))	die("Error en:<br><i>$strUpdate</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
	
		 
	}
	
	//para cada producto vamos buscando si existe el lote
	
	//si existe el lote realizamos el update
	
	//si no existe el lote lo creamos y lo asignamos
	
	
}

//salidas con lote en automatico
function generaSalidaLoteAutomaticoControlMovimiento($id_control_movimiento,$id_almacen,$conSinBeginTrans)
{
	
	
	//de este almacen obtenemos todos los productos del detalle y le asignamos el lote que despues le tendran que asignar el costeo
	
	//para cada producto vamos asignado 
	$strSQL="SELECT id_producto,
					sum(cantidad) as cantidad,
					if(id_apartado is null,-1,id_apartado) ,
					if(id_tipo_documento_interno is null,-1,id_tipo_documento_interno) ,
					if(id_detalle_documento_interno is null,-1,id_detalle_documento_interno) ,
					if(id_costeo_productos is null,-1,id_costeo_productos) ,
					if(id_costeo_producto_detalle is null,-1,id_costeo_producto_detalle),
					if(id_apartado_detalle is null,-1,id_apartado_detalle),
					if(id_producto_compuesto is null,-1,id_producto_compuesto),
					if(id_control_pedido is null,-1,id_control_pedido),
					if(id_pedido is null,-1,id_pedido)
			 FROM ad_movimientos_almacen_detalle where id_control_movimiento='".$id_control_movimiento."'
			 GROUP BY  id_producto,id_apartado,id_tipo_documento_interno,id_detalle_documento_interno,id_costeo_productos,id_costeo_producto_detalle,id_apartado_detalle";
					
			
	
	//creamos el array de los insert

	
	if(!($resource = mysql_query($strSQL)))	die("Error en:<br><i>$strSQL</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
	while($row0 = mysql_fetch_row($resource))
	{
					//--------------------
					unset($arrayInsert);
					unset($arrayDelete);
					$arrayInsert=array();
					$arrayDelete=array();
										
					//para el primer producto obtenemos los lotes que lo conforman
					$id_producto=$row0[0];
					$cantidad_solicitada=$row0[1];
					$id_apartado=$row0[2];
					$id_tipo_documento_interno=$row0[3];
					$id_detalle_documento_interno=$row0[4];
					$id_costeo_productos=$row0[5];
					$id_costeo_producto_detalle=$row0[6];
					$id_apartado_detalle=$row0[7];
					$id_producto_compuesto=$row0[8];
					$id_control_pedido_det=$row0[9];
					$id_pedido_det=$row0[10];
						
					
					$strSQL1="SELECT ad_movimientos_almacen_detalle.id_lote,
								SUM(cantidad * signo) as cantidad_lote
								FROM ad_movimientos_almacen_detalle
									LEFT JOIN ad_movimientos_almacen ON ad_movimientos_almacen_detalle.id_control_movimiento=ad_movimientos_almacen.id_control_movimiento
									LEFT JOIN ad_lotes ON ad_movimientos_almacen_detalle.id_lote=ad_lotes.id_lote
								WHERE ad_movimientos_almacen.no_modificable=1 and ad_movimientos_almacen_detalle.id_lote<>0 AND ad_movimientos_almacen.id_almacen='".$id_almacen."'
									AND ad_movimientos_almacen_detalle.id_producto='".$id_producto."'
								GROUP BY ad_movimientos_almacen_detalle.id_lote having SUM(cantidad * signo)> 0 
								ORDER BY fecha_registro_sistema";
				
					
					/*echo $strSQL1;
					die();*/
					
					if(!($resource1 = mysql_query($strSQL1)))	die("Error en:<br><i>$strSQL1</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
					$cantidad_lote=0;
					
					while(($row1 = mysql_fetch_row($resource1)) &&  ($cantidad_solicitada > 0) )
					{
						
									
							$id_lote=$row1[0];
							$cantidad_lote=$row1[1];
							
							
							//si la cantindad del lote es mayor obtenemos colocamos la cantidad solicitada
							if($cantidad_lote >= $cantidad_solicitada)
							{
								$cantidadInser=$cantidad_solicitada;
								$cantidad_solicitada=0;
							}
							else
							{//si la cantidad del lote es menor, colocamos la cantidad del lote para insertar
								$cantidadInser=$cantidad_lote;
								$cantidad_solicitada = $cantidad_solicitada - $cantidad_lote;
							}
							
							
							
							$strInsertDetalle="INSERT INTO ad_movimientos_almacen_detalle (id_detalle,id_control_movimiento,id_producto,id_lote,cantidad,id_tipo_documento_interno, id_detalle_documento_interno, signo,id_apartado, id_costeo_productos, id_costeo_producto_detalle, campo_1,id_apartado_detalle, id_producto_compuesto,id_control_pedido,	id_pedido)
											  VALUES (NULL,'".$id_control_movimiento."','".$id_producto."','".$id_lote."','".$cantidadInser."','".$id_tipo_documento_interno."','".$id_detalle_documento_interno."', -1,'".$id_apartado."', '".$id_costeo_productos."', '".$id_costeo_producto_detalle."','1','".$id_apartado_detalle."','".$id_producto_compuesto."','".$id_control_pedido_det."','".$id_pedido_det."')";
						
						
							$strDeleteDetalle="DELETE FROM ad_movimientos_almacen_detalle  WHERE 
												id_control_movimiento='".$id_control_movimiento."' AND
												id_producto='".$id_producto."'  ";
												
							if($id_tipo_documento_interno== '-1')
							{
								$strDeleteDetalle .= " AND id_tipo_documento_interno is null ";
							}
							else
							{
								$strDeleteDetalle .= " AND id_tipo_documento_interno ='".$id_tipo_documento_interno."'  ";
							}
							
							
							if($id_detalle_documento_interno== '-1')
							{
								$strDeleteDetalle .= " AND id_detalle_documento_interno is null ";
							}
							else
							{
								$strDeleteDetalle .= " AND id_detalle_documento_interno ='".$id_detalle_documento_interno."'  ";
							}
							
							
							if($id_apartado== '-1')
							{
								$strDeleteDetalle .= " AND id_apartado is null ";
							}
							else
							{
								$strDeleteDetalle .= " AND id_apartado ='".$id_apartado."'  ";
							}
							
							
							if($id_costeo_productos== '-1')
							{
								$strDeleteDetalle .= " AND id_costeo_productos is null ";
							}
							else
							{
								$strDeleteDetalle .= " AND id_costeo_productos ='".$id_costeo_productos."'  ";
							}
							
							
							if($id_costeo_producto_detalle== '-1')
							{
								$strDeleteDetalle .= " AND id_costeo_producto_detalle is null ";
							}
							else
							{
								$strDeleteDetalle .= " AND id_costeo_producto_detalle ='".$id_costeo_producto_detalle."'  ";
							}
							
							
							
							//$strDeleteDetalle .= " AND campo_1 <>'1' ";
									
							/*echo $strInsertDetalle. "<br> eliminar ";
							echo $strDeleteDetalle. "<br>";*/
							array_push($arrayInsert,$strInsertDetalle);
							array_push($arrayDelete,$strDeleteDetalle);
							
						
					}
					//si pudimos surtir toda la cantidad de los productos
					
					if($cantidad_solicitada==0)
					{
						//eliminamos los productos del almancen
						for($i=0;$i<count($arrayInsert);$i++)
						{
							$strSQLA=$arrayInsert[$i];
							//echo $strSQLA . '<br><br>';
							mysql_query($strSQLA) or 	die("Error en strSQLA:<br><i>$strSQLA</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
						}
						
						for($i=0;$i<count($arrayDelete);$i++)
						{
							$strSQLB=$arrayDelete[$i];
							//echo $strSQLB. '<br><br>';
							mysql_query($strSQLB) or	die("Error en strSQLB:<br><i>$strSQLB</i><br><br>Descripci&oacute;n:<br><b>".mysql_error());
							 
							 
						}
					}
				   	
					mysql_free_result($resource1);
					
					//agregamos los inserts generados
					
	}
	mysql_free_result($resource);
		
}




?>