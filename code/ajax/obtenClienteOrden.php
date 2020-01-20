<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$pedido = $_POST['id'];

$sql = "SELECT ad_pedidos.id_cliente AS id_cliente, 
		CONCAT(ad_clientes.nombre,' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno),' ',if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) AS cliente
		FROM ad_pedidos
		LEFT JOIN ad_clientes USING(id_cliente)
		WHERE ad_pedidos.id_control_pedido = " . $pedido;
$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();

echo $result['id_cliente'] . "|" . utf8_encode($result['cliente']);


?>