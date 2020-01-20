<?php	

include("../../conect.php");
include("../../consultaBase.php");

$idFacturas = $_POST["idFacturas"];
$formas = $_POST["formas"];
$nomCobros = explode(",", $formas);

//mysql_query("SET NAMES 'UTF8'");

$sql = "SELECT control_factura, factura, id_cliente, cliente,
		monto AS total, (cobrosDetalles + cobrosBancarios) AS suma_cobros,
		(monto -(cobrosDetalles + cobrosBancarios)) AS saldo_factura
		FROM
		(
			SELECT aux.id_control_factura AS control_factura, aux.id_factura AS factura, ad_clientes.id_cliente AS id_cliente, ad_clientes.razon_social AS cliente,
					aux.total AS monto, aux.fecha_y_hora AS fecha_factura,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_facturas_audicel_detalles_cobros WHERE activoCobro = 1 AND ad_facturas_audicel_detalles_cobros.id_control_factura=aux.id_control_factura) AS cobrosDetalles,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_depositos_bancarios_detalle WHERE activoDetBancarios=1 AND ad_depositos_bancarios_detalle.id_control_factura=aux.id_control_factura) AS cobrosBancarios
			FROM ad_facturas_audicel AS aux
			LEFT JOIN ad_clientes ON aux.id_cliente = ad_clientes.id_cliente
			LEFT JOIN ad_facturas_audicel_detalles_cobros ON aux.id_control_factura = ad_facturas_audicel_detalles_cobros.id_control_factura
			LEFT JOIN ad_depositos_bancarios_detalle ON aux.id_control_factura = ad_depositos_bancarios_detalle.id_control_factura
			WHERE aux.id_control_factura IN(" . $idFacturas . ")
		) AS datos
		WHERE (monto-(cobrosDetalles+cobrosBancarios)) > 0 GROUP BY control_factura;";

$datos = new consultarTabla($sql);	
$result = $datos -> obtenerRegistros();
$i = 0;
foreach($result as $registros){
		$sqlC = "SELECT id_forma_cobro AS id_forma_cobro, nombre AS forma_cobro FROM ad_formas_cobro WHERE id_forma_cobro = " . $nomCobros[$i];
		$datosC = new consultarTabla($sqlC);
		$resultC = $datosC -> obtenerLineaRegistro();
		$arrFacturas[] = array(
				'control_factura'		=> $registros -> control_factura,
				'id_cliente'			=> $registros -> id_cliente,
				'cliente'				=> utf8_encode($registros -> cliente),
				'factura'				=> $registros -> factura,
				'total'					=> $registros -> total,
				'suma_cobros'			=> $registros -> suma_cobros,
				'saldo_factura'			=> $registros -> saldo_factura,
				'id_forma_cobro'		=> $resultC['id_forma_cobro'],
				'forma_cobro'			=> $resultC['forma_cobro']
				);
		$i++;
		}
echo json_encode($arrFacturas); 

?>



<?php
/*
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
		$sql = "SELECT";
		$sql .= " ad_clientes.id_cliente AS id_cliente,";
		$sql .= " CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', ad_clientes.apellido_materno) AS cliente,";
		$sql .= " ad_pedidos.id_pedido AS id_pedido,";
		$sql .= " ad_pedidos.id_control_pedido AS id_control_pedido,";
		$sql .= " ad_pedidos.total AS total_registra,";
		$sql .= " CONCAT('$', FORMAT(ad_pedidos.total, 2)) AS total_muestra,";
		$sql .= " SUM(ad_pedidos_detalle_pagos.monto) AS pagos_registra,";
		$sql .= " (ad_pedidos.total - SUM(ad_pedidos_detalle_pagos.monto)) AS saldo_registra,";
		$sql .= " CONCAT('$', FORMAT((ad_pedidos.total - SUM(ad_pedidos_detalle_pagos.monto)),2)) AS saldo_muestra,";
		$sql .= " ad_pedidos_detalle_pagos.id_forma_pago AS forma_pago_registra,";
		$sql .= " ad_formas_pago.nombre AS forma_pago_muestra,";
		$sql .= " numero_documento AS numero_documento,";
		$sql .= " ad_pedidos_detalle_pagos.monto AS pagos_registrados,";
		$sql .= " CONCAT('$', FORMAT(ad_pedidos_detalle_pagos.monto, 2)) AS pagos_muestra,";
		$sql .= " ad_pedidos_detalle_pagos.id_pedido_detalle_pago AS id_detalle_pago";
		$sql .= " FROM ad_pedidos_detalle_pagos";
		$sql .= " LEFT JOIN ad_pedidos ON ad_pedidos.id_control_pedido = ad_pedidos_detalle_pagos.id_control_pedido";
		$sql .= " LEFT JOIN ad_clientes ON ad_pedidos.id_cliente = ad_clientes.id_cliente";
		$sql .= " LEFT JOIN ad_formas_pago ON ad_pedidos_detalle_pagos.id_forma_pago = ad_formas_pago.id_forma_pago";
		$sql .= " WHERE (ad_pedidos_detalle_pagos.id_deposito_bancario = '' OR ad_pedidos_detalle_pagos.id_deposito_bancario IS NULL OR ad_pedidos_detalle_pagos.id_deposito_bancario = 0)";
		$sql .= " AND ad_pedidos_detalle_pagos.confirmado <> 2";
		$sql .= " AND ad_pedidos_detalle_pagos.id_pedido_detalle_pago = " . $idPagos;
				
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerLineaRegistro();
		$contador = $datos -> cuentaRegistros();
		
		if($result['id_detalle_pago'] == "")
				echo "NO";
		else
				echo $result['id_cliente'] . "|" . $result['cliente'] . "|" . $result['id_control_pedido'] . "|" . $result['id_pedido'] . "|" . $result['total_registra'] . "|" . $result['total_muestra'] . "|" . $result['pagos_registra'] . "|" . $result['saldo_registra'] . "|" . $result['saldo_muestra'] . "|" . $result['forma_pago_registra'] . "|" . $result['forma_pago_muestra'] . "|" . $result['numero_documento'] . "|" . $result['pagos_registrados'] . "|" . $result['pagos_muestra'] . "|" . $result['id_detalle_pago'];
		}
		*/
?>