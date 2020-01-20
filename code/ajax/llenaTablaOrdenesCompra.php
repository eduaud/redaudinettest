<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$orden = $_POST['idOrden'];
$provedor = $_POST['idProveedor'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];

$dia = substr($fecini, 0, 2);
$mes = substr($fecini, 3, 2);
$anio = substr($fecini, -4);

$fecha_inicial = $anio . "-" . $mes . "-" . $dia;

$dia = substr($fecfin, 0, 2);
$mes = substr($fecfin, 3, 2);
$anio = substr($fecfin, -4);

$fecha_final = $anio . "-" . $mes . "-" . $dia;

$where = "";

if(!empty($orden)) {
		$where .= " AND id_orden_compra = " . $orden;
		}
if($provedor != 0) {
		$where .= " AND na_ordenes_compra.id_proveedor = " . $provedor;
		}
if(!empty($fecini) && !empty($fecfin)) {
		$where .= " AND na_ordenes_compra.fecha_creacion BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
		}
if(!empty($fecini) && empty($fecfin)) {
		$where .= " AND na_ordenes_compra.fecha_creacion = '" . $fecha_inicial . "'";
		}
if(empty($fecini) && !empty($fecfin)) {
		$where .= " AND na_ordenes_compra.fecha_creacion ='" . $fecha_final . "'";
		}

$sql = "SELECT na_proveedores.id_proveedor AS id_proveedor_suma, id_orden_compra, DATE_FORMAT(fecha_creacion,'%d/%m/%Y'), na_proveedores.razon_social AS proveedor, CONCAT('$', FORMAT(na_ordenes_compra.total,2)) AS monto_orden, 
		(SELECT CONCAT('$', FORMAT(IF(SUM(ad_cuentas_por_pagar_operadora.saldo) IS NULL, 0, SUM(ad_cuentas_por_pagar_operadora.saldo)),2)) FROM ad_cuentas_por_pagar_operadora WHERE id_proveedor = id_proveedor_suma) AS monto_adeudo,
		 '$2,000.00' AS limite_credito, 
		CONCAT(sys_usuarios.nombres, ' ', sys_usuarios.apellido_paterno, ' ', sys_usuarios.apellido_materno) AS usuario 
		FROM na_ordenes_compra
		LEFT JOIN na_proveedores ON na_ordenes_compra.id_proveedor = na_proveedores.id_proveedor
		LEFT JOIN sys_usuarios ON na_ordenes_compra.id_usuario_solicita = sys_usuarios.id_usuario
		WHERE id_estatus_orden_compra = 1" . $where;
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();

$smarty -> assign("filas", $result);
echo $smarty->fetch('especiales/respuestaTablaOrdenesCompra.tpl');


?>