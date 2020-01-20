<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$descuento = $_POST['porcentaje'];
$motivo = $_POST['motivo'];
$referencia = $_POST['referencia'];
$usuarioR = $_POST['usuarioR'];
$sucursal = $_POST['sucursal'];
$cliente = $_POST['cliente'];
$total = $_POST['total'];
$monto_descuento = $_POST['monto_descuento'];

/*$monto_descuento_s = $descuento * $total;
$monto_descuento = $monto_descuento_s / 100;*/


$sql = "INSERT INTO na_solicitud_descuento(id_pedido, id_usuario_solicita, id_sucursal, id_cliente, descuento, monto_descuento, total, motivo, id_estatus_descuento) VALUES('$referencia', $usuarioR, $sucursal, $cliente, $descuento, $monto_descuento, $total, '$motivo', 1)";
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());


echo "exito";


?>