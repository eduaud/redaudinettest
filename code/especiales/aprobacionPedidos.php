<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sql="
	SELECT ad_clientes.id_cliente,CONCAT(ad_clientes.nombre,' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) AS Cliente
	FROM ad_clientes 
	LEFT JOIN ad_pedidos 
	ON ad_clientes.id_cliente=ad_pedidos.id_cliente 
	LEFT JOIN ad_pedidos_estatus 
	ON ad_pedidos.id_estatus_pedido=ad_pedidos_estatus.id_estatus_pedido
	WHERE ad_pedidos_estatus.id_estatus_pedido=1 GROUP BY ad_clientes.id_cliente";
$datosCliente = new consultarTabla($sql);
$cliente = $datosCliente -> obtenerArregloRegistros();

$sqlPedidos="
	SELECT ad_pedidos.id_control_pedido,ad_sucursales.nombre AS Plaza,ad_pedidos.id_pedido AS Pedido,CONCAT(ad_clientes.nombre,' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) AS Cliente,DATE_FORMAT(ad_pedidos.fecha_alta_pedido,'%d/%m/%Y') AS Fecha,total AS Monto  FROM ad_pedidos 
	LEFT JOIN ad_sucursales 
	ON ad_pedidos.id_sucursal=ad_sucursales.id_sucursal 
	LEFT JOIN ad_clientes 
	ON ad_pedidos.id_cliente=ad_clientes.id_cliente 
	WHERE ad_pedidos.id_estatus_pedido=1
	";
$datosPedidos = new consultarTabla($sqlPedidos);
$Pedidos = $datosPedidos -> obtenerArregloRegistros();
$smarty->assign('a_cliente',$cliente);
$smarty->assign('a_pedidos',$Pedidos);
$smarty->display('especiales/aprobacionPedidos.tpl');
?>