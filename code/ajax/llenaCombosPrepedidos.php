<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

if($id == 1){
				$sql = "SELECT na_formas_pago.id_forma_pago, na_formas_pago.nombre FROM na_formas_pago";
				}
else{
		$sql = "SELECT na_formas_pago.id_forma_pago, na_formas_pago.nombre 
				FROM na_listas_detalle_pagos
				LEFT JOIN na_formas_pago ON na_listas_detalle_pagos.id_forma_pago = na_formas_pago.id_forma_pago
				WHERE na_listas_detalle_pagos.id_lista_precios = $id";
		}
				
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
echo "<option value='0'>Selecciona Forma de Pago</option>";
foreach($result as $opcion){
		echo "<option value='" . $opcion[0] . "'>" . utf8_encode($opcion[1]) . "</option>";
		}

?>