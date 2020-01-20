<?php	
include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];
$tipoDato=$_POST['dato']; //tipoProducto

if ($tipoDato=="tipoProducto")
{	
	$sql = "SELECT id_tipo_producto, nombre FROM na_tipos_productos WHERE activo=1 AND id_familia_producto = " . $id . " order by nombre";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	if ($total >= 1)
	{	
		echo "<option value='0'>Todos</option>";
		
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_tipo_producto.'" >'.utf8_encode($registro->nombre).'</option>';		
		}
	}
	else
	{
		echo "<option value='0'>Seleccione Familia...</option>";
	}	
}
elseif ($tipoDato=="clientes")
{
	
	$sql = "SELECT id_cliente, id_sucursal_alta, nombre FROM ad_clientes WHERE activo=1 AND id_sucursal_alta = " . $id. " order by nombre";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	if ($total >= 1)
	{
		echo "<option value='0'>Todos</option>";
	
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_caracteristica_producto.'" >'.utf8_encode($registro->nombre).'</option>';		
		}
	}
	else
	{
		echo "<option value='0'>Seleccione Sucursal...</option>";
	}
	
}

elseif ($tipoDato=="clientes_select_multiple"){	
		$sql = "SELECT id_cliente, CONCAT(ad_clientes.nombre,' ', IF(apellido_paterno is null,'',apellido_paterno),' ',IF(apellido_materno is null,'',apellido_materno)) AS nombre
				FROM ad_clientes
				WHERE activo=1 AND (concat_ws(' ',nombre, apellido_paterno, apellido_materno) LIKE '%$id%' OR nombre LIKE '%$id%' OR apellido_paterno LIKE '%$id%'
				OR apellido_materno LIKE '%$id%')
				ORDER BY nombre";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$total=$datos->cuentaRegistros($sql);
		if ($total >= 1){	
				foreach ($result as $registro ){
						echo '<option value="'.$registro->id_cliente.'" >'.utf8_encode($registro->nombre).'</option>';		
						}
				}
		else{
				echo '<option value="0" >No hay coincidencias</option>';		
				}
		}
		
elseif ($tipoDato=="vendedor_select_multiple"){	
		$sql = "SELECT id_vendedor, CONCAT(nombre,' ', IF(apellido_paterno is null,'',apellido_paterno),' ',IF(apellido_materno is null,'',apellido_materno)) AS nombre
				FROM na_vendedores
				WHERE activo=1 AND (concat_ws(' ',nombre, apellido_paterno, apellido_materno) LIKE '%$id%' OR nombre LIKE '%$id%' OR apellido_paterno LIKE '%$id%'
				OR apellido_materno LIKE '%$id%')
				ORDER BY nombre";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$total=$datos->cuentaRegistros($sql);
		if ($total >= 1){	
				foreach ($result as $registro ){
						echo '<option value="'.$registro->id_vendedor.'" >'.utf8_encode($registro->nombre).'</option>';		
						}
				}
		else{
				echo '<option value="0" >No hay coincidencias</option>';		
				}
		}

elseif ($tipoDato=="listaPrecios")
{
	$sql = "SELECT DISTINCT ad_lista_precios.id_lista_precios ,CONCAT(nombre, if(es_lista_precio_publico=0,
CONCAT(' :: Vigencia del: ', DATE_FORMAT(fecha_inicio, '%d/%m/%Y'),' al: ', DATE_FORMAT(fecha_termino, '%d/%m/%Y')),'')) vigencia  
FROM ad_lista_precios 
 left join na_listas_detalle_sucursales on ad_lista_precios.id_lista_precios=na_listas_detalle_sucursales.id_lista_precios
WHERE  ad_lista_precios.id_lista_precios =1 OR id_sucursal=" . $id;
	
	
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	if ($total >= 1)
	{
		
	
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_lista_precios.'" >'.utf8_encode($registro->vigencia).'</option>';		
		}
	}
	
}
elseif ($tipoDato=="listaPreciosConEtiquetas")
{
	$sql = "SELECT DISTINCT ad_lista_precios.id_lista_precios ,CONCAT(nombre, if(es_lista_precio_publico=0,
CONCAT(' :: Vigencia del: ', DATE_FORMAT(fecha_inicio, '%d/%m/%Y'),' al: ', DATE_FORMAT(fecha_termino, '%d/%m/%Y')),'')) vigencia  
FROM ad_lista_precios 
 left join na_listas_detalle_sucursales on ad_lista_precios.id_lista_precios=na_listas_detalle_sucursales.id_lista_precios
WHERE  ad_lista_precios.id_lista_precios =1 OR id_sucursal=" . $id;
	
	
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	if ($total >= 1)
	{
		
	
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_lista_precios.'" >'.utf8_encode($registro->vigencia).'</option>';		
		}
		echo '<option value="99999999" >ETIQUETAS</option>';		
	}
	
}

elseif ($tipoDato=="clientes_pedido")
{
	$sql = "SELECT ad_pedidos.id_cliente, 
				CONCAT(ad_clientes.nombre,' ', if(ad_clientes.apellido_paterno is null,'',ad_clientes.apellido_paterno),' ',if(ad_clientes.apellido_materno is null,'',ad_clientes.apellido_materno))  AS Cliente
						FROM ad_pedidos
				   LEFT JOIN ad_clientes 
						  on ad_pedidos.id_cliente = ad_clientes.id_cliente
					   WHERE (concat_ws(' ',ad_clientes.nombre, ad_clientes.apellido_paterno, ad_clientes.apellido_materno) LIKE '%$id%'
						  OR ad_clientes.nombre LIKE '%$id%'
						  OR ad_clientes.apellido_paterno LIKE '%$id%'
						  OR ad_clientes.apellido_materno LIKE '%$id%')
						 and ad_pedidos.id_estatus_pago_pedido <> 4
					GROUP BY id_cliente
					order by nombre
					   LIMIT 250;";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	if ($total >= 1)
	{
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_cliente.'" >'.utf8_encode($registro->Cliente).'</option>';		
		}
	}
	else
	{
		echo "<option value='0'>No hay coincidencias...</option>";
	}
	
}
elseif ($tipoDato=="no_pedido")
{
	$sql = "SELECT ad_pedidos.id_cliente, 
							 CONCAT(ad_clientes.nombre, ' ',  ad_clientes.apellido_paterno,' ', ad_clientes.apellido_materno) AS Cliente
						FROM ad_pedidos
				   LEFT JOIN ad_clientes 
						  on ad_pedidos.id_cliente = ad_clientes.id_cliente
					   WHERE id_pedido= '$id'						  
						 and ad_pedidos.id_estatus_pago_pedido <> 4
					order by nombre
					   LIMIT 250;";
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	
	if ($total >= 1)
	{		
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_cliente.'" selected="selected">'.utf8_encode($registro->Cliente).'</option>';		
		}
//		echo "*" . $registro->id_cliente . "*";
	}
	else
	{
		echo "<option value='0'>No hay coincidencias...</option>";
	}	
}

elseif ($tipoDato=="terminalPorSucursal")
{
	
	$sql = "SELECT id_terminal_bancaria, nombre 
			  FROM na_terminales_bancarias 
			 where activo=1
			   and id_sucursal=".$id." 
		  order by nombre; " ;
		  
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	if ($total >= 1)
	{
		echo "<option value='0'>Seleccione una opcion...</option>";
	
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_terminal_bancaria.'" >'.utf8_encode($registro->nombre).'</option>';		
		}
	}
	else
	{
		echo "<option value='0'>No hay coincidencias...</option>";
	}
	
}
elseif ($tipoDato=="pedidoCliente")
{	

	$sql = " SELECT id_control_pedido, 
					id_pedido 
			   FROM ad_pedidos 
			  WHERE id_cliente= " . $id;
			 
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	
	if ($total >= 1)
	{	
		echo "<option value='0' SELECTED>Seleccione un pedido...</option>";
		
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_control_pedido.'" >'.utf8_encode($registro->id_pedido).'</option>';		
		}
	}
	else
	{
		echo "<option value='0' SELECTED>Seleccione otro cliente...</option>";
	}	
}
elseif ($tipoDato=="direccionCliente")
{	
	$sql = " SELECT id_cliente_direccion_entrega AS id, 
					CONCAT('Calle: ',ad_clientes_direcciones_entrega.calle,' ', 'Num. Ext: ',
					if(ad_clientes_direcciones_entrega.numero_exterior is null,'',ad_clientes_direcciones_entrega.numero_exterior),
					' ',if(ad_clientes_direcciones_entrega.numero_interior is null,'',ad_clientes_direcciones_entrega.numero_interior),
					' ','Col. ',if(ad_clientes_direcciones_entrega.colonia is null,'',ad_clientes_direcciones_entrega.colonia),
					' ','Del. o Mun. ',if(ad_clientes_direcciones_entrega.delegacion_municipio is null,
					'',ad_clientes_direcciones_entrega.delegacion_municipio)) AS direccion
			   FROM ad_clientes_direcciones_entrega 
		  LEFT JOIN na_ciudades on  ad_clientes_direcciones_entrega.id_ciudad=na_ciudades.id_ciudad 
		  LEFT JOIN na_estados on ad_clientes_direcciones_entrega.id_estado=na_estados.id_estado 
		  LEFT JOIN ad_clientes ON ad_clientes_direcciones_entrega.id_cliente=ad_clientes.id_cliente 
			  WHERE ad_clientes_direcciones_entrega.id_cliente =  " . $id;
			 
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	
	if ($total >= 1)
	{	
		echo "<option value='0'>Seleccione una direccion...</option>";
		
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id.'" >'.utf8_encode($registro->direccion).'</option>';		
		}
	}
	else
	{
		echo "<option value='0'>No se encontraron registros...</option>";
	}	
}
elseif($tipoDato=="slct_prov"){	
		if(!empty($id)){
				$strSQL = "SELECT DISTINCT ad_cuentas_por_pagar_operadora.id_proveedor AS id_proveedor, na_proveedores.razon_social AS proveedor	
					FROM ad_cuentas_por_pagar_operadora
					LEFT JOIN na_proveedores USING(id_proveedor)
					WHERE id_estatus_cuentas_por_pagar = 1 AND na_proveedores.razon_social LIKE '%" . $id . "%' ORDER BY proveedor";
				$datos = new consultarTabla($strSQL);	
				$result = $datos -> obtenerRegistros();
				$total=$datos->cuentaRegistros($sql);
				if($total > 0){	
						foreach ($result as $registro ){
								echo '<option value="' . $registro->id_proveedor.'" >' . utf8_encode($registro->proveedor) . '</option>';		
								}
						}
				}
		}
elseif($tipoDato=="slct_reem"){	
		if(!empty($id)){
				$strSQL = "SELECT DISTINCT rembolsar_a AS idEmpleado,
				CONCAT(ad_empleados.nombre, ' ', IF(apellido_paterno IS NOT NULL, apellido_paterno, ''), ' ',  IF(apellido_materno IS NOT NULL, apellido_materno, '')) AS empleado
				FROM ad_cuentas_por_pagar_operadora
				LEFT JOIN ad_empleados ON ad_cuentas_por_pagar_operadora.rembolsar_a = ad_empleados.id_empleado
				WHERE id_estatus_cuentas_por_pagar = 1
				AND CONCAT(ad_empleados.nombre, ' ', IF(apellido_paterno IS NOT NULL, apellido_paterno, ''), ' ',  IF(apellido_materno IS NOT NULL, apellido_materno, '')) 
				LIKE '%" . $id . "%' ORDER BY empleado";
				$datos = new consultarTabla($strSQL);	
				$result = $datos -> obtenerRegistros();
				$total=$datos->cuentaRegistros($sql);
				if($total > 0){	
						foreach ($result as $registro ){
								echo '<option value="' . $registro->idEmpleado.'" >' . utf8_encode($registro->empleado) . '</option>';		
								}
						}
				}
		}

elseif ($tipoDato=="productosPedidoCliente")
{	

	$sql = " SELECT na_pedidos_detalle.id_producto, 
					na_productos.nombre 
			   FROM na_pedidos_detalle 
		  LEFT JOIN na_productos ON na_pedidos_detalle.id_producto=na_productos.id_producto
			  WHERE id_control_pedido= " . $id;
			 
	$datos = new consultarTabla($sql);
	$result = $datos -> obtenerRegistros();
	$total=$datos->cuentaRegistros($sql);
	
	if ($total >= 1)
	{	
//		echo "<option value='0'>Seleccione un pedido...</option>";
		
		foreach ($result as $registro )
		{
			echo '<option value="'.$registro->id_producto.'" >'.utf8_encode($registro->nombre).'</option>';		
		}
	}
	else
	{
		echo "<option value='0'>No se encontraron registros...</option>";
	}	
}
elseif ($tipoDato=="clientes_razon_social_multiple"){	
		$sql = "SELECT id_cliente, CONCAT(ad_clientes.nombre,' ', IF(apellido_paterno is null,'',apellido_paterno),' ',IF(apellido_materno is null,'',apellido_materno)) AS nombre
				FROM ad_clientes
				WHERE activo=1 AND (concat_ws(' ',nombre, apellido_paterno, apellido_materno) LIKE '%$id%' OR nombre LIKE '%$id%' OR apellido_paterno LIKE '%$id%'
				OR apellido_materno LIKE '%$id%'   or nombre_razon_social like '%$id%'  )
				ORDER BY nombre";
		$datos = new consultarTabla($sql);
		$result = $datos -> obtenerRegistros();
		$total=$datos->cuentaRegistros($sql);
		if ($total >= 1){	
				foreach ($result as $registro ){
						echo '<option value="'.$registro->id_cliente.'" >'.utf8_encode($registro->nombre).'</option>';		
						}
				}
		else{
				echo '<option value="0" >No hay coincidencias</option>';		
				}
		}

?>