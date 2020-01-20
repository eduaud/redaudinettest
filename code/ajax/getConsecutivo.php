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
	
	$letras=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');	
	
	
	
	$sql="SELECT letra FROM peug_boletines WHERE anio=$anio AND cosecutivo=$consc AND letra <> '' AND id_categoria=$categoria ORDER BY id_control_boletin DESC LIMIT 1";	
	$res=mysql_query($sql) or die("Error en \n$sql\n\nDescripcion:\n".mysql_error());
	
	if(mysql_num_rows($res) > 0)
	{	
		$row=mysql_fetch_row($res);
		$id=in_array($row[0], $letras);
		$letra=$letras[$id];
	}
	else
		$letra="A";	
	die("exito|$letra");	
	
?>