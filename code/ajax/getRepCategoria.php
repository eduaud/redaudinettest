<?php

include("../../conect.php");

$sucursal = $_POST['sucursal_id'];

$strSQL = "SELECT id_cliente, CONCAT(nombre, apellido_paterno, apellido_materno) AS 'nombre' 
			 FROM ad_clientes 
			WHERE id_sucursal_alta = $sucursal 
		 ORDER BY nombre";
$rs_cliente = mysql_query($strSQL) or die("Error en consulta $strSQL\nDescripcion:".mysql_error());	
$contador = mysql_num_rows($rs_cliente);

if($contador <= 0)
{
	echo "<option value='0'>Todos</option>";
}
else
{
	echo "<option value='0'>Todos</option>";
	while($row = mysql_fetch_array($rs_cliente)){
				
		echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
	}
}
?>