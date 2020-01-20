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

	$IDsucursal =  $_SESSION["USR"]->sucursalid;
	
	$sql2 = "SELECT id_ruta, fecha_y_hora
			FROM anderp_notas_venta 
			WHERE id_sucursal = ".$IDsucursal. " ORDER BY fecha_y_hora DESC LIMIT 1";
	//echo $sql2;
	$res2=mysql_query($sql2) or die("Error en:\n$sql2\n\nDescripcion:".mysql_error());
	$num2=mysql_num_rows($res2);
	if($num2 >0){
	  $row2 = mysql_fetch_array($res2);
	  $ruta = $row2[0]; 
	   echo "exito|$ruta|";
	}
	else
	{
		 echo "no_existe|0|";
	}
  

?>