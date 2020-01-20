<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idPedido = $_POST['idPedido'];
$motivo = $_POST['motivo'];
$caso = $_POST['caso'];
$rechazo = "";

if($motivo != ""){
	$rechazo = ", observaciones = '$motivo'";
}

$sql = "UPDATE ad_pedidos";
$sql .= " SET id_estatus_pedido = $caso" . $rechazo;
$sql .= " WHERE id_control_pedido = $idPedido";

mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

echo "Operacion realizada con exito";

?>