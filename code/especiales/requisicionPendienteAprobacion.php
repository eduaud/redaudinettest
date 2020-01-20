<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
//mysql_query("SET NAMES utf8");

// SE OBTIENEN LAS SUCURSALES -------------------------------
$sql = "SELECT id_sucursal,nombre FROM ad_sucursales WHERE activo=1 ORDER BY nombre";
$datosSucursal = new consultarTabla($sql);
$result = $datosSucursal -> obtenerArregloRegistros();
$smarty -> assign("sucursal", $result);

// SE OBTIENEN LOS USUARIOS SOLICITANTES --------------------
$sql = "SELECT id_usuario, CONCAT(nombres,' ',apellido_paterno,' ',apellido_materno) 'Nombre'";
$sql .= " FROM sys_usuarios";
$sql .= " WHERE activo = 1";
$sql .= " ORDER BY nombre";
$datosUsuarios = new consultarTabla($sql);
$result = $datosUsuarios -> obtenerArregloRegistros();
$smarty -> assign("usuario", $result);

//SE OBTIENEN LAS REQUISICIONES PENDIENTES DE APROBACION -------------------------
$sql = "SELECT";
$sql .= " ADS.nombre 'Plaza Solicitante',";
$sql .= " id_requisicion 'ID Requisición',";
$sql .= " DATE_FORMAT(fecha_de_creacion,'%d/%m/%Y') 'Fecha de Creación',";
$sql .= " DATE_FORMAT(fecha_requerida,'%d/%m/%Y') 'Fecha Requerida',";
$sql .= " CONCAT(SU.nombres,' ',SU.apellido_paterno,' ',SU.apellido_materno) 'Usuario Solicitante'";
$sql .= " FROM ad_requisiciones ADR";
$sql .= " INNER JOIN ad_sucursales ADS";
$sql .= " ON ADR.id_sucursal = ADS.id_sucursal";
$sql .= " INNER JOIN sys_usuarios SU";
$sql .= " ON ADR.id_usuario_solicita = SU.id_usuario";
$sql .= " WHERE ADR.id_estatus_requisicion = 1";
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);

$smarty -> display("especiales/requisicionPendienteAprobacion.tpl");
?>