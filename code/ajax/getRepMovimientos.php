<?php

include("../../conect.php");

$cambio = "";

$id_tipoMov = $_POST['tipo_mov'];

$sql = "SELECT id_subtipo_movimiento, nombre FROM rac_almacenes_subtipos_movimientos WHERE id_tipo_movimiento = $id_tipoMov ORDER BY nombre";

$result = mysql_query($sql) or die("Error en consulta $sql\nDescripcion:".mysql_error());	

echo "<option value='0'>Todos</option>";
while($row = mysql_fetch_array($result)){
				echo "<option value='" . $row[0] . "'>" . utf8_encode($row[1]) . "</option>";
				}


?>