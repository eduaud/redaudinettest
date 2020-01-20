<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$comision = $_GET['idComision'];

$sqlGasto = "SELECT id_gasto, nombre FROM na_conceptos_gastos WHERE activo = 1 AND utilizable_caja_chica = 1 ORDER BY nombre";
$datos = new consultarTabla($sqlGasto);	
$result = $datos -> obtenerRegistros();
foreach($result as $registros){
		$arrGastoId[] = $registros -> id_gasto;
		$arrGasto[] = $registros -> nombre;			
		}		
$smarty->assign('gasto_id', $arrGastoId);
$smarty->assign('gasto_nombre',$arrGasto);

$sqlCom = "SELECT na_comisiones.id_comision AS id_comision, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha_comision, CONCAT('$', FORMAT(monto_pagado, 2)) AS monto_comision,
			CONCAT(na_vendedores.nombre,' ', IF(apellido_paterno is null,'',apellido_paterno),' ',IF(apellido_materno is null,'',apellido_materno)) AS vendedor, monto_pagado AS total
			FROM na_comisiones
			LEFT JOIN na_vendedores USING(id_vendedor)
			WHERE id_comision = " . $comision;
			
$datosCom = new consultarTabla($sqlCom);
$resultCom = $datosCom -> obtenerLineaRegistro();
$smarty -> assign("vendedor", $resultCom['vendedor']);
$smarty -> assign("monto_total", $resultCom['monto_comision']);
$smarty -> assign("id_comision", $resultCom['id_comision']);
$smarty -> assign("fecha", $resultCom['fecha_comision']);
$smarty -> assign("total", $resultCom['total']);

$strSQLSuc = "SELECT id_sucursal, nombre FROM na_sucursales WHERE id_sucursal = " . $_SESSION["USR"] -> sucursalid . " AND activo = 1";	
$datosSuc = new consultarTabla($strSQLSuc);
$resultSuc = $datosSuc -> obtenerLineaRegistro();
$smarty -> assign("id_sucursal", $resultSuc['id_sucursal']);
$smarty -> assign("sucursal", $resultSuc['nombre']);


$sqlComDet = "SELECT ad_pedidos.id_pedido AS pedido, DATE_FORMAT(ad_pedidos.fecha_alta, '%d/%m/%Y') AS fecha_alta, 
			CONCAT('$', FORMAT(na_comisiones_detalles.monto_pedido, 2)) AS monto_pedido,
			CONCAT('$', FORMAT(na_comisiones_detalles.monto_comision, 2)) AS monto_comision, ad_pedidos.id_control_pedido AS control_pedido
			FROM na_comisiones_detalles
			LEFT JOIN ad_pedidos USING(id_control_pedido)
			WHERE na_comisiones_detalles.id_comision = " . $comision;
			
$datosComDet = new consultarTabla($sqlComDet);
$resultComDet = $datosComDet -> obtenerArregloRegistros();
$smarty -> assign("filasCom", $resultComDet);

$smarty->display('especiales/egresosComisiones.tpl');

?>