<?php

	php_track_vars;

	extract($_GET);
	extract($_POST);	
	
	/*
	$ruta = 'C:/ServidorWeb_S&W/newart/';
	
	
	include($ruta.'config.inc.php');
	//variables declaracion
	include(INCLUDEPATH."container.inc.php");
	include(INCLUDEPATH."module_exec.inc.php");	*/
	include("../../conect.php");
	//$prueba = "";
	//Buscamos si hay query
	$sql="SELECT query FROM sys_grid WHERE id_grid=$id_grid AND CHAR_LENGTH(query) > 0";	
	//$prueba .= "|" . $sql;
	
	if($id_grid==1000)
	{
		//necesitamos saber quien manda los datos del grid	
	}
	
	
	$res=mysql_query($sql);
	if(!$res)
		die("Error en:\n$sql\n\nDescripción:\n".mysql_error());
	$num=mysql_num_rows($res);	
	
	$strConsulta="SELECT borrado_logico, campo_borrado_logico FROM sys_grid WHERE id_grid=".$id_grid;
	//$prueba .= "|" . $sql;
	$resp=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripci&oacute;n:\n".mysql_error());
	$row=mysql_fetch_assoc($resp);
	extract($row);
	
	if(!$num)
	{	
		$sql="SELECT campo_tabla FROM `sys_grid_detalle` WHERE id_grid=$id_grid ORDER BY orden";
		//$prueba .= "|" . $sql;
		$res=mysql_query($sql);
		if(!$res)
			die("Error en:\n$sql\n\nDescripción:\n".mysql_error());
		$num=mysql_num_rows($res);
		$aux="";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			if($i > 0)
				$aux.=",";
			$aux.=$row[0];	
		}
		$sql="SELECT $aux FROM $tabla WHERE $campoid='$llave'";
				
	}
	else
	{
		$row=mysql_fetch_row($res);
		if(!isset($llave) || $llave == '' || $llave == NULL)			
			$sql=$sql=str_replace('$ID', '-1', $row[0]);
		else	
			$sql=str_replace('$ID', $llave, $row[0]);	
			$sql = str_replace('$SUCURSAL', $_SESSION["USR"]->sucursalid,$sql);	
			
	}
	if($borrado_logico==1)
			$sql.=" AND ".$campo_borrado_logico."=1";
	//die($sql);
	//echo $sql."</br>";
	//$prueba .= "|" . $sql;
	$res=mysql_query($sql);
	if(!$res)
		die("Error en:\n$sql\n\nDescripción:\n".mysql_error());
	echo "exito";
	$num=mysql_num_rows($res);	
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo "|";
		for($j=0;$j<sizeof($row);$j++)	
		{
			if($j > 0)
				echo "~";
			echo utf8_encode($row[$j]);	
		}
	}
	
	//echo $prueba;
	

?>