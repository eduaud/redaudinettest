<?php
php_track_vars;

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

extract($_GET);
extract($_POST);

/*$sql="
	SELECT cl_control_contratos.id_control_contrato,contrato,cuenta,fecha_activacion,cl_contratos_acciones.id_accion_control,cl_contratos_acciones.nombre AS estatus
	FROM cl_control_contratos 
	LEFT JOIN cl_control_contratos_detalles 
	ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato 
	LEFT JOIN cl_contratos_acciones ON cl_control_contratos_detalles.id_accion_contrato=cl_contratos_acciones.id_accion_control 
	WHERE cl_control_contratos_detalles.activo=1";
	
$datosContratos = new consultarTabla($sql);
$contratos = $datosContratos -> obtenerArregloRegistros();
$smarty->assign('a_contratos',$contratos);*/
$smarty->display('especiales/contrarecibos.tpl');
?>