<?php
	php_track_vars;

	extract($_GET);
	extract($_POST);
	
//CONECCION Y PERMISOS A LA BASE DE DATOS
	include("../../conect.php");
	include("../../code/general/funciones.php");
	
	//  -  id
	
	if($opcion=='1')
	{
		$strConsulta="SELECT id_categoria_articulo,nombre FROM rac_articulos_categorias where activo=1 and id_linea_articulo ='".$id."'";
	}
	
	
	/******Funciones para llenar combos en nasser catalogo de productos **************/
	
	
		else if($opcion=='200')
	{
		$strConsulta="SELECT id_familia_producto,nombre FROM na_tipos_productos where activo=1 and id_familia_producto ='".$id."'";
	}
	
			else if($opcion=='201')
	{
		$strConsulta="SELECT id_modelo_producto,nombre FROM na_modelos_productos where activo=1 and id_modelo_producto ='".$id."'";
	}
	
		else if($opcion=='202')
	{
		$strConsulta="SELECT id_caracteristica_producto,nombre FROM na_caracteristicas_productos where activo=1 and id_caracteristica_producto ='".$id."'";
	}
	
	
	
	
	/***********************************/
	elseif($opcion=='2')
	{
		
		$strConsulta="SELECT id_caracteristica, nombre FROM rac_articulos_caracteristicas  where activo=1 and id_linea_articulo ='".$id."'";
	
	}
	elseif($opcion=='3')
	{
		$strConsulta="SELECT id_subcategoria_articulo, nombre FROM rac_articulos_subcategorias  where activo=1 and id_categoria_articulo ='".$id."'";
	
	}
	elseif($opcion=='10')
	{
		//obtenemos los datos de las ciudades relacionadas al estado
		$strConsulta="SELECT id_ciudad,nombre FROM rac_ciudad WHERE id_estado='".$id."' ORDER BY nombre";
	}//para la parte de pedidos---------------------------------------
	elseif($opcion=='20')
	{
		$strConsulta="SELECT id_detalle,CONCAT( nombre,' ', if(apellidos is null,' ',apellidos),'->', if(cargo is null,'',cargo),'->',rac_tipos_contactos.descripcion) as nombre
FROM rac_clientes_detalle_contactos left join rac_tipos_contactos on rac_clientes_detalle_contactos.id_tipo_contacto=rac_tipos_contactos.id_tipo_contacto
WHERE rac_clientes_detalle_contactos.activo<> 0 and id_cliente='".$id."' ORDER BY nombre ";
	}
	elseif($opcion=='21')
	{
		//obtenemos los datos de las ciudades relacionadas al estado
		$strConsulta="SELECT id_detalle,CONCAT(rac_eventos_tipos_lugares.nombre,' ',
nombre_lugar,' Calle:',calle,' No. Ext:',numero_exterior,' Col.',colonia,' Cd; ',rac_ciudad.nombre,', ',rac_estado.nombre) as lugar
FROM rac_clientes_detalle_direcciones
left join rac_eventos_tipos_lugares
on rac_clientes_detalle_direcciones.id_tipo_lugar_evento=rac_eventos_tipos_lugares.id_tipo_lugar_evento
left join rac_ciudad on rac_ciudad.id_ciudad=rac_clientes_detalle_direcciones.id_ciudad
left join rac_estado on rac_estado.id_estado=rac_ciudad.id_estado WHERE rac_clientes_detalle_direcciones.activo<> 0 and id_cliente='".$id."'";
	}
	elseif($opcion=='22')
	{
		//obtenemos los datos de las ciudades relacionadas al estado
		$strConsulta="SELECT id_detalle,CONCAT(rac_eventos_tipos_lugares.nombre,' ',
nombre_lugar,' Calle:',calle,' No. Ext:',numero_exterior,' Col.',colonia,' Cd; ',rac_ciudad.nombre,', ',rac_estado.nombre) as lugar
FROM rac_clientes_detalle_direcciones
left join rac_eventos_tipos_lugares
on rac_clientes_detalle_direcciones.id_tipo_lugar_evento=rac_eventos_tipos_lugares.id_tipo_lugar_evento
left join rac_ciudad on rac_ciudad.id_ciudad=rac_clientes_detalle_direcciones.id_ciudad
left join rac_estado on rac_estado.id_estado=rac_ciudad.id_estado WHERE rac_clientes_detalle_direcciones.activo<> 0 and id_cliente='".$id."'";

	}
	elseif($opcion=='23')
	{
		$strConsulta="SELECT id_detalle,CONCAT( nombre,' ', if(apellidos is null,' ',apellidos),'->', if(cargo is null,'',cargo),'->',rac_tipos_contactos.descripcion) as nombre
FROM rac_clientes_detalle_contactos left join rac_tipos_contactos on rac_clientes_detalle_contactos.id_tipo_contacto=rac_tipos_contactos.id_tipo_contacto
WHERE rac_clientes_detalle_contactos.activo<> 0 and id_cliente='".$id."' ORDER BY nombre ";
	}
	elseif($opcion=='24')
	{
		$strConsulta="SELECT id_detalle,CONCAT(rac_eventos_tipos_lugares.nombre,' ',
nombre_lugar,' Calle:',calle,' No. Ext:',numero_exterior,' Col.',colonia,' Cd; ',rac_ciudad.nombre,', ',rac_estado.nombre) as lugar
FROM rac_clientes_detalle_direcciones
left join rac_eventos_tipos_lugares
on rac_clientes_detalle_direcciones.id_tipo_lugar_evento=rac_eventos_tipos_lugares.id_tipo_lugar_evento
left join rac_ciudad on rac_ciudad.id_ciudad=rac_clientes_detalle_direcciones.id_ciudad
left join rac_estado on rac_estado.id_estado=rac_ciudad.id_estado WHERE rac_clientes_detalle_direcciones.activo<> 0 and id_cliente='".$id."'";
	}
	elseif($opcion=='25')
	{
		$strConsulta="SELECT id_cliente, CONCAT(nombre,'  - ', nombre_comercial) nombre FROM rac_clientes WHERE id_cliente_mayorista='".$id."' or id_cliente= '".$id."' ";
	}
	elseif($opcion=='100')
	{
		//buscamos los subtipos de movimientos dado el almacen y tipo de almacen seleccionados para nuevo
		if($tipo_almacen==1)
			$strWHERE=" AND en_almacen_neteable=1 ";
		elseif($tipo_almacen==2)
			$strWHERE=" AND en_almacen_produccion=1 ";
		elseif($tipo_almacen==3)
			$strWHERE=" AND en_almacen_no_neteable=1 ";
		
		//aux=ajaxR('../ajax/obtenDatosCombos.php?opcion=100&id_almacen='+varAlmacen+'&tipo_almacen='+tipoAlmacen+'&tipo_movimiento='+tipoMovimiento);
			
	    $strConsulta="SELECT id_subtipo_movimiento, nombre 
					FROM rac_almacenes_subtipos_movimientos 
					WHERE mostrar_en_nuevo =1 AND id_tipo_movimiento ='".$tipo_movimiento."' " .$strWHERE ; 
					
		echo $strConsulta;
	}
	elseif($opcion=='101')
	{
		//aux=ajaxR('../ajax/obtenDatosCombos.php?opcion=100&id_almacen='+varAlmacen+'&tipo_almacen='+tipoAlmacen+'&tipo_movimiento='+tipoMovimiento);
			
	    $strConsulta="SELECT id_almacen,nombre FROM rac_almacenes WHERE id_almacen not in (".$id_almacen.")" ; 
					
		//echo $strConsulta;
	}
	else if($opcion=='500'){
		//buscamos la facturas con saldo
		$strConsulta="SELECT id_control_factura,id_factura 
		FROM (SELECT ".$tfac.".id_control_factura,".$tfac.".id_factura,".$tfac.".total as total_fac,
		(select 	sum(if(".$tabla.".total is null,0,".$tabla.".total)) 
		from ".$tabla." 
		where  ".$tabla.".id_control_factura= ".$tfac.".id_control_factura and((".$tabla.".cancelada is null) or ".$tabla.".cancelada=0)and ((not (".$tabla.".timbrado is null )) or  ".$tabla.".timbrado<>'')) 
		as total_nc
		FROM ".$tfac." 
		where ".$tfac.".id_cliente = '".$id."' and  ((".$tfac.".cancelada is null) or ".$tfac.".cancelada=0)and ((not (".$tfac.".timbrado is null )) or  ".$tfac.".timbrado<>'') group by ".$tfac.".id_control_factura) as dat where total_fac >if(total_nc is null,0,total_nc) ";
	}
	
	
	$res=mysql_query($strConsulta) or die("Error en:\n$strConsulta\n\nDescripcion:".mysql_error());	
	$num=mysql_num_rows($res);
	echo "exito";
	echo "|".$num;
	for($i=0;$i<$num;$i++)
	{
		echo "|";
		$row=mysql_fetch_row($res);
		echo utf8_encode($row[0]."~".($row[1]));
	}
	mysql_free_result($res);
	
	
?>