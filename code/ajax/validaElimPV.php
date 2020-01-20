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
	
	if($tipo == 1)
	{
	
		$sql="SELECT CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) FROM peug_clientes_usuarios_web WHERE id_tipo_cliente=2 AND id_punto_venta=$id_punto_venta";
		
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		
		$num=mysql_num_rows($res);	
		
		if($num <= 0)	
			die("exito|¿Desea eliminar el registro?");
			
		echo "exito|Este punto de venta cuenta con los siguientes usuarios:\n\n";	
		
		for($i=0;$i<=$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo $row[0]."\n";
		}
		
		echo "\n¿Desea eliminar el punto de venta?";
	}
	if($tipo == 2)
	{
		$sql="UPDATE peug_clientes_usuarios_web SET id_punto_venta=1, activo=0 WHERE id_punto_venta=$id_punto_venta";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		die("exito");
	}
	
?>