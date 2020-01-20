<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$contrato = $_GET['contrato'];
$cuenta = $_GET['cuenta'];
$ird = $_GET['ird'];

$where = "";
if($contrato!="") $where .= " AND cl_control_contratos.contrato LIKE '%".$contrato."%'";
if($cuenta!="") $where .= " AND cl_control_contratos.cuenta LIKE '%".$cuenta."%'";
if($ird!="") $where .= " AND cl_control_contratos_detalles.numero_serie LIKE '%".$ird."%'";

$sql = "SELECT";
$sql .= " DISTINCT(cl_control_contratos.id_control_contrato),";
$sql .= " cl_control_contratos.contrato,";
$sql .= " cl_control_contratos.cuenta,";
$sql .= " cl_control_contratos_detalles.id_cliente,";
$sql .= " CONCAT(ad_clientes.nombre,' ',apellido_paterno,' ',apellido_materno),";
$sql .= " cl_control_contratos_detalles.id_sucursal,";
$sql .= " ad_sucursales.nombre,";
$sql .= " cl_control_contratos.contrato_original_1,";
$sql .= " cl_control_contratos.contrato_original_2,";
$sql .= " cl_control_contratos.contrato_original_3,";
$sql .= " cl_control_contratos.fecha_activacion";
$sql .= " FROM cl_control_contratos";
$sql .= " INNER JOIN cl_control_contratos_detalles";
$sql .= " ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato";
$sql .= " INNER JOIN ad_clientes";
$sql .= " ON cl_control_contratos_detalles.id_cliente = ad_clientes.id_cliente";
$sql .= " INNER JOIN ad_sucursales";
$sql .= " ON cl_control_contratos_detalles.id_sucursal = ad_sucursales.id_sucursal";
$sql .= " WHERE cl_control_contratos.activo = '1'";
$sql .= " AND cl_control_contratos_detalles.ultimo_movimiento != '0'";
$sql .= $where;

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
$smarty -> assign("sql", $sql);
echo json_encode($smarty->fetch('especiales/respuestaMuestraContratos.tpl'));
?>