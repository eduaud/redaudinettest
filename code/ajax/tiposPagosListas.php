<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$listas = $_POST['listas'];

$datos = explode(",", $listas);
$contadorDatos = count($datos);
$cuentaPagos = array();

for($i=0; $i<$contadorDatos; $i++){
		if($datos[$i] == 1)
				$sql = "SELECT id_forma_pago FROM na_formas_pago";
		else
				$sql = "SELECT id_forma_pago FROM na_listas_detalle_pagos WHERE id_lista_precios = " . $datos[$i];
		
		$result = new consultarTabla($sql);
		$cuentaPagos[] = $result -> cuentaRegistros() . "|" . $datos[$i];
		}
		
		asort($cuentaPagos);
		$idFinal = array_shift($cuentaPagos);
		$datoFinal = explode("|", $idFinal);
		
if($datoFinal[1] == 1){
		$sql2 = "SELECT na_formas_pago.id_forma_pago AS id, na_formas_pago.nombre AS pago FROM na_formas_pago";
		}
else{		
		$sql2 = "SELECT na_formas_pago.id_forma_pago AS id, na_formas_pago.nombre AS pago
		FROM na_listas_detalle_pagos 
		LEFT JOIN na_formas_pago ON na_listas_detalle_pagos.id_forma_pago = na_formas_pago.id_forma_pago
		WHERE id_lista_precios = " . $datoFinal[1] . "
		UNION ALL
		SELECT na_formas_pago.id_forma_pago AS id, na_formas_pago.nombre AS pago
		FROM na_formas_pago 
		WHERE na_formas_pago.id_forma_pago = 9
		";
		}
$result2 = new consultarTabla($sql2);

$resultList = $result2 -> obtenerRegistros();
		foreach($resultList as $campo){
				echo '<option value="' . $campo -> id . '">' . utf8_encode($campo -> pago) . '</option>';
				}	

		
		
		
?>