<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

date_default_timezone_set('America/Mexico_City');

$idPedido = $_POST["id"];

$sql = "SELECT DATE_ADD(fecha_alta_pedido, INTERVAL 1 DAY) AS fecha_siguiente FROM ad_pedidos WHERE id_control_pedido = " . $idPedido;
$result = new consultarTabla($sql);
$registro = $result -> obtenerLineaRegistro();

$hoy = time();
$dia_siguiente = strtotime($registro['fecha_siguiente']);

if($hoy > $dia_siguiente)
		echo 0;
else
		echo 1;



?>