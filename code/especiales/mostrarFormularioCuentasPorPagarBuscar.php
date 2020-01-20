<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	include("../../consultaBase.php");
	
	$idProv = $_GET["idProv"];
	$idCXPG = $_GET["cxp"];
	
	
	//$sql = "SELECT id_proveedor,razon_social FROM na_proveedores WHERE activo=1 AND id_proveedor IN(SELECT DISTINCT id_proveedor FROM ad_cuentas_por_pagar_operadora) ORDER BY razon_social";
	$sql = "SELECT DISTINCT ad_cuentas_por_pagar_operadora.id_proveedor, ad_proveedores.razon_social
			FROM ad_cuentas_por_pagar_operadora
			LEFT JOIN ad_proveedores ON ad_cuentas_por_pagar_operadora.id_proveedor = ad_proveedores.id_proveedor
			WHERE ad_cuentas_por_pagar_operadora.id_estatus_cuentas_por_pagar = 1 AND ad_proveedores.activo = 1 ORDER BY ad_proveedores.razon_social";
	$datosProv = new consultarTabla($sql);
	$proveedor = $datosProv -> obtenerArregloRegistros();
	
	/*$sql2 = "SELECT DISTINCT id_empleado,
			CONCAT(nombre, ' ', IF(apellido_paterno IS NOT NULL, apellido_paterno, ''), ' ',  IF(apellido_materno IS NOT NULL, apellido_materno, '')) AS empleado	
			FROM ad_cuentas_por_pagar_operadora
			LEFT JOIN ad_empleados ON ad_cuentas_por_pagar_operadora.rembolsar_a = ad_empleados.id_empleado
			WHERE ad_empleados.activo=1 AND aplica_reembolso = 1 AND id_estatus_cuentas_por_pagar = 1 ORDER BY empleado";
	$datosEmp = new consultarTabla($sql2);
	$empleado = $datosEmp -> obtenerArregloRegistros();*/

	$smarty -> assign("idCXPG", $idCXPG);
	$smarty -> assign("idProveedor", $idProv);
	$smarty -> assign("proveedor", $proveedor);
	//$smarty -> assign("empleado", $empleado);
	$smarty->display("especiales/buscar_cuentas_por_pagar_seleccion.tpl");
	
?>