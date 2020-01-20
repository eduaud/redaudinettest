<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");


/*******************************Clientes***************************************/
$sql = "SELECT DISTINCT ad_pedidos.id_cliente, CONCAT(ad_clientes.nombre, ' ',  ad_clientes.apellido_paterno,' ', ad_clientes.apellido_materno) AS Cliente 
		FROM ad_pedidos
		LEFT JOIN ad_clientes USING(id_cliente)
		WHERE id_estatus_pedido = 1 OR id_estatus_pedido = 3";
$datosCliente = new consultarTabla($sql);
$cliente = $datosCliente -> obtenerArregloRegistros();


/*******************************Rutas***************************************/
$sql = "SELECT id_ruta, nombre FROM na_rutas WHERE activo = 1 ORDER BY nombre";
$datosRuta = new consultarTabla($sql);
$ruta = $datosRuta -> obtenerArregloRegistros();


/*******************************Generacion de pedidos liquidados***************************************/

$sqlPedidos = "SELECT na_movimientos_almacen_detalle.id_detalle AS detalle_movimiento, id_movimiento AS id_movimiento,
		na_rutas.nombre as ruta_nombre, ad_pedidos.id_pedido AS pedido, 
		CONCAT(DATE_FORMAT(fecha_movimiento , '%d/%m/%Y'), ' ',  hora_movimiento) AS fecha_hora,
		na_productos.nombre AS producto, na_productos.sku AS sku, na_movimientos_almacen_detalle.cantidad AS cantidad_requerida, 
		na_movimientos_almacen.id_control_movimiento AS control_movimiento
FROM na_movimientos_almacen_detalle
LEFT JOIN na_movimientos_almacen USING(id_control_movimiento)
LEFT JOIN na_tiempos ON na_movimientos_almacen.hora_movimiento = na_tiempos.id_tiempo
LEFT JOIN ad_pedidos ON na_movimientos_almacen_detalle.id_control_pedido = ad_pedidos.id_control_pedido
LEFT JOIN na_pedidos_detalle ON ad_pedidos.id_control_pedido = na_pedidos_detalle.id_control_pedido
LEFT JOIN na_productos ON na_pedidos_detalle.id_producto = na_productos.id_producto
LEFT JOIN ad_clientes ON ad_clientes.id_cliente = ad_pedidos.id_cliente
LEFT JOIN na_clientes_direcciones_entrega ON na_clientes_direcciones_entrega.id_cliente_direccion_entrega=ad_pedidos.id_direccion_entrega
LEFT JOIN na_rutas ON na_rutas.id_ruta=na_clientes_direcciones_entrega.id_ruta
WHERE id_subtipo_movimiento=70099 
AND (na_movimientos_almacen_detalle.id_bitacora_ruta = 0 OR na_movimientos_almacen_detalle.id_bitacora_ruta IS NULL OR na_movimientos_almacen_detalle.id_bitacora_ruta = '')
AND na_productos.id_producto = na_movimientos_almacen_detalle.id_producto ORDER BY fecha_movimiento ASC, hora_movimiento ASC, pedido ASC";
$datosPedidos = new consultarTabla($sqlPedidos);
$pedido = $datosPedidos -> obtenerArregloRegistros();

/*******************************Ordenes de recoleccion a clientes***************************************/

$sqlRecCli = "SELECT id_orden_recoleccion_cliente_producto AS detalle_recoleccion, id_orden_recoleccion_cliente AS id_recoleccion, na_rutas.nombre AS ruta, 
				ad_pedidos.id_pedido AS id_pedido, DATE_FORMAT(fecha_recoleccion , '%d/%m/%Y') AS fecha, na_productos.nombre AS producto, 
				na_productos.sku AS sku, na_ordenes_recoleccion_clientes_producto_detalle.cantidad AS cantidad_requerida
				FROM na_ordenes_recoleccion_clientes_producto_detalle
				LEFT JOIN na_ordenes_recoleccion_clientes USING(id_orden_recoleccion_cliente)
				LEFT JOIN na_productos USING(id_producto)
				LEFT JOIN na_rutas USING(id_ruta)
				LEFT JOIN ad_pedidos ON na_ordenes_recoleccion_clientes.id_pedido = ad_pedidos.id_control_pedido
				WHERE (na_ordenes_recoleccion_clientes_producto_detalle.id_bitacora_ruta = 0 OR na_ordenes_recoleccion_clientes_producto_detalle.id_bitacora_ruta IS NULL 
				OR na_ordenes_recoleccion_clientes_producto_detalle.id_bitacora_ruta = '') AND id_estatus_orden_recoleccion = 2";
$datosRecCli = new consultarTabla($sqlRecCli);
$recoleccionClientes = $datosRecCli -> obtenerArregloRegistros();

/*******************************Solicitud de Devolucion a Cedis***************************************/
$sqlOrdenes = "SELECT na_rutas.nombre AS ruta, na_ordenes_recoleccion_sucursal.id_orden_recoleccion_sucursal AS idOrden,
			na_tipos_devoluciones.nombre AS tipo_devolucion,
			CONCAT(DATE_FORMAT(fecha_propuesta_recoleccion , '%d/%m/%Y'), ' ', na_tiempos.nombre) AS fecha_hora,
			na_sucursales.nombre AS sucursal,
			CONCAT('Calle: ',na_sucursales.calle,' ', 'Num. Ext: ',if(na_sucursales.numero_exterior is null,'',na_sucursales.numero_exterior),' ',
					if(na_sucursales.numero_interior is null,'',na_sucursales.numero_interior),' ','Col. ',
					if(na_sucursales.colonia is null,'',na_sucursales.colonia),' ','Del. o Mun. ',
					if(na_sucursales.delegacion_municipio is null,'',na_sucursales.delegacion_municipio)) AS direccion_sucursal,
			na_ordenes_recoleccion_sucursal.observaciones AS observaciones
FROM na_ordenes_recoleccion_sucursal
LEFT JOIN na_sucursales USING(id_sucursal)
LEFT JOIN na_rutas ON na_sucursales.id_ruta = na_rutas.id_ruta
LEFT JOIN na_tipos_devoluciones USING(id_tipo_devolucion)
LEFT JOIN na_tiempos ON na_ordenes_recoleccion_sucursal.hora_propuesta_recoleccion = na_tiempos.id_tiempo
WHERE id_estatus_devolucion = 2 AND id_bitacora_ruta IS NULL";
$datosRecSucPed = new consultarTabla($sqlOrdenes);
$recoleccionSucPed = $datosRecSucPed -> obtenerArregloRegistros();


$sqlOrdenesPedidos = "SELECT na_rutas.nombre AS ruta, na_ordenes_recoleccion_pedidos.id_orden_recoleccion_pedido AS idOrden, 'RECOLECCION POR PEDIDO' AS tipo_devolucion,
			CONCAT(DATE_FORMAT(fecha_recoleccion , '%d/%m/%Y'), ' ', na_tiempos.nombre) AS fecha_hora,
			na_sucursales.nombre AS sucursal,
			CONCAT('Calle: ',na_sucursales.calle,' ', 'Num. Ext: ',if(na_sucursales.numero_exterior is null,'',na_sucursales.numero_exterior),' ',
					if(na_sucursales.numero_interior is null,'',na_sucursales.numero_interior),' ','Col. ',
					if(na_sucursales.colonia is null,'',na_sucursales.colonia),' ','Del. o Mun. ',
					if(na_sucursales.delegacion_municipio is null,'',na_sucursales.delegacion_municipio)) AS direccion_sucursal
FROM na_ordenes_recoleccion_pedidos
LEFT JOIN na_sucursales USING(id_sucursal)
LEFT JOIN na_rutas ON na_sucursales.id_ruta = na_rutas.id_ruta
LEFT JOIN na_tiempos ON na_ordenes_recoleccion_pedidos.hora_recoleccion = na_tiempos.id_tiempo
WHERE id_bitacora_ruta IS NULL";
$datosRecSucPed2 = new consultarTabla($sqlOrdenesPedidos);
$recoleccionSucPed2 = $datosRecSucPed2 -> obtenerArregloRegistros();


$rutaOrPed = ROOTURL . "code/general/encabezados.php?t=bmFfb3JkZW5lc19yZWNvbGVjY2lvbl9wZWRpZG9z1&op=2&v=1&k=";
$rutaOrSuc = ROOTURL . "code/general/encabezados.php?t=bmFfb3JkZW5lc19yZWNvbGVjY2lvbl9zdWN1cnNhbA==&op=2&v=1&k=";
$rutaOrRecCliente = ROOTURL . "code/general/encabezados.php?t=bmFfb3JkZW5lc19yZWNvbGVjY2lvbl9jbGllbnRlcw==&op=2&v=1&k=";
$rutaSalidaVenta= ROOTURL . "code/general/encabezados.php?stm=70099&t=bmFfbW92aW1pZW50b3NfYWxtYWNlbg==&op=2&v=1&k=";

$smarty -> assign("cliente", $cliente);

$smarty -> assign("rutaOrPed", $rutaOrPed);
$smarty -> assign("rutaOrSuc", $rutaOrSuc);
$smarty -> assign("rutaOrRecCliente", $rutaOrRecCliente);
$smarty -> assign("rutaAlmacen", $rutaSalidaVenta);

$smarty -> assign("rutas", $ruta);
$smarty -> assign("filasPedido", $pedido);
$smarty -> assign("filasRec", $recoleccionClientes);
$smarty -> assign("filasRecSucPed", $recoleccionSucPed);
$smarty -> assign("filasRecSucPed2", $recoleccionSucPed2);
$smarty -> display("especiales/armadoBitacora.tpl");

?>