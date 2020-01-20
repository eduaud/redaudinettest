<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$tipo_egreso = $_POST['id'];

$sql = "SELECT detalle_egresos FROM ad_tipos_egreso_caja_chica WHERE id_tipo_egreso = " . $tipo_egreso;
$datos = new consultarTabla($sql);
$detalle = $datos -> obtenerLineaRegistro();
echo $detalle['detalle_egresos'];


?>