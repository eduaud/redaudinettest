<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

extract($_POST);
extract($_GET);

if(isset($tabla)){
	if($cuenta_contable!=''){
		$cuentaContable = obtenerDetalleDeCuentaPorCuenta($cuenta_contable);
		if($cuentaContable[0][0]!=''&&$cuentaContable[0][1]!=''&&$cuentaContable[0][2])
			echo $cuentaContable[0][0]."|".$cuentaContable[0][1]."|".$cuentaContable[0][2];
		else 
			echo "error";
	}else{
		echo "error";
	}
}else{
	$cuentaContable = obtenerDetalleDeCuentaContable($id_cuenta_mayor);
	echo $cuentaContable[0][1];
}
?>