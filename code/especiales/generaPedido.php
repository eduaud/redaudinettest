<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

if($_SESSION["USR"]->sucursalid == 0){
		$consultaPre = "SELECT CONCAT(na_vendedores.nombre, ' ', na_vendedores.apellido_paterno, ' ', na_vendedores.apellido_materno) AS vendedor,
						nombre_cliente, CONCAT(date_format(fecha, '%d/%m/%Y'), ' -- ', hora), id_pre_pedido, CONCAT('$', FORMAT(total, 2)) 
						FROM na_pre_pedidos 
						LEFT JOIN na_vendedores ON na_pre_pedidos.id_vendedor = na_vendedores.id_vendedor
						WHERE na_pre_pedidos.activo = 1 AND id_pedido IS NULL ORDER BY hora, fecha DESC";
		}
else{
		$consultaPre = "SELECT CONCAT(na_vendedores.nombre, ' ', na_vendedores.apellido_paterno, ' ', na_vendedores.apellido_materno) AS vendedor,
						nombre_cliente, CONCAT(date_format(fecha, '%d/%m/%Y'), ' -- ', hora), id_pre_pedido, CONCAT('$', FORMAT(total, 2)) 
						FROM na_pre_pedidos 
						LEFT JOIN na_vendedores ON na_pre_pedidos.id_vendedor = na_vendedores.id_vendedor
						WHERE na_pre_pedidos.activo = 1 AND id_sucursal = " . $_SESSION["USR"]->sucursalid . " AND (id_pedido = '' OR id_pedido IS NULL) ORDER BY hora, fecha DESC";
		}
$datosPre = new consultarTabla($consultaPre);
$prepedido = $datosPre -> obtenerArregloRegistros();

$smarty -> assign("prepedido", $prepedido);

$smarty -> display("especiales/generaPedido.tpl");

?>

