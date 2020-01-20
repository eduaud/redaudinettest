<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

//mysql_query("SET NAMES utf8");

$sqlGrupo = "SELECT id_grupo AS grupo, id_sucursal AS sucursal_S FROM sys_usuarios WHERE id_usuario = " . $_SESSION["USR"] -> userid;
$datosGrupo = new consultarTabla($sqlGrupo);	
$grupo = $datosGrupo -> obtenerLineaRegistro();

$where = "";

if($grupo['sucursal_S'] != 0)
		$where .= " AND id_sucursal = " . $grupo['sucursal_S'];

$sql = "SELECT id_sucursal, na_sucursales.nombre AS sucursal
		FROM na_solicitud_devolucion_cedis 
		LEFT JOIN na_sucursales USING(id_sucursal)
		WHERE na_solicitud_devolucion_cedis.id_estatus_devolucion = 1" . $where;
$datosSuc = new consultarTabla($sql);
$sucursal = $datosSuc -> obtenerRegistros();

foreach($sucursal as $registros){
		$arrSucId[] = $registros -> id_sucursal;
		$arrSuc[] = $registros -> sucursal;			
		}		
$smarty->assign('sucursal_id', $arrSucId);
$smarty->assign('sucursal_nombre',$arrSuc);	



$sql2 = "SELECT id_solicitud_devolucion_cedis AS id_solicitud, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha, na_sucursales.nombre AS sucursal,
		na_tipos_devoluciones.nombre AS tipo, CONCAT(DATE_FORMAT(fecha_propuesta_recoleccion, '%d/%m/%Y'), ' ', na_tiempos.nombre, ' Hrs') AS fecha_hora,
		na_rutas.nombre AS ruta
		FROM na_solicitud_devolucion_cedis
		LEFT JOIN na_tipos_devoluciones USING(id_tipo_devolucion)
		LEFT JOIN na_sucursales USING(id_sucursal)
		LEFT JOIN na_rutas USING(id_ruta)
		LEFT JOIN na_tiempos ON na_solicitud_devolucion_cedis.hora_propuesta_recoleccion = na_tiempos.id_tiempo
		WHERE na_solicitud_devolucion_cedis.id_estatus_devolucion = 1";
		
$datos = new consultarTabla($sql2);
$result = $datos -> obtenerArregloRegistros();

$smarty -> assign('grupo', $grupo['grupo']);
$smarty -> assign("sucursal", $sucursal);
$smarty -> assign("filas", $result);
$smarty -> display("especiales/aprobacionSolicitudCedis.tpl");








?>