<?php
include("../../../conect.php");
include("../../general/funciones.php");
include("../../../consultaBase.php");

// ***   Lista de brakets   *** //
$arryIdsBraket = array();
$arryNombresBraket = array();

$queryBrakets='
	SELECT id_braket_vetv, nombre FROM cl_braket_vetv WHERE activo = 1
';
$resultBrakets = mysql_query($queryBrakets);

while($datosBrakets = mysql_fetch_array($resultBrakets)){
	array_push($arryIdsBraket,$datosBrakets['id_braket_vetv']);
	array_push($arryNombresBraket,$datosBrakets['nombre']);
}

$smarty->assign("arryIdsBraket",$arryIdsBraket);
$smarty->assign("arryNombresBraket",$arryNombresBraket);
// ***   Termina Lista de brakets   *** //

$smarty -> display("especiales/liberaciones/bonosVolumenPendientesFacturar.tpl");
?>