<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");

	if($opcion==1)
	{
		$sql = "DELETE FROM  spa_lista_precios_servicios_detalle where id_lista_de_precios_servicios = $id";
		mysql_query($sql) or die("Error en:\n$sql2\n\nDescripcion:".mysql_error());
		echo "exito";
	}
		
?>