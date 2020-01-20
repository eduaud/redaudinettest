<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["id"];
$caso = $_POST["caso"];

if($caso == 1){
		$tabla = "na_tipos_ingreso_caja_chica";
		$idTabla = "id_tipo_ingreso";
		}
if($caso == 2){
		$tabla = "na_tipos_egreso_caja_chica";
		$idTabla = "id_tipo_egreso";
		}

$sql = "SELECT modificable_usuario FROM " . $tabla . " WHERE " . $idTabla . " = " . $id;

$result = new consultarTabla($sql);
$modificable = $result -> obtenerLineaRegistro();

echo $modificable['modificable_usuario'];



?>