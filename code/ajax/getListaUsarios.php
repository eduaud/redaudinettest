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
	
	
	$sql="SELECT
	      CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno)
		  FROM peug_clientes_usuarios_web
		  WHERE id_punto_venta=$id_pv
		  AND id_tipo_cliente=2
		  AND activo=1";
	$res=mysql_query($sql) or die("Error en \n$sql\n\nDescripcion:\n".mysql_error());
	$num=mysql_num_rows($res);
	if($num <= 0)
		die("SI");
	else
		echo "NO|Los siguientes usuarios estan ligados a este punto de venta, por lo que serán desactivados:\n\n";	
		
	for($i=0;$i<$num;$i++)	
	{
		$row=mysql_fetch_row($res);
		echo $row[0]."\n";
	}
	
	echo "\n\n¿Desea continuar con esta operación?";	
	
?>