<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idIngreso = $_POST["id"];

$sql = "SELECT confirmado FROM ad_ingresos_caja_chica WHERE id_ingreso = " . $idIngreso;
$result = new consultarTabla($sql);
$registro = $result -> obtenerLineaRegistro();

echo $registro['confirmado'];



?>