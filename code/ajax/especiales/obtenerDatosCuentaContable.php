<?php
	
	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$obtenerDatosCuentaContable = obtenerDetalleDeCuentaContable($id_cuenta_contable);
	
	echo json_encode($obtenerDatosCuentaContable);
	
?>