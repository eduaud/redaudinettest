<?php

include("../../conect.php");
include("../general/funciones.php");
include("../../consultaBase.php");

$rep = $_GET['rep'];

//print_r($_SESSION);
//
//exit();


$instrucciones = "A continuaci&oacute;n seleccione los filtros deseados y de clic a 'Generar reporte' para verlo en pantalla o bien a 'Exportar a Excel' para guardar el archivo como un libro de Microsoft Excel.";

$carpetaRep = ROOTPATH.'/templates/reportes';
$smarty->assign('raiz',ROOTURL.'modules/');
$smarty->assign('rutaTPL',ROOTPATH.'/templates/');
$smarty->assign('carpetaImagenes',ROOTURL.'imagenes/general/');
$smarty->assign("contentheader","M&oacute;dulo de reportes");
$smarty->assign("carpeta",$carpetaRep);
$smarty->assign('instrucciones',$instrucciones);
$smarty->assign('reporte',$reporte);
$smarty->assign('accion','reportes');


$strConsulta="SELECT nombre FROM sys_menus WHERE cod_tabla='".$rep."'";
$datos = new consultarTabla($strConsulta);
$result = $datos -> obtenerLineaRegistro();

$smarty->assign('reporte',$rep);
$smarty->assign('titulo',$result['nombre']);

if($rep == 1) {
	$strSQL = "";		
	$datos = new consultarTabla($strSQL);	
	$result = $datos -> obtenerRegistros();
	
	foreach($result as $registros){
		$arrFamId[] = $registros -> id_familia_producto;
		$arrFam[] = $registros -> nombre;			
	}
	
	$smarty->assign('familia_id', $arrFamId);
}

if($rep == 228) {

	$strSQL = "
		SELECT id_tipo_producto_servicio, nombre
		FROM cl_tipos_producto_servicio
		WHERE activo = 1 AND id_clasificacion_producto = 1
		ORDER BY nombre";
	$datos = new consultarTabla($strSQL);
	$result = $datos -> obtenerRegistros();
	foreach($result as $registros){
		$arrTipoId[] = $registros -> id_tipo_producto_servicio;
		$arrTipo[] = $registros -> nombre;
	}
	$smarty->assign('tipo_id', $arrTipoId);
	$smarty->assign('tipo_nombre',$arrTipo);
}

if($rep == 228) {

		$strSQL = "SELECT id_subtipo_movimiento, nombre FROM ad_subtipos_movimientos where id_tipo_movimiento = 2 and id_subtipo_movimiento in(70099,70223) ORDER BY nombre";

	$datos = new consultarTabla($strSQL);
	$result = $datos -> obtenerRegistros();
	foreach($result as $registros){
			$arrSubMovimientos[] = $registros -> nombre;
			$arrIdSubMovimientos[] = $registros -> id_subtipo_movimiento;
			}
	$smarty->assign('arrSubMovimientos', $arrSubMovimientos);
	$smarty->assign('arrIdSubMovimientos',$arrIdSubMovimientos);
}
	
$smarty->display('templateReportes.tpl');	

?>

