<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$arreglo = array();
$arreglo_errores = array();
$arreglo_compuesto_errores = array();
$arrErr=array();
$error = false;
$id_usuario = $_SESSION["USR"]->userid;
$sesion = $_SESSION['sesion_unica'];

$accion = $_POST['accion'];
$cadena = $_POST['arreglo_numeros_serie'];
$idOrdenCompra = $_POST['idOrdenCompra'];
$idAlmacen = $_POST['idAlmacen'];
$idProducto = $_POST['idProducto'];
$idPlaza = $_POST['idPlaza'];
$id_carga = $_POST['idCarga'];
$arreglo = explode(",", $cadena);
$renglon_err = "";

$update = "UPDATE ad_ird_surtir SET activo = '0' WHERE id_usuario = '".$id_usuario."' AND sesion = '".$sesion."';";
mysql_query($update) or die("Error: " . mysql_error());

$j=0;
if($accion=='valida'){
	// Validaciones --->
	for($i=0; $i<count($arreglo); $i++){
		//Verifica el formato de la cadena -->
		$ird = $arreglo[$i];
		$sucadena = substr($ird, 0, 6);
		if( $sucadena != 'CE0B7D' ){
			$observacion = "Error en el formato";
			$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion, indicador) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."','E');";
			mysql_query($insert) or die("Error: " . mysql_error());
			$error = true;
		}
		//Verifica el formato de la cadena <--
	}
	for($i=0; $i<count($arreglo); $i++){
		//Verifica la longitud -->
		$ird = $arreglo[$i];
		$longitud = strlen($ird);
		if($longitud!=17){
			$observacion = "La longitud debe de ser de 17 caracteres";
			$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion, indicador) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."','E');";
			mysql_query($insert) or die("Error: " . mysql_error());
			$error = true;
		}
		//Verifica la longitud <--
	}
	// Validaciones <---

	for ($i = 0; $i < count($arreglo); $i++) {
		$observacion = "";
		$ird = $arreglo[$i];
		$sql = "SELECT *";
		$sql .= " FROM ad_movimientos_almacen";
		$sql .= " LEFT JOIN ad_movimientos_almacen_detalle";
		$sql .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
		$sql .= " WHERE ad_movimientos_almacen.id_almacen IN (";
		//SUBQUERY PARA OBTENER EL ALMACEN NETEABLE -->
		$sql .= " SELECT";
		$sql .= " cl_control_series_detalle.id_almacen";
		$sql .= " FROM cl_control_series";
		$sql .= " INNER JOIN cl_control_series_detalle";
		$sql .= " ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie";
		$sql .= " INNER JOIN ad_almacenes";
		$sql .= " ON cl_control_series_detalle.id_almacen = ad_almacenes.id_almacen";
		$sql .= " WHERE cl_control_series.numero_serie = '".$ird."'";
		$sql .= " AND cl_control_series_detalle.id_estatus = '2'";
		$sql .= " AND ad_almacenes.neteable = '1'";
		$sql .= " AND ad_almacenes.activo = '1'";
		$sql .= " )";
		//SUBQUERY PARA OBTENER EL ALMACEN NETEABLE <--
		$sql .= " AND ad_movimientos_almacen_detalle.numero_serie = '".$ird."';";
		$datos = new consultarTabla($sql);
		$result = $datos->obtenerRegistros();
		$contador = $datos->cuentaRegistros();
		if($contador==0){
			$observacion = "No existe el producto en el almacen";
			$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion, indicador) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."','E');";
			mysql_query($insert) or die("Error: " . mysql_error());
			$error = true;
		}			
		foreach($result AS $datos){
			if($datos->cantidad <= 0){
				$observacion = "No existe cantidad suficiente en almacen";
				$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion, indicador) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."','E');";
				mysql_query($insert) or die("Error: " . mysql_error());
				$error = true;
			}
		}

		//Verifica que los IRD's no esten ya en salida de almacen -->
		$sql = "SELECT *";
		$sql .= " FROM ad_movimientos_almacen";
		$sql .= " LEFT JOIN ad_movimientos_almacen_detalle";
		$sql .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
		$sql .= " WHERE ad_movimientos_almacen.id_almacen IN (";
		//SUBQUERY PARA OBTENER EL ALMACEN NETEABLE -->
		$sql .= " SELECT";
		$sql .= " cl_control_series_detalle.id_almacen";
		$sql .= " FROM cl_control_series";
		$sql .= " INNER JOIN cl_control_series_detalle";
		$sql .= " ON cl_control_series.id_control_serie = cl_control_series_detalle.id_control_serie";
		$sql .= " INNER JOIN ad_almacenes";
		$sql .= " ON cl_control_series_detalle.id_almacen = ad_almacenes.id_almacen";
		$sql .= " WHERE cl_control_series.numero_serie = '".$ird."'";
		$sql .= " AND cl_control_series_detalle.id_estatus = '2'";
		$sql .= " AND ad_almacenes.neteable = '1'";
		$sql .= " AND ad_almacenes.activo = '1'";
		//SUBQUERY PARA OBTENER EL ALMACEN NETEABLE <--
		$sql .= " )";
		$sql .= " AND ad_movimientos_almacen_detalle.numero_serie = '".$ird."'";
		$sql .= " AND IFNULL(ad_movimientos_almacen_detalle.id_control_pedido,0)<>0";
		$sql .= " AND ad_movimientos_almacen_detalle.signo = '-1';";
		//Verifica que los IRD's no esten ya en salida de almacen <--
		
		$datosP = new consultarTabla($sql);
		$resultP = $datosP->obtenerRegistros();
		$contadorP = $datosP->cuentaRegistros();
		if($contadorP > 0){
			$observacion = "El IRD ya esta registrado en una salida de almacen";
			$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion, indicador) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."','E');";
			mysql_query($insert) or die("Error: " . mysql_error());
			$error = true;
		}
	}

	if(!($error)){
	//EN CASO DE EXITO -->
		for ($i = 0; $i < count($arreglo); $i++) {
			//Aqui se guarda el numero de serie
			$observacion = "Exito";
			$ird = $arreglo[$i];
			$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion, indicador)";
			$insert .= " VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','1','".$observacion."','');";
			mysql_query($insert) or die("Error: " . mysql_error());
			$informe = 'Exito';
		}
		echo "correcto|0";
	//EN CASO DE EXITO <--
	}else{ 
		//EN CASO DE ERROR -->
		$informe = 'Error';
		$i = 0;
		$sql = "SELECT";
		$sql .= " DISTINCT(a.ird),";
		$sql .= " (SELECT GROUP_CONCAT(ais.observacion SEPARATOR ',') FROM ad_ird_surtir ais WHERE ais.ird = a.ird) observacion";
		$sql .= " FROM ad_ird_surtir a";
		$sql .= " WHERE a.id_usuario = '".$id_usuario."'";
		$sql .= " AND a.sesion = '".$sesion."'";
		$sql .= " AND a.indicador = 'E'";
		$sql .= " AND a.activo = '0';";
		$datos = new consultarTabla($sql);
		$result = $datos->obtenerRegistros();		
		foreach($result AS $datos){
			$ird = $datos->ird;
			$observacion = $datos->observacion;
			$array = explode(",", $observacion);
			$resultado = array_unique($array);
			$observacion = implode(",", $resultado);
			$arrErrLinea = array($i, $ird, $observacion);
			array_push($arrErr, $arrErrLinea);
			$i++;
		}	

		$update = "UPDATE ad_ird_surtir SET indicador = 'E2'";
		$update .= " WHERE id_usuario = '".$id_usuario."'";
		$update .= " AND sesion = '".$sesion."'";
		$update .= " AND indicador = 'E'";
		$update .= " AND activo = '0';";
		mysql_query($update) or die("Error: " . mysql_error());


		$idLayout="0";
		$smarty->assign("idLayout",$idLayout);
		$smarty->assign("informe",$informe);
		$smarty->assign("arrErr",$arrErr);
		$smarty->assign("sql",$sql);
		$smarty -> display("especiales/respuestaNumeroSerieParaSalidaDeAlmacen.tpl");
		//EN CASO DE ERROR <--
	}
}
?>

