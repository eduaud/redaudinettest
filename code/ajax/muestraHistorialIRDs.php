<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_control_serie = $_GET['id_control_serie'];
$sql = "SELECT";
$sql .= " cl_control_series_detalle.id_estatus,";
$sql .= " cl_control_series_detalle.fecha_movimiento,";
$sql .= " cl_control_series_detalle.id_tipo_movimiento,";
$sql .= " cl_control_series_detalle.id_tipo_documento,";
$sql .= " cl_control_series_detalle.id_control_documento,";
$sql .= " cl_control_series_detalle.id_almacen,";
$sql .= " cl_control_series_detalle.id_cliente,";
$sql .= " cl_control_series_detalle.id_plaza,";
$sql .= " DATE_FORMAT(cl_control_series_detalle.fecha_alta, '%d/%m/%Y'),";
$sql .= " DATE_FORMAT(cl_control_series_detalle.fecha_modificacion, '%d/%m/%Y'),";
$sql .= " DATE_FORMAT(cl_control_series_detalle.id_usuario_cancelo, '%d/%m/%Y'),";
$sql .= " DATE_FORMAT(cl_control_series_detalle.fecha_cancelacion, '%d/%m/%Y'),";
$sql .= " ad_almacenes.nombre";
$sql .= " FROM cl_control_series_detalle";
$sql .= " INNER JOIN ad_almacenes";
$sql .= " ON cl_control_series_detalle.id_almacen = ad_almacenes.id_almacen";
$sql .= " WHERE cl_control_series_detalle.id_control_serie = '".$id_control_serie."'";
$sql .= " AND cl_control_series_detalle.activo = '1';";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
echo json_encode($smarty->fetch('especiales/respuestaMuestraHistorialIRDs.tpl'));
?>