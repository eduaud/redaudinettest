<?php
include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");

$id_empleado=$_GET['empleado'];
$fecha1=$_GET['fecha1'];
$fecha2=$_GET['fecha2'];
$solicitud=$_GET['solicitud'];
$condicion="";
if($id_empleado!=''){
	$condicion="AND ad_entidades_financieras.id_entidad_financiera=".$id_empleado;
}

if($fecha1!=''&&$fecha2!=''){
	$date1 = DateTime::createFromFormat('d/m/Y', $fecha1);
	$date2 = DateTime::createFromFormat('d/m/Y', $fecha2);
	$fech1=$date1->format('Y-m-d');
	$fech2=$date2->format('Y-m-d');
	$condicion.=" AND ad_solicitudes_material.fecha_alta_solicitud BETWEEN '".$fech1."' AND '".$fech2."'";
}
if($solicitud!=''){
	$condicion="AND ad_solicitudes_material.id_solicitud_material='".$solicitud."'";
}
mysql_query("SET NAMES 'utf8'");
$sqlSolicitudes="
	SELECT ad_solicitudes_material.id_control_solicitud_material,ad_sucursales.nombre AS Plaza,ad_solicitudes_material.id_solicitud_material AS Solicitud,DATE_FORMAT(ad_solicitudes_material.fecha_alta_solicitud,'%d/%m/%Y') AS Fecha,CONCAT(ad_entidades_financieras.nombre,' ',IFNULL(ad_entidades_financieras.apellido_paterno,''),' ',IFNULL(ad_entidades_financieras.apellido_materno,'')) AS Empleado
	FROM ad_solicitudes_material
	LEFT JOIN ad_sucursales 
	ON ad_solicitudes_material.id_sucursal=ad_sucursales.id_sucursal 
	LEFT JOIN ad_entidades_financieras 
	ON ad_solicitudes_material.id_empleado=ad_entidades_financieras.id_entidad_financiera
	WHERE ad_solicitudes_material.id_estatus_solicitud=1 ".$condicion;
$datosSolicitudes = new consultarTabla($sqlSolicitudes);
$Solicitudes = $datosSolicitudes -> obtenerArregloRegistros();
//echo '<pre>';print_r($Pedidos);echo '</pre>';
$smarty->assign('a_solicitudes',$Solicitudes);
echo $smarty->fetch("especiales/ajax/mostrarSolicitudesMaterial.tpl");
?> 