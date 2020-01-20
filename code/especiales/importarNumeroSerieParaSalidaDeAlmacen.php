<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$cantidadAIngresar=$_GET["cantidadAIngresar"];
$numeroRenglon=$_GET["numeroRenglon"];
$arrErr=array();
$sql = "";
$error = false;
if ($_POST["action"] == "upload") {
	$tamano = $_FILES["archivo"]['size'];
	$tipo = $_FILES["archivo"]['type'];
	$archivo = $_FILES["archivo"]['name'];
	$tmp_name = $_FILES['archivo']['tmp_name'];

	$registros = array();
	if (($fichero = fopen($tmp_name, "r")) !== FALSE) {
		// Lee los nombres de los campos
		$nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
		$num_campos = count($nombres_campos);
		// Lee los registros
		while (($datos = fgetcsv($fichero, 0, ",", "\"", "\"")) !== FALSE) {
			// Crea un array asociativo con los nombres y valores de los campos
			for ($icampo = 0; $icampo < $num_campos; $icampo++) {
				$registro[$nombres_campos[$icampo]] = $datos[$icampo];
			}
			// Añade el registro leido al array de registros
			$registros[] = $registro;
		}
		fclose($fichero);
//		echo "Leidos " . count($registros) . " registros\n";

		$id_usuario = $_SESSION["USR"]->userid;
		$sesion = $_SESSION['sesion_unica'];
		$update = "UPDATE ad_ird_surtir SET activo = '0' WHERE id_usuario = '".$id_usuario."' AND sesion = '".$sesion."';";
		mysql_query($update) or die("Error: " . mysql_error());

		$j=0;
		for ($i = 0; $i < count($registros); $i++) {
			//echo "<br />Nombre: ".$registros[$i]["Numero de Serie"];
			$nombre_campo = $nombres_campos[0];
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
			$sql .= " WHERE cl_control_series.numero_serie = '".$registros[$i][$nombre_campo]."'";
			$sql .= " AND cl_control_series_detalle.id_estatus = '2'";
			$sql .= " AND ad_almacenes.neteable = '1'";
			$sql .= " AND ad_almacenes.activo = '1'";
			$sql .= " )";
			//SUBQUERY PARA OBTENER EL ALMACEN NETEABLE <--
			$sql .= " AND ad_movimientos_almacen_detalle.numero_serie = '".$registros[$i][$nombre_campo]."';";
			$datos = new consultarTabla($sql);
			$result = $datos->obtenerRegistros();
			$contador = $datos->cuentaRegistros();
			if($contador==0){
				$ird = $registros[$i][$nombre_campo];
				$observacion = "No existe el producto en el almacen";
				$arrErrLinea = array($j, $ird, $observacion);
				array_push($arrErr, $arrErrLinea);
				$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."');";
				mysql_query($insert) or die("Error: " . mysql_error());
				$error = true;
				$j++;
			}			
			foreach($result AS $datos){
				if($datos->cantidad <= 0){
					$ird = $registros[$i][$nombre_campo];
					$observacion = "No existe cantidad suficiente en almacen";
					$arrErrLinea = array($j, $ird, $observacion);
					array_push($arrErr, $arrErrLinea);
					$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."');";
					mysql_query($insert) or die("Error: " . mysql_error());
					$error = true;
					$j++;
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
			$sql .= " WHERE cl_control_series.numero_serie = '".$registros[$i][$nombre_campo]."'";
			$sql .= " AND cl_control_series_detalle.id_estatus = '2'";
			$sql .= " AND ad_almacenes.neteable = '1'";
			$sql .= " AND ad_almacenes.activo = '1'";
			//SUBQUERY PARA OBTENER EL ALMACEN NETEABLE <--
			$sql .= " )";
			$sql .= " AND ad_movimientos_almacen_detalle.numero_serie = '".$registros[$i][$nombre_campo]."'";
			$sql .= " AND IFNULL(ad_movimientos_almacen_detalle.id_control_pedido,0)<>0";
			$sql .= " AND ad_movimientos_almacen_detalle.signo = '-1';";
			//Verifica que los IRD's no esten ya en salida de almacen <--
			
			$datosP = new consultarTabla($sql);
			$resultP = $datosP->obtenerRegistros();
			$contadorP = $datosP->cuentaRegistros();
			if($contadorP > 0){
				$ird = $registros[$i][$nombre_campo];
				$observacion = "El IRD ya esta registrado en una salida de almacen";
				$arrErrLinea = array($j, $ird, $observacion);
				array_push($arrErr, $arrErrLinea);
				$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','0','".$observacion."');";
				mysql_query($insert) or die("Error: " . mysql_error());
				$error = true;
				$j++;
			}
		}

		if(!($error)){
			for ($i = 0; $i < count($registros); $i++) {
				//Aqui se guarda el numero de serie
				$ird = $registros[$i][$nombre_campo];
				$insert = "INSERT INTO ad_ird_surtir (ird, fecha, id_usuario, sesion, activo, observacion) VALUES ('".$ird."',now(),'".$id_usuario."','".$sesion."','1','Exito');";
				mysql_query($insert) or die("Error: " . mysql_error());
				$informe = 'Exito';
			}
		}else{ $informe = 'Error'; }
		fclose($gestor);
	}
}

$idLayout="0";
$smarty->assign("idLayout",$idLayout);
$smarty->assign("informe",$informe);
$smarty->assign("cantidadAIngresar",$cantidadAIngresar);
$smarty->assign("filaDatos",$filaDatos);
$smarty->assign("numeroRenglon",$numeroRenglon);
$smarty->assign("arrErr",$arrErr);
$smarty->assign("sql",$sql);
$smarty->assign("tmp_name",$tmp_name);
$smarty -> display("especiales/importarNumeroSerieParaSalidaDeAlmacen.tpl");
?>