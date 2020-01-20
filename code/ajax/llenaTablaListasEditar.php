<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

//$productos = $_POST['productos'];
$lista = $_POST['lista'];


$sql = "SELECT na_familias_productos.nombre AS familia0/*0*/, na_tipos_productos.nombre AS tipo/*1*/, na_modelos_productos.nombre AS modelo/*2*/, 
		na_marcas_productos.nombre AS marca/*3*/, sku/*4*/, na_productos.nombre AS producto/*5*/, FORMAT(precio_lista, 2) AS precio_publico/*6*/, na_productos.id_producto/*7*/,
		CONCAT(FORMAT(na_listas_detalle_productos.porcentaje, 2), '%') AS porcentaje/*8*/, na_listas_detalle_productos.porcentaje AS porcentraje_real/*9*/, 
		CONCAT('$', FORMAT(na_listas_detalle_productos.precio_final, 2)) AS precio_final/*10*/, na_listas_detalle_productos.precio_final AS precio_final2/*11*/
		FROM na_listas_detalle_productos
		LEFT JOIN na_productos ON na_listas_detalle_productos.id_producto = na_productos.id_producto
		LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
		LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
		LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
		LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
		WHERE id_lista_precios = " . $lista . " AND na_productos.id_producto IS NOT NULL
		ORDER BY na_productos.nombre, na_familias_productos.nombre, na_tipos_productos.nombre, na_modelos_productos.nombre, na_productos.sku";


$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);


echo $smarty->fetch('especiales/respuestaTablaProductos.tpl');
		
?>