<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$caso = $_POST['caso'];

$where = "";

if($caso == 2)
		$where = "AND id_lugar_entrega <> 1";
		
$sql = "SELECT id_lugar_entrega, nombre FROM ad_lugares_entrega WHERE 1 " . $where;
$result = new consultarTabla($sql);
$datos = $result -> obtenerRegistros();
		foreach($datos as $dato){
				echo '<option value="' . $dato -> id_lugar_entrega . '">' . utf8_encode($dato -> nombre) . '</option>';
				}	

		
		
		
?>