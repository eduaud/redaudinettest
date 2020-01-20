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
		$where .= " AND ad_pedidos.id_pedido = '" . $pedido . "'";
		}
if($cliente != 0) {
		$where .= " AND ad_pedidos.id_cliente = " . $cliente;
		}
if(!empty($fecini) && !empty($fecfin)) {
		$where .= " AND ad_pedidos.fecha_alta BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
		}
if(!empty($fecini) && empty($fecfin)) {
		$where .= " AND ad_pedidos.fecha_alta = '" . $fecha_inicial . "'";
		}
if(empty($fecini) && !empty($fecfin)) {
		$where .= " AND ad_pedidos.fecha_alta ='" . $fecha_final . "'";
		}

$sql = "SELECT ad_pedidos.id_pedido AS id_pedido, CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', ad_clientes.apellido_materno) AS cliente, CONCAT('$', FORMAT(ad_pedidos_detalle_pagos.monto,2)) AS monto_pago, id_pedido_detalle_pago AS id_detalle_pago, DATE_FORMAT(ad_pedidos_detalle_pagos.fecha,'%d/%m/%Y') AS fecha_pago, ad_formas_pago.nombre AS forma_pago, numero_documento AS documento
		FROM ad_pedidos_detalle_pagos
		LEFT JOIN ad_pedidos ON ad_pedidos.id_control_pedido = ad_pedidos_detalle_pagos.id_control_pedido
		LEFT JOIN ad_clientes ON ad_pedidos.id_cliente = ad_clientes.id_cliente
		LEFT JOIN ad_formas_pago ON ad_pedidos_detalle_pagos.id_forma_pago = ad_formas_pago.id_forma_pago
		WHERE (ad_pedidos_detalle_pagos.id_deposito_bancario = '' OR ad_pedidos_detalle_pagos.id_deposito_bancario IS NULL OR ad_pedidos_detalle_pagos.id_deposito_bancario = 0) AND ad_pedidos_detalle_pagos.confirmado <> 2" . $where;
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);

echo $smarty->fetch('especiales/respuestaTablaPedidos.tpl');


?>


