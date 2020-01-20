<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$proceso = $_POST['proceso'];
switch ($proceso) {
	case 'requisiciones':

		// Recupera Valores
		$id = $_POST['id'];
		$usuario = $_POST['idUsuarioSolicitante'];
		$fecha_inicial = $_POST['fecini'];
		$fecha_final = $_POST['fecfin'];
		
/*	
		//Formatea Fecha Inicial AAAA-MM-DD
		$dia = substr($fecini, 0, 2);
		$mes = substr($fecini, 3, 2);
		$anio = substr($fecini, -4);
		$fecha_inicial = $anio . "-" . $mes . "-" . $dia;
	
		//Formate Fecha Final AAAA-MM-DD
		$dia = substr($fecfin, 0, 2);
		$mes = substr($fecfin, 3, 2);
		$anio = substr($fecfin, -4);
		$fecha_final = $anio . "-" . $mes . "-" . $dia;
*/
		//Crea cadena de condiciones del query 
		$where = "";
		if($id != -1) { $where .= " AND ad_requisiciones.id_sucursal = " . $id; }
		if($usuario != -1) { $where .= " AND sys_usuarios.id_usuario = " . $usuario; }
		if(isset($fecini) && isset($fecfin)) {
			$where .= " AND ad_requisiciones.fecha_de_creacion BETWEEN '" . $fecha_inicial . " 00:00:00' AND '" . $fecha_final . " 23:59:59'";
		}
		if(!empty($fecini) && empty($fecfin)) {
			$where .= " AND ad_requisiciones.fecha_de_creacion = '" . $fecha_inicial . "'";
		}
		if(empty($fecini) && !empty($fecfin)) {
			$where .= " AND ad_requisiciones.fecha_de_creacion ='" . $fecha_final . "'";
		}

		// Se obtiene las Ordenes de Compra que estan es espera de Aprobacion-------------------------
		// Es decir, id_estatus_requisicion = 1
		$sql = "SELECT";
		$sql .= " ad_sucursales.nombre 'Plaza Solicitante',";
		$sql .= " id_requisicion 'ID Requisición',";
		$sql .= " DATE_FORMAT(fecha_de_creacion,'%d/%m/%Y') 'Fecha de Creación',";
		$sql .= " DATE_FORMAT(fecha_requerida,'%d/%m/%Y') 'Fecha Requerida',";
		$sql .= " CONCAT(sys_usuarios.nombres,' ',sys_usuarios.apellido_paterno,' ',sys_usuarios.apellido_materno) 'Usuario Solicitante'";
		$sql .= " FROM ad_requisiciones";
		$sql .= " LEFT JOIN ad_sucursales";
		$sql .= " ON ad_requisiciones.id_sucursal = ad_sucursales.id_sucursal";
		$sql .= " LEFT JOIN sys_usuarios";
		$sql .= " ON ad_requisiciones.id_usuario_solicita = sys_usuarios.id_usuario";
		$sql .= " WHERE ad_requisiciones.id_estatus_requisicion = 1 ". $where;

		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerArregloRegistros();

		$smarty -> assign("filas", $result);
		$smarty -> assign("sql", $sql);
		//echo $sql;
		echo $smarty->fetch('especiales/respuestaTablaRequisicionesPendientesDeAprobacion.tpl');
		break;

	case 'ordenes_compra_autorizadas':

		// Recupera Valores
		$id = $_POST['id'];
		$usuario = $_POST['idUsuarioSolicitante'];
		$fecha_inicial = $_POST['fecini'];
		$fecha_final = $_POST['fecfin'];
		$idRequisicion = $_POST['idRequisicion'];
		$idProducto = $_POST['idProducto'];
	/*	
	
		//Formatea Fecha Inicial AAAA-MM-DD
		$dia = substr($fecini, 0, 2);
		$mes = substr($fecini, 3, 2);
		$anio = substr($fecini, -4);
		$fecha_inicial = $anio . "-" . $mes . "-" . $dia;
	
		//Formate Fecha Final AAAA-MM-DD
		$dia = substr($fecfin, 0, 2);
		$mes = substr($fecfin, 3, 2);
		$anio = substr($fecfin, -4);
		$fecha_final = $anio . "-" . $mes . "-" . $dia;
*/
		//Crea cadena de condiciones del query 
		$where = "";

		if(!empty($idRequisicion))
			$where .= " AND ad_requisiciones.id_requisicion LIKE '%" . $idRequisicion."%'";
		else{
			if($id != -1) { $where .= " AND ad_requisiciones.id_sucursal = " . $id; }
			if($usuario != -1) { $where .= " AND sys_usuarios.id_usuario = " . $usuario; }
			if($idProducto != -1) {$where .= " AND ad_requisiciones_detalle.id_producto = ".$idProducto; }
			if(!empty($fecini) && !empty($fecfin))
				//$where .= " AND ad_requisiciones.fecha_de_creacion BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
				$where .= " AND ad_requisiciones.fecha_de_creacion BETWEEN '" . $fecha_inicial . " 00:00:00' AND '" . $fecha_final . " 23:59:59'";
			/*
			if(!empty($fecini) && empty($fecfin)) 
				$where .= " AND ad_requisiciones.fecha_de_creacion = '" . $fecha_inicial . "'";
			
			if(empty($fecini) && !empty($fecfin)) 
				$where .= " AND ad_requisiciones.fecha_de_creacion ='" . $fecha_final . "'";
			*/
		}
		// Se obtiene las Ordenes de Compra que ya estan aprobadas -------------------------
		// Es decir, id_estatus_requisicion = 2
		$sql = "SELECT";
		$sql .= " ad_requisiciones.id_requisicion,";
		$sql .= " cl_tipos_producto_servicio.nombre,";
		$sql .= " cl_productos_servicios.clave,";
		$sql .= " cl_productos_servicios.nombre,";
		$sql .= " ad_requisiciones_detalle.cantidad,";
		$sql .= " ad_sucursales.nombre,";
		$sql .= " ad_requisiciones_detalle.id_distribuidor_solicita,";
		$sql .= " CONCAT(sys_usuarios.nombres, sys_usuarios.apellido_paterno, sys_usuarios.apellido_materno) nombre,";
		$sql .= " DATE_FORMAT(ad_requisiciones.fecha_de_creacion,'%d/%m/%Y') fecha_de_creacion,";
		$sql .= " DATE_FORMAT(ad_requisiciones.fecha_requerida,'%d/%m/%Y') fecha_requerida,";
		$sql .= " ad_requisiciones_detalle.id_producto,";
		$sql .= " ad_requisiciones_detalle.id_detalle,";
		$sql .= " ad_requisiciones.id_sucursal,";
		$sql .= " ad_requisiciones.id_usuario_solicita,";
		$sql .= " ad_requisiciones_detalle.precio,";
		$sql .= " ad_requisiciones_detalle.importe,";
		$sql .= " cl_tipos_producto_servicio.id_tipo_producto_servicio";
		$sql .= " FROM ad_requisiciones";
		$sql .= " LEFT JOIN ad_requisiciones_detalle";
		$sql .= " ON ad_requisiciones.id_requisicion = ad_requisiciones_detalle.id_requisicion";
		$sql .= " LEFT JOIN cl_productos_servicios";
		$sql .= " ON ad_requisiciones_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
		$sql .= " LEFT JOIN cl_tipos_producto_servicio";
		$sql .= " ON cl_productos_servicios.id_tipo_producto_servicio = cl_tipos_producto_servicio.id_tipo_producto_servicio";
		$sql .= " LEFT JOIN ad_sucursales";
		$sql .= " ON ad_requisiciones.id_sucursal = ad_sucursales.id_sucursal";
		$sql .= " LEFT JOIN sys_usuarios";
		$sql .= " ON ad_requisiciones.id_usuario_solicita = sys_usuarios.id_usuario";
		$sql .= " WHERE ad_requisiciones.id_estatus_requisicion = '2'"; // 2:Autorizada
		$sql .= " AND ad_requisiciones.activo = '1'";
		$sql .= " AND ad_requisiciones_detalle.activo = '1'";
		$sql .= " AND IFNULL(ad_requisiciones_detalle.id_orden_de_compra_relacionada,0) = '0'";
		$sql .= $where;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerArregloRegistros();
		$smarty -> assign("filas", $result);
		$smarty->assign('sql',$sql);


		// SE OBTIENEN LOS PROVEEDORES DE CADA PRODUCTO EN EL DETALLE DE LA ORDEN DE COMPRA --------------------
		/*
		for($i=0;$i<count($result);$i++){
			$id_producto = $result[$i]['id_producto'];
			$sql = "SELECT";
			$sql .= " ad_proveedores_productos.id_proveedor, razon_social, ad_proveedores.permite_mezclar_tipo_producto_en_ODC";
			$sql .= " FROM ad_proveedores";
			$sql .= " LEFT JOIN ad_proveedores_productos";
			$sql .= " ON ad_proveedores.id_proveedor = ad_proveedores_productos.id_proveedor";
			$sql .= " WHERE activo = 1";
			$sql .= " AND ad_proveedores_productos.id_producto = ".$id_producto;
			$sql .= " ORDER BY razon_social";
			$datosProveedor = new consultarTabla($sql);
			$resultP = $datosProveedor -> obtenerArregloRegistros();
			$smarty->assign("proveedor_detalles", $resultP);
			//$smarty->assign('sql',$sql);
		}
		*/
		// SE OBTIENEN LOS ALMACENES DE LAS PLAZAS INDEPENDIENTE DE EL PRODUCTO -------------
		/*
		$sql = "SELECT";
		$sql .= " ad_proveedores_productos.id_proveedor, razon_social";
		$sql .= " FROM ad_proveedores";
		$sql .= " LEFT JOIN ad_proveedores_productos";
		$sql .= " ON ad_proveedores.id_proveedor = ad_proveedores_productos.id_proveedor";
		$sql .= " WHERE activo = 1";
		$sql .= " ORDER BY razon_social";
		*/
		$sql = "SELECT id_almacen, nombre FROM ad_almacenes;";
		$datosAlmacenDePlaza = new consultarTabla($sql);
		$result = $datosAlmacenDePlaza -> obtenerArregloRegistros();
		$smarty -> assign("almacen", $result);
		
		echo $smarty->fetch('especiales/respuestaTablaODCPartiendoDeRequisicionAutorizada.tpl');
		break;
/*		
	case 'provedores_odc':
		// Se obtiene las Ordenes de Compra que ya estan aprobadas -------------------------
		// Es decir, id_estatus_requisicion = 2
		$sql = "SELECT id_proveedor, razon_social FROM ad_proveedores";
		$proveedores = new consultarTabla($sql);
		$result = $proveedores -> obtenerArregloRegistros();
		$smarty -> assign("proveedores", $result);

		//echo $smarty->fetch('especiales/respuestaTablaODCPartiendoDeRequisicionAutorizada.tpl');
		break;
*/

	case 'ordenes_compra_pendientes_de_aprobacion':

		// Recupera Valores
		$idSucursal = $_POST['idSucursal'];
		$usuario = $_POST['idUsuarioSolicitante'];
		$fecha_inicial = $_POST['fecini'];
		$fecha_final = $_POST['fecfin'];
		$idOrdenCompra = $_POST['idOrdenCompra'];

		/*
		//Formatea Fecha Inicial AAAA-MM-DD
		$dia = substr($fecini, 0, 2);
		$mes = substr($fecini, 3, 2);
		$anio = substr($fecini, -4);
		$fecha_inicial = $anio . "-" . $mes . "-" . $dia;
	
		//Formate Fecha Final AAAA-MM-DD
		$dia = substr($fecfin, 0, 2);
		$mes = substr($fecfin, 3, 2);
		$anio = substr($fecfin, -4);
		$fecha_final = $anio . "-" . $mes . "-" . $dia;
		*/

		//Crea cadena de condiciones del query 
		$where = "";

		if($idOrdenCompra != "") { 
			$where .= " AND ad_ordenes_compra_productos.id_orden_compra LIKE '%" . $idOrdenCompra."%'";
		}else{
			if($idSucursal != -1) $where .= " AND ad_ordenes_compra_productos.id_sucursal = " . $idSucursal;
			if($usuario != -1) $where .= " AND ad_ordenes_compra_productos.id_usuario_solicita = " . $usuario;
			/*if(!empty($fecini) && !empty($fecfin)) $where .= " AND ad_ordenes_compra_productos.fecha_de_creacion BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
			if(!empty($fecini) && empty($fecfin)) $where .= " AND ad_ordenes_compra_productos.fecha_de_creacion = '" . $fecha_inicial . "'";
			if(empty($fecini) && !empty($fecfin)) $where .= " AND ad_ordenes_compra_productos.fecha_de_creacion ='" . $fecha_final . "'";*/
			if(!empty($fecha_inicial) && !empty($fecha_final)) $where .= " AND ad_ordenes_compra_productos.fecha_generacion BETWEEN '" . $fecha_inicial . " 00:00:00' AND '" . $fecha_final . " 23:59:59'";
			elseif(!empty($fecha_inicial) && empty($fecha_final)) $where .= " AND ad_ordenes_compra_productos.fecha_generacion = '" . $fecha_inicial . "'";
			elseif(empty($fecha_inicial) && !empty($fecha_final)) $where .= " AND ad_ordenes_compra_productos.fecha_generacion ='" . $fecha_final . "'";
		}
/*
		$sql = "SELECT";
		$sql .= " ad_sucursales.nombre,";
		$sql .= " ad_ordenes_compra_productos.id_orden_compra,";
		$sql .= " ad_proveedores.razon_social,";
		$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_generacion,'%d/%m/%Y'),";
		$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_entrega,'%d/%m/%Y'),";
		$sql .= " ad_ordenes_compra_productos.id_usuario_solicita,";
		$sql .= " FORMAT(ad_ordenes_compra_productos.total,2),";
		$sql .= " 
					(
					SELECT DATE_FORMAT(fecha_requerida,'%d/%m/%Y') FROM ad_requisiciones WHERE id_requisicion in (
					SELECT DISTINCT(id_requisicion) id_requisicion
					FROM ad_requisiciones_detalle
					INNER JOIN ad_ordenes_compra_productos
					ON ad_requisiciones_detalle.id_orden_de_compra_relacionada = ad_ordenes_compra_productos.id_control_orden_compra
					)
					) fecha_requerida,
		";
		$sql .= " concat(apellido_paterno, ' ', apellido_materno, ' ', nombres) Nombre,";
		$sql .= " ad_ordenes_compra_productos.id_control_orden_compra";
		$sql .= " FROM ad_ordenes_compra_productos";
		$sql .= " LEFT JOIN ad_proveedores";
		$sql .= " ON ad_ordenes_compra_productos.id_proveedor = ad_proveedores.id_proveedor";
		$sql .= " LEFT JOIN ad_sucursales";
		$sql .= " ON ad_ordenes_compra_productos.id_sucursal = ad_sucursales.id_sucursal";
		$sql .= " LEFT JOIN sys_usuarios";
		$sql .= " ON ad_ordenes_compra_productos.id_usuario_solicita = sys_usuarios.id_usuario";
		$sql .= " WHERE ad_ordenes_compra_productos.id_estatus = 1";
*/
		$sql = " SELECT";
		$sql .= " DISTINCT(ad_ordenes_compra_productos.id_control_orden_compra),";
		$sql .= " ad_sucursales.nombre,";
		$sql .= " ad_ordenes_compra_productos.id_orden_compra,";
		$sql .= " ad_proveedores.razon_social,";
		$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_generacion,'%d/%m/%Y'),";
		$sql .= " DATE_FORMAT(ad_ordenes_compra_productos.fecha_entrega,'%d/%m/%Y'),";
		$sql .= " ad_ordenes_compra_productos.id_usuario_solicita,";
		$sql .= " FORMAT(ad_ordenes_compra_productos.total,2),";
		$sql .= " DATE_FORMAT(fecha_entrega_requerida,'%d/%m/%Y'),";
		$sql .= " concat(apellido_paterno, ' ', apellido_materno, ' ', nombres) Nombre,";
		$sql .= " ad_ordenes_compra_productos.id_control_orden_compra";
		$sql .= " FROM ad_ordenes_compra_productos";
		$sql .= " LEFT JOIN ad_ordenes_compra_productos_detalle";
		$sql .= " ON ad_ordenes_compra_productos.id_control_orden_compra = ad_ordenes_compra_productos_detalle.id_control_orden_compra";
		$sql .= " LEFT JOIN ad_proveedores";
		$sql .= " ON ad_ordenes_compra_productos.id_proveedor = ad_proveedores.id_proveedor";
		$sql .= " LEFT JOIN ad_sucursales";
		$sql .= " ON ad_ordenes_compra_productos.id_sucursal = ad_sucursales.id_sucursal";
		$sql .= " LEFT JOIN sys_usuarios";
		$sql .= " ON ad_ordenes_compra_productos.id_usuario_solicita = sys_usuarios.id_usuario";
		$sql .= " WHERE ad_ordenes_compra_productos.id_estatus = 1";
		$sql .= $where;

		$datosOrdenCompra = new consultarTabla($sql);
		$result = $datosOrdenCompra -> obtenerArregloRegistros();
		$smarty -> assign("filas", $result);
		$smarty -> assign("sql", $sql);
		
		echo $smarty->fetch('especiales/respuestaTablaOrdenCompraPendienteDeAprobacion.tpl');
		break;



		case 'ad_movimientos_almacen':
		// Recupera Valores
		$almacenes = $_POST['almacenes'];
		$almacenesAux = explode(",", $almacenes);
		$cuentaAlmacenes = count(array_filter($almacenesAux));
		$proveedores = $_POST['proveedores'];
		$fecha_inicio_odc = $_POST['fecha_inicio_odc'];
		$fecha_fin_odc = $_POST['fecha_fin_odc'];
		$fecha_inicio_entrada_almacen = $_POST['fecha_inicio_entrada_almacen'];
		$fecha_fin_entrada_almacen = $_POST['fecha_fin_entrada_almacen'];
		$idOrdenCompra = $_POST['idOrdenCompra'];
		$documentoCliente = $_POST['documentoCliente'];		
		//Formatea Fecha Inicial AAAA-MM-DD
		$dia = substr($fecha_inicio_odc, 0, 2);
		$mes = substr($fecha_inicio_odc, 3, 2);
		$anio = substr($fecha_inicio_odc, -4);
		$fecha_inicial_odc = $anio . "-" . $mes . "-" . $dia;
		//Formate Fecha Final AAAA-MM-DD
		$dia = substr($fecha_fin_odc, 0, 2);
		$mes = substr($fecha_fin_odc, 3, 2);
		$anio = substr($fecha_fin_odc, -4);
		$fecha_final_odc = $anio . "-" . $mes . "-" . $dia;
		//Formatea Fecha Inicial AAAA-MM-DD
		$dia = substr($fecha_inicio_entrada_almacen, 0, 2);
		$mes = substr($fecha_inicio_entrada_almacen, 3, 2);
		$anio = substr($fecha_inicio_entrada_almacen, -4);
		$fecha_inicial_entrada_almacen = $anio . "-" . $mes . "-" . $dia;
		//Formate Fecha Final AAAA-MM-DD
		$dia = substr($fecha_fin_entrada_almacen, 0, 2);
		$mes = substr($fecha_fin_entrada_almacen, 3, 2);
		$anio = substr($fecha_fin_entrada_almacen, -4);
		$fecha_final_entrada_almacen = $anio . "-" . $mes . "-" . $dia;

		//Crea cadena de condiciones del query 
		$where = "";
		if($idOrdenCompra != "") {$where .= " AND ad_ordenes_compra_productos.id_orden_compra like '%".$idOrdenCompra."%'";}
		if($documentoCliente != "") {$where .= " AND (documento_proveedor_1 = '".$documentoCliente."' OR documento_proveedor_1 = '".$documentoCliente."')";}
		if($proveedores != 0) $where .= " AND ad_proveedores.id_proveedor = " . $proveedores;
		if($cuentaAlmacenes != '0'){
			$where .= " AND ad_almacenes.id_almacen IN (".$almacenes.")";
		}
		$where .= " AND IFNULL(ad_movimientos_almacen.id_costeo_productos,0) = '0'";

/*		
		$sql = "SELECT";
		$sql .= " ad_movimientos_almacen.id_control_movimiento,";
		$sql .= " ad_movimientos_almacen.id_almacen,";
		$sql .= " ad_almacenes.nombre,";
		$sql .= " ad_movimientos_almacen.id_control_orden_compra,";
		$sql .= " ad_proveedores.id_proveedor, ad_proveedores.razon_social,";

		$sql .= " ad_ordenes_compra_productos.id_control_orden_compra,";
		$sql .= " ad_ordenes_compra_productos.id_orden_compra,";
		$sql .= " ad_movimientos_almacen.id_movimiento,";
		$sql .= " ad_movimientos_almacen_detalle.id_detalle"; *****************************************************************
		$sql .= " FROM ad_movimientos_almacen";
		$sql .= " LEFT JOIN ad_movimientos_almacen_detalle";  *****************************************************************
		$sql .= " ON ad_movimientos_almacen.id_control_movimiento = ad_movimientos_almacen_detalle.id_control_movimiento"; ****
		$sql .= " LEFT JOIN ad_proveedores";
		$sql .= " ON ad_movimientos_almacen.id_proveedor = ad_proveedores.id_proveedor";
		$sql .= " LEFT JOIN ad_almacenes";
		$sql .= " ON ad_movimientos_almacen.id_almacen = ad_almacenes.id_almacen";

		$sql .= " LEFT JOIN ad_ordenes_compra_productos";
		$sql .= " ON ad_movimientos_almacen.id_control_orden_compra = ad_ordenes_compra_productos.id_control_orden_compra";
*/
		$sql = " SELECT";
		$sql .= " ad_movimientos_almacen.id_control_movimiento,";
		$sql .= " ad_movimientos_almacen.id_almacen,";
		$sql .= " ad_almacenes.nombre,";
		$sql .= " ad_movimientos_almacen.id_control_orden_compra,";
		$sql .= " ad_proveedores.id_proveedor, ad_proveedores.razon_social,";
		$sql .= " ad_ordenes_compra_productos.id_control_orden_compra,";
		$sql .= " ad_ordenes_compra_productos.id_orden_compra,";
		$sql .= " ad_movimientos_almacen.id_movimiento";
		$sql .= " FROM ad_movimientos_almacen";
		$sql .= " LEFT JOIN ad_proveedores";
		$sql .= " ON ad_movimientos_almacen.id_proveedor = ad_proveedores.id_proveedor";
		$sql .= " LEFT JOIN ad_almacenes";
		$sql .= " ON ad_movimientos_almacen.id_almacen = ad_almacenes.id_almacen";
		$sql .= " LEFT JOIN ad_ordenes_compra_productos";
		$sql .= " ON ad_movimientos_almacen.id_control_orden_compra = ad_ordenes_compra_productos.id_control_orden_compra";
		$sql .= " WHERE ad_movimientos_almacen.id_tipo_movimiento = '1'";
		$sql .= $where;
		$datosOrdenCompra = new consultarTabla($sql);
		$result = $datosOrdenCompra -> obtenerArregloRegistros();
		$smarty -> assign("filas", $result);
		$smarty -> assign("sql", $sql);
		echo $smarty->fetch('especiales/respuestaTablaEntradasPorOrdenCompra.tpl');
		break;
	default:
		# code...
		break;
}

?>