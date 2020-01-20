<?php
	php_track_vars;

    extract($_GET);
	extract($_POST);
	
	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	
	$strConsulta="SELECT id_lista_de_precios, nombre, DATE_FORMAT(fecha_inicio, '%d/%m/%Y'), DATE_FORMAT(fecha_fin, '%d/%m/%Y'), un_pago_total FROM spa_lista_precios WHERE id_lista_de_precios= ".$id." ";	
	//echo $strConsulta;
	$resultado=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());
	$num=mysql_num_rows($resultado);

	$row=mysql_fetch_row($resultado);
	for($j=0;$j<sizeof($row);$j++)
	{	
		echo $row[$j].'|';  //utf8_encode()
	}	


?>