<?
include("../../conect.php");
include("../../code/general/funciones.php");
include("../../consultaBase.php");
extract($_GET);
extract($_POST);
$cuenta_contable=$_POST['cuenta_contable'];
$options='';
if($cuenta_contable!=''){
	$sql="
		SELECT id_cuenta_contable,cuenta_contable,nombre FROM scfdi_cuentas_contables WHERE cuenta_contable like '%".$cuenta_contable."%'";
		
	$result = new consultarTabla($sql);
	$a_result = $result -> obtenerArregloRegistros();
	for($i=0;$i<count($a_result);$i++){
		$options.='<option value="'.$a_result[$i]['id_cuenta_contable'].'">'.$a_result[$i]['cuenta_contable'].":  ".$a_result[$i]['nombre'].'</option>';
	}
}
echo $options;
?>