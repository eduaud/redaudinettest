<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idSolicitud = $_POST['idSolicitud'];
$motivo = $_POST['motivo'];
$caso = $_POST['caso'];
$rechazo = "";

if($motivo != ""){
		$rechazo = ", observaciones = '$motivo'";
		}

$sql = "UPDATE na_solicitud_devolucion_cedis SET id_estatus_devolucion = " . $caso . $rechazo . " WHERE id_solicitud_devolucion_cedis = " . $idSolicitud;
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

echo "Operacion realizada con exito";

?>