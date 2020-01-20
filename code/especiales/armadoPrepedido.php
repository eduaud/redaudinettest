<?php
		
	php_track_vars;
	
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	extract($_GET);
	extract($_POST);

	$smarty->display("especiales/armadoPrepedido.tpl");
	
?>