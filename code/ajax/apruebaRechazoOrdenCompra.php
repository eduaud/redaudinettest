<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idControlOrdenCompra = $_POST['idControlOrdenCompra'];
$motivo = $_POST['motivo'];
$caso = $_POST['caso'];
$rechazo = "";

if($motivo != ""){
	$rechazo = ", observaciones = '$motivo'";
}

$sql = "UPDATE ad_ordenes_compra_productos";
$sql .= " SET id_estatus = $caso" . $rechazo;
$sql .= " WHERE id_control_orden_compra = $idControlOrdenCompra";

mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

echo "Operación realizada con éxito";

?>