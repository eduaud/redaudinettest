<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

$id = $_POST["id"];
$nombre = $_POST["nombre"];
$inicio = convierteFecha($_POST["inicio"]);
$fin = convierteFecha($_POST["fin"]);
$requiere = $_POST["requiere"];
$hora_ini = $_POST["hora_ini"];
$hora_fin = $_POST["hora_fin"];
$pagos = $_POST["pagos"];
$sucursales = $_POST["sucursales"];
$productos = $_POST["detallesProductos"];


$insertaPago = explode(",", $pagos);
$contadorPago = count($insertaPago);

$insertaSuc = explode(",", $sucursales);
$contadorSuc = count($insertaSuc);

$insertaProd = explode(",", $productos);
$contadorProd = count($insertaProd);


$sql = "UPDATE ad_lista_precios SET nombre = '" . utf8_decode($nombre) . "', fecha_inicio = '$inicio', hora_inicio = $hora_ini, fecha_termino = '$fin', hora_termino = $hora_fin, requiere_pago = $requiere WHERE id_lista_precios = $id";

mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

$sqlBorrar = "DELETE FROM na_listas_detalle_pagos WHERE id_lista_precios = " . $id;
mysql_query($sqlBorrar) or die ("Error en la consulta: <br>$sqlBorrar. ".mysql_error());

for($i=0; $i<$contadorPago; $i++){
		$sqlPagos = "INSERT INTO na_listas_detalle_pagos(id_lista_precios, id_forma_pago) VALUES ($id, $insertaPago[$i])";
		mysql_query($sqlPagos) or die ("Error en la consulta: <br>$sqlPagos. ".mysql_error());
		}
		
		
$sqlBorrar = "DELETE FROM na_listas_detalle_sucursales WHERE id_lista_precios = " . $id;
mysql_query($sqlBorrar) or die ("Error en la consulta: <br>$sqlBorrar. ".mysql_error());	
for($j=0; $j<$contadorSuc; $j++){
		$sqlSuc = "INSERT INTO na_listas_detalle_sucursales(id_lista_precios, id_sucursal) VALUES ($id, $insertaSuc[$j])";
		mysql_query($sqlSuc) or die ("Error en la consulta: <br>$sqlSuc. ".mysql_error());
		}
		
/*if($productos != ""){
	for($k=0; $k<$contadorProd; $k++){
		$partes = explode("|", $insertaProd[$k]);
		$contadorPartes = count($partes);
		$partes[2] = str_replace("$", "", $partes[2]);
		
		/*if($partes[1] == ""){
				$partes[1] = 0;
				}
		if($partes[0] == ""){
				$partes[0] = 0;
				}
		if($partes[2] == ""){
				$partes[2] = 0;
				}*
		if($partes[1] != "" && $partes[2] != ""){
				$consultaProducto = "SELECT id_producto FROM na_listas_detalle_productos WHERE id_lista_precios = $id AND id_producto = $partes[0]";
				$datosProducto = new consultarTabla($consultaProducto);
				$contadorPr = $datosProducto -> cuentaRegistros();
				
				if($contadorPr > 0)
						$sqlProd = "UPDATE na_listas_detalle_productos SET porcentaje = $partes[1], precio_final = $partes[2] WHERE id_lista_precios = $id AND id_producto = $partes[0]";
				else
						$sqlProd = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final) VALUES ($id, $partes[0], $partes[1], $partes[2])";
				
				mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
				
				/*if($partes[1] == 0){
						$sqlDel = "DELETE FROM na_listas_detalle_productos WHERE id_lista_precios = $id AND id_producto = $partes[0]";
						mysql_query($sqlDel) or die ("Error en la consulta: <br>$sqlDel. ".mysql_error());
						}
					}
		}
	}*/


echo "Datos actualizados correctamente";




?>