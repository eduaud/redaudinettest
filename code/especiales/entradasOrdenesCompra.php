<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");


$sql = "SELECT DISTINCT na_ordenes_compra.id_proveedor, na_proveedores.razon_social FROM na_ordenes_compra
		LEFT JOIN na_proveedores USING(id_proveedor)
		WHERE na_ordenes_compra.id_estatus_orden_compra = 2 OR na_ordenes_compra.id_estatus_orden_compra = 4 ORDER BY na_proveedores.razon_social";
$datosProvedor = new consultarTabla($sql);
$provedor = $datosProvedor -> obtenerArregloRegistros();

$smarty -> assign("proveedor", $provedor);
$smarty -> display("especiales/entradasOrdenesCompra.tpl");

?>