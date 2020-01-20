<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$factura = $_POST['factura'];

$sql = "SELECT ad_clientes.id_cliente AS id_cliente, 
		CONCAT(nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) AS cliente
		FROM ad_facturas
		LEFT JOIN ad_clientes ON ad_facturas.id_cliente = ad_clientes.id_cliente
		WHERE id_control_factura = " . $factura;

$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();

$datosFactura['id_cliente'] = $result['id_cliente'];
$datosFactura['cliente'] = utf8_encode($result['cliente']);

echo json_encode($datosFactura);



?>