<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
$id_control_contrato = $_GET['id_control_contrato'];

$sql = "SELECT * FROM cl_productos_servicios WHERE id_clasificacion = 2 AND activo = '1';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);

$smarty -> assign("filasProductos", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
$smarty -> assign("id_control_contrato", $id_control_contrato);
//$smarty -> assign("idDetalle", $id_control_contrato);
$smarty -> assign("sql", $sql);


echo json_encode($smarty->fetch('especiales/agregarTipoMovimientoDeContrato.tpl'));
?>