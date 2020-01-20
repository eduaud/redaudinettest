<?php

/***   Extrae el nombre del layout apartir de un id   ***/
function nombreImportacion($idLayout){
	$nombre='';
	
	if($idLayout != 0 && $idLayout > 0){
		$queryLayout='SELECT nombre FROM cl_layouts WHERE id_layout="'.$idLayout.'"';
		$resultLayout=mysql_query($queryLayout);
		
		if(mysql_num_rows($resultLayout) > 0){
			$datosLayout=mysql_fetch_array($resultLayout);
			$nombre=$datosLayout['nombre'];
		}
	}
	
	return $nombre;
}
/***   Termina Extrae el nombre del layout apartir de un id   ***/


/***   convirte fecha a formato Y-m-d   ***/
function formatoFechaYMD($fecha){
	if(strpos($fecha,"-")) $delimitador="-";
	elseif(strpos($fecha,"/")) $delimitador="/";
	$pos=strpos($fecha,$delimitador);
	
	if($pos == 2) $iniciaPor='dia';
	elseif($pos == 4) $iniciaPor='anio';
	
	$arrFecha=explode($delimitador,$fecha);
	
	if($iniciaPor=='dia'){
		$fechaAMD=$arrFecha[2].'-'.$arrFecha[1].'-'.$arrFecha[0];
	} elseif($iniciaPor=='anio'){
		$fechaAMD=$arrFecha[0].'-'.$arrFecha[1].'-'.$arrFecha[2];
	}
	
	return $fechaAMD;
}
/***   Termina convirte fecha a formato Y-m-d   ***/


/***   Valida el tipo de dato   ***/
function validarTipoDato($tipoDato, $dato){
	$dato=trim($dato);
	$respuesta=true;
	
	if($dato != ''){
		if($tipoDato == 'numero'){
			$respuesta=is_numeric($dato)? true: false;
			
		} elseif($tipoDato == 'fecha'){
			if(strpos($dato,"-")) $delimitador="-";
			elseif(strpos($dato,"/")) $delimitador="/";
			$pos=strpos($dato,$delimitador);
			
			if($pos == 2) $iniciaPor='dia';
			elseif($pos == 4) $iniciaPor='anio';
			
			if($delimitador == "-" || $delimitador == "/"){
				$arrFecha=explode($delimitador,$dato);
			} else {
				$arrFecha[0]='0'; $arrFecha[1]='0'; $arrFecha[2]='0';
			}
			
			if($iniciaPor=='dia'){
				$anio=$arrFecha[2]; $mes=$arrFecha[1]; $dia=$arrFecha[0];
			} elseif($iniciaPor=='anio'){
				$anio=$arrFecha[0]; $mes=$arrFecha[1]; $dia=$arrFecha[2];
			}
			
			$respuesta=checkdate($mes, $dia, $anio)? true: false;
			
		} elseif($tipoDato == 'decimal'){
			$respuesta=is_numeric($dato)? true: false;
		}
	}
	
	return $respuesta;
}
/***   Valida el tipo de dato   ***/


/***   Valida longitud Maxima   ***/
function validarLongitudMax($tipoDato, $dato){
	$respuesta=true;
	$dato=trim($dato);
	
	if($tipoDato > 0 && $tipoDato != ''){
		$respuesta = strlen($dato) <= $tipoDato? true: false;
	}
	
	return $respuesta;
}
/***   Termina Valida longitud Maxima   ***/


/***   Valida longitud Minima   ***/
function validarLongitudMin($tipoDato, $dato){
	$respuesta=true;
	$dato=trim($dato);
	
	if($tipoDato > 0 && $tipoDato != ''){
		$respuesta = strlen($dato) >= $tipoDato? true: false;
	}
	
	return $respuesta;
}
/***   Termina Valida longitud Minima   ***/


/***   Valida si es requerido   ***/
function validarRequerido($tipoDato, $dato){
	$respuesta=true;
	if($tipoDato == 1){
		$respuesta = $dato != ''? true: false;
	}
	return $respuesta;
}
/***   Termina Valida si es requerido   ***/


/***   Validar que el dato este registrado (SQL_BUSCADOR)   ***/
function validarTabla($arrValidaTabla,$dato){
	$respuesta=true;
	if($arrValidaTabla[0] == 1){
		$query=str_replace('valorLayout',$dato,$arrValidaTabla[3]);
		$result=mysql_query($query);
		$respuesta = mysql_num_rows($result) > 0? true: false;
	}
	return $respuesta;
}
/***   Terminar Validar que el dato este registrado   ***/


/***   Ejecuta los updates correspondientes al layout   ***/
function actualizaTabla($arrUpdate){
	for($ap=0; $ap < count($arrUpdate); $ap++){
		$query=$arrUpdate[11];
		mysql_query($query);
	}
}
/***   Termina Ejecuta los updates correspondientes al layout   ***/


/***   Extrae la informacion del layout   ***/
function encabezadosLayout($idLayout){
	$arrEncabezados=array();
	$queryEncabezados='SELECT id_layout_config as id, nombre as encabezado, tipo_dato, longitud_maxima, longitud_minima, requerido, validacion_tabla,
						tabla, campo_tabla, sql_buscador, actualiza_tabla, sql_actualizar, posicion_importacion, nombre_campo
						FROM cl_layouts_configuracion WHERE id_layout='.$idLayout.' AND activo=1 ORDER BY posicion_importacion';
	$resultEncabezados=mysql_query($queryEncabezados);
	
	while($datosEncabezados=mysql_fetch_array($resultEncabezados)){
		array_push($arrEncabezados,array($datosEncabezados['id'],$datosEncabezados['encabezado'],$datosEncabezados['tipo_dato'],
										$datosEncabezados['longitud_maxima'],$datosEncabezados['longitud_minima'],$datosEncabezados['requerido'],
										$datosEncabezados['validacion_tabla'],$datosEncabezados['tabla'],$datosEncabezados['campo_tabla'],
										$datosEncabezados['sql_buscador'],$datosEncabezados['actualiza_tabla'],$datosEncabezados['sql_actualizar'],
										$datosEncabezados['posicion_importacion'],$datosEncabezados['nombre_campo']));
	}
	
	return $arrEncabezados;
}
/***   Termina Extrae la informacion del layout   ***/


/***   Verifica que se cumplan todas las condiciones de un campo    ***/
function validaCampo($arrValida,$dato,$linea){
	$strError = array();
	if(! validarTipoDato($arrValida[2], $dato)){ array_push($strError, array($linea,$arrValida[1]," No contiene un valor de tipo ".$arrValida[2].".")); }
	
	if(! validarLongitudMax($arrValida[3], $dato)){ array_push($strError, array($linea,$arrValida[1]," Supera los ".$arrValida[3]." caracteres.")); }
	
	if(! validarLongitudMin($arrValida[4], $dato)){ array_push($strError, array($linea,$arrValida[1]," No contiene el minimo de caracteres solicitados (".$arrValida[4].").")); }
	
	if(! validarRequerido($arrValida[5], $dato)){ array_push($strError, array($linea,$arrValida[1]," Es requerido.")); }
	
	if(! validarTabla(array($arrValida[6],$arrValida[7],$arrValida[8],$arrValida[9]), $dato)){ 
		array_push($strError, array($linea,$arrValida[1]," El valor ".$dato." No se encuentra registrado.")); 
	}
	
	return $strError;
}
/***   Termina Verifica que se cumplan todas las condiciones de un campo    ***/

?>