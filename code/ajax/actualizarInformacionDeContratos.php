<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
$accion = $_GET['accion'];

switch ($accion) {
    case 1:	//Sustituir numero de contratos
		$id_control_contrato = $_GET['id_control_contrato'];
		$contrato_sustitulo = $_GET['contrato'];
		
		$sql = "SELECT contrato, contrato_original_1, contrato_original_2, contrato_original_3";
		$sql .= " FROM cl_control_contratos";
		$sql .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$sql .= " AND activo = 1;";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		foreach($result as $registros){	
			$contrato_original = $registros->contrato;
			$contrato_original_1 = $registros->contrato_original_1;
			$contrato_original_2 = $registros->contrato_original_2;
			$contrato_original_3 = $registros->contrato_original_3;
		}
		if($contrato_original_1==''){
			$update = "UPDATE cl_control_contratos SET contrato = '".strtoupper($contrato_sustitulo)."', contrato_original_1 = '".strtoupper($contrato_original)."' WHERE id_control_contrato = '".$id_control_contrato."' AND activo = 1;";
			mysql_query($update) or die("Error: " . mysql_error());
			$respuesta = 'SE HA REALIZADO LA PRIMERA SUSTITUCION NUMEROS DE CONTRATO<BR /><BR />['.strtoupper($contrato_original).'] POR ['.strtoupper($contrato_sustitulo).']<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		}elseif($contrato_original_2==''){
			$update = "UPDATE cl_control_contratos SET contrato = '".strtoupper($contrato_sustitulo)."', contrato_original_2 = '".strtoupper($contrato_original)."' WHERE id_control_contrato = '".$id_control_contrato."' AND activo = 1;";
			mysql_query($update) or die("Error: " . mysql_error());
			$respuesta = 'SE HA REALIZADO LA SEGUNDA SUSTITUCION DE NUMERO DE CONTRATO<BR /><BR />['.strtoupper($contrato_original).'] POR ['.strtoupper($contrato_sustitulo).']<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		}elseif($contrato_original_3==''){
			$update = "UPDATE cl_control_contratos SET contrato = '".strtoupper($contrato_sustitulo)."', contrato_original_3 = '".strtoupper($contrato_original)."' WHERE id_control_contrato = '".$id_control_contrato."' AND activo = 1;";
			mysql_query($update) or die("Error: " . mysql_error());
			$respuesta = 'SE HA REALIZADO LA TERCERA SUSTITUCION DE NUMERO DE CONTRATO<BR /><BR />['.strtoupper($contrato_original).'] POR ['.strtoupper($contrato_sustitulo).']<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		}else{
			$respuesta = '&iexcl;NO SE REALIZ&Oacute; LA MODIFICACI&Oacute;N!<br /><br />YA APLICARON TRES SUSTITUCIONES ANTERIORMENTE<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		}
		//echo json_encode($respuesta);
		$smarty -> assign("respuesta", $respuesta);
		$smarty -> assign("sql", $sql);
		$smarty -> assign("update", $update);
		echo json_encode($smarty->fetch('especiales/respuestaActualizarInformacionContratos.tpl'));
	break;
    case 2:	//Eliminar el detalle del contrato
		$id_control_contrato = $_GET['id_control_contrato'];
		$id_control_contrato_detalle = $_GET['id_control_contrato_detalle'];

		$update = "UPDATE cl_control_contratos_detalles SET activo = '0'";
		$update .= " WHERE id_detalle = '".$id_control_contrato_detalle."';";
		mysql_query($update) or die("Error: " . mysql_error());

		$respuesta = 'SE HA ELIMINADO LA INFORMACI&Oacute;N CORRECTAMENTE<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		$smarty -> assign("respuesta", $respuesta);
		echo json_encode($smarty->fetch('especiales/respuestaActualizarInformacionContratos.tpl'));
    break;
    case 3: //Modificar fecha del movimiento
		$fecha = array();
		$id_control_contrato = $_GET['id_control_contrato'];
		$id_control_contrato_detalle = $_GET['id_control_contrato_detalle'];
		$fecha_movimiento = $_GET['campo_modificar'];
		$fecha  = explode("/", $fecha_movimiento);
		$fecha_movimiento = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
		//Se inserta la misma informacion del registro del ultimo movimiento -->		
		$insert = "INSERT INTO cl_control_contratos_detalles (";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento)";
		$insert .= " SELECT";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento";
		$insert .= " FROM cl_control_contratos_detalles";
		$insert .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($insert) or die("Error: " . mysql_error());
		$ultimo_insertado = mysql_insert_id();
		//Se inserta la misma informacion del registro del ultimo movimiento <--
		//Se actualiza el registro en el campo de ultimo movimiento a cero -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET ultimo_movimiento = '0'";
		$update .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de ultimo movimiento a cero <--
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET fecha_movimiento = '".$fecha_movimiento."', ultimo_movimiento = '1'";
		$update .= " WHERE id_detalle = '".$ultimo_insertado."'";
		$update .= " AND id_control_contrato = '".$id_control_contrato."'";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato <--
		$respuesta = 'LA FECHA SE HA MODIFICADO CORRECTAMENTE<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		$smarty -> assign("respuesta", $respuesta);
		echo json_encode($smarty->fetch('especiales/respuestaActualizarInformacionContratos.tpl'));
    break;
    case 4: //Modificar el precio de suscripción
		$id_control_contrato = $_GET['id_control_contrato'];
		$id_control_contrato_detalle = $_GET['id_control_contrato_detalle'];
		$precio_suscripcion = $_GET['campo_modificar'];
		
		//Se inserta la misma informacion del registro del ultimo movimiento -->		
		$insert = "INSERT INTO cl_control_contratos_detalles (";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento)";
		$insert .= " SELECT";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento";
		$insert .= " FROM cl_control_contratos_detalles";
		$insert .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($insert) or die("Error: " . mysql_error());
		$ultimo_insertado = mysql_insert_id();
		//Se inserta la misma informacion del registro del ultimo movimiento <--
		//Se actualiza el registro en el campo de ultimo movimiento a cero -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET ultimo_movimiento = '0'";
		$update .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de ultimo movimiento a cero <--
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET precio_suscripcion = '".$precio_suscripcion."', ultimo_movimiento = '1'";
		$update .= " WHERE id_detalle = '".$ultimo_insertado."'";
		$update .= " AND id_control_contrato = '".$id_control_contrato."'";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato <--
		$respuesta = 'EL PRECIO DE SUSCRIPCI&Oacute;N SE HA MODIFICADO CORRECTAMENTE<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		$smarty -> assign("respuesta", $respuesta);
		echo json_encode($smarty->fetch('especiales/respuestaActualizarInformacionContratos.tpl'));
	break;
	
    case 5: //Modifica NIT
		$id_control_contrato = $_GET['id_control_contrato'];
		$id_control_contrato_detalle = $_GET['id_control_contrato_detalle'];
		$id_nit = $_GET['campo_modificar'];
		
		$query='SELECT hijo.id_cliente, hijo.id_cliente_padre, padre.id_sucursal as sucursalPadre
				FROM ad_clientes as hijo
				LEFT JOIN ad_clientes as padre
				on hijo.id_cliente_padre = padre.id_cliente
				WHERE hijo.id_cliente="'.$id_nit.'"';
		$result=mysql_query($query);
		$datos=mysql_fetch_array($result);
		
		//Se inserta la misma informacion del registro del ultimo movimiento -->		
		$insert = "INSERT INTO cl_control_contratos_detalles (";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento)";
		$insert .= " SELECT";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento";
		$insert .= " FROM cl_control_contratos_detalles";
		$insert .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($insert) or die("Error: " . mysql_error());
		$ultimo_insertado = mysql_insert_id();
		//Se inserta la misma informacion del registro del ultimo movimiento <--
		//Se actualiza el registro en el campo de ultimo movimiento a cero -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET ultimo_movimiento = '0'";
		$update .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de ultimo movimiento a cero <--
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET id_cliente_tecnico = '".$id_nit."', id_sucursal = '".$datos["sucursalPadre"]."', id_cliente = '".$datos["id_cliente_padre"]."', ultimo_movimiento = '1'";
		$update .= " WHERE id_detalle = '".$ultimo_insertado."'";
		$update .= " AND id_control_contrato = '".$id_control_contrato."'";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato <--
		$respuesta = 'EL NIT SE HA MODIFICADO CORRECTAMENTE<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		$smarty -> assign("respuesta", $respuesta);
		echo json_encode($smarty->fetch('especiales/respuestaActualizarInformacionContratos.tpl'));
	break;
    case 6: //Modifica Promocion
		$id_control_contrato = $_GET['id_control_contrato'];
		$id_control_contrato_detalle = $_GET['id_control_contrato_detalle'];
		$id_paquete_sky = $_GET['campo_modificar'];

		//Se inserta la misma informacion del registro del ultimo movimiento -->		
		$insert = "INSERT INTO cl_control_contratos_detalles (";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento)";
		$insert .= " SELECT";
		$insert .= " id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_usuario_modifico,fecha_modificacion,id_usuario_cancelo,fecha_cancelacion,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,activo,ultimo_movimiento";
		$insert .= " FROM cl_control_contratos_detalles";
		$insert .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($insert) or die("Error: " . mysql_error());
		$ultimo_insertado = mysql_insert_id();
		//Se inserta la misma informacion del registro del ultimo movimiento <--
		//Se actualiza el registro en el campo de ultimo movimiento a cero -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET ultimo_movimiento = '0'";
		$update .= " WHERE id_control_contrato = '".$id_control_contrato."'";
		$insert .= " AND ultimo_movimiento = '1';";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de ultimo movimiento a cero <--
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato -->
		$update = "UPDATE cl_control_contratos_detalles";
		$update .= " SET id_paquete_sky = '".$id_paquete_sky."', ultimo_movimiento = '1'";
		$update .= " WHERE id_detalle = '".$ultimo_insertado."'";
		$update .= " AND id_control_contrato = '".$id_control_contrato."'";
		mysql_query($update) or die("Error: " . mysql_error());
		//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato <--
		$respuesta = 'LA PROMOCI&Oacute;N SE HA MODIFICADO CORRECTAMENTE<br/><br/><p align="center"><img src="../../imagenes/general/wait.gif" width="30"/></p>';
		$smarty -> assign("respuesta", $respuesta);
		echo json_encode($smarty->fetch('especiales/respuestaActualizarInformacionContratos.tpl'));
	break;
}
?>