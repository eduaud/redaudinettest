<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	Global $logo;
	Global $sucursal;
	Global $nuevo_recibo;
	Global $fecha;
	
	$pedido = $_GET['pedido'];
	$pago = $_GET['pago'];
	
	
	$sql3 = "SELECT MAX(id_recibo) AS recibo FROM na_pedidos_detalle_pagos 
			LEFT JOIN ad_pedidos USING(id_control_pedido)
			WHERE id_sucursal_alta = " . $_SESSION["USR"]->sucursalid;
	$datos3 = new consultarTabla($sql3);
	
	$result3 = $datos3 -> obtenerLineaRegistro();

	if($result3['recibo'] == 0){
			$nuevo_recibo = 1;
			}
	else{
			$nuevo_recibo = $result3['recibo'] + 1;
			}
	
	$sql4 = "SELECT id_recibo FROM na_pedidos_detalle_pagos WHERE id_pedido_detalle_pago = $pago";

	$datos4 = new consultarTabla($sql4);
	$result4 = $datos4 -> obtenerLineaRegistro();

	if($result4['id_recibo'] == 0){
			$actualiza = ("UPDATE na_pedidos_detalle_pagos SET id_recibo = $nuevo_recibo WHERE id_pedido_detalle_pago = $pago");
			mysql_query($actualiza);
			}
	else{
			$nuevo_recibo = $result4['id_recibo'];
			}
	
	$sql = "SELECT na_sucursales.logo_sucursales AS logo, na_sucursales.nombre AS sucursal, na_pedidos_detalle_pagos.fecha AS fecha
			FROM ad_pedidos
			LEFT JOIN na_sucursales ON ad_pedidos.id_sucursal_alta = na_sucursales.id_sucursal
			LEFT JOIN na_pedidos_detalle_pagos ON ad_pedidos.id_control_pedido = na_pedidos_detalle_pagos.id_control_pedido AND na_pedidos_detalle_pagos.id_pedido_detalle_pago = $pago
			WHERE ad_pedidos.id_control_pedido = " . $pedido;
			
	$result = new consultarTabla($sql);
	$datosCabecera = $result->obtenerLineaRegistro();
	$logo = $datosCabecera['logo'];
	$sucursal = $datosCabecera['sucursal'];
	$fecha = $datosCabecera['fecha'];
	$fecha = convertDate2($fecha);
	
	$sql2 = "SELECT CONCAT(na_clientes.nombre, ' ', na_clientes.apellido_paterno, ' ', na_clientes.apellido_materno) AS cliente, ad_pedidos.id_pedido AS folio, na_pedidos_detalle_pagos.monto AS monto, IF(na_pedidos_detalle_pagos.monto = ad_pedidos.total, 'LIQUIDACIÓN DE PEDIDO', 'PAGO PARCIAL') AS concepto, CONCAT(na_vendedores.nombre, ' ', na_vendedores.apellido_paterno, ' ', na_vendedores.apellido_materno) AS vendedor
			FROM na_pedidos_detalle_pagos
			LEFT JOIN ad_pedidos ON na_pedidos_detalle_pagos.id_control_pedido = ad_pedidos.id_control_pedido
			LEFT JOIN na_clientes ON ad_pedidos.id_cliente = na_clientes.id_cliente
			LEFT JOIN na_vendedores ON ad_pedidos.id_vendedor = na_vendedores.id_vendedor
			WHERE na_pedidos_detalle_pagos.id_pedido_detalle_pago = " . $pago;
	$consulta = new consultarTabla($sql2);
	$datos = $consulta->obtenerRegistros();
	
	include("recibo_pdf.php");
	
?>