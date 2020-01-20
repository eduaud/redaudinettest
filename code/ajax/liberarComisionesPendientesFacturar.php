<?php
include("../../conect.php");

$comisiones = json_decode($_POST['comisiones'], true);

$idControl = '';
$idDetalle = '';

for($i = 0; $i < count($comisiones); $i++){
	$ids=explode(',',$comisiones[$i]);
	
	if($idControl == "") $idControl .= "'".$ids[0]."'";
	else $idControl .= ",'".$ids[0]."'";
	
	if($idDetalle == "") $idDetalle .= "'".$ids[1]."'";
	else $idDetalle .= ",'".$ids[1]."'";
}

$querySelect='SELECT id_detalle,id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,
					id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,
					id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,
					id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_carga,precio_suscripcion,activo
				FROM cl_control_contratos_detalles
				WHERE id_control_contrato IN ('.$idControl.') AND id_detalle IN ('.$idDetalle.') AND ultimo_movimiento="1"';

$resultSelect=mysql_query($querySelect);
$numSelect=mysql_num_rows($resultSelect);

if($numSelect > 0){
	while($colSelect=mysql_fetch_array($resultSelect)){
		$queryInsert='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,
						id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
						id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,
						id_calificacion,id_control_factura_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,id_carga,
						precio_suscripcion,activo,ultimo_movimiento)	VALUES ("'.$colSelect["id_control_contrato"].'","3","'.$colSelect["id_contra_recibo"].'",
						"'. date("Y-m-d H:i:s").'","'.$colSelect["id_tipo_activacion"].'","'.$colSelect["id_control_serie"].'","'.$colSelect["numero_serie"].'",
						"'.$colSelect["id_paquete_sky"].'","'.$colSelect["id_sucursal"].'","'.$colSelect["id_cliente"].'",
						"'.$colSelect["id_cliente_tecnico"].'","'.$colSelect["id_entidad_financiera_tecnico"].'",
						"'.$colSelect["id_entidad_financiera_vendedor"].'","'.$colSelect["id_funcionalidad"].'","'.$colSelect["clave"].'",
						"'.$colSelect["id_detalle_caja_comisiones"].'","'.$colSelect["id_producto_servicio_facturar"].'","'.$colSelect["monto_pagar"].'",
						"'.$colSelect["monto_cobrar"].'","'.$colSelect["id_calificacion"].'","'.$colSelect["id_control_factura_distribuidor"].'",
						"'.$colSelect["id_control_factura_audicel"].'","'.$colSelect["id_control_cuenta_por_pagar_operadora"].'","'.$_SESSION["USR"]->userid.'",
						"'.$colSelect["id_carga"].'","'.$colSelect["precio_suscripcion"].'","1","1")';
		
		$resultInsert=mysql_query($queryInsert);
		
		$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$colSelect["id_detalle"].'"';
		$resultUpdate=mysql_query($sqlUpdate);
	}
}
echo 'Listo.';
?>