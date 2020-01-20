<?php

	php_track_vars;

	extract($_POST);
	extract($_GET);		

	include("../../conect.php");
	
	$ip = sprintf("%u",ip2long($_SERVER['REMOTE_ADDR']));
	$namef=$ruta."cache/".$tabla."_".$llave."_".$ip.".dat";
	$fileant="";
	
	$tabla = str_replace('#','', $tabla);
	
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
	$file = fopen($namef,"wt");
	if($file)
	{			
		$myQuery=array();			
		if($iteracion > 0)
		{	
			$myQuery=explode("|",$fileant);
			array_pop($myQuery);			
		}	
		else		
			array_push($myQuery,"DELETE FROM $tabla WHERE 0");
			
		$sql="SELECT campo_tabla FROM `sys_grid_detalle` WHERE id_grid=$id_grid ORDER BY orden";
		$res=mysql_query($sql);
		if(!$res)
			die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
		$num=mysql_num_rows($res);
		//array_push($myQuery, "Campos Grid: ".$num);
		if($num <= 0)
			die("No se encontrar&oacute;n campos para la tabla [$tabla]");
		$campos=array();
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			array_push($campos,$row[0]);
		}
		//array_push($myQuery, "Datos enviados para guardar: ".$numdatos);
		for($i=0;$i<$numdatos;$i++)
		{
			$sql="SELECT ".$campos[0]." FROM $tabla WHERE ".$campos[0]."='".$dato1[$i]."'";			
			//array_push($myQuery, $sql);
			$res=mysql_query($sql);
			if(!$res)
				die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
			$num=mysql_num_rows($res);	
			if($num <= 0)
			{
				$sql="INSERT INTO $tabla(";
				for($j=0;$j<sizeof($campos);$j++)
				{
					if($j > 0 && $campos[$j] != "NO")
					{
						if($j > 1)
							$sql.=",";
						$sql.=$campos[$j];	
					}	
				}				
				$sql.=") VALUES(";
				for($j=0;$j<sizeof($campos);$j++)
				{
					if($j > 0 && $campos[$j] != "NO")
					{
						if($j > 1)
							$sql.=",";
						$aux="dato".($j+1);
						$ax=$$aux;
						
						if($ax[$i] == '$id_sucursal'){
						    $sql.=$_SESSION["USR"]->sucursalid;
						}
						else{						
						if($ax[$i] == '$id_usuario')
							$sql.=$_SESSION["USR"]->userid;
						else	
							$sql.="'".$ax[$i]."'";
					   }
					}	
					//echo $sql."-</br>";	
				}
				$sql.=")";
					  //$sql = str_replace('$id_sucursal', $_SESSION["USR"]->sucursalid,$sql);
				array_push($myQuery, $sql);	
			}
			else
			{
				//$myQuery[0].=" AND ".$campos[0]." <> ".$dato1[$i];
				$sql="SELECT 1 FROM $tabla WHERE ".$campos[0]."='".$dato1[$i]."' AND (";
				for($j=0;$j<sizeof($campos);$j++)
				{
					if($campos[$j] != "NO")
					{
						if($j > 0)
							$sql.=" OR ";
						$sql.=$campos[$j]." <> '";	
						$aux="dato".($j+1);
						$ax=$$aux;
						$sql.=$ax[$i]."'";
					}
				}
				$sql.=")";
				//array_push($myQuery, $sql);	
				$res=mysql_query($sql);
				if(!$res)
					die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
				$num=mysql_num_rows($res);
				if($num > 0)
				{
					$sql="UPDATE $tabla SET cancelado=1 WHERE ".$campos[0]."=".$dato1[$i];
					//die($sql);
					$res=mysql_query($sql);
					if(!$res)
						die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
					$sql="INSERT INTO $tabla(";
					for($j=0;$j<sizeof($campos);$j++)
					{
						if($j > 0 && $campos[$j] != "NO")
						{
							if($j > 1)
								$sql.=",";
							$sql.=$campos[$j];	
						}	
					}				
					$sql.=") VALUES(";
					for($j=0;$j<sizeof($campos);$j++)
					{
						if($j > 0 && $campos[$j] != "NO")
						{
							if($j > 1)
								$sql.=",";
							$aux="dato".($j+1);
							$ax=$$aux;					
							if($ax[$i] == '$id_usuario')
								$sql.=$_SESSION["USR"]->userid;
							else	
								$sql.="'".$ax[$i]."'";
						}		
					}
					$sql.=")";
					array_push($myQuery, $sql);	
				}
				/*$sql="UPDATE $tabla SET ";
				for($j=0;$j<sizeof($campos);$j++)
				{
					if($campos[$j] != "NO")
					{
						if($j > 0)
							$sql.=",";
						$sql.=$campos[$j]."='";	
						$aux="dato".($j+1);
						$ax=$$aux;
						$sql.=$ax[$i]."'";
					}	
				}				
				$sql.=" WHERE $campoid=".$dato1[$i];*/
				//array_push($myQuery, $sql);	
			}
		}		
		
		for($i=0;$i<sizeof($myQuery);$i++)
			fwrite($file,$myQuery[$i]."|");	
	}
	fclose($file);
	echo "exito|".$namef;
?>