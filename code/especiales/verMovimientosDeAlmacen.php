<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$id_control_movimiento=$_GET["id_control_movimiento"];

$smarty->assign("id_control_movimiento",$id_control_movimiento);


$sQuery = " SELECT";
$sQuery .= " ad_movimientos_almacen.id_movimiento,";
$sQuery .= " ad_movimientos_almacen.id_almacen,";
$sQuery .= " ad_almacenes.nombre,";
$sQuery .= " ad_movimientos_almacen.fecha_movimiento,";
$sQuery .= " ad_movimientos_almacen.hora_movimiento,";
$sQuery .= " ad_movimientos_almacen.id_proveedor,";
$sQuery .= " ad_proveedores.razon_social,";
$sQuery .= " ad_movimientos_almacen_detalle.id_producto,";
$sQuery .= " cl_productos_servicios.nombre,";
$sQuery .= " ad_movimientos_almacen_detalle.cantidad";
$sQuery .= " FROM ad_movimientos_almacen";
$sQuery .= " INNER JOIN ad_movimientos_almacen_detalle";
$sQuery .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
$sQuery .= " INNER JOIN ad_almacenes";
$sQuery .= " ON ad_movimientos_almacen.id_almacen = ad_almacenes.id_almacen";
$sQuery .= " INNER JOIN ad_proveedores";
$sQuery .= " ON ad_movimientos_almacen.id_proveedor = ad_proveedores.id_proveedor";
$sQuery .= " INNER JOIN cl_productos_servicios";
$sQuery .= " ON ad_movimientos_almacen_detalle.id_producto_origen = cl_productos_servicios.id_producto_servicio";
$sQuery .= " WHERE ad_movimientos_almacen.activo = '1'";
$sQuery .= " AND ad_movimientos_almacen.id_tipo_movimiento = '1'";
$sQuery .= " AND ad_movimientos_almacen.id_control_movimiento = '".$id_control_movimiento."';";
$datos = new consultarTabla($sQuery);
$detalles = $datos -> obtenerArregloRegistros();

$smarty->assign('detalles',$detalles);
$smarty->assign('sQuery',$sQuery);

$smarty -> display("especiales/verMovimientosDeAlmacen.tpl");

?>