<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$pedidos = $_POST['pedidos'];
$vendedor = $_POST['vendedor'];

$pedidosDatos = explode(",", $pedidos);
$cuentaPedido = count(array_filter($pedidosDatos));

$sqlPar = "SELECT porcentaje_comision_1 AS comision FROM sys_parametros_nasser";
$datosPar = new consultarTabla($sqlPar);
$resultPar = $datosPar -> obtenerLineaRegistro();

$sqlMonto = "SELECT SUM(((ad_pedidos.total_productos * " . $resultPar['comision'] . ")/100)) AS monto_pagado FROM ad_pedidos WHERE id_control_pedido IN (" . $pedidos . ")";
$datosMonto = new consultarTabla($sqlMonto);
$resultMonto = $datosMonto -> obtenerLineaRegistro();

$comision['id_vendedor'] = $vendedor;
$comision['fecha'] = date("Y-m-d");
$comision['hora'] = date("H-i-s");
$comision['monto_pagado'] = $resultMonto['monto_pagado'];
$comision['id_usuario_genero'] = $_SESSION["USR"]->userid;
$comision['activo'] = 1;

accionesMysql($comision, 'na_comisiones', 'Inserta');
$ultimo_id = mysql_insert_id();

if($cuentaPedido > 0){
		for($i=0; $i<$cuentaPedido; $i++){
				
				$sqlComDet = "SELECT total_productos, ((ad_pedidos.total_productos * " . $resultPar['comision'] . ")/100) AS comision_detalle
								FROM ad_pedidos
								WHERE id_control_pedido = " . $pedidosDatos[$i];
				
				$datosDet = new consultarTabla($sqlComDet);
				$resultDet = $datosDet -> obtenerLineaRegistro();
				
				$comDet['id_comision'] = $ultimo_id;
				$comDet['id_control_pedido'] = $pedidosDatos[$i];
				$comDet['monto_comision'] = $resultDet['comision_detalle'];
				$comDet['monto_pedido'] = $resultDet['total_productos'];
				
				accionesMysql($comDet, 'na_comisiones_detalles', 'Inserta');
				
				$actualiza1 = "UPDATE ad_pedidos SET id_comision = " . $ultimo_id . " WHERE id_control_pedido = " . $pedidosDatos[$i];
				mysql_query($actualiza1) or die("Error en consulta:<br> $actualiza1 <br>" . mysql_error());	
				}
		}
$ultimo_id = 1;		
echo $ultimo_id . "|Operacion realizada con exito";

?>