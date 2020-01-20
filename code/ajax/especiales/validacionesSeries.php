<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");

if($accion==1){

if($id_serie != '') $condicion = 	" AND id_serie <> ".$id_serie;
	$strConsulta="SELECT nombre FROM anderp_series WHERE activo = 1 AND nombre = '".$descrip."'".$condicion;
	//echo $strConsulta;

	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	
	echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]);
	}
	
}//fin accion 1


if($accion==2){
	

if($id_serie != '') $condicion = " AND id_serie <> ".$id_serie;
else  $condicion = " AND id_sucursal = ".$idSucursal;
	$strConsulta="SELECT nombre FROM anderp_series WHERE activo = 1 AND id_sucursal =".$_SESSION["USR"]->sucursalid." ".$condicion;
//	echo $strConsulta;

	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	
	echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo utf8_encode("|".$row[0]);
	}
	
}//fin accion 2

	
?>