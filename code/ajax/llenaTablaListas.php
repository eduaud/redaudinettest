<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$familia = $_POST['familia'];
$proveedor = $_POST['proveedor'];
$marcaProv = $_POST['marcaProv'];
$tipo = $_POST['tipo'];
$modelo = $_POST['modelo'];
$marca = $_POST['marca'];
$op = $_POST['op'];
$lista = $_POST['lista'];
$where = "";
$leftProd = "";
$campos = "";


if($familia != "null" && $familia != "")
		$where .= " AND na_familias_productos.id_familia_producto IN ($familia)";
if($tipo != "null" && $tipo != "")
		$where .= " AND na_tipos_productos.id_tipo_producto IN ($tipo)";
if($modelo != "null" && $modelo != "")
		$where .= " AND na_modelos_productos.id_modelo_producto IN ($modelo)";
if($marca != "null" && $marca != "")
		$where .= " AND na_marcas_productos.id_marca_producto IN ($marca)";
if($proveedor != "null" && $proveedor != "")
		$where .= " AND na_productos_proveedor_detalle.id_proveedor IN ($proveedor)";
if($marcaProv != "null" && $marcaProv != "")
		$where .= " AND na_marcas_productos.id_marca_producto IN ($marcaProv)";

if($lista == 1){
		$sql = "SELECT na_familias_productos.nombre AS familia, na_tipos_productos.nombre AS tipo, na_modelos_productos.nombre AS modelo, na_marcas_productos.nombre AS marca, na_productos.sku, na_productos.nombre AS producto, FORMAT(precio_lista, 2) AS precio_publico, na_productos.id_producto, '0.00%' AS porcentaje, '0' AS porcentraje_real, FORMAT(precio_lista, 2) AS precio_final, precio_lista AS precio_final2
				FROM na_productos 
				LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				LEFT JOIN na_productos_proveedor_detalle ON na_productos.id_producto = na_productos_proveedor_detalle.id_producto
				WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0" . $where;
		}
else{
		if($op == 1){
				$leftProd = " LEFT JOIN na_listas_detalle_productos ON na_productos.id_producto = na_listas_detalle_productos.id_producto AND na_listas_detalle_productos.id_lista_precios = $lista";
				$campos = ", CONCAT(FORMAT(na_listas_detalle_productos.porcentaje, 2), '%') AS porcentaje, na_listas_detalle_productos.porcentaje AS porcentraje_real, CONCAT('$', FORMAT(na_listas_detalle_productos.precio_final, 2)) AS precio_final, na_listas_detalle_productos.precio_final AS precio_final2";
				}
				
		$sql = "SELECT na_familias_productos.nombre AS familia, na_tipos_productos.nombre AS tipo, na_modelos_productos.nombre AS modelo, na_marcas_productos.nombre AS marca, na_productos.sku, na_productos.nombre AS producto, FORMAT(precio_lista, 2) AS precio_publico, na_productos.id_producto " . $campos . " 
				FROM na_productos 
				LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				LEFT JOIN na_productos_proveedor_detalle ON na_productos.id_producto = na_productos_proveedor_detalle.id_producto
				" . $leftProd . "
				WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0" . $where . "
				ORDER BY producto, familia, tipo, modelo, na_productos.sku";
		}

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);


echo $smarty->fetch('especiales/respuestaTablaProductos.tpl');
		
?>