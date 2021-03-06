<?php
include("../../conect.php");

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
/***   Termina convirte fecha a formato Y-m-d   ***/

//***   Cambia Formato Fecha   ***//
function cambiarFormatoFecha($fecha, $formato, $delimitador_fecha){
	$fechaAMD = "";
	
	if(($delimitador_fecha=="/" || $delimitador_fecha=="-") && ($formato=="ymd" || $formato=="dmy")){
		$delimitador="";
		
		if(strpos($fecha, "-") != false) $delimitador = "-";
		elseif(strpos($fecha, "/") != false) $delimitador = "/";
		$pos = strpos($fecha, $delimitador);
		
		if($delimitador != ""){
			$iniciaPor = "";
			if($pos == 2) $iniciaPor='dia';
			elseif($pos == 4) $iniciaPor='anio';
			
			if($iniciaPor!=""){
				$arrFecha = explode($delimitador, $fecha);
				
				if($formato=="ymd"){
					if($iniciaPor=='dia'){
						$fechaAMD=$arrFecha[2].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[0];
					} else if($iniciaPor=='anio'){
						$fechaAMD=$arrFecha[0].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[2];
					}
				} elseif($formato=="dmy"){
					if($iniciaPor=='dia'){
						$fechaAMD=$arrFecha[0].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[2];
					} elseif($iniciaPor=='anio'){
						$fechaAMD=$arrFecha[2].$delimitador_fecha.$arrFecha[1].$delimitador_fecha.$arrFecha[0];
					}
				}
			}
		}
	}
	
	return $fechaAMD;
}
//***   Termina Cambia Formato Fecha   ***//

$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];
$tipo = $_POST['tipo'];
$nombreProducto = trim($_POST['nombreProducto']);

//exit("fin del programa");



$filtro .= ' AND ad_clientes.id_cliente IN ('.$_SESSION["USR"]->clientes_relacionados.')';

if($fechaIni != "" && $fechaFin != ""){
	if($fechaIni <= $fechaFin){
		$filtroFechaContrato .= ' AND cl_control_contratos.fecha_activacion BETWEEN "'.formatoFechaYMD($fechaIni).'" AND "'.formatoFechaYMD($fechaFin).'"';
		$filtroFechaPenalizaciones .= ' AND cl_control_penalizaciones.fecha_activacion BETWEEN "'.formatoFechaYMD($fechaIni).'" AND "'.formatoFechaYMD($fechaFin).'"';
		$filtroFechaBonos .= ' AND cl_control_bonos.fecha_activacion BETWEEN "'.formatoFechaYMD($fechaIni).'" AND "'.formatoFechaYMD($fechaFin).'"';
	}
}

if($tipo == "LPP"){
	$query='SELECT 
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente,
				ad_clientes.clave as claveCliente, cl_productos_servicios.nombre as nombreProductoServicio, 
				cl_productos_servicios.clave as claveProducto,
				cl_control_contratos.contrato as folio,cl_control_contratos.cuenta as cuenta,
				cl_control_contratos.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_contratos_detalles.monto_penalizacion as montoPenalizacion,
				cl_control_contratos.id_control_contrato as id, cl_control_contratos_detalles.id_detalle as idDetalle, "contratos" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_contratos 
			LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato 
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente 
			LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones 
			LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky 
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal 
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN cl_contrarecibos ON cl_contrarecibos.id_contrarecibo = cl_control_contratos_detalles.id_contra_recibo
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar_audicel
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_contratos_detalles.id_accion_contrato IN (50,51) AND cl_control_contratos_detalles.activo = 1 AND cl_control_contratos_detalles.ultimo_movimiento = 2'.$filtro.$filtroFechaContrato.'

			union all

			SELECT
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente, ad_clientes.clave as claveCliente,
				cl_productos_servicios.nombre as nombreProductoServicio, cl_productos_servicios.clave as claveProducto, cl_control_penalizaciones.contrato as folio,
				cl_control_penalizaciones.cuenta as cuenta, cl_control_penalizaciones.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_penalizaciones_detalle.monto_penalizacion, cl_control_penalizaciones.id_control_penalizacion as id,
				cl_control_penalizaciones_detalle.id_control_penalizacion_detalle as idDetalle, "penalizaciones" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_penalizaciones
			LEFT JOIN cl_control_penalizaciones_detalle ON cl_control_penalizaciones.id_control_penalizacion = cl_control_penalizaciones_detalle.id_control_penalizacion
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_penalizaciones_detalle.id_cliente
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_penalizaciones_detalle.id_sucursal
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_penalizaciones.id_producto_servicio
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_penalizaciones_detalle.id_accion IN (50) AND cl_control_penalizaciones_detalle.activo = 1 AND cl_control_penalizaciones_detalle.ultimo_movimiento = 2'.$filtro.$filtroFechaPenalizaciones;
} 
elseif($tipo == "LPXP"){
	$folio = $_POST["folio"];
	$cuenta = $_POST["cuenta"];
	$clave = $_POST["clave"];
	$observaciones = $_POST["observaciones"];
	$campo_fecha = $_POST["campo_fecha"];
	
	if($fechaIni != "" && $fechaFin != "" && $campo_fecha == "fecha_movimiento"){
		if($fechaIni <= $fechaFin){
			$filtroFechaContrato = ' AND cl_control_contratos_detalles.fecha_movimiento BETWEEN "'.formatoFechaYMD($fechaIni).'" AND "'.formatoFechaYMD($fechaFin).'"';
			$filtroFechaPenalizaciones = ' AND cl_control_penalizaciones_detalle.fecha_movimiento BETWEEN "'.formatoFechaYMD($fechaIni).'" AND "'.formatoFechaYMD($fechaFin).'"';
		}
	}
	
	$filtro_unico = '';
	$filtro_unico_2 = '';
	
	if($folio != ""){
		$filtro_unico .= ' AND cl_control_contratos.contrato LIKE "%'.$folio.'%"';
		$filtro_unico_2 .= ' AND cl_control_penalizaciones.contrato LIKE "%'.$folio.'%"';
	}
	
	if($cuenta != ""){
		$filtro_unico .= ' AND cl_control_contratos.cuenta LIKE "%'.$cuenta.'%"';
		$filtro_unico_2 .= ' AND cl_control_penalizaciones.cuenta LIKE "%'.$cuenta.'%"';
	}
	
	if($clave != ""){
		$filtro_unico .= ' AND ad_clientes.clave LIKE "%'.$clave.'%"';
		$filtro_unico_2 .= ' AND ad_clientes.clave LIKE "%'.$clave.'%"';
	}
	
	if($observaciones != ""){
		$filtro_unico .= ' AND cl_importacion_penalizaciones.bt76 LIKE "%'.$observaciones.'%"';		
	}
	
	$query='SELECT 
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente,
				ad_clientes.clave as claveCliente, cl_productos_servicios.nombre as nombreProductoServicio, 
				cl_productos_servicios.clave as claveProducto,
				cl_control_contratos.contrato as folio,cl_control_contratos.cuenta as cuenta,
				cl_control_contratos.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_contratos_detalles.monto_penalizacion as montoPenalizacion,
				cl_control_contratos.id_control_contrato as id, cl_control_contratos_detalles.id_detalle as idDetalle, "contratos" as tabla,
				ad_tasas_ivas.porcentaje as IVA, cl_importacion_penalizaciones.t52 AS serie, cl_importacion_penalizaciones.bt76 AS observaciones,
				cl_control_contratos_detalles.fecha_movimiento
			FROM cl_control_contratos 
			LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato 
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente 
			LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones 
			LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky 
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal 
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN cl_contrarecibos ON cl_contrarecibos.id_contrarecibo = cl_control_contratos_detalles.id_contra_recibo
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar_audicel
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			LEFT JOIN cl_importacion_penalizaciones ON cl_importacion_penalizaciones.id_carga = cl_control_contratos_detalles.id_carga
			WHERE cl_control_contratos_detalles.id_accion_contrato IN (52,53) AND cl_control_contratos_detalles.activo = 1 AND cl_control_contratos_detalles.ultimo_movimiento = 2'.$filtro.$filtroFechaContrato.$filtro_unico.'

			union all

			SELECT
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente, ad_clientes.clave as claveCliente,
				cl_productos_servicios.nombre as nombreProductoServicio, cl_productos_servicios.clave as claveProducto, cl_control_penalizaciones.contrato as folio,
				cl_control_penalizaciones.cuenta as cuenta, cl_control_penalizaciones.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_penalizaciones_detalle.monto_penalizacion, cl_control_penalizaciones.id_control_penalizacion as id,
				cl_control_penalizaciones_detalle.id_control_penalizacion_detalle as idDetalle, "penalizaciones" as tabla,
				ad_tasas_ivas.porcentaje as IVA, "" AS serie, "" AS observaciones, cl_control_penalizaciones_detalle.fecha_movimiento
			FROM cl_control_penalizaciones
			LEFT JOIN cl_control_penalizaciones_detalle ON cl_control_penalizaciones.id_control_penalizacion = cl_control_penalizaciones_detalle.id_control_penalizacion
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_penalizaciones_detalle.id_cliente
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_penalizaciones_detalle.id_sucursal
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_penalizaciones.id_producto_servicio
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_penalizaciones_detalle.id_accion IN (52) AND cl_control_penalizaciones_detalle.activo = 1 AND cl_control_penalizaciones_detalle.ultimo_movimiento = 2'.$filtro.$filtroFechaPenalizaciones.$filtro_unico_2;
} 
elseif($tipo == "PPF"){
	$query='SELECT 
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente,
				ad_clientes.clave as claveCliente, cl_productos_servicios.nombre as nombreProductoServicio, 
				cl_productos_servicios.clave as claveProducto,
				cl_control_contratos.contrato as folio,cl_control_contratos.cuenta as cuenta,
				cl_control_contratos.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_contratos_detalles.monto_penalizacion as montoPenalizacion,
				cl_control_contratos.id_control_contrato as id, cl_control_contratos_detalles.id_detalle as idDetalle, "contratos" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_contratos 
			LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato 
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente 
			LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones 
			LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky 
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal 
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN cl_contrarecibos ON cl_contrarecibos.id_contrarecibo = cl_control_contratos_detalles.id_contra_recibo
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar_audicel
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_contratos_detalles.id_accion_contrato IN (54,55) AND cl_control_contratos_detalles.activo = 1 AND cl_control_contratos_detalles.ultimo_movimiento = 2 AND
			aceptado = 1 '.$filtro.$filtroFechaContrato.'

			union all

			SELECT
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente, ad_clientes.clave as claveCliente,
				cl_productos_servicios.nombre as nombreProductoServicio, cl_productos_servicios.clave as claveProducto, cl_control_penalizaciones.contrato as folio,
				cl_control_penalizaciones.cuenta as cuenta, cl_control_penalizaciones.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_penalizaciones_detalle.monto_penalizacion, cl_control_penalizaciones.id_control_penalizacion as id,
				cl_control_penalizaciones_detalle.id_control_penalizacion_detalle as idDetalle, "penalizaciones" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_penalizaciones
			LEFT JOIN cl_control_penalizaciones_detalle ON cl_control_penalizaciones.id_control_penalizacion = cl_control_penalizaciones_detalle.id_control_penalizacion
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_penalizaciones_detalle.id_cliente
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_penalizaciones_detalle.id_sucursal
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_penalizaciones.id_producto_servicio
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_penalizaciones_detalle.id_accion IN (54) AND cl_control_penalizaciones_detalle.activo = 1 AND cl_control_penalizaciones_detalle.ultimo_movimiento = 2 AND
			aceptado = 1 '.$filtro.$filtroFechaPenalizaciones;
} 
elseif($tipo == "LBXP"){
	$query='SELECT 
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente,
				ad_clientes.clave as claveCliente, cl_productos_servicios.nombre as nombreProductoServicio, 
				cl_productos_servicios.clave as claveProducto,
				cl_control_contratos.contrato as folio,cl_control_contratos.cuenta as cuenta,
				cl_control_contratos.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_contratos_detalles.monto_bono as montoBono,
				cl_control_contratos.id_control_contrato as id, cl_control_contratos_detalles.id_detalle as idDetalle, "contratos" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_contratos 
			LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato 
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente 
			LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones 
			LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky 
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal 
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN cl_contrarecibos ON cl_contrarecibos.id_contrarecibo = cl_control_contratos_detalles.id_contra_recibo
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar_audicel
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_contratos_detalles.id_accion_contrato IN (60,61) AND cl_control_contratos_detalles.activo = 1 AND cl_control_contratos_detalles.ultimo_movimiento = 3'.$filtro.$filtroFechaContrato.'

			union all

			SELECT
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente, ad_clientes.clave as claveCliente,
				cl_productos_servicios.nombre as nombreProductoServicio, cl_productos_servicios.clave as claveProducto, cl_control_bonos.contrato as folio,
				cl_control_bonos.cuenta as cuenta, cl_control_bonos.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_bonos_detalle.monto_bono as montoBono, cl_control_bonos.id_control_bono as id,
				cl_control_bonos_detalle.id_control_bono_detalle as idDetalle, "bonos" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_bonos
			LEFT JOIN cl_control_bonos_detalle ON cl_control_bonos.id_control_bono = cl_control_bonos_detalle.id_control_bono
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_bonos_detalle.id_cliente
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_bonos_detalle.id_sucursal
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_bonos.id_producto_servicio
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_bonos_detalle.id_accion IN (60,61) AND cl_control_bonos_detalle.activo = 1 AND cl_control_bonos_detalle.ultimo_movimiento = 3'.$filtro.$filtroFechaBonos;
} 
elseif($tipo == "BPF"){
    
    //verificamos si enviaron nombreProductoServicio
    
    $filtroProducto;
    
    if(strlen($nombreProducto) > 0){ // obtenemos el id correspondiente del nombre de producto
        
        $consulta = "select id_producto_servicio from cl_productos_servicios where nombre = '" . $nombreProducto . "';";
        
        $query = mysql_query($consulta);
        
        $datos = mysql_fetch_assoc($query);
        
        $idProductoServicio = $datos['id_producto_servicio'];
        
    if(!is_numeric($idProductoServicio)){
        
        echo '<br><br><p style="color:red; text-align:center;">El nombre del producto indicado '. '<strong>' . $nombreProducto . '</strong>' .' no tiene id relacionado, favor de corroborar</p><br>';
        exit();
    }
        
        $filtroProducto = ' and cl_productos_servicios.id_producto_servicio = ' . $idProductoServicio;

    }
    
	$query='SELECT 
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente,
				ad_clientes.clave as claveCliente, cl_productos_servicios.nombre as nombreProductoServicio, 
				cl_productos_servicios.clave as claveProducto,
				cl_control_contratos.contrato as folio,cl_control_contratos.cuenta as cuenta,
				cl_control_contratos.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_contratos_detalles.monto_bono as montoBono,
				cl_control_contratos.id_control_contrato as id, cl_control_contratos_detalles.id_detalle as idDetalle, "contratos" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_contratos 
			LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato 
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_contratos_detalles.id_cliente 
			LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones 
			LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky 
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_contratos_detalles.id_sucursal 
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN cl_contrarecibos ON cl_contrarecibos.id_contrarecibo = cl_control_contratos_detalles.id_contra_recibo
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_contratos_detalles.id_producto_servicio_facturar_audicel
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_contratos_detalles.id_accion_contrato IN (62,63) AND cl_control_contratos_detalles.activo = 1 AND cl_control_contratos_detalles.ultimo_movimiento = 3'.$filtro.$filtroFechaContrato.'

			union all

			SELECT
				CONCAT_WS(" ",ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) as nombreCliente, ad_clientes.clave as claveCliente,
				cl_productos_servicios.nombre as nombreProductoServicio, cl_productos_servicios.clave as claveProducto, cl_control_bonos.contrato as folio,
				cl_control_bonos.cuenta as cuenta, cl_control_bonos.fecha_activacion as fechaActivacion, ad_sucursales.nombre as nombreSucursal,
				cl_control_bonos_detalle.monto_bono as montoBono, cl_control_bonos.id_control_bono as id,
				cl_control_bonos_detalle.id_control_bono_detalle as idDetalle, "bonos" as tabla,
				ad_tasas_ivas.porcentaje as IVA
			FROM cl_control_bonos
			LEFT JOIN cl_control_bonos_detalle ON cl_control_bonos.id_control_bono = cl_control_bonos_detalle.id_control_bono
			LEFT JOIN ad_clientes ON ad_clientes.id_cliente = cl_control_bonos_detalle.id_cliente
			LEFT JOIN ad_sucursales ON ad_sucursales.id_sucursal = cl_control_bonos_detalle.id_sucursal
			LEFT JOIN cl_productos_servicios ON cl_productos_servicios.id_producto_servicio = cl_control_bonos.id_producto_servicio
			LEFT JOIN cl_tipos_cliente_proveedor ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = ad_clientes.id_tipo_cliente_proveedor 
			LEFT JOIN ad_tasas_ivas ON ad_tasas_ivas.id_tasa_iva = cl_productos_servicios.id_tasa_iva
			WHERE cl_control_bonos_detalle.id_accion IN (62,63) AND cl_control_bonos_detalle.activo = 1 AND cl_control_bonos_detalle.ultimo_movimiento = 3'. $filtro . $filtroFechaBonos . $filtroProducto;
}

$result = mysql_query($query);
$num = mysql_num_rows($result);

$contador=1;
$totalIVAG=0;
$totalG=0;
$totalMontoG=0;

echo '<div style="max-height: 300px; overflow: auto;">';
echo '<table class="comisiones_pendientes table_border" style="width: 100%;">
		<tr>
			<th>No</th>
			<th>Cliente</th>
			<th>Clave Cliente</th>
			<th>Nombre Producto Servicio</th>
			<th>Clave Producto Servicio</th>
			<th>Sucursal</th>
			<th>Folio</th>
			<th>Cuenta</th>
			<th>Fecha de Activaci&oacute;n</th>';
	if($tipo == "LPXP"){
		echo '
			<th>Serie</th>
			<th>Observaciones</th>
		';
	}
		echo '<th>Monto</th>';
	if($tipo == "LPXP"){
		echo '<th>Comentario Rechazo</th>';
	}
	  echo '<th><input id="sel-todos" type="checkbox" onclick="seleccionarCheck(this, \'comisiones_pendientes\');"></th>';
  echo '</tr>';
if($num > 0){
	while($datos=mysql_fetch_array($result)){
		if($contador % 2 == 0){ $fondo='#d8dce3 !important;'; }
		else { $fondo='#f0f1f4 !important;'; }
		
		echo '<tr style="background-color: '.$fondo.'">';
			echo '<td style="text-align: center">'.$contador.'</td>';
			echo '<td>'.htmlentities($datos["nombreCliente"], ENT_QUOTES).'</td>';
			echo '<td>'.$datos["claveCliente"].'</td>';
			echo '<td>'.$datos["nombreProductoServicio"].'</td>';
			echo '<td>'.$datos["claveProducto"].'</td>';
			echo '<td>'.$datos["nombreSucursal"].'</td>';
			echo '<td>'.$datos["folio"].'</td>';
			echo '<td>'.$datos["cuenta"].'</td>';
			echo '<td style="text-align: center">'.cambiarFormatoFecha($datos["fechaActivacion"],"dmy","-").'</td>';
			
			if($tipo == "LPXP"){
				echo '
					<td>'.$datos["serie"].'</td>
					<td>'.$datos["observaciones"].'</td>
				';
			}
			
			if($tipo == "LPP" || $tipo == "LPXP" || $tipo == "PPF"){
				echo '<td align="right">$ '. number_format($datos["montoPenalizacion"],2,'.',',').'</td>';
			} elseif($tipo == "LBXP" || $tipo="BPF"){
				echo '<td align="right">$'. number_format($datos["montoBono"],2,'.',',').'</td>';
			} else {
				echo '<td align="right"></td>';
			}
			
			if($tipo == "LPXP"){
				echo '<td><textarea id="textarea-'.$datos["id"].'-'.$datos["idDetalle"].'-'.$datos["tabla"].'"></textarea></td>';
			}
			
			echo '<td align="center"><input id="idComisionPendienteCheck1" type="checkbox" name="idComisionPendiente[]" value="'.$datos["id"].','.$datos["idDetalle"].','.$datos["tabla"].'"></td>';
		echo '</tr>';
		
		$totalMonto=0;
		if($tipo == "LPP" || $tipo == "LPXP" || $tipo == "PPF"){
			$totalMonto += $datos["montoPenalizacion"];
		} elseif($tipo == "LBXP" || $tipo="BPF"){
			$totalMonto += $datos["montoBono"];
		}
		
		$ivaXproducto = 0;
		if($datos["IVA"] != '' && $datos["IVA"] > 0){
			$ivaXproducto = ($datos["IVA"] / 100);
		}
		
		$totalMontoG += $totalMonto;
		$totalIVAG += ($totalMonto * $ivaXproducto);
		$totalG += ($totalMonto + ($totalMonto * $ivaXproducto));
		
		$contador++;
	}
}

echo '</table>';
echo '</div>';

echo '<table width="100%" class="campo_small" style="padding: 15px 25px 10px 0;">';
	echo '<tr>';
		echo '<td><p style="font-weight: bold;">TOTAL: &nbsp;'.($contador - 1).'</p></td>';
	echo '</tr>';
echo '</table>';

echo '<br>
		<div style="height: 150px; padding-left: 82%;">
			<table class="table_border">
				<tr>
					<th align="right">Monto Total</th>
					<td align="right" style="width: 120px;">$ '. number_format($totalMontoG, 2, '.', ',').'</td>
				</tr>
				<tr>
					<th align="right"><p style="font-weight: bold;">IVA</p></th>
					<td align="right" style="width: 120px;">$ '. number_format($totalIVAG, 2, '.', ',').'</td>
				</tr>
				<tr>
					<th align="right"><p style="font-weight: bold;">Total<p></th>
					<td align="right" style="width: 120px;">$ '. number_format($totalG, 2, '.', ',').'</td>
				</tr>
			</table>
		</div>';
?>