<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
$idOrdenCompra = $_GET['idOrdenCompra'];
$idDetalleOrdenCompra = $_GET['idDetalleOrdenCompra'];

$update = "UPDATE cl_importacion_numeros_series SET activo = '0' WHERE id_orden_compra = '".$idOrdenCompra."' AND n1 = '".$idDetalleOrdenCompra."';";
mysql_query($update) or die("Error: " . mysql_error());
//$respuesta = 'Ahora puede importar nuevamente la información';
//echo json_encode('Ahora puede importar nuevamente la información');
?>