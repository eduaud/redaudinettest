<?php
	
include("../../conect.php");
include("../general/funciones.php");
include("../../consultaBase.php");

$vale = $_POST['vale'];
$cliente = $_POST['cliente'];	
$monto = $_POST['monto'];

$sql = "SELECT id_estatus_vale, total FROM na_vales_productos WHERE id_cliente = " . $cliente . " AND valeno = '" . $vale . "'";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();
$contador = $datos -> cuentaRegistros();

if($contador == 0){
		$respuesta['mensaje'] = "Número de vale inválido. Verifique de nuevo";
		$respuesta['status'] = 0;
		}
else{
	if($result['id_estatus_vale'] == 2){
			$respuesta['mensaje'] = "Vale de producto utilizado";
			$respuesta['status'] = 0;
			}
	else if($result['id_estatus_vale'] == 3){
			$respuesta['mensaje'] = "Vale de producto cancelado";
			$respuesta['status'] = 0;
			}
	else if($result['total'] != $monto){
			$respuesta['mensaje'] = "El monto del vale no coincide con el monto registrado";
			$respuesta['status'] = 0;
			}
	else if($result['id_estatus_vale'] == 1){
			$respuesta['mensaje'] = "Vale correcto";
			$respuesta['total'] = $result['total'];
			$respuesta['status'] = 1;
			}
			}

echo json_encode($respuesta);
	
	
?>