<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$consecutivo = $_POST['consecutivo'];
$prefijo = $_POST['prefijo'];

$sql = "SELECT id_pedido FROM ad_pedidos WHERE consecutivo = " . $consecutivo . " AND prefijo = '" . $prefijo. "'";
$result = new consultarTabla($sql);
$contador = $result -> cuentaRegistros();

echo $contador;

?>