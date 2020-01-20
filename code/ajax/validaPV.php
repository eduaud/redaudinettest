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
	
	
	$sql="SELECT COUNT(1) FROM peug_puntos_venta WHERE id_gerente_regional_usr=$id_usuario";
	$res=mysql_query($sql) or die("Error en \n$sql\n\nDescripcion:\n".mysql_error());
	$row=mysql_fetch_row($res);
	if($row[0] > 0)
		die("No es posible realizar este cambio, debido a que hay puntos de vente relacionados.");
		
	die("exito");	
	
?>