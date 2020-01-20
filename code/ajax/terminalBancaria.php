<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$sucursal = $_POST['sucursal'];
		
$sql = "SELECT na_terminales_bancarias.id_terminal_bancaria AS id, na_terminales_bancarias.nombre AS banco FROM na_terminales_bancarias WHERE id_sucursal = " . $sucursal;
$result = new consultarTabla($sql);

$resultBanco = $result -> obtenerRegistros();
		foreach($resultBanco as $campos){
				echo '<option value="' . $campos -> id . '">' . utf8_encode($campos -> banco) . '</option>';
				}	


		
		
		
?>