<?php

include("../../conect.php");

$id = $_POST['id'];
$identificador = $_POST['op'];

if($op == "li")
		$sql = "SELECT id_categoria_articulo, nombre FROM rac_articulos_categorias WHERE activo = 1 AND id_linea_articulo = $id ORDER BY nombre";
		
else if($op == "sub")
		$sql = "SELECT id_subcategoria_articulo, nombre FROM rac_articulos_subcategorias WHERE activo = 1 AND id_categoria_articulo = $id ORDER BY nombre";
		
$result = mysql_query($sql) or die("Error en consulta $sql\nDescripcion:".mysql_error());	
$contador = mysql_num_rows($result);

if($contador <= 0){
		echo "<option value='0'>Todos</option>";
		}
else{
		echo "<option value='0'>Todos</option>";
		while($row = mysql_fetch_array($result)){
				
				echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
				}
		}

?>