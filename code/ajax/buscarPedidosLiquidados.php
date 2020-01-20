<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$opcion_buscador = $_GET['opcion_buscador'];

$wherePedidos = " WHERE 1 = 1";
$condicionesPedidos = " AND id_estatus_pedido = '2'";

$whereSolicitudMaterial = " WHERE 1 = 1";
$condicionesSolicitudMaterial = " AND id_estatus_solicitud = '2'";
$union = " UNION";
switch ($opcion_buscador) {
    case '1':	//BUSCAR EN EL PRIMER CRITERIO
		$idSucursal = $_GET['idSucursal'];
		$idCliente = $_GET['idCliente'];
		$fechaInicial = $_GET['fechaInicial'];
		$fechaFinal = $_GET['fechaFinal'];
		
		$fechaInicialAux = convertDate($fechaInicial);
		$fechaFinalAux = convertDate($fechaFinal);
		
		if( ($idSucursal != "-1") && ($idSucursal != "9999999999") ){
			$wherePedidos .= " AND ad_pedidos.id_sucursal = '".$idSucursal."'";
			$whereSolicitudMaterial .= " AND ad_solicitudes_material.id_sucursal = '".$idSucursal."'";
		}
		if( ($idCliente != "-1") && ($idCliente != "9999999999") ){
			$wherePedidos .= " AND ad_pedidos.id_cliente = '".$idCliente."'";
			$whereSolicitudMaterial .= " AND ad_solicitudes_material.id_cliente = '".$idCliente."'";
		}
		if($fechaInicial != ""){
			$wherePedidos .= " AND DATE_FORMAT(fecha_alta_pedido, '%Y-%m-%d') BETWEEN '".$fechaInicialAux."' AND '".$fechaFinalAux."'";
			$whereSolicitudMaterial .= " AND DATE_FORMAT(fecha_alta_pedido, '%Y-%m-%d') BETWEEN '".$fechaInicialAux."' AND '".$fechaFinalAux."'";
		}
		
		mysql_set_charset('utf8');
		$sqlPedidos = "SELECT";
		$sqlPedidos .= " ad_pedidos.id_control_pedido,";
		$sqlPedidos .= " ad_pedidos.id_pedido pedido,";
		$sqlPedidos .= " ad_pedidos.id_tipo_cliente,";
		$sqlPedidos .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
		$sqlPedidos .= " ad_pedidos.id_cliente,";
		$sqlPedidos .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
		$sqlPedidos .= " ad_pedidos_detalle.id_producto,";
		$sqlPedidos .= " cl_productos_servicios.nombre producto,";
		$sqlPedidos .= " ad_pedidos_detalle.cantidad_requerida,";
		$sqlPedidos .= " cl_productos_servicios.requiere_numero_serie,";
		$sqlPedidos .= " ad_pedidos_detalle.id_pedido_detalle,";
		$sqlPedidos .= " ad_pedidos.id_almacen_solicita,";
		$sqlPedidos .= " 'P' Tipo";
		$sqlPedidos .= " FROM ad_pedidos";
		$sqlPedidos .= " INNER JOIN ad_pedidos_detalle";
		$sqlPedidos .= " ON ad_pedidos.id_control_pedido = ad_pedidos_detalle.id_control_pedido";
		$sqlPedidos .= " INNER JOIN cl_tipos_cliente_proveedor";
		$sqlPedidos .= " ON ad_pedidos.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
		$sqlPedidos .= " INNER JOIN ad_clientes";
		$sqlPedidos .= " ON ad_pedidos.id_cliente = ad_clientes.id_cliente";
		$sqlPedidos .= " INNER JOIN cl_productos_servicios";
		$sqlPedidos .= " ON ad_pedidos_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
		$sqlPedidos .= $wherePedidos.$condicionesPedidos;
		
		$sqlSolicitudMaterial .= " SELECT";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_control_solicitud_material,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_solicitud_material pedido,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_tipo_cliente,";
		$sqlSolicitudMaterial .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_cliente,";
		$sqlSolicitudMaterial .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.id_producto,";
		$sqlSolicitudMaterial .= " cl_productos_servicios.nombre producto,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.cantidad_requerida,";
		$sqlSolicitudMaterial .= " cl_productos_servicios.requiere_numero_serie,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.id_solicitud_material_detalle,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_almacen_solicita,";
		$sqlSolicitudMaterial .= " 'S' Tipo";
		$sqlSolicitudMaterial .= " FROM ad_solicitudes_material";
		$sqlSolicitudMaterial .= " INNER JOIN ad_solicitudes_material_detalle";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_control_solicitud_material = ad_solicitudes_material_detalle.id_control_solicitud_material";
		$sqlSolicitudMaterial .= " INNER JOIN cl_tipos_cliente_proveedor";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
		$sqlSolicitudMaterial .= " INNER JOIN ad_clientes";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_cliente = ad_clientes.id_cliente";
		$sqlSolicitudMaterial .= " INNER JOIN cl_productos_servicios";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
		$sqlSolicitudMaterial .= $whereSolicitudMaterial.$condicionesSolicitudMaterial;	
		
		$sql = $sqlPedidos.$union.$sqlSolicitudMaterial;
		
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerArregloRegistros();
		$numeroDeRegistros = count($result);
		
		$smarty -> assign("filas", $result);
		$smarty -> assign("sql", $sqlPedidos);

	break;
    case '2':	//BUSCAR PARA EL SEGUNDO CRITERIO
		$idAlmacen = $_GET['idAlmacen'];
		$tipo = $_GET['tipo'];
		$PedidoSolicitudMaterial = $_GET['PedidoSolicitudMaterial'];
		
		if($idAlmacen != "-1"){
			$wherePedidos .= " AND ad_pedidos.id_almacen_solicita = '".$idAlmacen."'";
			$whereSolicitudMaterial .= " AND ad_solicitudes_material.id_almacen_solicita = '".$idAlmacen."'";
		}
	
		mysql_set_charset('utf8');
		$sqlPedidos = "SELECT";
		$sqlPedidos .= " ad_pedidos.id_control_pedido,";
		$sqlPedidos .= " ad_pedidos.id_pedido pedido,";
		$sqlPedidos .= " ad_pedidos.id_tipo_cliente,";
		$sqlPedidos .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
		$sqlPedidos .= " ad_pedidos.id_cliente,";
		$sqlPedidos .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
		$sqlPedidos .= " ad_pedidos_detalle.id_producto,";
		$sqlPedidos .= " cl_productos_servicios.nombre producto,";
		$sqlPedidos .= " ad_pedidos_detalle.cantidad_requerida,";
		$sqlPedidos .= " cl_productos_servicios.requiere_numero_serie,";
		$sqlPedidos .= " ad_pedidos_detalle.id_pedido_detalle";
		$sqlPedidos .= " ad_pedidos.id_almacen_solicita,";
		$sqlPedidos .= " 'P' Tipo";
		$sqlPedidos .= " FROM ad_pedidos";
		$sqlPedidos .= " INNER JOIN ad_pedidos_detalle";
		$sqlPedidos .= " ON ad_pedidos.id_control_pedido = ad_pedidos_detalle.id_control_pedido";
		$sqlPedidos .= " INNER JOIN cl_tipos_cliente_proveedor";
		$sqlPedidos .= " ON ad_pedidos.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
		$sqlPedidos .= " INNER JOIN ad_clientes";
		$sqlPedidos .= " ON ad_pedidos.id_cliente = ad_clientes.id_cliente";
		$sqlPedidos .= " INNER JOIN cl_productos_servicios";
		$sqlPedidos .= " ON ad_pedidos_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
		
		$sqlSolicitudMaterial .= " SELECT";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_control_solicitud_material,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_solicitud_material pedido,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_tipo_cliente,";
		$sqlSolicitudMaterial .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_cliente,";
		$sqlSolicitudMaterial .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.id_producto,";
		$sqlSolicitudMaterial .= " cl_productos_servicios.nombre producto,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.cantidad_requerida,";
		$sqlSolicitudMaterial .= " cl_productos_servicios.requiere_numero_serie,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material_detalle.id_solicitud_material_detalle,";
		$sqlSolicitudMaterial .= " ad_solicitudes_material.id_almacen_solicita,";
		$sqlSolicitudMaterial .= " 'S' Tipo";
		$sqlSolicitudMaterial .= " FROM ad_solicitudes_material";
		$sqlSolicitudMaterial .= " INNER JOIN ad_solicitudes_material_detalle";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_control_solicitud_material = ad_solicitudes_material_detalle.id_control_solicitud_material";
		$sqlSolicitudMaterial .= " INNER JOIN cl_tipos_cliente_proveedor";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
		$sqlSolicitudMaterial .= " INNER JOIN ad_clientes";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material.id_cliente = ad_clientes.id_cliente";
		$sqlSolicitudMaterial .= " INNER JOIN cl_productos_servicios";
		$sqlSolicitudMaterial .= " ON ad_solicitudes_material_detalle.id_producto = cl_productos_servicios.id_producto_servicio";
		
		switch($tipo){
		case 'P':	//PARA BUSCAR PEDIDOS
			$wherePedidos .= " AND ad_pedidos.id_pedido LIKE '%".$PedidoSolicitudMaterial."%'";
			$sqlPedidos .= $wherePedidos.$condicionesPedidos;
			$union = "";
			$sqlSolicitudMaterial = "";
		break;
		case 'S':	//PARA BUSCAR SOLICITUDES DE MATARIALES
			$whereSolicitudMaterial .= " AND ad_solicitudes_material.id_solicitud_material LIKE '%".$PedidoSolicitudMaterial."%'";
			$sqlSolicitudMaterial .= $whereSolicitudMaterial.$condicionesSolicitudMaterial;
			$union = "";
			$sqlPedidos = "";
		break;
		}


		$sql = $sqlPedidos.$union.$sqlSolicitudMaterial;

		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerArregloRegistros();
		
		$numeroDeRegistros = count($result);
		$smarty -> assign("filas", $result);
		$smarty -> assign("sql", $sql);
		
    break;
}
$smarty -> assign("respuesta", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
//Se actualiza el registro en el campo de fecha de movimiento del ultimo insertado del contrato <--
echo json_encode($smarty->fetch('especiales/respuestaBuscarPedidosLiquidados.tpl'));

//$smarty -> assign("filas", htmlentities($result, "ENT_QUOTE", "UTF-8"));
?>