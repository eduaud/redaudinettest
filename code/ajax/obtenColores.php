<?php
php_track_vars;
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");

extract($_GET);
extract($_POST);
if($id_color!=0){
	$sql="SELECT codigo_color FROM cl_colores WHERE id_color=".$id_color;
	$datos = new consultarTabla($sql);
	$colores = $datos-> obtenerArregloRegistros();
	echo $color=$colores[0][0];
}
 ?>