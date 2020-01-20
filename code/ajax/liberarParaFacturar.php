<?php
include("../../conect.php");

$comisiones = json_decode($_POST['comisiones'], true);

if(isset($_POST['comentariosRechazo'])){
	$comentariosRechazo = json_decode($_POST['comentariosRechazo'], true);
}

$arrComentarios=array();
$idUno = '';
$idDetalleUno = '';
$idDos = '';
$idDetalleDos = '';
$tipo2=$_POST['tipo2'];

for($i = 0; $i < count($comisiones); $i++){
	$ids=explode(',',$comisiones[$i]);
	
	if($ids[2] == "contratos"){
		if($idUno == "") $idUno .= "'".$ids[0]."'";
		else $idUno .= ",'".$ids[0]."'";
		
		if($idDetalleUno == "") $idDetalleUno .= "'".$ids[1]."'";
		else $idDetalleUno .= ",'".$ids[1]."'";
	} elseif($ids[2] == "penalizaciones" || $ids[2] == "bonos"){
		if($idDos == "") $idDos .= "'".$ids[0]."'";
		else $idDos .= ",'".$ids[0]."'";
		
		if($idDetalleDos == "") $idDetalleDos .= "'".$ids[1]."'";
		else $idDetalleDos .= ",'".$ids[1]."'";
	}
	
	if(isset($_POST['comentariosRechazo'])){
		array_push($arrComentarios,array($comentariosRechazo[$i],$ids[0],$ids[1],$ids[2]));
	}
}

$querySelect='SELECT * FROM cl_control_contratos_detalles WHERE id_control_contrato IN ('.$idUno.') AND id_detalle IN ('.$idDetalleUno.')';

$resultSelect=mysql_query($querySelect);
$numSelect=mysql_num_rows($resultSelect);

if($numSelect > 0){
	$ultimo_movimiento='';
	$aceptado='';
	if($tipo2 == 'penalizaciones'){
		$ultimo_movimiento='2';
		$aceptado='1';
	} elseif($tipo2 == 'bonos'){
		$ultimo_movimiento='3';
	}
	
	while($colSelect=mysql_fetch_array($resultSelect)){
		if($colSelect["id_accion_contrato"] == "50"){ $id_accion_contrato="52"; }
		elseif($colSelect["id_accion_contrato"] == "51"){ $id_accion_contrato="53"; }
		elseif($colSelect["id_accion_contrato"] == "52"){ $id_accion_contrato="54"; }
		elseif($colSelect["id_accion_contrato"] == "53"){ $id_accion_contrato="55"; }
		elseif($colSelect["id_accion_contrato"] == "60"){ $id_accion_contrato="62"; }
		elseif($colSelect["id_accion_contrato"] == "61"){ $id_accion_contrato="63"; }
		
		$comentario_rechazo_penalizacion='';
		
		if(isset($_POST['comentariosRechazo'])){
			for($p=0; $p< count($arrComentarios); $p++){
				
				//$q="insert into a_pruebas (valor) values ('".$colSelect['id_control_contrato']." : ".$arrComentarios[$p][1]." -- ".$colSelect['id_detalle']." : ".$arrComentarios[$p][2]." -- ".$arrComentarios[$p][3]." : contratos')"; mysql_query($q);

				if($colSelect['id_control_contrato'] == $arrComentarios[$p][1] && $colSelect['id_detalle'] == $arrComentarios[$p][2] && $arrComentarios[$p][3] == 'contratos'){
					if($arrComentarios[$p][0] != 'NA'){
						$comentario_rechazo_penalizacion=$arrComentarios[$p][0];
					}
				}
			}
		}
		
		$queryInsert='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,
					id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
					id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,
					id_producto_servicio_facturar_audicel,monto_pagar,monto_cobrar,monto_penalizacion,monto_bono,id_calificacion,id_control_factura_distribuidor,
					id_control_factura_detalle_distribuidor,id_control_factura_audicel,id_control_cuenta_por_pagar_operadora,id_usuario_alta,
					id_usuario_modifico,id_carga,precio_suscripcion,motivo_rechazo,numero_remesa,
					activo,ultimo_movimiento,comentario_rechazo_penalizacion,aceptado) VALUES ("'.$colSelect["id_control_contrato"].'","'.$id_accion_contrato.'","'.$colSelect["id_contra_recibo"].'",
					"'. date("Y-m-d H:i:s").'","'.$colSelect["id_tipo_activacion"].'","'.$colSelect["id_control_serie"].'","'.$colSelect["numero_serie"].'",
					"'.$colSelect["id_paquete_sky"].'","'.$colSelect["id_sucursal"].'","'.$colSelect["id_cliente"].'",
					"'.$colSelect["id_cliente_tecnico"].'","'.$colSelect["id_entidad_financiera_tecnico"].'",
					"'.$colSelect["id_entidad_financiera_vendedor"].'","'.$colSelect["id_funcionalidad"].'","'.$colSelect["clave"].'",
					"'.$colSelect["id_detalle_caja_comisiones"].'","'.$colSelect["id_producto_servicio_facturar"].'",
					"'.$colSelect["id_producto_servicio_facturar_audicel"].'","'.$colSelect["monto_pagar"].'","'.$colSelect["monto_cobrar"].'",
					"'.$colSelect["monto_penalizacion"].'","'.$colSelect["monto_bono"].'","'.$colSelect["id_calificacion"].'","'.$colSelect["id_control_factura_distribuidor"].'",
					"'.$colSelect["id_control_factura_detalle_distribuidor"].'","'.$colSelect["id_control_factura_audicel"].'",
					"'.$colSelect["id_control_cuenta_por_pagar_operadora"].'","'.$_SESSION["USR"]->userid.'",
					"'.$colSelect["id_usuario_modifico"].'","'.$colSelect["id_carga"].'","'.$colSelect["precio_suscripcion"].'",
					"'.$colSelect["motivo_rechazo"].'","'.$colSelect["numero_remesa"].'","1","'.$ultimo_movimiento.'","'.$comentario_rechazo_penalizacion.'","'.$aceptado.'")';
		
		$resultInsert=mysql_query($queryInsert);
		
		$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$colSelect["id_detalle"].'"';
		$resultUpdate=mysql_query($sqlUpdate);
	}
}

if($tipo2 == 'penalizaciones'){
	$querySelect2='SELECT * FROM cl_control_penalizaciones_detalle WHERE id_control_penalizacion IN ('.$idDos.') AND id_control_penalizacion_detalle IN ('.$idDetalleDos.')';

	$resultSelect2=mysql_query($querySelect2);
	$numSelect2=mysql_num_rows($resultSelect2);

	if($numSelect2 > 0){
		while($colSelect2=mysql_fetch_array($resultSelect2)){
			if($colSelect2["id_accion"] == "50"){ $id_accion="52"; }
			elseif($colSelect2["id_accion"] == "51"){ $id_accion="53"; }
			elseif($colSelect2["id_accion"] == "52"){ $id_accion="54"; }
			elseif($colSelect2["id_accion"] == "53"){ $id_accion="55"; }
			
			$comentario_rechazo_penalizacion='';
			
			if(isset($_POST['comentariosRechazo'])){
				for($p=0; $p< count($arrComentarios); $p++){
					if($colSelect['id_control_contrato'] == $arrComentarios[$p][1] && $colSelect['id_detalle'] == $arrComentarios[$p][2] && $arrComentarios[$p][3] == 'penalizaciones'){
						if($arrComentarios[$p][0] != 'NA'){
							$comentario_rechazo_penalizacion=$arrComentarios[$p][0];
						}
					}
				}
			}
			
			$queryInsert2='INSERT INTO cl_control_penalizaciones_detalle (id_control_penalizacion,id_accion,fecha_movimiento,hora,id_usuario_agrego,id_control_serie,
						id_sucursal,id_cliente,id_factura_audicel,id_producto_servicio,monto_penalizacion,aceptado,id_usuario_modifico,fecha_modificacion,
						activo,ultimo_movimiento,comentario_rechazo_penalizacion) VALUES ("'.$colSelect2["id_control_penalizacion"].'","'.$id_accion.'","'. date("Y-m-d").'",
						"'. date("H:i:s").'","'.$_SESSION["USR"]->userid.'","'.$colSelect2["id_control_serie"].'","'.$colSelect2["id_sucursal"].'",
						"'.$colSelect2["id_cliente"].'","'.$colSelect2["id_factura_audicel"].'","'.$colSelect2["id_producto_servicio"].'",
						"'.$colSelect2["monto_penalizacion"].'","1","'.$colSelect2["id_usuario_modifico"].'","'.$colSelect2["fecha_modificacion"].'","1","2",
						"'.$comentario_rechazo_penalizacion.'")';
			
			$resultInsert2=mysql_query($queryInsert2);
			
			$sqlUpdate2='UPDATE cl_control_penalizaciones_detalle SET ultimo_movimiento=0 WHERE id_control_penalizacion_detalle="'.$colSelect2["id_control_penalizacion_detalle"].'"';
			$resultUpdate2=mysql_query($sqlUpdate2);
		}
	}
} elseif($tipo2 == 'bonos'){
	$querySelect2='SELECT * FROM cl_control_bonos_detalle WHERE id_control_bono IN ('.$idDos.') AND id_control_bono_detalle IN ('.$idDetalleDos.')';

	$resultSelect2=mysql_query($querySelect2);
	$numSelect2=mysql_num_rows($resultSelect2);

	if($numSelect2 > 0){
		while($colSelect2=mysql_fetch_array($resultSelect2)){
			if($colSelect2["id_accion"] == "60"){ $id_accion="62"; }
			elseif($colSelect2["id_accion"] == "61"){ $id_accion="63"; }
			
			$queryInsert2='INSERT INTO cl_control_bonos_detalle (id_control_bono,id_accion,fecha_movimiento,hora,id_usuario_agrego,id_control_serie,
						id_sucursal,id_cliente,id_factura_audicel,id_producto_servicio,monto_bono,aceptado,id_usuario_modifico,fecha_modificacion,
						activo,ultimo_movimiento,comentario_rechazo_penalizacion) VALUES ("'.$colSelect2["id_control_penalizacion"].'","'.$id_accion.'","'. date("Y-m-d").'",
						"'. date("H:i:s").'","'.$_SESSION["USR"]->userid.'","'.$colSelect2["id_control_serie"].'","'.$colSelect2["id_sucursal"].'",
						"'.$colSelect2["id_cliente"].'","'.$colSelect2["id_factura_audicel"].'","'.$colSelect2["id_producto_servicio"].'",
						"'.$colSelect2["monto_bono"].'","","'.$colSelect2["id_usuario_modifico"].'","'.$colSelect2["fecha_modificacion"].'","1","3",
						"")';
			
			$resultInsert2=mysql_query($queryInsert2);
			
			$sqlUpdate2='UPDATE cl_control_bonos_detalle SET ultimo_movimiento=0 WHERE id_control_bono_detalle="'.$colSelect2["id_control_bono_detalle"].'"';
			$resultUpdate2=mysql_query($sqlUpdate2);
		}
	}
}
?>