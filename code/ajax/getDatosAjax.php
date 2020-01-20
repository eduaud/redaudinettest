<?php
php_track_vars;

extract($_GET);
extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
include("../../conect.php");
include_once("../general/funciones.php");
include_once("../clases/disponibilidad.class.php");

switch($opc){
	case 1://echo "probandojson";
		   	//regresamos el precio y el iva del producto x en el grid de Productos
		   	echo json_encode(ejecutaQueryAssoc(datosProductosBook($_REQUEST))); 
		   	break;
	case 2://regresamos los datos corespondientes a cada proyecto cuando este es nuevo en el grid proyectos
		   	echo json_encode(ejecutaQueryAssoc(datosProyectosGrid($_REQUEST))); 
		   	break;
	case 3://regresamos una cadena indicando los campos necesarios para las formas de cobro
			echo json_encode(ejecutaQueryAssoc(datosFormaCobro($_REQUEST))); 
		   	break;
	case 4://VALIDACION SOBRE LOS CAMPOS NECESARIOS SEGUN TIPO DE PAGO SELECCIONADO
			echo json_encode(ejecutaQueryAssoc(datosFormaCobroCamposReq($_REQUEST))); 
		   	break;
	case 5://VALIDACION MONTO DISPONIBLE EN NOTA DE CAMBIO
			echo json_encode(ejecutaQueryAssoc(datosDisponibleNota($_REQUEST))); 
		   	break;
	case 6://VALIDACION MONTO DISPONIBLE EN NOTA DE CAMBIO
			echo json_encode(ejecutaQueryAssoc(datosDisponibleNotaCifrado($_REQUEST))); 
		   	break;
	case 7://INDICA SI HAY CITAS CON CHECKOUT PENDIENTE
			echo json_encode(ejecutaQueryAssoc(datosIndicaCheckOutPendiente())); 
		   	break;
	case 8://OBTIENE LOS DATOS DE FACTURACION DEL CLIENTE
			echo json_encode(ejecutaQueryAssoc(datosFacturacionCliente($_REQUEST))); 
		   	break;
	case 9://OBTIENE LOS DATOS DE FACTURACION DEL CLIENTE
			echo validaDisponibilidadArticulo($_REQUEST); 
		   	break;
	case 10://OBTIENE CLIENTE DIRECTO DE SOLICITUD DE COTIZACION
			echo json_encode(ejecutaQueryAssoc(obtieneCteDirecto($_REQUEST))); 
		   	break;
	case 11://GENERAR COTIZACION
			generarCotizacion($_REQUEST); 
		   	break;
	case 12://GENERAR COTIZACION
			generarOrdenServicio($_REQUEST); 
		   	break;
	case 13://ACTUALIZA ESTATUS COTIZACION
			actualizaEstatusCotizacion($id, $estatus); 
		   	break;
	case 14://ACTUALIZA ESTATUS COTIZACION
			echo json_encode((obtenerVendAsignado($idCliente)));
		   	break;
	
};
	

function datosProductosBook($datosBusqueda){
   $strSQL = "SELECT
				precio_unitario,
				porcentaje_iva 
			FROM 
				of_productos_servicios
			WHERE activo=1 AND id_pr_serv =".$datosBusqueda["id_prod"];
   return $strSQL;		
}

function datosProyectosGrid($datosProyecto){
	$strSQL = "SELECT
					NULL as id_control_detalle_proyecto,
					a.id_book_proyecto,
					a.id_proyecto,
					a.id_pto_fran,
					b.nombre,
					a.nombre_del_espacio,
					a.titulo_para_impresion,
					a.insertar_en_pagina_numero
				FROM of_detalle_proyecto a LEFT JOIN of_productos_inventario_proyecto b ON a.id_pto_fran=b.id_pr_fran
				WHERE a.id_book_proyecto=".$datosProyecto['id_book']." and id_proyecto=(SELECT MAX(id_proyecto) FROM of_detalle_proyecto WHERE id_book_proyecto=".$datosProyecto['id_book'].")";
	$resSQL=mysql_query($strSQL);
	$num_filas = mysql_num_rows($resSQL);
	if($num_filas<=0){
			$strSQL = "SELECT
					NULL as id_control_detalle_proyecto,
					a.id_book_proyecto,
					a.id_pto_fran,
					b.nombre,
					a.nombre_del_espacio,
					a.titulo_para_impresion,
					a.insertar_en_pagina_numero
				FROM of_config_inventario_proyecto a LEFT JOIN of_productos_inventario_proyecto b ON a.id_pto_fran=b.id_pr_fran
				WHERE a.id_book_proyecto=".$datosProyecto['id_book'];
	}
	return $strSQL;
}

function datosFormaCobro($datosBusqueda){
   $strSQL = "SELECT
				precio_unitario,
				porcentaje_iva 
			FROM 
				of_productos_servicios
			WHERE activo=1 AND id_pr_serv =".$datosBusqueda["id_prod"];
   return $strSQL;		
}

function datosFormaCobroCamposReq($datosBusqueda){
	$strSQL = "
		SELECT requiere_terminal terminal, requiere_no_aprobacion aprobacion FROM spa_formas_cobro WHERE id_forma_cobro = " . $datosBusqueda['idFormaCobro'] . " LIMIT 0,1
	";
   return $strSQL;		
}

function datosDisponibleNota($datosBusqueda){
	$strSQL = "
		SELECT disponible FROM spa_notas_cambio WHERE id_nota_cambio = " . $datosBusqueda['idNota'] . " LIMIT 0,1
	";
   return $strSQL;		
}

function datosDisponibleNotaCifrado($datosBusqueda){
	$strSQL = "
		SELECT id_nota_cambio idx, nota_cambio valor FROM spa_notas_cambio WHERE id_cliente = " . desencX($datosBusqueda['idCliente']) . " LIMIT 0,1
	";
   return $strSQL;		
}

function datosIndicaCheckOutPendiente(){
	$strSQL = "
		SELECT COUNT(sa.id_cita) noCitas FROM spa_agenda sa WHERE sa.id_estatus_cita = 6 AND sa.id_realizara_n1 = " . $_SESSION["USR"]->sucursalid . "
	";
   return $strSQL;		
}

function ejecutaQueryAssoc($strSQL){
	$arrayDatos = array();
	$resSQL=mysql_query($strSQL) or die("Error en:\n$sql\n\nDescripcion: Error en consulta");
	$i = 0;
	while ($dato = mysql_fetch_assoc($resSQL))
	{
		$arrayDatos[$i] = $dato;
		$i++;
	}
	return $arrayDatos;
}

function datosFacturacionCliente($datosBusqueda){
   $strSQL = "SELECT sc.rfc, sc.calle_f, sc.numero_ext_f, sc.numero_int_f, sc.colonia_f, sc.id_ciudad_f, sc.del_mpo_f, sc.cp_f FROM spa_clientes sc WHERE id_cliente = ".$datosBusqueda["id_cliente"];
   return $strSQL;		
}

function validaDisponibilidadArticulo($peticion)
{
	extract($peticion);
	include_once("../clases/disponibilidad.class.php");
	
	$cantConsiderar = ($cantSurtir > $idArtExist)? $idArtExist : $cantSurtir;
	
	$disponibleArticulo = new ItemDisponibilidad($tipo_evento, $idArticulo, convertDate($fecha_entrega_articulos), $hora_entrega, convertDate($fecha_recoleccion), $hora_recoleccion2);
	$valDisponible = $disponibleArticulo->validarArticuloDisponibilidad();	
	$esValido = ($valDisponible - $cantConsiderar < 0)? 0 : 1;
	return $esValido;
}

function obtieneCteDirecto($datosBusqueda){
	$strSQL = "
		SELECT id_cliente_directo, persona_recibe, id_almacen_recoge, direccion_entrega, direccion_evento, persona_entrega, direccion_recoleccion
		FROM rac_pedidos 
		WHERE id_control_pedido = ".$datosBusqueda["id_cot"];
	return $strSQL;		
}

function generarCotizacion($_REQUEST)
{
	$id_control_pedido = (is_numeric($_REQUEST['id_control_pedido']) && $_REQUEST['id_control_pedido'] > 0)? $_REQUEST['id_control_pedido'] : 0;
	
	if($id_control_pedido > 0)
	{
		mysql_query("BEGIN;");
		
		$sqlFolio = "
			SELECT IF(ISNULL(MAX(folio)), 1, MAX(folio+1)) folio
			FROM rac_cotizaciones
		";
		$resFolio = mysql_query($sqlFolio) or die("Error en:\n$sql\n\nDescripcion: Error en consulta");
		$dato = mysql_fetch_row($resFolio);
		$folio = $dato[0];
		$id_cotizacion = $folio . "_1";
			
		//FALTA VALIDACION QUE CONFIRME QUE SE PUEDE GENERAR COTIZACION DESDE PEDIDOS
		$sqlCotizacion = "
			INSERT INTO rac_cotizaciones
			(
			id_cotizacion,
			id_control_pedido,
			id_pedido,
			id_control_pedido_montaje,
			id_vendedor,
			id_vendedor_registro_pedido,
			id_tipo_pedido,
			estatus_cotizacion,
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
			mostrar_leyenda_danios,
			porcentaje_anticipo,
			aplica_bonificacion_cliente,
			razones_rechazo_cliente,
			activo,
			id_almacen_entrega,
			id_estado,
			id_control_orden_servicio,
			id_orden_servicio,
			id_tipo_evento_localizacion,
			iva,
			subtotal_otros,
			subtotal_articulos,
			subtotal_danios,
			monto_viaticos_logistica,
			imagen_lugar,
			folio,
			consecutivo_folio
			)
			SELECT  
			'$id_cotizacion',
			id_control_pedido,
			id_pedido,
			'$id_control_pedido_montaje',
			id_vendedor,
			id_vendedor_registro_pedido,
			id_tipo_pedido,
			'1',
			NOW(),
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
			'0', 
			porcentaje_anticipo,
			'0', 
			'', 
			1,
			id_almacen_entrega,
			id_estado,
			NULL, 
			'', 
			id_tipo_evento_localizacion,
			iva,
			subtotal_otros,
			subtotal_articulos,
			'0',
			monto_viaticos_logistica,
			imagen_lugar,
			'$folio',
			1
			FROM rac_pedidos 
			WHERE id_control_pedido = '$id_control_pedido';
		";
		mysql_query($sqlCotizacion) or die("Error en rac_cotizaciones");
		$id_control_cotizacion = mysql_insert_id();
		
		
		$sqlCotizacionArt = "
			INSERT INTO rac_cotizaciones_detalle_articulos
			(
			id_control_cotizacion,
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
			precio_propuesto
			)
			SELECT 
			'$id_control_cotizacion', 
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
			precio_propuesto
			FROM rac_pedidos_detalle_articulos
			WHERE id_control_pedido = '$id_control_pedido';
		";
		mysql_query($sqlCotizacionArt) or die("Error en rac_cotizaciones_detalle_articulos");
		
		
		$sqlCotizacionArtBas = "
			INSERT INTO rac_cotizaciones_detalle_articulos_transformacion_basica
			(
			id_control_cotizacion,
			id_articulo,
			cantidad_solicitada_transformar,
			existencia,
			faltante_surtir,
			cantidad_surtir,
			id_articulo_final,
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
			estatus_autorizacion_gerente_anterior,
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
			'$id_control_cotizacion', 
			id_articulo,
			cantidad_solicitada_transformar,
			existencia,
			faltante_surtir,
			cantidad_surtir,
			id_articulo_final,
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
			estatus_autorizacion_gerente_anterior,
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
			FROM rac_pedidos_detalle_articulos_transformacion_basica
			WHERE id_control_pedido = '$id_control_pedido';
		";
		mysql_query($sqlCotizacionArtBas) or die("Error en rac_cotizaciones_detalle_articulos_transformacion_basica");
		
		
		$sqlCotizacionArtEsp = "
			INSERT INTO rac_cotizaciones_detalle_articulos_transformacion_especial
			(
			id_control_cotizacion,
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
			'$id_control_cotizacion',
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
			FROM rac_pedidos_detalle_articulos_transformacion_especial
			WHERE id_control_pedido = '$id_control_pedido';
		";
		mysql_query($sqlCotizacionArtEsp) or die("Error en rac_cotizaciones_detalle_articulos_transformacion_especial");
		
		
		$sqlCotizacionArtProd = "
			INSERT INTO rac_cotizaciones_detalle_articulos_solicitado_produccion
			(
			id_control_cotizacion,
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
			'$id_control_cotizacion',
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
			FROM rac_pedidos_detalle_articulos_solicitado_produccion
			WHERE id_control_pedido = '$id_control_pedido';
		";
		mysql_query($sqlCotizacionArtProd) or die("Error en rac_cotizaciones_detalle_articulos_solicitado_produccion");
		
		
		$sqlCotizacionArtComp = "
			INSERT INTO rac_cotizaciones_detalle_articulos_solicitado_compra
			(
			id_control_cotizacion,
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
			'$id_control_cotizacion',
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
			FROM rac_pedidos_detalle_articulos_solicitado_compra
			WHERE id_control_pedido = '$id_control_pedido';
		";
		mysql_query($sqlCotizacionArtComp) or die("Error en rac_cotizaciones_detalle_articulos_solicitado_compra");
		
		$sqlUpdPed = "
			UPDATE rac_pedidos SET estatus_pedido = 3 WHERE id_control_pedido = '$id_control_pedido'
		";
		mysql_query($sqlUpdPed) or die("Error en rac_pedidos");
		
		
		//ACTUALIZAR LA DISPONIBILIDAD, SOLO SE TOMAN LAS FECHAS DE USUARIO PORQUE SI NO HAY CAMBIOS AMBAS SON IGUALES Y DE HABER DIFERENCIAS SE LE DA PRIORIDAD A LA INDICADA POR EL USUARIO
		//CANCELAR LOS MOVIMIENTOS DE DISPONIBILIDAD DERIVADOS DE LA SOLICITUD DE COTIZACION
		$sqlUpdDisp = "
			SELECT 3 tipoLocalizacion, id_disponibilidad_sustento, id_fuente_ligada, id_fuente, id_detalle_articulo_fuente, id_grid, id_articulo, 
			DATE_FORMAT(fecha_inicio_usuario, '%Y/%m/%d') fecha_inicio_usuario, id_hora_inicio_usuario,
			DATE_FORMAT(fecha_fin_usuario, '%Y/%m/%d') fecha_fin_usuario, id_hora_fin_usuario,
			cantidadAdisponible, cantidadAreservado
			FROM rac_disponibilidad_sustento
			WHERE id_fuente_ligada = 1 
			AND id_grid IN (8,9,10)
			AND id_fuente = '$id_control_pedido'
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
		
		
		//ACTUALIZAR LA DISPONIBILIDAD, SOLO SE TOMAN LAS FECHAS DE USUARIO PORQUE SI NO HAY CAMBIOS AMBAS SON IGUALES Y DE HABER DIFERENCIAS SE LE DA PRIORIDAD A LA INDICADA POR EL USUARIO
		//GENERAR LOS MOVIMIENTOS DE DISPONIBILIDAD DERIVADOS DE LA COTIZACION
		$sqlUpdDispCot = "
			SELECT x.*
			FROM
			(
			SELECT a.id_tipo_evento_localizacion tipoLocalizacion, 0 id_disponibilidad_sustento, 2 id_fuente_ligada, a.id_control_cotizacion id_fuente,
			b.id_detalle id_detalle_articulo_fuente, 105 id_grid, b.id_articulo, 
			DATE_FORMAT(a.fecha_entrega_articulos, '%Y-%m-%d') fecha_inicio_usuario, hora_entrega id_hora_inicio_usuario,
			DATE_FORMAT(a.fecha_recoleccion, '%Y-%m-%d') fecha_fin_usuario, hora_recoleccion2 id_hora_fin_usuario,
			b.cantidad_surtir cantidadAdisponible, b.cantidad_surtir cantidadAreservado
			FROM rac_cotizaciones a
			LEFT JOIN rac_cotizaciones_detalle_articulos b ON a.id_control_cotizacion = b.id_control_cotizacion
			WHERE a.id_control_cotizacion = '$id_control_cotizacion'
			UNION
			SELECT a.id_tipo_evento_localizacion tipoLocalizacion, 0 id_disponibilidad_sustento, 2 id_fuente_ligada, a.id_control_cotizacion id_fuente,
			b.id_detalle id_detalle_articulo_fuente, 106 id_grid, b.id_articulo, 
			DATE_FORMAT(a.fecha_entrega_articulos, '%Y/%m/%d') fecha_inicio_usuario, hora_entrega id_hora_inicio_usuario,
			DATE_FORMAT(a.fecha_recoleccion, '%Y/%m/%d') fecha_fin_usuario, hora_recoleccion2 id_hora_fin_usuario,
			b.cantidad_surtir cantidadAdisponible, b.cantidad_surtir cantidadAreservado
			FROM rac_cotizaciones a
			LEFT JOIN rac_cotizaciones_detalle_articulos_transformacion_basica b ON a.id_control_cotizacion = b.id_control_cotizacion
			WHERE a.id_control_cotizacion = '$id_control_cotizacion'
			UNION
			SELECT a.id_tipo_evento_localizacion tipoLocalizacion, 0 id_disponibilidad_sustento, 2 id_fuente_ligada, a.id_control_cotizacion id_fuente,
			b.id_detalle id_detalle_articulo_fuente, 107 id_grid, b.id_articulo, 
			DATE_FORMAT(a.fecha_entrega_articulos, '%Y/%m/%d') fecha_inicio_usuario, hora_entrega id_hora_inicio_usuario,
			DATE_FORMAT(a.fecha_recoleccion, '%Y/%m/%d') fecha_fin_usuario, hora_recoleccion2 id_hora_fin_usuario,
			b.cantidad_surtir cantidadAdisponible, b.cantidad_surtir cantidadAreservado
			FROM rac_cotizaciones a
			LEFT JOIN rac_cotizaciones_detalle_articulos_transformacion_especial b ON a.id_control_cotizacion = b.id_control_cotizacion
			WHERE a.id_control_cotizacion = '$id_control_cotizacion'
			) x
			HAVING x.id_detalle_articulo_fuente IS NOT NULL
		";
		$respUpdDispCot = mysql_query($sqlUpdDispCot) or die("Error at rowsel $sqlSalvaDisp::".mysql_error());
		while($rowUpdDispCot = mysql_fetch_assoc($respUpdDispCot))
		{
			$disponibleArticulo2 = new ItemDisponibilidad($rowUpdDispCot['tipoLocalizacion'], $rowUpdDispCot['id_articulo'], $rowUpdDispCot['fecha_inicio_usuario'], $rowUpdDispCot['id_hora_inicio_usuario'], $rowUpdDispCot['fecha_fin_usuario'], $rowUpdDispCot['id_hora_fin_usuario']);
			$disponibleArticulo2->actualizarDisponibilidadSustento($rowUpdDispCot['id_fuente_ligada'], $rowUpdDispCot['id_fuente'], $rowUpdDispCot['id_detalle_articulo_fuente'], $rowUpdDispCot['id_grid'], ($rowUpdDispCot['cantidadAdisponible'] * 1), ($rowUpdDispCot['cantidadAreservado'] * 1));
		}
		
		mysql_query("COMMIT;");
		die("1");
	}
	else
	{
		die("ID de Solicitud de Cotización no valido");
	}
}

//------------------------------------------------
function generarOrdenServicio($_REQUEST)
{
	$id_control_cotizacion = (is_numeric($_REQUEST['id_control_cotizacion']) && $_REQUEST['id_control_cotizacion'] > 0)? $_REQUEST['id_control_cotizacion'] : 0;
	
	if($id_control_cotizacion > 0)
	{
		mysql_query("BEGIN;");
		
		$sqlFolio = "
			SELECT IF(ISNULL(MAX(folio)), 1, MAX(folio+1)) folio
			FROM rac_ordenes_servicio
		";
		$resFolio = mysql_query($sqlFolio) or die("Error en:\n$sql\n\nDescripcion: Error en consulta");
		$dato = mysql_fetch_row($resFolio);
		$folio = $dato[0];
		$id_control_orden_servicio = $folio . "_1";
			
		//FALTA VALIDACION QUE CONFIRME QUE SE PUEDE GENERAR COTIZACION DESDE PEDIDOS
		$sqlCotizacion = "
			INSERT INTO rac_ordenes_servicio 
					SELECT null,
					'".$id_control_orden_servicio."',
					id_control_pedido,
					id_control_cotizacion,
					id_cotizacion,
					id_vendedor,
					id_vendedor_registro_pedido,
					id_tipo_pedido,
					1,
					NOW(),
					id_cliente,
					id_cliente_directo,
					fecha_evento,
					hora_evento,
					dias_evento,
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
					id_almacen_recoge,
					observaciones_generales,
					id_almacen_entrega,
					id_tipo_evento_localizacion,
					'".$folio."'
					,1
					 FROM rac_cotizaciones WHERE id_control_cotizacion = '$id_control_cotizacion';
		";
		mysql_query($sqlCotizacion) or die("Error en rac_cotizaciones".mysql_error());
		$id_control_orden_servicio = mysql_insert_id();
		
		
		$sqlCotizacionArt = "INSERT INTO rac_ordenes_servicio_detalle_articulos
							(
							id_control_orden_servicio,
							id_articulo,
							cantidad_solicitada
							)
							SELECT '$id_control_orden_servicio', a.id_articulo, a.cantidad_surtir
							FROM rac_cotizaciones_detalle_articulos a
							WHERE id_control_cotizacion='".$id_control_cotizacion."'
							UNION
							SELECT '$id_control_orden_servicio', b.id_articulo, b.cantidad_surtir
							FROM rac_cotizaciones_detalle_articulos_transformacion_basica b
							WHERE b.id_control_cotizacion='".$id_control_cotizacion."'
							UNION
							SELECT '$id_control_orden_servicio', c.id_articulo, c.cantidad_surtir
							FROM rac_cotizaciones_detalle_articulos_transformacion_especial c
							WHERE c.id_control_cotizacion='".$id_control_cotizacion."'
							UNION
							SELECT '$id_control_orden_servicio', d.id_articulo, d.cantidad_solicitada
							FROM rac_cotizaciones_detalle_articulos_solicitado_compra d
							WHERE d.id_control_cotizacion='".$id_control_cotizacion."'
							UNION
							SELECT '$id_control_orden_servicio', e.id_articulo, e.cantidad_solicitada
							FROM rac_cotizaciones_detalle_articulos_solicitado_produccion e
							WHERE e.id_control_cotizacion= '".$id_control_cotizacion."';
		";
		mysql_query($sqlCotizacionArt) or die("Error en rac_cotizaciones_detalle_articulos");
		
		
		$sqlUpdPed = "
			UPDATE rac_cotizaciones SET estatus_cotizacion = 7 WHERE id_control_cotizacion = '$id_control_cotizacion'
		";
		mysql_query($sqlUpdPed) or die("Error en rac_cotizaciones");
		
		mysql_query("COMMIT;");
		die("1");
	}
	else
	{
		die("ID de Cotización no valido");
	}
}

function actualizaEstatusCotizacion($id, $estatus)
{
	$sqlUpd = "
		UPDATE rac_cotizaciones 
		SET estatus_cotizacion = '$estatus'
		WHERE id_control_cotizacion = '$id'
	";
	mysql_query($sqlUpd) or die("Error en actualizar estatus");
}

	function obtenerVendAsignado($idCliente)
	{
		$sqlCons = "
			SELECT a.id_vendedor_asignado, CONCAT(b.nombre,' ',b.apellido_paterno, ' ', b.apellido_paterno) vendedor
			FROM rac_clientes a
			LEFT JOIN rac_vendedores b ON a.id_vendedor_asignado = b.id_vendedor
			WHERE a.id_cliente = '$idCliente'
		";
		$resCons = mysql_query($sqlCons) or die("Error at rowsel $sqlSalvaDisp::".mysql_error());
		$rowCons = mysql_fetch_assoc($resCons);
		return $rowCons;
	}
?>