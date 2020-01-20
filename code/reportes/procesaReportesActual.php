<?php

/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/

include('../../conect.php');
include('../../code/general/funciones.php');
include('../../include/phpreports/PHPReportMaker.php');
include('../../consultaBase.php');

date_default_timezone_set('America/Mexico_City');

function getMonthDays($Month, $Year){
	if( is_callable("cal_days_in_month"))  //Si la extensión está instalada
		return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
	else	//Si no se calcula manualmente
		return date("d",mktime(0,0,0,$Month+1,0,$Year));
}
		
function ordenarArregloFecha( $a, $b ) {
	return strtotime($a['fecha']) - strtotime($b['fecha']);
}

extract($_GET);	

switch($idRep){
	case 227:
		$strWhere = "";
	
		$strWhere .= generar_where_consulta_para_fecha($sFechaInicial, $sFechaFinal, "na_mitabla.campo_fecha");
		$strWhere .= ($sSKU != "")? " AND SKU LIKE '%" . $sSKU . "%' " : "";
			
		$strAlmacenes = implode(",", $arrayAlmacenes);
		$strWhere .= ($strAlmacenes != 0 && $strAlmacenes != "")? " AND ad_sucursales.id_sucursal IN(" . $strAlmacenes . ")" : "";
			
		$strProveedores = implode(",", $sProveedores);
		$strWhere .= ($strProveedores != 0 && $strProveedores != "")? " AND ad_sucursales.id_sucursal IN(" . $strProveedores . ")" : "";
			
		$strWhere .= generar_where_consulta_para_fecha($sFechaIngresoInicial, $sFechaIngresoFinal, "na_mitabla.campo_fecha");
			
		$strSQL= "";
			
	break;
	case 105:
		$strWhere = " AND ad_clientes.id_cliente in(".$_SESSION["USR"]->clientes_relacionados .")";
		
		if($fechadel != "" && $fechaal != ""){
			$fechadel = convertDate($fechadel);
			$fechaal = convertDate($fechaal);

			$fechadelAux = strtotime($fechadel);
			$fechaalAux = strtotime($fechaal);
			
			if($fechadelAux > $fechaalAux){
				$strWhere .= " AND DATE_FORMAT(cl_control_contratos.fecha_activacion, '%Y-%m-%d') BETWEEN '".$fechaal."' AND '".$fechadel."'";
			} else {
				$strWhere .= " AND DATE_FORMAT(cl_control_contratos.fecha_activacion, '%Y-%m-%d') BETWEEN '".$fechadel."' AND '".$fechaal."'";
			}
		}
		$strSQL = "
				SELECT cl_control_contratos.contrato, cl_control_contratos.cuenta,
                                cl_importacion_activaciones.t54 as tarjeta,
                                DATE_FORMAT(cl_control_contratos.fecha_activacion,'%d/%m/%Y') AS fecha_activacion,cl_paquetes_sky.nombre_paquete_sky
					,/*cl_control_contratos_detalles.clave*/cl_importacion_caja_comisiones.t46 AS clave,ad_sucursales.nombre AS nombre_sucursal
					,IF(cl_control_contratos_detalles.id_cliente != 0, CONCAT_WS(' ',ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno),
						CONCAT_WS(' ',ad_entidades_financieras.nombre,ad_entidades_financieras.apellido_paterno,ad_entidades_financieras.apellido_materno)
					 ) AS nombre_cliente
					,IF(cl_control_contratos_detalles.id_cliente != 0, ad_clientes.clave,ad_entidades_financieras.clave) AS clave_cliente,IF(cl_control_contratos_detalles.id_contra_recibo IS NULL OR cl_control_contratos_detalles.id_contra_recibo = 0,'N/A',cl_control_contratos_detalles.id_contra_recibo) AS id_contra_recibo,cl_contratos_acciones.nombre AS estatus_actual
					,IF(cl_control_contratos_detalles.id_cliente != 0, ad_clientes.di, ad_sucursales.di) AS DI
					,cl_tipos_activacion.nombre AS tipo,
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 != 'TMK01', (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
						IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 != 'TMK01', (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
								IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
									IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
										IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35), 0)
									)
								)
							)
						)
					) as comision,cl_control_contratos_detalles.id_control_contrato
				FROM cl_control_contratos
				LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato
				LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
				LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal
				LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente
				LEFT JOIN ad_entidades_financieras ON ad_entidades_financieras.id_entidad_financiera = cl_control_contratos_detalles.id_entidad_financiera_tecnico
				LEFT JOIN cl_contratos_acciones ON cl_contratos_acciones.id_accion_control = cl_control_contratos_detalles.id_accion_contrato
				LEFT JOIN cl_tipos_activacion ON cl_tipos_activacion.id_tipo_activacion = cl_control_contratos_detalles.id_tipo_activacion
				
				LEFT JOIN cl_tipos_cliente_proveedor
					ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
				LEFT JOIN cl_importacion_caja_comisiones
					ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
				LEFT JOIN ad_tipos_entidades_financieras
					ON ad_tipos_entidades_financieras.id_tipo_entidad_financiera = ad_entidades_financieras.id_tipo_entidad_financiera
				LEFT JOIN cl_tipos_cliente_proveedor as cl_tipos_cliente_proveedor_entidadesFin
					ON cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = ad_tipos_entidades_financieras.id_tipo_cliente_proveedor
				LEFT JOIN cl_importacion_activaciones on cl_importacion_activaciones.t56 = cl_control_contratos.contrato	
				WHERE 
                                cl_importacion_activaciones.t46 = cl_control_contratos.cuenta
                                and cl_importacion_activaciones.t56 = cl_control_contratos.contrato
                                and cl_control_contratos_detalles.activo = 1 
				AND cl_control_contratos_detalles.ultimo_movimiento = 1 ".$strWhere."
				AND principal = 1
				ORDER BY DATE_FORMAT(cl_control_contratos.fecha_activacion, '%Y-%m-%d') DESC
			";
		include("rep105_contratos.html.php");
		die();
	break;
	case 150:
		$fechadel = $_GET['fechadel'];
		$fechaal = $_GET['fechaal'];
		$estatus = $_GET['estatus'];

		$strWhere = "";

		if(!empty($estatus)) {
			if($estatus == '1') {
				$strWhereFact .= " AND CAST((monto - (cobrosDetalles + cobrosBancarios)) AS DECIMAL (20 , 2)) > 0";
				$strWhereCXP  .= " AND (monto - (pagosCP + pagosEgresos)) > 0";
				$strWhereCXC  .= " AND (monto - (cobrosDetalles + cobrosBancarios)) > 0";
			} else {
				$strWhereFact .= " AND CAST((monto - (cobrosDetalles + cobrosBancarios)) AS DECIMAL (20 , 2)) <= 0";
				$strWhereCXP  .= " AND (monto - (pagosCP + pagosEgresos)) <= 0";
				$strWhereCXC  .= " AND (monto - (cobrosDetalles + cobrosBancarios)) <= 0";
			}
		}
		if($fechadel != "" && $fechaal != ""){
			$fechadel = convertDate($fechadel);
			$fechaal = convertDate($fechaal);

			$fechadelAux = strtotime($fechadel);
			$fechaalAux = strtotime($fechaal);
			
			if($fechadelAux > $fechaalAux){
				$strWhereFact .= " AND DATE_FORMAT(fecha_factura, '%Y-%m-%d') BETWEEN '" . $fechaal . "' AND '" . $fechadel . "'";
				$strWhereCXP  .= " AND DATE_FORMAT(fecha_captura, '%Y-%m-%d') BETWEEN '" . $fechaal . "' AND '" . $fechadel . "'";
				$strWhereCXC  .= " AND DATE_FORMAT(fecha_cxc, '%Y-%m-%d') BETWEEN '" . $fechaal . "' AND '" . $fechadel . "'";
			} else {
				$strWhereFact .= " AND DATE_FORMAT(fecha_factura, '%Y-%m-%d') BETWEEN '" . $fechadel . "' AND '" . $fechaal . "'";
				$strWhereCXP  .= " AND DATE_FORMAT(fecha_captura, '%Y-%m-%d') BETWEEN '" . $fechadel . "' AND '" . $fechaal . "'";
				$strWhereCXC  .= " AND DATE_FORMAT(fecha_cxc, '%Y-%m-%d') BETWEEN '" . $fechadel . "' AND '" . $fechaal . "'";
			}
		}
		$strSQLFacturas = "
			SELECT 
			    control_factura,
			    factura,
			    cliente,
			    DATE_FORMAT(fecha_factura, '%d/%m/%Y') AS fecha_factura,
			    CONCAT('$', FORMAT(monto, 2)) AS total,
			    CONCAT('$', FORMAT((cobrosDetalles + cobrosBancarios), 2)) AS suma_cobros,
			    CONCAT('$', FORMAT((monto - (cobrosDetalles + cobrosBancarios)), 2)) AS saldo_factura,
			    concepto,
			    datos.abreviacion,
			    FORMAT(monto - (cobrosDetalles + cobrosBancarios), 2) AS Cobrar,
			    CAST((monto - (cobrosDetalles + cobrosBancarios)) AS DECIMAL (20 , 2)) AS saldo_factura_sin_formato
			FROM
			    (
					SELECT 
						aux.id_control_factura AS control_factura,
			            aux.id_factura AS factura,
			            ad_clientes.razon_social AS cliente,
			            aux.total AS monto,
			            aux.fecha_y_hora AS fecha_factura,
			            abreviacion,
			            (
							SELECT IF(SUM(monto) IS NULL, 0, SUM(monto))
			                FROM ad_facturas_audicel_detalles_cobros
			                WHERE activoCobro = 1 AND ad_facturas_audicel_detalles_cobros.id_control_factura = aux.id_control_factura
						) AS cobrosDetalles,
			            (
							SELECT IF(SUM(monto) IS NULL, 0, SUM(monto))
			                FROM ad_depositos_bancarios_detalle
			                WHERE id_tipo_documento_deposito = 1
			                      AND activoDetBancarios = 1
			                      AND activoDetBancarios = 1
			                      AND ad_depositos_bancarios_detalle.id_control_documento = aux.id_control_factura
						) AS cobrosBancarios,
			            (
							SELECT nombre
			                FROM cl_facturas_conceptos_encabezado
			                WHERE activo = 1 AND cl_facturas_conceptos_encabezado.id_concepto = aux.id_concepto_factura
						) AS concepto
					FROM ad_facturas_audicel AS aux
					LEFT JOIN ad_clientes ON aux.id_cliente = ad_clientes.id_cliente
					LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
					LEFT JOIN ad_facturas_audicel_detalles_cobros ON aux.id_control_factura = ad_facturas_audicel_detalles_cobros.id_control_factura
					LEFT JOIN (
						SELECT id_control_documento
						FROM ad_depositos_bancarios_detalle
						LEFT JOIN ad_tipos_documentos_depositos ON ad_depositos_bancarios_detalle.id_tipo_documento_deposito = ad_tipos_documentos_depositos.id_tipo_documento
						WHERE ad_tipos_documentos_depositos.id_tipo_documento = 1
					) AS documento ON aux.id_control_factura = documento.id_control_documento
					WHERE (aux.folio_cfd IS NOT NULL OR aux.folio_cfd <> '') AND timbrado <> '' AND (aux.cancelada IS NULL OR aux.cancelada = 0) AND ad_clientes.id_cliente IN(" . $_SESSION["USR"] -> clientes_relacionados . ")
				) AS datos
			WHERE 1 " . $strWhereFact . "
			GROUP BY control_factura
		";
		$strSQLCXP = "
			SELECT 
			    id_cxp,
			    DATE_FORMAT(fecha_captura, '%d/%m/%Y') AS fecha_captura,
			    documento,
			    proveedor,
			    CONCAT('$', FORMAT(monto, 2)) AS monto,
			    CONCAT('$', FORMAT((pagosCP + pagosEgresos), 2)) AS totalPagos,
			    FORMAT((monto - (pagosCP + pagosEgresos)), 2) AS saldo,
			    id_proveedor,
			    cliente,
			    numero_documento,
			    concepto,
			    (monto - (pagosCP + pagosEgresos)) AS saldo_sin_formato
			FROM
			    (
					SELECT 
						id_cuenta_por_pagar AS id_cxp,
			            fecha_captura,
			            total AS monto,
			            ad_tipos_documentos.nombre AS documento,
			            ad_proveedores.razon_social AS proveedor,
			            ad_proveedores.id_proveedor AS id_proveedor,
			            CONCAT_WS(' ', ad_clientes.nombre, ad_clientes.apellido_paterno, ad_clientes.apellido_materno) AS cliente,
			            numero_documento,
			            (
							SELECT IF(SUM(monto) IS NULL, 0, SUM(monto))
			                FROM ad_cuentas_por_pagar_operadora_detalle_pagos
			                WHERE activoDetCXP = 1 AND id_cuenta_por_pagar = aux.id_cuenta_por_pagar
						) AS pagosCP,
			            (
							SELECT IF(SUM(monto) IS NULL, 0, SUM(monto))
			                FROM ad_egresos_detalle
			                WHERE activoDetEgreso = 1 AND id_cuenta_por_pagar = aux.id_cuenta_por_pagar
						) AS pagosEgresos,
			            (
							SELECT nombre
			                FROM cl_facturas_conceptos_encabezado
			                WHERE activo = 1 AND cl_facturas_conceptos_encabezado.id_concepto = aux.id_concepto
						) AS concepto
					FROM ad_cuentas_por_pagar_operadora AS aux
					LEFT JOIN ad_tipos_documentos ON aux.id_tipo_documento_recibido = ad_tipos_documentos.id_tipo_documento
					LEFT JOIN ad_proveedores ON aux.id_proveedor = ad_proveedores.id_proveedor
					LEFT JOIN ad_clientes ON ad_proveedores.clave = ad_clientes.clave
					WHERE ad_clientes.id_tipo_cliente_proveedor IN (1 , 2, 3) AND ad_clientes.activo = 1 and aux.id_estatus_cuentas_por_pagar <> 3 AND ad_clientes.id_cliente IN(" . $_SESSION["USR"] -> clientes_relacionados . ")
				) AS datos
			WHERE 1 " . $strWhereCXP;
			$strSQLCXC = "
				SELECT 
				    control_cxc,
				    cxc,
				    cliente,
				    DATE_FORMAT(fecha_cxc, '%d/%m/%Y') AS fecha_cxc,
				    CONCAT('$', FORMAT(monto, 2)) AS total,
				    CONCAT('$', FORMAT((cobrosDetalles + cobrosBancarios), 2)) AS suma_cobros,
				    CONCAT('$', FORMAT((monto - (cobrosDetalles + cobrosBancarios)), 2)) AS saldo_cxc,
				    concepto,
				    datos.abreviacion,
				    FORMAT(monto - (cobrosDetalles + cobrosBancarios), 2) AS Cobrar,
				    (monto - (cobrosDetalles + cobrosBancarios)) AS saldo_cxc_sin_formato,
				    contrato
				FROM
				    (
						SELECT 
							aux.id_control_cuenta_por_cobrar AS control_cxc,
				            aux.id_cuenta_por_cobrar AS cxc,
				            ad_clientes.razon_social AS cliente,
				            aux.total AS monto,
				            IF(cl_control_contratos.id_control_contrato IS NULL OR cl_control_contratos.id_control_contrato = 0 OR cl_control_contratos.id_control_contrato = '', fecha_creacion, fecha_activacion) AS fecha_cxc,
				            abreviacion,
				            (
								SELECT IF(SUM(monto) IS NULL, 0, SUM(monto))
				                FROM ad_cuentas_por_cobrar_detalle_cobros
				                WHERE activoCobro = 1 AND ad_cuentas_por_cobrar_detalle_cobros.id_control_cuenta_por_cobrar = aux.id_control_cuenta_por_cobrar
							) AS cobrosDetalles,
				            (
								SELECT IF(SUM(monto) IS NULL, 0, SUM(monto))
				                FROM ad_depositos_bancarios_detalle
				                WHERE id_tipo_documento_deposito = 2 AND activoDetBancarios = 1 AND ad_depositos_bancarios_detalle.id_control_documento = aux.id_control_cuenta_por_cobrar
							) AS cobrosBancarios,
				            (
								SELECT nombre 
								FROM cl_facturas_conceptos_encabezado 
								WHERE activo = 1 AND cl_facturas_conceptos_encabezado.id_concepto = aux.id_concepto
							) AS concepto,
							cl_control_contratos.contrato 
				    FROM ad_cuentas_por_cobrar AS aux
				    LEFT JOIN ad_clientes ON aux.id_cliente = ad_clientes.id_cliente
				    LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor
				    LEFT JOIN ad_cuentas_por_cobrar_detalle_cobros ON aux.id_control_cuenta_por_cobrar = ad_cuentas_por_cobrar_detalle_cobros.id_control_cuenta_por_cobrar
				    LEFT JOIN (
						SELECT id_control_documento
						FROM ad_depositos_bancarios_detalle
						LEFT JOIN ad_tipos_documentos_depositos ON ad_depositos_bancarios_detalle.id_tipo_documento_deposito = ad_tipos_documentos_depositos.id_tipo_documento
						WHERE ad_tipos_documentos_depositos.id_tipo_documento = 2
					) AS documento ON aux.id_control_cuenta_por_cobrar = documento.id_control_documento
				    LEFT JOIN cl_control_contratos ON aux.id_control_contrato = cl_control_contratos.id_control_contrato
				    WHERE /*aux.id_estatus_cuenta = 1 AND */ad_clientes.id_cliente IN(" . $_SESSION["USR"] -> clientes_relacionados . ")
				) AS datos
				WHERE 1 " . $strWhereCXC . "
				GROUP BY control_cxc";
		//die($strSQLFacturas.$strSQLCXP.$strSQLCXC);
                
                if($opcion == 1){
                    
                    include('estado_cuenta.html.php');

                    
                }        
                        
                elseif($opcion == 2){//genera excel
                 
                include('estado_cuenta.html.php');
                
		$out = ob_get_clean();
                
                $nombreArchivo = 'Estado_de_cuenta_' . date('Y-m-d_h:i:s') . '.xls';
                
                header("Content-disposition: attachment; filename=$nombreArchivo;");
                header("Content-type: attachment/vnd.ms-excel");
                readfile($nombreArchivo);
                
//----------------------------------------------------------------------//
                
                echo $out;
                
                die();
                
		
                
	}
	
	break;
        
        
        case 228:
		$strWhere = "";
            
                if($movimientos[0] == 0){
                    
                $strWhere .= " AND ad_subtipos_movimientos.id_subtipo_movimiento IN(70223,70099) ";   
                    
                }

		$movimientosFil = implode(",", $movimientos);
		$strWhere .= ($movimientosFil != 0 && $movimientosFil != "")? " AND ad_subtipos_movimientos.id_subtipo_movimiento IN(" . $movimientosFil . ")" : "";

		$strWhere .= generar_where_consulta_para_fecha($fecha_ingreso_inicial, $fecha_ingreso_final, "fecha_movimiento");

		$strWhere .= ($tipo_producto != 0 && $tipo_producto != "")? " AND cl_productos_servicios.id_tipo_producto_servicio IN(".$tipo_producto.")" : "";

		$productosFil = implode(",", $productos);
		$strWhere .= ($productosFil != 0 && $productosFil != "")? " AND ad_movimientos_almacen_detalle.id_producto IN(".$productosFil.")" : "";

		$proveedoresFil = implode(",", $proveedores);

		$strWhere .= ($proveedoresFil != 0 && $proveedoresFil != "")? " AND ad_proveedores.id_proveedor IN(" . $proveedoresFil . ")" : "";

		$idGrupo = $_SESSION["USR"] -> idGrupo;

                
    		if($idGrupo != 1 || $tipoReporte == 1){
    			$idRep = $idRep."d";
    		}
                
                
                $cliente = " and ad_clientes.id_cliente in(" . $_SESSION["USR"]->clientes_relacionados . ')';
                
		$strSQL = "
			SELECT
				ad_movimientos_almacen_detalle.id_detalle,  
				ad_movimientos_almacen.id_movimiento AS id_movimiento,
				ad_subtipos_movimientos.nombre AS subtipo_movimiento,
				ad_almacenes.nombre AS nombre_almacen,
				DATE_FORMAT(fecha_movimiento, '%d/%m/%Y') AS fecha_movimiento,
				hora_movimiento,
				IF(ad_movimientos_almacen.id_subtipo_movimiento=70099,
					CONCAT('FACTURA:',ad_facturas_audicel.id_factura),
						IF(ad_movimientos_almacen.id_subtipo_movimiento in (70014,70223),
							CONCAT('SOLICITUD MATERIAL :',ad_movimientos_almacen_detalle.id_solicitud_material),
								IF(ad_movimientos_almacen.id_subtipo_movimiento=70223,
									CONCAT('PEDIDO:',ad_movimientos_almacen_detalle.id_pedido),
										id_movimiento)
						)
				) AS idoc,
				ad_proveedores.razon_social AS proveedor,
				CONCAT_WS(' ',IF(ad_clientes.clave IS NULL,'',CONCAT(ad_clientes.clave,' ::')),ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) AS Cliente,
				CONCAT_WS(' ',IF(ad_entidades_financieras.clave IS NULL,'',CONCAT(ad_entidades_financieras.clave,' ::')),ad_entidades_financieras.nombre,ad_entidades_financieras.apellido_paterno,ad_entidades_financieras.apellido_materno) AS Tecnico,
				ad_movimientos_almacen.id_usuario AS id_usuario,
				CONCAT_WS(' ',sys_usuarios.nombres,sys_usuarios.apellido_paterno,sys_usuarios.apellido_materno) AS nombre_usuario,
				IF(ad_subtipos_movimientos.id_subtipo_movimiento,ad_movimientos_almacen.id_orden_servicio,ad_movimientos_almacen.observaciones) AS observaciones_movimiento,
				cl_productos_servicios.clave AS clave,
				cl_productos_servicios.nombre AS nombre_producto,
				ad_lotes.lote AS lote,
				ad_movimientos_almacen_detalle.cantidad AS cantidad,
				/*ad_lotes.costo_unitario AS costo,*/
                                cl_productos_servicios.precio as costo,
				/*ad_movimientos_almacen_detalle.cantidad * ad_lotes.costo_unitario AS importe,*/
                                ad_movimientos_almacen_detalle.cantidad * cl_productos_servicios.precio as importe,
				'' AS documento,
				ad_facturas_audicel.id_factura,
				ad_movimientos_almacen_detalle.id_pedido
			FROM ad_movimientos_almacen
			LEFT JOIN ad_movimientos_almacen_detalle
				ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento
			LEFT JOIN cl_productos_servicios
				ON cl_productos_servicios.id_producto_servicio = ad_movimientos_almacen_detalle.id_producto
			LEFT JOIN cl_tipos_producto_servicio
				ON cl_productos_servicios.id_tipo_producto_servicio = cl_tipos_producto_servicio.id_tipo_producto_servicio
			LEFT JOIN ad_almacenes
				ON ad_movimientos_almacen.id_almacen = ad_almacenes.id_almacen
			LEFT JOIN ad_subtipos_movimientos
				ON ad_subtipos_movimientos.id_subtipo_movimiento = ad_movimientos_almacen.id_subtipo_movimiento
			LEFT JOIN ad_proveedores
				ON ad_movimientos_almacen.id_proveedor = ad_proveedores.id_proveedor
			LEFT JOIN ad_lotes
				ON ad_lotes.id_lote = ad_movimientos_almacen_detalle.id_lote
			LEFT JOIN sys_usuarios
				ON sys_usuarios.id_usuario = ad_movimientos_almacen.id_usuario
			LEFT JOIN ad_pedidos
				ON ad_movimientos_almacen_detalle.id_control_pedido = ad_pedidos.id_control_pedido
			LEFT JOIN ad_clientes
				ON ad_pedidos.id_cliente = ad_clientes.id_cliente
			LEFT JOIN ad_solicitudes_material
				ON ad_movimientos_almacen_detalle.id_control_solicitud_material = ad_solicitudes_material.id_control_solicitud_material
			LEFT JOIN ad_entidades_financieras
				ON ad_solicitudes_material.id_empleado = ad_entidades_financieras.id_entidad_financiera
			LEFT JOIN ad_facturas_audicel
				ON ad_movimientos_almacen_detalle.id_control_factura = ad_facturas_audicel.id_control_factura
			WHERE ad_movimientos_almacen_detalle.cantidad <> 0 AND ad_movimientos_almacen.id_control_movimiento
			IN(
				SELECT DISTINCT ad_movimientos_almacen.id_control_movimiento
				FROM ad_movimientos_almacen
				LEFT JOIN ad_movimientos_almacen_detalle
					ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento
				LEFT JOIN cl_productos_servicios
					ON cl_productos_servicios.id_producto_servicio = ad_movimientos_almacen_detalle.id_producto
				LEFT JOIN cl_tipos_producto_servicio
					ON cl_productos_servicios.id_tipo_producto_servicio = cl_tipos_producto_servicio.id_tipo_producto_servicio
				LEFT JOIN ad_almacenes
					ON ad_movimientos_almacen.id_almacen = ad_almacenes.id_almacen
				LEFT JOIN ad_subtipos_movimientos
					ON ad_subtipos_movimientos.id_subtipo_movimiento = ad_movimientos_almacen.id_subtipo_movimiento
				LEFT JOIN ad_proveedores
					ON ad_movimientos_almacen.id_proveedor = ad_proveedores.id_proveedor
				LEFT JOIN sys_usuarios
					ON sys_usuarios.id_usuario = ad_movimientos_almacen.id_usuario
				WHERE ad_movimientos_almacen.id_tipo_movimiento = 2 AND ad_movimientos_almacen.activo = 1". $strWhere .  $cliente . "
			)
		";
                
//                echo $strSQL;
//                
//                exit("Fin del programa");

	break;
        
	default:
		echo "No existe algún reporte con este ID. Cierre la ventana e intente nuevamente.";
		die();
	break;
}

if($opcion != 2 && $idRep != 150)
if(!mysql_query($strSQL)) die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());

//Referenciamos al XML

if($version == 1)
	$reporte = "rep".$idRep."d.xml";
else
	$reporte = "rep".$idRep.".xml";

		
$titulo = (isset($titulo))?$titulo:"Reporte_generico";
$titulo=strtoupper($titulo);

function insert_date(){
	return "Generado el : ".date('d/m/Y   H:i:s' );
}
function insert_Titulo(){
	return $listaPreciosNombre;
}
function insert_where(){
	return ' -'.  $filtro;
}

function generar_where_consulta_para_fecha($sFechaInicial, $sFechaFinal, $sCampoTabla) {
	$strWhere = "";

	if($sFechaInicial == "" && $sFechaFinal != "") {
		$strWhere .= " AND DATE_FORMAT(" . $sCampoTabla . ", '%Y-%m-%d') <= '" . convertDate($sFechaFinal) . "'";
	}
	else if($sFechaInicial != "" && $sFechaFinal == "") {
		$strWhere .= " AND DATE_FORMAT(" . $sCampoTabla . ", '%Y-%m-%d') >= '" . convertDate($sFechaInicial) . "'";
	}
	else if($sFechaInicial != "" && $sFechaFinal != "") {
		$sFechaInicial = convertDate($sFechaInicial);
		$sFechaFinal = convertDate($sFechaFinal);

		$sFechaInicialAux = strtotime($sFechaInicial);
		$sFechaFinalAux = strtotime($sFechaFinal);

		if($sFechaInicialAux > $sFechaFinalAux) {
			$strWhere .= " AND DATE_FORMAT(" . $sCampoTabla . ", '%Y-%m-%d') BETWEEN '$sFechaFinal' AND '$sFechaInicial'";
		}
		else {
			$strWhere .= " AND DATE_FORMAT(" . $sCampoTabla . ", '%Y-%m-%d') BETWEEN '$sFechaInicial' AND '$sFechaFinal'";
		}
	}

	return $strWhere;
}

//Creamo el objeto de tipo reporte

if($idRep != 150){
    
$oRpt = new PHPReportMaker();
$oRpt->setConnection($dbhost);
$oRpt->setDatabase($dbname);
$oRpt->setDatabaseInterface($dbvendor);
$oRpt->setUser($dbuser);
$oRpt->setPassword($dbpassword);
$oRpt->setSQL(iconv("ISO-8859-1","UTF-8",$strSQL));
$oRpt->setXML($reporte);

}   

if($idRep != 150)
{
    
ob_start();
$oRpt->run();

}

if($opcion==2 && $idRep != 150){//genera excel
//                ob_start();
		$out = ob_get_clean();
	//	-------------------------------------------------------------//
                
                if($_SESSION["USR"]->clientes_relacionados == '3324,3511'){
                    
                    header("Pragma: public");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Content-type: atachment/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename=\"$titulo.xls\";");
                    header("Content-transfer-encoding: binary\n");
                    
//                    echo $out;
                    
                    
                }
                
                else{
                
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: atachment/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$titulo.xls\";");
		header("Content-transfer-encoding: binary\n");
                
                }
               
//----------------------------------------------------------------------//
		echo $out;
	}

die();

?>