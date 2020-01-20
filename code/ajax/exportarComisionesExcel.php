<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");
include("../../code/general/funcionesParaExportacionImportacion.php");
extract($_GET);
extract($_POST);
$csv="";
$csv_end="\n";	 
$csv_file="Comisiones.csv";
$titulos=ObtenerTitulos('20');
$titulos=implode(',',$titulos);
$campos=ObtenerCamposPorLayout('20');
$campos=implode(',',$campos);

$csv.=$titulos.$csv_end;

$tabla="cl_importacion_caja_comisiones";
$leftJoin="";
$IDLeft="";
$where=$datos;
$Pendientes=obtenDatos($campos,$tabla,$leftJoin,$IDLeft,$where);
$archivo=ConvierteLinea($Pendientes,$csv);
$temp = tmpfile();
fwrite($temp, $archivo);
fseek($temp, 0);
echo fread($temp, 1024);
// obtener contenido del archivo como un string
$output = stream_get_contents($temp);
// cerrar archivo
fclose($temp);
// cabeceras HTTP:
// tipo de archivo y codificación
Descargar('Comisiones',$output);
?>