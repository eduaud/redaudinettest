<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$fecha_aux = array();
$ordenesCompraGeneradas = "";
$sesion = $_SESSION['sesion_unica'];

$idDetalles = $_POST['idDetalles'];
$idRequisiciones = $_POST['idRequisiciones'];
$idProveedores = $_POST['idProveedores'];
$permiteMezclaDeProductos = $_POST['permiteMezclaDeProductos'];
$idAlmacenes = $_POST['idAlmacenes'];
$idProductos = $_POST['idProductos'];
$cantidades = $_POST['cantidades'];
$precios = $_POST['precios'];
$importes = $_POST['importes'];
$idTiposProductos = $_POST['idTiposProductos'];
$idSucursales = $_POST['idSucursales'];
$idUsuariosSolicitan = $_POST['idUsuariosSolicitan'];
$fechasRequeridas = $_POST['fechasRequeridas'];

$idDetallesAux = explode(",", $idDetalles);
$cuentaDetalles = count(array_filter($idDetallesAux));
$idRequisicionesAux = explode(",", $idRequisiciones);
$cuentaRequisiciones = explode(",", $idRequisicionesAux);
$idProveedoresAux = explode(",", $idProveedores);
$cuentaProveedores = count(array_filter($idProveedoresAux));
$permiteMezclaDeProductosAux = explode(",", $permiteMezclaDeProductos);
$cuentapermiteMezclaDeProductos = count(array_filter($permiteMezclaDeProductosAux));
$idAlmacenesAux = explode(",", $idAlmacenes);
$cuentaAlmacenes = count(array_filter($idAlmacenesAux));
$idProductosAux = explode(",", $idProductos);
$cuentaProductos = count(array_filter($idProductosAux));
$cantidadesAux = explode(",", $cantidades);
$cuentaCantidades = count(array_filter($cantidadesAux));
$preciosAux = explode(",", $precios);
$cuentaPrecios = count(array_filter($preciosAux));
$importesAux = explode(",", $importes);
$cuentaImportes = count(array_filter($importesAux));
$idTiposProductosAux = explode(",", $idTiposProductos);
$cuentaTiposProductos = count(array_filter($idTiposProductosAux));
$idSucursalesAux = explode(",", $idSucursales);
$cuentaSucursales = count(array_filter($idSucursalesAux));
$idUsuariosSolicitanAux = explode(",", $idUsuariosSolicitan);
$cuentaidUsuariosSolicitanAux = count(array_filter($idUsuariosSolicitanAux));
$fechasRequeridasAux = explode(",", $fechasRequeridas);
$cuentafechasRequeridasAux = count(array_filter($fechasRequeridasAux));


$caso = "";

//Se crea la tabla temporal tabla_temporal
$createTabla = "CREATE TEMPORARY TABLE tabla_temporal_".$sesion;
$createTabla .= " (";
$createTabla .= " id int(11) NOT NULL auto_increment,";
$createTabla .= " id_detalle int(11) NOT NULL DEFAULT '0',";
$createTabla .= " id_requisicion int(11) NOT NULL DEFAULT '0',";
$createTabla .= " id_proveedor int(11) NOT NULL DEFAULT '0',";
$createTabla .= " id_almacen int(11) NOT NULL DEFAULT '0',";
$createTabla .= " id_producto int(11) NOT NULL DEFAULT '0',";
$createTabla .= " cantidad int(11) NOT NULL DEFAULT '0',";
$createTabla .= " precio decimal(16,4) NOT NULL DEFAULT '0.0000',";
$createTabla .= " importe decimal(16,4) NOT NULL DEFAULT '0.0000',";
$createTabla .= " id_tipo_producto int(11) NOT NULL DEFAULT '0',";
$createTabla .= " id_sucursal int(11) NOT NULL DEFAULT '0',";
$createTabla .= " id_usuario_solicita int(11) NOT NULL DEFAULT '0',";
$createTabla .= " permite_mezclar int(11) NOT NULL DEFAULT '0',";
$createTabla .= " fecha_requerida varchar(20) NOT NULL,";
$createTabla .= " PRIMARY KEY  (`id`),";
$createTabla .= " UNIQUE KEY `id` (`id`)";
$createTabla .= " );";
mysql_query($createTabla) or die("Error en consulta:<br> $createTabla <br>" . mysql_error());	

//Se inserta información en la tabla tamporal tabla_temp
for ($i=0; $i < $cuentaDetalles; $i++) { 
	$insertAuxiliar = "INSERT INTO tabla_temporal_".$sesion." (";
	$insertAuxiliar .= "id_detalle,";
	$insertAuxiliar .= "id_requisicion,";
	$insertAuxiliar .= "id_proveedor,";
	$insertAuxiliar .= "id_almacen,";
	$insertAuxiliar .= "id_producto,";
	$insertAuxiliar .= "cantidad,";
	$insertAuxiliar .= "precio,";
	$insertAuxiliar .= "importe,";
	$insertAuxiliar .= "id_tipo_producto,";
	$insertAuxiliar .= " id_sucursal,";
	$insertAuxiliar .= " id_usuario_solicita,";
	$insertAuxiliar .= " permite_mezclar,";
	$insertAuxiliar .= " fecha_requerida";
	$insertAuxiliar .= ")";
	$insertAuxiliar .= " VALUES (";
	$insertAuxiliar .= $idDetallesAux[$i].",";
	$insertAuxiliar .= $idRequisicionesAux[$i].",";
	$insertAuxiliar .= $idProveedoresAux[$i].",";
	$insertAuxiliar .= $idAlmacenesAux[$i].",";
	$insertAuxiliar .= $idProductosAux[$i].",";
	$insertAuxiliar .= $cantidadesAux[$i].",";
	$insertAuxiliar .= $preciosAux[$i].",";
	$insertAuxiliar .= $importesAux[$i].",";
	$insertAuxiliar .= $idTiposProductosAux[$i].",";
	$insertAuxiliar .= $idSucursalesAux[$i].",";
	$insertAuxiliar .= $idUsuariosSolicitanAux[$i].",";
	$insertAuxiliar .= $permiteMezclaDeProductosAux[$i].",";
	$fecha_aux = explode("/",$fechasRequeridasAux[$i]);
	$insertAuxiliar .= "'".$fecha_aux[2].'-'.$fecha_aux[1].'-'.$fecha_aux[0]."'";
	$insertAuxiliar .= ")";
	mysql_query($insertAuxiliar) or die("Error en consulta:<br> $insertAuxiliar <br>" . mysql_error());	
}

// Quita los duplicados -->
$idProveedoresAuxUnicos = array_unique($idProveedoresAux);
$idAlmacenesAuxUnicos = array_unique($idAlmacenesAux);
$idTiposProductosAuxUnicos = array_unique($idTiposProductosAux);
// Quita los duplicados <--
// Reacomoda los elementos en sus posiciones consecutivas --->
$i=0;
foreach ($idProveedoresAuxUnicos as $idProveedoresAuxUnicosIndices) {
	$idProveedoresAuxUnicosAcomodados[$i] = $idProveedoresAuxUnicosIndices;
	$i++;
}
$i=0;
foreach ($idAlmacenesAuxUnicos as $idAlmacenesAuxUnicosIndices) {
	$idAlmacenesAuxUnicosAcomodados[$i] = $idAlmacenesAuxUnicosIndices;
	$i++;
}
$i=0;
foreach ($idTiposProductosAuxUnicos as $idTiposProductosAuxUnicosIndices) {
	$idTiposProductosAuxUnicosAcomodados[$i] = $idTiposProductosAuxUnicosIndices;
	$i++;
}
// Reacomoda los elementos en sus posiciones consecutivas <---

for ($i=0; $i < count($idProveedoresAuxUnicosAcomodados); $i++) { 
	for ($j=0; $j < count($idAlmacenesAuxUnicosAcomodados); $j++) { 
		for ($k=0; $k < count($idTiposProductosAuxUnicosAcomodados); $k++) { 
			$query = "SELECT";
			$query .= " id_proveedor,";
			$query .= " id_almacen,";
			$query .= " id_producto,";
			$query .= " id_requisicion,";
			$query .= " SUM(cantidad) cantidad,";
			$query .= " precio,";
			$query .= " SUM(importe) importe,";
			$query .= " id_tipo_producto,";
			$query .= " id_sucursal,";
			$query .= " id_usuario_solicita,";
			$query .= " permite_mezclar,";
			$query .= " GROUP_CONCAT(id_detalle) id_detalle,";
			$query .= " fecha_requerida";
			$query .= " FROM tabla_temporal_".$sesion;
			$query .= " WHERE id_proveedor = '".$idProveedoresAuxUnicosAcomodados[$i]."'";
			$query .= " AND id_almacen = '".$idAlmacenesAuxUnicosAcomodados[$j]."'";
			$query .= " AND id_tipo_producto = '".$idTiposProductosAuxUnicosAcomodados[$k]."'";
			$query .= " GROUP BY id_proveedor, id_almacen, id_tipo_producto, id_producto;";
			$datos = new consultarTabla($query);
			$result = $datos -> obtenerRegistros();

			foreach($result as $resultado){
				$id_proveedor = $resultado->id_proveedor;
				$id_almacen = $resultado->id_almacen;
				$id_producto = $resultado->id_producto;
				$id_requisicion = $resultado->id_requisicion;
				$cantidad = $resultado->cantidad;
				$precio = $resultado->precio;
				$importe = $resultado->importe;
				$id_tipo_producto = $resultado->id_tipo_producto;
				$id_sucursal = $resultado->id_sucursal;
				$id_usuario_solicita = $resultado->id_usuario_solicita;
				$permite_mezclar = $resultado->permite_mezclar;
				$id_detalle = $resultado->id_detalle;
				$fecha_requerida = $resultado->fecha_requerida;
				if($id_proveedor!="")
				{
					$inserta = "INSERT INTO tabla_detalle_auxiliar (id_proveedor, id_almacen, id_producto, cantidad, precio, importe, id_tipo_producto, id_sucursal, id_usuario_solicita, id_requisicion, permite_mezclar, sesion, id_detalle_requisicion, fecha_requerida)";
					$inserta .= " VALUES ('".$id_proveedor."','".$id_almacen."','".$id_producto."','".$cantidad."','".$precio."','".$importe."','".$id_tipo_producto."','".$id_sucursal."','".$id_usuario_solicita."','".$id_requisicion."','".$permite_mezclar."','".$sesion."','".$id_detalle."','".$fecha_requerida."');";
					mysql_query($inserta) or die("Error en consulta:<br> $inserta <br>" . mysql_error());	
				}
			}
			//echo $query;
		}
	}
}

// ***************************** A PARTIR DE AQUÍ SE TOMA COMO BASE LA TABLA AUXILIAR tabla_detalle_auxiliar *****************************

//PARA CUANDO **NO** SE PERMITE MEZCLA DE TIPOS DE PRODUCTO = 0 ------------------------------------------------------------------------------------------->
$query = "SELECT id_proveedor, id_almacen, id_sucursal, id_tipo_producto, id_usuario_solicita, SUM(cantidad) FROM tabla_detalle_auxiliar";
$query .= " WHERE permite_mezclar = '0' group by id_proveedor, id_almacen, id_tipo_producto;";
$datos = new consultarTabla($query);
$resultEncabezado = $datos -> obtenerRegistros();
foreach($resultEncabezado as $encabezado){
	$idOrdenCompra = "ID";
	$arrayIdOrdenesCompra = array();
	$insertaEncabezado = "INSERT INTO ad_ordenes_compra_productos (id_orden_compra, fecha_generacion, id_sucursal, id_proveedor, id_almacen_recepcion, id_tipo_producto, id_usuario_solicita, id_tipo_recepcion_productos, activo, sesion)";
	$insertaEncabezado .= " VALUES (";
	$insertaEncabezado .= " '".$idOrdenCompra."', ";
	$insertaEncabezado .= " now(), ";
	$insertaEncabezado .= "'".$encabezado -> id_sucursal."', ";
	$insertaEncabezado .= "'".$encabezado -> id_proveedor."', ";
	$insertaEncabezado .= "'".$encabezado -> id_almacen."', ";
	$insertaEncabezado .= "'".$encabezado -> id_tipo_producto."', ";
	$insertaEncabezado .= "'".$encabezado -> id_usuario_solicita."', ";
	$insertaEncabezado .= "'1',";	// Por default  1 Acepta Entregas Parciales
	$insertaEncabezado .= "'1',";
	$insertaEncabezado .= "'".$sesion."'";
	$insertaEncabezado .= ");";
	mysql_query($insertaEncabezado) or die("Error en consulta:<br> $insertaEncabezado <br>" . mysql_error());	
	$ultimo_id_insertado = mysql_insert_id();
	
	//Se actualiza la id_orden_compra -->
	$llave = $ultimo_id_insertado;
	#2 Se obtiene el numero de ordenes de compra que se ha generado para el almacen
	$sql = "SELECT COUNT(*) numero_de_ordenes FROM ad_ordenes_compra_productos WHERE id_almacen_recepcion IN (";
	$sql .= " SELECT id_almacen_recepcion FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$llave."'";
	$sql .= " );";
	$result = new consultarTabla($sql);
	$datos = $result -> obtenerRegistros();
	foreach($datos as $registros){
		$numeroDeOrdenes = $registros->numero_de_ordenes;
	}
	#3 Se obtiene la clave del almacen
	$sql = "SELECT clave_almacen FROM ad_almacenes WHERE id_almacen IN(";
	$sql .= " SELECT id_almacen_recepcion FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$llave."'";
	$sql .= " );";
	$result = new consultarTabla($sql);
	$datos = $result -> obtenerRegistros();
	foreach($datos as $registros){
		$claveDeAlmacen = $registros->clave_almacen;
	}
	$idOrdenCompra = $claveDeAlmacen."_ODC".$numeroDeOrdenes;
	$ordenesCompraGeneradas = $ordenesCompraGeneradas.', '.$idOrdenCompra;
	#Se actualiza la clave generada de la orden de compra
	$actualiza = "UPDATE ad_ordenes_compra_productos SET id_orden_compra = '".$idOrdenCompra."' WHERE id_control_orden_compra = '".$llave."';";
	mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
	//Se actualiza la id_orden_compra <--
	
	array_push($arrayIdOrdenesCompra, $ultimo_id_insertado);

	$sUpdateTemporal = "UPDATE tabla_detalle_auxiliar SET id_control_orden_compra = '".$ultimo_id_insertado."'";
	$sUpdateTemporal .= " WHERE id_proveedor = '".$encabezado -> id_proveedor."'";
	$sUpdateTemporal .= " AND id_almacen = '".$encabezado -> id_almacen."'";
	$sUpdateTemporal .= " AND id_sucursal = '".$encabezado -> id_sucursal."'";
	$sUpdateTemporal .= " AND id_tipo_producto = '".$encabezado -> id_tipo_producto."'";	//Solo Cuando No permite mezcla!!
	$sUpdateTemporal .= " AND sesion = '".$sesion."'";
	mysql_query($sUpdateTemporal) or die("Error en consulta:<br> $sUpdateTemporal <br>" . mysql_error());	

	//if($k<=1){$limit = $k;}else{$limit=$k-1;}
	//$sSqlOrden = "SELECT * FROM ad_ordenes_compra_productos ORDER BY id_control_orden_compra DESC LIMIT 0,".$limit;
	$sSqlOrden = "SELECT * FROM ad_ordenes_compra_productos WHERE sesion = '".$sesion."' ORDER BY id_control_orden_compra DESC LIMIT 0,1;";
	$encabezadosODC = new consultarTabla($sSqlOrden);
	$resultEncabezadoODC = $encabezadosODC -> obtenerRegistros();
	foreach($resultEncabezadoODC as $encabezadoOCD){
		$queryAux = "SELECT id_producto, cantidad, precio, importe, fecha_requerida";
		$queryAux .= " FROM tabla_detalle_auxiliar WHERE id_control_orden_compra = '".$encabezadoOCD -> id_control_orden_compra."'";
		$queryAux .= " GROUP BY id_proveedor, id_almacen, id_producto;";
		$datosTablaAuxiliar = new consultarTabla($queryAux);
		$resultDetalle = $datosTablaAuxiliar -> obtenerRegistros();
		foreach($resultDetalle as $detalle){
			//Se obtiene el iva del producto
			$iva = 0;
			$queryIVA = "SELECT ad_tasas_ivas.porcentaje";
			$queryIVA .= " FROM cl_productos_servicios";
			$queryIVA .= " INNER JOIN ad_tasas_ivas";
			$queryIVA .= " ON cl_productos_servicios.id_tasa_iva = ad_tasas_ivas.id_tasa_iva";
			$queryIVA .= " AND cl_productos_servicios.id_producto_servicio = '".$detalle -> id_producto."'";
			$queryIVA .= " AND ad_tasas_ivas.activo = '1'";
			$queryIVA .= " AND cl_productos_servicios.activo = '1'";
			$ivas = new consultarTabla($queryIVA);
			$resultIva = $ivas -> obtenerRegistros();
			foreach($resultIva as $porcentaje){
				$iva = $porcentaje -> porcentaje;
			}
			//Ahora se Inserta el detalle de la Orden de Compra
			$insertaDetalle = "INSERT INTO ad_ordenes_compra_productos_detalle (id_control_orden_compra, id_producto, cantidad, precio_unitario, importe, iva, monto_iva, fecha_entrega_requerida)";
			$insertaDetalle .= " VALUES (";
			$insertaDetalle .= "'".$encabezadoOCD -> id_control_orden_compra."', ";
			$insertaDetalle .= "'".$detalle -> id_producto."', ";
			$insertaDetalle .= "'".$detalle -> cantidad."', ";
			$insertaDetalle .= "'".$detalle -> precio."', ";
			$insertaDetalle .= "'".$detalle -> importe."', ";
			$insertaDetalle .= "'".$iva."',";
			$insertaDetalle .= "'".$detalle -> importe * ($iva/100)."', ";
			$insertaDetalle .= "'".$detalle -> fecha_requerida."'";
			$insertaDetalle .= ");";
			mysql_query($insertaDetalle) or die("Error en consulta:<br> $insertaDetalle <br>" . mysql_error());	
		}
	}
	$IdOrdenesCompra = implode(",", $arrayIdOrdenesCompra);
	//Se modifican los totales en el encabezado, esto se obtendra de el detalle ----->
	$sSql = "SELECT";
	$sSql .= " ad_ordenes_compra_productos.id_almacen_recepcion,";
	$sSql .= " ad_ordenes_compra_productos.id_control_orden_compra,";
	$sSql .= " SUM(ad_ordenes_compra_productos_detalle.importe) subtotal,";
	$sSql .= " SUM(ad_ordenes_compra_productos_detalle.monto_iva) iva,";
	$sSql .= " SUM(ad_ordenes_compra_productos_detalle.importe+ad_ordenes_compra_productos_detalle.monto_iva) total";
	$sSql .= " FROM ad_ordenes_compra_productos";
	$sSql .= " LEFT JOIN ad_ordenes_compra_productos_detalle";
	$sSql .= " ON ad_ordenes_compra_productos.id_control_orden_compra = ad_ordenes_compra_productos_detalle.id_control_orden_compra";
	$sSql .= " WHERE ad_ordenes_compra_productos_detalle.id_control_orden_compra IN (".$IdOrdenesCompra.");";
	$encabezadosODC = new consultarTabla($sSql);
	$resultEncabezadoODC = $encabezadosODC -> obtenerRegistros();
	foreach($resultEncabezadoODC as $encabezadoOCD){
		$sSql = "SELECT COUNT(*) consecutivo, ad_almacenes.clave_almacen clave_almacen
		FROM ad_ordenes_compra_productos LEFT JOIN ad_almacenes
		ON ad_ordenes_compra_productos.id_almacen_recepcion = ad_almacenes.id_almacen
		WHERE ad_ordenes_compra_productos.id_almacen_recepcion = '".$encabezadoOCD -> id_almacen_recepcion."';";
		$consecutivo = new consultarTabla($sSql);
		$resultConsecutivo = $consecutivo -> obtenerRegistros();
		foreach($resultConsecutivo as $consecutivo_id){
			$consecutivo_odc_almacen = $consecutivo_id -> consecutivo;
			$clave_almacen = $consecutivo_id -> clave_almacen;
		}
		$sUpdateEncabezado = "UPDATE ad_ordenes_compra_productos";
		$sUpdateEncabezado .= " SET subtotal = '".$encabezadoOCD->subtotal."',";
		$sUpdateEncabezado .= " iva = '".$encabezadoOCD->iva."',";
		$sUpdateEncabezado .= " total = '".$encabezadoOCD->total."',";
		$sUpdateEncabezado .= " id_orden_compra = '".$clave_almacen."_ODC".$consecutivo_odc_almacen."'";
		$sUpdateEncabezado .= " WHERE id_control_orden_compra = '".$encabezadoOCD->id_control_orden_compra."'";
		$sUpdateEncabezado .= " AND activo = '1';";
		mysql_query($sUpdateEncabezado) or die("Error en consulta:<br> $sUpdateEncabezado <br>" . mysql_error());
	}
	//Se modifican los totales en el encabezado, esto se obtendra de el detalle <-----

	// ---->
	/*
	//Se modifica en la tabla "ad_requisiciones" el campo "id_orden_de_compra_relacionada", 
	//por el id de la orden de compra generada en la tabla "ad_ordenes_compra_productos" del campo "id_control_orden_compra"
	$sSql = "SELECT id_requisicion, id_control_orden_compra, id_producto FROM tabla_detalle_auxiliar;";
	$temporal = new consultarTabla($sSql);
	$resultTemporal = $temporal -> obtenerRegistros();
	//echo $sSql;
	foreach($resultTemporal as $id_temporal){
		$sUpdate = "UPDATE ad_requisiciones_detalle";
		$sUpdate .= " SET id_orden_de_compra_relacionada = '".$id_temporal -> id_control_orden_compra."',";
		$sUpdate .= " id_estatus_detalle_requisicion = '2'"; //YA EN ORDEN DE COMPRA
		$sUpdate .= " WHERE id_requisicion = '".$id_temporal -> id_requisicion."'";
		$sUpdate .= " AND id_producto = '".$id_temporal -> id_producto."'";
		$sUpdate .= " AND activo = '1';";
		mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
	}
	*/
	
	$sSql = "SELECT * FROM tabla_detalle_auxiliar;";
	$temporal = new consultarTabla($sSql);
	$resultTemporal = $temporal -> obtenerRegistros();
	//echo $sSql;
	foreach($resultTemporal as $id_temporal){
		$sUpdate = "UPDATE ad_requisiciones_detalle";
		$sUpdate .= " SET id_orden_de_compra_relacionada  = '".$id_temporal -> id_control_orden_compra."', id_estatus_detalle_requisicion = '2'"; //YA EN ORDEN DE COMPRA
		$sUpdate .= " WHERE id_detalle IN (".$id_temporal -> id_detalle_requisicion.")";
		$sUpdate .= " AND activo = '1';";
		mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
	}
	
	
	// <----
}
//PARA CUANDO **NO** SE PERMITE MEZCLA DE TIPOS DE PRODUCTO = 0 -------------------------------------------------------------------------------------------<

//PARA CUANDO **SÍ** SE PERMITE MEZCLA DE TIPOS DE PRODUCTO = 1 ------------------------------------------------------------------------------------------->
$query = "SELECT id_proveedor, id_almacen, id_sucursal, id_tipo_producto, id_usuario_solicita, SUM(cantidad) FROM tabla_detalle_auxiliar";
$query .= " WHERE permite_mezclar = '1' group by id_proveedor, id_almacen;";
$datos = new consultarTabla($query);
$resultEncabezado = $datos -> obtenerRegistros();
foreach($resultEncabezado as $encabezado){
	$idOrdenCompra = "ID";
	$arrayIdOrdenesCompra = array();
	$insertaEncabezado = "INSERT INTO ad_ordenes_compra_productos (id_orden_compra, fecha_generacion, id_sucursal, id_proveedor, id_almacen_recepcion, id_tipo_producto, id_usuario_solicita, id_tipo_recepcion_productos, activo, sesion)";
	$insertaEncabezado .= " VALUES (";
	$insertaEncabezado .= " '".$idOrdenCompra."', ";
	$insertaEncabezado .= " now(), ";
	$insertaEncabezado .= "'".$encabezado -> id_sucursal."', ";
	$insertaEncabezado .= "'".$encabezado -> id_proveedor."', ";
	$insertaEncabezado .= "'".$encabezado -> id_almacen."', ";
	$insertaEncabezado .= "'".$encabezado -> id_tipo_producto."', ";
	$insertaEncabezado .= "'".$encabezado -> id_usuario_solicita."', ";
	$insertaEncabezado .= "'1',";	// Por default  1 Acepta Entregas Parciales
	$insertaEncabezado .= "'1',";
	$insertaEncabezado .= "'".$sesion."'";
	$insertaEncabezado .= ");";
	mysql_query($insertaEncabezado) or die("Error en consulta:<br> $insertaEncabezado <br>" . mysql_error());	
	$ultimo_id_insertado = mysql_insert_id();
	
	//Se actualiza la id_orden_compra -->
	$llave = $ultimo_id_insertado;
	#2 Se obtiene el numero de ordenes de compra que se ha generado para el almacen
	$sql = "SELECT COUNT(*) numero_de_ordenes FROM ad_ordenes_compra_productos WHERE id_almacen_recepcion IN (";
	$sql .= " SELECT id_almacen_recepcion FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$llave."'";
	$sql .= " );";
	$result = new consultarTabla($sql);
	$datos = $result -> obtenerRegistros();
	foreach($datos as $registros){
		$numeroDeOrdenes = $registros->numero_de_ordenes;
	}
	#3 Se obtiene la clave del almacen
	$sql = "SELECT clave_almacen FROM ad_almacenes WHERE id_almacen IN(";
	$sql .= " SELECT id_almacen_recepcion FROM ad_ordenes_compra_productos WHERE id_control_orden_compra = '".$llave."'";
	$sql .= " );";
	$result = new consultarTabla($sql);
	$datos = $result -> obtenerRegistros();
	foreach($datos as $registros){
		$claveDeAlmacen = $registros->clave_almacen;
	}
	$idOrdenCompra = $claveDeAlmacen."_ODC".$numeroDeOrdenes;
	$ordenesCompraGeneradas = $ordenesCompraGeneradas.', '.$idOrdenCompra;
	#Se actualiza la clave generada de la orden de compra
	$actualiza = "UPDATE ad_ordenes_compra_productos SET id_orden_compra = '".$idOrdenCompra."' WHERE id_control_orden_compra = '".$llave."';";
	mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
	//Se actualiza la id_orden_compra <--
	
	array_push($arrayIdOrdenesCompra, $ultimo_id_insertado);

	$sUpdateTemporal = "UPDATE tabla_detalle_auxiliar SET id_control_orden_compra = '".$ultimo_id_insertado."'";
	$sUpdateTemporal .= " WHERE id_proveedor = '".$encabezado -> id_proveedor."'";
	$sUpdateTemporal .= " AND id_almacen = '".$encabezado -> id_almacen."'";
	$sUpdateTemporal .= " AND id_sucursal = '".$encabezado -> id_sucursal."'";
	//$sUpdateTemporal .= " AND id_tipo_producto = '".$encabezado -> id_tipo_producto."'";	//Solo Cuando No permite mezcla!!
	$sUpdateTemporal .= " AND sesion = '".$sesion."'";
	mysql_query($sUpdateTemporal) or die("Error en consulta:<br> $sUpdateTemporal <br>" . mysql_error());	

	//if($k<=1){$limit = $k;}else{$limit=$k-1;}
	//$sSqlOrden = "SELECT * FROM ad_ordenes_compra_productos ORDER BY id_control_orden_compra DESC LIMIT 0,".$limit;
	$sSqlOrden = "SELECT * FROM ad_ordenes_compra_productos WHERE sesion = '".$sesion."' ORDER BY id_control_orden_compra DESC LIMIT 0,1;";
	$encabezadosODC = new consultarTabla($sSqlOrden);
	$resultEncabezadoODC = $encabezadosODC -> obtenerRegistros();
	foreach($resultEncabezadoODC as $encabezadoOCD){
		$queryAux = "SELECT id_producto, cantidad, precio, importe, fecha_requerida";
		$queryAux .= " FROM tabla_detalle_auxiliar WHERE id_control_orden_compra = '".$encabezadoOCD -> id_control_orden_compra."'";
		$queryAux .= " GROUP BY id_proveedor, id_almacen, id_producto;";
		$datosTablaAuxiliar = new consultarTabla($queryAux);
		$resultDetalle = $datosTablaAuxiliar -> obtenerRegistros();
		foreach($resultDetalle as $detalle){
			//Se obtiene el iva del producto
			$iva = 0;
			$queryIVA = "SELECT ad_tasas_ivas.porcentaje";
			$queryIVA .= " FROM cl_productos_servicios";
			$queryIVA .= " INNER JOIN ad_tasas_ivas";
			$queryIVA .= " ON cl_productos_servicios.id_tasa_iva = ad_tasas_ivas.id_tasa_iva";
			$queryIVA .= " AND cl_productos_servicios.id_producto_servicio = '".$detalle -> id_producto."'";
			$queryIVA .= " AND ad_tasas_ivas.activo = '1'";
			$queryIVA .= " AND cl_productos_servicios.activo = '1'";
			$ivas = new consultarTabla($queryIVA);
			$resultIva = $ivas -> obtenerRegistros();
			foreach($resultIva as $porcentaje){
				$iva = $porcentaje -> porcentaje;
			}
			//Ahora se Inserta el detalle de la Orden de Compra
			$insertaDetalle = "INSERT INTO ad_ordenes_compra_productos_detalle (id_control_orden_compra, id_producto, cantidad, precio_unitario, importe, iva, monto_iva, fecha_entrega_requerida)";
			$insertaDetalle .= " VALUES (";
			$insertaDetalle .= "'".$encabezadoOCD -> id_control_orden_compra."', ";
			$insertaDetalle .= "'".$detalle -> id_producto."', ";
			$insertaDetalle .= "'".$detalle -> cantidad."', ";
			$insertaDetalle .= "'".$detalle -> precio."', ";
			$insertaDetalle .= "'".$detalle -> importe."', ";
			$insertaDetalle .= "'".$iva."',";
			$insertaDetalle .= "'".$detalle -> importe * ($iva/100)."', ";
			$insertaDetalle .= "'".$detalle -> fecha_requerida."'";
			$insertaDetalle .= ");";
			mysql_query($insertaDetalle) or die("Error en consulta:<br> $insertaDetalle <br>" . mysql_error());	
		}
	}
	$IdOrdenesCompra = implode(",", $arrayIdOrdenesCompra);
	//Se modifican los totales en el encabezado, esto se obtendra de el detalle ----->
	$sSql = "SELECT";
	$sSql .= " ad_ordenes_compra_productos.id_almacen_recepcion,";
	$sSql .= " ad_ordenes_compra_productos.id_control_orden_compra,";
	$sSql .= " SUM(ad_ordenes_compra_productos_detalle.importe) subtotal,";
	$sSql .= " SUM(ad_ordenes_compra_productos_detalle.monto_iva) iva,";
	$sSql .= " SUM(ad_ordenes_compra_productos_detalle.importe+ad_ordenes_compra_productos_detalle.monto_iva) total";
	$sSql .= " FROM ad_ordenes_compra_productos";
	$sSql .= " LEFT JOIN ad_ordenes_compra_productos_detalle";
	$sSql .= " ON ad_ordenes_compra_productos.id_control_orden_compra = ad_ordenes_compra_productos_detalle.id_control_orden_compra";
	$sSql .= " WHERE ad_ordenes_compra_productos_detalle.id_control_orden_compra IN (".$IdOrdenesCompra.");";
	$encabezadosODC = new consultarTabla($sSql);
	$resultEncabezadoODC = $encabezadosODC -> obtenerRegistros();
	foreach($resultEncabezadoODC as $encabezadoOCD){
		$sSql = "SELECT COUNT(*) consecutivo, ad_almacenes.clave_almacen clave_almacen
		FROM ad_ordenes_compra_productos LEFT JOIN ad_almacenes
		ON ad_ordenes_compra_productos.id_almacen_recepcion = ad_almacenes.id_almacen
		WHERE ad_ordenes_compra_productos.id_almacen_recepcion = '".$encabezadoOCD -> id_almacen_recepcion."';";
		$consecutivo = new consultarTabla($sSql);
		$resultConsecutivo = $consecutivo -> obtenerRegistros();
		foreach($resultConsecutivo as $consecutivo_id){
			$consecutivo_odc_almacen = $consecutivo_id -> consecutivo;
			$clave_almacen = $consecutivo_id -> clave_almacen;
		}
		$sUpdateEncabezado = "UPDATE ad_ordenes_compra_productos";
		$sUpdateEncabezado .= " SET subtotal = '".$encabezadoOCD->subtotal."',";
		$sUpdateEncabezado .= " iva = '".$encabezadoOCD->iva."',";
		$sUpdateEncabezado .= " total = '".$encabezadoOCD->total."',";
		$sUpdateEncabezado .= " id_orden_compra = '".$clave_almacen."_ODC".$consecutivo_odc_almacen."'";
		$sUpdateEncabezado .= " WHERE id_control_orden_compra = '".$encabezadoOCD->id_control_orden_compra."'";
		$sUpdateEncabezado .= " AND activo = '1';";
		mysql_query($sUpdateEncabezado) or die("Error en consulta:<br> $sUpdateEncabezado <br>" . mysql_error());
	}
	//Se modifican los totales en el encabezado, esto se obtendra de el detalle <-----

	// ---->
	
	/*
	//Se modifica en la tabla "ad_requisiciones" el campo "id_orden_de_compra_relacionada", 
	//por el id de la orden de compra generada en la tabla "ad_ordenes_compra_productos" del campo "id_control_orden_compra"
	$sSql = "SELECT id_requisicion, id_control_orden_compra, id_producto FROM tabla_detalle_auxiliar;";
	$temporal = new consultarTabla($sSql);
	$resultTemporal = $temporal -> obtenerRegistros();
	//echo $sSql;
	foreach($resultTemporal as $id_temporal){
		$sUpdate = "UPDATE ad_requisiciones_detalle";
		$sUpdate .= " SET id_orden_de_compra_relacionada = '".$id_temporal -> id_control_orden_compra."',";
		$sUpdate .= " id_estatus_detalle_requisicion = '2'"; //YA EN ORDEN DE COMPRA
		$sUpdate .= " WHERE id_requisicion = '".$id_temporal -> id_requisicion."'";
		$sUpdate .= " AND id_producto = '".$id_temporal -> id_producto."'";
		$sUpdate .= " AND activo = '1';";
		mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
	}
	*/
	
	$sSql = "SELECT * FROM tabla_detalle_auxiliar;";
	$temporal = new consultarTabla($sSql);
	$resultTemporal = $temporal -> obtenerRegistros();
	foreach($resultTemporal as $id_temporal){
		$sUpdate = "UPDATE ad_requisiciones_detalle";
		$sUpdate .= " SET id_orden_de_compra_relacionada = '".$id_temporal -> id_control_orden_compra."', id_estatus_detalle_requisicion = '2'"; //YA EN ORDEN DE COMPRA
		$sUpdate .= " WHERE id_detalle IN (".$id_temporal -> id_detalle_requisicion.")";
		$sUpdate .= " AND activo = '1';";
		mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
	}
	// <----
}
//PARA CUANDO **SÍ** SE PERMITE MEZCLA DE TIPOS DE PRODUCTO = 1 -------------------------------------------------------------------------------------------<




//Se limpia la tabla auxiliar del detalle 
$sDeteleAuxiliar = "DELETE FROM tabla_detalle_auxiliar WHERE sesion = '".$sesion."';";
mysql_query($sDeteleAuxiliar) or die("Error en consulta:<br> $sTruncate <br>" . mysql_error());

//Se envia el mensaje de éxito
$ordenesCompraGeneradas = substr($ordenesCompraGeneradas,2,strlen($ordenesCompraGeneradas));
echo 'Las siguientes órdenes de compra: [ '.$ordenesCompraGeneradas.' ], se generaron exitosamente';
//echo $fechasRequeridasAux[0].', '.$fechasRequeridasAux[1].' : '.$fechasRequeridas;
?>


