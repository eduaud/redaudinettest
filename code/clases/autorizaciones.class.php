<?php
	$ruta = "../../";
	include_once($ruta . "conect.php");
	include_once($ruta . "config.inc.php");
	include_once($ruta . "code/general/funciones.php");
	include_once("disponibilidad.class.php");
	
	extract($_GET);
	extract($_POST);	
	
	class ItemAutorizaciones { 
		//DATOS SOLICITADOS Y A ENTRGAR
		protected $id_control_pedido, $error, $aplicarTransaccion; 

		public function __construct($id_control_pedido, $aplicarTransaccion) { 
			$this->aplicarTransaccion = $aplicarTransaccion;
			
			//VALIDAR SOLICITUD DE COTIZACION
			$sqlValSolCot = "
				SELECT COUNT(*) total
				FROM rac_pedidos
				WHERE id_control_pedido = '" . mysql_real_escape_string($id_control_pedido) . "' 
				AND estatus_pedido IN (1,2,8,9)
				/*AND no_modificable = 1;*/
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
			if($this->aplicarTransaccion == '1')
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
			if($this->aplicarTransaccion == '1')
				mysql_query("COMMIT;");
		}
		
		public function __toString() { 
			return $this->respuesta;
		}
			
		public function cancelarSolicCot($tipoCancelacion)
		{
			if($this->error == 0 && ($tipoCancelacion == 4 || $tipoCancelacion == 5 || $tipoCancelacion == 11))
			{
				//ACTUALIZAMOS LA SOLICITUD DE COTIZACION A CANCELADO ACORDE A QUIEN LO SOLICITE
				$sqlUpdEst = "
					UPDATE rac_pedidos
					SET estatus_pedido = '$tipoCancelacion'
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlUpdEst) or die("Error en $sqlUpdEst:: " . mysql_error());
				
				//ACTUALIZAMOS FLETES
				$sqlUpdEst = "
					UPDATE rac_fletes
					SET activo = 0
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlUpdEst) or die("Error en $sqlUpdEst:: " . mysql_error());
				
				//ACTUALIZAMOS VIATICOS
				$sqlUpdEst = "
					UPDATE rac_viaticos
					SET activo = 0
					WHERE id_control_pedido = '" . $this->id_control_pedido . "'
				";
				mysql_query($sqlUpdEst) or die("Error en $sqlUpdEst:: " . mysql_error());
				
				//ACTUALIZAR LA DISPONIBILIDAD, SOLO SE TOMAN LAS FECHAS DE USUARIO PORQUE SI NO HAY CAMBIOS AMBAS SON IGUALES Y DE HABER DIFERENCIAS SE LE DA PRIORIDAD A LA INDICADA POR EL USUARIO
				$sqlUpdDisp = "
					SELECT 3 tipoLocalizacion, id_disponibilidad_sustento, id_fuente_ligada, id_fuente, id_detalle_articulo_fuente, id_grid, id_articulo, 
					DATE_FORMAT(fecha_inicio_usuario, '%Y/%m/%d') fecha_inicio_usuario, id_hora_inicio_usuario,
					DATE_FORMAT(fecha_fin_usuario, '%Y/%m/%d') fecha_fin_usuario, id_hora_fin_usuario,
					cantidadAdisponible, cantidadAreservado
					FROM rac_disponibilidad_sustento
					WHERE id_fuente_ligada = 1 
					AND id_grid IN (8,9,10)
					AND id_fuente = '" . $this->id_control_pedido . "'
					AND activo = 1
				";
				$respUpdDisp = mysql_query($sqlUpdDisp) or die("Error at rowsel $sqlSalvaDisp::".mysql_error());
				while($rowUpdDisp = mysql_fetch_assoc($respUpdDisp))
				{
					$disponibleArticulo = new ItemDisponibilidad($rowUpdDisp['tipoLocalizacion'], $rowUpdDisp['id_articulo'], $rowUpdDisp['fecha_inicio_usuario'], $rowUpdDisp['id_hora_inicio_usuario'], $rowUpdDisp['fecha_fin_usuario'], $rowUpdDisp['id_hora_fin_usuario']);
					$disponibleArticulo->actualizarDisponibilidadSustento($rowUpdDisp['id_fuente_ligada'], $rowUpdDisp['id_fuente'], $rowUpdDisp['id_detalle_articulo_fuente'], $rowUpdDisp['id_grid'], ($rowUpdDisp['cantidadAdisponible'] * -1), ($rowUpdDisp['cantidadAreservado'] * -1));
					
					//DESHABILITAMOS EL REGISTRO PADRE PARA EVITAR SE GENERE DOS VECES
					$sqlUpdDispSustLinea = "
						UPDATE rac_disponibilidad_sustento
						SET activo = 0
						WHERE id_disponibilidad_sustento = '" . $rowUpdDisp['id_disponibilidad_sustento'] . "'
					";
					mysql_query($sqlUpdDispSustLinea) or die("Error en $sqlUpdDispSustLinea:: " . mysql_error());
				}
				
				//FALTA CANCELAR ARTICULOS LIGADOS A COMPRAS
				//FALTA CANCELAR ARTICULOS LIGADOS A PRODUCCION
				//FALTA CANCELAR TAREAS LIGADAS A TRANSFORMACIONES
				
				//FALTA ENVIAR EMAIL NOTIFICACION
				$strDatos = "
					SELECT rp.id_pedido, rp.fecha_hora_creacion, rc.nombre_comercial, rc.nombre, rp.total, rc.email
					FROM rac_pedidos rp
					LEFT JOIN rac_clientes rc ON rp.id_cliente = rc.id_cliente 
					WHERE rp.id_control_pedido = '" . $this->id_control_pedido . "' /*AND no_modificable = 1*/
				";		
				$resDatos = mysql_query($strDatos) or die("Error en \n$strSQL\n\nDescripcion:\n".mysql_error("error"));
				$rowDatosEnvia = mysql_fetch_assoc($resDatos);
				
				mailPHP(
					utf8_decode("R&C Solicitud Cancelada"), 
					mailLayout($rowDatosEnvia['id_pedido'], $rowDatosEnvia['fecha_hora_creacion'], $rowDatosEnvia['nombre_comercial'], $rowDatosEnvia['nombre'], $rowDatosEnvia['total'], 'la confirmación de cancelación'), 
					$rowDatosEnvia['email'], /*destinatario*/
					"",	/*cc*/
					""	/*adjunto*/
				);
			}			
		}
		
	} 
	
	
	
	
	//inciializar con esta linea
	if(!isset($id_pedidoReplica))
	{
		$autorizacion = new ItemAutorizaciones($id_pedido, '1');
		$autorizacion->cancelarSolicCot($id_pedido_estatus);	
		echo $autorizacion->getError();	
	}
	
	unset($disponibleArticulo);
	
	
?>