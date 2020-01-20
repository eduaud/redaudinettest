<?php

	php_track_vars;

	extract($_GET);
	extract($_POST);	
	/*echo '<pre>';print_r($_POST);echo '</pre>';
	echo '<pre>';print_r($_GET);echo '</pre>';*/
	include("../../conect.php");
	
	$ip = sprintf("%u",ip2long($_SERVER['REMOTE_ADDR']));
	$namef=$ruta."cache/".$tabla."_".$llave."_".$ip.".dat";
	$fileant="";
	
	$tabla = str_replace('_v2x','', $tabla);
	
	/*echo "llego -----> <br><br>";
	echo $ip . "<br><br>";
	echo $namef=$ruta . "llego -----> <br><br>";
	print_r($_GET);
	die;*/
	
	if($iteracion > 0)
	{
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
		$sql="SELECT borrado_logico, campo_borrado_logico FROM sys_grid WHERE tabla_relacionada='".$tabla."'";
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
		$row=mysql_fetch_assoc($res);
		extract($row);
		mysql_free_result($res);			
		if($iteracion > 0)
		{	
			$myQuery=explode("|",$fileant);
			array_pop($myQuery);			
		}	
		else
		{
		   if($tabla == 'anderp_productos_detalle'){
		       $condicion= " AND id_sucursal=".$_SESSION["USR"]->sucursalid;
		   }
		   else {
		      $condicion="";
		    }
		 
			if($borrado_logico==0){
				array_push($myQuery,"DELETE FROM $tabla WHERE $campoid = '$llave'".$condicion);
			     
			}
			else{
				array_push($myQuery,"UPDATE $tabla SET ".$campo_borrado_logico."=0 WHERE $campoid = '$llave'".$condicion);
				}
		}				
			
		$sql="SELECT campo_tabla FROM `sys_grid_detalle` WHERE id_grid=$id_grid ORDER BY orden";
		$res=mysql_query($sql);
		if(!$res)
			die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
		$num=mysql_num_rows($res);
		if($num <= 0)
			die("No se encontrar&oacute;n campos para la tabla [$tabla]");
		$campos=array();
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			array_push($campos,$row[0]);
		}

		for($i=0;$i<$numdatos;$i++)
		{
			$sql="SELECT ".$campos[0]." FROM $tabla WHERE ".$campos[0]."='".$dato1[$i]."'";		
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
						
						//if es productos guardamos el tipo de presentacion en un arreglo
 						if($campos[$j] == 'id_producto_tipo'){
						    $aux="dato".($j+1);
						    $ax=$$aux;	
							$v_tipoProd[count($v_tipoProd)] = $ax[$i];	
						}
						
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
						//echo $ax[$i]."-</br>"; 
						if($ax[$i] == '$id_sucursal'){
						    $sql.=$_SESSION["USR"]->sucursalid;
						}
						else{	
						if($ax[$i] == '$id_usuario'){
							$sql.=$_SESSION["USR"]->userid;
						}	
						/*elseif($campos[$j]==$campoid)
							$sql.="'".$llave."'";*/
						else{	
							$sql.="'".$ax[$i]."'";
							}
						}						
					}		
					//echo $sql."</br>";
				}
				$sql.=")";
				
	
					  //$sql = str_replace('$id_sucursal', $_SESSION["USR"]->sucursalid,$sql);
				if($tabla != 'sys_usuarios_grupos' || ($tabla == "sys_usuarios_grupos" &&$dato5[$i] == '1'))				
					array_push($myQuery, $sql);	
			  
		
			  	//echo "tipo productos: ".$v_tipoProd[0]."";
				 $smarty->assign("v_tipoProd",$v_tipoProd);
			}
			else 
			{
			//echo "edicion//";
				$sql="UPDATE $tabla SET ";
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
				$sql.=" WHERE ".$campos[0]."=".$dato1[$i];
				if($tabla != 'sys_usuarios_grupos')
					array_push($myQuery, $sql);	
				if($tabla != 'sys_usuarios_grupos' || ($tabla == "sys_usuarios_grupos" &&$dato5[$i] == '1'))				
					$myQuery[0].=" AND ".$campos[0]." <> ".$dato1[$i];
				
				if($tabla == 'anderp_productos_detalle'){
				   $sql .= " AND id_sucursal=".$_SESSION["USR"]->sucursalid;
				}	
					
			}
			
			
		}		

		//echo  "PIO ".$sql;
		for($i=0;$i<sizeof($myQuery);$i++)
			//echo $myQuery[$i];
			fwrite($file,$myQuery[$i]."|");	
	}
	fclose($file);
	echo "exito|".$namef;
?>