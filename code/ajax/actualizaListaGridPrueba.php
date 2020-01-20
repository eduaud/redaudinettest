<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$banderaPorc = $_POST['banderaPorc'];
$banderaProd = $_POST['banderaProd'];
$banderaBusca = $_POST['banderaBusca'];
$banderaSKU = $_POST['banderaSKU'];
$lista = $_POST['lista'];

$where = "";


if($banderaPorc == 1 && $banderaSKU == 1 && $banderaBusca == 0){
		$porcentaje = $_POST['porcentajeDesc'];
		$where .= " AND (na_productos.sku LIKE '%" . $skuNombre . "%' OR na_productos.nombre LIKE '%" . $skuNombre . "%')";
		}

else if($banderaPorc == 1 && $banderaBusca == 1 && $banderaSKU == 0){
		$familia = $_POST['familia'];
		$tipo = $_POST['tipo'];
		$modelo = $_POST['modelo'];
		$marca = $_POST['marca'];
		$porcentaje = $_POST['porcentajeDesc'];
		$proveedor = $_POST['proveedor'];
		$marcaProv = $_POST['marcaProv'];
		
		if($familia != "null" && $familia != "")
		$where .= " AND na_familias_productos.id_familia_producto IN ($familia)";
		if($tipo != "null" && $tipo != "")
				$where .= " AND na_tipos_productos.id_tipo_producto IN ($tipo)";
		if($modelo != "null" && $modelo != "")
				$where .= " AND na_modelos_productos.id_modelo_producto IN ($modelo)";
		if($marca != "null" && $marca != "")
				$where .= " AND na_marcas_productos.id_marca_producto IN ($marca)";
		if($proveedor != "null" && $proveedor != "")
				$where .= " AND na_productos_proveedor_detalle.id_proveedor IN ($proveedor)";
		if($marcaProv != "null" && $marcaProv != "")
				$where .= " AND na_marcas_productos.id_marca_producto IN ($marcaProv)";
		}
		
else if($banderaPorc == 1 && $banderaBusca == 0 && $banderaSKU == 0){
		$porcentaje = $_POST['porcentajeDesc'];
		}
	
if($banderaProd > 0){
		$productos = json_decode($_POST['productos']);
		foreach($productos as $registros){
				$where .= " AND na_productos.id_producto <> " . $registros -> producto;
				}
		}

$strTrans="AUTOCOMMIT=0";
mysql_query($strTrans);
mysql_query("BEGIN");
try {
		
		if($banderaPorc == 1 && $banderaSKU == 1 && $banderaBusca == 0){
		$sql = "SELECT id_producto AS id_producto, precio_lista AS precio_actual, ((precio_lista * " . $porcentaje . ")/100) AS porcentaje 
						FROM na_productos
						WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0" . $where;
				$datos = new consultarTabla($sql);
				$result = $datos -> obtenerRegistros();
				foreach($result as $registros){
						$precio_final = $registros -> precio_actual + $registros -> porcentaje;
						$consultaProducto = "SELECT id_producto FROM na_listas_detalle_productos WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> id_producto;
						$datosProducto = new consultarTabla($consultaProducto);
						$contadorPr = $datosProducto -> cuentaRegistros();
						
						if($contadorPr > 0)
								$sqlProd = "UPDATE na_listas_detalle_productos SET porcentaje = " . $porcentaje . ", precio_final = " . $precio_final . " 
											WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> id_producto;
						else
								$sqlProd = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final) 
											VALUES (" . $lista . ", " . $registros -> id_producto . ", " . $porcentaje . ", " . $precio_final . ")";
						mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
						
						$bitacora['id_usuario'] = $_SESSION["USR"]->userid;
						$bitacora['fecha_hora'] = date('Y-m-d H:i:s');
						$bitacora['id_producto'] = $registros -> id_producto;
						$bitacora['id_lista_precios'] = $lista;
						$bitacora['porcentaje'] = $porcentaje;
						$bitacora['precio'] = $precio_final;
						accionesMysql($bitacora, 'na_bitacoras_listas', 'Inserta');
						}
		}
		
		else if($banderaPorc == 1 && $banderaBusca == 1 && $banderaSKU == 0){
		$sql = "SELECT na_productos.id_producto AS id_producto, precio_lista AS precio_actual, ((precio_lista * " . $porcentaje . ")/100) AS porcentaje 
				FROM na_productos
				LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
				LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
				LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
				LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
				LEFT JOIN na_productos_proveedor_detalle ON na_productos.id_producto = na_productos_proveedor_detalle.id_producto
				WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0" . $where . " GROUP BY na_productos.id_producto";
				$datos = new consultarTabla($sql);
				$result = $datos -> obtenerRegistros();
				foreach($result as $registros){
						$precio_final = $registros -> precio_actual + $registros -> porcentaje;
						$consultaProducto = "SELECT id_producto FROM na_listas_detalle_productos WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> id_producto;
						$datosProducto = new consultarTabla($consultaProducto);
						$contadorPr = $datosProducto -> cuentaRegistros();
						
						if($contadorPr > 0)
								$sqlProd = "UPDATE na_listas_detalle_productos SET porcentaje = " . $porcentaje . ", precio_final = " . $precio_final . " 
											WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> id_producto;
						else
								$sqlProd = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final) 
											VALUES (" . $lista . ", " . $registros -> id_producto . ", " . $porcentaje . ", " . $precio_final . ")";
						mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
						
						$bitacora['id_usuario'] = $_SESSION["USR"]->userid;
						$bitacora['fecha_hora'] = date('Y-m-d H:i:s');
						$bitacora['id_producto'] = $registros -> id_producto;
						$bitacora['id_lista_precios'] = $lista;
						$bitacora['porcentaje'] = $porcentaje;
						$bitacora['precio'] = $precio_final;
						accionesMysql($bitacora, 'na_bitacoras_listas', 'Inserta');
						}
		}
		else if($banderaPorc == 1 && $banderaBusca == 0 && $banderaSKU == 0){
				$sql = "SELECT id_producto AS id_producto, precio_lista AS precio_actual, ((precio_lista * " . $porcentaje . ")/100) AS porcentaje 
						FROM na_productos
						LEFT JOIN na_listas_detalle_productos USING(id_producto)
						WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0 AND id_lista_precios = " . $lista;
				$datos = new consultarTabla($sql);
				$result = $datos -> obtenerRegistros();
				foreach($result as $registros){
						$precio_final = $registros -> precio_actual + $registros -> porcentaje;
						$consultaProducto = "SELECT id_producto FROM na_listas_detalle_productos WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> id_producto;
						$datosProducto = new consultarTabla($consultaProducto);
						$contadorPr = $datosProducto -> cuentaRegistros();
						
						if($contadorPr > 0)
								$sqlProd = "UPDATE na_listas_detalle_productos SET porcentaje = " . $porcentaje . ", precio_final = " . $precio_final . " 
											WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> id_producto;
						else
								$sqlProd = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final) 
											VALUES (" . $lista . ", " . $registros -> id_producto . ", " . $porcentaje . ", " . $precio_final . ")";
						mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
						
						$bitacora['id_usuario'] = $_SESSION["USR"]->userid;
						$bitacora['fecha_hora'] = date('Y-m-d H:i:s');
						$bitacora['id_producto'] = $registros -> id_producto;
						$bitacora['id_lista_precios'] = $lista;
						$bitacora['porcentaje'] = $porcentaje;
						$bitacora['precio'] = $precio_final;
						accionesMysql($bitacora, 'na_bitacoras_listas', 'Inserta');
						}
				}
		
		if($banderaProd > 0){
				foreach($productos as $registros){
						$consultaProducto = "SELECT id_producto FROM na_listas_detalle_productos WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> producto;
						$datosProducto = new consultarTabla($consultaProducto);
						$contadorPr = $datosProducto -> cuentaRegistros();
						
						$bitacora['id_usuario'] = $_SESSION["USR"]->userid;
						$bitacora['fecha_hora'] = date('Y-m-d H:i:s');
						$bitacora['id_producto'] = $registros -> producto;
						$bitacora['id_lista_precios'] = $lista;
						$bitacora['precio'] = $registros -> precio_final;
						accionesMysql($bitacora, 'na_bitacoras_listas', 'Inserta');
						
						if($contadorPr > 0)
								$sqlProd = "UPDATE na_listas_detalle_productos SET porcentaje = " . $registros -> porcentajeInd . ", precio_final = " . $registros -> precio_final . " 
											WHERE id_lista_precios = " . $lista . " AND id_producto = " . $registros -> producto;
						else
								$sqlProd = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final) 
											VALUES (" . $lista . ", " . $registros -> producto . ", " . $registros -> porcentajeInd . ", " . $registros -> precio_final . ")";
						mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
						}
				}

		
				
		mysql_query("COMMIT");
		echo "Actualizacion realizada correctamente";
		}
catch (Exception $e){
		mysql_query("ROLLBACK");
    	echo 'Excepcion capturada: ',  $e -> getMessage(), "\n";
		}

?>