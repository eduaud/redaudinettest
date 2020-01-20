<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$sql = "SELECT ATI.porcentaje porcentaje_iva";
$sql .= " FROM cl_productos_servicios CPS INNER JOIN ad_tasas_ivas ATI";
$sql .= " ON CPS.id_tasa_iva = ATI.id_tasa_iva";
$sql .= " WHERE id_producto_servicio =" . $id;
$result = new consultarTabla($sql);
$datos = $result -> obtenerLineaRegistro();

echo $datos['porcentaje_iva'];
?>