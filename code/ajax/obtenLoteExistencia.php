<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
include("../especiales/funcionesCosteoLote.php");

$idalmacen = $_POST['almacen'];
$idsubtipo = $_POST['subtipo'];
$idproducto = $_POST['producto'];

//Funcion para obtener el lote y la existencia
echo obtenLotesProductosAlmacenSubtipoMov($idproducto,$idalmacen,$idsubtipo);


?>