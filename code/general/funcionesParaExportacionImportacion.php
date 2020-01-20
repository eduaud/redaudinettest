<?php 
function Exportacion($titulo,$objPHPExcel){		
	$objPHPExcel->getProperties()->setCreator("Audicel")
								 ->setLastModifiedBy("Audicel")
								 ->setTitle("Sistema Audicel")
								 ->setSubject("Reporte de ".$titulo)
								 ->setDescription("Reporte de ".$titulo)
								 ->setKeywords("Reporte de ".$titulo)
								 ->setCategory($titulo);
}
function Descargar($titulo,$output){
	$hoy = getdate();
	$anio=$hoy['year'];
	$mes=$hoy['mon'];
	$dia=$hoy['mday'];

	$fecha=$dia.'-'.$mes.'-'.$anio;
	header('Content-Type:text/csv; charset=utf-8');
	// forzar descarga del archivo con un nombre de archivo determinado
	header('Content-Disposition: attachment; filename='.$titulo.'-'.$fecha.'.csv' );
	// indicar tamaño del archivo
	header('Content-Length:'. strlen($output) );
	// enviar archivo
	echo $output;
	exit;
}
function colocaEncabezados($arrayEncabezados,$fila,$objPHPExcel,$arrayDatos){
	foreach($arrayEncabezados as $encabezados=>$encabezado){
		$columna=numeros_a_letras(($encabezados));
		ColocaDato($objPHPExcel,$columna,$fila,$encabezado);
		
	}
	$fila+=1;
	
	foreach($arrayDatos as $datos){
			foreach($arrayEncabezados as $encabezados=>$encabezado){
			foreach($datos as $posicion=>$dato){
				if($posicion==$encabezados){
					$columna=numeros_a_letras(($encabezados));
					ColocaDato($objPHPExcel,$columna,$fila,$dato);
				}
			}
		}
		$fila+=1;
	}
	return $fila;
}
function numeros_a_letras($numero) {
    // Convierte un numero en una letra de la A a la Z en el alfabeto latin
    // Utilizado para las columnas de excel
    // A = 0, si queremos que A = 1, modificaremos 26 por 27 (en los 2 sitios que esta)
 
    // Si le pasamos el valor 0 nos devolvera A, si pasamos 27 nos devolvera AB
    $res = "";
    
    while ($numero > -1) {
        // Cargaremos la letra actual
        $letter = $numero % 26;
        $res = chr(65 + $letter) . $res;  // A 65 en ASCII (A) le sumaremos el valor de la letra y lo convertiremos a texto (65 + 0 = A)
        
        $numero = intval($numero / 26) - 1; // Le quitamos la letra para ir a la siguiente y le restamos 1 si no se saltara una serie
    }            
    
    return $res;
}
function convertir_a_columna_excel($numero_columna) {
	return strtoupper(chr($numero_columna + 96));
}
function ColocaColor($objPHPExcel,$columna,$fila,$color,$tamañoLetra){
	$objPHPExcel->getActiveSheet()->getStyle($columna.$fila)->getFont()->setBold(true)->setSize($tamañoLetra)->getColor()->setRGB($color);
}
function ColocaBorde($objPHPExcel,$columna,$fila){
	$objPHPExcel->getActiveSheet()->getStyle($columna.$fila)->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
}
function ColocaDato($objPHPExcel,$columna,$fila,$dato){
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($columna.$fila,$dato);
}
function Relleno($objPHPExcel,$columna,$fila,$color){
	$objPHPExcel->getActiveSheet()->getStyle($columna.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB($color);
}
function AlinearCentro($objPHPExcel,$columna,$fila){
	$objPHPExcel->getActiveSheet()->getStyle($columna.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
function AlinearDerecha($objPHPExcel,$columna,$fila){
	$objPHPExcel->getActiveSheet()->getStyle($columna.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}
function FormatoNumerico($objPHPExcel,$columna,$fila){
	$objPHPExcel->getActiveSheet()->getStyle($columna.$fila)->getNumberFormat()->setFormatCode('##0');
}
function obtenDatos($campos,$tabla,$leftJoin,$IDLeft,$where){
	$sql="SELECT ".$campos." FROM ".$tabla;
	if($leftJoin!=''){
		$a_left=explode(',',$leftJoin);	
		$a_IDs=explode(',',$IDLeft);
		if(count($a_left)>0){	
			for($i=0;$i<count($a_left);$i++){
				$sql.=" LEFT JOIN ".$a_left[$i]." ON ".$a_IDs[$i];
			}
		}
	}
	if($where!=''){
		$sql.=" WHERE ".$where;
	}
	$arr_Datos=array();
	//die($sql);
	$result=mysql_query($sql)or die(mysql_error());
	$indice=0;
	while($array_result=mysql_fetch_array($result)){
		for($i=0;$i<(count($array_result)/2);$i++){
			$arr_Datos[$indice][$i]=$array_result[$i];
		}
		$indice+=1;
	}
	return $arr_Datos;
}
function ObtenerCamposPorLayout($id_layout){
	$sqlCampos="SELECT nombre_campo
				FROM cl_layouts_configuracion 
				LEFT JOIN cl_layouts ON cl_layouts_configuracion.id_layout=cl_layouts.id_layout 
				WHERE cl_layouts.id_layout='".$id_layout."' ORDER BY posicion_importacion";
	$arr_Campos=array();
	$result=mysql_query($sqlCampos)or die(mysql_error());
	while($array_result=mysql_fetch_array($result)){
		for($i=0;$i<(count($array_result)/2);$i++){
			array_push($arr_Campos,$array_result[$i]);
		}
	}
	return $arr_Campos;
}
function ObtenerTitulos($id_layout){
	$sqlCampos="SELECT cl_layouts_configuracion.nombre
				FROM cl_layouts_configuracion 
				LEFT JOIN cl_layouts ON cl_layouts_configuracion.id_layout=cl_layouts.id_layout 
				WHERE cl_layouts.id_layout='".$id_layout."' ORDER BY posicion_importacion";
				
	$arr_titulos=array();
	$result=mysql_query($sqlCampos)or die(mysql_error());
	$indice=0;
	while($array_result=mysql_fetch_array($result)){
		for($i=0;$i<(count($array_result)/2);$i++){
			array_push($arr_titulos,$array_result[$i]);
		}
	}
	return $arr_titulos;
}
function ConvierteLinea($array,$csv){
	$csv_end="\n";	
	foreach($array as $fila){
			$arraySeparado=implode(',',$fila);
			$csv.=$arraySeparado.$csv_end;
	}
	return $csv;
}
?>