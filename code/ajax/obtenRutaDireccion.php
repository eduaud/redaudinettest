<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT na_rutas.id_ruta AS id_ruta, na_rutas.nombre AS ruta
		FROM ad_clientes_direcciones_entrega 
		LEFT JOIN na_rutas USING(id_ruta)
		WHERE id_cliente_direccion_entrega = $id";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $consulta){
		echo "<option value='" . $consulta -> id_ruta . "'>" . $consulta -> ruta . "</option>";
		}

?>