<?php	

include("../../conect.php");
include("../../consultaBase.php");


$caso = $_POST["caso"];

mysql_query("SET NAMES 'UTF8'");

if($caso == 1){
		$idPedido = $_POST["idPedido"];
		$sql = "SELECT id_pedido_detalle_pago AS detalles FROM ad_pedidos_detalle_pagos WHERE id_control_pedido = " . $idPedido;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$contador = $datos -> cuentaRegistros();
		
		foreach($result as $id_pagos){
				$idPagos[] = $id_pagos -> detalles;
				}
		
		echo $contador . "|" . implode(",", $idPagos);
		}
else if($caso == 2){
		$contador = 0;
		$idPagos = $_POST["idPagos"];
		$sql = "SELECT ad_clientes.id_cliente AS id_cliente, CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', ad_clientes.apellido_materno) AS cliente, ad_pedidos.id_pedido AS id_pedido, ad_pedidos.id_control_pedido AS id_control_pedido, ad_pedidos.total AS total_registra, CONCAT('$', FORMAT(ad_pedidos.total, 2)) AS total_muestra, SUM(ad_pedidos_detalle_pagos.monto) AS pagos_registra, 
		(ad_pedidos.total - SUM(ad_pedidos_detalle_pagos.monto)) AS saldo_registra, CONCAT('$', FORMAT((ad_pedidos.total - SUM(ad_pedidos_detalle_pagos.monto)),2)) AS saldo_muestra, ad_pedidos_detalle_pagos.id_forma_pago AS forma_pago_registra, ad_formas_pago.nombre AS forma_pago_muestra, numero_documento AS numero_documento, ad_pedidos_detalle_pagos.monto AS pagos_registrados, CONCAT('$', FORMAT(ad_pedidos_detalle_pagos.monto, 2)) AS pagos_muestra, ad_pedidos_detalle_pagos.id_pedido_detalle_pago AS id_detalle_pago
				FROM ad_pedidos_detalle_pagos
				LEFT JOIN ad_pedidos ON ad_pedidos.id_control_pedido = ad_pedidos_detalle_pagos.id_control_pedido
				LEFT JOIN ad_clientes ON ad_pedidos.id_cliente = ad_clientes.id_cliente
				LEFT JOIN ad_formas_pago ON ad_pedidos_detalle_pagos.id_forma_pago = ad_formas_pago.id_forma_pago
				WHERE (ad_pedidos_detalle_pagos.id_deposito_bancario = '' OR ad_pedidos_detalle_pagos.id_deposito_bancario IS NULL OR ad_pedidos_detalle_pagos.id_deposito_bancario = 0) AND ad_pedidos_detalle_pagos.confirmado <> 2 AND ad_pedidos_detalle_pagos.id_pedido_detalle_pago = " . $idPagos;
				
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerLineaRegistro();
		$contador = $datos -> cuentaRegistros();
		
		if($result['id_detalle_pago'] == "")
				echo "NO";
		else
				echo $result['id_cliente'] . "|" . $result['cliente'] . "|" . $result['id_control_pedido'] . "|" . $result['id_pedido'] . "|" . $result['total_registra'] . "|" . $result['total_muestra'] . "|" . $result['pagos_registra'] . "|" . $result['saldo_registra'] . "|" . $result['saldo_muestra'] . "|" . $result['forma_pago_registra'] . "|" . $result['forma_pago_muestra'] . "|" . $result['numero_documento'] . "|" . $result['pagos_registrados'] . "|" . $result['pagos_muestra'] . "|" . $result['id_detalle_pago'];
		}