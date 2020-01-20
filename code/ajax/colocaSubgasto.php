<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$sql="SELECT id_subgasto, nombre FROM na_conceptos_subgastos WHERE id_gasto = " . $id . " AND utilizable_caja_chica = 1";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $dato){
		echo '<option value="' . $dato -> id_subgasto . '">' . utf8_decode($dato -> nombre) . '</option>';
		}
?>