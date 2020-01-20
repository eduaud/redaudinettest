<?php 
extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../consultaBase.php");
$sql="SELECT id_control,t46,t47,t48,t52,t49,n4,t50,t51,dc16,dc17,dc18,dc19,dc20,dc23,dc24,dc25,dc26,dc27,dc28,dc29,dc30,dc31,dc32,dc33,dc34,dc35,dc21,dc22,t53 FROM cl_importacion_caja_comisiones WHERE id_control=".$id_control;
$Caja = new consultarTabla($sql);
$aCaja = $Caja -> obtenerArregloRegistros();
$smarty->assign('a_cajas',$aCaja);
$smarty->assign('accion',$caso);
echo $smarty->fetch('especiales/CajaComisionesEditar.tpl');
?>