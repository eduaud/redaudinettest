<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT na_planos.id_plano, na_planos.plano FROM na_rutas_detalle_planos LEFT JOIN na_planos ON na_rutas_detalle_planos.id_plano = na_planos.id_plano WHERE id_ruta = $id";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $consulta){
		echo "<option value='" . $consulta -> id_plano . "'>" . $consulta -> plano . "</option>";
		}

?>