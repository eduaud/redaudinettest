<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$gasto = $_POST['gasto'];
$subgasto = $_POST['subgasto'];
$descripcion = $_POST['descripcion'];
$monto = $_POST['monto'];
$pedido = $_POST['pedido'];
$recibio = $_POST['recibio'];



$egresos['id_tipo_egreso'] = 6;
$egresos['fecha'] = date('Y-m-d');						
$egresos['total'] = $monto;
$egresos['id_sucursal'] = $_SESSION["USR"] -> sucursalid;
$egresos['id_usuario_registro'] = $_SESSION["USR"] -> userid;
$egresos['recibio'] = $recibio;
$egresos['activo'] = 1;
$egresos['id_control_pedido'] = $pedido;

accionesMysql($egresos, 'ad_egresos_caja_chica', 'Inserta');

$ultimo = mysql_insert_id();


$detalle['id_egreso'] = $ultimo;
$detalle['id_gasto'] = $gasto;
$detalle['id_subgasto'] = $subgasto;
$detalle['descripcion'] = $descripcion;
$detalle['monto'] = $monto;
$detalle['cantidad'] = 1;
$detalle['importe'] = $monto;
accionesMysql($detalle, 'ad_egresos_caja_chica_detalle', 'Inserta');

echo "Egreso generado correctamente";


?>