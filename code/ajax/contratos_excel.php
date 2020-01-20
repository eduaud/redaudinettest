<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

/*echo '<pre>';print_r($_GET);echo '</pre>';
die();*/

extract($_GET);
extract($_POST);
$arrayVacio = array();
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
		$condicion .= " AND cuenta='" .$cuenta. "'";
	else if($cuenta != '')
		$condicion .= " cuenta='" .$cuenta. "'";
}

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');
require('../../include/phpexcel/Classes/PHPExcel.php');
$objPHPExcel = new PHPExcel();
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../../imagenes/header_logo.png');
$objDrawing->setHeight(70);
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$objPHPExcel->getActiveSheet()->mergeCells('D2:E2');
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('D2','AUDICEL Comunicación y Entretenimiento');
ColocaColor($objPHPExcel,'D',2,'010872',17.5);
AlinearCentro($objPHPExcel,'D',2);
$objPHPExcel->getActiveSheet()->mergeCells('D3:E3');
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('D3','Contratos de: '.$_SESSION["USR"]-> fullusername);
ColocaColor($objPHPExcel,'D',3,'010872',17);
AlinearCentro($objPHPExcel,'D',3);
			
				
$objPHPExcel->getProperties()->setCreator("Audicel")
							 ->setLastModifiedBy("Audicel")
							 ->setTitle("Sistema Audicel")
							 ->setSubject("Reporte de Contratos")
							 ->setDescription("Reporte de Contratos")
							 ->setKeywords("Reporte de Contratos")
							 ->setCategory("Contratos");
$titulos=array();
array_push($titulos,'CONTRATOS PENDIENTES DE ENTREGA','CONTRATOS ENTREGADOS EN PLAZA','CONTRATOS RECHAZADOS EN PLAZA','CONTRATOS REAGENDADOS POR SKY','CONTRATOS DE MALA CALIDAD','CONTRATOS POR FACTURAR','CONTRATOS FACTURADOS');

$fila=6;

for($i = 0; $i < 7; $i++){
	$body = $i;
	
	
	
	if($body == 0){
		$contratosPendientes=array();
		array_push($contratosPendientes,'CONTRATO','CUENTA','DISTRIBUIDOR','FECHA ACTIVACIÓN','COMISIÓN POSIBLE','FECHA DE VENCIMIENTO','DIAS TRANSCURRIDOS');
		
		$PendientesEntrega = Datos('pendientes','1,100',$condicion,'','');
		$Datos = array();
		$posicion = 0;
		foreach($PendientesEntrega as $dato){
			foreach($dato as $dat => $valor){
				if($dat == 'contrato'){
					$Datos[$posicion][0] = $valor;
				}elseif($dat == 'cuenta'){
					$Datos[$posicion][1] = $valor;
				}elseif($dat == 'distribuidor'){
					$Datos[$posicion][2] = $valor;
				}elseif($dat == 'fecha_activacion'){
					$Datos[$posicion][3] = $valor;
				}elseif($dat == 'comision'){
					$Datos[$posicion][4] = $valor;
				}elseif($dat == 'fecha_vencimiento'){
					$Datos[$posicion][5] = $valor;
				}elseif($dat == 'dias_transcurridos'){
					$Datos[$posicion][6] = $valor;
				}
			}
			$posicion += 1;
		}
		if(count($Datos)==0)
			array_push($arrayVacio,0);
		else{
			$objPHPExcel -> setActiveSheetIndex(0)
						 -> setCellValue('B'.$fila,$titulos[$i]);
			ColocaColor($objPHPExcel,'B',$fila,'010872',15);
			$fila += 1;
			$fila = colocaEncabezados($contratosPendientes,$fila,$objPHPExcel,$Datos);
		}
	}
	else if($body == 1){
		$contratosEntregados=array();
		array_push($contratosEntregados,'CONTRATO','CUENTA','DISTRIBUIDOR','FECHA ACTIVACIÓN','COMISIÓN POSIBLE','FECHA DE ENTREGA A PLAZA','CONTRA RECIBO');
		
		$campoEspecial = ",cl_contrarecibos.id_contrarecibo,cl_contrarecibos.fecha_hora";
		$leftUnionEspecial = " LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
		$Entregados = Datos('entregados','11',$condicion,$campoEspecial,$leftUnionEspecial);
		//echo '<pre>';print_r($Entregados);die('</pre>');
		$Datos = array();
		$posicion = 0;
		foreach($Entregados as $dato){
			foreach($dato as $dat => $valor){
				if($dat == 'contrato'){
					$Datos[$posicion][0] = $valor;
				}elseif($dat == 'cuenta'){
					$Datos[$posicion][1] = $valor;
				}elseif($dat == 'distribuidor'){
					$Datos[$posicion][2] = $valor;
				}elseif($dat == 'fecha_activacion'){
					$Datos[$posicion][3] = $valor;
				}elseif($dat == 'comision'){
					$Datos[$posicion][4] = $valor;
				}elseif($dat == 'fecha_entrega'){
					$Datos[$posicion][5] = $valor;
				}elseif($dat == 'contrarecibo'){
					$Datos[$posicion][6] = $valor;
				}
			}
			$posicion += 1;
		}//echo '<pre>';print_r($Datos);die('</pre>');
		if(count($Datos)==0)
			array_push($arrayVacio,0);
		else{
			$objPHPExcel -> setActiveSheetIndex(0)
						-> setCellValue('B'.$fila,$titulos[$i]);
			ColocaColor($objPHPExcel,'B',$fila,'010872',15);
			$fila += 1;
			$fila = colocaEncabezados($contratosEntregados,$fila,$objPHPExcel,$Datos);
			}
		
	}
	else if($body == 2){
		$contratosRechazados = array();
		array_push($contratosRechazados,'CONTRATO','CUENTA','DISTRIBUIDOR','FECHA ACTIVACIÓN','COMISIÓN POSIBLE','FECHA DE RECHAZO','FECHA DE VENCIMIENTO','DIAS TRANSCURRIDOS','MOTIVO DE RECHAZO');
		
		$campoEspecial=",cl_contrarecibos.fecha_hora,motivo_rechazo";
		$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
		$Rechazados = Datos('rechazados','12',$condicion,$campoEspecial,$leftUnionEspecial);
		$Datos = array();
		$posicion = 0;
		foreach($Rechazados as $dato){
			foreach($dato as $dat => $valor){
				if($dat == 'contrato'){
					$Datos[$posicion][0] = $valor;
				}elseif($dat == 'cuenta'){
					$Datos[$posicion][1] = $valor;
				}elseif($dat == 'distribuidor'){
					$Datos[$posicion][2] = $valor;
				}elseif($dat == 'fecha_activacion'){
					$Datos[$posicion][3] = $valor;
				}elseif($dat == 'comision'){
					$Datos[$posicion][4] = $valor;
				}elseif($dat == 'fecha_rechazo'){
					$Datos[$posicion][5] = $valor;
				}elseif($dat == 'fecha_vencimiento'){
					$Datos[$posicion][6] = $valor;
				}elseif($dat == 'dias_transcurridos'){
					$Datos[$posicion][7] = $valor;
				}
				elseif($dat == 'motivo_rechazo'){
					$Datos[$posicion][8] = $valor;
				}
			}
			$posicion += 1;
		}
		if(count($Datos)== 0)
			array_push($arrayVacio,0);
		else{
			$objPHPExcel -> setActiveSheetIndex(0)
						-> setCellValue('B'.$fila,$titulos[$i]);
			ColocaColor($objPHPExcel,'B',$fila,'010872',15);
			$fila += 1;
			$fila = colocaEncabezados($contratosRechazados,$fila,$objPHPExcel,$Datos);
		}
		
	}else if($body==3){
		$contratosReagendados=array();
		array_push($contratosReagendados,'CONTRATO','CUENTA','DISTRIBUIDOR','FECHA ACTIVACIÓN','COMISIÓN POSIBLE','FECHA DE RECHAZO SKY','CONTRA RECIBO','FECHA DE VENCIMIENTO','DIAS TRANSCURRIDOS','MOTIVO DE RECHAZO');		
		
		$campoEspecial=",cl_contrarecibos.id_contrarecibo,cl_contrarecibos.fecha_hora,motivo_rechazo";
		$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
		$Reagendado = Datos('reagendados','22',$condicion,$campoEspecial,$leftUnionEspecial);
		$Datos = array();
		foreach($Reagendado as $dato){
			$posicion = 0;
			foreach($dato as $dat => $valor){
				if($dat == 'contrato'){
					$Datos[$posicion][0] = $valor;
				}elseif($dat == 'cuenta'){
					$Datos[$posicion][1] = $valor;
				}elseif($dat == 'distribuidor'){
					$Datos[$posicion][2] = $valor;
				}elseif($dat == 'fecha_activacion'){
					$Datos[$posicion][3] = $valor;
				}elseif($dat == 'comision'){
					$Datos[$posicion][4] = $valor;
				}elseif($dat == 'fecha_rechazo'){
					$Datos[$posicion][5] = $valor;
				}elseif($dat == 'contrarecibo'){
					$Datos[$posicion][6] = $valor;
				}
				elseif($dat == 'fecha_vencimiento'){
					$Datos[$posicion][7] = $valor;
				}elseif($dat == 'dias_transcurridos'){
					$Datos[$posicion][8] = $valor;
				}
				elseif($dat == 'motivo_rechazo'){
					$Datos[$posicion][9] = $valor;
				}
			}
		}
		if(count($Datos)== 0)
			array_push($arrayVacio,0);
		else{
			$objPHPExcel -> setActiveSheetIndex(0)
						-> setCellValue('B'.$fila,$titulos[$i]);
			ColocaColor($objPHPExcel,'B',$fila,'010872',15);
			$fila += 1;
			$fila = colocaEncabezados($contratosReagendados,$fila,$objPHPExcel,$Datos);
		}
	}
	else if($body==4){
		$contratosMalaCalidad=array();
		array_push($contratosMalaCalidad,'CONTRATO','CUENTA','DISTRIBUIDOR','FECHA ACTIVACIÓN','COMISIÓN POSIBLE','CONTRA RECIBO','MOTIVO DE RECHAZO');
		
		$campoEspecial=",cl_contrarecibos.id_contrarecibo,cl_contrarecibos.fecha_hora,motivo_rechazo";
		$leftUnionEspecial=" LEFT JOIN cl_contrarecibos ON cl_control_contratos_detalles.id_contra_recibo=cl_contrarecibos.id_contrarecibo ";
		$MalaCalidad = Datos('malaCalidad','23',$condicion,$campoEspecial,$leftUnionEspecial);
		$Datos = array();
		$posicion = 0;
		foreach($MalaCalidad as $dato){
			foreach($dato as $dat => $valor){
				if($dat == 'contrato'){
					$Datos[$posicion][0] = $valor;
				}elseif($dat == 'cuenta'){
					$Datos[$posicion][1] = $valor;
				}elseif($dat == 'distribuidor'){
					$Datos[$posicion][2] = $valor;
				}elseif($dat == 'fecha_activacion'){
					$Datos[$posicion][3] = $valor;
				}elseif($dat == 'comision'){
					$Datos[$posicion][4] = $valor;
				}elseif($dat == 'contrarecibo'){
					$Datos[$posicion][5] = $valor;
				}elseif($dat == 'motivo_rechazo'){
					$Datos[$posicion][6] = $valor;
				}
			}
			$posicion += 1;
		}

		if(count($Datos)== 0)
			array_push($arrayVacio,0);
		else{
			$objPHPExcel -> setActiveSheetIndex(0)
						-> setCellValue('B'.$fila,$titulos[$i]);
			ColocaColor($objPHPExcel,'B',$fila,'010872',15);
			$fila += 1;
			$fila = colocaEncabezados($contratosMalaCalidad,$fila,$objPHPExcel,$Datos);
		}
	}
	else if($body==5){
		$contratosPorFacturar=array();
		array_push($contratosPorFacturar,'CONTRATO','CUENTA','DISTRIBUIDOR','FECHA ACTIVACIÓN','COMISIÓN POSIBLE');

		$PorFacturar = Datos('facturar','3',$condicion,'','');
		$Datos = array();
		$posicion = 0;
		foreach($PorFacturar as $dato){
			foreach($dato as $dat => $valor){
				if($dat == 'contrato'){
					$Datos[$posicion][0] = $valor;
				}elseif($dat == 'cuenta'){
					$Datos[$posicion][1] = $valor;
				}elseif($dat == 'distribuidor'){
					$Datos[$posicion][2] = $valor;
				}elseif($dat == 'fecha_activacion'){
					$Datos[$posicion][3] = $valor;
				}elseif($dat == 'comision'){
					$Datos[$posicion][4] = $valor;
				}
			}
			$posicion += 1;
		}
		if(count($Datos)== 0)
			array_push($arrayVacio,0);
		else{
			$objPHPExcel -> setActiveSheetIndex(0)
						-> setCellValue('B'.$fila,$titulos[$i]);
			ColocaColor($objPHPExcel,'B',$fila,'010872',15);
			$fila += 1;
			$fila = colocaEncabezados($contratosPorFacturar,$fila,$objPHPExcel,$Datos);
		}
		
	}
	else if($body==6){
		$contratosFacturados=array();
		if(count($contratosFacturados)== 0)
			array_push($arrayVacio,0);
		array_push($contratosFacturados,'CONTRATO','CUENTA','DISTRIBUIDOR','FECHA ACTIVACIÓN','COMISIÓN REAL','IVA','TOTAL','FOLIO FACTURA');
		
		$campoEspecial = ",ad_facturas.id_factura,FORMAT(ad_facturas_detalle.iva_monto,2) AS iva,FORMAT(ad_facturas_detalle.importe,2) AS total,FORMAT((ad_facturas_detalle.importe + ad_facturas_detalle.iva_monto),2) AS total_contrato";
		$leftUnionEspecial=" LEFT JOIN ad_facturas ON cl_control_contratos_detalles.id_control_factura_distribuidor = ad_facturas.id_control_factura 
			LEFT JOIN ad_facturas_detalle ON cl_control_contratos_detalles.id_control_factura_detalle_distribuidor = ad_facturas_detalle.id_control_factura_detalle";
		$Facturado = Datos('facturados','4',$condicion,$campoEspecial,$leftUnionEspecial);
		$Datos = array();
		$posicion = 0;
		//echo '<pre>';print_r($Facturado);die('</pre>');
		foreach($Facturado as $dato){
			foreach($dato as $dat => $valor){
				if($dat == 'contrato'){
					$Datos[$posicion][0] = $valor;
				}elseif($dat == 'cuenta'){
					$Datos[$posicion][1] = $valor;
				}elseif($dat == 'distribuidor'){
					$Datos[$posicion][2] = $valor;
				}elseif($dat == 'fecha_activacion'){
					$Datos[$posicion][3] = $valor;
				}elseif($dat == 'total'){
					$Datos[$posicion][4] = $valor;
				}elseif($dat == 'iva'){
					$Datos[$posicion][5] = $valor;
				}elseif($dat == 'total_contrato'){
					$Datos[$posicion][6] = $valor;
				}elseif($dat == 'folio'){
					$Datos[$posicion][7] = $valor;
				}
				
			}
			$posicion += 1;
		}
		if(count($Datos)== 0)
			array_push($arrayVacio,0);
		else{
			$objPHPExcel -> setActiveSheetIndex(0)
						-> setCellValue('B'.$fila,$titulos[$i]);
			ColocaColor($objPHPExcel,'B',$fila,'010872',15);
			$fila += 1;
			$fila = colocaEncabezados($contratosFacturados,$fila,$objPHPExcel,$Datos);
		}
	}
	$fila+=1;
}


for($i = 1; $i < 20; $i++){
	$columna = convertir_a_columna_excel($i+1);
	$objPHPExcel -> getActiveSheet() -> getColumnDimension($columna) -> setWidth(30);
}
$hoy = getdate();
$anio=$hoy['year'];
$mes=$hoy['mon'];
$dia=$hoy['mday'];

$fecha=$dia.'-'.$mes.'-'.$anio;

$objPHPExcel->getActiveSheet()->setTitle('CONTRATOS-'. $fecha);

if(count($arrayVacio) < 8){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Contratos.xls"');
	header('Cache-Control: max-age=0');
	header('Cache-Control: max-age=1');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header ('Cache-Control: cache, must-revalidate');
	header ('Pragma: public');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}else{
	//echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
}
function colocaEncabezados($arrayEncabezados,$fila,$objPHPExcel,$arrayDatos){
	foreach($arrayEncabezados as $encabezados=>$encabezado){
		$columna=convertir_a_columna_excel(($encabezados+2));
		ColocaDato($objPHPExcel,$columna,$fila,$encabezado);
		ColocaBorde($objPHPExcel,$columna,$fila);
		ColocaColor($objPHPExcel,$columna,$fila,'FFFFFF',12);
		Relleno($objPHPExcel,$columna,$fila,'010872');
		
	}
	$fila+=1;
	
	foreach($arrayDatos as $datos){
			foreach($arrayEncabezados as $encabezados=>$encabezado){
			foreach($datos as $posicion=>$dato){
				if($posicion==$encabezados){
					$columna=convertir_a_columna_excel(($encabezados+2));
					ColocaDato($objPHPExcel,$columna,$fila,$dato);
					ColocaBorde($objPHPExcel,$columna,$fila);
					AlinearDerecha($objPHPExcel,$columna,$fila);
					FormatoNumerico($objPHPExcel,$columna,$fila);
				}
			}
		}
		$fila+=1;
	}
	return $fila;
}
function convertir_a_columna_excel($numero_columna) {
	return strtoupper(chr($numero_columna + 96));
}
function ajustarTamaño($objPHPExcel,$columna) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
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

function Datos($caso,$accion,$condicion,$campoEspecial,$leftUnion){
	$aDatos = array();
	$sql = "SELECT CONCAT_WS(' ',ad_clientes.nombre,ad_clientes.apellido_paterno,ad_clientes.apellido_paterno) AS distribuidor,ad_clientes.id_cliente,id_detalle,cl_control_contratos.id_control_contrato,contrato,cl_control_contratos.cuenta,fecha_activacion,
	CONCAT('$',FORMAT(IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, IF(cl_importacion_caja_comisiones.t46 NOT IN ('TMK01','TMKOM','BCRCOMV','BCRCOM'),(cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion),cl_importacion_caja_comisiones.dc30),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32 - cl_control_contratos_detalles.precio_suscripcion),
						IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, IF(cl_importacion_caja_comisiones.t46 NOT IN ('TMK01','TMKOM','BCRCOMV','BCRCOM'), (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion), cl_importacion_caja_comisiones.dc31),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33 - cl_control_contratos_detalles.precio_suscripcion),
								IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34 - cl_control_contratos_detalles.precio_suscripcion),
									IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35 - cl_control_contratos_detalles.precio_suscripcion), 0)
								)
							)
						)
					)
				),2)) as comision,DATEDIFF(NOW(),fecha_activacion) AS dias_transcurridos". $campoEspecial ."
	FROM cl_control_contratos 
	LEFT JOIN cl_control_contratos_detalles 
	ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato 
	LEFT JOIN ad_clientes
	ON cl_control_contratos_detalles.id_cliente=ad_clientes.id_cliente
	LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	LEFT JOIN cl_importacion_caja_comisiones
	ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
	". $leftUnion ."
	WHERE cl_control_contratos_detalles.id_accion_contrato IN(".$accion.")
	AND cl_control_contratos_detalles.activo=1 
	AND ultimo_movimiento = 1
	AND principal = 1 AND (".$condicion.");";
        
//        echo "<br>";
        
	$result = mysql_query($sql) or die($sql);
	$indice=0;
	while($datos=mysql_fetch_array($result)){
            
//            print_r($datos);
            
		$aDatos[$indice]['distribuidor'] = $datos['distribuidor'];
		$aDatos[$indice]['id_distribuidor'] = $datos['id_cliente'];
		$aDatos[$indice]['id_detalle']=$datos['id_detalle'];
		$aDatos[$indice]['id_control_contrato'] = $datos['id_control_contrato'];
		$aDatos[$indice]['contrato'] = $datos['contrato'];
		$aDatos[$indice]['cuenta'] = $datos['cuenta'];
		$aDatos[$indice]['fecha_activacion'] = $datos['fecha_activacion'];
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
		
		$sqlRango="
			SELECT dia_final FROM cl_rangos_contratos 
			WHERE id_rango=2
		";
		$resultRango=mysql_query($sqlRango);
		$nuevafecha = strtotime ( '+'.mysql_result($resultRango,0).' day' , strtotime ( $fecha_activacion ) ) ;

//                print_r(mysql_result($resultRango,0));
                
//		if($aDatos[$indice]['dias_transcurridos']>mysql_result($resultRango,0)){
//                        
//                        
//			$aDatos[$indice]['comision'] = '$' . number_format(0,2);	
//		}
		
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		$aDatos[$indice]['fecha_vencimiento'] = $nuevafecha;
		
		$sqlColor="
			SELECT codigo_color FROM cl_rangos_contratos 
			LEFT JOIN cl_colores
			ON cl_rangos_contratos.id_color=cl_colores.id_color
			WHERE  ". $datos['dias_transcurridos'] ." BETWEEN dia_inicial AND dia_final 
			GROUP BY codigo_color";
		$resultColor = mysql_query($sqlColor);
		
		
		$aDatos[$indice]['codigo_color'] = mysql_result($resultColor,0);
		if($accion == '4'){
			$aDatos[$indice]['total_contrato'] = $datos['total_contrato'];
		}
		$indice+=1;
	}
//	echo '<pre>';print_r($aDatos);echo '</pre>';
	return $aDatos;
}
?>