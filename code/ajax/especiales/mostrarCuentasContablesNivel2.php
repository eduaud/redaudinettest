<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");


$aCuentasContablesNivel2 = obtenerCuentasContablesNivel2($id_cuenta_superior);

if($aCuentasContablesNivel2 == array()){
	echo "<p style='text-indent:30px !important;'><b>No hay Cuentas que mostrar</b></p>";
}
else{
	if($campoId!='undefined'&&$campoCuenta!='undefined'){
		$smarty -> assign("id",$campoId);	
		$smarty -> assign("cuenta",$campoCuenta);
		$smarty -> assign("caso",'fancy');
	}
	$smarty -> assign("cuentasNivel2",$aCuentasContablesNivel2);
	echo $smarty->fetch("especiales/ajax/mostrar_cuentas_contables_nivel2.tpl");
}
		
?>