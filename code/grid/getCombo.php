<?php

	php_track_vars;

	extract($_GET);
	extract($_POST);
	
	include("../../conect.php");
	
	$sql="SELECT datosdb, tipo, depende, id_grid,valor_inicial FROM `sys_grid_detalle` WHERE id_grid_detalle='$id'";
	$res=mysql_query($sql) or die("Error al obtener detalle del grid");//en:\n$sql\n\nDescripcion:\n".mysql_error());
	$row=mysql_fetch_row($res);
	$tipo=$row[1];
	$valor_inicial=$row[4];
	$sql=str_replace('$LLAVE',$llave,$row[0]);
	

	if(isset($nodependencias))
	{
		for($i=0;$i<$nodependencias;$i++)
		{
			$nomDep="llaveaux".$i;
			//die('$_LLAVE'.$i." - ".$$nomDep);
			$sql=str_replace('$_LLAVE'.$i,$$nomDep,$sql);
		}	
	}

	//reemplazamos el valor auxiliar $VAR2
	
	
	
	

	if(isset($llaveaux))
	{
		$strConsulta="SELECT campo_tabla,display FROM sys_grid_detalle WHERE orden='".$row[2]."' AND id_grid='".$row[3]."'";
		$res=mysql_query($strConsulta) or die("Error al obtener campos del grid");// en:\n$strConsulta\n\nDescripcion:\n".mysql_error());
		$rowDep=mysql_fetch_row($res);
		mysql_free_result($res);
		if($rowDep[0]=="NO")
			$rowDep[0]=$rowDep[1];
		$pos=strpos(strtoupper($sql),"WHERE")+4;
		$sqla=substr($sql,0,$pos+1);
		$sqlb=substr($sql,$pos+1);
		$sql=$sqla."(".$sqlb.")";		
		
		$sql.=" AND ".$rowDep[0]."='".$llaveaux."'";
	}	
	
	$sql = str_replace('$SUCURSAL', $_SESSION["USR"]->sucursalid,$sql);	
	if($id == 35 && isset($aux))
		$sql = str_replace("id_cliente =", "id_cliente = $aux",$sql);	
	
	//para mandarin detalle de pedidos
	
	if(($_SESSION["USR"]->parametrosAux)=='')
		
		$sql = str_replace('$VAR2', $_SESSION["USR"]->sucursalid,$sql);	
	else
		$sql = str_replace('$VAR2', $_SESSION["USR"]->parametrosAux,$sql);	
		
	if(isset($proveedor))	
		$sql = str_replace('$PROVEEDOR', $proveedor,$sql);
	
	//Reemplazamos la variable SUCURSAL_SEL que viene de ordenes de compra
	//if(isset($sucursal_sel))	
	//	$sql = str_replace('$SUC_SEL', $sucursal_sel,$sql);
	
	/*echo $sql;
	die();*/
	$res=mysql_query($sql) or die("Por favor valide que ha llenado los datos previos");// en:\n$sql\n\nDescripcion 1:\n".mysql_error());;	
	$num=mysql_num_rows($res);
	/*if($num <= 0)
		die("No se encontraron datos\n$sql");*/
	if($tipo == 'combo')
	{
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]);
		}
	}
	else
	{
		echo "exito|$num";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0].":".$row[1]);
		}
	}	

?>