<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$sku = $_POST['skuNombre'];
$lista = $_POST['lista'];

$where = "";


if(isset($sku))
		$where .= " AND (na_productos.sku LIKE '%" . $sku . "%' OR na_productos.nombre LIKE '%" . $sku . "%')";

if($lista == 1){
		$sql = "SELECT na_productos.id_producto, na_familias_productos.nombre AS familia, na_tipos_productos.nombre AS tipo, na_modelos_productos.nombre AS modelo, na_marcas_productos.nombre AS marca, sku, na_productos.nombre AS producto, CONCAT('$', FORMAT(precio_lista, 2)) AS precio
				FROM na_productos 
				LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				WHERE na_productos.activo = 1" . $where . "
				ORDER BY producto, familia, tipo, modelo, sku";
		}
else{
		$sql = "SELECT na_productos.id_producto, na_familias_productos.nombre AS familia, na_tipos_productos.nombre AS tipo, na_modelos_productos.nombre AS modelo, na_marcas_productos.nombre AS marca, sku, na_productos.nombre AS producto, CONCAT('$', FORMAT(na_listas_detalle_productos.precio_final,2)) AS precio
				FROM na_productos 
				LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				LEFT JOIN na_listas_detalle_productos ON na_productos.id_producto = na_listas_detalle_productos.id_producto AND na_listas_detalle_productos.id_lista_precios = $lista
				WHERE na_productos.activo = 1" . $where . "
				ORDER BY producto, familia, tipo, modelo, sku";
		}

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);


echo $smarty->fetch('especiales/respuestaTablaProductosEtiquetas.tpl');
		
?>