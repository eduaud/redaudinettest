<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$motivo = $_POST['motivo'];
$caso = $_POST['caso'];
$pedido = $_POST['idPedido'];


$sql = "SELECT (total - SUM(na_pedidos_detalle_pagos.monto)) AS saldo_real, id_estatus_pedido AS estatus_pedido, total AS total_pedido 
		FROM ad_pedidos
		LEFT JOIN na_pedidos_detalle_pagos USING(id_control_pedido)
		WHERE id_referencia = " . $pedido . " AND na_pedidos_detalle_pagos.activoDetPagos = 1";
		$datos2 = new consultarTabla($sql);
		$result2 = $datos2 -> obtenerLineaRegistro();

$sqlC = "SELECT descuento FROM na_solicitud_descuento WHERE id_solicitud_descuento = $id";
$datos = new consultarTabla($sqlC);
$result = $datos -> obtenerLineaRegistro();

$totalP = $result2['total_pedido'];
$saldoPre = $result2['saldo_real'];


/*********************DEPENDIENDO DEL RECHAZO O APROBACION ACTUALIZAMOS LOS MONTOS DEL PEDIDO ********************************/
if($caso == 2){
		$descuento = ($totalP * $result['descuento']) / 100;
		$total_desc = $totalP - $descuento;
		$saldoPre -= $descuento;

		$sql2 = "UPDATE ad_pedidos SET descuento_aprobado = " . $result['descuento'] . ", descuento_solicitado = " . $result['descuento'] . ", total = " . $total_desc . "
		, saldo = " . $saldoPre . " WHERE id_referencia = " . $pedido;
		mysql_query($sql2) or die ("Error en la consulta: <br>$sql2. ".mysql_error());
		}
else if($caso == 3){
		$sql2 = "UPDATE ad_pedidos SET descuento_aprobado = 0.00, descuento_solicitado = " . $result['descuento'] . " WHERE id_referencia = " . $pedido;
		mysql_query($sql2) or die ("Error en la consulta: <br>$sql2. ".mysql_error());
		}

/**********************REALIZAMOS LA ACTUALIZACION DEL PEDIDO CON LOS NUEVOS TOTALES CON EL DESCUENTO***************************/

		if($saldoPre <= 0){
				$strUpdate = "UPDATE ad_pedidos SET id_estatus_pedido = 1, id_estatus_pago_pedido = 2 WHERE id_referencia = " . $pedido;
				mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
				}
		else{
				$strUpdate = "UPDATE ad_pedidos SET id_estatus_pedido = 10, id_estatus_pago_pedido = 5 WHERE id_referencia = " . $pedido;
				mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
				}
/***************************FINALMENTE ACTUALIZAMOS LA TABLA DEL DESCUENTO DEL PEDIDO****************************/
$sql = "UPDATE na_solicitud_descuento SET id_estatus_descuento = $caso, motivo_rechazo = '$motivo' WHERE id_solicitud_descuento = $id";
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

echo "Operacion realizada con exito";

?>