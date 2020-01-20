<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$sucursales = $_POST['sucursales'];
$lista = $_POST['lista'];

$sqlBorrar = "DELETE FROM na_listas_detalle_sucursales WHERE id_lista_precios = " . $lista;
mysql_query($sqlBorrar) or die ("Error en la consulta: <br>$sqlBorrar. ".mysql_error());

$sql2 = "INSERT INTO na_listas_detalle_sucursales(id_lista_precios, id_sucursal) 
		SELECT " . $lista . " AS lista, id_sucursal FROM na_sucursales WHERE id_sucursal IN(" . $sucursales . ")";

mysql_query($sql2) or die ("Error en la consulta: <br>$sql2. ".mysql_error());
/*
for($i=0; $i<$contador; $i++){
		$sql2 = "INSERT INTO na_listas_detalle_sucursales(id_lista_precios, id_sucursal) VALUES($lista, $datos[$i])";
		echo $datos[$i];
		//mysql_query($sql2) or die ("Error en la consulta: <br>$sql2. ".mysql_error());
		}*/
echo "Se han guardado correctamente las sucursales";


?>