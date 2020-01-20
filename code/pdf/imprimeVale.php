<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	Global $logo;
	Global $numero_recibo;
	
	$vale = $_GET['vale'];
	
	$sql = "SELECT id_vale_producto AS id_vale, na_sucursales.prefijo AS prefijo, na_sucursales.logo_sucursales AS logo,
			ad_pedidos.id_pedido AS pedido, CONCAT('$', FORMAT(na_vales_productos.total, 2)) AS monto,
			CONCAT(na_sucursales.calle, ' No. ' , na_sucursales.numero_exterior, IF(na_sucursales.numero_interior <> '', CONCAT(' Int. ', na_sucursales.numero_interior, ', '), ' '), ' Col. ', na_sucursales.colonia, ', ',  na_sucursales.delegacion_municipio, ', ', na_estados.nombre, ', C.P. ', na_sucursales.codigo_postal) AS direccion,
			CONCAT(na_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) AS cliente,
			ad_pedidos.id_control_pedido AS control_pedido, na_vales_productos.valeno AS recibo
			FROM na_vales_productos
			LEFT JOIN na_sucursales USING(id_sucursal)
			LEFT JOIN na_estados ON na_sucursales.id_estado = na_estados.id_estado
			LEFT JOIN na_clientes ON na_vales_productos.id_cliente = na_clientes.id_cliente
			LEFT JOIN ad_pedidos ON na_vales_productos.id_pedido_relacionado = ad_pedidos.id_control_pedido
			WHERE id_vale_producto = " . $vale;
			
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	
	foreach($result as $datosV){
			$logo = $datosV -> logo;
			$pedido = $datosV -> control_pedido;
			$numero_recibo = $datosV -> recibo;
			}


$sql2 = "SELECT na_productos.nombre AS producto, na_pedidos_detalle.cantidad_requerida AS cantidad, na_pedidos_detalle.observaciones AS observaciones
		FROM na_pedidos_detalle
		LEFT JOIN na_productos USING(id_producto)
		WHERE id_control_pedido = " . $pedido;
$datos2 = new consultarTabla($sql2);
$result2 = $datos2 -> obtenerRegistros();


			
include("valeProd_pdf.php");		
			
			
			
?>