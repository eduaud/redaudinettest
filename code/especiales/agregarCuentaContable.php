<?php

	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);
	
	$smarty->display("especiales/agregar_cuentas_contables.tpl");
?>