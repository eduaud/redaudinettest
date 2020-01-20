<?php

		php_track_vars;

	/*Conseguimos los datos del post
	
		tabla -> Nombre de la tabla que se esta accediendo
		id -> id del campo a utilizar
		accion -> accion a realizar, los valores pueden ser: [1 => Modificar, 2 => Ver, 3 => Eliminar o Cancelar]
	*/
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	
	if($tipo == 1)
	{
		$sql="SELECT
		      IF(bcp.id_control IS NULL, 'NO', bcp.id_control),
			  IF(bcp.id_control_boletin IS NULL, '$"."llave', bcp.id_control_boletin),
			  cp.id_cliente_perfil,
			  IF(bcp.activo IS NULL, 1, bcp.activo),
			  cp.nombre
			  FROM peug_clientes_perfiles cp
			  LEFT JOIN peug_boletines_clientes_perfiles bcp ON cp.id_cliente_perfil = bcp.id_cliente_perfil AND bcp.id_control_boletin='$id_boletin'
			  WHERE cp.activo=1";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]."~".$row[2]."~".$row[3]."~".$row[4]);
		}
	}
	if($tipo == 2)
	{
		if($id_boletin == '' || $id_boletin == '0')
			$def=1;
		else
			$def=0;	
			
		$sql="SELECT
		      IF(bpv.id_control IS NULL, 'NO', bpv.id_control),
			  IF(bpv.id_control_boletin IS NULL, '$"."llave', bpv.id_control_boletin),
			  pv.id_punto_venta,
			  IF(bpv.activo IS NULL, $def, bpv.activo),
			  pv.nombre
			  FROM peug_puntos_venta pv
			  LEFT JOIN peug_boletines_puntos_venta bpv ON pv.id_punto_venta = bpv.id_punto_venta AND bpv.id_control_boletin='$id_boletin'
			  WHERE pv.id_punto_venta <> 1
			  AND (pv.activo = 1 OR bpv.activo = 1)";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]."~".$row[2]."~".$row[3]."~".$row[4]);
		}
	}
	if($tipo == 3)
	{
		$sql="SELECT
		      IF(ba.id_control IS NULL, 'NO', ba.id_control),
			  IF(ba.id_control_boletin IS NULL, '$"."llave', ba.id_control_boletin),
			  a.id_area,
			  IF(ba.activo IS NULL, 1, ba.activo),
			  a.nombre
			  FROM peug_areas a
			  LEFT JOIN peug_boletines_areas ba ON a.id_area = ba.id_area AND ba.id_control_boletin='$id_boletin'";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]."~".$row[2]."~".$row[3]."~".$row[4]);
		}
	}
	if($tipo == 4)
	{
		$namef="../../cache/boletinPerfil_{$id_control_boletin}_".sprintf("%u",ip2long($_SERVER['REMOTE_ADDR']));
		
		$fileant="";
	
		if($iteracion > 0)
		{
			//die();
			$file=fopen($namef,"rt");
			if($file)
			{
				while(!feof($file))
				{
					$cadaux=fread($file,1000);
					$fileant.=$cadaux;
				}
			}		
			fclose($file);		
			
		}
		
		$ar=fopen($namef, "wt");
		if($ar)
		{
			$myQuery=array();
			if($iteracion > 0)
			{	
				$myQuery=explode("|",$fileant);
				array_pop($myQuery);			
			}
			else
				array_push($myQuery, "DELETE FROM peug_boletines_clientes_perfiles WHERE id_control_boletin='$id_control_boletin'");
			
			for($i=0;$i<$numdatos;$i++)
			{
				if($dato1[$i] == "NO")
				{
					$sql="INSERT INTO peug_boletines_clientes_perfiles(id_control_boletin, id_cliente_perfil, activo) VALUES($"."llave, ".$dato3[$i].", ".$dato4[$i].")";
					array_push($myQuery, $sql);											
				}
				else
				{
					$sql="UPDATE peug_boletines_clientes_perfiles SET activo=".$dato4[$i]." WHERE id_control=".$dato1[$i];
					array_push($myQuery, $sql);											
					$myQuery[0].=" AND id_control <> ".$dato1[$i];
				}
			}
			for($i=0;$i<sizeof($myQuery);$i++)
				//echo $myQuery[$i];
				fwrite($ar,$myQuery[$i]."|");	
				
			fclose($ar);	
			echo "exito|$namef";
				
		}
		else
			echo "Error, no se pudo escribir en el archivo";
	}
	
	if($tipo == 5)
	{
		$namef="../../cache/boletinPV_{$id_control_boletin}_".sprintf("%u",ip2long($_SERVER['REMOTE_ADDR']));
		
		$fileant="";
	
		if($iteracion > 0)
		{
			//die();
			$file=fopen($namef,"rt");
			if($file)
			{
				while(!feof($file))
				{
					$cadaux=fread($file,1000);
					$fileant.=$cadaux;
				}
			}		
			fclose($file);		
			
		}
		
		$ar=fopen($namef, "wt");
		if($ar)
		{
			$myQuery=array();
			if($iteracion > 0)
			{	
				$myQuery=explode("|",$fileant);
				array_pop($myQuery);			
			}
			else
				array_push($myQuery, "DELETE FROM peug_boletines_puntos_venta WHERE id_control_boletin='$id_control_boletin'");
			
			for($i=0;$i<$numdatos;$i++)
			{
				if($dato1[$i] == "NO")
				{
					$sql="INSERT INTO peug_boletines_puntos_venta(id_control_boletin, id_punto_venta, activo) VALUES($"."llave, ".$dato3[$i].", ".$dato4[$i].")";
					array_push($myQuery, $sql);											
				}
				else
				{
					$sql="UPDATE peug_boletines_puntos_venta SET activo=".$dato4[$i]." WHERE id_control=".$dato1[$i];
					array_push($myQuery, $sql);											
					$myQuery[0].=" AND id_control <> ".$dato1[$i];
				}
			}
			for($i=0;$i<sizeof($myQuery);$i++)
				//echo $myQuery[$i];
				fwrite($ar,$myQuery[$i]."|");	
				
			fclose($ar);	
			echo "exito|$namef";
				
		}
		else
			echo "Error, no se pudo escribir en el archivo";
	}
	
	if($tipo == 6)
	{
		$namef="../../cache/boletinAreas_{$id_control_boletin}_".sprintf("%u",ip2long($_SERVER['REMOTE_ADDR']));
		
		$fileant="";
	
		if($iteracion > 0)
		{
			//die();
			$file=fopen($namef,"rt");
			if($file)
			{
				while(!feof($file))
				{
					$cadaux=fread($file,1000);
					$fileant.=$cadaux;
				}
			}		
			fclose($file);		
			
		}
		
		$ar=fopen($namef, "wt");
		if($ar)
		{
			$myQuery=array();
			if($iteracion > 0)
			{	
				$myQuery=explode("|",$fileant);
				array_pop($myQuery);			
			}
			else
				array_push($myQuery, "DELETE FROM peug_boletines_areas WHERE id_control_boletin='$id_control_boletin'");
			
			for($i=0;$i<$numdatos;$i++)
			{
				if($dato1[$i] == "NO")
				{
					$sql="INSERT INTO peug_boletines_areas(id_control_boletin, id_area, activo) VALUES($"."llave, ".$dato3[$i].", ".$dato4[$i].")";
					array_push($myQuery, $sql);											
				}
				else
				{
					$sql="UPDATE peug_boletines_areas SET activo=".$dato4[$i]." WHERE id_control=".$dato1[$i];
					array_push($myQuery, $sql);											
					$myQuery[0].=" AND id_control <> ".$dato1[$i];
				}
			}
			for($i=0;$i<sizeof($myQuery);$i++)
				//echo $myQuery[$i];
				fwrite($ar,$myQuery[$i]."|");	
				
			fclose($ar);	
			echo "exito|$namef";
				
		}
		else
			echo "Error, no se pudo escribir en el archivo";
	}
	
	if($tipo == 7)
	{
		$sql="SELECT
		      ba.id_control,
			  ba.id_control_boletin,
			  ba.ruta,
			  ba.nombre,
			  ba.fecha_alta,
			  CONCAT(up.nombres, ' ', up.apellido_paterno, ' ', up.apellido_materno),
			  ''
			  FROM peug_boletines_archivos ba
			  JOIN sys_usuarios up ON ba.id_usuario_pfmx_alta = up.id_usuario
			  WHERE ba.id_control_boletin='$id_boletin'";
			  
		if(($id_boletin == '0' || $id_boletin == '') AND $randomNuevo != '')	  
			$sql.=" AND random=$randomNuevo";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo "|".$row[0]."~".$row[1]."~".$row[2]."~".$row[3]."~".$row[4]."~".$row[5]."~".$row[6];
		}
	}
	
	//Conseguimos el consecutivo
	if($tipo == 8)
	{
		$sql="SELECT
		      IF(MAX(cosecutivo) IS NULL, 0, MAX(cosecutivo))+1
			  FROM peug_boletines
			  WHERE id_categoria=$categoria
			  AND anio=".(date("Y")-2000);
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$row=mysql_fetch_row($res);
		echo "exito|".$row[0];
		
	}
	
?>