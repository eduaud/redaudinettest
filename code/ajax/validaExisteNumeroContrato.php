<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$cuenta = $_GET['cuenta'];
$fecha_activacion = $_GET['fecha_activacion'];
$contrato = $_GET['contrato'];

$sql = "SELECT";
$sql .= " DISTINCT(cl_control_contratos.id_control_contrato)";
$sql .= " FROM cl_control_contratos";
$sql .= " INNER JOIN cl_control_contratos_detalles";
$sql .= " ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato";
$sql .= " WHERE cl_control_contratos.activo = '1'";
$sql .= " AND cl_control_contratos.contrato = '".$contrato."'";
$sql .= " AND cl_control_contratos.cuenta = '".$cuenta."'";
$sql .= " AND cl_control_contratos.fecha_activacion = '".$fecha_activacion."';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
if($numeroDeRegistros>0)
{
	$existe="1";
}else{
	$existe="0";
}
echo json_encode($existe);
?>