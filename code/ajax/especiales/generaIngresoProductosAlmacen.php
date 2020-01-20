<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../code/especiales/funcionesRent.php");

/*
		echo $productosIngresarDetalle[$i]['id_detalle'];
		echo $productosIngresarDetalle[$i]['id_articulo'];
		echo $productosIngresarDetalle[$i]['cantidad_ingresar'];
		echo $productosIngresarDetalle[$i]['descripcion'];
		echo $productosIngresarDetalle[$i]['id_control_orden_servicio'];
*/


$productosIngresarDetalle = $_POST['arreglo'];
$id_control_orden_servicio =$productosIngresarDetalle[0]['id_control_orden_servicio'];


$numDetalle = count($productosIngresarDetalle);

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
								1,
								24,
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


for($i=0;$i<$numDetalle;$i++)
{
		
		if($productosIngresarDetalle[$i]['cantidad_ingresar'] != 0){
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
														".$productosIngresarDetalle[$i]['id_articulo'].", 
														".$productosIngresarDetalle[$i]['cantidad_ingresar'].", 
														".$productosIngresarDetalle[$i]['id_detalle'].", 
														1)";
					mysql_query($strInsertDetalle) or rollback('strInsertDetalle',mysql_error(),mysql_errno(),$strInsertDetalle );
		}
						
}

echo  $idControl;
?>