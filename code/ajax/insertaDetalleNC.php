<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$productos = $_POST['detalle_productos'];

$sql = "SELECT id_detalle_factura AS id_factura, na_facturas_detalle.id_producto AS id_producto, na_productos.nombre AS producto, 
		IF(na_facturas_detalle.descripcion IS NULL, '', na_facturas_detalle.descripcion) AS descripcion, na_facturas_detalle.cantidad AS cantidad,
		na_facturas_detalle.precio_unitario AS precio_unitario, CONCAT('$', FORMAT(na_facturas_detalle.precio_unitario, 2)) AS precio_unitario_muestra,
		na_facturas_detalle.importe AS importe, CONCAT('$', FORMAT(na_facturas_detalle.importe, 2)) AS importe_muestra,
		IF(na_facturas_detalle.observaciones IS NULL, '', na_facturas_detalle.observaciones) AS observaciones, 
		na_facturas_detalle.iva AS iva, na_facturas_detalle.monto_iva AS monto_iva, na_facturas_detalle.id_control_factura AS factura
		FROM na_facturas_detalle 
		LEFT JOIN na_productos ON na_facturas_detalle.id_producto = na_productos.id_producto
		WHERE id_detalle_factura IN(" . $productos . ")";
$result = new consultarTabla($sql);
$datos = $result -> obtenerRegistros();

foreach($datos as $detalles){
		$registros[] = array(
				'id_factura' => $detalles -> id_factura,
				'id_producto' => $detalles -> id_producto,
				'muestraProducto' => $detalles -> id_producto . ":" . $detalles -> producto,
				'producto' => $detalles -> producto,
				'descripcion' => $detalles -> descripcion,
				'cantidad' => $detalles -> cantidad,
				'precio_unitario' => $detalles -> precio_unitario,
				'precio_unitario_muestra' => $detalles -> precio_unitario_muestra,
				'importe' => $detalles -> importe,
				'importe_muestra' => $detalles -> importe_muestra,
				'observaciones' => $detalles -> observaciones,
				'iva' => $detalles -> iva,
				'monto_iva' => $detalles -> monto_iva,
				'factura' => $detalles -> factura
				);
		}

echo json_encode($registros);


?>