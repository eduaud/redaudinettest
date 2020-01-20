<?php

	php_track_vars;

	
	extract($_GET);
	extract($_POST);
	
	//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	

if($accion == 1){
 /*Valida si la ciudad ya existe en la base
 *****/
   $IdSucursal = $_SESSION["USR"]->sucursalid;
   $sql = "SELECT  id_ciudad,nombre 
   FROM anderp_ciudades 
   WHERE id_estado=".$id_estado." AND nombre='".$name_ciu."' AND id_ciudad <> ".$id_ciu;
   
   
   $res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripción:\n".mysql_error());	  
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		echo utf8_decode ("exito|Ya Existe la Ciudad en el Catálogo,Verifique.");
	}
	else{
	   echo utf8_decode("No existe este producto.");
	}
   
 
}	

?>