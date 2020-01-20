<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

//$sql = "SELECT precio_lista AS precio_real, CONCAT('$', FORMAT(precio_lista, 2)) AS precio_muestra FROM na_productos WHERE id_producto = " . $id;
$sql = "SELECT precio FROM cl_productos_servicios WHERE id_producto_servicio = " . $id;
$result = new consultarTabla($sql);
$datos = $result -> obtenerLineaRegistro();

//echo $datos['precio_real'] . "|" . $datos['precio_muestra'];
echo $datos['precio'];


?>