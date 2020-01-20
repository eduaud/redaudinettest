<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	$productos = $_GET['productos'];
	$lista = $_GET['lista'];
	$sucursal = $_GET['sucursal'];
	
	$sqlFactor = "SELECT factor FROM na_sucursales WHERE id_sucursal = " . $sucursal;
	$resultF = new consultarTabla($sqlFactor);
	$datosFac = $resultF->obtenerLineaRegistro();
	
	if($lista == 1){
			$sql = "SELECT (na_productos.precio_lista * " . $datosFac['factor'] . ") AS precio, na_productos.nombre AS producto, na_modelos_productos.nombre AS modelo, na_piezas.piezas AS cantidad, na_productos.sku AS sku, na_productos.descripcion AS descripcion, na_productos.precio_lista AS promocion, 
			na_marcas_productos.nombre AS marca
			FROM na_productos 
			LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
			LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
			LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
			LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
			LEFT JOIN na_piezas USING(id_piezas)
			WHERE na_productos.id_producto IN (" . $productos . ")";
			}
	else{
			$sql = "SELECT (na_listas_detalle_productos.precio_final * " . $datosFac['factor'] . ") AS precio, 
			na_productos.nombre AS producto, na_modelos_productos.nombre AS modelo, na_piezas.piezas AS cantidad, na_productos.sku AS sku, na_productos.descripcion AS descripcion, 
			na_listas_detalle_productos.precio_final AS promocion, na_marcas_productos.nombre AS marca
			FROM na_productos 
			LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
			LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
			LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
			LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
			LEFT JOIN na_listas_detalle_productos ON na_productos.id_producto = na_listas_detalle_productos.id_producto
			LEFT JOIN na_piezas USING(id_piezas)
			WHERE na_productos.id_producto IN (" . $productos . ") AND na_listas_detalle_productos.id_lista_precios = " . $lista;	
			}
	
	$result = new consultarTabla($sql);
	$datos = $result->obtenerRegistros();
	
	if($sucursal == 10)
			include("etiquetas_pdf_jakane.php");
	else if($sucursal == 12)
			include("etiquetas_pdf_lv.php");
	else if($sucursal == 11)
			include("etiquetas_pdf_ql.php");
	else if($sucursal == 3)
			include("etiquetas_pdf_sm.php");
	else
			include("etiquetas_pdf.php");
?>