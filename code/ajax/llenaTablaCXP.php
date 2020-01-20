<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$cxp = $_POST['idcxp'];
$numDoc = $_POST['numDoc'];
$idProv = $_POST['idProv'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];
$idEmp = $_POST['idEmp'];
$total = $_POST['total'];
$fechavec = $_POST['fechavec'];



$dia = substr($fecfin, 0, 2);
$mes = substr($fecfin, 3, 2);
$anio = substr($fecfin, -4);

$fecha_final = $anio . "-" . $mes . "-" . $dia;


$dia = substr($fechavec, 0, 2);
$mes = substr($fechavec, 3, 2);
$anio = substr($fechavec, -4);

$fecha_vencimiento = $anio . "-" . $mes . "-" . $dia;

$where = "";

if(!empty($cxp)) {
		$where .= " AND aux.id_cuenta_por_pagar = '" . $cxp . "'";
		}
if(!empty($numDoc)) {
		$where .= " AND aux.numero_documento = '" . $numDoc . "'";
		}
if($idProv != 0) {
		$where .= " AND ad_proveedores.id_proveedor = " . $idProv;
		}
if(!empty($fecini) && !empty($fecfin)) {
		$where .= " AND aux.fecha_captura BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
		}
if(!empty($fecini) && empty($fecfin)) {
		$where .= " AND aux.fecha_captura = '" . $fecha_inicial . "'";
		}
if(empty($fecini) && !empty($fecfin)) {
		$where .= " AND aux.fecha_captura ='" . $fecha_final . "'";
		}
if(!empty($idEmp)) {
		$where .= " AND aux.rembolsar_a = '" . $idEmp . "'";
		}

if(!empty($total)) {
		$where .= " AND aux.total = '" . $total . "'";
		}
if(!empty($fechavec)) {
		$where .= " AND aux.fecha_vencimiento = '" . $fecha_vencimiento . "'";
		}

$sql = "SELECT id_cxp, documento, proveedor, CONCAT('$', FORMAT(monto, 2)) AS monto, 
		CONCAT('$', FORMAT((pagosCP+pagosEgresos),2)) AS totalPagos, 
		CONCAT('$', FORMAT((monto-(pagosCP+pagosEgresos)),2)) AS saldo, id_proveedor, empleado, numero_documento
		FROM
		(
			SELECT id_cuenta_por_pagar AS id_cxp, total AS monto, ad_tipos_documentos.nombre AS documento, ad_proveedores.razon_social AS proveedor, ad_proveedores.id_proveedor AS id_proveedor, CONCAT(ad_empleados.nombre, ' ', IF(ad_empleados.apellido_paterno IS NOT NULL, ad_empleados.apellido_paterno, ''), ' ',  IF(ad_empleados.apellido_materno IS NOT NULL, ad_empleados.apellido_materno, '')) AS empleado, numero_documento,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_cuentas_por_pagar_operadora_detalle_pagos WHERE activoDetCXP=1 AND id_cuenta_por_pagar=aux.id_cuenta_por_pagar) AS pagosCP,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_egresos_detalle WHERE activoDetEgreso=1 AND id_cuenta_por_pagar=aux.id_cuenta_por_pagar) AS pagosEgresos
			FROM ad_cuentas_por_pagar_operadora AS aux
				LEFT JOIN ad_tipos_documentos ON aux.id_tipo_documento_recibido = ad_tipos_documentos.id_tipo_documento
				LEFT JOIN ad_proveedores ON aux.id_proveedor = ad_proveedores.id_proveedor
				LEFT JOIN ad_empleados ON aux.rembolsar_a = ad_empleados.id_empleado
				WHERE aux.id_estatus_cuentas_por_pagar <> 3" . $where . "
		) AS datos
		WHERE (monto-(pagosCP+pagosEgresos)) > 0";
		
		
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);
$smarty->assign("sql", $sql);
echo $smarty->fetch('especiales/respuestaTablaCXP.tpl');


?>


