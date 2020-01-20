<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");


mysql_query("SET NAMES utf8");

$numeroRenglon=$_GET["renglon"];
$idAlmacen=$_GET["idAlmacen"];
$cantidadAIngresar=$_GET["cantidadAIngresar"];
$idProducto = $_GET["idProducto"];
$informe = "VACIO";

$sSql = "SELECT id_sucursal FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$idOrdenCompra."';";
$datos = new consultarTabla($sSql);
$result = $datos -> obtenerRegistros();
foreach($result AS $dato){
	$idPlaza = $dato -> id_sucursal;
}

$smarty->assign("idOrdenCompra", $idOrdenCompra);
$smarty->assign("idLayout", $idLayout);
$smarty->assign("idAlmacen", $idAlmacen);
$smarty->assign("cantidadAIngresar", $cantidadAIngresar);
$smarty->assign("idProducto", $idProducto);
$smarty->assign("idPlaza", $idPlaza);
$smarty->assign("informe", $informe);
$smarty->assign("numeroRenglon", $numeroRenglon);

$smarty -> display("especiales/capturarNumeroSerieParaSalidaDeAlmacen.tpl");

?>