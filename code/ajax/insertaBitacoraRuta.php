<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$prodPedidos = $_POST['prodPedidos'];
$recolecciones = $_POST['recolecciones'];
$recoleccionesSuc = $_POST['recoleccionesSuc'];
$recoleccionesSuc2 = $_POST['recoleccionesSuc2'];

$prodPedidosDatos = explode(",", $prodPedidos);
$recDatos = explode(",", $recolecciones);
$recDatosSucPed = explode(",", $recoleccionesSuc);
$recDatosSucPed2 = explode(",", $recoleccionesSuc2);

$cuentaProdPedido = count(array_filter($prodPedidosDatos));
$cuentaRec = count(array_filter($recDatos));
$cuentaRecSucPed = count(array_filter($recDatosSucPed));
$cuentaRecSucPed2 = count(array_filter($recDatosSucPed2));

$bitacora['fecha'] = date("Y-m-d");
$bitacora['cerrar_bitacora'] = 0;
$bitacora['id_usuario_creacion'] = $_SESSION["USR"]->userid;
accionesMysql($bitacora, 'na_bitacora_rutas', 'Inserta');
$ultimo_id = mysql_insert_id();

if($cuentaProdPedido > 0){
		for($i=0; $i<$cuentaProdPedido; $i++){
				$sql = "SELECT na_rutas.id_ruta AS ruta, ad_pedidos.id_pedido AS pedido, ad_pedidos.id_control_pedido AS control_pedido,
				CONCAT(ad_clientes.nombre,' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno),' ',if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) AS nombre_cliente, na_movimientos_almacen_detalle.id_control_movimiento AS control_movimiento,
				CONCAT('Calle: ',ad_clientes_direcciones_entrega.calle,' ', 'Num. Ext: ',
				if(ad_clientes_direcciones_entrega.numero_exterior is null,'',ad_clientes_direcciones_entrega.numero_exterior),' ',
				if(ad_clientes_direcciones_entrega.numero_interior is null,'',ad_clientes_direcciones_entrega.numero_interior),' ',
				'Col. ',if(ad_clientes_direcciones_entrega.colonia is null,'',ad_clientes_direcciones_entrega.colonia),' ',
				'Del. o Mun. ',if(ad_clientes_direcciones_entrega.delegacion_municipio is null,'',
				ad_clientes_direcciones_entrega.delegacion_municipio)) AS direccion, na_productos.id_producto AS id_producto, na_productos.nombre AS producto,
				na_movimientos_almacen_detalle.cantidad AS cantidad_requerida, ad_pedidos.id_sucursal_alta AS id_sucursal, ad_pedidos.id_cliente AS id_cliente,
				ad_clientes_direcciones_entrega.id_cliente_direccion_entrega AS id_direccion_entrega, na_productos.sku AS sku,
				na_pedidos_detalle.fecha_entrega AS fecha_entrega_prod
						FROM na_movimientos_almacen_detalle
						LEFT JOIN ad_pedidos on na_movimientos_almacen_detalle.id_control_pedido=ad_pedidos.id_control_pedido
						LEFT JOIN na_pedidos_detalle ON ad_pedidos.id_control_pedido = na_pedidos_detalle.id_control_pedido
						LEFT JOIN na_productos ON na_pedidos_detalle.id_producto = na_productos.id_producto
						LEFT JOIN ad_clientes on ad_clientes.id_cliente=ad_pedidos.id_cliente
						LEFT JOIN ad_clientes_direcciones_entrega on ad_clientes_direcciones_entrega.id_cliente_direccion_entrega=ad_pedidos.id_direccion_entrega
						LEFT JOIN na_estados on ad_clientes_direcciones_entrega.id_estado=na_estados.id_estado
						LEFT JOIN na_ciudades on ad_clientes_direcciones_entrega.id_ciudad=na_ciudades.id_ciudad
						LEFT JOIN na_rutas on na_rutas.id_ruta=ad_clientes_direcciones_entrega.id_ruta
						WHERE na_productos.id_producto = na_movimientos_almacen_detalle.id_producto AND na_movimientos_almacen_detalle.id_detalle = " . $prodPedidosDatos[$i];
				
				$datos = new consultarTabla($sql);
				$result = $datos -> obtenerRegistros();
				
				foreach($result as $pedidosMuestra){
						$pedido['id_bitacora_ruta'] = $ultimo_id;
						$pedido['id_ruta'] = $pedidosMuestra -> ruta;
						$pedido['id_tipo_documento'] = 1;
						$pedido['id_realizada'] = 2;
						$pedido['id_estatus_bitacora_entrega'] = 2;
						$pedido['numero_documento'] = $pedidosMuestra -> pedido;
						$pedido['cliente_tienda'] = $pedidosMuestra -> nombre_cliente;
						$pedido['direccion_entrega_recoleccion'] = $pedidosMuestra -> direccion;
						$pedido['id_control_pedido'] = $pedidosMuestra -> control_pedido;
						$pedido['id_control_movimiento'] = $pedidosMuestra -> control_movimiento;
						$pedido['id_producto'] = $pedidosMuestra -> id_producto;
						$pedido['id_sucursal'] = $pedidosMuestra -> id_sucursal;
						$pedido['id_cliente'] = $pedidosMuestra -> id_cliente;
						$pedido['id_direccion_entrega'] = $pedidosMuestra -> id_direccion_entrega;
						$pedido['id_partida'] = $prodPedidosDatos[$i];
						$pedido['cantidad'] = $pedidosMuestra -> cantidad_requerida;
						$pedido['sku'] = $pedidosMuestra -> sku;
						$pedido['fecha_entrega'] = $pedidosMuestra -> fecha_entrega_prod;
						$pedido['activoDetBitacora'] = 1;
						
						
						accionesMysql($pedido, 'na_bitacora_rutas_entregas_detalle', 'Inserta');
						
						$actualiza1 = "UPDATE na_movimientos_almacen_detalle SET id_bitacora_ruta = " . $ultimo_id . " WHERE id_detalle = " . $prodPedidosDatos[$i];
						mysql_query($actualiza1) or die("Error en consulta:<br> $actualiza1 <br>" . mysql_error());	
						}
				}
		}
if($cuentaRec > 0){
		for($i=0; $i<$cuentaRec; $i++){
				$sqlOrdCli = "SELECT na_ordenes_recoleccion_clientes.id_orden_recoleccion_cliente, na_ordenes_recoleccion_clientes.id_pedido AS control_pedido,
				na_rutas.id_ruta AS ruta,  na_ordenes_recoleccion_clientes_producto_detalle.id_producto AS id_producto,
				fecha_recoleccion, ad_clientes_direcciones_entrega.id_cliente_direccion_entrega AS id_direccion_entrega,
				na_ordenes_recoleccion_clientes.id_cliente AS id_cliente, na_ordenes_recoleccion_clientes_producto_detalle.cantidad AS cantidad_rec,
				CONCAT(ad_clientes.nombre, ' ',  ad_clientes.apellido_paterno,' ', ad_clientes.apellido_materno) AS Cliente, ad_pedidos.id_sucursal_alta AS id_sucursal, na_productos.sku AS sku, 
				CONCAT('Calle: ',ad_clientes_direcciones_entrega.calle,' ', 'Num. Ext: ',
							if(ad_clientes_direcciones_entrega.numero_exterior is null,'',ad_clientes_direcciones_entrega.numero_exterior),
							' ',if(ad_clientes_direcciones_entrega.numero_interior is null,'',ad_clientes_direcciones_entrega.numero_interior),
							' ','Col. ',if(ad_clientes_direcciones_entrega.colonia is null,'',ad_clientes_direcciones_entrega.colonia),
							' ','Del. o Mun. ',if(ad_clientes_direcciones_entrega.delegacion_municipio is null,
							'',ad_clientes_direcciones_entrega.delegacion_municipio)) AS direccion
				FROM na_ordenes_recoleccion_clientes_producto_detalle
				LEFT JOIN na_ordenes_recoleccion_clientes USING(id_orden_recoleccion_cliente)
				LEFT JOIN ad_pedidos ON na_ordenes_recoleccion_clientes.id_pedido = ad_pedidos.id_control_pedido
				LEFT JOIN na_productos ON na_ordenes_recoleccion_clientes_producto_detalle.id_producto = na_productos.id_producto
				LEFT JOIN na_rutas ON na_ordenes_recoleccion_clientes.id_ruta = na_rutas.id_ruta 
				LEFT JOIN ad_clientes on na_ordenes_recoleccion_clientes.id_cliente=ad_clientes.id_cliente
				LEFT JOIN ad_clientes_direcciones_entrega USING(id_cliente_direccion_entrega)
				WHERE na_ordenes_recoleccion_clientes_producto_detalle.id_orden_recoleccion_cliente_producto = " . $recDatos[$i];
				$datosOrdCli = new consultarTabla($sqlOrdCli);
				$resultOrCli = $datosOrdCli -> obtenerLineaRegistro();
				
				$recoleccion['id_bitacora_ruta'] = $ultimo_id;
				$recoleccion['id_ruta'] = $resultOrCli['ruta'];
				$recoleccion['id_tipo_documento'] = 2;
				$recoleccion['id_realizada'] = 2;
				$recoleccion['id_estatus_bitacora_entrega'] = 2;
				$recoleccion['numero_documento'] = $recDatos[$i];
				$recoleccion['cliente_tienda'] = $resultOrCli['Cliente'];
				$recoleccion['direccion_entrega_recoleccion'] = $resultOrCli['direccion'];
				$recoleccion['id_orden_recoleccion'] = $recDatos[$i];
				$recoleccion['id_control_pedido'] = $resultOrCli['control_pedido'];
				$recoleccion['id_producto'] = $resultOrCli['id_producto'];
				$recoleccion['id_sucursal'] = $resultOrCli['id_sucursal'];
				$recoleccion['id_cliente'] = $resultOrCli['id_cliente'];
				$recoleccion['id_direccion_entrega'] = $resultOrCli['id_direccion_entrega'];
				$recoleccion['id_partida'] = $recDatos[$i];
				$recoleccion['cantidad'] = $resultOrCli['cantidad_rec'];
				$recoleccion['sku'] = $resultOrCli['sku'];
				$recoleccion['fecha_entrega'] = $resultOrCli['fecha_recoleccion'];
				$recoleccion['activoDetBitacora'] = 1;
				
				accionesMysql($recoleccion, 'na_bitacora_rutas_entregas_detalle', 'Inserta');
				
				$actualiza2 = "UPDATE na_ordenes_recoleccion_clientes_producto_detalle SET id_bitacora_ruta = " . $ultimo_id . " WHERE id_orden_recoleccion_cliente_producto = " .  $recDatos[$i];
				mysql_query($actualiza2) or die("Error en consulta:<br> $actualiza2 <br>" . mysql_error());
				}
		}

if($cuentaRecSucPed > 0){
		for($i=0; $i<$cuentaRecSucPed; $i++){
			$sqlOrdenes = "SELECT na_rutas.nombre AS ruta, na_ordenes_recoleccion_sucursal.id_orden_recoleccion_sucursal AS idOrden, na_rutas.id_ruta AS idruta,
			na_tipos_devoluciones.nombre AS tipo_devolucion, na_rutas.id_ruta AS idruta,
			CONCAT(fecha_propuesta_recoleccion, ' ', na_tiempos.nombre) AS fecha_hora,
			na_sucursales.nombre AS sucursal,
			CONCAT('Calle: ',na_sucursales.calle,' ', 'Num. Ext: ',if(na_sucursales.numero_exterior is null,'',na_sucursales.numero_exterior),' ',
					if(na_sucursales.numero_interior is null,'',na_sucursales.numero_interior),' ','Col. ',
					if(na_sucursales.colonia is null,'',na_sucursales.colonia),' ','Del. o Mun. ',
					if(na_sucursales.delegacion_municipio is null,'',na_sucursales.delegacion_municipio)) AS direccion_sucursal
			FROM na_ordenes_recoleccion_sucursal
			LEFT JOIN na_sucursales USING(id_sucursal)
			LEFT JOIN na_rutas ON na_sucursales.id_ruta = na_rutas.id_ruta
			LEFT JOIN na_tipos_devoluciones USING(id_tipo_devolucion)
			LEFT JOIN na_tiempos ON na_ordenes_recoleccion_sucursal.hora_propuesta_recoleccion = na_tiempos.id_tiempo
			WHERE id_estatus_devolucion = 2 AND id_bitacora_ruta IS NULL AND na_ordenes_recoleccion_sucursal.id_orden_recoleccion_sucursal = " . $recDatosSucPed[$i];
				$datos = new consultarTabla($sqlOrdenes);
				$result = $datos -> obtenerLineaRegistro();
				
				$recoleccion['id_bitacora_ruta'] = $ultimo_id;
				$recoleccion['id_ruta'] = $result['idruta'];
				$recoleccion['id_tipo_documento'] = 4;
				$recoleccion['numero_documento'] = $recDatosSucPed[$i];
				$recoleccion['cliente_tienda'] = $result['sucursal'];
				$recoleccion['direccion_entrega_recoleccion'] = $result['direccion_sucursal'];
				$recoleccion['id_orden_recoleccion'] = $recDatosSucPed[$i];
				accionesMysql($recoleccion, 'na_bitacora_rutas_entregas_detalle', 'Inserta');
				
				$actualiza22 = "UPDATE na_ordenes_recoleccion_sucursal SET id_bitacora_ruta = " . $ultimo_id . " WHERE id_orden_recoleccion_sucursal = " .  $recDatosSucPed[$i];
				mysql_query($actualiza22) or die("Error en consulta:<br> $actualiza22 <br>" . mysql_error());
				}
		}
if($cuentaRecSucPed2 > 0){
		for($i=0; $i<$cuentaRecSucPed2; $i++){
			$sqlOrdenes = "SELECT na_rutas.nombre AS ruta, na_ordenes_recoleccion_pedidos.id_orden_recoleccion_pedido AS idOrden, 'RECOLECCION POR PEDIDO' AS tipo_devolucion,
						CONCAT(fecha_recoleccion, ' ', na_tiempos.nombre) AS fecha_hora, na_rutas.id_ruta AS idruta,
						na_sucursales.nombre AS sucursal,
						CONCAT('Calle: ',na_sucursales.calle,' ', 'Num. Ext: ',if(na_sucursales.numero_exterior is null,'',na_sucursales.numero_exterior),' ',
								if(na_sucursales.numero_interior is null,'',na_sucursales.numero_interior),' ','Col. ',
								if(na_sucursales.colonia is null,'',na_sucursales.colonia),' ','Del. o Mun. ',
								if(na_sucursales.delegacion_municipio is null,'',na_sucursales.delegacion_municipio)) AS direccion_sucursal
			FROM na_ordenes_recoleccion_pedidos
			LEFT JOIN na_sucursales USING(id_sucursal)
			LEFT JOIN na_rutas ON na_sucursales.id_ruta = na_rutas.id_ruta
			LEFT JOIN na_tiempos ON na_ordenes_recoleccion_pedidos.hora_recoleccion = na_tiempos.id_tiempo
			WHERE id_bitacora_ruta IS NULL AND na_ordenes_recoleccion_pedidos.id_orden_recoleccion_pedido = " . $recDatosSucPed2[$i];
				$datos = new consultarTabla($sqlOrdenes);
				$result = $datos -> obtenerLineaRegistro();
				
				$recoleccion['id_bitacora_ruta'] = $ultimo_id;
				$recoleccion['id_ruta'] = $result['idruta'];
				$recoleccion['id_tipo_documento'] = 4;
				$recoleccion['numero_documento'] = $recDatosSucPed2[$i];
				$recoleccion['cliente_tienda'] = $result['sucursal'];
				$recoleccion['direccion_entrega_recoleccion'] = $result['direccion_sucursal'];
				$recoleccion['id_orden_recoleccion'] = $recDatosSucPed2[$i];
				accionesMysql($recoleccion, 'na_bitacora_rutas_entregas_detalle', 'Inserta');
				
				$actualiza2 = "UPDATE na_ordenes_recoleccion_pedidos SET id_bitacora_ruta = " . $ultimo_id . " WHERE id_orden_recoleccion_pedido = " .  $recDatosSucPed2[$i];
				mysql_query($actualiza2) or die("Error en consulta:<br> $actualiza2 <br>" . mysql_error());
				
				}
		}

$redirecciona = ROOTURL . "code/general/encabezados.php?t=bmFfYml0YWNvcmFfcnV0YXM=&k=" . $ultimo_id . "&op=2&v=0&tabla=&cadP1=MDI0ZG5CbGZqRjhibkJsZmpGOGJYQmxmakY4MzQ=&cadP2=MDI0WlhCbGZqRjhhWEJsZmpGOFozQmxmakU9MzQ=";		



echo "Operacion realizada con exito|" . $redirecciona;




?>