<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");


$aCuentasContablesNivel2 = obtenerCuentasContablesNivel2($id_cuenta_superior);

if($aCuentasContablesNivel2 == array()){
	echo "<p style='text-indent:30px !important;'><b>No hay Cuentas que mostrar</b></p>";
}
else{
	
	$cantidadSubcuentas = count ($aCuentasContablesNivel2);
	
	$agregarRadioButton = array();
	
	for( $i = 0; $i < $cantidadSubcuentas; $i++ ){
		
		$tieneSubcuentas =  obtenerCuentasContablesNivel3($aCuentasContablesNivel2[$i][0]);
		if($tieneSubcuentas == array()){
			array_push($agregarRadioButton, array($aCuentasContablesNivel2[$i][0], "SI"));
		}
		else{
			array_push($agregarRadioButton, array($aCuentasContablesNivel2[$i][0],"NO"));
		}
		
	}
	
	$smarty -> assign("agregarRadioButton",$agregarRadioButton);
	$smarty -> assign("cuentasNivel2",$aCuentasContablesNivel2);
	echo $smarty->fetch("especiales/ajax/mostrar_cuentas_contables_nivel2_seleccion.tpl");
	
}
		
?>