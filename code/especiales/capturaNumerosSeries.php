<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
extract($_GET);
mysql_query("SET NAMES utf8");

$idOrdenCompra=$_GET["idOrdenCompra"];
$numeroRenglon=$_GET["renglon"];
$idLayout=$_GET["idLayout"];
$idAlmacen=$_GET["idAlmacen"];
$cantidadAIngresar=$_GET["cantidadAIngresar"];
$idProducto = $_GET["idProducto"];
$idDetalleOrdenCompra = $_GET["idDetalleOrdenCompra"];
$informe = "VACIO";
$sSql = "SELECT id_sucursal FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$idOrdenCompra."';";
$datos = new consultarTabla($sSql);
$result = $datos -> obtenerRegistros();
if(count($result)==0){
	$idPlaza = 0;
}else{
	foreach($result AS $dato){
		$idPlaza = $dato -> id_sucursal;
	}
}
$smarty->assign("idOrdenCompra", $idOrdenCompra);
$smarty->assign("idLayout", $idLayout);
$smarty->assign("idAlmacen", $idAlmacen);
$smarty->assign("cantidadAIngresar", $cantidadAIngresar);
$smarty->assign("idProducto", $idProducto);
$smarty->assign("idPlaza", $idPlaza);
$smarty->assign("informe", $informe);
$smarty->assign("numeroRenglon", $numeroRenglon);
$smarty->assign("idDetalleOrdenCompra", $idDetalleOrdenCompra);
if(isset($Modulo)){
	$smarty->assign("modulo", $Modulo);
	$smarty->assign("irds", $irds);
}
$smarty -> display("especiales/capturaNumerosSeries.tpl");

?>