<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$llenaComboCuentaSuperiorPorCuentaMayor = obtenerCuentaSuperiorPorCuentaMayor($id_cuenta_mayor);
	
	$smarty -> assign("comboCuentaSuperior",$llenaComboCuentaSuperiorPorCuentaMayor);
	$smarty->display("especiales/combo_cuenta_superior_por_cuenta_mayor.tpl");
?>