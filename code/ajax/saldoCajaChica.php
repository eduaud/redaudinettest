<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$sql = "SELECT SUM(monto) AS ingresos FROM ad_ingresos_caja_chica WHERE id_sucursal = " . $_SESSION['USR']->sucursalid . " AND confirmado = 1 AND activo = 1";
$datos = new consultarTabla($sql);
$ingresos = $datos -> obtenerLineaRegistro();
					
$sql2 = "SELECT SUM(total) AS egresos FROM ad_egresos_caja_chica WHERE id_sucursal = " . $_SESSION['USR']->sucursalid . " AND activo = 1";
$datos2 = new consultarTabla($sql2);
$egresos = $datos2 -> obtenerLineaRegistro();
					
$ingresos['ingresos'] = $ingresos['ingresos'] = "" ? 0 : $ingresos['ingresos'];
$egresos['egresos'] = $egresos['egresos'] = "" ? 0 : $egresos['egresos'];
					
$total_caja = $ingresos['ingresos'] - $egresos['egresos'];

echo $total_caja;

?>