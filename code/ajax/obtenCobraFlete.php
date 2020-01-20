<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$direccion = $_POST['direccion'];

$sql = "SELECT na_tipos_rutas.id_tipo_ruta AS ruta  
		FROM ad_clientes_direcciones_entrega
		LEFT JOIN na_rutas ON ad_clientes_direcciones_entrega.id_ruta = na_rutas.id_ruta
		LEFT JOIN na_tipos_rutas ON na_rutas.id_tipo_ruta = na_tipos_rutas.id_tipo_ruta
		WHERE id_cliente_direccion_entrega = " . $direccion;

$datos = new consultarTabla($sql);
$ruta = $datos -> obtenerLineaRegistro();

echo $ruta['ruta'];

?>