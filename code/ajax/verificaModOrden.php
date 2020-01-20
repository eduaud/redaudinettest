<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT 1 FROM na_movimientos_almacen WHERE id_orden_compra = " . $id;
$result = new consultarTabla($sql);
$contador = $result -> cuentaRegistros();

if($contador > 0)
		echo 0;
else
		echo 1;

?>