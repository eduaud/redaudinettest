<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id_pedido = $_POST['id_pedido'];
$prepedido = $_POST['prepedido'];

$sql = "UPDATE na_pre_pedidos SET id_pedido = '$id_pedido' WHERE id_pre_pedido = $prepedido";
mysql_query($sql);
		


?>