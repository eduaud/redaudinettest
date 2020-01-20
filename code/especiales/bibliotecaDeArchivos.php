<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

function icono_imagen($extencion){
	$icono = "../../imagenes/format_desconocido.gif";
	
	if($extencion == "txt" || $extencion == "TXT" || $extencion == "csv" || $extencion == "CSV"){
		$icono = "../../imagenes/format_txt.gif";
	} elseif($extencion == "xls" || $extencion == "XLS" || $extencion == "xlsx" || $extencion == "XLSX"){
		$icono = "../../imagenes/format_xls.gif";
	} elseif($extencion == "doc" || $extencion == "DOC" || $extencion == "docx" || $extencion == "DOCX"){
		$icono = "../../imagenes/format_doc.gif";
	} elseif($extencion == "jpg" || $extencion == "JPG" || $extencion == "gif" || $extencion == "GIF" || $extencion == "png" || $extencion == "PNG"){
		$icono = "../../imagenes/format_jpg.gif";
	} elseif($extencion == "mov" || $extencion == "MOV" || $extencion == "mp4" || $extencion == "MP4"){
		$icono = "../../imagenes/format_mov.gif";
	} elseif($extencion == "mp3" || $extencion == "MP3"){
		$icono = "../../imagenes/format_mp3.gif";
	} elseif($extencion == "pdf" || $extencion == "PDF"){
		$icono = "../../imagenes/format_pdf.gif";
	} elseif($extencion == "ppt" || $extencion == "PPT"){
		$icono = "../../imagenes/format_ppt.gif";
	} elseif($extencion == "zip" || $extencion == "ZIP" || $extencion == "rar" || $extencion == "RAR"){
		$icono = "../../imagenes/format_zip.gif";
	}
	
	return $icono;
}

$directorio = "../../../Biblioteca_de_Archivos_Audinet/";
$archivos = array_diff(scandir($directorio), array('..', '.'));
$num_archivos = count($archivos);

$arr_archivos = array();

if($num_archivos > 0){
	for($i = 2; $i < ($num_archivos + 2); $i++){
		$pos = strrpos($archivos[$i], ".");
		$extencion = substr($archivos[$i], ($pos + 1));
		$img = icono_imagen($extencion);
		
		$arr_archivos[$i - 2][0] = $archivos[$i];
		$arr_archivos[$i - 2][1] = $img;
	}
}

$smarty->assign("arr_archivos",$arr_archivos);
$smarty->assign("directorio",$directorio);
$smarty -> display("especiales/bibliotecaDeArchivos.tpl");
?>