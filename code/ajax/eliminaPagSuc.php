<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["id"];
$caso = $_POST["caso"];
$lista = $_POST["lista"];

if($caso == 1){
		$tabla = "na_listas_detalle_pagos";
		$idTabla = "id_forma_pago";
		}
else if($caso == 2){
		$tabla = "na_listas_detalle_sucursales";
		$idTabla = "id_sucursal";
		}

$sql = "DELETE FROM " . $tabla . " WHERE " . $idTabla . " = " . $id . " AND id_lista_precios = " . $lista;
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());



?>