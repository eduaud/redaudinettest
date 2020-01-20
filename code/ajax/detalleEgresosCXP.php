<?php	

include("../../conect.php");
include("../../consultaBase.php");

mysql_query("SET NAMES 'UTF8'");

$idCXP = $_POST["idCXP"];

$sql = "SELECT idProveedor, proveedor, idDocumento, documento, num_documento, monto AS total_registra, CONCAT('$', FORMAT(monto, 2)) AS total_muestra, 
		(pagosCP+pagosEgresos) AS subtotal_registra, CONCAT('$', FORMAT((pagosCP+pagosEgresos),2)) AS subtotal_muestra, 
		(monto-(pagosCP+pagosEgresos)) AS saldo_registra, CONCAT('$', FORMAT((monto-(pagosCP+pagosEgresos)),2)) AS saldo_muestra, id_cxp
		FROM
		(
			SELECT ad_proveedores.id_proveedor AS idProveedor, id_cuenta_por_pagar AS id_cxp, total AS monto, ad_tipos_documentos.nombre AS documento, 
					ad_proveedores.razon_social AS proveedor, ad_proveedores.id_proveedor AS id_proveedor, 
					id_tipo_documento_recibido AS idDocumento,
					CONCAT(aux.id_cuenta_por_pagar,' / ', if(aux.numero_documento is null , '',aux.numero_documento)) AS num_documento,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_cuentas_por_pagar_operadora_detalle_pagos WHERE activoDetCXP=1 AND id_cuenta_por_pagar=aux.id_cuenta_por_pagar) AS pagosCP,
					(SELECT if( SUM(monto) is null,0,SUM(monto)) FROM ad_egresos_detalle WHERE  activoDetEgreso=1 AND id_cuenta_por_pagar=aux.id_cuenta_por_pagar) AS pagosEgresos
							
			FROM ad_cuentas_por_pagar_operadora AS aux
				LEFT JOIN ad_tipos_documentos ON aux.id_tipo_documento_recibido = ad_tipos_documentos.id_tipo_documento
				LEFT JOIN ad_proveedores ON aux.id_proveedor = ad_proveedores.id_proveedor
				WHERE id_cuenta_por_pagar = " . $idCXP . "
		) AS datos";
				
$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();

echo $result['idProveedor'] . "|" . $result['proveedor'] . "|" . $result['idDocumento'] . "|" . $result['documento'] . "|" . $result['num_documento'] . "|" . $result['total_registra'] . "|" . $result['total_muestra'] . "|" . $result['subtotal_registra'] . "|" . $result['subtotal_muestra'] . "|" . $result['saldo_registra'] . "|" . $result['saldo_muestra'] . "|" . $result['id_cxp'];
		