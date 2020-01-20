<?php 
extract($_GET);
extract($_POST);
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
$smarty->assign("irds",$irds);
$smarty->assign('numeroRenglon',$numeroFilaGrid);
$smarty -> display("especiales/importacionGrid.tpl");
?>