<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_control_serie = $_GET['id_control_serie'];
$sql = "SELECT";
$sql .= " DISTINCT(cl_control_series.id_control_serie),";
$sql .= " cl_control_series.id_producto_servicio,";
$sql .= " cl_control_series.numero_serie,";
$sql .= " DATE_FORMAT(cl_control_series.fecha_alta, '%d/%m/%Y'),";
$sql .= " DATE_FORMAT(cl_control_series.fecha_asignacion, '%d/%m/%Y'),";
$sql .= " cl_control_series.id_almacen_ingreso,";
$sql .= " cl_control_series.id_control_orden_compra,";
$sql .= " cl_control_series.id_carga,";
$sql .= " cl_control_series.id_usuario_alta,";
$sql .= " cl_control_series.id_usuario_modifico,";
$sql .= " DATE_FORMAT(cl_control_series.fecha_modificacion, '%d/%m/%Y'),";
$sql .= " cl_control_series.id_usuario_cancelo,";
$sql .= " cl_control_series.fecha_cancelacion,";
$sql .= " cl_control_series.activo,";
$sql .= " DATE_FORMAT(now(), '%d/%m/%Y'),";
$sql .= " DATEDIFF(now(), cl_control_series.fecha_asignacion),";
$sql .= " IFNULL(";
$sql .= " (";
$sql .= " SELECT codigo_color";
$sql .= " FROM cl_rangos_irds";
$sql .= " INNER JOIN cl_colores";
$sql .= " ON cl_rangos_irds.id_color = cl_colores.id_color";
$sql .= " WHERE DATEDIFF(now(), cl_control_series.fecha_asignacion) BETWEEN dia_inicial AND dia_final";
$sql .= " ),";
$sql .= " '#000000') codigo_color,";
$sql .= " ad_almacenes.id_almacen,";
$sql .= " ad_almacenes.nombre";
$sql .= " FROM cl_control_series";
$sql .= " INNER JOIN cl_control_series_detalle";
$sql .= " ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie";

$sql .= " INNER JOIN ad_almacenes";
$sql .= " ON cl_control_series_detalle.id_almacen = ad_almacenes.id_almacen";

$sql .= " WHERE cl_control_series.id_control_serie = '".$id_control_serie."'";
//$sql .= " AND cl_control_series_detalle.id_estatus = '2'";	//CHECAR ESTOOOOOOOOOOOOOO000000000000000
$sql .= " AND cl_control_series.activo = '1'";
$sql .= " AND cl_control_series_detalle.activo = '1';";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
$smarty -> assign("sql", $sql);
echo json_encode($smarty->fetch('especiales/respuestaMuestraEstatusIRDs.tpl'));
?>