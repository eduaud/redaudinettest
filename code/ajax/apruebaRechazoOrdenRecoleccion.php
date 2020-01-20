<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idOrden = $_POST['idOrden'];
$motivo = $_POST['motivo'];
$caso = $_POST['caso'];
$rechazo = "";
/*
if($motivo != ""){
		$rechazo = ", observaciones = '$motivo'";
		}*/

$sql = "UPDATE na_ordenes_recoleccion_clientes SET id_estatus_orden_recoleccion = $caso" . $rechazo . " WHERE id_orden_recoleccion_cliente = $idOrden";
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

echo "Operacion realizada con exito";

?>