<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$pago = $_POST['id'];

$sqlVerifica = "SELECT id_grupo 
FROM sys_grupos 
LEFT JOIN sys_usuarios USING(id_grupo)
WHERE id_usuario = " . $_SESSION["USR"] -> userid;
$datosV = new consultarTabla($sqlVerifica);
$resultV = $datosV -> obtenerLineaRegistro();

if($resultV['id_grupo'] == 1){
		$actualiza = "UPDATE na_pedidos_detalle_pagos SET activoDetPagos = 0 WHERE id_pedido_detalle_pago = " . $pago;
		mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
		$texto = "|Se ha cancelado el pago";
		
		$sql = "SELECT id_control_pedido FROM na_pedidos_detalle_pagos WHERE id_pedido_detalle_pago = " . $pago;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerLineaRegistro();
		
		$verifica = $result['id_control_pedido'];
		}
else{
		$texto = "|Necesita ser administrador para cancelar el pago";
		$verifica = 0;
		}
		
		


echo $verifica . $texto;


?>