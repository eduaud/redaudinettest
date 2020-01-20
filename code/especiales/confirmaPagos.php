<?php
		
	php_track_vars;
	
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
 $strSQL="SELECT na_sucursales.nombre,id_pedido_detalle_pago, na_pedidos_detalle_pagos.id_control_pedido, ad_pedidos.id_pedido,fecha ,
na_pedidos_detalle_pagos.id_forma_pago, na_formas_pago.nombre ,
na_pedidos_detalle_pagos.id_terminal_bancaria, na_terminales_bancarias.nombre ,
numero_documento AS 'Número de Documento', numero_aprobacion , monto ,
confirmado , observaciones ,

CONCAT(ad_clientes.nombre,' ', ad_clientes.apellido_paterno, ' ',if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) as cliente
 FROM na_pedidos_detalle_pagos
left join  ad_pedidos on na_pedidos_detalle_pagos.id_control_pedido=ad_pedidos.id_control_pedido
left join  ad_clientes on ad_clientes.id_cliente=ad_pedidos.id_cliente
left join  na_sucursales on na_sucursales.id_sucursal=ad_pedidos.id_sucursal_alta
LEFT JOIN na_formas_pago ON na_pedidos_detalle_pagos.id_forma_pago=na_formas_pago.id_forma_pago
LEFT JOIN na_terminales_bancarias ON na_pedidos_detalle_pagos.id_terminal_bancaria=na_terminales_bancarias.id_terminal_bancaria
WHERE confirmado=2";
	
	//obttenemos todos los pagos pendientes de confirmación

	$smarty->display("especiales/confirmaPagos.tpl");
	
	
?>