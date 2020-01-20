<?php 
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

extract($_GET);
extract($_POST);
$sqlDIstribuidores="SELECT id_cliente,CONCAT(ad_clientes.nombre,' ',IFNULL(apellido_paterno,''),' ',IFNULL(apellido_materno,'')) AS cliente FROM ad_clientes 
LEFT JOIN cl_tipos_cliente_proveedor 
ON ad_clientes.id_cliente=cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
WHERE cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=2 OR cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=3";
$datosDistribuidor = new consultarTabla($sqlDIstribuidores);
$Distribuidores = $datosDistribuidor-> obtenerArregloRegistros();
$smarty->assign('a_Distribuidores',$Distribuidores);

$smarty->display('especiales/tiemposContratos.tpl');
/*
$accion="1,100";
$PendientesEntrega = Datos($accion);
$smarty->assign('a_PendientesEntrega',$PendientesEntrega);

				
$accion="11";
$Entregados = Datos($accion);
$smarty->assign('a_Entregados',$Entregados);

$accion="12";
$Rechazados = Datos($accion);
$smarty->assign('a_Rechazados',$Rechazados);

$accion="12";
$Reagendado = Datos($accion);
$smarty->assign('a_Reagendado',$Reagendado);

$sqlMalaCalidad="SELECT 		id_detalle,cl_control_contratos.id_control_contrato,contrato,cuenta,fecha_activacion,fecha_movimiento,motivo_rechazo
	FROM cl_control_contratos 
	LEFT JOIN cl_control_contratos_detalles 
	ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato 
	WHERE cl_control_contratos_detalles.id_accion_contrato=22 AND cl_control_contratos_detalles.activo=1 AND ultimo_movimiento=1";
$datosMalaCalidad = new consultarTabla($sqlMalaCalidad);
$MalaCalidad = $datosMalaCalidad-> obtenerArregloRegistros();
$smarty->assign('a_MalaCalidad',$MalaCalidad);

$accion="3";
$PorFacturar = Datos($accion);
$smarty->assign('a_PorFacturar',$PorFacturar);

$accion="4";
$Facturado = Datos($accion);
$smarty->assign('a_Facturado',$Facturado);
*/


/*function Datos($id_accion){
	$sql="SELECT 
	IF(cl_control_contratos_detalles.id_cliente<>'',cl.nombre,IF(cl_control_contratos_detalles.id_entidad_financiera_tecnico<>'',ef.nombre,'')) AS distribuidor,id_detalle,cl_control_contratos.id_control_contrato,contrato,cuenta,fecha_activacion,
	IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 1, (cl_importacion_caja_comisiones.dc30 - cl_control_contratos_detalles.precio_suscripcion),
					IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 3, (cl_importacion_caja_comisiones.dc32 - cl_control_contratos_detalles.precio_suscripcion),
						IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 2, (cl_importacion_caja_comisiones.dc31 - cl_control_contratos_detalles.precio_suscripcion),
							IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 4, (cl_importacion_caja_comisiones.dc33 - cl_control_contratos_detalles.precio_suscripcion),
								IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 12, (cl_importacion_caja_comisiones.dc34 - cl_control_contratos_detalles.precio_suscripcion),
									IF(cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor = 7, (cl_importacion_caja_comisiones.dc35 - cl_control_contratos_detalles.precio_suscripcion), 0)
								)
							)
						)
					)
				) as comision
	FROM cl_control_contratos 
	LEFT JOIN cl_control_contratos_detalles 
	ON cl_control_contratos.id_control_contrato=cl_control_contratos_detalles.id_control_contrato 
	LEFT JOIN(SELECT id_cliente,CONCAT(ad_clientes.nombre,' ',ad_clientes.apellido_paterno,' ',IF(ad_clientes.apellido_materno<>'',ad_clientes.apellido_materno,'')) AS nombre,cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
						FROM ad_clientes
						LEFT JOIN cl_tipos_cliente_proveedor 
						ON ad_clientes.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
						)AS cl
	ON cl_control_contratos_detalles.id_cliente=cl.id_cliente 
	LEFT JOIN(SELECT id_entidad_financiera,CONCAT(ad_entidades_financieras.nombre,' ',ad_entidades_financieras.apellido_paterno,' ',IF(ad_entidades_financieras.apellido_materno<>'',ad_entidades_financieras.apellido_materno,'')) AS nombre,cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
			FROM ad_entidades_financieras
			LEFT JOIN ad_tipos_entidades_financieras
			ON ad_entidades_financieras.id_tipo_entidad_financiera= ad_tipos_entidades_financieras.id_tipo_entidad_financiera
			LEFT JOIN cl_tipos_cliente_proveedor 
			ON ad_tipos_entidades_financieras.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
			)AS ef
	ON cl_control_contratos_detalles.id_entidad_financiera_tecnico=ef.id_entidad_financiera
	LEFT JOIN cl_tipos_cliente_proveedor ON cl.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor OR ef.id_tipo_cliente_proveedor = cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
	LEFT JOIN cl_importacion_caja_comisiones
	ON cl_importacion_caja_comisiones.id_control = cl_control_contratos_detalles.id_detalle_caja_comisiones
	WHERE cl_control_contratos_detalles.id_accion_contrato IN(".$id_accion.") 
	AND cl_control_contratos_detalles.activo=1 
	AND ultimo_movimiento=1
	AND principal=1
	";
	
	$datos = new consultarTabla($sql);
	$a_datos = $datos-> obtenerArregloRegistros();
	
	return $a_datos;
}*/
?>