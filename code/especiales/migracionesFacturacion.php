<?php
include("../../conect.php");
include("../general/funciones.php");
include("../../consultaBase.php");

// ***   Array Remesas   *** //
$arr_remesas = array();
$query = "
	SELECT DISTINCT(remesa) AS remesas
	FROM cl_importacion_migraciones
	WHERE remesa != '' AND remesa IS NOT NULL
	ORDER BY remesa
";

$result = mysql_query($query);

while($datos = mysql_fetch_array($result)){
	array_push($arr_remesas, $datos["remesas"]);
}

$smarty->assign('arr_remesas',$arr_remesas);
// ***   Termina Array Remesas   *** //

$smarty -> display("especiales/migracionesFacturacion.tpl");
?>