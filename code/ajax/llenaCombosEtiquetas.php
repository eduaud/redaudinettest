<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT ad_lista_precios.id_lista_precios, ad_lista_precios.nombre 
		FROM ad_lista_precios 
		LEFT JOIN na_listas_detalle_sucursales ON ad_lista_precios.id_lista_precios = na_listas_detalle_sucursales.id_lista_precios 
		WHERE activo = 1 AND na_listas_detalle_sucursales.id_sucursal = $id ORDER BY nombre";
				
echo '<option value="0">Selecciona una opci&oacute;n</option>';


$datos = new consultarTabla($sql);
$result = $datos -> obtenerArregloRegistros();

foreach($result as $opcion){
		echo "<option value='" . $opcion[0] . "'>" . utf8_encode($opcion[1]) . "</option>";
		}

?>