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
	include("../../../conect.php");
	

	$sql="SELECT prefijo FROM sys_sucursales WHERE id_sucursal = ".$id;	
	$res=mysql_query($sql) or die("Error en \n$sql\n\nDescripcion:\n".mysql_error());
	
	if(mysql_num_rows($res) > 0)
	{	
		$row=mysql_fetch_row($res);
		$prefijo = $row[0];
		
	}
	else
		$prefijo="";	
		
	die("exito|$prefijo");	
	
?>