<?php
php_track_vars;

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");

extract($_GET);
extract($_POST);
$condicion="";
if(isset($caja)){
	$condicion=" id_caja_comisiones=".$caja;
}
if(isset($clave)){
	if($condicion!="")
		$condicion.=" AND t46='".$clave."'";
	else
		$condicion=" t46='".$clave."'";
}
if(isset($tipoProducto)){
	if($condicion!="")
		$condicion.=" AND n1=".$tipoProducto;
	else
		$condicion.=" n1=".$tipoProducto;
}
if(isset($promocion)){
	if($condicion!="")
		$condicion.=" AND n2=".$promocion;
	else
		$condicion.=" n2=".$promocion;
}
if(isset($funcionalidad)){
	if($condicion!="")
		$condicion.=" AND n6=".$funcionalidad;
	else
		$condicion.=" n6=".$funcionalidad;
}
if(isset($formaPago)){
	if($condicion!="")
		$condicion.=" AND n3=".$formaPago;
	else
		$condicion.=" n3=".$formaPago;
}
if(isset($NumEquipos)){
	if($condicion!="")
		$condicion.=" AND n4=".$NumEquipos;
	else
		$condicion.=" n4=".$NumEquipos;
}
if(isset($paquete)){
	if($condicion!="")
		$condicion.=" AND n5=".$paquete;
	else
		$condicion.=" n5=".$paquete;
}
$sql="SELECT id_control,t46,t47,t48,t52,t49,n4,t50,t51,dc16,dc17,dc18,dc19,dc20,dc23,dc24,dc25,dc26,dc27,dc28,dc29,dc30,dc31,dc32,dc33,dc34,dc35,dc21,dc22,t53 FROM cl_importacion_caja_comisiones WHERE ".$condicion;
$Cajas = new consultarTabla($sql);
$aCajas = $Cajas -> obtenerArregloRegistros();
$smarty->assign('a_cajas',$aCajas);
$smarty->assign('caso',$caso);
$sqlCount="SELECT COUNT(*) FROM cl_importacion_caja_comisiones WHERE ".$condicion;
$Registros = new consultarTabla($sqlCount);
$RegistrosT = $Registros -> obtenerArregloRegistros();
if($RegistrosT[0][0]>20){
	echo 'error|'.$condicion;
}
else{
	echo $smarty->fetch('especiales/ajax/cajasComisiones.tpl');
}
?>