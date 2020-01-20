<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$cliente = $_POST['idCliente'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];
$requiereFactura = $_POST['reqFac'];
$id_sucursal = $_POST['id_sucursal'];


$dia = substr($fecini, 0, 2);
$mes = substr($fecini, 3, 2);
$anio = substr($fecini, -4);

$fecha_inicial = $anio . "-" . $mes . "-" . $dia;

$dia = substr($fecfin, 0, 2);
$mes = substr($fecfin, 3, 2);
$anio = substr($fecfin, -4);

$fecha_final = $anio . "-" . $mes . "-" . $dia;

$where = "";


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
		
if($requiereFactura=='si')
		$where .= " AND ad_pedidos.requiere_factura = 1 ";		
else
		$where .= " AND ad_pedidos.requiere_factura = 0 ";		
		
		
if($id_sucursal !=0)
{
		$where .= " AND  ad_pedidos.id_sucursal_alta = '".$id_sucursal."' ";		
}

$sql = "SELECT ad_pedidos.id_pedido AS id_pedido,
			 CONCAT(ad_clientes.nombre, ' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno), ' ', if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno)) AS cliente, 
			 na_sucursales.nombre AS sucursal, 
			DATE_FORMAT(ad_pedidos.fecha_alta,'%d/%m/%Y') AS fecha_pedido, 
			CONCAT('$', FORMAT(ad_pedidos.total, 2)) AS total, 
			ad_pedidos.id_control_pedido AS id_control_pedido, 
			CONCAT('$', FORMAT(ad_pedidos.total_productos, 2)) AS total_productos , 
			CONCAT('$', FORMAT(ad_pedidos.total_fletes, 2)) AS total_servicios,
			 ad_pedidos.factura_servicios AS facturar_servicios,
			SUM(na_pedidos_detalle_pagos.monto) AS pagos, 
			ad_pedidos.total AS total_pedido,
			ad_pedidos.id_sucursal_alta,
			ad_pedidos.id_cliente,
			'".$rooturl ."' as url 
		FROM ad_pedidos
		LEFT JOIN ad_clientes ON ad_pedidos.id_cliente = ad_clientes.id_cliente
		LEFT JOIN na_sucursales ON ad_pedidos.id_sucursal_alta= na_sucursales.id_sucursal
		LEFT JOIN na_pedidos_detalle_pagos ON ad_pedidos.id_control_pedido = na_pedidos_detalle_pagos.id_control_pedido 
		WHERE id_control_factura IS NULL AND na_pedidos_detalle_pagos.confirmado <> 2" . $where . " GROUP BY  na_sucursales.nombre ,id_pedido  HAVING pagos >= total_pedido";
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();

$sqlPago = "SELECT id_forma_pago_sat, descripcion FROM scfdi_formas_de_pago_sat WHERE activo = 1";
$datosPagos = new consultarTabla($sqlPago);
$pagos = $datosPagos -> obtenerArregloRegistros();

$sqlServicios = "SELECT id_si_no, nombre FROM sys_si_no WHERE id_si_no in (1,2)";
$datosServicios = new consultarTabla($sqlServicios);
$servicios = $datosServicios -> obtenerRegistros();

foreach($servicios as $opciones){
		$si_no_id[] = $opciones -> id_si_no;
		$si_no_nombre[] = $opciones -> nombre;
		}

$smarty->assign('si_no_id', $si_no_id);
$smarty->assign('si_no_nombre', $si_no_nombre);
$smarty -> assign("pagos", $pagos);
$smarty -> assign("filas", $result);

echo $smarty->fetch('especiales/respuestaTablaFacturasPedidos.tpl');


?>
