<?php

function asignaConsecutivoMovimientoAlmacen($llave,$stm,$valor_almacen)
{
	/*$strSQL = "SELECT if(max(id_movimiento) is null,1, max(id_movimiento) + 1)  as id_movimiento_asigando,
	           id_control_movimiento FROM ad_movimientos_almacen 
			   WHERE id_tipo_movimiento=".$stm. " AND id_almacen =".$valor_almacen ;*/

	$strSQL="SELECT if(max(id_movimiento) is null,1, max(id_movimiento) + 1)  as id_movimiento_asigando
             FROM ad_movimientos_almacen where id_tipo_movimiento in 
			 (SELECT id_tipo_movimiento FROM ad_subtipos_movimientos WHERE id_subtipo_movimiento= ".$stm. ")
			  AND id_almacen =".$valor_almacen;
			   
	unset($arrRes);
	$arrRes=array();
	$arrRes=valBuscador($strSQL);
	$id_rel=$arrRes[0];	
	
	$strUpdade="UPDATE ad_movimientos_almacen SET id_movimiento='".$id_rel."' where id_control_movimiento=".$llave;
	mysql_query($strUpdade) or die("Error en consulta $strUpdade mysql_errno()");
			
}


//funcion que obtiene la existencias de un producto en el almancen
// $idProducto es siempre requerido
// producto   con almacen con tipo de almacen con lote

function obtenExistenciaUnProducto($idProducto,$idAlmacen,$tipoAlmacen,$idLote)
{
	$strWhere="";
	//obtenemos la existencia del producto apartir del ultimo movimiento del almacen que presente el tipo de movimiento de entrada, neteable
	if($idAlmacen!="")
	{
		$strWhere=" and ad_movimientos_almacen.id_almacen ='".$idAlmacen."'";
	}
	
	if($tipoAlmacen!="")
	{
		$strWhere=" and ad_almacenes.neteable =".$tipoAlmacen;
	}

	if($$idLote!="")
	{
		$strWhere=" and ad_movimientos_almacen_detalle.id_lote =".$tipoAlmacen;
	}
		
	$strSQL="SELECT if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia FROM ad_movimientos_almacen_detalle
			left join ad_movimientos_almacen on ad_movimientos_almacen_detalle.id_control_movimiento=ad_movimientos_almacen.id_control_movimiento
			left join ad_almacenes on ad_movimientos_almacen.id_almacen=ad_almacenes.id_almacen
			left join ad_productos on ad_productos.id_producto=ad_movimientos_almacen_detalle.id_producto
			LEFT JOIN ad_familias_productos on ad_productos.id_familia_producto = ad_familias_productos.id_familia_producto
			LEFT JOIN ad_tipos_productos  on ad_productos.id_tipo_producto = ad_tipos_productos.id_tipo_producto
			LEFT JOIN ad_modelos_productos  on ad_productos.id_modelo_producto = ad_modelos_productos.id_modelo_producto
			LEFT JOIN ad_caracteristicas_productos  on ad_productos.id_caracteristica_producto = ad_caracteristicas_productos.id_caracteristica_producto
			LEFT JOIN ad_lotes  on ad_movimientos_almacen_detalle.id_lote = ad_lotes.id_lote
			WHERE ad_movimientos_almacen.no_modificable =1 and ad_movimientos_almacen_detalle.id_producto ='".$idProducto."'  ".$strWhere ;
	
	
			
     $arrResultado =valBuscador($strSQL);
	
	 return  $arrResultado[0];
	 
}

//----------------------------------------------------------------------------
//obtenemos las cantidades apartadas
function obtenCantidadApartadoUnProducto($idProducto)
{
	$strSQL="SELECT SUM(cantidad) as apartado  FROM ad_movimientos_apartados where id_estatus_apartado=1  and id_producto ='".$idProducto."'" ;
	$arrResultado =valBuscador($strSQL);
	return  $arrResultado[0];
}
	
//obtenemos los productos de cantidades apartadas de los pro
	
	//realizamos la correspondiente entrada de la salida generada
function generaEntradaAlmacen($id_control_movimiento_almacen,$id_subtipo_mov)
{
	//obtenemos toda la informacion del almance
	if($id_subtipo_mov=='70055')
	{//TRASPASO SALIDA DEVOLUCION / RERACION A CEDIS -

		//TRASPASO ENTRADA REPARACION A CEDIS +
		$id_subtipo_mov_entrada='70005';
	}
	else if($id_subtipo_mov=='70066')
	{//TRASPASO SALIDA ENTRE SUCURSALES -
		
		//TRASPASO ENTRADA ENTRE SUCURSALES +
		$id_subtipo_mov_entrada='70006';
	}
			
	//obtenemos el subtipo de movimiento
	
		$strSQLInsert="INSERT INTO ad_movimientos_almacen (
									id_control_movimiento, 
									id_movimiento, 
									id_tipo_movimiento, 
									id_subtipo_movimiento, 
									id_almacen, 
									fecha_movimiento, 
									hora_movimiento, 
									id_almacen_origen_destino, 
									id_control_movimiento_entrada_relacionada, 
									id_movimiento_entrada, 
									id_control_movimiento_salida_relacionada, 
									id_movimiento_salida, 
									id_control_pedido, 
									id_pedido, 
									no_modificable, 
									id_orden_compra, 
									id_proveedor, 
									id_proveedor_transporte, 
									chofer, 
									placas, 
									id_solicitud_devolucion_cedis, 
									id_orden_recoleccion, 
									id_usuario, 
									id_usuario_valido, 
									id_usuario_direccion_valido, 
									observaciones, 
									id_control_poliza, 
									activo, 
									id_bitacora_ruta, 
									consecutivo )
									
									SELECT NULL, 
											NULL, 
											1, 
											'".$id_subtipo_mov_entrada."', 
										    id_almacen_origen_destino, 
											NOW(), 
											NOW(), 
											id_almacen, 
											id_control_movimiento_entrada_relacionada, 
											id_movimiento_entrada, 
											'".$id_control_movimiento_almacen."', 
											id_movimiento, 
											id_control_pedido, 
											id_pedido, 
											0, 
											id_orden_compra, 
											id_proveedor, 
											id_proveedor_transporte, 
											chofer, 
											placas, 
											id_solicitud_devolucion_cedis, 
											id_orden_recoleccion, 
											id_usuario, 
											id_usuario_valido, 
											id_usuario_direccion_valido, 
											observaciones, 
											id_control_poliza, 
											activo, 
											id_bitacora_ruta, 
											consecutivo 
											FROM ad_movimientos_almacen WHERE id_control_movimiento='".$id_control_movimiento_almacen."'
									
									";
	mysql_query($strSQLInsert) or die("Error en consulta > $strSQLInsert  < \nDescripcion:".mysql_error());	
		
	//OBTENEMOS EL ID CREADO
	$id_nuevo_control=mysql_insert_id();
	
	//obtenemos el almacen del que se creo la entrada
	$strSQL="SELECT id_almacen									
			FROM ad_movimientos_almacen where id_control_movimiento ='".$id_nuevo_control."'";
	
	$arrResultAlmacen=valBuscador($strSQL);
	$idAlmacenBuscar=$arrResultAlmacen[0];
	
	//-------------------------------------------------
	//-------------------------------------------------
	$strSQL="SELECT if(MAX(id_movimiento) is null,1,MAX(id_movimiento)+1) as movimiento 
					FROM ad_movimientos_almacen where id_almacen= '".$idAlmacenBuscar."' and id_tipo_movimiento=1 ";
		
	$arrResultMov=valBuscador($strSQL);
	$id_movimiento_nuevo=$arrResultMov[0];
	
	$strSQL="UPDATE ad_movimientos_almacen SET id_movimiento ='".$id_movimiento_nuevo."' 
			where id_control_movimiento ='".$id_nuevo_control."'";
	
	mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
	
	
	//realizmaos un update al movieminto de almacen creado
	
	
	
	$strSQLInsertDetalle="INSERT INTO ad_movimientos_almacen_detalle (id_detalle,
					id_control_movimiento,
					id_producto,
					cantidad_traspaso,
					cantidad_solicitada,
					id_lote,
					cantidad_existencia,
					cantidad,
					observaciones, 
					id_tipo_documento_interno, 
					id_detalle_documento_interno,
					signo, 
					id_bitacora_ruta,
					id_apartado,
					id_costeo_productos, 
					id_costeo_producto_detalle, 
					campo_1,
					id_apartado_detalle, 
					id_producto_compuesto,
					campo_4 )  
					SELECT NULL,
					'".$id_nuevo_control."',
					id_producto,
					cantidad,
					0,
					id_lote,
					0,
					0,
					observaciones, 
					'".$id_subtipo_mov."', 
					id_detalle,
					1, 
					0,
					0,
					0, 
					0, 
					0,
					0, 
					0,
					0 
					FROM ad_movimientos_almacen_detalle WHERE id_control_movimiento='".$id_control_movimiento_almacen."'
	
	
	 ";
	
	//obtenemos toda la informacion de su detalle , en el detalle colocamos el pendiente por confirmar
	//realizamos el inbsert en el almacen selecionado
	mysql_query($strSQLInsertDetalle) or die("Error en consulta $strSQLInsertDetalle\nDescripcion:".mysql_error());	
	
	
	//mandamos mail al gerente de la sucursla para que valide la entrada relacionada
	
}
	
?>