<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["id"];

$sql = "SELECT no_modificable FROM ad_costeo_productos WHERE id_costeo_productos = " . $id;

$result = new consultarTabla($sql);
$modificable = $result -> obtenerLineaRegistro();

echo $modificable['no_modificable'];



?>