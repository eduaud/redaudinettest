<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

extract($_POST);
mysql_query("SET NAMES 'utf8'");
$where="";
if($cuentaSAT != ""){
	$sqlSuperior="SELECT cuenta_superior_sat FROM con_cuentas_agrupadoras_sat WHERE id_cuenta_sat=".$cuentaSAT;
	$result=mysql_query($sqlSuperior);
	$where=" where cuenta_superior_sat=".mysql_result($result,0)." AND nivel_cuenta_sat=2";
} else {
	$where=" where nivel_cuenta_sat=1";
}
		
$sql = "SELECT id_cuenta_sat,nombre_cuenta_sat FROM con_cuentas_agrupadoras_sat".$where;

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("cuentasSAT", $result);

echo $smarty->fetch('especiales/obtenerCuentaSATPorNivel.tpl');
?>