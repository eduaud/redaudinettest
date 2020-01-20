<?php
	
	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$aDetalleCuenta = obtenerDetalleDeCuentaContable($id_cuenta_contable);
	$aDetalleCuentaSAT = obtenerCuentaSAT($id_cuenta_contable);
	$listaGenerosCuentasContables = obtenerGenerosCuentasContables();
	$listaCuentasMayores = obtenerCuentasMayores();
	$listaCuentasSuperiores = obtenerCuentaSuperiorPorCuentaMayor($aDetalleCuenta[0][8]);
	$smarty -> assign("aCuentasSuperiores",$listaCuentasSuperiores);
	$smarty -> assign("aCuentaSAT",$aDetalleCuentaSAT);
	$smarty -> assign("aGenerosCC",$listaGenerosCuentasContables);
	$smarty -> assign("aCuentasMayores",$listaCuentasMayores);
	$smarty -> assign("datosCuenta",$aDetalleCuenta);
	echo $smarty -> fetch("especiales/ajax/ver_cuenta_accion.tpl");
?>