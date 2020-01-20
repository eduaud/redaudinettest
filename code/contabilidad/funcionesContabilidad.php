<?php
/*include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");*/
function obtenerCuentaMayor($documento,$id_relacionado){
	$cuentaContable = '';
	switch($documento){
		case 1://CLIENTES
			$cuentaContable = '1130';
		break;
		case 2://PROVEEDORES
			$cuentaContable = '2110';
		break;
		case 3://CUENTAS BANCARIAS
			$cuentaContable = '1120';
		break;
		case 4://FACTURAS
			$sqlFacturasCC = "
				SELECT cuenta_contable
				FROM ad_clientes 
				LEFT JOIN ad_facturas_audicel 
					ON ad_clientes.id_cliente = ad_facturas_audicel.id_cliente
				LEFT JOIN scfdi_cuentas_contables 
					ON ad_clientes.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable
				WHERE id_control_factura = ".$id_relacionado;
			$arrFacturasCC = valBuscador($sqlFacturasCC);
			$cuentaContable = $arrFacturasCC[0];
		break;
		case 5://NOTAS DE CRÉDITO
		$sqlNotasCreditoCC = "
			SELECT cuenta_contable
			FROM ad_clientes 
			LEFT JOIN ad_notas_credito_audicel 
				ON ad_clientes.id_cliente = ad_notas_credito_audicel.id_cliente
			LEFT JOIN scfdi_cuentas_contables 
				ON ad_clientes.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable
			WHERE id_control_nota_credito = ".$id_relacionado;
		$arrNotasCreditoCC = valBuscador($sqlNotasCreditoCC);
		$cuentaContable = $arrNotasCreditoCC[0];		
		break;
		case 6://CUENTAS POR PAGAR
			$sqlCXP = "
				SELECT cuenta_contable
				FROM ad_proveedores 
				LEFT JOIN ad_cuentas_por_pagar_operadora 
					ON ad_proveedores.id_proveedor = ad_cuentas_por_pagar_operadora.id_proveedor
				LEFT JOIN scfdi_cuentas_contables 
					ON ad_proveedores.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable
				WHERE id_cuenta_por_pagar = ".$id_relacionado;
			$arrCXP = valBuscador($sqlCXP);
			$cuentaContable = $arrCXP[0];	
		break;
		case 7://PAGOS ANTICIPADOS
			$sqlPA = "
				SELECT cuenta_contable
				FROM ad_clientes 
				LEFT JOIN ad_pagos_anticipados 
					ON ad_clientes.id_cliente = ad_pagos_anticipados.id_cliente
				LEFT JOIN scfdi_cuentas_contables 
					ON ad_clientes.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable
				WHERE id_control_pago_anticipado = ".$id_relacionado;
			$arrPA = valBuscador($sqlPA);
			$cuentaContable = $arrPA[0];
		break;
		case 8://DEPOSITOS BANCARIOS
		break;
		case 9://ALMACEN
		break;
		case 10://COSTO DE VENTAS
		break;
		case 11://TIPO CLIENTES PROVEEDORES CASOS ESPECIALES PARA DISTRIBUIDORES
			$sqlTipo_proveedor = "
				SELECT cuenta_contable 
				FROM sys_parametros_contabilidad 
				LEFT JOIN ad_tipos_proveedores 
					ON sys_parametros_contabilidad.id_cliente_proveedor = ad_tipos_proveedores.id_tipo_proveedor 
				LEFT JOIN scfdi_cuentas_contables ON ad_tipos_proveedores.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable
				";
			$arrTipoProveedor = valBuscador($sqlTipo_proveedor);
			$cuentaContable = $arrTipoProveedor[0];
		break;
		case 12://TIPO PROVEEDORES CLIENTES CASO ESPECIAL SKY
			$sqlTipo_proveedor = "
				SELECT cuenta_contable 
				FROM sys_parametros_contabilidad 
				LEFT JOIN ad_tipos_proveedores 
					ON sys_parametros_contabilidad.id_proveedor_cliente = ad_tipos_proveedores.id_tipo_proveedor 
				LEFT JOIN scfdi_cuentas_contables ON ad_tipos_proveedores.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable
				";
			$arrTipoProveedor = valBuscador($sqlTipo_proveedor);
			$cuentaContable = $arrTipoProveedor[0];
		break;
		case 13://TIPO DE CLIENTES
			$sqlTipo_cliente = "
				SELECT cuenta_contable 
				FROM ad_tipos_clientes
				LEFT JOIN scfdi_cuentas_contables 
					ON ad_tipos_clientes.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable 
				WHERE id_tipo_cliente = ".$id_relacionado;
			$arrTipo_cliente = valBuscador($sqlTipo_cliente);
			$cuentaContable = $arrTipo_cliente[0];
		break;
		case 14://TIPO DE PROVEEDORES
			$sqlTipo_proveedor = "
				SELECT cuenta_contable 
				FROM ad_tipos_proveedores
				LEFT JOIN scfdi_cuentas_contables 
					ON ad_tipos_proveedores.id_cuenta_contable = scfdi_cuentas_contables.id_cuenta_contable 
				WHERE id_tipo_proveedor = ".$id_relacionado;
			$arrTipoProveedor = valBuscador($sqlTipo_proveedor);
			$cuentaContable = $arrTipoProveedor[0];
		break;
		default:
		break;
	}
	$sqlCuentaContable = "
		SELECT id_cuenta_contable 
		FROM scfdi_cuentas_contables 
		WHERE cuenta_contable = '".$cuentaContable."'";
	$arrCC = valBuscador($sqlCuentaContable);
	return $arrCC[0];
}
function GeneraCuentaContable($cuenta_padre,$cuenta,$nombre,$estatus,$nivel,$en_poliza,$visible_arbol){
	$sqlCuentaContable = "
		INSERT INTO scfdi_cuentas_contables(cuenta_contable, nombre, nivel, es_cuenta_mayor, id_genero_cuenta_contable, facturable, id_cuenta_superior, id_cuenta_mayor, visible_arbol, en_poliza, activo, niveles, id_cuenta_sat, id_estatus_cc)
		(SELECT CONCAT(cuenta_contable,'-','".$cuenta."'), '".$nombre."', '".$nivel."', 0, id_genero_cuenta_contable, 0, '".$cuenta_padre."', id_cuenta_mayor,'".$visible_arbol."', '".$en_poliza."', 1, niveles, id_cuenta_sat, '".$estatus."' FROM scfdi_cuentas_contables WHERE id_cuenta_contable = '".$cuenta_padre."')";
	if(mysql_query($sqlCuentaContable))
		$id_cuenta_contable = mysql_insert_id();
	else
		die("Error en $sqlCuentaContable ".mysql_error());
	return $id_cuenta_contable;
}
function ActualizaCuentaContable($cuenta_contable,$nombre){
	$sqlUpdate = "
		UPDATE scfdi_cuentas_contables 
		SET nombre = '".$nombre."' 
		WHERE id_cuenta_contable = ".$cuenta_contable;
	mysql_query($sqlUpdate) or die("error en $sqlUpdate ".mysql_error());
}
?>