<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idSucursal = $_GET['idSucursal'];

$sql = "SELECT";
$sql .= " cl_control_series.id_control_serie,";
$sql .= " cl_control_series.numero_serie,";
$sql .= " cl_control_series.id_producto_servicio,";
$sql .= " cl_productos_servicios.nombre,";
$sql .= " DATE_FORMAT(cl_control_series.fecha_alta, '%d/%m/%Y')";
$sql .= " FROM cl_control_series";
$sql .= " LEFT JOIN cl_control_series_detalle";
$sql .= " ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie";
$sql .= " LEFT JOIN cl_productos_servicios";
$sql .= " ON cl_control_series.id_producto_servicio = cl_productos_servicios.id_producto_servicio";
$sql .= " WHERE cl_control_series_detalle.id_plaza = '".$idSucursal."'";
$sql .= " AND cl_control_series_detalle.id_estatus = '1'";	//Son los que aun no testan ligados a pipeline
$sql .= " AND cl_control_series_detalle.activo = '1';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);

/*
$sql = "SELECT";
$sql .= " cl_importacion_pipeline.t8";
$sql .= " FROM cl_importacion_pipeline";
//$sql .= " WHERE";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas2", $result);
$smarty -> assign("numeroDeRegistros2", $numeroDeRegistros);
*/
echo json_encode($smarty->fetch('especiales/respuestaMuestraIRDsSinPipeline.tpl'));
?>