<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
include("/especiales/funcionesExistenciaProductos.php");

$idProducto = $_POST['idProducto'];

echo obtenExistenciaProductoBasico($idProducto);





?>