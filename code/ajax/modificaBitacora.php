<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT cerrar_bitacora FROM na_bitacora_rutas WHERE id_bitacora_ruta = " . $id;

$result = new consultarTabla($sql);
$respuesta = $result -> obtenerLineaRegistro();

echo $respuesta["cerrar_bitacora"];

?>