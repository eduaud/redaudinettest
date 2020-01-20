<?php 

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["referencia"];

$sql = "SELECT id_control_pedido FROM ad_pedidos WHERE id_referencia = '$id'";

$datos = new consultarTabla($sql);

$result = $datos -> obtenerLineaRegistro();

echo $result[id_control_pedido];

?>