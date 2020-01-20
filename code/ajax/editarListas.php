<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST['id'];

$sql = "SELECT nombre, fecha_inicio, fecha_termino, hora_inicio, hora_termino, requiere_pago FROM ad_lista_precios WHERE id_lista_precios = $id";

$datos = new consultarTabla($sql);
$result = $datos -> obtenerLineaRegistro();

$editaLista["nombreLista"] = $result['nombre'];
$editaLista["finicio"] = $result['fecha_inicio'];
$editaLista["ftermino"] = $result['fecha_termino'];
$editaLista["hinicio"] = $result['hora_inicio'];
$editaLista["htermino"] = $result['hora_termino'];
$editaLista["requiere"] = $result['requiere_pago'];
		
$sqlPagos = "SELECT id_forma_pago FROM na_listas_detalle_pagos WHERE id_lista_precios = " . $id;

$datosPagos = new consultarTabla($sqlPagos);
$resultPagos = $datosPagos -> obtenerRegistros();

foreach($resultPagos as $campo){
		$pagos[] = $campo -> id_forma_pago;
		}
$editaLista["pagos"] = $pagos;

$sqlSuc = "SELECT id_sucursal FROM na_listas_detalle_sucursales WHERE id_lista_precios = " . $id;

$datosSuc = new consultarTabla($sqlSuc);
$resultSuc = $datosSuc -> obtenerRegistros();

foreach($resultSuc as $campo){
		$sucursal[] = $campo -> id_sucursal;
		}	
$editaLista["sucursales"] = $sucursal;		


/*if($id == 1)
		$sqlProd = "SELECT na_productos.id_producto AS producto, '0' AS porcentaje, precio_lista AS precio_final FROM na_productos ORDER by producto";
else
		$sqlProd = "SELECT na_listas_detalle_productos.id_producto AS idProducto, porcentaje AS porcentaje, precio_final AS precio_final 
					FROM na_listas_detalle_productos
					LEFT JOIN na_productos ON na_listas_detalle_productos.id_producto = na_productos.id_producto
					LEFT JOIN na_familias_productos ON na_productos.id_familia_producto = na_familias_productos.id_familia_producto
					LEFT JOIN na_tipos_productos ON na_productos.id_tipo_producto = na_tipos_productos.id_tipo_producto
					LEFT JOIN na_modelos_productos ON na_productos.id_modelo_producto = na_modelos_productos.id_modelo_producto
					LEFT JOIN na_marcas_productos ON na_productos.id_marca_producto = na_marcas_productos.id_marca_producto
					WHERE id_lista_precios = " . $id . " 
					ORDER BY na_productos.nombre, na_familias_productos.nombre, na_tipos_productos.nombre, na_modelos_productos.nombre, na_productos.sku";*/
		$sqlProd = "SELECT na_listas_detalle_productos.id_producto AS idProducto
					FROM na_listas_detalle_productos
					WHERE id_lista_precios = " . $id;
					
$datosProd = new consultarTabla($sqlProd);
$contadorPr = $datosProd -> cuentaRegistros();
$editaLista["contador"] = $contadorPr;

/*if($contadorPr > 0){
		$resultProd = $datosProd -> obtenerRegistros();
		$editaLista["datosProd"] = $resultProd;
		foreach($resultProd as $campo){
				$idProductos[] = $campo -> idProducto;
				}	
		$editaLista["idProd"] = $idProductos;	
		}*/
		
echo json_encode($editaLista);
?>