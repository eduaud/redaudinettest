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

$sql = "UPDATE ad_solicitudes_material";
$sql .= " SET id_estatus_solicitud = $caso" . $rechazo;
$sql .= " WHERE id_control_solicitud_material = $idSolicitud";

mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

echo "Operacion realizada con exito";

?>