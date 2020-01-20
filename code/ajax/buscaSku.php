<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$busqueda = $_POST['busqueda'];

$sql = "
	SELECT id_producto_servicio, CONCAT(clave, ' : ', nombre) AS producto 
	FROM cl_productos_servicios 
	WHERE activo = 1 AND id_clasificacion = 1 AND clave 
	LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' ORDER BY nombre";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $opcion){
	echo "<option value='" . $opcion -> id_producto_servicio . "'>" . utf8_encode($opcion -> producto) . "</option>";
}


?>