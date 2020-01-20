<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$sqlProd = "SELECT id_producto, porcentaje, precio_final FROM na_listas_detalle_productos WHERE id_lista_precios = $id";

$datosProd = new consultarTabla($sqlProd);
echo $datosProd -> cuentaRegistros();



?>