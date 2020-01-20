<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	
	$aCuentasContables = obtenerCuentasContablesNivel1();
	$cantidadSubcuentas = count ($aCuentasContables);
	
	$agregarRadioButton = array();
	
	for( $i = 0; $i < $cantidadSubcuentas; $i++ ){
		
		$tieneSubcuentas =  obtenerCuentasContablesNivel2($aCuentasContables[$i][3]);
		if($tieneSubcuentas == array()){
			array_push($agregarRadioButton, array($aCuentasContables[$i][0], "SI"));
		}
		else{
			array_push($agregarRadioButton, array($aCuentasContables[$i][0],"NO"));
		}
	}
	
	
	$smarty -> assign("agregarRadioButton",$agregarRadioButton);
	$smarty -> assign("nombreGridDetalle",$nombreGridDetalle);
	$smarty -> assign("numeroFilaGrid",$numeroFilaGrid);
	$smarty -> assign("cuentasNivel1",$aCuentasContables);
	$smarty->display("especiales/arbol_cuentas_contables_seleccionable.tpl");
?>