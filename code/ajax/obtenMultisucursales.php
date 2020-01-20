<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$cxp = $_POST['cxp'];

$sqlMuestraSuc = "SELECT id_sucursal, porcentaje, monto FROM na_cuentas_por_pagar_sucursal_detalle WHERE id_cuenta_por_pagar = " . $cxp;
		$muestraSucCXP = new consultarTabla($sqlMuestraSuc);
		$monto_porc_suc_cxp = $muestraSucCXP -> obtenerRegistros();
		
		foreach($monto_porc_suc_cxp as $datos){
				$valoresSuccxp[] = $datos -> id_sucursal . "|" . $datos -> monto . "|" . $datos -> porcentaje;
				}


$valoresSuccxp = implode(",", $valoresSuccxp);

echo $valoresSuccxp;
		
				
?>