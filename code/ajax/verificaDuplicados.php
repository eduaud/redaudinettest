<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$sku = $_POST["sku"];
$producto = $_POST["producto"];
$idProducto = $_POST["idProducto"];

$where = "";

if(!empty($idProducto))
		$where = " AND id_producto <> " . $idProducto;
		
$sql = "SELECT sku FROM na_productos WHERE sku = '" . $sku . "'" . $where;
$result = new consultarTabla($sql);
$contadorSKU = $result -> cuentaRegistros();

echo $contadorSKU;

?>