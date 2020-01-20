<?php 
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sqlAnios="SELECT id_anio,nombre FROM sys_anios WHERE id_anio>=2015";
$sqlMeses="SELECT id_mes,nombre FROM sys_meses";

$datosAnios = new consultarTabla($sqlAnios);
$anios= $datosAnios -> obtenerArregloRegistros();
$smarty->assign('a_anios',$anios);

$datosMeses = new consultarTabla($sqlMeses);
$meses= $datosMeses -> obtenerArregloRegistros();
$smarty->assign('a_meses',$meses);
$smarty->display('especiales/contabilidadElectronica.tpl');
?>