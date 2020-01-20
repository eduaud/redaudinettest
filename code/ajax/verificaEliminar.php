<?php

include("../../conect.php");

function regresaCatalogo($tabla_menu){
		
		$consulta = "SELECT nombre FROM sys_menus WHERE tabla_asociada = '" . $tabla_menu . "'";
		$resultado=mysql_query($consulta) or die("Error en:<br><i>$consulta</i><br><br>Descripcion:<b>".mysql_error());
		$cat = mysql_fetch_row($resultado);
		return $cat[0];
		}

$tabla = $_POST["tabla"];
$id = $_POST["id"];

$tabla = base64_decode($tabla);

if($tabla == "na_productos"){
		$sqlProd = "SELECT nombre 
					FROM na_productos_basicos_detalle 
					LEFT JOIN na_productos ON na_productos_basicos_detalle.id_producto = na_productos.id_producto
					WHERE id_producto_relacionado = " . $id;
		$resultProd = mysql_query($sqlProd) or die("Error en:<br><i>$sqlProd</i><br><br>Descripcion:<b>".mysql_error());
		$compuesto = mysql_fetch_row($resultProd);
		if(mysql_num_rows($resultProd) <= 0) {
				echo "SI";
				}
		else{
				echo "No se puede eliminar este producto\nya que es parte del producto compuesto:\n" . $compuesto[0];
				}
		}
else{

		$sql2 = "SELECT tabla, campo FROM sys_config_encabezados WHERE tabla_relacion = '$tabla'";
		$result2 = mysql_query($sql2) or die("Error en:<br><i>$sql2</i><br><br>Descripcion:<b>".mysql_error());
			
		$contador = 0;


		if(mysql_num_rows($result2) <= 0) {
				echo "SI";
				}
		else{
				$cat_relacionados = "";
				
				while ($row = mysql_fetch_assoc($result2)) {
						$sql3 = "SELECT " . $row['campo'] . " FROM " . $row['tabla'] . " WHERE " . $row['campo'] . " = " . $id;
						$result3 = mysql_query($sql3) or die("Error en:<br><i>$sql3</i><br><br>Descripcion:<b>".mysql_error());
						$registros = mysql_num_rows($result3);
						
						$texto1 = ($registros == 1) ? ' registro insertado' : ' registros insertados';
						
						$cat_relacionados .= "Catálogo " . utf8_encode(regresaCatalogo($row['tabla'])) . ": " . $registros . $texto1 ."\n";
						
						$contador = $contador + $registros;
						
						}
				if($contador <= 0){
						echo "SI";
						}
				else{
						$texto2 = "No puedes eliminar el registro por que esta ligado a los siguientes catálogos:\n\n";
						echo $texto2 . $cat_relacionados;
						}
				}
		}

?>