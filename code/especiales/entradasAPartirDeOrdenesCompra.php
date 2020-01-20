<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sql = "SELECT";
$sql .= " DISTINCT ad_ordenes_compra_productos.id_proveedor,";
$sql .= " ad_proveedores.razon_social";
$sql .= " FROM ad_ordenes_compra_productos";
$sql .= " LEFT JOIN ad_proveedores USING(id_proveedor)";
//$sql .= " WHERE ad_ordenes_compra_productos.id_estatus = 2";	//Aprobada
$sql .= " WHERE (ad_ordenes_compra_productos.id_estatus = 2 || ad_ordenes_compra_productos.id_estatus = 4)";
$sql .= " ORDER BY ad_proveedores.razon_social";
$datosProvedor = new consultarTabla($sql);
$provedor = $datosProvedor -> obtenerArregloRegistros();

$smarty -> assign("proveedor", $provedor);
$smarty -> display("especiales/entradasAPartirDeOrdenesCompra.tpl");

?>