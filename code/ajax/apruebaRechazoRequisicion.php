<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idRequisicion = $_POST['idRequisicion'];
$motivo = $_POST['motivo'];
$caso = $_POST['caso'];
$rechazo = "";

if($motivo != "") $rechazo = ", razones_de_rechazo = '$motivo'";

$sql = "UPDATE ad_requisiciones SET id_estatus_requisicion = $caso" . $rechazo . " WHERE id_requisicion = $idRequisicion";
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

echo "Operacion realizada con exito";
?>