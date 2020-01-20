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

// SE OBTIENEN LOS TIPOS DE PRODUCTOS SERVICIOS --------------------
$sql = "SELECT id_tipo_producto_servicio, nombre";
$sql .= " FROM cl_tipos_producto_servicio";
$sql .= " WHERE activo = 1";
$sql .= " ORDER BY nombre";
$datosProductoServicio = new consultarTabla($sql);
$result = $datosProductoServicio -> obtenerArregloRegistros();
$smarty -> assign("producto", $result);

//SE OBTIENEN LAS REQUISICIONES PENDIENTES DE APROBACION -------------------------
$sql = "SELECT";
$sql .= " ad_requisiciones.id_requisicion,";
$sql .= " cl_tipos_producto_servicio.nombre,";
$sql .= " cl_productos_servicios.clave,";
$sql .= " cl_productos_servicios.nombre,";
$sql .= " ad_requisiciones_detalle.cantidad,";
$sql .= " ad_sucursales.nombre,";
$sql .= " ad_requisiciones_detalle.id_distribuidor_solicita,";
$sql .= " CONCAT(sys_usuarios.nombres, sys_usuarios.apellido_paterno, sys_usuarios.apellido_materno) nombre,";
$sql .= " DATE_FORMAT(ad_requisiciones.fecha_de_creacion,'%d/%m/%Y') fecha_de_creacion,";
$sql .= " DATE_FORMAT(ad_requisiciones.fecha_requerida,'%d/%m/%Y') fecha_requerida";
$sql .= " FROM ad_requisiciones";
$sql .= " LEFT JOIN ad_requisiciones_detalle";
$sql .= " ON ad_requisiciones.id_requisicion = ad_requisiciones_detalle.id_requisicion";
$sql .= " LEFT JOIN cl_productos_servicios";
$sql .= " ON ad_requisiciones_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
$sql .= " LEFT JOIN cl_tipos_producto_servicio";
$sql .= " ON cl_productos_servicios.id_tipo_producto_servicio = cl_tipos_producto_servicio.id_tipo_producto_servicio";
$sql .= " LEFT JOIN ad_sucursales";
$sql .= " ON ad_requisiciones.id_sucursal = ad_sucursales.id_sucursal";
$sql .= " LEFT JOIN sys_usuarios";
$sql .= " ON ad_requisiciones.id_usuario_solicita = sys_usuarios.id_usuario";
$sql .= " WHERE ad_requisiciones.id_estatus_requisicion = 2"; // 2:ID del estatus en Espera de Aprobación
$sql .= " AND ad_requisiciones_detalle.activo = 1";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);

$smarty->display("especiales/generacionOrdenCompra.tpl");
?>