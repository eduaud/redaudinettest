<?php
	$ruta = "../../";
	include_once($ruta . "conect.php");
	include_once($ruta . "config.inc.php");
	include_once($ruta . "code/general/funciones.php");
	include_once("autorizaciones.class.php");
	
	extract($_GET);
	extract($_POST);	
	
	class ItemReplicaSolic { 
		//DATOS SOLICITADOS Y A ENTRGAR
		protected $id_control_pedido, $error; 

		public function __construct($id_control_pedido) { 
			//VALIDAR SOLICITUD DE COTIZACION
			$sqlValSolCot = "
				SELECT COUNT(*) total
				FROM rac_pedidos
				WHERE id_control_pedido = '" . mysql_real_escape_string($id_control_pedido) . "' 
				AND estatus_pedido IN (1,2,8,9)
				AND no_modificable = 1;
			";
			$respValSolCot = mysql_query($sqlValSolCot) or die("Error at rowsel $sqlValSolCot::".mysql_error());
			$rowValSolCot = mysql_fetch_row($respValSolCot);
			
			if($rowValSolCot[0] == 1)
			{
				$this->id_control_pedido = mysql_real_escape_string($id_control_pedido); 
				$this->error = 0;
			}
			else
			{
				$this->id_control_pedido = 0; 
				$this->error = 1;
			}
			//ABRIR CONEXION
			//mysql_query("LOCK TABLES mytest WRITE;");			
			mysql_query("BEGIN;");
		}
		
		public function getError()
		{
			return $this->error;
		}
		
		function __destruct()
		{
			//CERRAR CONEXION
			//mysql_query("UNLOCK TABLES;");
			//mysql_query("ROLLBACK;");
			mysql_query("COMMIT;");
		}
		
		public function __toString() { 
			return $this->respuesta;
		}
			
		public function replicarCotizacion()
		{
			if($this->error == 0)
			{
				//DATOS GENERALES DEL PEDIDO
				$sqlDatosPed = "
					SELECT id_tipo_evento_localizacion, 
					DATE_FORMAT(fecha_entrega_articulos, '%Y/%m/%d') fecha_entrega_articulos, 
					hora_entrega, DATE_FORMAT(fecha_recoleccion, '%Y/%m/%d') fecha_recoleccion, 
					hora_recoleccion2
					FROM rac_pedidos
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				$resDatosPed = mysql_query($sqlDatosPed) or die("Error at rowsel $strConsulta::".mysql_error());
				$rowDatosPed = mysql_fetch_assoc($resDatosPed);
				
				//DUPLICAR SOLICITUD DE COTIZACION
				$sqlInsSolCot = "
					INSERT INTO rac_pedidos
					(
					id_pedido,
					id_vendedor,
					id_vendedor_registro_pedido,
					id_tipo_pedido,
					estatus_pedido,
					fecha_hora_creacion,
					id_cliente,
					id_cliente_directo,
					fecha_evento,
					hora_evento,
					dias_evento,
					factor_costo_total_producto,
					numero_personas,
					fecha_entrega_articulos,
					hora_entrega,
					hora_entrega2,
					fecha_recoleccion,
					hora_recoleccion,
					hora_recoleccion2,
					tipo_entrega_cliente,
					direccion_entrega,
					direccion_evento,
					tipo_regreso_almacen,
					direccion_recoleccion,
					persona_recibe,
					persona_entrega,
					id_tipo_evento,
					requiere_presupuesto_flete,
					requiere_presupuesto_motaje,
					requiere_presupuesto_viaticos,
					monto_flete_registrado_logistica,
					monto_flete_gerente_ventas,
					monto_montaje_gerente_ventas,
					monto_montaje_extraorinario_gerente_ventas,
					monto_desmontaje_gerente_ventas,
					monto_viaticos_gerente_ventas,
					id_almacen_recoge,
					monto_pagare,
					id_tipo_descuento,
					porcentaje_descuento_subtotal,
					id_tipo_documento,
					nombre_razon_social,
					rfc,
					calle,
					numero_exterior,
					numero_interior,
					colonia,
					id_ciudad,
					delegacion_municipio,
					codigo_postal,
					observaciones_generales,
					subtotal,
					descuento_tipo_cliente,
					monto_descuento_adicional,
					total,
					mostrar_leyenda_anticipo_pago,
					porcentaje_anticipo,
					no_modificable,
					ariculos_apartados,
					id_almacen_entrega,
					id_estado,
					id_control_pedido_montaje,
					id_tipo_evento_localizacion,
					iva,
					subtotal_otros,
					subtotal_articulos,
					porcentaje_descuento_subtotal_aprobado,
					monto_viaticos_logistica,
					imagen_lugar,
					id_pedido_padre,
					id_pedido_super_padre,
					folio,
					consecutivo_folio,
					porcentaje
					)
					SELECT 
					CONCAT(folio, '_', (consecutivo_folio + 1)) id_pedido,
					id_vendedor,
					id_vendedor_registro_pedido,
					id_tipo_pedido,
					1 estatus_pedido,
					NOW() fecha_hora_creacion,
					id_cliente,
					id_cliente_directo,
					fecha_evento,
					hora_evento,
					dias_evento,
					factor_costo_total_producto,
					numero_personas,
					fecha_entrega_articulos,
					hora_entrega,
					hora_entrega2,
					fecha_recoleccion,
					hora_recoleccion,
					hora_recoleccion2,
					tipo_entrega_cliente,
					direccion_entrega,
					direccion_evento,
					tipo_regreso_almacen,
					direccion_recoleccion,
					persona_recibe,
					persona_entrega,
					id_tipo_evento,
					0 requiere_presupuesto_flete,
					0 requiere_presupuesto_motaje,
					0 requiere_presupuesto_viaticos,
					0 monto_flete_registrado_logistica,
					0 monto_flete_gerente_ventas,
					0 monto_montaje_gerente_ventas,
					0 monto_montaje_extraorinario_gerente_ventas,
					0 monto_desmontaje_gerente_ventas,
					0 monto_viaticos_gerente_ventas,
					id_almacen_recoge,
					0 monto_pagare,
					id_tipo_descuento,
					0 porcentaje_descuento_subtotal,
					id_tipo_documento,
					nombre_razon_social,
					rfc,
					calle,
					numero_exterior,
					numero_interior,
					colonia,
					id_ciudad,
					delegacion_municipio,
					codigo_postal,
					observaciones_generales,
					0 subtotal,
					0 descuento_tipo_cliente,
					0 monto_descuento_adicional,
					0 total,
					mostrar_leyenda_anticipo_pago,
					porcentaje_anticipo,
					0 no_modificable,
					0 ariculos_apartados,
					id_almacen_entrega,
					id_estado,
					id_control_pedido_montaje,
					id_tipo_evento_localizacion,
					0 iva,
					0 subtotal_otros,
					0 subtotal_articulos,
					0 porcentaje_descuento_subtotal_aprobado,
					0 monto_viaticos_logistica,
					imagen_lugar,
					'" . $this->id_control_pedido . "' id_pedido_padre,
					id_pedido_super_padre,
					folio,
					(consecutivo_folio + 1) consecutivo_folio,
					porcentaje
					FROM rac_pedidos				
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlInsSolCot) or die("Error en $sqlInsSolCot:: " . mysql_error());
				$id_insertado = mysql_insert_id();
				
				//DUPLICAR SOLICITUD DE COTIZACION DET ART
				$sqlInsSolCot = "
					INSERT INTO rac_pedidos_detalle_articulos
					(
					id_control_pedido,
					id_articulo,
					cantidad_solicitada,
					cantidad_existencia,
					faltante_surtir,
					cantidad_surtir,
					precio_unitario,
					porcentaje_descuento_cliente,
					porcentaje_descuento_adicional_vendedor,
					porcentaje_descuento_aprobado_direccion,
					factor_costo,
					precio_final,
					importe,
					importe_final,
					observaciones,
					estatus_articulo,
					estatus_direccion,
					estatus_articulo_anterior,
					estatus_direccion_anterior,
					accion1,
					accion2,
					accion3,
					accion4,
					accion5,
					activo,
					precio_solicitado,
					precio_propuesto,
					accion6,
					accion7,
					cant_solic_a_canc
					)
					SELECT 
					'$id_insertado' id_control_pedido,
					id_articulo,
					0 cantidad_solicitada,
					0 cantidad_existencia,
					0 faltante_surtir,
					0 cantidad_surtir,
					0 precio_unitario,
					porcentaje_descuento_cliente,
					0 porcentaje_descuento_adicional_vendedor,
					0 porcentaje_descuento_aprobado_direccion,
					factor_costo,
					0 precio_final,
					0 importe,
					0 importe_final,
					observaciones,
					NULL estatus_articulo,
					NULL estatus_direccion,
					NULL estatus_articulo_anterior,
					NULL estatus_direccion_anterior,
					NULL accion1,
					NULL accion2,
					NULL accion3,
					NULL accion4,
					NULL accion5,
					1 activo,
					0 precio_solicitado,
					0 precio_propuesto,
					NULL accion6,
					NULL accion7,
					0 cant_solic_a_canc
					FROM rac_pedidos_detalle_articulos
					WHERE id_control_pedido	 = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlInsSolCot) or die("Error en $sqlInsSolCot:: " . mysql_error());
				
				$sqlInsSolCot = "
					INSERT INTO rac_pedidos_detalle_articulos_transformacion_especial
					(
					id_control_pedido,
					id_articulo,
					cantidad_solicitada_transformar,
					existencia,
					cantidad_surtir,
					faltante_surtir,
					articulo_final,
					caracteristica_transformacion,
					precio_unitario,
					precio_transformacion,
					porcentaje_descuento_cliente,
					porcentaje_descuento_adicional,
					porcentaje_descuento_aprobado,
					factor_costo,
					precio_final,
					importe,
					importe_final,
					observaciones,
					estatus_articulo,
					estatus_autorizacion_gerente_produccion,
					estatus_autorizacion_direccion_produccion,
					estatus_autorizacion_direccion_administracion,
					estatus_gerente_produccion_anterior,
					estatus_autorizacion_direccion_anterior,
					estatus_autorizacion_administracion_anterior,
					accion1,
					accion2,
					accion3,
					accion4,
					accion5,
					activo,
					precio_solicitado,
					precio_propuesto
					)
					SELECT
					'$id_insertado' id_control_pedido,
					id_articulo,
					0 cantidad_solicitada_transformar,
					0 existencia,
					0 cantidad_surtir,
					0 faltante_surtir,
					articulo_final,
					caracteristica_transformacion,
					0 precio_unitario,
					0 precio_transformacion,
					porcentaje_descuento_cliente,
					0 porcentaje_descuento_adicional,
					0 porcentaje_descuento_aprobado,
					factor_costo,
					0 precio_final,
					0 importe,
					0 importe_final,
					observaciones,
					NULL estatus_articulo,
					NULL estatus_autorizacion_gerente_produccion,
					NULL estatus_autorizacion_direccion_produccion,
					NULL estatus_autorizacion_direccion_administracion,
					NULL estatus_gerente_produccion_anterior,
					NULL estatus_autorizacion_direccion_anterior,
					NULL estatus_autorizacion_administracion_anterior,
					NULL accion1,
					NULL accion2,
					NULL accion3,
					NULL accion4,
					NULL accion5,
					1 activo,
					0 precio_solicitado,
					0 precio_propuesto
					FROM rac_pedidos_detalle_articulos_transformacion_especial
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlInsSolCot) or die("Error en $sqlInsSolCot:: " . mysql_error());
				
				$sqlInsSolCot = "
					INSERT INTO rac_pedidos_detalle_articulos_solicitado_compra
					(
					id_control_pedido, 
					id_articulo,
					cantidad_solicitada,
					precio_unitario,
					porcentaje_descuento_cliente,
					porcentaje_descuento_adicional,
					porcentaje_descuento_aprobado,
					factor_costo,
					precio_final,
					importe,
					importe_final,
					observaciones,
					estatus_articulo,
					estatus_autorizacion_gerente_produccion,
					estatus_autorizacion_direccion_produccion,
					estatus_autorizacion_direccion_administracion,
					estatus_gerente_produccion_anterior,
					estatus_direccion_produccion,
					estatus_direccion_administracion,
					accion1,
					accion2,
					accion3,
					accion4,
					accion5,
					activo,
					precio_solicitado,
					precio_propuesto
					)
					SELECT
					'$id_insertado' id_control_pedido, 
					id_articulo,
					0 cantidad_solicitada,
					0 precio_unitario,
					porcentaje_descuento_cliente,
					0 porcentaje_descuento_adicional,
					0 porcentaje_descuento_aprobado,
					factor_costo,
					0 precio_final,
					0 importe,
					0 importe_final,
					observaciones,
					NULL estatus_articulo,
					NULL estatus_autorizacion_gerente_produccion,
					NULL estatus_autorizacion_direccion_produccion,
					NULL estatus_autorizacion_direccion_administracion,
					NULL estatus_gerente_produccion_anterior,
					NULL estatus_direccion_produccion,
					NULL estatus_direccion_administracion,
					NULL accion1,
					NULL accion2,
					NULL accion3,
					NULL accion4,
					NULL accion5,
					1 activo,
					0 precio_solicitado,
					0 precio_propuesto
					FROM rac_pedidos_detalle_articulos_solicitado_compra
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlInsSolCot) or die("Error en $sqlInsSolCot:: " . mysql_error());
				
				$sqlInsSolCot = "
					INSERT INTO rac_pedidos_detalle_articulos_solicitado_produccion
					(
					id_control_pedido,
					id_articulo,
					cantidad_solicitada,
					precio_unitario,
					precio_produccion,
					porcentaje_descuento_cliente,
					factor_costo,
					precio_final,
					importe,
					importe_final,
					observaciones,
					estatus_articulo,
					estatus_autorizacion_gerente_produccion,
					estatus_autorizacion_direccion_produccion,
					estatus_autorizacion_direccion_administracion,
					porcentaje_descuento_adicional,
					porcentaje_descuento_aprobado,
					estatus_gerente_produccion_anterior,
					estatus_direccion_produccion_anterior,
					estatus_direccion_administracion_anterior,
					accion1,
					accion2,
					accion3,
					accion4,
					accion5,
					activo,
					precio_solicitado,
					precio_propuesto
					)
					SELECT
					'$id_insertado' id_control_pedido,
					id_articulo,
					0 cantidad_solicitada,
					0 precio_unitario,
					0 precio_produccion,
					porcentaje_descuento_cliente,
					factor_costo,
					0 precio_final,
					0 importe,
					0 importe_final,
					observaciones,
					NULL estatus_articulo,
					NULL estatus_autorizacion_gerente_produccion,
					NULL estatus_autorizacion_direccion_produccion,
					NULL estatus_autorizacion_direccion_administracion,
					0 porcentaje_descuento_adicional,
					0 porcentaje_descuento_aprobado,
					NULL estatus_gerente_produccion_anterior,
					NULL estatus_direccion_produccion_anterior,
					NULL estatus_direccion_administracion_anterior,
					NULL accion1,
					NULL accion2,
					NULL accion3,
					NULL accion4,
					NULL accion5,
					1 activo,
					0 precio_solicitado,
					0 precio_propuesto
					FROM rac_pedidos_detalle_articulos_solicitado_produccion
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlInsSolCot) or die("Error en $sqlInsSolCot:: " . mysql_error());
				
				$autorizacion = new ItemAutorizaciones($this->id_control_pedido, '0');
				$autorizacion->cancelarSolicCot(11);	
				
				if($autorizacion->getError() == 0)
				{
					//FALTA ENVIAR EMAIL NOTIFICACION
					$strDatos = "
						SELECT rp.id_pedido, rp.fecha_hora_creacion, rc.nombre_comercial, rc.nombre, rp.total, rc.email
						FROM rac_pedidos rp
						LEFT JOIN rac_clientes rc ON rp.id_cliente = rc.id_cliente 
						WHERE rp.id_control_pedido = '$id_insertado'
					";		
					$resDatos = mysql_query($strDatos) or die("Error en \n$strSQL\n\nDescripcion:\n".mysql_error("error"));
					$rowDatosEnvia = mysql_fetch_assoc($resDatos);
					
					mailPHP(
						utf8_decode("R&C Solicitud Replicada"), 
						mailLayout($rowDatosEnvia['id_pedido'], $rowDatosEnvia['fecha_hora_creacion'], $rowDatosEnvia['nombre_comercial'], $rowDatosEnvia['nombre'], $rowDatosEnvia['total'], 'la confirmación de replicación'), 
						$rowDatosEnvia['email'], /*destinatario*/
						"",	/*cc*/
						""	/*adjunto*/
					);
				}
				else
				{
					$this->error = 1;
				}
			}			
		}
		
	} 
	
	
	
	
	//inciializar con esta linea
	
	$replicar = new ItemReplicaSolic($id_pedidoReplica);
	$replicar->replicarCotizacion();
	echo $replicar->getError();	
	
	unset($disponibleArticulo);
	
?>