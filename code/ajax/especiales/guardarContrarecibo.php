<?php
php_track_vars;

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");

extract($_GET);
extract($_POST);

$contratos=json_decode($contratos);
$sucursales=json_decode($sucursales);
$clientes=json_decode($clientes);

$a_sucursales= Array();
$a_contratos= Array();
$a_clientes= Array();
$a_clientes_contratos= Array();

array_push($a_clientes,$clientes[0]);
$aux=$clientes[0];

for($i=1;$i<count($clientes);$i++){
	if($aux!=$clientes[$i]){
		$aux=$clientes[$i];
		if(in_array($aux,$a_clientes)==false)
			array_push($a_clientes,$aux);		
	}
}
for($l=0;$l<count($a_clientes);$l++){
	for($j=0;$j<count($contratos);$j++){
		if($contratos[$j][1]==$a_clientes[$l]){
			$a_clientes_contratos[$clientes[$l]][$j]=$contratos[$j][0];
			array_push($a_contratos,$contratos[$j][0]);
		}
	}
}

$contrarecibosGenerados=0;
$bandera=false;
foreach($a_clientes_contratos as $cliente=>$clientes){
		$strTrans = "AUTOCOMMIT = 0;";
		mysql_query($strTrans);
		mysql_query("BEGIN;");
		
		$sqlInsert="INSERT INTO cl_contrarecibos(usuario,fecha_hora,envio_mail,nombre_entrego,estatus) VALUES(".$_SESSION["USR"]->userid .",now(),1,'".$usuario."','11')";
		mysql_query($sqlInsert) or die(mysql_error());
		
		$idUltimo=mysql_insert_id();
		
		foreach($clientes as $contratos){
			$sqlC="SELECT cl_control_contratos.id_control_contrato,id_cliente,id_entidad_financiera_tecnico FROM cl_control_contratos LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato where id_detalle=".$contratos;
			$result_c=mysql_query($sqlC);
			
			$arr_contrato=mysql_fetch_assoc($result_c);
			
			$id_contrato=$arr_contrato['id_control_contrato'];
		
			if($arr_contrato['id_cliente']!=''){
				$sqlUpda="UPDATE cl_contrarecibos SET id_cliente=".$arr_contrato['id_cliente']." WHERE id_contrarecibo=".$idUltimo;
			}elseif($arr_contrato['id_entidad_financiera_tecnico']!=''){
				$sqlUpda="UPDATE cl_contrarecibos SET id_entidad_financiera=".$arr_contrato['id_entidad_financiera_tecnico']." WHERE id_contrarecibo=".$idUltimo;
			}
			mysql_query($sqlUpda)or die(mysql_error());
			
			$sqlInsertDetalle="INSERT INTO cl_contrarecibos_detalle(id_contrarecibo,id_control_contrato) VALUES(".$idUltimo.",".$id_contrato.")";
			if(mysql_query($sqlInsertDetalle)){
				InsertaDetalle($idUltimo,$id_contrato);
				mysql_query("COMMIT;");
				$bandera=true;				
			}else{
				mysql_query("ROLLBACK;");
			}
		}
		if($bandera==true)
			$contrarecibosGenerados++;
}
echo "Se generaron ".$contrarecibosGenerados." contra recibos";

function ultimoMov($id_control_contrato){
	$sqlUltimoContrato="SELECT id_detalle FROM cl_control_contratos_detalles WHERE id_control_contrato=".$id_control_contrato." AND principal = 1 AND ultimo_movimiento=1 ORDER BY id_detalle DESC LIMIT 1";
	$result=mysql_query($sqlUltimoContrato);
	$contratodetalle=mysql_result($result,0);
	return $contratodetalle;
}
function InsertaDetalle($id_contrarecibo,$contrato){
$contratodetalle=ultimoMov($contrato);
if($contratodetalle!=''){
		$insertUltimo="INSERT INTO cl_control_contratos_detalles(id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico
		,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar
		,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_audicel
		,precio_suscripcion,activo,ultimo_movimiento,id_usuario_alta,principal) 
			SELECT id_control_contrato,11,$id_contrarecibo,now(),id_tipo_activacion,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico
		,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar
		,monto_pagar,monto_cobrar,id_calificacion,id_control_factura_distribuidor,id_control_factura_audicel
		,precio_suscripcion,activo,ultimo_movimiento,".($_SESSION["USR"]->userid).",principal
			FROM cl_control_contratos_detalles WHERE id_detalle=".$contratodetalle;
		mysql_query($insertUltimo) or die(mysql_error().$insertUltimo);
		
		$id=mysql_insert_id();
		$sqlUpdateN="UPDATE cl_control_contratos_detalles SET id_accion_contrato=11 WHERE id_detalle=".$id;
		mysql_query($sqlUpdateN)or die(mysql_error().$sqlUpdateN);

}
		
		$sqlUpdate="UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_accion_contrato in(1,100) AND id_control_contrato=".$contrato;
		mysql_query($sqlUpdate)or die(mysql_error().$sqlUpdate);

		
	
}
?>