<?php
	//Ajax de cancelacion de documentos
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	if($tipodocumento==1)//Documento tipo 1
	{
		$num=1;
	}	
	else
	{
		$num=1;
	}
	
	die("exito|".$num);
?>