<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	
	$id_cuenta_contable    = isset($id_cuenta_contable) ? $id_cuenta_contable : "";
	$tipo_cuenta_contable = isset($tipo_cuenta_contable) ? $tipo_cuenta_contable : "";
	
	
	$camposEditar = verificaCamposQuePuedenEditarse($id_cuenta_contable,$tipo_cuenta_contable);
	
			
	$detalleCuentaContable = obtenerDetalleDeCuentaContable($id_cuenta_contable);
	$detalleCuentaSAT=obtenerCuentaSAT($id_cuenta_contable);
	$smarty -> assign("detalleCuentaSAT",$detalleCuentaSAT);
	if($tipo_cuenta_contable == 2 || $tipo_cuenta_contable == 3){
	
		$listaCuentasMayores = obtenerCuentasMayores();
		$smarty -> assign("aCuentasMayores",$listaCuentasMayores);
		/*$sql="SELECT cuenta_contable FROM scfdi_cuentas_contables WHERE id_cuenta_contable=".$detalleCuentaContable[0][7];
		
		$arr_id_cuenta_superior = str_split($detalleCuentaContable[0][7]);
		$num_pos_eliminar = (count($arr_id_cuenta_superior)) + 1;
		$id_cuenta_contable = substr($detalleCuentaContable[0][0],$num_pos_eliminar);*/
		$cuenta_contable=explode('-',$detalleCuentaContable[0][1]);
		
		//$smarty -> assign("numEliminar",$num_pos_eliminar);
		$smarty -> assign("cuenta_contable",$cuenta_contable[1]);
		$smarty -> assign("cuenta_contable_superior",$cuenta_contable[0]);
	}
	
	if($tipo_cuenta_contable == 3){
	
		$llenaComboCuentaSuperiorPorCuentaMayor = obtenerCuentaSuperiorPorCuentaMayor($detalleCuentaContable[0][7]);
		$smarty -> assign("comboCuentaSuperior",$llenaComboCuentaSuperiorPorCuentaMayor);
		
	}
	$listaGenerosCuentasContables = obtenerGenerosCuentasContables();
	
	$smarty -> assign("editar",$camposEditar);
	$smarty -> assign("datosCuenta",$detalleCuentaContable);
	$smarty -> assign("aGenerosCC",$listaGenerosCuentasContables);
	$smarty -> assign("tipoFormularioMostrar",$tipo_cuenta_contable);
	
	$smarty->display("especiales/editar_cuenta_contable.tpl");
			
	
?>