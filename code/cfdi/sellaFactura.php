<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("funciones_facturacion.php");
	include("facturacion_sellado_timbrado.php");


	//var url="../cfdi/sellaFactura.php?tabla=facturas&llave="+id+"&id_compania=1";
	
	$res=cfdiSellaTimbraFactura($llave,$id_compania);
	echo $res;
?>