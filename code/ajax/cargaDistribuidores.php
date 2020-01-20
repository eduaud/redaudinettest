<?php 
include("../../conect.php");

$sqlDistribuidores="SELECT id_distribuidor,nombre FROM cl_distribuidores";
$distribuidores=mysql_query($sqlDistribuidores);
$cont=0;
while($a_distrbuidores=mysql_fetch_array($distribuidores)){
	$array_distribuidor[$cont]['id_distribuidor']=$a_distrbuidores['id_distribuidor'];
	$array_distribuidor[$cont]['nombre']=$a_distrbuidores['nombre'];
	$cont+=1;
}
echo json_encode($array_distribuidor);
?>