<?php
	//header("Content-type: application/pdf");
	//librerias de funcion	
	include("../../include/fpdf153/nclasepdf.php");	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../general/funciones.php");
	
	$mesNombre=array(
						1=>'Enero',
						2=>'Febrero',
						3=>'Marzo',
						4=>'Abril',
						5=>'Mayo',
						6=>'Junio',
						7=>'Julio',
						8=>'Agosto',
						9=>'Septiembre',
						10=>'Octubre',
						11=>'Noviembre',
						12=>'Diciembre'						
					);
	$impDoc=0;
	$fpdf=true;
	extract($_GET);
	extract($_POST);
	
	//die($Devolucion);
	
	if($tipo == "PEDIDO")
	{
		$orient_doc="P";
		$unid_doc="cm";
		$alto_doc=28;
		$ancho_doc=21.5;
		$ftam=9;
		$tamano_doc=array($ancho_doc,$alto_doc);
		$ypag=28;
		$impDoc++;
		include("pedido.php");
		$tipoimpresion="Factura No.".$idfactura; 
	}
	
	if($tipo == "COTIZACION")
	{
		$orient_doc="P";
		$unid_doc="cm";
		$alto_doc=28;
		$ancho_doc=21.5;
		$ftam=9;
		$tamano_doc=array($ancho_doc,$alto_doc);
		$ypag=28;
		$impDoc++;
		include("cotizacion.php");
		$tipoimpresion="Factura No.".$idfactura; 
	}
	
	if($tipo == "ORDEN")
	{
		$orient_doc="P";
		$unid_doc="cm";
		$alto_doc=28;
		$ancho_doc=21.5;
		$ftam=9;
		$tamano_doc=array($ancho_doc,$alto_doc);
		$ypag=28;
		$impDoc++;
		include("orden.php");
		$tipoimpresion="Factura No.".$idfactura; 
	}
	
	if($tipo == "GENERA_REPORTE"){
		
		$orient_doc="P";
		$unid_doc="cm";
		$alto_doc=28;
		$ancho_doc=21.5;
		$ftam=9;
		$tamano_doc=array($ancho_doc,$alto_doc);
		$ypag=28;
		$impDoc++;
		
		//include("generaReporteConsultaPresurtido.php");
		include("testPdfTabla.php");
		$tipoimpresion="Orden de Servicio:  ".$id_control_orden_servicio; 
	}
	
	if($Factura == "SI")
	{
		$orient_doc="P";
		$unid_doc="cm";
		$alto_doc=28;
		$ancho_doc=21.5;
		$ftam=9;
		$tamano_doc=array($ancho_doc,$alto_doc);
		$ypag=28;
		$impDoc++;
		include("factura.php");
		$tipoimpresion="Factura No.".$idfactura;
	}
	
	if($NC == "SI")
	{
		$orient_doc="P";
		$unid_doc="cm";
		$alto_doc=28;
		$ancho_doc=21.5;
		$ftam=9;
		$tamano_doc=array($ancho_doc,$alto_doc);
		$ypag=28;
		$impDoc++;
		include("nc.php");
		$tipoimpresion="Nota de Credito No.".$idfactura;
	}
	
	
	if($impDoc>0)
	{
		//grabaBitacora('8','','0','0',$_SESSION["USR"]->userid,'0',$tipoimpresion,'');
		//Determina el nombre del archivo temporal
		$file=basename(tempnam(getcwd(),'tmp'));
		//Salva elPDF en un archivo
		$pdf->Output($file);
		//Redireccionamiento por Javascript
		echo "<HTML><SCRIPT>document.location='getpdf.php?f=$file';</SCRIPT></HTML>";
	}/**/
	
	
	if(file_exists("FAC_$folio.png"))
		unlink("FAC_$folio.png");
	
?>