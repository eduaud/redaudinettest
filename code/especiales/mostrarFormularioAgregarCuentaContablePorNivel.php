<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$tipo_cuenta_contable = isset($tipo_cuenta_contable) ? $tipo_cuenta_contable : "";
			
	if($tipo_cuenta_contable == 2 || $tipo_cuenta_contable == 3){
	
		$listaCuentasMayores = obtenerCuentasMayores();
		$smarty -> assign("aCuentasMayores",$listaCuentasMayores);
		
	}
	
	$listaGenerosCuentasContables = obtenerGenerosCuentasContables();

	$smarty -> assign("aGenerosCC",$listaGenerosCuentasContables);
	$smarty -> assign("tipoFormularioMostrar",$tipo_cuenta_contable);
	
	$smarty->display("especiales/agregar_cuentas_contables.tpl");
	
?>