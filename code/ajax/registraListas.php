<?php

include("../../conect.php");
include("../../consultaBase.php");
include("../../code/general/funciones.php");

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


$sql = "INSERT INTO ad_lista_precios(nombre, fecha_inicio, hora_inicio, fecha_termino, hora_termino, requiere_pago, activo) VALUES('" . utf8_decode($nombre) . "', '$inicio', $hora_ini,'$fin', $hora_fin, $requiere, 1)";
mysql_query($sql) or die ("Error en la consulta: <br>$sql. ".mysql_error());

$idInsertado = mysql_insert_id();

for($i=0; $i<$contadorPago; $i++){
		$sqlPagos = "INSERT INTO na_listas_detalle_pagos(id_lista_precios, id_forma_pago) VALUES ($idInsertado, $insertaPago[$i])";
		mysql_query($sqlPagos) or die ("Error en la consulta: <br>$sqlPagos. ".mysql_error());
		}
		
for($j=0; $j<$contadorSuc; $j++){
		$sqlSuc = "INSERT INTO na_listas_detalle_sucursales(id_lista_precios, id_sucursal) VALUES ($idInsertado, $insertaSuc[$j])";
		mysql_query($sqlSuc) or die ("Error en la consulta: <br>$sqlSuc. ".mysql_error());
		}
if(!empty($productos)){
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
				}*/
		if($partes[1] != "" && $partes[2] != ""){
			$sqlProd = "INSERT INTO na_listas_detalle_productos(id_lista_precios, id_producto, porcentaje, precio_final) VALUES ($idInsertado, $partes[0], $partes[1], $partes[2])";
			mysql_query($sqlProd) or die ("Error en la consulta: <br>$sqlProd. ".mysql_error());
			
			/*if($partes[1] == 0){
					$sqlDel = "DELETE FROM na_listas_detalle_productos WHERE id_lista_precios = $idInsertado AND id_producto = $partes[0]";
					mysql_query($sqlDel) or die ("Error en la consulta: <br>$sqlDel. ".mysql_error());
					}*/
				}
		}
}
echo "Datos guardados correctamente|" . $idInsertado;




?>