<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
/*
$sql = "SELECT id_cliente,CONCAT(nombre, ' ', apellido_paterno, ' ', if(apellido_materno is null,'',apellido_materno)) 
		FROM ad_clientes 
		WHERE activo=1 AND id_cliente IN(SELECT DISTINCT id_cliente FROM ad_pedidos WHERE requiere_factura =1 AND (id_control_factura IS NULL OR id_control_factura=0) ) ORDER BY nombre";
		*/
		
		
$sql = "SELECT id_cliente,CONCAT(nombre, ' ', apellido_paterno, ' ', if(apellido_materno is null,'',apellido_materno))
		FROM ad_clientes 
		WHERE activo=1 AND id_cliente IN(
		
			SELECT  id_cliente FROM ad_pedidos

		LEFT JOIN ad_pedidos_detalle_pagos ON ad_pedidos.id_control_pedido = ad_pedidos_detalle_pagos.id_control_pedido

		WHERE  requiere_factura =1 and (id_control_factura IS NULL OR id_control_factura=0 )


          AND ad_pedidos_detalle_pagos.confirmado <> 2
          GROUP BY  id_pedido  HAVING sum(ad_pedidos_detalle_pagos.monto) >= max(ad_pedidos.total)
		
		 ) ORDER BY nombre";
		
		
		
		
$datosCliente = new consultarTabla($sql);
$cliente = $datosCliente -> obtenerArregloRegistros();

$smarty -> assign("cliente", $cliente);


//colocamos en nombre de la sucursales donde existan pedidos pedientes por facturar


$sql = "SELECT id_sucursal, nombre
		FROM ad_sucursales 
		WHERE activo=1 AND id_sucursal IN(SELECT DISTINCT id_sucursal FROM ad_pedidos) ORDER BY nombre";
$datosSucursales= new consultarTabla($sql);
$sucursales = $datosSucursales -> obtenerArregloRegistros();
$smarty -> assign("sucursales", $sucursales);


$smarty -> display("especiales/facturasPedidosLiquidados.tpl");

?>