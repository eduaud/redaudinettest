<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");


$aCuentasContablesNivel3 = obtenerCuentasContablesNivel3($id_cuenta_contable);

if($aCuentasContablesNivel3 == array()){
	echo "<p style='text-indent:50px !important;'><b>No hay Cuentas que mostrar</b></p>";
}
else{
	if($campoId!='undefined'&&$campoCuenta!='undefined'){
		$smarty -> assign("id",$campoId);	
		$smarty -> assign("cuenta",$campoCuenta);
		$smarty -> assign("caso",'fancy');
	}
	$smarty -> assign("cuentasNivel3",$aCuentasContablesNivel3);
	echo $smarty->fetch("especiales/ajax/mostrar_cuentas_contables_nivel3.tpl");
}
?>