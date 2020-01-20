<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$pedido = $_POST['pedido'];

$sql = "SELECT id_referencia FROM ad_pedidos WHERE id_referencia = $pedido";

$datos = new consultarTabla($sql);

$contador = $datos -> cuentaRegistros();

echo $contador;



?>