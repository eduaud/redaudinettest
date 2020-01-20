<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

//query basico para obtener los clientes 
//### V A R I A B L E S   A   R E E M P L A Z A R  ###
//VAR_ALMACEN tenemos que realizar un reemplazo de esta variable por el almacen a seleccionar
//VAR_SUCURSALES_PEDIDO  temos que realizar un reeplazo de la sucursal de los que quermos incluir el pedido

//DEL TABLA na_movimientos_apartados OBTENEMOS TODOS LOS PRODUCTOS CON 
//1 id_estatus_apartado IN (1 ,5 )      1 APARTADO      5   LIBERADO ENTREGADO AL CLIENTE PARCIALMENTE
//id_estatus_pago in (1,3,5)    1 PAGADOS TOTALMENTE     3 PAGADO PARCIALMENTE ENTREGA INEMDIATA   5 CREDITO
//ad_pedidos.id_estatus_pedido in (1,2,3,10)  1 PENDIENTE DE ENTREGA 2 PRODUCTOS APARTADOS 3 PARCIALEMNTE ENTREGADOS 10 PENDIENTE()

//OBTENEMOS LAS SUCURSALES DEL USUARIO -->
$strSQL = "SELECT id_sucursal FROM sys_usuarios WHERE id_usuario = '".$_SESSION["USR"]->userid."' ";
$arrResult = valBuscador($strSQL);
$sucursal_usuario = $arrResult[0];
$strWhere = "";
if($sucursal_usuario != '0'){ $strWhere=" and id_sucursal = ".$sucursal_usuario; }
$strSQL = "SELECT id_sucursal, nombre FROM ad_sucursales WHERE activo = '1' AND id_sucursal <> '0' ". $strWhere;
$registrosCombos = retornaListaIdsNombres($strSQL,'NO', 0, 0, ' ');
$reg_sucursales = array();
$reg_sucursales = $registrosCombos;
//OBTENEMOS LAS SUCURSALES DEL USUARIO <--

//OBTENEMOS LOS ALMACENES -->
$strWhere="";
if($sucursal_usuario!='0'){
	$strWhere = " AND id_almacen IN (SELECT id_almacen FROM ad_sucursales WHERE activo = '1' AND id_sucursal = ".$sucursal_usuario.")" ;
	$strWhereUnAlmancen = " AND id_almacen IN (SELECT id_almacen FROM ad_sucursales WHERE activo = '1' AND id_sucursal = ".$sucursal_usuario.")" ;
}else{
	//SOLO PODREMOS SACAR DEL CEDIS PRODUCTOS
	$strWhere= " AND id_almacen = '1'"; 
}
$strSQL = "SELECT id_almacen, nombre FROM ad_almacenes WHERE neteable = '1' AND  activo = '1' ". $strWhere;	//TODOS LOS ALMACENES NETEABLES Y ACTIVOS
$registrosCombos2 = retornaListaIdsNombres($strSQL,'NO', 0, 0, ' ');
$reg_almacenes = array();
$reg_almacenes = $registrosCombos2;
//OBTENEMOS LOS ALMACENES <--

//OBTENEMOS LOS CLIENTES -->
$strSQL = "SELECT id_cliente, CONCAT(IFNULL(nombre,''),' ',IFNULL(apellido_paterno,''),' ',IFNULL(apellido_materno,'')) FROM ad_clientes WHERE activo = '1';";
$registrosCombos3=retornaListaIdsNombres($strSQL,'NO', 0, 0, ' ');
$reg_clientes = array();
$reg_clientes = $registrosCombos3;
//OBTENEMOS LOS CLIENTES <--

//OBTENEMOS EL TIPO DE ENTREGA -->
//$strSQL="SELECT id_tipo_entrega_producto, nombre FROM ad_tipos_entrega_productos ";
//$registrosCombos3=retornaListaIdsNombres($strSQL,'NO', 0, 0, ' ');
//$reg_tipos_entrega=array();
//$reg_tipos_entrega=$registrosCombos3;
//OBTENEMOS EL TIPO DE ENTREGA <--

//$smarty -> assign("id_cliente_buscar", $id_cliente_buscar);
//$smarty -> assign("id_pedido_buscar", $id_pedido_buscar);
//$smarty -> assign("reg_tipos_entrega", $reg_tipos_entrega);
//$smarty -> assign("reg_rutas", $reg_rutas);
//$smarty -> assign("id_almacen_buscar", $id_almacen_buscar);
//$smarty -> assign("sucursal_usuario", $sucursal_usuario);
//$smarty -> assign("rooturl", $rooturl);

$smarty -> assign("reg_sucursales", $reg_sucursales);
$smarty -> assign("reg_almacenes", $reg_almacenes);
$smarty -> assign("reg_clientes", $reg_clientes);

$sql = "SELECT";
$sql .= " ad_pedidos.id_control_pedido,";
$sql .= " ad_pedidos.id_pedido pedido,";
$sql .= " ad_pedidos.id_tipo_cliente,";
$sql .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
$sql .= " ad_pedidos.id_cliente,";
$sql .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
$sql .= " ad_pedidos_detalle.id_producto,";
$sql .= " cl_productos_servicios.nombre producto,";
$sql .= " ad_pedidos_detalle.cantidad_requerida,";
$sql .= " cl_productos_servicios.requiere_numero_serie,";
$sql .= " ad_pedidos_detalle.id_pedido_detalle";
$sql .= " FROM ad_pedidos";
$sql .= " INNER JOIN ad_pedidos_detalle";
$sql .= " ON ad_pedidos.id_control_pedido = ad_pedidos_detalle.id_control_pedido";
$sql .= " INNER JOIN cl_tipos_cliente_proveedor";
$sql .= " ON ad_pedidos.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
$sql .= " INNER JOIN ad_clientes";
$sql .= " ON ad_pedidos.id_cliente = ad_clientes.id_cliente";
$sql .= " INNER JOIN cl_productos_servicios";
$sql .= " ON ad_pedidos_detalle.id_producto = cl_productos_servicios.id_producto_servicio";

$sql .= " UNION";

$sql .= " SELECT";
$sql .= " ad_solicitudes_material.id_control_solicitud_material,";
$sql .= " ad_solicitudes_material.id_solicitud_material pedido,";
$sql .= " ad_solicitudes_material.id_tipo_cliente,";
$sql .= " cl_tipos_cliente_proveedor.nombre tipo_cliente,";
$sql .= " ad_solicitudes_material.id_cliente,";
$sql .= " CONCAT(IFNULL(ad_clientes.nombre,''),' ',IFNULL(ad_clientes.apellido_paterno,''),' ',IFNULL(ad_clientes.apellido_materno,'')) cliente,";
$sql .= " ad_solicitudes_material_detalle.id_producto,";
$sql .= " cl_productos_servicios.nombre producto,";
$sql .= " ad_solicitudes_material_detalle.cantidad_requerida,";
$sql .= " cl_productos_servicios.requiere_numero_serie,";
$sql .= " ad_solicitudes_material_detalle.id_solicitud_material_detalle";
$sql .= " FROM ad_solicitudes_material";
$sql .= " INNER JOIN ad_solicitudes_material_detalle";
$sql .= " ON ad_solicitudes_material.id_control_solicitud_material = ad_solicitudes_material_detalle.id_control_solicitud_material";
$sql .= " INNER JOIN cl_tipos_cliente_proveedor";
$sql .= " ON ad_solicitudes_material.id_tipo_cliente = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor";
$sql .= " INNER JOIN ad_clientes";
$sql .= " ON ad_solicitudes_material.id_cliente = ad_clientes.id_cliente";
$sql .= " INNER JOIN cl_productos_servicios";
$sql .= " ON ad_solicitudes_material_detalle.id_producto = cl_productos_servicios.id_producto_servicio";


$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);

$smarty -> assign("filas", $result);
$smarty -> assign("sql", $sql);

$smarty->display("especiales/surtidoPedidos.tpl");
?>