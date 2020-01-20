<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$idPrepedido = $_POST['idPrepedido'];
//CONCAT(ad_clientes.nombre, ' ' , ad_clientes.apellido_paterno, ' ' , ad_clientes.apellido_materno)

$sql = "SELECT nombre_cliente, total, id_sucursal, id_vendedor
		FROM na_pre_pedidos
		WHERE id_pre_pedido = " . $idPrepedido;
		
$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $consulta){
		echo utf8_encode($consulta -> nombre_cliente) . "|" . $consulta -> total . "|" . $consulta -> id_sucursal . "|" . $consulta -> id_vendedor;
		
		}
echo "#";

$sql2 = "SELECT na_productos.id_producto AS id_producto, na_productos.nombre AS producto, na_pre_pedidos_detalle_productos.cantidad AS cantidad, CONCAT(na_productos.id_producto, ':', na_productos.nombre) AS valor_editar, na_pre_pedidos.id_lista_precios AS lista_precio
		FROM na_pre_pedidos_detalle_productos
		LEFT JOIN na_productos ON na_pre_pedidos_detalle_productos.id_producto = na_productos.id_producto
		LEFT JOIN na_pre_pedidos ON na_pre_pedidos_detalle_productos.id_prepedido = na_pre_pedidos.id_pre_pedido
		WHERE na_pre_pedidos_detalle_productos.id_prepedido = " . $idPrepedido;
		
$datos2 = new consultarTabla($sql2);
$result2 = $datos2 -> obtenerRegistros();

$productos = array();
foreach($result2 as $consulta2){
		$productos[] =  utf8_encode($consulta2 -> id_producto) . "|" . utf8_encode($consulta2 -> producto) . "|" . $consulta2 -> cantidad . "|" . $consulta2 -> valor_editar . "|" . utf8_encode($consulta2 -> lista_precio);
		}
$productos2 = implode(",", $productos);

echo $productos2;

?>