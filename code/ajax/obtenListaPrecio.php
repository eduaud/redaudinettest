<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");


$id = $_POST['id'];
$caso = $_POST['caso'];

if($caso == 1){
		$sucursal = $_POST['sucursal'];
		$sql = "SELECT na_listas_detalle_productos.id_lista_precios, ad_lista_precios.nombre,id_producto, precio_final
from na_listas_detalle_productos
left join ad_lista_precios on na_listas_detalle_productos.id_lista_precios=ad_lista_precios.id_lista_precios
left join na_listas_detalle_sucursales on na_listas_detalle_sucursales.id_lista_precios=ad_lista_precios.id_lista_precios
WHERE id_producto = " . $id . " and id_sucursal=" . $sucursal . " 
UNION ALL
SELECT 1, 'Lista de Precios Publica', id_producto, precio_lista 
FROM na_productos
LEFT JOIN na_listas_detalle_sucursales on na_listas_detalle_sucursales.id_lista_precios = 1
WHERE id_producto = " . $id . " AND na_listas_detalle_sucursales.id_sucursal = " . $sucursal;
		
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();

		
		foreach($result as $dato){
				echo '<option value="' . $dato -> id_lista_precios . '">' . utf8_decode($dato -> nombre) . ' : $' . number_format($dato -> precio_final, 2) . '</option>';
				}
		}
else if($caso == 2){
		$lista = $_POST['lista'];
		if($lista == 1){
				$sql = "SELECT precio_lista AS precio_final FROM na_productos WHERE id_producto = " . $id;
				}
		else{
				$sql = "SELECT na_listas_detalle_productos.precio_final
				FROM na_listas_detalle_productos
				LEFT JOIN ad_lista_precios ON na_listas_detalle_productos.id_lista_precios = ad_lista_precios.id_lista_precios
				WHERE na_listas_detalle_productos.id_producto = " . $id . " AND na_listas_detalle_productos.id_lista_precios = " . $lista;
				}
		
		
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		foreach($result as $dato){
				echo number_format($dato -> precio_final, 2);
				}
		}
else if($caso == 3){
		$producto = $_POST['producto'];
		$sql = "SELECT nombre FROM ad_lista_precios WHERE id_lista_precios = " . $id;
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerLineaRegistro();
		
		if($id == 1){
				$sql2 = "SELECT CONCAT('$', FORMAT(precio_lista,2)) AS precio FROM na_productos WHERE id_producto = " . $producto;
				$datos2 = new consultarTabla($sql2);
				$result2 = $datos2 -> obtenerLineaRegistro();
				}
		else{
				$sql2 = "SELECT CONCAT('$', FORMAT(precio_final,2)) AS precio FROM na_listas_detalle_productos WHERE id_producto = " . $producto . " AND id_lista_precios = " . $id;
				$datos2 = new consultarTabla($sql2);
				$result2 = $datos2 -> obtenerLineaRegistro();
				}
		
		echo $result['nombre'] . " : " . $result2['precio'];
		
		}


?>