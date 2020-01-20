<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idSucursal = $_GET['idSucursal'];
$opcion = $_GET['opcion'];

if($opcion == 'pipeSinAsignar'){
	/*$sql = "SELECT";
	$sql .= " cl_importacion_pipeline.id_control,";
	$sql .= " cl_importacion_pipeline.id_layout,";
	$sql .= " cl_importacion_pipeline.id_carga,";
	$sql .= " UPPER(cl_importacion_pipeline.t48) IRDS,";
	$sql .= " DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%d/%m/%Y') FCarga,";
	$sql .= " DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%H:%i:%s') HCarga,";
	$sql .= " 'PENDIENTE POR ASIGNAR' Estatus";
	$sql .= " FROM cl_importacion_pipeline";
	$sql .= " WHERE cl_importacion_pipeline.activo = '1'";
	$sql .= " ORDER BY cl_importacion_pipeline.t48, cl_importacion_pipeline.fecha_carga;";*/
	$sql = "SELECT cl_importacion_pipeline.id_control,cl_importacion_pipeline.id_layout,cl_importacion_pipeline.id_carga,UPPER(cl_importacion_pipeline.t48) IRDS,
						DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%d/%m/%Y') FCarga,DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%H:%i:%s') HCarga
			FROM cl_importacion_pipeline
			WHERE cl_importacion_pipeline.activo = '1' and cl_importacion_pipeline.t48 IN (
						SELECT cl_control_series.numero_serie 
						FROM cl_control_series
						LEFT JOIN cl_control_series_detalle
						ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie
						WHERE cl_control_series_detalle.activo = 1 AND cl_control_series_detalle.id_estatus = 1
					)
			ORDER BY cl_importacion_pipeline.t48, cl_importacion_pipeline.fecha_carga";
} elseif($opcion == 'pipeSinExistencia'){
	$sql = "SELECT cl_importacion_pipeline.id_control,cl_importacion_pipeline.id_layout,cl_importacion_pipeline.id_carga,UPPER(cl_importacion_pipeline.t48) IRDS,
						DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%d/%m/%Y') FCarga,DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%H:%i:%s') HCarga
			FROM cl_importacion_pipeline
			WHERE cl_importacion_pipeline.activo = '1' and cl_importacion_pipeline.t48 NOT IN (
						SELECT cl_control_series.numero_serie 
						FROM cl_control_series
						LEFT JOIN cl_control_series_detalle
						ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie
						WHERE cl_control_series_detalle.activo = 1 AND cl_control_series_detalle.id_estatus = 1
					)
			ORDER BY cl_importacion_pipeline.t48, cl_importacion_pipeline.fecha_carga";
}

$datos = new consultarTabla($sql);
$result = $datos->obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty->assign("filas", $result);
$smarty->assign("numeroDeRegistros", $numeroDeRegistros);
$smarty->assign("sql", $sql);
$smarty->assign("opcion", $opcion);
echo json_encode($smarty->fetch('especiales/respuestaMuestraIRDsEnPipeline.tpl'));
?>