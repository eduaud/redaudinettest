<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_control_contrato = $_GET['id_control_contrato'];
$id_control_contrato_detalle = $_GET['id_control_contrato_detalle'];
$accion = $_GET['accion'];

$sql = "SELECT *, DATE_FORMAT(cl_control_contratos_detalles.fecha_movimiento,'%d/%m/%Y') ";
$sql .= " FROM cl_control_contratos";
$sql .= " INNER JOIN cl_control_contratos_detalles";
$sql .= " ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato";
$sql .= " WHERE cl_control_contratos.id_control_contrato = '".$id_control_contrato."'";
$sql .= " AND cl_control_contratos_detalles.id_detalle = '".$id_control_contrato_detalle."';";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
$smarty -> assign("id_control_contrato", $id_control_contrato);
$smarty -> assign("id_control_contrato_detalle", $id_control_contrato_detalle);
$smarty -> assign("accion", $accion);
$smarty -> assign("sql1", $sql);


//$sql = "SELECT id_instalador, CONCAT(nombres,' ',apellido_paterno,' ',apellido_materno) FROM cl_instaladores WHERE activo = '1';";
$sql = "SELECT id_cliente, CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) FROM ad_clientes WHERE activo = '1';";
$datosNIT = new consultarTabla($sql);
$resultNIT = $datosNIT -> obtenerArregloRegistros();
$numeroDeRegistrosNIT = count($resultNIT);
$smarty -> assign("filasNIT", $resultNIT);
$smarty -> assign("numeroDeRegistrosNIT", $numeroDeRegistrosNIT);
$smarty -> assign("sql2", $sql);

/***   Extrae los tipos de cliente   ***/
if($accion == 5){
	$query='SELECT id_tipo_cliente_proveedor,nombre FROM cl_tipos_cliente_proveedor WHERE id_tipo_cliente_proveedor IN (1,2,3,4) order by nombre';
	$result=mysql_query($query);

	$arrTiposCliente=array();
	$arrIdTiposCliente=array();
	$tc=0;
	while($datos=mysql_fetch_array($result)){
		$arrTiposCliente[$tc]=$datos["nombre"];
		$arrIdTiposCliente[$tc]=$datos["id_tipo_cliente_proveedor"];
		$tc++;
	}

	$smarty -> assign("arrTiposCliente", $arrTiposCliente);
	$smarty -> assign("arrIdTiposCliente", $arrIdTiposCliente);
}
/***   Termina Extrae los tipos de cliente   ***/

/***   Extrae plazas   ***/
if($accion == 5){
	$query='SELECT id_sucursal,nombre FROM ad_sucursales WHERE activo = 1 order by nombre';
	$result=mysql_query($query);

	$arrPlazas=array();
	$arrIdPlazas=array();
	$tc=0;
	while($datos=mysql_fetch_array($result)){
		$arrPlazas[$tc]=$datos["nombre"];
		$arrIdPlazas[$tc]=$datos["id_sucursal"];
		$tc++;
	}

	$smarty -> assign("arrPlazas", $arrPlazas);
	$smarty -> assign("arrIdPlazas", $arrIdPlazas);
}
/***   Termina Extrae plazas   ***/

/*
$sql = "SELECT id_promocion_sky, nombre_promocion_sky FROM cl_promociones_sky WHERE activo = '1';";
$datosPromos = new consultarTabla($sql);
$resultPromos = $datosPromos -> obtenerArregloRegistros();
$numeroDeRegistrosPromos = count($resultPromos);

$smarty -> assign("filasPromos", $resultPromos);
$smarty -> assign("numeroDeRegistrosPromos", $numeroDeRegistrosPromos);
$smarty -> assign("sql3", $sql);
*/

echo json_encode($smarty->fetch('especiales/respuestaEditarHistorialContrato.tpl'));
?>