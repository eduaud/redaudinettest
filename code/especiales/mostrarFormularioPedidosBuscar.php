<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	include("../../consultaBase.php");
	
	$sqlC = "SELECT ad_pedidos.id_cliente, 
			CONCAT(ad_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) as cliente
			FROM ad_pedidos 
			LEFT JOIN ad_clientes USING(id_cliente)
			LEFT JOIN ad_pedidos_detalle_pagos USING(id_control_pedido)
			WHERE activo = 1 AND (ad_pedidos_detalle_pagos.id_deposito_bancario = '' OR ad_pedidos_detalle_pagos.id_deposito_bancario IS NULL OR ad_pedidos_detalle_pagos.id_deposito_bancario = 0) AND ad_pedidos_detalle_pagos.confirmado <> 2";
	$datosCliente = new consultarTabla($sqlC);
	$cliente = $datosCliente -> obtenerArregloRegistros();

	$smarty -> assign("cliente", $cliente);
	
	$smarty->display("especiales/buscar_pedidos_clientes_seleccion.tpl");
	
	
?>