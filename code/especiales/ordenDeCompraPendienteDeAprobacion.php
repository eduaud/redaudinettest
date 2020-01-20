<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
//mysql_query("SET NAMES utf8");

// SE OBTIENEN LAS SUCURSALES -------------------------------
$sql = "SELECT id_sucursal,nombre FROM ad_sucursales WHERE activo=1 ORDER BY nombre";
$datosSucursal = new consultarTabla($sql);
$result = $datosSucursal -> obtenerArregloRegistros();
$smarty -> assign("sucursal", $result);

// SE OBTIENEN LOS USUARIOS SOLICITANTES --------------------
$sql = "SELECT id_usuario, CONCAT(nombres,' ',apellido_paterno,' ',apellido_materno) 'Nombre'";
$sql .= " FROM sys_usuarios";
$sql .= " WHERE activo = 1";
$sql .= " ORDER BY nombre";
$datosUsuarios = new consultarTabla($sql);
$result = $datosUsuarios -> obtenerArregloRegistros();
$smarty -> assign("usuario", $result);

//SE OBTIENEN LAS ORDENES DE COMPRA PENDIENTES DE APROBACION -------------------------
// **************** HAY QUE MODIFICAR ESTE QUERY ****************
/*
$sql = "SELECT";
$sql .= " DISTINCT(ad_ordenes_compra_productos.id_control_orden_compra),";
$sql .= " ad_sucursales.nombre,";
$sql .= " ad_ordenes_compra_productos.id_orden_compra,";
$sql .= " ad_proveedores.razon_social,";
$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_generacion,'%d/%m/%Y'),";
$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_entrega,'%d/%m/%Y'),";
$sql .= " ad_ordenes_compra_productos.id_usuario_solicita,";
$sql .= " FORMAT(ad_ordenes_compra_productos.total,2),";
$sql .= " DATE_FORMAT(fecha_entrega_requerida,'%d/%m/%Y'),";
$sql .= " concat(apellido_paterno, ' ', apellido_materno, ' ', nombres) Nombre,";
$sql .= " ad_ordenes_compra_productos.id_control_orden_compra";
$sql .= " FROM ad_ordenes_compra_productos";
$sql .= " LEFT JOIN ad_proveedores";
$sql .= " ON ad_ordenes_compra_productos.id_proveedor = ad_proveedores.id_proveedor";
$sql .= " LEFT JOIN ad_sucursales";
$sql .= " ON ad_ordenes_compra_productos.id_sucursal = ad_sucursales.id_sucursal";
$sql .= " LEFT JOIN sys_usuarios";
$sql .= " ON ad_ordenes_compra_productos.id_usuario_solicita = sys_usuarios.id_usuario";
$sql .= " WHERE ad_ordenes_compra_productos.id_estatus = 1;";
*/
$sql = " SELECT";
$sql .= " DISTINCT(ad_ordenes_compra_productos.id_control_orden_compra),";
$sql .= " ad_sucursales.nombre,";
$sql .= " ad_ordenes_compra_productos.id_orden_compra,";
$sql .= " ad_proveedores.razon_social,";
$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_generacion,'%d/%m/%Y'),";
$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_entrega,'%d/%m/%Y'),";
$sql .= " ad_ordenes_compra_productos.id_usuario_solicita,";
$sql .= " FORMAT(ad_ordenes_compra_productos.total,2),";
$sql .= " DATE_FORMAT(fecha_entrega_requerida,'%d/%m/%Y'),";
$sql .= " concat(apellido_paterno, ' ', apellido_materno, ' ', nombres) Nombre,";
$sql .= " ad_ordenes_compra_productos.id_control_orden_compra";
$sql .= " FROM ad_ordenes_compra_productos";
$sql .= " LEFT JOIN ad_ordenes_compra_productos_detalle";
$sql .= " ON ad_ordenes_compra_productos.id_control_orden_compra = ad_ordenes_compra_productos_detalle.id_control_orden_compra";
$sql .= " LEFT JOIN ad_proveedores";
$sql .= " ON ad_ordenes_compra_productos.id_proveedor = ad_proveedores.id_proveedor";
$sql .= " LEFT JOIN ad_sucursales";
$sql .= " ON ad_ordenes_compra_productos.id_sucursal = ad_sucursales.id_sucursal";
$sql .= " LEFT JOIN sys_usuarios";
$sql .= " ON ad_ordenes_compra_productos.id_usuario_solicita = sys_usuarios.id_usuario";
$sql .= " WHERE ad_ordenes_compra_productos.id_estatus = 1";
$sql .= " GROUP BY ad_ordenes_compra_productos.id_control_orden_compra";
/*

$sql = "SELECT";
$sql .= " DISTINCT(ad_ordenes_compra_productos.id_control_orden_compra),";
$sql .= " ad_sucursales.nombre,";
$sql .= " ad_ordenes_compra_productos.id_orden_compra,";
$sql .= " ad_proveedores.razon_social,";
$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_generacion,'%d/%m/%Y'),";
$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_entrega,'%d/%m/%Y'),";
$sql .= " ad_ordenes_compra_productos.id_usuario_solicita,";
$sql .= " FORMAT(ad_ordenes_compra_productos.total,2),";
$sql .= "'23/10/2015' fecha_requerida, ";

//$sql .= " 
//			(
//			SELECT DATE_FORMAT(fecha_requerida,'%d/%m/%Y') FROM ad_requisiciones WHERE id_requisicion in (
//			SELECT DISTINCT(id_requisicion) id_requisicion
//			FROM ad_requisiciones_detalle
//			INNER JOIN ad_ordenes_compra_productos
//			ON ad_requisiciones_detalle.id_orden_de_compra_relacionada = ad_ordenes_compra_productos.id_control_orden_compra
//			)
//			) fecha_requerida,
//";

$sql .= " concat(apellido_paterno, ' ', apellido_materno, ' ', nombres) Nombre,";
$sql .= " ad_ordenes_compra_productos.id_control_orden_compra";

$sql .= " FROM ad_ordenes_compra_productos";
$sql .= " LEFT JOIN ad_proveedores";
$sql .= " ON ad_ordenes_compra_productos.id_proveedor = ad_proveedores.id_proveedor";
$sql .= " LEFT JOIN ad_sucursales";
$sql .= " ON ad_ordenes_compra_productos.id_sucursal = ad_sucursales.id_sucursal";

$sql .= " LEFT JOIN sys_usuarios";
$sql .= " ON ad_ordenes_compra_productos.id_usuario_solicita = sys_usuarios.id_usuario";

$sql .= " WHERE ad_ordenes_compra_productos.id_estatus = 1;";
*/
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);

$smarty -> display("especiales/ordenDeCompraPendienteDeAprobacion.tpl");
?>