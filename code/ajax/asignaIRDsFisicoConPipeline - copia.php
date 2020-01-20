<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
$arreglo_irds = array();
$separado_por_comas = array();
$arreglo_irds = $_GET['arreglo_irds'];
$idSucursal = $_GET['idSucursal'];
$separado_por_comas = implode(",", $arreglo_irds);

for($i=0; $i<count($arreglo_irds); $i++){
	//Se debe de actualizar el estatus activo de 1 a 0, indicando que el numero de serie ya no es valido -->
	$update = "UPDATE cl_control_series_detalle";
	$update .= " SET activo = '0'";
	$update .= " WHERE id_control_serie IN";
	$update .= " (";
	$update .= " SELECT id_control_serie FROM cl_control_series WHERE numero_serie = '".$arreglo_irds[$i]."'";
	$update .= " );";
	mysql_query($update) or die("Error: " . mysql_error());
	//Se debe de actualizar el estatus activo de 1 a 0, indicando que el numero de serie ya no es valido <--

	//Se cambia el estado de activo a 0 en la tabla cl_importacion_pipeline, indicando que ya fue asignado a un ird fisico -->
	$update = "UPDATE cl_importacion_pipeline";
	$update .= " SET activo = '0'";
	$update .= " WHERE t48 = '".$arreglo_irds[$i]."';";
	mysql_query($update) or die("Error: " . mysql_error());
	//Se cambia el estado de activo a 0 en la tabla cl_importacion_pipeline, indicando que ya fue asignado a un ird fisico <--


	//Se obtiene el id del almacen no neteable de la plaza -->
	$id_almacen = "0";
	$select = "SELECT ad_almacenes.id_almacen, ad_almacenes.nombre";
	$select .= " FROM ad_almacenes";
	$select .= " INNER JOIN ad_sucursales_almacenes_detalle";
	$select .= " ON ad_almacenes.id_almacen = ad_sucursales_almacenes_detalle.id_almacen";
	$select .= " WHERE ad_almacenes.neteable = 1";
	$select .= " AND ad_sucursales_almacenes_detalle.id_sucursal = '".$idSucursal."';";
	$datosP = new consultarTabla($select);
	$resultP = $datosP -> obtenerRegistros();
	foreach($resultP as $registrosP){	
		$id_almacen_no_netable = $registrosP -> id_almacen;
	}
	//Se obtiene el id del almacen no neteable de la plaza <--

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
		$insert .= "'".$id_almacen."', ";
		$insert .= "'".$id_plaza."', ";
		$insert .= "'".$id_usuario."', ";
		$insert .= "now(), ";
		$insert .= "'1'";
		$insert .= " );";
		mysql_query($insert) or die("Error: " . mysql_error());
	}
	//Se inserta un nuevo registro en la tabla cl_control_series_detalle, con el estatus 2 <--
}

//Obtenemos el consecutivo en los movimientos del almacen de salida, en este caso del almacen no neteable -->
$sqlMov = "SELECT MAX(id_movimiento) AS movimiento FROM ad_movimientos_almacen where id_almacen = '".$id_almacen_no_netable."'";
$datosMov = new consultarTabla($sqlMov);
$resultMov = $datosMov -> obtenerLineaRegistro();
$contador = $datosMov -> cuentaRegistros();
if($contador == 0) 	$id_movimiento = 1;
else $id_movimiento = $resultMov['movimiento'] + 1;
//Obtenemos el consecutivo en los movimientos del almacen de salida, en este caso del almacen no neteable <--

//SE INSERTA EL ENCABEZADO DE LA SALIDA DEL ALMACEN NO NETEABLE -->
$id_tipo_movimiento = 2; //SALIDA
$id_subtipo_movimiento = 70066; //TRASPASO SALIDA ENTRE ALMACENES -
$no_modificable = 1;
$id_control_orden_compra =;
$id_proveedor = ;
$id_usuario = $_SESSION["USR"]->userid;
$activo = 1;
$insert = "INSERT INTO ad_movimientos_almacen (";
$insert .= " id_movimiento,";
$insert .= " id_tipo_movimiento,";
$insert .= " id_subtipo_movimiento,";
$insert .= " id_almacen,";
$insert .= " fecha_movimiento,";
$insert .= " hora_movimiento,";
$insert .= " no_modificable,";	//E
$insert .= " id_control_orden_compra,";	//E
$insert .= " id_proveedor,";	//E
$insert .= " id_usuario,";	//E
$insert .= " activo";	//E
$insert .= " )";
$insert .= " VALUES (";
$insert .= "'".$id_movimiento."', ";
$insert .= "'".$id_tipo_movimiento."', ";
$insert .= "'".$id_subtipo_movimiento."', ";
$insert .= "'".$id_almacen_no_netable."', ";
$insert .= "DATE_FORMAT((now(),'%d-%m-%Y'), ";
$insert .= "DATE_FORMAT((now(),'%H:%i:%s'), ";
$insert .= "'".$.no_modificable"', ";
$insert .= "'".$id_control_orden_compra."', ";
$insert .= "'".$id_proveedor."', ";
$insert .= "'".$id_usuario."', ";
$insert .= "'".$activo."'";
$insert .= " );";
mysql_query($insert) or die("Error: " . mysql_error());
//SE INSERTA EL ENCABEZADO DE LA SALIDA DEL ALMACEN NO NETEABLE <--

//SE INSERTA EL DETALLE DE LA SALIDA DEL ALMACEN NO NETEBALE -->
//SE INSERTA EL DETALLE DE LA SALIDA DEL ALMACEN NO NETEABLE <--

/***********************************************************************************************************/
//SE INSERTA EL ENCABEZADO DE LA ENTRADA AL ALMACEN NETEABLE -->
$id_tipo_movimiento = 1; //ENTRADA
$id_subtipo_movimiento = 70006; //TRASPASO ENTRADA ENTRE ALMACENES +
$id_almacen = $id_almacen_netable;
$no_modificable = 1;
$id_control_orden_compra =;
$id_proveedor = ;
$id_usuario = $_SESSION["USR"]->userid;
$activo = 1;
//SE INSERTA EL ENCABEZADO DE LA ENTRADA AL ALMACEN NETEABLE <--

//SE INSERTA EL DETALLE DE LA ENTRADA AL ALMACEN NETEBALE -->
//SE INSERTA EL DETALLE DE LA ENTRADA AL ALMACEN NETEABLE <--


//Se registra el movimiento en el almacen. Sale del no neteable al neteable <--
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
$sql .= " INNER JOIN cl_control_series_detalle";
$sql .= " ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie";
$sql .= " WHERE numero_serie = cl_importacion_pipeline.t48";
$sql .= " ) >= 1,";
$sql .= " IF(";
$sql .= " (";
$sql .= " SELECT SUM(1)";
$sql .= " FROM cl_control_series";
$sql .= " INNER JOIN cl_control_series_detalle";
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

echo json_encode($smarty->fetch('especiales/respuestaMuestraIRDsEnPipeline.tpl'));
?>