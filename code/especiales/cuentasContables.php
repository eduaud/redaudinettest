<?php
		
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$aCuentasContables = obtenerCuentasContablesNivel1();

	$smarty -> assign("cuentasNivel1",$aCuentasContables);
	$smarty->display("especiales/cuentas_contables.tpl");
?>