<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);

//CONECCION Y PERMISOS A LA BASE DE DATOS
 	include("../../../conect.php");
	include("../../../code/general/funciones.php");
	include("funcionesExistenciaProductos.php");
//funcionesExistenciaProductos.php
	//vemos si el producto es compuesto  
	//cho $id;
	$strRespuesta=obtenExistenciaUnProducto($id);
	
	echo $strRespuesta;
	
?>