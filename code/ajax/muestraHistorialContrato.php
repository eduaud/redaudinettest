<?php
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_control_contrato = $_GET['id_control_contrato'];

$sql = "SELECT";
$sql .= " cl_contratos_acciones.nombre,";
$sql .= " DATE_FORMAT(cl_control_contratos_detalles.fecha_movimiento,'%d/%m/%Y'),";
$sql .= "IF(cl_control_contratos_detalles.id_cliente_tecnico='0',";
$sql .= "(SELECT CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) FROM ad_entidades_financieras WHERE id_entidad_financiera = cl_control_contratos_detalles.id_cliente AND activo = 1),";
$sql .= "(SELECT CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) FROM ad_clientes WHERE id_cliente = cl_control_contratos_detalles.id_cliente AND activo = 1)";
$sql .= "),";
$sql .= " cl_control_contratos_detalles.id_usuario_alta,";
$sql .= " CONCAT(sys_usuarios.nombres,' ',sys_usuarios.apellido_paterno,' ',sys_usuarios.apellido_materno),";
$sql .= " cl_control_contratos_detalles.id_detalle,";
$sql .= " cl_control_contratos_detalles.id_contra_recibo,";
$sql .= " cl_control_contratos_detalles.id_control_contrato,";
$sql .= " cl_control_contratos_detalles.ultimo_movimiento,";
$sql .= " cl_control_contratos_detalles.id_tipo_activacion,";
$sql .= " cl_control_contratos_detalles.id_accion_contrato,";
$sql .= " cl_control_contratos.contrato";
$sql .= " FROM cl_control_contratos";
$sql .= " INNER JOIN cl_control_contratos_detalles";
$sql .= " ON cl_control_contratos.id_control_contrato = cl_control_contratos_detalles.id_control_contrato";
$sql .= " INNER JOIN cl_contratos_acciones";
$sql .= " ON cl_control_contratos_detalles.id_accion_contrato = cl_contratos_acciones.id_accion_control";
$sql .= " INNER JOIN sys_usuarios";
$sql .= " ON cl_control_contratos_detalles.id_usuario_alta = sys_usuarios.id_usuario";
//$sql .= " INNER JOIN cl_tipos_activacion";
//$sql .= " ON cl_control_contratos_detalles.id_tipo_activacion = cl_tipos_activacion.id_tipo_activacion";
$sql .= " WHERE cl_control_contratos_detalles.id_control_contrato = '".$id_control_contrato."'";
$sql .= " AND cl_control_contratos_detalles.activo = '1'";
$sql .= " ORDER BY ultimo_movimiento DESC;";
//$sql .= " ORDER BY fecha_movimiento;";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$numeroDeRegistros = count($result);
$smarty -> assign("filas", $result);
$smarty -> assign("numeroDeRegistros", $numeroDeRegistros);
$smarty -> assign("id_control_contrato", $id_control_contrato);
$smarty -> assign("sql", $sql);
echo json_encode($smarty->fetch('especiales/respuestaMuestraHistorialContrato.tpl'));
?>