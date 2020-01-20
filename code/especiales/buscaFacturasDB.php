<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	include("../../consultaBase.php");
	
	$facturasR = $_GET['facturasR'];
	$sqlC = "SELECT DISTINCT ad_facturas_audicel.id_cliente, razon_social";
	$sqlC .= " FROM ad_facturas_audicel";
	$sqlC .= " LEFT JOIN ad_clientes ON ad_facturas_audicel.id_cliente = ad_clientes.id_cliente";
//	$sqlC .= " WHERE id_estatus_cobro_factura = 1";
	$datosCliente = new consultarTabla($sqlC);
	$cliente = $datosCliente -> obtenerArregloRegistros();

	$smarty -> assign("cliente", $cliente);
	$smarty -> assign("facturasR", $facturasR);
	
	$smarty->display("especiales/buscaFacturasDB.tpl");
	
	
?>