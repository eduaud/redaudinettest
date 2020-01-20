<?php

include("../../conect.php");

$id_linea = $_POST['linea'];
$id_categoria = $_POST['categoria'];
$id_subcategoria = $_POST['subcategoria'];

$where = "";



if($id_categoria <> 0){
		$where .= " AND id_categoria_articulo = $id_categoria ";
		}
if($id_subcategoria <> 0){
		$where .= " AND id_subcategoria_articulo = $id_subcategoria ";
		}

		$sql = "SELECT id_articulo, nombre FROM rac_articulos WHERE id_linea_articulo = $id_linea " . $where . " ORDER BY nombre";

$result = mysql_query($sql) or die("Error en consulta $sql\nDescripcion:".mysql_error());	

while($row = mysql_fetch_array($result)){
				echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
				}


?>
