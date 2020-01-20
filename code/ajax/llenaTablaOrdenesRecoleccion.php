<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES utf8");

$orden = $_POST['idOrden'];
$provedor = $_POST['idCliente'];
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

if(!empty($orden)) {
		$where .= " AND id_orden_recoleccion_cliente = " . $orden;
		}
if($provedor != 0) {
		$where .= " AND na_ordenes_recoleccion_clientes.id_cliente = " . $provedor;
		}
if(!empty($fecini) && !empty($fecfin)) {
		$where .= " AND na_ordenes_recoleccion_clientes.fecha_generacion BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "'";
		}
if(!empty($fecini) && empty($fecfin)) {
		$where .= " AND na_ordenes_recoleccion_clientes.fecha_generacion = '" . $fecha_inicial . "'";
		}
if(empty($fecini) && !empty($fecfin)) {
		$where .= " AND na_ordenes_recoleccion_clientes.fecha_generacion ='" . $fecha_final . "'";
		}

$sql = "SELECT id_orden_recoleccion_cliente, fecha_generacion, CONCAT(ad_clientes.nombre, ' ', ad_clientes.apellido_paterno, ' ', ad_clientes.apellido_materno) AS cliente, 
		CONCAT('Calle: ',ad_clientes_direcciones_entrega.calle,' ', 'Num. Ext: ',if(ad_clientes_direcciones_entrega.numero_exterior is null,'',ad_clientes_direcciones_entrega.numero_exterior),' ',if(ad_clientes_direcciones_entrega.numero_interior is null,'',ad_clientes_direcciones_entrega.numero_interior),' ','Col. ',if(ad_clientes_direcciones_entrega.colonia is null,'',ad_clientes_direcciones_entrega.colonia),' ','Del. o Mun. ',if(ad_clientes_direcciones_entrega.delegacion_municipio is null,'',ad_clientes_direcciones_entrega.delegacion_municipio)) AS direccion_cliente,
		fecha_recoleccion, na_rutas.nombre AS ruta
		FROM na_ordenes_recoleccion_clientes
		LEFT JOIN ad_clientes ON na_ordenes_recoleccion_clientes.id_cliente = ad_clientes.id_cliente
		LEFT JOIN ad_clientes_direcciones_entrega ON na_ordenes_recoleccion_clientes.id_cliente_direccion_entrega = ad_clientes_direcciones_entrega.id_cliente_direccion_entrega
		LEFT JOIN na_estatus_ordenes_recoleccion ON na_ordenes_recoleccion_clientes.id_estatus_orden_recoleccion = na_estatus_ordenes_recoleccion.id_estatus_orden_recoleccion
		LEFT JOIN na_rutas ON na_ordenes_recoleccion_clientes.id_ruta = na_rutas.id_ruta
		WHERE na_ordenes_recoleccion_clientes.id_estatus_orden_recoleccion = 1" . $where;
		

$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();

$smarty -> assign("filas", $result);
echo $smarty->fetch('especiales/respuestaTablaOrdenesRecoleccion.tpl');


?>