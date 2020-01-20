<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

//$separado_por_comas = array();
$arreglo_irds = array();
$idSucursal = $_GET['idSucursal'];

$queryIEPSA="SELECT UPPER(cl_importacion_pipeline.t48) IRDS
			FROM cl_importacion_pipeline
			WHERE cl_importacion_pipeline.activo = '1' and cl_importacion_pipeline.t48 IN (
						SELECT cl_control_series.numero_serie 
						FROM cl_control_series
						LEFT JOIN cl_control_series_detalle
						ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie
						WHERE cl_control_series_detalle.activo = 1 AND cl_control_series_detalle.id_estatus = 1
					)
			ORDER BY cl_importacion_pipeline.t48, cl_importacion_pipeline.fecha_carga";
$resultIEPSA=mysql_query($queryIEPSA);

$ie=0;
while($datosIEPSA=mysql_fetch_array($resultIEPSA)){
	$arreglo_irds[$ie]=$datosIEPSA['IRDS'];
	$ie++;
}

//$arreglo_irds = $_GET['arreglo_irds'];
//$separado_por_comas = implode(",", $arreglo_irds);

for($i=0; $i<count($arreglo_irds); $i++){
	//Se debe de actualizar el estatus activo de 1 a 0, indicando que el numero de serie ya no es valido -->
	$update = "UPDATE cl_control_series_detalle SET activo = '0'";
	$update .= " WHERE id_control_serie IN";
	$update .= " (";
	$update .= " SELECT id_control_serie FROM cl_control_series WHERE numero_serie = '".$arreglo_irds[$i]."'";
	$update .= " );";
	mysql_query($update) or die("Error: " . mysql_error());
	//Se debe de actualizar el estatus activo de 1 a 0, indicando que el numero de serie ya no es valido <--

	//Se cambia el estado de activo a 0 en la tabla cl_importacion_pipeline, indicando que ya fue asignado a un ird fisico -->
	$update = "UPDATE cl_importacion_pipeline SET activo = '0' WHERE t48 = '".$arreglo_irds[$i]."';";
	mysql_query($update) or die("Error: " . mysql_error());
	//Se cambia el estado de activo a 0 en la tabla cl_importacion_pipeline, indicando que ya fue asignado a un ird fisico <--

	//Se obtiene el id del almacen no neteable de la plaza -->
	$id_almacen = "0";
	$select = "SELECT ad_almacenes.id_almacen, ad_almacenes.nombre";
	$select .= " FROM ad_almacenes";
	$select .= " LEFT JOIN ad_sucursales_almacenes_detalle";
	$select .= " ON ad_almacenes.id_almacen = ad_sucursales_almacenes_detalle.id_almacen";
	$select .= " WHERE ad_almacenes.neteable = 0";
	$select .= " AND ad_sucursales_almacenes_detalle.id_sucursal = '".$idSucursal."' LIMIT 1;";
	$datosP = new consultarTabla($select);
	$resultP = $datosP -> obtenerRegistros();
	foreach($resultP as $registrosP){	
		$id_almacen_no_netable = $registrosP -> id_almacen;
	}
	//Se obtiene el id del almacen no neteable de la plaza <--

	//Se obtiene el id del almacen neteable de la plaza -->
	$id_almacen = "0";
	$select = "SELECT ad_almacenes.id_almacen, ad_almacenes.nombre";
	$select .= " FROM ad_almacenes";
	$select .= " LEFT JOIN ad_sucursales_almacenes_detalle";
	$select .= " ON ad_almacenes.id_almacen = ad_sucursales_almacenes_detalle.id_almacen";
	$select .= " WHERE ad_almacenes.neteable = 1";
	$select .= " AND ad_sucursales_almacenes_detalle.id_sucursal = '".$idSucursal."' LIMIT 1;";
	$datosP = new consultarTabla($select);
	$resultP = $datosP -> obtenerRegistros();
	foreach($resultP as $registrosP){	
		$id_almacen_netable = $registrosP -> id_almacen;
	}
	//Se obtiene el id del almacen neteable de la plaza <--

	//Se inserta un nuevo registro en la tabla cl_control_series_detalle, con el estatus 2 -->
	$sql = "SELECT id_control_serie FROM cl_control_series WHERE numero_serie = '".$arreglo_irds[$i]."';";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	foreach($result as $registros){
		$id_control_serie = $registros -> id_control_serie;
		$id_estatus = "2";
		$id_plaza = $idSucursal;
		$id_usuario = $_SESSION["USR"]->userid;
		$insert = "INSERT INTO cl_control_series_detalle";
		$insert .= " (id_control_serie, id_estatus, id_almacen, id_plaza, id_usuario, fecha_alta, activo)";
		$insert .= " VALUES (";
		$insert .= "'".$id_control_serie."', ";
		$insert .= "'".$id_estatus."', ";
		$insert .= "'".$id_almacen_netable."', ";
		$insert .= "'".$id_plaza."', ";
		$insert .= "'".$id_usuario."', ";
		$insert .= "now(), ";
		$insert .= "'1'";
		$insert .= " );";
		mysql_query($insert) or die("Error: " . mysql_error());
	}
	//Se inserta un nuevo registro en la tabla cl_control_series_detalle, con el estatus 2 <--

//*************************************** M O V I M I E N T O S   D E   A L M A C E N *************************************************************** -->
	//Obtenemos el consecutivo en los movimientos del almacen de salida, en este caso del almacen no neteable -->
	$sqlMov = "SELECT MAX(id_movimiento) AS movimiento FROM ad_movimientos_almacen where id_almacen = '".$id_almacen_no_netable."'";
	$datosMov = new consultarTabla($sqlMov);
	$resultMov = $datosMov -> obtenerLineaRegistro();
	$contador = $datosMov -> cuentaRegistros();
	if($contador == 0) 	$id_movimiento = 1;
	else $id_movimiento = $resultMov['movimiento'] + 1;
	//Obtenemos el consecutivo en los movimientos del almacen de salida, en este caso del almacen no neteable <--
	
	$chofer = "";
	$placas = "";
	$id_control_orden_compra = "0";
	$id_proveedor = "0";
	//Se obtiene el id_control_orde_compra y el id_proveedor de el ultimo movimiento de almacen -->
	$sql = "SELECT *";
	$sql .= " FROM ad_movimientos_almacen";
	$sql .= " LEFT JOIN ad_movimientos_almacen_detalle";
	$sql .= " ON ad_movimientos_almacen_detalle.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
	$sql .= " WHERE ad_movimientos_almacen_detalle.numero_serie = '".$arreglo_irds[$i]."';";
	$datosMas = new consultarTabla($sql);
	$resultMas = $datosMas -> obtenerRegistros();
	foreach($resultMas as $registrosMas){	
		$id_control_movimiento = $registrosMas->id_control_movimiento;
		$id_detalle = $registrosMas->id_detalle;
		$id_control_orden_compra = $registrosMas->id_control_orden_compra;
		$id_proveedor = $registrosMas->id_proveedor;
	}
	//Se obtiene el id_control_orde_compra y el id_proveedor de el ultimo movimiento de almacen <--
	
	//SE INSERTA EL ENCABEZADO DE LA SALIDA DEL ALMACEN NO NETEABLE -->
	$id_tipo_movimiento = "2"; //SALIDA
	$id_subtipo_movimiento = "70066"; //TRASPASO SALIDA ENTRE ALMACENES -
	$no_modificable = "1";
	$id_usuario = $_SESSION["USR"]->userid;
	$activo = "1";
	$insertE = "INSERT INTO ad_movimientos_almacen (";
	$insertE .= " id_movimiento,";
	$insertE .= " id_tipo_movimiento,";
	$insertE .= " id_subtipo_movimiento,";
	$insertE .= " id_almacen,";
	$insertE .= " fecha_movimiento,";
	$insertE .= " hora_movimiento,";
	$insertE .= " no_modificable,";
	$insertE .= " id_control_orden_compra,";
	$insertE .= " id_proveedor,";
	$insertE .= " id_usuario,";
	$insertE .= " activo,";
	$insertE .= " chofer,";
	$insertE .= " placas";
	$insertE .= " )";
	$insertE .= " VALUES (";
	$insertE .= "'".$id_movimiento."', ";
	$insertE .= "'".$id_tipo_movimiento."', ";
	$insertE .= "'".$id_subtipo_movimiento."', ";
	$insertE .= "'".$id_almacen_no_netable."', ";
	$insertE .= "DATE_FORMAT(now(),'%Y-%m-%d'), ";
	$insertE .= "DATE_FORMAT(now(),'%H:%I:%S'), ";
	$insertE .= "'".$no_modificable."', ";
	$insertE .= "'".$id_control_orden_compra."', ";
	$insertE .= "'".$id_proveedor."', ";
	$insertE .= "'".$id_usuario."', ";
	$insertE .= "'".$activo."', ";
	$insertE .= "'".$chofer."', ";
	$insertE .= "'".$placas."'";
	$insertE .= " );";
	mysql_query($insertE) or die("Error: " . mysql_error());
	$ultimo_insertado_encabezado = mysql_insert_id();
	//SE INSERTA EL ENCABEZADO DE LA SALIDA DEL ALMACEN NO NETEABLE <--
	
	//SE INSERTA EL DETALLE DE LA SALIDA DEL ALMACEN NO NETEBALE -->
	$insertD = " INSERT INTO ad_movimientos_almacen_detalle (";
	$insertD .= " id_control_movimiento, id_producto, id_control_pedido, id_pedido, cantidad_traspaso, cantidad_solicitada, id_lote, cantidad_existencia, cantidad, observaciones,";
	$insertD .= " id_tipo_documento_interno, id_detalle_documento_interno, signo, id_bitacora_ruta, id_apartado, id_costeo_productos, id_costeo_producto_detalle, id_orden_compra_producto_detalle,";
	$insertD .= " campo_1, id_apartado_detalle, id_producto_compuesto, campo_4, numero_serie, id_control_solicitud_material, id_solicitud_material, id_producto_origen";
	$insertD .= " )";
	$insertD .= " SELECT";
	$insertD .= " ad_movimientos_almacen_detalle.id_control_movimiento,";
	$insertD .= " ad_movimientos_almacen_detalle.id_producto,";
	$insertD .= " ad_movimientos_almacen_detalle.id_control_pedido,";
	$insertD .= " ad_movimientos_almacen_detalle.id_pedido,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad_traspaso,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad_solicitada,";
	$insertD .= " ad_movimientos_almacen_detalle.id_lote,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad_existencia,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad,";
	$insertD .= " ad_movimientos_almacen_detalle.observaciones,";
	$insertD .= " ad_movimientos_almacen_detalle.id_tipo_documento_interno,";
	$insertD .= " ad_movimientos_almacen_detalle.id_detalle_documento_interno,";
	$insertD .= " ad_movimientos_almacen_detalle.signo,";
	$insertD .= " ad_movimientos_almacen_detalle.id_bitacora_ruta,";
	$insertD .= " ad_movimientos_almacen_detalle.id_apartado,";
	$insertD .= " ad_movimientos_almacen_detalle.id_costeo_productos,";
	$insertD .= " ad_movimientos_almacen_detalle.id_costeo_producto_detalle,";
	$insertD .= " ad_movimientos_almacen_detalle.id_orden_compra_producto_detalle,";
	$insertD .= " ad_movimientos_almacen_detalle.campo_1,";
	$insertD .= " ad_movimientos_almacen_detalle.id_apartado_detalle,";
	$insertD .= " ad_movimientos_almacen_detalle.id_producto_compuesto,";
	$insertD .= " ad_movimientos_almacen_detalle.campo_4,";
	$insertD .= " ad_movimientos_almacen_detalle.numero_serie,";
	$insertD .= " ad_movimientos_almacen_detalle.id_control_solicitud_material,";
	$insertD .= " ad_movimientos_almacen_detalle.id_solicitud_material,";
	$insertD .= " ad_movimientos_almacen_detalle.id_producto_origen";
	$insertD .= " FROM ad_movimientos_almacen";
	$insertD .= " LEFT JOIN ad_movimientos_almacen_detalle";
	$insertD .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
	$insertD .= " WHERE ad_movimientos_almacen_detalle.id_control_movimiento = '".$id_control_movimiento."'";
	$insertD .= " AND ad_movimientos_almacen_detalle.id_detalle = '".$id_detalle."';";
	mysql_query($insertD) or die("Error: " . mysql_error());
	$ultimo_insertado_detalle = mysql_insert_id();
	
	$updateD = "UPDATE ad_movimientos_almacen_detalle SET id_control_movimiento = '".$ultimo_insertado_encabezado."', cantidad = '1', signo = '-1' WHERE id_detalle = '".$ultimo_insertado_detalle."';";
	mysql_query($updateD) or die("Error: " . mysql_error());
	//SE INSERTA EL DETALLE DE LA SALIDA DEL ALMACEN NO NETEABLE <--
	
	$smarty -> assign("insert", $insertD);
	$smarty -> assign("update", $updateD);
	
	//SE INSERTA EL ENCABEZADO DE LA ENTRADA AL ALMACEN NETEABLE -->
	$id_tipo_movimiento = "1"; //ENTRADA
	$id_subtipo_movimiento = "70006"; //TRASPASO ENTRADA ENTRE ALMACENES +
	$no_modificable = "1";
	$id_usuario = $_SESSION["USR"]->userid;
	$activo = 1;
	$insertE = "INSERT INTO ad_movimientos_almacen (";
	$insertE .= " id_movimiento,";
	$insertE .= " id_tipo_movimiento,";
	$insertE .= " id_subtipo_movimiento,";
	$insertE .= " id_almacen,";
	$insertE .= " fecha_movimiento,";
	$insertE .= " hora_movimiento,";
	$insertE .= " no_modificable,";
	$insertE .= " id_control_orden_compra,";
	$insertE .= " id_proveedor,";
	$insertE .= " id_usuario,";
	$insertE .= " activo,";
	$insertE .= " chofer,";
	$insertE .= " placas";
	$insertE .= " )";
	$insertE .= " VALUES (";
	$insertE .= "'".$id_movimiento."', ";
	$insertE .= "'".$id_tipo_movimiento."', ";
	$insertE .= "'".$id_subtipo_movimiento."', ";
	$insertE .= "'".$id_almacen_netable."', ";
	$insertE .= "DATE_FORMAT(now(),'%Y-%m-%d'), ";
	$insertE .= "DATE_FORMAT(now(),'%H:%I:%S'), ";
	$insertE .= "'".$no_modificable."', ";
	$insertE .= "'".$id_control_orden_compra."', ";
	$insertE .= "'".$id_proveedor."', ";
	$insertE .= "'".$id_usuario."', ";
	$insertE .= "'".$activo."', ";
	$insertE .= "'".$chofer."', ";
	$insertE .= "'".$placas."'";
	$insertE .= " );";
	mysql_query($insertE) or die("Error: " . mysql_error());
	$ultimo_insertado_encabezado = mysql_insert_id();
	//SE INSERTA EL ENCABEZADO DE LA ENTRADA AL ALMACEN NETEABLE <--

	//SE INSERTA EL DETALLE DE LA ENTRADA AL ALMACEN NETEBALE -->
	$insertD = " INSERT INTO ad_movimientos_almacen_detalle (";
	$insertD .= " id_control_movimiento, id_producto, id_control_pedido, id_pedido, cantidad_traspaso, cantidad_solicitada, id_lote, cantidad_existencia, cantidad, observaciones,";
	$insertD .= " id_tipo_documento_interno, id_detalle_documento_interno, signo, id_bitacora_ruta, id_apartado, id_costeo_productos, id_costeo_producto_detalle, id_orden_compra_producto_detalle,";
	$insertD .= " campo_1, id_apartado_detalle, id_producto_compuesto, campo_4, numero_serie, id_control_solicitud_material, id_solicitud_material, id_producto_origen";
	$insertD .= " )";
	$insertD .= " SELECT";
	$insertD .= " ad_movimientos_almacen_detalle.id_control_movimiento,";
	$insertD .= " ad_movimientos_almacen_detalle.id_producto,";
	$insertD .= " ad_movimientos_almacen_detalle.id_control_pedido,";
	$insertD .= " ad_movimientos_almacen_detalle.id_pedido,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad_traspaso,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad_solicitada,";
	$insertD .= " ad_movimientos_almacen_detalle.id_lote,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad_existencia,";
	$insertD .= " ad_movimientos_almacen_detalle.cantidad,";
	$insertD .= " ad_movimientos_almacen_detalle.observaciones,";
	$insertD .= " ad_movimientos_almacen_detalle.id_tipo_documento_interno,";
	$insertD .= " ad_movimientos_almacen_detalle.id_detalle_documento_interno,";
	$insertD .= " ad_movimientos_almacen_detalle.signo,";
	$insertD .= " ad_movimientos_almacen_detalle.id_bitacora_ruta,";
	$insertD .= " ad_movimientos_almacen_detalle.id_apartado,";
	$insertD .= " ad_movimientos_almacen_detalle.id_costeo_productos,";
	$insertD .= " ad_movimientos_almacen_detalle.id_costeo_producto_detalle,";
	$insertD .= " ad_movimientos_almacen_detalle.id_orden_compra_producto_detalle,";
	$insertD .= " ad_movimientos_almacen_detalle.campo_1,";
	$insertD .= " ad_movimientos_almacen_detalle.id_apartado_detalle,";
	$insertD .= " ad_movimientos_almacen_detalle.id_producto_compuesto,";
	$insertD .= " ad_movimientos_almacen_detalle.campo_4,";
	$insertD .= " ad_movimientos_almacen_detalle.numero_serie,";
	$insertD .= " ad_movimientos_almacen_detalle.id_control_solicitud_material,";
	$insertD .= " ad_movimientos_almacen_detalle.id_solicitud_material,";
	$insertD .= " ad_movimientos_almacen_detalle.id_producto_origen";
	$insertD .= " FROM ad_movimientos_almacen";
	$insertD .= " LEFT JOIN ad_movimientos_almacen_detalle";
	$insertD .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
	$insertD .= " WHERE ad_movimientos_almacen_detalle.id_control_movimiento = '".$id_control_movimiento."'";
	$insertD .= " AND ad_movimientos_almacen_detalle.id_detalle = '".$id_detalle."';";
	mysql_query($insertD) or die("Error: " . mysql_error());
	$ultimo_insertado_detalle = mysql_insert_id();
	
	$updateD = "UPDATE ad_movimientos_almacen_detalle SET id_control_movimiento = '".$ultimo_insertado_encabezado."', cantidad = '1', signo = '1' WHERE id_detalle = '".$ultimo_insertado_detalle."';";
	mysql_query($updateD) or die("Error: " . mysql_error());
	//SE INSERTA EL DETALLE DE LA ENTRADA AL ALMACEN NETEABLE <--
//*************************************** M O V I M I E N T O S   D E   A L M A C E N *************************************************************** <--
}
//Se registra el movimiento en el almacen. Sale del no neteable al neteable <--

//Se obtienen los IRD en pipeline actualizado para ser mostrados -->
$sql = "SELECT";
$sql .= " cl_importacion_pipeline.id_control,";
$sql .= " cl_importacion_pipeline.id_layout,";
$sql .= " cl_importacion_pipeline.id_carga,";
$sql .= " UPPER(cl_importacion_pipeline.t48) IRDS,";
$sql .= " DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%d/%m/%Y') FCarga,";
$sql .= " DATE_FORMAT(cl_importacion_pipeline.fecha_carga, '%H:%i:%s') HCarga,";
$sql .= " (";
$sql .= " IF(";
$sql .= " (";
$sql .= " SELECT SUM(1)";
$sql .= " FROM cl_control_series";
$sql .= " LEFT JOIN cl_control_series_detalle";
$sql .= " ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie";
$sql .= " WHERE numero_serie = cl_importacion_pipeline.t48";
$sql .= " ) >= 1,";
$sql .= " IF(";
$sql .= " (";
$sql .= " SELECT SUM(1)";
$sql .= " FROM cl_control_series";
$sql .= " LEFT JOIN cl_control_series_detalle";
$sql .= " ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie";
$sql .= " WHERE numero_serie = cl_importacion_pipeline.t48";
$sql .= " AND id_estatus = '1'";
$sql .= " ) >=1, 'PENDIENTE POR ASIGNAR','ASIGNADO')";
$sql .= " ,";
$sql .= " 'NO FISICO')";
$sql .= " ) Estatus";
$sql .= " FROM cl_importacion_pipeline";
$sql .= " WHERE cl_importacion_pipeline.activo = '1'";
$sql .= " ORDER BY cl_importacion_pipeline.t48, cl_importacion_pipeline.fecha_carga;";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
//Se obtienen los IRD en pipeline actualizado para ser mostrados <--

echo json_encode($smarty->fetch('especiales/respuestaMuestraIRDsEnPipeline.tpl'));
?>