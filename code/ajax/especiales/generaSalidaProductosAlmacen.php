<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../code/especiales/funcionesRent.php");

/*
echo "id_detalle: " . $productosSurtirDetalle[$i]['id_detalle'] . "\n";
echo "Id_articulo: " . $productosSurtirDetalle[$i]['id_articulo'] . "\n";
echo "cantidad_surtir: " . $productosSurtirDetalle[$i]['cantidad_surtir'] . "\n";
echo "descripcion: " . $productosSurtirDetalle[$i]['descripcion'] . "\n";
echo "id_control_orden_servicio: " . $productosSurtirDetalle[$i]['id_control_orden_servicio'] . "\n";
*/

$productosSurtirDetalle = $_POST['arreglo'];
$id_control_orden_servicio =$productosSurtirDetalle[0]['id_control_orden_servicio'];
$numDetalle = count($productosSurtirDetalle);

$strInsert="INSERT INTO rac_movimientos_almacen (
								id_control_movimiento,
								id_movimiento,
								id_tipo_movimiento,
								id_subtipo_movimiento,
								id_almacen,
								id_tipo_almacen,
								fecha_movimiento,
								hora_movimiento,
								id_tiempo,
								id_usuario,
								id_control_orden_servicio,
								no_modificable)
								VALUES
								(
								NULL,
								0,
								2,
								130,
								1,
								1,
								NOW(),
								NOW(),
								1,
								".$_SESSION["USR"]->userid.",
								".$id_control_orden_servicio.",
								1
								)";
								
				mysql_query($strInsert) or rollback('strInsert',mysql_error(),mysql_errno(),$strInsert );
				$idControl=mysql_insert_id();
				asignaConsecutivoMovimientoAlmacen($idControl,2,1);

				
for($i=0;$i<$numDetalle;$i++){
				
		if($productosSurtirDetalle[$i]['cantidad_surtir'] != 0){
				//seleccionamos el maximo movimiento generado
				//colocamos la entrada al almacen
				$strInsertDetalle="INSERT INTO rac_movimientos_almacen_detalle 
														(id_detalle,
														id_control_movimiento,
														id_articulo, 
														cantidad, 
														id_detalle_tipo_documento, 
														signo)
											VALUES
														( NULL, 
														".$idControl.", 
														".$productosSurtirDetalle[$i]['id_articulo'].", 
														".$productosSurtirDetalle[$i]['cantidad_surtir'].", 
														".$productosSurtirDetalle[$i]['id_detalle'].", 
														-1)";
					mysql_query($strInsertDetalle) or rollback('strInsertDetalle',mysql_error(),mysql_errno(),$strInsertDetalle );
		}
		
}

echo $idControl;

?>