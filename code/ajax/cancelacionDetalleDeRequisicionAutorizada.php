<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idDetalleRequisicion = $_POST['idDetalleRequisicion'];
$motivo = $_POST['motivo']; 	// ****************** Esto esta pendiente  ******************
$caso = $_POST['caso'];
$rechazo = "";
$sql = "UPDATE ad_requisiciones_detalle";
$sql .= " SET activo = ".$caso.",";
if($caso=='0') {$sql .= " fecha_cancelacion = now()";}
//$sql .= " id_usuario_cancelo = " . $idCancelo; 	// ****************** ESTO ESTA PENDIENTE ******************
$sql .= " WHERE id_detalle = " . $idDetalleRequisicion;

mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());
echo "Operacion realizada con exito";
?>