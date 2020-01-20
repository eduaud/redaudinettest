<?php
include("../../conect.php");

$arr_migraciones = json_decode($_POST['migraciones'], true);
$cadena_migraciones = implode(",", $arr_migraciones);


// ***   Extrae los campos del layout de migraciones   *** //
$campos = "";
$query = "SELECT nombre_campo FROM cl_layouts_configuracion WHERE id_layout = 21";
$result = mysql_query($query);

while($datos = mysql_fetch_array($result)){
	$campos .= $campos == "" ? $datos["nombre_campo"] : ",".$datos["nombre_campo"];
}
$campos .= ",id_layout,id_carga,fecha_carga,id_cliente,id_tipo_cliente_proveedor,id_entidad_financiera,remesa,factura,estatus,porcentaje_comision";
$campos .= ",monto_sin_iva,id_control_factura_distribuidor,id_control_cuenta_por_pagar,observaciones,activo,ultimo_movimiento";
// ***   Termina Extrae los campos del layout de migraciones   *** //


// ***   Query principal   *** //
$query = "
	SELECT GROUP_CONCAT(DISTINCT(id_control)) AS ids, d106,t46,t47,t48,t49,t50,t51,t52,t53,id_layout,id_carga,cl_importacion_migraciones.fecha_carga
		,cl_importacion_migraciones.id_cliente,cl_importacion_migraciones.id_tipo_cliente_proveedor,cl_importacion_migraciones.id_entidad_financiera
		,remesa,factura,estatus,porcentaje_comision,monto_sin_iva,id_control_factura_distribuidor,id_control_cuenta_por_pagar,observaciones
		,cl_importacion_migraciones.activo,ultimo_movimiento
		,IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 1, cl_clasificacion_de_servicios_detalle.distribuidor,
				IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 2, cl_clasificacion_de_servicios_detalle.discom,
					IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 3, cl_clasificacion_de_servicios_detalle.fuerza_externa,
						IF(cl_importacion_migraciones.id_tipo_cliente_proveedor = 4, cl_clasificacion_de_servicios_detalle.fuerza_propia, 0)
					)
				)
		 ) AS monto_a_facturar, ROUND((ad_tasas_ivas.porcentaje / 100),2) AS IVA
		,IF(cl_importacion_migraciones.id_cliente IS NOT NULL AND cl_importacion_migraciones.id_cliente != '',
				sucursales_clientes.nombre, sucursales_entidades.nombre
			) AS sucursal
		,IF(cl_importacion_migraciones.id_cliente IS NOT NULL AND cl_importacion_migraciones.id_cliente != '',
				sucursales_clientes.id_sucursal, sucursales_entidades.id_sucursal
			) AS id_sucursal_pertenece, ad_clientes.id_proveedor_relacionado, COUNT(id_control) AS cantidad
		, cl_clasificacion_de_servicios.id_producto AS id_producto_servicio_facturar
	FROM cl_importacion_migraciones
	LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_importacion_migraciones.id_cliente
	LEFT JOIN ad_entidades_financieras ON ad_entidades_financieras.id_entidad_financiera = cl_importacion_migraciones.id_entidad_financiera
	LEFT JOIN cl_clasificacion_de_servicios ON cl_clasificacion_de_servicios.nombre = cl_importacion_migraciones.t53
	LEFT JOIN cl_clasificacion_de_servicios_detalle
		ON cl_clasificacion_de_servicios_detalle.id_clasificacion_servicios = cl_clasificacion_de_servicios.id_clasificacion_servicios
		AND cl_clasificacion_de_servicios_detalle.cantidad_a_facturar = cl_importacion_migraciones.monto_sin_iva
	LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_clasificacion_de_servicios.id_producto
	LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
	LEFT JOIN ad_sucursales AS sucursales_clientes ON sucursales_clientes.id_sucursal = ad_clientes.id_sucursal
	LEFT JOIN ad_sucursales AS sucursales_entidades ON sucursales_entidades.id_sucursal = ad_entidades_financieras.id_sucursal
	WHERE id_control IN (".$cadena_migraciones.")
	GROUP BY t46, monto_sin_iva
";
// ***   Termina Query principal   *** //

$result_general = mysql_query($query);
while($datos_general = mysql_fetch_array($result_general)){
	// ***   Variables de datos fiscales   *** //
	$id_factura = "";
	$prefijo = "";
	$consecutivo = "";
	$id_compania = "";
	$id_compania_fiscales = "";
	$id_cliente = "";
	$email = "";
	$id_forma_pago = "";
	$cuenta = "";
	$id_cliente_fiscal = "";
	// ***   Termina Variables de datos fiscales   *** //
	
	
	// ***   Registra Factura   *** //
	$queryInsert='INSERT INTO ad_facturas(id_factura,prefijo,consecutivo,id_tipo_comprobante_fiscal,id_compania,id_compania_fiscal,id_sucursal,
				fecha_y_hora,id_moneda,tipo_cambio,id_cliente,email_envio_fa,id_forma_pago_sat,cuenta,id_fiscales_cliente,id_tipo_factura,
				porcentaje_descuento,subtotal_0,subtotal,descuento,iva,ieps,retencion_iva,retencion_isr,total,activo,id_concepto_factura) 
				VALUES ("'.$id_factura.'","'.$prefijo.'","'.$consecutivo.'",1,"'.$id_compania.'","'.$id_compania_fiscales.'",
				"'.$datos_general['id_sucursal_pertenece'].'","'. date('Y-m-d H:i:s').'",1,1,"'.$id_cliente.'","'.$email.'","'.$id_forma_pago.'","'.$cuenta.'",
				"'.$id_cliente_fiscal.'",1,0,0,"'.$datos_general['monto_a_facturar'].'",0,
				"'.round(($datos_general['monto_a_facturar'] * $datos_general['IVA']), 2).'",0,0,0,
				"'.round((($datos_general['monto_a_facturar'] * $datos_general['IVA']) + $datos_general['monto_a_facturar']), 2).'",1,1)';
	$resultInsert=mysql_query($queryInsert)or die($queryInsert.mysql_error());
	$id_control_factura=mysql_insert_id();
	// ***   Termina Registra Factura   *** //
	
	
	// ***   Registra CXP   *** //
	$queryCXP='INSERT INTO ad_cuentas_por_pagar_operadora(id_sucursal,id_tipo_cuenta_por_pagar,id_proveedor,fecha_captura,fecha_documento,fecha_vencimiento
				,id_tipo_documento_recibido,numero_documento,id_cuenta_contable,id_estatus_cuentas_por_pagar,subtotal_productos,subtotal_gastos,subtotal_2
				,subtotal,iva,retencion_iva_documentos,retencion_isr_documentos,total,saldo,ieps,pagos,id_moneda,id_control_factura) 
				VALUES ("'.$datos_general['id_sucursal_pertenece'].'",1,"'.$datos_general['id_proveedor_relacionado'].'","'. date('Y-m-d').'",
				"'. date('Y-m-d').'","'. date('Y-m-d').'",1,"'.$id_factura.'",1,1,"'. round(($datos_general['monto_a_facturar'] * $datos_general['cantidad']), 2).'"
				,0,0,"'. round(($datos_general['monto_a_facturar'] * $datos_general['cantidad']), 2).'"
				,"'. round(($datos_general['monto_a_facturar'] * $datos_general['IVA']) * $datos_general['cantidad'], 2).'",0,0
				,"'. round(($datos_general['monto_a_facturar'] * $datos_general['cantidad']) + (($datos_general['monto_a_facturar'] * $datos_general['IVA']) * $datos_general['cantidad']), 2).'"
				,"'. round(($datos_general['monto_a_facturar'] * $datos_general['cantidad']) + (($datos_general['monto_a_facturar'] * $datos_general['IVA']) * $datos_general['cantidad']), 2).'"
				,0,0,1,"'.$id_control_factura.'")';
	$resultCXP=mysql_query($queryCXP);
	$id_cuenta_por_pagar=mysql_insert_id();
	// ***   Termina Registra CXP   *** //
	
	$subtotal = 0;
	$iva = 0;
	$total = 0;
	
	$arr_ids=explode(',',$datos_general['ids']);
	for($i = 0; $i < count($arr_ids); $i++){
		// ***   Registra detalle de la factura   *** //
		$queryInsertDetalle='INSERT INTO ad_facturas_detalle (id_control_factura,id_producto,cantidad,valor_unitario,valor_precio_publico,importe,descuento,
		descuento_porcentaje,iva_monto,iva_tasa,ieps_monto,ieps_tasa,retencion_monto,retencion_tasa,activo,id_control_pedido_detalle,
		ids_detalle_control_contrato) VALUES ("'.$id_control_factura.'","'.$datos_general['id_producto_servicio_facturar'].'",
		"1","'.$datos_general['monto_a_facturar'].'",0,"'.$datos_general['monto_a_facturar'].'",0,0,
		"'. round(($datos_general['monto_a_facturar'] * $datos_general['IVA']), 2).'","'.$datos_general['IVA'].'",0,0,0,0,1,0,"'.$arr_ids[$i].'")';
		$resultInsertDetalle=mysql_query($queryInsertDetalle)or die(mysql_error());
		$id_control_factura_detalle=mysql_insert_id();
		// ***   Termina Registra detalle de la factura   *** //
		
		$subtotal += round((1 * $datos_general['monto_a_facturar']),2);
		$iva += round(((1 * $datos_general['monto_a_facturar']) * $datos_general['IVA']),2);
		$total += round(((1 * $datos_general['monto_a_facturar']) + ((1 * $datos_general['monto_a_facturar']) * $datos_general['IVA'])),2);
		
		
		// ***   Registra detalle de la CXP   *** //
		$queryCXPDetalle='INSERT INTO ad_cuentas_por_pagar_operadora_detalle_productos(id_cuenta_por_pagar,id_producto,cantidad,costo,importe,
		id_control_factura_detalle) VALUES ("'.$id_cuenta_por_pagar.'","'.$datos_general['id_producto_servicio_facturar'].'",
		"1","'.$datos_general['monto_a_facturar'].'","'. round(($datos_general['monto_a_facturar'] * 1), 2).'",
		"'.$id_control_factura_detalle.'")';
		$resultCXPDetalle=mysql_query($queryCXPDetalle);
		// ***   Termina Registra detalle de la CXP   *** //
		
		
		// ***   Duplica el registro para generar historial de la migracion   *** //
		$query = "
			INSERT INTO cl_importacion_migraciones (".$campos.")
			SELECT ".$campos." FROM cl_importacion_migraciones WHERE id_control = ".$arr_ids[$i]."
		";

		$result = mysql_query($query);
		$id_migracion_new = mysql_insert_id();
		// ***   Termina Duplica el registro para generar historial de la migracion   *** //
		
		
		// ***   Actualiza la informacion del resgistro que se acaba de duplicar   *** //
		$query = "
			UPDATE cl_importacion_migraciones SET fecha_carga = '".date("Y-m-d H:i:s")."', estatus = 'Facturado'
				, id_control_factura_distribuidor = ".$id_control_factura.", id_control_cuenta_por_pagar = ".$id_cuenta_por_pagar."
			WHERE id_control = ".$id_migracion_new."
		";
		$result = mysql_query($query);
		// ***   Termina Actualiza la informacion del resgistro que se acaba de duplicar   *** //
		
		
		// ***   Actualiza campo ultimo_movimiento a 0   *** //
		$query = "UPDATE cl_importacion_migraciones SET ultimo_movimiento = 0 WHERE id_control = ".$arr_ids[$i];
		$result = mysql_query($query);
		// ***   Termina Actualiza campo ultimo_movimiento a 0   *** //
	}
	
	$sqlUpdateFacturas="UPDATE ad_facturas SET subtotal='".$subtotal."', iva='".$iva."', total='".$total."' WHERE id_control_factura=".$id_control_factura;
	mysql_query($sqlUpdateFacturas)or die(mysql_error());
	
	$sqlUpdateCXP="UPDATE ad_cuentas_por_pagar_operadora SET subtotal_productos='".$subtotal."', iva='".$iva."', total='".$total."',saldo='".$total."' WHERE id_control_factura=".$id_control_factura;
	mysql_query($sqlUpdateCXP)or die(mysql_error());
	
	$subtotal=0;
	$iva=0;
	$total=0;
	// ***   Termina Registra Factura   *** //
}

echo "exito";
?>