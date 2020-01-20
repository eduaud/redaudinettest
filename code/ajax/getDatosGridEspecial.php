<?php

		php_track_vars;

	/*Conseguimos los datos del post
	
		tabla -> Nombre de la tabla que se esta accediendo
		id -> id del campo a utilizar
		accion -> accion a realizar, los valores pueden ser: [1 => Modificar, 2 => Ver, 3 => Eliminar o Cancelar]
	*/
	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	
	if($tipo == 1)
	{
		if($id_lista_precios == '' || $id_lista_precios == '0')
			$id_lista_precios=0;
			
		$sql=" SELECT if(id_lista_de_precios_forma_cobro is null,'NO',id_lista_de_precios_forma_cobro),
if(id_lista_de_precios is null,'$"."llave',id_lista_de_precios),
spa_formas_cobro.id_forma_cobro,
IF(id_lista_de_precios_forma_cobro  IS NULL, 0, 1),
nombre
 FROM  spa_formas_cobro
left join spa_lista_precios_formas_cobros_detalle
on spa_lista_precios_formas_cobros_detalle.id_forma_cobro=spa_formas_cobro.id_forma_cobro
and
id_lista_de_precios= $id_lista_precios ";
			  
			 
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]."~".$row[2]."~".$row[3]."~".$row[4]);
		}
	}
	elseif($tipo == 2)
	{
		if($id_lista_precios == '' || $id_lista_precios == '0')
			$id_lista_precios=0;
			
		
		$sql=" SELECT if(id_lista_de_precios_sucursal is null,'NO',id_lista_de_precios_sucursal),
if(id_lista_de_precios is null,'$"."llave',id_lista_de_precios),
sys_sucursales.id_sucursal,
IF(id_lista_de_precios_sucursal  IS NULL, 0, 1),
nombre
 FROM  sys_sucursales
left join spa_lista_precios_sucursales_detalle on spa_lista_precios_sucursales_detalle.id_sucursal=sys_sucursales.id_sucursal
and
id_lista_de_precios= $id_lista_precios ";
			    
			 
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]."~".$row[2]."~".$row[3]."~".$row[4]);
		}
	}
	elseif($tipo == 3)
	{
		$strWhere="";
		if($id_servicio!='0' && $id_servicio!='')
			$strWhere=" AND spa_servicios.id_servicio = '$id_servicio' ";
		elseif($id_tipo_servicio!='0' && $id_tipo_servicio!='')
			$strWhere=" AND spa_servicios.id_tipo_servicio = '$id_tipo_servicio' ";	
	
		
		if($id_lista_precios=='0' || $id_lista_precios=='')
		{
			$strWhere=" AND spa_servicios.id_servicio = 0 ";
		}
		
		//vemos las consultas de los grids
		$sql="SELECT if(id_lista_de_precios_servicios is null,0,id_lista_de_precios_servicios) as id_lista_de_precios_servicios,
			if(id_lista_de_precios is null,0,id_lista_de_precios) as  id_lista_de_precios,
			0 as cambio,
			spa_servicios.id_servicio,
			spa_servicios.id_servicio as id,
			precio_publico,
			if(cantidad_minima_venta is null,0,cantidad_minima_venta),
			if(cantidad_maxima_venta is null,0,cantidad_maxima_venta),
			if(porcentaje_descuento is null,0,porcentaje_descuento),
			if(precio_publico is null,0,precio_publico)-(if(precio_publico is null,0,precio_publico)*(if(porcentaje_descuento is null,0,porcentaje_descuento)/100))  as precio_final
			FROM spa_servicios left join spa_servicios_tipos on
			spa_servicios.id_tipo_servicio = spa_servicios_tipos.id_tipo_servicio
			left join spa_lista_precios_servicios_detalle on spa_lista_precios_servicios_detalle.id_servicio=spa_servicios.id_servicio
			and id_lista_de_precios= $id_lista_precios WHERE 1 ". $strWhere. " ORDER BY spa_servicios_tipos.nombre,spa_servicios.nombre,cantidad_minima_venta,cantidad_maxima_venta ";
	
		$res=mysql_query($sql) or die("Error en:\n$sql\n\nDescripcion:\n".mysql_error());	  
		$num=mysql_num_rows($res);
		echo "exito";
		for($i=0;$i<$num;$i++)
		{
			$row=mysql_fetch_row($res);
			echo utf8_encode("|".$row[0]."~".$row[1]."~".$row[2]."~".$row[3]."~".$row[4]."~".$row[5]."~".$row[6]."~".$row[7]."~".$row[8]."~".$row[9]);
		}
	}
	
	
	
	
	
	
	
	
	
	
	

	
?>