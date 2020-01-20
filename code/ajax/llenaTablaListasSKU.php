<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$skuNombre = $_POST['skuNombre'];
$op = $_POST['op'];
$lista = $_POST['lista'];
$where = "";
$leftProd = "";
$campos = "";


if(isset($skuNombre))
		$where .= " AND (na_productos.sku LIKE '%" . $skuNombre . "%' OR na_productos.nombre LIKE '%" . $skuNombre . "%')";

if($lista == 1){
		$sql = "SELECT na_familias_productos.nombre AS familia, na_tipos_productos.nombre AS tipo, na_modelos_productos.nombre AS modelo, na_marcas_productos.nombre AS marca, sku, na_productos.nombre AS producto, FORMAT(precio_lista, 2) AS precio_publico, na_productos.id_producto, '0.00%' AS porcentaje, '0' AS porcentraje_real, FORMAT(precio_lista, 2) AS precio_final, precio_lista AS precio_final2
				FROM na_productos 
				LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0" . $where . "
				ORDER BY producto, familia, tipo, modelo, sku";
		}
else{
		if($op == 1){
				$leftProd = " LEFT JOIN na_listas_detalle_productos ON na_productos.id_producto = na_listas_detalle_productos.id_producto AND na_listas_detalle_productos.id_lista_precios = $lista";
				$campos = ", CONCAT(FORMAT(na_listas_detalle_productos.porcentaje, 2), '%') AS porcentaje, na_listas_detalle_productos.porcentaje AS porcentraje_real, CONCAT('$', FORMAT(na_listas_detalle_productos.precio_final, 2)) AS precio_final, CONCAT('$', FORMAT(na_listas_detalle_productos.precio_final,2)) AS precio_final2";
				}
				
		$sql = "SELECT na_familias_productos.nombre AS familia, na_tipos_productos.nombre AS tipo, na_modelos_productos.nombre AS modelo, na_marcas_productos.nombre AS marca, sku, na_productos.nombre AS producto, FORMAT(precio_lista, 2) AS precio_publico, na_productos.id_producto " . $campos . " 
				FROM na_productos 
				LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				" . $leftProd . "
				WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0" . $where . "
				ORDER BY producto, familia, tipo, modelo, sku";
		}

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);


echo $smarty->fetch('especiales/respuestaTablaProductos.tpl');
		
?>