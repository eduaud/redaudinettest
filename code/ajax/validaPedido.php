<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$pedido = $_POST['id'];

$sql = "SELECT id_estatus_pedido FROM ad_pedidos WHERE id_pedido = '$pedido'";

$datos = new consultarTabla($sql);
$contador = $datos -> cuentaRegistros();
$status = $datos -> obtenerLineaRegistro();

echo $contador . "|" . $status['id_estatus_pedido'];



?>