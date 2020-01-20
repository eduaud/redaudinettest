<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	$pedido = $_GET['pedido'];
	$bitacora = $_GET['bitacora'];
	
	//Variables globales para el encabezado del pdf_add_annotation
	Global $sucursal;
	Global $folio;
	Global $fecha_pedido;
	Global $tipo_pago;
	Global $logo;
	
	$sql="SELECT na_sucursales.nombre AS sucursal, ad_pedidos.id_pedido AS folio, DATE_FORMAT(ad_pedidos.fecha_alta, '%d/%m/%Y') AS fecha_pedido, CONCAT(na_sucursales.calle, ' No. ' , na_sucursales.numero_exterior, IF(na_sucursales.numero_interior <> '', CONCAT(' Int. ', na_sucursales.numero_interior, ', '), ' '), ' Col. ', na_sucursales.colonia, ', ',  na_sucursales.delegacion_municipio, ', ', na_estados.nombre, ', C.P. ', na_sucursales.codigo_postal) AS direccion, ad_pedidos.id_cliente AS cliente, ad_tipos_pago_clientes.nombre AS tipo_pago, ad_pedidos.id_direccion_entrega AS direccion_cliente, logo_sucursales, ad_pedidos.observaciones AS observaciones, na_sucursales.telefono AS telefono, na_sucursales.correo AS correo
	FROM ad_pedidos
	LEFT JOIN na_sucursales ON ad_pedidos.id_sucursal_alta = na_sucursales.id_sucursal
	LEFT JOIN na_estados ON na_sucursales.id_estado = na_estados.id_estado
	LEFT JOIN ad_tipos_pago_clientes ON ad_pedidos.id_tipo_pago = ad_tipos_pago_clientes.id_tipo_pago
	
	WHERE ad_pedidos.id_control_pedido = " . $pedido;
	
	$ConsultaPedido = new consultarTabla($sql);
	$datosPedido = $ConsultaPedido->obtenerLineaRegistro();
	
	$sucursal = $datosPedido['sucursal'];
	$folio = $datosPedido['folio'];
	$fecha_pedido = $datosPedido['fecha_pedido'];
	$tipo_pago = $datosPedido['tipo_pago'];
	if($tipo_pago == "PAGOS PARCIALES"){
			$tipo_pago = "CONTADO";
			}
	$logo = $datosPedido['logo_sucursales'];
	
	$sql2 = "SELECT CONCAT(na_clientes.nombre,' ', if(na_clientes.apellido_paterno is null,'',na_clientes.apellido_paterno),' ',if(na_clientes.apellido_materno is null,'',na_clientes.apellido_materno)) AS cliente, 
	CONCAT(na_clientes_direcciones_entrega.calle, ' ', na_clientes_direcciones_entrega.numero_exterior, IF(na_clientes_direcciones_entrega.numero_interior <> '', CONCAT(' Int. ', na_clientes_direcciones_entrega.numero_interior), '')) AS domicilio, na_clientes_direcciones_entrega.colonia AS colonia, na_ciudades.nombre AS ciudad, na_clientes_direcciones_entrega.codigo_postal AS cp, 
	CONCAT(na_clientes.telefono_1, IF(na_clientes.telefono_2 <> '', CONCAT(', ', na_clientes.telefono_2), ''), IF(na_clientes.celular <> '', CONCAT(', ', na_clientes.celular), '')) AS telefonos,
DATE_FORMAT(na_pedidos_detalle.fecha_entrega, '%d/%m/%Y') AS fecha_entrega, CONCAT(na_vendedores.nombre , ' ', na_vendedores.apellido_paterno, ' ', na_vendedores.apellido_materno) AS vendedor, na_planos.plano AS plano, na_coordenadas.coordenada AS coordenada, na_clientes_direcciones_entrega.referencias AS referencia
			FROM na_clientes
			LEFT JOIN na_clientes_direcciones_entrega ON na_clientes.id_cliente = na_clientes_direcciones_entrega.id_cliente AND na_clientes_direcciones_entrega.id_cliente_direccion_entrega = " . $datosPedido['direccion_cliente'] . "
			LEFT JOIN na_ciudades ON na_clientes_direcciones_entrega.id_ciudad = na_ciudades.id_ciudad
			LEFT JOIN ad_pedidos ON na_clientes.id_cliente = ad_pedidos.id_cliente AND ad_pedidos.id_control_pedido = " . $pedido . "
			LEFT JOIN na_pedidos_detalle ON ad_pedidos.id_control_pedido = na_pedidos_detalle.id_control_pedido 
			LEFT JOIN na_vendedores ON ad_pedidos.id_vendedor = na_vendedores.id_vendedor 
			LEFT JOIN na_planos ON na_clientes_direcciones_entrega.id_plano = na_planos.id_plano 
			LEFT JOIN na_coordenadas ON na_clientes_direcciones_entrega.id_coordenada = na_coordenadas.id_coordenada 
			WHERE na_clientes.id_cliente = " . $datosPedido['cliente'] . " LIMIT 1";
	
	$ConsultaCliente = new consultarTabla($sql2);
	$datosCliente= $ConsultaCliente->obtenerRegistros();
	
	
	$sql3 = "SELECT na_bitacora_rutas_entregas_detalle.cantidad AS cantidad, na_productos.sku AS clave, na_productos.nombre AS producto, 
	ad_tipos_entrega_productos.nombre AS tipo_entrega, CONCAT('$ ', FORMAT(na_pedidos_detalle.precio,2)) AS precio, 
	CONCAT('$ ', FORMAT((cantidad_requerida * precio),2)) AS importe, na_productos.descripcion AS descripcion, 
	IF(ad_tipos_entrega_productos.id_tipo_entrega_producto = 2, CONCAT(ad_lugares_entrega.nombre, ' (C/R)'), ad_lugares_entrega.nombre) AS lugar_entrega
			FROM na_bitacora_rutas_entregas_detalle
			LEFT JOIN na_bitacora_rutas USING(id_bitacora_ruta)
			LEFT JOIN ad_pedidos ON ad_pedidos.id_control_pedido = na_bitacora_rutas_entregas_detalle.id_control_pedido
			LEFT JOIN na_pedidos_detalle ON ad_pedidos.id_control_pedido = na_pedidos_detalle.id_control_pedido
			LEFT JOIN na_productos ON na_pedidos_detalle.id_producto = na_productos.id_producto
			LEFT JOIN ad_tipos_entrega_productos ON na_pedidos_detalle.id_tipo_entrega = ad_tipos_entrega_productos.id_tipo_entrega_producto
			LEFT JOIN ad_lugares_entrega ON na_pedidos_detalle.id_lugar_entrega = ad_lugares_entrega.id_lugar_entrega
			WHERE na_pedidos_detalle.id_control_pedido = " . $pedido . " AND na_bitacora_rutas_entregas_detalle.id_bitacora_ruta = " . $bitacora . "
			AND na_productos.id_producto = na_bitacora_rutas_entregas_detalle.id_producto
			UNION ALL
			SELECT numero_camiones AS cantidad, '' AS clave, 'FLETES' AS producto, 'FLETES' AS tipo_entrega, CONCAT('$ ', FORMAT(precio, 2)) AS precio, CONCAT('$ ', FORMAT((numero_camiones * precio),2)) AS importe, '' AS descripcion, na_tipos_rodada.nombre AS lugar_entrega
			FROM na_pedidos_detalle_fletes
			LEFT JOIN na_tipos_rodada ON na_pedidos_detalle_fletes.id_tipo_rodada = na_tipos_rodada.id_tipo_rodada
			WHERE id_control_pedido = ". $pedido;
			
	$ConsultaProducto = new consultarTabla($sql3);
	$datosProducto = $ConsultaProducto->obtenerRegistros();
	
	$sql4 = "SELECT SUM(na_pedidos_detalle.cantidad_requerida * na_pedidos_detalle.precio) AS subtotalP
			FROM na_pedidos_detalle
			WHERE na_pedidos_detalle.id_control_pedido = ". $pedido;
	$ConsultaTotales = new consultarTabla($sql4);
	$datosTotales = $ConsultaTotales->obtenerLineaRegistro();
	
	$sql6 = "SELECT SUM(na_pedidos_detalle_fletes.numero_camiones * na_pedidos_detalle_fletes.precio) AS subtotalF
			FROM na_pedidos_detalle_fletes
			WHERE na_pedidos_detalle_fletes.id_control_pedido = ". $pedido;
	$ConsultaTotalesF = new consultarTabla($sql6);
	$datosTotalesF = $ConsultaTotalesF->obtenerLineaRegistro();
	
	$subtotalIP = $datosTotalesF['subtotalF'] + $datosTotales['subtotalP'];
	
	$sql5 = "SELECT na_clientes.email AS email FROM ad_pedidos LEFT JOIN na_clientes ON ad_pedidos.id_cliente = na_clientes.id_cliente WHERE id_control_pedido = " . $pedido;
	$emailC = new consultarTabla($sql5);
	$datosEmail = $emailC->obtenerLineaRegistro();
	
	
	include("embarque_pdf.php");
?>