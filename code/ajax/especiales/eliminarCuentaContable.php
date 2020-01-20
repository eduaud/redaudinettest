<?php
	
	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$confirmado = isset($confirmado) ? $confirmado : "";
	
	if($confirmado == 's'){
			$eliminaCuentaContable = eliminaCuentaContable($id_cuenta_contable);
			echo "Cuenta Contable Eliminada";
	}
	else{
			$sePuedeEliminar = verificarSiPuedeEliminarse($id_cuenta_contable,$nivel);			
			echo $sePuedeEliminar;
	}
?>