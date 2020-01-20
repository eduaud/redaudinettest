<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idPedido = $_POST["id"];

$sql = "SELECT 1 FROM ad_movimientos_almacen_detalle WHERE id_control_pedido = " . $idPedido;
$result = new consultarTabla($sql);
$contador = $result -> cuentaRegistros();

echo $contador;




?>