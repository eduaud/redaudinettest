<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["id"];

$sql = "SELECT id_tipo_ingreso, confirmado
		FROM ad_ingresos_caja_chica
		WHERE id_ingreso = " . $id;
		
$result = new consultarTabla($sql);
$tipo = $result -> obtenerLineaRegistro();

if($tipo['id_tipo_ingreso'] == 2 && $tipo['confirmado'] == 0)
		$modifica = 1;
else
		$modifica = 0;

echo $modifica;


?>