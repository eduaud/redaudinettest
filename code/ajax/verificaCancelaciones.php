<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$tabla = $_POST['tabla'];
$tabla = base64_decode($tabla);

if($tabla == 'ad_cuentas_por_pagar_operadora'){
		//Poliza y estatus de la cuenta por pagar
		$sql = "SELECT id_estatus_cuentas_por_pagar, id_control_poliza FROM " . $tabla . " WHERE id_cuenta_por_pagar = " . $id;
		$result = new consultarTabla($sql);
		$datos = $result -> obtenerLineaRegistro();
		
		//Pagos asociados a la cuenta por pagar
		$sql2 = "SELECT 1 FROM ad_cuentas_por_pagar_pagos_detalle WHERE id_cuenta_por_pagar = " . $id;
		$result2 = new consultarTabla($sql2);
		$contador = $result2 -> cuentaRegistros();
		
		//Egresos asociados a la cuenta por pagar
		$sql3 = "SELECT id_egreso FROM ad_egresos_detalle WHERE id_cuenta_por_pagar = " . $id;
		$result3 = new consultarTabla($sql3);
		$contadorE = $result3 -> cuentaRegistros();
		
		/*********Validaciones para cancelar************/
		if($datos['id_estatus_cuentas_por_pagar'] == 3)
				$respuesta['texto'] = "Imposible cancelar\n\n-- la cuenta por pagar ya esta cancelada --";
		else if($datos['id_control_poliza'] != "")
				$respuesta['texto'] = "Imposible cancelar\n\n-- esta cuenta por pagar ya esta relacionada a una poliza --";
		
		else if($contadorE > 0){
				$cadena = "";
				$registros = $result3 -> obtenerRegistros();
				foreach($registros as $datos){
						$cadena .= "- ID Egreso " . $datos -> id_egreso . "\n";
						}
				$respuesta['texto'] = "Imposible cancelar\nEsta cuenta por pagar tiene relacionado los siguientes egresos:\n\n" . $cadena;
				}
		else if($contador > 0)
				$respuesta['texto'] = "Imposible cancelar\n\n-- esta cuenta por pagar ya tiene pagos relacionados --";
		
		else{
				$respuesta['cancela'] = 1;
				$respuesta['texto'] = "Esta a punto de cancelar esta cuenta por pagar\n¿Desea Continuar?";
				}
		echo json_encode($respuesta);
		}
		
else if($tabla == 'ad_egresos'){
		//Poliza y estatus de los egresos
		$sql = "SELECT id_estatus_egreso, id_control_poliza FROM " . $tabla . " WHERE id_egreso = " . $id;
		$result = new consultarTabla($sql);
		$datos = $result -> obtenerLineaRegistro();
		
		//Cuentas por pagar asociadas al egreso
		$sql2 = "SELECT DISTINCT id_cuenta_por_pagar FROM ad_egresos_detalle WHERE id_egreso = " . $id;
		$result2 = new consultarTabla($sql2);
		$contador = $result2 -> cuentaRegistros();
		
		//Cuentas contables asociadas al egreso
		$sql3 = "SELECT 1 FROM ad_egresos_cuentas_contables_detalle WHERE id_egreso = " . $id;
		$result3 = new consultarTabla($sql3);
		$contadorCC = $result3 -> cuentaRegistros();
		
		/*********Validaciones para cancelar************/
		if($datos['id_estatus_egreso'] == 2)
				$respuesta['texto'] = "Imposible cancelar\n\n-- el egreso ya esta cancelado --";
		else if($datos['id_control_poliza'] != "")
				$respuesta['texto'] = "Imposible cancelar\n\n-- este egreso ya esta relacionada a una poliza --";
		
		else if($contador > 0){
				$cadena = "";
				$registros = $result2 -> obtenerRegistros();
				foreach($registros as $datos){
						$cadena .= "- ID Cuenta por pagar " . $datos -> id_cuenta_por_pagar . "\n";
						}
				$respuesta['cancela'] = 1;
				$respuesta['texto'] = "Este egreso tiene relacionado las siguientes cuentas por pagar:\n\n" . $cadena . "\n¿Esta seguro de cancelar?";
				}
				
		else if($contadorCC > 0){
				$respuesta['cancela'] = 1;
				$respuesta['texto'] = "Este egreso ya tiene cuentas contables relacionadas\n" . "¿Esta seguro de cancelar?";
				}
		else{
				$respuesta['cancela'] = 1;
				$respuesta['texto'] = "¿Esta seguro de cancelar?";
				}
		
		echo json_encode($respuesta);
		}

else if($tabla == 'ad_pedidos'){
		/*$sql = "SELECT id_control_pedido FROM ad_depositos_bancarios_detalle WHERE id_control_pedido = " . $id;
		$result = new consultarTabla($sql);
		$contadorD = $result -> cuentaRegistros();*/
		
		$sqlC = "SELECT count(id_control_pedido) AS cancelado FROM ad_pedidos WHERE id_control_pedido = " . $id . " AND id_estatus_pedido <> 6";
		$resultC = new consultarTabla($sqlC);
		$contadorC = $resultC -> obtenerLineaRegistro();
		
		$sqlVerifica = "SELECT id_grupo 
		FROM sys_grupos 
		LEFT JOIN sys_usuarios USING(id_grupo)
		WHERE id_usuario = " . $_SESSION["USR"] -> userid;
		$datosV = new consultarTabla($sqlVerifica);
		$resultV = $datosV -> obtenerLineaRegistro();

		if($resultV['id_grupo'] != 1){
				$respuesta['texto'] = "Imposible cancelar\n\n-- Necesita permisos de administrador --";
				}
		else if($contadorD > 0){
				$respuesta['texto'] = "Imposible cancelar\n\n-- Este pedido contiene pagos asociados a un depósito bancario --";
				}
		else if($contadorC['cancelado'] == 0){
				$respuesta['texto'] = "Imposible cancelar\n\n-- Este pedido ya esta cancelado --";
				}
		else{
				$respuesta['cancela'] = 1;
				$respuesta['texto'] = "¿Esta seguro de cancelar?";
				}
		echo json_encode($respuesta);
		}
else if($tabla == 'ad_ingresos_caja_chica'){
		$sql = "SELECT id_tipo_ingreso, monto, confirmado FROM ad_ingresos_caja_chica WHERE id_ingreso = " . $id;
		$result = new consultarTabla($sql);
		$ingreso = $result -> obtenerLineaRegistro();
		
		if($ingreso['id_tipo_ingreso'] != 2){
				$respuesta['texto'] = "Imposible cancelar\n\n-- Solo se pueden cancelar ingresos del tipo traspaso entre sucursales --";
				}
		else if($ingreso['monto'] > 0){
				$respuesta['texto'] = "Imposible cancelar\n\n-- El ingreso ya tiene un monto recibido --";
				}
		else if($ingreso['confirmado'] == 1){
				$respuesta['texto'] = "Imposible cancelar\n\n-- El ingreso ya esta confirmado --";
				}
		else{
				$respuesta['cancela'] = 1;
				$respuesta['texto'] = "¿Esta seguro de cancelar este ingreso?";
				}
				
		echo json_encode($respuesta);
		}
		
		
		
		
		
		
		
		
		
?>