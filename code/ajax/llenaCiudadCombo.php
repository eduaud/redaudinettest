<?php

include("../../conect.php");


$id = $_POST['id'];

$strConsulta="SELECT id_ciudad,nombre FROM sys_ciudades where activo=1 and id_estado ='".$id."' ORDER BY nombre";

$result = mysql_query($strConsulta) or die("Error en consulta $sql\nDescripcion:".mysql_error());	
$contador = mysql_num_rows($result);

if($contador <= 0){
		echo "<option value='0'>Selecciona una opci&oacute;n</option>";
		}
else{
		echo "<option value='0'>Selecciona una opci&oacute;n</option>";
		while($row = mysql_fetch_array($result)){
				
				echo "<option value='" . $row[0] . "'>" . utf8_encode($row[1]) . "</option>";
				}
		}

?>