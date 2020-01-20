<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sql = "SELECT id_sucursal, nombre FROM na_sucursales WHERE activo=1 AND id_sucursal <> 0 ORDER BY nombre";
$datosSuc = new consultarTabla($sql);
$sucursal = $datosSuc -> obtenerArregloRegistros();

$sql = "SELECT id_cliente,CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) FROM ad_clientes WHERE activo=1 ORDER BY nombre";
$datosCliente = new consultarTabla($sql);
$cliente = $datosCliente -> obtenerArregloRegistros();

$smarty -> assign("sucursal", $sucursal);
$smarty -> assign("cliente", $cliente);
$smarty -> display("especiales/depositosCobros.tpl");

?>