<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");


$pedido = $_POST['pedido'];
$nomPedido = $_POST['nomPedido'];
$almacen = $_POST['almacen'];
$movimiento = $_POST['movimiento'];
$productos = json_decode($_POST['productos']);

//Consulta para obtener el id_movimiento de la tabla de almacenes
$sqlM = "SELECT IF(MAX(id_movimiento) IS NULL,1,MAX(id_movimiento)+1) AS movimiento 
		FROM na_movimientos_almacen 
		WHERE id_almacen = '" . $almacen . "' AND id_tipo_movimiento=2";
$datosM = new consultarTabla($sqlM);
$resultM = $datosM -> obtenerLineaRegistro();


//Insertamos encabezado de movimiento del almacen del tipo entrada por devolucion de pedido
$devolucion['id_movimiento'] = $resultM['movimiento'];
$devolucion['id_tipo_movimiento'] = 1;
$devolucion['id_subtipo_movimiento'] = 70009;
$devolucion['id_almacen'] = $almacen;
$devolucion['fecha_movimiento'] = date('Y-m-d');
$devolucion['hora_movimiento'] = date("H:i:s");
$devolucion['id_usuario'] = $_SESSION["USR"]->userid;
$devolucion['id_usuario_valido'] = $_SESSION["USR"]->userid;
$devolucion['id_control_pedido'] = $pedido;
$devolucion['id_pedido'] = $nomPedido;
$devolucion['no_modificable'] = 1;
$devolucion['activo'] = 1;

accionesMysql($devolucion, 'na_movimientos_almacen', 'Inserta');

$ultimo_mov = mysql_insert_id();

//Iteramos entre los productos que se seleccionaron
foreach($productos as $registros){
		
		//Insertamos el detalle del movimiento del almacen del tipo entrada por devolucion de pedido
		
		$detalleMov['id_control_movimiento'] = $ultimo_mov;
		$detalleMov['id_producto'] = $registros -> producto;
		
		//Consulta para obtener el LOTE MAXIMO para devolver el producto 
		$sqlLote = "SELECT MAX(id_lote) AS lote FROM na_movimientos_almacen_detalle WHERE id_producto = " . $registros -> producto . " AND id_control_movimiento = " . $movimiento;
		$datosLote = new consultarTabla($sqlLote);
		$resultLote = $datosLote -> obtenerLineaRegistro();
		
		$detalleMov['id_lote'] = $resultLote['lote'];
		$detalleMov['cantidad'] = $registros -> cantidad;
		$detalleMov['signo'] = 1;
		$detalleMov['id_tipo_documento_interno'] = 2;
		$detalleMov['id_detalle_documento_interno'] = $registros -> documento_interno;
		
		accionesMysql($detalleMov, 'na_movimientos_almacen_detalle', 'Inserta');
		}
	
echo "exito|" . $ultimo_mov . "|" . ROOTURL;



?>