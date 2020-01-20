<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$pedido = $_POST['id'];
$producto = $_POST['producto'];

$sql = "SELECT cantidad_requerida AS cantidad
		FROM na_pedidos_detalle
		WHERE id_control_pedido = " . $pedido . " AND id_producto = " . $producto;
		
$result = new consultarTabla($sql);
$respuesta = $result -> obtenerLineaRegistro();

echo $respuesta["cantidad"];

?>