<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$familia = $_POST['familia'];
$tipo = $_POST['tipo'];
$modelo = $_POST['modelo'];
$caracteristica = $_POST['caracteristica'];

$where = "";

if($familia != 0)
		$where .= " AND na_familias_productos.id_familia_producto = " . $familia;
if($tipo != 0)
		$where.= " AND na_tipos_productos.id_tipo_producto = " . $tipo;
if($modelo != 0)
		$where.= " AND na_modelos_productos.id_modelo_producto = " . $modelo;
if($caracteristica != 0)
		$where.= " AND na_caracteristicas_productos.id_caracteristica_producto = " . $caracteristica;
		
$strSQL = "SELECT na_productos.id_producto, CONCAT(na_productos.sku, ' : ', na_productos.nombre) AS producto 
			FROM na_productos 
			LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto 
			LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto 
			LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto 
			LEFT JOIN na_caracteristicas_productos ON na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto 
			WHERE na_productos.activo = 1" . $where . " 
			ORDER BY na_productos.nombre";
			
$datos = new consultarTabla($strSQL);
$result = $datos -> obtenerRegistros();

foreach($result as $opcion){
		echo "<option value='" . $opcion -> id_producto . "'>" . utf8_encode($opcion -> producto) . "</option>";
		}


?>