<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$caso = $_POST['caso'];

$select = "";

if($caso == "fecha_entrega")
		$select .= "dias_fecha_entrega";

$sql = "SELECT " . $select . " FROM sys_parametros_nasser";
$result = new consultarTabla($sql);
$respuesta = $result -> obtenerLineaRegistro();

echo $respuesta[$select];

?>