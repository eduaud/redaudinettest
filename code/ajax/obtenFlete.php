<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$rodada = $_POST['id'];
$direccion = $_POST['direccion'];

$sql = "SELECT na_rutas_detalle_costo_transporte.costo 
		FROM ad_clientes_direcciones_entrega 
		LEFT JOIN na_rutas ON ad_clientes_direcciones_entrega.id_ruta = na_rutas.id_ruta
		LEFT JOIN na_rutas_detalle_costo_transporte ON na_rutas.id_ruta = na_rutas_detalle_costo_transporte.id_ruta AND id_tipo_rodada = " . $rodada . "
		WHERE id_cliente_direccion_entrega = " . $direccion;

$datos = new consultarTabla($sql);
$costo = $datos -> obtenerLineaRegistro();

echo  $costo['costo'];



?>