<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");


	$strConsulta="SELECT id_servicio, 
						nombre, 
						precio_publico, 
						id_tipo_servicio, 
						tiempo_estimado,
						porc_descuento_adicional, 
						porc_comision_atencion, 
						activo FROM spa_servicios WHERE id_servicio=".$id."";
	
	//echo $strConsulta;
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode($row[0]."|".$row[1]."|".$row[2]."|".$row[3]."|".$row[4]."|".$row[5]."|".$row[6]);
	}
	
?>