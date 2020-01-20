<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	if($tipo=='0')
	{
		$strConsulta="SELECT DISTINCT id_almacen, nombre FROM spa_almacenes WHERE   id_almacen<> ".$idAlamacen." AND activo=1 ";
	}
	else
	{
		$strConsulta="SELECT DISTINCT id_almacen, nombre FROM spa_almacenes WHERE   activo=1 AND id_almacen IN (
									SELECT id_almacen_productos FROM sys_sucursales WHERE id_sucursal=".$id_suc." 
									UNION ALL
									SELECT  id_almacen_atencion FROM sys_sucursales WHERE id_sucursal=".$id_suc."
									
									)  ";
	
	}
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
		$num=mysql_num_rows($res);
		echo "exito";
		echo "|".$num;
		for($i=0;$i<$num;$i++)
		{
			echo "|";
			$row=mysql_fetch_row($res);
			echo utf8_encode($row[0]."~".$row[1]);
		}
		mysql_free_result($res);
	
	
?>