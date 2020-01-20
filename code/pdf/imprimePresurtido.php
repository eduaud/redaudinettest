<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	$ids = str_replace('|',',',  $_GET['ids']);

	Global $logo;
	Global $almacen;
	Global $vale;
	Global $folio;
	Global $texto_tipo;
	
	$vale = "PRESURTIDO";
	$texto_tipo = "Tipo de Salida";

		
			
	//$logo = $datosTipo["logo_sucursales"];		
	$logo = "../../imagenes/header_logo.png";	
							
	//$almacen = $datosTipo["almacen"];
	
	

	
/*	
	$sqlProd = "SELECT id_consecutivo, 
				id_token, 
				id_sucursal, 
				nombre_sucursal, 
				id_control_pedido, 
				id_pedido, 
				id_cliente, 
				nombre_cliente, 
				id_ruta, 
				ruta_nombre, 
				es_compuesto, 
				id_lugar_entrega, 
				nombre_lugar_entrega, 
				id_tipo_entrega, 
				nombre_tipo_entrega,
				id_pedido_detalle, 
				id_producto, 
				nombre_producto, 
				sku, 
				id_producto_compuesto, 
				nombre_producto_compuesto, 
				sku_compuesto, 
				id_apartado, 
				id_apartado_detalle, 
				id_estatus_apartado, 
				fecha_apartado,
				cantidad,
				id_estatus_pago,
				id_direccion_entrega, 
				direccion, 
				DATE_FORMAT(fecha_entrega_pto, '%d/%m/%Y')  AS fecha_entrega_pto,

				total_monto_pedido, 
				cantidad_existencia, 
				cantidad_existencia_otras_sucursales, 
				pagos, 
				cantidad_entregada, 
				id_almacen_buscar, 
				nombre_almacen_buscar, 
				id_almacen_sucursal_pedido,
				fecha FROM ad_pedidos_surtido_auxiliar WHERE id_consecutivo IN (".$ids.") " ;

	$consultaProd = new consultarTabla($sqlProd);
	$productos = $consultaProd -> obtenerRegistros();
*/	



	
	$sqlPedidos = "SELECT";
	$sqlPedidos .= " ad_pedidos.id_control_pedido,";
	$sqlPedidos .= " ad_pedidos.id_pedido pedido,";
	$sqlPedidos .= " ad_pedidos.id_tipo_cliente,";
	$sqlPedidos .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
	$sqlPedidos .= " ad_pedidos.id_cliente,";
	$sqlPedidos .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
	$sqlPedidos .= " ad_pedidos_detalle.id_producto,";
	$sqlPedidos .= " cl_productos_servicios.nombre producto,";
	$sqlPedidos .= " ad_pedidos_detalle.cantidad_requerida,";
	$sqlPedidos .= " cl_productos_servicios.requiere_numero_serie,";
	$sqlPedidos .= " ad_pedidos_detalle.id_pedido_detalle";
	$sqlPedidos .= " FROM ad_pedidos";
	$sqlPedidos .= " INNER JOIN ad_pedidos_detalle";
	$sqlPedidos .= " ON ad_pedidos.id_control_pedido = ad_pedidos_detalle.id_control_pedido";
	$sqlPedidos .= " INNER JOIN cl_tipos_cliente_proveedor";
	$sqlPedidos .= " ON ad_pedidos.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
	$sqlPedidos .= " INNER JOIN ad_clientes";
	$sqlPedidos .= " ON ad_pedidos.id_cliente = ad_clientes.id_cliente";
	$sqlPedidos .= " INNER JOIN cl_productos_servicios";
	$sqlPedidos .= " ON ad_pedidos_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
	$sqlPedidos .= " WHERE ad_pedidos.id_control_pedido IN (".$ids.")";
	$union = " UNION";
	$sqlSolicitudMaterial .= " SELECT";
	$sqlSolicitudMaterial .= " ad_solicitudes_material.id_control_solicitud_material,";
	$sqlSolicitudMaterial .= " ad_solicitudes_material.id_solicitud_material pedido,";
	$sqlSolicitudMaterial .= " ad_solicitudes_material.id_tipo_cliente,";
	$sqlSolicitudMaterial .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
	$sqlSolicitudMaterial .= " ad_solicitudes_material.id_cliente,";
	$sqlSolicitudMaterial .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
	$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.id_producto,";
	$sqlSolicitudMaterial .= " cl_productos_servicios.nombre producto,";
	$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.cantidad_requerida,";
	$sqlSolicitudMaterial .= " cl_productos_servicios.requiere_numero_serie,";
	$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.id_solicitud_material_detalle";
	$sqlSolicitudMaterial .= " FROM ad_solicitudes_material";
	$sqlSolicitudMaterial .= " INNER JOIN ad_solicitudes_material_detalle";
	$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_control_solicitud_material = ad_solicitudes_material_detalle.id_control_solicitud_material";
	$sqlSolicitudMaterial .= " INNER JOIN cl_tipos_cliente_proveedor";
	$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
	$sqlSolicitudMaterial .= " INNER JOIN ad_clientes";
	$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_cliente = ad_clientes.id_cliente";
	$sqlSolicitudMaterial .= " INNER JOIN cl_productos_servicios";
	$sqlSolicitudMaterial .= " ON ad_solicitudes_material_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
	$sqlSolicitudMaterial .= " WHERE ad_solicitudes_material.id_control_solicitud_material IN (".$ids.")";
	
	$sql = $sqlPedidos.$union.$sqlSolicitudMaterial;
	
	$consultaProd = new consultarTabla($sql);
	$productos = $consultaProd -> obtenerRegistros();	
	
	include("presurtido_pdf.php");
	
	
	?>