<?php
	
	function obtenExistenciaUnProducto($id)
	{
		
		//-------------------
		$disponible=0;
		
		$strSQL="SELECT producto_compuesto FROM na_productos WHERE id_producto = '".$id."' ";
		$arrEsCompuesto=valBuscador($strSQL);
		
		if($arrEsCompuesto[0]==1)
		{
			//buscamos sus hijos del compuesto	
			unset($arrProductosHijos);
			$arrProductosHijos= array();
			$arrProductosHijos=obteneHijosProductoCompuesto($id);
			
			$existenciaFisica=obtenExistenciaCompuestaArrProductos($arrProductosHijos);
			$cantidadApartada =obtenApartadoProducto($id,1);
			
			//realizamos un recorrido para checar su existencia
			
		}
		else
		{
			//obtenemos la existencia fisica
			$existenciaFisica =obtenExistenciaProductoBasico($id);		
			//echo $existenciaFisica."---";
			
			//obtenemos la cantidad apartada del producto
			$cantidadApartada =obtenApartadoProducto($id,0);
			
		}
		
		//obtenemos la cantidad apartada 
		$disponible=$existenciaFisica-$cantidadApartada;
		
		//-------------------		
		//tambien los buscamos  en los compuestos
			
		
		return $existenciaFisica."|".$cantidadApartada."|".$disponible;
	
	}
	
	
	
//-----------------------------------------------------------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------
//
// FUNCIONES
//
//-----------------------------------------------------------------------------------------------------------------------------------------------

	
	function obteneHijosProductoCompuesto($id_producto)
	{
		unset($arrDetalle);
		$arrDetalle = array();
		
		//obtenemos sus hijos y las cantidades y las anexamos a un arreglo
		$strSQL="SELECT id_producto_relacionado , cantidad FROM na_productos_basicos_detalle where id_producto='".$id_producto."'";
		$ref = mysql_query($strSQL) or die(mysql_error());

		while($linea = mysql_fetch_assoc($ref))
		{
			array_push($arrDetalle,array($linea['id_producto_relacionado'],$linea['cantidad'],0,0,0));
		}
		
		return $arrDetalle;
	
	}
	
	//obtenemos la i
	function obtenExistenciaProductoBasico($id_producto)
	{
		$strSQL="SELECT if(sum(signo * cantidad) is null,0,sum(signo * cantidad)) as existencia FROM na_movimientos_almacen_detalle
			left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
			left join ad_almacenes on na_movimientos_almacen.id_almacen=ad_almacenes.id_almacen
			left join na_productos on na_productos.id_producto=na_movimientos_almacen_detalle.id_producto
			LEFT JOIN na_familias_productos on na_productos.id_familia_producto = na_familias_productos.id_familia_producto
			LEFT JOIN na_tipos_productos  on na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
			LEFT JOIN na_modelos_productos  on na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
			LEFT JOIN na_caracteristicas_productos  on na_productos.id_caracteristica_producto = na_caracteristicas_productos.id_caracteristica_producto
			LEFT JOIN na_lotes  on na_movimientos_almacen_detalle.id_lote = na_lotes.id_lote
			WHERE na_movimientos_almacen.no_modificable =1 and na_movimientos_almacen_detalle.id_producto ='".$id_producto."' ";
    	
		$arrREsp=valBuscador($strSQL);
		//retornamos la existencia del basico
		return $arrREsp[0];
		
			
	}
	
	//-----------------------------------------------------------
	//-----------------------------------------------------------
	function obtenApartadoProducto($id_producto,$es_compuesto)
	{
		
		//SI NO ES COMPUESTO VEMOS EN QUE DETALLE ESTA DE LOS QUE SON COMPUESTOS
		if($es_compuesto==0)
		{
			$strSQL="SELECT if(SUM(cantidad) is null,0,SUM(cantidad)) as apartado  FROM na_movimientos_apartados where id_estatus_apartado=1  and id_producto ='".$id_producto."'" ;
			$arrREsp1=valBuscador($strSQL);
			$valor1=$arrREsp1[0];
		
				
			$strSQL="SELECT if(SUM(cantidad) is null,0,SUM(cantidad)) as apartado  FROM na_movimientos_apartados_detalles where id_estatus_apartado=1  and id_producto ='".$id_producto."'" ;
			$arrREsp3=valBuscador($strSQL);
			$valor2=$arrREsp3[0];
			
			
			//qutamos los que ya hallan sido presentados en una orden de salida
			//esto tambien agregar en el 
			//--------------
			$strSQL="SELECT if(sum(cantidad) is null,0,sum(cantidad)) as salidas FROM na_movimientos_almacen_detalle
			left join na_movimientos_almacen on na_movimientos_almacen_detalle.id_control_movimiento=na_movimientos_almacen.id_control_movimiento
			left join ad_almacenes on na_movimientos_almacen.id_almacen=ad_almacenes.id_almacen
			left join na_productos on na_productos.id_producto=na_movimientos_almacen_detalle.id_producto
			WHERE na_movimientos_almacen.id_subtipo_movimiento=70099 AND na_movimientos_almacen.no_modificable =1 and na_movimientos_almacen_detalle.id_producto ='".$id_producto."' ";
    	
		    $arrREspSalio=valBuscador($strSQL);
			$valorSalio= $arrREspSalio[0];
			
    		 return $valor1+$valor2-$valorSalio;
			
		}
		else
		{
			$strSQL="SELECT if(SUM(cantidad) is null,0,SUM(cantidad)) as apartado  FROM na_movimientos_apartados where id_estatus_apartado=1  and id_producto ='".$id_producto."'" ;
			$arrREsp2=valBuscador($strSQL);
			
			//si es compuesto debemos trare los que se armaron ya entregados
			
			
			//debemos restar todo lo que ya esta entregado
			
			//en una orden de salida de los diferentes a esta orden
			
			return $arrREsp2[0];
		}
		
		//quitamos los que ya esten entregados
		
				
		
		//si el producto es basico tambien obtenemos en los hijos cuentos estn apartados
		
	
	}
	
	///----------------
	//---------------
	
	function obtenExistenciaCompuestaArrProductos($arrProductosHijos)
	{
		//print_r($arrProductosHijos);
		unset($arrayBuscarMinimo);
		$arrayBuscarMinimo=array();
		//para el recorrido obtenemos los datos
		$tamArreglo= count($arrProductosHijos);
		for($i=0;$i<$tamArreglo;$i++)
		{
			// vamos buscando la existencia
			$id_productoBasico=$arrProductosHijos[$i][0];
			
			
			$arrProductosHijos[$i][2]=obtenExistenciaProductoBasico($id_productoBasico);
			
			$arrProductosHijos[$i][3]= floor($arrProductosHijos[$i][2]/$arrProductosHijos[$i][1]);
			
			$arrayBuscarMinimo[$i]=$arrProductosHijos[$i][3];			
			
			//de la existencia realizamos la division y obtenemos la parte entera
		}
		
		//obtenemos el valor minimo del array
	//	print_r($arrayBuscarMinimo);	
		//ahora vemos cuantos compuestos podemos armar
		return min($arrayBuscarMinimo);
		
		
	}
	
	
?>