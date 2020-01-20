<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");


$productosSurtirDetalle = $_POST['arreglo'];

$numDetalle = count($productosSurtirDetalle);

for($i=0;$i<$numDetalle;$i++)
{
					
		//Solo hace consulta al almacen neteable (CEDIS)
		$sqlExistenciasAlmacen = "SELECT COALESCE(sum(cantidad*signo),0) existencia
											FROM na_movimientos_almacen_detalle
											LEFT JOIN na_movimientos_almacen ON  na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
											WHERE id_articulo=". $productosSurtirDetalle[$i]['id_articulo'] . "
											AND id_almacen=1";
		
		$res=mysql_query($sqlExistenciasAlmacen) or die("Error en:<br><i>$sqlExistenciasAlmacen</i><br><br>Descripcion:<br><b>".mysql_error());
		
		$mensaje = "";
		
		while($existenciaOk = mysql_fetch_array($res)){
		
			if($productosSurtirDetalle['cantidad_surtir']  >  $existenciaOk[0]){
				$mensaje .= "Articulo: " . $productosSurtirDetalle['descripcion'] . "existencia "  . $existenciaOk[0] . " \n";
			}
			
		}									
}

if($mensaje != ""){
	echo  "Las existencias de los siguientes articulos es menor a la que debe de surtirse:  \n" . $mensaje;
}


?>