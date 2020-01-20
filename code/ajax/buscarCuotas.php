<?php 
include("../../conect.php");
include("../general/funciones.php");
include("../../consultaBase.php");
extract($_POST);
mysql_query("SET NAMES 'utf8'");
if($mes=='1')
	$campos='dc16,dc17,dc18,dc19,dc20';
elseif($mes=='2')
	$campos='dc21,dc22,dc23,dc24,dc25';
elseif($mes=='3')
	$campos='dc26,dc27,dc28,dc29,dc30';
elseif($mes=='4')
	$campos='dc31,dc32,dc33,dc34,dc35';
elseif($mes=='5')
	$campos='dc36,dc37,dc38,dc39,dc40';
elseif($mes=='6')
	$campos='dc41,dc42,dc43,dc44,dc45';
elseif($mes=='7')
	$campos='dc46,dc47,dc48,dc49,dc50';
elseif($mes=='8')
	$campos='dc51,dc52,dc53,dc54,dc55';
elseif($mes=='9')
	$campos='dc56,dc57,dc58,dc59,dc60';
elseif($mes=='10')
	$campos='dc61,dc62,dc63,dc64,dc65';
elseif($mes=='11')
	$campos='dc66,dc67,dc68,dc69,dc70';
elseif($mes=='12')
	$campos='dc71,dc72,dc73,dc74,dc75';

if(isset($sucursal)){
	$condicion=" AND id_sucursal=".$sucursal;
}
else 
	$condicion="";
$sql="SELECT id_control,t86,t87,t88,".$campos." FROM cl_importacion_cuotas WHERE id_anio='".$anio."'".$condicion." AND activo=1";
$datosCuotas = new consultarTabla($sql);
$cuotas = $datosCuotas -> obtenerArregloRegistros();

$smarty->assign('a_cuotas',$cuotas);
echo $smarty->fetch('especiales/ajax/mostrar_cuotas.tpl');
?>