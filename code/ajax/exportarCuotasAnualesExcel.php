<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
include("../../code/general/funcionesParaExportacionImportacion.php");

extract($_GET);
extract($_POST);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('america/mexico_city');
if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');
require('../../include/phpexcel/Classes/PHPExcel.php');
$objPHPExcel = new PHPExcel();			
$objPHPExcel->getProperties()->setCreator("Audicel")
							 ->setLastModifiedBy("Audicel")
							 ->setTitle("Sistema Audicel")
							 ->setSubject("Reporte de Cuotas")
							 ->setDescription("Reporte de Cuotas")
							 ->setKeywords("Reporte de Cuotas")
							 ->setCategory("Cuotas");
	
$fila=1;		 
$titulos=array();
array_push($titulos,'Clave Plaza','Clave Distribuidor','');
for($i=0;$i<12;$i++){
	array_push($titulos,'Tradicional SD','Tradicional HD','VETV 1','VETV 2','Total');
}
$campos='dc16,dc17,dc18,dc19,dc20,dc21,dc22,dc23,dc24,dc25,dc26,dc27,dc28,dc29,dc30,dc31,dc32,dc33,dc34,dc35,dc36,dc37,dc38,dc39,dc40,dc41,dc42,dc43,dc44,dc45,dc46,dc47,dc48,dc49,dc50,dc51,dc52,dc53,dc54,dc55,dc56,dc57,dc58,dc59,dc60,dc61,dc62,dc63,dc64,dc65,dc66,dc67,dc68,dc69,dc70,dc71,dc72,dc73,dc74,dc75';


$campos="t86,t87,t88,".$campos;
$tabla="cl_importacion_cuotas";
$leftJoin="";
$IDLeft="";
$where="id_anio='".$anio."'";
$Pendientes=obtenDatos($campos,$tabla,$leftJoin,$IDLeft,$where);
$fila=colocaEncabezados($titulos,$fila,$objPHPExcel,$Pendientes);

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