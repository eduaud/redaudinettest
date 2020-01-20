<?php
php_track_vars;

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");

extract($_GET);
extract($_POST);

$sql="
	SELECT id_detalle,cl_control_contratos.id_control_contrato,contrato,IF(cl_control_contratos_detalles.id_cliente<>'',cl_control_contratos_detalles.id_cliente,cl_control_contratos_detalles.id_entidad_financiera_tecnico) as id_cliente,IF(cl_control_contratos_detalles.id_cliente<>'',(SELECT CONCAT(ad_clientes.nombre,' ',IF(ad_clientes.apellido_paterno='','',ad_clientes.apellido_paterno),' ',IF(ad_clientes.apellido_materno='','',ad_clientes.apellido_materno)) AS cliente FROM ad_clientes WHERE ad_clientes.id_cliente=cl_control_contratos_detalles.id_cliente),(SELECT CONCAT(ad_entidades_financieras.nombre,' ',IF(ad_entidades_financieras.apellido_paterno='','',ad_entidades_financieras.apellido_paterno),' ',IF(ad_entidades_financieras.apellido_materno='','',ad_entidades_financieras.apellido_materno)) AS cliente FROM ad_entidades_financieras WHERE ad_entidades_financieras.id_entidad_financiera=cl_control_contratos_detalles.id_entidad_financiera_tecnico))as cliente,cl_control_contratos.cuenta,fecha_activacion,cl_contratos_acciones.id_accion_control,cl_contratos_acciones.nombre AS estatus,cl_tipos_activacion.nombre AS tipoActivacion
	FROM cl_control_contratos 
	LEFT JOIN cl_control_contratos_detalles 
	ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato 
	LEFT JOIN cl_contratos_acciones ON cl_control_contratos_detalles.id_accion_contrato=cl_contratos_acciones.id_accion_control 
	LEFT JOIN cl_tipos_activacion ON cl_contratos_acciones.id_tipo_activacion=cl_tipos_activacion.id_tipo_activacion
	WHERE cl_control_contratos_detalles.activo=1 AND cl_control_contratos_detalles.id_accion_contrato  IN(1,100) AND id_detalle=".$id_detalle;
	
$datosContratos = new consultarTabla($sql);

$contratos = $datosContratos -> obtenerArregloRegistros();
$smarty->assign('a_contratos',$contratos);
echo $smarty->fetch('especiales/agregaContrarecibos.tpl');

?>