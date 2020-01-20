﻿<?php
include("../../consultaBase.php");
include("../../conect.php");
include("../../code/cfdi/funciones_facturacion.php");
include("../../code/cfdi/facturacion_sellado_timbrado.php");
include("../../code/especiales/enviarFacturas.php");
$seleccion = json_decode($_POST['seleccion'], true);
$tipo2=$_POST['tipo2'];

$idUno = '0';
$idDetalleUno = '0';
$idDos = '0';
$idDetalleDos = '0';
$envio="";
for($i = 0; $i < count($seleccion); $i++){
	$ids=explode(',',$seleccion[$i]);
	
	if($ids[2] == "contratos"){
		if($idUno == "0") $idUno = "'".$ids[0]."'";
		else $idUno .= ",'".$ids[0]."'";
		
		if($idDetalleUno == "0") $idDetalleUno = "'".$ids[1]."'";
		else $idDetalleUno .= ",'".$ids[1]."'";
	} elseif($ids[2] == "penalizaciones"){
		if($idDos == "0") $idDos = "'".$ids[0]."'";
		else $idDos .= ",'".$ids[0]."'";
		
		if($idDetalleDos == "0") $idDetalleDos = "'".$ids[1]."'";
		else $idDetalleDos .= ",'".$ids[1]."'";
	}
}
$campoMonto='';
if($tipo2 == 'penalizaciones'){
	$campoMonto='cl_control_contratos_detalles.monto_penalizacion';
} elseif($tipo2 == 'bonos'){
	$campoMonto='cl_control_contratos_detalles.monto_bono';
}

$query='SELECT '.$campoMonto.' as comision, cl_control_contratos.id_control_contrato, cl_control_contratos_detalles.id_detalle,ad_sucursales.id_sucursal,
				ad_clientes.id_cliente,cl_control_contratos_detalles.id_producto_servicio_facturar_audicel, COUNT(ad_clientes.id_cliente) AS cantidad,
				GROUP_CONCAT(cl_control_contratos_detalles.id_detalle) as idsDetalleControlContrato,ad_clientes.id_proveedor_relacionado,
				ad_tasas_ivas.porcentaje as IVA

		FROM cl_control_contratos
		LEFT JOIN cl_control_contratos_detalles
			ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato
		LEFT JOIN ad_clientes
			ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente
		LEFT JOIN cl_importacion_caja_comisiones
			ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones

		LEFT JOIN cl_paquetes_sky
			ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
		LEFT JOIN ad_sucursales
			ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal
		LEFT JOIN cl_tipos_cliente_proveedor
			ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
		
		LEFT JOIN cl_productos_servicios
			ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar_audicel
		LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		WHERE cl_control_contratos.id_control_contrato IN ('.$idUno.') AND cl_control_contratos_detalles.id_detalle IN ('.$idDetalleUno.')
		HAVING COUNT(ad_clientes.id_cliente) > 0
		/*GROUP BY ad_clientes.id_cliente,cl_control_contratos_detalles.id_producto_servicio_facturar_audicel,comision*/';

$result = mysql_query($query);
$num = mysql_num_rows($result);

if($num > 0){
	$ultimo_movimiento='';
	$tablaEncabezado='';
	$tablaDetalle='';
	if($tipo2 == 'penalizaciones'){
		$ultimo_movimiento='2';
		$tablaEncabezado='ad_facturas_audicel';
		$tablaDetalle='ad_facturas_audicel_detalle';
	} elseif($tipo2 == 'bonos'){
		$ultimo_movimiento='3';
		$tablaEncabezado='ad_facturas';
		$tablaDetalle='ad_facturas_detalle';
	}
	while($a_datos = mysql_fetch_array($result)){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		if($tablaEncabezado=='ad_facturas_audicel'){
			$facturas=FacturasAudicel($a_datos['id_cliente']);
		}
		if($tablaEncabezado=='ad_facturas'){
			$facturas=DatosFacturas($a_datos['id_cliente']);
		}
		if($tablaEncabezado=='ad_facturas_audicel')
		$queryInsert='INSERT INTO '.$tablaEncabezado.' (id_factura,prefijo,consecutivo,id_tipo_comprobante_fiscal,id_compania,id_compania_fiscal,id_sucursal,fecha_y_hora,id_moneda,tipo_cambio,id_cliente,email_envio_fa,id_forma_pago_sat,cuenta,id_fiscales_cliente,id_tipo_factura,porcentaje_descuento,subtotal_0,subtotal,descuento,iva,ieps,retencion_iva,retencion_isr,total,activo,id_concepto_factura) 
VALUES ("'.$facturas['id_factura'].'","'.$facturas['prefijo'].'","'.$facturas['consecutivo'].'",1,"'.$facturas['id_compania'].'","'.$facturas['id_compania'].'","'.$a_datos['id_sucursal'].'","'. date('Y-m-d H:i:s').'",1,1,"'.$facturas['id_cliente_fiscal'].'",
						"'.$facturas['email'].'","'.$facturas['id_forma_pago'].'","'.$facturas['cuenta'].'","'.$facturas['id_cliente_fiscal'].'",1,0,0,"'.$a_datos['comision'].'",0,"'.round(($a_datos['comision'] * ($a_datos['IVA'] / 100)), 2).'",0,0,0,"'.round((($a_datos['comision'] * ($a_datos['IVA'] / 100)) + $a_datos['comision']), 2).'",1,1)';	
		else 
			$queryInsert='INSERT INTO '.$tablaEncabezado.' (id_factura,prefijo,consecutivo,id_tipo_comprobante_fiscal,id_compania,id_compania_fiscal,id_sucursal,fecha_y_hora,id_moneda,tipo_cambio,id_cliente,email_envio_fa,id_forma_pago_sat,cuenta,id_fiscales_cliente,id_tipo_factura,porcentaje_descuento,subtotal_0,subtotal,descuento,iva,ieps,retencion_iva,retencion_isr,total,activo,id_concepto_factura) 
VALUES ("'.$facturas['id_factura'].'","'.$facturas['prefijo'].'","'.$facturas['consecutivo'].'",1,"'.$facturas['id_compania'].'","'.$facturas['id_compania_fiscal'].'","'.$a_datos['id_sucursal'].'","'. date('Y-m-d H:i:s').'",1,1,"'.$facturas['id_cliente_fiscal'].'",
						"'.$facturas['email'].'","'.$facturas['id_forma_pago'].'","'.$facturas['cuenta'].'","'.$facturas['id_cliente_fiscal'].'",1,0,0,"'.$a_datos['comision'].'",0,"'.round(($a_datos['comision'] * ($a_datos['IVA'] / 100)), 2).'",0,0,0,"'.round((($a_datos['comision'] * ($a_datos['IVA'] / 100)) + $a_datos['comision']), 2).'",1,1)';
		$resultInsert=mysql_query($queryInsert);
		$idControlFactura=mysql_insert_id();
		$id_cuenta_por_pagar="";
		if($tipo2 == 'bonos'){
			/*$queryCXP='INSERT INTO ad_cuentas_por_pagar_operadora (id_sucursal,id_tipo_cuenta_por_pagar,id_proveedor,fecha_captura,fecha_documento,
						fecha_vencimiento,id_tipo_documento_recibido,numero_documento,id_cuenta_contable,id_estatus_cuentas_por_pagar,subtotal_productos,
						subtotal_gastos,subtotal_2,subtotal,iva,retencion_iva_documentos,retencion_isr_documentos,total,saldo,ieps,pagos,id_moneda,
						id_control_factura,activo) VALUES ("'.$datos['id_sucursal'].'",1,"'.$datos['id_proveedor_relacionado'].'","'. date('Y-m-d').'",
						"'. date('Y-m-d').'","'. date('Y-m-d').'",1,"'.$facturas['id_factura'].'",1,1,"'. round($datos['comision'], 2).'",
						0,0,"'. round($datos['comision'], 2).'","'. round(($datos['comision'] * ($datos['IVA'] / 100)) * 1, 2).'",
						0,0,"'. round(($datos['comision'] * 1) + (($datos['comision'] * ($datos['IVA'] / 100)) * 1), 2).'",
						"'. round(($datos['comision'] * 1) + (($datos['comision'] * ($datos['IVA'] / 100)) * 1), 2).'",0,0,1,
						"'.$idControlFactura.'","1")';
			$resultCXP=mysql_query($queryCXP);
			$id_cuenta_por_pagar=mysql_insert_id();*/
		}
		
		$subtotal=0;
		$iva=0;
		$total=0;
		/**/
		$queryContratos = 'SELECT '.$campoMonto.' as comision, cl_control_contratos.id_control_contrato, cl_control_contratos_detalles.id_detalle,ad_sucursales.id_sucursal,
				ad_clientes.id_cliente,cl_control_contratos_detalles.id_producto_servicio_facturar_audicel, COUNT(ad_clientes.id_cliente) AS cantidad,
				GROUP_CONCAT(cl_control_contratos_detalles.id_detalle) as idsDetalleControlContrato,ad_clientes.id_proveedor_relacionado,
				ad_tasas_ivas.porcentaje as IVA

		FROM cl_control_contratos
		LEFT JOIN cl_control_contratos_detalles
			ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato
		LEFT JOIN ad_clientes
			ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente
		LEFT JOIN cl_importacion_caja_comisiones
			ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones

		LEFT JOIN cl_paquetes_sky
			ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
		LEFT JOIN ad_sucursales
			ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal
		LEFT JOIN cl_tipos_cliente_proveedor
			ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
		
		LEFT JOIN cl_productos_servicios
			ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar_audicel
		LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		WHERE cl_control_contratos.id_control_contrato IN ('.$idUno.') AND cl_control_contratos_detalles.id_detalle IN ('.$idDetalleUno.')
		GROUP BY id_detalle';
		$resultContratos = mysql_query($queryContratos);
		/**/
		while($datos = mysql_fetch_array($resultContratos)){
			$arr_idsDetalleControlContrato = explode(',',$datos['idsDetalleControlContrato']);
			for($i=0;$i<count($arr_idsDetalleControlContrato);$i++){
				$queryInsertDetalle='INSERT INTO '.$tablaDetalle.' (id_control_factura,id_producto,cantidad,valor_unitario,valor_precio_publico,importe,descuento,
								descuento_porcentaje,iva_monto,iva_tasa,ieps_monto,ieps_tasa,retencion_monto,retencion_tasa,activo,id_control_pedido_detalle,
								ids_detalle_control_contrato) VALUES ("'.$idControlFactura.'","'.$datos['id_producto_servicio_facturar_audicel'].'",
								"1","'.$datos['comision'].'",0,"'.$datos['comision'].'",0,0,
								"'. round(($datos['comision'] * ($datos['IVA'] / 100)), 2).'","'.$datos['IVA'].'",0,0,0,0,1,0,"'.$arr_idsDetalleControlContrato[$i].'")';
				$resultInsertDetalle = mysql_query($queryInsertDetalle);
				$idControlFacturaDetalle=mysql_insert_id();
				
				$subtotal+=round((1 * $datos['comision']),2);
				$iva+=round(((1 * $datos['comision'])*($datos['IVA'] / 100)),2);
				$total+=round(((1 * $datos['comision'])+((1 * $datos['comision'])*($datos['IVA'] / 100))),2);
				
				if($tipo2 == 'bonos'){
					/*$queryCXPDetalle='INSERT INTO ad_cuentas_por_pagar_operadora_detalle_productos (id_cuenta_por_pagar,id_producto,cantidad,costo,importe,
									id_control_factura_detalle) VALUES ("'.$id_cuenta_por_pagar.'","'.$datos['id_producto_servicio_facturar_audicel'].'",
									"1","'.$datos['comision'].'","'. round(($datos['comision'] * 1), 2).'",
									"'.$idControlFacturaDetalle.'")';
					$resultCXPDetalle=mysql_query($queryCXPDetalle);*/
				}
				$querySelCCD='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
							cl_control_contratos_detalles.id_contra_recibo,
							cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
							cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
							cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
							cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
							cl_control_contratos_detalles.id_entidad_financiera_vendedor,
							cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
							cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar_audicel,
							cl_control_contratos_detalles.monto_pagar,cl_control_contratos_detalles.monto_cobrar,
							cl_control_contratos_detalles.monto_bono,cl_control_contratos_detalles.id_calificacion,
							cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
							cl_control_contratos_detalles.activo
						FROM cl_control_contratos_detalles 
						WHERE id_detalle='.$arr_idsDetalleControlContrato[$i];
				$resultSelCCD=mysql_query($querySelCCD);
				$numSelCCD=mysql_num_rows($resultSelCCD);
			
				if($numSelCCD > 0){
					while($datosSelCCD=mysql_fetch_array($resultSelCCD)){
						if($datosSelCCD["id_accion_contrato"] == "54"){ $id_accion_contrato="56"; }
						elseif($datosSelCCD["id_accion_contrato"] == "55"){ $id_accion_contrato="57"; }
						elseif($datosSelCCD["id_accion_contrato"] == "62"){ $id_accion_contrato="64"; }
						elseif($datosSelCCD["id_accion_contrato"] == "63"){ $id_accion_contrato="65"; }
						
						$queryInsertCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,
										id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
										id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar_audicel,monto_pagar,
										monto_cobrar,monto_bono,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_carga,precio_suscripcion,
										activo,ultimo_movimiento) VALUES ("'.$datosSelCCD["id_control_contrato"].'",
										"'.$id_accion_contrato.'","'.$datosSelCCD["id_contra_recibo"].'","'. date('Y-m-d H:i:s').'",
										"'.$datosSelCCD["id_tipo_activacion"].'","'.$datosSelCCD["id_control_serie"].'","'.$datosSelCCD["numero_serie"].'","'.$datosSelCCD["id_paquete_sky"].'",
										"'.$datosSelCCD["id_sucursal"].'","'.$datosSelCCD["id_cliente"].'","'.$datosSelCCD["id_cliente_tecnico"].'",
										"'.$datosSelCCD["id_entidad_financiera_tecnico"].'","'.$datosSelCCD["id_entidad_financiera_vendedor"].'","'.$datosSelCCD["id_funcionalidad"].'",
										"'.$datosSelCCD["clave"].'","'.$datosSelCCD["id_detalle_caja_comisiones"].'","'.$datosSelCCD["id_producto_servicio_facturar_audicel"].'",
										"'.$datosSelCCD["monto_pagar"].'","'.$datosSelCCD["monto_cobrar"].'","'.$datosSelCCD["monto_bono"].'",
										"'.$datosSelCCD["id_calificacion"].'","'.$idControlFactura.'","'.$idControlFacturaDetalle.'","'.$datosSelCCD["id_carga"].'",
										"'.$datosSelCCD["precio_suscripcion"].'","'.$datosSelCCD["activo"].'","'.$ultimo_movimiento.'")';
						$resultInsertCCd=mysql_query($queryInsertCCD);
						
						$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$datosSelCCD["idDetalle"].'"';
						$resultUpdate=mysql_query($sqlUpdate);
					}
				}
			}
		}
		$sqlUpdateFacturas="UPDATE ".$tablaEncabezado." SET subtotal='".$subtotal."', iva='".$iva."', total='".$total."' WHERE id_control_factura=".$idControlFactura;
		mysql_query($sqlUpdateFacturas)or die(mysql_error());
		
		if($tipo2 == 'bonos'){
			/*$sqlUpdateCXP="UPDATE ad_cuentas_por_pagar_operadora SET subtotal_productos='".$subtotal."', iva='".$iva."', total='".$total."',saldo='".$total."' WHERE id_control_factura=".$idControlFactura;
			mysql_query($sqlUpdateCXP)or die(mysql_error());*/
		}
		
		$subtotal=0;
		$iva=0;
		$total=0;
		//$errorFacturacion=cfdiSellaTimbraFactura('FAC',$idControlFactura,$facturas['id_compania'],$tablaEncabezado);	
		$errorFacturacion = 0;		
		if($errorFacturacion=='0'){
		$tabla=$tablaEncabezado;
		$llave=$idControlFactura;
		
		
		
		/*$envio=mailEnviar($llave,$tablaEncabezado,$envio);
			if($envio!=''){
				if($envio=='error'){
					echo 'La factura: '. $id_factura.' no se envio intente facturar nuevamente';
					mysql_query("ROLLBACK");
				}else if($envio=='enviada'){
					mysql_query("COMMIT");
					array_push($facturasEnviadas,$id_factura);
					}
				}*/
		mysql_query("COMMIT");
		echo $errorFacturacion;
		}
		elseif(is_array($errorFacturacion)){
			mysql_query("ROLLBACK");
			echo 'Por favor verifique los datos del receptor para poder realizar el timbrado.';
		}elseif($errorFacturacion == 'fallo'){
			mysql_query("ROLLBACK");
			echo 'Error al conectarse al servicio de timbrado';
		}
		else{
			mysql_query("ROLLBACK");
			echo 'Error al conectarse al servicio de timbrado';
		}
}
}


if($tipo2 == 'penalizaciones'){
	$query='SELECT  cl_control_penalizaciones_detalle.monto_penalizacion as comision, cl_control_penalizaciones.id_control_penalizacion,
					cl_control_penalizaciones_detalle.id_control_penalizacion_detalle as id_detalle,ad_sucursales.id_sucursal,ad_clientes.id_cliente,
					cl_control_penalizaciones_detalle.id_producto_servicio as id_producto_servicio_facturar_audicel, COUNT(ad_clientes.id_cliente) AS cantidad,
					GROUP_CONCAT(cl_control_penalizaciones_detalle.id_control_penalizacion_detalle) as idsDetalleControlPenalizacion,
					ad_clientes.id_proveedor_relacionado, ad_tasas_ivas.porcentaje as IVA

			FROM cl_control_penalizaciones
			LEFT JOIN cl_control_penalizaciones_detalle
				ON cl_control_penalizaciones.id_control_penalizacion = cl_control_penalizaciones_detalle.id_control_penalizacion
			LEFT JOIN ad_sucursales
				ON ad_sucursales.id_sucursal = cl_control_penalizaciones_detalle.id_sucursal
			LEFT JOIN ad_clientes
				ON ad_clientes.id_cliente = cl_control_penalizaciones_detalle.id_cliente
			
			LEFT JOIN cl_productos_servicios
			ON cl_productos_servicios.id_producto_servicio = cl_control_penalizaciones_detalle.id_producto_servicio
			LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_penalizaciones.id_control_penalizacion IN ('.$idDos.') AND cl_control_penalizaciones_detalle.id_control_penalizacion_detalle IN ('.$idDetalleDos.')
			GROUP BY ad_clientes.id_cliente,cl_control_penalizaciones_detalle.id_producto_servicio,comision';

	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	
	if($num > 0){
		while($datos = mysql_fetch_array($result)){
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");
			$facturas=FacturasAudicel($datos['id_cliente']);
			$queryInsert='INSERT INTO ad_facturas_audicel (id_factura,prefijo,consecutivo,id_tipo_comprobante_fiscal,id_compania,id_compania_fiscal,id_sucursal,fecha_y_hora,
							id_moneda,tipo_cambio,id_cliente,email_envio_fa,id_forma_pago_sat,cuenta,id_fiscales_cliente,id_tipo_factura,
							porcentaje_descuento,subtotal_0,subtotal,descuento,iva,ieps,retencion_iva,retencion_isr,total,activo,id_concepto_factura) 
							VALUES ("'.$facturas['id_factura'].'","'.$facturas['prefijo'].'","'.$facturas['consecutivo'].'",1,
							'.$facturas['id_compania'].','.$facturas['id_compania_fiscal'].',"'.$datos['id_sucursal'].'","'. date('Y-m-d H:i:s').'",1,1,
							"'.$facturas['id_cliente'].'","'.$facturas['email'].'","'.$facturas['id_forma_pago'].'","'.$facturas['cuenta'].'",
							"'.$facturas['id_cliente_fiscal'].'",1,0,0,"'.$datos['comision'].'",0,"'.round(($datos['comision'] * ($datos['IVA'] / 100)), 2).'",0,0,0,
							"'.round((($datos['comision'] * ($datos['IVA'] / 100)) + $datos['comision']), 2).'",1,1)';
			
			$resultInsert=mysql_query($queryInsert)or die(mysql_error());
			$idControlFactura=mysql_insert_id();
			
			$subtotalPenalizaciones=0;
			$ivaPenalizaciones=0;
			$totalPenalizaciones=0;
			
			$arr_idsDetalleControlPenalizacion=explode(',',$datos['idsDetalleControlPenalizacion']);
			for($i=0;$i<count($arr_idsDetalleControlPenalizacion);$i++){
				$queryInsertDetalle='INSERT INTO ad_facturas_audicel_detalle (id_control_factura,id_producto,cantidad,valor_unitario,valor_precio_publico,importe,descuento,
									descuento_porcentaje,iva_monto,iva_tasa,ieps_monto,ieps_tasa,retencion_monto,retencion_tasa,activo,id_control_pedido_detalle,
									ids_detalle_control_contrato) VALUES ("'.$idControlFactura.'","'.$datos['id_producto_servicio_facturar_audicel'].'",
									"1","'.$datos['comision'].'",0,"'.$datos['comision'].'",0,0,
									"'. round(($datos['comision'] * ($datos['IVA'] / 100)), 2).'","'.$datos['IVA'].'",0,0,0,0,1,0,"'.$arr_idsDetalleControlPenalizacion[$i].'")';
				$resultInsertDetalle=mysql_query($queryInsertDetalle);
				$idControlFacturaDetalle=mysql_insert_id();
				
				$subtotalPenalizaciones+=round((1 * $datos['comision']),2);
				$ivaPenalizaciones+=round(((1 * $datos['comision'])*($datos['IVA'] / 100)),2);
				$totalPenalizaciones+=round(((1 * $datos['comision'])+((1 * $datos['comision'])*($datos['IVA'] / 100))),2);
				
				$querySelCCD='SELECT * FROM cl_control_penalizaciones_detalle WHERE id_control_penalizacion_detalle='.$arr_idsDetalleControlPenalizacion[$i];
				
				$resultSelCCD=mysql_query($querySelCCD);
				$numSelCCD=mysql_num_rows($resultSelCCD);
				
				if($numSelCCD > 0){
					while($datosSelCCD=mysql_fetch_array($resultSelCCD)){
						if($datosSelCCD["id_accion"] == "54"){ $id_accion="56"; }
						elseif($datosSelCCD["id_accion"] == "55"){ $id_accion="57"; }
						
						$queryInsertCCD='INSERT INTO cl_control_penalizaciones_detalle (id_control_penalizacion,id_accion,fecha_movimiento,hora,id_usuario_agrego,
										id_control_serie,id_sucursal,id_cliente,id_factura_audicel,id_producto_servicio,monto_penalizacion,aceptado,
										id_usuario_modifico,fecha_modificacion,comentario_rechazo_penalizacion,activo,ultimo_movimiento) VALUES 
										("'.$datosSelCCD["id_control_penalizacion"].'","'.$id_accion.'","'.$datosSelCCD["fecha_movimiento"].'","'.$datosSelCCD["hora"].'",
										"'.$datosSelCCD["id_usuario_agrego"].'","'.$datosSelCCD["id_control_serie"].'","'.$datosSelCCD["id_sucursal"].'",
										"'.$datosSelCCD["id_cliente"].'","'.$idControlFactura.'","'.$datosSelCCD["id_producto_servicio"].'",
										"'.$datosSelCCD["monto_penalizacion"].'","'.$datosSelCCD["aceptado"].'","'.$_SESSION["USR"]->userid.'","'. date('Y-m-d H:i:s').'",
										"'.$datosSelCCD["comentario_rechazo_penalizacion"].'","1","2")';
						$resultInsertCCd=mysql_query($queryInsertCCD);
						
						$sqlUpdate='UPDATE cl_control_penalizaciones_detalle SET ultimo_movimiento=0 WHERE id_control_penalizacion_detalle="'.$datosSelCCD["id_control_penalizacion_detalle"].'"';
						$resultUpdate=mysql_query($sqlUpdate);
					}
				}
			}
			
			$sqlUpdateFacturas="UPDATE ad_facturas_audicel SET subtotal='".$subtotal."', iva='".$iva."', total='".$total."' WHERE id_control_factura=".$idControlFactura;
			mysql_query($sqlUpdateFacturas)or die(mysql_error());
			
			$subtotalPenalizaciones=0;
			$ivaPenalizaciones=0;
			$totalPenalizaciones=0;
			
			//$errorFacturacion=cfdiSellaTimbraFactura('FAC',$idControlFactura,$facturas['id_compania'],'ad_facturas_audicel');	
				$errorFacturacion= 0;
				if($errorFacturacion=='0'){
				$tabla=$tablaEncabezado;
				$llave=$idControlFactura;
		/*$envio=mailEnviar($llave,$tablaEncabezado,$envio);
			if($envio!=''){
				if($envio=='error'){
					echo 'La factura: '. $id_factura.' no se envio intente facturar nuevamente';
					mysql_query("ROLLBACK");
				}else if($envio=='enviada'){
					mysql_query("COMMIT");
					array_push($facturasEnviadas,$id_factura);
					}
				}*/
				mysql_query("COMMIT");
		}
		else if(is_array($errorFacturacion)){
			mysql_query("ROLLBACK");
			echo $errorFacturacion['error'];
		}else if($errorFacturacion=='fallo'){
			mysql_query("ROLLBACK");
			echo 'Falló al conectarse al servicio de timbrado';
		}
		else{
			mysql_query("ROLLBACK");
			echo $errorFacturacion;
		}
		}
	}
} 
elseif($tipo2 == 'bonos'){
	$query='SELECT  cl_control_bonos_detalle.monto_bono as comision, cl_control_bonos.id_control_bono,
					cl_control_bonos_detalle.id_control_bono_detalle as id_detalle,ad_sucursales.id_sucursal,ad_clientes.id_cliente,
					cl_control_bonos_detalle.id_producto_servicio as id_producto_servicio_facturar_audicel, COUNT(ad_clientes.id_cliente) AS cantidad,
					GROUP_CONCAT(cl_control_bonos_detalle.id_control_bono_detalle) as idsDetalleControlBono,
					ad_clientes.id_proveedor_relacionado, ad_tasas_ivas.porcentaje as IVA

			FROM cl_control_bonos
			LEFT JOIN cl_control_bonos_detalle
				ON cl_control_bonos.id_control_bono = cl_control_bonos_detalle.id_control_bono
			LEFT JOIN ad_sucursales
				ON ad_sucursales.id_sucursal = cl_control_bonos_detalle.id_sucursal
			LEFT JOIN ad_clientes
				ON ad_clientes.id_cliente = cl_control_bonos_detalle.id_cliente

			LEFT JOIN cl_productos_servicios
				ON cl_productos_servicios.id_producto_servicio = cl_control_bonos_detalle.id_producto_servicio
			LEFT JOIN ad_tasas_ivas
				ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_bonos.id_control_bono IN ('.$idUno.') AND cl_control_bonos_detalle.id_control_bono_detalle IN ('.$idDetalleUno.')
			HAVING COUNT(ad_clientes.id_cliente) > 0
			/*GROUP BY ad_clientes.id_cliente,cl_control_bonos_detalle.id_producto_servicio,comision*/';

	$result = mysql_query($query);
	$num = mysql_num_rows($result);

	if($num > 0){
		while($a_datos = mysql_fetch_array($result)){
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");
			
			$facturas=DatosFacturas($a_datos['id_cliente']);

			$queryInsert='INSERT INTO ad_facturas (id_factura,prefijo,consecutivo,id_tipo_comprobante_fiscal,id_compania,id_compania_fiscal,id_sucursal,fecha_y_hora,id_moneda,tipo_cambio,id_cliente,email_envio_fa,id_forma_pago_sat,cuenta,id_fiscales_cliente,id_tipo_factura,porcentaje_descuento,subtotal_0,subtotal,descuento,iva,ieps,retencion_iva,retencion_isr,total,activo,id_concepto_factura) VALUES ("'.$facturas['id_factura'].'","'.$facturas['prefijo'].'","'.$facturas['consecutivo'].'",1,"'.$facturas['id_compania'].'","'.$facturas['id_compania'].'","'.$a_datos['id_sucursal'].'","'. date('Y-m-d H:i:s').'",1,1,"'.$facturas['id_cliente_fiscal'].'","'.$facturas['email'].'","'.$facturas['id_forma_pago'].'","'.$facturas['cuenta'].'","'.$facturas['id_cliente_fiscal'].'",1,0,0,"'.$a_datos['comision'].'",0,"'.round(($a_datos['comision'] * ($a_datos['IVA'] / 100)), 2).'",0,0,0,"'.round((($a_datos['comision'] * ($a_datos['IVA'] / 100)) + $a_datos['comision']), 2).'",1,1)';
			$resultInsert=mysql_query($queryInsert);
			$idControlFactura=mysql_insert_id();
			
			/*$queryCXP='INSERT INTO ad_cuentas_por_pagar_operadora (id_sucursal,id_tipo_cuenta_por_pagar,id_proveedor,fecha_captura,fecha_documento,
						fecha_vencimiento,id_tipo_documento_recibido,numero_documento,id_cuenta_contable,id_estatus_cuentas_por_pagar,subtotal_productos,
						subtotal_gastos,subtotal_2,subtotal,iva,retencion_iva_documentos,retencion_isr_documentos,total,saldo,ieps,pagos,id_moneda,
						id_control_factura,activo) VALUES ("'.$datos['id_sucursal'].'",1,"'.$datos['id_proveedor_relacionado'].'","'. date('Y-m-d').'",
						"'. date('Y-m-d').'","'. date('Y-m-d').'",1,"'.$facturas['id_factura'].'",1,1,"'. round(($datos['comision'] * 1), 2).'",
						0,0,"'. round(($datos['comision'] * 1), 2).'","'. round(($datos['comision'] * ($datos['IVA'] / 100)) * 1, 2).'",
						0,0,"'. round(($datos['comision'] * 1) + (($datos['comision'] * ($datos['IVA'] / 100)) * 1), 2).'",
						"'. round(($datos['comision'] * 1) + (($datos['comision'] * ($datos['IVA'] / 100)) * 1), 2).'",0,0,1,
						"'.$idControlFactura.'","1")';
			$resultCXP=mysql_query($queryCXP);
			$id_cuenta_por_pagar=mysql_insert_id();*/
			
			$subtotalBonos=0;
			$ivaBonos=0;
			$totalBonos=0;
			
			$queryContratos='SELECT  cl_control_bonos_detalle.monto_bono as comision, cl_control_bonos.id_control_bono,
					cl_control_bonos_detalle.id_control_bono_detalle as id_detalle,ad_sucursales.id_sucursal,ad_clientes.id_cliente,
					cl_control_bonos_detalle.id_producto_servicio as id_producto_servicio_facturar_audicel, COUNT(ad_clientes.id_cliente) AS cantidad,
					GROUP_CONCAT(cl_control_bonos_detalle.id_control_bono_detalle) as idsDetalleControlBono,
					ad_clientes.id_proveedor_relacionado, ad_tasas_ivas.porcentaje as IVA

			FROM cl_control_bonos
			LEFT JOIN cl_control_bonos_detalle
				ON cl_control_bonos.id_control_bono = cl_control_bonos_detalle.id_control_bono
			LEFT JOIN ad_sucursales
				ON ad_sucursales.id_sucursal = cl_control_bonos_detalle.id_sucursal
			LEFT JOIN ad_clientes
				ON ad_clientes.id_cliente = cl_control_bonos_detalle.id_cliente

			LEFT JOIN cl_productos_servicios
				ON cl_productos_servicios.id_producto_servicio = cl_control_bonos_detalle.id_producto_servicio
			LEFT JOIN ad_tasas_ivas
				ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_bonos.id_control_bono IN ('.$idUno.') AND cl_control_bonos_detalle.id_control_bono_detalle IN ('.$idDetalleUno.')
			GROUP BY id_detalle';
			$resultContratos = mysql_query($queryContratos);
			while($datos = mysql_fetch_array($resultContratos)){
				$arr_idsDetalleControlBono = explode(',',$datos['idsDetalleControlBono']);
				for($i=0;$i<count($arr_idsDetalleControlBono);$i++){
					$queryInsertDetalle='INSERT INTO ad_facturas_detalle (id_control_factura,id_producto,cantidad,valor_unitario,valor_precio_publico,importe,descuento,
									descuento_porcentaje,iva_monto,iva_tasa,ieps_monto,ieps_tasa,retencion_monto,retencion_tasa,activo,id_control_pedido_detalle,
									ids_detalle_control_contrato) VALUES ("'.$idControlFactura.'","'.$datos['id_producto_servicio_facturar_audicel'].'",
									"1","'.$datos['comision'].'",0,"'.$datos['comision'].'",0,0,
									"'. round(($datos['comision'] * ($datos['IVA'] / 100)), 2).'","'.$datos['IVA'].'",0,0,0,0,1,0,"'.$arr_idsDetalleControlBono[$i].'")';
					$resultInsertDetalle=mysql_query($queryInsertDetalle);
					$idControlFacturaDetalle=mysql_insert_id();
					
					$subtotalBonos+=round((1 * $datos['comision']),2);
					$ivaBonos+=round(((1 * $datos['comision'])*($datos['IVA'] / 100)),2);
					$totalBonos+=round(((1 * $datos['comision'])+((1 * $datos['comision'])*($datos['IVA'] / 100))),2);
					
					/*$queryCXPDetalle='INSERT INTO ad_cuentas_por_pagar_operadora_detalle_productos (id_cuenta_por_pagar,id_producto,cantidad,costo,importe,
								id_control_factura_detalle) VALUES ("'.$id_cuenta_por_pagar.'","'.$datos['id_producto_servicio_facturar_audicel'].'",
								"1","'.$datos['comision'].'","'. round($datos['comision'], 2).'",
								"'.$idControlFacturaDetalle.'")';
					$resultCXPDetalle=mysql_query($queryCXPDetalle);*/
					
					$querySelCCD='SELECT * FROM cl_control_bonos_detalle WHERE id_control_bono_detalle = '.$arr_idsDetalleControlBono[$i];
					$resultSelCCD=mysql_query($querySelCCD);
					$numSelCCD=mysql_num_rows($resultSelCCD);
					
					if($numSelCCD > 0){
						while($datosSelCCD=mysql_fetch_array($resultSelCCD)){
							if($datosSelCCD["id_accion"] == "62"){ $id_accion="64"; }
							elseif($datosSelCCD["id_accion"] == "63"){ $id_accion="65"; }
							
							$queryInsertCCD='INSERT INTO cl_control_bonos_detalle (id_control_bono,id_accion,fecha_movimiento,hora,id_usuario_agrego,
											id_control_serie,id_sucursal,id_cliente,id_factura_audicel,id_producto_servicio,monto_bono,aceptado,
											comentario_rechazo_bono,activo,ultimo_movimiento,) VALUES 
											("'.$datosSelCCD["id_control_bono"].'","'.$id_accion.'","'. date('Y-m-d').'","'. date('H:i:s').'",
											"'.$datosSelCCD["id_usuario_agrego"].'","'.$datosSelCCD["id_control_serie"].'","'.$datosSelCCD["id_sucursal"].'",
											"'.$datosSelCCD["id_cliente"].'","'.$idControlFactura.'","'.$datosSelCCD["id_producto_servicio"].'",
											"'.$datosSelCCD["monto_bono"].'","'.$datosSelCCD["aceptado"].'","'.$datosSelCCD["comentario_rechazo_bono"].'",
											"1","3")';
							$resultInsertCCd=mysql_query($queryInsertCCD);
							
							$sqlUpdate='UPDATE cl_control_bonos_detalle SET ultimo_movimiento=0 WHERE id_control_bono_detalle="'.$datosSelCCD["id_control_bono_detalle"].'"';
							$resultUpdate=mysql_query($sqlUpdate);
						}
					}
				}
			}
			
			$sqlUpdateFacturas="UPDATE ad_facturas SET subtotal='".$subtotal."', iva='".$iva."', total='".$total."' WHERE id_control_factura=".$idControlFactura;
			mysql_query($sqlUpdateFacturas)or die(mysql_error());
			
			/*$sqlUpdateCXP="UPDATE ad_cuentas_por_pagar_operadora SET subtotal_productos='".$subtotal."', iva='".$iva."', total='".$total."',saldo='".$total."' WHERE id_control_factura=".$idControlFactura;
			mysql_query($sqlUpdateCXP)or die(mysql_error());*/
			
			$subtotalBonos=0;
			$ivaBonos=0;
			$totalBonos=0;
			
			//$errorFacturacion=cfdiSellaTimbraFactura('FAC',$idControlFactura,$facturas['id_compania'],'ad_facturas');	
			$errorFacturacion= 0;			
			if($errorFacturacion=='0'){
			$tabla=$tablaEncabezado;
			$llave=$idControlFactura;
			
			
			
			/*$envio=mailEnviar($llave,$tablaEncabezado,$envio);
			if($envio!=''){
				if($envio=='error'){
					echo 'La factura: '. $id_factura.' no se envio intente facturar nuevamente';
					mysql_query("ROLLBACK");
				}else if($envio=='enviada'){
					mysql_query("COMMIT");
					array_push($facturasEnviadas,$id_factura);
					}
			}*/
			mysql_query("COMMIT");
			}
			else if(is_array($errorFacturacion)){
				mysql_query("ROLLBACK");
				echo $errorFacturacion['error'];
			}else if($errorFacturacion=='fallo'){
				mysql_query("ROLLBACK");
				echo 'Falló al conectarse al servicio de timbrado';
			}
			else{
				mysql_query("ROLLBACK");
				echo $errorFacturacion;
			}
		}
	}
}
function DatosFacturas($id_cliente){
	$datosFactura=array();
	$queryCompania="SELECT MAX(id_cliente_dato_fiscal),ad_clientes.id_cliente 
					FROM ad_clientes_datos_fiscales 
					LEFT JOIN ad_clientes ON ad_clientes.id_cliente=ad_clientes_datos_fiscales.id_cliente
					WHERE ad_clientes.id_cliente='".$id_cliente."'";
	$resultCompania=mysql_query($queryCompania);
	$aCompania = mysql_fetch_array($resultCompania);
	$datosFactura['id_compania_fiscal']=$aCompania[0];
	$datosFactura['id_compania']=$aCompania[1];
	
	$queryFiscales="SELECT id_compania, id_forma_pago, if( id_forma_pago =2, 'NO IDENTIFICADO', cuenta) as cuenta,email
					FROM sys_companias
					WHERE activo =1";
	$resultFiscales=mysql_query($queryFiscales);
	$resultFiscales=mysql_fetch_assoc($resultFiscales);
	$datosFactura['id_cliente']=$resultFiscales['id_compania'];
	$datosFactura['id_cliente_fiscal']=$resultFiscales['id_compania'];
	$datosFactura['id_forma_pago']=$resultFiscales['id_forma_pago'];
	$datosFactura['cuenta']=$resultFiscales['cuenta'];
	$datosFactura['email']=$resultFiscales['email'];
	
	$sql="SELECT serie AS prefijo,folio FROM ad_clientes WHERE id_cliente='".$id_cliente."'";
	$datos_ = new consultarTabla($sql);
	$result_ = $datos_ -> obtenerLineaRegistro();

	$sql_2="
			SELECT id_factura, MAX(consecutivo) AS siguiente
			FROM ad_facturas
			LEFT JOIN(SELECT MAX( id_cliente_dato_fiscal ) AS id_compania
			FROM ad_clientes_datos_fiscales
			LEFT JOIN ad_clientes ON ad_clientes_datos_fiscales.id_cliente = ad_clientes.id_cliente
			WHERE ad_clientes.id_cliente ='".$id_cliente."'
			) AS A ON ad_facturas.id_compania = A.id_compania";
	$datos2 = new consultarTabla($sql_2);
	$result2 = $datos2 -> obtenerLineaRegistro();
	$contador = $datos2 -> cuentaRegistros();

	$consecutivo[0]['prefijo']= $result_['prefijo'];
	if($result2['id_factura'] == ''){
		$consecutivo[0]['pedido']= $result_['prefijo'].$result_['folio'];
		$consecutivo[0]['consecutivo']= $result_['folio'];

	}else{
	$consecutivo[0]['pedido'] =$result_['prefijo'].($result2['siguiente'] + 1);
	$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
	}
	$datosFactura['id_factura']=$consecutivo[0]['pedido'];
	$datosFactura['prefijo']=$consecutivo[0]['prefijo'];
	$datosFactura['consecutivo']=$consecutivo[0]['consecutivo'];
	
	return $datosFactura;
}
function FacturasAudicel($id_cliente){
	$datosFacturaAudicel=array();
	$strEmisor="SELECT id_compania FROM sys_companias where activo=1";
	$resultCompania=mysql_query($strEmisor);
	$datosFacturaAudicel['id_compania']=mysql_result($resultCompania,0);
	$datosFacturaAudicel['id_compania_fiscal']=mysql_result($resultCompania,0);

	$strFiscales="SELECT max(id_cliente_dato_fiscal) as id_cliente_dato_fiscal,email_envio_facturas,id_metodo_pago,if(id_metodo_pago=2, 'NO IDENTIFICADO', cuenta) as cuenta FROM ad_clientes_datos_fiscales LEFT JOIN ad_clientes ON ad_clientes_datos_fiscales.id_cliente=ad_clientes.id_cliente 
	WHERE ad_clientes.id_cliente ='".$id_cliente."'";
	$resultFiscales=mysql_query($strFiscales)or die(mysql_error());
	$arr_fiscales=mysql_fetch_array($resultFiscales);
	$datosFacturaAudicel['id_cliente']=$id_cliente;
	$datosFacturaAudicel['id_cliente_fiscal']=$arr_fiscales['id_cliente_dato_fiscal'];
	
	$datosFacturaAudicel['id_forma_pago']=$arr_fiscales['id_metodo_pago'];
	$datosFacturaAudicel['cuenta']=$arr_fiscales['cuenta'];
	$datosFacturaAudicel['email']=$arr_fiscales['email_envio_facturas'];

	$sql="SELECT clave FROM ad_sucursales WHERE id_sucursal=10";
	$datos_ = mysql_query($sql);
	$result_ = mysql_fetch_array($datos_);
	
	$sql_2="
		SELECT id_factura, MAX(consecutivo) AS siguiente
			FROM ad_facturas_audicel
			LEFT JOIN(SELECT MAX(id_compania) AS id_compania
			FROM sys_companias
			WHERE sys_companias.activo = 1
			) AS A ON ad_facturas_audicel.id_compania = A.id_compania";
	$datos2 = mysql_query($sql_2);
	$result2 = mysql_fetch_array($datos2);
	$consecutivo[0]['prefijo']= $result_['clave'];
	
	if($result2['id_factura'] == ''){
		$consecutivo[0]['pedido']= $result_['clave']."1";
		$consecutivo[0]['consecutivo']= "1";
		
	} else{
			$consecutivo[0]['pedido'] =$result_['clave'].($result2['siguiente'] + 1);
			$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
	}
	$datosFacturaAudicel['id_factura']=$consecutivo[0]['pedido'];
	$datosFacturaAudicel['prefijo']=$consecutivo[0]['prefijo'];
	$datosFacturaAudicel['consecutivo']=$consecutivo[0]['consecutivo'];
	
	return $datosFacturaAudicel;
}
?>