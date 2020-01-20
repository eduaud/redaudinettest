<?php
	
include("../../conect.php");
include("../general/funciones.php");
include("../../consultaBase.php");

$vale = $_POST['vale'];
$cliente = $_POST['cliente'];	

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
			$respuesta['mensaje'] = "Este vale ya ha sido utilizado";
			$respuesta['status'] = 0;
			}
	else if($result['id_estatus_vale'] == 3){
			$respuesta['mensaje'] = "Este vale ha sido cancelado";
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