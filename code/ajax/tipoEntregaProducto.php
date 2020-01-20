<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT entrega_inmediata FROM na_productos WHERE id_producto = " . $id;
$result = new consultarTabla($sql);
$datos = $result -> obtenerLineaRegistro();

$where = "";

if($datos['entrega_inmediata'] == 0)
		$where .= " AND id_tipo_entrega_producto <> 2";

$sql = "SELECT id_tipo_entrega_producto, nombre FROM ad_tipos_entrega_productos WHERE 1" . $where;
$result = new consultarTabla($sql);
$datos = $result -> obtenerRegistros();

		foreach($datos as $dato){
				echo '<option value="' . $dato -> id_tipo_entrega_producto . '">' . utf8_encode($dato -> nombre) . '</option>';
				}	

		
		
		
?>