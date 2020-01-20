<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
include("../../code/especiales/funcionesCosteoLote.php");

$idOrden = $_POST['idOrden'];
$almacen = $_POST['almacen'];
$observaciones = $_POST['observaciones'];
$factura = $_POST['factura'];
$pedimento = $_POST['pedimento'];
$pedimentoFecha = $_POST['pedimentoFecha'];
$aduana = $_POST['aduana'];
$proveedor = $_POST['proveedor'];
$productos = $_POST['productos'];
$estatus = $_POST['estatus'];
$id_sucursal = $_POST['id_sucursal'];
$id_detalles_odc = $_POST['id_detalles_odc'];
//die($id_detalles_odc);
$mensaje = "";
/*********
********Obtenemos el consecutivo en los movimientos del almacen **************************/
//agregue en el where el almacen al cual entrará el producto
$sqlMov = "SELECT MAX(id_movimiento) AS movimiento FROM ad_movimientos_almacen where id_almacen = '".$almacen."'";
$datosMov = new consultarTabla($sqlMov);
$resultMov = $datosMov -> obtenerLineaRegistro();
$contador = $datosMov -> cuentaRegistros();

if($contador == 0)
	$id_movimiento = 1;
else
	$id_movimiento = $resultMov['movimiento'] + 1;
/************************************************************************************************/

//Recorro el detalle de la orden de entrada ----------------------------------------------------->
$productosLinea = explode(",", $productos); //Obtenemos el numero de productos que vienen del formulario de entradas de almacen
$cuentaP = count($productosLinea);
$total_productos = 0;

$badera_no_neteable = FALSE;
$badera_neteable = FALSE;

for($i=0; $i<$cuentaP; $i++){
	$datosProd = explode("|", $productosLinea[$i]); //Obtenemos los id productos que vienen del formulario de entradas de almacen
	//$insertaProd['id_costeo_productos'] = $ultimo_costeo;
	$insertaProd['id_producto'] = $datosProd[0];
	$insertaProd['costo_por_unidad_odc'] = $datosProd[1];
	$insertaProd['costo_neto_unitario'] = $datosProd[1];
	$insertaProd['cantidad'] = $datosProd[2];
	$importe = $datosProd[2] * $datosProd[1];
	$insertaProd['importe'] = $importe;
	$insertaProd['id_orden_compra_producto_detalle'] =  $datosProd[3];

	if( ($datosProd[5]=='1') && ($datosProd[7]=='1')){
	//En caso de REQUERIR NUMERO DE SERIE y VALIDACION DE TIPO DE EQUIPO (ALMACEN NO NETABLE)

		//Se valida que el numero de serie no exista en la tabla cl_control_series --->
		//Se obtienen los NUMERO DE SERIE de la tabla cl_importacion_numeros_series
		$sExisteNumeroSerie = "SELECT * FROM cl_control_series WHERE  numero_serie IN (";
		$sExisteNumeroSerie .= " SELECT cl_importacion_numeros_series.t48 numero_serie";
		$sExisteNumeroSerie .= " FROM cl_cargas";
		$sExisteNumeroSerie .= " INNER JOIN cl_importacion_numeros_series";
		$sExisteNumeroSerie .= " ON cl_cargas.id_carga = cl_importacion_numeros_series.id_carga";
		$sExisteNumeroSerie .= " WHERE cl_cargas.id_carga = ".$datosProd[6];
		/////////////////////$sExisteNumeroSerie .= " WHERE cl_cargas.id_layout = '5'";
		//$sExisteNumeroSerie .= " AND cl_importacion_numeros_series.activo = '1'";
		$sExisteNumeroSerie .= ");";

		$datosExisteNumerosSeries = new consultarTabla($sExisteNumeroSerie);
		$registros = $datosExisteNumerosSeries -> cuentaRegistros();
		
		$resultIC = $datosExisteNumerosSeries -> obtenerRegistros();
		foreach($resultIC AS $ic){
			$id_carga_por_captura = $ic -> id_carga;
		}
		
		
		
		if($registros == 0){

			//Se obtiene el ID del Almacen no neteable -->
			$id_almacen_no_neteable = 0;
			$sSql = "SELECT ad_almacenes.id_almacen FROM ad_almacenes";
			$sSql .= " INNER JOIN ad_sucursales_almacenes_detalle";
			$sSql .= " ON ad_almacenes.id_almacen = ad_sucursales_almacenes_detalle.id_almacen";
			$sSql .= " AND ad_sucursales_almacenes_detalle.id_sucursal = ".$id_sucursal."";
			$sSql .= " AND ad_almacenes.neteable = '2'";	//1:Neteable, 2:No Neteable
			//$sSql .= "AND ad_almacenes.activo = '0';";
			$datosAlmacen = new consultarTabla($sSql);
			$resultAlmacen = $datosAlmacen -> obtenerRegistros();
			foreach($resultAlmacen AS $tipoAlmacen){
				$id_almacen_no_neteable = $tipoAlmacen -> id_almacen;
			}
			//Se obtiene el ID del Almacen no neteable <--


			if(!$badera_no_neteable){
				$insertaAlmacen['id_movimiento'] = $id_movimiento;
				$insertaAlmacen['id_control_orden_compra'] = $idOrden;
				$insertaAlmacen['id_tipo_movimiento'] = 1;
				$insertaAlmacen['id_subtipo_movimiento'] = 70002;
				$insertaAlmacen['fecha_movimiento'] = date("Y-m-d");
				$insertaAlmacen['hora_movimiento'] = date("H:i:s");;
				$insertaAlmacen['id_proveedor'] = $proveedor;
				$insertaAlmacen['id_almacen'] = $id_almacen_no_neteable;
				$insertaAlmacen['id_usuario'] = $_SESSION["USR"]->userid;
				//$insertaAlmacen['observaciones'] = $observaciones;
				$insertaAlmacen['no_modificable'] = 1;
				$insertaAlmacen['activo'] = 1;
				accionesMysql($insertaAlmacen, 'ad_movimientos_almacen', 'Inserta'); //Insertamos en el encabezado del movimiento almacen
				$ultimo_mov_no_neteable = mysql_insert_id();
				$badera_no_neteable = TRUE;

			}

			//Se desactivan los números de serien que se subieron para esta generación de entrada --->
			$sUpdate = "UPDATE cl_importacion_numeros_series SET activo = '0' WHERE id_carga = '".$datosProd[6]."'";
			mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
			//Se desactivan los números de serien que se subieron para esta generación de entrada <---

			//Lote 1 vemos si existe el lote si no lo creamos
			$insertaProd['id_lote'] = obtenAsignaCreaLote($insertaProd['id_producto'],$ultimo_mov_no_neteable,$ultimo_costeo,$idOrden,$pedimento,$pedimentoFecha,$aduana);
			$lote = $insertaProd['id_lote'];

			//Se obtienen los NUMERO DE SERIE de la tabla cl_importacion_numeros_series
			$sNumeroSerie = "SELECT cl_importacion_numeros_series.t48";
			$sNumeroSerie .= " FROM cl_cargas";
			$sNumeroSerie .= " INNER JOIN cl_importacion_numeros_series";
			$sNumeroSerie .= " ON cl_cargas.id_carga = cl_importacion_numeros_series.id_carga";
			$sNumeroSerie .= " WHERE cl_cargas.id_layout = 5";
			$sNumeroSerie .= " AND cl_cargas.id_carga = ".$datosProd[6];
			//$sNumeroSerie .= " AND cl_importacion_numeros_series.activo = '1';";
			$datosNumerosSeries = new consultarTabla($sNumeroSerie);
			$resultNumerosSeries = $datosNumerosSeries -> obtenerRegistros();
			foreach($resultNumerosSeries AS $numeroSerie){

				$IDP = "0";
				$numeroSerie = $numeroSerie -> t48;
				//Se obtiene el verdadero ID Producto del numero de serie -->
				$sQuery = "SELECT id_producto_servicio";
				$sQuery .= " FROM cl_productos_servicios";
				$sQuery .= " WHERE id_tipo_equipo  IN (SELECT id_tipo_equipo FROM cl_tipos_equipo WHERE SUBSTRING(inicio_serie,1, 6) = '".substr($numeroSerie,0,6)."');";
				$datosIDProd = new consultarTabla($sQuery);
				$resultIDProd = $datosIDProd -> obtenerRegistros();
				foreach($resultIDProd AS $IDProd){
					$IDP = $IDProd -> id_producto_servicio;
				}
				//Se obtiene el verdadero ID Producto del numero de serie <--
				
				$insertaDetalleAlmacen['id_control_movimiento'] = $ultimo_mov_no_neteable;
				$insertaDetalleAlmacen['id_producto'] = $IDP;
				$insertaDetalleAlmacen['cantidad'] = 1;
				$insertaDetalleAlmacen['id_lote'] = $lote;					/******************* VERIFICAR **********************/
				$insertaDetalleAlmacen['id_tipo_documento_interno'] = 1;
				$insertaDetalleAlmacen['id_detalle_documento_interno'] = $datosProd[3];
				$insertaDetalleAlmacen['signo'] = 1;
				$insertaDetalleAlmacen['id_costeo_productos'] = '0';
				$insertaDetalleAlmacen['id_orden_compra_producto_detalle'] = $datosProd[3];
				$insertaDetalleAlmacen['id_producto_origen'] = $datosProd[0]; ////Aqui debe variar por los numeros de serie
				$insertaDetalleAlmacen['numero_serie'] = $numeroSerie;
				//$insertaDetalleAlmacen['id_costeo_producto_detalle'] = 1;
				$insertaDetalleAlmacen['id_costeo_producto_detalle'] = 0;	/******************* VERIFICAR **********************/
				accionesMysql($insertaDetalleAlmacen, 'ad_movimientos_almacen_detalle', 'Inserta'); //Insertamos en el detalle del movimiento

				//Se inserta en la tabla cl_control_series, los numeros de serie para llevar un control -->
				$insertaNumerosDeSerie['id_producto_servicio'] = $datosProd[0];
				$insertaNumerosDeSerie['numero_serie'] = $numeroSerie;
				$insertaNumerosDeSerie['fecha_alta'] = date("Y-m-d H:i:s");
				$insertaNumerosDeSerie['id_almacen_ingreso'] = $id_almacen_no_neteable;
				$insertaNumerosDeSerie['id_control_orden_compra'] = $idOrden;
				$insertaNumerosDeSerie['id_carga'] = $datosProd[6];
				//$insertaNumerosDeSerie['id_carga'] = $id_carga_por_captura; 
				
				$insertaNumerosDeSerie['id_usuario_alta'] = $_SESSION["USR"]->userid;
				$insertaNumerosDeSerie['activo'] = 1;
				accionesMysql($insertaNumerosDeSerie, 'cl_control_series', 'Inserta'); //Insertamos en el encabezado del movimiento almacen
				//Se inserta en la tabla cl_control_series, los numeros de serie para llevar un control <--
				$ultimo_insertado = mysql_insert_id();
				//Se inserta en la tabla cl_control_series_detalle, los numeros de serie para llevar un control -->
				$insertaNumerosDeSerieDetalle['id_control_serie'] = $ultimo_insertado;
				$insertaNumerosDeSerieDetalle['id_estatus'] = 1;
				$insertaNumerosDeSerieDetalle['id_almacen'] = $id_almacen_no_neteable;
				$insertaNumerosDeSerieDetalle['id_plaza'] = $id_sucursal;
				$insertaNumerosDeSerieDetalle['id_usuario'] = $_SESSION["USR"]->userid;
				$insertaNumerosDeSerieDetalle['fecha_alta'] = date("Y-m-d H:i:s");
				$insertaNumerosDeSerieDetalle['activo'] = 1;
				accionesMysql($insertaNumerosDeSerieDetalle, 'cl_control_series_detalle', 'Inserta'); //Insertamos en el encabezado del movimiento almacen
				//Se inserta en la tabla cl_control_series_detalle, los numeros de serie para llevar un control <--
			}

		}
		else{
			$update = "UPDATE cl_importacion_numeros_series SET activo = '0' WHERE n1 in (".$id_detalles_odc.")";
			//die($update); 
			mysql_query($update) or die("Error en consulta:<br> $update <br>" . mysql_error());		
			$mensaje = "Ya existen los numeros de serie";
		}
		//Se valida que el numero de serie no exista en la tabla cl_control_series <---



	}else{
	//En caso de NO REQUERIR NUMERO DE SERIE, NI VALIDACION DE TIPO DE EQUIPO (ALMACEN NETEABLE)
		# CODIGO AQUI --->

		if(!$badera_neteable){
			$insertaAlmacen['id_movimiento'] = $id_movimiento;
			$insertaAlmacen['id_control_orden_compra'] = $idOrden;
			$insertaAlmacen['id_tipo_movimiento'] = 1;
			$insertaAlmacen['id_subtipo_movimiento'] = 70002;
			$insertaAlmacen['fecha_movimiento'] = date("Y-m-d");
			$insertaAlmacen['hora_movimiento'] = date("H:i:s");;
			$insertaAlmacen['id_proveedor'] = $proveedor;
			$insertaAlmacen['id_almacen'] = $almacen;
			$insertaAlmacen['id_usuario'] = $_SESSION["USR"]->userid;
			//$insertaAlmacen['observaciones'] = $observaciones;
			$insertaAlmacen['no_modificable'] = 1;
			$insertaAlmacen['activo'] = 1;
			accionesMysql($insertaAlmacen, 'ad_movimientos_almacen', 'Inserta'); //Insertamos en el encabezado del movimiento almacen ************************************************************
			$ultimo_mov_netable = mysql_insert_id();
			$badera_neteable = TRUE;
		}

		//Lote 1 vemos si existe el lote si no lo creamos
		$insertaProd['id_lote'] = obtenAsignaCreaLote($insertaProd['id_producto'],$ultimo_mov_netable,$ultimo_costeo,$idOrden,$pedimento,$pedimentoFecha,$aduana);
		$lote = $insertaProd['id_lote'];

		//Esto pasa cuando NO REQUIERE CONTROL DE NUMERO DE SERIE, NI REQUIERE CONTROL DE EQUIPO --->
		$insertaDetalleAlmacen['id_control_movimiento'] = $ultimo_mov_netable;
		$insertaDetalleAlmacen['id_producto'] = $datosProd[0]; /****************************************************************************************************/
		$insertaDetalleAlmacen['cantidad'] = $datosProd[2];
		$insertaDetalleAlmacen['id_lote'] = $lote;					/******************* VERIFICAR **********************/
		$insertaDetalleAlmacen['id_tipo_documento_interno'] = 1;
		$insertaDetalleAlmacen['id_detalle_documento_interno'] = $datosProd[3];
		$insertaDetalleAlmacen['signo'] = 1;
		$insertaDetalleAlmacen['id_costeo_productos'] = '0';
		$insertaDetalleAlmacen['id_orden_compra_producto_detalle'] = $datosProd[3];
		$insertaDetalleAlmacen['id_producto_origen'] = $datosProd[0];
		$insertaDetalleAlmacen['numero_serie'] = "";
		//$insertaDetalleAlmacen['id_costeo_producto_detalle'] = 1;
		$insertaDetalleAlmacen['id_costeo_producto_detalle'] = 0;	/******************* VERIFICAR **********************/
		accionesMysql($insertaDetalleAlmacen, 'ad_movimientos_almacen_detalle', 'Inserta'); //Insertamos en el detalle del movimiento
		//Esto pasa cuando NO REQUIERE CONTROL DE NUMERO DE SERIE, NI REQUIERE CONTROL DE EQUIPO <---
		# CODIGO AQUI <---
	}
}
//Recorro el detalle de la orden de entrada  <-----------------------------------------------------
if($mensaje==""){
	if($estatus == 0)
			$cambiaEst = 4;	//No se que indica
	else if($estatus == 1)
			$cambiaEst = 5;	//Que ya se generó la entrada
	//Se actualiza el estatus de la orden de compra a 5, que indica que ya se genero la orden de entrada --->
	$actualiza = "UPDATE ad_ordenes_compra_productos SET id_estatus = " . $cambiaEst . " WHERE id_control_orden_compra = " . $idOrden;
	mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());		
	//Se actualiza el estatus de la orden de compra a 5, que indica que ya se genero la orden de entrada <---
	$mensaje = " La entrada al almacén de la ODC $idOrden se ha insertado correctamente.";
}

echo $mensaje;

?>