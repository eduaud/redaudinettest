<?php

		php_track_vars;

	/*Conseguimos los datos del post
	
		tabla -> Nombre de la tabla que se esta accediendo
		id -> id del campo a utilizar
		accion -> accion a realizar, los valores pueden ser: [1 => Modificar, 2 => Ver, 3 => Eliminar o Cancelar]
	*/
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	
	
	$sql="SELECT ruta FROM peug_boletines_archivos WHERE id_control=$id_control";
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
	$num=mysql_num_rows($res);
	if($num <= 0)
		die("No se encontro el archivo especificado");
	$row=mysql_fetch_row($res);	
	if(file_exists($row[0]))	
		unlink($row[0]);
		
	$sql="DELETE FROM peug_boletines_archivos WHERE id_control=$id_control";
	mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  	
	
	die("exito");
	
?>