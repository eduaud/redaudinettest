<?php
include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");

$id_cliente=$_GET['cliente'];
$fecha1=$_GET['fecha1'];
$fecha2=$_GET['fecha2'];
$pedido=$_GET['pedido'];
$condicion="";
if($id_cliente!=''){
	$condicion="AND ad_clientes.id_cliente=".$id_cliente;
}

if($fecha1!=''&&$fecha2!=''){
	$date1 = DateTime::createFromFormat('d/m/Y', $fecha1);
	$date2 = DateTime::createFromFormat('d/m/Y', $fecha2);
	$fech1=$date1->format('Y-m-d');
	$fech2=$date2->format('Y-m-d');
	$condicion.=" AND ad_pedidos.fecha_alta_pedido BETWEEN '".$fech1."' AND '".$fech2."'";
}
if($pedido!=''){
	$condicion="AND ad_pedidos.id_pedido='".$pedido."'";
}
mysql_query("SET NAMES 'utf8'");
$sqlPedidos="
	SELECT ad_pedidos.id_control_pedido,ad_sucursales.nombre AS Plaza,ad_pedidos.id_pedido AS Pedido,CONCAT(ad_clientes.nombre,' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) AS Cliente,DATE_FORMAT(ad_pedidos.fecha_alta_pedido,'%d/%m/%Y') AS Fecha,total AS Monto  FROM ad_pedidos 
	LEFT JOIN ad_sucursales 
	ON ad_pedidos.id_sucursal=ad_sucursales.id_sucursal 
	LEFT JOIN ad_clientes 
	ON ad_pedidos.id_cliente=ad_clientes.id_cliente 
	WHERE ad_pedidos.id_estatus_pedido=1 ".$condicion;
$datosPedidos = new consultarTabla($sqlPedidos);
$Pedidos = $datosPedidos -> obtenerArregloRegistros();
//echo '<pre>';print_r($Pedidos);echo '</pre>';
$smarty->assign('a_pedidos',$Pedidos);
echo $smarty->fetch("especiales/ajax/mostrarPedidos.tpl");
?> 