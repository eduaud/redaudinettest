<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	if($datos=="SI")
	{
		$strConsulta="SELECT sql_combo, combo_where_2 FROM sys_config_encabezados WHERE id_encabezado='".$id."'";
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
		$arrDatos=mysql_fetch_row($res);
		if($arrDatos[0]!="")
		{
			echo $strConsulta=$arrDatos[0]." ".$arrDatos[1]."'".$llave."'";
			
			$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());			
			$num=mysql_num_rows($res);
		}
	}
	else
	{
		$strConsulta="SELECT sql_buscador, sql_combo_order, tabla, depende_de,campo2buscador FROM sys_config_encabezados WHERE id_encabezado='".$id."'";
		$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
		$arrDatos=mysql_fetch_row($res);
		if($arrDatos[0]!="")
		{
			$strConsulta=$arrDatos[0];
			if($arrDatos[3]!=""&&isset($valordep)&&$valordep!="")
			{
				$sqlDep="SELECT campo FROM sys_config_encabezados WHERE tabla='".$arrDatos[2]."' AND orden='".$depende."'";
				$res=mysql_query($sqlDep) or die("Error en:\n$sqlDep\n\nDescripcion:".mysql_error());
				$num=mysql_num_rows($res);
				$row=mysql_fetch_row($res);				
				if($num>0)
				{
					if($arrDatos[4]!="")
					{
						$strConsulta=$arrDatos[4];
					}
					if(!isset($nom_dependencia))
						$strConsulta.=" AND ".$row[0]."='".$valordep."'";
					else
						$strConsulta.=" AND ".$nom_dependencia."='".$valordep."'";
				}				
			}
			$strConsulta=str_replace('$LLAVE',$llave,$strConsulta);
			$strConsulta=str_replace('$SUCURSAL',$_SESSION["USR"]->sucursalid,$strConsulta);
			$strConsulta.=" ".$arrDatos[1];
			//echo $strConsulta;
			$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
			$num=mysql_num_rows($res);
		}
	}
	echo "exito|".$num;
	for($i=0;$i<$num;$i++)
	{
		$row=mysql_fetch_row($res);
		echo "|".$row[0]."~".$row[1];
	}
	
?>