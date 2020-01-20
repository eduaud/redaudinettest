<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES utf8");

$pedido = $_POST['pedido'];

$sql = "SELECT na_pedidos_detalle.id_producto AS id_producto, na_productos.nombre AS producto, 
			cantidad_requerida AS cantidad, observaciones AS observaciones
			FROM na_pedidos_detalle
			LEFT JOIN na_productos USING(id_producto)
			WHERE id_control_pedido = " . $pedido;
$result = new consultarTabla($sql);
$datos = $result -> obtenerArregloRegistros();

$campoProductos['productos'] = $datos;

echo json_encode($campoProductos);


?>