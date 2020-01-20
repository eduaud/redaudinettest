<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");
	
	
	$pagos = $_GET['pagos'];
	$reciboGet = $_GET['recibo'];
	$pedidoGet = $_GET['pedido'];
	
	Global $logo;
	Global $numero_recibo;
	
	if($reciboGet == 100000000)
			$where = "id_pedido_detalle_pago IN (" . $pagos . ")";
	else
			$where = "id_recibo = " . $reciboGet . " AND id_control_pedido = " . $pedidoGet;

	
$sql = "SELECT id_pedido_detalle_pago, id_recibo, DATE_FORMAT(na_pedidos_detalle_pagos.fecha,'%d/%m/%Y') AS fecha_pago, na_formas_pago.nombre AS forma_pago, na_sucursales.nombre AS sucursal,
		IF(na_terminales_bancarias.nombre = '' OR na_terminales_bancarias.nombre IS NULL, '', na_terminales_bancarias.nombre) AS terminal_bancaria,
		IF(numero_documento = '' OR numero_documento IS NULL, '', numero_documento) AS numero_documento,
		IF(numero_aprobacion = '' OR numero_aprobacion IS NULL, '', numero_aprobacion) AS numero_aprobacion,
		CONCAT('$', FORMAT(na_pedidos_detalle_pagos.monto, 2)) AS monto, sys_si_no.nombre AS confirmado, 
		IF(na_pedidos_detalle_pagos.observaciones = '' OR na_pedidos_detalle_pagos.observaciones IS NULL, '', na_pedidos_detalle_pagos.observaciones) AS observaciones,
		id_control_pedido, na_pedidos_detalle_pagos.confirmado AS confirmacion, na_sucursales.prefijo AS prefijo, na_pedidos_detalle_pagos.id_sucursal_pago AS sucursal_pago,
		
		CONCAT(na_sucursales.calle, ' No. ' , na_sucursales.numero_exterior, IF(na_sucursales.numero_interior <> '', CONCAT(' Int. ', na_sucursales.numero_interior, ', '), ' '), ' Col. ', na_sucursales.colonia, ', ',  na_sucursales.delegacion_municipio, ', ', na_estados.nombre, ', C.P. ', na_sucursales.codigo_postal) AS direccion,

 if(na_formas_pago.requiere_banco =1,if(requiere_registro_terminal=1,
    (select na_bancos.nombre from na_terminales_bancarias as aux left join na_bancos on aux.id_banco=na_bancos.id_banco where aux.id_terminal_bancaria=na_terminales_bancarias.id_terminal_bancaria)
    ,( SELECT nombre FROM na_bancos as aux2 where aux2.id_banco= na_pedidos_detalle_pagos.id_banco)

    ) ,'') as banco

		FROM na_pedidos_detalle_pagos
		LEFT JOIN na_formas_pago USING(id_forma_pago)
		LEFT JOIN na_sucursales ON na_pedidos_detalle_pagos.id_sucursal_pago = na_sucursales.id_sucursal
		LEFT JOIN sys_si_no ON na_pedidos_detalle_pagos.confirmado = sys_si_no.id_si_no
		LEFT JOIN na_terminales_bancarias USING(id_terminal_bancaria)
		LEFT JOIN na_estados ON na_sucursales.id_estado = na_estados.id_estado
		WHERE " . $where. " AND na_pedidos_detalle_pagos.confirmado <> 2 AND activoDetPagos = 1 ORDER BY na_pedidos_detalle_pagos.fecha DESC";
	

$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();
$contador = $datos -> cuentaRegistros();

if($contador == 0){
		echo "No puedes imprimir pagos no confirmados";
		die();
		}

foreach($result as $datosP){
		$pedido = $datosP -> id_control_pedido;
		$recibo = $datosP -> id_recibo;
		$prefijo = $datosP -> prefijo;
		$sucursal_pago = $datosP -> sucursal_pago;
		$direccion = $datosP -> direccion;
		}

if($recibo == 0){
		$sqlRec = "SELECT MAX(id_recibo) AS recibo 
					FROM na_pedidos_detalle_pagos
					WHERE id_sucursal_pago = " . $sucursal_pago;
		$datosRec = new consultarTabla($sqlRec);
		$resultRec = $datosRec -> obtenerLineaRegistro();
		
		$reciboNum = $resultRec['recibo'] = 0 ? 1 : $resultRec['recibo'] + 1;
		
		$actualiza = "UPDATE na_pedidos_detalle_pagos SET id_recibo = " . $reciboNum . " WHERE " . $where . " AND na_pedidos_detalle_pagos.confirmado <> 2 AND activoDetPagos = 1";
		mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
		$numero_recibo = $reciboNum;
		}
else{
		$numero_recibo = $recibo;
		}
		


$sqlPedido = "SELECT id_pedido, CONCAT(na_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno is null,'',apellido_materno)) as cliente,
				logo_sucursales
				FROM ad_pedidos
				LEFT JOIN na_clientes USING(id_cliente)
				LEFT JOIN na_sucursales ON ad_pedidos.id_sucursal_alta = na_sucursales.id_sucursal
				WHERE id_control_pedido = " . $pedido;

$datosPedido = new consultarTabla($sqlPedido);
$resultP = $datosPedido -> obtenerLineaRegistro();

$numero_recibo = $prefijo . $numero_recibo;
$logo = $resultP['logo_sucursales'];

include("reciboPagos_pdf.php");


	
?>