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
	
	//buscamos todas la tablas en las cuales esta relacionada la tabla
	
	//ahora en estas tablas buscamos generamos los querys del id y los valores relacionados
	//base64_decode
	if($accion=='3')
	{
		$cadena=array();
		
		$strSQL="SELECT  tabla, campo FROM sys_config_encabezados where tabla_relacion='".base64_decode($tabla)."'";
				
		//realizamos una cadena de wheres
		$ref=mysql_query($strSQL) or die(mysql_error());
		
		while($linea=mysql_fetch_assoc($ref))
		{
			array_push($cadena,"SELECT '".$linea['tabla']."' as tabla_bus, ".$linea['campo']."  as dato FROM ".$linea['tabla']." WHERE ".$linea['campo']."='".$id."'");
		}
		
		mysql_free_result($ref);
		
		$strSQLUnion=implode(' UNION ALL ', $cadena);
		
		
		if($strSQLUnion !='')
		{
			$strCount= "SELECT COUNT(tabla_bus) as total FROM (".$strSQLUnion.") AS total ";
			
			$resConsulta = mysql_query($strCount) or 	die("Error at a $strSQL ::".mysql_error());
			extract(mysql_fetch_assoc($resConsulta));
			if($total>0)
			{
				echo "no";
			}
			else
				echo "si";
		}		
		else
			echo "si";
		
		
	}
	else if($accion == '1')
	{
		echo "si";
	}
	else
		echo "si";
	//tabla
	
	//id
	
	//seleccionamos la tabla
	
	
	//En caso de estar permitido regresar "si", en caso contrario el mensaje que debe desplegarse al usuario
	
	
?>
