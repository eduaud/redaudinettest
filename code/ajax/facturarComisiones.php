<?php
include("../../consultaBase.php");
include("../../conect.php");
include("../../code/cfdi/funciones_facturacion.php");
include("../../code/cfdi/facturacion_sellado_timbrado.php");
include("../../code/general/funciones.php");
include("../../code/especiales/enviarFacturas.php");
include("../../code/ajax/notificaciones.php");
include("../../code/contabilidad/funcionesContabilidad.php");
/***   convirte fecha a formato Y-m-d   ***/
function formatoFechaYMD($fecha){
	if(strpos($fecha,"-")) $delimitador="-";
	elseif(strpos($fecha,"/")) $delimitador="/";
	$pos=strpos($fecha,$delimitador);
	
	if($pos == 2) $iniciaPor='dia';
	elseif($pos == 4) $iniciaPor='anio';
	
	$arrFecha=explode($delimitador,$fecha);
	
	if($iniciaPor=='dia'){
		$fechaAMD=$arrFecha[2].'-'.$arrFecha[1].'-'.$arrFecha[0];
	} elseif($iniciaPor=='anio'){
		$fechaAMD=$arrFecha[0].'-'.$arrFecha[1].'-'.$arrFecha[2];
	}
	
	return $fechaAMD;
}
$contadorEnvioMails = 0;
/***   Termina convirte fecha a formato Y-m-d   ***/

$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];
$id_accion_contrato = $_POST['id_accion_contrato'];
$filtro = '';

if($fechaIni != "" && $fechaFin != ""){
	if($fechaIni <= $fechaFin){
		$filtro .= ' AND cl_control_contratos.fecha_activacion BETWEEN "'.formatoFechaYMD($fechaIni).'" AND "'.formatoFechaYMD($fechaFin).'"';
	}
}

$query='SELECT  IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 NOT IN ("TMK01", "TMKOM", "BCRCOMV", "BCRCOM"), (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
						IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 NOT IN ("TMK01", "TMKOM", "BCRCOMV", "BCRCOM"), (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
								IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
									IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35), 0)
								)
							)
						)
					)
				) as comision, cl_control_contratos.id_control_contrato, cl_control_contratos_detalles.id_detalle,ad_clientes.id_sucursal,
				ad_clientes.id_cliente,cl_importacion_caja_comisiones.id_producto_servicio_cxp AS id_producto_servicio_facturar, COUNT(ad_clientes.id_cliente) AS cantidad,
				GROUP_CONCAT(cl_control_contratos_detalles.id_detalle) as idsDetalleControlContrato,ad_clientes.id_proveedor_relacionado,
				ad_tasas_ivas.porcentaje as IVA, cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor

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
			ON cl_productos_servicios.id_producto_servicio = cl_importacion_caja_comisiones.id_producto_servicio_cxp
		LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		WHERE cl_control_contratos_detalles.id_accion_contrato = '.$id_accion_contrato.' AND cl_control_contratos_detalles.id_calificacion = 1 AND
				cl_control_contratos_detalles.activo = 1 AND ultimo_movimiento="1" '.$filtro.'
				AND ad_clientes.id_cliente IN ('.$_SESSION["USR"]->clientes_relacionados.')
		/*GROUP BY ad_clientes.id_cliente,cl_importacion_caja_comisiones.id_producto_servicio_cxp,comision*/';

$result = mysql_query($query);
$num = mysql_num_rows($result);
if($num > 0){
	$envio="";
	$facturasEnviadas="";
	$facturasNoEnviadas="";
	$contadorFacturasEnviadas=1;
	while($a_datos = mysql_fetch_array($result)){
		//$datos['id_cliente']='61';
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$queryCompania = "SELECT MAX(id_cliente_dato_fiscal) 
				     FROM ad_clientes_datos_fiscales 
				     LEFT JOIN ad_clientes ON ad_clientes.id_cliente=ad_clientes_datos_fiscales.id_cliente
				     WHERE ad_clientes.id_cliente=".$a_datos['id_cliente'];
		$resultCompania = mysql_query($queryCompania);
		$id_compania_fiscales = mysql_result($resultCompania,0);
		
		$queryFiscales = "SELECT id_compania, id_forma_pago, if( id_forma_pago =2, 'NO IDENTIFICADO', cuenta) as cuenta,email
				FROM sys_companias
				WHERE activo =1";
		$resultFiscales = mysql_query($queryFiscales);
		$resultFiscales = mysql_fetch_assoc($resultFiscales);
		$id_cliente = $resultFiscales['id_compania'];
		$id_forma_pago = $resultFiscales['id_forma_pago'];
		$cuenta = $resultFiscales['cuenta'];
		$email = $resultFiscales['email'];
		
		$sql = "SELECT IFNULL(serie,'') AS prefijo,folio FROM ad_clientes WHERE id_cliente IN(".$_SESSION["USR"]->clientes_relacionados .")";	
		$datos_ = new consultarTabla($sql);
		$result_ = $datos_ -> obtenerLineaRegistro();
	
			$sql_2 = "
			SELECT id_factura, MAX(consecutivo) AS siguiente
			FROM ad_facturas
			WHERE ad_facturas.id_compania IN(".$_SESSION["USR"]->clientes_relacionados .")";
			$datos2 = new consultarTabla($sql_2);
			$result2 = $datos2 -> obtenerLineaRegistro();
			$consecutivo=array();
			$consecutivo[0]['prefijo']= $result_['prefijo'];
			if($result2['id_factura'] == ''){
				$consecutivo[0]['pedido'] = $result_['prefijo'].$result_['folio'];
				$consecutivo[0]['consecutivo']= $result_['folio'];
		
			} else{
					$consecutivo[0]['pedido'] =$result_['prefijo'].($result2['siguiente'] + 1);
					$consecutivo[0]['consecutivo']= $result2['siguiente'] + 1;
			}
		$id_factura=$consecutivo[0]['pedido'];
		$prefijo=$consecutivo[0]['prefijo'];
		$consecutivo=$consecutivo[0]['consecutivo'];
		$id_compania=$a_datos['id_cliente'];
		$id_cliente_fiscal=$id_cliente;
		//die($id_factura.'::'.$prefijo.'::'.$consecutivo);
		
		$queryInsert='INSERT INTO ad_facturas(id_factura,prefijo,consecutivo,id_tipo_comprobante_fiscal,id_compania,id_compania_fiscal,id_sucursal,
						fecha_y_hora,id_moneda,tipo_cambio,id_cliente,email_envio_fa,id_forma_pago_sat,cuenta,id_fiscales_cliente,id_tipo_factura,
						porcentaje_descuento,subtotal_0,subtotal,descuento,iva,ieps,retencion_iva,retencion_isr,total,activo,id_concepto_factura) 
						VALUES ("'.$id_factura.'","'.$prefijo.'","'.$consecutivo.'",1,"'.$id_compania.'","'.$id_compania_fiscales.'","'.$a_datos['id_sucursal'].'",
						"'. date('Y-m-d H:i:s').'",1,1,"'.$id_cliente.'","'.$email.'","'.$id_forma_pago.'","'.$cuenta.'",
						"'.$id_cliente_fiscal.'",1,0,0,"'.$a_datos['comision'].'",0,"'.round(($a_datos['comision'] * ($a_datos['IVA'] / 100)), 2).'",0,0,0,
						"'.round((($a_datos['comision'] * ($a_datos['IVA'] / 100)) + $a_datos['comision']), 2).'",1,1)';
		$resultInsert=mysql_query($queryInsert)or die($queryInsert.mysql_error());
		$idControlFactura=mysql_insert_id();	
		
		/*$queryCXP='INSERT INTO ad_cuentas_por_pagar_operadora(id_sucursal,id_tipo_cuenta_por_pagar,id_proveedor,fecha_captura,fecha_documento,fecha_vencimiento,id_tipo_documento_recibido,numero_documento,id_cuenta_contable,id_estatus_cuentas_por_pagar,subtotal_productos,
					subtotal_gastos,subtotal_2,subtotal,iva,retencion_iva_documentos,retencion_isr_documentos,total,saldo,ieps,pagos,id_moneda,
					id_control_factura,activo) VALUES ("'.$a_datos['id_sucursal'].'",1,"'.$a_datos['id_proveedor_relacionado'].'","'. date('Y-m-d').'",
					"'. date('Y-m-d').'","'. date('Y-m-d').'",1,"'.$id_factura.'",1,1,"'. round(($a_datos['comision'] * $a_datos['cantidad']), 2).'",
					0,0,"'. round(($a_datos['comision'] * $a_datos['cantidad']), 2).'","'. round(($a_datos['comision'] * ($a_datos['IVA'] / 100)) * $a_datos['cantidad'], 2).'",
					0,0,"'. round(($a_datos['comision'] * $a_datos['cantidad']) + (($a_datos['comision'] * ($a_datos['IVA'] / 100)) * $a_datos['cantidad']), 2).'",
					"'. round(($a_datos['comision'] * $a_datos['cantidad']) + (($a_datos['comision'] * ($a_datos['IVA'] / 100)) * $a_datos['cantidad']), 2).'",0,0,1,
					"'.$idControlFactura.'",1)';
		$resultCXP=mysql_query($queryCXP);
		$id_cuenta_por_pagar=mysql_insert_id();*/
		$subtotal=0;
		$iva=0;
		$total=0;
		$queryContratos = 'SELECT  IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 NOT IN ("TMK01", "TMKOM", "BCRCOMV", "BCRCOM"), (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
						IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 NOT IN ("TMK01", "TMKOM", "BCRCOMV", "BCRCOM"), (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
								IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
									IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35), 0)
								)
							)
						)
					)
				) as comision, cl_control_contratos.id_control_contrato, cl_control_contratos_detalles.id_detalle,ad_clientes.id_sucursal,
				ad_clientes.id_cliente,cl_importacion_caja_comisiones.id_producto_servicio_cxp AS id_producto_servicio_facturar, COUNT(ad_clientes.id_cliente) AS cantidad,
				cl_control_contratos_detalles.id_detalle as idsDetalleControlContrato,ad_clientes.id_proveedor_relacionado,
				ad_tasas_ivas.porcentaje as IVA, cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor

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
			ON cl_productos_servicios.id_producto_servicio = cl_importacion_caja_comisiones.id_producto_servicio_cxp
		LEFT JOIN ad_tasas_ivas
			ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
		WHERE cl_control_contratos_detalles.id_accion_contrato = '.$id_accion_contrato.' AND cl_control_contratos_detalles.id_calificacion = 1 AND
				cl_control_contratos_detalles.activo = 1 AND ultimo_movimiento="1" '.$filtro.'
				AND ad_clientes.id_cliente IN ('.$_SESSION["USR"]->clientes_relacionados.')
				GROUP BY id_detalle';
		$resultContratos = mysql_query($queryContratos);
		//die($queryContratos);
		while($datos = mysql_fetch_array($resultContratos)){
			$arr_detalleFacturas = explode(',',$datos['idsDetalleControlContrato']);
			for($i=0;$i<count($arr_detalleFacturas);$i++){
				$ivaXproducto = 0;
				if($datos["IVA"] != '' && $datos["IVA"] > 0){
					$ivaXproducto = ($datos["IVA"] / 100);
				}
				
				$queryInsertDetalle='INSERT INTO ad_facturas_detalle (id_control_factura,id_producto,cantidad,valor_unitario,valor_precio_publico,importe,descuento,
				descuento_porcentaje,iva_monto,iva_tasa,ieps_monto,ieps_tasa,retencion_monto,retencion_tasa,activo,id_control_pedido_detalle,
				ids_detalle_control_contrato) VALUES ("'.$idControlFactura.'","'.$datos['id_producto_servicio_facturar'].'",
				"1","'.round(($datos['comision'] / (1 + $ivaXproducto)),2).'",0,"'.round(($datos['comision'] / (1 + $ivaXproducto)),2).'",0,0,
				"'. round(($datos['comision'] - ($datos['comision'] / (1 + $ivaXproducto))), 2).'","'.$datos['IVA'].'",0,0,0,0,1,0,"'.$arr_detalleFacturas[$i].'")';
				$resultInsertDetalle = mysql_query($queryInsertDetalle)or die(mysql_error());
				$idControlFacturaDetalle=mysql_insert_id();
				
				
				
				if($datos["id_tipo_cliente_proveedor"] == '3' || $datos["id_tipo_cliente_proveedor"] == '12') { // ***   Total Tec Ext, Total Vendedor Ext   *** //
					$subtotal += round(($datos['comision'] / (1 + $ivaXproducto)),2);
					$iva += round((($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto),2);
					$total += round((($datos['comision'] / (1 + $ivaXproducto)) + (($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto)),2);
				} elseif($datos["id_tipo_cliente_proveedor_ef"] == '7' || $datos["id_tipo_cliente_proveedor_ef"] == '4') { // ***   Total Tec FP, Total Vendedor FP   *** //
					$subtotal += round($datos['comision'],2);
					$iva += 0;
					$total += round($datos['comision'],2);
				} else {
					$subtotal += round(($datos['comision'] / (1 + $ivaXproducto)),2);
					$iva += round((($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto),2);
					$total += round((($datos['comision'] / (1 + $ivaXproducto)) + (($datos['comision'] / (1 + $ivaXproducto)) * $ivaXproducto)),2);
				}
				
				/*$queryCXPDetalle='INSERT INTO ad_cuentas_por_pagar_operadora_detalle_productos(id_cuenta_por_pagar,id_producto,cantidad,costo,importe,
				id_control_factura_detalle) VALUES ("'.$id_cuenta_por_pagar.'","'.$datos['id_producto_servicio_facturar'].'",
				"1","'.$datos['comision'].'","'. round(($datos['comision'] * 1), 2).'",
				"'.$idControlFacturaDetalle.'")';
				$resultCXPDetalle=mysql_query($queryCXPDetalle);*/
				
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
								cl_control_contratos_detalles.id_calificacion,
								cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
								cl_control_contratos_detalles.activo
							FROM cl_control_contratos_detalles 
							WHERE id_detalle='.$arr_detalleFacturas[$i];
				$resultSelCCD=mysql_query($querySelCCD);
				$numSelCCD=mysql_num_rows($resultSelCCD);
				
				if($numSelCCD > 0){
					while($datosSelCCD=mysql_fetch_array($resultSelCCD)){
						$queryInsertCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,
										id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
										id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,
										monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,
										id_carga,precio_suscripcion,principal,activo,ultimo_movimiento,id_usuario_alta) VALUES ("'.$datosSelCCD["id_control_contrato"].'",
										"4","'.$datosSelCCD["id_contra_recibo"].'","'. date("Y-m-d H:i:s").'",
										"'.$datosSelCCD["id_tipo_activacion"].'","'.$datosSelCCD["id_control_serie"].'","'.$datosSelCCD["numero_serie"].'","'.$datosSelCCD["id_paquete_sky"].'",
										"'.$datosSelCCD["id_sucursal"].'","'.$datosSelCCD["id_cliente"].'","'.$datosSelCCD["id_cliente_tecnico"].'",
										"'.$datosSelCCD["id_entidad_financiera_tecnico"].'","'.$datosSelCCD["id_entidad_financiera_vendedor"].'","'.$datosSelCCD["id_funcionalidad"].'",
										"'.$datosSelCCD["clave"].'","'.$datosSelCCD["id_detalle_caja_comisiones"].'","'.$datosSelCCD["id_producto_servicio_facturar"].'",
										"'.$datosSelCCD["monto_pagar"].'","'.$datosSelCCD["monto_cobrar"].'","'.$datosSelCCD["id_calificacion"].'","'.$idControlFactura.'",
										"'.$idControlFacturaDetalle.'","'.$datosSelCCD["id_carga"].'","'.$datosSelCCD["precio_suscripcion"].'",1,
										"'.$datosSelCCD["activo"].'","1","'.$_SESSION["USR"]->userid.'")';
						$resultInsertCCd=mysql_query($queryInsertCCD);
						
						$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$datosSelCCD["idDetalle"].'"';
						$resultUpdate=mysql_query($sqlUpdate);
					}
				}
			}
		}
		$sqlUpdateFacturas="UPDATE ad_facturas SET subtotal='".$subtotal."', iva='".$iva."', total='".$total."' WHERE id_control_factura=".$idControlFactura;
		mysql_query($sqlUpdateFacturas)or die(mysql_error());
		
		/*$sqlUpdateCXP="UPDATE ad_cuentas_por_pagar_operadora SET subtotal_productos='".$subtotal."', iva='".$iva."', total='".$total."',saldo='".$total."' WHERE id_control_factura=".$idControlFactura;
		mysql_query($sqlUpdateCXP)or die(mysql_error());*/
		
		$subtotal=0;
		$iva=0;
		$total=0;
		//$errorFacturacion = cfdiSellaTimbraFactura('FAC',$idControlFactura,$id_compania,'ad_facturas');	
		$errorFacturacion=0;
		if($errorFacturacion=='0'){
			/***** 
			$tabla='ad_facturas';
			$llave=$idControlFactura;
			$sqlFactura = "SELECT folio_cfd FROM ad_facturas WHERE id_control_factura = ".$idControlFactura;
			$datosFac = new consultarTabla($sqlFactura);
			$resultFac = $datosFac -> obtenerLineaRegistro();
			
			$updateCXP = "UPDATE ad_cuentas_por_pagar_operadora SET folio_fiscal = '".$resultFac['folio_cfd']."' WHERE id_control_factura = ".$idControlFactura;
			mysql_query($updateCXP) or die('error en: '.$updateCXP. mysql_error());
			include("../../code/ajax/genera_documentos.php");
			$sqlCXP = "
					SELECT CONCAT('CP_',numero_documento) AS nota
					FROM ad_cuentas_por_pagar_operadora 
					WHERE id_cuenta_por_pagar = ".$id_cuenta_por_pagar;
			$arrCXP = valBuscador($sqlCXP);
			$cuenta_padre = obtenerCuentaMayor(6,$llave);
			$id_cc = GeneraCuentaContable($cuenta_padre,$arrCXP[0],$arrCXP[0],1,3,0,0);
			$sqlUpdate = "
				UPDATE ad_cuentas_por_pagar_operadora 
				SET id_cuenta_contable = ".$id_cc." 
				WHERE id_cuenta_por_pagar = ".$llave;
			mysql_query($sqlUpdate) or die("error en $sqlUpdate ".mysql_error());
			*****/
			
			/**** ENVIAR MAIL 
			$strDatos = "
					SELECT id_factura, DATE_FORMAT( fecha_y_hora, '%d/%m/%Y' ) AS fecha , sys_companias.razon_social, IF( ISNULL( email_envio_fa ) , '',email_envio_fa ) correo_electronico, cancelada, '' AS descripcion,CONCAT('$',FORMAT(total,2)) AS total,sys_companias.rfc,ad_clientes.razon_social as compania
					FROM ad_facturas
					LEFT JOIN sys_companias ON ad_facturas.id_fiscales_cliente = sys_companias.id_compania
					LEFT JOIN ad_clientes ON ad_facturas.id_compania = ad_clientes.id_cliente
					WHERE ad_facturas.id_control_factura='".$idControlFactura."'";
			$resDatos = mysql_query($strDatos)or die("Error en \n$strDatos\n\nDescripcion:\n".mysql_error("error"));
			$rowDatos = mysql_fetch_array($resDatos);
			$asunto = "Factura Electronica  :: ".$rowDatos[0];
			$email = $rowDatos[3];
			$cuerpoMail = layoutFactura($rowDatos);
			$documentos = $nombre_archivo.','.$nombre_archivoPDF;
			
			enviar_notificacion('', $email, $rowDatos['razon_social'], $cuerpoMail,$asunto ,"" , "rmedina@audicel.com.mx,psanjuan@audicel.com.mx,amcsysandweb@gmail.com", $documentos,'','');
			/**** TERMINA ENVIAR MAIL ****/
			mysql_query("COMMIT");
			echo "exito";
		}else{
			mysql_query("ROLLBACK");
			echo $errorFacturacion;
		}
	}
}
/*echo "Facturas enviadas ".$facturasEnviadas."\n\n";
echo "Facturas no enviadas ".$facturasNoEnviadas;*/
?>