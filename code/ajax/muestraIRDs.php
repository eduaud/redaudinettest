<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$ird = $_GET['ird'];
$sql = "SELECT id_control_serie, numero_serie FROM cl_control_series WHERE numero_serie LIKE '%".$ird."%' AND activo = '1';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
echo json_encode($smarty->fetch('especiales/respuestaMuestraIRDs.tpl'));
?>