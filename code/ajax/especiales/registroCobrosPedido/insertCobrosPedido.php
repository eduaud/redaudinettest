<?php
include("../../../../conect.php");
include("../../../../code/general/funciones.php");
include("../../../../consultaBase.php");
// Autorizacion Credito Cobranza

$ACC=$_POST['autorizacion_credito_cobranza'];
// autorizacion credito cobranza

if($ACC==0){$confirmado=3;}
if($ACC==1){$confirmado=2;}

$sql="INSERT INTO na_pedidos_detalle_pagos 
				( id_pedido_detalle_pago,
				  id_control_pedido,
				  fecha,
				  id_forma_pago,
				  id_terminal_bancaria,
				  numero_documento,
				  numero_aprobacion,
				  id_banco,
				  monto,
				  confirmado,
					cancelado,
				  id_usuario_pago,
				  id_sucursal_pago,
				  fecha_hora_pago,
				  activoDetPagos)
		  VALUES( null,
		  		  ".$_POST['id_control_pedido'].",
				  NOW(),
				  ".$_POST['id_forma_pago'].",
				  ".$_POST['id_terminal_bancaria'].",
				  '".$_POST['numero_documento']."',
				  '',
				  ".$_POST['banco'].",
				  '".$_POST['monto']."',
				  $confirmado,0,
				  ".$_POST['id_usuario'].",
				  ".$_POST['id_sucursal'].",
				  NOW(), 1);";
							
$result = mysql_query($sql) or die(mysql_error());
$id_pago = mysql_insert_id();

$sql2 = "SELECT saldo, total_pagos FROM ad_pedidos WHERE id_control_pedido = " . $_POST['id_control_pedido'];
$result = new consultarTabla($sql2);
$datos = $result -> obtenerLineaRegistro();

$saldo = $datos['saldo'] - $_POST['monto'];
$pagos = $datos['total_pagos'] + $_POST['monto'];

$actualiza = "UPDATE ad_pedidos SET total_pagos = " . $pagos . ", saldo = " . $saldo . " WHERE id_control_pedido = " . $_POST['id_control_pedido'];
mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());

$pedidoR = $_POST['id_control_pedido'];
apartaDesapartaProductos($pedidoR);
ingresoCajaChicaPedido($pedidoR, $_POST['id_sucursal']);
				
$sql = "SELECT (total - SUM(na_pedidos_detalle_pagos.monto)) AS saldo_real 
		FROM ad_pedidos
		LEFT JOIN na_pedidos_detalle_pagos USING(id_control_pedido)
		WHERE id_control_pedido = " . $pedidoR . " AND na_pedidos_detalle_pagos.activoDetPagos = 1";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();
if($result['saldo_real'] <= 0){
		$strUpdate = "UPDATE ad_pedidos SET id_estatus_pedido = 1, id_estatus_pago_pedido = 2 WHERE id_control_pedido = " . $pedidoR;
		mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
		}
else{
		$strUpdate = "UPDATE ad_pedidos SET id_estatus_pedido = 10, id_estatus_pago_pedido = 5 WHERE id_control_pedido = " . $pedidoR;
		mysql_query($strUpdate) or die("Error en consulta:<br> $strUpdate <br>" . mysql_error());
		}
		
$mensaje = "Registro Insertado Correctamente";

$exito['pago'] = $id_pago;
$exito['mensaje'] = $mensaje;
$exito['pedido'] = $pedidoR;
echo json_encode($exito);



?>