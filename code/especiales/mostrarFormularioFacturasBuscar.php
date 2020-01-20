<?php
	include("../../conect.php");
	include("../../code/general/funciones.php");
	include("../../consultaBase.php");
/*
	$sqlC = "SELECT ad_pedidos.id_cliente,";
	$sqlC .= " CONCAT(ad_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) as cliente";
	$sqlC .= " FROM ad_pedidos";
	$sqlC .= " LEFT JOIN ad_clientes USING(id_cliente)";
	$sqlC .= " LEFT JOIN ad_pedidos_detalle_pagos USING(id_control_pedido)";
	$sqlC .= " WHERE activo = 1";
	$sqlC .= " AND (ad_pedidos_detalle_pagos.id_deposito_bancario = '' OR ad_pedidos_detalle_pagos.id_deposito_bancario IS NULL OR ad_pedidos_detalle_pagos.id_deposito_bancario = 0) AND ad_pedidos_detalle_pagos.confirmado <> 2";
*/
	$sqlC = "SELECT ad_facturas_audicel.id_cliente,";
	$sqlC .= " CONCAT(ad_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) as cliente";
	$sqlC .= " FROM ad_facturas_audicel";
	$sqlC .= " LEFT JOIN ad_clientes USING(id_cliente)";
	$sqlC .= " LEFT JOIN ad_facturas_audicel_detalle USING(id_control_factura)";
	$sqlC .= " WHERE ad_clientes.activo = 1";
//	$sqlC .= " AND (ad_facturas_audicel_detalle.id_deposito_bancario = '' OR ad_pedidos_detalle_pagos.id_deposito_bancario IS NULL OR ad_pedidos_detalle_pagos.id_deposito_bancario = 0) AND ad_pedidos_detalle_pagos.confirmado <> 2";

	$datosCliente = new consultarTabla($sqlC);
	$cliente = $datosCliente -> obtenerArregloRegistros();

	$smarty -> assign("cliente", $cliente);
	$smarty->display("especiales/buscar_facturas_clientes_seleccion.tpl");
?>