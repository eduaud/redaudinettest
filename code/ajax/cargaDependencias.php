<?php 
include("../../conect.php");
$referencia=$_GET['referencia'];
mysql_query("SET NAMES 'utf8'");
if($referencia=='ad_sucursales'&&($_GET['id']=='3'||$_GET['id']=='2')){
	$sqlPlazas="SELECT id_sucursal,nombre FROM ad_sucursales";
	$plazas=mysql_query($sqlPlazas);
	$cont=0;
	while($a_plazas=mysql_fetch_array($plazas)){
		$array[$cont]['id']=$a_plazas['id_sucursal'];
		$array[$cont]['nombre']=$a_plazas['nombre'];
		$cont+=1;
	}
}
else if($referencia=='cl_distribuidores'&&($_GET['id']=='1')){
	$sqlDistribuidores="SELECT id_distribuidor,nombre FROM cl_distribuidores";
	$distribuidores=mysql_query($sqlDistribuidores);
	$cont=0;
	while($a_distrbuidores=mysql_fetch_array($distribuidores)){
		$array[$cont]['id']=$a_distrbuidores['id_distribuidor'];
		$array[$cont]['nombre']=$a_distrbuidores['nombre'];
		$cont+=1;
	}
}
else if($referencia=='cl_tipos_producto_servicio'){
	$sqlProductoServicio="
	SELECT id_tipo_producto_servicio,cl_tipos_producto_servicio.nombre from cl_tipos_producto_servicio 
	LEFT JOIN cl_clasificacion_productos ON cl_tipos_producto_servicio.id_clasificacion_producto=cl_clasificacion_productos.id_clasificacion_producto
	WHERE cl_clasificacion_productos.id_clasificacion_producto=".$_GET['id'];
	$ProductoServicio=mysql_query($sqlProductoServicio);
	$cont=0;
	while($a_ProductoServicio=mysql_fetch_array($ProductoServicio)){
		$array[$cont]['id']=$a_ProductoServicio['id_tipo_producto_servicio'];
		$array[$cont]['nombre']=$a_ProductoServicio['nombre'];
		$cont+=1;
	}
}
else if($referencia=='cl_tipos_equipo'){
	$sqlTipoEquipos="
	SELECT id_tipo_equipo,cl_tipos_equipo.nombre 
	FROM cl_tipos_equipo,cl_tipos_producto_servicio
	WHERE cl_tipos_producto_servicio.requiere_tipo_equipo=1 AND cl_tipos_producto_servicio.id_tipo_producto_servicio=".$_GET['id'];
	$TipoEquipos=mysql_query($sqlTipoEquipos);
	$cont=0;
	while($a_TipoEquipos=mysql_fetch_array($TipoEquipos)){
		$array[$cont]['id']=$a_TipoEquipos['id_tipo_equipo'];
		$array[$cont]['nombre']=$a_TipoEquipos['nombre'];
		$cont+=1;
	}
}
else if($referencia=='ad_clientes'){
$sqlClientes="
	SELECT id_cliente,CONCAT(ad_clientes.nombre,' ', if(apellido_paterno is null,'',apellido_paterno),' ',if(apellido_materno
	is null,'',apellido_materno)) as 'nombre' 
	FROM ad_clientes 
	LEFT JOIN cl_tipos_cliente_proveedor 
	ON ad_clientes.id_tipo_cliente_proveedor=cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor 
	WHERE  ad_clientes.activo=1 AND cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=".$_GET['id'];
	$Clientes=mysql_query($sqlClientes)or die(mysql_error());
	$cont=0;
	while($a_Clientes=mysql_fetch_array($Clientes)){
		$array[$cont]['id']=$a_Clientes['id_cliente'];
		$array[$cont]['nombre']=$a_Clientes['nombre'];
		$cont+=1;
	}
}
else if($referencia=='ad_cliente_direccion_entrega'){
	$sqlDireccionesCliente="
	SELECT id_cliente_direccion_entrega AS id_registro, CONCAT('Calle: ',ad_clientes_direcciones.calle,' ', 'Num. Ext: ',if(ad_clientes_direcciones.numero_exterior is null,'',ad_clientes_direcciones.numero_exterior),' ',if(ad_clientes_direcciones.numero_interior is null,'',ad_clientes_direcciones.numero_interior),' ','Col. ',if(ad_clientes_direcciones.colonia is null,'',ad_clientes_direcciones.colonia),' ','Del. o Mun. ',if(ad_clientes_direcciones.delegacion_municipio is null,'',ad_clientes_direcciones.delegacion_municipio)) AS dato_muestra 
	FROM ad_clientes_direcciones 
	LEFT JOIN sys_ciudades on  ad_clientes_direcciones.id_ciudad=sys_ciudades.id_ciudad 
	LEFT JOIN sys_estados on ad_clientes_direcciones.id_estado=sys_estados.id_estado 
	LEFT JOIN ad_clientes ON ad_clientes_direcciones.id_cliente=ad_clientes.id_cliente 
	WHERE ad_clientes.id_cliente=".$_GET['id'];
	$DireccionesCliente=mysql_query($sqlDireccionesCliente);
	$cont=0;
	while($a_DireccionesCliente=mysql_fetch_array($DireccionesCliente)){
		$array[$cont]['id']=$a_DireccionesCliente['id_registro'];
		$array[$cont]['nombre']=$a_DireccionesCliente['dato_muestra'];
		$cont+=1;
	}
} 
else if($referencia=='ad_proveedores_contacto'){
	$sql = "SELECT id_proveedor_contacto, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) nombre";
	$sql .= " FROM ad_proveedores AP INNER JOIN ad_proveedores_contacto APC";
	$sql .= " ON AP.id_proveedor = APC.id_proveedor";
	$sql .= " AND AP.id_proveedor = " . $_GET['id'];
	$resultado=mysql_query($sql);
	$cont=0;
	while($row=mysql_fetch_array($resultado)){
		$array[$cont]['id']=$row['id_proveedor_contacto'];
		$array[$cont]['nombre']=$row['nombre'];
		$cont+=1;
	}
} 
else if($referencia=='ad_proveedores'){
	$sql = "SELECT";
	$sql .= " ad_proveedores.id_proveedor, ad_proveedores.razon_social, ad_proveedores.permite_mezclar_tipo_producto_en_ODC";
	$sql .= " FROM ad_proveedores";
	$sql .= " LEFT JOIN ad_proveedores_productos";
	$sql .= " ON ad_proveedores.id_proveedor = ad_proveedores_productos.id_proveedor";
	$sql .= " WHERE ad_proveedores_productos.id_producto =" . $_GET['idProducto'];
	$sql .= " AND ad_proveedores.activo = 1";
	$resultado=mysql_query($sql);
	$cont=0;
	if(mysql_num_rows($resultado)>0){
		while($row=mysql_fetch_array($resultado)){
			$array[$cont]['id']=$row['id_proveedor'];
			$array[$cont]['nombre']=$row['razon_social'];
			$array[$cont]['mezclar']=$row['permite_mezclar_tipo_producto_en_ODC'];
			$cont+=1;
		}
	}else{
			$array[$cont]['id']='';
			$array[$cont]['nombre']='';
			$array[$cont]['mezclar']='';
	}
}
else if($referencia=='cl_tipos_cliente_proveedor'){
	$sqlTipoClientes="
	SELECT cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor,cl_tipos_cliente_proveedor.nombre
	FROM cl_tipos_cliente_proveedor
	LEFT JOIN ad_clientes
	ON cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=ad_clientes.id_tipo_cliente_proveedor
	LEFT JOIN  ad_sucursales 
	ON ad_sucursales.id_sucursal=ad_clientes.id_sucursal 
	WHERE cl_tipos_cliente_proveedor.genera_pedido=1 AND ad_sucursales.id_sucursal=".$_GET['id'];
	$tipoClientes=mysql_query($sqlTipoClientes);
	$cont=0;
	$number_rows = mysql_num_rows($tipoClientes);
	while($a_tipoClientes=mysql_fetch_array($tipoClientes)){
		$array[$cont]['id']=$a_tipoClientes['id_tipo_cliente_proveedor'];
		$array[$cont]['nombre']=$a_tipoClientes['nombre'];
		$cont+=1;
	}
}
else if($referencia=='ad_almacenes'){
	$sqlAlmacenes="
	SELECT ad_almacenes.id_almacen,ad_almacenes.nombre 
	FROM ad_almacenes
	INNER JOIN ad_sucursales_almacenes_detalle
	ON ad_sucursales_almacenes_detalle.id_almacen = ad_almacenes.id_almacen 
	INNER JOIN  ad_sucursales 
	ON ad_sucursales.id_sucursal = ad_sucursales_almacenes_detalle.id_sucursal 
	WHERE ad_almacenes.activo = 1
	AND ad_almacenes.neteable = 1
	AND ad_sucursales.id_sucursal=".$_GET['id'];
	$almacenes=mysql_query($sqlAlmacenes);
	$cont=0;
	while($a_almacenes=mysql_fetch_array($almacenes)){
		$array[$cont]['id']=$a_almacenes['id_almacen'];
		$array[$cont]['nombre']=$a_almacenes['nombre'];
		$cont+=1;
	}
}
else if($referencia=='ad_tipos_proveedores'){
	$sql="SELECT id_proveedor,razon_social FROM ad_proveedores 
		LEFT JOIN ad_tipos_proveedores 
		ON ad_proveedores.id_tipo_proveedor=ad_tipos_proveedores.id_tipo_proveedor WHERE ad_tipos_proveedores.id_tipo_proveedor=".$_GET['id'];
	$Proveedores=mysql_query($sql);
	$cont=0;
	while($a_Proveedores=mysql_fetch_array($Proveedores)){
		$array[$cont]['id']=$a_Proveedores['id_proveedor'];
		$array[$cont]['nombre']=$a_Proveedores['razon_social'];
		$cont+=1;
	}
}
else if($referencia=='1'){
	$sql="SELECT ad_clientes.id_cliente,CONCAT(ad_clientes.nombre,' ',apellido_paterno,' ',IFNULL(apellido_materno,'')) AS nombre
		FROM ad_clientes 
		LEFT JOIN ad_sucursales ON ad_clientes.id_sucursal=ad_sucursales.id_sucursal
		LEFT JOIN cl_tipos_cliente_proveedor ON ad_clientes.id_tipo_cliente_proveedor=cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor
		WHERE cl_tipos_cliente_proveedor.id_tipo_cliente_proveedor=".$_GET['id_tipo_cliente']." AND ad_sucursales.id_sucursal=".$_GET['id'];
	$Clientes=mysql_query($sql);
	$cont=0;
	while($a_Clientes=mysql_fetch_array($Clientes)){
		$array[$cont]['id']=$a_Clientes['id_cliente'];
		$array[$cont]['nombre']=$a_Clientes['nombre'];
		$cont+=1;
	}
}else if($referencia=='2'){
	$sql="SELECT id_sucursal,ad_sucursales.nombre
		FROM ad_sucursales 
		WHERE ad_sucursales.id_sucursal='10'";
	$Clientes=mysql_query($sql);
	$cont=0;
	while($a_Clientes=mysql_fetch_array($Clientes)){
		$array[$cont]['id']=$a_Clientes['id_sucursal'];
		$array[$cont]['nombre']=$a_Clientes['nombre'];
		$cont+=1;
	}
}

echo json_encode($array);
?>