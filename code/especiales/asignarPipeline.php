<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sql = "SELECT * FROM ad_sucursales WHERE activo = '1' ORDER BY nombre;";
$datos = new consultarTabla($sql);
$sucursal = $datos->obtenerArregloRegistros();

$smarty->assign("sucursal", $sucursal);
$smarty->display("especiales/asignarPipeline.tpl");

?>