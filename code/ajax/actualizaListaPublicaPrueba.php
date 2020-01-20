<?php	

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$banderaPorc = $_POST['banderaPorc'];
$banderaProd = $_POST['banderaProd'];

$where = "";

if($banderaPorc == 1){
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
		if($banderaPorc == 1){
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
						$sqlProd = "UPDATE na_productos SET precio_lista = " . $precio_final . " WHERE id_producto = " . $registros -> id_producto;
						mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
						
						$sqlProd = "UPDATE na_productos SET precio_lista = " . $precio_final . " WHERE id_producto = " . $registros -> id_producto;
						mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
						
						$bitacora['id_usuario'] = $_SESSION["USR"]->userid;
						$bitacora['fecha_hora'] = date('Y-m-d H:i:s');
						$bitacora['id_producto'] = $registros -> id_producto;
						$bitacora['id_lista_precios'] = 1;
						$bitacora['porcentaje'] = $porcentaje;
						$bitacora['precio'] = $precio_final;
						accionesMysql($bitacora, 'na_bitacoras_listas', 'Inserta');
						
						$sqlLista = "SELECT id_producto, precio_final, id_lista_precios FROM na_listas_detalle_productos WHERE id_producto = " . $registros -> id_producto;
						$datosL = new consultarTabla($sqlLista);
						$contador = $datosL -> cuentaRegistros();
						if($contador > 0){
								$prodLista = $datosL -> obtenerRegistros();
								foreach($prodLista as $listas){
										$sub = ($listas -> precio_final * 100) / $precio_final;
										$porcen = $sub -100;
										$sqlAL = "UPDATE na_listas_detalle_productos SET porcentaje =  " . $porcen . "
										WHERE id_producto = " . $registros -> id_producto . " AND id_lista_precios = " . $listas -> id_lista_precios;
										mysql_query($sqlAL) or die ("Error en la consulta: <br>$sqlAL. ".mysql_error());
										}
								}
						}
				}
		if($banderaProd > 0){
				foreach($productos as $registros){
						$sqlProdInd = "UPDATE na_productos SET precio_lista = " . $registros -> precio_final . " WHERE id_producto = " . $registros -> producto;
						mysql_query($sqlProdInd) or die ("Error en la consulta: <br>$sqlProdInd. ".mysql_error());
						
						$bitacora['id_usuario'] = $_SESSION["USR"]->userid;
						$bitacora['fecha_hora'] = date('Y-m-d H:i:s');
						$bitacora['id_producto'] = $registros -> producto;
						$bitacora['id_lista_precios'] = 1;
						$bitacora['precio'] = $registros -> precio_final;
						accionesMysql($bitacora, 'na_bitacoras_listas', 'Inserta');
						
						$sqlLista = "SELECT id_producto, precio_final, id_lista_precios FROM na_listas_detalle_productos WHERE id_producto = " . $registros -> producto;
						$datosL = new consultarTabla($sqlLista);
						$contador = $datosL -> cuentaRegistros();
						if($contador > 0){
								$prodLista = $datosL -> obtenerRegistros();
								foreach($prodLista as $listas){
										$sub = ($listas -> precio_final * 100) / $registros -> precio_final;
										$porcen = $sub -100;
										$sqlAL = "UPDATE na_listas_detalle_productos SET porcentaje =  " . $porcen . "
										WHERE id_producto = " . $registros -> producto . " AND id_lista_precios = " . $listas -> id_lista_precios;
										mysql_query($sqlAL) or die ("Error en la consulta: <br>$sqlAL. ".mysql_error());
										}
								}
								
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