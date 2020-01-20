<?php
include("../../consultaBase.php");
include("../../conect.php");
include("../../code/cfdi/funciones_facturacion.php");
include("../../code/cfdi/facturacion_sellado_timbrado.php");
include("../../code/especiales/enviarFacturas.php");

$seleccion = json_decode($_POST['seleccion'], true);

$ids_Detalle=0;
$envio="";
for($i = 0; $i < count($seleccion); $i++){
	$ids_Detalle .= ','.$seleccion[$i];
}

$query='SELECT cl_control_contratos_detalles.monto_bono as comision, GROUP_CONCAT(cl_control_contratos.id_control_contrato) AS idsControlContratos, cl_control_contratos_detalles.id_detalle,ad_sucursales.id_sucursal,
				ad_clientes.id_cliente,cl_control_contratos_detalles.id_producto_servicio_facturar, COUNT(ad_clientes.id_cliente) AS cantidad,
				GROUP_CONCAT(cl_control_contratos_detalles.id_detalle) as idsDetalleControlContrato,ad_clientes.id_proveedor_relacionado,
				ad_tasas_ivas.porcentaje as IVA, GROUP_CONCAT(cl_control_contratos_detalles.evaluacion_en_braket) AS evaluacion

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
			ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar
		LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		WHERE cl_control_contratos_detalles.id_detalle IN ('.$ids_Detalle.')
		GROUP BY cl_control_contratos_detalles.id_braket';

$result = mysql_query($query);
$num = mysql_num_rows($result);

if($num > 0){
	$ultimo_movimiento='4';
	$tablaEncabezado='ad_facturas';
	$tablaDetalle='ad_facturas_detalle';

	while($datos = mysql_fetch_array($result)){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");

		$facturas=DatosFacturas($datos['id_cliente']);
		
		$ivaXproducto = 0;
		if($datos["IVA"] != '' && $datos["IVA"] > 0){
			$ivaXproducto = ($datos["IVA"] / 100);
		}

		$queryInsert='INSERT INTO '.$tablaEncabezado.' (id_factura,prefijo,consecutivo,id_tipo_comprobante_fiscal,id_compania,id_compania_fiscal,id_sucursal,fecha_y_hora,id_moneda,tipo_cambio,id_cliente,email_envio_fa,id_forma_pago_sat,cuenta,id_fiscales_cliente,id_tipo_factura,porcentaje_descuento,subtotal_0,subtotal,descuento,iva,ieps,retencion_iva,retencion_isr,total,activo,id_concepto_factura) 
VALUES ("'.$facturas['id_factura'].'","'.$facturas['prefijo'].'","'.$facturas['consecutivo'].'",1,"'.$datos['id_cliente'].'","'.$facturas['id_compania'].'","'.$datos['id_sucursal'].'","'. date('Y-m-d H:i:s').'",1,1,"'.$facturas['id_cliente_fiscal'].'",
						"'.$facturas['email'].'","'.$facturas['id_forma_pago'].'","'.$facturas['cuenta'].'","'.$facturas['id_cliente_fiscal'].'",1,0,0,"'.$datos['comision'].'",0,"'.round(($datos['comision'] * ($datos['IVA'] / 100)), 2).'",0,0,0,"'.round((($datos['comision'] * ($datos['IVA'] / 100)) + $datos['comision']), 2).'",1,1)';	
		$resultInsert=mysql_query($queryInsert);
		$idControlFactura=mysql_insert_id();
		
		$id_cuenta_por_pagar="";
		$queryCXP='INSERT INTO ad_cuentas_por_pagar_operadora (id_sucursal,id_tipo_cuenta_por_pagar,id_proveedor,fecha_captura,fecha_documento,
					fecha_vencimiento,id_tipo_documento_recibido,numero_documento,id_cuenta_contable,id_estatus_cuentas_por_pagar,subtotal_productos,
					subtotal_gastos,subtotal_2,subtotal,iva,retencion_iva_documentos,retencion_isr_documentos,total,saldo,ieps,pagos,id_moneda,
					id_control_factura,activo) VALUES ("'.$datos['id_sucursal'].'",1,"'.$datos['id_proveedor_relacionado'].'","'. date('Y-m-d').'",
					"'. date('Y-m-d').'","'. date('Y-m-d').'",1,"'.$idControlFactura.'",1,1,"'. round(($datos['comision'] / (1 + $ivaXproducto)), 4).'",
					0,0,"'. round(($datos['comision'] / (1 + $ivaXproducto)), 4).'","'. round((($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto), 4).'",
					0,0,"'. round((($datos['comision'] / (1 + $ivaXproducto))) + ((($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto)), 4).'",
					"'. round((($datos['comision'] / (1 + $ivaXproducto))) + ((($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto)), 4).'",0,0,1,
					"'.$idControlFactura.'","1")';
		$resultCXP=mysql_query($queryCXP);
		$id_cuenta_por_pagar=mysql_insert_id();
		
		$subtotal=0;
		$iva=0;
		$total=0;
		
		$arr_evaluaciones = explode(",",$datos['evaluacion']);
		$arr_idsDetalleControlContrato=explode(',',$datos['idsDetalleControlContrato']);
		
		for($i=0;$i<count($arr_idsDetalleControlContrato);$i++){
			$queryInsertDetalle='INSERT INTO '.$tablaDetalle.' (id_control_factura,id_producto,cantidad,valor_unitario,valor_precio_publico,importe,descuento,
							descuento_porcentaje,iva_monto,iva_tasa,ieps_monto,ieps_tasa,retencion_monto,retencion_tasa,activo,id_control_pedido_detalle,
							ids_detalle_control_contrato) VALUES ("'.$idControlFactura.'","'.$datos['id_producto_servicio_facturar'].'",
							"1","'.round(($datos['comision'] / (1 + $ivaXproducto)), 4).'",0,"'.round(($datos['comision'] / (1 + $ivaXproducto)), 4).'",0,0,
							"'. round(($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto, 4).'","'.$ivaXproducto.'",0,0,0,0,1,0,"'.$arr_idsDetalleControlContrato[$i].'")';
			$resultInsertDetalle=mysql_query($queryInsertDetalle);
			$idControlFacturaDetalle=mysql_insert_id();
			
			if($arr_evaluaciones[$i] == 'E'){
				$subtotal += $datos['comision'] / (1 + $ivaXproducto);
				$iva += (($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto);
				$total += ($datos['comision'] / (1 + $ivaXproducto)) +((($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto));
			}
			
			$queryCXPDetalle='INSERT INTO ad_cuentas_por_pagar_operadora_detalle_productos (id_cuenta_por_pagar,id_producto,cantidad,costo,importe,
							id_control_factura_detalle) VALUES ("'.$id_cuenta_por_pagar.'","'.$datos['id_producto_servicio_facturar'].'",
							"1","'.round(($datos['comision'] / (1 + $ivaXproducto)), 4).'","'. round(($datos['comision'] / (1 + $ivaXproducto)), 4).'",
							"'.$idControlFacturaDetalle.'")';
			$resultCXPDetalle=mysql_query($queryCXPDetalle);
			
			
			$querySelCCD='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
						cl_control_contratos_detalles.id_contra_recibo,
						cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
						cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
						cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
						cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
						cl_control_contratos_detalles.id_entidad_financiera_vendedor,
						cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
						cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar,
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
					$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$datosSelCCD["idDetalle"].'"';
					$resultUpdate=mysql_query($sqlUpdate);
				}
			}
		}
		
		$sqlUpdateFacturas="UPDATE ".$tablaEncabezado." SET subtotal='".round($subtotal,4)."', iva='".round($iva,4)."', total='".round($total,4)."' WHERE id_control_factura=".$idControlFactura;
		mysql_query($sqlUpdateFacturas)or die(mysql_error());
		
		$sqlUpdateCXP="UPDATE ad_cuentas_por_pagar_operadora SET subtotal_productos='".round($subtotal,4)."', iva='".round($iva,4)."', total='".round($total,4)."',saldo='".round($total,4)."' WHERE id_control_factura=".$idControlFactura;
		mysql_query($sqlUpdateCXP)or die(mysql_error());
		
		$subtotal=0;
		$iva=0;
		$total=0;
		
		$errorFacturacion=cfdiSellaTimbraFactura('FAC',$idControlFactura,$facturas['id_compania'],$tablaEncabezado);	
		$errorFacturacion=0;
		
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
		} elseif(is_array($errorFacturacion)){
			mysql_query("ROLLBACK");
			echo $errorFacturacion['error'];
		}elseif($errorFacturacion=='fallo'){
			mysql_query("ROLLBACK");
			echo 'Falló al conectarse al servicio de timbrado';
		} else {
			mysql_query("ROLLBACK");
			echo $errorFacturacion; 
		}
	}
}


function DatosFacturas($id_cliente){
	//$id_cliente=2;
	$datosFactura=array();
	$queryCompania="SELECT MAX(id_cliente_dato_fiscal) 
					FROM ad_clientes_datos_fiscales 
					LEFT JOIN ad_clientes ON ad_clientes.id_cliente=ad_clientes_datos_fiscales.id_cliente
					WHERE ad_clientes.id_cliente=".$id_cliente;
	$resultCompania=mysql_query($queryCompania);
	$datosFactura['id_compania']=mysql_result($resultCompania,0);
	
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
	
	$sql="SELECT serie AS prefijo,folio FROM ad_clientes WHERE id_cliente=".$id_cliente;
	$datos_ = new consultarTabla($sql);
	$result_ = $datos_ -> obtenerLineaRegistro();

	$sql_2="
			SELECT id_factura, MAX(consecutivo) AS siguiente
			FROM ad_facturas
			LEFT JOIN(SELECT MAX( id_cliente_dato_fiscal ) AS id_compania
			FROM ad_clientes_datos_fiscales
			LEFT JOIN ad_clientes ON ad_clientes_datos_fiscales.id_cliente = ad_clientes.id_cliente
			WHERE ad_clientes.id_cliente =".$id_cliente."
			) AS A ON ad_facturas.id_compania = A.id_compania";
	$datos2 = new consultarTabla($sql_2);
	$result2 = $datos2 -> obtenerLineaRegistro();
	$contador = $datos2 -> cuentaRegistros();

	$consecutivo[0]['prefijo']= $result_['prefijo'];
	if($result2['id_factura'] == ''){
		$consecutivo[0]['pedido']= $result_['prefijo'].$result_['folio'];
		$consecutivo[0]['consecutivo']= $result_['folio'];

	}else{
	$consecutivo[0]['pedido'] = $result_['prefijo'].($result2['siguiente'] + 1);
	$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
	}
	$datosFactura['id_factura']=$consecutivo[0]['pedido'];
	$datosFactura['prefijo']=$consecutivo[0]['prefijo'];
	$datosFactura['consecutivo']=$consecutivo[0]['consecutivo'];
	
	return $datosFactura;
}
function FacturasAudicel($id_cliente){
	//$id_cliente=2;
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
