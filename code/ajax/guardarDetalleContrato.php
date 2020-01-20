<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_control_contrato = $_POST['id_control_contrato'];
$accion = $_POST['accion'];
$producto = $_POST['producto'];
$monto = $_POST['monto'];
switch ($accion){
case '1': //Penalizacion
	$id_accion_contrato = "51";	//Penalización por captura manual
	$id_producto_servicio_facturar = "0";
	$id_producto_servicio_facturar_audicel = $producto;
	$monto_pagar = $monto;
	$monto_cobrar = "0";
	break;
case '2': //Bono
	$id_accion_contrato = "61";	//Bono por captura manual
	$id_producto_servicio_facturar = $producto;
	$id_producto_servicio_facturar_audicel = "0";
	$monto_pagar = "0";
	$monto_cobrar = $monto;
	break;
}

$sql = "SELECT *";
$sql .= " FROM cl_control_contratos_detalles";
$sql .= " WHERE id_control_contrato = '".$id_control_contrato."'";
$sql .= " AND activo = '1'";
$sql .= " AND ultimo_movimiento = '1';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();
foreach($result as $resultado){
	$id_detalle = $resultado->id_detalle;
	$id_tipo_activacion = $resultado->id_tipo_activacion;
	$id_paquete_sky = $resultado->id_paquete_sky;
	$id_sucursal = $resultado->id_sucursal;
	$id_cliente = $resultado->id_cliente;
	$id_cliente_tecnico = $resultado->id_cliente_tecnico;
	$id_entidad_financiera_tecnico = $resultado->id_entidad_financiera_tecnico;
	$id_entidad_financiera_vendedor = $resultado->id_entidad_financiera_vendedor;
	$id_funcionalidad = $resultado->id_funcionalidad;
	$clave = $resultado->clave;
	$id_detalle_caja_comisiones = $resultado->id_detalle_caja_comisiones;
	$id_usuario_alta = $resultado->id_usuario_alta;
	$activo = $resultado->activo;
	$ultimo_movimiento = $resultado->ultimo_movimiento;
}

$id_usuario_alta = $_SESSION["USR"]->userid;
$sInsert = "INSERT INTO cl_control_contratos_detalles";
$sInsert .= "(";
$sInsert .= " id_control_contrato,";
$sInsert .= " id_accion_contrato,";
$sInsert .= " fecha_movimiento,";
$sInsert .= " id_tipo_activacion,";
$sInsert .= " id_paquete_sky,";
$sInsert .= " id_sucursal,";
$sInsert .= " id_cliente,";
$sInsert .= " id_cliente_tecnico,";
$sInsert .= " id_entidad_financiera_tecnico,";
$sInsert .= " id_entidad_financiera_vendedor,";
$sInsert .= " id_funcionalidad,";
$sInsert .= " clave,";
$sInsert .= " id_detalle_caja_comisiones,";
$sInsert .= " id_producto_servicio_facturar,";
$sInsert .= " id_producto_servicio_facturar_audicel,";
$sInsert .= " monto_pagar,";
$sInsert .= " monto_cobrar,";
$sInsert .= " id_usuario_alta,";
$sInsert .= " activo,";
$sInsert .= " ultimo_movimiento";
$sInsert .= ")";
$sInsert .= " VALUES (";
$sInsert .= "'".$id_control_contrato."', ";						//id_control_contrato E
$sInsert .= "'".$id_accion_contrato."', ";						//id_accion_contrato C
$sInsert .= "now(), ";											//fecha_movimiento C
$sInsert .= "'".$id_tipo_activacion."', ";						//id_tipo_activacion E
$sInsert .= "'".$id_paquete_sky."', ";							//id_paquete_sky E
$sInsert .= "'".$id_sucursal."', ";								//id_sucursal E
$sInsert .= "'".$id_cliente."', ";								//id_cliente E
$sInsert .= "'".$id_cliente_tecnico."', ";						//id_cliente_tecnico C
$sInsert .= "'".$id_entidad_financiera_tecnico."', ";			//id_entidad_financiera_tecnico C
$sInsert .= "'".$id_entidad_financiera_vendedor."', ";			//id_entidad_financiera_vendedor C
$sInsert .= "'".$id_funcionalidad."', ";						//id_funcionalidad E
$sInsert .= "'".$clave."', ";									//clave E
$sInsert .= "'".$id_detalle_caja_comisiones."', ";				//id_detalle_caja_comisiones E
$sInsert .= "'".$id_producto_servicio_facturar."', ";			//id_producto_servicio_facturar E
$sInsert .= "'".$id_producto_servicio_facturar_audicel."', ";	//id_producto_servicio_facturar_audicel
$sInsert .= "'".$monto_pagar."', ";								//monto_pagar C
$sInsert .= "'".$monto_cobrar."', ";							//monto_cobrar C
$sInsert .= "'".$id_usuario_alta."', ";							//id_usuario_alta C
$sInsert .= "'1', ";											//activo C
$sInsert .= "'".$ultimo_movimiento."'";							//ultimo_movimiento C
$sInsert .= ");";
mysql_query($sInsert) or die("Error en consulta:<br> $sInsert <br>" . mysql_error());

//Se desactiva el indicador del ultimo movimiento ultimo movimiento -->
$sUpdate = "UPDATE cl_control_contratos_detalles";
$sUpdate .= " SET ultimo_movimiento = '0'";
$sUpdate .= " WHERE id_detalle = '".$id_detalle."'";
$sUpdate .= " AND activo = '1';";
mysql_query($sUpdate) or die("Error en consulta:<br> $sUpdate <br>" . mysql_error());
//Se desactiva el indicador del ultimo movimiento ultimo movimiento <--
//echo $mensaje;
?>