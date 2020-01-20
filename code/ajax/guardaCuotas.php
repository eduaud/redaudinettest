<?php
include("../../conect.php");
include("../general/funciones.php");
include("../../consultaBase.php");
extract($_GET);
extract($_POST);
if($mes=='1')
	$campos='dc16='.$tradicionalhd.',dc17='.$tradicionalsd.',dc18='.$vetv.',dc19='.$vetv2.',dc20='.$total;
elseif($mes=='2')
	$campos='dc21='.$tradicionalhd.',dc22='.$tradicionalsd.',dc23='.$vetv.',dc24='.$vetv2.',dc25='.$total;
elseif($mes=='3')
	$campos='dc26='.$tradicionalhd.',dc27='.$tradicionalsd.',dc28='.$vetv.',dc29='.$vetv2.',dc30='.$total;
elseif($mes=='4')
	$campos='dc31='.$tradicionalhd.',dc32='.$tradicionalsd.',dc33='.$vetv.',dc34='.$vetv2.',dc35='.$total;
elseif($mes=='5')
	$campos='dc36='.$tradicionalhd.',dc37='.$tradicionalsd.',dc38='.$vetv.',dc39='.$vetv2.',dc40='.$total;
elseif($mes=='6')
	$campos='dc41='.$tradicionalhd.',dc42='.$tradicionalsd.',dc43='.$vetv.',dc44='.$vetv2.',dc45='.$total;
elseif($mes=='7')
	$campos='dc46='.$tradicionalhd.',dc47='.$tradicionalsd.',dc48='.$vetv.',dc49='.$vetv2.',dc50='.$total;
elseif($mes=='8')
	$campos='dc51='.$tradicionalhd.',dc52='.$tradicionalsd.',dc53='.$vetv.',dc54='.$vetv2.',dc55='.$total;
elseif($mes=='9')
	$campos='dc56='.$tradicionalhd.',dc57='.$tradicionalsd.',dc58='.$vetv.',dc59='.$vetv2.',dc60='.$total;
elseif($mes=='10')
	$campos='dc61='.$tradicionalhd.',dc62='.$tradicionalsd.',dc63='.$vetv.',dc64='.$vetv2.',dc65='.$total;
elseif($mes=='11')
	$campos='dc66='.$tradicionalhd.',dc67='.$tradicionalsd.',dc68='.$vetv.',dc69='.$vetv2.',dc70='.$total;
elseif($mes=='12')
	$campos='dc71='.$tradicionalhd.',dc72='.$tradicionalsd.',dc73='.$vetv.',dc74='.$vetv2.',dc75='.$total;
$sqlClave="SELECT clave FROM ad_sucursales WHERE id_sucursal=$plaza";
$datosClave = new consultarTabla($sqlClave);
$clave = $datosClave -> obtenerArregloRegistros();
$clv=$clave[0][0];
$sqlUpdate="UPDATE cl_importacion_cuotas SET t86='$clv',t87='$clavedis',t88='$disNom',$campos,id_sucursal=$plaza,id_cliente=$distribuidor WHERE id_control=".$id;

if(mysql_query($sqlUpdate)){
	echo "Datos Actualizados Correctamente";
}
?>