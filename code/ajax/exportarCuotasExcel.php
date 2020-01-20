<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
include("../../code/general/funcionesParaExportacionImportacion.php");

extract($_GET);
extract($_POST);
$sqlMes="SELECT nombre FROM sys_meses WHERE id_mes=".$mes;
$datosMeses = new consultarTabla($sqlMes);
$meses = $datosMeses -> obtenerArregloRegistros();

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('america/mexico_city');
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
			->setCellValue('D3','CUOTAS DE '.strtoupper($meses[0][0]).' '.$anio);
ColocaColor($objPHPExcel,'D',3,'010872',17);
AlinearCentro($objPHPExcel,'D',3);
			
				
$objPHPExcel->getProperties()->setCreator("Audicel")
							 ->setLastModifiedBy("Audicel")
							 ->setTitle("Sistema Audicel")
							 ->setSubject("Reporte de Cuotas")
							 ->setDescription("Reporte de Cuotas")
							 ->setKeywords("Reporte de Cuotas")
							 ->setCategory("Cuotas");
	
						 
$titulos=array();
array_push($titulos,'CLAVE PLAZA','CLAVE DISTRIBUIDOR','TRADICIONAL SD','TRADICIONAL HD','VETV 1','VETV 2','TOTAL');
$fila=6;

if($mes=='1')
	$campos='dc16,dc17,dc18,dc19,dc20';
elseif($mes=='2')
	$campos='dc21,dc22,dc23,dc24,dc25';
elseif($mes=='3')
	$campos='dc26,dc27,dc28,dc29,dc30';
elseif($mes=='4')
	$campos='dc31,dc32,dc33,dc34,dc35';
elseif($mes=='5')
	$campos='dc36,dc37,dc38,dc39,dc40';
elseif($mes=='6')
	$campos='dc41,dc42,dc43,dc44,dc45';
elseif($mes=='7')
	$campos='dc46,dc47,dc48,dc49,dc50';
elseif($mes=='8')
	$campos='dc51,dc52,dc53,dc54,dc55';
elseif($mes=='9')
	$campos='dc56,dc57,dc58,dc59,dc60';
elseif($mes=='10')
	$campos='dc61,dc62,dc63,dc64,dc65';
elseif($mes=='11')
	$campos='dc66,dc67,dc68,dc69,dc70';
elseif($mes=='12')
	$campos='dc71,dc72,dc73,dc74,dc75';

if(isset($sucursal)){
	$condicion=" AND id_sucursal=".$sucursal;
}
else 
	$condicion="";
$campos="t86,t87,t88,".$campos;
$tabla="cl_importacion_cuotas";
$leftJoin="";
$IDLeft="";
$where="id_anio='".$anio."'".$condicion;
$Pendientes=obtenDatos($campos,$tabla,$leftJoin,$IDLeft,$where);
$fila=colocaEncabezados($titulos,$fila,$objPHPExcel,$Pendientes);
/*echo '<pre>';
print_r($Pendientes);
echo '</pre>';
die();*/
for($i=1;$i<20;$i++){
	$columna=convertir_a_columna_excel($i+1);
	$objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setWidth(30);
}
$hoy = getdate();
$anio=$hoy['year'];
$mes=$hoy['mon'];
$dia=$hoy['mday'];

$fecha=$dia.'-'.$mes.'-'.$anio;
$objPHPExcel->getActiveSheet()->setTitle('Cuotas-'.$fecha);
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
?>