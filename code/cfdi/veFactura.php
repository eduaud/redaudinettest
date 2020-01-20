<?php

	extract($_GET);
	
	//$ruta=dirname(__FILE__);
	        
	$ruta.="/var/www/vhosts/sysandweb.com/ci/CFD/Facturas/F".$fact.".xml";
		
	
	if(file_exists($ruta))
	{			
		//header("Content-Type", "text/html;");
		header('Content-Type: text/html charset=utf-8');
		$nombreArchivo='F'.$fact.'.xml';
		
		header('content-disposition: attachment; filename='.$nombreArchivo);
    	
		readfile($ruta);
		
		
	}
	else
		die("No se pudo abrir el archivo");


		
		/*
		

	if(substr($f,0,3)!='tmp' or strpos($f,'/') or strpos($f,'\\'))
    	die('Nombre de archivo incorrecto');
	if(!file_exists($f))
    	die('El archivo no existe');
	if($HTTP_SERVER_VARS['HTTP_USER_AGENT']=='contype'){
		Header('Content-Type: application/pdf');
		exit;
	}
	Header('Content-Type: application/pdf');
	Header('Content-Length: '.filesize($f));
	readfile($f);
	unlink($f);
	exit;
		*/
?>

