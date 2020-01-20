<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$prepedido = $_GET['prepedido'];

$smarty -> assign("prepedido", $prepedido);

$smarty -> display("especiales/pedidos.tpl");

?>