<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "DELETE FROM ad_costeo_productos_cuentas_por_pagar WHERE id_costeo_producto_cuentas_por_pagar = " . $id;
mysql_query($sql) or die("Error en consulta:<br> $sql <br>" . mysql_error());
?>