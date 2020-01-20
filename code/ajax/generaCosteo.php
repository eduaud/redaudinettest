<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");


$idMovimientos = $_POST['idMovimientos'];
$idControlMovimientos = $_POST['idControlMovimientos'];
$idOrdenesCompra = $_POST['idOrdenesCompra'];
$idControlOrdenesCompra = $_POST['idControlOrdenesCompra'];
$idProveedores = $_POST['idProveedores'];

$idControlMovimientosAux = explode(",", $idControlMovimientos);
$cuentaControlMovimientos = count(array_filter($idControlMovimientosAux));

$idMovimientosAux = explode(",", $idMovimientos);
$cuentaMovimientos = count(array_filter($idMovimientosAux));

$idOrdenesCompraAux = explode(",", $idOrdenesCompra);
$cuentaOrdenesCompra = count(array_filter($idOrdenesCompraAux));

$idControlOrdenesCompraAux = explode(",", $idControlOrdenesCompra);
$cuentaControlOrdenesCompra = count(array_filter($idControlOrdenesCompraAux));

$idProveedoresAux = explode(",", $idProveedores);
$cuentaProveedores = count(array_filter($idProveedoresAux));

$idProveedor = $idProveedoresAux[0];	//Aquí es indiferente la posición en el arreglo, ya que todos traen el mismo ID del proveedor.
//Inserta el Encabezado del Costeo --->
//Solo se hace una inserción en el encabezado
$sInsertEncabezado = "INSERT INTO ad_costeo_productos (id_proveedor, id_moneda, tipo_cambio, id_usuario_registro, total_productos, no_modificable, genero_cuenta_por_pagar, fecha)";
$sInsertEncabezado .= "VALUES(";
$sInsertEncabezado .= "'".$idProveedor."',";
$sInsertEncabezado .= "'1',";
$sInsertEncabezado .= "'1',";
$sInsertEncabezado .= "'".$_SESSION["USR"]->userid."',";
$sInsertEncabezado .= "'0',";
$sInsertEncabezado .= "'0',";
$sInsertEncabezado .= "'0',";
$sInsertEncabezado .= "now()";
$sInsertEncabezado .= ")";
mysql_query($sInsertEncabezado) or die("Error en consulta:<br> $sInsertEncabezado <br>" . mysql_error());
$ultimo_id_insertado = mysql_insert_id();
//Inserta el Encabezado del Costeo <---


//Inserta el Detalle del Costeo --->
$sSql = "SELECT";
//$sSql .= " ad_movimientos_almacen_detalle.id_producto,";
$sSql .= " ad_movimientos_almacen_detalle.id_producto_origen,";
$sSql .= " ad_movimientos_almacen_detalle.id_lote,";
$sSql .= " id_orden_compra_producto_detalle,";
$sSql .= " ad_ordenes_compra_productos.id_control_orden_compra,";
$sSql .= " id_orden_compra,";
$sSql .= " id_movimiento,";
$sSql .= " SUM(ad_movimientos_almacen_detalle.cantidad) cantidad,";
//$sSql .= " SUM(ad_ordenes_compra_productos_detalle.precio_unitario) precio_unitario";
$sSql .= " ad_ordenes_compra_productos_detalle.precio_unitario precio_unitario";
$sSql .= " FROM ad_movimientos_almacen";
$sSql .= " INNER JOIN ad_movimientos_almacen_detalle";
$sSql .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento";
$sSql .= " INNER JOIN ad_ordenes_compra_productos_detalle";
$sSql .= " ON ad_ordenes_compra_productos_detalle.id_detalle = ad_movimientos_almacen_detalle.id_orden_compra_producto_detalle";
$sSql .= " INNER JOIN ad_ordenes_compra_productos";
$sSql .= " ON ad_ordenes_compra_productos.id_control_orden_compra = ad_ordenes_compra_productos_detalle.id_control_orden_compra";
$sSql .= " AND ad_movimientos_almacen.id_control_movimiento IN (".$idControlMovimientos.")";
$sSql .= " AND ad_movimientos_almacen.activo = '1'";
$sSql .= " GROUP BY ad_movimientos_almacen_detalle.id_producto, ad_movimientos_almacen_detalle.id_lote;";
$datos = new consultarTabla($sSql);
$result = $datos -> obtenerRegistros();

$total_cantidad = 0;
$total_productos = 0;
$i=0;
foreach($result as $resultado){
	$id_producto = $resultado -> id_producto_origen;
	$id_lote = $resultado -> id_lote;
	$id_orden_compra_producto_detalle = $resultado -> id_orden_compra_producto_detalle;
	$id_control_orden_compra = $resultado -> id_control_orden_compra;
	$id_orden_compra = $resultado -> id_orden_compra;
	$id_movimiento = $resultado -> id_movimiento;
	$cantidad = $resultado -> cantidad;
	$precio_unitario = $resultado -> precio_unitario;

	$insertaDetalle = "INSERT INTO ad_costeo_productos_detalle (";
	$insertaDetalle .= " id_costeo_productos,";
	$insertaDetalle .= " id_control_orden_compra,";
	$insertaDetalle .= " id_orden_compra,";
	$insertaDetalle .= " id_control_movimiento,";
	$insertaDetalle .= " id_movimiento,";
	$insertaDetalle .= " id_producto,";
	$insertaDetalle .= " cantidad_solicitada,";
	$insertaDetalle .= " cantidad,";
	$insertaDetalle .= " costo_por_unidad_odc,";
	$insertaDetalle .= " costo_neto_unitario,";
	$insertaDetalle .= " importe,";
	$insertaDetalle .= " porcentaje,";
	$insertaDetalle .= " monto_proporcional_otros_servicios,";
	$insertaDetalle .= " costo_lote,";
	$insertaDetalle .= " id_orden_compra_producto_detalle,";
	$insertaDetalle .= " id_lote";
	$insertaDetalle .= " )";
	$insertaDetalle .= " VALUES (";
	$insertaDetalle .= "'".$ultimo_id_insertado."', "; 					//id_costeo_productos
	$insertaDetalle .= "'".$id_control_orden_compra."', ";				//id_control_orden_compra
	$insertaDetalle .= "'".$id_orden_compra."', ";						//id_orden_compra (Es el compuesto)
	$insertaDetalle .= "'".$id_orden_compra."', ";						//id_control_movimiento
	$insertaDetalle .= "'".$id_movimiento."', ";						//id_movimiento
	$insertaDetalle .= "'".$id_producto."', ";							//id_producto
	$insertaDetalle .= "'0', ";											//cantidad_solicitada
	$insertaDetalle .= "'".$cantidad."',";								//cantidad
	$insertaDetalle .= "'".$precio_unitario."',";						//costo_por_unidad
	$insertaDetalle .= "'".$precio_unitario."',";						//costo_neto_unitario
	$insertaDetalle .= "'".($cantidad*$precio_unitario)."',";			//importe
	$insertaDetalle .= "'',";											//porcentaje
	$insertaDetalle .= "'',";											//monto_proporcional_otros_servicios
	$insertaDetalle .= "'',";											//costo_lote
	$insertaDetalle .= "'".$id_orden_compra_producto_detalle."',";		//id_orden_compra_producto_detalle
	$insertaDetalle .= "'".$id_lote."'";								//id_lote
	$insertaDetalle .= ");";
	$total_cantidad = $total_cantidad + $cantidad;
	$total_productos = $total_productos + ($cantidad*$precio_unitario);
	
	mysql_query($insertaDetalle) or die("Error en consulta:<br> $insertaDetalle <br>" . mysql_error());	
	$ultimo_id_insertado_detalle = mysql_insert_id();

	//Se actualiza en la tabla ad_movimientos_almacen_detalle, los campos id_costeo_productos, id_costeo_producto_detalle -->
	$sUpdate = "UPDATE ad_movimientos_almacen_detalle SET";
	$sUpdate .= " id_costeo_productos = '".$ultimo_id_insertado."',";
	$sUpdate .= " id_costeo_producto_detalle = '".$ultimo_id_insertado_detalle."'";
	$sUpdate .= " WHERE id_control_movimiento = '".$idControlMovimientosAux[$i]."'";
	mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());	
	//Se actualiza en la tabla ad_movimientos_almacen_detalle, los campos id_costeo_productos, id_costeo_producto_detalle <--
	$i++;
}
//Inserta el Detalle del Costeo <---

/********************Iniciamos el ciclo de nuevo para insertar los calculos de porcentaje de costeo de acuerdo al total de productos sacados anteriormente**********/
$sSqlProcentaje = "SELECT * FROM ad_costeo_productos_detalle WHERE id_costeo_productos = '".$ultimo_id_insertado."'";
$datosPorcentajes = new consultarTabla($sSqlProcentaje);
$resultPorcentajes = $datosPorcentajes -> obtenerRegistros();
foreach($resultPorcentajes as $resultadoPorcentajes){
	$importe = $resultadoPorcentajes -> cantidad * $resultadoPorcentajes -> costo_por_unidad_odc;
	$porcentaje_s =  $importe * 100;
	$porcentaje = $porcentaje_s / $total_productos;
	//$porcentaje = number_format($porcentaje, 2);
	$actualiza = "UPDATE ad_costeo_productos_detalle SET porcentaje = ".$porcentaje." WHERE id_costeo_productos = ".$ultimo_id_insertado." AND id_costeo_producto_detalle = ".$resultadoPorcentajes -> id_costeo_producto_detalle;
	mysql_query($actualiza) or die("Error en consulta:<br> $actualiza <br>" . mysql_error());
}

//Se modifica en el encabezado totales, que se obtienen del detalle -->
$sUpdate = "UPDATE ad_costeo_productos SET total_productos = '".$total_productos."' WHERE id_costeo_productos = '".$ultimo_id_insertado."'";
mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());	
//Se modifica en el encabezado totales, que se obtienen del detalle <--

//Se actualiza en la tabla ad_movimientos_almacen, el campo id_costeo_productos -->
//$sUpdate = "UPDATE ad_movimientos_almacen SET id_costeo_productos = '".$ultimo_id_insertado."' WHERE id_control_movimiento = '".$idControlMovimientosAux[0]."'";
$sUpdate = "UPDATE ad_movimientos_almacen SET id_costeo_productos = '".$ultimo_id_insertado."' WHERE id_control_movimiento IN (".$idControlMovimientos.")";
mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());	
//Se actualiza en la tabla ad_movimientos_almacen, el campo id_costeo_productos <--

//echo 'La información se registró correctamente '.$sSql;
echo 'La información se registró correctamente';
?>