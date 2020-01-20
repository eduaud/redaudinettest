<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
$id_control_contrato = $_GET['id_control_contrato'];

$sql = "SELECT * FROM cl_control_contratos WHERE id_control_contrato = '".$id_control_contrato."';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);

echo json_encode($smarty->fetch('especiales/respuestaEditarNumeroContrato.tpl'));
?>