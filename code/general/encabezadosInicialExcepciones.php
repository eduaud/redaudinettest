<?php
	//cuando se acaba la sesion tambien es necesario en el logueo por pop up regeneras los datos del sesion
	//******************abc***************************************************************************************
	
	if($tabla == 'ad_movimientos_almacen')
	{
		
		$strWhereExcepcionesEncabezados=" AND id_asociacion in (1000,1001,1002,1003,1004,1005,1006,1014,1022,1025,1027";
		
		$sqlEncaIniExcep="select id_asociacion from ad_encabezados_inicial_excepciones where tipo_movimiento=".$stm;
		$queryEncaIniExcep=mysql_query($sqlEncaIniExcep);
		$colEncaIniExcep=mysql_fetch_assoc($queryEncaIniExcep);
		
		$strWhereExcepcionesEncabezados .= ",".$colEncaIniExcep["id_asociacion"];
		
		/*
		//INVENTARIO +
		if($stm==70001)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,1024 ";
				
		}//ORDEN DE COMPRA +	
		elseif($stm==70002)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1015,1016 ";
				
		}//AJUSTE +
		elseif($stm==70003)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1024 ";
				
		}//ENTRADAS INICIALES +
		elseif($stm==70004)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ";
			
		}//TRASPASO ENTRADA REPARACION A CEDIS +
		elseif($stm==70005)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1011,1007,1023 ";
			
		}//TRASPASO ENTRADA ENTRE SUCURSALES +
		elseif($stm==70006)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=", 1011,1007,1023";
		
		}//ENTRADA POR CAMBIO PROVEEDOR +
		elseif($stm==70007)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1016 ";
		
		}//ENTRADA REPARACION PROVEEDOR +
		elseif($stm==70008)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,1016";
			
		}//DEVOLUCION POR VENTA +
		elseif($stm==70009)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,1013";
			
		}//ENTRADA POR INVENTARIO FISICO +
		elseif($stm==70010)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1024 ";
			
		}//DEVOLUCION A PROVEEDOR -
		elseif($stm==70011)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,1016";
			
		}//MERMAS -
		elseif($stm==70012)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1024 ";
			
		}//AJUSTE -
		elseif($stm==70033)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,1024";
			
		}//TRASPASO SALIDA DEVOLUCION / RERACION A CEDIS -
		elseif($stm==70055)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1007 ";
			
		}//TRASPASO SALIDA ENTRE SUCURSALES -
		elseif($stm==70066)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,1007";
			
		}//REPARACION A PROVEEDOR -
		elseif($stm==70088)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1016 ";
			
		}//VENTA -
		elseif($stm==70099)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			//en  este recuadro mostramos tambien los pedidos que lo integran cuando se a por 
			$strWhereExcepcionesEncabezados .=",1013 ";
			
		}//INVETARIO FISICO -
		elseif($stm==71010)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",1024 ";
			
		}
		*/
		
		if($op==2 && $make =='' && $ver==0)
		{
			
		}
		
		
		
		//vemos el tipo de movimiento para traer los campos visibles
		
		$strWhereExcepcionesEncabezados .=") ";
		
		
	}
	else if($tabla == 'ad_clientes')
	{
		$strWhereExcepcionesEncabezados=" AND id_asociacion in (100,101,102,103,106,107,108,110,114,139 ,140 ";
	

		//DISTRIBUIDORES
		if($stm==75000)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,109,115,116,117,118,119,120,121,122,123,124,125,126,127,130,131,132,133,134,135,136,137,138 ";
				
		}//2 DISTRIBUDIROES COMISIONISTAS
		elseif($stm==75001)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",111,115,116,117,118,119,120,121,122,123,124,125,126,127,130,131,132,133,134,135,136,137,138 ";
				
		}//DISTRIBUIDOR TECNICO EXTERNO
		elseif($stm==75002)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,111,115,116,117,118,119,120,121,122,123,124,125,126,127,130,131,132,133,134,135,136,137,138 ";
				
		}//TECNICO DISTRIBUIDOR
		elseif($stm==75003)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",104,112 ";
	
		}//CLIENTE PROVEEDOR SKY------------
		elseif($stm==75005)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,115,116,117,118,119,120,121,122,123,124,125,126,127,130,131,132,133,134,135,136,137,138 ";
				
		}//VENDEDOR DISTRIBUIDOR
		elseif($stm==75004)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",104,113 ";
				

		}//CLIENTE
		elseif($stm==75006)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=" ,115,116,117,118,119,120,121,122,123,124,125,126,127,130,131,132,133,134,135,136,137,138 ";
				

		}//ECNICO DISTRIBUIDOR COMISIONISTA
		elseif($stm==75008)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",104,112 ";
				
		}//TECNICO DISTRIBUIDOR EXTERNO
		elseif($stm==75009)
		{
			//solo mostramos los campos que  se veran en el campo de entradas iniciales mas a
			$strWhereExcepcionesEncabezados .=",104,112 ";
				
		}//VENDEDOR DISTRIBUIDOR


	 $strWhereExcepcionesEncabezados .=") ";
	
	}
	 
	 
	//.....................FUNCIONES AUXILIARES .......................
	//funcion para obtener la posicion del campo en base al id en la base
	
	
	
?>