<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

//mysql_query("SET NAMES utf8");

$sql = "SELECT id_proveedor,razon_social FROM na_proveedores WHERE activo=1 ORDER BY razon_social";
$datosProvedor = new consultarTabla($sql);
$provedor = $datosProvedor -> obtenerArregloRegistros();

$sql2 = "SELECT na_proveedores.id_proveedor AS id_proveedor_suma, id_orden_compra, DATE_FORMAT(fecha_creacion,'%d/%m/%Y'), na_proveedores.razon_social AS proveedor, CONCAT('$', FORMAT(na_ordenes_compra.total,2)) AS monto_orden, 
		(SELECT CONCAT('$', FORMAT(IF(SUM(ad_cuentas_por_pagar_operadora.saldo) IS NULL, 0, SUM(ad_cuentas_por_pagar_operadora.saldo)),2)) FROM ad_cuentas_por_pagar_operadora WHERE id_proveedor = id_proveedor_suma) AS monto_adeudo,
		 '$2,000.00' AS limite_credito, 
		CONCAT(sys_usuarios.nombres, ' ', sys_usuarios.apellido_paterno, ' ', sys_usuarios.apellido_materno) AS usuario 
		FROM na_ordenes_compra
		LEFT JOIN na_proveedores ON na_ordenes_compra.id_proveedor = na_proveedores.id_proveedor
		LEFT JOIN sys_usuarios ON na_ordenes_compra.id_usuario_solicita = sys_usuarios.id_usuario
		WHERE id_estatus_orden_compra = 1";
		
$datos = new consultarTabla($sql2);
$result = $datos -> obtenerArregloRegistros();

$smarty -> assign("proveedor", $provedor);
$smarty -> assign("filas", $result);
$smarty -> display("especiales/aprobacionOrdenesCompra.tpl");

?>