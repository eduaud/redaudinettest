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

	
	$strConsulta="SELECT vendedores_sucursal,asignar_vendedor FROM sys_grupos WHERE id_grupo=" . $per;
	
	$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
	$row=mysql_fetch_row($resultado);
	echo utf8_encode($row[0]."|".$row[1]);
	//""ho mundo"
	
?>