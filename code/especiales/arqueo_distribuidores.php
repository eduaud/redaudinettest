<?php 
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$sqlPlazas="
	SELECT id_sucursal,nombre from ad_sucursales";
$datosPlaza= new consultarTabla($sqlPlazas);
$resultPlaza = $datosPlaza -> obtenerArregloRegistros();

$sqlDistribuidores="
	SELECT id_cliente,CONCAT(ad_clientes.nombre,' ',IFNULL(apellido_paterno,''),' ',IFNULL(apellido_materno,'')) AS nombre FROM ad_clientes 
	LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor=cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	WHERE cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=3 OR cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=1 OR cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=5";
$datosDistribuidores= new consultarTabla($sqlDistribuidores);
$resultDistribuidores = $datosDistribuidores -> obtenerArregloRegistros();

$sql="
	SELECT id_cuenta_por_pagar,fecha_captura,fecha_vencimiento,total 
	FROM ad_cuentas_por_pagar_operadora
";
$datos= new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();

$sqlFacturas="SELECT id_control_factura,fecha_y_hora,nombre,total,'pagos','saldo' 
			FROM ad_facturas_audicel 
			LEFT JOIN cl_facturas_conceptos_encabezado ON ad_facturas_audicel.id_concepto_factura=cl_facturas_conceptos_encabezado.id_concepto;";
$datosFacturas= new consultarTabla($sqlFacturas);
$resultFactura = $datosFacturas -> obtenerArregloRegistros();


$smarty -> assign('totalcxp',count($result));
$smarty -> assign('a_cxp',$result);
$smarty -> assign('a_Plazas',$resultPlaza);
$smarty -> assign('a_Distribuidores',$resultDistribuidores);
$smarty -> assign('a_Facturas',$resultFactura);
$smarty -> assign('totalFactura',count($resultFactura));
$smarty->display('especiales/arqueoDistribuidores.tpl');
?>