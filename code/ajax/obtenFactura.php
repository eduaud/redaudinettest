<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$sucursal = $_POST['sucursal'];
$caso = $_POST['caso'];

$sql = "SELECT prefijo FROM na_sucursales WHERE id_sucursal = " . $sucursal;

$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();

if($caso == 1)
		$tabla = "ad_facturas";
else if($caso == 2)
		$tabla = "ad_notas_credito";

$sql2 = "SELECT MAX(consecutivo) AS siguiente FROM " . $tabla . " WHERE prefijo = '" . $result['prefijo'] . "'";

$datos2 = new consultarTabla($sql2);
$result2 = $datos2 -> obtenerLineaRegistro();
$contador = $datos2 -> cuentaRegistros();

if($contador == 0)
		$consecutivo = 1;
else
		$consecutivo = $result2['siguiente'] + 1;
		

$datosFactura['prefijo'] = $result['prefijo'];
$datosFactura['consecutivo'] = $consecutivo;

echo json_encode($datosFactura);





?>