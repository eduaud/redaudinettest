<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	include("../../consultaBase.php");
	
	$sqlC = "SELECT id_cliente, CONCAT(nombre, ' ',apellido_paterno, ' ', apellido_materno) AS cliente FROM ad_clientes WHERE activo = 1";
	$datosCliente = new consultarTabla($sqlC);
	$cliente = $datosCliente -> obtenerArregloRegistros();

	$smarty -> assign("cliente", $cliente);
	
	$smarty->display("especiales/seleccionaFacturaNC.tpl");
	
	
?>