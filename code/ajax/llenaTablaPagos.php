<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$pedido = $_POST['id'];

//Consulta para llenar la tabla de detalle de pedidos

$sql = "SELECT id_pedido_detalle_pago, id_recibo, DATE_FORMAT(na_pedidos_detalle_pagos.fecha,'%d/%m/%Y') AS fecha_pago, na_formas_pago.nombre AS forma_pago, na_sucursales.nombre AS sucursal,
		IF(na_terminales_bancarias.nombre = '' OR na_terminales_bancarias.nombre IS NULL, '&nbsp;', na_terminales_bancarias.nombre) AS terminal_bancaria,
		IF(numero_documento = '' OR numero_documento IS NULL, '&nbsp;', numero_documento) AS numero_documento,
		IF(numero_aprobacion = '' OR numero_aprobacion IS NULL, '&nbsp;', numero_aprobacion) AS numero_aprobacion,
		CONCAT('$', FORMAT(na_pedidos_detalle_pagos.monto, 2)) AS monto, sys_si_no.nombre AS confirmado, 
		IF(na_pedidos_detalle_pagos.observaciones = '' OR na_pedidos_detalle_pagos.observaciones IS NULL, '&nbsp;', na_pedidos_detalle_pagos.observaciones) AS observaciones,
		id_control_pedido, na_pedidos_detalle_pagos.confirmado AS confirmacion
		FROM na_pedidos_detalle_pagos
		LEFT JOIN na_formas_pago USING(id_forma_pago)
		LEFT JOIN na_sucursales ON na_pedidos_detalle_pagos.id_sucursal_pago = na_sucursales.id_sucursal
		LEFT JOIN sys_si_no ON na_pedidos_detalle_pagos.confirmado = sys_si_no.id_si_no
		LEFT JOIN na_terminales_bancarias USING(id_terminal_bancaria)
		WHERE id_control_pedido = " . $pedido . " AND activoDetPagos = 1 ORDER BY id_recibo, na_pedidos_detalle_pagos.fecha DESC";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result); //Asignamos el array que pintara la tabla

//Consulta para obtener el rowspan en base al id_recibo 
$sqlRecibo = "SELECT id_recibo, COUNT(*) AS repite FROM na_pedidos_detalle_pagos WHERE id_control_pedido = " . $pedido . " AND activoDetPagos = 1 GROUP BY id_recibo";
$datosRecibo = new consultarTabla($sqlRecibo);
$resultRecibo = $datosRecibo -> obtenerArregloRegistros();

$resultRecibo[0][2] = 1; //Agregamos al array de resultado donde empezara el rowspan

$suma = 1; //Variable que llevara la suma de en cada fila habra un corte para la siguiente imagen de imprimir
$tamArreglo= count($resultRecibo);

for($i=0;$i<$tamArreglo;$i++){
		$suma += $resultRecibo[$i][1]; // le sumamos a la variable el valor de las veces que se repite el id_recibo
		$resultRecibo[$i+1][2] = $suma; // Le agregamos la suma a la ultima posicion del array
		}
array_pop($resultRecibo); //Quitamos el elemento sobrante del array

$smarty -> assign("recibos", $resultRecibo); //Asignamos el array para el template

echo $smarty->fetch('especiales/respuestaTablaPagos.tpl');
