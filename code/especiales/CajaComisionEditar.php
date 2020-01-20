<?php 
extract($_GET);
extract($_POST);

include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
$smarty->display('especiales/CajaComisionesEditar.tpl');
?>