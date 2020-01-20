<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	//  -  id
	
	if($opcion=='1')
	{
		$strConsulta="SELECT nombre, siglas FROM rac_articulos_categorias where  id_categoria_articulo ='".$id."'";
	}
	elseif($opcion=='2')
	{
		$strConsulta="SELECT nombre, siglas FROM rac_articulos_subcategorias  where   id_subcategoria_articulo ='".$id."'";
	}
	elseif($opcion=='3')
	{
		$strConsulta="SELECT nombre, siglas FROM rac_articulos_caracteristicas where id_caracteristica ='".$id."'";
	}
	
	
	
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
	$num=mysql_num_rows($res);
	echo "exito";
	for($i=0;$i<$num;$i++)
	{
		
		$row=mysql_fetch_row($res);
		echo "|".$row[0]."|".$row[1];
	}
	mysql_free_result($res);
	
	
?>