<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$busqueda = $_POST['busqueda'];

$sql = "SELECT id_producto, CONCAT(sku, ' : ', nombre) AS producto FROM na_productos WHERE activo = 1 AND sku LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' ORDER BY nombre";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $opcion){
		echo "<option value='" . $opcion -> id_producto . "'>" . utf8_encode($opcion -> producto) . "</option>";
		}


?>