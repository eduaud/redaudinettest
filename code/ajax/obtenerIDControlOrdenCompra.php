<?php 
include("../../conect.php");
$idAlmacenRecepcion=$_GET['idAlmacenRecepcion'];
mysql_query("SET NAMES 'utf8'");
$clave_almacen="A";
$sSql = "SELECT id_almacen, nombre, clave_almacen FROM ad_almacenes WHERE id_almacen = '".$idAlmacenRecepcion."' AND activo = '1';";
$almacenes=mysql_query($sSql);
while($a_almacenes=mysql_fetch_array($almacenes)){
	$prefijo = $a_almacenes['clave_almacen'];
}


$numero = 0;
//$sSql = "SELECT MAX(SUBSTRING(id_orden_compra,8))+1 maximo";
$sSql = "SELECT COUNT(*)+1 maximo";
$sSql .= " FROM ad_ordenes_compra_productos";
$sSql .= " WHERE id_almacen_recepcion = '".$idAlmacenRecepcion."'";
$sSql .= " AND activo = '1';";

$mayor=mysql_query($sSql);
while($a_mayor=mysql_fetch_array($mayor)){
	$numero = $a_mayor['maximo'];
}


$clave = $prefijo."_ODC".$numero;

echo json_encode($clave);
?>