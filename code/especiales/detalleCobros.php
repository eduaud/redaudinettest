<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
	
$factura = $_GET["idFactura"];

		
$sql = "SELECT control_factura, uuid, DATE_FORMAT(fecha_factura,'%d/%m/%Y'), cliente, sucursal, FORMAT(iva,2), FORMAT(subtotal,2), 
		FORMAT(total,2), FORMAT((cobrosDetalles + cobrosBancarios),2) AS suma_cobros,
		FORMAT((total -(cobrosDetalles + cobrosBancarios)),2) AS saldo_factura, factura, observaciones, estatus_cobros,
		cobrosDetalles, cobrosBancarios
		FROM
		(
			SELECT aux.id_control_factura AS control_factura, aux.folio_cfd AS uuid, aux.id_factura AS factura, ad_clientes.razon_social AS cliente, 
					aux.total AS total, aux.fecha_y_hora AS fecha_factura, ad_sucursales.id_sucursal AS sucursal, aux.iva AS iva, aux.subtotal AS subtotal,
					aux.observaciones AS observaciones, ad_estatus_cobros_facturas.nombre AS estatus_cobros,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_facturas_audicel_detalles_cobros WHERE activoCobro = 1 AND ad_facturas_audicel_detalles_cobros.id_control_factura=aux.id_control_factura) AS cobrosDetalles,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_depositos_bancarios_detalle WHERE activoDetBancarios=1 AND ad_depositos_bancarios_detalle.id_control_factura=aux.id_control_factura) AS cobrosBancarios
			FROM ad_facturas_audicel AS aux
			LEFT JOIN ad_clientes ON aux.id_cliente = ad_clientes.id_cliente
			LEFT JOIN ad_sucursales ON aux.id_sucursal = ad_sucursales.id_sucursal
			LEFT JOIN ad_facturas_audicel_detalles_cobros ON aux.id_control_factura = ad_facturas_audicel_detalles_cobros.id_control_factura
			LEFT JOIN ad_depositos_bancarios_detalle ON aux.id_control_factura = ad_depositos_bancarios_detalle.id_control_factura
			LEFT JOIN ad_estatus_cobros_facturas ON aux.id_estatus_cobro_factura = ad_estatus_cobros_facturas.id_estatus_cobro_factura
			WHERE aux.id_control_factura = " . $factura . "
		) AS datos GROUP BY control_factura";
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("facturas", $result);

$sql2 = "SELECT DATE_FORMAT(fecha, '%d/%m/%Y'), id_forma_cobro, documento, FORMAT(monto,2), observaciones, id_factura_detalle_cobro 
		FROM ad_facturas_audicel_detalles_cobros
		WHERE activoCobro = 1 AND id_control_factura = " . $factura;
$datos2 = new consultarTabla($sql2);
$result2 = $datos2 -> obtenerArregloRegistros();
$smarty -> assign("detallesCobros", $result2);

$sql3 = "SELECT id_forma_cobro, nombre FROM ad_formas_cobro WHERE genera_deposito_bancario = 0 AND activo = 1 ORDER BY nombre";
$datos3 = new consultarTabla($sql3);	
		$result3 = $datos3 -> obtenerRegistros();
		foreach($result3 as $registros){
				$arrPagoId[] = $registros -> id_forma_cobro;
				$arrPago[] = $registros -> nombre;			
				}		
		$smarty->assign('pago_id', $arrPagoId);
		$smarty->assign('pago_nombre',$arrPago);	
		
$sql4 = "SELECT id_sucursal, nombre FROM ad_sucursales ORDER BY nombre";
$datos4 = new consultarTabla($sql4);	
		$result4 = $datos4 -> obtenerRegistros();
		foreach($result4 as $registros){
				$arrSucId[] = $registros -> id_sucursal;
				$arrSuc[] = $registros -> nombre;			
				}		
		$smarty->assign('area_id', $arrSucId);
		$smarty->assign('area_nombre',$arrSuc);
		
$smarty->assign('factura',$factura);	

$smarty->display("especiales/detalleCobros.tpl");

?>