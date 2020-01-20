<?php
	
	include("../../include/fpdf153/fpdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	include("../../consultaBase.php");

	$orden = $_GET['orden'];
	
	Global $logo;
	Global $folio;
	
	$sql = "SELECT na_proveedores.razon_social AS proveedor, DATE_FORMAT(na_ordenes_compra.fecha_creacion, '%d/%m/%Y') AS fecha,
			DATE_FORMAT(na_ordenes_compra.fecha_entrega, '%d/%m/%Y') AS fecha_entrega,
			ad_mercancia_puesta.nombre AS mercancia_puesta, na_estatus_orden_compra.nombre AS estatus, na_ordenes_compra.id_orden_compra AS id,
			CONCAT('$', FORMAT(subtotal,2)) AS subtotal, CONCAT('$', FORMAT(iva,2)) AS iva, CONCAT('$', FORMAT(total,2)) AS total, na_ordenes_compra.observaciones AS observaciones,
			CONCAT('Calle ', ad_almacenes.calle, ', No. Ext. ', ad_almacenes.numero_exterior, ', Col. ', ad_almacenes.colonia, ', 
			Del. o Mun. ', ad_almacenes.delegacion_municipio, ', ',na_estados.nombre) AS dir_cedis,
			CONCAT('Calle ', na_proveedores.calle, ', No. Ext. ', na_proveedores.numero_exterior, ', Col. ', na_proveedores.colonia, ', 
			Del. o Mun. ', na_proveedores.delegacion_municipio, ', ',na_estados.nombre) AS dir_proveedor
			FROM na_ordenes_compra
			LEFT JOIN na_proveedores USING(id_proveedor)
			LEFT JOIN ad_mercancia_puesta USING(id_mercancia_puesta)
			LEFT JOIN na_estatus_orden_compra USING(id_estatus_orden_compra)
			LEFT JOIN ad_almacenes USING(id_almacen)
			LEFT JOIN na_ciudades ON ad_almacenes.id_ciudad = na_ciudades.id_ciudad
			LEFT JOIN na_estados ON ad_almacenes.id_estado = na_estados.id_estado
			WHERE na_ordenes_compra.id_orden_compra=" . $orden;
	$consulta = new consultarTabla($sql);
	$ordenCompra = $consulta -> obtenerRegistros();
	
	/*print_r($totalesReg);
	die();*/
	
	$sqlDetalle = "SELECT na_productos.nombre AS producto, cantidad AS cantidad, precio_unitario AS unitario, importe AS importe, porcentaje_descuento AS descuento,
					precio_final AS final_precio, na_ordenes_compra_producto_detalle.observaciones AS observaciones
					FROM na_ordenes_compra_producto_detalle
					LEFT JOIN na_productos USING(id_producto)
					WHERE na_ordenes_compra_producto_detalle.id_orden_compra = " . $orden;
	$consultaDet = new consultarTabla($sqlDetalle);
	$ordenCompraDet = $consultaDet -> obtenerRegistros();
			
	$logo = "../../fotos/[EAC29F5C-FF24-92F7-C30B-BF3B1F17FD0E]bG9nbyBuYXNzZXIuanBn.jpg";	
		
	/*************Consecutivo de recibo*********************/
	
	$sql4 = "SELECT consecutivo FROM na_ordenes_compra WHERE id_orden_compra = " . $orden;
	$datos4 = new consultarTabla($sql4);
	$result4 = $datos4 -> obtenerLineaRegistro();

	if($result4['consecutivo'] == ""){
			$sqlF = "SELECT MAX(consecutivo) AS folio FROM na_ordenes_compra";
			$datosF = new consultarTabla($sqlF);
			$resultF = $datosF -> obtenerLineaRegistro();

			if($resultF['folio'] == ""){
					$folio = 1;
					}
			else{
					$folio = $resultF['folio'] + 1;
					}
			
			$actualiza = "UPDATE na_ordenes_compra SET consecutivo = " . $folio . " WHERE id_orden_compra = " . $orden;
			mysql_query($actualiza);
			}	
	else{
			$folio = $result4['consecutivo'];
			}
	/*********************************************************/		
	
	
	include("ordenCompra_pdf.php");
	
			
	
			
			

?>