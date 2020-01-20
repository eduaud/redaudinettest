<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

//Hacemos la consulta principal para obtener los pedidos que cumplan con la condicion
$sql = "SELECT id_control_pedido, id_pedido, 
		CONCAT(ad_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) AS cliente,
		id_control_movimiento
		FROM
		(

		SELECT ad_pedidos.id_control_pedido, ad_pedidos.id_pedido, ad_pedidos.id_cliente, ma.id_control_movimiento, ma_detalle.id_producto,
					 SUM(ma_detalle.cantidad) AS cantidad_salida, na_pedidos_detalle.id_pedido_detalle, ma_detalle.id_detalle_documento_interno,
					 na_pedidos_detalle.cantidad_requerida,
				(SELECT SUM(IF(na_movimientos_almacen_detalle.cantidad IS NULL, 0, na_movimientos_almacen_detalle.cantidad)) 
				FROM na_movimientos_almacen_detalle 
				LEFT JOIN na_movimientos_almacen ON na_movimientos_almacen.id_control_movimiento = na_movimientos_almacen_detalle.id_control_movimiento 
				WHERE na_movimientos_almacen_detalle.id_detalle_documento_interno = ma_detalle.id_detalle_documento_interno 
								AND na_movimientos_almacen.id_control_pedido = ma.id_control_pedido AND na_movimientos_almacen.id_subtipo_movimiento = 70009 
								AND na_movimientos_almacen.no_modificable = 1 AND na_movimientos_almacen.activo = 1
				) AS cantidad_entrada
		FROM ad_pedidos
		LEFT JOIN na_pedidos_detalle ON ad_pedidos.id_control_pedido = na_pedidos_detalle.id_control_pedido
		LEFT JOIN na_movimientos_almacen AS ma ON ad_pedidos.id_control_pedido = ma.id_control_pedido 
		LEFT JOIN na_movimientos_almacen_detalle AS ma_detalle ON ma.id_control_movimiento = ma_detalle.id_control_movimiento 
					AND ma_detalle.id_detalle_documento_interno = na_pedidos_detalle.id_pedido_detalle
		WHERE (id_estatus_pedido = 5 OR id_estatus_pedido = 6) AND ma.id_subtipo_movimiento = 70099
					AND ma.no_modificable = 1 AND ma.activo = 1 
		GROUP BY(na_pedidos_detalle.id_control_pedido)
		) AS datos
		LEFT JOIN ad_clientes ON datos.id_cliente = ad_clientes.id_cliente
		WHERE IF(cantidad_entrada IS NULL, 0, cantidad_entrada) < cantidad_salida";
		

$datos = new consultarTabla($sql);
$resultPedido = $datos -> obtenerArregloRegistros();

//Declaramos el array que contendra los detalles del pedido
unset($detalles);
$detalles = array(); 

//Recorremos el array del pedido
foreach($resultPedido as $datos){
		//Consulta para obtener los productos del pedido
		$sqlDet = "SELECT datos.id_producto, na_productos.nombre, cantidad_requerida, cantidad_salida, IF(cantidad_entrada IS NULL, 0, cantidad_entrada) AS entradas, 
					id_control_pedido, id_pedido_detalle
				FROM
				(

				SELECT ad_pedidos.id_control_pedido, ad_pedidos.id_cliente, ma.id_control_movimiento, ma_detalle.id_producto,
							 SUM(ma_detalle.cantidad) AS cantidad_salida, na_pedidos_detalle.id_pedido_detalle, ma_detalle.id_detalle_documento_interno,
							 na_pedidos_detalle.cantidad_requerida,
				( 
						SELECT SUM(IF(na_movimientos_almacen_detalle.cantidad IS NULL, 0, na_movimientos_almacen_detalle.cantidad)) 
						FROM na_movimientos_almacen_detalle 
						LEFT JOIN na_movimientos_almacen ON na_movimientos_almacen.id_control_movimiento = na_movimientos_almacen_detalle.id_control_movimiento 
						WHERE na_movimientos_almacen_detalle.id_detalle_documento_interno = ma_detalle.id_detalle_documento_interno 
										AND na_movimientos_almacen.id_control_pedido = ma.id_control_pedido AND na_movimientos_almacen.id_subtipo_movimiento = 70009 
										AND na_movimientos_almacen.no_modificable = 1 AND na_movimientos_almacen.activo = 1
				) AS cantidad_entrada

				FROM ad_pedidos
				LEFT JOIN na_pedidos_detalle ON ad_pedidos.id_control_pedido = na_pedidos_detalle.id_control_pedido
				LEFT JOIN na_movimientos_almacen AS ma ON ad_pedidos.id_control_pedido = ma.id_control_pedido 
				LEFT JOIN na_movimientos_almacen_detalle AS ma_detalle ON ma.id_control_movimiento = ma_detalle.id_control_movimiento 
							AND ma_detalle.id_detalle_documento_interno = na_pedidos_detalle.id_pedido_detalle
				WHERE (id_estatus_pedido = 5 OR id_estatus_pedido = 6) AND ma.id_subtipo_movimiento = 70099
							AND ma.no_modificable = 1 AND ma.activo = 1
				GROUP BY(na_pedidos_detalle.id_pedido_detalle)
				) AS datos
				LEFT JOIN ad_clientes ON datos.id_cliente = ad_clientes.id_cliente
				LEFT JOIN na_productos ON datos.id_producto = na_productos.id_producto
				WHERE IF(cantidad_entrada IS NULL, 0, cantidad_entrada) < cantidad_salida AND id_control_pedido = " . $datos[0];  
		//Hara la consulta por cada pedido y con la condicion de que ese producto tenga un movimiento de apartado
		$datosDet = new consultarTabla($sqlDet);
		$resultDetalles = $datosDet -> obtenerArregloRegistros();
		$detalles[] = $resultDetalles; //Almacenamos los productos en el array
		}
		
$strSQL = "SELECT id_almacen, nombre FROM ad_almacenes WHERE activo = 1 ORDER BY nombre";
		$datos = new consultarTabla($strSQL);	
		$result = $datos -> obtenerRegistros();
		foreach($result as $registros){
				$arrAlmId[] = $registros -> id_almacen;
				$arrAlm[] = $registros -> nombre;			
				}		
		$smarty->assign('alm_id', $arrAlmId);
		$smarty->assign('alm_nombre',$arrAlm);

$smarty -> assign("pedidos", $resultPedido);		
$smarty -> assign("detalles", $detalles);	


//Consulta para obtener los clientes
//Hacemos la consulta principal para obtener los pedidos que cumplan con la condicion
$sqlClientes = "SELECT DISTINCT ad_clientes.id_cliente AS id_cliente,
		CONCAT(ad_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) AS cliente
		FROM
		(

		SELECT ad_pedidos.id_control_pedido, ad_pedidos.id_pedido, ad_pedidos.id_cliente, ma.id_control_movimiento, ma_detalle.id_producto,
					 SUM(ma_detalle.cantidad) AS cantidad_salida, na_pedidos_detalle.id_pedido_detalle, ma_detalle.id_detalle_documento_interno,
					 na_pedidos_detalle.cantidad_requerida,
				(SELECT SUM(IF(na_movimientos_almacen_detalle.cantidad IS NULL, 0, na_movimientos_almacen_detalle.cantidad)) 
				FROM na_movimientos_almacen_detalle 
				LEFT JOIN na_movimientos_almacen ON na_movimientos_almacen.id_control_movimiento = na_movimientos_almacen_detalle.id_control_movimiento 
				WHERE na_movimientos_almacen_detalle.id_detalle_documento_interno = ma_detalle.id_detalle_documento_interno 
								AND na_movimientos_almacen.id_control_pedido = ma.id_control_pedido AND na_movimientos_almacen.id_subtipo_movimiento = 70009 
								AND na_movimientos_almacen.no_modificable = 1 AND na_movimientos_almacen.activo = 1
				) AS cantidad_entrada
		FROM ad_pedidos
		LEFT JOIN na_pedidos_detalle ON ad_pedidos.id_control_pedido = na_pedidos_detalle.id_control_pedido
		LEFT JOIN na_movimientos_almacen AS ma ON ad_pedidos.id_control_pedido = ma.id_control_pedido 
		LEFT JOIN na_movimientos_almacen_detalle AS ma_detalle ON ma.id_control_movimiento = ma_detalle.id_control_movimiento 
					AND ma_detalle.id_detalle_documento_interno = na_pedidos_detalle.id_pedido_detalle
		WHERE (id_estatus_pedido = 5 OR id_estatus_pedido = 6) AND ma.id_subtipo_movimiento = 70099
					AND ma.no_modificable = 1 AND ma.activo = 1 
		GROUP BY(na_pedidos_detalle.id_control_pedido)
		) AS datos
		LEFT JOIN ad_clientes ON datos.id_cliente = ad_clientes.id_cliente
		WHERE IF(cantidad_entrada IS NULL, 0, cantidad_entrada) < cantidad_salida";
		
		$datosCliente = new consultarTabla($sqlClientes);	
		$resultCliente = $datosCliente -> obtenerRegistros();
		foreach($resultCliente as $registrosC){
				$arrClienId[] = $registrosC -> id_cliente;
				$arrClien[] = $registrosC -> cliente;			
				}		
		$smarty->assign('clien_id', $arrClienId);
		$smarty->assign('clien_nombre',$arrClien);
	

$smarty -> display("especiales/devolucionVenta.tpl");

?>