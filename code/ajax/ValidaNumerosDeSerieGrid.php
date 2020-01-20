<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$arreglo = array();
$arreglo_errores = array();
$arreglo_compuesto_errores = array();

$accion = $_POST['accion'];
$cadena = $_POST['arreglo_numeros_serie'];
$idOrdenCompra = $_POST['idOrdenCompra'];
$idAlmacen = $_POST['idAlmacen'];
$idProducto = $_POST['idProducto'];
$idPlaza = $_POST['idPlaza'];
$id_carga = $_POST['idCarga'];
$id_detalle_orden_compra = $_POST['idDetalleOrdenCompra'];

$arreglo = explode(",", $cadena);

$renglon_err = "";
$bandera_error = false;


if($accion=='valida'){

	//Primero se desactivan los numeros de serie en el campo activo, que pasaria de 1 a 0, de acuerdo al numero de carga -->
	//$sUpdate = "UPDATE cl_importacion_numeros_series SET activo = '0' WHERE id_carga = '".$id_carga."'";
	//mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
	//Primero se desactivan los numeros de serie en el campo activo, que pasaria de 1 a 0, de acuerdo al numero de carga <--
	
	// Validaciones --->
	for($i=0; $i<count($arreglo); $i++){
		//Verifica la duplicidad en la base de datos, en la tabla  cl_control_series -->
		$sQuery = "SELECT * FROM cl_control_series WHERE numero_serie ='".$arreglo[$i]."' AND activo = '1';";
		$datos = new consultarTabla($sQuery);
		$registros = $datos -> cuentaRegistros();
		if($registros != 0){
			$arreglo_errores[$i][0] = $arreglo[$i];
			$arreglo_errores[$i][1] = 'El registro ya existe en la base de datos';
			$bandera_error = true;
		}
		//Verifica la duplicidad en la base de datos, en la tabla  cl_control_series <--
	}
	for($i=0; $i<count($arreglo); $i++){
		//Verifica el formato de la cadena -->
		$sucadena = substr($arreglo[$i], 0, 6);
		if( $sucadena != 'CE0B7D' ){
			$arreglo_errores[$i][0] = $arreglo[$i];
			$arreglo_errores[$i][1] = 'Error en el formato';
			$bandera_error = true;
		}
		//Verifica el formato de la cadena <--
	}
	for($i=0; $i<count($arreglo); $i++){
		//Verifica la longitud -->
		$longitud = strlen($arreglo[$i]);
		if($longitud!=17){
			$arreglo_errores[$i][0] = $arreglo[$i];
			$arreglo_errores[$i][1] = 'La longitud debe de ser de 17 caracteres';
			$bandera_error = true;
		}
		//Verifica la longitud <--
	}
	// Validaciones <---
	
	if($bandera_error){
		//En caso de detectar errores en la información subida -->
		for($i=0; $i<count($arreglo_errores); $i++){
			$cadena_errores = '';
			for($j=0; $j<count($arreglo_errores); $j++){
				if($arreglo_errores[$i][0]==$arreglo_errores[$j][0]){
					$cadena_errores .= $arreglo_errores[$j][1];
				}
			}
			$arreglo_compuesto_errores[$i][0] = $arreglo_errores[$i][0];
			$arreglo_compuesto_errores[$i][1] = $cadena_errores;
		}
	/*	echo '<pre>';
		print_r($arreglo_compuesto_errores);
		echo '</pre>';*/
		$errores_all=array();
		for($i=0;$i<count($arreglo_compuesto_errores);$i++){
			array_push($errores_all,$arreglo_compuesto_errores[$i][0].':'.$arreglo_compuesto_errores[$i][1]);
		}
		$errors=implode(",\n",$errores_all);
		echo $errors;
	}else{
		//En caso de no existir errores en la información, se guarda la información en la tabla de cargas cl_cargas y cl_importacion_numeros_series --->
		$id_layout = 5;
		$id_producto_servicio = $idProducto;
		$id_almacen_ingreso = $idAlmacen;
		$id_control_orden_compra = $idOrdenCompra;
		$id_usuario_alta = $_SESSION["USR"]->userid;
		$id_plaza = $idPlaza;
	
		//Se inserta en la tabla cl_cargas
		$sInsert = "INSERT INTO cl_cargas (id_layout, fecha_hora, usuario_realizo, accion)";
		$sInsert .= " VALUES (";
		$sInsert .= "'".$id_layout."',";
		$sInsert .= "now(),";
		$sInsert .= "'".$id_usuario_alta."',";
		$sInsert .= "'Insercion Manual'";
		$sInsert .= ");";
		mysql_query($sInsert) or die("Error en consulta:<br> $sInsert <br>" . mysql_error());
		$ultimo_insertado = mysql_insert_id();
		$id_carga = $ultimo_insertado;
		for($i=0; $i<count($arreglo); $i++){
			$numero_serie = $arreglo[$i];
			//Se inserta en la tabla de importación cl_importacion_numeros_series
			//En el campo n1 se insertara el ID del datelle de la orden de compra. Esto servira a desctivar un carga
			$sInsert = "INSERT INTO cl_importacion_numeros_series (id_layout, id_carga, fecha_carga, t48, id_almacen, id_orden_compra, activo, n1)";
			$sInsert .= " VALUES (";
			$sInsert .= "'".$id_layout."',";
			$sInsert .= "'".$id_carga."',";
			$sInsert .= "now(),";
			$sInsert .= "'".$numero_serie."',";
			$sInsert .= "'".$id_almacen_ingreso."',";
			$sInsert .= "'".$id_control_orden_compra."',";
			$sInsert .= "'1',";
			$sInsert .= "'".$id_detalle_orden_compra."'";
			$sInsert .= ");";
			mysql_query($sInsert) or die("Error en consulta:<br> $sInsert <br>" . mysql_error());
		}

		//Se desactivan los numeros de serie en el campo activo, que pasaria de 1 a 0, de acuerdo al numero de carga -->
		$sUpdate = "UPDATE cl_importacion_numeros_series SET activo = '0' WHERE id_carga = '".$id_carga."'";
		mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
		//Se desactivan los numeros de serie en el campo activo, que pasaria de 1 a 0, de acuerdo al numero de carga <--

		echo 'correcto|'.$id_carga;
		//En caso de no existir errores en la información, se guarda la información en la tabla de cargas cl_cargas y cl_importacion_numeros_series <---
	}
}elseif($accion=='guarda'){
/************************************************************************************************
//YA NO APLICA, POR QUE SE GUARDA EN EL PROCESO DE CUANDO SE GUARDA LA ENTRADA DE ORDEN DE COMPRA
/************************************************************************************************
/*
	//En caso de no existir errores en la información, se guarda la información en la tabla cl_control_series y cl_control_series_detalle --->
	$id_layout = 5;
	$id_producto_servicio = $idProducto;
	$id_almacen_ingreso = $idAlmacen;
	$id_control_orden_compra = $idOrdenCompra;
	$id_usuario_alta = $_SESSION["USR"]->userid;
	$id_plaza = $idPlaza;
	
	for($i=0; $i<count($arreglo); $i++){
	
	
		//Se obtiene el id del almacen no neteable -->
		$id_almacen_no_neteable = 0;
		$sSql = "SELECT ad_almacenes.id_almacen FROM ad_almacenes";
		$sSql .= " INNER JOIN ad_sucursales_almacenes_detalle";
		$sSql .= " ON ad_almacenes.id_almacen = ad_sucursales_almacenes_detalle.id_almacen";
		$sSql .= " AND ad_sucursales_almacenes_detalle.id_sucursal = ".$id_plaza."";
		$sSql .= " AND ad_almacenes.neteable = '2'";	//1:Neteable, 2:No Neteable
		//$sSql .= "AND ad_almacenes.activo = '0';";
		$datosAlmacen = new consultarTabla($sSql);
		$resultAlmacen = $datosAlmacen -> obtenerRegistros();
		foreach($resultAlmacen AS $tipoAlmacen){
			$id_almacen_no_neteable = $tipoAlmacen -> id_almacen;
		}
		$id_almacen_ingreso = $id_almacen_no_neteable;
		//Se obtiene el id del almacen no neteable <--
	
		$numero_serie = $arreglo[$i];
		$sInsert = "INSERT INTO cl_control_series (id_producto_servicio, numero_serie, fecha_alta, id_almacen_ingreso, id_control_orden_compra, id_carga, id_usuario_alta, activo)";
		$sInsert .= " VALUES(";
		$sInsert .= "'".$id_producto_servicio."',";
		$sInsert .= "'".$numero_serie."',";
		$sInsert .= "now(),";
		$sInsert .= "'".$id_almacen_ingreso."',";
		$sInsert .= "'".$id_control_orden_compra."',";
		$sInsert .= "'".$id_carga."',";
		$sInsert .= "'".$id_usuario_alta."',";
		$sInsert .= "'1'";
		$sInsert .= ");";
		mysql_query($sInsert) or die("Error en consulta:<br> $sInsert <br>" . mysql_error());
		$ultimo_insertado = mysql_insert_id();
	
		$sInsert = "INSERT INTO cl_control_series_detalle (id_control_serie, id_estatus, id_almacen, id_plaza, id_usuario, fecha_alta, activo)";
		$sInsert .= " VALUES(";
		$sInsert .= "'".$ultimo_insertado."',";
		$sInsert .= "'1',";
		$sInsert .= "'".$id_almacen_ingreso."',";
		$sInsert .= "'".$id_plaza."',";
		$sInsert .= "'".$id_usuario_alta."',";
		$sInsert .= "now(),";
		$sInsert .= "'1'";
		$sInsert .= ");";
		mysql_query($sInsert) or die("Error en consulta:<br> $sInsert <br>" . mysql_error());
	}
	
	//Se desactivan los numero de serie en la tabla cl_importacion_numeros_series
	$sUpdate = "UPDATE cl_importacion_numeros_series SET activo = '0' WHERE id_carga = '".$id_carga."';";
	mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
	//En caso de no existir errores en la información, se guarda la información en la tabla cl_control_series y cl_control_series_detalle <---
	echo 'correcto';
*/
}














	/*
			$sInsert = "INSERT INTO cl_control_series (id_producto_servicio, numero_serie, fecha_alta, id_almacen_ingreso, id_control_orden_compra, id_carga, id_usuario_alta, activo)";
			$sInsert .= " VALUES(";
			$sInsert .= "'".$id_producto_servicio."',";
			$sInsert .= "'".$numero_serie."',";
			$sInsert .= "now(),";
			$sInsert .= "'".$id_almacen_ingreso."',";
			$sInsert .= "'".$id_control_orden_compra."',";
			$sInsert .= "'".$id_carga."',";
			$sInsert .= "'".$id_usuario_alta."',";
			$sInsert .= "'1'";
			$sInsert .= ");";
			mysql_query($sInsert) or die("Error en consulta:<br> $sInsert <br>" . mysql_error());
			$ultimo_insertado = mysql_insert_id();
	
			$sInsert = "INSERT INTO cl_control_series_detalle (id_control_serie, id_estatus, id_almacen, id_plaza, id_usuario, fecha_alta, activo)";
			$sInsert .= " VALUES(";
			$sInsert .= "'".$ultimo_insertado."',";
			$sInsert .= "'1',";
			$sInsert .= "'".$id_almacen_ingreso."',";
			$sInsert .= "'".$id_plaza."',";
			$sInsert .= "'".$id_usuario_alta."',";
			$sInsert .= "now(),";
			$sInsert .= "'1'";
			$sInsert .= ");";
			mysql_query($sInsert) or die("Error en consulta:<br> $sInsert <br>" . mysql_error());
		}
	*/



?>

