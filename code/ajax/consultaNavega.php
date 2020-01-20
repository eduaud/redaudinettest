<?php

include("../../conect.php");

$id_consulta_siguiente = $_GET['id'];
$navega3 = $_GET['navega2'];

//Ordenamos los datos por orden alfabetico

$sql = "SELECT id_articulo FROM rac_articulos ORDER BY codigo_articulo";
$result=mysql_query($sql) or die("error en: $sql<br><br>".mysql_error());	  


//Dentro del ciclo llenamos el array con los datos de la consulta

while($row = mysql_fetch_array($result)){
		$datos = $row[0];
		$array[] = $datos;
		}

// Con array search buscamos la posicion del id que traemos por get		
		
$posicion=array_search($id_consulta_siguiente, $array);
$total = count($array);

if($navega3 == "s"){
		if($posicion == $total-1)
				echo $array[0];
		else
				echo $array[$posicion+1];
		}
else if($navega3 == "a"){
		if($posicion == 0)
				echo $array[$total-1];
		else
				echo $array[$posicion-1];
		}


?>

