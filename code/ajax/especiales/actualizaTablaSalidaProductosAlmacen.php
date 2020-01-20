<?php

extract($_GET);
extract($_POST);

include("../../../conect.php");
include("../../../code/general/funciones.php");
include("../../../code/especiales/funcionesRent.php");


$strSQL= "SELECT id_control_orden_servicio,
								id_orden_servicio, 
								CONCAT(rac_clientes.nombre,' - ', nombre_comercial) as cliente,
								DATE_FORMAT(fecha_hora_creacion, '%d/%m/%Y'),
								CONCAT(DATE_FORMAT(fecha_entrega_articulos, '%d/%m/%Y') ,' ',rac_tiempos.nombre) as entrega
						  FROM rac_ordenes_servicio 
						  LEFT JOIN rac_clientes 
						  ON rac_clientes.id_cliente=rac_ordenes_servicio.id_cliente 
						  left join rac_tiempos on id_tiempo=hora_entrega
						  where  id_orden_servicio=" .$id_control_orden_servicio. "  AND estatus_orden = 1"  ; 

		$registros=array();
		$res=mysql_query($strSQL) or die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
		$num=mysql_num_rows($res);
		for($i=0;$i<$num;$i++)
		{
			$registrosDetalle=array();
			$row=mysql_fetch_row($res);
			//obtenemos el registro de los articulos
			//--
			$strSQL="SELECT id_detalle, 
												id_control_orden_servicio,
												id_articulo_det, 
												codigo_articulo, 
												nombre, 
												cantidad_solicitada,
												if(existencia is null,0,existencia), 
												if(cantidad_entregada is null,0,cantidad_entregada),
												cantidad_solicitada-if(cantidad_entregada is null,0,-1*cantidad_entregada)as faltanteSurtir 
												FROM (
														SELECT id_detalle,
																		id_control_orden_servicio,
																		detalle.id_articulo as id_articulo_det,
																		codigo_articulo,nombre,
																		cantidad_solicitada,
																			(SELECT sum(cantidad*signo) 
																			FROM na_movimientos_almacen_detalle
																			left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
																			where no_modificable =1 
																			and id_almacen = 1 
																			and id_articulo=detalle.id_articulo) 
																		as existencia,
																			(SELECT sum(cantidad*signo) 
																			FROM na_movimientos_almacen_detalle
																			left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
																			where no_modificable =1 
																			and id_almacen = 1 
																			and id_articulo=detalle.id_articulo and id_subtipo_movimiento=130) 
																		as cantidad_entregada
														FROM rac_ordenes_servicio_detalle_articulos detalle
														left join rac_articulos on detalle.id_articulo=rac_articulos.id_articulo
														where id_control_orden_servicio=".$id_control_orden_servicio."
												) as dat";
			
			$resDetalle=mysql_query($strSQL) or die("Error en:<br><i>$strSQL</i><br><br>Descripcion:<br><b>".mysql_error());
			$numDetalle=mysql_num_rows($resDetalle);
			
			$sqlAlmacenesNeteables = "SELECT id_almacen FROM rac_almacenes WHERE id_tipo_almacen = 1";
			$oAlmacenesNeteables = mysql_query($sqlAlmacenesNeteables) or die("Error en:<br><i>$sqlAlmacenesNeteables</i><br><br>Descripcion:<br><b>".mysql_error());
			$numAlmacenesNeteables = mysql_num_rows($oAlmacenesNeteables);
			
			for($j=0;$j<$numDetalle;$j++)
			{
				
				$aDetalleExistencias = array();
				$rowDetalle=mysql_fetch_row($resDetalle);
				
				//Buscamos la existencia restante de cada articulo en los diferentes almacenes neteables
				
				while($aAlmacenesNeteables=mysql_fetch_row($oAlmacenesNeteables)){
						
						$sqlExistenciasAlmacen = "SELECT isnull(sum(cantidad*signo),0)
															FROM na_movimientos_almacen_detalle
															LEFT JOIN na_movimientos_almacen ON na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
															WHERE id_articulo=". $rowDetalle[2] . "
															AND id_almacen=" . $aAlmacenesNeteables[0];
				}

				array_push($registrosDetalle,$rowDetalle);
				
			}
			array_push($row,$registrosDetalle);
			//colocamos el row en el detalle del registro
			array_push($registros,$row);
			//--
			
		}

		$smarty->assign("registros",$registros);
echo $smarty->fetch("especiales/ajax/tablaPreSurtido.tpl");
		
?>