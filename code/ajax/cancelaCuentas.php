<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$tabla = $_POST['tabla'];
$tabla = base64_decode($tabla);

if($tabla == 'ad_cuentas_por_pagar_operadora'){
		$actualiza = "UPDATE " . $tabla . " SET id_estatus_cuentas_por_pagar = 3 WHERE id_cuenta_por_pagar = " . $id;
		mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
		echo "exito";
		}
if($tabla == 'ad_egresos'){
		$sql = "SELECT id_egreso_detalle, id_cuenta_por_pagar FROM ad_egresos_detalle WHERE id_egreso = " . $id;
		$result = new consultarTabla($sql);
		$registros = $result -> obtenerRegistros();
		$contador = $result -> cuentaRegistros();
		if($contador > 0){
				foreach($registros as $datos){
						
						$actualiza = "UPDATE ad_egresos_detalle SET activoDetEgreso = 0 WHERE id_egreso_detalle = " . $datos -> id_egreso_detalle;
						mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
//											 na_cuentas_por_pagar_pagos_egresos_detalle
						$strSQL="DELETE FROM ad_cuentas_por_pagar_operadora_detalle_pagos_egresos WHERE id_egreso_detalle ='" . $datos -> id_egreso_detalle . "'";
						mysql_query($strSQL) or die("Error en consulta:<br> $strSQL <br>" . mysql_error());
						
						actualizaEstatusPorPagar($datos -> id_cuenta_por_pagar);	
						}
				}
		
		$actualiza = "UPDATE " . $tabla . " SET id_estatus_egreso = 2 WHERE id_egreso = " . $id;
		mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
		echo "exito";
		}
if($tabla == 'ad_pedidos'){
		$actualiza = "UPDATE " . $tabla . " SET id_estatus_pedido = 6, id_estatus_pago_pedido = 4 WHERE id_control_pedido = " . $id;
		mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
		
		$sqlPagos = "SELECT id_pedido_detalle_pago FROM ad_pedidos_detalle_pagos WHERE id_control_pedido = " . $id;
		$resultP = new consultarTabla($sqlPagos);
		$registrosP = $resultP -> obtenerRegistros();
		$contadorP = $resultP -> cuentaRegistros();
		if($contadorP > 0){
				foreach($registrosP as $datosP){
						$actualizaP = "UPDATE ad_pedidos_detalle_pagos SET activoDetPagos = 0 WHERE id_pedido_detalle_pago = " . $datosP -> id_pedido_detalle_pago;
						mysql_query($actualizaP) or die("Error en consulta:<br> $actualizaP <br>" . mysql_error());
						}
				}
		echo "exito";
		
		
		}
if($tabla == 'ad_ingresos_caja_chica'){
		
		date_default_timezone_set('America/Mexico_City');
		
		$actualiza = "UPDATE " . $tabla . " SET activo = 0, id_usuario_cancelo = " . $_SESSION["USR"]->userid . ", fecha_hora_cancelo = '" . date('Y-m-d H:i:s'). "' 
						WHERE id_ingreso = " . $id;
		mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
		
		$sql = "SELECT id_egreso FROM ad_ingresos_caja_chica WHERE id_ingreso = " . $id;
		$result = new consultarTabla($sql);
		$ingreso = $result -> obtenerLineaRegistro();
		
		$actualiza2 = "UPDATE ad_egresos_caja_chica SET activo = 0 WHERE id_egreso = " . $ingreso['id_egreso'];
		mysql_query($actualiza2) or die("Error en consulta:<br> $actualiza2 <br>" . mysql_error());
		echo "exito";
		}
		
		
		
		
		
		
		
?>