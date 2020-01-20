<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$producto = $_POST["producto"];
$proveedor = $_POST["proveedor"];

$sql = "SELECT precio_unitario, porcentaje_descuento, precio_final 
		FROM na_ordenes_compra_producto_detalle
		LEFT JOIN na_ordenes_compra ON na_ordenes_compra_producto_detalle.id_orden_compra = na_ordenes_compra.id_orden_compra
		WHERE id_producto = " . $producto . " AND id_proveedor = " . $proveedor . " ORDER BY id_orden_compra_producto_detalle DESC";

$datos = new consultarTabla($sql);
$contador = $datos -> cuentaRegistros();
$registros = $datos -> obtenerLineaRegistro();

if($contador == 0){
		$productos['estatus'] = 0;
		}
else{
		$productos['estatus'] = 1;
		$productos['unitario'] = $registros['precio_unitario'];
		$productos['descuento'] = $registros['porcentaje_descuento'];
		$productos['finalp'] = $registros['precio_final'];
		}

echo json_encode($productos);


?>
