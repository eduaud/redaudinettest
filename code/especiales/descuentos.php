<?php

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$referencia = $_GET['referencia'];
$cliente = $_GET['cliente'];
$total = $_GET['total'];
$sucursal = $_GET['sucursal'];

$smarty -> assign("usuarioR", $_SESSION["USR"]->userid);
$smarty -> assign("sucursal", $sucursal);
$smarty -> assign("referencia", $referencia);
$smarty -> assign("cliente", $cliente);
$smarty -> assign("total", $total);

$smarty -> display("especiales/descuentos.tpl");

?>