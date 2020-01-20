<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_cxp = $_GET['cxp'];
$caso = $_GET['caso'];
$timeArchivosCxP = time();

$sql = "SELECT referencia_xml, nombre_xml, nombre_pdf, referencia_pdf FROM ad_cuentas_por_pagar_operadora WHERE id_cuenta_por_pagar = " . $id_cxp;
$result = new consultarTabla($sql);
$archivo = $result -> obtenerLineaRegistro();

if($caso == 1){
		$descarga = $archivo['referencia_xml'];
		$nombre = $archivo['nombre_xml'];
		}
else if($caso == 2){
		$descarga = $archivo['referencia_pdf'];
		$nombre = "PDF_" . $timeArchivosCxP . ".pdf";
		}

if(file_exists($descarga)){			
		header('Content-Type: text/xml charset=utf-8');
		header('content-disposition: attachment; filename=' . $nombre);
    	readfile($descarga);
	}
else{
		die("Sin archivo registrado");
	}

?>