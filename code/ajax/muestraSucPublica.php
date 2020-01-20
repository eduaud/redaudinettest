<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sqlSuc = "SELECT id_sucursal FROM na_listas_detalle_sucursales WHERE id_lista_precios = $id";

$datosSuc = new consultarTabla($sqlSuc);
$resultSuc = $datosSuc -> obtenerRegistros();

$i = 0;
foreach($resultSuc as $campo){
		$sucursal[$i] = $campo -> id_sucursal;
		$i++;
		}		

echo json_encode($sucursal);


?>