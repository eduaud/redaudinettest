<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idCliente = $_POST['idCliente'];

$sql = "SELECT id_control_pedido, id_pedido 
		FROM ad_pedidos 
		WHERE id_control_pedido NOT IN(SELECT id_pedido_relacionado FROM na_vales_productos)
		AND id_cliente = " . $idCliente . " AND (id_estatus_pedido = 5 OR id_estatus_pedido = 6)";
$result = new consultarTabla($sql);
$datos = $result -> obtenerRegistros();
echo '<option value="0">Selecciona una opci&oacute;n</option>';
foreach($datos as $dato){
		echo '<option value="' . $dato -> id_control_pedido . '">' . $dato -> id_pedido . '</option>';
		}	


?>