<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
extract($_GET);
extract($_POST);
$sql="SELECT requiere_numero_serie FROM cl_productos_servicios WHERE id_producto_servicio=".$id;
$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();
if($result['requiere_numero_serie']=='0')
	echo 'error|1';
else if($result['requiere_numero_serie']=='1')
	echo 'exito|0';
?>

