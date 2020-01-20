<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

mysql_query("SET NAMES 'utf8'");

$banderaPorc = $_POST['banderaPorc'];
$banderaProd = $_POST['banderaProd'];

$nombre = $_POST["nombre"];
$inicio = convierteFecha($_POST["inicio"]);
$fin = convierteFecha($_POST["fin"]);
$requiere = $_POST["requiere"];
$hora_ini = $_POST["hora_ini"];
$hora_fin = $_POST["hora_fin"];
$pagos = $_POST["pagos"];
$sucursales = $_POST["sucursales"];

$insertaPago = explode(",", $pagos);
$contadorPago = count($insertaPago);

$insertaSuc = explode(",", $sucursales);
$contadorSuc = count($insertaSuc);

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
		
		if($banderaProd > 0){
				$productos = json_decode($_POST['productos']);
				foreach($productos as $registros){
						$where .= " AND na_productos.id_producto <> " . $registros -> producto;
						}
				}
		}
		
		
		
$strTrans="AUTOCOMMIT=0";
mysql_query($strTrans);
mysql_query("BEGIN");
try {
	
		$sql = "INSERT INTO ad_lista_precios(nombre, fecha_inicio, hora_inicio, fecha_termino, hora_termino, requiere_pago, activo) VALUES('" . utf8_decode($nombre) . "', '$inicio', $hora_ini,'$fin', $hora_fin, $requiere, 1)";
		mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

		$idInsertado = mysql_insert_id();

		for($i=0; $i<$contadorPago; $i++){
				$sqlPagos = "INSERT INTO na_listas_detalle_pagos(id_lista_precios, id_forma_pago) VALUES (" . $idInsertado . ", " . $insertaPago[$i] . ")";
				mysql_query($sqlPagos) or die ("Error en la consulta: <br>$sqlPagos. ".mysql_error());
				}
				
		for($j=0; $j<$contadorSuc; $j++){
				$sqlSuc = "INSERT INTO na_listas_detalle_sucursales(id_lista_precios, id_sucursal) VALUES (" . $idInsertado . ", " . $insertaSuc[$j] . ")";
				mysql_query($sqlSuc) or die ("Error en la consulta: <br>$sqlSuc. ".mysql_error());
				}
	
		if($banderaPorc == 1){
				$sqlInserta = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final)
								SELECT " . $idInsertado . " AS lista_precio, na_productos.id_producto AS id_producto, " . $porcentaje . " AS porcentaje, 
								(precio_lista + ((precio_lista * " . $porcentaje . ")/100)) AS precio_final
								FROM na_productos
								LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
								LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
								LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
								LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
								LEFT JOIN na_productos_proveedor_detalle ON na_productos.id_producto = na_productos_proveedor_detalle.id_producto
								WHERE na_productos.activo = 1 AND na_productos.id_producto <> 0" . $where . " GROUP BY na_productos.id_producto";
				mysql_query($sqlInserta) or die ("Error en la consulta: <br>$sqlInserta. ".mysql_error());
				}
		if($banderaProd > 0){
				$productos = json_decode($_POST['productos']);
				foreach($productos as $registros){
						$prodGeneral['id_lista_precios'] = $idInsertado;
						$prodGeneral['id_producto'] = $registros -> producto;
						$prodGeneral['porcentaje'] = $registros -> porcentajeInd;
						$prodGeneral['precio_final'] = $registros -> precio_final;
				
						accionesMysql($prodGeneral, 'na_listas_detalle_productos', 'Inserta');
						}
				}
				
		mysql_query("COMMIT");
		echo "Datos insertados correctamente|" . $idInsertado;
		}
catch (Exception $e){
		mysql_query("ROLLBACK");
    	echo 'Excepcion capturada: ',  $e -> getMessage(), "\n";
		}		
		
		
		
		

?>