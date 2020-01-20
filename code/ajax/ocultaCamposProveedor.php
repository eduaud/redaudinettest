<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT es_de_productos FROM na_tipos_proveedores WHERE id_tipo_proveedor = " . $id;
$result = new consultarTabla($sql);
$datos = $result -> obtenerLineaRegistro();

echo $datos['es_de_productos'];


?>