<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	if($tipo=='1')
	{
		
		//var respuesta=ajaxR("../ajax/obtenExistenciaAlmacenes.php?tipo=1&id_almacen="+id_almacen+"&id_pto="+id_pto+"&cantidad="+cantidad);
		
		
		//tomamos encuenta todosa las variables
		
		//vemos si el articulo es compuesto.
		$strSQL="SELECT id_articulo,es_compuesto FROM rac_articulos where id_articulo='".$id_pto."'";
		
		$arrCompuesto=valBuscador($strSQL);
		
		if($arrCompuesto[1]==1)
		{
			//es articulo compuesto, buscamos todos los articulos y las cantudades que lo conforman
			
		}
		else
		{	
			$strConsulta="SELECT sum(monto) FROM (
	(SELECT if(sum(cantidad) is null,0,sum(cantidad)) as monto FROM spa_almacenes_entradas
	left join  spa_almacenes_entradas_detalle on spa_almacenes_entradas.id_control_entrada=spa_almacenes_entradas_detalle.id_control_entrada
	where id_almacen_entrada='".$id_almacen."' and id_producto='".$id_pto."')
	UNION ALL
	(SELECT if(sum(cantidad) is null,0,-1*sum(cantidad)) as monto FROM spa_almacenes_salidas
	left join  spa_almacenes_salidas_detalle on spa_almacenes_salidas.id_control_salida=spa_almacenes_salidas_detalle.id_control_salida
	where id_almacen_salida='".$id_almacen."' and id_producto='".$id_pto."') ) as existencia";
	
			
			$arrExistencia=valBuscador($strConsulta);
			
			if($cantidad>$arrExistencia[0])
			{
				echo "insuficiente|$arrExistencia[0]";
			}
			else
			{
				echo "exito|$arrExistencia[0]";
			}
		}
	}
	
		
	
	
?>