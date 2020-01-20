<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	$pedido = $_GET['pedido'];
	
	//Variables globales para el encabezado del pdf_add_annotation
	Global $accion;
	Global $sucursal;
	Global $folio;
	Global $fecha_pedido;
	Global $tipo_pago;
	Global $logo;
	
	$sql="SELECT ad_sucursales.nombre AS sucursal, ad_solicitudes_material.id_solicitud_material AS folio,DATE_FORMAT(ad_solicitudes_material.fecha_alta_solicitud,'%d/%m/%Y') AS fecha_pedido,CONCAT(ad_sucursales.calle, ' No. ' , ad_sucursales.numero_exterior,IF(ad_sucursales.numero_interior <> '', CONCAT(' Int. ', ad_sucursales.numero_interior, ', '), ' '), ' Col. ', ad_sucursales.colonia, ', ',  ad_sucursales.delegacion, ', ', sys_estados.nombre, ', C.P. ', ad_sucursales.codigo_postal) AS direccion,ad_solicitudes_material.id_cliente AS cliente,ad_tipos_pago_clientes.nombre AS tipo_pago,ad_solicitudes_material.observaciones AS observaciones, 
	ad_sucursales.telefono_1 AS telefono, ad_sucursales.email AS correo
	FROM ad_solicitudes_material
	LEFT JOIN ad_sucursales ON ad_solicitudes_material.id_sucursal = ad_sucursales.id_sucursal
	LEFT JOIN sys_estados ON ad_sucursales.id_estado = sys_estados.id_estado
	LEFT JOIN ad_tipos_pago_clientes ON ad_solicitudes_material.id_tipo_pago = ad_tipos_pago_clientes.id_tipo_pago
	WHERE ad_solicitudes_material.id_control_solicitud_material= " . $pedido;
	
	$ConsultaPedido = new consultarTabla($sql);
	$datosPedido = $ConsultaPedido->obtenerLineaRegistro();
	
	$accion='SOLICITUD';
	$sucursal = $datosPedido['sucursal'];
	$folio = $datosPedido['folio'];
	$fecha_pedido = $datosPedido['fecha_pedido'];
	$tipo_pago = $datosPedido['tipo_pago'];
	if($tipo_pago == "PAGOS PARCIALES"){
			$tipo_pago = "CONTADO";
			}
	$logo ='../../imagenes/header_logo.png';
	
	$sql2 = "SELECT CONCAT(ad_clientes.nombre,' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno),' ',if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) AS cliente,
	CONCAT(ad_clientes.calle_fiscal, ' ', ad_clientes.numero_exterior_fiscal, IF(ad_clientes.numero_interior_fiscal <> '', CONCAT(' Int. ', ad_clientes.numero_interior_fiscal), '')) AS domicilio,
	ad_clientes.colonia_fiscal AS colonia,sys_ciudades.nombre AS ciudad, ad_clientes.codigo_postal_fiscal AS cp,
	IF(ad_clientes.telefono_1_fiscal  <> '',ad_clientes.telefono_1_fiscal,'') AS telefono
	FROM ad_clientes 
	LEFT JOIN sys_ciudades
	ON ad_clientes.id_ciudad_fiscal=sys_ciudades.id_ciudad 
	WHERE ad_clientes.id_cliente = " . $datosPedido['cliente'] . " LIMIT 1";
	
	$ConsultaCliente = new consultarTabla($sql2);
	$datosCliente= $ConsultaCliente->obtenerRegistros();
	
	
	$sql3 = "SELECT cantidad_requerida AS cantidad, cl_productos_servicios.clave, cl_productos_servicios.nombre AS producto,
	CONCAT('$ ',FORMAT(ad_solicitudes_material_detalle.precio,2)) AS precio,
	CONCAT('$ ', FORMAT((cantidad_requerida*(ad_solicitudes_material_detalle.precio)),2)) AS importe
	FROM ad_solicitudes_material_detalle 
	LEFT JOIN cl_productos_servicios ON ad_solicitudes_material_detalle.id_producto=cl_productos_servicios.id_producto_servicio 
	LEFT JOIN ad_solicitudes_material ON ad_solicitudes_material_detalle.id_control_solicitud_material=ad_solicitudes_material.id_control_solicitud_material	
	WHERE ad_solicitudes_material.id_control_solicitud_material=".$pedido;
	
	$ConsultaProducto = new consultarTabla($sql3);
	$datosProducto = $ConsultaProducto->obtenerRegistros();
	
	$sql4 ="SELECT SUM((ad_solicitudes_material_detalle.cantidad_requerida*ad_solicitudes_material_detalle.precio)) AS subtotalP
			FROM ad_solicitudes_material_detalle
			LEFT JOIN ad_solicitudes_material on ad_solicitudes_material_detalle.id_control_solicitud_material=ad_solicitudes_material.id_control_solicitud_material
			WHERE ad_solicitudes_material.id_control_solicitud_material=".$pedido;
			
			
			
	$ConsultaTotales = new consultarTabla($sql4);
	$datosTotales = $ConsultaTotales->obtenerLineaRegistro();
	
	$sql6 = "SELECT SUM((ad_solicitudes_material_detalle.cantidad_requerida*ad_solicitudes_material_detalle.precio)+monto_iva) AS Total
			FROM ad_solicitudes_material_detalle
			LEFT JOIN  ad_solicitudes_material on ad_solicitudes_material_detalle.id_control_solicitud_material=ad_solicitudes_material.id_control_solicitud_material
			WHERE ad_solicitudes_material.id_control_solicitud_material = ". $pedido;
	
	$sqliva = "SELECT SUM(monto_iva) AS iva
				FROM ad_solicitudes_material_detalle
				LEFT JOIN  ad_solicitudes_material on ad_solicitudes_material_detalle.id_control_solicitud_material=ad_solicitudes_material.id_control_solicitud_material
				WHERE ad_solicitudes_material_detalle.id_control_solicitud_material = ". $pedido;
			
			
	$ConsultaTotalesF = new consultarTabla($sql6);
	$datosTotal = $ConsultaTotalesF->obtenerLineaRegistro();
	
	$ConsultaIVA = new consultarTabla($sqliva);
	$datosIVA = $ConsultaIVA->obtenerLineaRegistro();
	
	$subtotalIP =$datosTotales['subtotalP'];
	$Total =$datosTotal['Total'];
	$iva=$datosIVA['iva'];
	
	//die('subtotal='.$subtotalIP.' : Total='.$Total.' : IVA='.$iva);
	
	$sql5 = "SELECT ad_clientes.email AS email 
			FROM ad_solicitudes_material 
			LEFT JOIN ad_clientes ON ad_solicitudes_material.id_cliente = ad_clientes.id_cliente WHERE id_control_solicitud_material=".$pedido;
	$emailC = new consultarTabla($sql5);
	$datosEmail = $emailC->obtenerLineaRegistro();
	
	
	include("pedido_pdf.php");
?>