<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_detalle = $_GET['id_detalle'];
$sql = "SELECT id_producto_servicio, nombre FROM cl_productos_servicios WHERE activo = '1' AND id_clasificacion = '2';";
$datosProductos = new consultarTabla($sql);
$resultProductos = $datosProductos -> obtenerArregloRegistros();
$numeroDeRegistrosProductos = count($resultProductos);
$smarty -> assign("filasProductos", $resultProductos);
$smarty -> assign("numeroDeRegistrosProductos", $numeroDeRegistrosProductos);
$smarty -> assign("sql", $sql);
$smarty->assign("idDetalle", $id_detalle);
echo json_encode($smarty->fetch('especiales/respuestaMuestraListaRenglones.tpl'));
/*
$sqlNS = "SELECT numero_serie FROM cl_control_contratos_detalles WHERE id_detalle LIKE '%".$id_detalle."%' AND cl_control_contratos_detalles.activo = '1';";
$datosNS = new consultarTabla($sqlNS);
$resultNS = $datosNS -> obtenerArregloRegistros();
$numeroDeRegistrosNS = count($resultNS);
$smarty -> assign("filasirds", $resultNS);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistrosNS);
$smarty -> assign("sql", $sqlNS);
*/
?>