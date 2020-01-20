<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	//opcion=1&id_articulo="+id_articulo+"&codigo_articulo
	
	if($opcion=='1')
	{
		$strConsulta="SELECT id_producto, sku,nombre FROM na_productos where  sku ='".$codigo_articulo."'";
	}
	
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
	$num=mysql_num_rows($res);
	echo "exito|$num";
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo "|".$row[0]."|".$row[1]."|".$row[2];
	}
	mysql_free_result($res);
	
	
?>