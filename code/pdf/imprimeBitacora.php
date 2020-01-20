<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	Global $logo;
	Global $bitacora;
	$bitacora = $_GET['bitacora'];
	
	$sqlLogo = "SELECT logo_sucursales FROM na_sucursales WHERE id_sucursal = " . $_SESSION["USR"]->sucursalid;
	$ConsultaLogo = new consultarTabla($sqlLogo);
	$datosLogo = $ConsultaLogo->obtenerLineaRegistro();
	$logo = $datosLogo['logo_sucursales'];
	
	$sql = "SELECT DATE_FORMAT(na_bitacora_rutas.fecha,'%d/%m/%Y') AS fecha_bitacora, 
			CONCAT(na_choferes.nombre, ' ', na_choferes.apellido_paterno, ' ', na_choferes.apellido_materno) AS chofer,
			na_camiones.placas AS camion, na_bitacora_rutas.km_iniciales AS km_iniciales, na_bitacora_rutas.km_finales AS km_finales,
			hs.nombre AS hora_salida, hll.nombre AS hora_llegada, na_bitacora_rutas.ayudante AS ayudante
			FROM na_bitacora_rutas
			LEFT JOIN na_choferes ON na_bitacora_rutas.id_chofer = na_choferes.id_chofer
			LEFT JOIN na_camiones ON na_bitacora_rutas.id_camion = na_camiones.id_camion
			LEFT JOIN na_tiempos hs ON na_bitacora_rutas.id_hora_salida = hs.id_tiempo
			LEFT JOIN na_tiempos hll ON na_bitacora_rutas.id_hora_llegada = hll.id_tiempo
			WHERE id_bitacora_ruta = " . $bitacora;
	$consultaBitacora = new consultarTabla($sql);
	$datosBitacora = $consultaBitacora->obtenerRegistros();
	
	$sql2 = "SELECT id_bitacora_ruta_entrega_detalle AS id_entrega_detalle, orden AS orden_entrega, na_rutas.nombre AS ruta, na_tipos_documentos_ruta.nombre AS tipo,
			DATE_FORMAT(na_bitacora_rutas_entregas_detalle.fecha_entrega,'%d/%m/%Y') AS fecha_entrega, na_bitacora_rutas_entregas_detalle.cliente_tienda AS cliente_tienda, na_bitacora_rutas_entregas_detalle.direccion_entrega_recoleccion AS direccion_entrega, he.nombre AS hora_entrega, hs.nombre AS hora_salida, na_bitacora_rutas_entregas_detalle.observaciones AS observaciones
			FROM na_bitacora_rutas_entregas_detalle
			LEFT JOIN na_rutas ON na_bitacora_rutas_entregas_detalle.id_ruta = na_rutas.id_ruta
			LEFT JOIN na_tipos_documentos_ruta ON na_bitacora_rutas_entregas_detalle.id_tipo_documento = na_tipos_documentos_ruta.id_tipo_documento_ruta
			LEFT JOIN na_tiempos he ON na_bitacora_rutas_entregas_detalle.id_hora_entrega = he.id_tiempo
			LEFT JOIN na_tiempos hs ON na_bitacora_rutas_entregas_detalle.id_hora_salida = hs.id_tiempo
			WHERE na_bitacora_rutas_entregas_detalle.id_bitacora_ruta = " . $bitacora . " ORDER BY orden + 0";
	$consultaEntregas = new consultarTabla($sql2);
	$bitacoraEntrega = $consultaEntregas->obtenerRegistros();
	
	$sql3 = "SELECT id_bitacora_ruta_entrega_manual_detalle AS id_manual_detalle, orden AS orden_manual, na_rutas.nombre AS ruta, DATE_FORMAT(na_bitacora_rutas_entrega_manual_detalle.fecha_hora_entrega,'%d/%m/%Y') AS fecha_manual, na_bitacora_rutas_entrega_manual_detalle.direccion_entrega_recoleccion AS direccion_entrega_manual, he.nombre AS hora_entrega_manual, hs.nombre AS hora_salida_manual, na_bitacora_rutas_entrega_manual_detalle.observaciones AS observaciones_manual
			FROM na_bitacora_rutas_entrega_manual_detalle
			LEFT JOIN na_rutas ON na_bitacora_rutas_entrega_manual_detalle.id_ruta = na_rutas.id_ruta
			LEFT JOIN na_tiempos he ON na_bitacora_rutas_entrega_manual_detalle.id_hora_entrega = he.id_tiempo
			LEFT JOIN na_tiempos hs ON na_bitacora_rutas_entrega_manual_detalle.id_hora_salida = hs.id_tiempo
			WHERE na_bitacora_rutas_entrega_manual_detalle.id_bitacora_ruta = " . $bitacora . " ORDER BY orden + 0";
	$consultaManual = new consultarTabla($sql3);
	$bitacoraManual = $consultaManual->obtenerRegistros();
	
	include("bitacora_ruta_pdf.php");
	
	
?>
