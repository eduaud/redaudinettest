<?php

	
	require('../../conect.php');
	extract($_GET);
	
	$sql="SELECT
			CONCAT(
			  sql_combo,
			  ' ',
			  sql_combo_order
			)
			FROM `sys_config_encabezados`
			WHERE tabla='$tabla'
			AND posicion=$campo";
	$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
	if(mysql_num_rows($res) > 0)
	{
		$row=mysql_fetch_row($res);
		$sql=$row[0];
		//$sql=str_replace('$IDD', $id, $sql);
		//echo $sql;
		//die("entro al die " . $id . ' - ' . $sql);
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripci&oacute;n:\n".mysql_error());
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo "|".$row[0]."~".$row[1];			
		}
	}
	else
		die("No se encontrar&oacute;n datos para cargar el combo[$combo] $sql");
	
?>