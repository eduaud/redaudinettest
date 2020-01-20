<?
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
extract($_GET);
extract($_POST);

$date1 = DateTime::createFromFormat('d/m/Y',$fechaI);
$date2 = DateTime::createFromFormat('d/m/Y',$fechaF);
$fecI=$date1->format('Y-m-d');
$fecF=$date2->format('Y-m-d');

$fI=strtotime($fecI);
$fF=strtotime($fecF);
if($fI<$fF){
	if($id_caja=='')
		$where="1";
	else
		$where="id_caja_comision<>".$id_caja;
	$sql="
		SELECT id_caja_comision,fecha_inicio,fecha_fin FROM cl_cajas_comisiones WHERE ".$where;
		
	$result = new consultarTabla($sql);
	$a_result = $result -> obtenerArregloRegistros();
	if(count($a_result)>0){
		for($i=0;$i<count($a_result);$i++){
			$fechaInicio= strtotime($a_result[$i]['fecha_inicio']);
			$fechaFin= strtotime($a_result[$i]['fecha_fin']);
			
			if($fI<$fechaInicio&&$fF<$fechaInicio){
				$result='exito';
			}
			else if($fI>$fechaFin&&$fF>$fechaFin){
				$result='exito';
			}
			else{
				$result='error';
				$i=count($a_result);
			}
		}
	}else{
		$result='exito';
	}
}
else
	echo "La fecha de inicio debe ser menor que la fecha final";
echo $result;
?>