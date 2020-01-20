<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES utf8");

$solicitud = $_POST['idSolicitud'];
$sucursal = $_POST['idSucursal'];
$fecini = $_POST['fecini'];
$fecfin = $_POST['fecfin'];

$dia = substr($fecini, 0, 2);
$mes = substr($fecini, 3, 2);
$anio = substr($fecini, -4);

$fecha_inicial = $anio . "-" . $mes . "-" . $dia;

$dia = substr($fecfin, 0, 2);
$mes = substr($fecfin, 3, 2);
$anio = substr($fecfin, -4);

$fecha_final = $anio . "-" . $mes . "-" . $dia;

$where = "";

if(!empty($solicitud)) {
		$where .= " AND id_solicitud_devolucion_cedis = " . $solicitud;
		}
if($sucursal != 0) {
		$where .= " AND na_solicitud_devolucion_cedis.id_sucursal = " . $sucursal;
		}
if(!empty($fecini) && !empty($fecfin)) {
		$where .= " AND na_solicitud_devolucion_cedis.fecha BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
		}
if(!empty($fecini) && empty($fecfin)) {
		$where .= " AND na_solicitud_devolucion_cedis.fecha = '" . $fecha_inicial . "'";
		}
if(empty($fecini) && !empty($fecfin)) {
		$where .= " AND na_solicitud_devolucion_cedis.fecha ='" . $fecha_final . "'";
		}
		
$sql = "SELECT id_solicitud_devolucion_cedis AS id_solicitud, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha, na_sucursales.nombre AS sucursal,
		na_tipos_devoluciones.nombre AS tipo, CONCAT(DATE_FORMAT(fecha_propuesta_recoleccion, '%d/%m/%Y'), ' ', hora_propuesta_recoleccion) AS fecha_hora,
		na_rutas.nombre AS ruta
		FROM na_solicitud_devolucion_cedis
		LEFT JOIN na_tipos_devoluciones USING(id_tipo_devolucion)
		LEFT JOIN na_sucursales USING(id_sucursal)
		LEFT JOIN na_rutas USING(id_ruta)
		WHERE na_solicitud_devolucion_cedis.id_estatus_devolucion = 1" . $where;
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();

$smarty -> assign("filas", $result);
echo $smarty->fetch('especiales/respuestaTablaSolCedis.tpl');