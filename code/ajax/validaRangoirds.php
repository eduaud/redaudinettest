<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

extract($_GET);
extract($_POST);

$sql="SELECT id_rango_ird,dia_inicial,dia_final FROM cl_rangos_irds";
$datos = new consultarTabla($sql);
$result = $datos-> obtenerArregloRegistros();

for($i=0;$i<count($result);$i++){
	//if($inicio>=$result[$i][1]&&)
	if($fin>$inicio){
		if($result[$i][1]>$fin||($result[$i][1]<$inicio&&$result[$i][2]<$inicio))
				$resultado='exito';
		else
				$resultado='error';
	}
	$resultado='error';
}
echo $resultado;
?>