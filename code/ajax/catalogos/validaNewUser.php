<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");

if($accion==1){


//$strConsulta="SELECT login FROM sys_usuarios_pfmx WHERE activo = 1 AND id_sucursal = ".$sucursal." AND login = '".$nlogin."' ";
if($actual != '') $condicion = ' AND   id_usuario <> '.$actual;
$strConsulta="SELECT login FROM sys_usuarios WHERE login = '".$nlogin."' ".$condicion;
	//echo $strConsulta;

	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($res);
	
	if($num>0){
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("Dos usuarios no pueden tener el mismo login,Verifique.");
		}
	}
    else{ 
	   echo "exito|".$num;
	}
}//fin accion 1


	
?>