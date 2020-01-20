<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
include("../../code/especiales/funcionesCosteoLote.php");
include("../../code/especiales/funcionesNasser.php");

$sql = "SELECT * FROM ad_ird_surtir WHERE activo = '1';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();	
$numeroDeRegistros = count($result);
//Verifica que se haya importado el archivo <-->
if($numeroDeRegistros<=0){
	echo json_encode("Debe importar o capturar los IRDS");
}else{
// PARA PEDIDOS --->
$arrayDetIdDetPedidos = $_GET['arrayDetIdDetPedidos'];
$arrayDetIdProductos = $_GET['arrayDetIdProductos'];
$arrayDetCantidad = $_GET['arrayDetCantidad'];
$arrayListadoPedidos = $_GET['arrayListadoPedidos'];
$arrayControlPedidos = $_GET['arrayControlPedidos'];
$arrayAlmacen = $_GET["arrayAlmacen"];
$array_id_detalle_pedidos = array();

$id_usuario = $_SESSION["USR"]->userid;
$sesion = $_SESSION['sesion_unica'];

for($i=0;$i<count($arrayDetIdDetPedidos);$i++){
	$id_detalle_pedido = $arrayDetIdDetPedidos[$i];				//traemos el id del detalle del pedido
	$id_producto = $arrayDetIdProductos[$i];					//traemos el id del producto
	$cantidaASurtir = $arrayDetCantidad[$i];					//traemos la cantidad para surtir
	$id_control_pedido = $arrayControlPedidos[$i];
	$id_pedido = $arrayListadoPedidos[$i];
	$id_almacen = $arrayAlmacen[$i];
	$cantidad_salir=$cantidaASurtir;
	$lote_salir=0;
	//Se verifica si el producto es un IRD -->
	$requiere_numero_serie = "";
	$sql = "SELECT requiere_numero_serie FROM cl_productos_servicios WHERE id_producto_servicio = '".$id_producto."' AND requiere_numero_serie = '1' AND ird_generico = '1' AND activo = '1';";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerArregloRegistros();	
	$numeroDeRegistros = count($result);
	//Se verifica si el producto es un IRD <--
	if($numeroDeRegistros>0){
		$sql = "SELECT * FROM ad_ird_surtir WHERE id_usuario = '".$id_usuario."' AND sesion = '".$sesion."' AND activo = '1';";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerArregloRegistros();	
		$numeroDeRegistros = count($result);
		foreach($result as $opcion){ 
			//Se obtiene el lote del al que pertenece el numero de serie y el nuevo id_producto -->
			$sql = "SELECT id_lote, id_producto FROM ad_movimientos_almacen_detalle WHERE numero_serie = '".$opcion[1]."' AND signo = '1';";
			$datosMAD = new consultarTabla($sql);
			$resultMAD = $datosMAD -> obtenerArregloRegistros();	
			foreach($resultMAD as $opcionMAD){ $lote = $opcionMAD[0]; $id_producto_nuevo = $opcionMAD[1]; }
			//Se obtiene el lote del al que pertenece el numero de serie y el nuevo id_producto <--
			$strInsert = "INSERT INTO ad_movimientos_almacen_detalle (";
			$strInsert .= " id_control_movimiento,";
			$strInsert .= " id_producto,";
			$strInsert .= " id_lote,";
			$strInsert .= " cantidad,";
			$strInsert .= " id_tipo_documento_interno,";
			$strInsert .= " id_detalle_documento_interno,";
			$strInsert .= " signo,";
			$strInsert .= " id_control_pedido,";
			$strInsert .= " id_pedido,";
			$strInsert .= " numero_serie,";
			$strInsert .= " id_producto_origen";
			$strInsert .= ")";
			$strInsert .= " VALUES(";
			$strInsert .= " '0',";
			$strInsert .= " '".$id_producto_nuevo."',";
			$strInsert .= " '".$lote."',";
			$strInsert .= " '1',";
			$strInsert .= " '1',";
			$strInsert .= " '1',";
			$strInsert .= " -1,";
			$strInsert .= " '".$id_control_pedido."',";
			$strInsert .= " '".$id_pedido."',";
			$strInsert .= " '".$opcion[1]."',";
			$strInsert .= " '".$id_producto."'";
			$strInsert .= " );";	
			mysql_query($strInsert) or die("Error en consulta $strInsert \nDescripcion:".mysql_error());	
			$id_detalle = mysql_insert_id();
			array_push($array_id_detalle_pedidos, $id_detalle);
		}	
	}else{
		//realizamos la cadena de inserts en la base de datos
		$strInsert = "INSERT INTO ad_movimientos_almacen_detalle (";
		$strInsert .= " id_control_movimiento,";
		$strInsert .= " id_producto,";
		$strInsert .= " id_lote,";
		$strInsert .= " cantidad,";
		$strInsert .= " id_tipo_documento_interno,";
		$strInsert .= " id_detalle_documento_interno,";
		$strInsert .= " signo,";
		$strInsert .= " id_control_pedido,";
		$strInsert .= " id_pedido)";
		$strInsert .= " VALUES(";
		$strInsert .= " '0',";
		$strInsert .= " '".$id_producto."',";
		$strInsert .= " '".$lote_salir."',";
		$strInsert .= " '".$cantidad_salir."',";
		$strInsert .= " '1',";
		$strInsert .= " '1',";
		$strInsert .= " -1,";
		$strInsert .= " '".$id_control_pedido."',";
		$strInsert .= " '".$id_pedido."'";
		$strInsert .= " );";	
		mysql_query($strInsert) or die("Error en consulta $strInsert \nDescripcion:".mysql_error());	
		$id_detalle = mysql_insert_id();
		array_push($array_id_detalle_pedidos, $id_detalle);
	}
	//traemos los numeros de lotes de los cuales se sacara los productos, los traemos en el array
}
$cadena_ids_detalles = "";
for($j=0; $j<count($array_id_detalle_pedidos); $j++){$cadena_ids_detalles = $cadena_ids_detalles.','.$array_id_detalle_pedidos[$j];}
$cadena_ids_detalles = substr($cadena_ids_detalles,1, strlen($cadena_ids_detalles));

if($cadena_ids_detalles!=""){
	//realizamos el insert en la tabla padre
	$strInserPadre = "INSERT INTO ad_movimientos_almacen";
	$strInserPadre .= " (";
	$strInserPadre .= " id_movimiento,";
	$strInserPadre .= " id_tipo_movimiento,";
	$strInserPadre .= " id_subtipo_movimiento,";
	$strInserPadre .= " id_almacen,";
	$strInserPadre .= " fecha_movimiento,";
	$strInserPadre .= " hora_movimiento,";
	$strInserPadre .= " id_control_pedido,";
	$strInserPadre .= " id_pedido,";
	$strInserPadre .= " no_modificable," ;
	$strInserPadre .= " id_usuario,";
	$strInserPadre .= " id_usuario_valido,";
	$strInserPadre .= " activo";
	$strInserPadre .= " )";
	$strInserPadre .= " VALUES (";
	$strInserPadre .= " '1',";
	$strInserPadre .= " 2,";
	$strInserPadre .= " 70014,";
	$strInserPadre .= " '".$id_almacen."',";
	$strInserPadre .= " NOW(),";
	$strInserPadre .= " TIME(NOW()),";
	$strInserPadre .= " '0',";
	$strInserPadre .= " '0',";
	$strInserPadre .= " 0,";
	$strInserPadre .= " '".$_SESSION["USR"]->userid."',";
	$strInserPadre .= " '".$_SESSION["USR"]->userid."',";
	$strInserPadre .= " 0";
	$strInserPadre .= " );";
	mysql_query($strInserPadre) or die("Error en consulta $strInserPadre\nDescripcion:".mysql_error());	
	$id_control_movimiento = mysql_insert_id();
	//PONER A EN EL DETALLE EL ID_CONTROL_MOVIMIENTO -->
	$update = "UPDATE ad_movimientos_almacen_detalle SET id_control_movimiento = '".$id_control_movimiento."' WHERE id_detalle IN (".$cadena_ids_detalles.");";
	mysql_query($update) or die("Error en consulta $update\nDescripcion:".mysql_error());	
	//PONER A EN EL DETALLE EL ID_CONTROL_MOVIMIENTO <--
	//cambiamos los estatus en los apartados
	$strUPDATE = "UPDATE ad_movimientos_almacen SET activo = '1', no_modificable = '1' WHERE id_control_movimiento = '".$id_control_movimiento."';";
	mysql_query($strUPDATE) or die("Error en consulta $arrayDetInsertsDetalle[$i] \nDescripcion:".mysql_error());	
}
// PARA PEDIDOS <---

// PARA SOLICITUD DE MATERIAL --->
$arrayDetIdDetPedidosSM = $_GET['arrayDetIdDetPedidosSM'];
$arrayDetIdProductosSM = $_GET['arrayDetIdProductosSM'];
$arrayDetCantidadSM = $_GET['arrayDetCantidadSM'];
$arrayListadoPedidosSM = $_GET['arrayListadoPedidosSM'];
$arrayControlPedidosSM = $_GET['arrayControlPedidosSM'];
$arrayAlmacenSM = $_GET["arrayAlmacenSM"];
$array_id_detalle_solicitud_material = array();

for($i=0;$i<count($arrayDetIdDetPedidosSM);$i++){
	$id_detalle_pedido = $arrayDetIdDetPedidosSM[$i];				//traemos el id del detalle del pedido
	$id_producto = $arrayDetIdProductosSM[$i];						//traemos el id del producto
	$cantidaASurtir = $arrayDetCantidadSM[$i];						//traemos la cantidad para surtir
	$id_control_pedido = $arrayControlPedidosSM[$i];
	$id_pedido = $arrayListadoPedidosSM[$i];
	$id_almacen = $arrayAlmacenSM[$i];
	$cantidad_salir=$cantidaASurtir;
	$lote_salir=0;
	//Se verifica si el producto es un IRD -->
	$requiere_numero_serie = "";
	$sql = "SELECT requiere_numero_serie FROM cl_productos_servicios WHERE id_producto_servicio = '".$id_producto."' AND requiere_numero_serie = '1' AND ird_generico = '1' AND activo = '1';";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerArregloRegistros();	
	$numeroDeRegistros = count($result);
	//Se verifica si el producto es un IRD <--
	if($numeroDeRegistros>0){
		$sql = "SELECT * FROM ad_ird_surtir WHERE id_usuario = '".$id_usuario."' AND sesion = '".$sesion."' AND activo = '1';";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerArregloRegistros();	
		$numeroDeRegistros = count($result);

		foreach($result as $opcion){ 
			//Se obtiene el lote del al que pertenece el numero de serie y el nuevo id_producto -->
			$sql = "SELECT id_lote, id_producto FROM ad_movimientos_almacen_detalle WHERE numero_serie = '".$opcion[1]."' AND signo = '1';";
			$datosMAD = new consultarTabla($sql);
			$resultMAD = $datosMAD -> obtenerArregloRegistros();	
			foreach($resultMAD as $opcionMAD){ $lote = $opcionMAD[0]; $id_producto_nuevo = $opcionMAD[1]; }
			//Se obtiene el lote del al que pertenece el numero de serie y el nuevo id_producto <--
			$strInsert = "INSERT INTO ad_movimientos_almacen_detalle (";
			$strInsert .= " id_control_movimiento,";
			$strInsert .= " id_producto,";
			$strInsert .= " id_lote,";
			$strInsert .= " cantidad,";
			$strInsert .= " id_tipo_documento_interno,";
			$strInsert .= " id_detalle_documento_interno,";
			$strInsert .= " signo,";
			$strInsert .= " id_control_pedido,";
			$strInsert .= " id_pedido,";
			$strInsert .= " numero_serie,";
			$strInsert .= " id_producto_origen";
			$strInsert .= ")";
			$strInsert .= " VALUES(";
			$strInsert .= " '0',";
			$strInsert .= " '".$id_producto_nuevo."',";
			$strInsert .= " '".$lote."',";
			$strInsert .= " '1',";
			$strInsert .= " '1',";
			$strInsert .= " '1',";
			$strInsert .= " -1,";
			$strInsert .= " '".$id_control_pedido."',";
			$strInsert .= " '".$id_pedido."',";
			$strInsert .= " '".$opcion[1]."',";
			$strInsert .= " '".$id_producto."'";
			$strInsert .= " );";	
			mysql_query($strInsert) or die("Error en consulta $strInsert \nDescripcion:".mysql_error());	
			$id_detalle = mysql_insert_id();
			array_push($array_id_detalle_pedidos, $id_detalle);
		}
	}else{
		//realizamos la cadena de inserts en la base de datos
		$strInsert = "INSERT INTO ad_movimientos_almacen_detalle (";
		$strInsert .= " id_control_movimiento,";
		$strInsert .= " id_producto,";
		$strInsert .= " id_lote,";
		$strInsert .= " cantidad,";
		$strInsert .= " id_tipo_documento_interno,";
		$strInsert .= " id_detalle_documento_interno,";
		$strInsert .= " signo,";
		$strInsert .= " id_control_pedido,";
		$strInsert .= " id_pedido)";
		$strInsert .= " VALUES(";
		$strInsert .= " '0',";
		$strInsert .= " '".$id_producto."',";
		$strInsert .= " '".$lote_salir."',";
		$strInsert .= " '".$cantidad_salir."',";
		$strInsert .= " '1',";
		$strInsert .= " '1',";
		$strInsert .= " -1,";
		$strInsert .= " '".$id_control_pedido."',";
		$strInsert .= " '".$id_pedido."'";
		$strInsert .= " );";	
		mysql_query($strInsert) or die("Error en consulta $strInsert \nDescripcion:".mysql_error());	
		$id_detalle = mysql_insert_id();
		array_push($array_id_detalle_solicitud_material, $id_detalle);
		//traemos los numeros de lotes de los cuales se sacara los productos, los traemos en el array	
	}
}
$cadena_ids_detalles = "";
for($j=0; $j<count($array_id_detalle_solicitud_material); $j++){$cadena_ids_detalles = $cadena_ids_detalles.','.$array_id_detalle_solicitud_material[$j];}
$cadena_ids_detalles = substr($cadena_ids_detalles,1, strlen($cadena_ids_detalles));

if($cadena_ids_detalles!=""){
	//realizamos el insert en la tabla padre
	$strInserPadre = "INSERT INTO ad_movimientos_almacen";
	$strInserPadre .= " (";
	$strInserPadre .= " id_movimiento,";
	$strInserPadre .= " id_tipo_movimiento,";
	$strInserPadre .= " id_subtipo_movimiento,";
	$strInserPadre .= " id_almacen,";
	$strInserPadre .= " fecha_movimiento,";
	$strInserPadre .= " hora_movimiento,";
	$strInserPadre .= " id_control_pedido,";
	$strInserPadre .= " id_pedido,";
	$strInserPadre .= " no_modificable," ;
	$strInserPadre .= " id_usuario,";
	$strInserPadre .= " id_usuario_valido,";
	$strInserPadre .= " activo";
	$strInserPadre .= " )";
	$strInserPadre .= " VALUES (";
	$strInserPadre .= " '1',";
	$strInserPadre .= " 2,";
	$strInserPadre .= " 70014,";
	$strInserPadre .= " '".$id_almacen."',";
	$strInserPadre .= " NOW(),";
	$strInserPadre .= " TIME(NOW()),";
	$strInserPadre .= " '0',";
	$strInserPadre .= " '0',";
	$strInserPadre .= " 0,";
	$strInserPadre .= " '".$_SESSION["USR"]->userid."',";
	$strInserPadre .= " '".$_SESSION["USR"]->userid."',";
	$strInserPadre .= " 0";
	$strInserPadre .= " );";
	mysql_query($strInserPadre) or die("Error en consulta $strInserPadre\nDescripcion:".mysql_error());	
	$id_control_movimiento = mysql_insert_id();
	//PONER A EN EL DETALLE EL ID_CONTROL_MOVIMIENTO -->
	$update = "UPDATE ad_movimientos_almacen_detalle SET id_control_movimiento = '".$id_control_movimiento."' WHERE id_detalle IN (".$cadena_ids_detalles.");";
	mysql_query($update) or die("Error en consulta $update\nDescripcion:".mysql_error());	
	//PONER A EN EL DETALLE EL ID_CONTROL_MOVIMIENTO <--
	//cambiamos los estatus en los apartados
	$strUPDATE = "UPDATE ad_movimientos_almacen SET activo = '1', no_modificable = '1' WHERE id_control_movimiento = '".$id_control_movimiento."';";
	mysql_query($strUPDATE) or die("Error en consulta $arrayDetInsertsDetalle[$i] \nDescripcion:".mysql_error());	
}
// PARA SOLICITUD DE MATERIAL <---
//Se pone en estatus 0 el campo "activo" de la tabla "ad_ird_surtir" -->
$id_usuario = $_SESSION["USR"]->userid;
$sesion = $_SESSION['sesion_unica'];
$update = "UPDATE ad_ird_surtir SET activo = '0' WHERE id_usuario = '".$id_usuario."' AND sesion = '".$sesion."';";
mysql_query($update) or die("Error: " . mysql_error());
//Se pone en estatus 0 el campo "activo" de la tabla "ad_ird_surtir" <--
//echo json_encode($sql);


$smarty -> display("especiales/importarNumeroSerieParaSalidaDeAlmacen.tpl");
}
//echo json_encode('Las salidas de almacen se generaron correctamente');
?>