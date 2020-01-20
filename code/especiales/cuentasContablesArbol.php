<?php
		
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$aCuentasContables = obtenerCuentasContablesNivel1();
	$cantidadSubcuentas = count ($aCuentasContables);
	
	$agregarCheck = array();
	
	for( $i = 0; $i < $cantidadSubcuentas; $i++ ){
		
		$tieneSubcuentas =  obtenerCuentasContablesNivel2($aCuentasContables[$i][3]);
		if($tieneSubcuentas == array()){
			array_push($agregarCheck, array($aCuentasContables[$i][0], "SI"));
		}
		else{
			array_push($agregarCheck, array($aCuentasContables[$i][0],"NO"));
		}
	}
	$smarty -> assign("agregarCheck",$agregarCheck);
	$smarty -> assign("cuentasNivel1",$aCuentasContables);
	$smarty -> assign("id",$campoId);	
	$smarty -> assign("cuenta",$campoCuenta);	
	
	$smarty->display("especiales/cuentas_contables_arbol.tpl");
?>