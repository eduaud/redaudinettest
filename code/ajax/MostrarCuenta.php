<?php
	
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$aDetalleCuenta = obtenerDetalleDeCuentaContable($id_cuenta_contable);
	
	echo $aDetalleCuenta[0][1].": ".$aDetalleCuenta[0][2];
?>