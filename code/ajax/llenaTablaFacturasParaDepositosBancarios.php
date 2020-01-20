<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$pedido = $_POST['idPedido'];
$cliente = $_POST['idCliente'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];

$dia = substr($fecini, 0, 2);
$mes = substr($fecini, 3, 2);
$anio = substr($fecini, -4);

$fecha_inicial = $anio . "-" . $mes . "-" . $dia;

$dia = substr($fecfin, 0, 2);
$mes = substr($fecfin, 3, 2);
$anio = substr($fecfin, -4);

$fecha_final = $anio . "-" . $mes . "-" . $dia;

$where = "";

if(!empty($pedido)) {
		$where .= " AND ad_facturas_audicel.id_factura = '" . $pedido . "'";
		}
if($cliente != 0) {
		$where .= " AND ad_facturas_audicel.id_cliente = " . $cliente;
		}
if(!empty($fecini) && !empty($fecfin)) {
		$where .= " AND ad_facturas_audicel.fecha_alta_usuario BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
		}
if(!empty($fecini) && empty($fecfin)) {
		$where .= " AND ad_facturas_audicel.fecha_alta_usuario = '" . $fecha_inicial . "'";
		}
if(empty($fecini) && !empty($fecfin)) {
		$where .= " AND ad_facturas_audicel.fecha_alta_usuario ='" . $fecha_final . "'";
		}
/*
$sql = "SELECT";
$sql .= " ad_facturas_audicel.id_control_factura AS id_pedido,";
$sql .= " CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', ad_clientes.apellido_materno) AS cliente,";
$sql .= " CONCAT('$', FORMAT(ad_facturas_audicel_detalle.monto,2)) AS monto_pago,";
$sql .= " id_pedido_detalle_pago AS id_detalle_pago,";
$sql .= " DATE_FORMAT(ad_facturas_audicel_detalle.fecha,'%d/%m/%Y') AS fecha_pago,";
$sql .= " ad_formas_pago.nombre AS forma_pago#,";
$sql .= " numero_documento AS documento";
$sql .= " FROM ad_facturas_audicel_detalle";
$sql .= " LEFT JOIN ad_facturas_audicel";
$sql .= " ON ad_facturas_audicel.id_control_pedido = ad_facturas_audicel_detalle.id_control_pedido";
$sql .= " LEFT JOIN ad_clientes";
$sql .= " ON ad_facturas_audicel.id_cliente = ad_clientes.id_cliente";
$sql .= " LEFT JOIN ad_formas_pago";
$sql .= " ON ad_facturas_audicel_detalle.id_forma_pago = ad_formas_pago.id_forma_pago";
$sql .= " WHERE (ad_facturas_audicel_detalle.id_deposito_bancario = '' OR ad_facturas_audicel_detalle.id_deposito_bancario IS NULL OR ad_facturas_audicel_detalle.id_deposito_bancario = 0)";
$sql .= " AND ad_facturas_audicel_detalle.confirmado <> 2" . $where;
*/
	
$sql = "SELECT";
$sql .= " ad_facturas_audicel.id_factura AS id_factura,";
$sql .= " CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', ad_clientes.apellido_materno) AS cliente,";
$sql .= " CONCAT('$', FORMAT(ad_facturas_audicel_detalle.importe,2)) AS monto_pago,";
$sql .= " id_control_factura_detalle AS id_detalle_pago,";
$sql .= " DATE_FORMAT(ad_facturas_audicel.fecha_alta_usuario,'%d/%m/%Y') AS fecha_pago,";
$sql .= " ad_formas_pago.nombre AS forma_pago,";
$sql .= " 'X' AS documento,";
$sql .= " ad_facturas_audicel.id_control_factura AS id_pedido";
$sql .= " FROM ad_facturas_audicel_detalle";
$sql .= " LEFT JOIN ad_facturas_audicel";
$sql .= " ON ad_facturas_audicel.id_control_pedido = ad_facturas_audicel_detalle.id_control_pedido";
$sql .= " LEFT JOIN ad_clientes";
$sql .= " ON ad_facturas_audicel.id_cliente = ad_clientes.id_cliente";
$sql .= " LEFT JOIN ad_formas_pago";
$sql .= " ON ad_facturas_audicel.id_forma_pago_sat = ad_formas_pago.id_forma_pago";
$sql .= " WHERE 1 =1 ".$where;

	
	
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);

echo $smarty->fetch('especiales/respuestaTablaFacturasParaDepositosBancarios.tpl');
?>




