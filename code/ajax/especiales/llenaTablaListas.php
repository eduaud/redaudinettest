<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$familia = $_POST['familia'];
$tipo = $_POST['tipo'];
$modelo = $_POST['modelo'];
$marca = $_POST['marca'];


$sql = "SELECT na_familias_productos.nombre AS familia, na_tipos_productos.nombre AS tipo, na_modelos_productos.nombre AS modelo, na_marcas_productos.nombre AS marca, sku, na_productos.nombre AS producto, precio_lista
		FROM na_productos 
		LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
		LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
		LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
		LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
		WHERE na_familias_productos.activo = 1";


$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $celdas){
		echo "<tr>";
		echo "<td>" . $celdas -> familia . "</td>";
		echo "<td>" . $celdas -> tipo . "</td>";
		echo "<td>" . $celdas -> modelo . "</td>";
		echo "<td>" . $celdas -> marca . "</td>";
		echo "<td>" . $celdas -> sku . "</td>";
		echo "<td>" . $celdas -> producto . "</td>";
		echo "<td>" . $celdas -> precio_lista . "</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "</tr>";
		}
		
		
//WHERE activo = 1 AND id_familia_producto IN ($id)
?>