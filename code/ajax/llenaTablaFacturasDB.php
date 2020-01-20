<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$factura = $_POST['idFactura'];
$cliente = $_POST['idCliente'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];
$total = $_POST['total'];

$dia = substr($fecini, 0, 2);
$mes = substr($fecini, 3, 2);
$anio = substr($fecini, -4);

$fecha_inicial = $anio . "-" . $mes . "-" . $dia;

$dia = substr($fecfin, 0, 2);
$mes = substr($fecfin, 3, 2);
$anio = substr($fecfin, -4);

$fecha_final = $anio . "-" . $mes . "-" . $dia;

$where = "";

if(!empty($factura)) 
		$where .= " AND aux.id_factura = '" . $factura . "'";
		
if(!empty($total))
		$where .= " AND aux.total = '" . $total . "'";
		
if($cliente != 0)
		$where .= " AND aux.id_cliente = " . $cliente;
		
if(!empty($fecini) && !empty($fecfin)) 
		$where .= " AND aux.fecha_y_hora BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
		
if(!empty($fecini) && empty($fecfin)) 
		$where .= " AND aux.fecha_y_hora = '" . $fecha_inicial . "'";
		
if(empty($fecini) && !empty($fecfin)) 
		$where .= " AND aux.fecha_y_hora ='" . $fecha_final . "'";
		

$sql = "SELECT control_factura, factura, cliente, date_format(fecha_factura , '%d/%m/%Y') AS fecha_factura, 
		CONCAT('$',FORMAT(monto,2)) AS total, 
		CONCAT('$',FORMAT((cobrosDetalles + cobrosBancarios),2)) AS suma_cobros,
		CONCAT('$',FORMAT((monto -(cobrosDetalles + cobrosBancarios)),2)) AS saldo_factura
		FROM
		(
			SELECT aux.id_control_factura AS control_factura, aux.id_factura AS factura, ad_clientes.razon_social AS cliente, 
					aux.total AS monto, aux.fecha_y_hora AS fecha_factura,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_facturas_audicel_detalles_cobros WHERE activoCobro = 1 AND ad_facturas_audicel_detalles_cobros.id_control_factura=aux.id_control_factura) AS cobrosDetalles,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_depositos_bancarios_detalle WHERE activoDetBancarios=1 AND ad_depositos_bancarios_detalle.id_control_factura=aux.id_control_factura) AS cobrosBancarios
			FROM ad_facturas_audicel AS aux
			LEFT JOIN ad_clientes ON aux.id_cliente = ad_clientes.id_cliente
			LEFT JOIN ad_facturas_audicel_detalles_cobros ON aux.id_control_factura = ad_facturas_audicel_detalles_cobros.id_control_factura
			LEFT JOIN ad_depositos_bancarios_detalle ON aux.id_control_factura = ad_depositos_bancarios_detalle.id_control_factura
			WHERE 1 " . $where . "
		) AS datos
		WHERE (monto-(cobrosDetalles+cobrosBancarios)) > 0 GROUP BY control_factura";
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);
$smarty -> assign("sql", $sql);

$sql3 = "SELECT id_forma_cobro, nombre FROM ad_formas_cobro WHERE genera_deposito_bancario = 1 AND activo = 1 ORDER BY nombre";	
$datos3 = new consultarTabla($sql3);	
		$result3 = $datos3 -> obtenerRegistros();
		foreach($result3 as $registros){
				$arrPagoId[] = $registros -> id_forma_cobro;
				$arrPago[] = $registros -> nombre;			
				}		
		$smarty->assign('cobro_id', $arrPagoId);
		$smarty->assign('cobro_nombre',$arrPago);	

echo $smarty->fetch('especiales/respuestaTablaFacturasDB.tpl');


?>


