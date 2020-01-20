<?php
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

$tipo=$_GET["tipo"];
$numero=$_GET["numero"];

$smarty->assign("tipo",$tipo);
$smarty->assign("numero",$numero);

$smarty -> display("especiales/verPantalla.tpl");
?>