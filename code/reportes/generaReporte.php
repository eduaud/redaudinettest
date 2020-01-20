<?php
include("../../conect.php");
include("../general/funciones.php");
include("../../consultaBase.php");

$rep = $_GET['rep'];

// ***   Extrae las Plazas de la BD   *** //
if($rep == 106 || $rep == 107){
	$arrPlazasID=array();
	$arrPlazasNombre=array();

	$queryPlazas='SELECT id_sucursal,nombre FROM ad_sucursales WHERE activo = 1 ORDER BY nombre';
	$resultPlazas=mysql_query($queryPlazas);

	$p=0;
	while($datosPlazas=mysql_fetch_array($resultPlazas)){
		$arrPlazasID[$p] = $datosPlazas['id_sucursal'];
		$arrPlazasNombre[$p] = $datosPlazas['nombre'];
		$p++;
	}

	$smarty->assign('arrPlazasID',$arrPlazasID);
	$smarty->assign('arrPlazasNombre',$arrPlazasNombre);
}
// ***   Termina Extrae las Plazas de la BD   *** //

if($rep == 106){
	$nombreReporte = 'Reporte_Facturas_para_Arqueo';
	$titulo = 'Facturas Arqueo';
} elseif($rep == 107) {
	$nombreReporte = 'Relacion_de_Facturas_por_Pagar';
	$titulo = 'Relacion de Facturas por Pagar';
}

$smarty->assign('rep',$rep);
$smarty->assign('nombreReporte',$nombreReporte);
$smarty->assign('titulo',$titulo);
$smarty->display('generaReporte.tpl');	
?>

