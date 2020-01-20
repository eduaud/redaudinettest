<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$sql="
	SELECT ad_entidades_financieras.id_entidad_financiera,CONCAT(ad_entidades_financieras.nombre,' ',IFNULL(ad_entidades_financieras.apellido_paterno,''),' ',IFNULL(ad_entidades_financieras.apellido_materno,'')) AS Empleado 
	FROM ad_entidades_financieras 
	LEFT JOIN ad_solicitudes_material 
	ON ad_entidades_financieras.id_entidad_financiera=ad_solicitudes_material.id_empleado 
	WHERE ad_solicitudes_material.id_estatus_solicitud=1 GROUP BY ad_entidades_financieras.id_entidad_financiera";
$datosEmpleado = new consultarTabla($sql);
$empleado = $datosEmpleado -> obtenerArregloRegistros();
$sqlSolicitudes="
	SELECT ad_solicitudes_material.id_control_solicitud_material,ad_sucursales.nombre AS Plaza,ad_solicitudes_material.id_solicitud_material AS Solicitud,DATE_FORMAT(ad_solicitudes_material.fecha_alta_solicitud,'%d/%m/%Y') AS Fecha,CONCAT(ad_entidades_financieras.nombre,' ',IFNULL(ad_entidades_financieras.apellido_paterno,''),' ',IFNULL(ad_entidades_financieras.apellido_materno,'')) AS Empleado
	FROM ad_solicitudes_material
	LEFT JOIN ad_sucursales 
	ON ad_solicitudes_material.id_sucursal=ad_sucursales.id_sucursal 
	LEFT JOIN ad_entidades_financieras 
	ON ad_solicitudes_material.id_empleado=ad_entidades_financieras.id_entidad_financiera
	WHERE ad_solicitudes_material.id_estatus_solicitud=1
";
$datosSolicitudes = new consultarTabla($sqlSolicitudes);
$Solicitudes = $datosSolicitudes -> obtenerArregloRegistros();
$smarty->assign('a_empleado',$empleado);
$smarty->assign('a_solicitudes',$Solicitudes);
$smarty->display('especiales/aprobacionSolicitudesMaterial.tpl');
?>