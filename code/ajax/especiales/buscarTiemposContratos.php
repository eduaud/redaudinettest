<?php 
php_track_vars;
include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");

//***   Calcula los Tiempos de Entrega   ***//
function calcula_tiempos_entrega($id_control_contrato){
	$queryT='SELECT cl_control_contratos.fecha_activacion, cl_control_contratos_detalles.fecha_movimiento,
				cl_control_contratos_detalles.id_accion_contrato
			FROM cl_control_contratos
			LEFT JOIN cl_control_contratos_detalles
				ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato
			WHERE cl_control_contratos_detalles.id_control_contrato = '.$id_control_contrato.' AND cl_control_contratos_detalles.id_accion_contrato IN (11,21)
				AND cl_control_contratos_detalles.principal = 1 AND cl_control_contratos_detalles.activo = 1 
			ORDER BY cl_control_contratos_detalles.fecha_movimiento desc';
	
	$resultT=mysql_query($queryT);
	$numT=mysql_num_rows($resultT);
	
	$fechaUno="";
	$fechaDos="";
	
	if($numT == 2 || $numT == 1){
		$i=0;
		while($datosT=mysql_fetch_array($resultT)){
			if($i == ($numT - 1)){
				$fechaUno=$datosT["fecha_activacion"];
				$fechaDos=$datosT["fecha_movimiento"];
			}
			$i++;
		}
		$tipoEntrega="1";
	} elseif($numT == 3 || $numT == 4){
		$i=0;
		while($datosT=mysql_fetch_array($resultT)){
			if($i == ($numT - 3)){
				$fechaDos=$datosT["fecha_movimiento"];
			} elseif($i == ($numT - 3)){
				$fechaUno=$datosT["fecha_movimiento"];
			}
			$i++;
		}
		$tipoEntrega="2";
	}
	
	$datetime1 = date_create($fechaUno);
	$datetime2 = date_create($fechaDos);
	$interval = date_diff($datetime1,$datetime2);
	$diasDiferencia = $interval->format('%a');
	
	return $diasDiferencia;
}
//***   Termina Calcula los Tiempos de Entrega   ***//

extract($_GET);
extract($_POST);
$condicion = " ad_clientes.id_cliente IN(".$_SESSION["USR"]-> clientes_relacionados.")";
if($folio != '' && $cuenta!=''){
	$condicion .= " AND contrato ='" .$folio. "' AND cl_control_contratos.cuenta='" .$cuenta. "'";
}
else{
	if($folio != '' && $condicion != '')
		$condicion .= " AND contrato='" .$folio. "'";
	else if($folio != '')
		$condicion .= " contrato='" .$folio ."'";
	if($cuenta != '' && $condicion!='')
		$condicion .= " AND cl_control_contratos.cuenta='" .$cuenta. "'";
	else if($cuenta != '')
		$condicion .= " cl_control_contratos.cuenta='" .$cuenta. "'";
}
if($body == 0){
	$PendientesEntrega = Datos('pendientes','1,100',$condicion,'','');
	$smarty->assign('a_PendientesEntrega',$PendientesEntrega);
	$smarty->assign('Body',$body);
	echo $smarty->fetch('especiales/busquedaTiemposContratos.tpl');
}
else if($body == 1){
	$campoEspecial=",cl_contrarecibos.id_contrarecibo,DATE_FORMAT(cl_contrarecibos.fecha_hora,'%d/%m/%Y') AS fecha_hora";
	$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
	$Entregados = Datos('entregados','11',$condicion,$campoEspecial,$leftUnionEspecial);
	//echo '<pre>';print_r($Entregados);echo '</pre>';
	$smarty->assign('a_Entregados',$Entregados);
	$smarty->assign('Body',$body);
	echo $smarty->fetch('especiales/busquedaTiemposContratos.tpl');
}
else if($body == 2){
	$campoEspecial=",DATE_FORMAT(cl_control_contratos_detalles.fecha_movimiento,'%d/%m/%Y') AS fecha_hora,motivo_rechazo";
	$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
	$Rechazados = Datos('rechazados','12',$condicion,$campoEspecial,$leftUnionEspecial);
	$smarty->assign('a_Rechazados',$Rechazados);
	$smarty->assign('Body',$body);
	echo $smarty->fetch('especiales/busquedaTiemposContratos.tpl');
} else if($body==3){
	/*$campoEspecial=",cl_contrarecibos.id_contrarecibo,DATE_FORMAT(cl_contrarecibos.fecha_hora,'%d/%m/%Y') AS fecha_hora,motivo_rechazo";
	$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";*/
	$campoEspecial=",cl_contrarecibos.id_contrarecibo,DATE_FORMAT(cl_contrarecibos.fecha_hora,'%d/%m/%Y') AS fecha_hora,cl_importacion_sabana_contratos.bt76 AS motivo_rechazo_1,cl_importacion_sabana_contratos.bt77 AS motivo_rechazo_2,cl_importacion_sabana_contratos.bt78 AS motivo_rechazo_3";
	$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
	$leftUnionEspecial.=" LEFT JOIN cl_importacion_sabana_contratos ON cl_importacion_sabana_contratos.id_carga=cl_control_contratos_detalles.id_carga 
	AND cl_importacion_sabana_contratos.n2 = cl_control_contratos.contrato 
	AND cl_importacion_sabana_contratos.t46 = cl_control_contratos.cuenta 
	AND cl_importacion_sabana_contratos.d106 = cl_control_contratos.fecha_activacion ";
	$Reagendado = Datos('reagendados','22',$condicion,$campoEspecial,$leftUnionEspecial);
	$smarty->assign('a_Reagendado',$Reagendado);
	$smarty->assign('Body',$body);
	echo $smarty->fetch('especiales/busquedaTiemposContratos.tpl');
}
else if($body==4){
	$campoEspecial=",cl_contrarecibos.id_contrarecibo,DATE_FORMAT(cl_contrarecibos.fecha_hora,'%d/%m/%Y') AS fecha_hora,motivo_rechazo";
	$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
	$MalaCalidad = Datos('malaCalidad','23',$condicion,$campoEspecial,$leftUnionEspecial);
	$smarty->assign('a_MalaCalidad',$MalaCalidad);
	$smarty->assign('Body',$body);
	echo $smarty->fetch('especiales/busquedaTiemposContratos.tpl');
}else if($body==5){
	$PorFacturar = Datos('facturar','3',$condicion,'','');
	$smarty->assign('a_PorFacturar',$PorFacturar);
	$smarty->assign('Body',$body);
	echo $smarty->fetch('especiales/busquedaTiemposContratos.tpl');
}else if($body==6){
	$campoEspecial = ",ad_facturas.id_factura,FORMAT(ad_facturas_detalle.iva_monto,2) AS iva,FORMAT(ad_facturas_detalle.importe,2) AS total,FORMAT((ad_facturas_detalle.importe + ad_facturas_detalle.iva_monto),2) AS total_contrato";
	$leftUnionEspecial=" LEFT JOIN ad_facturas ON cl_control_contratos_detalles.id_control_factura_distribuidor = ad_facturas.id_control_factura 
	LEFT JOIN ad_facturas_detalle ON cl_control_contratos_detalles.id_control_factura_detalle_distribuidor = ad_facturas_detalle.id_control_factura_detalle";
	$Facturado = Datos('facturados','4',$condicion,$campoEspecial,$leftUnionEspecial);
	$smarty->assign('a_Facturado',$Facturado);
	$smarty->assign('Body',$body);
	echo $smarty->fetch('especiales/busquedaTiemposContratos.tpl');
}
function Datos($caso,$accion,$condicion,$campoEspecial,$leftUnion){
	$aDatos=array();
	/*$sql="SELECT IF(cl_control_contratos_detalles.id_cliente <> 0,CONCAT_WS(' ',CONCAT(ad_clientes.clave,' ::'),ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno),CONCAT_WS(' ',CONCAT(ad_entidades_financieras.clave,' ::'),ad_entidades_financieras.nombre,ad_entidades_financieras.apellido_paterno,ad_entidades_financieras.apellido_materno)) AS distribuidor,IF(cl_control_contratos_detalles.id_cliente <> 0,ad_clientes.id_cliente,ad_entidades_financieras.id_entidad_financiera) AS id_cliente,id_detalle,cl_control_contratos.id_control_contrato,contrato,cl_control_contratos.cuenta,fecha_activacion,
	CONCAT('$',FORMAT(IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 != 'TMK01', (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
				IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 != 'TMK01', (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
						IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
								IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35),0)
							)
						)
					)
				)
			),2)) as comision,DATEDIFF(NOW(),fecha_activacion) AS dias_transcurridos". $campoEspecial .",cl_control_contratos.cuenta
	FROM cl_control_contratos_detalles 
	LEFT JOIN cl_control_contratos ON cl_control_contratos_detalles.id_control_contrato  = cl_control_contratos.id_control_contrato
	LEFT JOIN ad_clientes ON cl_control_contratos_detalles.id_cliente=ad_clientes.id_cliente
	LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
	LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
	LEFT JOIN ad_entidades_financieras ON ad_entidades_financieras.id_entidad_financiera = cl_control_contratos_detalles.id_entidad_financiera_tecnico
	LEFT JOIN ad_tipos_entidades_financieras 	ON ad_tipos_entidades_financieras.id_tipo_entidad_financiera = ad_entidades_financieras.id_tipo_entidad_financiera	
	LEFT JOIN cl_tipos_cliente_proveedor as cl_tipos_cliente_proveedor_entidadesFin	ON cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = ad_tipos_entidades_financieras.id_tipo_cliente_proveedor
	". $leftUnion ."
	WHERE 1 AND (".$condicion.")  AND cl_control_contratos_detalles.id_accion_contrato IN(".$accion.")
	AND cl_control_contratos_detalles.activo=1 
	AND ultimo_movimiento = 1
	AND principal=1 ORDER BY DATEDIFF(NOW(),fecha_activacion),CONCAT_WS(' ',CONCAT(ad_clientes.clave,' ::'),ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno)";*/

	$sql="SELECT IF(cl_control_contratos_detalles.id_cliente <> 0,CONCAT_WS(' ',CONCAT(ad_clientes.clave,' ::'),ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno),CONCAT_WS(' ',CONCAT(ad_entidades_financieras.clave,' ::'),ad_entidades_financieras.nombre,ad_entidades_financieras.apellido_paterno,ad_entidades_financieras.apellido_materno)) AS distribuidor,IF(cl_control_contratos_detalles.id_cliente <> 0,ad_clientes.id_cliente,ad_entidades_financieras.id_entidad_financiera) AS id_cliente,id_detalle,cl_control_contratos.id_control_contrato,contrato,cl_control_contratos.cuenta,fecha_activacion,
	CONCAT('$',FORMAT(IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 NOT IN ('TMK01','TMKOM','BCRCOMV','BCRCOM'), (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
				IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 NOT IN ('TMK01','TMKOM','BCRCOMV','BCRCOM'), (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
						IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
								IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35),0)
							)
						)
					)
				)
			),2)) as comision,DATEDIFF(NOW(),fecha_activacion) AS dias_transcurridos". $campoEspecial .",cl_control_contratos.cuenta
	FROM cl_control_contratos_detalles 
	LEFT JOIN cl_control_contratos ON cl_control_contratos_detalles.id_control_contrato  = cl_control_contratos.id_control_contrato
	LEFT JOIN ad_clientes ON cl_control_contratos_detalles.id_cliente=ad_clientes.id_cliente
	LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
	LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
	LEFT JOIN ad_entidades_financieras ON ad_entidades_financieras.id_entidad_financiera = cl_control_contratos_detalles.id_entidad_financiera_tecnico
	LEFT JOIN ad_tipos_entidades_financieras 	ON ad_tipos_entidades_financieras.id_tipo_entidad_financiera = ad_entidades_financieras.id_tipo_entidad_financiera	
	LEFT JOIN cl_tipos_cliente_proveedor as cl_tipos_cliente_proveedor_entidadesFin	ON cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = ad_tipos_entidades_financieras.id_tipo_cliente_proveedor
	". $leftUnion ."
	WHERE 1 AND (".$condicion.")  AND cl_control_contratos_detalles.id_accion_contrato IN(".$accion.")
	AND cl_control_contratos_detalles.activo=1 
	AND ultimo_movimiento = 1
	AND principal=1 ORDER BY DATEDIFF(NOW(),fecha_activacion),CONCAT_WS(' ',CONCAT(ad_clientes.clave,' ::'),ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno)";
	

	$result = mysql_query($sql)or die("Error en".$sql.mysql_error());
	$indice=0;
	while($datos=mysql_fetch_array($result)){
		$aDatos[$indice]['distribuidor'] = $datos['distribuidor'];
		$aDatos[$indice]['id_distribuidor'] = $datos['id_distribuidor'];
		$aDatos[$indice]['id_detalle']=$datos['id_detalle'];
		$aDatos[$indice]['id_control_contrato'] = $datos['id_control_contrato'];
		$aDatos[$indice]['contrato'] = $datos['contrato'];
		$aDatos[$indice]['cuenta'] = $datos['cuenta'];
		$aDatos[$indice]['fecha_activacion'] =date ( 'd/m/Y' , strtotime($datos['fecha_activacion']) );
		$aDatos[$indice]['comision'] = $datos['comision'];
		$fecha_activacion = $datos['fecha_activacion'];
		$aDatos[$indice]['dias_transcurridos'] = $datos['dias_transcurridos'];
		
		
		if($caso=='entregados'||$caso=="reagendados"||$caso=="malaCalidad"){
			$aDatos[$indice]['fecha_entrega'] = $datos['fecha_hora'];
			$aDatos[$indice]['contrarecibo'] = $datos['id_contrarecibo'];
		}
		if($caso=='rechazados' || $caso == 'reagendados' || $caso == 'malaCalidad'){
			$aDatos[$indice]['fecha_rechazo'] = $datos['fecha_hora'];
			$aDatos[$indice]['motivo_rechazo'] = $datos['motivo_rechazo'];
		}
		if($caso == 'reagendados'){
			$aDatos[$indice]['motivo_rechazo_1'] = $datos['motivo_rechazo_1'];
			$aDatos[$indice]['motivo_rechazo_2'] = $datos['motivo_rechazo_2'];
			$aDatos[$indice]['motivo_rechazo_3'] = $datos['motivo_rechazo_3'];
		}
		if($caso=='facturados'){
			$aDatos[$indice]['iva'] = $datos['iva'];
			$aDatos[$indice]['total'] = $datos['total'];
			$aDatos[$indice]['folio'] = $datos['id_factura'];
		}
		if($accion == '1,100' || $accion == '11' || $accion == '12' || $accion == '22'){
			if($accion == '1,100' || $accion == '11' || $accion == '12'){
				$sqlRango="
					SELECT dia_final FROM cl_rangos_contratos 
					WHERE id_rango=2
				";
			}elseif($accion == '22'){
				$sqlRango="
					SELECT dia_final FROM cl_rangos_contratos 
					WHERE id_rango=5
				";
			}
			$resultRango=mysql_query($sqlRango);
			
			$dias_transcurridos = calcula_tiempos_entrega($datos['id_control_contrato']);
			if($dias_transcurridos == 0){ $dias_transcurridos = 1; }
			
			$nuevafecha = strtotime ( '+'.mysql_result($resultRango,0).' day' , strtotime ( $fecha_activacion ) ) ;
			//if($aDatos[$indice]['dias_transcurridos'] > mysql_result($resultRango,0)){
			if($dias_transcurridos > mysql_result($resultRango,0)){
				$aDatos[$indice]['comision'] = '$' . number_format(0,2);	
			}
			$nuevafecha = date ( 'd/m/Y' , $nuevafecha );
			$aDatos[$indice]['fecha_vencimiento'] = $nuevafecha;
		}
		
		
		if($accion == '1,100' || $accion == '11' || $accion == '12'){
			$sqlColor="
				SELECT codigo_color 
				FROM cl_colores
				LEFT JOIN cl_rangos_contratos ON cl_colores.id_color = cl_rangos_contratos.id_color
				WHERE  (". $dias_transcurridos." BETWEEN dia_inicial AND dia_final) AND id_rango IN(1,2,3)";
		}elseif($accion == '22'){
			$sqlColor="
				SELECT codigo_color 
				FROM cl_colores
				LEFT JOIN cl_rangos_contratos ON cl_colores.id_color = cl_rangos_contratos.id_color
				WHERE  (". $dias_transcurridos." BETWEEN dia_inicial AND dia_final) AND id_rango IN(4,5,6)";
		}
		
		$resultColor = mysql_query($sqlColor);
		$array_color = mysql_fetch_array($resultColor);
		//print_r(	$array_color);
		$aDatos[$indice]['codigo_color'] = $array_color[0];
		
		if($accion == '4'){
			$aDatos[$indice]['total_contrato'] = $datos['total_contrato'];
		}
		$indice+=1;
	}
	//echo '<pre>';print_r($aDatos);echo '</pre>';
	return $aDatos;
}/*
function Datos($caso,$accion,$condicion,$campoEspecial,$leftUnion){
	$aDatos=array();
	$sql = "SELECT CONCAT_WS(' ',ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_materno) AS distribuidor,ad_clientes.id_cliente,id_detalle,cl_control_contratos.id_control_contrato,contrato,cl_control_contratos.cuenta,fecha_activacion,
	CONCAT('$',FORMAT(IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 != 'TMK01', (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc30),
				IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 != 'TMK01', (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
						IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34),
								IF(cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35), 0)
							)
						)
					)
				)
			),2)) as comision,DATEDIFF(NOW(),fecha_activacion) AS dias_transcurridos". $campoEspecial .",cl_control_contratos.cuenta
	FROM cl_control_contratos 
	LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato 
	LEFT JOIN ad_clientes ON cl_control_contratos_detalles.id_cliente=ad_clientes.id_cliente
	LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	LEFT JOIN cl_importacion_caja_comisiones ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
	LEFT JOIN cl_paquetes_sky ON cl_paquetes_sky.id_paquete_sky = cl_control_contratos_detalles.id_paquete_sky
	LEFT JOIN ad_entidades_financieras ON ad_entidades_financieras.id_entidad_financiera = cl_control_contratos_detalles.id_entidad_financiera_tecnico
	LEFT JOIN ad_tipos_entidades_financieras 	ON ad_tipos_entidades_financieras.id_tipo_entidad_financiera = ad_entidades_financieras.id_tipo_entidad_financiera	
	LEFT JOIN cl_tipos_cliente_proveedor as cl_tipos_cliente_proveedor_entidadesFin	ON cl_tipos_cliente_proveedor_entidadesFin.id_tipo_cliente_proveedor = ad_tipos_entidades_financieras.id_tipo_cliente_proveedor
	". $leftUnion ."
	WHERE  (".$condicion.")  AND cl_control_contratos_detalles.id_accion_contrato IN(".$accion.")
	AND cl_control_contratos_detalles.activo=1 
	AND ultimo_movimiento = 1
	AND principal=1 ";
	$result = mysql_query($sql)or die("Error en".$sql);
	$indice=0;
	while($datos=mysql_fetch_array($result)){
		$aDatos[$indice]['distribuidor'] = $datos['distribuidor'];
		$aDatos[$indice]['id_distribuidor'] = $datos['id_distribuidor'];
		$aDatos[$indice]['id_detalle']=$datos['id_detalle'];
		$aDatos[$indice]['id_control_contrato'] = $datos['id_control_contrato'];
		$aDatos[$indice]['contrato'] = $datos['contrato'];
		$aDatos[$indice]['cuenta'] = $datos['cuenta'];
		$aDatos[$indice]['fecha_activacion'] = date ( 'd/m/Y' , strtotime($datos['fecha_activacion']) );
		$aDatos[$indice]['comision'] = $datos['comision'];
		$fecha_activacion = $datos['fecha_activacion'];
		$aDatos[$indice]['dias_transcurridos'] = $datos['dias_transcurridos'];
		
		
		if($caso=='entregados'||$caso=="reagendados"||$caso=="malaCalidad"){
			$aDatos[$indice]['fecha_entrega'] = $datos['fecha_hora'];
			$aDatos[$indice]['contrarecibo'] = $datos['id_contrarecibo'];
		}
		if($caso=='rechazados' || $caso == 'reagendados' || $caso == 'malaCalidad'){
			$aDatos[$indice]['fecha_rechazo'] = $datos['fecha_hora'];
			$aDatos[$indice]['motivo_rechazo'] = $datos['motivo_rechazo'];
		}
		if($caso=='facturados'){
			$aDatos[$indice]['iva'] = $datos['iva'];
			$aDatos[$indice]['total'] = $datos['total'];
			$aDatos[$indice]['folio'] = $datos['id_factura'];
		}
		if($accion == '1,100' || $accion == '11' || $accion == '12' || $accion == '22'){
			if($accion == '1,100' || $accion == '11' || $accion == '12'){
				$sqlRango="
					SELECT dia_final FROM cl_rangos_contratos 
					WHERE id_rango=2
				";
			}elseif($accion == '22'){
				$sqlRango="
					SELECT dia_final FROM cl_rangos_contratos 
					WHERE id_rango=5
				";
			}
			$resultRango=mysql_query($sqlRango);
			
			$dias_transcurridos = calcula_tiempos_entrega($datos['id_control_contrato']);
			if($dias_transcurridos == 0){ $dias_transcurridos = 1; }
			
			$nuevafecha = strtotime ( '+'.mysql_result($resultRango,0).' day' , strtotime ( $fecha_activacion ) ) ;
			//if($aDatos[$indice]['dias_transcurridos'] > mysql_result($resultRango,0)){
			if($dias_transcurridos > mysql_result($resultRango,0)){
				$aDatos[$indice]['comision'] = '$' . number_format(0,2);	
			}
			$nuevafecha = date ('d/m/Y',$nuevafecha );
			$aDatos[$indice]['fecha_vencimiento'] = $nuevafecha;
		}
		
		
		if($accion == '1,100' || $accion == '11' || $accion == '12'){
		$sqlColor="
			SELECT codigo_color 
			FROM cl_colores
			LEFT JOIN cl_rangos_contratos ON cl_colores.id_color = cl_rangos_contratos.id_color
			WHERE  (". $dias_transcurridos." BETWEEN dia_inicial AND dia_final) AND id_rango IN(1,2,3)";
		}elseif($accion == '22'){
		$sqlColor="
			SELECT codigo_color 
			FROM cl_colores
			LEFT JOIN cl_rangos_contratos ON cl_colores.id_color = cl_rangos_contratos.id_color
			WHERE  (". $dias_transcurridos." BETWEEN dia_inicial AND dia_final) AND id_rango IN(4,5,6)";
		}
		
		$resultColor = mysql_query($sqlColor);
		$array_color = mysql_fetch_array($resultColor);
		//print_r(	$array_color);
		$aDatos[$indice]['codigo_color'] = $array_color[0];
		
		if($accion == '4'){
			$aDatos[$indice]['total_contrato'] = $datos['total_contrato'];
		}
		$indice+=1;
	}
	//echo '<pre>';print_r($aDatos);echo '</pre>';
	return $aDatos;
}*/
?>
