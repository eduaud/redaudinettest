<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sql = "SELECT DISTINCT(ad_proveedores.id_proveedor) id_proveedor, ad_proveedores.razon_social";
$sql .= " FROM ad_proveedores";
$sql .= " INNER JOIN ad_movimientos_almacen";
$sql .= " ON ad_proveedores.id_proveedor = ad_movimientos_almacen.id_proveedor";
$sql .= " WHERE ad_movimientos_almacen.id_subtipo_movimiento = '70002'";
$sql .= " AND IFNULL(ad_movimientos_almacen.id_costeo_productos,0) = '0'";
$sql .= " AND ad_movimientos_almacen.activo = '1'";
$sql .= " AND ad_proveedores.activo = '1'";
$sql .= " ORDER BY ad_proveedores.razon_social;";
$datosProvedor = new consultarTabla($sql);
$provedor = $datosProvedor -> obtenerArregloRegistros();


$sql = "SELECT DISTINCT(ad_almacenes.id_almacen) id_almacen, ad_almacenes.nombre";
$sql .= " FROM ad_almacenes";
$sql .= " left JOIN ad_movimientos_almacen";
$sql .= " ON ad_almacenes.id_almacen = ad_movimientos_almacen.id_almacen";
$sql .= " WHERE ad_movimientos_almacen.id_subtipo_movimiento = '70002'";
$sql .= " AND IFNULL(ad_movimientos_almacen.id_costeo_productos,0) = '0'";
$sql .= " AND ad_movimientos_almacen.activo = '1'";
$sql .= " ORDER BY ad_almacenes.nombre;";
$datosAlmacen = new consultarTabla($sql);
$almacen = $datosAlmacen -> obtenerArregloRegistros();

$smarty -> assign("proveedor", $provedor);
$smarty -> assign("almacen", $almacen);
$smarty -> display("especiales/generacionCosteo.tpl");

?>