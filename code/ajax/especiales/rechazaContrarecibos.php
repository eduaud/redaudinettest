<?php
php_track_vars;

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");

extract($_GET);
extract($_POST);

$sqlContrarecibo="SELECT cl_control_contratos_detalles.id_contra_recibo
					FROM cl_contrarecibos
					LEFT JOIN cl_control_contratos_detalles ON cl_contrarecibos.id_contrarecibo=cl_control_contratos_detalles.id_contra_recibo
					WHERE id_detalle=".$contrato;
$result = valBuscador($sqlContrarecibo);
$sql_Update = "UPDATE cl_contrarecibos SET estatus='12' WHERE id_contrarecibo='".$result[0]."'";
mysql_query($sql_Update);

$insertUltimo="INSERT INTO cl_control_contratos_detalles(id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico
,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar
,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_audicel
,precio_suscripcion,motivo_rechazo,principal,activo,ultimo_movimiento ) 
	SELECT id_control_contrato,12,id_control_contrato,now(),id_tipo_activacion,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico
,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar
,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_audicel
,precio_suscripcion,'".$motivo."',principal,activo,ultimo_movimiento  
	FROM cl_control_contratos_detalles WHERE id_detalle=".$contrato;
mysql_query($insertUltimo) or die(mysql_error());

$sqlUpdate="UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle=".$contrato;
mysql_query($sqlUpdate)or die(mysql_error());

$contratodetalle=ultimoMov();

$sqlUpdateN="UPDATE cl_control_contratos_detalles SET id_accion_contrato=12 WHERE id_detalle=".$contratodetalle;
mysql_query($sqlUpdateN)or die(mysql_error());

function ultimoMov(){
	$sqlUltimoContrato="SELECT id_detalle FROM cl_control_contratos_detalles ORDER BY id_detalle DESC LIMIT 1";
	$result = valBuscador($sqlUltimoContrato);
	$contratodetalle=$result[0];
	return $contratodetalle;
}
?>