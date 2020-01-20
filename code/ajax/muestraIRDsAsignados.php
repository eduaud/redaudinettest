<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_detalle = $_GET['id_detalle'];
$sql = "SELECT numero_serie FROM cl_control_contratos_detalles WHERE id_detalle = '".$id_detalle."' AND cl_control_contratos_detalles.activo = '1';";
$datosNumerosSerie = new consultarTabla($sql);
$resultNumerosSerie = $datosNumerosSerie -> obtenerArregloRegistros();
$numeroDeRegistrosNumerosSerie = count($resultNumerosSerie);
$smarty -> assign("filaNumerosSerie", $resultNumerosSerie);
$smarty -> assign("numeroDeRegistrosNumeroSerie", $numeroDeRegistrosNumerosSerie);
$smarty -> assign("sql", $sql);
echo json_encode($smarty->fetch('especiales/respuestaMuestraIRDsAsignados.tpl'));
?>