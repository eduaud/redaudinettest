<?php
include("../../../conect.php");
include("../../general/funciones.php");
include("../../../consultaBase.php");

/***   LLena array Plazas   ***/
$arrIDPlaza=array();
$arrNombrePlaza=array();

$queryPlazas='SELECT id_sucursal,nombre FROM ad_sucursales';
$resultPlazas=mysql_query($queryPlazas);

while($datosPlazas=mysql_fetch_array($resultPlazas)){
	array_push($arrIDPlaza, $datosPlazas[0]);
	array_push($arrNombrePlaza, $datosPlazas[1]);
}

$smarty->assign('arrIDPlaza',$arrIDPlaza);
$smarty->assign('arrNombrePlaza',$arrNombrePlaza);
/***   Termina LLena array Plazas   ***/


/***   LLena array Tipo Cliente   ***/
$arrIDTipoCliente=array();
$arrNombreTipoCliente=array();

$queryTipoCliente = 'SELECT id_tipo_cliente_proveedor, nombre FROM cl_tipos_cliente_proveedor WHERE activo="1" AND id_tipo_cliente_proveedor != 0 
						GROUP BY id_tipo_cliente_proveedor';
$resultTipoCliente = mysql_query($queryTipoCliente);

while($datosTipoCliente=mysql_fetch_array($resultTipoCliente)){
	array_push($arrIDTipoCliente, $datosTipoCliente[0]);
	array_push($arrNombreTipoCliente, $datosTipoCliente[1]);
}

$smarty->assign('arrIDTipoCliente',$arrIDTipoCliente);
$smarty->assign('arrNombreTipoCliente',$arrNombreTipoCliente);
/***   Termina LLena array Tipo Cliente   ***/

$smarty -> display("especiales/liberaciones/liberaPlazasPenalizacion.tpl");
?>