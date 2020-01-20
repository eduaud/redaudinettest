<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$caso = $_POST['caso'];

if($caso == 1)
		$sql = "SELECT na_rutas.id_ruta AS id, na_rutas.nombre AS nombre FROM na_rutas 
				LEFT JOIN na_rutas_detalle_ciudades ON na_rutas.id_ruta = na_rutas_detalle_ciudades.id_ruta
				LEFT JOIN na_ciudades ON na_rutas_detalle_ciudades.id_ciudad = na_ciudades.id_ciudad
				WHERE na_rutas.activo=1 AND na_rutas_detalle_ciudades.id_ciudad = " . $id;
else if($caso == 2)
		$sql = "SELECT na_planos.id_plano AS id, na_planos.plano AS nombre FROM na_planos 
				LEFT JOIN na_rutas_detalle_planos ON na_planos.id_plano = na_rutas_detalle_planos.id_plano
				LEFT JOIN na_rutas ON na_rutas_detalle_planos.id_ruta = na_rutas.id_ruta
				WHERE na_planos.activo=1 AND na_rutas_detalle_planos.id_ruta = "  . $id;
$datos = new consultarTabla($sql);
$result = $datos -> obtenerRegistros();

foreach($result as $opcion){
		echo "<option value='" . $opcion -> id . "'>" . utf8_decode($opcion -> nombre) . "</option>";
		}