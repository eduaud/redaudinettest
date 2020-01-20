<?php 
extract($_GET);
extract($_POST);

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sqlSuc="SELECT id_sucursal,nombre FROM ad_sucursales ";

$datosSucursales = new consultarTabla($sqlSuc);
$sucursales = $datosSucursales -> obtenerArregloRegistros();

$sqlAnios="SELECT id_anio,nombre FROM sys_anios WHERE nombre>'2015'";

$datosAnios = new consultarTabla($sqlAnios);
$anios = $datosAnios -> obtenerArregloRegistros();

$sqlMeses="SELECT id_mes,nombre FROM sys_meses";

$datosMeses = new consultarTabla($sqlMeses);
$meses = $datosMeses -> obtenerArregloRegistros();

$smarty->assign('a_sucursales',$sucursales);
$smarty->assign('a_anios',$anios);
$smarty->assign('a_meses',$meses);	
$smarty->display("especiales/cuotasAnuales.tpl");

?>