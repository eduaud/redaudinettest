<?php

	php_track_vars;

	
	extract($_GET);
	extract($_POST);
	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	

if($accion == 1){ //valida si la ruta existe	
	  
	$sql="SELECT
	      a.id_ruta,
		  a.nombre,
		  b.nombre as 'sucur'
		  FROM anderp_rutas a
		  LEFT JOIN sys_sucursales b ON b.id_sucursal=a.id_sucursal 
		  WHERE a.activo=1 AND a.nombre = '".$name_ruta."' AND a.id_ruta <> ".$ruta_sel; 

	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		die("exito|Ya Existe el Nombre de Ruta para la Sucursal ($row[2]),Verifique.");
	}

   
	$sql="SELECT
	      a.id_ruta,
		  a.nombre,
		  b.nombre as 'sucur'
		  FROM anderp_rutas a
		  LEFT JOIN sys_sucursales b ON b.id_sucursal=a.id_sucursal 
		  WHERE a.activo=1 AND a.prefijo = '".$prefijo."' AND id_ruta <> ".$ruta_sel;
		  
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		die("exito|Ya Existe el Prefijo para la Ruta ($row[1]) de la Sucursal ($row[2]),Verifique.");
	}
	
		
		
}//fin if accion 1

if($accion == 2){
  
  	$sql="SELECT
	      a.id_ruta,
		  a.nombre,
		  b.nombre as 'sucur'
		  FROM anderp_rutas a
		  LEFT JOIN sys_sucursales b ON b.id_sucursal=a.id_sucursal 
		  LEFT JOIN anderp_vendedores c ON c.id_vendedor=a.id_vendedor AND c.activo=1
		  WHERE a.activo=1 AND c.activo=1 AND a.id_vendedor = '".$id_vende."' AND id_ruta <> ".$ruta_sel;
		  
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		die("exito|El Vendedor seleccionado ya esta Asignado a la Ruta ($row[1]) de la Sucursal ($row[2]),Verifique.");
	}
	
}
	


?>