<?php

/************************************************************************************/
//Valida Que el numero introducido en "Cantidad a Ingresar" sea igual a el numero de registros en el archivo CSV.
function comparaCantidadIngresarVSRegistros($idLayout, $tmp_name, $numeroSerie){
	//$idLayout Es el Id de Layout a subir
	//$tmp_name Es el nombre del archivo
	//$numeroSerie Es el numero de registro el el achivo
	$strError = "";
	switch ($idLayout) {
		case '5':
			$numero_registros = count(file($tmp_name))-1;
			if($numero_registros!=$numeroSerie){ 
				$strError = " El numero de registros en el archivo CSV, no corresponde a la Cantidad a Ingresar<br />"; 
			}
			break;
	}
	return $strError;
}
/************************************************************************************/


/***   Actualiza los campos de una tabla   ***/
function actualizaCampos($tabla,$campos,$datos,$camposWhere,$datosWhere){
	$camposActualiza='';
	if($campos != ""){
		$arrCampos=explode(',',$campos);
		$arrDatos=explode(',',$datos);
		
		for($i=0; $i< count($arrCampos); $i++){
			if($camposActualiza == ''){
				$camposActualiza .= $arrCampos[$i].'="'.$arrDatos[$i].'"';
			} else {
				$camposActualiza .= ','.$arrCampos[$i].'="'.$arrDatos[$i].'"';
			}
		}
	}
	
	$filtro='';
	if($camposWhere != ""){
		$arrCamposWhere=explode(',',$camposWhere);
		$arrDatosWhere=explode(',',$datosWhere);
		
		for($j=0; $j< count($arrCamposWhere); $j++){
			if($filtro == ''){
				$filtro .= $arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			} else {
				$filtro .= ' AND '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			}
		}
	}
	
	if($camposActualiza != '' && $filtro != ''){
		$query='UPDATE '.$tabla.' SET '.$camposActualiza.' WHERE '.$filtro;
		$result=mysql_query($query);
	}
}
/***   Termina Actualiza los campos de una tabla   ***/


/***   Extrae caja de comisiones   ***/
function extraeCajasDeComision(){
	$arrCajaComisiones=array(array(0,'-- Seleccione --'));
	$queryCC='SELECT id_caja_comision,nombre FROM cl_cajas_comisiones';
	$resultCC=mysql_query($queryCC);
	
	if(mysql_num_rows($resultCC) > 0){
		while($datosCC=mysql_fetch_array($resultCC)){
			array_push($arrCajaComisiones,$datosCC);
		}
	}
	
	return $arrCajaComisiones;
}
function extraeAnios(){
	$arrAnios=array(array(0,'-- Seleccione --'));
	$queryAnios='SELECT id_anio,nombre FROM sys_anios WHERE id_anio>=2016';
	$resultAnios=mysql_query($queryAnios);
	
	if(mysql_num_rows($resultAnios) > 0){
		while($datosAnios=mysql_fetch_array($resultAnios)){
			array_push($arrAnios,$datosAnios);
		}
	}
	
	return $arrAnios;
}
/***   Termina Extrae caja de comisiones   ***/


/***   Extrae el nombre de una caja de comision   ***/
function extraeNombreCajaDeComision($idCajaComision){
	$queryCC='SELECT nombre FROM cl_cajas_comisiones WHERE id_caja_comision='.$idCajaComision;
	$resultCC=mysql_query($queryCC);
	$datosCC=mysql_fetch_array($resultCC);
	$nombre=$datosCC['nombre'];
	
	return $nombre;
}
/***   Termina Extrae el nombre de una caja de comision   ***/


/***   Valida que exista un valor en un campo de una tabla   ***/
function validaValorEnCampoTabla($tabla,$camposWhere,$datosWhere,$tablaLeftJoin,$camposUnion){
	$filtro='';
	if($camposWhere != ""){
		$arrCamposWhere=explode(',',$camposWhere);
		$arrDatosWhere=explode(',',$datosWhere);
		
		for($j=0; $j< count($arrCamposWhere); $j++){
			if($filtro == ''){
				$filtro .= $arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			} else {
				$filtro .= ' AND '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			}
		}
	}
	
	if($tablaLeftJoin != ""){
		$arrcamposUnion=explode(',',$camposUnion);
		$queryVVBD='SELECT '.$camposWhere.' FROM '.$tabla.' LEFT JOIN '.$tablaLeftJoin.' ON '.$arrcamposUnion[0].' = '.$arrcamposUnion[1].' WHERE '.$filtro;
	} else {
		$queryVVBD='SELECT '.$camposWhere.' FROM '.$tabla.' WHERE '.$filtro;
	}
	
	$resultVVBD=mysql_query($queryVVBD);
	
	if(mysql_num_rows($resultVVBD) > 0) return true;
	else return false;
}
/***   Termina Valida que exista un valor en un campo de una tabla   ***/


/***   Valida que una fecha se encuentre entre un rango de fechas de una tabla   ***/
function validaFechaEsteEnRangoFechas($tabla,$campoIni,$campoFin,$fecha,$camposWhere,$datosWhere){
	$filtro='';
	if($camposWhere != ""){
		$arrCamposWhere=explode(',',$camposWhere);
		$arrDatosWhere=explode(',',$datosWhere);
		
		for($j=0; $j< count($arrCamposWhere); $j++){
			$filtro .= ' AND '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
		}
	}
	
	$queryVF="SELECT ".$campoIni." FROM ".$tabla." WHERE '".$fecha."' BETWEEN ".$campoIni." AND ".$campoFin." ".$filtro;
	$resultVF=mysql_query($queryVF);
	
	if(mysql_num_rows($resultVF) > 0) return true;
	else return false;
}
/***   Termina Valida que una fecha se encuentre entre un rango de fechas de una tabla   ***/


/***   Valida que una fecha se encuentre entre un rango de fechas de una tabla   ***/
function validaFechaTengaDetalleComisionAsociado($tabla,$campoIni,$campoFin,$fecha,$camposWhere,$datosWhere){
	$filtro='';
	if($camposWhere != ""){
		$arrCamposWhere=explode(',',$camposWhere);
		$arrDatosWhere=explode(',',$datosWhere);
		
		for($j=0; $j< count($arrCamposWhere); $j++){
			$filtro .= ' AND '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
		}
	}
	
	$queryVF="SELECT id_caja_comision FROM ".$tabla." WHERE '".$fecha."' BETWEEN ".$campoIni." AND ".$campoFin.$filtro;
	$resultVF=mysql_query($queryVF);
	
	if(mysql_num_rows($resultVF) > 0){
		$idCajaComision="";
		$datosVF=mysql_fetch_array($resultVF);
		$idCajaComision=$datosVF["id_caja_comision"];
		
		$queryVD="SELECT id_caja_comisiones FROM cl_importacion_caja_comisiones WHERE id_caja_comisiones='".$idCajaComision."' AND activo='1' LIMIT 1";
		$resultVD=mysql_query($queryVD);
		
		if(mysql_num_rows($resultVD) > 0) return true;
		else return false;
	}
	else return false;
}
/***   Termina Valida que una fecha se encuentre entre un rango de fechas de una tabla   ***/


/***   Valida que no se repita la informacion en el archivo a importar   ***/
function validaInformacionRepetidaEnElArchivoAImportar($idLayout,$archivo){
	$repetidos=array();
	
	if($idLayout == 2 || $idLayout == 7){
		$campos='IRD';
	} elseif ($idLayout == 5) {
		$campos='Numero de Serie';
	}
	
	if($campos != ''){
		$arrCampos=explode(',',$campos);
		$arrRegistros=array();
		
		if(($gestorAI = fopen($archivo, "r")) !== FALSE){
			$datosAIEncabezado = fgetcsv($gestorAI, 1000, ",");
			$totalCamposAI=count($datosAIEncabezado);
			
			$posiciones=array();
			for($e=0; $e < $totalCamposAI; $e++){
				for($f=0; $f < count($arrCampos); $f++){
					if($datosAIEncabezado[$e] == $arrCampos[$f]){
						array_push($posiciones,$e);
					}
				}
			}
			
			$linea = 2;
			while(($datosAI = fgetcsv($gestorAI, 1000, ",")) !== FALSE){
				$dt='';
				for($j=0; $j < count($posiciones); $j++){
					if($dt == ''){
						$dt .= $datosAI[$posiciones[$j]];
					} else {
						$dt .= ','.$datosAI[$posiciones[$j]];
					}
					
					if(count($arrRegistros) > 0){
						for($k=0; $k < count($arrRegistros); $k++){
							if($arrRegistros[$k][$j] == $datosAI[$posiciones[$j]]){
								array_push($repetidos,array($linea,$datosAIEncabezado[$posiciones[$j]],"El valor ".$datosAI[$posiciones[$j]]." se repite con el de la linea: ".($k + 2)));
							}
						}
					}
				}
				
				array_push($arrRegistros,explode(',',$dt));
				$linea++;
			}
			fclose($gestorAI);
		}
	}
	
	return $repetidos;
}
/***   Termina Valida que no se repita la informacion en el archivo a importar   ***/


/***   Se agregan campos unicos por layout   ***/
function agregarCamposUnicosPorLayout($idLayout){
	$campos='';
	
	if($idLayout == 6){ /***   Caja de Comisiones   ***/
		$campos=',id_producto_servicio_cxc,id_producto_servicio_cxp,id_caja_comisiones,activo';
	} elseif($idLayout == 8){
		$campos=',id_estatus_contrato';
	} elseif($idLayout == 5){
		$campos=',id_almacen, id_orden_compra, activo,n1';
	} elseif($idLayout == 12){
		$campos=',id_cxp';
	} elseif($idLayout == 17){
		$campos=',activo';
	} elseif($idLayout == 18){
		$campos=',id_sucursal,id_cliente,id_anio,id_mes1,id_mes2,id_mes3,id_mes4,id_mes5,id_mes6,id_mes7,id_mes8,id_mes9,id_mes10,id_mes11,id_mes12,id_concepto,activo';
	} elseif($idLayout == 19){
		$campos=',activo';
	}
	return $campos;
}
/***   Termina Se agregan campos unicos por layout   ***/


/***   Devuelve un campo apartir de otro campo   ***/
function devuelveCampoApartirOtroCampo($tabla,$camposRespuesta,$camposWhere,$datosWhere,$tablaLeftJoin,$camposUnion){
	$arrRespuesta=array();
	
	$filtro='';
	if($camposWhere != ""){
		$arrCamposWhere=explode(',',$camposWhere);
		$arrDatosWhere=explode(',',$datosWhere);
		
		for($j=0; $j< count($arrCamposWhere); $j++){
			if($filtro == ''){
				$filtro .= $arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			} else {
				$filtro .= ' AND '.$arrCamposWhere[$j].'="'.$arrDatosWhere[$j].'"';
			}
		}
	}
	
	if($tablaLeftJoin != ""){
		$arrcamposUnion=explode(',',$camposUnion);
		$queryVVBD='SELECT '.$camposRespuesta.' FROM '.$tabla.' LEFT JOIN '.$tablaLeftJoin.' ON '.$arrcamposUnion[0].' = '.$arrcamposUnion[1].' WHERE '.$filtro;
	} else {
		$queryVVBD='SELECT '.$camposRespuesta.' FROM '.$tabla.' WHERE '.$filtro;
	}
	$resultVVBD=mysql_query($queryVVBD);
	//echo $queryVVBD;
	while($datosVVBD=mysql_fetch_array($resultVVBD)){
		array_push($arrRespuesta, $datosVVBD);
	}
	
	return $arrRespuesta;
}
/***   Termina Devuelve un campo apartir de otro campo   ***/


/***   Realiza inserts en una segunda tabla por layout   ***/
function ActualizaDatosAntesDeInsertar($idLayout,$datos){
	if($idLayout==17){
		$sqlUpdate="UPDATE cl_importacion_remesas SET activo=0 WHERE t46 IN($datos)";
		mysql_query($sqlUpdate);
	}
}


//***   Obtener funcionalidad   ***//
function obtenerFuncionalidad($idImportacion,$contrato,$cuenta,$fechaActivacion,$ird,$tipo){
	$queryFMI=''; $id_funcionalidad='';
	
	if($tipo == 'activacion'){
		$queryFMI='SELECT GROUP_CONCAT(t55) as IRDS FROM cl_importacion_activaciones
					WHERE id_carga = "'.$idImportacion.'" AND t56 = "'.$contrato.'" AND t46 = "'.$cuenta.'" AND
							d106 = "'. formatoFechaYMD($fechaActivacion).'"
					GROUP BY t56, t46, d106 HAVING COUNT(t56) > 1';
	} elseif($tipo == 'crecimiento'){
		$queryFMI='SELECT GROUP_CONCAT(t55) as IRDS FROM cl_importacion_crecimientos
								WHERE id_carga = "'.$idImportacion.'" AND t56 = "'.$contrato.'" AND t46 = "'.$cuenta.'" AND
										dt116 = "'. formatoFechaYMD($fechaActivacion).'"
								GROUP BY t56, t46, dt116 HAVING COUNT(t56) > 1';
	}
	
	$resultFMI=mysql_query($queryFMI);
	$numFMI=mysql_num_rows($resultFMI);

	if($numFMI > 0){
		while($datosFMI=mysql_fetch_array($resultFMI)){
			$arr_IRDS=explode(",",$datosFMI["IRDS"]);
			$arr_funcionalidades=array();
			$id_funcionalidad = '';
			
			for($f = 0; $f < count($arr_IRDS); $f++){
				$arr_id_funcionalidad=devuelveCampoApartirOtroCampo('cl_tipos_equipo','id_funcionalidad','inicio_serie', substr($arr_IRDS[$f],0,6),'','');
				$arr_funcionalidades[$f]=$arr_id_funcionalidad[0][0];
			}
			
			$arr_funcionalidad_cantidad = array_count_values($arr_funcionalidades);
			$arr_posiciones=array_keys($arr_funcionalidad_cantidad);
			$num_equipos_diferentes=count($arr_funcionalidad_cantidad);
			
			if($num_equipos_diferentes > 1){
				$filtro='';
				for($h = 0; $h < $num_equipos_diferentes; $h++){
					if($filtro == ''){
						$filtro .= '(id_funcionalidad_basica = '.$arr_posiciones[$h].' AND cantidad = '.$arr_funcionalidad_cantidad[$arr_posiciones[$h]].')';
					} else {
						$filtro .= 'OR (id_funcionalidad_basica = '.$arr_posiciones[$h].' AND cantidad = '.$arr_funcionalidad_cantidad[$arr_posiciones[$h]].')';
					}					
				}
				
				$queryFuncionalidad='SELECT id_funcionalidad FROM cl_funcionalidades_detalles
									WHERE '.$filtro.' GROUP BY id_funcionalidad HAVING COUNT(id_funcionalidad) = '.$num_equipos_diferentes;
				
				$result_Funcionalidad=mysql_query($queryFuncionalidad);
				$datos_Funcionalidad=mysql_fetch_array($result_Funcionalidad);
				$id_funcionalidad = $datos_Funcionalidad['id_funcionalidad'];
			} else {
				$arr_id_funcionalidad=devuelveCampoApartirOtroCampo('cl_tipos_equipo','id_funcionalidad','inicio_serie', substr($ird,0,6),'','');
				$id_funcionalidad=$arr_id_funcionalidad[0][0];
			}
		}
	} else {
		$arr_id_funcionalidad=devuelveCampoApartirOtroCampo('cl_tipos_equipo','id_funcionalidad','inicio_serie', substr($ird,0,6),'','');
		$id_funcionalidad=$arr_id_funcionalidad[0][0];
	}
	
	return $id_funcionalidad;
}
//***   Termina Obtener funcionalidad   ***//


function insertPorLayout($idLayout,$tmp_name,$idImportacion,$datos){
	$arrErrores=array();
	if($idLayout == 2){ /***   Activaciones   ***/
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			$totalCamposCSV=count($datosLEncabezado);
			
			$fila=2;
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				$id_tipo_activacion='';
				$IRD='';
				$id_control_serie='';
				$id_paquete_sky='';
				
				$id_cliente_tecnico='';
				$id_sucursal='';
				$id_cliente='';
				$id_entidad_financiera_tecnico='';
				$id_funcionalidad='';
				$clave='';
				$id_detalle_caja_comisiones='';
				$id_producto_servicio_facturar='';
				$monto_pagar=0;
				$monto_cobrar=0;
				$precio_suscripcion='';
				
				for($j=0; $j < $totalCamposCSV; $j++){
					if($datosLEncabezado[$j] == 'Tipo de Instalacion'){
						$arr_id_tipo_activacion=devuelveCampoApartirOtroCampo('cl_tipos_activacion','id_tipo_activacion','letra',$datosL[$j],'','');
						$id_tipo_activacion = count($arr_id_tipo_activacion) > 0 ? $arr_id_tipo_activacion[0][0] : '';
					} elseif($datosLEncabezado[$j] == 'IRD'){
						$IRD=$datosL[$j];
						
						$arr_id_control_serie=devuelveCampoApartirOtroCampo('cl_control_series','id_control_serie','numero_serie',$datosL[$j],'','');
						$id_control_serie = count($arr_id_control_serie) > 0 ? $arr_id_control_serie[0][0] : '';
						
						//$arr_id_funcionalidad=devuelveCampoApartirOtroCampo('cl_tipos_equipo','id_funcionalidad','inicio_serie', substr($datosL[$j],0,6),'','');
						//$id_funcionalidad=$arr_id_funcionalidad[0][0];
						
					} elseif($datosLEncabezado[$j] == 'Paquete Principal'){
						$arr_id_paquete_sky=devuelveCampoApartirOtroCampo('cl_paquetes_sky','id_paquete_sky','nombre_paquete_sky',$datosL[$j],'','');
						$id_paquete_sky = count($arr_id_paquete_sky) > 0 ? $arr_id_paquete_sky[0][0] : '';
					} elseif($datosLEncabezado[$j] == 'Tecnico'){
						$arr_tecnico=devuelveCampoApartirOtroCampo('ad_clientes','id_cliente,id_tipo_cliente_proveedor,id_cliente_padre,id_sucursal','nit',$datosL[$j],'','');
						
						if(count($arr_tecnico) > 0){
							$id_cliente_tecnico = $arr_tecnico[0][0];
							
							$tipo_cliente = $arr_tecnico[0][1];
							if($tipo_cliente == '5'){
								$id_cliente = $arr_tecnico[0][2];
							} elseif($tipo_cliente == '3') {
								$id_cliente = $arr_tecnico[0][0];
							}
							
							$id_sucursal=$arr_tecnico[0][3];
						} else {
							$arr_Entidades_Financieras=devuelveCampoApartirOtroCampo('ad_entidades_financieras','sys_usuarios.id_sucursal,ad_entidades_financieras.id_entidad_financiera','ad_entidades_financieras.nit',$datosL[$j],'sys_usuarios','ad_entidades_financieras.id_entidad_financiera,sys_usuarios.id_empleado_relacionado');
							if(count($arr_Entidades_Financieras) > 0){
								$id_sucursal=$arr_Entidades_Financieras[0][0];
								$id_entidad_financiera_tecnico=$arr_Entidades_Financieras[0][1];
							}
						}
					} elseif($datosLEncabezado[$j] == 'Special Messaje 3'){
						$clave = $datosL[$j];
					} elseif($datosLEncabezado[$j] == 'Precio Suscripcion'){
						$precio_suscripcion = $datosL[$j];
					}
				}
				
				$arr_id_producto_servicio_facturar=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46",$clave,"","");
				$id_producto_servicio_facturar=$arr_id_producto_servicio_facturar[0][0];
				
				$queryRevisaSiSeRepiten='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
											cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
											cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
											cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
											cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
											cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
											cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar,
											cl_control_contratos_detalles.monto_pagar,cl_control_contratos_detalles.monto_cobrar,
											cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
											cl_control_contratos_detalles.activo
										FROM cl_control_contratos 
										LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato
										WHERE cl_control_contratos.contrato="'.$datosL[11].'" AND cl_control_contratos.cuenta="'.$datosL[1].'" AND cl_control_contratos.fecha_activacion="'.formatoFechaYMD($datosL[0]).'" AND 
										cl_control_contratos.id_carga="'.$idImportacion.'" AND cl_control_contratos.activo="1" AND ultimo_movimiento="1"';
				$resultRevisaSiSeRepiten=mysql_query($queryRevisaSiSeRepiten);
				$numRevisaSiSeRepiten=mysql_num_rows($resultRevisaSiSeRepiten);
				
				if($numRevisaSiSeRepiten > 0){
					$datosLRevisaSiSeRepiten=mysql_fetch_array($resultRevisaSiSeRepiten);
					$id_control_contrato=$datosLRevisaSiSeRepiten['id_control_contrato'];
					
					$queryCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,
								id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
								id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_carga,
								precio_suscripcion,activo,ultimo_movimiento,id_usuario_alta) VALUES ("'.$datosLRevisaSiSeRepiten["id_control_contrato"].'","'.$datosLRevisaSiSeRepiten["id_accion_contrato"].'",
								"'. date("Y-m-d H:i:s").'","'.$datosLRevisaSiSeRepiten["id_tipo_activacion"].'","'.$id_control_serie.'","'.$IRD.'",
								"'.$datosLRevisaSiSeRepiten["id_paquete_sky"].'","'.$datosLRevisaSiSeRepiten["id_sucursal"].'",
								"'.$datosLRevisaSiSeRepiten["id_cliente"].'","'.$datosLRevisaSiSeRepiten["id_cliente_tecnico"].'",
								"'.$datosLRevisaSiSeRepiten["id_entidad_financiera_tecnico"].'","'.$id_funcionalidad.'","'.$datosLRevisaSiSeRepiten["clave"].'",
								"'.$datosLRevisaSiSeRepiten["id_detalle_caja_comisiones"].'","'.$datosLRevisaSiSeRepiten["id_producto_servicio_facturar"].'",
								"'.$monto_pagar.'","'.$monto_cobrar.'","'.$datosLRevisaSiSeRepiten["id_carga"].'",
								"'.$datosLRevisaSiSeRepiten["precio_suscripcion"].'","1","1","'.$_SESSION["USR"]->userid.'")';
					
				} else {
					$id_funcionalidad=obtenerFuncionalidad($idImportacion,$datosL[11],$datosL[1],$datosL[0],$datosL[10],'activacion');
					
					if($id_funcionalidad == ''){
						//echo $idImportacion.' -- '.$datosL[11].' -- '.$datosL[1].' -- '.$datosL[0].' -- '.$datosL[10].' -- '.'activacion'.'<br>';
						array_push($arrErrores, array($fila,"N/A","No cuenta con una funcionalidad."));
					}
					
					//***   Obtiene caja de comisiones   ***//
					$arr_valida_uno=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46",$clave,"","");
					if(count($arr_valida_uno) > 1){ //***   Special Messaje 3   ***//
						$arr_id_paquete=devuelveCampoApartirOtroCampo("cl_paquetes_sky","id_paquete_sky","nombre_paquete_sky",$datosL[6],"","");
						$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5",$clave.",".$arr_id_paquete[0][0],"","");
						
						//$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,t50",$clave.",".$datosL[6],"","");
						
						if(count($arr_valida_dos) > 1){ //***   Paquete Principal   ***//
							$arr_valida_tres=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,n4",$clave.",".$arr_id_paquete[0][0].",".$datosL[16],"","");
							
							if(count($arr_valida_tres) > 1){ //***   Num Equipos   ***//
								$arr_valida_cuatro=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,n4,n6",$clave.",".$arr_id_paquete[0][0].",".$datosL[16].",".$id_funcionalidad,"","");
								
								if(count($arr_valida_cuatro) == 1){ //***   funcionalidad   ***//
									$id_detalle_caja_comisiones = $arr_valida_cuatro[0][0];
								}
							} elseif(count($arr_valida_tres) == 1){
								$id_detalle_caja_comisiones = $arr_valida_tres[0][0];
							}
						} elseif(count($arr_valida_dos) == 1){
							$id_detalle_caja_comisiones = $arr_valida_dos[0][0];
						}
					} elseif(count($arr_valida_uno) == 1){
						$id_detalle_caja_comisiones = $arr_valida_uno[0][0];
					}
					//***   Termina Obtiene caja de comisiones   ***//
					
					if($id_detalle_caja_comisiones == ''){
						array_push($arrErrores, array($fila,"N/A","No cuenta con una caja de comisiones."));
					}
					
					$queryCC='INSERT INTO cl_control_contratos (contrato,cuenta,fecha_activacion,id_tipo_activacion,id_carga,id_usuario_alta,activo)
						VALUES ("'.$datosL[11].'","'.$datosL[1].'","'. formatoFechaYMD($datosL[0]).'","'.$id_tipo_activacion.'","'.$idImportacion.'","'.$_SESSION["USR"]->userid.'","1")';
					$resultCC=mysql_query($queryCC);
					$id_control_contrato=mysql_insert_id();
					
					$queryCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,
								id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
								id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_carga,
								precio_suscripcion,activo,ultimo_movimiento,id_usuario_alta,principal) VALUES ("'.$id_control_contrato.'","1",
								"'. date("Y-m-d").'","'.$id_tipo_activacion.'","'.$id_control_serie.'","'.$IRD.'","'.$id_paquete_sky.'","'.$id_sucursal.'",
								"'.$id_cliente.'","'.$id_cliente_tecnico.'","'.$id_entidad_financiera_tecnico.'","'.$id_funcionalidad.'","'.$clave.'",
								"'.$id_detalle_caja_comisiones.'","'.$id_producto_servicio_facturar.'","'.$monto_pagar.'","'.$monto_cobrar.'",
								"'.$idImportacion.'","'.$precio_suscripcion.'","1","1","'.$_SESSION["USR"]->userid.'","1")';
				}
				$resultCC=mysql_query($queryCCD);
				
				$fila++;
			}
			
			fclose($gestorL);
		}
		
		if(count($arrErrores) > 0){
			$queryElimina='DELETE FROM cl_control_contratos_detalles WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
			
			$queryElimina='DELETE FROM cl_control_contratos WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
			
			$queryElimina='DELETE FROM cl_importacion_activaciones WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
			
			$queryElimina='DELETE FROM cl_cargas WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
		}
	} elseif($idLayout == 8){ /***   Calificaciones   ***/
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			$totalCamposCSV=count($datosLEncabezado);
			$id_calificacion='';
			$id_accion_contrato='';
			
			$fila=2;
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				for($j=0; $j < $totalCamposCSV; $j++){
					if($datosLEncabezado[$j] == 'Status Contrato'){
						if($datosL[$j] == 'Reagendado por Nova'){
							$id_calificacion = "2";
							$id_accion_contrato='22';
						}
					} elseif($datosLEncabezado[$j] == 'Subestatus Contrato'){
						if($datosL[$j] == 'VN Liberado 0-45' || $datosL[$j] == 'VN Liberado 46-60'){
							$id_calificacion = "1";
							$id_accion_contrato='21';
						} elseif($datosL[$j] == 'VN Rechazo Definitivo' || $datosL[$j] == 'VN Liberado Parcial'){
							$id_calificacion = "3";
							$id_accion_contrato='23';
						}
					}
				}
				
				$query='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
							cl_control_contratos_detalles.id_contra_recibo,
							cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
							cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
							cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
							cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
							cl_control_contratos_detalles.id_entidad_financiera_vendedor,
							cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
							cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar,
							cl_control_contratos_detalles.monto_pagar,cl_control_contratos_detalles.monto_cobrar,
							cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
							cl_control_contratos_detalles.activo,cl_control_contratos_detalles.principal
						FROM cl_control_contratos 
						LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato
						WHERE cl_control_contratos.contrato="'.$datosL[1].'" AND cl_control_contratos.cuenta="'.$datosL[3].'" AND 
							cl_control_contratos.fecha_activacion="'.formatoFechaYMD($datosL[10]).'" AND ultimo_movimiento="1" AND
							cl_control_contratos_detalles.activo = 1 AND cl_control_contratos_detalles.principal = 1';
				
				$result=mysql_query($query);
				$datos=mysql_fetch_array($result);
				
				if($datos["id_accion_contrato"] == '11'){
					$queryInsert='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,
									id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
									id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,
									monto_cobrar,id_calificacion,id_carga,precio_suscripcion,activo,ultimo_movimiento,id_usuario_alta,principal) VALUES ("'.$datos["id_control_contrato"].'",
									"'.$id_accion_contrato.'","'.$datos["id_contra_recibo"].'","'. date("Y-m-d H:i:s").'",
									"'.$datos["id_tipo_activacion"].'","'.$datos["id_control_serie"].'","'.$datos["numero_serie"].'","'.$datos["id_paquete_sky"].'",
									"'.$datos["id_sucursal"].'","'.$datos["id_cliente"].'","'.$datos["id_cliente_tecnico"].'",
									"'.$datos["id_entidad_financiera_tecnico"].'","'.$datos["id_entidad_financiera_vendedor"].'","'.$datos["id_funcionalidad"].'",
									"'.$datos["clave"].'","'.$datos["id_detalle_caja_comisiones"].'","'.$datos["id_producto_servicio_facturar"].'",
									"'.$datos["monto_pagar"].'","'.$datos["monto_cobrar"].'","'.$id_calificacion.'","'.$idImportacion.'","'.$datos["precio_suscripcion"].'",
									"'.$datos["activo"].'","1","'.$_SESSION["USR"]->userid.'","'.$datos["principal"].'")';
					
					$resultInsert=mysql_query($queryInsert);
					
					$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$datos["idDetalle"].'"';
					$resultUpdate=mysql_query($sqlUpdate);
				} else {
					array_push($arrErrores, array($fila,"N/A","La calificacion no fue registrada debido a que no cuenta con un contrarecibo o ya fue registrado previamente."));
				}
				$fila++;
			}
			
			fclose($gestorL);
		}
	} elseif($idLayout == 7){ /***   Crecimientos   ***/
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			$totalCamposCSV=count($datosLEncabezado);
			
			$fila=2;
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				$id_tipo_activacion='';
				$IRD='';
				$id_control_serie='';
				$id_paquete_sky='';
				
				$id_cliente_tecnico='';
				$id_sucursal='';
				$id_cliente='';
				$id_entidad_financiera_tecnico='';
				$id_funcionalidad='';
				$clave='';
				$id_detalle_caja_comisiones='';
				$id_producto_servicio_facturar='';
				$monto_pagar=0;
				$monto_cobrar=0;
				$precio_suscripcion='';
				
				for($j=0; $j < $totalCamposCSV; $j++){
					if($datosLEncabezado[$j] == 'Tipo de Instalacion'){
						$arr_id_tipo_activacion=devuelveCampoApartirOtroCampo('cl_tipos_activacion','id_tipo_activacion','letra',$datosL[$j],'','');
						$id_tipo_activacion = count($arr_id_tipo_activacion) > 0 ? $arr_id_tipo_activacion[0][0] : '';
					} elseif($datosLEncabezado[$j] == 'IRD'){
						$IRD=$datosL[$j];
						
						$arr_id_control_serie=devuelveCampoApartirOtroCampo('cl_control_series','id_control_serie','numero_serie',$datosL[$j],'','');
						$id_control_serie = count($arr_id_control_serie) > 0 ? $arr_id_control_serie[0][0] : '';
						
						//$arr_id_funcionalidad=devuelveCampoApartirOtroCampo('cl_tipos_equipo','id_funcionalidad','inicio_serie', substr($datosL[$j],0,6),'','');
						//$id_funcionalidad=$arr_id_funcionalidad[0][0];
						
					} elseif($datosLEncabezado[$j] == 'Paquete Principal'){
						$arr_id_paquete_sky=devuelveCampoApartirOtroCampo('cl_paquetes_sky','id_paquete_sky','nombre_paquete_sky',$datosL[$j],'','');
						$id_paquete_sky = count($arr_id_paquete_sky) > 0 ? $arr_id_paquete_sky[0][0] : '';
					} elseif($datosLEncabezado[$j] == 'Tecnico'){
						$arr_tecnico=devuelveCampoApartirOtroCampo('ad_clientes','id_cliente,id_tipo_cliente_proveedor,id_cliente_padre,id_sucursal','nit',$datosL[$j],'','');
						
						if(count($arr_tecnico) > 0){
							$id_cliente_tecnico = $arr_tecnico[0][0];
							
							$tipo_cliente = $arr_tecnico[0][1];
							if($tipo_cliente == '5'){
								$id_cliente = $arr_tecnico[0][2];
							} elseif($tipo_cliente == '3') {
								$id_cliente = $arr_tecnico[0][0];
							}
							
							$id_sucursal=$arr_tecnico[0][3];
						} else {
							$arr_Entidades_Financieras=devuelveCampoApartirOtroCampo('ad_entidades_financieras','sys_usuarios.id_sucursal,ad_entidades_financieras.id_entidad_financiera_tecnico','ad_entidades_financieras.nit',$datosL[$j],'sys_usuarios','ad_entidades_financieras.id_entidad_financiera,sys_usuarios.id_empleado_relacionado');
							if(count($arr_Entidades_Financieras) > 0){
								$id_sucursal=$arr_Entidades_Financieras[0][0];
								$id_entidad_financiera_tecnico=$arr_Entidades_Financieras[0][1];
							}
						}
					} elseif($datosLEncabezado[$j] == 'Special Messaje 3'){
						$clave = $datosL[$j];
					}
				}
				
				$arr_id_producto_servicio_facturar=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46",$clave,"","");
				$id_producto_servicio_facturar=$arr_id_producto_servicio_facturar[0][0];
				
				$queryRevisaSiSeRepiten='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
											cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
											cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
											cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
											cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
											cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
											cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar,
											cl_control_contratos_detalles.monto_pagar,cl_control_contratos_detalles.monto_cobrar,
											cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
											cl_control_contratos_detalles.activo
										FROM cl_control_contratos 
										LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato
										WHERE cl_control_contratos.contrato="'.$datosL[10].'" AND cl_control_contratos.cuenta="'.$datosL[0].'" AND cl_control_contratos.fecha_activacion="'.formatoFechaYMD($datosL[2]).'" AND 
										cl_control_contratos.id_carga="'.$idImportacion.'" AND cl_control_contratos.activo="1" AND ultimo_movimiento="1"';
				$resultRevisaSiSeRepiten=mysql_query($queryRevisaSiSeRepiten);
				$numRevisaSiSeRepiten=mysql_num_rows($resultRevisaSiSeRepiten);
				
				if($numRevisaSiSeRepiten > 0){
					$datosLRevisaSiSeRepiten=mysql_fetch_array($resultRevisaSiSeRepiten);
					$id_control_contrato=$datosLRevisaSiSeRepiten['id_control_contrato'];
					
					$queryCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,
								id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
								id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_carga,
								precio_suscripcion,activo,ultimo_movimiento,id_usuario_alta) VALUES ("'.$datosLRevisaSiSeRepiten["id_control_contrato"].'","'.$datosLRevisaSiSeRepiten["id_accion_contrato"].'",
								"'. date("Y-m-d H:i:s").'","'.$datosLRevisaSiSeRepiten["id_tipo_activacion"].'","'.$id_control_serie.'","'.$IRD.'",
								"'.$datosLRevisaSiSeRepiten["id_paquete_sky"].'","'.$datosLRevisaSiSeRepiten["id_sucursal"].'",
								"'.$datosLRevisaSiSeRepiten["id_cliente"].'","'.$datosLRevisaSiSeRepiten["id_cliente_tecnico"].'",
								"'.$datosLRevisaSiSeRepiten["id_entidad_financiera_tecnico"].'","'.$id_funcionalidad.'","'.$datosLRevisaSiSeRepiten["clave"].'",
								"'.$datosLRevisaSiSeRepiten["id_detalle_caja_comisiones"].'","'.$datosLRevisaSiSeRepiten["id_producto_servicio_facturar"].'",
								"'.$monto_pagar.'","'.$monto_cobrar.'","'.$datosLRevisaSiSeRepiten["id_carga"].'",
								"'.$datosLRevisaSiSeRepiten["precio_suscripcion"].'","1","1","'.$_SESSION["USR"]->userid.'")';
					
				} else {
					$id_funcionalidad=obtenerFuncionalidad($idImportacion,$datosL[10],$datosL[0],$datosL[2],$datosL[9],'crecimiento');
					
					if($id_funcionalidad == ''){
						array_push($arrErrores, array($fila,"N/A","No cuenta con una funcionalidad."));
					}
					
					//***   Obtiene caja de comisiones   ***//
					$arr_valida_uno=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46",$clave,"","");
					if(count($arr_valida_uno) > 1){ //***   Special Messaje 3   ***//
						$arr_id_paquete=devuelveCampoApartirOtroCampo("cl_paquetes_sky","id_paquete_sky","nombre_paquete_sky",$datosL[5],"","");
						$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5",$clave.",".$arr_id_paquete[0][0],"","");
						
						//$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,t50",$clave.",".$datosL[5],"","");
						
						if(count($arr_valida_dos) > 1){ //***   Paquete Principal   ***//
							$arr_valida_tres=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,n4",$clave.",".$arr_id_paquete[0][0].",".$datosL[13],"","");
							
							if(count($arr_valida_tres) > 1){ //***   Numero de Equipos   ***//
								$arr_valida_cuatro=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,n4,n6",$clave.",".$arr_id_paquete[0][0].",".$datosL[13].",".$id_funcionalidad,"","");
								
								if(count($arr_valida_cuatro) == 1){ //***   Funcionalidad   ***//
									$id_detalle_caja_comisiones = $arr_valida_cuatro[0][0];
								}
							}  elseif(count($arr_valida_tres) == 1){
								$id_detalle_caja_comisiones = $arr_valida_tres[0][0];
							}
						} elseif(count($arr_valida_dos) == 1){
							$id_detalle_caja_comisiones = $arr_valida_dos[0][0];
						}
					} elseif(count($arr_valida_uno) == 1){
						$id_detalle_caja_comisiones = $arr_valida_uno[0][0];
					}
					//***   Termina Obtiene caja de comisiones   ***//
					
					if($id_detalle_caja_comisiones == ''){
						array_push($arrErrores, array($fila,"N/A","No cuenta con una caja de comisiones."));
					}
					
					$queryCC='INSERT INTO cl_control_contratos (contrato,cuenta,fecha_activacion,id_tipo_activacion,id_carga,id_usuario_alta,activo)
						VALUES ("'.$datosL[10].'","'.$datosL[0].'","'. formatoFechaYMD($datosL[2]).'","'.$id_tipo_activacion.'","'.$idImportacion.'","'.$_SESSION["USR"]->userid.'","1")';
					$resultCC=mysql_query($queryCC);
					$id_control_contrato=mysql_insert_id();
					
					$queryCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,
								id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
								id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_carga,
								precio_suscripcion,activo,ultimo_movimiento,id_usuario_alta,principal) VALUES ("'.$id_control_contrato.'","100","'. date("Y-m-d H:i:s").'","'.$id_tipo_activacion.'",
								"'.$id_control_serie.'","'.$IRD.'","'.$id_paquete_sky.'","'.$id_sucursal.'","'.$id_cliente.'","'.$id_cliente_tecnico.'",
								"'.$id_entidad_financiera_tecnico.'","'.$id_funcionalidad.'","'.$clave.'","'.$id_detalle_caja_comisiones.'",
								"'.$id_producto_servicio_facturar.'","'.$monto_pagar.'","'.$monto_cobrar.'","'.$idImportacion.'","'.$precio_suscripcion.'","1","1",
								"'.$_SESSION["USR"]->userid.'","1")';
				}
				$resultCC=mysql_query($queryCCD);
				
				$fila++;
			}
			
			fclose($gestorL);
		}
		
		if(count($arrErrores) > 0){
			$queryElimina='DELETE FROM cl_control_contratos_detalles WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
			
			$queryElimina='DELETE FROM cl_control_contratos WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
			
			$queryElimina='DELETE FROM cl_importacion_activaciones WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
			
			$queryElimina='DELETE FROM cl_cargas WHERE id_carga = "'.$idImportacion.'"';
			$resultElimina=mysql_query($queryElimina);
		}
	} elseif($idLayout == 17){	/***  Remesas  **/	
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			$totalCamposCSV=count($datosLEncabezado);
			$id_calificacion='';
			
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				
				$query='SELECT cl_control_contratos_detalles.id_detalle 
						AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
						 cl_control_contratos_detalles.id_contra_recibo,cl_control_contratos_detalles.fecha_movimiento,
						cl_control_contratos_detalles.id_tipo_activacion, cl_control_contratos_detalles.id_control_serie,
						cl_control_contratos_detalles.numero_serie, cl_control_contratos_detalles.id_paquete_sky,
						cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente, 
						cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico, 
						cl_control_contratos_detalles.id_entidad_financiera_vendedor,cl_control_contratos_detalles.id_funcionalidad,
						cl_control_contratos_detalles.clave, cl_control_contratos_detalles.id_detalle_caja_comisiones,
						cl_control_contratos_detalles.id_producto_servicio_facturar,cl_control_contratos_detalles.id_carga,
						cl_control_contratos_detalles.precio_suscripcion, cl_control_contratos_detalles.activo,cl_control_contratos_detalles.principal
						FROM cl_control_contratos LEFT JOIN cl_control_contratos_detalles 
						ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato 
						WHERE cl_control_contratos.contrato="'.$datosL[3].'" AND cl_control_contratos.cuenta="'.$datosL[5].'" AND 
							cl_control_contratos.fecha_activacion="'.formatoFechaYMD($datosL[7]).'" AND ultimo_movimiento="1" AND(id_accion_contrato="1" OR id_accion_contrato="11")';
				
				$result=mysql_query($query);
				$datos=mysql_fetch_array($result);
				if(count($datos)>0){
					if($datos["id_accion_contrato"=='1']){
						$queryInsert="INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,	monto_cobrar,id_calificacion,id_carga,precio_suscripcion,activo,ultimo_movimiento) VALUES ('".$datos["id_control_contrato"]."','111','','". date("Y-m-d").",'".$datos["id_tipo_activacion"]."','".$datos["id_control_serie"]."','".$dtaosL[6]."','".$datos["id_paquete_sky"]."','".$datos["id_sucursal"]."','".$datos["id_cliente"]."','".$datos["id_cliente_tecnico"]."','".$datos["id_entidad_financiera_tecnico"]."','".$datos["id_entidad_financiera_vendedor"]."','".$datos["id_funcionalidad"]."','".$datos["clave"]."','".$datos["id_detalle_caja_comisiones"]."','".$datos["id_producto_servicio_facturar"]."','".$id_calificacion."','".$datos["id_carga"]."','".$datos["precio_suscripcion"]."','".$datos["activo"]."','1')";
						$resultInsert=mysql_query($queryInsert);
						
						$sqlUpdate="UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle='".$datos["idDetalle"]."'";
						$resultUpdate=mysql_query($sqlUpdate);
					}
					else if($datos["id_accion_contrato"=='11']){
					$queryInsert='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,id_contra_recibo,fecha_movimiento,id_tipo_activacion,
								id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
								id_entidad_financiera_vendedor,id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,
								monto_cobrar,id_calificacion,id_carga,precio_suscripcion,activo,ultimo_movimiento,principal) VALUES ("'.$datos["id_control_contrato"].'","111",'.$datos["id_contra_recibo"].',
								"'. date("Y-m-d").'",
								"'.$datos["id_tipo_activacion"].'","'.$datos["id_control_serie"].'","'.$dtaosL[5].'","'.$datos["id_paquete_sky"].'",
								"'.$datos["id_sucursal"].'","'.$datos["id_cliente"].'","'.$datos["id_cliente_tecnico"].'",
								"'.$datos["id_entidad_financiera_tecnico"].'","'.$datos["id_entidad_financiera_vendedor"].'","'.$datos["id_funcionalidad"].'",
								"'.$datos["clave"].'","'.$datos["id_detalle_caja_comisiones"].'","'.$datos["id_producto_servicio_facturar"].',"'.$id_calificacion.'","'.$datos["id_carga"].'","'.$datos["precio_suscripcion"].'",
								"'.$datos["activo"].'","1","'.$datos["principal"].'")';
				
								$resultInsert=mysql_query($queryInsert);
								
								$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$datos["idDetalle"].'"';
								$resultUpdate=mysql_query($sqlUpdate);
					}
				}
			}
			
			fclose($gestorL);
		}
	} elseif($idLayout == 19){ /***   Derechos de Activacion   ***/
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			$totalCamposCSV=count($datosLEncabezado);
			
			$id_control_cuenta_por_pagar_operadora='';
			
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				for($j=0; $j < $totalCamposCSV; $j++){
					if($datosLEncabezado[$j] == 'Factura'){
						$arr_id_control_cuenta_por_pagar_operadora=devuelveCampoApartirOtroCampo('ad_cuentas_por_pagar_operadora','id_cuenta_por_pagar','numero_documento_2',$datosL[$j],'','');
						$id_control_cuenta_por_pagar_operadora = count($arr_id_control_cuenta_por_pagar_operadora) > 0 ? $arr_id_control_cuenta_por_pagar_operadora[0][0] : '';
					}
				}
				
				$queryRevisaSiSeRepiten='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
											cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
											cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
											cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
											cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
											cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
											cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar,
											cl_control_contratos_detalles.monto_pagar,cl_control_contratos_detalles.monto_cobrar,
											cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
											cl_control_contratos_detalles.activo,cl_control_contratos_detalles.principal
										FROM cl_control_contratos 
										LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato
										WHERE cl_control_contratos.cuenta="'.$datosL[2].'" AND cl_control_contratos.activo="1" AND 
										cl_control_contratos_detalles.numero_serie="'.$datosL[3].'" AND cl_control_contratos_detalles.id_accion_contrato = 1
										ORDER BY cl_control_contratos_detalles.fecha_movimiento DESC';
				$resultRevisaSiSeRepiten=mysql_query($queryRevisaSiSeRepiten);
				$numRevisaSiSeRepiten=mysql_num_rows($resultRevisaSiSeRepiten);
				
				if($numRevisaSiSeRepiten > 0){
					$datosLRevisaSiSeRepiten=mysql_fetch_array($resultRevisaSiSeRepiten);
					$id_control_contrato=$datosLRevisaSiSeRepiten['id_control_contrato'];
					
					$queryCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,
								id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
								id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_carga,
								precio_suscripcion,activo,ultimo_movimiento,id_control_cuenta_por_pagar_operadora,id_usuario_alta,principal) VALUES ("'.$datosLRevisaSiSeRepiten["id_control_contrato"].'","5000",
								"'. date("Y-m-d H:i:s").'","'.$datosLRevisaSiSeRepiten["id_tipo_activacion"].'","'.$datosLRevisaSiSeRepiten['id_control_serie'].'","'.$datosLRevisaSiSeRepiten['numero_serie'].'",
								"'.$datosLRevisaSiSeRepiten["id_paquete_sky"].'","'.$datosLRevisaSiSeRepiten["id_sucursal"].'",
								"'.$datosLRevisaSiSeRepiten["id_cliente"].'","'.$datosLRevisaSiSeRepiten["id_cliente_tecnico"].'",
								"'.$datosLRevisaSiSeRepiten["id_entidad_financiera_tecnico"].'","'.$datosLRevisaSiSeRepiten['id_funcionalidad'].'","'.$datosLRevisaSiSeRepiten["clave"].'",
								"'.$datosLRevisaSiSeRepiten["id_detalle_caja_comisiones"].'","'.$datosLRevisaSiSeRepiten["id_producto_servicio_facturar"].'",
								"'.$datosLRevisaSiSeRepiten['monto_pagar'].'","'.$datosLRevisaSiSeRepiten['monto_cobrar'].'","'.$idImportacion.'",
								"'.$datosLRevisaSiSeRepiten["precio_suscripcion"].'","1","0","'.$id_control_cuenta_por_pagar_operadora.'","'.$_SESSION["USR"]->userid.'","'.$datosLRevisaSiSeRepiten["principal"].'")';
					
					$resultCC=mysql_query($queryCCD);
					
					$arr_facturas_repetidas=devuelveCampoApartirOtroCampo('cl_control_contratos','cl_control_contratos_detalles.id_detalle','cl_control_contratos.cuenta,cl_control_contratos_detalles.numero_serie,cl_control_contratos_detalles.activo,cl_control_contratos_detalles.id_accion_contrato',$datosL[2].','.$datosL[3].',1,5000','cl_control_contratos_detalles','cl_control_contratos.id_control_contrato,cl_control_contratos_detalles.id_control_contrato');
					$tot=count($arr_id_control_cuenta_por_pagar_operadora);
					if($tot > 0){
						for($t=0; $t<$tot; $t++){
							$sqlUpdate='UPDATE cl_control_contratos_detalles SET activo=0, fecha_cancelacion="'.date('Y-m-d H:i:s').'", id_usuario_cancelo="'.$_SESSION["USR"]->userid.'" WHERE id_detalle="'.$arr_facturas_repetidas[$t][0].'"';
							$resultUpdate=mysql_query($sqlUpdate);
						}
					}
				}
			}
			
			fclose($gestorL);
		}
	} elseif($idLayout == 11){ /***   Penalizaciones   ***/
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			
			$totalCamposCSV=count($datosLEncabezado);
			
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				$contrato=$datosL[5];
				$cuenta=$datosL[6];
				$fecha_activacion=$datosL[7];
				
				$arr_id_producto_servicio_facturar_audicel=devuelveCampoApartirOtroCampo('cl_productos_servicios','id_producto_servicio','clave',$datosL[0],'','');
				$id_producto_servicio_facturar_audicel = count($arr_id_producto_servicio_facturar_audicel) > 0 ? $arr_id_producto_servicio_facturar_audicel[0][0] : '';
				
				$arr_id_control_serie=devuelveCampoApartirOtroCampo('cl_control_series','id_control_serie','numero_serie',$datosL[8],'','');
				$id_control_serie = count($arr_id_control_serie) > 0 ? $arr_id_control_serie[0][0] : '';
				
				$arr_id_cxp=devuelveCampoApartirOtroCampo('ad_cuentas_por_pagar_operadora','id_cuenta_por_pagar','numero_documento_2',$datosL[2],'','');
				$id_cxp = count($arr_id_cxp) > 0 ? $arr_id_cxp[0][0] : '';
				
				$arr_id_cliente=devuelveCampoApartirOtroCampo('ad_clientes','id_cliente','clave',$datosL[3],'','');
				$id_cliente = count($arr_id_cliente) > 0 ? $arr_id_cliente[0][0] : '';
				
				$arr_id_sucursal=devuelveCampoApartirOtroCampo('ad_clientes','id_sucursal','clave',$datosL[3],'','');
				$id_sucursal = count($arr_id_sucursal) > 0 ? $arr_id_sucursal[0][0] : '';
				
				if($contrato != '' && $cuenta != '' && $fecha_activacion != ''){
					$queryRevisaSiSeRepiten='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
												cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
												cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
												cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
												cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
												cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
												cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar,
												cl_control_contratos_detalles.monto_pagar,cl_control_contratos_detalles.monto_cobrar,
												cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
												cl_control_contratos_detalles.activo,cl_control_contratos_detalles.principal
											FROM cl_control_contratos 
											LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato
											WHERE cl_control_contratos.contrato="'.$datosL[5].'" AND cl_control_contratos.cuenta="'.$datosL[6].'" AND 
											cl_control_contratos.fecha_activacion="'.formatoFechaYMD($datosL[7]).'" AND ultimo_movimiento="1"';
					$resultRevisaSiSeRepiten=mysql_query($queryRevisaSiSeRepiten);
					$numRevisaSiSeRepiten=mysql_num_rows($resultRevisaSiSeRepiten);
					
					if($numRevisaSiSeRepiten > 0){
						$datosLRevisaSiSeRepiten=mysql_fetch_array($resultRevisaSiSeRepiten);
						$id_control_contrato=$datosLRevisaSiSeRepiten['id_control_contrato'];
						
						$queryCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,
									id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
									id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_carga,
									precio_suscripcion,activo,ultimo_movimiento,id_control_cuenta_por_pagar_operadora,id_producto_servicio_facturar_audicel,
									id_control_factura_audicel,monto_penalizacion,id_usuario_alta,principal) VALUES 
									("'.$datosLRevisaSiSeRepiten["id_control_contrato"].'","50",
									"'. date("Y-m-d H:i:s").'","'.$datosLRevisaSiSeRepiten["id_tipo_activacion"].'","'.$id_control_serie.'","'.$datosLRevisaSiSeRepiten['numero_serie'].'",
									"'.$datosLRevisaSiSeRepiten["id_paquete_sky"].'","'.$datosLRevisaSiSeRepiten["id_sucursal"].'",
									"'.$datosLRevisaSiSeRepiten["id_cliente"].'","'.$datosLRevisaSiSeRepiten["id_cliente_tecnico"].'",
									"'.$datosLRevisaSiSeRepiten["id_entidad_financiera_tecnico"].'","'.$datosLRevisaSiSeRepiten['id_funcionalidad'].'","'.$datosLRevisaSiSeRepiten["clave"].'",
									"'.$datosLRevisaSiSeRepiten["id_detalle_caja_comisiones"].'","'.$datosLRevisaSiSeRepiten["id_producto_servicio_facturar"].'",
									"'.$datosLRevisaSiSeRepiten['monto_pagar'].'","'.$datosLRevisaSiSeRepiten['monto_cobrar'].'","'.$idImportacion.'",
									"'.$datosLRevisaSiSeRepiten["precio_suscripcion"].'","1","2","'.$datosLRevisaSiSeRepiten['id_control_cuenta_por_pagar_operadora'].'",
									"'.$id_producto_servicio_facturar_audicel.'","'.$id_cxp.'","'.$datosL[4].'","'.$_SESSION["USR"]->userid.'","'.$datosLRevisaSiSeRepiten["principal"].'")';
						
						$resultCC=mysql_query($queryCCD);
						
						/*$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$datosLRevisaSiSeRepiten["idDetalle"].'"';
						$resultUpdate=mysql_query($sqlUpdate);*/
					}
				} else {
					$queryIP='INSERT INTO cl_control_penalizaciones (id_producto_servicio,id_cliente,IRD,contrato,cuenta,fecha_activacion,id_cuenta_por_pagar,id_carga,activo) VALUES 
								("'.$id_producto_servicio_facturar_audicel.'","'.$id_cliente.'","'.$datosL[8].'","'.$datosL[5].'","'.$datosL[6].'",
								"'.formatoFechaYMD($datosL[7]).'","'.$id_cxp.'","'.$idImportacion.'","1")';
					$resultIP=mysql_query($queryIP);
					$id_control_penalizacion=mysql_insert_id();
					
					$queryIPDetalle='INSERT INTO cl_control_penalizaciones_detalle (id_control_penalizacion,id_accion,fecha_movimiento,hora,id_usuario_agrego,
									id_control_serie,id_sucursal,id_cliente,id_producto_servicio,monto_penalizacion,activo,
									ultimo_movimiento) VALUES ("'.$id_control_penalizacion.'","50","'. date('Y-m-d').'","'. date('H:i:s').'",
									"'.$_SESSION["USR"]->userid.'","'.$id_control_serie.'","'.$id_sucursal.'","'.$id_cliente.'",
									"'.$id_producto_servicio_facturar_audicel.'","'.$datosL[4].'","1","2")';
					$resultIPDetalle=mysql_query($queryIPDetalle);
					
				}
			}
			
			fclose($gestorL);
		}
	} elseif($idLayout == 13){ /***   Bonos   ***/
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			
			$totalCamposCSV=count($datosLEncabezado);
			
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				$contrato=$datosL[5];
				$cuenta=$datosL[4];
				
				$arr_id_producto_servicio_facturar_audicel=devuelveCampoApartirOtroCampo('cl_productos_servicios','id_producto_servicio','clave',$datosL[7],'','');
				$id_producto_servicio_facturar_audicel = count($arr_id_producto_servicio_facturar_audicel) > 0 ? $arr_id_producto_servicio_facturar_audicel[0][0] : '';
				
				$arr_id_cliente=devuelveCampoApartirOtroCampo('ad_clientes','id_cliente','clave',$datosL[2],'','');
				$id_cliente = count($arr_id_cliente) > 0 ? $arr_id_cliente[0][0] : '';
				
				$arr_id_sucursal=devuelveCampoApartirOtroCampo('ad_clientes','id_sucursal','clave',$datosL[2],'','');
				$id_sucursal = count($arr_id_sucursal) > 0 ? $arr_id_sucursal[0][0] : '';
				
				if($contrato != '' && $cuenta != ''){
					$queryRevisaSiSeRepiten='SELECT cl_control_contratos_detalles.id_detalle AS idDetalle,cl_control_contratos_detalles.id_control_contrato,cl_control_contratos_detalles.id_accion_contrato,
												cl_control_contratos_detalles.fecha_movimiento,cl_control_contratos_detalles.id_tipo_activacion,
												cl_control_contratos_detalles.id_control_serie,cl_control_contratos_detalles.numero_serie,
												cl_control_contratos_detalles.id_paquete_sky,cl_control_contratos_detalles.id_sucursal,cl_control_contratos_detalles.id_cliente,
												cl_control_contratos_detalles.id_cliente_tecnico,cl_control_contratos_detalles.id_entidad_financiera_tecnico,
												cl_control_contratos_detalles.id_funcionalidad,cl_control_contratos_detalles.clave,
												cl_control_contratos_detalles.id_detalle_caja_comisiones,cl_control_contratos_detalles.id_producto_servicio_facturar,
												cl_control_contratos_detalles.monto_pagar,cl_control_contratos_detalles.monto_cobrar,
												cl_control_contratos_detalles.id_carga,cl_control_contratos_detalles.precio_suscripcion,
												cl_control_contratos_detalles.activo,cl_control_contratos_detalles.principal
											FROM cl_control_contratos 
											LEFT JOIN cl_control_contratos_detalles ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato
											WHERE cl_control_contratos.contrato="'.$datosL[5].'" AND cl_control_contratos.cuenta="'.$datosL[4].'" AND 
											ultimo_movimiento="1"';
					$resultRevisaSiSeRepiten=mysql_query($queryRevisaSiSeRepiten);
					$numRevisaSiSeRepiten=mysql_num_rows($resultRevisaSiSeRepiten);
					
					if($numRevisaSiSeRepiten > 0){
						$datosLRevisaSiSeRepiten=mysql_fetch_array($resultRevisaSiSeRepiten);
						$id_control_contrato=$datosLRevisaSiSeRepiten['id_control_contrato'];
						
						$queryCCD='INSERT INTO cl_control_contratos_detalles (id_control_contrato,id_accion_contrato,fecha_movimiento,id_tipo_activacion,
									id_control_serie,numero_serie,id_paquete_sky,id_sucursal,id_cliente,id_cliente_tecnico,id_entidad_financiera_tecnico,
									id_funcionalidad,clave,id_detalle_caja_comisiones,id_producto_servicio_facturar,monto_pagar,monto_cobrar,id_carga,
									precio_suscripcion,activo,ultimo_movimiento,id_control_cuenta_por_pagar_operadora,id_producto_servicio_facturar_audicel,
									id_control_factura_audicel,monto_bono,id_usuario_alta,principal) VALUES 
									("'.$datosLRevisaSiSeRepiten["id_control_contrato"].'","60",
									"'. date("Y-m-d H:i:s").'","'.$datosLRevisaSiSeRepiten["id_tipo_activacion"].'","'.$datosLRevisaSiSeRepiten["id_control_serie"].'",
									"'.$datosLRevisaSiSeRepiten['numero_serie'].'","'.$datosLRevisaSiSeRepiten["id_paquete_sky"].'",
									"'.$datosLRevisaSiSeRepiten["id_sucursal"].'","'.$datosLRevisaSiSeRepiten["id_cliente"].'","'.$datosLRevisaSiSeRepiten["id_cliente_tecnico"].'",
									"'.$datosLRevisaSiSeRepiten["id_entidad_financiera_tecnico"].'","'.$datosLRevisaSiSeRepiten['id_funcionalidad'].'","'.$datosLRevisaSiSeRepiten["clave"].'",
									"'.$datosLRevisaSiSeRepiten["id_detalle_caja_comisiones"].'","'.$datosLRevisaSiSeRepiten["id_producto_servicio_facturar"].'",
									"'.$datosLRevisaSiSeRepiten['monto_pagar'].'","'.$datosLRevisaSiSeRepiten['monto_cobrar'].'","'.$idImportacion.'",
									"'.$datosLRevisaSiSeRepiten["precio_suscripcion"].'","1","3","'.$datosLRevisaSiSeRepiten['id_control_cuenta_por_pagar_operadora'].'",
									"'.$id_producto_servicio_facturar_audicel.'","","'.$datosL[6].'","'.$_SESSION["USR"]->userid.'","'.$datosLRevisaSiSeRepiten["principal"].'")';
						
						$resultCC=mysql_query($queryCCD);
						
						/*  No se actualiza ya que es informativo
						$sqlUpdate='UPDATE cl_control_contratos_detalles SET ultimo_movimiento=0 WHERE id_detalle="'.$datosLRevisaSiSeRepiten["idDetalle"].'"';
						$resultUpdate=mysql_query($sqlUpdate);
						*/
					}
				} else {
					$queryIP='INSERT INTO cl_control_bonos (id_producto_servicio,id_cliente,IRD,contrato,cuenta,fecha_activacion,id_cuenta_por_pagar,id_carga,activo) VALUES 
								("'.$id_producto_servicio_facturar_audicel.'","'.$id_cliente.'","","'.$datosL[5].'","'.$datosL[4].'",
								"","","'.$idImportacion.'","1")';
					$resultIP=mysql_query($queryIP);
					$id_control_bono=mysql_insert_id();
					
					$queryIPDetalle='INSERT INTO cl_control_bonos_detalle (id_control_bono,id_accion,fecha_movimiento,hora,id_usuario_agrego,
									id_control_serie,id_sucursal,id_cliente,id_producto_servicio,monto_bono,activo,
									ultimo_movimiento) VALUES ("'.$id_control_bono.'","60","'. date('Y-m-d').'","'. date('H:i:s').'",
									"'.$_SESSION["USR"]->userid.'","","'.$id_sucursal.'","'.$id_cliente.'",
									"'.$id_producto_servicio_facturar_audicel.'","'.$datosL[4].'","1","3")';
					$resultIPDetalle=mysql_query($queryIPDetalle);
					
				}
			}
			
			fclose($gestorL);
		}
	} else if($idLayout==18){
		$sqlCuotas="SELECT id_anio FROM cl_importacion_cuotas WHERE id_carga=".$idImportacion." LIMIT 1";
		$result=mysql_query($sqlCuotas);
		$anio=mysql_result($result,0);
		$sqlUpdate="UPDATE cl_importacion_cuotas SET activo=0 WHERE id_anio=$anio AND id_carga AND id_carga<>$idImportacion";
		mysql_query($sqlUpdate);
	} else if($idLayout==20){
		if(($gestorL = fopen($tmp_name, "r")) !== FALSE){
			$datosLEncabezado = fgetcsv($gestorL, 1000, ",");
			$totalCamposCSV=count($datosLEncabezado);
			while (($datosL = fgetcsv($gestorL, 1000, ",")) !== FALSE){
				$update="UPDATE cl_importacion_caja_comisiones SET dc16='".$datosL[14]."',dc17='".$datosL[15]."',dc18='".$datosL[16]."',dc19='".$datosL[17]."',dc20='".$datosL[18]."',dc24='".$datosL[20]."',dc25='".$datosL[21]."',dc26='".$datosL[22]."',dc27='".$datosL[23]."',dc28='".$datosL[24]."',dc29='".$datosL[25]."',dc30='".$datosL[26]."',dc31='".$datosL[27]."',dc32='".$datosL[28]."',dc33='".$datosL[29]."',dc34='".$datosL[30]."',dc35='".$datosL[31]."',dc21='".$datosL[32]."',dc22='".$datosL[33]."' WHERE id_control=".$datosL[35];
				mysql_query($update)or die(mysql_error().$update);;
			}
		fclose($gestorL);
		}
	}
	
	return $arrErrores;
}
/***   Termina Realiza inserts en una segunda tabla por layout   ***/
			

/***   Funcion General para validaciones especiales por leyout   ***/
function validacionEspecial($arrConfigLeyaut,$datoCSV,$fila,$idLayout,$datos){
	$strErrorEspecial = array();
	
	if($idLayout == 6){	/***   Caja de Comisiones   ***/
		if($arrConfigLeyaut[1] == 'ID Tipo de Producto' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_tipos_producto_servicio","id_tipo_producto_servicio,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El ID Tipo de Producto ".$datoCSV." no se encuentra registrado."));
			}
		} elseif($arrConfigLeyaut[1] == 'ID Promocion' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_promociones_sky","id_promocion_sky,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El ID Promocion ".$datoCSV." no se encuentra registrado."));
			}
		} elseif($arrConfigLeyaut[1] == 'ID Forma de Pago' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_forma_pago_sky","id_forma_pago_sky,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El ID Forma de Pago ".$datoCSV." no se encuentra registrado."));
			}
		} elseif($arrConfigLeyaut[1] == 'ID Paquete' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_paquetes_sky","id_paquete_sky",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El ID Paquete ".$datoCSV." no se encuentra registrado."));
			}
		} elseif($arrConfigLeyaut[1] == 'ID Funcionalidad' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_funcionalidades","id_funcionalidad,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El ID Funcionalidad ".$datoCSV." no se encuentra registrado."));
			}
		} elseif($arrConfigLeyaut[1] == 'ID Numero de Equipo' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_numeros_equipo_sky","id_numero_equipo_sky,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El ID Numero de Equipo ".$datoCSV." no se encuentra registrado."));
			}
		} elseif($arrConfigLeyaut[1] == 'Clave Producto CXC' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_productos_servicios","clave,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La clave de producto CXC: ".$datoCSV." no se encuentra registrada."));
			}
		} elseif($arrConfigLeyaut[1] == 'Clave Producto CXP' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_productos_servicios","clave,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La clave de producto CXP: ".$datoCSV." no se encuentra registrada."));
			}
		}
	} elseif($idLayout == 2){ /***   Activaciones   ***/
		if($arrConfigLeyaut[1] == 'Fecha Activacion' && $datoCSV != ''){
			$fecha=formatoFechaYMD($datoCSV);
			if(! validaFechaEsteEnRangoFechas("cl_cajas_comisiones","fecha_inicio","fecha_fin",$fecha,"activo","1")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Fecha de Activacion ".$datoCSV." no esta incluida en algun rango de fechas de la caja de comisiones."));
			}
			if(! validaFechaTengaDetalleComisionAsociado("cl_cajas_comisiones","fecha_inicio","fecha_fin",$fecha,"activo","1")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Fecha de Activacion ".$datoCSV." no tiene un detalle de comisiones."));
			}
		} elseif($arrConfigLeyaut[1] == 'Special Messaje 3' && $datoCSV != ''){
			if($datoCSV != ''){
				if(! validaValorEnCampoTabla("cl_importacion_caja_comisiones","t46,activo",$datoCSV.",1","","")){
					array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," Special Messaje3 ".$datoCSV." no coincide con alguna clave de la caja de comisiones."));
				} else {
					$arr_valida_uno=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,activo",$datoCSV.",1","","");
					
					if(count($arr_valida_uno) > 1){ //***   Special Messaje 3   ***//
						$arr_id_paquete=devuelveCampoApartirOtroCampo("cl_paquetes_sky","id_paquete_sky","nombre_paquete_sky",$datos[6],"","");
						$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,activo",$datoCSV.",".$arr_id_paquete[0][0].",1","","");
						
						//$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,t50,activo",$datoCSV.",".$datos[6].",1","","");
						
						if(count($arr_valida_dos) > 1){ //***   Paquete Principal   ***//
							//$arr_id_funcionalidad=devuelveCampoApartirOtroCampo('cl_tipos_equipos','id_funcionalidad','inicio_serie', substr($datos[10],0,6),'','');
							//$id_funcionalidad=$arr_id_funcionalidad[0];
							
							$arr_valida_tres=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,n4,activo",$datoCSV.",".$arr_id_paquete[0][0].",".$datos[16].",1","","");

							if(count($arr_valida_tres) == 0){ //***   Num Equipos   ***//
								array_push($strErrorEspecial, array($fila,"N/A"," (3) La activacion no cuenta con una caja de comision."));
							}
							
							/*$arr_valida_tres=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,t50,n6,activo",$datoCSV.",".$datos[6].",".$id_funcionalidad.",1","","");
							
							if(count($arr_valida_tres) > 1){
								array_push($strErrorEspecial, array($fila,"N/A"," Existe mas de una comision para esta activacion."));
							} elseif(count($arr_valida_tres) == 0){
								array_push($strErrorEspecial, array($fila,"N/A"," (3) La activacion no cuenta con una caja de comision."));
							}*/
						} elseif(count($arr_valida_dos) == 0){
							array_push($strErrorEspecial, array($fila,"N/A"," (2) La activacion no cuenta con una caja de comision."));
						}
					} elseif(count($arr_valida_uno) == 0){
						array_push($strErrorEspecial, array($fila,"N/A"," (1) La activacion no cuenta con una caja de comision."));
					}
				}
			}
		} elseif($arrConfigLeyaut[1] == 'Paquete Principal' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_paquetes_sky","nombre_paquete_sky",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Paquete Principal ".$datoCSV." no se encuentra registrado en el catalogo de paquetes sky."));
			}
		} elseif($arrConfigLeyaut[1] == 'CVE Distribuidor' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_clientes","di,activo",$datoCSV.",1","","")){
				if(! validaValorEnCampoTabla("ad_sucursales","di,activo",$datoCSV.",1","","")){
					array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El distribuidor: ".$datos[8]." con clave ".$datoCSV." no se encuentra registrado."));
				}
			}
			if(!validaValorEnCampoTabla("ad_clientes","ad_clientes.nit,clientePadre.di",$datos[13].",".$datos[7],"ad_clientes as clientePadre","ad_clientes.id_cliente_padre,clientePadre.id_cliente")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Tecnico: ".$datos[13]." no coincide con el Distribuidor: ".$datos[8]." con DI: ".$datos[7]."."));
			}
		} elseif($arrConfigLeyaut[1] == 'Tecnico' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_clientes","nit,activo",$datoCSV.",1","","")){
				if(! validaValorEnCampoTabla("ad_entidades_financieras","nit,activo",$datoCSV.",1","","")){
					array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Tecnico ".$datoCSV." no existe y en el archivo se indica que esta relacionado con el distribuidor: ".$datos[8]." con Clave: ".$datos[7]."."));
				}
			}
		} elseif($arrConfigLeyaut[1] == 'IRD' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_series","cl_control_series.numero_serie,cl_control_series.activo,cl_control_series_detalle.id_estatus",$datoCSV.",1,2","cl_control_series_detalle","cl_control_series.id_control_serie,cl_control_series_detalle.id_control_serie")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El IRD ".$datoCSV." no se encuentra registrado o no se encuentra en estatus Asignado por SKY."));
			}
			if(validaValorEnCampoTabla("cl_control_contratos_detalles","numero_serie,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El IRD ".$datoCSV." fue registrado previamente."));
			}
		} elseif($arrConfigLeyaut[1] == 'Cuenta' && $datoCSV != ''){
			if(validaValorEnCampoTabla("cl_control_contratos","cuenta,fecha_activacion,contrato,activo",$datos[1].",".formatoFechaYMD($datos[0]).",".$datos[11].",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El contrato ya fue registrado previamente."));
			}
		} elseif($arrConfigLeyaut[1] == 'Tipo de Instalacion' && $datoCSV != ''){
			if($datoCSV != 'A'){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Tipo de Instalacion debe ser ACTIVACION."));
			}
		}
	} elseif($idLayout == 8){ /***   Calificaciones   ***/
		if($arrConfigLeyaut[1] == 'Contrato' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","contrato,cuenta,fecha_activacion,activo",$datos[1].",".$datos[3].",".formatoFechaYMD($datos[10]).",1","","")){
				array_push($strErrorEspecial, array($fila,"Contrato,Cuenta,Fecha Activacion"," No se encuentra la activacion registrada."));
			}
		}
		if($arrConfigLeyaut[1] == 'Status Contrato' && $datoCSV != ''){
			if(validaValorEnCampoTabla("cl_control_contratos","cl_control_contratos.contrato,cl_control_contratos.cuenta,cl_control_contratos.fecha_activacion,cl_control_contratos.activo,cl_control_contratos_detalles.id_accion_contrato,cl_control_contratos_detalles.ultimo_movimiento",$datos[1].",".$datos[3].",".formatoFechaYMD($datos[10]).",1,22,1","cl_control_contratos_detalles","cl_control_contratos.id_control_contrato,cl_control_contratos_detalles.id_control_contrato")){
				array_push($strErrorEspecial, array($fila,"Contrato,Cuenta,Fecha Activacion,Estatus Contrato"," El contrato ya fue reasignado por nova anteriormente."));
			}
		}
	} elseif($idLayout == 5){ /***   Numero der serie   ***/
		if($arrConfigLeyaut[1] == 'Numero de Serie' && $datoCSV != ''){
			if(validaValorEnCampoTabla("cl_importacion_numeros_series","t48,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El numero se serie: ".$datoCSV." ya esta registrado"));
			}
		}
	} elseif($idLayout == 11){ /***   Penalizaciones   ***/
		if($arrConfigLeyaut[1] == 'Clave' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_productos_servicios","clave,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Clave: ".$datoCSV." no se encuentra registrada."));
			}
		}
		if($arrConfigLeyaut[1] == 'Folio SKY' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_cuentas_por_pagar_operadora","numero_documento",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Folio SKY: ".$datoCSV." no se encuentra registrado."));
			}
		}
		if($arrConfigLeyaut[1] == 'Factura SKY' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_cuentas_por_pagar_operadora","numero_documento_2",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Factura SKY: ".$datoCSV." no se encuentra registrado."));
			}
			if(validaValorEnCampoTabla("cl_control_contratos_detalles","id_control_cuenta_por_pagar_operadora",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Factura SKY: ".$datoCSV." ya se encuentra registrada en los contratos."));
			}
			if(validaValorEnCampoTabla("cl_control_penalizaciones","id_cuenta_por_pagar",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Factura SKY: ".$datoCSV." ya se encuentra registrado en las penalizaciones."));
			}
		}
		if($arrConfigLeyaut[1] == 'Clave del Cliente' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_clientes","clave,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Clave del Cliente: ".$datoCSV." no se encuentra registrada."));
			}
		}
		if($arrConfigLeyaut[1] == 'Contrato'){
			if($datos[5] != '' && $datos[6] != '' && $datos[7] != ''){
				if(! validaValorEnCampoTabla("cl_control_contratos","contrato,cuenta,fecha_activacion,activo",$datos[5].",".$datos[6].",".formatoFechaYMD($datos[7]).",1","","")){
					array_push($strErrorEspecial, array($fila,"Contrato,Cuenta,Fecha Activacion"," El Contrato no se encuentra registrado."));
				}
			}
		}
		if($arrConfigLeyaut[1] == 'Serie' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_series","numero_serie,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Serie: ".$datoCSV." no se encuentra registrada."));
			}
		}
	} elseif($idLayout == 12){ /***   Descuentos   ***/
		if($arrConfigLeyaut[1] == 'Folio' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_cuentas_por_pagar_operadora","numero_documento",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Folio : ".$datoCSV." no se encuentra registrado."));
			}
		}
		if($arrConfigLeyaut[1] == 'Cuenta' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","cuenta,fecha_activacion,activo",$datos[1].",".formatoFechaYMD($datos[2]).",1","","")){
				array_push($strErrorEspecial, array($fila,"Cuenta,Fecha Activacion"," La Cuenta no se encuentra registrada."));
			}
		}
		if($arrConfigLeyaut[1] == 'Tipo de Producto' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_tipo_producto_sky","nombre_producto_sky,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Tipo de Producto: ".$datoCSV." no se encuentra registrado."));
			}
		}
	} elseif($idLayout == 13){ /***   Bonos Extra   ***/
		if($arrConfigLeyaut[1] == 'Folio Factura Emitida a SKY' && $datoCSV != '' && $datoCSV != '#N/A'){
			if(! validaValorEnCampoTabla("ad_facturas_audicel","id_factura,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Folio Factura Emitida a SKY : ".$datoCSV." no se encuentra registrado."));
			}
		}
		if($arrConfigLeyaut[1] == 'CVE Distribuidor' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_clientes","di,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La CVE Distribuidor : ".$datoCSV." no se encuentra registrada."));
			}
		}
		if($arrConfigLeyaut[1] == 'Cuenta'){
			if($datos[5] != '' && $datos[4] != ''){
				if(! validaValorEnCampoTabla("cl_control_contratos","cuenta,contrato,activo",$datos[4].",".$datos[5].",1","","")){
					array_push($strErrorEspecial, array($fila,"Cuenta,Contrato"," La Cuenta o/y Contrato ".$datos[4]." - ".$datos[5]." no se encuentran registrados."));
				}
			}
		}
		if($arrConfigLeyaut[1] == 'Clave Servicio' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_productos_servicios","clave,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Clave Servicio : ".$datoCSV." no se encuentra registrada."));
			}
		}
	} elseif($idLayout == 14){ /***   Complementos   ***/
		if($arrConfigLeyaut[1] == 'Factura Emitida a SKY' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_facturas","id_factura,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Factura Emitida a SKY : ".$datoCSV." no se encuentra registrada."));
			}
		}
		if($arrConfigLeyaut[1] == 'Cuenta' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","cuenta,contrato,fecha_activacion,id_tipo_activacion,activo",$datos[0].",".$datos[1].",".formatoFechaYMD($datos[2]).",1,1","","")){
				array_push($strErrorEspecial, array($fila,"Cuenta,Contrato,Fecha Activacion"," No existe la activacion."));
			}
		}
	} elseif($idLayout == 15){ /***   Servicios   ***/
		if($arrConfigLeyaut[1] == 'Contrato' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","contrato,id_tipo_activacion,activo",$datos[0].",1,1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," No existe una activacion con  el Contrato: ".$datoCSV."."));
			}
		}
		if($arrConfigLeyaut[1] == 'Factura' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_facturas","id_factura,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Factura : ".$datoCSV." no se encuentra registrada."));
			}
		}
	} elseif($idLayout == 16){ /***   Promosiones   ***/
		if($arrConfigLeyaut[1] == 'Contrato' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","contrato,id_tipo_activacion,activo",$datos[0].",1,1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," No existe una activacion con  el Contrato: ".$datoCSV."."));
			}
		}
		if($arrConfigLeyaut[1] == 'Factura' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_facturas","id_factura,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Factura : ".$datoCSV." no se encuentra registrada."));
			}
		}
	} elseif($idLayout == 7){ /***   Crecimientos   ***/
		if($arrConfigLeyaut[1] == 'Cuenta' && $datoCSV != ''){
			if(validaValorEnCampoTabla("cl_control_contratos","cuenta,contrato,fecha_activacion,activo",$datos[0].",".$datos[10].",".formatoFechaYMD($datos[2]).",1","","")){
				array_push($strErrorEspecial, array($fila,"Cuenta,Contrato,Fecha Activacion"," El crecimiento ya fue registrado previamente."));
			}
		} elseif($arrConfigLeyaut[1] == 'Fecha Activacion' && $datoCSV != ''){
			$fecha=formatoFechaYMD($datoCSV);
			if(! validaFechaEsteEnRangoFechas("cl_cajas_comisiones","fecha_inicio","fecha_fin",$fecha,"activo","1")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Fecha de Activacion ".$datoCSV." no esta incluida en algun rango de fechas de la caja de comisiones."));
			}
			if(! validaFechaTengaDetalleComisionAsociado("cl_cajas_comisiones","fecha_inicio","fecha_fin",$fecha,"activo","1")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Fecha de Activacion ".$datoCSV." no tiene un detalle de comisiones."));
			}
		} elseif($arrConfigLeyaut[1] == 'Special Messaje 3' && $datoCSV != ''){
			if($datoCSV != ''){
				if(! validaValorEnCampoTabla("cl_importacion_caja_comisiones","t46,activo",$datoCSV.",1","","")){
					array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," Special Messaje 3 ".$datoCSV." no coincide con alguna clave de la caja de comisiones."));
				} else {
					$arr_valida_uno=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,activo",$datoCSV.",1","","");
					
					if(count($arr_valida_uno) > 1){ //***   Special Messaje 3   ***//
						$arr_id_paquete=devuelveCampoApartirOtroCampo("cl_paquetes_sky","id_paquete_sky","nombre_paquete_sky",$datos[5],"","");
						$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,activo",$datoCSV.",".$arr_id_paquete[0][0].",1","","");
						
						//$arr_valida_dos=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,t50,activo",$datoCSV.",".$datos[5].",1","","");
						
						if(count($arr_valida_dos) > 1){ //***   Paquete Principal   ***//
							//$arr_id_funcionalidad=devuelveCampoApartirOtroCampo('cl_tipos_equipos','id_funcionalidad','inicio_serie', substr($datos[9],0,6),'','');
							//$id_funcionalidad=$arr_id_funcionalidad[0];
							
							$arr_valida_tres=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,n5,n4,activo",$datoCSV.",".$arr_id_paquete[0][0].",".$datos[13],",1","");
							
							if(count($arr_valida_tres) == 0){ //***   Numero de Equipos   ***//
								array_push($strErrorEspecial, array($fila,"N/A"," La activacion no cuenta con una caja de comision."));
							}
							
							/*$arr_valida_tres=devuelveCampoApartirOtroCampo("cl_importacion_caja_comisiones","id_control","t46,t50,n6,activo",$datoCSV.",".$datos[5].",".$id_funcionalidad.",1","","");
							
							if(count($arr_valida_tres) > 1){
								array_push($strErrorEspecial, array($fila,"N/A"," Existe mas de una comision para esta activacion."));
							} elseif(count($arr_valida_tres) == 0){
								array_push($strErrorEspecial, array($fila,"N/A"," La activacion no cuenta con una caja de comision."));
							}*/
						} elseif(count($arr_valida_dos) == 0){
							array_push($strErrorEspecial, array($fila,"N/A"," La activacion no cuenta con una caja de comision."));
						}
					} elseif(count($arr_valida_uno) == 0){
						array_push($strErrorEspecial, array($fila,"N/A"," La activacion no cuenta con una caja de comision."));
					}
				}
			}
		} elseif($arrConfigLeyaut[1] == 'Paquete Principal' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_paquetes_sky","nombre_paquete_sky",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Paquete Principal ".$datoCSV." no se encuentra registrado en el catalogo de paquetes sky."));
			}
		} elseif($arrConfigLeyaut[1] == 'CVE Distribuidor' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_clientes","di,activo",$datoCSV.",1","","")){
				if(! validaValorEnCampoTabla("ad_sucursales","di,activo",$datoCSV.",1","","")){
					array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La CVE Distribuidor ".$datoCSV." no se encuentra registrada."));
				}
			}
		} elseif($arrConfigLeyaut[1] == 'Tecnico' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_clientes","nit,activo",$datoCSV.",1","","")){
				if(! validaValorEnCampoTabla("ad_entidades_financieras","nit,activo",$datoCSV.",1","","")){
					array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Tecnico ".$datoCSV." no se encuentra registrado."));
				}
			}
		} elseif($arrConfigLeyaut[1] == 'IRD' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_series","cl_control_series.numero_serie,cl_control_series.activo,cl_control_series_detalle.id_estatus",$datoCSV.",1,2","cl_control_series_detalle","cl_control_series.id_control_serie,cl_control_series_detalle.id_control_serie")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El IRD ".$datoCSV." no se encuentra registrado o no se encuentra en estatus Asignado por SKY."));
			}
			if(validaValorEnCampoTabla("cl_control_contratos_detalles","numero_serie,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El IRD ".$datoCSV." fue registrado previamente."));
			}
		} elseif($arrConfigLeyaut[1] == 'Tipo de Instalacion' && $datoCSV != ''){
			if($datoCSV != 'C'){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Tipo de Instalacion debe ser CRECIMIENTO."));
			}
		}
	} elseif($idLayout == 17){
		if($arrConfigLeyaut[1] == 'Cuenta' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","cuenta,fecha_activacion,contrato",$datos[5].",".formatoFechaYMD($datos[7]).",".$datos[3],"","")){
				array_push($strErrorEspecial, array($fila,"Cuenta,Fecha Activacion"," La Cuenta no se encuentra registrada."));
			}
		}
	} elseif($idLayout == 1){
		if($arrConfigLeyaut[1] == 'Numero de Serie IRD' && $datoCSV != ''){
			if(validaValorEnCampoTabla("cl_control_series","numero_serie,activo",$datoCSV.",1","","")){
				if(! validaValorEnCampoTabla("cl_control_series","cl_control_series.numero_serie,cl_control_series_detalle.activo,cl_control_series_detalle.id_estatus",$datoCSV.",1,1","cl_control_series_detalle","cl_control_series.id_control_serie,cl_control_series_detalle.id_control_serie")){
					//array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El IRD ya se encuentra registrado."));
					array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El IRD ya se encuentra asignado."));
				}
			}
			//***   Valida que no se repita en importaciones anteriores   ***//
			if(validaValorEnCampoTabla("cl_importacion_numeros_series","t48,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El IRD se ha importado anteriormente."));
			}
		}
		if($arrConfigLeyaut[1] == 'Remision' && $datoCSV != ''){
			if(validaValorEnCampoTabla("cl_importacion_pipeline","t50,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La remision ya se encuentra registrada."));
			}
		}
	} elseif($idLayout == 4){ /*** Derechos de activacion ***/
		if($arrConfigLeyaut[1] == 'Codigo Interno' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_productos_servicios","clave,activo",$datoCSV.",1","","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Codigo Interno: ".$datoCSV." no se encuentra registrado."));
			}
		}
		if($arrConfigLeyaut[1] == 'Factura SAP' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_cuentas_por_pagar_operadora","numero_documento_2",$datoCSV,"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La Factura SAP: ".$datoCSV." no se encuentra registrada."));
			}
		}
		if($arrConfigLeyaut[1] == 'Total' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("ad_cuentas_por_pagar_operadora","total,activo,numero_documento_2",$datoCSV.",1,".$datos[4],"","")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," El Total: ".$datoCSV." no coincide con el total registrado."));
			}
		}
	} elseif($idLayout == 19){
		if($arrConfigLeyaut[1] == 'Concepto 2' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","cl_control_contratos_detalles.numero_serie,cl_control_contratos.activo,cl_control_contratos.cuenta",$datoCSV.",1,".$datos[2],"cl_control_contratos_detalles","cl_control_contratos.id_control_contrato,cl_control_contratos_detalles.id_control_contrato")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," No se encontro un registro con el IRD: ".$datoCSV." y cuenta ".$datos[2]."."));
			}
		}
		if($arrConfigLeyaut[1] == 'Factura' && $datoCSV != ''){
			if(! validaValorEnCampoTabla("cl_control_contratos","cl_control_contratos_detalles.id_control_cuenta_por_pagar_operadora,cl_control_contratos_detalles.activo",",1,","cl_control_contratos_detalles","cl_control_contratos.id_control_contrato,cl_control_contratos_detalles.id_control_contrato")){
				array_push($strErrorEspecial, array($fila,$arrConfigLeyaut[1]," La factura: ".$datoCSV." ya tiene una cuenta por pagar asignada."));
			}
		}
	}
	
	return $strErrorEspecial;
}
/***   Termina Funcion General para validaciones especiales por leyout   ***/

?>