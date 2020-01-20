<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$caso = $_POST['caso'];

if($caso == 1){
		$sql = "SELECT id_control_factura, id_factura, DATE_FORMAT(fecha_y_hora,'%d/%m/%Y') AS 'Fecha', CONCAT('$', FORMAT(total, 2)) AS 'Total' 
				FROM ad_facturas WHERE id_cliente = " . $id;
		$template = "respuestaTablaFacturas";
		}
else if($caso == 2){
		$sql = "SELECT id_detalle_factura, na_productos.nombre AS producto, IF(na_facturas_detalle.descripcion IS NULL, '-', na_facturas_detalle.descripcion), na_facturas_detalle.cantidad, CONCAT('$', FORMAT(na_facturas_detalle.importe, 2)) AS importe, id_control_factura
				FROM na_facturas_detalle
				LEFT JOIN na_productos ON na_facturas_detalle.id_producto = na_productos.id_producto
				WHERE id_control_factura = " . $id;
		$template = "respuestaTablaFacturasDetalles";
		}

		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();
$smarty -> assign("filas", $result);
echo $smarty->fetch('especiales/' . $template . '.tpl');