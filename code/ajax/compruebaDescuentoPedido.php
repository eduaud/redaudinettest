<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$referencia = $_POST['referencia'];

$sql = "SELECT id_pedido FROM na_solicitud_descuento WHERE id_pedido = '$referencia'";

$datos = new consultarTabla($sql);
$contador = $datos -> cuentaRegistros();

echo $contador;


?>