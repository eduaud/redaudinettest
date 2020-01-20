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

	
	$strConsulta="SELECT id_tipo_almacen FROM rac_almacenes WHERE id_almacen=" . $id_almacen;
	
	$resultado=mysql_query($strConsulta) or die("Consulta:\n$strConsulta\n\nDescripcion:\n".mysql_error());
	$num=mysql_num_rows($resultado);
	echo "exito";
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($resultado);
		echo "|";
		for($j=0;$j<sizeof($row);$j++)
		{	
			if($j > 0)
				echo "~";
			echo utf8_encode($row[$j]);
		}	
	}
		
	
	
	

	
	
?>