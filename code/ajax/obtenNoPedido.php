<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$sucursal = $_POST['sucursal'];

$sql = "SELECT prefijo FROM ad_sucursales WHERE id_sucursal = " . $sucursal;

$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();


$sql2 = "SELECT id_pedido, MAX(consecutivo) AS siguiente FROM ad_pedidos WHERE prefijo = '" . $result['prefijo'] . "'";

$datos2 = new consultarTabla($sql2);
$result2 = $datos2 -> obtenerLineaRegistro();
$contador = $datos2 -> cuentaRegistros();

if($contador == 0){
		$consecutivo = 1;
		}
else{
		$consecutivo = $result2['siguiente'] + 1;
		}


echo $result['prefijo'] . "|" . $consecutivo;



?>